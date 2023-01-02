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

class SelfAppraisalController extends Controller
{

    public function getTemplateData(Request $request, $templateId)
    {
        $templObjTypes = AppraisalTemplateType::where('temp_id', $templateId)->pluck('type_id');
        $returnHTML = '';
        if(isset($templObjTypes))
        {
            $apprisalObjTypes = AppraisalObjectiveType::whereIn('id', $templObjTypes->toArray())->select('id','name','points')->with('objectives:id,obj_type_id,objective,marks')->where([['status' ,'=', '1']])->get();
            $returnHTML = view('admin.appraisal.ajaxAppraisal', ['apprisalObjTypes' => $apprisalObjTypes])->render();
        }
        return ['data'=>$returnHTML];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Admin | Self Appraisals';
        $page_description = 'List of Self Appraisals';
        $appraisals = AppraisalPerformance::where('employee_id', \Auth::user()->id)->orwhere('evaluator_id',\Auth::user()->id)->select('id','employee_id','evaluator_id','appraisal_month','template_id','total_marks','marks_by_employee','date_reviewed_by_employee', 'marks_by_evaluator','entry_by','appraisal_from','appraisal_to','form_level')->with('employee:id,first_name,last_name,username', 'evaluator:id,first_name,last_name,username', 'template:id,name')->orderBy('id', 'desc')->get();
        return view('admin.appraisal.self.index', compact('appraisals', 'page_title', 'page_description'));
    }
    // public function pending_appraisals()
    // {
    //     $page_title = 'Admin | Pending Appraisals';
    //     $page_description = 'List of Pending Appraisals';
    //     $appraisals = AppraisalPerformance::where('evaluator_id', \Auth::user()->id)->where('form_level','1')->select('id','employee_id','evaluator_id','appraisal_month','template_id','total_marks','marks_by_employee','date_reviewed_by_employee', 'marks_by_evaluator','entry_by','appraisal_from','appraisal_to','form_level')->with('employee:id,first_name,last_name,username', 'evaluator:id,first_name,last_name,username', 'template:id,name')->orderBy('id', 'desc')->get();
    //     return view('admin.appraisal.self.index', compact('appraisals', 'page_title', 'page_description'));
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin | Self Appraisal | Create';
        $page_description = 'Create Self Appraisal';
        $templates = AppraisalTemplate::orderBy('name', 'asc')->get();
        $apprisalObjTypes = AppraisalObjectiveType::select('id','name','points')->with('objectives:id,obj_type_id,objective,marks')->where([['is_master', '=', 'master'],['status' ,'=', '1']])->get();
        return view('admin.appraisal.self.create', compact('page_title', 'page_description', 'apprisalObjTypes', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $chk = AppraisalPerformance::where('employee_id', \Auth::user()->id)->where('appraisal_from', $request->appraisal_from)->where('appraisal_to', $request->appraisal_to)->first();
        // dd($chk);
        if($chk)
        {
            Flash::success('Sorry! Appraisal has already been created for this month.');
            return redirect('/admin/self-appraisals');
        }
        $data = $request->except(['_token', 'appraisal_marks', 'comments', 'marks']);
        $appraisalMarks = $request->appraisal_marks;
        $comments = $request->comments;
        $marks = $request->marks;

        $data['employee_id'] = \Auth::user()->id;
        $data['evaluator_id'] = \Auth::user()->first_line_manager;
        $data['entry_by'] = \Auth::user()->id;
        $data['date_reviewed_by_employee'] = date('Y-m-d H:i:s');
        $data['send_to'] =\Auth::user()->first_line_manager;
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
        $marks_by_employee = 0;

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
                $marks_by_employee += $av;
            }
        }
        $appraisal->update([
            'marks_by_employee' => $marks_by_employee,
            'updated_at' => $data['date_reviewed_by_employee']
        ]);
        // AppraisalPerformanceData
        Flash::success('Self Appraisal Created');

        return redirect('/admin/self-appraisals');
    }

    public function edit(Request $request, $id)
    {
        // dd($id);
        $page_title = 'Admin | Self Appraisal | Edit';
        $appraisal = AppraisalPerformance::with('template:id,name', 'selfAppraisalData:id,appraisal_performance_id,appraisal_data_field,appraisal_data_field_key,appraisal_point,comment,appraisal_by')->findOrFail($id);
        $templates = AppraisalTemplate::orderBy('name', 'asc')->get();
        $apprisalObjTypes = AppraisalObjectiveType::select('id','name','points')->with('objectives:id,obj_type_id,objective,marks')->where([['is_master', '=', 'master'],['status' ,'=', '1']])->get();
        return view('admin.appraisal.self.edit', compact('appraisal', 'page_title', 'apprisalObjTypes', 'templates'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', 'appraisal_marks', 'comments', 'marks']);
        $appraisalMarks = $request->appraisal_marks;
        $comments = $request->comments;
        $marks = $request->marks;

        $appraisal = AppraisalPerformance::where('employee_id', \Auth::user()->id)->orwhere('evaluator_id',\Auth::user()->id)->findOrFail($id);

        $data['date_reviewed_by_employee'] = date('Y-m-d H:i:s');
        $total_marks = 0;

        foreach($marks as $mk => $mv)
            $total_marks += $mv;

        if($request->Send){
            if(isset($appraisal->form_level))
            $data['form_level'] = 2;
            else
            $data['form_level'] = 1;


        }
        //draft
        else{
            $data['form_level'] = 0;
        }
        $data['total_marks'] = $total_marks;

        $appraisal->update($data);
        AppraisalPerformanceData::where('appraisal_performance_id', $id)->where('appraisal_by', \Auth::user()->id)->delete();

        $marks_by_employee = 0;

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
                $marks_by_employee += $av;
            }
        }
        if(  $data['form_level'] == 2){
            $appraisal->update([
                'marks_by_evaluator' => $marks_by_employee,
                'date_reviewed_by_evaluator' => date('Y-m-d H:i:s')
            ]);
        }else{
            $appraisal->update([
                'marks_by_employee' => $marks_by_employee
            ]);
        }
        
        // AppraisalPerformanceData

        Flash::success('Self Appraisal Updated');

        return redirect('/admin/self-appraisals');
    }

    public function deleteModal (Request $request , $id)
    {
        $appraisal = AppraisalPerformance::where('employee_id', \Auth::user()->id)->find($id);
        $modal_title = 'Delete Self Appraisal';
        $modal_body = 'Are you sure you want to delete appraisal with Id #'.$id;
        $modal_route = route('admin.appraisal.self.delete', $id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroy($id)
    {
        $appraisal = AppraisalPerformance::where('employee_id', \Auth::user()->id)->findOrFail($id);
        AppraisalPerformanceData::where('appraisal_performance_id', $id)->delete();
        $appraisal->delete();
        Flash::success('Self Appraisal SucessFully deleted !!');
        return redirect('/admin/self-appraisals');
    }
}
