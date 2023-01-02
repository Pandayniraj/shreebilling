<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Lead as Lead;
use App\Models\Note as LeadNote;
use App\Models\Role as Permission;
use App\Models\Role as Role;
use App\Models\Task as Task;
use App\Models\TaskUser;
use App\User as User;
use Datatables;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TasksController extends Controller
{
    /**
     * @var Lead
     */
    private $lead;
    private $task;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @var User
     */
    private $user;

    /**
     * @param Lead $lead
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Lead $lead, Task $task, Permission $permission, Role $role, User $user, LeadNote $LeadNote)
    {
        parent::__construct();

        $this->lead = $lead;
        $this->task = $task;
        $this->permission = $permission;
        $this->user = $user;
        $this->role = $role;
        $this->LeadNote = $LeadNote;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/tasks/general.audit-log.category'), trans('admin/tasks/general.audit-log.msg-index'));

        //->where('task_owner', Auth::user()->id)
        //->where('task_assign_to', Auth::user()->id)

        $tasks = \App\Models\Task::where(function ($query) {
            if (\Request::get('assign_to') && \Request::get('assign_to') != '') {
                return $query->where('task_assign_to', \Request::get('assign_to'));
            }
        })
            ->paginate(25);

        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id')->all();

        $page_title = trans('admin/tasks/general.page.index.title');
        $page_description = trans('admin/tasks/general.page.index.description');

        return view('admin.tasks.index', compact('tasks', 'users', 'page_title', 'page_description'));
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allData()
    {

        //echo 1; exit;
        // Make sure to pull task model instead of repository for yajra datatables for index view only,
        // Use above Task repository for rest of these controller function
        $task = \App\Models\Task::orderBy('id', 'desc')
            ->where('org_id', Auth::user()->org_id)
            ->where('task_owner', Auth::user()->id)
            ->where('task_owner', '!=', Auth::user()->id)
            ->orWhere('task_assign_to', Auth::user()->id)
            ->orWhere('task_assign_to', '!=', Auth::user()->id)
            ->where(function ($query) {
                if (Request::get('assign_to') && Request::get('assign_to') != '') {
                    return $query->where('task_assign_to', Request::get('assign_to'));
                }
            });

        return Datatables::of($task)->addColumn('action', function ($task) {
            $datas = '';
            if ($task->isEditable()) {
                $datas .= '<a href="' . route('admin.tasks.edit', $task->id) . '" title="' . trans('general.button.edit') . '"> <i class="fa fa-pencil-square-o"></i> </a>';
            } else {
                $datas .= '<i class="fa fa-pencil-square-o text-muted" title="' . trans('admin/tasks/general.error.cant-edit-this-lead') . '"></i>';
            }

            if ($task->isEnableDisable()) {
                if ($task->enabled) {
                    $datas .= '<a href="' . route('admin.tasks.disable', $task->id) . '" title="' . trans('general.button.disable') . '"> <i class="fa fa-check-circle-o enabled"></i> </a>';
                } else {
                    $datas .= '<a href="' . route('admin.tasks.enable', $task->id) . '" title="' . trans('general.button.enable') . '"> <i class="fa fa-ban disabled"></i> </a>';
                }
            } else {
                if ($task->enabled) {
                    $datas .= '<a title="' . trans('general.button.disable') . '"> <i class="fa fa-check-circle-o enabled"></i> </a>';
                } else {
                    $datas .= '<a title="' . trans('general.button.enable') . '"> <i class="fa fa-ban disabled"></i> </a>';
                }
            }
            if ($task->isDeletable()) {
                $datas .= '<a href="' . route('admin.tasks.confirm-delete', $task->id) . '" data-toggle="modal" data-target="#modal_dialog" title="' . trans('general.button.delete') . '"><i class="fa fa-trash-o deletable"></i></a>';
            } else {
                $datas .= '<i class="fa fa-trash-o text-muted" title="' . trans('admin/tasks/general.error.cant-delete-this-lead') . '"></i>';
            }

            return $datas;
            //return '<a href="#edit-'.$user->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
        })
            ->editColumn('selectall', function ($task) {
                return \Form::checkbox('chkTask[]', $task->id);
            })
            ->editColumn('task_subject', function ($task) {
                return $task->task_subject;
            })
            ->editColumn('lead', function ($task) {
                return \link_to_route('admin.tasks.show', $task->lead->name, [$task->id], []);
            })
            ->editColumn('task_status', function ($task) {
                return $task->task_status;
            })
            ->editColumn('task_owner', function ($task) {
                return $task->owner->first_name;
            })
            ->editColumn('assigned_to', function ($task) {
                return $task->assigned_to->first_name;
            })
            ->editColumn('task_priority', function ($task) {
                return $task->task_priority;
            })
            ->editColumn('task_start_date', function ($task) {
                return date('Y-m-d', strtotime($task->task_start_date));
            })
            ->editColumn('task_due_date', function ($task) {
                $due = date('Y-m-d', strtotime($task->task_due_date));
                if ($due == date('Y-m-d')) {
                    return '<span style="color:red; font-weight:bold;">' . $due . '</span>';
                } else {
                    return $due;
                }
            })
            ->editColumn('task_complete_percent', function ($task) {
                return $task->task_complete_percent;
            })
            ->editColumn('task_enable', function ($task) {
                if ($task->enabled) {
                    return '<span style="color:green; font-weight:bold;">Enabled</span>';
                } else {
                    return '<span style="color:orange; font-weight:bold;">Disabled</span>';
                }
            })
            ->editColumn('date', function ($task) {
                return date('Y-m-d', strtotime($task->created_at));
            })
            ->make(true);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $task = $this->task->find($id);

        Audit::log(Auth::user()->id, trans('admin/tasks/general.audit-log.category'), trans('admin/tasks/general.audit-log.msg-show', ['name' => $task->name]));

        $page_title = trans('admin/tasks/general.page.show.title'); // "Admin | Lead | Show";
        $page_description = trans('admin/tasks/general.page.show.description', ['name' => $task->name]);

        return view('admin.tasks.show', compact('task', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/tasks/general.page.create.title'); // "Admin | Lead | Create";
        $page_description = trans('admin/tasks/general.page.create.description'); // "Creating a new lead";

        $lead = new \App\Models\Lead();
        $perms = $this->permission->all();

        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');
        if (\Request::ajax()) {
            return view('admin.tasks.modals.create', compact('lead', 'perms', 'page_title', 'page_description', 'users'));
        }

        return view('admin.tasks.create', compact('lead', 'perms', 'page_title', 'page_description', 'users'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public static function makebladeview($tv)
    {
        $html = "<div class='task-wrap' style='margin-top:10px; padding:0 15px; position: relative;'>
          <a href='/admin/tasks/{$tv->id}'>{$tv->task_subject}</a><br/>
          <i class='date'>Assigned To: {$tv->assigned_to->first_name}</i>";
        $due = date('Y-m-d', strtotime($tv->task_due_date));
        if ($due == date('Y-m-d')) {
            $d_date = "<span style='color:red;'>{$tv->task_due_date}</span>";
        } else {
            $d_date = $tv->task_due_date;
        }
        $html .= "<span style='position:absolute; top:0; right:0;'>Due Date: {$d_date}</span></div>";

        return $html;
    }

    public function store(Request $request)
    {
        if (\Request::ajax() && !isset($request->isAddedFromCalandar)) {
            $validator = Validator::make($request->all(), [
                'task_due_date' => 'required',
                'task_subject' => 'required',
            ]);
            if ($validator->fails()) {
                return ['error' => $validator->errors()];
            }
        }
        $attributes = $request->all();
        
        if (!isset($request->isAddedFromCalandar)) {
            $this->validate($request, [
                'task_due_date' => 'required',
                'task_subject' => 'required',
            ]);
            $temp_task = $this->lead->where('id', $request['lead_id'])->orWhere('name', $request['lead'])->first();
            if (!$temp_task) {
                $attributes['lead_id'] = 0;
            } else {
                $attributes['lead_id'] = $temp_task->id;
            }
        }

        if (isset($request->isAddedFromCalandar)) {
            $attributes['lead_id'] = $attributes['lead_id'] ?? '0';
            $attributes['task_start_date'] = null;
            $attributes['task_due_date'] = date('Y-m-d G:i', strtotime($request['task_due_date']));
        }

        $attributes['org_id'] = Auth::user()->org_id;
        $attributes['task_owner'] = Auth::user()->id;
        Audit::log(Auth::user()->id, trans('admin/tasks/general.audit-log.category'), trans('admin/tasks/general.audit-log.msg-store', ['name' => $attributes['lead']]));

        $attributes['task_start_date'] = date('Y-m-d G:i', strtotime($request['task_start_date']));
        $attributes['task_due_date'] = date('Y-m-d G:i', strtotime($request['task_due_date']));

        $lead = $this->task->create($attributes);

        //Now send data to notes table so that it reflects to calendar and Follow ups list

        $note_attribute['note'] = $attributes['task_subject'];
        $note_attribute['lead_id'] = $attributes['lead_id'];
        $note_attribute['user_id'] = Auth::user()->id;

        $mailcontent = $this->LeadNote->create($note_attribute);
        //send emails and in a task users table
        //TIME TO SEND EMAIL

        try {
            $from = env('APP_EMAIL');
            $app_company = env('APP_COMPANY');

            Mail::send('emails.task-create', compact('mailcontent'), function ($message) use ($attributes, $mailcontent, $request, $from, $app_company) {
                $message->subject(env('APP_NAME') . ' New Task Created');
                $message->from($from, $app_company);
                $message->to(\TaskHelper::getUser($attributes['task_assign_to'])->email, '');
            });
        } catch (\Exception $e) {
        }

        //used for notification, send note details to task table and User_Task table
        // and then show this in Activity Index
        $task_user = new TaskUser();
        $task_user->task_id = $mailcontent->id; //last inserted IDin task table
        $task_user->user_id = Auth::user()->id;
        $task_user->save();

        // dd($task_user);

        Flash::success(trans('admin/tasks/general.status.created'));
        if (\Request::ajax()) {
            $html = self::makebladeview($lead);

            return ['tasks' => $lead, 'html' => $html];
        }

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $task = $this->task->find($id);

        Audit::log(Auth::user()->id, trans('admin/tasks/general.audit-log.category'), trans('admin/tasks/general.audit-log.msg-edit', ['name' => $task->name]));

        $page_title = trans('admin/tasks/general.page.edit.title'); // "Admin | Lead | Edit";
        $page_description = trans('admin/tasks/general.page.edit.description', ['name' => $task->name]); // "Editing lead";

        if (!$task->isEditable() && !$task->canChangePermissions()) {
            abort(403);
        }
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');
        $leads = \App\Models\Lead::where('enabled', '1')->get();
        if (\Request::ajax() && \Request::get('calandar')) {

            return view('admin.tasks.modals.editfromcalander', compact('task', 'leads'));
        }
        return view('admin.tasks.edit', compact('page_title', 'page_description', 'task', 'users'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {


        $attributes = $request->all();

        $attributes['org_id'] = Auth::user()->org_id;

        if($request->submit_option && $request->submit_option =='complete'){
            $wantToMakeComplete  = true;
            $attributes['task_status'] = 'Completed';

        }

        $temp_task = $this->lead->where('name', $request['lead'])->first();

        if (!$temp_task) {

            $attributes['lead_id'] = 0;
        } else {
            $attributes['lead_id'] = $temp_task->id;
        }
        // To set the enabled option
        if ($request['enabled']) {
            $attributes['enabled'] = $request['enabled'];
        } else {
            $attributes['enabled'] = '0';
        }

        $attributes['task_start_date'] = date('Y-m-d G:i', strtotime($request['task_start_date']));
        $attributes['task_due_date'] = date('Y-m-d G:i', strtotime($request['task_due_date']));
        //dd($attributes['task_start_date']);

        $task = $this->task->find($id);
        if($task->task_status != 'Completed' && !$wantToMakeComplete){
            $attributes['task_status'] = 'Processing';
        }

        Audit::log(Auth::user()->id, trans('admin/tasks/general.audit-log.category'), trans('admin/tasks/general.audit-log.msg-update', ['name' => $task->name]));

        if ($task->isEditable()) {
            $task->update($attributes);
        }
        Flash::success(trans('admin/tasks/general.status.updated')); // 'Lead successfully updated');

        return Redirect::back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $task = $this->task->find($id);

        if (!$task->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/tasks/general.audit-log.category'), trans('admin/tasks/general.audit-log.msg-destroy', ['name' => $task->task_subject]));

        $task->delete($id);

        Flash::success(trans('admin/tasks/general.status.deleted')); // 'Lead successfully deleted');

        return redirect('/admin/tasks');
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

        $task = $this->task->find($id);

        if (!$task->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/tasks/dialog.delete-confirm.title');

        $modal_route = route('admin.tasks.delete', ['taskId' => $task->id]);

        $modal_body = trans('admin/tasks/dialog.delete-confirm.body', ['id' => $task->id, 'name' => $task->task_subject]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $task = $this->task->find($id);

        Audit::log(Auth::user()->id, trans('admin/tasks/general.audit-log.category'), trans('admin/tasks/general.audit-log.msg-enable', ['name' => $task->task_subject]));

        $task->enabled = true;
        $task->save();

        Flash::success(trans('admin/tasks/general.status.enabled'));

        return redirect('/admin/tasks');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $task = $this->task->find($id);

        Audit::log(Auth::user()->id, trans('admin/tasks/general.audit-log.category'), trans('admin/tasks/general.audit-log.msg-disabled', ['name' => $task->task_subject]));

        $task->enabled = false;
        $task->save();

        Flash::success(trans('admin/tasks/general.status.disabled'));

        return redirect('/admin/tasks');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkTasks = $request->input('chkTask');

        Audit::log(Auth::user()->id, trans('admin/tasks/general.audit-log.category'), trans('admin/tasks/general.audit-log.msg-enabled-selected'), $chkTasks);

        if (isset($chkTasks)) {
            foreach ($chkTasks as $lead_id) {
                $task = $this->task->find($lead_id);
                $task->enabled = true;
                $task->save();
            }
            Flash::success(trans('admin/tasks/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/tasks/general.status.no-lead-selected'));
        }

        return redirect('/admin/tasks');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkTasks = $request->input('chkTask');

        Audit::log(Auth::user()->id, trans('admin/tasks/general.audit-log.category'), trans('admin/tasks/general.audit-log.msg-disabled-selected'), $chkTasks);

        if (isset($chkTasks)) {
            foreach ($chkTasks as $lead_id) {
                $task = $this->task->find($lead_id);
                $task->enabled = false;
                $task->save();
            }
            Flash::success(trans('admin/tasks/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/tasks/general.status.no-lead-selected'));
        }

        return redirect('/admin/tasks');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getInfo(Request $request)
    {
        $id = $request->input('id');
        $task = $this->task->find($id);

        return $task;
    }

    // To get the data for autocomplete of Company
    public function getLeads()
    {
        $term = strtolower(\Request::get('term'));
        $products = \App\Models\Lead::select('id', 'name')->where('name', 'LIKE', '%' . $term . '%')->groupBy('name')->take(5)->get();
        $return_array = [];

        foreach ($products as $v) {
            if (strpos(strtolower($v->name), $term) !== false) {
                $return_array[] = ['value' => $v->name, 'id' => $v->id];
            }
        }

        return Response::json($return_array);
    }

    public function ajaxTaskStatus(Request $request)
    {
        $task = $this->task->find($request->id);
        $type = $request->update_types;
         if($task->task_status != 'Completed'){
            $attributes['task_status'] = 'Processing';
        }
        if ($type == 'start_date') {
            $attributes['task_start_date'] = $request->update_value;
            $task->update($attributes);

            return ['status' => 1];
        } elseif ($type == 'calendar_changes') {
            if ($request->start_date) {
                $attributes['task_start_date'] = $request->start_date;
            }
            if ($request->end_date) {
                $attributes['task_due_date'] = $request->end_date;
            }
            $task->update($attributes);

            return ['status' => 1];
        }
        $attributes['task_status'] = $request->task_status;

       

        // dd($request->id);

        $task->update($attributes);

        return ['status' => 1];
    }
}
