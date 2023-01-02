<?php

namespace App\Helpers;

use App\Models\Designation;
use App\Models\EmployeePayroll;
use App\Models\SalaryAllowance;
use App\Models\SalaryDeduction;

class PayrollHelper
{
    public static function getSalaryAllowance($salary_template_id)
    {
        $temp = SalaryAllowance::where('salary_template_id', $salary_template_id)
                 ->get();

        return $temp;
    }

    public static function getSalaryDeduction($salary_template_id)
    {
        $temp = SalaryDeduction::where('salary_template_id', $salary_template_id)
                 ->get();

        return $temp;
    }

    public static function getDesignation($designations_id)
    {
        $designation = Designation::where('designations_id', $designations_id)->first();

        return $designation ? $designation->designations: null;
    }

    public static function getEmployeePayroll($user_id)
    {
        $payroll = EmployeePayroll::where('user_id', $user_id)->first();

        return $payroll;
    }

    public static function overtime($user_id, $start_date, $end_date)
    {
        $overtime = \App\Models\Overtime::where('user_id', $user_id)
                ->where('overtime_date', '>=', $start_date)
                ->where('overtime_date', '<=', $end_date)->get();
        $thours = 0;
        $ot_report = [];
        foreach ($overtime as $over) {
            $time = strtotime($over->overtime_hours);
            $hours = date('i', $time) / 60 + date('H', $time);
            $ot_report[] = $over->overtime_date.' ('.$over->overtime_hours.')';
            $thours += $hours;
        }

        return [round($thours, 2), $ot_report];
    }

    public static function getTimeSheetSalaryDetails($user_id)
    {
        $payroll = \App\Models\TimeSheetUserSalary::where('user_id', $user_id)->first();

        return $payroll;
    }

    public static function TimesheetEmpRegularHours($user_id, $start_date, $end_date)
    {
        $overtime = \App\Models\TimeSheet::where('date', '>=', $start_date)
                    ->where('date', '<=', $end_date)
                    ->where('employee_id', $user_id)
                    ->get();
        $total_regular_time = 0;
        $rt_report = [];
        $total_over_time = 0;
        $ot_report = [];

        foreach ($overtime as $key => $o) {
            $total_time = \TaskHelper::GetTimeDifference($o->time_from, $o->time_to);
            $total_time = floor($total_time / 60 / 60);
            if ($total_time > 9) {
                $total_over_time += ($total_time - 9);
                $total_regular_time += 9;
                $ot_start = date('H:i', strtotime('+9 hour', strtotime($o->time_from))); //geneating ot start reprot
                $ot_report[] = $o->date.' ('.$ot_start.' - '.$o->time_to.') ';
                $rt_report[] = $o->date.' ('.$o->time_from.' - '.$ot_start.') ';
            } else {
                $total_regular_time += $total_time;
                $rt_report[] = $o->date.' ('.$o->time_from.' - '.$o->time_to.') ';
            }
        }

        return ['rt'=>$total_regular_time, 'ot'=>$total_over_time, 'rt_report'=>$rt_report, 'ot_report'=>$ot_report];
    }

    public static function getDepartment($departments_id)
    {
        $department = \App\Models\Department::where('departments_id', $departments_id)->first();

        return $department ? $department->deptname : null;
    }

    public static function timeSheetSalaryPerDay($template, $time)
    {
        $total_time = floor($time / 60 / 60);
        if ($total_time > 9) { //overtimestart
            $total_over_time = ($total_time - 9);
            $salary['overtime_salary'] = $total_over_time * $template->overtime_salary_per_hour;
            $salary['regular_salary'] = 9 * $template->salary_per_hour;
        } else {
            $salary['overtime_salary'] = 0;
            $salary['regular_salary'] = $total_time * $template->salary_per_hour;
        }
        // dd($salary);
        return $salary;
    }

    public static function formatedAllowance()
    {
        //allownce_list_slugify
        $allowances_list = \App\Models\SalaryAllowance::distinct('allowance_label')->get();
        $formatted_allowance_list = [];
        foreach ($allowances_list as $key => $value) {
            $formatted_allowance_list[] = '_'.\TaskHelper::make_name_slug($value->allowance_label);
        }
        $formatted_allowance_list = array_unique($formatted_allowance_list);

        return $formatted_allowance_list;
    }

    public static function formattedDeduction()
    {
        //deduction_list_slugify
        $deduction_list = \App\Models\SalaryDeduction::distinct('deduction_label')->get();
        $formatted_deduction_list = [];
        foreach ($deduction_list as $key => $value) {
            $formatted_deduction_list[] = \TaskHelper::make_name_slug($value->deduction_label).'_';
        }
        $formatted_deduction_list = array_unique($formatted_deduction_list);

        return $formatted_deduction_list;
    }

    public static function PushUserAllowance($data, $value, $formatted_allowance_list, $user_id)
    {
        if ($value && $value->total_allowance_json) { //for enter_pay
            $allowances = json_decode($value->total_allowance_json);
            $allowances_arr = [];
            foreach ($allowances as $a) {
                $allowances_arr['_'.$a->formatted_label] = $a->allowance_value;
            }
        } else {
            $template = \PayrollHelper::getEmployeePayroll($user_id)->template;

            $allowances = \PayrollHelper::getSalaryAllowance($template->salary_template_id);

            $allowances_arr = [];
            foreach ($allowances as $a) {
                $k = '_'.\TaskHelper::make_name_slug($a->allowance_label);
                $allowances_arr[$k] = $a->allowance_value;
            }
        }

        foreach ($formatted_allowance_list as $key_val) {
            $data[$key_val] = $allowances_arr[$key_val] ?? 0;
        }
        foreach ($formatted_deduction_list as $key_val) {
            $data[$key_val] = $deduction_arr[$key_val] ?? 0;
        }

        return $data;
    }

    public static function PushUserDeduction($data, $value, $formatted_deduction_list, $user_id)
    {
        if ($value && $value->total_deduction_json) {
            $deduction = json_decode($value->total_deduction_json);
            $deduction_arr = [];
            foreach ($deduction as $a) {
                $deduction_arr[$a->formatted_label.'_'] = $a->deduction_value;
            }
        } else {
            $template = \PayrollHelper::getEmployeePayroll($user_id)->template;

            $deduction = \PayrollHelper::getSalaryDeduction($template->salary_template_id);

            $deduction_arr = [];
            foreach ($deduction as $a) {
                $k = \TaskHelper::make_name_slug($a->deduction_label).'_';
                $deduction_arr[$k] = $a->deduction_value;
            }
        }
        foreach ($formatted_deduction_list as $key_val) {
            $data[$key_val] = $deduction_arr[$key_val] ?? 0;
        }

        return $data;
    }
}
