<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Projects;
use App\Models\ProjectUser;
use DB;
use Illuminate\Http\Request;

class ProjectCalendarController extends Controller
{
    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // Protect all dashboard routes. Users must be authenticated.
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title = 'Project Task Calendar';
        $page_description = 'Syncs with Google Calendar';
        $pendingTask = [];
        if (\Auth::user()->hasRole('admins')) {
            $projects = Projects::where('org_id',\Auth::user()->org_id)->where('status', 'New')
                        ->orWhere('status', 'Started')
                        ->orderBy('id', 'DESC')
                        ->get();

        } else {
            
            $projectsUsers = ProjectUser::where('user_id', \Auth::user()->id)->get()->pluck('project_id');

            $projects = Projects::whereIn('id', $projectsUsers)
                        ->where('org_id',\Auth::user()->org_id)
                        ->orderBy('id', 'DESC')
                        ->where('enabled', 1)
                        ->get();
        }
        $selected_project = \Request::get('project');
        if (\Auth::user()->hasRole('admins')) {
            $tasks = \App\Models\ProjectTask::leftJoin('project_task_user', 'project_task_user.project_task_id', '=', 'project_tasks.id')
                        ->select('project_tasks.id', 'project_tasks.subject as title', 'project_tasks.start_date as start', 'project_tasks.end_date as end','project_tasks.color as color','project_tasks.start_date as start_date',
                            'project_tasks.description as description', 'project_tasks.project_id')
                        ->where('org_id',\Auth::user()->org_id)
                        ->where(function ($query) use ($selected_project) {
                            if ($selected_project) {
                                return $query->where('project_tasks.project_id', $selected_project);
                            }
                        })->whereNotNull('end_date')
                         ->where('end_date', '!=', '0000-00-00 00:00:00')
                        ->whereNotNull('start_date')
                        ->orderBy('project_tasks.created_at', 'desc')
                      //  ->groupBy('project_tasks.id')
                        ->get();
            if ($selected_project) {
                $pendingTask = \App\Models\ProjectTask::leftJoin('project_task_user', 'project_task_user.project_task_id', '=', 'project_tasks.id')
                      ->select('project_tasks.*')
                      ->where('org_id',\Auth::user()->org_id)
                      ->where('project_tasks.project_id', $selected_project)
                      ->whereNull('start_date')
                      ->orWhere('start_date', '0000-00-00 00:00:00')
                      ->orderBy('project_tasks.created_at', 'desc')
                      ->take(7)
                      ->get();
            } //to be added in draggable

                       // dd($tasks);
        } else {
            $tasks = \App\Models\ProjectTask::leftJoin('project_task_user', 'project_task_user.project_task_id', '=', 'project_tasks.id')
                        ->select('project_tasks.id', 'project_tasks.subject as title', 'project_tasks.start_date as start', 'project_tasks.end_date as end','project_tasks.color as color','project_tasks.start_date as start_date',
                            'project_tasks.description as description', 'project_tasks.project_id')
                        ->where('project_task_user.user_id', \Auth::user()->id)
                        ->where(function ($query) use ($selected_project) {
                            if ($selected_project) {
                                return $query->where('project_tasks.project_id', $selected_project);
                            }
                        })->whereNotNull('end_date')
                         ->where('end_date', '!=', '0000-00-00 00:00:00')
                        ->whereNotNull('start_date')
                        ->orderBy('project_tasks.created_at', 'desc')
                      //  ->groupBy('project_tasks.id')
                        ->get();
        }

        //  dd($tasks);
        $allTasks = [];
        foreach ($tasks as $key => $value) {
            $new = [
            'title'=> $value->title,
            'start'=> $value->start,
            'end'=> $value->end,
            'url' => '/admin/project_task/'.$value->id,
            'backgroundColor'=>$value->color ?? '#f56954',
            'borderColor'=>$value->color ?? '#f56954',
            'id'=>$value->id,
            'group_name'=>$value->project->name,
            'start_date'=> date('dS M Y', strtotime($value->start_date)),
            'description'=>strlen($value->description) > 20 ? substr($value->description, 0, 20).'..' : $value->description,

            ];
            array_push($allTasks, $new);
        }

        $allTasks = json_encode($allTasks);
        //      echo '<pre>';
        //dd($allTasks);
        //dd($allTasks);
        //dd($pendingTask);
        return view('admin.project_task.calendar', compact('page_title', 'page_description', 'allTasks', 'projects', 'selected_project', 'pendingTask'));
    }
}
