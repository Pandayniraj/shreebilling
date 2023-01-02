<?php

namespace App\Http\Controllers;

use Flash;
use Illuminate\Http\Request;

class TaskStageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Admin | Task | Stages';

        $page_description = 'List of Task Stages';

        $taskstages = \App\Models\TaskStage::orderBy('id', 'desc')->get();

        return view('admin.taskstages.index', compact('taskstages', 'page_title', 'page_description'));
    }

    public function create()
    {
        $page_title = 'Create | Stages';
        $page_description = '';

        return view('admin.taskstages.create', compact('page_title', 'page_description'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required',

        ]);

        $attributes = $request->all();

        //dd($attributes);

        $transactiongroups = \App\Models\TaskStage::create($attributes);
        Flash::success('Task Stages sucessfully added');

        return redirect('/admin/task/stages');
    }

    public function edit($id)
    {
        $taskstages = \App\Models\TaskStage::find($id);

        $page_title = 'Edit Task Stages';
        $page_description = '';

        return view('admin.taskstages.edit', compact('taskstages', 'page_title', 'page_description'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'      => 'required',

        ]);

        $attributes = $request->all();

        \App\Models\TaskStage::find($id)->update($attributes);

        Flash::success('Task Stages sucessfully updated');

        return redirect('/admin/task/stages');
    }

    public function getModalDelete($id)
    {
        $error = null;
        $taskstages = \App\Models\TaskStage::find($id);

        $modal_title = 'Delete Task Stages';
        $modal_body = 'Are you sure that you want to delete Task Stages id '.$taskstages->id.' with the number '.$taskstages->name.'? This operation is irreversible';

        $modal_route = route('admin.task.stages.delete', $taskstages->id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroy($id)
    {
        $taskstages = \App\Models\TaskStage::find($id)->delete();
        Flash::success('Task Stages  sucessfully deleted');

        return redirect('/admin/task/stages');
    }
}
