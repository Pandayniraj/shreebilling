<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Role as Permission;
use App\Models\Taskscat as TaskscatModel;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class TaskscatController extends Controller
{
    /**
     * @var Client
     */
    private $taskscat;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $knowledgecat
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(TaskscatModel $taskscat, Permission $permission)
    {
        parent::__construct();
        $this->taskscat = $taskscat;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-index'));
        $tasks = TaskscatModel::where('org_id', Auth::user()->org_id)->orderBy('id', 'DESC')->get();
        //  dd($tasks);

        $page_title = 'Tasks Category';
        $page_description = 'tasks Category';

        return view('admin.taskscat.index', compact('tasks', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $tasks = $this->taskscat->find($id);
        //   dd($projects);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-show', ['name' => $tasks->name]));

        $page_title = 'Admin | Projects Category| Show'; // "Admin | Client | Show";
        $page_description = 'Admin | Projects Category| Show'; // "Displaying client: :name";

        return view('admin.taskscat.show', compact('tasks', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Tasks Category Create'; // "Admin | Client | Create";
        $page_description = 'Creating a new Tasks category'; // "Creating a new client";

        $tasks = new TaskscatModel();

        $perms = $this->permission->all();
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');

        return view('admin.taskscat.create', compact('tasks', 'perms', 'page_title', 'page_description', 'users'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required',

        ]);

        $attributes = $request->all();
        // $attributes['user_id'] = Auth::user()->id;
        $attributes['org_id'] = Auth::user()->org_id;

        //  dd( Auth::user()->org_id);

        //  dd($attributes);

        if (!isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        $tasks = $this->taskscat->create($attributes);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-store', ['name' => 'subject created']));

        Flash::success('Tasks Category created '); // 'Client successfully created');

        return redirect('/admin/taskscat');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $tasks = $this->taskscat->find($id);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-edit', ['name' => $tasks->name]));

        $page_title = 'Admin | Client | Edit'; // "Admin | Client | Edit";
        $page_description = trans('admin/knowledge/general.page.edit.description', ['name' => $tasks->name]); // "Editing client";
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');

        if (!$tasks->isEditable() && !$tasks->canChangePermissions()) {
            abort(403);
        }

        return view('admin.taskscat.edit', compact('tasks','users', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'      => 'required',

        ]);

        $attributes = $request->all();

        if (!isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        $tasks = $this->taskscat->find($id);
        if ($tasks->isEditable()) {
            $tasks->update($attributes);
        }

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-update', ['name' => $tasks->name]));

        Flash::success('Tasks Categories successfully Updated'); // 'Client successfully updated');

        return redirect('/admin/taskscat');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    // public function destroy($id)

    public function destroy($id)
    {
        $taskscat = $this->taskscat->find($id);

        $taskscat->delete($id);

        if (!$taskscat->isdeletable()) {
            abort(403);
        }
        //Audit::log(Auth::user()->id, trans('admin/courses/general.audit-log.category'), trans('admin/courses/general.audit-log.msg-destroy', ['name' => $taskscat->name]));

        Flash::success('Project Task Category successfully deleted'); // 'Course successfully deleted');

        return redirect('/admin/taskscat');
    }

    /**
     * Delete Confirm.
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalDelete($id)
    {
        $error = null;

        $taskscat = $this->taskscat->find($id);

        if (!$taskscat->isdeletable()) {
            abort(403);
        }

        $modal_title = ' Do you surely want to to delete this';
        $taskscat = $this->taskscat->find($id);
        $modal_route = route('admin.taskscat.delete', ['tasksCatId' => $taskscat->id]);
        $modal_body = trans('admin/knowledge/dialog.delete-confirm.body', ['id' => $taskscat->id, 'name' => $taskscat->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $projects = $this->taskscat->find($id);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-enable', ['name' => $projects->name]));

        $projects->enabled = true;
        $projects->save();

        Flash::success(' Project Task  category Enabled');

        return redirect('/admin/taskscat');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $projects = $this->taskscat->find($id);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-disabled', ['name' => $projects->name]));

        $projects->enabled = false;
        $projects->save();

        Flash::success('Project category disabled');

        return redirect('/admin/taskscat');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkknowledge = $request->input('chkClient');

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-enabled-selected'), $chkknowledge);

        if (isset($chkknowledge)) {
            foreach ($chkknowledge as $knowledge_id) {
                $knowledge = $this->knowledge->find($knowledge_id);
                $knowledge->enabled = true;
                $knowledge->save();
            }
            Flash::success(trans('admin/knowledge/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/knowledge/general.status.no-client-selected'));
        }

        return redirect('/admin/knowledge');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkknowledge = $request->input('chkClient');

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-disabled-selected'), $chkknowledge);

        if (isset($chkknowledge)) {
            foreach ($chkknowledge as $knowledge_id) {
                $knowledge = $this->knowledge->find($knowledge_id);
                $knowledge->enabled = false;
                $knowledge->save();
            }
            Flash::success(trans('admin/knowledge/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/knowledge/general.status.no-client-selected'));
        }

        return redirect('/admin/knowledge');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $knowledge = $this->knowledge->pushCriteria(new knowledgeWhereDisplayNameLike($query))->all();

        foreach ($knowledge as $knowledge) {
            $id = $knowledge->id;
            $name = $knowledge->name;
            $email = $knowledge->email;

            $entry_arr = ['id' => $id, 'text' => "$name ($email)"];
            $return_arr[] = $entry_arr;
        }

        return $return_arr;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getInfo(Request $request)
    {
        $id = $request->input('id');
        $knowledge = $this->knowledgecat->find($id);

        return $knowledge;
    }
}
