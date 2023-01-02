<?php

namespace App\Http\Controllers;

use App\Models\ShiftAttendance;
use Flash;
use Illuminate\Http\Request;

class MarkShiftAttendanceController extends Controller
{
    public function create()
    {
        $users = \App\User::where('enabled', '1')->pluck('username', 'id')->all();
        $page_title = 'Mark | Attendance';
        $shift = \App\Models\Shift::all();

        return view('admin.shift_attendance.markattendance.create', compact('page_title', 'users', 'shift'));
    }

    public function viewStatus($empid)
    {
        if (\Request::get('date')) {
            $date = \Request::get('date');
        } else {
            $date = date('Y-m-d');
        }

        $checkStatus = ShiftAttendance::where('date', $date)->where('user_id', $empid)
                        ->orderBy('attendance_status', 'desc')
                        ->first();

        if ($checkStatus) {
            $nextOp = $checkStatus->attendance_status % 2 == 0 ? 'In' : 'Out';

            $lastActive = date('h:i A', strtotime($checkStatus->time));

            $lastStatus = $checkStatus->attendance_status % 2 == 0 ? 'Out' : 'In';

            $message = $nextOp." ( Last  {$lastStatus} at  {$lastActive} )";

            $shift = $checkStatus->shift_id;

            $requiredDate = $checkStatus->date;
        } else {
            $nextOp = 'In';
            $lastActive = null;
            $lastStatus = null;
            $message = 'In';
            $shift = '';
            $requiredDate = date('Y-m-d');
        }

        return ['message'=>$message, 'lastActive'=>$lastActive, 'nextOp'=>$nextOp, 'shift'=>$shift, 'requiredDate'=>$requiredDate];
    }

    private function validateShift($shift_id, $time, $assigned_shift)
    {
        $checkShift = \App\Models\Shift::find($shift_id);

        if ($shift_id == $assigned_shift) {
            return ['allow'=>true];
        }

        if (strtotime($checkShift->shift_time) <= strtotime($time) && strtotime($checkShift->shift_time) >= strtotime($time)) {
            return ['allow'=>false, 'message'=>"Shift {$checkShift->shift_name } Has Not been Completed"];
        }

        return ['allow'=>true];
    }

    public function store(Request $request)
    {
        $attendance = [
            'user_id'=>$request->employee_id,
            'date'=>$request->date,
            'time'=>$request->date.' '.$request->time,
            'shift_id'=>$request->shift_id,
            'ip_address'=>\Request::getClientIp(),
            'remarks'=>$request->comments,
        ];
        $lastAttendence = ShiftAttendance::where('user_id', $attendance['user_id'])
                            ->where('date', $attendance['date'])
                            ->orderBy('attendance_status', 'desc')
                            ->first();

        if ($lastAttendence) {
            $checkShift = $this->validateShift($lastAttendence->shift_id, $request->time, $attendance['shift_id']);

            if (! $checkShift['allow']) {
                Flash::error($checkShift['message']);

                return redirect()->back();
            }

            //everything is ok
            $nextStatus = $lastAttendence ? ($lastAttendence->attendance_status + 1) : 1;
        } else {
            $nextStatus = 1;
        }

        $location = json_decode(\Request::get('locations'), true);
        if ($location) {
            $location = ['latitude'=>$location['lat'], 'longitude'=>$location['long']];

            $location = json_encode(\TaskHelper::getLocDetails($location));
        } else {
            $location = null;
        }
        $attendance['location'] = $location;

        $attendance['attendance_status'] = $nextStatus;

        $attendance = ShiftAttendance::create($attendance);

        if ($attendence->attendance_status % 2 == 0) {
            Flash::success('SuccessFully Clock Out');
        } else {
            Flash::success('SuccessFully Clock In');
        }

        return redirect()->back();
    }

    public function createBulk()
    {
        $page_title = 'Bulk | Attendance';
        if(\Auth::user()->hasRole('admins')){


            $department = \App\Models\Department::all();

        }elseif(\Auth::user()->department_head){

            $department = \App\Models\Department::where('departments_id',\Auth::user()->departments_id)->get();

        }

        $page_title = 'Bulk Attendance';
        $page_description = 'Attendance For Multiple Employee';
        $shift = \App\Models\Shift::all();

        return view('admin.shift_attendance.markattendance.bulkcreate', compact('page_title', 'department', 'page_title', 'page_description', 'shift'));
    }

    public function createBulkNext(Request $request)
    {
        if(\Auth::user()->hasRole('admins')){


            $department = \App\Models\Department::all();

        }elseif(\Auth::user()->department_head){

            $department = \App\Models\Department::where('departments_id',\Auth::user()->departments_id)->get();
        
        }

        $shift_id = $request->shift_id;
        $departments_id = $request->dep_id;

        $users = \App\User::where(function ($query) use ($departments_id) {
            if ($departments_id || !\Auth::user()->hasRole('admins')) {
                return $query->where('departments_id', $departments_id);
            }
        })->where(function ($query) use ($shift_id) {
            if ($shift_id) {
                $userList = \AttendanceHelper::getShiftusers($shift_id);

                return $query->whereIn('id', $userList);
            }
        })
                ->where('enabled', '1')->get();

        $page_title = 'Bulk Attendance';
        $page_description = 'Attendance For Multiple Employee';
        $userArr = [];
        $shift = \App\Models\Shift::all();

        foreach ($users as $key=>$value) {
            $list = [];
            $list['user'] = $value;
            $status = $this->viewStatus($value->id);
            $list['status'] = $status;
            $userArr[] = $list;
        }

        return view('admin.shift_attendance.markattendance.bulkcreate', compact('page_title', 'department', 'page_title', 'page_description', 'userArr', 'shift', 'departments_id', 'shift_id'));
    }

    public function createBulkSave(Request $request)
    {
        $location = json_decode(\Request::get('locations'), true);
        if ($location) {
            $location = ['latitude'=>$location['lat'], 'longitude'=>$location['long']];

            $location = json_encode(\TaskHelper::getLocDetails($location));
        } else {
            $location = null;
        }
        $errors = [];
        $ip_address = \Request::getClientIp();
        foreach ($request->employee_id as $key=>$emp) {
            $attendance = [
                'user_id'=>$emp,
                'date'=>$request->date[$key],
                'time'=>$request->date[$key].' '.$request->time[$key],
                'shift_id'=>$request->shift_id[$key],
                'ip_address'=>$ip_address,
                'remarks'=>$request->comments[$key],
                'location'=>$location,

            ];
            $lastAttendence = ShiftAttendance::where('user_id', $attendance['user_id'])
                                ->where('date', $attendance['date'])
                                ->orderBy('attendance_status', 'desc')
                                ->first();

            if ($lastAttendence) {
                $checkShift = $this->validateShift($lastAttendence->shift_id, $request->time, $attendance['shift_id']);

                if (! $checkShift['allow']) {
                    $error[] = \App\User::find($attendance['user_id'])->username;
                } else {
                    //everything is ok
                    $nextStatus = $lastAttendence ? ($lastAttendence->attendance_status + 1) : 1;

                    $attendance['attendance_status'] = $nextStatus;

                    $attendance = ShiftAttendance::create($attendance);
                }
            } else {
                $nextStatus = 1;

                $attendance['attendance_status'] = $nextStatus;

                $attendance = ShiftAttendance::create($attendance);
            }
        }

        if (count($error) > 0) {
            $message = implode(',', $error);
            $message = 'These users '.$message.' has not matching shift please vaidate them and try again';
            Flash::warning($message);
        } else {
            Flash::success('Attendance SuccessFully Posted');
        }

        return redirect()->back();
    }
}
