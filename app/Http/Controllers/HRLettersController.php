<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Hrletter;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class HRLettersController extends Controller
{
    /**
     * @var Client
     */
    private $hrletter;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $hrletter
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Hrletter $hrletter, Permission $permission)
    {
        parent::__construct();
        $this->hrletter = $hrletter;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $hrletter = Hrletter::orderBy('id', 'DESC')->where('org_id', Auth::user()->org_id)->get();
        $page_title = 'HR Letters';
        $page_description = 'Issue letters';

        return view('admin.hrletter.index', compact('hrletter','page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $hrletter = $this->hrletter->find($id);

        $page_title = 'Admin | Hrletter | Show';
        $page_description = 'Hrletter Detail';

        return view('admin.hrletter.show', compact('hrletter', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Hrletter or Contract'; // "Admin | Client | Create";
        $page_description = 'Create'; // "Creating a new client";

        $hrletter = new \App\Models\Hrletter();
        $perms = $this->permission->all();

        $clients = Client::select('id', 'name', 'location')
            ->where('type', 'Customer')->orderBy('id', DESC)->get();
        $contacts = \App\Models\Contact::select('id', 'full_name')
            ->where('enabled', '1')->get();
        $templates = \App\Models\HrLetterTemplate::where('org_id', \Auth::user()->org_id)->pluck('name', 'id');
        $users = \App\User::select('id', 'first_name', 'last_name', 'email')->orderBy('id', DESC)->get();

        //dd($users);

        return view('admin.hrletter.create', compact('hrletter', 'perms', 'page_title', 'page_description', 'clients', 'contacts', 'products', 'users', 'templates'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'subject'      => 'required',
            'type'    => 'required',
            'status'    => 'required',
            'body'    => 'required',
        ]);

        $attributes = $request->all();
        $attributes['org_id'] = Auth::user()->org_id;
        $attributes['user_id'] = Auth::user()->id;

        //dd($attributes);

        $hrletter = $this->hrletter->create($attributes);

        Flash::success('Hrletter created successfully.');

        return redirect('/admin/hrletter');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $hrletter = $this->hrletter->find($id);
        $perms = $this->permission->all();

        $clients = Client::select('id', 'name', 'location')
            ->where('type', 'Customer')->orderBy('id', 'DESC')->get();
        $contacts = \App\Models\Contact::select('id', 'full_name')
            ->where('enabled', '1')->get();

        $leads = \App\Models\Lead::select('id', 'name')
            ->orderBy('id', 'DESC')->take(100)->get();

        $users = \App\User::select('id', 'first_name', 'last_name', 'email')->orderBy('id', 'DESC')->get();

        $page_title = 'Edit Hrletter'; // "Admin | Client | Edit";
        $page_description = 'Edit Hrletter';
        $templates = \App\Models\HrLetterTemplate::where('org_id', \Auth::user()->org_id)->pluck('name', 'id');
        if (!$hrletter->isEditable() && !$hrletter->canChangePermissions()) {
            abort(403);
        }

        return view('admin.hrletter.edit', compact('hrletter', 'perms', 'clients', 'contacts', 'leads', 'page_title', 'page_description', 'clients', 'contacts', 'users','templates'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'subject'      => 'required',
            'type'    => 'required',
            'status'    => 'required',
            'body'    => 'required',
        ]);

        $attributes = $request->all();

        $hrletter = Hrletter::where('id', $id)->first();
        if ($hrletter->isEditable()) {
            $hrletter->update($attributes);
        }

        Flash::success('Hrletter updated successfully.'); // 'Client successfully updated');

        return redirect('/admin/hrletter');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $hrletter = Hrletter::where('id', $id)->first();
        if (!$hrletter->isdeletable()) {
            abort(403);
        }
        $hrletter->delete();

        Flash::success('Hrletter successfully deleted.'); // 'Client successfully deleted');

        return redirect('/admin/hrletter');
    }

    /**
     * Delete Confirm.
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalDelete($id)
    {
        $error = null;

        $hrletter = $this->hrletter->find($id);

        if (!$hrletter->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Hrletter';

        $hrletter = $this->hrletter->find($id);
        $modal_route = route('admin.hrletter.delete', ['proposalId' => $hrletter->id]);

        $modal_body = 'Are you sure to delete Hrletter with subject = ' . $hrletter->subject . ', id = ' . $hrletter->id . '.';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $hrletter = $this->hrletter->find($id);

        $hrletter->enabled = true;
        $hrletter->save();

        Flash::success('Hrletter is enabled');

        return redirect('/admin/hrletter');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $hrletter = $this->hrletter->find($id);

        $hrletter->enabled = false;
        $hrletter->save();

        Flash::success('Hrletter disabled successfully.');

        return redirect('/admin/hrletter');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkproposal = $request->input('chkClient');

        if (isset($chkproposal)) {
            foreach ($chkproposal as $proposal_id) {
                $hrletter = $this->hrletter->find($proposal_id);
                $hrletter->enabled = true;
                $hrletter->save();
            }
            Flash::success('Hrletter is enabled successfully.');
        } else {
            Flash::warning('Sorry! No Letters selected.');
        }

        return redirect('/admin/hrletter');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkproposal = $request->input('chkClient');

        if (isset($chkproposal)) {
            foreach ($chkproposal as $proposal_id) {
                $hrletter = $this->hrletter->find($proposal_id);
                $hrletter->enabled = false;
                $hrletter->save();
            }
            Flash::success('Hrletter is disabled successfully.');
        } else {
            Flash::warning('Sorry! No letters selected.');
        }

        return redirect('/admin/hrletter');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $hrletter = $this->hrletter->pushCriteria(new proposalWhereDisplayNameLike($query))->all();

        foreach ($hrletter as $hrletter) {
            $id = $hrletter->id;
            $name = $hrletter->name;
            $email = $hrletter->email;

            $entry_arr = ['id' => $id, 'text' => "$name ($email)"];
            $return_arr[] = $entry_arr;
        }

        return $return_arr;
    }

    public function printLetter($id)
    {
        $hrletter = Hrletter::where('id', $id)->first();
        //dd($orderDetails);
        return view('admin.hrletter.print', compact('hrletter'));
    }

    public function copyDoc($id)
    {
        $hrletter = Hrletter::where('id', $id)->first();

        $new_item = $hrletter->replicate(); //copy attributes
        $new_item->push();

        Flash::success('Letter successfully duplicated.');

        return redirect('/admin/hrletter');
    }

    public function generatePDF($id)
    {
        $hrletter = Hrletter::where('id', $id)->first();
        //dd($hrletter);
        $pdf = \PDF::loadView('admin.hrletter.generateProposalPDF', compact('hrletter'));
        $file = 'HR_' . $id . '.pdf';
        if (File::exists('hrletter/' . $file)) {
            File::Delete('hrletter/' . $file);
        }

        return $pdf->download($file);
    }

    public function getModalMail($proposalId)
    {
        $error = null;

        $hrletter = Hrletter::where('id', $proposalId)->first();

        $email = 'test@test.com';

        if (!$email) {
            abort(403);
        }

        // dd($email);

        $modal_title = trans('admin/mails/dialog.send-mail.title', ['email' => $email]);
        $modal_route = route('admin.proposalMail.send-mail-modal', ['id' => $proposalId]);
        $to_email = $email;

        return view('admin.hrletter.modal_mail', compact('hrletter', 'modal_title', 'to_email', 'modal_route'));
    }

    public function postModalMail(Request $request, $proposalId)
    {
        $this->validate(
            $request,
            [
                'mail_from' => 'required',
                'mail_to'  => 'required',
                'subject'  => 'required',
                'message'  => 'required',
            ]
        );

        $attributes = [
            'id'   =>  $proposalId,
            'mail_from' =>  $request['mail_from'],
            'mail_to'   =>  $request['mail_to'],
            'subject'   =>  $request['subject'],
            'message'   =>  $request['message'],
        ];

        $hrletter = Hrletter::where('id', $proposalId)->first();

        //$pdf = App::make('dompdf.wrapper');
        $pdf = \PDF::loadHTML($request['message']);
        $file = 'Proposal_' . $proposalId . '.pdf';
        if (File::exists('hrletter/' . $file)) {
            File::Delete('hrletter/' . $file);
        }
        $pdf->save('hrletter/' . $file);

        //send email
        $mail = Mail::send('admin.hrletter.email-send', [], function ($message) use ($attributes, $hrletter, $request) {
            $message->subject($attributes['subject']);
            $message->from($attributes['mail_from'], env('APP_COMPANY'));
            $message->to($attributes['mail_to'], '');
            $message->attach('hrletter/' . $file);
        });

        Flash::success(trans('admin/mails/general.status.sent'));

        return redirect('/admin/hrletter/' . $proposalId);
    }

    public function loadTemplate(Request $request)
    {
        $template = $request->template;
        $staff = \App\User::find($request->staff_id);
        $templateString = \App\Models\HrLetterTemplate::find($template)->description;
        $salaryTemplate = \PayrollHelper::getEmployeePayroll($staff->id)->template;
        $userInfo = [
            'user_name' => $staff->username,
            'first_name' => $staff->first_name,
            'last_name' => $staff->last_name,
            'mobile_phone' => $staff->phone,
            'home_phone' => $staff->parent_phone,
            'email' => $staff->email,
            'address' => $staff->userDetail->present_address,
            'designation' => $staff->designation->designations,
            'department' => $staff->department->deptname,
            'basic_salary' => $salaryTemplate->basic_salary,
            'gratuity_salary' => $salaryTemplate->gratuity_salary,
        ];

        // /.*\{{|\}}/gi
        preg_match_all('/ {{ ( (?: [^{}]* | (?R) )* ) }} /x', $templateString, $matches);
        $content = $templateString;

        foreach ($matches[0] as $index => $var_name) {
            $key = strip_tags($matches[1][$index]);
            $key = str_replace('&nbsp;', '', $key);
            $key = preg_replace('/\s+/', '', $key);

            if (isset($userInfo[$key])) {
                $content = str_replace($var_name, $userInfo[$key], $content);
            }
        }

        $content = html_entity_decode($content);

        if ($content) {
            return ['success' => 1, 'data' => $content];
        } else {
            return ['success' => 0, 'data' => ''];
        }
    }
}
