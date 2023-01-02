<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\ShiftAttendance;
use App\User;
use AttendanceHelper;
use DB;
use Flash;
use Illuminate\Http\Request;

class ShiftAttendanceReportController extends Controller
{
    public function timeHistory()
    {
        $page_title = 'Attendance | history';
        $user_id = null;
        $users = User::select('id', 'first_name')->where('enabled', '1')->get();
        $history = null;

        return view('admin.shift_attendance.timehistory', compact('users', 'page_title'));
    }

    public function timeHistoryShow(Request $request)
    {
        $page_title = 'Attendance | history';
        $user_id = $request->user_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $users = User::select('id', 'first_name')->where('enabled', '1')->get();

        $attendance = ShiftAttendance::where('user_id', $user_id)
                        ->where('date', '>=', $start_date)
                        ->where('date', '<=', $end_date)
                        ->get()
                        ->groupBy('shift_id');

        $allReport = AttendanceHelper::singleuserAttendanceReportWithShift($attendance);
        // dd($allReport);
        if (count($allReport) == 0) {
            Flash::warning('This user Does Not have any record on attendance');
        }

        return view('admin.shift_attendance.timehistory', compact('users', 'page_title', 'allReport', 'user_id', 'start_date', 'end_date'));
    }

    public function attendanceReport()
    {
        $page_title = 'Attendance';
        $page_description = 'Attendance Report';

        $attendance = null;

        if(\Auth::user()->hasRole('admins') ){
            $departments = Department::get();
        }else{


            $departments = Department::where('departments_id',\Auth::user()
                            ->department_head)->get();
        }

        $shifts = \App\Models\Shift::get();

        return view('admin.shift_attendance.attendance_report', compact('page_title', 'page_description', 'attendance', 'departments', 'shifts'));
    }

    public function attendanceReportShow(Request $request)
    {
        $page_title = 'Attendance';
        $page_description = 'Attendance Report';
        //dd($request->all());
        $department = $request->department_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $date_in = $start_date.'.'.$end_date;
     
        /* $attendance = DB::table('tbl_attendance')
                        ->select('users.first_name as user_name', 'users.id as user_id')
                        ->join('users', 'users.id', '=', 'tbl_attendance.user_id')
                        ->where('users.department', $request->department_id)
                        ->where('tbl_attendance.date_in', '>=', $date_in.'-01')
                        ->where('tbl_attendance.date_in', '<=', $date_in.'-32')
                        ->groupBy('user_id')
                        ->get(); */

        $filterShift = function ($query) use ($request) {
            if ($request->shift_id) {
                $shiftMap = AttendanceHelper::getShiftusers($request->shift_id);

                return $query->whereIn('id', $shiftMap);
            }
        };
        $attendance = DB::table('users')
                        ->select('first_name as user_name', 'id as user_id')
                        ->where(function ($query) use ($request) {
                            if(!\Auth::user()->hasRole('admins')){

                            $depaments_list = [];
                            if(\Auth::user()->department_head){

                            $depaments_list = Department::where('departments_id',\Auth::user()
                            ->departments_id)->get()->pluck('departments_id')->toArray();

                            }

                            

                            if(count($depaments_list) == 0 ){
                                return $query->where('id',\Auth::user()->id);
                            }

                            return $query->whereIn('departments_id', $depaments_list);


                            }
                            if ($request->department_id) {
                                return $query->where('departments_id', $request->department_id);
                            }
                        })

                        ->where('enabled', '1')
                        ->where(function ($query) use ($filterShift) {
                            $filterShift($query);
                        })
                        ->groupBy('user_id')
                        ->get();

        $holidays = \App\Models\Holiday::select('event_name', 'start_date', 'end_date')->where('start_date', '>=', $start_date)->where('end_date', '<=', $end_date)->get();
        //dd($holidays);
          if(\Auth::user()->hasRole('admins') ){
            $departments = Department::get();
        }else{


             $departments = Department::where('departments_id',\Auth::user()
                            ->department_head)->get();
        }
        $shifts = \App\Models\Shift::get();
        $shift = $request->shift_id;

        return view('admin.shift_attendance.attendance_report', compact('page_title', 'page_description', 'department', 'date_in', 'attendance', 'holidays', 'departments', 'start_date', 'end_date', 'shifts', 'shift'));
    }

    private function absPresentExcel($users, $holidays, $lang, $start_date, $end_date)
    {
        $excelReport = [];

        $begin = new \DateTime($start_date);
        $end = new \DateTime($end_date);
        $end->add(new \DateInterval('P1D'));
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);
        $cal = new \App\Helpers\NepaliCalendar();
        $columns = [];
        foreach ($period as $dt) {
            $date = $dt->format('Y-m-d');
            if ($lang == 'nepali') {
                $dateArr = [
                    'label'=>$cal->formated_nepali_from_eng_date($date),
                    'value'=>$date,
                ];
            } else {
                $dateArr = [
                    'label'=>$date,
                    'value'=>$date,
                ];
            }
            $columns[] = $dateArr;
        }

        $weekends = \Config::get('hrm.weekends');
        foreach ($users as $key=>$av) {
            $report = [];
            $report['emp_id'] = $av->user_id;
            $report['username'] = $av->user_name;
            $userAtt = \AttendanceHelper::getUserAttendanceHistroy($av->user_id, $start_date, $end_date);
            foreach ($columns as $k=>$c) {
                $currentDate = $c['value'];
                $currentDateLabel = $c['label'];

                $checkHoliday = $holidays->where('start_date', '<=', $currentDate)
                                        ->where('end_date', '>=', $currentDate)
                                        ->first();

                $checkLeave = AttendanceHelper::checkUserLeave($av->user_id, $currentDate);

                $checkPresent = count($userAtt->where('date', $currentDate));
                if (in_array(date('l', strtotime($currentDate)), $weekends)) {
                    $report[$currentDateLabel] = 'W';
                } elseif ($checkHoliday) {
                    $report[$currentDateLabel] = 'H';
                } elseif ($checkLeave) {
                    $report[$currentDateLabel] = 'L';
                } elseif ($checkPresent > 0) {
                    if ($checkPresent % 2 == 0) {
                        $report[$currentDateLabel] = 'P';
                    } else {
                        $report[$currentDateLabel] = 'PnoClockOut';
                    }
                } else {
                    $report[$currentDateLabel] = '-';
                }
            }

            $excelReport[] = $report;
        }

        return ['summary'=>$excelReport];
    }

    public function download_report(Request $request, $type)
    {
        $filterShift = function ($query) use ($request) {
            if ($request->shift_id) {
                $shiftMap = AttendanceHelper::getShiftusers($request->shift_id);

                return $query->whereIn('id', $shiftMap);
            }
        };
        $attendance = DB::table('users')
                        ->select('first_name as user_name', 'id as user_id')
                        ->where(function ($query) use ($request) {
                            if ($request->department_id) {
                                return $query->where('departments_id', $request->department_id);
                            }
                        })
                        ->where('enabled', '1')
                        ->where(function ($query) use ($filterShift) {
                            $filterShift($query);
                        })
                        ->where(function ($query) use ($request) {
                            if(!\Auth::user()->hasRole('admins')){

                            $depaments_list = Department::where('departments_id',\Auth::user()
                            ->department_head)->get()->pluck('departments_id')->toArray();

                            if(count($depaments_list) == 0 ){
                                return $query->where('id',\Auth::user()->id);
                            }

                            return $query->whereIn('departments_id', $depaments_list);


                            }
                          
                        })
                        ->groupBy('user_id')
                        ->get();

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $holidays = \App\Models\Holiday::select('event_name', 'start_date', 'end_date')->where('start_date', '>=', $start_date)->where('end_date', '<=', $end_date)->get();
        $report = $this->absPresentExcel($attendance, $holidays, $request->lang, $start_date, $end_date);
        $summaryReport = $report['summary'];

        return \Excel::download(new \App\Exports\ExcelExport($summaryReport), "attendance_report_summary_{$start_date}_{$end_date}.{$type}");
    }
}
