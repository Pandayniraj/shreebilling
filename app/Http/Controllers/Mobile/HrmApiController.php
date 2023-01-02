<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Auth;
use Illuminate\Http\Request;

class HrmApiController extends Controller
{
    private $userinfo;

    private function authorizeToken($token)
    {
        $checktoken = \App\Models\Usertoken::where('token', $token)->first();
        if ($request->secret == 'apptesting9841') { //just for testing bug need to be removed
            return true;
        }
        if (! $checktoken) {
            abort(404);
        } else {
            $this->userinfo = $checktoken;
            Auth::loginUsingId($checktoken->user_id);
        }

        return $checktoken;
    }

    public function dashboard($token, $user_id)
    {
        $this->authorizeToken($token);
        $total_travelrequest = \App\Models\TravelRequest::where('user_id', $user_id)->count();
        $start_date = date('Y-m').'-01';
        $totaldays = \Carbon\Carbon::parse($start_date)->daysInMonth;
        $end_date = date('Y-m').'-'.$totaldays;
        $month_holidays = \App\Models\Holiday::where('start_date', '>=', $start_date)->where('end_date', '<=', $end_date)->count();

        $unseen_message = \DB::table('messages')->where('messages.is_seen', '!=', '1')->where('messages.user_id', '!=', $user_id)
              ->leftjoin('conversations', 'messages.conversation_id', '=', 'conversations.id')
              ->where(function ($query) use ($user_id) {
                  return  $query->where('conversations.user_one', $user_id)->orWhere('conversations.user_two', $user_id);
              })->distinct('messages.conversation_id')
              ->get();

        $total_unseen = count($unseen_message);

        return ['travelrequest'=>$total_travelrequest, 'holidays'=>$month_holidays,
        'messages'=>$total_unseen, ];
    }

    //leave management
    public function leaveindex($token, $user_id)
    {
        $this->authorizeToken($token);
        $category = \App\Models\LeaveCategory::all();
        $leaves = 0;
        $totalLeaves = 0;
        $user = \App\User::find($user_id);

        foreach ($category as $key => $cv) {
            $leavesTaken = \TaskHelper::userLeave($user_id, $cv->leave_category_id,
                date('Y'));
            $userleave[] = ['category'=>$cv->leave_category, 'leavetaken'=>$leavesTaken, 'quota'=>$cv->leave_quota];
            $leaves += $leavesTaken;
            $totalLeaves += $cv->leave_quota;
        }

        $request_to = \App\Models\Team::where('org_id', $user->org_id)
                        ->select('name as username', 'id')->get();
        $userleave[] = ['category'=>'Total', 'leavetaken'=>$leaves, 'quota'=>$totalLeaves];
        $myleaves = \App\Models\LeaveApplication::leftjoin('tbl_leave_category',
            'tbl_leave_application.leave_category_id', '=', 'tbl_leave_category.leave_category_id')->orderBy('leave_start_date', 'desc')->where('tbl_leave_application.user_id', $user_id)->distinct('leave_application_id')->get();

        return ['leavescounts'=>$userleave, 'leavesdata'=>$myleaves, 'allcategory'=>$category, 'request_to'=>$request_to];
    }

    public function leavecategory()
    {
        $category = \App\Models\LeaveCategory::all();

        return ['category'=>$category];
    }

    public function postleave(Request $request, $token)
    {
        $this->authorizeToken($token);
        $leaves = json_decode($request->data, true);

        if (isset($leaves['attachment'])) {
            $destinationPath = public_path('/leave_files/');
            if (! \File::isDirectory($destinationPath)) {
                \File::makeDirectory($destinationPath, 0777, true, true);
            }
            $image = $leaves['attachment'];
            error_log($image);
            $img_name = time().''.rand().''.'.jpg';
            file_put_contents($destinationPath.$img_name, base64_decode($image));
            $leaves['attachment'] = $img_name;
        }
        $leaves['leave_days'] = \TaskHelper::findDays($leaves['leave_start_date'], $leaves['leave_end_date']);

        if (\App\Models\LeaveApplication::create($leaves)) {
            return ['d'=>'sucess'];
        } else {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    }

    //payroll
    public function payrollindex($token, $user_id)
    {
        $this->authorizeToken($token);
        $salarypayment = \App\Models\SalaryPayment::where('user_id', $user_id)->orderBy('salary_payment_id', 'desc')->take(20)->get();
        $usersalary = [];
        foreach ($salarypayment as $key => $salary) {
            $temp['salary_grade'] = $salary->salary_grade;
            $temp['department'] = $salary->user->department->deptname;
            $temp['gross_salary'] = $salary->gross_salary;
            $temp['total_allowance'] = $salary->total_allowance;
            $temp['total_deduction'] = $salary->total_deduction;
            $temp['fine_deduction'] = $salary->fine_deduction;
            $temp['overtime'] = $salary->overtime;
            $temp['net_salary'] = $temp['gross_salary'] + $temp['total_allowance'] - $temp['total_deduction'];
            $temp['total_salary'] = $temp['net_salary'] + $temp['overtime'] - $temp['fine_deduction'];
            $temp['paid_date'] = $salary->paid_date;
            $usersalary[] = $temp;
        }

        return ['usersalary'=>$usersalary];
    }

    public function attendencehistory($token, $user_id, $date_in)
    {
        $this->authorizeToken($token);
        $totaldays = \Carbon\Carbon::parse($date_in.'-01')->daysInMonth;
        $history = \DB::table('tbl_attendance')
                        ->select('tbl_clock.clock_id', 'tbl_clock.clockin_time', 'tbl_clock.clockout_time', 'tbl_clock.ip_address', 'tbl_attendance.*')
                        ->join('tbl_clock', 'tbl_clock.attendance_id', '=', 'tbl_attendance.attendance_id')
                        ->where('tbl_attendance.date_in', '>=', $date_in.'-01')
                        ->where('tbl_attendance.date_in', '<=', $date_in.'-'.$totaldays)
                        ->where('tbl_attendance.user_id', $user_id)
                        ->orderBy('tbl_clock.clock_id', 'desc')
                        ->get();
        $reports = [];
        foreach ($history as $hk => $hv) {
            $temp['date_in'] = $hv->date_in;
            $temp['date_out'] = $hv->date_out;
            $temp['clockin_time'] = date('h:i a', strtotime($hv->clockin_time));
            $temp['clockout_time'] = date('h:i a', strtotime($hv->clockout_time));
            $temp['ip_address'] = $hv->ip_address;
            if ($hv->inLoc) {
                $loc = json_decode($hv->inLoc);
                $temp['clockin_loc'] = $loc->street_name;
            } else {
                $temp['clockin_loc'] = '';
            }
            if ($hv->outLoc) {
                $loc = json_decode($hv->outLoc);
                $temp['clockout_loc'] = $loc->street_name;
            } else {
                $temp['clockout_loc'] = '';
            }
            $startTime = \Carbon\Carbon::parse(date('Y-m-d H:i:s', strtotime($hv->date_in.' '.$hv->clockin_time)));
            $finishTime = \Carbon\Carbon::parse(date('Y-m-d H:i:s', strtotime($hv->date_out.' '.$hv->clockout_time)));
            $totalDuration = $finishTime->diffInSeconds($startTime);
            if (gmdate('d', $totalDuration) != '01') {
                $hour = ((gmdate('d', $totalDuration) - 1) * 24) + gmdate('H', $totalDuration);
            } else {
                $hour = gmdate('H', $totalDuration);
            }
            $minute = gmdate('i', $totalDuration);
            $temp['hours'] = $hour.':'.$minute.' m';
            $reports[] = $temp;
        }

        return ['attendence'=>$reports];
    }

    public function holiday($token, $date_in)
    {
        $this->authorizeToken($token);
        $totaldays = \Carbon\Carbon::parse($date_in.'-01')->daysInMonth;
        $start_date = $date_in.'-01';

        $end_date = $date_in.'-'.$totaldays;
        $holidays = \App\Models\Holiday::where('start_date', '>=', $start_date)->where('end_date', '<=', $end_date)->get();

        return ['data'=>$holidays];
    }

    public function travelrequest($token, $user_id)
    {
        $this->authorizeToken($token);
        $travelrequest = \App\Models\TravelRequest::select('travel_requests.*', 'clients.name as business_account', 'users.username as staff')->leftjoin('clients', 'clients.id', '=', 'travel_requests.business_account')->leftjoin('users', 'users.id', '=', 'travel_requests.staff_id')->where('travel_requests.user_id', $user_id)
            ->get();

        return ['data'=>$travelrequest];
    }

    public function create_travelrequest($token)
    {
        $this->authorizeToken($token);
        $clients = \App\Models\Client::all();
        $staff = \App\User::where('enabled', '1')->get();

        return ['clients'=>$clients, 'staff'=>$staff];
    }

    public function store_travelrequest(Request $request, $token)
    {
        $this->authorizeToken($token);
        $travelrequest = $request->formdata;
        $travelrequest = json_decode($travelrequest, true);
        \App\Models\TravelRequest::create($travelrequest);

        return ['sucess'=>true];
    }
}
