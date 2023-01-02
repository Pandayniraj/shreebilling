<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Knowledgecat as KnowledgecatModel;
use App\Models\MasterComments;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class KnowledgecatController extends Controller
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
     * @param Client $knowledgecat
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(KnowledgecatModel $knowledgecat, Permission $permission)
    {
        parent::__construct();
        $this->knowledgecat = $knowledgecat;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-index'));
        $knowledge = KnowledgecatModel::where('org_id', Auth::user()->org_id)->orderBy('id', 'DESC')->get();
        $page_title = 'Knowledge Category';
        $page_description = 'Knowledge Category';

        return view('admin.knowledgecat.index', compact('knowledge', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $knowledge = $this->knowledgecat->find($id);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-show', ['name' => $knowledgecat->name]));

        $page_title = trans('admin/knowledge/general.page.show.title'); // "Admin | Client | Show";
        $page_description = trans('admin/knowledge/general.page.show.description'); // "Displaying client: :name";

        return view('admin.knowledgecat.show', compact('knowledge', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Knowledge Category Create'; // "Admin | Client | Create";
        $page_description = trans('admin/knowledge/general.page.create.description'); // "Creating a new client";

        $knowledge = new KnowledgecatModel();
        $perms = $this->permission->all();
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');

        return view('admin.knowledgecat.create', compact('knowledge', 'perms', 'page_title', 'page_description', 'users'));
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
        $attributes['user_id'] = Auth::user()->id;
        $attributes['org_id'] = Auth::user()->org_id;

        if (!isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        //dd($attributes);

        $knowledge = $this->knowledgecat->create($attributes);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-store', ['name' => 'subject created']));

        Flash::success(trans('admin/knowledge/general.status.created')); // 'Client successfully created');

        return redirect('/admin/knowledgecat');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $knowledge = $this->knowledgecat->find($id);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-edit', ['name' => $knowledge->name]));

        $page_title = trans('admin/knowledge/general.page.edit.title'); // "Admin | Client | Edit";
        $page_description = trans('admin/knowledge/general.page.edit.description', ['name' => $knowledge->name]); // "Editing client";
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');

        if (!$knowledge->isEditable() && !$knowledge->canChangePermissions()) {
            abort(403);
        }

        return view('admin.knowledgecat.edit', compact('knowledge', 'cat', 'catDetail', 'users', 'page_title', 'page_description'));
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

        $knowledge = $this->knowledgecat->find($id);
        if ($knowledge->isEditable()) {
            $knowledge->update($attributes);
        }

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-update', ['name' => $knowledge->name]));

        Flash::success(trans('admin/knowledge/general.status.updated')); // 'Client successfully updated');

        return redirect('/admin/knowledgecat');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $knowledgecat = $this->knowledgecat->find($id);

        if (!$knowledgecat->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-destroy', ['name' => $knowledgecat->name]));

        $knowledgecat->delete($id);

        MasterComments::where('type', 'kb')->where('master_id', $id)->delete();

        Flash::success(trans('admin/knowledge/general.status.deleted')); // 'Client successfully deleted');

        return redirect('/admin/knowledgecat');
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

        $knowledge = $this->knowledgecat->find($id);

        if (!$knowledge->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/knowledge/dialog.delete-confirm.title');

        $knowledge = $this->knowledgecat->find($id);
        $modal_route = route('admin.knowledgecat.delete', ['knowledgeCatId' => $knowledge->id]);

        $modal_body = trans('admin/knowledge/dialog.delete-confirm.body', ['id' => $knowledge->id, 'name' => $knowledge->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $knowledge = $this->knowledgecat->find($id);

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

        $knowledge = $this->knowledgecat->find($id);

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
