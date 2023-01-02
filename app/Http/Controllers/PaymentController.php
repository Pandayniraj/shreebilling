<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\EmployeeAward;
use App\Models\EmployeePayroll;
use App\Models\Paymentmethod as PaymentMethod;
use App\Models\Role as Permission;
use App\Models\SalaryPayment;
use App\Models\SalaryPaymentAllowance;
use App\Models\SalaryPaymentDeduction;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class PaymentController extends Controller
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

    public function index()
    {
        $page_title = 'Manage Payment';
        $page_description = 'Manage Payment';

        $departments = Department::orderBy('deptname', 'asc')->get();

        return view('admin.payroll.payment.index', compact('page_title', 'page_description', 'departments'));
    }

    public function listPayment(Request $request)
    {
        $page_title = 'Manage Payment';
        $page_description = 'Manage Payment';

        $departments = Department::orderBy('deptname', 'asc')->get();

        $users = DB::table('users')
                        ->select('first_name', 'last_name', 'id as user_id')
                        ->where('departments_id', $request->departments_id)
                        ->where('enabled', '1')
                        ->whereNotIn('users.id', [1, 2, 3, 28])
                        ->groupBy('users.id')
                        ->get();
        $departments_id = $request->departments_id;
        $date_start = \Request::get('payment_month').'-01';
        $date_end = \Request::get('payment_month').'-'.\Carbon\Carbon::parse($date_start)->daysInMonth;

        return view('admin.payroll.payment.index', compact('page_title', 'page_description', 'departments', 'users', 'departments_id', 'date_start', 'date_end'));
    }

    public function showPaymentDetail($user_id, $payment_month)
    {
        $salary = SalaryPayment::where('user_id', $user_id)->where('payment_month', $payment_month)->first();

        if (! $salary) {
            $salary = EmployeePayroll::where('user_id', $user_id)->first();
            $returnHTML = view('admin.payroll.unpaid_salary_detail_modal', compact('salary'))->render();
        } else {
            $returnHTML = view('admin.payroll.paid_salary_detail_modal', compact('salary'))->render();
        }

        return $returnHTML;
    }

    public function salaryPaymentDetail($salary_payment_id)
    {
        $salary = SalaryPayment::where('salary_payment_id', $salary_payment_id)->first();
        $returnHTML = view('admin.payroll.paid_salary_detail_modal', compact('salary'))->render();

        return $returnHTML;
    }

    public function makePayment($user_id, $departments_id, $payment_month)
    {
        $username = \TaskHelper::getUserName($user_id);
        $page_title = 'Payment History | '.$username;
        $page_description = 'Payment History';

        $departments = Department::orderBy('deptname', 'asc')->get();
        $paymentMethods = PaymentMethod::orderBy('name', 'asc')->get();
        $payment = SalaryPayment::where('user_id', $user_id)->where('payment_month', $payment_month)->first();
        $date_start = $payment_month.'-01';
        $date_end = $payment_month.'-'.\Carbon\Carbon::parse($date_start)->daysInMonth;
        $overtime = \TaskHelper::overtimesal($user_id, $date_start, $date_end);
        $payments = SalaryPayment::where('user_id', $user_id)->orderBy('payment_month', 'desc')->get();
        $users = User::orderBy('first_name', 'ASC')->where('enabled', '1')->pluck('first_name', 'id')->all();

        return view('admin.payroll.payment.make_payment', compact('page_title', 'page_description', 'departments', 'paymentMethods', 'payment', 'payments', 'overtime', 'users'));
    }

    public function submitNewPayment(Request $request)
    {
        $payment = SalaryPayment::where('user_id', $request->user_id)->where('payment_month', $payment_month)->first();
        //dd($payment);
        if (! count($types)) {
            $attributes = $request->except('_token');
            $attributes['paid_date'] = date('Y-m-d');
            $newPayment = SalaryPayment::create($attributes);
            $allowances = \PayrollHelper::getSalaryAllowance($request->salary_template_id);
            $deductions = \PayrollHelper::getSalaryDeduction($request->salary_template_id);
            foreach ($allowances as $ak => $av) {
                if ($av->allowance_value != '') {
                    SalaryPaymentAllowance::create(['salary_payment_id' => $newPayment->id, 'salary_payment_allowance_label' => $av->allowance_label, 'salary_payment_allowance_value' => $av->allowance_value]);
                }
            }

            foreach ($deductions as $dk => $dv) {
                if ($dv->deduction_value != '') {
                    SalaryPaymentDeduction::create(['salary_payment_id' => $newPayment->id, 'salary_payment_deduction_label' => $dv->deduction_label, 'salary_payment_deduction_value' => $dv->deduction_value]);
                }
            }
            Flash::success('Salary Payment done Successfully');
        } else {
            SalaryPayment::where('user_id', $request->user_id)->where('payment_month', $payment_month)->update($attributes);
            Flash::success('Salary Payment updated Successfully');
        }

        //update ledger
        $attributes['entrytype_id'] = \FinanceHelper::get_entry_type_id('payment'); //payment
        $attributes['tag_id'] = '1'; //payroll
        $attributes['user_id'] = Auth::user()->id;
        $attributes['org_id'] = Auth::user()->org_id;
        $attributes['number'] = $request->user_id;
        $attributes['date'] = \Carbon\Carbon::today();
        $attributes['dr_total'] = $request->payment_amount;
        $attributes['cr_total'] = $request->payment_amount;
        $attributes['source'] = 'Auto Payroll';
        $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
        $entry = \App\Models\Entry::create($attributes);

        //User account
        $sub_amount = new \App\Models\Entryitem();
        $sub_amount->entry_id = $entry->id;
        $sub_amount->user_id = Auth::user()->id;
        $sub_amount->org_id = Auth::user()->org_id;
        $sub_amount->dc = 'C';
        $sub_amount->ledger_id = 27; //Payroll Payable
        $sub_amount->amount = $request->payment_amount;
        $sub_amount->narration = \TaskHelper::getUser($request->user_id)->username.' Payroll Payment received'; //$request->user_id
        $sub_amount->save();

        // Checking account
        $cash_amount = new \App\Models\Entryitem();
        $cash_amount->entry_id = $entry->id;
        $cash_amount->user_id = Auth::user()->id;
        $cash_amount->org_id = Auth::user()->org_id;
        $cash_amount->dc = 'D';
        $cash_amount->ledger_id = $request->payment_method; //select checking account from dropdown
        $cash_amount->amount = $request->payment_amount;
        $cash_amount->narration = \TaskHelper::getUser($request->user_id)->username.' Payroll payment made';
        $cash_amount->save();

        return redirect('/admin/payroll/make_payment');
    }

    public function generatePaySlip()
    {
        $page_title = 'Generate Pay Slip';
        $page_description = 'generate pay slip';

        $departments = Department::orderBy('deptname', 'asc')->get();

        return view('admin.payroll.generatepayslip.index', compact('page_title', 'page_description', 'departments'));
    }

    public function listPayslip(Request $request)
    {
        $page_title = 'Generate Payslip';
        $page_description = 'generate pay slip';

        $departments = Department::orderBy('deptname', 'asc')->get();

        $users = DB::table('users')
                        ->select('first_name', 'last_name', 'id as user_id')
                        ->where('departments_id', $request->departments_id)
                        ->whereNotIn('users.id', [1, 2, 3, 28])
                         ->where('enabled', '1')
                        ->groupBy('users.id')
                        ->get();
        $departments_id = $request->departments_id;

        return view('admin.payroll.generatepayslip.index', compact('page_title', 'page_description', 'departments', 'users', 'departments_id'));
    }

    public function employeeAward()
    {
        $page_title = 'Employee Awards';
        $page_description = 'Awards for employee';

        $users = User::orderBy('designations_id', 'desc')->where('enabled', 1)->get();

        if (\Request::get('year') && \Request::get('year') != '') {
            $employeeawards = EmployeeAward::whereBetween('month', [\Request::get('year').'-01', \Request::get('year').'-12'])->where('org_id', \Auth::user()->org_id)->orderBy('month', 'asc')->get();
        } else {
            $employeeawards = EmployeeAward::whereBetween('month', [date('Y').'-01-01', date('Y').'-12-31'])->where('org_id', \Auth::user()->org_id)->orderBy('month', 'asc')->get();
        }
        //dd($employeeawards);

        return view('admin.payroll.employeeaward.index', compact('page_title', 'users', 'employeeawards', 'page_description', 'page_title'));
    }

    public function employeeAwardStore(Request $request)
    {

        //dd($request->all());

        $attributes = $request->all();
        $attributes['org_id'] = \Auth::user()->org_id;
        EmployeeAward::create($attributes);
        Flash::success('Employee Award has been created successfully.');

        return Redirect::back();
    }

    public function editEmployeeAward($id)
    {
        $holiday = EmployeeAward::where('award_id', $id)->first()->toArray();
        //dd($holiday);
        return $holiday;
    }

    public function updateEmployeeAward(Request $request, $id)
    {
        EmployeeAward::where('award_id', $id)->update(['award_name' => $request->award_name, 'gift_item' => $request->gift_item, 'cash_price' => $request->cash_price, 'month' => $request->month, 'award_date' => $request->award_date]);

        Flash::success('Employee Award has been updated successfully.');

        return Redirect::back();
    }

    public function destroyEmployeeAward($id)
    {
        EmployeeAward::where('award_id', $id)->delete();
        Flash::success('Over Time has been deleted successfully.');

        return Redirect::back();
    }
}
