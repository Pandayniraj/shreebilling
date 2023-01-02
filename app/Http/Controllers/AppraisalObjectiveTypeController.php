<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\AppraisalObjectiveType;
use App\Models\AppraisalObjective;
use App\Models\AppraisalTemplate;
use App\Models\AppraisalTemplateType;
use Flash;
use Illuminate\Http\Request;

class AppraisalObjectiveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Admin | Appraisal | Objective Type';
        $page_description = 'List of  Appraisal Objective Type';

        $objectivetypes = AppraisalObjectiveType::with('template')->orderBy('id', 'desc')->get();

        return view('admin.appraisal-objective-type.index', compact('objectivetypes', 'page_title','page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin | Appraisal | Objective Type | Create';
        $page_description = 'Create Appraisal Objective Type';
        $templates = AppraisalTemplate::orderBy('id', 'desc')->get();
        return view('admin.appraisal-objective-type.create', compact('page_title','page_description', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $objectivetypes = $request->except('appraisal_template');
        $data = AppraisalObjectiveType::create($objectivetypes);
        if(!$request->is_master)
        {
            AppraisalTemplateType::create([
                'temp_id' => $request->appraisal_template,
                'type_id' => $data->id
            ]);

        }
        $count=(int)$data->points/5;
        // dd($count);
        for($i=0; $i<$count;$i++){
            AppraisalObjective::create([
                'obj_type_id' =>$data->id,
                'objective' => "Text".$i,
                'marks' => 5
            ]);
        }

        Flash::success('Objective Type Successfully Created !!');

        return redirect('/admin/appraisal/objective-types/');
    }

    public function edit($id)
    {
        $page_title = 'Admin | Appraisal | Objective Type | Edit';
        $page_description = 'Create Appraisal Objective Type';
        $edit = AppraisalObjectiveType::with('template:id,temp_id,type_id')->where('id', $id)->first();
        $templates = AppraisalTemplate::orderBy('id', 'desc')->get();
        return view('admin.appraisal-objective-type.edit', compact('edit', 'page_title','page_description', 'templates'));
    }

    public function update($id, Request $request)
    {
        $objectivetypes = $request->except('_token', 'appraisal_template');
        $request->is_master ? ($objectivetypes['is_master'] = $request->is_master) : ($objectivetypes['is_master'] = null);
        $request->status ? ($objectivetypes['status'] = $request->status) : ($objectivetypes['status'] = '0');

        // dd($request->all());
        AppraisalObjectiveType::where('id', $id)->update($objectivetypes);
        if($request->is_master)
            AppraisalTemplateType::where('type_id', $id)->delete();
        else
            AppraisalTemplateType::updateOrCreate(['type_id' => $id], ['temp_id' => $request->appraisal_template]);

        Flash::success('Objective Type Successfully Updated !!');

        return redirect('/admin/appraisal/objective-types/');
    }

    public function getModalDelete($id)
    {
        // $template = AppraisalObjectiveType::where('id', $id)->delete();
        $modal_title = 'Delete Objective Type Template';
        $modal_body = 'Are you sure you want to delete objective type with title '.$template->name.' and Id'.$id;
        $modal_route = route('admin.appraisal.objective-types.delete', ['id' => $id]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function delete($id)
    {
        AppraisalObjectiveType::where('id', $id)->delete();
        AppraisalTemplateType::where('type_id', $id)->delete();
        Flash::success('Objective Type SucessFully Deleted !! ');

        return redirect('/admin/appraisal/objective-types/');
    }

}
