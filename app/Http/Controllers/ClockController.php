<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Clock;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class ClockController extends Controller
{
    /**
     * @var Client
     */
    private $client;
    private $attendance;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $client
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
    }

    public function clockin()
    {
        $location = json_decode(\Request::get('location'), true);
        if ($location) {
            $inloc = $location['lat'].','.$location['long'];
            $inloc = ['latitude'=>$location['lat'], 'longitude'=>$location['long']];
            $inloc = json_encode(\TaskHelper::getLocDetails($inloc));
        } else {
            $inloc = null;
        }
        $chk_attendance = Attendance::where('user_id', Auth::user()->id)->where('date_in', date('Y-m-d'))->first();
        if ($chk_attendance) {
            Attendance::where('attendance_id', $chk_attendance->attendance_id)->update([
                'clocking_status' => 1, ]);
            $clock = Clock::where('attendance_id', $chk_attendance->attendance_id)
                    ->orderBy('clock_id', 'desc')
                    ->first();
            if ($clock->clockin_time) {
                $clock = new Clock();
                $clock->attendance_id = $chk_attendance->attendance_id;
                $clock->clockin_time = date('H:i:s');
                $clock->clocking_status = 1;
                $clock->ip_address = \Request::getClientIp();
                $clock->in_device = 'Web';
                $clock->save();
            } else {
                Clock::where('clock_id', $clock->clock_id)->update(['clockout_time' => date('H:i:s'), 'clocking_status' => 1, 'in_device'=>'Web']);
            }
        } else {
            $attendance = new Attendance();
            $attendance->user_id = Auth::user()->id;
            $attendance->org_id = Auth::user()->org_id;
            $attendance->leave_category_id = 0;
            $attendance->date_in = date('Y-m-d');
            $attendance->attendance_status = 1;
            $attendance->clocking_status = 1;
            $attendance->inLoc = $inloc;
            $attendance->save();

            $clock = new Clock();
            $clock->attendance_id = $attendance->id;
            $clock->clockin_time = date('H:i:s');
            $clock->clocking_status = 1;
            $clock->ip_address = \Request::getClientIp();
            $clock->in_device = 'Web';
            $clock->save();
        }
        Flash::success('You have been successfully Clocked In.');

        return Redirect::back();
    }

    public function clockout()
    {
        $location = json_decode(\Request::get('location'), true);
        if ($location) {
            $outloc = $location['lat'].','.$location['long'];
            $outloc = ['latitude'=>$location['lat'], 'longitude'=>$location['long']];
            $outloc = json_encode(\TaskHelper::getLocDetails($outloc));
        } else {
            $outloc = null;
        }
        $chk_attendance = Attendance::where('user_id', Auth::user()->id)->where('clocking_status', '1')->first();
        if ($chk_attendance) {
            Attendance::where('attendance_id', $chk_attendance->attendance_id)->update(['date_out' => date('Y-m-d'), 'org_id'=>\Auth::user()->org_id, 'clocking_status' => 0, 'outLoc'=>$outloc]);

            $clock = Clock::where('attendance_id', $chk_attendance->attendance_id)
                    ->orderBy('clock_id', 'desc')
                    ->first();
            if ($clock->clockout_time) {
                $clock = new Clock();
                $clock->attendance_id = $chk_attendance->attendance_id;
                $clock->clockout_time = date('H:i:s');
                $clock->clocking_status = 1;
                $clock->ip_address = \Request::getClientIp();
                $clock->out_device = 'Web';
                $clock->save();
            } else {
                Clock::where('clock_id', $clock->clock_id)->update(['clockout_time' => date('H:i:s'), 'clocking_status' => 1, 'out_device'=>'Web']);
            }
            Flash::success('You have been successfully Clocked Out.');
        } else {
            Flash::warning('You have already Clocked Out.');
        }

        return Redirect::back();
    }

    public function markAttendanceClockin($user_id)
    {
        $location = json_decode(\Request::get('location'), true);

        if ($location) {
            $inloc = $location['lat'].','.$location['long'];
        } else {
            $inloc = '0'.','.'0';
        }
        $chk_attendance = Attendance::where('user_id', $user_id)->where('date_in', date('Y-m-d'))->first();
        if ($chk_attendance) {
            Attendance::where('attendance_id', $chk_attendance->attendance_id)->update(['date_out' => null, 'clocking_status' => 1]);
            $clock = Clock::where('attendance_id', $chk_attendance->attendance_id)->first();
            Clock::where('clock_id', $clock->clock_id)->update(['clockout_time' => null, 'clocking_status' => 1]);
        } else {
            $attendance = new Attendance();
            $attendance->user_id = $user_id;
            $attendance->leave_category_id = 0;
            $attendance->date_in = date('Y-m-d');
            $attendance->attendance_status = 1;
            $attendance->clocking_status = 1;
            $attendance->inLoc = $inloc;
            $attendance->save();

            $clock = new Clock();
            $clock->attendance_id = $attendance->id;
            $clock->clockin_time = date('H:i:s');
            $clock->clocking_status = 1;
            $clock->ip_address = \Request::getClientIp();
            $clock->save();
        }
        Flash::success('You have been successfully Clocked In.');

        return Redirect::back();
    }

    public function markAttendanceClickout($user_id)
    {
        $location = json_decode(\Request::get('location'), true);

        if ($location) {
            $outloc = $location['lat'].','.$location['long'];
        } else {
            $outloc = '0'.','.'0';
        }

        $chk_attendance = Attendance::where('user_id', $user_id)->where('clocking_status', '1')->first();
        if ($chk_attendance) {
            Attendance::where('attendance_id', $chk_attendance->attendance_id)->update(['date_out' => date('Y-m-d'), 'clocking_status' => 0, 'outLoc'=>$outloc]);

            $clock = Clock::where('attendance_id', $chk_attendance->attendance_id)->first();
            Clock::where('clock_id', $clock->clock_id)->update(['clockout_time' => date('H:i:s'), 'clocking_status' => 0]);

            Flash::success('You have been successfully Clocked Out.');
        } else {
            Flash::warning('You have already Clocked Out.');
        }

        return Redirect::back();
    }
}
