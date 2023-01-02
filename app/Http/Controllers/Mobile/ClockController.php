<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Clock;
use Flash;
use Illuminate\Http\Request;
use AttendanceHelper;
/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class ClockController extends Controller
{


 

    public function clockin(Request $request)
    {


        $uid = $request->user_id;
        \Auth::loginUsingId($uid);
        if ($request->latitude && $request->longitude) {
            $inloc = ['lat'=>$request->latitude, 'long'=>$request->longitude];
            $request->request->add(['location' => json_encode($inloc)]);
        } else {
            $inloc = null;
        }

        $makeClock = AttendanceHelper::clockin();
        // error_log(\Request::get('location'));
        if($makeClock['success']){

           return ['result'=>true];

        }else{

           abort(403);

        }

        return ['result'=>true];
    }

    public function clockout(Request $request)
    {
        
        $uid = $request->user_id;
        \Auth::loginUsingId($uid);
        if ($request->latitude && $request->longitude) {
            $outloc = ['lat'=>$request->latitude, 'long'=>$request->longitude];
            $request->request->add(['location' => json_encode($outloc)]);
        } else {
            $outloc = null;
        }
        

        $makeClock = AttendanceHelper::clockout(json_encode($outloc));

        if($makeClock['success']){

           return ['result'=>true];

        }else{

           abort(403);

        }
    }

    public function clocking_status(Request $request)
    {
        
        $uid = $request->user_id;
        

        $attendance_log = \App\Models\ShiftAttendance::where('user_id', $uid)->where('date', date('Y-m-d'))->orderBy('attendance_status', 'desc')->first();


        if ($attendance_log && ($attendance_log->attendance_status % 2) != 0) {
            $time = \Carbon\Carbon::createFromTimeStamp(strtotime($attendance_log->clockinstart()->time))->diffForHumans() ;

            return ['result'=>true, 'time'=>$time];
        } else {
            return ['result'=>false];
        }
    }
}
