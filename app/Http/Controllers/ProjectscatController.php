<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\MasterComments;
use App\Models\Projectscat as ProjectscatModel;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class ProjectscatController extends Controller
{
    /**
     * @var Client
     */
    private $projects;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $knowledgecat
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(ProjectscatModel $projectscat, Permission $permission)
    {
        parent::__construct();
        $this->projectscat = $projectscat;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-index'));
        $projects = ProjectscatModel::where('org_id', Auth::user()->org_id)->orderBy('id', 'DESC')->get();
        //  dd($projects);

        $page_title = 'Projects Category';
        $page_description = 'projects Category';

        return view('admin.projectscat.index', compact('projects', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $projects = $this->projectscat->find($id);
        //   dd($projects);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-show', ['name' => $projectscat->name]));

        $page_title = 'Admin | Projects Category| Show'; // "Admin | Client | Show";
        $page_description = 'Admin | Projects Category| Show'; // "Displaying client: :name";

        return view('admin.projectscat.show', compact('projects', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Projects Category Create'; // "Admin | Client | Create";
        $page_description = 'Creating a new Projects category'; // "Creating a new client";

        $projects = new ProjectscatModel();
        $perms = $this->permission->all();
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');

        return view('admin.projectscat.create', compact('projects', 'perms', 'page_title', 'page_description', 'users'));
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

        $projects = $this->projectscat->create($attributes);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-store', ['name' => 'subject created']));

        Flash::success('Project Category created '); // 'Client successfully created');

        return redirect('/admin/projectscat');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $projects = $this->projectscat->find($id);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-edit', ['name' => $projects->name]));

        $page_title = 'Admin | Client | Edit'; // "Admin | Client | Edit";
        $page_description = trans('admin/knowledge/general.page.edit.description', ['name' => $projects->name]); // "Editing client";
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');

        if (!$projects->isEditable() && !$projects->canChangePermissions()) {
            abort(403);
        }

        return view('admin.projectscat.edit', compact('projects','users', 'page_title', 'page_description'));
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

        $projects = $this->projectscat->find($id);
        if ($projects->isEditable()) {
            $projects->update($attributes);
        }

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-update', ['name' => $projects->name]));

        Flash::success('Projects Categories successfully Updated'); // 'Client successfully updated');

        return redirect('/admin/projectscat');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $projectscat = $this->projectscat->find($id);

        if (!$projectscat->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-destroy', ['name' => $projectscat->name]));

        // dd( $projectscat);

        $this->projectscat->find($id)->delete();

        MasterComments::where('type', 'kb')->where('master_id', $id)->delete();

        Flash::success('Project Category successfully deleted'); // 'Client successfully deleted');

        return redirect('/admin/projectscat');
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

        $projectscat = $this->projectscat->find($id);

        if (!$projectscat->isdeletable()) {
            abort(403);
        }

        $modal_title = ' Do you surely want to to delete this';

        $projectscat = $this->projectscat->find($id);
        $modal_route = route('admin.projectscat.delete', ['projectsCatId' => $projectscat->id]);

        $modal_body = trans('admin/knowledge/dialog.delete-confirm.body', ['id' => $projects->id, 'name' => $projectscat->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $projects = $this->projectscat->find($id);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-enable', ['name' => $projects->name]));

        $projects->enabled = true;
        $projects->save();

        Flash::success(' Project category Enabled');

        return redirect('/admin/projects');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $projects = $this->projectscat->find($id);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-disabled', ['name' => $projects->name]));

        $projects->enabled = false;
        $projects->save();

        Flash::success('Project category disabled');

        return redirect('/admin/knowledge');
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
