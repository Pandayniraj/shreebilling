<?php

namespace App\Http\Controllers;

use App\Models\Cases as Cases;
use App\Models\Client;
use App\Models\JobApplication;
use App\Models\JobCircular;
use App\Models\Lead as Lead;
use Datatables;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

/**
FOR ONLINE ENQUIRY

 **/
class ApplicationController extends Controller
{
    /**
     * @var Lead
     */
    private $Application;

    public function __construct(Lead $Lead, Cases $cases, JobApplication $jobapplication)
    {
        parent::__construct();
        $this->lead = $Lead;
        $this->cases = $cases;
        $this->jobapplication = $jobapplication;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function form()
    {
        // Courses means Product here
        $key1 = \Request::get('communication_id');

        //dd($key1);
        $key2 = \Request::get('course_id');
        if ($key2 != '') {
            $courses = \App\Models\Product::where('id', $key2)->select('name', 'id')->get();
        } else {
            $courses = \App\Models\Product::where('enabled', '1')->orderBy('name', 'asc')->select('name', 'id')->get();
        }
        if ($key1 != '') {
            $communication_id = $key1;
        // dd($communication_id);
        } else {
            $communication_id = 8;
            //  dd($communication_id);
        }

        return view('application.enquiry', compact('courses', 'communication_id'));
    }

    public function postPreview(Request $request)
    {
        $this->validate($request,
                ['course_id' => 'required',
                        'name' => 'required',
                        'mob_phone' => 'required',
                        'email' => 'email:rfc,dns,spoof'
                ]
        );

        $attributes = $request->all();
        // TODO: This lead_type_id can be dynamic later when this CRM grows like post_type in wordpress
        $attributes['lead_type_id'] = '1';

        $attributes['communication_id'] = '8';	//Since the communication is from Website, 8 = Crmenquiry from databaseenqiury mode from database
        $attributes['status_id'] = '17';	// 17 = Pending status from database
        $attributes['user_id'] = '1';	// 1 = Root, user is setup as online to post this entry
        $attributes['viewed'] = '0';
        

        $lead = $this->lead->create($attributes);
        $request['lead_id'] = $lead->id;

        return view('application.form-preview', $request->all());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postEnquiry(Request $request)
    {
        $this->validate($request,
                ['course_id' => 'required',
                        'name' => 'required',
                        'mob_phone' => 'required',
                        'email' => 'email:rfc,dns,spoof'
                ]
        );

        $attributes = $request->all();
        // TODO: This lead_type_id can be dynamic later when this CRM grows like post_type in wordpress
        $attributes['lead_type_id'] = '1';

        //$attributes['communication_id'] = '8';	//Since the communication is from Website, 8 = Crmenquiry from databaseenqiury mode from database
        $attributes['status_id'] = '28';	// 17 = Pending status from database
        $attributes['user_id'] = env('ONLINE_ENQUIRY_STAFF');	// 1 = Root, user is setup as online to post this entry
        $attributes['viewed'] = '0';
        $attributes['org_id'] = '1';
        $attributes['target_date'] = date('Y-m-d',strtotime('+3 days'));
      
        $lead = $this->lead->create($attributes);

        //send email
        //		 $mail = Mail::send('emails.application-letter', ['lead'=>$lead], function ($message) use ($request) {
        //			$message->subject('Online Enquiry from '.env('APP_NAME'));
        //			$message->from(env('APP_EMAIL'), env('APP_NAME'));
        //			$message->to($request['email'], '');
        //		});

        return redirect('/enquiry_thankyou')->with('lead_id', $lead->id)->with('name', $request['name'])->with('course', \TaskHelper::getCourseName($request['course_id']))->with('mob_phone', $request['mob_phone']);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function thankyou()
    {
        return view('application.thankyou');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function ticket()
    {
        // Courses means Product here
        $courses = \App\Models\Product::where('enabled', '1')->orderBy('name', 'asc')->select('name', 'id')->get();
        $logo = \App\Models\Organization::find($id = 1);

        return view('application.ticket', compact('courses','logo'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postTicket(Request $request)
    {
        $this->validate($request,
                ['from_user' => 'required',
                    'from_email' => 'required',
                    'from_phone' => 'required',
                ]
        );

        $attributes = $request->all();

        $attributes['ticket_number'] = rand(1000000, 9999999);

        $attributes['ticket_status'] = 1;

        $attributes['form_source'] = 'external';

        $ticket = \App\Models\Ticket::create($attributes);

        $files = $request->file('attachment');

        $destinationPath = public_path('/tickets/');

        if (! \File::isDirectory($destinationPath)) {
            \File::makeDirectory($destinationPath, 0777, true, true);
        }

        foreach ($files as $key=>$doc_) {
            if ($doc_ && $doc_->getSize() < 10000000) { //lest than 10MB
                $doc_name = time().''.$doc_->getClientOriginalName();
                $doc_->move($destinationPath, $doc_name);
                $ticket_attachment = ['type'=>'summary', 'attachment'=>$doc_name, 'ticket_id'=>$ticket->id];
                \App\Models\TicketFile::create($ticket_attachment);
            }
        }

        Flash::success('Tickets Successfully created');

        return redirect('/ticket_thankyou')->with('id', $ticket->ticket_number)->with('subject', $ticket->detail_reason);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function ticketThankyou()
    {
        return view('application.ticketThankyou');
    }

    // For load clients by ajax
    public function get_client()
    {
        $term = strtolower(\Request::get('term'));
        $contacts = Client::select('id', 'name')->where('name', 'LIKE', '%'.$term.'%')->where('enabled', '1')->groupBy('name')->take(5)->get();
        $return_array = [];

        foreach ($contacts as $v) {
            if (strpos(strtolower($v->name), $term) !== false) {
                $return_array[] = ['value' => $v->name, 'id' =>$v->id];
            }
        }

        return Response::json($return_array);
    }

    public function listJob()
    {
        $page_title = 'Jobs';
        $page_description = 'All Jobs';

        $circulars = JobCircular::where('status', 'published')->orderBy('created_at', 'desc')->get();

        return view('application.listjob', compact('circulars'));
    }

    public function applyJob($job_circular_id)
    {
        $circular = JobCircular::where('job_circular_id', $job_circular_id)->first();

        return view('application.applyjob', compact('circular'));
    }

    public function jobApplyForm()
    {
        return view('application.apply-form');
    }

    public function postJobApplication(Request $request)
    {
        $this->validate($request,
                [
                        'name' => 'required',
                        'mobile' => 'required',
                        'email' => 'required',
                        'resume' => 'required',
                ]
        );

        $attributes = $request->all();
        $attributes['application_status'] = '0';

        $stamp = time();
        $file = $request->file('resume');
        //dd($file);
        $destinationPath = public_path().'/job_applied/';
        $filename = $file->getClientOriginalName();
        $file->move($destinationPath, $stamp.'_'.$filename);
        $attributes['resume'] = $stamp.'_'.$filename;
        //dd($attributes);
        $jobapplication = $this->jobapplication->create($attributes);

        //send email
//       $mail = Mail::send('emails.application-letter', ['lead'=>$lead], function ($message) use ($request) {
//          $message->subject('Online Enquiry from '.env('APP_NAME'));
//          $message->from(env('APP_EMAIL'), env('APP_NAME'));
//          $message->to($request['email'], '');
//      });

        return redirect('/job_thankyou');
    }

    public function jobThankyou()
    {
        return view('application.job-thankyou');
    }
}
