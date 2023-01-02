<?php

namespace App\Http\Controllers;

use App\Models\Role as Permission;
use App\Models\Sms as Sms;
use App\Models\CustomerGroup;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SmsController extends Controller
{
    /**
     * @param
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Sms $sms, Permission $permission)
    {
        parent::__construct();
        $this->sms = $sms;
        $this->permission = $permission;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title = trans('admin/sms/general.page.send.title'); // "Admin | SMS | Create";
        $page_description = trans('admin/sms/general.page.send.description'); // "Creating a new course";

        $perms = $this->permission->all();

        return view('admin.sms.send', compact('perms', 'page_title', 'page_description'));
    }

    /**
     * Send SMS - Outbound.
     *
     * @return Response
     */
    public function postSend()
    {
        $recipients = str_replace(' ', ',', trim(Request::get('recipient')));
        $message = trim(Request::get('message')).' - '.env('APP_COMPANY');
        //dd($text);
        $ch = curl_init();
        $curlUrl = 'https://smsprima.com/api/api/index?username=luckyt&password=12345678&sender=LuckyTravel&destination='.$recipients.'&type=1&message='.urlencode($message).''; // api url as provided in the document.
        curl_setopt($ch, CURLOPT_URL, $curlUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        //dd($response);
        if (! curl_errno($ch)) {
            Flash::success(trans('admin/sms/general.status.sms-sent'));
        } else {
            Flash::warning(trans('admin/sms/general.status.no-msg'));
        }
    }

    public function getBulkSMSForm()
    {
        $page_title = 'Send the Bulk SMS';
        $page_description = 'Send the Bulk SMS to all the users';

        $courses = \App\Models\Product::select('id', 'name')->where('enabled', '1')->get();
        $lead_status = \App\Models\Leadstatus::select('id', 'name')->where('enabled', '1')->get();

        return view('admin.sms.bulkSMSForm', compact('page_title', 'page_description', 'courses', 'lead_status'));
    }

    public function postBulkSMS(Request $request)
    {
        $this->validate($request, ['message' => 'required']);
        $error = 0;
        $start = \Request::get('start_date');
        $end = \Request::get('end_date');

        $leads = \App\Models\Lead::select('mob_phone')
                            ->where('mob_phone', '!=', '')
                            ->where('enabled', '1');

        if ($start != '' && $end != '') {
            $leads->whereBetween('created_at', [$start, $end]);
        }
        if ($request['course_id'] != 0) {
            $leads->where('product_id', $request['course_id']);
        }
        if ($request['status_id'] != 0) {
            $leads->where('status_id', $request['status_id']);
        }

        $allLeads = $leads->pluck('mob_phone');
        $recipients = implode(',', $allLeads->toArray());

        $message = trim(\Request::get('message')).' - '.env('APP_COMPANY');
        //dd($text);
        $ch = curl_init();
        $curlUrl = 'https://smsprima.com/api/api/index?username=luckyt&password=12345678&sender=LuckyTravel&destination='.$recipients.'&type=1&message='.urlencode($message).''; // api url as provided in the document.
        curl_setopt($ch, CURLOPT_URL, $curlUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        //dd($response);
        if (! curl_errno($ch)) {
            Flash::success(trans('admin/sms/general.status.sms-sent'));
        } else {
            Flash::warning('Error in sending SMS. Please try again.');
        }

        return Redirect::back();
    }

    public function getBulkSMSContact()
    {
        $page_title = 'Bulk SMS';
        $page_description = 'Send the Bulk SMS to all the contacts';
        $groups = \App\Models\CustomerGroup::where('enabled', '1')->get();

        $valid_addresses = \App\Models\Contact::where('email_1', 'like', '%_@__%.__%')->count();
        $total_leads = \App\Models\Contact::count();
        // dd($groups);
        // $clients = \App\Models\Client::select('name', 'id')->with('groups')->where('enabled', '1')->get();
        // $clients = \App\Models\Client::select('name', 'id')->with('groups')->where('enabled', '1')->get();
        // $clients = \App\Models\Client::where('enabled', '1')->first();

        // dd($clients);


        return view('admin.sms.bulkSMSContact', compact('page_title', 'page_description','valid_addresses','total_leads','groups'));
    }

    public function postBulkSMSContact(Request $request)
    {
        $this->validate($request, ['message'  => 'required',
        ]);

        //dd($request->message);

        $error = 0;
        $start = \Request::get('start_date');
        $end = \Request::get('end_date');
        $group_id = \Request::get('group_id');
        $client_id = \Request::get('client_id');
        $contact_id = \Request::get('contact_id');


          // if ($start != '' && $end != '') {
        //     if ($request['client_id'] != '0') {
        //         $clients = \App\Models\Contact::whereBetween('created_at', [$start, $end])->where('phone', '!=', '')->where('enabled', '1')->where('client_id', $request['client_id'])->pluck('phone');
        //     } else {
        //         $clients = \App\Models\Contact::whereBetween('created_at', [$start, $end])->where('phone', '!=', '')->where('enabled', '1')->pluck('phone');
        //     }
        // } else {
        //     if ($request['client_id'] != '0') {
        //         $clients = \App\Models\Contact::where('phone', '!=', '')->where('enabled', '1')->where('client_id', $request['client_id'])->pluck('phone');
        //     } else {
        //         $clients = \App\Models\Contact::where('phone', '!=', '')->where('enabled', '1')->pluck('phone');
        //     }
        // }



        if ($start != '' && $end != '') {
            if ($request['contact_id'] != '') {
                $contacts = \App\Models\Contact::whereBetween('created_at', [$start, $end])->where('phone', '!=', '')->where('enabled', '1')->where('id', $request['contact_id'])->pluck('phone');
            } elseif($request['client_id'] != '') {
                $contacts = \App\Models\Contact::whereBetween('created_at', [$start, $end])->where('phone', '!=', '')->where('enabled', '1')->where('client_id', $request['client_id'])->pluck('phone');
            }
            elseif($request['group_id'] != '' && $request['client_id'] == '' && $request['contact_id'] == '')
            {
                $client_ids = \App\Models\Client::where('customer_group',$request['group_id'])->pluck(id);
                $contacts = \App\Models\Contact::whereBetween('created_at', [$start, $end])->whereIn('client_id',$client_ids)->where('phone', '!=', '')->where('enabled', '1')->where('enabled', '1')->pluck('phone');
            }else{
                $contacts = \App\Models\Contact::whereBetween('created_at', [$start, $end])->where('phone', '!=', '')->where('enabled', '1')->pluck('phone');
            }
        } else {
            if ($request['contact_id'] != '') {
                $contacts = \App\Models\Contact::where('phone', '!=', '')->where('enabled', '1')->where('id', $request['contact_id'])->pluck('phone');
            } elseif($request['client_id'] != '') {
                $contacts = \App\Models\Contact::where('phone', '!=', '')->where('enabled', '1')->where('client_id', $request['client_id'])->pluck('phone');
            } elseif($request['group_id'] != '' && $request['client_id'] == '' && $request['contact_id'] == '')
            {
                $client_ids = \App\Models\Client::where('customer_group',$request['group_id'])->pluck(id);
                $contacts = \App\Models\Contact::whereIn('client_id',$client_ids)->where('phone', '!=', '')->where('enabled', '1')->where('enabled', '1')->pluck('phone');
            }else{
                $contacts = \App\Models\Contact::where('phone', '!=', '')->where('enabled', '1')->pluck('phone');
            }
        }


        foreach ($contacts as $key => $value) {
            $message = trim(\Request::get('message')).' - '.env('APP_COMPANY');

            dispatch(new \App\Jobs\SendBulkSmsToContact($value,$message));

              Flash::success('Bulk sms send successfully in the background...');

              return Redirect::back();

            // $sendsms = \App\Jobs\SendBulkSmsToContact::($value,$message);
        }
        // $recipients = implode(',', $clients->toArray());
        // //dd($recipients);
        // $message = trim(\Request::get('message')).' - '.env('APP_COMPANY');
        // //dd($text);
        // $ch = curl_init();
        // $curlUrl = 'https://smsprima.com/api/api/index?username=luckyt&password=12345678&sender=LuckyTravel&destination='.$recipients.'&type=1&message='.urlencode($message).''; // api url as provided in the document.
        // curl_setopt($ch, CURLOPT_URL, $curlUrl);

        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $response = curl_exec($ch);

        //dd($response);
        // dd($response);
        // if (! curl_errno($ch)) {
        //     Flash::success(trans('admin/sms/general.status.sms-sent'));
        // } else {
        //     Flash::warning('Message Cannot Be at the Moment');
        // }

        // return Redirect::back();
    }

    public function get_group_select_clients(Request $request)
    {
        $clients = \App\Models\Client::where('customer_group',$request->ID)->get();
        $client_ids = \App\Models\Client::where('customer_group',$request->ID)->pluck(id);
        $contacts_count = \App\Models\Contact::whereIn('client_id',$client_ids)->where('phone', '!=', '')->where('enabled', '1')->get()->count();
        $emails_count = \App\Models\Contact::whereIn('client_id',$client_ids)->where('email_1', 'like', '%_@__%.__%')->where('enabled', '1')->get()->count();
         return response()->json([
            'contacts_count' => $contacts_count,
            'clients' => $clients,
            'emails_count' => $emails_count
        ], 200);
    }

    public function get_client_select_contacts(Request $request)
    {
        $contacts = \App\Models\Contact::where('client_id',$request->ID)->where('enabled', '1')->get();

        $contacts_count = \App\Models\Contact::where('client_id',$request->ID)->where('phone', '!=', '')->where('enabled', '1')->get()->count();

        $emails_count = \App\Models\Contact::where('client_id',$request->ID)->where('email_1', 'like', '%_@__%.__%')->where('enabled', '1')->get()->count();


        return response()->json([
            'contacts_count' => $contacts_count,
            'contacts' => $contacts,
            'emails_count' => $emails_count
        ], 200);

    }
}