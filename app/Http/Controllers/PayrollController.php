<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\EmployeePayroll;
use App\Models\Role as Permission;
use App\Models\SalaryAllowance;
use App\Models\SalaryDeduction;
use App\Models\SalaryTemplate;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class PayrollController extends Controller
{
    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
    }

    public function salaryTemplate()
    {
        $page_title = 'Salary Template';
        $page_description = 'All Salary Templates';

        $templates = SalaryTemplate::orderBy('created_at', 'desc')->get();

        return view('admin.payroll.salary_template.index', compact('page_title', 'page_description', 'templates'));
    }

    public function storeSalaryTemplate(Request $request)
    {
        /* if(!$training->isEditable())
        {
            abort(403);
        } */

        $attributes = $request->only('salary_grade', 'basic_salary', 'overtime_salary', 'sick_salary', 'annual_salary', 'public_holiday_salary', 'other_leave_salary',
            'gratuity_salary', 'annual_leave_salary');
        //dd($attributes);

        if (! $request->salary_template_id) {
            $salaryTempl = SalaryTemplate::create($attributes);
            $salary_template_id = $salaryTempl->id;
            Flash::success('Salary Template created Successfully');
        } else {
            $salary_template_id = $request->salary_template_id;
            $salaryTempl = SalaryTemplate::where('salary_template_id', $salary_template_id)->update($attributes);

            SalaryAllowance::where('salary_template_id', $salary_template_id)->delete();
            SalaryDeduction::where('salary_template_id', $salary_template_id)->delete();
            Flash::success('Salary Template updated Successfully');
        }

        // To store Allowance
        $allowance_label = [];
        $allowance_value = [];
        if ($request->house_rent_allowance != '') {
            array_push($allowance_label, 'House Rent Allowance');
            array_push($allowance_value, $request->house_rent_allowance);
        }
        if ($request->medical_allowance != '') {
            array_push($allowance_label, 'Medical Allowance');
            array_push($allowance_value, $request->medical_allowance);
        }

        if ($request->allowance_label) {
            $allowance_label = array_merge($allowance_label, $request->allowance_label);
        }
        if ($request->allowance_value) {
            $allowance_value = array_merge($allowance_value, $request->allowance_value);
        }

        $all_allowance = [];

        foreach ($allowance_label as $alk => $alv) {
            if ($alv != '') {
                array_push($all_allowance, ['salary_template_id' => $salary_template_id, 'allowance_label' => $alv, 'allowance_value' => $allowance_value[$alk]]);
            }
        }
        //dd($all_allowance);
        SalaryAllowance::insert($all_allowance);

        // To store Deduction
        $deduction_label = [];
        $deduction_value = [];
        if ($request->provident_fund != '') {
            array_push($deduction_label, 'Provident Fund');
            array_push($deduction_value, $request->provident_fund);
        }
        if ($request->tax_deduction != '') {
            array_push($deduction_label, 'Tax Deduction');
            array_push($deduction_value, $request->tax_deduction);
        }
        if ($request->deduction_label) {
            $deduction_label = array_merge($deduction_label, $request->deduction_label);
        }
        if ($request->deduction_value) {
            $deduction_value = array_merge($deduction_value, $request->deduction_value);
        }

        $all_deduction = [];
        foreach ($deduction_label as $dlk => $dlv) {
            if ($dlv != '') {
                array_push($all_deduction, ['salary_template_id' => $salary_template_id, 'deduction_label' => $dlv, 'deduction_value' => $deduction_value[$dlk]]);
            }
        }
        SalaryDeduction::insert($all_deduction);

        return Redirect::back();
    }

    public function manageEmpSalaryTemp($payroll_id)
    {
        $current_temp = EmployeePayroll::where('payroll_id', $payroll_id)->first();
        $templates = SalaryTemplate::orderBy('created_at', 'desc')->get();
        $histroy = \App\Models\EmpSalaryTemplateHistroy::where('user_id', $current_temp->user_id)->orderBy('id', 'desc')->get();

        return view('admin.payroll.salary_template.edit_emp_sal_temp', compact(
            'current_temp', 'templates', 'histroy'));
    }

    public function manageEmpSalaryTempPost(Request $request, $payroll_id)
    {
        $current_temp = EmployeePayroll::where('payroll_id', $payroll_id)
                        ->first();
        EmployeePayroll::where('payroll_id', $payroll_id)->update(['salary_template_id'=>$request->salary_template_id]);
        //update histroy
        $histroy = [
            'user_id'=> $current_temp->user_id,
            'created_by'=>\Auth::user()->id,
            'salary_template_id'=> $current_temp->salary_template_id,
        ];
        \App\Models\EmpSalaryTemplateHistroy::create($histroy);
        Flash::success('Salary Template Successfully Updated');

        return redirect()->back();
    }

    public function editSalaryTemplate($salary_template_id)
    {
        $page_title = 'Salary Template';
        $page_description = 'All Salary Templates';

        $templates = SalaryTemplate::orderBy('created_at', 'desc')->get();
        $salaryTemplate = SalaryTemplate::where('salary_template_id', $salary_template_id)->first();

        if (! $salaryTemplate) {
            Flash::warning('Salary Template does not found.');

            return redirect('/admin/payroll/salary_template');
        }

        return view('admin.payroll.salary_template.index', compact('page_title', 'page_description', 'templates', 'salaryTemplate'));
    }

    public function destroySalaryTemplate($salary_template_id)
    {
        $temp = SalaryTemplate::where('salary_template_id', $salary_template_id)->delete();
        if (! $temp) {
            Flash::warning('Sorry! Salary Template does not found.');

            return redirect('/admin/payroll/salary_template');
        }
        SalaryDeduction::where('salary_template_id', $salary_template_id)->delete();
        SalaryAllowance::where('salary_template_id', $salary_template_id)->delete();

        Flash::success('Salary Template deleted Successfully');

        return Redirect::back();
    }

    public function showSalaryTemplate($salary_template_id)
    {
        $salaryTemplate = SalaryTemplate::where('salary_template_id', $salary_template_id)->first();

        $returnHTML = view('admin.payroll.salary_template.show_modal', ['salaryTemplate'=> $salaryTemplate])->render();

        return $returnHTML;
    }

    public function generatePdfSalarytemplate($salary_template_id)
    {
        $salaryTemplate = SalaryTemplate::where('salary_template_id', $salary_template_id)->first();

        $pdf = \PDF::loadView('admin.payroll.salary_template.pdfgenerate', compact('salaryTemplate'));
        $file = 'Salary Template - '.$salaryTemplate->salary_grade.'.pdf';

        return $pdf->download($file);
    }

    public function generatePrintSalarytemplate($salary_template_id)
    {
        $salaryTemplate = SalaryTemplate::where('salary_template_id', $salary_template_id)->first();

        return view('admin.payroll.salary_template.printsalarytemplate', compact('salaryTemplate'));
    }

    public function manageSalaryDetails()
    {
        $page_title = 'Manage Salary';
        $page_description = 'Manage Salary';

        $departments = Department::orderBy('deptname', 'asc')->get();
        $salaryTemplates = SalaryTemplate::orderBy('salary_grade', 'asc')->get();

        return view('admin.payroll.salary_details', compact('page_title', 'page_description', 'departments', 'salaryTemplates'));
    }

    public function listSalaryDetails(Request $request)
    {
        $page_title = 'Manage Salary';
        $page_description = 'Manage Salary';

        $departments = Department::orderBy('deptname', 'asc')->get();
        $salaryTemplates = SalaryTemplate::orderBy('salary_grade', 'asc')->get();

        $users = User::select('id', 'first_name', 'last_name', 'departments_id', 'designations_id')->where('enabled', '1')->where('departments_id', $request->departments_id)->orderBy('first_name')->get();

        $departments_id = $request->departments_id;

        return view('admin.payroll.salary_details', compact('page_title', 'page_description', 'departments', 'salaryTemplates', 'users', 'departments_id'));
    }

    public function listDepartmentSalaryDetails($departments_id)
    {
        $page_title = 'Manage Salary';
        $page_description = 'Manage Salary';

        $departments = Department::orderBy('deptname', 'asc')->get();
        $salaryTemplates = SalaryTemplate::orderBy('salary_grade', 'asc')->get();

        $users = User::select('id', 'first_name', 'last_name', 'departments_id', 'designations_id')->where('enabled', '1')->where('departments_id', $departments_id)->orderBy('first_name')->get();

        return view('admin.payroll.salary_details', compact('page_title', 'page_description', 'departments', 'salaryTemplates', 'users', 'departments_id'));
    }

    public function storeSalaryDetails(Request $request)
    {
        //$departments_id = $request->departments_id;

        $user_ids = $request->user_id;
        $salary_template_ids = $request->salary_template_id;

        foreach ($user_ids as $uk => $uv) {
            EmployeePayroll::where('user_id', $uv)->delete();
            EmployeePayroll::create(['user_id' => $uv, 'salary_template_id' => $salary_template_ids[$uk]]);
        }

        Flash::success('Salary Detail Information Updated Successfully');

        return redirect('/admin/payroll/manage_salary_details/'.$request->departments_id);
    }

    public function employeeSalaryList()
    {
        $page_title = 'Employee Salary List';
        $page_description = 'Employee Salary List';

        $salaryLists = EmployeePayroll::whereNotIn('user_id', [1, 2, 3, 28])->get();

        return view('admin.payroll.salary_list', compact('page_title', 'page_description', 'salaryLists'));
    }

    public function destroySalary($payroll_id)
    {
        $temp = EmployeePayroll::where('payroll_id', $payroll_id)->first();

        if (! $temp) {
            Flash::warning('Sorry! Salary Detail does not found.');

            return redirect('/admin/payroll/employee_salary_list');
        }
        if (! $temp->isDeletable()) {
            abort(403);
        }

        EmployeePayroll::where('payroll_id', $payroll_id)->delete();
        Flash::success('Salary Detail deleted Successfully');

        return Redirect::back();
    }

    public function showSalaryDetail($payroll_id)
    {
        $payroll = EmployeePayroll::where('payroll_id', $payroll_id)->first();

        $returnHTML = view('admin.payroll.salary_detail_modal', ['payroll'=> $payroll])->render();

        return $returnHTML;
    }

    public function generatePdfSalaryDetail($payroll_id)
    {
        $payroll = EmployeePayroll::where('payroll_id', $payroll_id)->first();

        $pdf = \PDF::loadView('admin.payroll.salary_detail_modal', compact('payroll'));
        $file = 'Employee Salary Detail.pdf';

        return $pdf->download($file);
    }

    public function salary_summary()
    {
        $page_title = 'Salary Summary';
        $page_description = 'Manage Salary Summary';
        $dep = \App\Models\Department::all();
        $employee = [];
        foreach ($dep as $d) {
            $employee[$d->deptname] = \App\User::where('departments_id', $d->departments_id)->where('enabled', '1')->get();
        }

        return view('admin.payroll.salary_summary.index', compact('page_description', 'page_title', 'employee'));
    }

    public function salary_summary_details(Request $request)
    {
        $page_title = 'Salary Summary';
        $type = $request->type;
        $emp_id = $request->employee_id;
        $payment_month = $request->payment_month;
        $page_description = 'Manage Salary Summary';
        $users = DB::table('users')
                        ->select('first_name', 'last_name', 'id as user_id')
                        ->where('enabled', '1')
                        ->whereNotIn('users.id', [1, 2, 3, 28])
                        ->groupBy('users.id')
                        ->get();
        $date_start = null;$date_end = null;
        if (isset($payment_month) && $request->payment_month != '') {
            $date_start = $request->input('payment_month').'-01';
            $date_end = $request->input('payment_month').'-'.\Carbon\Carbon::parse($date_start)->daysInMonth;
        } else {
            $employee_name = \App\User::find($emp_id);
        }
        $dep = \App\Models\Department::all();
        $employee = [];
        foreach ($dep as $d) {
            $employee[$d->deptname] = \App\User::where('departments_id', $d->departments_id)->where('enabled', '1')->get();
        }

        return view('admin.payroll.salary_summary.index', compact('page_title', 'page_description', 'users', 'date_start', 'date_end', 'employee', 'type', 'emp_id', 'payment_month', 'employee_name'));
    }

    public function salary_summary_print($query, $type, $option)
    {
        dd('OK');
        $tdata = [];
        $tgross_salary = 0;
        $tnet_sal = 0;
        $tovertime_money = 0;
        $tfine = 0;
        $total = 0;
        $formatted_allowance_list = \PayrollHelper::formatedAllowance();
        $formatted_deduction_list = \PayrollHelper::formattedDeduction();
        if ($type == 'month') {
            $month = $query;
            $users = DB::table('users')
                        ->select('first_name', 'last_name', 'id as user_id')
                        ->where('enabled', '1')
                        ->whereNotIn('users.id', [1, 2, 3, 28])
                        ->groupBy('users.id')
                        ->get();
            $date_start = $month.'-01';
            $date_end = $month.'-'.\Carbon\Carbon::parse($date_start)->daysInMonth;
            foreach ($users as $sk => $sv) {
                $data = [];
                $data['emp_id'] = $sv->user_id;
                $data['name'] = $sv->first_name.' '.$sv->last_name;
                $salary = \App\Models\SalaryPayment::where('user_id', $sv->user_id)
                         ->where('payment_month', $month)
                         ->first();

                if (count($salary)) {
                    $data['salary_grade'] = $salary->salary_grade;
                    $data = \PayrollHelper::PushUserAllowance($data,$sv,$formatted_allowance_list,
                            $sv->user_id);
                    $data['gross_salary'] = $salary->gross_salary;
                    $data = \PayrollHelper::PushUserDeduction($data,$sv,$formatted_deduction_list,
                            $sv->user_id);
                    $data['net_sal'] = $salary->gross_salary + $salary->total_allowance - $salary->total_deduction;
                    $data['overtime_money'] = $salary->overtime;
                    $data['fine'] = $salary->fine_deduction;

                    $data['total'] = $data['net_sal'] + $salary->overtime - $salary->fine_deduction;
                    $data['status'] = 'Paid';
                } else {
                    $overtime_money = \TaskHelper::overtimesal($sv->user_id, $date_start, $date_end);
                    $template = \PayrollHelper::getEmployeePayroll($sv->user_id)->template;
                    $data['salary_grade'] = $template->salary_grade;
                    $data = \PayrollHelper::PushUserAllowance($data,$sv,$formatted_allowance_list,
                            $sv->user_id);
                    $data['gross_salary'] = $template->basic_salary;
                    $data = \PayrollHelper::PushUserDeduction($data,$sv,$formatted_deduction_list,
                            $sv->user_id);
                    $net_salary = $template->basic_salary;
                    $allowances = \PayrollHelper::getSalaryAllowance($template->salary_template_id);
                    $deductions = \PayrollHelper::getSalaryDeduction($template->salary_template_id);
                    foreach ($allowances as $ak => $av) {
                        $net_salary += $av->allowance_value;
                    }
                    foreach ($deductions as $dk => $dv) {
                        $net_salary -= $dv->deduction_value;
                    }

                    $data['net_sal'] = $net_salary;
                    $data['overtime_money'] = $overtime_money;
                    $data['fine'] = 0;
                    $data['total'] = $net_salary + $overtime_money;
                    $data['status'] = 'Unpaid';
                }
                $tgross_salary = $tgross_salary + $data['gross_salary'];
                $tnet_sal = $tnet_sal + $data['net_sal'];
                $tovertime_money = $tovertime_money + $data['overtime_money'];
                $tfine = $tfine + $data['fine'];
                $total = $total + $data['total'];

                array_push($tdata, $data);
            }
        } elseif ($type == 'emp') {
            $data = [];
            $user_id = $query;
            $employee_name = \App\User::find($user_id);
            $sal = \App\Models\SalaryPayment::where('user_id', $user_id)->get();
            foreach ($sal as $salary) {
                $data['emp_id'] = $user_id;
                $data['name'] = $employee_name->first_name.' '.$employee_name->last_name;
                $data['salary_grade'] = $salary->salary_grade;
                $data = \PayrollHelper::PushUserAllowance($data,$salary,$formatted_allowance_list,
                            $user_id);
                $data['gross_salary'] = $salary->gross_salary;
                $data = \PayrollHelper::PushUserDeduction($data,$salary,$formatted_deduction_list,
                            $user_id);
                $data['net_sal'] = $salary->gross_salary + $salary->total_allowance - $salary->total_deduction;
                $data['overtime_money'] = $salary->overtime;
                $data['fine'] = $salary->fine_deduction;
                $data['total'] = $data['net_sal'] + $salary->overtime - $salary->fine_deduction;
                $data['payment_month'] = $salary->payment_month;
                $tgross_salary = $tgross_salary + $data['gross_salary'];
                $tnet_sal = $tnet_sal + $data['net_sal'];
                $tovertime_money = $tovertime_money + $data['overtime_money'];
                $tfine = $tfine + $data['fine'];
                $total = $total + $data['total'];
                array_push($tdata, $data);
            }
        }

        if ($option == 'pdf') {
            $pdf = \PDF::loadView('admin.payroll.salary_summary.print', compact('tdata', 'tgross_salary', 'tnet_sal', 'tovertime_money', 'tfine', 'total', 'employee_name', 'month'));
            $file = 'salary_summary.pdf';

            if (File::exists('reports/'.$file)) {
                File::Delete('reports/'.$file);
            }

            return $pdf->download($file);
        } elseif ($option == 'excel') {
            $summary = [
            '1'=>'',
            '2' => '',
            'salary_grade'=>'Total',
            '4' =>$tgross_salary,
            '5' =>$tnet_sal,
            '6' =>$tovertime_money,
            '7' =>$tfine,
            '8' =>$total,
        ];
            array_push($tdata, $summary);

            return \Excel::create('monthly_payroll_report', function ($excel) use ($tdata,$type,$month,$employee_name) {
                $excel->sheet('mySheet', function ($sheet) use ($tdata,$type,$month,$employee_name) {
                    $sheet->fromArray($tdata);
                    if ($type == 'month') {
                        $sheet->prependRow([
                    '', 'Month', date('M Y', strtotime($month)),
                ]);
                    } else {
                        $sheet->prependRow([
                    '', 'Employee', $employee_name->first_name.' '.$employee_name->last_name,
                ]);
                    }
                    $sheet->cell('B1:B2', function ($cell) {
                        $cell->setFontWeight('bold');
                    });
                    $last_row = count($tdata) + 2;
                    $sheet->cell('C'.$last_row.':H'.$last_row, function ($cell) {
                        $cell->setFontWeight('bold');
                        $cell->setBackground('#ffff00');
                    });

                    $sheet->cell('A2:I2', function ($cell) {
                        $cell->setFontWeight('bold');
                        $cell->setBackground('#ffff00');
                    });
                });
            })->download(xls);
        } else {
            return view('admin.payroll.salary_summary.print', compact('tdata', 'tgross_salary', 'tnet_sal', 'tovertime_money', 'tfine', 'total', 'employee_name', 'month'));
        }
    }

    //********************************
    // RUN PAYROLL WIZARD START
    //********************************

    public function run_payroll_step1(Request $request)
    {
        if ($request->ajax()) {
            $frequencyinfo = \App\Models\PayFrequency::find($request->id);

            return ['data'=>$frequencyinfo];
        }
        $time_entry_method = ['W'=>'Web & Mobile', 'B'=>'Biometric', 'T'=>'Timesheet'];
        foreach ($time_entry_method as $key => $value) {
            $records = \App\Models\PayFrequency::select('id', 'frequency', 'name')->where('is_issued', '0')->orderBy('check_date')->where('time_entry_method', $key)->get()->unique('frequency');
            $payfrequecny[$key] = $records;
        }
        $page_title = 'Run Payroll';
        $page_description = 'Choose weekly or monthly pay frequency';
        $frequency = ['H' => 'Hourly', 'W' => 'Weekly', 'M' => 'Monthly', 'B'=>'Biweekly'];

        return view('admin.payroll.run.step1', compact( 'page_title', 'page_description', 'payfrequecny', 'frequency', 'time_entry_method'));
    }

    private function getdaysperiods($start_date, $end_date, $leavestart, $leaveend)
    {
        $count = 0;
        if ($leavestart && $leaveend) {
            $interval = new \DateInterval('P1D');
            $realEnd = new \DateTime($leaveend);
            $realEnd->add($interval);
            $period = new \DatePeriod(new \DateTime($leavestart), $interval, $realEnd);
            $absentday = [];
            foreach ($period as $d) {
                $date = $d->format('Y-m-d');
                $date_str = strtotime($date);
                if ($start_date <= $date_str && $end_date >= $date_str) {
                    $absentday[] = $date;
                    $count++;
                }
            }
        }

        return [$count, $absentday];
    }

    public function getleavedays($leavecategory, $user_id, $start_date, $end_date)
    {
        $dayscollection = \App\Models\LeaveApplication::where('user_id', $user_id)->
                    where('leave_category_id', $leavecategory)->
                    where('application_status', '2')->
                    where(function ($query) use ($start_date, $end_date) {
                        $query->where(function ($query) use ($start_date, $end_date) {
                            $query->where('leave_start_date', '<=', $start_date)
                            ->where('leave_end_date', '>=', $start_date);
                        })->orWhere(function ($query) use ($start_date, $end_date) {
                            $query->where('leave_start_date', '<=', $end_date)
                            ->where('leave_end_date', '>=', $end_date);
                        })->orWhere(function ($query) use ($start_date, $end_date) {
                            $query->where('leave_start_date', '>=', $start_date)
                            ->where('leave_end_date', '<=', $end_date);
                        });
                    })->get();

        return $dayscollection;
    }

    public function getleavedata($leavecategory, $user_id, $start_date, $end_date)
    {
        $data = [];
        $start_date_str = strtotime($start_date);
        $end_date_str = strtotime($end_date);
        $total = 0;
        foreach ($leavecategory as $lc) {
            $dayscollection = $this->getleavedays($lc->leave_category_id, $user_id, $start_date, $end_date);
            $count = 0;
            $absentday = [];
            foreach ($dayscollection as $dc) {
                $leaveday = $this->getdaysperiods($start_date_str, $end_date_str, $dc->leave_start_date, $dc->leave_end_date);
                $count = $count + $leaveday[0];
                $absentday = array_merge($absentday, $leaveday[1]);
            }
            $keys = strtolower(str_replace(' ', '_', $lc->leave_category));
            $total = $total + $count;
            $data[$keys] = [$count, $absentday]; // 0=>total leave days, 1=> list leave days list , 3 => leave_category_id
        }

        return [$data, $total];
    }

    public function run_payroll_timecard_review_Timesheet($timekeeping, $start_date, $end_date)
    {
        foreach ($timekeeping as $key => $value) {
            $working_hours = \PayrollHelper::TimesheetEmpRegularHours($value->user_id, $start_date, $end_date);
            $regularhours = $working_hours['rt'];
            $overtimehours = $working_hours['ot'];
            $records['user_id'] = $value->user_id;
            $records['username'] = $value->user->first_name.' '.$value->user->last_name;
            $records['designation'] = $value->user->designation->designations;
            $records['departments'] = $value->user->department->deptname;
            $records['time_entry_method'] = $value->time_entry_method;
            $records['regular'] = $regularhours;
            $records['overtime'] = $overtimehours;
            $records['work_report'] = [$working_hours['rt_report'], $working_hours['ot_report']];
            $records['total'] = round(($records['regular'] + ($records['overtime'])), 3);
            $result[] = $records;
        }

        return $result;
    }

    public function employee_working_days_type($user_id, $start_date, $end_date)
    {
        // dd($user_id,$start_date,$end_date);
        // $attendance = \App\Models\Attendance::where('user_id', $user_id)->where('date_in', '>=', $start_date)->where('date_in', '<=', $end_date)->orderBy('date_in', 'asc')->get();

        $attendance = \App\Models\ShiftAttendance::select('date as date_in')
                        ->where('user_id', $user_id)->where('date', '>=', $start_date)
                        ->where('date', '<=', $end_date)
                        ->orderBy('date', 'asc')
                        ->groupBy('date')
                        ->get();

        $regular = 0;
        $regular_arr = [];
        $weekend = 0;
        $weekend_arr = [];
        $public_holiday = 0;
        $public_holiday_arr = [];
        //dd($user_id,$start_date,$end_date);
        foreach ($attendance as $key => $value) {
            $day = new \DateTime($value->date_in);
            $is_public_holiday = \App\Models\Holiday::where('start_date', '<=', $value->date_in)
            ->where('end_date', '>=', $value->date_in)
            ->where('types', 'public')->first();
            if ($is_public_holiday) {
                $public_holiday += 1;
                $public_holiday_arr[] = $value->date_in.'('.$is_public_holiday->event_name.')';
            } elseif ($day->format('l') == 'Saturday') {
                $weekend += 1;
                $weekend_arr[] = $value->date_in;
            } else {
                $regular += 1;
                $regular_arr[] = $value->date_in;
            }
        }

        return ['regular'=>[$regular, $regular_arr], 'weekend'=>[$weekend, $weekend_arr], 'public_holiday_work'=>[$public_holiday, $public_holiday_arr]];
    }

    public function run_payroll_timecard_review_WebMobile($leavecategory, $timekeeping, $start_date, $end_date)
    {
        foreach ($timekeeping as $key => $value) {
            $leavedetails = $this->getleavedata($leavecategory, $value->user_id, $start_date, $end_date);
            $records = $leavedetails[0];
            $working_days_types = $this->employee_working_days_type($value->user_id, $start_date, $end_date);
            $overtimehours = \PayrollHelper::overtime($value->user_id, $start_date, $end_date);
            $records['user_id'] = $value->user_id;
            $records['username'] = $value->user->first_name.' '.$value->user->last_name;
            $records['designation'] = $value->user->designation->designations;
            $records['departments'] = $value->user->department->deptname;
            $records['time_entry_method'] = $value->time_entry_method;
            $records['regular'] = $working_days_types['regular'];
            $records['weekend'] = $working_days_types['weekend'];
            $records['public_holiday_work'] = $working_days_types['public_holiday_work'];
            $records['overtime'] = $overtimehours;
            $records['total'] = round(($records['regular'][0] + $records['weekend'][0] + $records['public_holiday_work'][0] + ($records['overtime'][0] / 24) - $leavedetails[1]), 3);
            $result[] = $records;
        }

        return $result;
    }

    public function run_payroll_timecard_review(Request $request)
    {
        $this->validate($request, [
            'start_date'      => 'required',
            'end_date'     => 'required',
            'frequency_id'     => 'required',
            ]);

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $entrymethod = ['W' => 'Web & Mobile', 'B' => 'Biometric', 'T'=>'Timesheet'];
        $page_title = 'Timecard Review';
        $page_description ='Run Payroll';
        $timecard = \App\Models\PayrollTimeCardReview::where('pay_frequency_id', $request->frequency_id)->get();
        $payfrequency = \App\Models\PayFrequency::find($request->frequency_id);
        $leavecategory = \App\Models\LeaveCategory::all();
        foreach ($leavecategory as $lc) {
            $keys = strtolower(str_replace(' ', '_', $lc->leave_category));
            $leavecollection[$keys] = ['id'=>$lc->leave_category_id, 'quota'=>$lc->leave_quota];
        }
        if ($timecard->count() > 0) { // return time card if already exists in database
            if ($payfrequency->time_entry_method == 'T') {
                return view('admin.payroll.run.timecard_review_timesheet', compact('page_title', 'page_description', 'timecard', 'payfrequency', 'entrymethod'));
            } elseif ($payfrequency->time_entry_method == 'W') {
                return view('admin.payroll.run.timecard_review', compact('page_title', 'page_description', 'timecard', 'payfrequency', 'entrymethod', 'leavecollection'));
            }
        }
        $page_description = 'Check attendance';
        $timekeeping = \App\Models\TimeKeeping::select('time_keeping.user_id',
                    'time_keeping.time_entry_method', 'time_keeping.pay_frequency', 'time_keeping.pay_type')
                    ->leftjoin('users', 'users.id', '=', 'time_keeping.user_id')
                    ->where('users.enabled', '1')
                    ->where('pay_frequency', $payfrequency->frequency)
                    ->where('time_entry_method', $payfrequency->time_entry_method)
                    ->groupBy('users.id')
                    ->get();

        if ($payfrequency->time_entry_method == 'T') {
            $result = $this->run_payroll_timecard_review_Timesheet($timekeeping, $start_date, $end_date);

            return view('admin.payroll.run.timecard_review_timesheet', compact('data', 'page_title', 'page_description', 'result', 'payfrequency', 'entrymethod'));
        } elseif ($payfrequency->time_entry_method == 'W') {
            $result = $this->run_payroll_timecard_review_WebMobile($leavecategory, $timekeeping, $start_date, $end_date);

            return view('admin.payroll.run.timecard_review', compact('data', 'page_title', 'page_description', 'result', 'payfrequency', 'entrymethod', 'leavecollection'));
        } else {
            return redirect()->back()->withErrors(['No Valid Time Entrymethod Set']);
        }
    }

    public function run_payroll_enter_payroll_Web($data)
    {
        $records = ['total_allowance'=>0,'total_deduction'=>0];
        $records['user'] = User::find($data['user_id']);
        $template = \PayrollHelper::getEmployeePayroll($data['user_id'])->template??null;
        $records['basic_salary'] = $template->basic_salary ?? '';
        $allowances = \PayrollHelper::getSalaryAllowance($template->salary_template_id ?? null);
        $deduction = \PayrollHelper::getSalaryDeduction($template->salary_template_id ?? null);
        foreach ($allowances as $ak => $av) {
            $records['total_allowance'] +=(float) $av->allowance_value;
        }
        foreach ($deduction as $ak => $av) {
            $records['total_deduction'] +=(float) $av->deduction_value;
        }
        $records['regular_salary'] = (($template->basic_salary ?? 0) / 30) * $data['regular_days'];
        $records['gratuity_salary'] = ($template->basic_salary??0) * (env('GRATUITY_PERCENT') / 100);
        $records['weekend_salary'] = (($template->basic_salary??0) / 30) * env('WEEKEND_SALARY') * $data['weekend'];
        $records['public_holiday_work_salary'] = (($template->basic_salary??0) / 30) * env('PUBLIC_HOLIDAY_SALARY') * $data['public_holiday_work'];
        $records['overtime_salary'] = $data['ot_hour'] * ($template->overtime_salary??0);
        $records['sick_salary'] = $data['sick_leave'] * ($template->sick_salary??0);
        $records['annual_leave_salary'] = $data['annual_leave'] * ($template->annual_leave_salary??0);
        $records['public_holiday_salary'] = $data['public_holidays'] * ($template->public_holiday_salary??0);
        $records['other_leave_salary'] = $data['other_leave'] * ($template->other_leave_salary??0);
        $records['tax'] = 1;

        return $records;
    }

    public function run_payroll_enter_payroll_Timesheet($data)
    {
        $records = [];

        $records['user'] = User::find($data['user_id']);
        $template = \PayrollHelper::getTimeSheetSalaryDetails($data['user_id'])->template;
        $records['regular_salary'] = $template->salary_per_hour * $data['regular_days']; //regular days means regular hours
        $records['overtime_salary'] = $data['ot_hour'] * $template->overtime_salary_per_hour;
        $records['tax'] = 1;

        return $records;
    }

    public function run_payroll_enter_payroll(Request $request)
    {
        $data = json_decode($request->data, true);

        $payfrequency = \App\Models\PayFrequency::find($request->frequency_id);
        $totalarr = ['regular'=>0, 'weekend'=>0, 'public_holiday_work'=>0, 'overtime'=>0, 'sick'=>0, 'anual'=>0, 'public'=>0, 'other'=>0, 'total'=>0, 'taxamount'=>0, 'totalamountwithouttax'=>0,'allowance'=>0,'deduction'=>0];
        $enter_payroll = \App\Models\PayrollEnterPay::where('pay_frequency_id', $request->frequency_id)->get();
        $calculatesalary = count($enter_payroll) > 0 ? false : true;
        foreach ($data as $key=>$d) {
            $check_exists_timecard = \App\Models\PayrollTimeCardReview::where('user_id', $d['user_id'])->where('pay_frequency_id', $request->frequency_id)->first();
            if ($check_exists_timecard) {
                $d['issued_by'] = Auth::user()->id;
                $check_exists_timecard->update($d);
            } else {
                $d['issued_by'] = Auth::user()->id;
                $d['pay_frequency_id'] = $request->frequency_id;
                \App\Models\PayrollTimeCardReview::create($d);
            }
            if ($calculatesalary) {
                if ($payfrequency->time_entry_method == 'T') {
                    $records = $this->run_payroll_enter_payroll_Timesheet($d);  //return all salary
                    $total = $records['regular_salary'] + $records['overtime_salary'];
                    $records['taxamount'] = $total * 0.01;
                    $records['total'] = $total - $records['taxamount'];
                    $records['totalwithouttax'] = $total;
                    $salary[] = $records;
                    //total array
                    $totalarr['regular'] += $records['regular_salary'];
                    $totalarr['overtime'] += $records['overtime_salary'];
                    $totalarr['taxamount'] += $records['taxamount'];
                    $totalarr['total'] += $records['total'];
                    $totalarr['totalamountwithouttax'] += $records['totalwithouttax'];
                } elseif ($payfrequency->time_entry_method == 'W') {
                    $records = $this->run_payroll_enter_payroll_Web($d); //return all salary
                    $total = $records['regular_salary'] + $records['weekend_salary'] + $records['public_holiday_work_salary'] + $records['overtime_salary'] + $records['sick_salary'] + $records['annual_leave_salary'] + $records['public_holiday_salary'] +
                        $records['other_leave_salary'] + $records['total_allowance'] - $records['total_deduction'] +
                        $records['gratuity_salary'];
                    $records['taxamount'] = $total * 0.01;
                    $records['total'] = $total - $records['taxamount'];
                    $records['totalwithouttax'] = $total;
                    $totalarr['regular'] += $records['regular_salary'];
                    $totalarr['allowance'] += $records['total_allowance'];
                    $totalarr['deduction'] += $records['total_deduction'];
                    $totalarr['weekend'] += $records['weekend_salary'];
                    $totalarr['public_holiday_work'] += $records['public_holiday_work_salary'];
                    $totalarr['overtime'] += $records['overtime_salary'];
                    $totalarr['sick'] += $records['sick_salary'];
                    $totalarr['anual'] += $records['annual_leave_salary'];
                    $totalarr['public'] += $records['public_holiday_salary'];
                    $totalarr['other'] += $records['other_leave_salary'];
                    $totalarr['total'] += $records['total'];
                    $totalarr['taxamount'] += $records['taxamount'];
                    $totalarr['totalamountwithouttax'] += $records['totalwithouttax'];
                    $salary[] = $records;
                }
            }
        }
        $page_title = 'Enter Payroll';
        $page_description = 'Check amount';
        if ($request->runtype == 'later') {
            Flash::success('Salary Timecard Saved');

            return redirect('/admin/payroll/run/step1');
        }
        if ($payfrequency->time_entry_method == 'T') {
            return view('admin.payroll.run.enter_payroll_timesheet', compact('salary', 'page_title', 'page_description', 'totalarr', 'payfrequency', 'enter_payroll'));
        } elseif ($payfrequency->time_entry_method == 'W') {
            return view('admin.payroll.run.enter_payroll', compact('salary', 'page_title', 'page_description', 'totalarr', 'payfrequency', 'enter_payroll'));
        }
    }

    public function run_payroll_summary(Request $request)
    {
        $page_title = 'Payroll Summary';
        $page_description = 'Check amount';
        $data = json_decode($request->data, true);

        foreach ($data as $salary) {
            $check_exists_salary = \App\Models\PayrollEnterPay::where('user_id', $salary['user_id'])->where('pay_frequency_id', $request->frequency_id)->first();
            if ($check_exists_salary) {
                $salary['issued_by'] = Auth::user()->id;
                $check_exists_salary->update($salary);
            } else {
                $salary['issued_by'] = Auth::user()->id;
                $salary['pay_frequency_id'] = $request->frequency_id;
                \App\Models\PayrollEnterPay::create($salary);
            }
        }
        $payfrequency = \App\Models\PayFrequency::find($request->frequency_id);
        $totalamount = $request->totalamount;
        if ($request->runtype == 'later') {
            Flash::success('Salary Template Saved');

            return redirect('/admin/payroll/run/step1');
        }
        $payfrequency->update(['is_issued'=>'1']);

        return view('admin.payroll.run.payroll_summary', compact('page_title', 'page_description', 'totalamount', 'payfrequency'));
    }

    public function user_allowance($pay_frequency_id, $user_id)
    {
        $enter_pay = \App\Models\PayrollEnterPay::where('user_id', $user_id)->where('pay_frequency_id',
            $pay_frequency_id)->first();
        if ($enter_pay && $enter_pay->total_allowance_json) {
            $allowances = json_decode($enter_pay->total_allowance_json);
        } else {
            $template = \PayrollHelper::getEmployeePayroll($user_id)->template;
            $allowances = \PayrollHelper::getSalaryAllowance($template->salary_template_id);
        }

        $user = \App\User::find($user_id);

        return view('admin.payroll.run.modals.user_allowance', compact('template', 'allowances', 'user_id', 'user', 'enter_pay', 'modified'));
    }

    public function user_deduction($pay_frequency_id, $user_id)
    {
        $enter_pay = \App\Models\PayrollEnterPay::where('user_id', $user_id)->where('pay_frequency_id', $pay_frequency_id)->first();
        if ($enter_pay && $enter_pay->total_deduction_json) {
            $modified = true;
            $deduction = json_decode($enter_pay->total_deduction_json);
        } else {
            $template = \PayrollHelper::getEmployeePayroll($user_id)->template;
            $deduction = \PayrollHelper::getSalaryDeduction($template->salary_template_id);
        }
        $user = \App\User::find($user_id);

        return view('admin.payroll.run.modals.user_deduction', compact('template', 'deduction', 'user_id', 'user', 'enter_pay', 'modified'));
    }

    public function dowloadFile($type, $pay_frequency_id)
    {
        $enter_pay = \App\Models\PayrollEnterPay::where('pay_frequency_id', $pay_frequency_id)->get();
        $all_data = [];
        $formatted_allowance_list = \PayrollHelper::formatedAllowance();
        $formatted_deduction_list = \PayrollHelper::formattedDeduction();
        //loop though value

        foreach ($enter_pay as $key => $value) {
            $data = [];
            $data['emp_id'] = $value->user_id;
            $data['emp_name'] = $value->user->first_name.' '.$value->user->last_name;
            $data['designation'] = $value->user->designation->designations;
            $data['department'] = $value->user->department->deptname;
            $data['basic_salary'] = $value->basic_salary;
            $data['regular_salary'] = $value->regular_salary;
            $data['gratuity_salary'] = $value->gratuity_salary;
            $data['overtime_salary'] = $value->overtime_salary;
            $data['weekend_salary'] = $value->weekend_salary;
            $data['public_holiday_work_salary'] = $value->public_holiday_work_salary;
            $data['sick_salary'] = $value->sick_salary;
            $data['annual_leave_salary'] = $value->annual_leave_salary;
            $data['public_holiday_salary'] = $value->public_holiday_salary;
            $data['other_leave_salary'] = $value->other_leave_salary;
            $data = \PayrollHelper::PushUserAllowance($data, $value, $formatted_allowance_list, $value->user_id);
            $data['gross_salary'] = $value->gross_salary;
            $data = \PayrollHelper::PushUserDeduction($data, $value, $formatted_deduction_list, $value->user_id);
            $data['tax_amount'] = $value->tax_amount;
            $data['tax_percent'] = $value->tax_percent;
            $data['net_salary'] = $value->net_salary;
            $data['issued_by'] = $value->issuedBy->username;
            $data['issued_at'] = $value->created_at;
            $all_data[] = $data;
        }

        // $top = [['Company Name','TEST'],['Company Name','TEST']];

        // $all_data = array_merge($top,$all_data);
        return \Excel::download(new \App\Exports\ExcelExport($all_data), "payroll_summary_pay_frequency # {$pay_frequency_id}.{$type}");
        //  return \Excel::create('payroll_summary_pay_frequency #'.$pay_frequency_id, function ($excel) use ($all_data) {
        //     $excel->sheet('mySheet', function ($sheet) use ($all_data) {
        //         $sheet->fromArray($all_data);
        //         $sheet->prependRow(['Company PAN',\Auth::user()->organization->tpid]);
        //         $sheet->prependRow(['Company Address',\Auth::user()->organization->address]);
        //         $sheet->prependRow(['Company Name',env('APP_COMPANY')]);
        //         $sheet->cell('A1:A3', function ($cell) {
        //             $cell->setFontWeight('bold');
        //         });
        //     });
        // })->download($type);
    }
}
