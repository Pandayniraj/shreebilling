<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Clock;
use App\Models\ClockHistory;
use App\Models\Department;
use App\Models\Holiday;
use App\User;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class AttendanceController extends Controller
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Permission
     */
    private $permission;
    private $attendance;

    /**
     * @param Client $client
     * @param Permission $permission
     * @param User $user
     */
    public function __construct()
    {
        parent::__construct();
     
    }

    public function index()
    {
        $page_title = 'Attendance';
        $page_description = 'Attendance Report';

        $attendance = null;
        $departments = Department::get();

        return view('admin.attendance.index', compact('page_title', 'page_description', 'attendance', 'departments'));
    }

    public function show(Request $request)
    {
        $page_title = 'Attendance';
        $page_description = 'Attendance Report';
        //dd($request->all());
        $department = $request->department_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $date_in = $start_date . '.' . $end_date;

        /* $attendance = DB::table('tbl_attendance')
                        ->select('users.first_name as user_name', 'users.id as user_id')
                        ->join('users', 'users.id', '=', 'tbl_attendance.user_id')
                        ->where('users.department', $request->department_id)
                        ->where('tbl_attendance.date_in', '>=', $date_in.'-01')
                        ->where('tbl_attendance.date_in', '<=', $date_in.'-32')
                        ->groupBy('user_id')
                        ->get(); */

        $attendance = DB::table('users')
            ->select('first_name as user_name', 'id as user_id')
            ->where('departments_id', $request->department_id)
            ->where('enabled', '1')
            ->groupBy('user_id')
            ->get();
        //dd($attendance);

        $holidays = Holiday::select('event_name', 'start_date', 'end_date')->where('start_date', '>=', $start_date)->where('end_date', '<=', $end_date)->get();
        //dd($holidays);
        $departments = Department::get();

        return view('admin.attendance.index', compact('page_title', 'page_description', 'department', 'date_in', 'attendance', 'holidays', 'departments', 'start_date', 'end_date'));
    }

    public function detailreport(Request $request)
    {
        if ($request->ajax()) {
            if ($request->type == 'dep') {
                $deginations = \App\Models\Designation::where(function ($query) {
                    if (\Request::get('value') && \Request::get('value') != '') {
                        return $query->where('departments_id', \Request::get('value'));
                    }
                })->get();

                return ['data' => $deginations];
            } elseif ($request->type == 'org') {
                $filter = function ($query) {
                    if (\Request::get('value') && \Request::get('value') != '') {
                        return $query->where('org_id', \Request::get('value'));
                    }
                };
                $projects = \App\Models\Projects::where(function ($query) use ($filter) {
                    return $filter($query);
                })->get();
                $departments = \App\Models\Department::where(function ($query) use ($filter) {
                    return $filter($query);
                })->get();
                $teams = \App\Models\Team::where(function ($query) use ($filter) {
                    return $filter($query);
                })->get();

                return ['projects' => $projects, 'departments' => $departments, 'teams' => $teams];
            }
        }
        $page_title = 'Filter Attendance';

        $projects = \App\Models\Projects::pluck('name', 'id');

        $departments = \App\Models\Department::pluck('deptname', 'departments_id as id');

        $teams = \App\Models\Team::pluck('name', 'id');

        $org = \App\Models\Organization::pluck('organization_name as name', 'id');

        $deginations = [];

        return view(
            'admin.attendance.dailyregister.daily_registerdetails',
            compact('projects', 'departments', 'teams', 'org', 'deginations', 'page_title')

        );
    }

    public function detailreportShow(Request $request)
    {
        $users = \App\User::where(function ($query) use ($request) {
            if ($request->org_id && $request->org_id != '') {
                return $query->where('org_id', $request->org_id);
            }
        })->where(function ($query) use ($request) {
            if ($request->dep_id && $request->dep_id != '') {
                return $query->where('departments_id', $request->dep_id);
            }
        })->where(function ($query) use ($request) {
            if ($request->deg_id && $request->deg_id != '') {
                return $query->where('designations_id', $request->deg_id);
            }
        })->where(function ($query) use ($request) {
            if ($request->project && $request->project != '') {
                return $query->where('project_id', $request->project);
            }
        })->where(function ($query) use ($request) {
            if ($request->teams && $request->teams != '') {
                $teams = \App\Models\UserTeam::where('team_id', $request->teams)->pluck('user_id');

                return $query->whereIn('id', $teams);
            }
        })
            ->groupBy('id')->pluck('id');

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $usersLists = \App\User::whereIn('id', $users)
            ->where('enabled', '1')->get();

        $history = Attendance::select(
            'tbl_clock.clock_id',
            'tbl_clock.clockin_time',
            'tbl_clock.clockout_time',
            'tbl_clock.ip_address',
            'tbl_clock.in_device',
            'tbl_clock.out_device',
            'tbl_attendance.*'
        )
            ->join('tbl_clock', 'tbl_clock.attendance_id', '=', 'tbl_attendance.attendance_id')
            ->where('tbl_attendance.date_in', '>=', $start_date)
            ->where('tbl_attendance.date_in', '<=', $end_date)
            ->whereIn('tbl_attendance.user_id', $users)
            ->get()
            ->groupBy('date_in');

        // return view('admin.attendance.dailyregister.details',compact('history','usersList','start_date','end_date','usersLists'));
        $html = view('admin.attendance.dailyregister.details', compact('history', 'usersList', 'start_date', 'end_date', 'usersLists'))->render();

        return ['result' => $html];
    }

    public function allattendanceindex()
    {
        $page_title = 'All Attendance';
        $page_description = 'All Attendance Report';

        $attendance = null;
        $departments = Department::get();

        return view('admin.attendance.allattendance', compact('page_title', 'page_description', 'attendance', 'departments'));
    }

    public function allattendanceshow(Request $request)
    {
        $page_title = 'Attendance';
        $page_description = 'Attendance Report';
        //dd($request->all());
        $department = $request->department_id;
        $date_in = $request->date_in;

        if (Auth::user()->hasRole('admins')) {
            $attendance = DB::table('users')
                ->select('first_name as user_name', 'id as user_id')
                ->groupBy('user_id')
                ->get();
        } else {
            $attendance = DB::table('users')
                ->select('first_name as user_name', 'id as user_id')
                ->where('id', Auth::user()->id)
                ->groupBy('user_id')
                ->get();
        }

        //dd($attendance);

        $holidays = Holiday::select('event_name', 'start_date', 'end_date')->where('start_date', '>=', $date_in . '-01')->where('end_date', '<=', $date_in . '-32')->get();
        //dd($holidays);
        $departments = Department::get();

        return view('admin.attendance.allattendance', compact('page_title', 'page_description', 'department', 'date_in', 'attendance', 'holidays', 'departments'));
    }

    public function Attendance()
    {
        $page_title = 'Attendance';
        $page_description = 'Attendance Report';
        //dd($request->all());
        $department = $request->department_id;
        $date_in = $request->date_in;

        /* $attendance = DB::table('tbl_attendance')
                        ->select('users.first_name as user_name', 'users.id as user_id')
                        ->join('users', 'users.id', '=', 'tbl_attendance.user_id')
                        ->where('users.department', $request->department_id)
                        ->where('tbl_attendance.date_in', '>=', $date_in.'-01')
                        ->where('tbl_attendance.date_in', '<=', $date_in.'-32')
                        ->groupBy('user_id')
                        ->get(); */

        $attendance = DB::table('users')
            ->select('first_name as user_name', 'id as user_id')
            ->where('departments_id', $request->department_id)
            ->groupBy('user_id')
            ->get();
        //dd($attendance);

        $holidays = Holiday::select('event_name', 'start_date', 'end_date')->where('start_date', '>=', $date_in . '-01')->where('end_date', '<=', $date_in . '-32')->get();
        //dd($holidays);
        $departments = Department::get();

        return view('admin.attendance.index', compact('page_title', 'page_description', 'department', 'date_in', 'attendance', 'holidays', 'departments'));
    }

    public function printAttendance($department, $date_in)
    {
        /* $attendance = DB::table('tbl_attendance')
                    ->select('users.first_name as user_name', 'users.id as user_id')
                    ->join('users', 'users.id', '=', 'tbl_attendance.user_id')
                    ->where('users.department', $department)
                    ->where('tbl_attendance.date_in', '>=', $date_in.'-01')
                    ->where('tbl_attendance.date_in', '<=', $date_in.'-32')
                    ->groupBy('user_id')
                    ->get(); */
        $date_in = explode('.', $date_in);

        $start_date = $date_in[0];
        $end_date = $date_in[1];
        $attendance = DB::table('users')
            ->select('first_name as user_name', 'id as user_id')
            ->where('departments_id', $department)
            ->groupBy('user_id')
            ->get();

        $holidays = Holiday::select('event_name', 'start_date', 'end_date')->where('start_date', '>=', $date_in[0])->where('end_date', '<=', $date_in[1])->get();
        $department = Department::where('departments_id', $department)->first();
        //dd($attendance);
        return view('admin.attendance.print', compact('attendance', 'holidays', 'department', 'date_in', 'start_date', 'end_date'));
    }

    public function generatePDF($department, $date_in)
    {
        /* $attendance = DB::table('tbl_attendance')
                    ->select('users.first_name as user_name', 'users.id as user_id')
                    ->join('users', 'users.id', '=', 'tbl_attendance.user_id')
                    ->where('users.department', $department)
                    ->where('tbl_attendance.date_in', '>=', $date_in.'-01')
                    ->where('tbl_attendance.date_in', '<=', $date_in.'-32')
                    ->groupBy('user_id')
                    ->get(); */
        $date_in = explode('.', $date_in);
        $start_date = $date_in[0];
        $end_date = $date_in[1];
        $attendance = DB::table('users')
            ->select('first_name as user_name', 'id as user_id')
            ->where('departments_id', $department)
            ->groupBy('user_id')
            ->get();

        $holidays = Holiday::select('event_name', 'start_date', 'end_date')->where('start_date', '>=', $date_in[0])->where('end_date', '<=', $date_in[1])->get();
        $department = Department::where('departments_id', $department)->first();
        $pdf = \PDF::loadView('admin.attendance.generateAttendancePDF', compact('attendance', 'holidays', 'department', 'date_in', 'start_date', 'end_date'));
        $file = 'Attandance_' . ucfirst($department->deptname) . '_' . $date_in . '.pdf';

        if (File::exists('reports/' . $file)) {
            File::Delete('reports/' . $file);
        }

        return $pdf->download($file);
    }

    public function printAllAttendance($date_in)
    {
        if (Auth::user()->hasRole('admins')) {
            $attendance = DB::table('users')
                ->select('first_name as user_name', 'id as user_id')
                ->groupBy('user_id')
                ->get();
        } else {
            $attendance = DB::table('users')
                ->select('first_name as user_name', 'id as user_id')
                ->where('id', Auth::user()->id)
                ->groupBy('user_id')
                ->get();
        }

        $holidays = Holiday::select('event_name', 'start_date', 'end_date')->where('start_date', '>=', $date_in . '-01')->where('end_date', '<=', $date_in . '-32')->get();
        //dd($attendance);
        return view('admin.attendance.print', compact('attendance', 'holidays', 'department', 'date_in'));
    }

    public function generateAllPDF($date_in)
    {
        if (Auth::user()->hasRole('admins')) {
            $attendance = DB::table('users')
                ->select('first_name as user_name', 'id as user_id')
                ->groupBy('user_id')
                ->get();
        } else {
            $attendance = DB::table('users')
                ->select('first_name as user_name', 'id as user_id')
                ->where('id', Auth::user()->id)
                ->groupBy('user_id')
                ->get();
        }

        $holidays = Holiday::select('event_name', 'start_date', 'end_date')->where('start_date', '>=', $date_in . '-01')->where('end_date', '<=', $date_in . '-32')->get();

        $pdf = \PDF::loadView('admin.attendance.generateAttendancePDF', compact('attendance', 'holidays', 'department', 'date_in'));
        $file = 'Attandance_' . ucfirst($department) . '_' . date('F_Y', strtotime($date_in)) . '.pdf';

        if (File::exists('reports/' . $file)) {
            File::Delete('reports/' . $file);
        }

        return $pdf->download($file);
    }

    public function timeHistory()
    {
        $page_title = 'Time Report';
        $page_description = 'Time History Report';

        $user_id = null;
        $users = User::select('id', 'first_name')->where('enabled', '1')->get();
        $history = null;

        return view('admin.attendance.timeHistory', compact('page_title', 'page_description', 'history', 'user_id', 'users'));
    }

    public function timeHistoryPost(Request $request)
    {
        $page_title = 'Time Report';
        $page_description = 'Time History Report';

        $users = User::select('id', 'first_name')->where('enabled', '1')->get();

        $user_id = $request->user_id;

        $start_date = $request->start_date;

        $end_date = $request->end_date;

        $date_in = $start_date . '.' . $end_date;

        $history = Attendance::select(
            'tbl_clock.clock_id',
            'tbl_clock.clockin_time',
            'tbl_clock.clockout_time',
            'tbl_clock.ip_address',
            'tbl_clock.in_device',
            'tbl_clock.out_device',
            'tbl_attendance.*'
        )
            ->join('tbl_clock', 'tbl_clock.attendance_id', '=', 'tbl_attendance.attendance_id')
            ->where('tbl_attendance.date_in', '>=', $start_date)
            ->where('tbl_attendance.date_in', '<=', $end_date)
            ->where('tbl_attendance.user_id', $user_id)
            ->get()
            ->groupBy('date_in');

        // foreach($history as $key =>$h){
        //     dd($h->sortBy('clock_id')->take(2));
        // }

        return view('admin.attendance.timeHistory', compact('page_title', 'page_description', 'users', 'user_id', 'date_in', 'history', 'start_date', 'end_date'));
    }

    public function printTimeHistory($user_id, $date_in)
    {
        $date_in = explode('.', $date_in);

        $history = DB::table('tbl_attendance')
            ->select('tbl_clock.clockin_time', 'tbl_clock.clockout_time', 'tbl_clock.ip_address', 'tbl_attendance.*')
            ->join('tbl_clock', 'tbl_clock.attendance_id', '=', 'tbl_attendance.attendance_id')
            ->where('tbl_attendance.date_in', '>=', $date_in[0])
            ->where('tbl_attendance.date_in', '<=', $date_in[1])
            ->where('tbl_attendance.user_id', $user_id)
            ->get();

        return view('admin.attendance.printTimeHistory', compact('history', 'user_id', 'date_in'));
    }

    public function generatePDFTimeHistory($user_id, $date_in)
    {
        $date_in = explode('.', $date_in);
        $history = DB::table('tbl_attendance')
            ->select('tbl_clock.clockin_time', 'tbl_clock.clockout_time', 'tbl_clock.ip_address', 'tbl_attendance.*')
            ->join('tbl_clock', 'tbl_clock.attendance_id', '=', 'tbl_attendance.attendance_id')
            ->where('tbl_attendance.date_in', '>=', $date_in[0])
            ->where('tbl_attendance.date_in', '<=', $date_in[1])
            ->where('tbl_attendance.user_id', $user_id)
            ->get();

        $holidays = Holiday::select('event_name', 'start_date', 'end_date')->where('start_date', '>=', $date_in . '-01')->where('end_date', '<=', $date_in . '-32')->get();

        $pdf = \PDF::loadView('admin.attendance.generatePDFTimeHistory', compact('history', 'user_id', 'date_in'));
        $file = 'Time_Logs_' . \TaskHelper::getUserName($user_id) . '_' . date('F_Y', strtotime($date_in[0])) . '.pdf';

        if (File::exists('reports/' . $file)) {
            File::Delete('reports/' . $file);
        }

        return $pdf->download($file);
    }

    public function editTime($clock_id)
    {
        $clock = Clock::where('clock_id', $clock_id)->first();
        $data = '<div class="panel panel-custom" data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <strong>Edit </strong>
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                    class="sr-only">Close</span></button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form id="time_update_request"
                              action="/admin/time_history/update_time/' . $clock_id . '"
                              method="post" class="">' . csrf_field() . '
                            <div class="col-sm-12 margin">
                                <div class="col-lg-2"></div>
                                <div class="col-sm-4">
                                    <label class="control-label">Old Time In </label>
                                    <div class="input-group">
                                        <p class="form-control-static">' . date('h:i a', strtotime($clock->clockin_time)) . '</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">New Time In </label>
                                    <div class="input-group">
                                        <input type="text" name="clockin_edit" id="clockin_edit" class="form-control timepicker"
                                               value="' . date('h:i a', strtotime($clock->clockin_time)) . '">
                                        <input type="hidden" name="clockin_old" id="clockin_old" value="' . date('h:i a', strtotime($clock->clockin_time)) . '">
                                        <div class="input-group-addon">
                                            <a href="#"><i class="fas fa-clock"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 margin">
                                <div class="col-lg-2"></div>
                                <div class="col-sm-4">
                                    <label class="control-label">Old Time Out </label>
                                    <div class="input-group">
                                        <p class="form-control-static">' . date('h:i a', strtotime($clock->clockout_time)) . '</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">New Time Out</label>
                                    <div class="input-group">
                                        <input type="text" name="clockout_edit" id="clockout_edit" class="form-control timepicker"
                                               value="' . date('h:i a', strtotime($clock->clockout_time)) . '">
                                        <input type="hidden" name="clockout_old" id="clockout_old" value="' . date('h:i a', strtotime($clock->clockout_time)) . '">
                                        <div class="input-group-addon">
                                            <a href="#"><i class="fas fa-clock"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 margin">
                                <div class="col-lg-2"></div>
                                <div class="col-sm-8 center-block">
                                    <label class="control-label">Reason for Edit <span
                                            class="required">*</span></label>
                                    <div>
                                        <textarea class="form-control" name="reason" id="reason" rows="6" required="required"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 margin">
                                <div class="col-lg-2"></div>
                                <div class="col-sm-4 m0 mt">
                                    <button type="button" class="btn btn-block btn-primary" id="request_update">Request Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>';

        return $data;
    }

    public function updateTime(Request $request, $clock_id)
    {
        $attributes = $request->all();
        $attributes['user_id'] = Auth::user()->id;
        $attributes['clock_id'] = $clock_id;
        $attributes['clockin_edit'] = date('H:i', strtotime($request->clockin_edit));
        $attributes['clockout_edit'] = date('H:i', strtotime($request->clockout_edit));
        $attributes['clockin_old'] = date('H:i', strtotime($request->clockin_old));
        $attributes['clockout_old'] = date('H:i', strtotime($request->clockout_old));
        $attributes['request_date'] = date('Y-m-d H:i:s');
        $attributes['view_status'] = 0;
        ClockHistory::create($attributes);

        return ['success' => 1];
    }

    public function timeChangeRequest()
    {
        $page_title = 'Timechange Request';
        $page_description = 'Timechange Request';

        $timechange = ClockHistory::orderBy('created_at', 'desc')->get();

        return view('admin.attendance.timeChangeRequest', compact('page_title', 'page_description', 'timechange'));
    }

    public function timeRequestModal($clock_history_id)
    {
        $clockHistory = ClockHistory::where('clock_history_id', $clock_history_id)->first();
        $user = \TaskHelper::getUser($clockHistory->user_id);

        $data = '<div class="panel panel-custom">
                    <div class="panel-heading">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <div class="panel-title">
                            <strong>Time Change Request Details</strong>
                        </div>
                    </div>
                    <form id="time_update_request"
                              action="/admin/timechange_request/' . $clockHistory->clock_history_id . '"
                              method="post" class="panel-body form-horizontal">' . csrf_field() . '
                        <div class="col-md-12">
                            <div class="col-sm-4 text-right">
                                <label class="control-label"><strong>EMP ID : </strong></label>
                            </div>
                            <div class="col-sm-8">
                                <p class="form-control-static">' . $user->id . '</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-4 text-right">
                                <label class="control-label"><strong>Name : </strong></label>
                            </div>
                            <div class="col-sm-8">
                                <p class="form-control-static">' . $user->first_name . ' ' . $user->last_name . '</p>
                            </div>
                        </div>
                        <div style="margin-top: 80px;margin-bottom: 20px;">
                            <div class="col-md-12">
                                <div class="col-sm-4 text-right">
                                    <label class="control-label"><strong>Old Time In : </strong></label>
                                </div>
                                <div class="col-sm-2">
                                    <p class="form-control-static"><span class="text-success">' . date('h:i a', strtotime($clockHistory->clockin_old)) . '</span></p>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <label class="control-label"><strong>New Time In : </strong></label>
                                </div>
                                <div class="col-sm-2">
                                    <p class="form-control-static"><span class="text-danger">' . date('h:i a', strtotime($clockHistory->clockin_edit)) . '</span></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-sm-4 text-right">
                                    <label class="control-label"><strong>Old Time Out : </strong></label>
                                </div>
                                <div class="col-sm-2">
                                    <p class="form-control-static"><span class="text-success">' . date('h:i a', strtotime($clockHistory->clockout_old)) . '</span></p>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <label class="control-label"><strong>New Time Out : </strong></label>
                                </div>
                                <div class="col-sm-2">
                                    <p class="form-control-static"><span class="text-danger">' . date('h:i a', strtotime($clockHistory->clockout_edit)) . '</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-4 text-right">
                                <label class="control-label"><strong>Reason : </strong></label>
                            </div>
                            <div class="col-sm-8">
                                <p class="form-control-static">' . $clockHistory->reason . '</p>
                            </div>
                        </div>
                        <div class="col-md-12 margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label"><strong>Action : </strong></label>
                            </div>
                            <div class="col-sm-4">';

        if ($clockHistory->status != 2) {
            $data .= '<select class="form-control" name="status">';

            if ($clockHistory->status == 1) {
                $data .= '<option value="1" selected=""> Pending</option>';
            } else {
                $data .= '<option value="1"> Pending</option>';
            }

            $data .= '<option value="2"> Accepted</option>';
            if ($clockHistory->status == 3) {
                $data .= '<option value="3" selected=""> Rejected</option>';
            } else {
                $data .= '<option value="3"> Rejected</option>';
            }

            $data .= '</select>';
        }
        if ($clockHistory->status == 2) {
            $data .= '<p class="form-control-static"><span class="text-success">Accepted</span></p>';
        }

        $data .= '
                            </div>
                        </div>';

        if ($clockHistory->status != 2) {
            $data .= '<div class="col-md-12 mt">
                                <div class="col-sm-4">

                                </div>
                                <div class="col-sm-4 margin">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>';
        }
        $data .= '
                    </form>
                </div>';

        return $data;
    }

    public function updateTimeChangeRequest(Request $request, $clock_history_id)
    {
        $clock = ClockHistory::where('clock_history_id', $clock_history_id)->update(['status' => $request->status, 'view_status' => '1']);

        $clockHistory = ClockHistory::where('clock_history_id', $clock_history_id)->first();

        if ($request->status == 2) {   // 2 = Accepted
            $clock = Clock::where('clock_id', $clockHistory->clock_id)->update(['clockin_time' => $clockHistory->clockin_edit, 'clockout_time' => $clockHistory->clockout_edit, 'time_changed' => 1]);
        }
        Flash::success('Time Change Status has been updated.');

        return redirect('/admin/timechange_request');
    }

    public function importexport()
    {
        $page_title = 'Attendance | Import/Export';
        $page_description = 'Import export attendance';

        return view('admin.excel.importExportAttendence', compact('page_description', 'page_title'));
    }

    public function downloadexcel($type)
    {
        $attendance = Attendance::select('tbl_attendance.attendance_id', 'tbl_attendance.user_id', 'tbl_attendance.date_in', 'tbl_attendance.date_out', 'tbl_attendance.attendance_status', 'tbl_attendance.clocking_status', 'tbl_clock.clockin_time', 'tbl_clock.clockout_time', 'tbl_clock.ip_address')
            ->leftjoin('tbl_clock', 'tbl_clock.attendance_id', '=', 'tbl_attendance.attendance_id')->get();

        return \Excel::create('attendance', function ($excel) use ($attendance) {
            $excel->sheet('mySheet', function ($sheet) use ($attendance) {
                $sheet->fromArray($attendance);
            });
        })->download(xls);
    }

    public function importexportstore()
    {
        if (Input::hasFile('import_file')) {
            $path = Input::file('import_file')->getRealPath();
            $data = \Excel::load($path, function ($reader) {
            })->get();

            if (!empty($data) && $data->count()) {
                foreach ($data as $key => $value) {
                    $insert[] = ['user_id' => $value->user_id, 'date_in' => $value->date_in, 'date_out' => $value->date_out, 'attendance_status' => $value->attendance_status, 'clocking_status' => $value->clocking_status];
                }
                if (!empty($insert)) {
                    Attendance::insert($insert);
                    $ip_address = Request::getClientIp();
                    $lastcreated = Attendance::orderBy('attendance_id', 'desc')->take(count($insert))->get();
                    foreach ($lastcreated as $key => $attendance) {
                        $clock[] = ['attendance_id' => $attendance->attendance_id, 'clockin_time' => $data[$key]['clockin_time'], 'clockout_time' => $data[$key]['clockout_time'], 'ip_address' => $ip_address];
                    }
                    Clock::insert($clock);
                    Flash::success('Attendance Record Insert successfully.');

                    return redirect()->back();
                }
            }
        }

        Flash::success('Sorry no file is selected to import attendance.');

        return redirect()->back();
    }

    public function timekeep_setup()
    {
        $data = '';

        $staffs = User::select('*')->where('enabled', '1')->where('id', '!=', '1')->orderBy('first_name')->get();
        $page_title = 'Timekeeping Setup';
        $page_description = 'set up time entry method';
        $frequency = ['H' => 'Hourly', 'W' => 'Weekly', 'M' => 'Monthly', 'B' => 'Biweekly'];
        $pay_frequency = \App\Models\PayFrequency::select('id', 'frequency')->get()->unique('frequency');

        return view('admin.attendance.timekeep_setup', compact('data', 'staffs', 'page_title', 'page_description', 'pay_frequency', 'frequency'));
    }

    public function timekeep_setup_post(Request $request)
    {
        foreach ($request->user_id as $key => $user_id) {
            $records = [
                'user_id' => $user_id,
                'time_entry_method' => ($request->time_entry_method)[$key],
                'pay_frequency' => ($request->pay_frequency_id)[$key],
                'pay_type' => ($request->pay_type)[$key],
            ];
            $exists = \App\Models\TimeKeeping::where('user_id', $user_id)->first();
            if ($exists) {
                $exists->update($records);
            } else {
                \App\Models\Timekeeping::create($records);
            }
        }
        Flash::success('Timekeeping setup successfully updated');

        return redirect()->back();
    }

    public function myTimeHistory()
    {
        $page_title = 'My Time History';
        $page_description = 'Show Your Time History ';

        return view('admin.attendance.mytimehistory', compact('page_title', 'page_description'));
    }

    public function myTimeHistoryUpdate(Request $request)
    {
        $page_title = 'Time Report';
        $page_description = 'Time History Report';

        $users = User::select('id', 'first_name')->where('enabled', '1')->get();

        $user_id = Auth::user()->id;

        $date_in = $request->date_in;

        //$last_day =  $date_in->firstOfMonth();

        $history = DB::table('tbl_attendance')
            ->select('tbl_clock.clock_id', 'tbl_clock.clockin_time', 'tbl_clock.clockout_time', 'tbl_clock.ip_address', 'tbl_attendance.*')
            ->join('tbl_clock', 'tbl_clock.attendance_id', '=', 'tbl_attendance.attendance_id')
            ->where('tbl_attendance.date_in', '>=', $date_in . '-01')
            ->where('tbl_attendance.date_in', '<=', $date_in . '-31')
            ->where('tbl_attendance.user_id', $user_id)
            ->get();

        //dd($history);

        return view('admin.attendance.mytimehistory', compact('page_title', 'page_description', 'users', 'user_id', 'date_in', 'history'));
    }

    public function markAttendance()
    {
        $users = \App\User::where('enabled', '1')->pluck('username', 'id')->all();
        $page_title = 'Mark | Attendance';

        return view('admin.attendance.mark_attendance.create', compact('page_title', 'users'));
    }

    private function clockin($user_id, $date, $time, $comments, $inLoc)
    {
        $chk_attendance = Attendance::where('user_id', $user_id)->where('clocking_status', '1')->where('date_in', $date)->first();
        if ($chk_attendance) {
            Attendance::where('attendance_id', $chk_attendance->attendance_id)->update(['date_out' => $date, 'clocking_status' => 1, 'outLoc' => 0]);
            $clock = Clock::where('attendance_id', $chk_attendance->attendance_id)
                ->orderBy('clock_id', 'desc')
                ->first();
            if ($clock->clockin_time) {
                $clock = new Clock();
                $clock->attendance_id = $chk_attendance->attendance_id;
                $clock->clockin_time = $time;
                $clock->clocking_status = 1;
                $clock->comments = $comments;
                $clock->ip_address = \Request::getClientIp();
                $clock->save();
            } else {
                Clock::where('clock_id', $clock->clock_id)->update(['clockout_time' => $time, 'clocking_status' => 1, 'comments' => $comments]);
            }
        } else {
            $attendance = new Attendance();
            $attendance->user_id = $user_id;
            $attendance->org_id = 1;
            $attendance->leave_category_id = 0;
            $attendance->date_in = $date;
            $attendance->attendance_status = 1;
            $attendance->clocking_status = 1;
            $attendance->inLoc = $inLoc;
            $attendance->save();

            $clock = new Clock();
            $clock->attendance_id = $attendance->id;
            $clock->clockin_time = $time;
            $clock->comments = $comments;
            $clock->clocking_status = 1;
            $clock->ip_address = \Request::getClientIp();
            $clock->save();
        }
    }

    private function clockout($user_id, $date, $time, $comments, $outLoc)
    {
        $chk_attendance = Attendance::where('user_id', $user_id)->where('clocking_status', '1')->where('date_in', $date)->first();

        if ($chk_attendance) {
            Attendance::where('attendance_id', $chk_attendance->attendance_id)->update(['date_out' => $date, 'org_id' => \Auth::user()->org_id, 'clocking_status' => 0, 'outLoc' => $outLoc]);

            $clock = Clock::where('attendance_id', $chk_attendance->attendance_id)
                ->orderBy('clock_id', 'desc')
                ->first();
            if ($clock->clockout_time) {
                $clock = new Clock();
                $clock->attendance_id = $chk_attendance->attendance_id;
                $clock->clockout_time = $time;
                $clock->clocking_status = 1;
                $clock->comments = $comments;
                $clock->ip_address = \Request::getClientIp();
                $clock->save();
            } else {
                Clock::where('clock_id', $clock->clock_id)->update(['clockout_time' => $time, 'clocking_status' => 1, 'comments' => $comments]);
            }
        }
    }

    public function storeMarkAttendance(Request $request)
    {
        $location = json_decode($request->locations, true);

        if ($location) {
            $loc = ['latitude' => $location['lat'], 'longitude' => $location['long']];
            $loc = json_encode(\TaskHelper::getLocDetails($loc));
        } else {
            $loc = null;
        }

        $user_id = $request->employee_id;
        $date = $request->date;
        $time = $request->time;
        $comments = $request->comments;
        if ($request->clock_type == 'in') {
            $this->clockin($user_id, $date, $time, $comments, $loc);
        } else {
            $this->clockout($user_id, $date, $time, $comments, $loc);
        }
        Flash::success('You have been successfully Clocked ' . $request->clock_type);

        return redirect()->back();
    }

    public function view_status($user_id)
    {
        $attendance_log = Attendance::where('user_id', $user_id)->where('clocking_status', '1')->first();

        if (!$attendance_log) {
            $status = 'in';
        } else {
            $status = 'out';
        }

        return ['status' => $status];
    }

    public function bulkAttendance()
    {
        $page_title = 'Bulk | Attendance';
        $department = \App\Models\Department::all();
        $page_title = 'Bulk Attendance';
        $page_description = 'Attendance For Multiple Employee';

        return view('admin.attendance.mark_attendance.bulkcreate', compact('page_title', 'department', 'page_title', 'page_description'));
    }

    public function bulkAttendanceNext(Request $request)
    {
        $department = \App\Models\Department::all();
        $users = \App\User::where('departments_id', $request->dep_id)->where('enabled', '1')->get();
        $departments_id = $request->dep_id;
        $page_title = 'Bulk Attendance';
        $page_description = 'Attendance For Multiple Employee';

        return view('admin.attendance.mark_attendance.bulkcreate', compact('page_title', 'department', 'departments_id', 'users', 'page_title', 'page_description'));
    }

    public function bulkAttendanceSave(Request $request)
    {
        $location = json_decode($request->locations, true);
        if ($location) {
            $loc = ['latitude' => $location['lat'], 'longitude' => $location['long']];
            $loc = json_encode(\TaskHelper::getLocDetails($loc));
        } else {
            $loc = null;
        }

        foreach ($request->employee_id as $key => $user_id) {
            $user_id = $user_id;
            $date = $request->date[$key];
            $time = $request->time[$key];
            $comments = $request->comments[$key];

            if ($request->clock_type[$key] == 'in') {
                $this->clockin($user_id, $date, $time, $comments, $loc);
            } else {
                $this->clockout($user_id, $date, $time, $comments, $loc);
            }
        }
        Flash::success('Bulk Attendance Added');

        return redirect()->back();
    }
}
