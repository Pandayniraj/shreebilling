<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
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
        $page_title = 'Marketing Calendar';
        $page_description = 'Syncs with Google Calendar';

        if (\Auth::user()->hasRole('admins')) {
            $tasks = \App\Models\Task::leftJoin('task_users', 'task_users.task_id', '=', 'tasks.id')
                        ->select('tasks.id', 'tasks.task_subject as title', 'tasks.task_start_date as start', 'tasks.task_due_date as end',
                        'tasks.task_detail', 'tasks.color', 'tasks.task_start_date', 'tasks.lead_id','tasks.task_status')
                        ->whereNotNull('task_start_date')
                        ->where('task_start_date', '!=', '0000-00-00 00:00:00')
                        ->orderBy('tasks.task_start_date', 'desc')
                        ->where('org_id',\Auth::user()->org_id)
                        ->groupBy('tasks.id')
                        ->get();

            $pendingTask = \App\Models\Task::leftJoin('task_users', 'task_users.task_id', '=', 'tasks.id')
                        ->select('tasks.*')
                        ->whereNull('task_start_date')
                         ->orWhere('task_start_date', '0000-00-00 00:00:00')
                        ->orderBy('tasks.created_at', 'desc')
                        ->where('org_id',\Auth::user()->org_id)
                        ->take(7)
                        ->get();

        // dd($tasks);
        } else {
            $tasks = \App\Models\Task::leftjoin('task_users', 'task_users.task_id', '=', 'tasks.id')
                        ->select('tasks.id', 'tasks.task_subject as title', 'tasks.task_start_date as start', 'tasks.task_due_date as end',
                        'tasks.task_detail', 'tasks.color', 'tasks.task_start_date', 'tasks.lead_id','tasks.task_status')
                        
                        ->orWhere('tasks.task_owner',Auth::user()->id)
                        ->where(function ($query) use ($selected_lead) {
                             if ($selected_lead) {
                                 return $query->where('tasks.lead_id', $selected_lead);
                             }
                         })
                        ->where('org_id',\Auth::user()->org_id)
                        ->orderBy('tasks.task_start_date', 'desc')
                        ->get();

            $pendingTask = \App\Models\Task::leftJoin('task_users', 'task_users.task_id', '=', 'tasks.id')
                    ->select('tasks.*')
                    ->where('task_users.user_id', Auth::user()->id)
                    ->orWhere('tasks.task_owner',Auth::user()->id)
                    ->whereNull('task_start_date')
                    ->orWhere('task_start_date', '0000-00-00 00:00:00')
                    ->where('org_id',\Auth::user()->org_id)
                    ->orderBy('tasks.task_start_date', 'desc')
                    ->take(7)
                    ->get();
        }
        $allTasks = [];
        foreach ($tasks as $key => $value) {
            $leadInfo = $value->lead;
            $new = [
                'title'=>$value->title,
                'iscompleted'=>$value->task_status == 'Completed'?true:false,
                'start'=> $value->start,
                'end'=> $value->end,
                'url' => '/admin/tasks/'.$value->id.'/edit',
                'backgroundColor'=>$value->color ?? '#f56954',
                'borderColor'=>$value->color ?? '#f56954',
                'group_name'=>$leadInfo ? $leadInfo->name : '',
                'id'=>$value->id,
                'end_date'=> date('dS M Y', strtotime($value->task_end_date)),
                'description'=>strlen($value->task_detail) > 20 ? substr($value->task_detail, 0, 20).'..' : $value->task_detail,
            ];
            array_push($allTasks, $new);
        }

        $allTasks = json_encode($allTasks);
        //dd($allTasks);
        $leads = \App\Models\Lead::where('org_id', \Auth::user()->org_id)->get();

        return view('calendar', compact('page_title', 'page_description', 'allTasks', 'leads', 'pendingTask'));
    }

    public function shiftCalenderold()
    {
        $page_title = 'Employee Shift Calender';
        $page_description = 'Syncs with Google Calender';

        $usersarray = [];

        if (Auth::user()->hasRole('admins')) {
            $shifts = \App\Models\ShiftMap::orderBy('id', 'desc')->get();
            //dd($shifts);
            foreach ($shifts as $shift) {
                $shift_user = \App\Models\ShiftMap::find($shift->id)->user_id;
                $shift_user = explode(',', $shift_user);

                foreach ($shift_user as $su) {
                    array_push($usersarray, $su);
                }
            }

            $allShifts = [];

            foreach ($usersarray as $ua) {
                $shift_maps = \App\Models\ShiftMap::orderBy('id', 'desc')->get();

                foreach ($shift_maps as $sm) {
                    $shifts_user = \App\Models\ShiftMap::find($sm->id)->user_id;
                    $shift_user = explode(',', $shifts_user);

                    foreach ($shift_user as $su) {
                        if ($su == $ua) {
                            $username = \App\User::find($ua)->username;
                            $value = \App\Models\ShiftMap::select('shift_maps.id', 'shifts.shift_name as title', 'shift_maps.map_from_date as start', 'shift_maps.map_to_date as end')
                                                                    ->leftJoin('shifts', 'shift_maps.shift_id', '=', 'shifts.id')
                                                                    ->where('shift_maps.id', $sm->id)
                                                                    ->first();

                            $new = ['title'=> $value->title.'-'.ucfirst($username), 'start'=> $value->start, 'end'=> $value->end, 'url' => '/admin/shifts/maps/'.$value->id.'/edit'];

                            array_push($allShifts, $new);
                        }
                    }
                }
            }

            //dd($allShifts);

            //$shiftsusers = \App\User::
        } else {
            $shifts = \App\Models\ShiftMap::orderBy('id', 'desc')->get();
            //dd($shifts);
            foreach ($shifts as $shift) {
                $shift_user = \App\Models\ShiftMap::find($shift->id)->user_id;
                $shift_user = explode(',', $shift_user);

                foreach ($shift_user as $su) {
                    array_push($usersarray, $su);
                }
            }

            $allShifts = [];

            foreach ($usersarray as $ua) {
                $shift_maps = \App\Models\ShiftMap::orderBy('id', 'desc')->get();

                foreach ($shift_maps as $sm) {
                    $shifts_user = \App\Models\ShiftMap::find($sm->id)->user_id;
                    $shift_user = explode(',', $shifts_user);

                    foreach ($shift_user as $su) {
                        if ($su == Auth::user()->id) {
                            $username = \App\User::find($ua)->username;
                            $value = \App\Models\ShiftMap::select('shift_maps.id', 'shifts.shift_name as title', 'shift_maps.map_from_date as start', 'shift_maps.map_to_date as end')
                                                                    ->leftJoin('shifts', 'shift_maps.shift_id', '=', 'shifts.id')
                                                                    ->where('shift_maps.id', $sm->id)
                                                                    ->first();

                            $new = ['title'=> $value->title.'-'.ucfirst($username), 'start'=> $value->start, 'end'=> $value->end, 'url' => '/admin/shifts/maps/'.$value->id.'/edit'];

                            array_push($allShifts, $new);
                        }
                    }
                }
            }
        }

        $allShifts = json_encode($allShifts);

        return view('admin.shifts.calender', compact('page_title', 'page_description', 'allShifts'));
    }

    public function shiftCalender(Request $request)
    {
        $projects = \App\Models\Projects::all();
        $page_title = 'Employee Shift Calender';
        $filter_type = $request->type;
        $usersarray = [];

        if ($filter_type == 'date' || $filter_type == 'all') {
            $date_today = $request->date_in;
            $date_now = $request->date_in;
        } else {
            $date_today = date('Y-m-d');
            $date_now = date('Y-m-d');
        }
        $date_week_after = date('Y-m-d', strtotime($date_now.' +7 day'));
        $dates = [];

        for ($i = 1; $i <= 7; $i++) {
            array_push($dates, $date_now);
            $date_now = date('Y-m-d', strtotime($date_now.' +1 day'));
        }

        if (Auth::user()->hasRole('admins')) {
            $shifts = \App\Models\ShiftMap::orderBy('id', 'desc')->where('map_from_date', '<=', $date_today)->where('map_to_date', '>=', $date_today)->get();
            // dd($shifts);
            foreach ($shifts as $shift) {
                $shift_user = \App\Models\ShiftMap::find($shift->id)->user_id;
                $shift_user = explode(',', $shift_user);

                foreach ($shift_user as $su) {
                    array_push($usersarray, $su);
                }
            }

            $allShifts = [];

            foreach ($usersarray as $ua) {
                $shift_maps = \App\Models\ShiftMap::orderBy('id', 'desc')->where('map_from_date', '<=', $date_today)->where('map_to_date', '>=', $date_today)->get();

                foreach ($shift_maps as $sm) {
                    $shifts_user = \App\Models\ShiftMap::find($sm->id)->user_id;
                    $shift_user = explode(',', $shifts_user);

                    foreach ($shift_user as $su) {
                        if ($su == $ua) {
                            $username = \App\User::find($ua)->username;
                            $value = \App\Models\ShiftMap::select('shift_maps.id', 'shifts.id as shift_id', 'shifts.shift_name as title', 'shift_maps.map_from_date as start', 'shift_maps.map_to_date as end')
                                                                    ->leftJoin('shifts', 'shift_maps.shift_id', '=', 'shifts.id')
                                                                    ->where('shift_maps.id', $sm->id)
                                                                    ->first();

                            $new = ['user_id'=> $ua, 'start'=> $value->start, 'end'=> $value->end, 'shift_id'=>$value->shift_id];

                            array_push($allShifts, $new);
                        }
                    }
                }
            }
        }
        $project_id = $request->project_id;

        $users = \App\User::orderBy('username', 'asc')
                ->where(function ($query) use ($filter_type,$project_id) {
                    if ($filter_type == 'pro' || $filter_type == 'all') {
                        return $query->where('project_id', $project_id);
                    }
                })
                ->where('enabled', '1')->get();

        // dd($users);
        $page_description = 'Shows the Shift Of '.(($filter_type == 'date' || $filter_type == 'all') ? date('dS Y M ', strtotime($date_today)) : 'The Week ').(($filter_type == 'pro' || $filter_type == 'all') ? 'With Project '.\App\Models\Projects::find($project_id)->name : '');

        return view('admin.shifts.calender', compact('page_title', 'page_description', 'allShifts', 'usersarray', 'dates', 'users', 'projects', 'project_id', 'filter_type', 'date_today'));
    }
}
