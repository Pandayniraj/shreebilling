<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Division;
use App\Models\EmployeeAward;
use App\Models\EmployeePayroll;
use App\Models\PaymentMethod;
use App\Models\Payroll;
use App\Models\SalaryPayment;
use App\Models\PayrollDetails;
use App\Models\SalaryPaymentAllowance;
use App\Models\payrollDetials;
use App\Models\SalaryPaymentDeduction;
use App\Models\Role as Permission;
use App\User;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class PayrollControllerNew extends Controller
{
    /**
     * @var Permission
     */
    private $permission;
    private $nepali_dates = [[2000, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31, 365], [2001, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2002, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30, 365], [2003, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2004, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31, 365], [2005, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2006, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30, 365], [2007, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2008, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31, 365], [2009, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2010, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30, 365], [2011, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2012, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30, 365], [2013, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2014, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30, 365], [2015, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2016, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30, 365], [2017, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2018, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30, 365], [2019, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31, 366], [2020, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2021, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2022, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30, 365], [2023, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31, 366], [2024, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2025, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2026, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2027, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31, 365], [2028, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2029, 31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30, 365], [2030, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2031, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31, 365], [2032, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2033, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30, 365], [2034, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2035, 30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31, 365], [2036, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2037, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30, 365], [2038, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2039, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30, 365], [2040, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2041, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30, 365], [2042, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2043, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30, 365], [2044, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2045, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30, 365], [2046, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2047, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2048, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2049, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30, 365], [2050, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31, 366], [2051, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2052, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2053, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30, 365], [2054, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31, 366], [2055, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2056, 31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30, 365], [2057, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2058, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31, 365], [2059, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2060, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30, 365], [2061, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2062, 30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31, 365], [2063, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2064, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30, 365], [2065, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2066, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31, 365], [2067, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2068, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30, 365], [2069, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2070, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30, 365], [2071, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2072, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30, 365], [2073, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31, 366], [2074, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2075, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2076, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30, 365], [2077, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31, 366], [2078, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2079, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30, 365], [2080, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30, 365], [2081, 31, 31, 32, 32, 31, 30, 30, 30, 29, 30, 30, 30, 366], [2082, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30, 365], [2083, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30, 365], [2084, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30, 365], [2085, 31, 32, 31, 32, 30, 31, 30, 30, 29, 30, 30, 30, 366], [2086, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30, 365], [2087, 31, 31, 32, 31, 31, 31, 30, 30, 29, 30, 30, 30, 366], [2088, 30, 31, 32, 32, 30, 31, 30, 30, 29, 30, 30, 30, 365]];


    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
    }

    public function index()
    {
        $page_title = 'Manage Payment';
        $page_description = 'Manage Payment';
        $payroll = \App\Models\Payroll::latest()->paginate(12);
        return view('admin.payrolllist.index', compact('page_title', 'page_description','payroll'));
    }

    public function getMonthDays($year,$month){

        foreach ($this->nepali_dates as $key => $value) {
            $days=0;
            if ($value[0]==$year) {
                $days=$this->nepali_dates[$key][$month];
                break;
            }
            continue;
        }
        return $days;
    }



    public function bsToAd($selected_date)
    {
        if ($selected_date) {

            $chosen_date = explode('-', $selected_date);
            // dd($chosen_date);
            $date = date_create('1943-4-13');
            foreach ($this->nepali_dates as $index => $d) {
                if ($d[0] < $chosen_date[0]) {
                    date_add($date, date_interval_create_from_date_string($d[13]." days"));
//                    dd($date);
                } else {
                    foreach ($d as $index_month => $m) {
                        if ($index_month > 0 && $index_month <= $chosen_date[1]) {
                            if ($index_month < $chosen_date[1]) {
                                date_add($date, date_interval_create_from_date_string($m." days"));

                            } else {
                                date_add($date, date_interval_create_from_date_string($chosen_date[2]." days"));
                                break;
                            }
                        }
                    }
                    break;
                }
            }

            $new_date = date_format($date, "Y-m-d");
            return $new_date;
        }
    }
    public function create(Request $request)
    {
        $no_days = $this->getMonthDays($request->year,$request->month);
        $page_title = 'Create Payroll Payment';
        $page_description = 'Manage Payment';
        $departments = Department::orderBy('deptname', 'desc')->get();
        $divisions = Division::orderBy('name', 'desc')->get();
        $fiscalyears = \App\Models\Fiscalyear::where('current_year',1)->latest()->first();
        $start_date  =   $request->year.'-'.$request->month.'-01';
        $year_month  =   $request->year.'-'.$request->month;
        $start_date = $this->bsToAd($start_date);
        $end_date = date('Y-m-d', strtotime($start_date.'+ '.($no_days-1).' days'));

        if(\Request::get('year') && \Request::get('month')){

            $payroll_check = \App\Models\Payroll::
            where('departments_id',\Request::get('department_id'))->where('date',$year_month)->first();
            if(!empty($payroll_check))
            {
                Flash::warning('Already Created Payroll View Payroll From List Page');
                return redirect()->route('admin.payrolllist.create');
            }
            $department_id =  $request->department_id;
            $users = User::
                select('first_name', 'last_name', 'id','emp_id','designations_id')
                ->when($department_id,function($q) use($department_id){
                    $q->where('departments_id', $department_id);
                })
                ->whereHas('department',function ($q) use ($request) {
                    $q->where('division_id', $request->division_id);
                })
                ->where('enabled', '1')
                ->whereNotIn('users.id', [1])
                ->with('employeePayroll.template')
                ->groupBy('users.id')
                ->get();
            $total_days = $no_days;
            // $paymentmonth = explode('-', \Request::get('payment_month'));
            // $year = $paymentmonth[0];
            // $month   = $paymentmonth[1];
            // $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            // $start_date = \Request::get('payment_month').-01;
            // $end_date = \Request::get('payment_month').'-'.$total_days;

            $tax_band = \App\Models\TaxBand::all();
            $allowances=['Dearness Allowance','Telephone Allowance','Other Allowance',];
        }


        return view('admin.payrolllist.create',compact('allowances','divisions','users','departments','total_days','start_date','end_date','tax_band','fiscalyears'));
    }


    public function store(Request $request)
    {

        $attributes['departments_id'] = $request->departments_id;
        $attributes['division_id'] = $request->division_id;
        $attributes['created_by'] = auth()->user()->id;
        $attributes['date']  =   $request->year.'-'.$request->month;

        // $attributes['date'] = $request->date;

        DB::beginTransaction();
        $payroll = \App\Models\Payroll::create($attributes);
        $user_ids = $request->user_id;
        $attendance = $request->attendance;
        if($payroll)
        {
//            dd($request->all());
            foreach($user_ids as $index=>$user)
            {
                $attributesDetials['user_id'] = $user;
                $attributesDetials['payroll_id'] = $payroll->id;
                $attributesDetials['attendance'] = $attendance[$index];
                $attributesDetials['absent'] = $request->absent[$index];
                $attributesDetials['adjust_leave'] = $request->adjust_leave[$index];
                $attributesDetials['holidays'] = $request->holidays[$index];
                $attributesDetials['weekends'] = $request->weekends[$index];
                $attributesDetials['payable_days'] = $request->payable_days[$index];
                $attributesDetials['total_days'] = $request->total_days[$index];
                $attributesDetials['paid_leaves'] = $request->paid_leaves[$index];
                $attributesDetials['actual_salary'] = $request->actual_salary[$index];
                $attributesDetials['t_basic'] = $request->t_basic[$index];
                $attributesDetials['salary_grade'] = $request->salary_grade[$index];
                $attributesDetials['incentive'] = $request->incentive[$index];
                $attributesDetials['pf_contribution'] = $request->pf_contribution[$index];
                $attributesDetials['dashain_allowance'] = $request->dashain_allowance[$index];
                $attributesDetials['payable_basic'] = $request->payable_basic[$index];
                $attributesDetials['total_after_allowance'] = $request->total_after_allowance[$index];
                $attributesDetials['error_adjust'] = $request->error_adjust[$index];
                $attributesDetials['gratuity'] = $request->gratuity[$index];
                $attributesDetials['pf'] = $request->pf[$index];
                $attributesDetials['tds'] = $request->tds[$index];
                $attributesDetials['advance_deduction'] = $request->advance_deduction[$index];
                $attributesDetials['insurance_premium'] = $request->insurance_premium[$index];
                $attributesDetials['loan_deduction'] = $request->loan_deduction[$index];
                $attributesDetials['dormitory'] = $request->dormitory[$index];
                $attributesDetials['meal'] = $request->meal[$index];
                $attributesDetials['monthly_payable_amount'] = $request->monthly_payable_amount[$index];
                $attributesDetials['net_salary'] = $request->net_salary[$index];
                $attributesDetials['sst'] = $request->sst[$index];
                $attributesDetials['annual_incentive'] = $request->annual_incentive[$index];
                $attributesDetials['annual_leave_salary'] = $request->annual_leave_salary[$index];
                $attributesDetials['sst'] = $request->sst[$index];
                $attributesDetials['remarks'] = $request->remarks[$index];
                $payroll_detail=\App\Models\PayrollDetails::create($attributesDetials);
                $payment_allowances=[];
                foreach(request($user.'_allowance_value') as $i=>$allowance){
                    $allowances['payroll_detail_id']=$payroll_detail->id;
                    $allowances['salary_payment_allowance_value']=$allowance;
                    $allowances['salary_payment_allowance_label']=request($user.'_allowance_label')[$i];
                    $payment_allowances[]=$allowances;
                }

                $payroll_detail->paidAllowances()->createMany($payment_allowances);
                $new_carryforwardleave=$request->new_carryforwardleave[$index];
                \App\Helpers\TaskHelper::usercarryforwardleaveadjust($user,$new_carryforwardleave);
            }

        }
        DB::commit();
        Flash::success('Payroll  Has Been Created');
        return redirect()->route('admin.payrolllist.index');
    }
    public function update(Request $request,$id)
    {
        DB::beginTransaction();
        $payroll = \App\Models\Payroll::find($id);
        $user_ids = $request->user_id;
        $attendance = $request->attendance;
        if($payroll)
        {
            foreach ($payroll->payrollDetails as $pd){
                $pd->paidAllowances()->delete();
            }
            $payroll->payrollDetails()->delete();
            foreach($user_ids as $index=>$user)
            {
                $attributesDetials['user_id'] = $user;
                $attributesDetials['payroll_id'] = $payroll->id;
                $attributesDetials['attendance'] = $attendance[$index];
                $attributesDetials['absent'] = $request->absent[$index];
                $attributesDetials['total_days'] = $request->total_days[$index];
                $attributesDetials['paid_leaves'] = $request->paid_leaves[$index];
                $attributesDetials['actual_salary'] = $request->actual_salary[$index];
                $attributesDetials['t_basic'] = $request->t_basic[$index];
                $attributesDetials['salary_grade'] = $request->salary_grade[$index];
                $attributesDetials['incentive'] = $request->incentive[$index];
                $attributesDetials['pf_contribution'] = $request->pf_contribution[$index];
                $attributesDetials['dashain_allowance'] = $request->dashain_allowance[$index];
                $attributesDetials['payable_basic'] = $request->payable_basic[$index];
                $attributesDetials['total_after_allowance'] = $request->total_after_allowance[$index];
                $attributesDetials['error_adjust'] = $request->error_adjust[$index];
                $attributesDetials['gratuity'] = $request->gratuity[$index];
                $attributesDetials['pf'] = $request->pf[$index];
                $attributesDetials['tds'] = $request->tds[$index];
                $attributesDetials['advance_deduction'] = $request->advance_deduction[$index];
                $attributesDetials['insurance_premium'] = $request->insurance_premium[$index];
                $attributesDetials['loan_deduction'] = $request->loan_deduction[$index];
                $attributesDetials['dormitory'] = $request->dormitory[$index];
                $attributesDetials['meal'] = $request->meal[$index];
                $attributesDetials['monthly_payable_amount'] = $request->monthly_payable_amount[$index];
                $attributesDetials['net_salary'] = $request->net_salary[$index];
                $attributesDetials['sst'] = $request->sst[$index];
                $attributesDetials['annual_incentive'] = $request->annual_incentive[$index];
                $attributesDetials['annual_leave_salary'] = $request->annual_leave_salary[$index];
                $attributesDetials['remarks'] = $request->remarks[$index];
                $payroll_detail=\App\Models\PayrollDetails::create($attributesDetials);
                $payment_allowances=[];
                foreach(request($user.'_allowance_value') as $i=>$allowance){
                    $allowances['payroll_detail_id']=$payroll_detail->id;
                    $allowances['salary_payment_allowance_value']=$allowance;
                    $allowances['salary_payment_allowance_label']=request($user.'_allowance_label')[$i];
                    $payment_allowances[]=$allowances;
                }

                $payroll_detail->paidAllowances()->createMany($payment_allowances);
            }

        }
        DB::commit();
        Flash::success('Payroll  Has Been Updated');
        return redirect()->route('admin.payrolllist.index');
    }


    public function show($id)
    {
        $page_title = 'Show Payroll Payment';
        $page_description = 'Department Wise Payment';
        $payroll = \App\Models\Payroll::find($id);
        $departments = Department::orderBy('deptname', 'asc')->get();
        $allowances=['Dearness Allowance','Telephone Allowance','Other Allowance'];
        return view('admin.payrolllist.show',compact('allowances','page_title','page_description','payroll','departments','users'));
    }
    public function edit($id)
    {
        $page_title = 'Edit Payroll Payment';
        $page_description = 'Department Wise Payment';
        $payroll = \App\Models\Payroll::find($id);
        $departments = Department::orderBy('deptname', 'asc')->get();

        $explode=explode('-',$payroll->date);
        $no_days = $this->getMonthDays($explode[0],$explode[1]);

        $start_date  =   $explode[0].'-'.$explode[1].'-01';
        $year_month  =   $explode[0].'-'.$explode[1];

        // $selected_date=date('Y-m-d',str_to_time($start_date));
        $start_date = $this->bsToAd($start_date);
        $end_date = date('Y-m-d', strtotime($start_date.'+ '.$no_days.' days'));
        $allowances=['Dearness Allowance','Telephone Allowance','Other Allowance'];
        return view('admin.payrolllist.edit',compact('allowances','page_title','page_description','payroll','departments','users','start_date','end_date'));
    }


    public function payrollDownload(Request $request)
    {
        $payroll_id = \Request::get('payroll_id');
        $payroll = \App\Models\Payroll::with('payrollDetails')->find($payroll_id);

        $payrolldetails = $payroll->payrollDetails;
        return \Excel::download(new \App\Exports\PayrollExcelExportFromView($payroll,$payrolldetails), 'payroll_'.$payroll->date.'.xls');

    }


    public function downloadpayrollindividual(Request $request)
    {
        $payroll_id = \Request::get('payroll_id');
        $payroll = \App\Models\Payroll::with('payrollDetails')->find($payroll_id);
        $user_id = $request->user_id;
        $payrolldetails = $payroll->payrollDetails;
        $allowances=['Dearness Allowance','Telephone Allowance','Other Allowance'];

        return \Excel::download(new \App\Exports\PayrollExcelExportUserFromView($user_id,$payroll,$payroll->payrollDetails,$allowances), 'payroll_'.$payroll->date.'.xls');
    }

    public function generatePdf(Request $request)
    {
        $payroll_detail_id = \Request::get('payroll_detail_id');
        $slip = \App\Models\PayrollDetails::with(['payroll','paidAllowances','user'])->where('id',$payroll_detail_id)->first();

       // dd($slip);

        $pdf = \PDF::loadview('admin.payslip.pdf', compact('slip'));
        $file = 'payslip'.$slip->payroll->date.'.pdf';

//        if (File::exists('reports/'.$file)) {
//            File::Delete('reports/'.$file);
//        }
        return $pdf->download($file);
    }

    public function getDepartments($id)
    {
        $dept = \App\Models\Department::select('deptname','departments_id')->where('division_id',$id)->get();
        return ['data' => $dept];
    }

    public function destroy($id)
    {
        $payroll = Payroll::find($id);
        $payroll->delete();

        PayrollDetails::where('payroll_id', $id)->delete();

        Flash::success('Payroll successfully deleted');

        return redirect('/admin/payroll/list_payroll');
    }

    /**
     * Delete Confirm.
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalDelete($id)
    {
        $error = null;

        $payroll = Payroll::find($id);
//        if ($requisition->isApproved()){
//            Flash::error('Request has already been approved');
//            return redirect()->back();
//        }
        $modal_title = 'Delete Payroll';

        $modal_route = route('admin.payroll.delete', ['id' => $payroll->id]);


        $modal_body = 'Are you sure that you want to delete payroll for '.$payroll->date.' of '.
            $payroll->department->deptname.' department, '.$payroll->department->division->name.
            '. This operation is irreversible';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

}
