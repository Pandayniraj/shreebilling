<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Bugs as Bugs;
use App\Models\Bugs as BugsModel;
use App\Models\Client;
use App\Models\MasterComments;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class BugsController extends Controller
{
    /**
     * @var Client
     */
    private $bugs;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $bug
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Bugs $bugs, Permission $permission)
    {
        parent::__construct();
        $this->bugs = $bugs;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/bugs/general.audit-log.category'), trans('admin/bugs/general.audit-log.msg-index'));

        if (Auth::user()->hasRole('admins')) {
            $bugs = BugsModel::orderBy('id', 'DESC')->get();
        } else {
            $generatedBugs = BugsModel::orderBy('id', 'desc')
                            ->where('user_id', Auth::user()->id)
                           // ->where('assigned_to',\Auth::user()->id)
                            ->get()
                            ->pluck('id');

            $assignedBugs = BugsModel::orderBy('id', 'desc')
                            //->where('user_id',\Auth::user()->id)
                            ->where('assigned_to', Auth::user()->id)
                            ->get()
                            ->pluck('id');

            $bugsIDs = array_merge($generatedBugs->toArray(), $assignedBugs->toArray());

            $bugs = BugsModel::whereIn('id', $bugsIDs)
                                        ->orderBy('id', 'desc')
                                        ->get();
        }

        $page_title = trans('admin/bugs/general.page.index.title');
        $page_description = trans('admin/bugs/general.page.index.description');

        return view('admin.bugs.index', compact('bugs', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $bug = $this->bugs->find($id);

        if ($bug->viewed == 0) {
            DB::table('bugs')->where('id', $id)->update(['viewed' => 1]);
        }

        Audit::log(Auth::user()->id, trans('admin/bugs/general.audit-log.category'), trans('admin/bugs/general.audit-log.msg-show', ['name' => $bug->name]));

        $page_title = trans('admin/bugs/general.page.show.title'); // "Admin | Client | Show";
        $page_description = trans('admin/bugs/general.page.show.description'); // "Displaying client: :name";

        $comments = MasterComments::where('type', 'bugs')->where('master_id', $id)->get();

        return view('admin.bugs.show', compact('bug', 'page_title', 'page_description', 'comments'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/bugs/general.page.create.title'); // "Admin | Client | Create";
        $page_description = trans('admin/bugs/general.page.create.description'); // "Creating a new client";

        $bug = new \App\Models\Client();
        $perms = $this->permission->all();
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');
        $projects = \App\Models\Projects::select('id', 'name')->get();

        return view('admin.bugs.create', compact('bug', 'projects', 'perms', 'page_title', 'page_description', 'users'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'subject'      => 'required',
            'description'      => 'required',

        ]);

        $attributes = $request->all();

        $attributes['user_id'] = Auth::user()->id;

        if (! isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        $bug = $this->bugs->create($attributes);

        Audit::log(Auth::user()->id, trans('admin/bugs/general.audit-log.category'), trans('admin/bugs/general.audit-log.msg-store', ['name' => 'bugs']));

        Flash::success(trans('admin/bugs/general.status.created')); // 'Client successfully created');

        return redirect('/admin/bugs');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $bug = $this->bugs->find($id);

        if ($bug->viewed == 0) {
            DB::table('bugs')->where('id', $id)->update(['viewed' => 1]);
        }

        Audit::log(Auth::user()->id, trans('admin/bugs/general.audit-log.category'), trans('admin/bugs/general.audit-log.msg-edit', ['name' => $bug->name]));

        $page_title = trans('admin/bugs/general.page.edit.title'); // "Admin | Client | Edit";
        $page_description = trans('admin/bugs/general.page.edit.description', ['name' => $bug->name]); // "Editing client";
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');
        $projects = \App\Models\Projects::select('id', 'name')->get();

        if (! $bug->isEditable() && ! $bug->canChangePermissions()) {
            abort(403);
        }

        return view('admin.bugs.edit', compact('bug', 'projects', 'users', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'subject'      => 'required',
            'description'      => 'required',

        ]);

        $attributes = $request->all();

        if (! isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        $bugs = $this->bugs->find($id);
        if ($bugs->isEditable()) {
            $bugs->update($attributes);
        }

        Audit::log(Auth::user()->id, trans('admin/bugs/general.audit-log.category'), trans('admin/bugs/general.audit-log.msg-update', ['name' => $bugs->name]));

        Flash::success(trans('admin/bugs/general.status.updated')); // 'Client successfully updated');

        return redirect('/admin/bugs');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $bugs = $this->bugs->find($id);

        if (! $bugs->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/bugs/general.audit-log.category'), trans('admin/bugs/general.audit-log.msg-destroy', ['name' => $bugs->name]));

        $bugs->delete($id);

        MasterComments::where('type', 'bugs')->where('master_id', $id)->delete();

        Flash::success(trans('admin/bugs/general.status.deleted')); // 'Client successfully deleted');

        return redirect('/admin/bugs');
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

        $bugs = $this->bugs->find($id);

        if (! $bugs->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/bugs/dialog.delete-confirm.title');

        $bugs = $this->bugs->find($id);
        $modal_route = route('admin.bugs.delete', ['id' => $bugs->id]);

        $modal_body = trans('admin/bugs/dialog.delete-confirm.body', ['id' => $bugs->id, 'name' => $bugs->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $bugs = $this->bugs->find($id);

        Audit::log(Auth::user()->id, trans('admin/bugs/general.audit-log.category'), trans('admin/bugs/general.audit-log.msg-enable', ['name' => $bugs->name]));

        $bugs->enabled = true;
        $bugs->save();

        Flash::success(trans('admin/bugs/general.status.enabled'));

        return redirect('/admin/bugs');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $bugs = $this->bugs->find($id);

        Audit::log(Auth::user()->id, trans('admin/bugs/general.audit-log.category'), trans('admin/bugs/general.audit-log.msg-disabled', ['name' => $bugs->name]));

        $bugs->enabled = false;
        $bugs->save();

        Flash::success(trans('admin/bugs/general.status.disabled'));

        return redirect('/admin/bugs');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkbugs = $request->input('chkClient');

        Audit::log(Auth::user()->id, trans('admin/bugs/general.audit-log.category'), trans('admin/bugs/general.audit-log.msg-enabled-selected'), $chkbugs);

        if (isset($chkbugs)) {
            foreach ($chkbugs as $bugs_id) {
                $bugs = $this->bugs->find($bugs_id);
                $bugs->enabled = true;
                $bugs->save();
            }
            Flash::success(trans('admin/bugs/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/bugs/general.status.no-client-selected'));
        }

        return redirect('/admin/bugs');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkbugs = $request->input('chkClient');

        Audit::log(Auth::user()->id, trans('admin/bugs/general.audit-log.category'), trans('admin/bugs/general.audit-log.msg-disabled-selected'), $chkbugs);

        if (isset($chkbugs)) {
            foreach ($chkbugs as $bugs_id) {
                $bugs = $this->bugs->find($bugs_id);
                $bugs->enabled = false;
                $bugs->save();
            }
            Flash::success(trans('admin/bugs/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/bugs/general.status.no-client-selected'));
        }

        return redirect('/admin/bugs');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $bugs = $this->bugs->where('name', 'LIKE', '%'.$query.'%')->get();

        foreach ($bugs as $bugs) {
            $id = $bugs->id;
            $name = $bugs->name;
            $email = $bugs->email;

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
        $bugs = $this->bugs->find($id);

        return $bugs;
    }

    public function get_client(Request $request)
    {
        $term = strtolower($request->input('term'));
        $contacts = ClientModel::select('id', 'name')->where('name', 'LIKE', '%'.$term.'%')->groupBy('name')->take(5)->get();
        $return_array = [];

        foreach ($contacts as $v) {
            if (strpos(strtolower($v->name), $term) !== false) {
                $return_array[] = ['value' => $v->name, 'id' =>$v->id];
            }
        }

        return Response::json($return_array);
    }
}
