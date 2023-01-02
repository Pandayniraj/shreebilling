<?php

namespace App\Http\Controllers;

use App\Models\TimeSheetSalary;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TimeSheetSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(TimeSheetSalary $timesheetsalary)
    {
        $this->timesheetsalary = $timesheetsalary;
    }

    public function index()
    {
        $page_title = 'Admin | TimeSheetSalary';

        $timesheetsalary = $this->timesheetsalary->where('org_id',\Auth::user()->org_id)->paginate(30);

        return view('admin.timesheet.salary.index', compact('timesheetsalary', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_description = 'Created New TimeSheetSalary Template';
        $page_title = 'Admin | TimeSheetSalary';
        $designation = \App\Models\Designation::all();

        return view('admin.timesheet.salary.create', compact('designation', 'page_description', 'page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'salary_grade'      => 'required|unique:timesheet_salary_templates',
        ]);
        $attributes = $request->all();
        $attributes['enabled'] = isset($request->enabled) ? '1' : '0';
        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['user_id'] = Auth::user()->id;
        $this->timesheetsalary->create($attributes);
        Flash::success('TimeSheetSalary Template SuccessFully Created');

        return redirect('/admin/timesheetsalary/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_description = 'Edit TimeSheetSalary Template #'.$id;
        $page_title = 'Admin | TimeSheetSalary';
        $salary = $this->timesheetsalary->find($id);
        $designation = \App\Models\Designation::all();

        return view('admin.timesheet.salary.edit', compact('designation', 'salary', 'page_description', 'page_title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'salary_grade'      => 'required|unique:timesheet_salary_templates,salary_grade,'.$id,
        ]);
        $attributes = $request->all();
        $timesheetsalary = $this->timesheetsalary->find($id);
        if (! $timesheetsalary->isEditable()) {
            abort(403);
        }
        $attributes['enabled'] = isset($request->enabled) ? '1' : '0';
        $timesheetsalary->update($attributes);
        Flash::success('TimeSheetSalary Template SuccessFully Updated');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $timesheetsalary = $this->timesheetsalary->find($id);
        if (! $timesheetsalary->isEditable()) {
            abort(403);
        }
        $timesheetsalary->delete();

        return redirect()->back();
    }

    public function enabledisable($id)
    {
        $timesheetsalary = $this->timesheetsalary->find($id);
        $attributes['enabled'] = $timesheetsalary->enabled ? '0' : '1';
        $timesheetsalary->update($attributes);

        return redirect()->back();
    }

    public function managesalary()
    {
        $page_title = 'Admin | TimeSheetSalary';
        $page_description = 'Manage TimeSheet Employee Salary';
        $projects = \App\Models\Projects::all();
        if (Session::get('project_id')) {
            $project_id = Session::get('project_id');
            $salaryTemplates = $this->timesheetsalary->where('enabled', '1')->get();
            $users = \App\User::where('project_id', $project_id)->where('payroll_method', 't')->orderBy('username', 'ASC')->get();

            return view('admin.timesheet.salary.salary_details', compact('projects', 'project_id', 'users', 'salaryTemplates', 'page_title', 'page_description'));
        }

        return view('admin.timesheet.salary.salary_details', compact('projects', 'page_description', 'page_title'));
    }

    public function managesalary_details(Request $request)
    {
        $page_title = 'Admin | TimeSheetSalary';
        $page_description = 'Manage TimeSheet Employee Salary';
        $projects = \App\Models\Projects::all();
        $project_id = $request->project_id;
        $salaryTemplates = $this->timesheetsalary->where('enabled', '1')->get();
        $users = \App\User::where('project_id', $project_id)->where('payroll_method', 't')->orderBy('username', 'ASC')->get();

        return view('admin.timesheet.salary.salary_details', compact('projects', 'project_id', 'users', 'salaryTemplates', 'page_title', 'page_description'));
    }

    public function managesalary_details_post(Request $request)
    {
        foreach ($request->user_id as $key=>$user_id) {
            $salaryTemplates = \App\Models\TimeSheetUserSalary::where('user_id', $user_id)->first();
            if ($salaryTemplates) {
                $update = ['salary_template_id'=>($request->salary_template_id)[$key], 'assigned_by'=>\Auth::user()->id];
                $salaryTemplates->update($update);
            } else {
                $template = ['salary_template_id'=>($request->salary_template_id)[$key], 'assigned_by'=>\Auth::user()->id, 'user_id'=>$user_id];
                \App\Models\TimeSheetUserSalary::create($template);
            }
        }
        Flash::success('Salary Template SuccessFully Updated');

        return redirect()->back()->with('project_id', $request->project_id);
    }
}
