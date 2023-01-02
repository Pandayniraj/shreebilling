<?php

namespace App\Http\Controllers;

use App\Models\AppraisalTemplate;
use App\Models\AppraisalTemplateType;
use App\Models\AppraisalObjective;
use App\Models\AppraisalObjectiveType;
use Flash;
use Illuminate\Http\Request;

class AppraisalTemplateController extends Controller
{
    public function __construct(AppraisalTemplate $appraisalTemp)
    {
        $this->appraisalTemp = $appraisalTemp;
    }

    public function index()
    {
        $appraisalTemp = $this->appraisalTemp->orderBy('id', 'desc')->paginate(30);
        $page_title = 'Admin | Appraisal | Template';
        $page_description = 'List';

        return view('admin.appraisal_templates.index', compact('appraisalTemp', 'page_title', 'page_description'));
    }

    public function create()
    {
        $page_title = 'Admin | Appraisal | Template | Create';
        $page_description = 'Create a Template';

        return view('admin.appraisal_templates.create', compact('page_title', 'page_description'));
    }

    public function store(Request $request)
    {
        $attributes = $request->all();
        $appraisal_temp=$this->appraisalTemp->create($attributes);

        $temp_array=[['name'=>'Taking initiations with creativity','points'=>'10','status'=>'1'],['name'=>'Work Efficiency','points'=>'15','status'=>'1'],['name'=>'Teamwork Work','points'=>'10','status'=>'1'],['name'=>'Time Management','points'=>'20','status'=>'1']];

        foreach($temp_array as $item){
            $data = AppraisalObjectiveType::create($item);
            AppraisalTemplateType::create([
                'temp_id' => $appraisal_temp->id,
                'type_id' => $data->id
            ]);
            $count=(int)$data->points/5;
            // dd($count);
            for($i=0; $i<$count;$i++){
                AppraisalObjective::create([
                    'obj_type_id' =>$data->id,
                    'objective' => "Text".$i,
                    'marks' => 5
                ]);
            }

        }

        Flash::success('Appraisal Template SuccessFully Created');

        return redirect('/admin/appraisal-template');
    }

    public function edit($id)
    {
        $page_title = 'Admin | Appraisal | Template | Edit';
        $page_description = 'Edit a template #'.$id;
        $appraisalTemp = $this->appraisalTemp->find($id);

        return view('admin.appraisal_templates.edit', compact('page_title', 'page_description', 'appraisalTemp'));
    }

    public function update(Request $request, $id)
    {
        $attributes = $request->all();
        $appraisalTemp = $this->appraisalTemp->find($id);
        $appraisalTemp->update($attributes);
        Flash::success('Appraisal Template SuccessFully Updated');

        return redirect()->back();
    }

    public function getModalDelete($id)
    {
        $template = $this->appraisalTemp->find($id);
        $modal_title = 'Delete Appraisal Template';
        $modal_body = 'Are you sure you want to delete template with title '.$template->name.' and Id'.$id;
        $modal_route = route('admin.appraisalTemplate.delete', ['id' => $id]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroy($id)
    {
        $appraisalTemp = $this->appraisalTemp->find($id);
        $appraisalTemp->delete();
        Flash::success('Appraisal Template SuccessFully Deleted');

        return redirect('/admin/appraisal-template');
    }
}
