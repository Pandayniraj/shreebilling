<?php

namespace App\Http\Controllers;

use App\Models\AdvanceSalary;
use App\Models\Role as Permission;
use App\Models\SalaryPayment;
use App\Models\SalaryPaymentDeduction;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class ProvidentFundController extends Controller
{
    /**
     * @var Client
     */
    private $client;

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

    public function index()
    {
        $page_title = 'Provident Fund';
        $page_description = 'Provident Fund Lists';

        $users = User::orderBy('designations_id', 'desc')->where('enabled', 1)->get();

        $salarydeductions = SalaryPaymentDeduction::orderBy('salary_payment_deduction', 'desc')->get();

        //dd($salarydeductions);

        if (\Request::get('year') && \Request::get('year') != '') {
            $advanceSalaries = SalaryPayment::whereBetween('payment_month', [\Request::get('year').'-01', \Request::get('year').'-12'])->orderBy('deduct_month', 'asc')->get();
        } else {
            $advanceSalaries = SalaryPayment::whereBetween('payment_month', [date('Y').'-01-01', date('Y').'-12-31'])->orderBy('payment_month', 'asc')->get();
        }

        //dd($advanceSalaries);

        //dd($advanceSalaries);

        return view('admin.payroll.providentfund.index', compact('page_title', 'page_description', 'salarydeductions', 'users', 'advanceSalaries'));
    }

    public function store(Request $request)
    {

        //dd($request->all());
        AdvanceSalary::create($request->all());
        Flash::success('Advance Salary has been created successfully.');

        return Redirect::back();
    }

    public function destroy($id)
    {
        AdvanceSalary::where('advance_salary_id', $id)->delete();
        Flash::success('Advance Salary has been deleted successfully.');

        return Redirect::back();
    }

    public function edit($id)
    {
        $advanceSalary = AdvanceSalary::where('advance_salary_id', $id)->first()->toArray();

        // dd($advanceSalary);
        return $advanceSalary;
    }

    public function update(Request $request, $id)
    {
        AdvanceSalary::where('advance_salary_id', $id)->update(['user_id' => $request->user_id, 'advance_amount' => $request->advance_amount, 'deduct_month' => $request->deduct_month, 'reason' => $request->reason, 'request_date' => $request->request_date, 'status' => $request->status]);

        Flash::success('Advance Salary has been updated successfully.');

        return Redirect::back();
    }
}
