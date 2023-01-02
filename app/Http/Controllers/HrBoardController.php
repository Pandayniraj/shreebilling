<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HrBoardController extends Controller
{
    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    private $org_id;

    public function __construct()
    {
        parent::__construct();
        // Protect all dashboard routes. Users must be authenticated.
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->org_id = \Auth::user()->org_id;

            return $next($request);
        });
    }

    public function hrBoard()
    {
        $page_title = 'HR DashBoard';

        $page_description = 'Details of Employes Activities';
        $birthdays = \App\User::orderBy('id', 'desc')->where('dob', \Carbon\Carbon::today())->get();

        $today = \Carbon\Carbon::today();

        $duration = \Carbon\Carbon::now()->addDays(20);

        $holidays = \App\Models\Holiday::where('start_date', '>=', $today)->where('start_date', '<=', $duration)->get();

        $onedayago = \Carbon\Carbon::now()->subDays(2);
        $activity = \App\Models\LeaveApplication::select('tbl_leave_application.*')
                    ->leftjoin('users', 'tbl_leave_application.user_id', '=', 'users.id')
                    ->where('users.org_id', $this->org_id)
                    ->where('application_date', '<=', $today)
                    ->where('application_date', '>=', $onedayago)
                    ->get();

        $on_leave = \App\Models\LeaveApplication::select('tbl_leave_application.*')
                    ->leftjoin('users', 'tbl_leave_application.user_id', '=', 'users.id')
                    ->where('users.org_id', $this->org_id)
                    ->where('leave_start_date', '<=', $today)
                    ->where('leave_end_date', '>=', $today)
                    ->where('application_status', '2')->get();

        $present_user  = \App\Models\ShiftAttendance::select('users.*')->leftjoin('users', 'shift_attendance.user_id', '=', 'users.id')
                        ->where('users.enabled','1')
                        ->where('users.org_id', $this->org_id)
                        ->where('date',date('Y-m-d'))
                        ->groupBy('users.id')
                        ->get();
        $present_user_list = $present_user->pluck('id')->toArray();

        $absent_user = \App\User::where('enabled', '1')->whereNotIn('id',$present_user_list)->get();


        $pen_leave = \App\Models\LeaveApplication::select('tbl_leave_application.*')
                    ->leftjoin('users', 'tbl_leave_application.user_id', '=', 'users.id')
                    ->where('users.org_id', $this->org_id)
                    ->where('application_status', '1')->get();

        $allleaves = \App\Models\LeaveApplication::select('tbl_leave_application.*')
                    ->leftjoin('users', 'tbl_leave_application.user_id', '=', 'users.id')
                    ->where('users.org_id', $this->org_id)
                    ->orderBy('leave_start_date', 'desc')->get()->take(10);

        $dep_users = \DB::select("SELECT tbl_departments.departments_id, tbl_departments.deptname , COUNT(*) as total  FROM users LEFT JOIN tbl_departments ON users.departments_id = tbl_departments.departments_id WHERE users.departments_id != '0' AND users.enabled = '1' AND users.org_id = '$this->org_id'
            GROUP BY users.departments_id ");
        $user_by_dep_data = [];
        foreach ($dep_users as $key => $value) {
            $data = [
                'name'=>$value->deptname,
                'y'=>$value->total,
            ];
            array_push($user_by_dep_data, $data);
        }

        $today_date = date('Y-m-d');  //2020-03-15
        $date_after_x_days = date('Y-m-d', strtotime('+ 60 days'));

        $leaving_employee = \App\Models\UserDetail::select('user_details.*')
                    ->leftjoin('users', 'user_details.user_id', '=', 'users.id')
                    ->where('users.org_id', $this->org_id)
                    ->where('contract_end_date', '>=', $today_date)
                    ->where('contract_end_date', '<=', $date_after_x_days)
                    ->orderBy('contract_end_date', 'asc')->get();

        function x_week_range($date)
        {
            $ts = strtotime($date);
            $start = (date('w', $ts) == 0) ? $ts : strtotime('last monday', $ts);

            return [date('Y-m-d', $start), date('Y-m-d', strtotime('next sunday', $start))];
        }

        list($start_date, $end_date) = x_week_range($today_date);
        //dd($start_date, $end_date);
        $timesheet_info = [
            'total_project'=>\App\Models\Projects::count(),
            'total_user_involved'=>0,
            'total_active_project'=>0,
            'total_regular_cost' => 0,
            'total_overtime_cost' => 0,
            'total_cost' => 0,
            'start_date'=>$start_date,
            'end_date'=>$end_date,
        ];

        $project_ids = [];
        $timesheet_project_chart = [];
        $timesheet_project_chart_by_cost = [];
        $working_employee = \App\Models\TimeSheet::where('date', '>=', $start_date)->where('date', '<=', $end_date)->groupBy('employee_id')->get();
        foreach ($working_employee as $key => $value) {
            if ($value->employee->project) {
                $project = $value->employee->project;

                $total_time = \TaskHelper::GetTimeDifference($value->time_from, $value->time_to);
                $template = \PayrollHelper::getTimeSheetSalaryDetails($value->employee_id)->template;
                $salary = \PayrollHelper::timeSheetSalaryPerDay($template, $total_time);
                $timesheet_info['total_regular_cost'] += $salary['regular_salary'];
                $total_regular_cost['total_overtime_cost'] += $salary['overtime_salary'];
                $timesheet_info['total_user_involved']++;
                $total = $salary['regular_salary'] + $salary['overtime_salary'];
                $timesheet_info['total_cost'] = $timesheet_info['total_cost'] + $total;

                if (! in_array($project->id, $project_ids)) {
                    $project_ids[] = $value->employee->project->id;
                    $timesheet_project_chart[$project->id] = ['name'=>$project->name, 'y'=>1]; //adds first one employee
                    $timesheet_project_chart_by_cost[$project->id] = ['name'=>$project->name, 'y'=>$total]; //adds first cost
                    $timesheet_info['total_active_project']++;
                } else {
                    $timesheet_project_chart[$project->id]['y']++; //adds another employee
                    $timesheet_project_chart_by_cost[$project->id]['y'] = $timesheet_project_chart_by_cost[$project->id]['y'] + $total; //adds another cost
                }
            }
        }

        $timesheet_project_chart = array_values($timesheet_project_chart);
        $timesheet_project_chart_by_cost = array_values($timesheet_project_chart_by_cost);

        $user_dep = \App\User::where('enabled', '1')->where('org_id', $this->org_id)->where('departments_id', '!=', '0')->get()->groupBy('departments_id');
        $dep_by_user_salary = [];
        foreach ($user_dep as $key => $dep) {
            $salary = 0;
            foreach ($dep as $key => $user) {
                $salary += $this->getEmployeSalary($user->id);
            }
            $data = ['name'=>$dep[0]->department->deptname, 'y'=>$salary];
            array_push($dep_by_user_salary, $data);
        }
        $male_female_count = DB::select("
        SELECT SUM(CASE WHEN user_details.gender = 'Male' THEN 1 ELSE 0 END) as male,
               SUM(CASE WHEN user_details.gender = 'Female' THEN 1 ELSE 0 END) as female
        FROM user_details
        LEFT JOIN users ON users.id = user_details.user_id
        WHERE users.org_id = '$this->org_id' 
        ORDER BY user_id
        ");
        $male_female_data = [
            ['name'=>'Male', 'y'=>(int) $male_female_count[0]->male],
            ['name'=>'Female', 'y'=>(int) $male_female_count[0]->female],
        ];

        //dd($timesheet_project_chart,$user_by_dep_data);

        return view('hrboard', compact('page_title', 'page_description', 'birthdays', 'holidays', 'activity', 'on_leave', 'pen_leave', 'allleaves', 'user_by_dep_data', 'timesheet_project_chart', 'timesheet_info', 'timesheet_project_chart_by_cost', 'leaving_employee', 'absent_user', 'present_user', 'male_female_data', 'dep_by_user_salary'));
    }

    private function getEmployeSalary($user_id)
    {
        $template = \PayrollHelper::getEmployeePayroll($user_id)->template ?? null;
        $net_salary = $template->basic_salary ?? null;
        $allowances = \PayrollHelper::getSalaryAllowance($template->salary_template_id??null);
        $deductions = \PayrollHelper::getSalaryDeduction($template->salary_template_id??null);
        foreach ($allowances as $ak => $av) {
            $net_salary += is_numeric($av->allowance_value) ? $av->allowance_value : 0;
        }
        foreach ($deductions as $dk => $dv) {
            $net_salary -= is_numeric($dv->deduction_value) ? $dv->deduction_value : 0;
        }

        return $net_salary;
    }
}
