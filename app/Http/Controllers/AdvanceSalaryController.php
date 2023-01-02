<?php

namespace App\Http\Controllers;

use App\Models\AdvanceSalary;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class AdvanceSalaryController extends Controller
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
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $page_title = 'Advance Salary';
        $page_description = 'Advance Salary Lists';

        $users = User::orderBy('designations_id', 'desc')->where('enabled', 1)->get();

        if (\Request::get('year') && \Request::get('year') != '') {
            $advanceSalaries = AdvanceSalary::whereBetween('deduct_month', [\Request::get('year').'-01', \Request::get('year').'-12'])->orderBy('deduct_month', 'asc')->get();
        } else {
            $advanceSalaries = AdvanceSalary::whereBetween('deduct_month', [date('Y').'-01-01', date('Y').'-12-31'])->orderBy('deduct_month', 'asc')->get();
        }

        //dd($advanceSalaries);

        return view('admin.advanceSalary.index', compact('page_title', 'page_description', 'users', 'advanceSalaries'));
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
