<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Models\OnboardingTaskType;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(OnboardingTaskType $task_type, Permission $permission)
    {
        parent::__construct();
        $this->task_type = $task_type;
        $this->permission = $permission;
    }

    public function index()
    {
        $page_title = 'Onboarding | Tasktype';
        $tasktype = $this->task_type->orderBy('id', 'desc')->paginate(30);

        return view('admin.onboarding.task_type.index', compact('tasktype', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_description = 'Create a new task type';
        $page_title = 'Onboarding | Tasktype';
        $owner = \App\User::where('enabled', '1')->get();

        return view('admin.onboarding.task_type.create', compact('owner', 'page_title', 'page_description'));
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
        $this->task_type->create($attributes);
        Flash::success('Onboarding task type created');

        return redirect('/admin/onboard/tasktype');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_description = "Showing task type of #{$id}";
        $page_title = 'Onboarding | Tasktype';
        $tasktype = $this->task_type->find($id);
        $owner = \App\User::where('enabled', '1')->get();

        return view('admin.onboarding.task_type.show', compact('tasktype', 'owner', 'page_title', 'page_description'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_description = "Edit task type of #{$id}";
        $page_title = 'Onboarding | Tasktype';
        $tasktype = $this->task_type->find($id);
        $owner = \App\User::where('enabled', '1')->get();

        return view('admin.onboarding.task_type.edit', compact('tasktype', 'owner', 'page_title', 'page_description'));
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
        $this->task_type->find($id)->update($attributes);
        Flash::success('Successfully updated');

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
        $this->task_type->find($id)->delete();
        Flash::success('Successfully deleted');

        return redirect()->back();
    }

    public function getModalDelete($id)
    {
        $error = null;

        $tasktype = $this->task_type->find($id);

        if (! $tasktype->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete task type';
        $modal_route = route('admin.onboard.tasktype.delete', $id);

        $modal_body = 'Are you sure you want to delete tasktype with owner '.$tasktype->owner->username.' and id '.$id;

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
