<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\MasterComments;
use App\Models\Projects as ProjectsModel;
use App\Models\ProjectTask as ProjectTaskModel;
use App\Models\ProjectTaskCategory;
use App\Models\ProjectTaskUser;
use App\Models\ProjectUser;
use App\Models\Role as Permission;
use App\User;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class ProjectsController extends Controller
{
    /**
     * @var Project
     */
    private $projects;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Project $project
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(ProjectsModel $projects, Permission $permission)
    {
        parent::__construct();
        $this->projects = $projects;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Audit::log(Auth::user()->id, trans('admin/projects/general.audit-log.category'), trans('admin/projects/general.audit-log.msg-index'));

        if (Auth::user()->hasRole('admins')) {
            $projects = ProjectsModel::where('org_id',\Auth::user()->org_id)->where('status', 'New')
                ->orWhere('status', 'Started')
                ->orderBy('id', 'DESC')
                ->paginate(15);

        } else {
            $projectsUsers = ProjectUser::where('user_id', Auth::user()->id)->get()->pluck('project_id');

            $projects = ProjectsModel::whereIn('id', $projectsUsers)
                ->orderBy('id', 'DESC')
                ->where('enabled', 1)
                ->where('org_id',\Auth::user()->org_id)
                ->paginate(15);
        }
      

        $page_title = trans('admin/projects/general.page.index.title');
        $page_description = trans('admin/projects/general.page.index.description');

        //dd($projects);

        return view('admin.projects.index', compact('projects', 'page_title', 'page_description'));
    }

    public function old_projects()
    {
        $page_title = 'Old Projects';
        $page_description = 'Lists';
        $projects = ProjectsModel::where('status', 'Completed')->get();
        $users = \App\User::select(DB::raw("id, CONCAT(first_name, ' ', last_name) AS name"))->where('enabled', '1')->get();

        return view('admin.projects.old_projects', compact('projects', 'page_title', 'page_description', 'users'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        if (!Auth::user()->hasRole('admins')) {
            $project = $this->projects->find($id);
            $users = $project->staffs;
            $value = strtolower(Auth::user()->username);
            // dd($users);
            $array = explode(',', $users);
            
            $array = array_map(function($arr){
                return strtolower($arr);
            },$array);
     
            if (!in_array($value, $array)) {
                return redirect('/admin/projects')->withErrors('You are not assigned to this project');
            }
        }

        $project = $this->projects->find($id);
        $projects = ProjectsModel::orderBy('name', 'ASC')->pluck('name', 'id')->all();

        Audit::log(Auth::user()->id, trans('admin/projects/general.audit-log.category'), trans('admin/projects/general.audit-log.msg-show', ['name' => $project->name]));

        $page_title = trans('admin/projects/general.page.show.title'); // "Admin | Project | Show";
        $page_description = trans('admin/projects/general.page.show.description', ['name' => $project->name]);
        // "Displaying client: :name";
        $cat = ProjectTaskCategory::orderBy('name', 'ASC')->pluck('name', 'id');

        $duties = ProjectTaskModel::orderBy('created_at', 'desc')->where('category_id', '23')->take(1)->get();
        $timespan = Carbon::now();
        $stages = DB::select('select id as value ,CONCAT(UPPER(SUBSTRING(name,1,1)),LOWER(SUBSTRING(name,2))) AS text from task_stages');
        // $comments = MasterComments::orderBy('id', 'desc')->get();
        //new ,ongoing, completed were made as static at beggining so we select seperatly

        $project_status = ['new', 'ongoing', 'completed'];
        foreach ($project_status as $key => $value) {
            $new_ongoing_completed[$value] = \App\Models\ProjectTaskStatus::select('name', 'description')->where('name', $value)->where('project_id', $id)->first();
        }
        $other_status = \App\Models\ProjectTaskStatus::select('name', 'description')->whereNotIn('name', $project_status)->where('project_id', $id)->where('enabled', '1')->get();
        $other_project_tasks = [];
        $other_project_tasks_data = [];

        if (Auth::user()->hasRole('admins') || $project->assign_to == Auth::user()->id) {
            //SHOW EVERYTHING FOR ADMIN AND PROJECT MANAGER

            $timespan = Carbon::now();
            $new_tasks = ProjectTaskModel::where('project_id', $id)
                ->where('status', 'new')
                ->orderByRaw('task_order = 0 , task_order ASC')
                ->get();

            //  dd($new_tasks);

            $ongoing_tasks = ProjectTaskModel::where('project_id', $id)
                ->orderByRaw('task_order = 0 , task_order ASC')
                ->where('status', 'ongoing')->get();
            //dd($ongoing_tasks);
            $completed_tasks = ProjectTaskModel::where('project_id', $id)->orderBy('updated_at', 'desc')->where('status', 'completed')->paginate(10);
            foreach ($other_status as $key => $value) {
                $other_project_tasks_data[$value->name] = ProjectTaskModel::where('project_id', $id)
                    ->where('status', $value->name)
                    ->orderByRaw('task_order = 0 , task_order ASC')
                    ->get();

                $other_project_tasks[$value->name] = $value->description;
                array_push($project_status, $value->name);
            }
        } else {
            $userAssignedTasks = ProjectTaskUser::where('user_id', Auth::user()->id)
                ->get()
                ->pluck('project_task_id');

            $usersCreatedNewTask = ProjectTaskModel::where('user_id', Auth::user()->id)
                ->Where('project_id', $id)
                ->where('status', 'new')
                ->orderBy('id', 'desc')
                ->get()
                ->pluck('id');

            $allnewtasks = array_merge($usersCreatedNewTask->toArray(), $userAssignedTasks->toArray());
            //dd($allnewtasks);
            // SHOW THE TASKS TO USERS THAT ARE ASSIGNED AND ALSO SHOW TASKS FOR OWNER

            /*************NEW TASKS*********************/
            $new_tasks = ProjectTaskModel::whereIn('id', $allnewtasks)
                ->Where('project_id', $id)
                ->where('status', 'new')
                ->orderByRaw('task_order = 0 , task_order ASC')
                ->get();

            $usersCreatedOngoingTask = ProjectTaskModel::where('user_id', Auth::user()->id)
                ->Where('project_id', $id)
                ->where('status', 'ongoing')
                ->orderBy('id', 'desc')
                ->get()
                ->pluck('id');

            $allOngingtasks = array_merge($usersCreatedOngoingTask->toArray(), $userAssignedTasks->toArray());

            /*************ONGOING TASKS*********************/
            $ongoing_tasks = ProjectTaskModel::whereIn('id', $allOngingtasks)
                ->where('project_id', $id)
                ->where('status', 'ongoing')
                ->orderByRaw('task_order = 0 , task_order ASC')
                ->get();

            $usersCreatedCompleteTask = ProjectTaskModel::where('user_id', Auth::user()->id)
                ->Where('project_id', $id)
                ->where('status', 'completed')
                ->orderBy('id', 'desc')
                ->get()
                ->pluck('id');
            $allCompletedtasks = array_merge($usersCreatedCompleteTask->toArray(), $userAssignedTasks->toArray());

            /*************COMPLETED TASKS*********************/
            $completed_tasks = ProjectTaskModel::whereIn('id', $allCompletedtasks)
                ->where('project_id', $id)
                ->orderBy('updated_at', 'desc')
                ->where('status', 'completed')
                ->paginate(10);

            foreach ($other_status as $key => $value) {
                $usersCreatedOtherTask = ProjectTaskModel::where('user_id', Auth::user()->id)
                    ->Where('project_id', $id)
                    ->where('status', $value->name)
                    ->orderBy('id', 'desc')
                    ->get()
                    ->pluck('id');

                $allotherstasks = array_merge($usersCreatedOtherTask->toArray(), $userAssignedTasks->toArray());
                $other_project_tasks_data[$value->name] = ProjectTaskModel::whereIn('id', $allotherstasks)
                    ->where('project_id', $id)
                    ->where('status', $value->name)
                    ->orderByRaw('task_order = 0 , task_order ASC')
                    ->get();

                $other_project_tasks[$value->name] = $value->description;
                array_push($project_status, $value->name);
            }
        }

        $project_files = ProjectTaskModel::select('id', 'attachment')->where('project_id', $id)->where('attachment', '!=', '')->orderBy('id', 'desc')->get();

        $projectTaskAttachment = \App\Models\ProjectTaskAttachment::select('project_task_attachment.*')->leftjoin('project_tasks','project_task_attachment.task_id','=','project_tasks.id')->where("project_tasks.project_id",$id)->take(30)->get();


        $comments_files = MasterComments::select('file as attachment', 'master_id as tskid')
            ->leftjoin('project_tasks', 'project_tasks.id', '=', 'master_comments.master_id')
            ->leftjoin('projects', 'projects.id', '=', 'project_tasks.project_id')
            ->where('projects.id', $id)
            ->where('master_comments.file', '!=', '')
            ->where('master_comments.type', '=', 'project_task')
            ->groupBy('master_comments.id')
            ->get();

        // $days_spent = ProjectTaskModel::select('actual_duration')
        //                 ->where('project_id', $id)
        //                 ->where('actual_duration', 'like', '%d')
        //                 ->get();

        // $hours_spent = ProjectTaskModel::select('actual_duration')
        //                 ->where('project_id', $id)
        //                 ->where('actual_duration', 'like', '%h')
        //                 ->get();

        // $taskByStatus = DB::select("SELECT COUNT(*) AS `num`, project_tasks.status FROM `project_tasks` where project_tasks.project_id = ".$id." GROUP BY `status` ORDER BY num DESC");

        // $taskByStatusData = array();
        // foreach ($taskByStatus as $statusview) {
        //   array_push($taskByStatusData,
        //     [
        //       'name'=>ucfirst($statusview->status),
        //       'y'=>$statusview->num,
        //     ]
        //   );
        // }
        // $taskByStatusData = json_encode($taskByStatusData);
        return view('admin.projects.show', compact('project', 'projects', 'new_tasks', 'duties', 'cat', 'ongoing_tasks', 'timespan', 'completed_tasks', 'page_title', 'page_description', 'project_files',  'stages', 'new_ongoing_completed', 'other_project_tasks_data', 'other_project_tasks', 'project_status', 'comments_files','projectTaskAttachment'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/projects/general.page.create.title'); // "Admin | Project | Create";
        $page_description = trans('admin/projects/general.page.create.description'); // "Creating a new client";

        $project = new \App\Models\Projects();
        $perms = $this->permission->all();
        $users = \App\User::where('enabled', '1')->pluck('username', 'id');
        $projects_cat = \App\Models\Projectscat::pluck('name', 'id');

        return view('admin.projects.create', compact('project', 'perms', 'page_title', 'page_description', 'users', 'projects_cat'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required',

        ]);

        $attributes = $request->all();

        $attributes['user_id'] = Auth::user()->id;
        $attributes['org_id'] = Auth::user()->org_id;
        if (!isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        $theTags = [];
        $staffs = [];
        $tags = explode(',', \Request::get('staffs'));
        foreach ($tags as $key => $value) {
            $dbtag = User::where('username', '=', $value)->first();

            if ($dbtag) {
                array_push($theTags, $dbtag->id);
                array_push($staffs, $value);
            }
        }
        $attributes['staffs'] = implode(',', $staffs);
        //dd($attributes);

        $project = $this->projects->create($attributes);

        foreach ($theTags as $k => $v) {
            $project_user = new ProjectUser();
            $project_user->project_id = $project->id;
            $project_user->user_id = $v;
            $project_user->save();
        }
        $projectgroups = ['new', 'ongoing', 'completed'];
        foreach ($projectgroups as $key => $value) {
            $groups[] = [
                'project_id' => $project->id,
                'enabled' => '1',
                'name' => $value,
                'description' => ucfirst($value) . ' Tasks',
            ];
        }
        \App\Models\ProjectTaskStatus::insert($groups);
        Audit::log(Auth::user()->id, trans('admin/projects/general.audit-log.category'), trans('admin/projects/general.audit-log.msg-store', ['name' => $request->name]));

        Flash::success(trans('admin/projects/general.status.created')); // 'Project successfully created');

        return redirect('/admin/projects');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $project = $this->projects->find($id);
        $peoples = ProjectUser::leftjoin('users', 'users.id', '=', 'project_users.user_id')
            ->where('project_users.project_id', $id)->pluck('users.username')->all();
        Audit::log(Auth::user()->id, trans('admin/projects/general.audit-log.category'), trans('admin/projects/general.audit-log.msg-edit', ['name' => $project->name]));

        $page_title = trans('admin/projects/general.page.edit.title'); // "Admin | Project | Edit";
        $page_description = trans('admin/projects/general.page.edit.description', ['name' => $project->name]); // "Editing client";
        $projects_cat = \App\Models\Projectscat::pluck('name', 'id');
        $users = \App\User::where('enabled', '1')->pluck('username', 'id');

        if (!$project->isEditable() && !$project->canChangePermissions()) {
            abort(403);
        }

        return view('admin.projects.edit', compact('project', 'users', 'page_title', 'page_description', 'projects_cat', 'peoples'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'      => 'required',

        ]);

        $attributes = $request->all();

        if (!isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        $projects = $this->projects->find($id);
        if ($projects->isEditable()) {
            $theTags = [];
            $staffs = [];
            $tags = explode(',', \Request::get('staffs'));
            foreach ($tags as $key => $value) {
                $dbtag = User::where('username', '=', $value)->first();

                if ($dbtag) {
                    array_push($theTags, $dbtag->id);
                    array_push($staffs, $value);
                }
            }
            $attributes['staffs'] = implode(',', $staffs);
            ProjectUser::where('project_id', $id)->delete();
            foreach ($theTags as $k => $v) {
                $project_user = new ProjectUser();
                $project_user->project_id = $id;
                $project_user->user_id = $v;
                $project_user->save();
            }
            $projects->update($attributes);
        }

        Audit::log(Auth::user()->id, trans('admin/projects/general.audit-log.category'), trans('admin/projects/general.audit-log.msg-update', ['name' => $projects->name]));

        Flash::success(trans('admin/projects/general.status.updated')); // 'Project successfully updated');

        return redirect('/admin/projects');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $projects = $this->projects->find($id);

        //    / dd($projects);

        if (!$projects->isdeletable()) {
            abort(403);
        }


        $no_of_tasks = ProjectTaskModel::where('project_id', $id)->get();

        // dd($no_of_tasks);
        if (count($no_of_tasks) > 0) {
            Flash::error('Task Of Project Exists So cannot Delete Project');
            return redirect()->back();
        }
        ProjectUser::where('project_id', $id)->delete();


        \App\Models\ProjectTaskStatus::where('project_id', $id)->delete();
        Audit::log(Auth::user()->id, trans('admin/projects/general.audit-log.category'), trans('admin/projects/general.audit-log.msg-destroy', ['name' => $projects->name]));

        $projects->delete($id);

        Flash::success(trans('admin/projects/general.status.deleted')); // 'Project successfully deleted');

        return redirect('/admin/projects');
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

        $projects = $this->projects->find($id);

        if (!$projects->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/projects/dialog.delete-confirm.title');

        $projects = $this->projects->find($id);
        $modal_route = route('admin.projects.delete', ['leadId' => $projects->id]);

        $modal_body = trans('admin/projects/dialog.delete-confirm.body', ['id' => $projects->id, 'name' => $projects->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $projects = $this->projects->find($id);

        Audit::log(Auth::user()->id, trans('admin/projects/general.audit-log.category'), trans('admin/projects/general.audit-log.msg-enable', ['name' => $projects->name]));

        $projects->enabled = true;
        $projects->save();

        Flash::success(trans('admin/projects/general.status.enabled'));

        return redirect('/admin/projects');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $projects = $this->projects->find($id);

        Audit::log(Auth::user()->id, trans('admin/projects/general.audit-log.category'), trans('admin/projects/general.audit-log.msg-disabled', ['name' => $projects->name]));

        $projects->enabled = false;
        $projects->save();

        Flash::success(trans('admin/projects/general.status.disabled'));

        return redirect('/admin/projects');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkprojects = $request->input('chkProject');

        Audit::log(Auth::user()->id, trans('admin/projects/general.audit-log.category'), trans('admin/projects/general.audit-log.msg-enabled-selected'), $chkprojects);

        if (isset($chkprojects)) {
            foreach ($chkprojects as $projects_id) {
                $projects = $this->projects->find($projects_id);
                $projects->enabled = true;
                $projects->save();
            }
            Flash::success(trans('admin/projects/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/projects/general.status.no-client-selected'));
        }

        return redirect('/admin/projects');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkprojects = $request->input('chkProject');

        Audit::log(Auth::user()->id, trans('admin/projects/general.audit-log.category'), trans('admin/projects/general.audit-log.msg-disabled-selected'), $chkprojects);

        if (isset($chkprojects)) {
            foreach ($chkprojects as $projects_id) {
                $projects = $this->projects->find($projects_id);
                $projects->enabled = false;
                $projects->save();
            }
            Flash::success(trans('admin/projects/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/projects/general.status.no-client-selected'));
        }

        return redirect('/admin/projects');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $projects = $this->projects->pushCriteria(new projectsWhereDisplayNameLike($query))->all();

        foreach ($projects as $projects) {
            $id = $projects->id;
            $name = $projects->name;
            $email = $projects->email;

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
        $projects = $this->projects->find($id);

        return $projects;
    }

    public function get_client()
    {
        $term = strtolower(\Request::get('term'));
        $contacts = ProjectModel::select('id', 'name')->where('name', 'LIKE', '%' . $term . '%')->where('enabled', '1')->groupBy('name')->take(5)->get();
        $return_array = [];

        foreach ($contacts as $v) {
            if (strpos(strtolower($v->name), $term) !== false) {
                $return_array[] = ['value' => $v->name, 'id' => $v->id];
            }
        }

        return Response::json($return_array);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function projectTaskByUser($userId)
    {
        $page_title = 'Admin | Project | Tasks';
        $page_description = 'Project Tasks related to <strong>' . \TaskHelper::getUserName($userId) . '</strong>';

        $task_assigned = ProjectTaskModel::join('project_task_user', 'project_task_user.project_task_id', '=', 'project_tasks.id')
            ->where('project_task_user.user_id', $userId)
            ->where('project_tasks.enabled', '1')
            ->where('project_tasks.status', '!=', 'completed')
            ->orderBy('project_tasks.id', 'DESC')
            ->get();
        //dd($task_assigned);

        return view('admin.projects.projectTaskByUser', compact('page_title', 'page_description', 'task_assigned'));
    }

    public function projectTaskByStatus($status)
    {
        $page_title = 'Admin | Project | Tasks | Status';
        $page_description = 'Project Tasks status <strong>' . ucfirst($status) . '</strong>';

        if (Auth::user()->hasRole('admins')) {
            $tasks = ProjectTaskModel::where('status', $status)->orderBy('created_at', DESC)->get();
        } else {
            $tasks = ProjectTaskModel::where('status', $status)
                ->where('user_id', Auth::user()->id)
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('admin.projects.projectTaskByStatus', compact('page_title', 'page_description', 'tasks'));
    }

     public function filterbydate(Request $request){

        //dd($request->all());

        $start_date = $request->start_date;
        $pid =  $request->pid;
        $end_date = $request->end_date;

        $project_name =  \App\Models\Projects::find($pid)->name;

    
        //dd($start_date);
        // dd($request->all());
        $filterLocation = function($query){

            if(\Request::get('location')){

                return $query->where('location',\Request::get('location'));
            }
        };
        $category_id = \App\Models\ProjectTask::select('category_id')
                                ->where('project_id',$pid)
                                ->where('start_date','>=',$start_date)
                                ->where('start_date','<=',$end_date)
                                ->where(function($query) use ($filterLocation){
                                    $filterLocation($query);
                                })
                                ->distinct('category_id')
                                ->orderBy('category_id','asc')
                                ->get();

         $data = [];
         $count_task = [];
         $attachments = [];

         foreach($category_id as $val){


            $category_groups = \App\Models\ProjectTask::where('category_id',$val->category_id)
                                              ->where('project_id',$pid)
                                              ->where('start_date','>=',$start_date)
                                              ->where('start_date','<=',$end_date)
                                              ->where(function($query) use ($filterLocation){
                                                    $filterLocation($query);
                                                })
                                              ->get()->groupBy('sub_cat_id');

            $total_task = \App\Models\ProjectTask::where('category_id',$val->category_id)
                                              ->where('project_id',$pid)
                                              ->where('start_date','>=',$start_date)
                                              ->where('start_date','<=',$end_date)
                                              ->where(function($query) use ($filterLocation){
                                                     $filterLocation($query);
                                                 })
                                              ->count();



               
                if(count($category_groups) > 0){

                  array_push($data,$category_groups);
                  $count_task [] = $total_task;
                  $att = \App\Models\ProjectTask::where('category_id',$val->category_id)
                                                  ->where('project_id',$pid)
                                                  ->where('start_date','>=',$start_date)
                                                  ->where('start_date','<=',$end_date)
                                                  ->where('attachment','!=','')
                                                  ->where(function($query) use ($filterLocation){
                                                            $filterLocation($query);
                                                    })
                                                  ->get();
                  if(count($att) > 0)
                    $attachment [$val->category->name] = array_chunk($att->toArray(),3);
                }
          }


        
          if(\Request::ajax()){
        
              $html = \view('pdf.monthly_report_word',compact('data','start_date','end_date','project_name','attachment','count_task'))->render();
              return response()->json(['status'=>'success', 'html'=>$html], 200);
          }

      
          
          if($request->download_lite == "Lite"){
        
             $pdf = \PDF::loadView('pdf.monthly_report_lite', compact('data','start_date','end_date','project_name','attachment','count_task'));  
                $file = date('Y-m-d').' ISPL Lite Report.pdf';
                if (\File::exists('reports/'.$file))
                {
                  \File::Delete('reports/'.$file);    
                }
                return $pdf->download($file);   


          }else{
    //dd($data);
            //dd('done');

             $pdf = \PDF::loadView('pdf.monthly_report', compact('data','start_date','end_date','project_name','attachment','count_task'));
             // return view('pdf.monthly_report', compact('data','start_date','end_date','project_name','attachment','count_task'));  
            $file = date('Y-m-d').' ISPL Report.pdf';
            if (\File::exists('reports/'.$file))
            {
              \File::Delete('reports/'.$file);
            }
            //dd($data);
            return $pdf->download($file);
          }
 
           

        }
           

    public function openactivities($id)
    {
        $index_start = \Request::get('page') ? (\Request::get('page') - 1) * 50 + 1 : '1';
        $activities = \App\Models\ProjectTaskActivity::select('project_task_activities.*', 'project_tasks.subject')
            ->leftjoin('project_tasks', 'project_tasks.id', '=', 'project_task_activities.task_id')
            ->where('project_tasks.project_id', $id)
            ->orderBy('project_task_activities.id', 'desc')
            ->paginate(50);

        return view('admin.projects.taskactivities', compact('activities', 'index_start'));
    }

    // public function creategroup($id){

    // }
    // public function creategroupform($id){

    // }
    // public function storegroupform(Request $request,$id){

    // }
}
