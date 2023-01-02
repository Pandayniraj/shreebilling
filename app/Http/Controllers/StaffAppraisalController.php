<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\PerformanceApprisal;
use App\Models\AppraisalObjectiveType;
use App\Models\AppraisalObjective;
use App\Models\AppraisalTemplate;
use App\Models\AppraisalTemplateType;
use App\Models\AppraisalPerformance;
use App\Models\AppraisalPerformanceData;
use Flash;
use Illuminate\Http\Request;

class StaffAppraisalController extends Controller
{

    public function index()
    {
        $page_title = 'Admin | Staff Appraisals';
        $page_description = 'List of Staff Appraisals';
    
            $appraisals = AppraisalPerformance::select('id','employee_id','evaluator_id','appraisal_month','template_id','total_marks','marks_by_evaluator','date_reviewed_by_evaluator', 'marks_by_employee','entry_by','appraisal_from','appraisal_to','form_level')->with('employee:id,first_name,last_name,username', 'evaluator:id,first_name,last_name,username', 'template:id,name')->orderBy('id', 'desc')->get();
   
        return view('admin.appraisal.staff.index', compact('appraisals', 'page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin | Staff Appraisal | Create';
        $page_description = 'Create Staff Appraisal';
        $templates = AppraisalTemplate::orderBy('name', 'asc')->get();
        $apprisalObjTypes = AppraisalObjectiveType::select('id','name','points')->with('objectives:id,obj_type_id,objective,marks')->where([['is_master', '=', 'master'],['status' ,'=', '1']])->get();
        $department = Department::all();
        return view('admin.appraisal.staff.create', compact('page_title', 'page_description', 'apprisalObjTypes', 'templates', 'department'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $chk = AppraisalPerformance::where('evaluator_id', \Auth::user()->id)->where('employee_id', $request->employee_id)->where('appraisal_from', $request->appraisal_from)->where('appraisal_to', $request->appraisal_to)->first();
        if($chk)
        {
            Flash::success('Sorry! Appraisal has already been created for this month.');
            return redirect('/admin/staff-appraisals');
        }

        $data = $request->except(['_token', 'appraisal_marks', 'comments', 'marks']);
        $appraisalMarks = $request->appraisal_marks;
        $comments = $request->comments;
        $marks = $request->marks;

        $data['evaluator_id'] = \Auth::user()->id;
        $data['entry_by'] = \Auth::user()->id;
        $data['date_reviewed_by_evaluator'] = date('Y-m-d H:i:s');
        $data['send_to'] = $request->send_to;
        $data['form_level'] = $request->form_level;
        $data['appraisal_from'] = $request->appraisal_from;
        $data['appraisal_to'] = $request->appraisal_to;
        if($request->Send){
            $data['form_level'] = 1;
        }
        //draft
        else{
            $data['form_level'] = 0;
        }

        $total_marks = 0;

        foreach($marks as $mk => $mv)
            $total_marks += $mv;

        $data['total_marks'] = $total_marks;
        $appraisal = AppraisalPerformance::create($data);
        $marks_by_evaluator = 0;

        foreach($appraisalMarks as $amk => $amv)
        {
            foreach($amv as $ak => $av)
            {
                AppraisalPerformanceData::create([
                    'appraisal_performance_id' => $appraisal->id,
                    'appraisal_data_field' => $amk,
                    'appraisal_data_field_key' => $ak,
                    'appraisal_point' => $av,
                    'comment' => $comments[$amk][$ak],
                    'appraisal_by' => \Auth::user()->id
                ]);
                $marks_by_evaluator += $av;
            }
        }
        $appraisal->update([
            'marks_by_evaluator' => $marks_by_evaluator,
            'updated_at' => $data['date_reviewed_by_evaluator']
        ]);
        // AppraisalPerformanceData
        Flash::success('Staff Appraisal Created');

        return redirect('/admin/staff-appraisals');
    }

    public function edit(Request $request, $id)
    {

        $page_title = 'Admin | Staff Appraisal | Edit';
        $appraisal = AppraisalPerformance::with('template:id,name', 'selfAppraisalData:id,appraisal_performance_id,appraisal_data_field,appraisal_data_field_key,appraisal_point,comment,appraisal_by')->findOrFail($id);
        // dd($appraisal);
        $templates = AppraisalTemplate::orderBy('name', 'asc')->get();
        $apprisalObjTypes = AppraisalObjectiveType::select('id','name','points')->with('objectives:id,obj_type_id,objective,marks')->where([['is_master', '=', 'master'],['status' ,'=', '1']])->get();
        $department = Department::all();
        // dd($appraisal,$page_title,$apprisalObjTypes,$templates,$department);
        return view('admin.appraisal.staff.edit', compact('appraisal', 'page_title', 'apprisalObjTypes', 'templates', 'department'));


        // $page_title = 'Admin | Self Appraisal | Edit';
        // $appraisal = AppraisalPerformance::with('template:id,name', 'selfAppraisalData:id,appraisal_performance_id,appraisal_data_field,appraisal_data_field_key,appraisal_point,comment,appraisal_by')->findOrFail($id);
        // $templates = AppraisalTemplate::orderBy('name', 'asc')->get();
        // $apprisalObjTypes = AppraisalObjectiveType::select('id','name','points')->with('objectives:id,obj_type_id,objective,marks')->where([['is_master', '=', 'master'],['status' ,'=', '1']])->get();
        // return view('admin.appraisal.staff.edit', compact('appraisal', 'page_title', 'apprisalObjTypes', 'templates'));
    }

    public function update(Request $request, $id)
    {
        $appraisalMarks = $request->appraisal_marks;
        $comments = $request->comments;
        $marks = $request->marks;
        // dd($request->all());
        $appraisal = AppraisalPerformance::where('evaluator_id', \Auth::user()->id)->findOrFail($id);
        if($appraisal->entry_by != \Auth::user()->id)
            $data = $request->except(['_token', 'employee_id', 'appraisal_month', 'template_id', 'appraisal_marks', 'comments', 'marks']);
        else
        {
            if((int)$appraisal->marks_by_employee != 0)
                $data = $request->except(['_token', 'employee_id', 'appraisal_month', 'template_id', 'appraisal_marks', 'comments', 'marks']);
            else
                $data = $request->except(['_token', 'appraisal_marks', 'comments', 'marks']);

        }

        $data['date_reviewed_by_evaluator'] = date('Y-m-d H:i:s');
        $data['form_level']=2;
        $total_marks = 0;
        foreach($marks as $mk => $mv)
            $total_marks += $mv;
        $data['total_marks'] = $total_marks;

        $appraisal->update($data);
        AppraisalPerformanceData::where('appraisal_performance_id', $id)->where('appraisal_by', \Auth::user()->id)->delete();

        $marks_by_evaluator = 0;

        foreach($appraisalMarks as $amk => $amv)
        {
            foreach($amv as $ak => $av)
            {
                AppraisalPerformanceData::create([
                    'appraisal_performance_id' => $appraisal->id,
                    'appraisal_data_field' => $amk,
                    'appraisal_data_field_key' => $ak,
                    'appraisal_point' => $av,
                    'comment' => $comments[$amk][$ak],
                    'appraisal_by' => \Auth::user()->id
                ]);
                $marks_by_evaluator += $av;
            }
        }
        $appraisal->update([
            'marks_by_evaluator' => $marks_by_evaluator
        ]);
        // AppraisalPerformanceData
        Flash::success('Staff Appraisal Updated');

        return redirect('/admin/staff-appraisals');
    }

    public function deleteModal (Request $request , $id)
    {
        $appraisal = AppraisalPerformance::where('evaluator_id', \Auth::user()->id)->find($id);
        $modal_title = 'Delete Staff Appraisal';
        $modal_body = 'Are you sure you want to delete appraisal with Id #'.$id;
        $modal_route = route('admin.appraisal.staff.delete', $id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroy($id)
    {
        $appraisal = AppraisalPerformance::where('evaluator_id', \Auth::user()->id)->findOrFail($id);
        AppraisalPerformanceData::where('appraisal_performance_id', $id)->delete();
        $appraisal->delete();
        Flash::success('Staff Appraisal SucessFully deleted !!');
        return redirect('/admin/staff-appraisals');
    }
}
