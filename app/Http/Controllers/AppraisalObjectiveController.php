<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\AppraisalObjective;
use App\Models\AppraisalObjectiveType;
use Flash;
use Illuminate\Http\Request;

class AppraisalObjectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($typeId)
    {
        $page_title = 'Admin | Appraisal | Objective';
        $page_description = 'List of  Appraisal Objective';
        $objectiveType = AppraisalObjectiveType::select('id', 'name')->where('id', $typeId)->first();
        if (!$objectiveType) {
            Flash::success('Sorry! No Appraisal Objective Type found!!');
            return redirect('/admin/appraisal/objective-types');
        }
        $objectives = AppraisalObjective::where('obj_type_id', $typeId)->orderBy('id', 'desc')->get();
        return view('admin.appraisal-objective.index', compact('objectiveType', 'objectives', 'page_title','page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($typeId)
    {
        $objectiveType = AppraisalObjectiveType::select('id','name')->where('id', $typeId)->first();
        if (!$objectiveType) {
            Flash::success('Sorry! No Appraisal Objective Type found!!');
            return redirect('/admin/appraisal/objective-types');
        }
        $page_title = 'Admin | Appraisal | Objective | Create';
        $page_description = 'Create Appraisal Objective';
        return view('admin.appraisal-objective.create', compact('page_title','page_description','objectiveType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $typeId)
    {
        $objectiveType = AppraisalObjectiveType::select('id','name')->where('id', $typeId)->first();
        if (!$objectiveType) {
            Flash::success('Sorry! No Appraisal Objective Type found!!');
            return redirect('/admin/appraisal/objective-types');
        }
        $data = $request->all();
        AppraisalObjective::create([
            'obj_type_id' => $typeId,
            'objective' => $request->objective,
            'marks' => $request->marks
        ]);
        Flash::success('Objective Successfully Created !!');

        return redirect('/admin/appraisal/'.$typeId.'/objectives');
    }

    public function edit($objId)
    {
        $page_title = 'Admin | Appraisal | Objective | Edit';
        $page_description = 'Create Appraisal Objective';
        $objective = AppraisalObjective::where('id', $objId)->first();
        if (!$objective) {
            Flash::success('Sorry! No Appraisal Objective found!!');
            return redirect('/admin/appraisal/objective-types');
        }
        
        return view('admin.appraisal-objective.edit', compact('objective', 'page_title','page_description'));
    }

    public function update($objId, Request $request)
    {
        $objective = AppraisalObjective::where('id', $objId)->first();
        $objective->update([
            'objective' => $request->objective,
            'marks' => $request->marks
        ]);
        Flash::success('Objective Successfully Updated !!');

        return redirect('/admin/appraisal/'.$objective->obj_type_id.'/objectives');
    }

    public function getModalDelete($id)
    {
        $modal_title = 'Delete Objective';
        $modal_body = 'Are you sure you want to delete objective with Id'.$id;
        $modal_route = route('admin.appraisal.objectives.delete', ['id' => $id]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function delete($id)
    {
        $objective = AppraisalObjective::where('id', $id)->first();
        $obj_id = $objective->obj_type_id;
        $objective->delete();
        Flash::success('Objective SucessFully Deleted !! ');

        return redirect('/admin/appraisal/'.$obj_id.'/objectives');
    }

}
