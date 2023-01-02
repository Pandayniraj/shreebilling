<?php

namespace App\Http\Controllers;

use App\DataTables\LeadsDataTable;
use App\Mobile\Audit as Audit;
use App\Mobile\Role as Permission;
use App\Mobile\Role as Role;
use App\Models\Attachment as Attachment;
use App\Models\Company as Company;
use App\Models\Lead as Lead;
use App\Models\Mail as Mail;
use App\Models\Note as LeadNote;
use App\Models\Sms as Sms;
use App\Repositories\LeadFileRepository as LeadFile;
use App\User as User;
use Datatables;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class OnlineLeadsController extends Controller
{
    /**
     * @var Lead
     */
    private $lead;
    private $LeadFile;
    private $Mail;
    private $Attachment;
    private $LeadNote;
    private $Sms;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @var User
     */
    private $user;

    private $company;

    /**
     * @param Lead $lead
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(LeadFile $LeadFile, Lead $lead, Mail $Mail, LeadNote $LeadNote, Attachment $attachment, Permission $permission, Role $role, User $user, Company $company, Sms $Sms)
    {
        parent::__construct();
        $this->LeadFile = $LeadFile;
        $this->lead = $lead;
        $this->mail = $Mail;
        $this->attachment = $attachment;
        $this->permission = $permission;
        $this->LeadNote = $LeadNote;
        $this->role = $role;
        $this->user = $user;
        $this->company = $company;
        $this->sms = $Sms;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/onlineleads/general.audit-log.category'), trans('admin/onlineleads/general.audit-log.msg-index'));

        $leads = \App\Models\Lead::orderBy('id', 'desc')
                 ->where('user_id', '14')	// id of 'Online' user is 14, this means list only the list submit from online form.
                 ->where('status_id', '17')	// lead status 17 means only Pending
                 ->where(function ($query) {
                     if (Request::get('course_id') && Request::get('course_id') != '') {
                         return $query->where('course_id', Request::get('course_id'));
                     }
                     if (Request::get('rating') && Request::get('rating') != '') {
                         return $query->where('rating', Request::get('rating'));
                     }
                     if (Request::get('user_id') && Request::get('user_id') != '') {
                         return $query->where('user_id', Request::get('user_id'));
                     }
                 })
                ->paginate(30);

        $courses = \App\Models\Product::where('enabled', '1')->pluck('name', 'id')->all();
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id')->all();

        $page_title = trans('admin/onlineleads/general.page.index.title'); // "Admin | Leads";
        $page_description = trans('admin/onlineleads/general.page.index.description'); // "List of Leads";

        return view('admin.onlineleads.index', compact('leads', 'courses', 'users', 'page_title', 'page_description'));
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        // Make sure to pull lead model instead of repository for yajra datatables for index view only,
        // Use above Lead repository for rest of these controller function
        //$lead = \App\Models\Lead::orderBy('id', 'desc');
        $lead = \App\Models\Lead::orderBy('id', 'desc')
                 ->where('user_id', '14')	// id of 'Online' user is 14, this means list only the list submit from online form.
                 ->where('status_id', '17')	// lead status 17 means only Pending
                 ->where(function ($query) {
                     if (Request::get('course_id') && Request::get('course_id') != '') {
                         return $query->where('course_id', Request::get('course_id'));
                     }
                 })
                ->where(function ($query) {
                    if (Request::get('rating') && Request::get('rating') != '') {
                        return $query->where('rating', Request::get('rating'));
                    }
                })
                ->where(function ($query) {
                    if (Request::get('user_id') && Request::get('user_id') != '') {
                        return $query->where('user_id', Request::get('user_id'));
                    }
                });

        return Datatables::of($lead)
        ->addColumn('action', function ($lead) {
            $datas = '';
            if ($lead->isEditable()) {
                $datas .= '<a href="'.route('admin.onlineleads.edit', $lead->id).'" title="{{ trans(\'general.button.edit\') }}"> <i class="fas fa-edit"></i> </a>';
            } else {
                $datas .= '<i class="fas fa-edit text-muted" title="{{ trans(\'admin/onlineleads/general.error.cant-edit-this-lead\') }}"></i>';
            }

            if ($lead->isEnableDisable()) {
                if ($lead->enabled) {
                    $datas .= '<a href="'.route('admin.onlineleads.disable', $lead->id).'" title="{{ trans(\'general.button.disable\') }}"> <i class="far fa-check-circle enabled"></i> </a>';
                } else {
                    $datas .= '<a href="'.route('admin.onlineleads.enable', $lead->id).'" title="{{ trans(\'general.button.enable\') }}"> <i class="fa fa-ban disabled"></i> </a>';
                }
            } else {
                if ($lead->enabled) {
                    $datas .= '<a title="{{ trans(\'general.button.disable\') }}"> <i class="far fa-check-circle enabled"></i> </a>';
                } else {
                    $datas .= '<a title="{{ trans(\'general.button.enable\') }}"> <i class="fa fa-ban disabled"></i> </a>';
                }
            }

            if ($lead->isDeletable()) {
                $datas .= '<a href="'.route('admin.onlineleads.confirm-delete', $lead->id).'" data-toggle="modal" data-target="#modal_dialog" title="{{ trans(\'general.button.delete\') }}"><i class="fas fa-trash-alt deletable"></i></a>';
            } else {
                $datas .= '<i class="fas fa-trash-alt text-muted" title="{{ trans(\'admin/onlineleads/general.error.cant-delete-this-lead\') }}"></i>';
            }

            return $datas;
            //return '<a href="#edit-'.$user->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
        })
            ->editColumn('selectall', function ($lead) {
                return \Form::checkbox('chkLead[]', $lead->id);
            })
                  ->editColumn('id', function ($lead) {
                      return $lead->id;
                  })
            ->editColumn('name', function ($lead) {
                return \link_to_route('admin.onlineleads.show', $lead->name, [$lead->id], []);
            })
            ->editColumn('course_name', function ($lead) {
                return $lead->course->name;
            })
            ->editColumn('intake_session_name', function ($lead) {
                return $lead->intake->name;
            })
            ->editColumn('status_id', function ($lead) {
                return $lead->status->name;
            })
            ->editColumn('form_status', function ($lead) {
                return $lead->online_application_status;
            })
            ->editColumn('date', function ($lead) {
                return date('Y-m-d', strtotime($lead->created_at));
            })
            ->editColumn('user_name', function ($lead) {
                return $lead->user->first_name;
            })
            ->editColumn('rating', function ($lead) {
                return ucfirst($lead->rating);
            })
        ->make(true);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $lead = $this->lead->find($id);

        Audit::log(Auth::user()->id, trans('admin/onlineleads/general.audit-log.category'), trans('admin/onlineleads/general.audit-log.msg-show', ['name' => $lead->name]));

        $page_title = trans('admin/onlineleads/general.page.show.title'); // "Admin | Lead | Show";
        $page_description = trans('admin/onlineleads/general.page.show.description', ['name' => $lead->name]); // "Displaying lead";
        $notes = \App\Models\Note::where('lead_id', $id)->orderBy('id', 'desc')->get();
        $files = \App\Models\File::where('lead_id', $id)->orderBy('id', 'desc')->get();
        $emails = \App\Models\Mail::where('lead_id', $id)->orderBy('id', 'desc')->get();
        $tasks = \App\Models\Task::where('lead_id', $id)->orderBy('id', 'desc')->take(5)->get();

        return view('admin.onlineleads.show', compact('lead', 'perms', 'page_title', 'page_description', 'notes', 'files', 'emails', 'tasks'));
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $lead = $this->lead->find($id);

        Audit::log(Auth::user()->id, trans('admin/onlineleads/general.audit-log.category'), trans('admin/onlineleads/general.audit-log.msg-edit', ['name' => $lead->name]));

        $page_title = trans('admin/onlineleads/general.page.edit.title'); // "Admin | Lead | Edit";
        $page_description = trans('admin/onlineleads/general.page.edit.description', ['name' => $lead->name]); // "Editing lead";

        if (! $lead->isEditable() && ! $lead->canChangePermissions()) {
            abort(403);
        }

        $courses = \App\Models\Product::where('enabled', '1')->pluck('name', 'id');
        $intakes = \App\Models\Intake::where('enabled', '1')->pluck('name', 'id');
        $communications = \App\Models\Communication::where('enabled', '1')->pluck('name', 'id');
        $enquiry_modes = \App\Models\Enquirymode::where('enabled', '1')->pluck('name', 'id');
        $lead_status = \App\Models\Leadstatus::where('enabled', '1')->pluck('name', 'id');
        $admission_process = \App\Models\Admissionprocess::where('enabled', '1')->pluck('name', 'id');
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');

        return view('admin.onlineleads.edit', compact('lead', 'perms', 'page_title', 'page_description', 'courses', 'intakes', 'communications', 'enquiry_modes', 'lead_status', 'admission_process', 'users'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name'          => 'required|unique:leads,name,'.$id,
        ]);

        $lead = $this->lead->find($id);

        Audit::log(Auth::user()->id, trans('admin/onlineleads/general.audit-log.category'), trans('admin/onlineleads/general.audit-log.msg-update', ['name' => $lead->name]));

        $attributes = $request->all();

        $temp_company = $this->company->where('name', $request['company_id'])->first();
        if ($temp_company) {
            $attributes['company_id'] = $temp_company->id;
        } else {
            $company_attr['name'] = $request['company_id'];
            $company = $this->company->create($company_attr);
            $attributes['company_id'] = $company->id;
        }

        if ($lead->status_id != $request['status_id']) {
            $attributes['lead_status_udpated_date'] = date('Y-m-d H:i:s');
        }

        if ($lead->isEditable()) {
            $lead->update($attributes);
        }

        Flash::success(trans('admin/onlineleads/general.status.updated')); // 'Lead successfully updated');

        return redirect('/admin/onlineleads');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $lead = $this->lead->find($id);

        if (! $lead->isdeletable()) {
            abort(403);
        }

        // Delete the Notes related to the lead
        $LeadNote = \App\Models\Note::where('lead_id', $id)->orderBy('id', 'desc')->get();
        if ($LeadNote) {
            foreach ($LeadNote as $lnk => $lnv) {
                $this->LeadNote->delete($lnv->id);
            }
        }

        // Delete the File related to the lead
        $LeadFile = \App\Models\File::where('lead_id', $id)->orderBy('id', 'desc')->get();
        if ($LeadFile) {
            foreach ($LeadFile as $k => $v) {
                $fileUrl = public_path().'/files/'.$v->file;
                File::delete($fileUrl);
                $this->LeadFile->delete($v->id);
            }
        }

        // Delete mail data and attached file related to the lead
        $mail = \App\Models\Mail::where('lead_id', $id)->orderBy('id', 'desc')->get();
        if ($mail) {
            foreach ($mail as $k => $v) {
                // To delete the related attachment file while deleting the mail.
                $attachments = \App\Models\Attachment::where('mail_id', $v->id)->orderBy('id', 'desc')->get();
                if ($attachments) {
                    if ($attachments) {
                        foreach ($attachments as $key => $val) {
                            // Delete the attachment file from its location
                            $fileUrl = public_path().'/sent_attachments/'.$val->file;
                            File::delete($fileUrl);
                            $this->attachment->delete($val->id);
                        }
                    }
                }
                $this->mail->delete($v->id);
            }
        }

        Audit::log(Auth::user()->id, trans('admin/onlineleads/general.audit-log.category'), trans('admin/onlineleads/general.audit-log.msg-destroy', ['name' => $lead->name]));

        $this->lead->delete($id);

        Flash::success(trans('admin/onlineleads/general.status.deleted')); // 'Lead successfully deleted');

        return redirect('/admin/onlineleads');
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

        $lead = $this->lead->find($id);

        if (! $lead->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/onlineleads/dialog.delete-confirm.title');

        $lead = $this->lead->find($id);
        $modal_route = route('admin.onlineleads.delete', ['id' => $lead->id]);

        $modal_body = trans('admin/onlineleads/dialog.delete-confirm.body', ['id' => $lead->id, 'name' => $lead->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $lead = $this->lead->find($id);

        Audit::log(Auth::user()->id, trans('admin/onlineleads/general.audit-log.category'), trans('admin/onlineleads/general.audit-log.msg-enable', ['name' => $lead->name]));

        $lead->enabled = true;
        $lead->save();

        Flash::success(trans('admin/onlineleads/general.status.enabled'));

        return redirect('/admin/onlineleads');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $lead = $this->lead->find($id);

        Audit::log(Auth::user()->id, trans('admin/onlineleads/general.audit-log.category'), trans('admin/onlineleads/general.audit-log.msg-disabled', ['name' => $lead->name]));

        $lead->enabled = false;
        $lead->save();

        Flash::success(trans('admin/onlineleads/general.status.disabled'));

        return redirect('/admin/onlineleads');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkLeads = $request->input('chkLead');

        Audit::log(Auth::user()->id, trans('admin/onlineleads/general.audit-log.category'), trans('admin/onlineleads/general.audit-log.msg-enabled-selected'), $chkLeads);

        if (isset($chkLeads)) {
            foreach ($chkLeads as $lead_id) {
                $lead = $this->lead->find($lead_id);
                $lead->enabled = true;
                $lead->save();
            }
            Flash::success(trans('admin/onlineleads/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/onlineleads/general.status.no-lead-selected'));
        }

        return redirect('/admin/onlineleads');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkLeads = $request->input('chkLead');

        Audit::log(Auth::user()->id, trans('admin/onlineleads/general.audit-log.category'), trans('admin/onlineleads/general.audit-log.msg-disabled-selected'), $chkLeads);

        if (isset($chkLeads)) {
            foreach ($chkLeads as $lead_id) {
                $lead = $this->lead->find($lead_id);
                $lead->enabled = false;
                $lead->save();
            }
            Flash::success(trans('admin/onlineleads/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/onlineleads/general.status.no-lead-selected'));
        }

        return redirect('/admin/onlineleads');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function sendSMS(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkLeads = $request->input('chkLead');

        Audit::log(Auth::user()->id, trans('admin/sms/general.audit-log.category'), trans('admin/onlineleads/general.audit-log.send-sms'), $chkLeads);

        $recipients = '';
        if (isset($chkLeads)) {
            foreach ($chkLeads as $lead_id) {
                $lead = $this->lead->find($lead_id);
                $recipients .= $lead->mob_phone.',';
                $lead->enabled = false;
                $lead->save();
            }
            rtrim($recipients, ',');

            if (trim(Request::get('message')) != '') {
                $message = trim(Request::get('message')).' - The British College';
                //dd($text);
                $ch = curl_init();
                $curlUrl = 'http://smsprima.com/api/api/index?username=tbc&password=123TBC123&sender=TBC&destination='.$recipients.'&type=1&message='.urlencode($message).''; // api url as provided in the document.
                curl_setopt($ch, CURLOPT_URL, $curlUrl);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);

                //dd($response);
                if (! curl_errno($ch)) {
                    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
                        /*foreach(json_decode($response) as $sms) {
                            if(!$sms->recipient || isset($sms->error)) {
                                continue;
                            }

                            $attributes['recipient'] = $sms->recipient;
                            $attributes['uuid'] = $sms->id;
                            $attributes['status'] = '0';
                            $attributes['message'] = $message;
                            $this->sms->create($attributes);
                        }*/
                    }
                    Flash::success(trans('admin/sms/general.status.sms-sent'));
                } else {
                    Flash::warning(trans('admin/sms/general.status.sms-err'));
                }
            } else {
                Flash::warning(trans('admin/sms/general.status.no-msg'));
            }
        } else {
            Flash::warning(trans('admin/sms/general.status.no-lead-selected'));
        }

        return redirect('/admin/onlineleads');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function sendLeadSMS(Request $request)
    {
        $lead_id = Request::get('lead_id');

        if (trim(Request::get('message')) != '') {
            if (Request::get('recipients_no') == '') {
                Flash::warning('Mobile number is empty');
            } else {
                $message = trim(Request::get('message')).' - The British College';
                $recipients = trim(Request::get('recipients_no'));
                $ch = curl_init();
                $curlUrl = 'http://smsprima.com/api/api/index?username=tbc&password=123TBC123&sender=TBC&destination='.$recipients.'&type=1&message='.urlencode($message).''; // api url as provided in the document.
                curl_setopt($ch, CURLOPT_URL, $curlUrl);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);

                //dd($response);
                if (! curl_errno($ch)) {
                    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
                        /*foreach(json_decode($response) as $sms) {
                            if(!$sms->recipient || isset($sms->error)) {
                                continue;
                            }

                            $attributes['recipient'] = $sms->recipient;
                            $attributes['uuid'] = $sms->id;
                            $attributes['status'] = '0';
                            $attributes['message'] = $message;
                            $this->sms->create($attributes);
                        }*/
                    }
                    Flash::success(trans('admin/sms/general.status.sms-sent'));
                } else {
                    Flash::warning(trans('admin/sms/general.status.sms-err'));
                }
            }
        } else {
            Flash::warning(trans('admin/sms/general.status.no-msg'));
        }

        return redirect('/admin/onlineleads/'.$lead_id);
    }

    /*
    * Receive SMS Report
    *
    */
    public function receiveSMSReport()
    {
        $sms = \App\Models\Sms::where('uuid', Request::get('id'))->first();
        $attributes['status'] = Request::get('status');
        $sms->update($attributes);
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $leads = $this->lead->where('name', 'LIKE', '%'.$query.'%')->get();
        foreach ($leads as $lead) {
            $id = $lead->id;
            $name = $lead->name;
            $email = $lead->email;

            $entry_arr = ['id' => $id, 'text' => "$name ($email)"];
            $return_arr[] = $entry_arr;
        }

        return $return_arr;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getInfo(Request $request)
    {
        $id = $request->input('id');
        $lead = $this->lead->find($id);

        return $lead;
    }

    /*public function sendsms()
    {
        \SMS::send('This is my message', [], function($sms) {
            $sms->to('+9779841858178');
        });
    }*/

    // To get the data for autocomplete of Company
    public function get_company()
    {
        $term = strtolower(Request::get('term'));
        $courses = \App\Models\Company::select('id', 'name')->where('name', 'LIKE', '%'.$term.'%')->groupBy('name')->take(5)->get();
        $return_array = [];

        foreach ($courses as $v) {
            if (strpos(strtolower($v->name), $term) !== false) {
                $return_array[] = ['value' => $v->name, 'id' =>$v->id];
            }
        }

        return Response::json($return_array);
    }
}
