<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Models\OnboardTask;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(OnboardTask $task)
    {
        $this->task = $task;
    }

    public function index()
    {
        $page_title = 'Onboard | Task';
        $task = $this->task->orderBy('id', 'desc')->paginate(30);
        $priority = ['low'=>'btn bg-maroon margin', 'high'=>'btn-danger margin', 'medium'=>'btn bg-blue'];

        return view('admin.onboarding.task.index', compact('task', 'page_title', 'priority'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Onboard | Task';
        $page_description = 'create new task';
        $task_event = \App\Models\OnboardEvents::all();
        $task_type = \App\Models\OnboardingTaskType::all();
        $owner = \App\User::all();
        $priority = ['low', 'medium', 'high'];

        return view('admin.onboarding.task.create', compact('task_type', 'task_event', 'owner', 'priority', 'page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->all();
        $attributes['user_id'] = Auth::user()->id;
        $this->task->create($attributes);
        Flash::success('Task Sucessfully created');

        return Redirect('/admin/onboard/task');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_title = 'Onboard | Task';
        $page_description = "Showing task #{$id}";
        $comments = \App\Models\MasterComments::where('type', 'onboard_task')->where('master_id', $id)->get();
        $tasks = $this->task->find($id);

        return view('admin.onboarding.task.show', compact('task_type', 'task_event', 'owner', 'priority', 'tasks', 'comments', 'page_title', 'page_description'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = 'Onboard | Task';
        $page_description = "Edit task #{$id}";
        $tasks = $this->task->find($id);
        $task_event = \App\Models\OnboardEvents::all();
        $task_type = \App\Models\OnboardingTaskType::all();
        $owner = \App\User::all();
        $priority = ['low', 'medium', 'high'];

        return view('admin.onboarding.task.edit', compact('task_type', 'task_event', 'owner', 'priority', 'tasks', 'page_title', 'page_description'));
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
        $attributes = $request->all();
        $tasks = $this->task->find($id);
        $tasks->update($attributes);
        Flash::success('Task Sucessfully Updated');

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
        $this->task->find($id)->delete();
        \App\Models\MasterComments::where('type', 'onboard_task')->where('master_id', $id)->delete();
        Flash::success('Task Sucessfully deleted');

        return redirect()->back();
    }

    public function getTaskinfo($id)
    {
        $tinfo = \App\Models\OnboardingTaskType::find($id);

        return response()->json($tinfo);
    }

    public function getModalDelete($id)
    {
        $error = null;
        $tasks = $this->task->find($id);

        if (! $tasks->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete task';
        $modal_route = route('admin.onboard.task.delete', $id);

        $modal_body = 'Are you sure you want to delete task with name '.$tasks->name.' and id '.$id;

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
