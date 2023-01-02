<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\MasterComments;
use App\Models\Projects;
use App\Models\ProjectTask as ProjectTask;
use App\Models\ProjectTask as ProjectTaskModel;
use App\Models\ProjectTaskCategory;
use App\Models\Role as Permission;
use App\User;
use Carbon\Carbon;
use Flash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as Files;
use Illuminate\Support\Facades\Mail;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class ProjectTaskController extends Controller
{
    /**
     * @var ProjectTask
     */
    private $projectTask;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param ProjectTask $projectTask
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(ProjectTask $projectTask, Permission $permission)
    {
        parent::__construct();
        $this->projectTask = $projectTask;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {


       



        $projects_tasks = ProjectTaskModel::orderBy('id', 'desc')
            ->where('org_id',\Auth::user()->org_id)
            ->where(function ($query) {
                if (\Request::get('start_date') != '' && \Request::get('end_date') != '') {
                    return $query->whereBetween('created_at', [\Request::get('start_date'), \Request::get('end_date')]);
                }
            })
            ->where(function ($query) {
                if (\Request::get('user_id') && \Request::get('user_id') != '') {
                    $user_id = \Request::get('user_id');

                    return $query->where('user_id', $user_id);
                }
            })
            ->where(function ($query) {
                if (\Request::get('status_id') && \Request::get('status_id') != '') {
                    return $query->where('status', \Request::get('status_id'));
                }else{
                    return $query->where('status', '!=', 'completed');
                }
            })->where(function($query) {
                if(!\Auth::user()->hasRole('admins')){
                    $project_task_user = \App\Models\ProjectTaskUser::where('user_id',\Auth::user()->id)->pluck('project_task_id')->toArray();
                    return $query->whereIn('id',$project_task_user);
                }
            })

            ->paginate(30);



        Audit::log(Auth::user()->id, trans('admin/leads/general.audit-log.category'), trans('admin/leads/general.audit-log.msg-index'));

        $courses = \App\Models\Product::where('enabled', '1')->pluck('name', 'id')->all();
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id')->all();
        $lead_status = \App\Models\Leadstatus::where('enabled', '1')->pluck('name', 'id')->all();
        $total_target_amount = DB::table('leads')->select('total_target_amount')->where('lead_type_id', '1')
            ->select(DB::raw('SUM(price_value) as total_target_amount'))->get();
        $total_lead_amount = DB::table('leads')->where('lead_type_id', '2')
            ->select(DB::raw('SUM(price_value) as total_lead_amount'))->get();

        if (!null == \Request::get('type')) {
            $type = ucfirst(\Request::get('type'));
        } else {
            $type = 'Lead';
        }

        $page_title = 'Project | Tasks';
        $page_description = 'List of Project Tasks ';

        $total_enquiry = \App\Models\Lead::count();
        $target_enquiry = \App\Models\Lead::where(['lead_type_id' => 1])->count();
        $lead_enquiry = \App\Models\Lead::where(['lead_type_id' => 2])->count();
        $qualified_enquiry = \App\Models\Lead::where(['lead_type_id' => 3])->count();
    
        return view('admin.project_task.index', compact( 'courses', 'users', 'projects_tasks', 'lead_status', 'page_title', 'page_description', 'total_target_amount', 'total_lead_amount', 'total_enquiry', 'qualified_enquiry', 'lead_enquiry', 'target_enquiry'));
    }

    public function create($projectId)
    {
        Audit::log(Auth::user()->id, trans('admin/project-task/general.audit-log.category'), trans('admin/project-task/general.audit-log.msg-show', ['subject' => $task->subject]));

        $page_title = 'Admin | Task | Create';
        $page_description = 'Createing new task in :project';
        $cat = ProjectTaskCategory::orderBy('name', 'ASC')->pluck('name', 'id');
        $project_status = ['new', 'ongoing', 'completed'];
        $other_status = \App\Models\ProjectTaskStatus::select('name', 'description')->whereNotIn('name', $project_status)->where('project_id', $id)->where('enabled', '1')->get();
        $pid = \Request::get('pid');
        $project_status = ['new' => 'New', 'ongoing' => 'Ongoing', 'completed' => 'Completed'];
        $other_status = \App\Models\ProjectTaskStatus::whereNotIn('name', $project_status)->where('project_id', $pid)->get();
        foreach ($other_status as $key => $value) {
            $project_status[$value->name] = ucfirst($value->name);
        }

        return view('admin.project_task.create', compact('task', 'project_status', 'page_title', 'page_description', 'cat', 'projectId'));
    }

    public function createGlobal()
    {
        Audit::log(Auth::user()->id, trans('admin/project-task/general.audit-log.category'), trans('admin/project-task/general.audit-log.msg-show', ['subject' => $task->subject]));

        $page_title = 'Admin | Task | Create';
        $page_description = 'Createing new task in :project';
        $cat = ProjectTaskCategory::orderBy('name', 'ASC')->pluck('name', 'id');
        $projects = Projects::orderBy('name', 'ASC')->pluck('name', 'id')->all();
        $pid = \Request::get('pid');
        $project_status = ['new' => 'New', 'ongoing' => 'Ongoing', 'completed' => 'Completed'];
        $other_status = \App\Models\ProjectTaskStatus::whereNotIn('name', $project_status)->where('project_id', $pid)->where('enabled', '1')->get();
        foreach ($other_status as $key => $value) {
            $project_status[$value->name] = ucfirst($value->name);
        }

        return view('admin.project_task.createglobal', compact('task', 'page_title', 'page_description', 'cat', 'projects', 'pid', 'project_status'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {

        try {
            $task = ProjectTaskModel::findOrFail($id);
            $peoples = \App\Models\ProjectTaskUser::leftjoin('users', 'users.id', '=', 'project_task_user.user_id')
                ->where('project_task_user.project_task_id', $id)->pluck('users.username')->all();

            if (!Auth::user()->hasRole('admins')) {

                if (!in_array(Auth::user()->username, $peoples)) {
                    abort(404);
                }
            }

            $project_status = ['new' => 'New', 'ongoing' => 'Ongoing', 'completed' => 'Completed']; //default status
            $other_status = \App\Models\ProjectTaskStatus::whereNotIn('name', $project_status)->where('project_id', $task->project_id)->get();
            foreach ($other_status as $key => $value) {
                $project_status[$value->name] = ucfirst($value->name);
            }

            Audit::log(Auth::user()->id, trans('admin/project-task/general.audit-log.category'), trans('admin/project-task/general.audit-log.msg-show', ['subject' => $task->subject]));

            $page_title = trans('admin/project-task/general.page.show.title'); // "Admin | Project | Show";
            $page_description = 'Project Name:' . $task->project->name; // "Displaying client: :name";
            //  dd($page_description);
            $comments = MasterComments::where('type', 'project_task')->where('master_id', $id)->get();
            $cat = DB::select('select id as value ,name AS text from project_task_categories');
            $projects = \App\Models\Projects::select('name as text', 'id as value')->get();
            $task_attachments = \App\Models\ProjectTaskAttachment::where('task_id', $id)->get();
            $sub_cat = \App\Models\TaskSubCat::select('name as text','id as value')->where('task_cat_id',$task->category_id)->get();
            //dd("OK");
            return view('admin.project_task.show', compact('task', 'page_title', 'page_description', 'comments', 'cat', 'projects', 'project_status', 'peoples', 'task_attachments','sub_cat'));
        } catch (ModelNotFoundException $e) {
            Flash::warning('Invalid Task Id');

            return redirect()->back();
        }
    }

  
    public function getScheduleDate($start_date,$enddate,$days){

        $nextdate =  date('Y-m-d', strtotime($start_date. " + {$days} days"));

        if(strtotime($nextdate) > strtotime($enddate) ){

            return ['hasNext'=>false,'nextDate'=>false];
        }
        return ['hasNext'=>true,'nextDate'=>$nextdate];

    }


    public function scheduleTaskAndSave($attributes,$days){

        $actualStart = $attributes['start_date'];
        $actualEnd = $attributes['end_date'];
        do{
            $attributes['start_date'] = $actualStart;
            $attributes['end_date'] = $actualStart;
            $nextdates = $this->getScheduleDate($actualStart,$actualEnd,$days);
            $this->saveProjectTask($attributes);
            $d[] = $actualStart;
            $hasNext = $nextdates['hasNext'];
            $actualStart =$nextdates['nextDate'];
            
        }while($hasNext);
      

        return true;

    } 
    public function saveProjectTask($attributes){

        $theTags = [];
        $peoples = [];
        $tags = explode(',', \Request::get('peoples'));
        foreach ($tags as $key => $value) {
            $dbtag = User::where('username', '=', $value)->first();

            if ($dbtag) {
                array_push($theTags, $dbtag->id);
                array_push($peoples, $value);
            }
        }

        $projectTask = $this->projectTask->create($attributes);
        // Save the attachment data and file
        foreach ($attributes['attachments'] as $key => $doc_) {
            if ($doc_) {
                $doc_name = time() . '' . $doc_->getClientOriginalName();
                $destinationPath = public_path('/task_attachments/');
                $doc_->move($destinationPath, $doc_name);
                $task_attachment = ['task_id' => $projectTask->id, 'attachment' => $doc_name, 'user_id' => \Auth::user()->id];
                \App\Models\ProjectTaskAttachment::create($task_attachment);
            }
        }

        $temp = $projectTask->tags()->sync($theTags);
        
    }   

    public function getTimeStampDays($type){

        switch ($type) {
            case 'weekly':
                # code...
                return 7;
            case 'monthly':
                return 30;
            case 'twice_a_month':
                return 15;
            case 'three_months':
                return 90;
            case 'six_months':
                return 180;
            case 'two_months':
                return 60;
            case 'yearly':
                return 365;
            default: 
                Flash::error("Please Provied validate date");
                abort(403);
        }




    }


        public function storeGlobal(Request $request)
    {


        $attributes = $request->all();
        //dd($attributes);
        $projectId = $attributes['project_id'];
        $attributes['user_id'] = Auth::user()->id;
        $attributes['org_id'] = Auth::user()->org_id;
        $attributes['schedule_type']= $request->schedule;

        if (!isset($attributes['enabled'])) {
            $attributes['enabled'] = 1;
        }

        if (!isset($attributes['milestone'])) {
            $attributes['milestone'] = 0;
        }
       
        $scheduleTime = $request->schedule;
        if($scheduleTime && $request->timespan){
            Flash::success("TASK CREATED AND SCHEDULED {$request->timespan}") ;
            $days = $this->getTimeStampDays($request->timespan);
            $this->scheduleTaskAndSave($attributes,$days);

        }else{
            Flash::success("TASK CREATED SUCCESSFULLY") ;
            $this->saveProjectTask($attributes);
        }
            


        Audit::log(Auth::user()->id, trans('admin/project-task/general.audit-log.category'), trans('admin/project-task/general.audit-log.msg-store', ['subject' => $attributes['subject']]));

        Flash::success('ProjectTask successfully created'); // 'ProjectTask successfully created');

        return redirect()->route('admin.projects.show', $projectId);
        
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $projectId)
    {
        $attributes = $request->all();

        //dd($attributes);
        $attributes['user_id'] = Auth::user()->id;
         $attributes['org_id'] = Auth::user()->org_id;
        if (!isset($attributes['enabled'])) {
            $attributes['enabled'] = 1;
        }

        if (!isset($attributes['milestone'])) {
            $attributes['milestone'] = 0;
        }

        $attributes['status'] = 'new';

        $scheduleTime = $request->schedule;

        if ($scheduleTime == 'on') {
            $todaydate = date('Y-m-d');
            if ($request->timespan == 'weekly') {
                $date = $request->end_date;
                $datework = new Carbon($date);
                $now = Carbon::now();
                $weekly = $datework->diffInWeeks($now);

                // $d = new DateTime($date);
                // $timespan=$d->format('l');

                $timespan = Carbon::now();

                $attributes['timespan'] = $timespan;

                $theTags = [];
                $peoples = [];
                $tags = explode(',', \Request::get('peoples'));

                foreach ($tags as $key => $value) {
                    $dbtag = User::where('username', '=', $value)->first();

                    if ($dbtag) {
                        array_push($theTags, $dbtag->id);
                        array_push($peoples, $value);
                    }
                }

                // Save the attachment data and file
                if ($request->file('attachment')) {
                    $stamp = date('Ymdhis') . '_';
                    $file = $request->file('attachment');
                    $destinationPath = public_path() . '/task_attachments/';
                    $filename = $file->getClientOriginalName();
                    $attributes['attachment'] = $stamp . $filename;
                    $request->file('attachment')->move($destinationPath, $stamp . $filename);
                }

                $projectTask = $this->projectTask->create($attributes);

                $temp = $projectTask->tags()->sync($theTags);

                for ($i = 0; $i < $weekly; $i++) {
                    $attributes['timespan'] = $timespan;
                    $timespan = $timespan->adddays(7);
                    $theTags = [];
                    $peoples = [];
                    $tags = explode(',', \Request::get('peoples'));
                    foreach ($tags as $key => $value) {
                        $dbtag = User::where('username', '=', $value)->first();

                        if ($dbtag) {
                            array_push($theTags, $dbtag->id);
                            array_push($peoples, $value);
                        }
                    }

                    // Save the attachment data and file
                    if ($request->file('attachment')) {
                        $stamp = date('Ymdhis') . '_';
                        $file = $request->file('attachment');
                        $destinationPath = public_path() . '/task_attachments/';
                        $filename = $file->getClientOriginalName();
                        $attributes['attachment'] = $stamp . $filename;
                        $request->file('attachment')->move($destinationPath, $stamp . $filename);
                    }

                    $projectTask = $this->projectTask->create($attributes);

                    $temp = $projectTask->tags()->sync($theTags);
                }
            }

            if ($request->timespan == 'monthly') {
                $date = $request->end_date;
                $datework = new Carbon($date);
                $now = Carbon::now();
                $monthly = $datework->diffInMonths($now);

                // $d = new DateTime($date);
                // $timespan=$d->format('l');
                $timespan = Carbon::now();

                $attributes['timespan'] = $timespan;

                $theTags = [];
                $peoples = [];
                $tags = explode(',', \Request::get('peoples'));
                foreach ($tags as $key => $value) {
                    $dbtag = User::where('username', '=', $value)->first();

                    if ($dbtag) {
                        array_push($theTags, $dbtag->id);
                        array_push($peoples, $value);
                    }
                }
                $attributes['peoples'] = implode(',', $peoples);

                // Save the attachment data and file
                if ($request->file('attachment')) {
                    $stamp = date('Ymdhis') . '_';
                    $file = $request->file('attachment');
                    $destinationPath = public_path() . '/task_attachments/';
                    $filename = $file->getClientOriginalName();
                    $attributes['attachment'] = $stamp . $filename;
                    $request->file('attachment')->move($destinationPath, $stamp . $filename);
                }

                $projectTask = $this->projectTask->create($attributes);
                $temp = $projectTask->tags()->sync($theTags);

                for ($i = 0; $i < $monthly; $i++) {
                    $attributes['timespan'] = $timespan;
                    $timespan = $timespan->addMonths(1);
                    $theTags = [];
                    $peoples = [];
                    $tags = explode(',', \Request::get('peoples'));
                    foreach ($tags as $key => $value) {
                        $dbtag = User::where('username', '=', $value)->first();

                        if ($dbtag) {
                            array_push($theTags, $dbtag->id);
                            array_push($peoples, $value);
                        }
                    }
                    $attributes['peoples'] = implode(',', $peoples);

                    // Save the attachment data and file
                    if ($request->file('attachment')) {
                        $stamp = date('Ymdhis') . '_';
                        $file = $request->file('attachment');
                        $destinationPath = public_path() . '/task_attachments/';
                        $filename = $file->getClientOriginalName();
                        $attributes['attachment'] = $stamp . $filename;
                        $request->file('attachment')->move($destinationPath, $stamp . $filename);
                    }

                    $projectTask = $this->projectTask->create($attributes);
                    $temp = $projectTask->tags()->sync($theTags);
                }
            }
            if ($request->timespan == 'twice_a_month') {
                $date = $request->end_date;
                $datework = new Carbon($date);
                $now = Carbon::now();
                $weeks = $datework->diffInWeeks($now);

                $weeks = $weeks / 2;

                // $d = new DateTime($date);
                // $timespan=$d->format('l');

                $timespan = Carbon::now();

                $attributes['timespan'] = $timespan;

                $theTags = [];
                $peoples = [];
                $tags = explode(',', \Request::get('peoples'));
                foreach ($tags as $key => $value) {
                    $dbtag = User::where('username', '=', $value)->first();
                    if ($dbtag) {
                        array_push($theTags, $dbtag->id);
                        array_push($peoples, $value);
                    }
                }

                // Save the attachment data and file
                if ($request->file('attachment')) {
                    $stamp = date('Ymdhis') . '_';
                    $file = $request->file('attachment');
                    $destinationPath = public_path() . '/task_attachments/';
                    $filename = $file->getClientOriginalName();
                    $attributes['attachment'] = $stamp . $filename;
                    $request->file('attachment')->move($destinationPath, $stamp . $filename);
                }

                $projectTask = $this->projectTask->create($attributes);
                $temp = $projectTask->tags()->sync($theTags);

                for ($i = 0; $i < $weeks; $i++) {
                    $attributes['timespan'] = $timespan;
                    $timespan = $timespan->adddays(14);
                    $theTags = [];
                    $peoples = [];
                    $tags = explode(',', \Request::get('peoples'));
                    foreach ($tags as $key => $value) {
                        $dbtag = User::where('username', '=', $value)->first();

                        if ($dbtag) {
                            array_push($theTags, $dbtag->id);
                            array_push($peoples, $value);
                        }
                    }
                    $attributes['peoples'] = implode(',', $peoples);

                    // Save the attachment data and file
                    if ($request->file('attachment')) {
                        $stamp = date('Ymdhis') . '_';
                        $file = $request->file('attachment');
                        $destinationPath = public_path() . '/task_attachments/';
                        $filename = $file->getClientOriginalName();
                        $attributes['attachment'] = $stamp . $filename;
                        $request->file('attachment')->move($destinationPath, $stamp . $filename);
                    }

                    $projectTask = $this->projectTask->create($attributes);
                    $temp = $projectTask->tags()->sync($theTags);
                }
            }
            if ($request->timespan == 'three_months') {
                $date = $request->end_date;
                $datework = new Carbon($date);
                $now = Carbon::now();
                $monthly = $datework->diffInMonths($now);
                $monthly = $monthly / 3;
                // $d = new DateTime($date);
                // $timespan=$d->format('l');

                $timespan = Carbon::now();

                $attributes['timespan'] = $timespan;

                $theTags = [];
                $peoples = [];
                $tags = explode(',', \Request::get('peoples'));
                foreach ($tags as $key => $value) {
                    $dbtag = User::where('username', '=', $value)->first();

                    if ($dbtag) {
                        array_push($theTags, $dbtag->id);
                        array_push($peoples, $value);
                    }
                }
                $attributes['peoples'] = implode(',', $peoples);

                // Save the attachment data and file
                if ($request->file('attachment')) {
                    $stamp = date('Ymdhis') . '_';
                    $file = $request->file('attachment');
                    $destinationPath = public_path() . '/task_attachments/';
                    $filename = $file->getClientOriginalName();
                    $attributes['attachment'] = $stamp . $filename;
                    $request->file('attachment')->move($destinationPath, $stamp . $filename);
                }

                $projectTask = $this->projectTask->create($attributes);
                $temp = $projectTask->tags()->sync($theTags);

                for ($i = 0; $i < $monthly; $i++) {
                    $attributes['timespan'] = $timespan;
                    $timespan = $timespan->addMonths(3);
                    $theTags = [];
                    $peoples = [];
                    $tags = explode(',', \Request::get('peoples'));
                    foreach ($tags as $key => $value) {
                        $dbtag = User::where('username', '=', $value)->first();

                        if ($dbtag) {
                            array_push($theTags, $dbtag->id);
                            array_push($peoples, $value);
                        }
                    }

                    // Save the attachment data and file
                    if ($request->file('attachment')) {
                        $stamp = date('Ymdhis') . '_';
                        $file = $request->file('attachment');
                        $destinationPath = public_path() . '/task_attachments/';
                        $filename = $file->getClientOriginalName();
                        $attributes['attachment'] = $stamp . $filename;
                        $request->file('attachment')->move($destinationPath, $stamp . $filename);
                    }

                    $projectTask = $this->projectTask->create($attributes);
                    $temp = $projectTask->tags()->sync($theTags);
                }
            }
            if ($request->timespan == 'six_months') {
                $date = $request->end_date;
                $datework = new Carbon($date);
                $now = Carbon::now();
                $monthly = $datework->diffInWeeks($now);
                $monthly = $monthly / 6;
                // $d = new DateTime($date);
                // $timespan=$d->format('l');

                $timespan = Carbon::now();

                $attributes['timespan'] = $timespan;

                $theTags = [];
                $peoples = [];
                $tags = explode(',', \Request::get('peoples'));
                foreach ($tags as $key => $value) {
                    $dbtag = User::where('username', '=', $value)->first();

                    if ($dbtag) {
                        array_push($theTags, $dbtag->id);
                        array_push($peoples, $value);
                    }
                }

                // Save the attachment data and file
                if ($request->file('attachment')) {
                    $stamp = date('Ymdhis') . '_';
                    $file = $request->file('attachment');
                    $destinationPath = public_path() . '/task_attachments/';
                    $filename = $file->getClientOriginalName();
                    $attributes['attachment'] = $stamp . $filename;
                    $request->file('attachment')->move($destinationPath, $stamp . $filename);
                }

                $projectTask = $this->projectTask->create($attributes);
                $temp = $projectTask->tags()->sync($theTags);

                for ($i = 0; $i < $monthly; $i++) {
                    $attributes['timespan'] = $timespan;
                    $timespan = $timespan->addMonths(6);
                    $theTags = [];
                    $peoples = [];
                    $tags = explode(',', \Request::get('peoples'));
                    foreach ($tags as $key => $value) {
                        $dbtag = User::where('username', '=', $value)->first();

                        if ($dbtag) {
                            array_push($theTags, $dbtag->id);
                            array_push($peoples, $value);
                        }
                    }

                    // Save the attachment data and file
                    if ($request->file('attachment')) {
                        $stamp = date('Ymdhis') . '_';
                        $file = $request->file('attachment');
                        $destinationPath = public_path() . '/task_attachments/';
                        $filename = $file->getClientOriginalName();
                        $attributes['attachment'] = $stamp . $filename;
                        $request->file('attachment')->move($destinationPath, $stamp . $filename);
                    }

                    $projectTask = $this->projectTask->create($attributes);
                    $temp = $projectTask->tags()->sync($theTags);
                }
            }
            if ($request->timespan == 'two_months') {
                $date = $request->end_date;
                $datework = new Carbon($date);
                $now = Carbon::now();
                $monthly = $datework->diffInMonths($now);
                $monthly = $monthly / 2;
                // $d = new DateTime($date);
                // $timespan=$d->format('l');

                $timespan = Carbon::now();
                $attributes['timespan'] = $timespan;

                $theTags = [];
                $peoples = [];
                $tags = explode(',', \Request::get('peoples'));
                foreach ($tags as $key => $value) {
                    $dbtag = User::where('username', '=', $value)->first();

                    if ($dbtag) {
                        array_push($theTags, $dbtag->id);
                        array_push($peoples, $value);
                    }
                }

                // Save the attachment data and file
                if ($request->file('attachment')) {
                    $stamp = date('Ymdhis') . '_';
                    $file = $request->file('attachment');
                    $destinationPath = public_path() . '/task_attachments/';
                    $filename = $file->getClientOriginalName();
                    $attributes['attachment'] = $stamp . $filename;
                    $request->file('attachment')->move($destinationPath, $stamp . $filename);
                }

                $projectTask = $this->projectTask->create($attributes);
                $temp = $projectTask->tags()->sync($theTags);

                for ($i = 0; $i < $monthly; $i++) {
                    $attributes['timespan'] = $timespan;
                    $timespan = $timespan->addMonths(2);
                    $theTags = [];
                    $peoples = [];
                    $tags = explode(',', \Request::get('peoples'));
                    foreach ($tags as $key => $value) {
                        $dbtag = User::where('username', '=', $value)->first();

                        if ($dbtag) {
                            array_push($theTags, $dbtag->id);
                            array_push($peoples, $value);
                        }
                    }
                    $attributes['peoples'] = implode(',', $peoples);

                    // Save the attachment data and file
                    if ($request->file('attachment')) {
                        $stamp = date('Ymdhis') . '_';
                        $file = $request->file('attachment');
                        $destinationPath = public_path() . '/task_attachments/';
                        $filename = $file->getClientOriginalName();
                        $attributes['attachment'] = $stamp . $filename;
                        $request->file('attachment')->move($destinationPath, $stamp . $filename);
                    }

                    $projectTask = $this->projectTask->create($attributes);
                    $temp = $projectTask->tags()->sync($theTags);
                }
            }
            if ($request->timespan == 'yearly') {
                $date = $request->end_date;
                $datework = new Carbon($date);
                $now = Carbon::now();
                $years = $datework->diffInYears($now);

                // $d = new DateTime($date);
                // $timespan=$d->format('l');
                $timespan = Carbon::now();

                $attributes['timespan'] = $timespan;
                $theTags = [];
                $peoples = [];
                $tags = explode(',', \Request::get('peoples'));
                foreach ($tags as $key => $value) {
                    $dbtag = User::where('username', '=', $value)->first();

                    if ($dbtag) {
                        array_push($theTags, $dbtag->id);
                        array_push($peoples, $value);
                    }
                }
                $attributes['peoples'] = implode(',', $peoples);

                // Save the attachment data and file
                if ($request->file('attachment')) {
                    $stamp = date('Ymdhis') . '_';
                    $file = $request->file('attachment');
                    $destinationPath = public_path() . '/task_attachments/';
                    $filename = $file->getClientOriginalName();
                    $attributes['attachment'] = $stamp . $filename;
                    $request->file('attachment')->move($destinationPath, $stamp . $filename);
                }

                $projectTask = $this->projectTask->create($attributes);
                $temp = $projectTask->tags()->sync($theTags);

                for ($i = 0; $i < $years; $i++) {
                    $attributes['timespan'] = $timespan;
                    $timespan = $timespan->addYears(1);
                    $theTags = [];
                    $peoples = [];
                    $tags = explode(',', \Request::get('peoples'));
                    foreach ($tags as $key => $value) {
                        $dbtag = User::where('username', '=', $value)->first();

                        if ($dbtag) {
                            array_push($theTags, $dbtag->id);
                            array_push($peoples, $value);
                        }
                    }

                    // Save the attachment data and file
                    if ($request->file('attachment')) {
                        $stamp = date('Ymdhis') . '_';
                        $file = $request->file('attachment');
                        $destinationPath = public_path() . '/task_attachments/';
                        $filename = $file->getClientOriginalName();
                        $attributes['attachment'] = $stamp . $filename;
                        $request->file('attachment')->move($destinationPath, $stamp . $filename);
                    }

                    $projectTask = $this->projectTask->create($attributes);
                    $temp = $projectTask->tags()->sync($theTags);
                }
            }
        } else {
            $theTags = [];
            $peoples = [];
            $tags = explode(',', \Request::get('peoples'));
            foreach ($tags as $key => $value) {
                $dbtag = User::where('username', '=', $value)->first();

                if ($dbtag) {
                    array_push($theTags, $dbtag->id);
                    array_push($peoples, $value);
                }
            }

            // Save the attachment data and file
            if ($request->file('attachment')) {
                $stamp = date('Ymdhis') . '_';
                $file = $request->file('attachment');
                $destinationPath = public_path() . '/task_attachments/';
                $filename = $file->getClientOriginalName();
                $attributes['attachment'] = $stamp . $filename;
                $request->file('attachment')->move($destinationPath, $stamp . $filename);
            }

            $projectTask = $this->projectTask->create($attributes);

            $temp = $projectTask->tags()->sync($theTags);
        }

        Audit::log(Auth::user()->id, trans('admin/project-task/general.audit-log.category'), trans('admin/project-task/general.audit-log.msg-store', ['subject' => $attributes['subject']]));

        Flash::success(trans('admin/project-task/general.status.created')); // 'ProjectTask successfully created');

        return redirect()->route('admin.projects.show', $projectId);
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $task = $this->projectTask->find($id);
        $peoples = \App\Models\ProjectTaskUser::leftjoin('users', 'users.id', '=', 'project_task_user.user_id')
            ->where('project_task_id', $id)->pluck('users.username')->all();
        Audit::log(Auth::user()->id, trans('admin/project-task/general.audit-log.category'), trans('admin/project-task/general.audit-log.msg-show', ['subject' => $task->subject]));

        $page_title = trans('admin/project-task/general.page.show.title'); // "Admin | Project | Show";
        $page_description = trans('admin/project-task/general.page.show.description'); // "Displaying client: :name";
        $cat = ProjectTaskCategory::orderBy('name', 'ASC')->pluck('name', 'id');
        
        if (!$task->isEditable() && !$task->canChangePermissions()) {
            abort(403);
        }
        $task_attachments = \App\Models\ProjectTaskAttachment::where('task_id', $id)->get();
        //dd($task_attachments);
        $projects = Projects::orderBy('name', 'ASC')->pluck('name', 'id')->all();
         $sub_cat = \App\Models\TaskSubCat::orderBy('name', 'ASC')->where('task_cat_id',$task->category_id)->where('org_id',Auth::user()->org_id)->select('name', 'id')->get();
         $project_status = ['new' => 'New', 'ongoing' => 'Ongoing', 'completed' => 'Completed'];
        return view('admin.project_task.edit', compact('task', 'page_title', 'page_description', 'cat', 'peoples', 'task_attachments','projects','project_status','sub_cat'));
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

        ]);

        $attributes = $request->all();
        $attributes['schedule']  = $request->schedule == 'on' ? 'on' : '';
        $attributes['schedule_type']= $request->timespan ?? '';
            
        if (!isset($attributes['enabled'])) {
            $attributes['enabled'] = 1;
        }

        if (!isset($attributes['milestone'])) {
            $attributes['milestone'] = 0;
        }

        $task = $this->projectTask->find($id);

        $theTags = [];
        $peoples = [];
        $tags = explode(',', \Request::get('peoples'));

        foreach ($tags as $key => $value) {
            $dbtag = User::where('username', '=', $value)->first();

            if ($dbtag) {
                array_push($theTags, $dbtag->id);
                array_push($peoples, $value);
            }
        }

        if ($task->isEditable()) {
            // Save the attachment data and file
            if ($request->file('attachment')) {
                if ($task->attachment != '') {
                    // Delete the File from its location
                    $fileUrl = public_path() . '/task_attachments/' . $task->attachment;
                    Files::delete($fileUrl);
                }

                $stamp = date('Ymdhis') . '_';
                $file = $request->file('attachment');
                $destinationPath = public_path() . '/task_attachments/';
                $filename = $file->getClientOriginalName();
                $attributes['attachment'] = $stamp . $filename;
                $request->file('attachment')->move($destinationPath, $stamp . $filename);
            }
            //dd($attributes);
            foreach ($request->attachments as $key => $doc_) {
                if ($doc_) {
                    $doc_name = time() . '' . $doc_->getClientOriginalName();
                    $destinationPath = public_path('/task_attachments/');
                    $doc_->move($destinationPath, $doc_name);
                    $task_attachment = ['task_id' => $id, 'attachment' => $doc_name, 'user_id' => \Auth::user()->id];
                    \App\Models\ProjectTaskAttachment::create($task_attachment);
                }
            }

            $task->update($attributes);
            //dd($theTags);
            $task->tags()->sync($theTags);
        }
        \App\Models\ProjectTaskActivity::create([
            'task_id' => $id,
            'activity' => 'Tasks been updated',
            'user_id' => \Auth::User()->id,
        ]);
        Audit::log(Auth::user()->id, trans('admin/project-task/general.audit-log.category'), trans('admin/project-task/general.audit-log.msg-update', ['subject' => $task->subject]));

        Flash::success(trans('admin/project-task/general.status.updated')); // 'Project successfully updated');

        return redirect('/admin/project_task/' . $id . '/edit');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $task = $this->projectTask->find($id);

        if (!$task->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/project-task/general.audit-log.category'), trans('admin/project-task/general.audit-log.msg-destroy', ['subject' => $task->subject]));

        $task->delete($id);
        if (\Request::ajax()) {
            return ['status' => 1];
        }
        Flash::success(trans('admin/project-task/general.status.deleted')); // 'Lead successfully deleted');

        MasterComments::where('type', 'project_task')->where('master_id', $id)->delete();

        return redirect()->back();
    }

    public function destroyAttachment($id,$pid){

        $taskAttachment = \App\Models\ProjectTaskAttachment::where('id',$id)->delete();

        Flash::success("File successfully Deleted");

        return redirect()->back();
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

        $task = $this->projectTask->find($id);

        if (!$task->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/project-task/dialog.delete-confirm.title');

        $modal_route = route('admin.project_task.delete', ['taskId' => $task->id]);

        $modal_body = trans('admin/project-task/dialog.delete-confirm.body', ['id' => $task->id, 'subject' => $task->subject]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function getUserTagsJson()
    {
        $tags = User::select('username', 'id', 'image')->where('username', 'like', \Request::get('term') . '%')->get();
        foreach ($tags as $key => $value) {
            if ($value->image) {
                $img = url('/') . '/images/profiles/' . $value->image;
            } else {
                $img = url('/') . '/images/logo.png';
            }
            $tags[$key]['icons'] = $img;
        }

        return $tags;
    }

    public function postComment(Request $request)
    {
        $masterComment = new MasterComments();

        if ($request->file('file_name')) {
            $stamp = time();
            $file = $request->file('file_name');
            $destinationPath = public_path() . '/files/';

            $filename = $file->getClientOriginalName();
            $request->file('file_name')->move($destinationPath, $stamp . '_' . $filename);

            $masterComment->file = $stamp . '_' . $filename;
        }

        $masterComment->type = \Request::get('type');

        if (\Request::get('type') == 'project_task') {
            $task = \App\Models\ProjectTask::find(\Request::get('master_id'));
            $peoples = explode(',', $task->peoples);
            $comment = \Request::get('comment_text');
            foreach ($peoples as $key => $value) {
                $person = \App\User::where('username', $value)->first();
                //person to send mail
                //$person->email;
                $from = env('APP_EMAIL');
                try {
                    $mail = Mail::send('emails.task-comment', compact('comment'), function ($message) use ($person, $task, $from) {
                        $message->subject('Comment on - ' . $task->subject);
                        $message->from($from, env('APP_COMPANY'));
                        $message->to($person->email, '');
                    });
                } catch (\Exception $e) {
                }
            }
        }

        $masterComment->master_id = \Request::get('master_id');
        $masterComment->user_id = Auth::user()->id;
        $masterComment->comment_text = \Request::get('comment_text');

        $masterComment->save();

        Flash::success('Comment has been submitted.');

        return redirect()->back();
    }

    public function ajaxProjectTaskStatus(Request $request)
    {
        $task = $this->projectTask->find($request->id);
        $attributes['status'] = $request->status;
        $task->update($attributes);

        //send an email to all the peoples in this task
        $peoples = explode(',', $task->peoples);
        $status = $attributes['status'];

        foreach ($peoples as $key => $value) {
            $person = \App\User::where('username', $value)->first();
            $from = env('APP_EMAIL');
            //person to send mail
            //$person->email;
            try {
                $mail = Mail::send('emails.task-status', compact('status', 'task'), function ($message) use ($person, $task, $from) {
                    $message->subject('Status Changed - ' . $task->subject);
                    $message->from($from, env('APP_COMPANY'));
                    $message->to($person->email, '');
                });
            } catch (\Exception $e) {
            }
        }

        //send to activities table
        \App\Models\ProjectTaskActivity::create([
            'task_id' => $request->id,
            'activity' => 'Tasks status has been updated to ' . $attributes['status'],
            'user_id' => \Auth::User()->id,
        ]);

        return ['status' => 1];
    }

    public function ajaxProjectTaskOrder(Request $request)
    {
        $taskorder = $request->task_order;
        foreach ($taskorder as $value) {
            $attributes['task_order'] = $value['pos'];
            $this->projectTask->find($value['id'])->update($attributes);
        }

        return ['status' => 1];
    }

    public function searchprojecttask(Request $request)
    {

        $term = $request->table_search;
        
        $page_title = 'Admin | Project | Tasks | Search';
        
        $page_description = 'Project Tasks search <strong>' . ($term) . '</strong>';
        


        $tasks = ProjectTaskModel::select('project_tasks.id', 'project_tasks.project_id', 'project_tasks.user_id', 'project_tasks.subject', 'project_tasks.percent_complete', 'project_tasks.priority', 'project_tasks.created_at', 'project_tasks.end_date')
            ->leftjoin('users', 'users.id', '=', 'project_tasks.user_id')
            ->leftjoin('projects', 'projects.id', '=', 'project_tasks.project_id')
            ->where(function ($query) {
                if (!Auth::user()->hasRole('admins')) {
                    return $query->where('user_id', Auth::user()->id);
                }
            })
            ->where(function ($query) use ($term) {
                return $query->where('project_tasks.id', $term)
                    ->orWhere('users.username', $term)
                    ->orWhere('project_tasks.subject', 'LIKE', '%' . $term . '%')
                    ->orWhere('project_tasks.description', 'LIKE', '%' . $term . '%')
                    ->orWhere('projects.name', 'LIKE', '%' . $term . '%');
            })
            ->where(function($query){
                $start_date = \Request::get('start_date');
                 $end_date = \Request::get('end_date');

                if($start_date && $end_date){

                    return $query->where('project_tasks.created_at','>=',$start_date)
                                ->where('project_tasks.created_at','<=',$end_date);
                }

            })

            ->get();



        return view('admin.projects.search', compact('page_title', 'page_description', 'tasks'));
    }

    public function openmodal($projectid)
    {
        $page_description = 'Create new task modals';

        return view('admin.project_task.modals.create', compact('projectid', 'page_description'));
    }

    public function postmodal(Request $request)
    {
        $attributes = $request->all();

        $attributes['org_id'] = \Auth::user()->org_id;

        $attributes['peoples'] = $request->peoples ? $request->peoples : \Auth::User()->username;
        
        $tags = explode(',', $attributes['peoples']);
        $theTags = [];
        foreach ($tags as $key => $value) {
            $dbtag = User::where('username', '=', $value)->first();

            if ($dbtag) {
                array_push($theTags, $dbtag->id);
            }
        }
        $attributes['user_id'] = \Auth::user()->id;
        if (!isset($request->ispostedfromcalandar)) {
            $attributes['start_date'] = $request->start_date ? $request->start_date : date('Y-m-d');
        }

        $task = ProjectTaskModel::create($attributes);
        $task->tags()->sync($theTags);
        $html = \view('admin.project_task.ajax_taskrow', compact('task'))->render();

        if (!$request->ajax()) {
            return redirect()->back();
        }

        return ['task' => $html, 'id' => $task->id];
    }

    public function ajaxTaskUpdate(Request $request)
    {
        $changetype = $request->type;

        switch ($changetype) {
            case 'start_date':
                $date = date_create($request->update_value);
                $attributes['start_date'] = date_format($date, 'Y-m-d');
                break;
            case 'end_date':
                $date = date_create($request->update_value);
                $attributes['end_date'] = date_format($date, 'Y-m-d');
                break;
            case 'duration':
                $attributes['duration'] = $request->update_value . 'd';
                break;
            case 'percent_complete':
                $attributes['percent_complete'] = $request->update_value;
                break;
            case 'category':
                $attributes['category_id'] = $request->update_value;
                break;
            case 'priority':
                $attributes['priority'] = $request->update_value;
                break;
            case 'stages':
                $attributes['stage_id'] = $request->update_value;
                break;
            case 'description':
                $attributes['description'] = $request->update_value;
                break;
            case 'subject':
                $attributes['subject'] = $request->update_value;
                break;
            case 'projects':
                $attributes['project_id'] = $request->update_value;
                break;
            case 'status':
                $attributes['status'] = $request->update_value;
                break;
            case 'calendar_changes':
                if ($request->start_date) {
                    $attributes['start_date'] = $request->start_date;
                }
                if ($request->end_date) {
                    $attributes['end_date'] = $request->end_date;
                }
                // dd($attributes);
                $request->update_value = $attributes['start_date'] . '-' . $attributes['end_date'];
                break;
            case 'sub_cat_id':
                $attributes['sub_cat_id'] = $request->update_value;
                break;
            default:
                // code...
                break;
        }

        $task = $this->projectTask->find($request->id);
        \App\Models\ProjectTaskActivity::create([
            'task_id' => $request->id,
            'activity' => $changetype . ' has been updated to ' . $request->update_value,
            'user_id' => \Auth::User()->id,
        ]);
        //dd($attributes);
        $task->update($attributes);
        $updated = $this->projectTask->find($request->id);
        $updated['stages_color'] = $updated->stage->bg_color;

        return  ['status' => 1, 'data' => $updated];
    }

    public function ajaxTaskPeopleUpdate(Request $request)
    {
        $task = $this->projectTask->find($request->id);
        $current_people = explode(',', $task->peoples);
        $total_people = explode(',', $request->peoples);
        $new_people = array_diff($total_people, $current_people);

        foreach ($new_people as $key => $value) {
            $person = \App\User::where('username', $value)->first();
            $from = env('APP_EMAIL');
            $to = $person->email;
            try {
                $mail = Mail::send('emails.project_task', compact('task'), function ($message) use ($from, $to) {
                    $message->subject('A task - ' . $task->subject);
                    $message->from($from, env('APP_COMPANY'));
                    $message->to($to, '');
                });
            } catch (\Exception $e) {
            }
        }
        $theTags = [];
        $tags = explode(',', $request->peoples);
        foreach ($tags as $key => $value) {
            $dbtag = User::where('username', '=', $value)->first();

            if ($dbtag) {
                array_push($theTags, $dbtag->id);
            }
        }
        $task->tags()->sync($theTags);
        $task->update(['peoples' => $request->peoples]);
        \App\Models\ProjectTaskActivity::create([
            'task_id' => $request->id,
            'activity' => 'New People Added',
            'user_id' => \Auth::User()->id,
        ]);
    }

    public function backlogsTasks($project_id)
    {
        $page_title = 'Admin | Project | Tasks | Backlogs';
        $page_description = 'Project Tasks Backlogs ';
        if (Auth::user()->hasRole('admins')) {
            $tasks = ProjectTaskModel::where('project_id', $project_id)->where('end_date', '<=', \Carbon\Carbon::today())->where('status', '!=', 'completed')->orderBy('id', 'desc')->paginate(100);
        } else {
            $tasks = ProjectTaskModel::where('project_id', $project_id)->where('end_date', '<=', \Carbon\Carbon::today())->where('status', '!=', 'completed')->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(100);
        }

        return view('admin.tasks.backlogstasks', compact('page_title', 'page_description', 'tasks'));
    }

    public function taskdescription(Request $request)
    {
        $taskstatus = \App\Models\ProjectTaskStatus::where('name', $request->name)->where('project_id', $request->pk)->first();
        if ($taskstatus) {
            $taskstatus->update(['description' => $request->value]);
        } else {
            \App\Models\ProjectTaskStatus::create([
                'project_id' => $request->pk,
                'name' => $request->name,
                'description' => $request->value,
            ]);
        }

        return 0;
    }

     public function ajaxGetSubCat(Request $request){
            $taskssubcat = \App\Models\TaskSubCat::where('task_cat_id', $request->cat_id)->get();


            $data = '<option value="">Please Select </option>';

            foreach ($taskssubcat as $key => $value) {
                $data .= '<option value="'.$value->id.'">'.$value->name.'</option>';
            }

            return ['success' => 1, 'data' => $data];
    }
}
