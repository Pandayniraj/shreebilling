<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Client;
use App\Models\Knowledge as Knowledge;
use App\Models\Knowledge as KnowledgeModel;
use App\Models\Knowledgecat as KnowledgecatModel;
use App\Models\MasterComments;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class KnowledgeController extends Controller
{
    /**
     * @var Client
     */
    private $knowledge;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $knowledge
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Knowledge $knowledge, Permission $permission)
    {
        parent::__construct();
        $this->knowledge = $knowledge;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-index'));

        $knowledge = KnowledgeModel::where('org_id', Auth::user()->org_id)->orderBy('id', 'DESC')->get();
        $cat = KnowledgecatModel::where('org_id', Auth::user()->org_id)->with('knowl')->orderBy('id', 'DESC')->get();

        $page_title = trans('admin/knowledge/general.page.index.title');
        $page_description = trans('admin/knowledge/general.page.index.description');

        return view('admin.knowledge.index', compact('knowledge', 'cat', 'page_title', 'page_description'));
    }

    public function category($cat_id)
    {
        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-index'));

        $knowledge = KnowledgeModel::where('org_id', Auth::user()->org_id)->where('cat_id', $cat_id)->orderBy('id', 'DESC')->get();
        $cat = KnowledgecatModel::where('org_id', Auth::user()->org_id)->with('knowl')->orderBy('id', 'DESC')->get();

        $page_title = trans('admin/knowledge/general.page.index.title');
        $page_description = trans('admin/knowledge/general.page.index.description');

        return view('admin.knowledge.category', compact('knowledge', 'cat', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $knowledge = $this->knowledge->find($id);
        $cat = KnowledgecatModel::with('knowl')->orderBy('id', 'DESC')->get();

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-show', ['name' => $knowledge->name]));

        $page_title = trans('admin/knowledge/general.page.show.title'); // "Admin | Client | Show";
        $page_description = trans('admin/knowledge/general.page.show.description'); // "Displaying client: :name";

        $comments = MasterComments::where('type', 'kb')->where('master_id', $id)->get();

        return view('admin.knowledge.show', compact('knowledge', 'cat', 'page_title', 'page_description', 'comments'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/knowledge/general.page.create.title'); // "Admin | Client | Create";
        $page_description = trans('admin/knowledge/general.page.create.description'); // "Creating a new client";

        $knowledge = new \App\Models\Knowledge();
        $perms = $this->permission->all();
        $cat = KnowledgecatModel::where('org_id', Auth::user()->org_id)->select('id', 'name')->get();
        $users = [];
        return view('admin.knowledge.create', compact('knowledge', 'cat', 'perms', 'page_title', 'page_description', 'users'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'      => 'required',

        ]);

        $attributes = $request->all();
        $attributes['author_id'] = Auth::user()->id;
        $attributes['org_id'] = Auth::user()->org_id;

        if (!isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        $knowledge = $this->knowledge->create($attributes);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-store', ['name' => 'subject created']));

        Flash::success(trans('admin/knowledge/general.status.created')); // 'Client successfully created');

        return redirect('/admin/knowledge');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $knowledge = $this->knowledge->find($id);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-edit', ['name' => $knowledge->name]));

        $page_title = trans('admin/knowledge/general.page.edit.title'); // "Admin | Client | Edit";
        $page_description = trans('admin/knowledge/general.page.edit.description', ['name' => $knowledge->name]); // "Editing client";

        if (!$knowledge->isEditable() && !$knowledge->canChangePermissions()) {
            abort(403);
        }

        $cat = KnowledgecatModel::where('org_id', Auth::user()->org_id)->select('id', 'name')->get();

        return view('admin.knowledge.edit', compact('knowledge', 'cat', 'users', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title'      => 'required',

        ]);

        $attributes = $request->all();

        if (!isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        $knowledge = $this->knowledge->find($id);
        if ($knowledge->isEditable()) {
            $knowledge->update($attributes);
        }

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-update', ['name' => $knowledge->name]));

        Flash::success(trans('admin/knowledge/general.status.updated')); // 'Client successfully updated');

        return redirect('/admin/knowledge');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $knowledge = $this->knowledge->find($id);

        if (!$knowledge->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-destroy', ['name' => $knowledge->name]));

        $knowledge->delete($id);

        MasterComments::where('type', 'kb')->where('master_id', $id)->delete();

        Flash::success(trans('admin/knowledge/general.status.deleted')); // 'Client successfully deleted');

        return redirect('/admin/knowledge');
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

        $knowledge = $this->knowledge->find($id);

        if (!$knowledge->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/knowledge/dialog.delete-confirm.title');

        $knowledge = $this->knowledge->find($id);
        $modal_route = route('admin.knowledge.delete', ['knowledgeId' => $knowledge->id]);

        $modal_body = trans('admin/knowledge/dialog.delete-confirm.body', ['id' => $knowledge->id, 'name' => $knowledge->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $knowledge = $this->knowledge->find($id);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-enable', ['name' => $knowledge->name]));

        $knowledge->enabled = true;
        $knowledge->save();

        Flash::success(trans('admin/knowledge/general.status.enabled'));

        return redirect('/admin/knowledge');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $knowledge = $this->knowledge->find($id);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-disabled', ['name' => $knowledge->name]));

        $knowledge->enabled = false;
        $knowledge->save();

        Flash::success(trans('admin/knowledge/general.status.disabled'));

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

        $knowledge = $this->knowledge->where('title', '%' . $title . '%')->get();

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
        $knowledge = $this->knowledge->find($id);

        return $knowledge;
    }

    public function get_client()
    {
        $term = strtolower(Request::get('term'));
        $contacts = ClientModel::select('id', 'name')->where('name', 'LIKE', '%' . $term . '%')->groupBy('name')->take(5)->get();
        $return_array = [];

        foreach ($contacts as $v) {
            if (strpos(strtolower($v->name), $term) !== false) {
                $return_array[] = ['value' => $v->name, 'id' => $v->id];
            }
        }

        return Response::json($return_array);
    }
}
