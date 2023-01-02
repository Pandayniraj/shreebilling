<?php

namespace App\Helpers;

use App\Models\ShiftAttendance;
use Jenssegers\Agent\Agent;

class AttendanceHelper
{
    public static  function checkNightShift()
    {
        $authid = \Auth::user()->id;

        $message = null;

        $check_night_shift = ShiftAttendance::where('user_id', $authid)
                            ->orderBy('date', 'desc')
                            ->first();

        if ($check_night_shift && $check_night_shift->shift->is_night) {
            //night shiftt
            $shiftdate = $check_night_shift->date;

            $shift = $check_night_shift->shift;

            $period_start = $shiftdate.' '.$shift->shift_time;

            $period_end = $shiftdate.' '.$shift->end_time;

            //like start time 5:PM end time is 5Am

            if (strtotime($period_start) > strtotime($period_end)) {
                $period_end = date('Y-m-d h:i A', strtotime($period_end.' +1 day'));
            }

            $period_start = strtotime($period_start);

            $period_end = strtotime($period_end);

            if (is_numeric($shift->shift_margin_end)) {
                $period_end = $period_end + $shift->shift_margin_end * 60 * 60;
            }

            $presenttime = strtotime(date('Y-m-d h:i A'));

            if ($period_start <= $presenttime && $period_end >= $presenttime) {
                //you are in night shift
                return ['allow'=>true, 'shift'=>$shift, 'time'=>$presenttime, 'date'=>$shiftdate];
            }
        }

        return ['allow'=>false, 'message'=>$message];
    }

     public  static function clockout()
    {
        $night_shift = self::checkNightShift();
        if ($night_shift['allow']) {
            $today = $night_shift['date'];
            $shift = $night_shift;
        } else {
            $today = date('Y-m-d');
            $shift = self::checkshift('out');
        }
        $authid = \Auth::User()->id;
        if ($shift['allow']) {
            $lastAttendence = ShiftAttendance::where('shift_id', $shift['shift']->id)
                            ->where('user_id', $authid)
                            ->where('date', $today)
                            ->orderBy('attendance_status', 'desc')
                            ->first();

            if (! $lastAttendence) {

                $message ='Opps !! you must clockin first';

                return  ['success'=>false,'message'=>$message];

            }
            $location = json_decode(\Request::get('location'), true);

            if ($location) {
                $location = ['latitude'=>$location['lat'], 'longitude'=>$location['long']];
                $location = json_encode(\TaskHelper::getLocDetails($location));
            } else {
                $location = null;
            }

            $nextStatus = $lastAttendence->attendance_status + 1;
            $source = self::getDeviceSource();
            $attendence = [
                'user_id'=>$authid,
                'shift_id'=>$shift['shift']->id,
                'date'=>$today,
                'time'=> date('Y-m-d  H:i:s', $shift['time']),
                'attendance_status'=>$nextStatus,
                'location'=>$location,
                'source'=>$source,
                'ip_address'=>\Request::getClientIp(),
            ];

            $attendence = ShiftAttendance::create($attendence);

            $message = 'Successfully Clocked Out';

            return  ['success'=>true,'message'=>$message];
        } else {
            $message = $shift['message'] ?? 'You are Not Assigned With any shift currently';
            return  ['success'=>false,'message'=>$message];
        }

        return redirect()->back();
    }

       public  static function  clockin()
    {
        $night_shift = self::checkNightShift();

        if ($night_shift['allow']) {
            $today = $night_shift['date'];
            $shift = $night_shift;
        } else {
            $today = date('Y-m-d');
            $shift = self::checkshift();
        }

        $authid = \Auth::User()->id;
        if ($shift['allow']) {
            $lastAttendence = ShiftAttendance::where('shift_id', $shift['shift']->id)
                            ->where('user_id', $authid)
                            ->where('date', $today)
                            ->orderBy('attendance_status', 'desc')
                            ->first();
            $nextStatus = $lastAttendence ? ($lastAttendence->attendance_status + 1) : 1;
            $location = json_decode(\Request::get('location'), true);

            if ($location) {
                $location = ['latitude'=>$location['lat'], 'longitude'=>$location['long']];

                $location = json_encode(\TaskHelper::getLocDetails($location));
            } else {
                $location = null;
            }
        
            $source = self::getDeviceSource();

            $attendence = [
                'user_id'=>$authid,
                'shift_id'=>$shift['shift']->id,
                'date'=>$today,
                'time'=>date('Y-m-d H:i:s'),
                'attendance_status'=>$nextStatus,
                'location'=>$location,
                'source'=> $source,
                'ip_address'=>\Request::getClientIp(),

            ];

            $attendence = ShiftAttendance::create($attendence);
            $message = 'Successfully Clocked In';
            return  ['success'=>true,'message'=>$message];
        } else {
            $message = $shift['message'] ? $shift['message'] : 'You are Not Assigned With any shift currently';
            return  ['success'=>false,'message'=>$message];
        }
    }

      public static function getDeviceSource()
    {
        $agent = new Agent();

        if ($agent->isMobile()) {
            $platformName = $agent->device();
            $source = $platformName;
        } else {
            $platformName = $agent->platform();
            $browserName = $agent->browser();
            $source = $platformName.' '.$browserName;
        }

        return $source;
    }

    public static function checkshift($type = 'in')
    {
        $today = date('Y-m-d');
        $shiftsMap = \App\Models\ShiftMap::where('map_from_date', '<=', date('Y-m-d'))
                    ->where('map_to_date', '>=', $today)
                    ->get();
        $authid = \Auth::User()->id;
        $message = false;
        $current_time = strtotime(date('Y-m-d h:i A'));
        foreach ($shiftsMap as $key=> $value) {
            $shiftUsers = explode(',', $value->user_id);
            if (in_array($authid, $shiftUsers)) {
                $shift = $value->shift;
                $shift_time = strtotime($today.' '.$shift->shift_time);

                if (is_numeric($shift->shift_margin_start)) {
                    $shift_time = $shift_time - $shift->shift_margin_start * 60 * 60;
                }

                $end_time = strtotime($today.' '.$shift->end_time);

                if (is_numeric($shift->shift_margin_end)) {
                    $end_time = $end_time + $shift->shift_margin_end * 60 * 60;
                }

                if ($shift_time <= $current_time && $end_time >= $current_time) {
                    return ['shift'=>$shift, 'time'=>$current_time, 'allow'=>true];
                } elseif ($end_time < $current_time && $type == 'out') {
                    return ['shift'=>$shift, 'time'=> $end_time, 'allow'=>true];
                }

                if ($shift->is_night) {
                    if ($shift_time > $end_time) {
                        $end_time = date('h:i A', $end_time);

                        $end_time = date('Y-m-d h:i A', strtotime($end_time.' +1 day'));
                    }

                    $message = 'Your Shift Has not been Started !! , You can clockin From '.date('H:i A', $shift_time).' To '.date('dS M Y H:i A', strtotime($end_time));
                } else {
                    $message = 'You can clockin From '.date('H:i A', $shift_time).' To '.date('H:i A', $end_time);
                }
            }
        }

        return ['allow'=>false, 'message'=>$message];
    }


    public static function breakDuration($shift_id)
    {
        $breaks = \App\Models\ShiftBreak::where('shift_id', $shift_id)->get();
        $break_duration = 0;
        $breakInfo = [];
        foreach ($breaks as $key=>$break) {
            $difference = strtotime($break->end_time) - strtotime($break->start_time);
            $break_duration += $difference / 60;
            $numberOfBreak++;
            $breakInfo[] = ['name'=>$break->name, 'start'=>$break->start_time, 'end'=>$break->end_time, 'icon'=>$break->icon];
        }
        //return duration in minutes
        $formatted_hours = self::minutesToHours($break_duration).' Hrs';

        return ['duration'=>$break_duration, 'breakInfo'=>$breakInfo, 'formatted'=>$formatted_hours];
    }

    public static function getShiftusers($shift_id)
    {
        $shiftsMap = \App\Models\ShiftMap::where('shift_id', $shift_id)
                    ->get();
        $users = [];
        foreach ($shiftsMap as $key => $value) {
            $userlist = explode(',', $value->user_id);
            $users = array_merge($users, $userlist);
        }
        $users = array_unique($users);
        $users = array_values($users);

        return $users;
    }

    public static function calculateBreakAndWork($attendence)
    {
        $attendence = $attendence->values();

        $index = 0;

        $workTime = 0; //inminutes

        $breakTime = 0; //inminutes

        $timeDifference = [];
        $message = null;
        foreach ($attendence as $key => $value) {
            $current = strtotime($value->time);

            $next = $attendence[++$index];

            $location = $value->location ? json_decode($value->location) : null;
            if ($next) {
                $nextTime = strtotime($next->time);
                $difference = ($nextTime - $current) / 60;
                if ($value->attendance_status % 2 == 0) {
                    //breaktime
                    $breakTime += $difference;
                    $timeDifference[] = [
                        'start'=> $value->time,
                        'end'=> $next->time,
                        'duration'=> $difference,
                        'type'=>'break',
                        'id'=>$value->id,
                        'status'=>$value->attendance_status,
                        'location'=>$location,
                        'source'=>$value->source,
                        'endArr'=>[
                            'location'=> $next->location ? json_decode($next->location) : null,
                            'source'=>$next->source,
                        ],
                    ];
                } else {
                    $workTime += $difference;
                    $timeDifference[] = [
                        'start'=> $value->time,
                        'end'=> $next->time,
                        'duration'=> $difference,
                        'type'=>'work',
                        'id'=>$value->id,
                        'status'=>$value->attendance_status,
                        'location'=>$location,
                        'source'=>$value->source,
                        'endArr'=>[
                            'location'=> $next->location ? json_decode($next->location) : null,
                            'source'=>$next->source,
                        ],
                    ];
                }
            } elseif ($value->attendance_status % 2 != 0) { //no clock out
                if ($value->shift->is_night) {
                    $nextTime = strtotime($value->date.' '.$value->shift->end_time);

                    if ($current > $nextTime) {
                        $nextTime = $value->date.' '.$value->shift->end_time;
                        $nextTime = date('Y-m-d h:i A', strtotime($value->date.' +1 day'));
                        $nextTime = strtotime($nextTime);
                    }
                } else {
                    $shift_time = $value->shift->shift_time;
                    $end_time = $value->shift->end_time;
                    if (strtotime($shift_time) > strtotime($end_time)) {
                        $nextTime = date('Y-m-d h:i A', strtotime($end_time.' +1 day'));
                    } else {
                        $nextTime = $value->date.' '.$value->shift->end_time;
                    }
                    $nextTime = strtotime($nextTime); //change at night shift
                }

                $difference = ($nextTime - $current) / 60;
                $lastclockin = date('h:i A', $current);
                $workTime += $difference;
                $timeDifference[] = [
                    'start'=> $value->time,
                    'end'=> $value->shift->end_time,
                    'duration'=> $difference,
                    'type'=>'work',
                    'status'=>$value->attendance_status,
                    'id'=>$value->id,
                    'remark'=>'Adjusted Without ClockOut',
                    'location'=>$location,
                    'source'=>$value->source,
                ];

                $message = "Generated Without ClockOut (last clockin was {$lastclockin}";
            }
        }

        return ['timeDifference'=>$timeDifference, 'workTime'=>$workTime, 'breakTime'=>$breakTime, 'message'=>$message];
    }

    public static function calculateOvertime($workdone, $requiredwork)
    {
        if ($workdone > $requiredwork) {
            return $workdone - $requiredwork;
        }

        return 0;
    }

    public static function calculateOfficeTime($report)
    {
        $breakperiod = $report['breakduration']['duration'];

        if ($report['shift']->is_night) {
            $current = strtotime($report['shift']->shift_time);
            $nextTime = strtotime($report['shift']->end_time);

            if ($current > $nextTime) {
                $nextTime = date('Y-m-d').' '.$report['shift']->end_time;

                $nextTime = date('Y-m-d h:i A', strtotime($nextTime.' +1 day'));

                $nextTime = strtotime($nextTime);
            }
            $officeTime = $current - $nextTime;
        } else {
            $officeTime = strtotime($report['shift']->end_time) - strtotime($report['shift']->shift_time);
        }

        $requiredwork = ($officeTime / 60) - $breakperiod;

        return ['officeTime'=>$officeTime, 'requiredworkTime'=>$requiredwork];
    }

    public static function singleuserAttendanceReport($attendence, $report)
    {
        if (count($attendence) > 0) {
            $firstClockin = $attendence->sortBy('attendance_status')->first();

            $lastClockout = $attendence->where('attendance_status','>',
                        $firstClockin->attendance_status)
                        ->sortByDesc('attendance_status')
                        ->first();
            if ($lastClockout->attendance_status % 2 != 0) {
                $lastClockout = $attendence->where('attendance_status','<',
                        $lastClockout->attendance_status)
                        ->sortByDesc('attendance_status')
                        ->first();
            }
            $report['clockin'] = $firstClockin->time;
            $report['in_location'] = $firstClockin->location;
            $report['clockout'] = $lastClockout->time;
            $report['out_location'] = $firstClockin->location;
            $regulartime = strtotime($firstClockin->date.' '.$firstClockin->shift->shift_time);

            if (strtotime($report['clockin']) > $regulartime) {
                //late
                $lateby = strtotime($report['clockin']) - $regulartime;

                $report['lateby'] = $lateby / 60;

                $report['earlyby'] = null;
            } elseif (strtotime($report['clockin']) < $regulartime) {
                //early
                $earlyby = $regulartime - strtotime($report['clockin']);

                $report['earlyby'] = $earlyby / 60;

                $report['lateby'] = null;
            } else {
                $report['earlyby'] = null;

                $report['lateby'] = null;
            }

            $attendence = $attendence->sortBy('attendance_status');

            $attendenceReport = self::calculateBreakAndWork($attendence);

            $report['overTime'] = self::calculateOvertime($attendenceReport['workTime'], $report['requiredworkTime']);

            $report['summary'] = $attendenceReport;
        } else {
            $report['clockin'] = null;
            $report['clockout'] = null;
            $report['summary'] = null;
            $report['earlyby'] = null;
            $report['lateby'] = null;
            $report['overTime'] = null;
        }

        return $report;
    }

    public static function minutesToHours($minutes)
    {
        $hours = (int) ($minutes / 60);
        $minutes -= $hours * 60;

        return sprintf('%d:%02.0f', $hours, $minutes);
    }

    public static function reportSummary($ShiftAttendance = [], $userFilter = false, $userList = [])
    {
        $allReport = [];

        foreach ($ShiftAttendance as $shift_id=>$value) {
            $dates = $value->groupBy('date');
            // dd($dates);
            $shift = $value->first()->shift;

            $breaks = self::breakDuration($shift_id);

            $shiftReport = [
                'shift_name'=> $shift->shift_name,
                'shift_info'=>$shift,
                'shift_break'=>$breaks,
                'data_by_date' => [],

            ];

            $userInShift = self::getShiftusers($shift_id);

            if ($userFilter) {
                $userInShift = array_intersect($userInShift, $userList);
            }

            $users = \App\User::whereIn('id', $userInShift)
                    ->where('enabled', '1')
                    ->get();

            foreach ($dates as $d=>$dateShift) {
                $reportByDate = [
                    'date'=> $d,
                    'data'=>[],
                ];
                foreach ($users as $k=>$user) {
                    $report = [];
                    $report['user'] = $user;
                    $report['shift'] = $shift;
                    $report['breakduration'] = $breaks;

                    $officeTime = self::calculateOfficeTime($report);

                    $report['officeTime'] = $officeTime['officeTime'] / 60;

                    $report['requiredworkTime'] = $officeTime['requiredworkTime'];

                    $attendence = $dateShift->where('user_id', $user->id);

                    $report = self::singleuserAttendanceReport($attendence, $report);

                    $reportByDate['data'][] = $report;
                }

                $shiftReport['data_by_date'][] = $reportByDate;
            }

            $allReport[] = $shiftReport;
        }

        return ['result'=>$allReport];
    }

    public static function getUserAttendanceHistroy($user_id, $start_date, $end_date)
    {
        $attendence = ShiftAttendance::where('date', '>=', $start_date)->where('date', '<=', $end_date)
                                    ->where('user_id', $user_id)
                                    ->get();

        return $attendence;
    }

    public static function checkUserLeave($user_id, $date)
    {  
        $userLeave = \App\Models\LeaveApplication::where('leave_start_date', '<=', $date)->where('leave_end_date', '>=', $date)->where('user_id', $user_id)->where('application_status', '2')
            ->first();

        return $userLeave;
    }

    public static function getshiftInfo($shift_id, $report = [])
    {
        $breaks = self::breakDuration($shift_id);

        $report['shift'] = \App\Models\Shift::find($shift_id);

        $report['breakduration'] = $breaks;

        $officeTime = self::calculateOfficeTime($report);

        $report['officeTime'] = $officeTime['officeTime'] / 60;

        $report['requiredworkTime'] = $officeTime['requiredworkTime'];

        $report['shift_name'] = $report['shift']->shift_name;

        return $report;
    }

    public static function singleuserAttendanceReportWithShift($attendance = [])
    {
        //attendence record with shift group must be passed eg:- at ShiftAttendencReportControoler timeHistoryShow
        $allReport = [];

        foreach ($attendance as $shift_id => $value) {
            $report = [];

            $report = self::getshiftInfo($shift_id, $report);

            $report['user'] = \App\User::find($value->first()->user_id);

            $attendanceDate = $value->groupBy('date');

            $records = [];

            foreach ($attendanceDate as $ad=>$attend) {
                $records[] = [
                    'date'=>$ad,
                    'data'=>  self::singleuserAttendanceReport($attend, $report),
                ];
            }

            $report['data_by_date'] = $records;

            $allReport[] = $report;
        }

        return $allReport;
    }



    public static function getAbsentPesentHolidayUser(){
        $today = date('Y-m-d');

        $org_id = \Auth::user()->org_id;


        $on_leave = \App\Models\LeaveApplication::select('tbl_leave_application.*')
                    ->leftjoin('users', 'tbl_leave_application.user_id', '=', 'users.id')
                    ->where('users.org_id', $org_id)
                    ->where('leave_start_date', '<=', $today)
                    ->where('leave_end_date', '>=', $today)
                    ->where('application_status', '2')->get();

        $present_user  = \App\Models\ShiftAttendance::select('users.*')->leftjoin('users', 'shift_attendance.user_id', '=', 'users.id')
                        ->where('users.enabled','1')
                        ->where('users.org_id', $org_id)
                        ->where('date',date('Y-m-d'))
                        ->groupBy('users.id')
                        ->get();

        $present_user_list = $present_user->pluck('id')->toArray();

        $absent_user = \App\User::where('enabled', '1')->whereNotIn('id',$present_user_list)->get();
      
        return ['l'=>$on_leave,'p'=>$present_user,'a'=>$absent_user];



    }
}
