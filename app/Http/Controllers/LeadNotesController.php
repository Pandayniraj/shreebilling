<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\LeadNote as LeadNote;
use App\Models\Role as Permission;
use App\Models\Task as Task;
use App\Models\TaskUser;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LeadNotesController extends Controller
{
    /**
     * @var LeadNote
     */
    private $LeadNote;

    /**
     * @var Permission
     */
    private $permission;
    private $task;

    /**
     * @param LeadNote $LeadNote
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(LeadNote $LeadNote, Permission $permission, Task $task)
    {
        parent::__construct();
        $this->LeadNote = $LeadNote;
        $this->permission = $permission;
        $this->task = $task;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {   

        $this->validate($request, ['note'=> 'required',
            'lead_id' => 'required',
            'user_id' => 'required',
        ]);
       
        $attributes = $request->except('_token'); //$request->all();
        Audit::log(Auth::user()->id, trans('admin/leadnotes/general.audit-log.category'), trans('admin/leadnotes/general.audit-log.msg-store', ['name' => $attributes['note']]));

        $LeadNote = $this->LeadNote->create($attributes);

        $notes = \App\Models\Note::where('lead_id', $attributes['lead_id'])->orderBy('id', 'desc')->get();

        $data = '';
        if ($notes) {
            foreach ($notes as $key => $val) {
                $data .= '<div class="note-wrap" style="margin-top:10px; padding:0 15px; position: relative;">
                    	<p data-letters="'.mb_substr($val->note, 0, 3).'" style="margin-bottom:0; font-family:Consolas, \'Andale Mono\', \'Lucida Console\', \'Lucida Sans Typewriter\', \'Monaco\', \'Courier New\', monospace;">'.$val->note.'</p>
                        <i class="date"> ('.\Carbon\Carbon::createFromTimeStamp(strtotime($val->created_at))->diffForHumans().') by '.$val->user->first_name.'</i>';
                if (Auth::user()->id == $val->user_id || Auth::user()->hasRole('admins')) {
                    $data .= '<a title="Delete" data-target="#modal_dialog" data-toggle="modal" href="/admin/leadnotes/'.$val->id.'/confirm-delete" style="position:absolute; top:0; right:0;"><i class="fa fa-trash deletable"></i></a>';
                }
                $data .= '</div><hr style="margin:5px 0 0; border-color:#000;"/>';
            }
        }

        if ($request->stage_id == 5 || $request->stage_id == 6) {
            $lead = \App\Models\Lead::find($attributes['lead_id']);
            $app_name = env('APP_NAME');
            $from = env('APP_EMAIL');
            $to = env('REPORT_EMAIL');
            if ($request->stage_id == 5) {
                $this->convertToClients($attributes['lead_id']);
                $stages = 'Won';
            } else {
                $stages = $request->stage_id;
            }
            $lead->update(['stage_id'=>$request->stage_id, 'reason_id'=>$request->closure_reason, 'lead_type_id'=>'4']);
            $closure_reasons = \App\Models\LeadClosureReason::find($request->closure_reason);
            \App\Models\LeadActivityStream::create([
                'lead_id' => $attributes['lead_id'],
                'column_name'=>'',
                'related_status_or_rating_id'=>'',
                'change_type'=>'closure',
                'activity' =>'<b>Reason: </b>'.$closure_reasons->reason.'<br><b>Notes</b><br>'.mb_substr($attributes['note'], 0, 35).'<br><b>Start Date:- </b>'.$task_attribute['task_start_date'].'<br><b>Updated stage :-</b>'.$stages,
                'icons' => 'fa-tasks',
                'color'=>'bg-yellow',
                'task_assigned_to'=>$attributes['task_assign_to'],
                'user_id' => Auth::User()->id,
            ]);
            Flash::success('Sucessfully changed');
            Mail::send('emails.task-create', compact('mailcontent'), function ($message) use ($from,$to,$lead,$app_name,$stages) {
                $message->subject($app_name.' '.$lead->name.' Was marked '.$stages);
                $message->from($from, env('APP_COMPANY'));
                $message->to($to, '');
            });

            return ['status'=>$request->stage_id, 'messages' => $data];
        }
        if (! null == \Request::get('task_due_date')) {
            $task_attribute['lead_id'] = $attributes['lead_id'];
            $task_attribute['task_subject'] = Auth::user()->first_name.' '.mb_substr($attributes['note'], 0, 35);
            $task_attribute['task_detail'] = $attributes['note'];
            $task_attribute['task_status'] = 'Processing';
            $task_attribute['task_owner'] = $attributes['user_id'];
            $task_attribute['task_assign_to'] = $attributes['task_assign_to'];
            $task_attribute['task_priority'] = 'Medium';
            $task_attribute['task_start_date'] = date('Y-m-d G:i', (strtotime($attributes['task_due_date']) - (2 * 60)));
            $task_attribute['task_due_date'] = date('Y-m-d G:i', strtotime($attributes['task_due_date']));
            $task_attribute['task_complete_percent'] = '50';
            $task_attribute['task_alert'] = '1';
            $task_attribute['enabled'] = '1';
            $task_attribute['org_id'] = \Auth::user()->org_id;
            $task_attribute['user_id'] = \Auth::user()->id;
            $mailcontent = $this->task->create($task_attribute);  //weired data is inserted here

            $lead = \App\Models\Lead::find($attributes['lead_id']);

            $lead->update([
                'target_date'=>  date('Y-m-d', strtotime($attributes['task_due_date'])),
                'stage_id' => $request->stage_id,
            ]);
            $stages = \App\Models\Stage::find($request->stage_id);

            \App\Models\LeadActivityStream::create([
                'lead_id' => $attributes['lead_id'],
                'column_name'=>'',
                'related_status_or_rating_id'=>'',
                'change_type'=>'tasks',
                'activity' =>'<b>Task Subject</b><br>'.mb_substr($attributes['note'], 0, 35).'<br><b>Start Date:- </b>'.$task_attribute['task_start_date'].'<br><b>Updated Stage:- </b>'.$stages->name,
                'icons' => 'fa-tasks',
                'color'=>'bg-yellow',
                'task_assigned_to'=>$attributes['task_assign_to'],
                'user_id' => Auth::User()->id,
            ]);

            Mail::send('emails.task-create', compact('mailcontent'), function ($message) use ($attributes, $mailcontent, $request) {
                $message->subject(env('APP_NAME').' New Task Created');
                $message->from(env('APP_EMAIL'), env('APP_COMPANY'));
                $message->to(\TaskHelper::getUser($attributes['task_assign_to'])->email, '');
            });
            //$mailcontent = $this->task->create($task_attribute);
            // dd(\TaskHelper::getUser($attributes['task_assign_to'])->email);
            //TIME TO SEND EMAIL

            //used for notification, send note details to task table and User_Task table
            // and then show this in Activity Index
            $task_user = new TaskUser();
            $task_user->task_id = $mailcontent->id; //last inserted IDin task table
            $task_user->user_id = Auth::user()->id;
            $task_user->save();
        }

        Flash::success('Sucessfully changed');

        return ['messages' => $data];
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $LeadNote = $this->LeadNote->find($id);

        if (! $LeadNote->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/leadnotes/general.audit-log.category'), trans('admin/leadnotes/general.audit-log.msg-destroy', ['name' => $LeadNote->name]));

        $LeadNote->delete($id);

        Flash::success(trans('admin/leadnotes/general.status.deleted')); // 'LeadNote successfully deleted');

        return redirect('/admin/leads/'.$LeadNote->lead_id);
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

        $LeadNote = $this->LeadNote->find($id);
        if (! $LeadNote->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/leadnotes/dialog.delete-confirm.title');

        $LeadNote = $this->LeadNote->find($id);
        $modal_route = route('admin.leadnotes.delete', $LeadNote->id);

        $modal_body = trans('admin/leadnotes/dialog.delete-confirm.body', ['id' => $LeadNote->id, 'name' => $LeadNote->note]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function convertToClients($id)
    {
        $leads = \App\Models\Lead::find($id);
        if ($leads->company->name) {
            $clients_name = $leads->name;
        } else {
            $clients_name = $leads->name;
        }
        if ($leads->mob_phone) {
            $mobile = $leads->mob_phone;
        } else {
            $mobile = '';
        }
        $clients = [
            'org_id' => Auth::user()->id,
            'name' => $clients_name,
            'phone' =>$mobile,
            'email'=> $leads->email,
            'type' => 'Customers',
            'enabled' =>'1',
        ];
        $client = \App\Models\client::create($clients);
        $full_name = $client->name;
        $_ledgers = \TaskHelper::PostLedgers($full_name, \FinanceHelper::get_ledger_id('CUSTOMER_LEDGER_GROUP'));
        $attributes['ledger_id'] = $_ledgers;
        $client->update($attributes);
        $leads->update(['moved_to_client'=>'1']);
        Audit::log(Auth::user()->id, trans('admin/clients/general.audit-log.category'), trans('admin/clients/general.audit-log.msg-store', ['name' => $client->name]));

        return 0;
    }
}
