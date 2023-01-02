<?php

namespace App\Http\Controllers;

use App\Models\LeadType as LeadType;
use App\Models\Role as Permission;
use App\Repositories\AuditRepository as Audit;
use App\Repositories\Criteria\LeadType\LeadTypesWhereDisplayNameLike;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadTypesController extends Controller
{
    /**
     * @var LeadType
     */
    private $LeadType;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param LeadType $LeadType
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(LeadType $LeadType, Permission $permission)
    {
        parent::__construct();
        $this->LeadType = $LeadType;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/LeadTypes/general.audit-log.category'), trans('admin/LeadTypes/general.audit-log.msg-index'));

        $LeadTypes = $this->LeadType->orderBy('name', 'asc')->paginate(10);
        $page_title = trans('admin/LeadTypes/general.page.index.title');
        $page_description = trans('admin/LeadTypes/general.page.index.description');

        return view('admin.LeadTypes.index', compact('LeadTypes', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $LeadType = $this->LeadType->find($id);

        Audit::log(Auth::user()->id, trans('admin/LeadTypes/general.audit-log.category'), trans('admin/LeadTypes/general.audit-log.msg-show', ['name' => $LeadType->name]));

        $page_title = trans('admin/LeadTypes/general.page.show.title'); // "Admin | LeadType | Show";
        $page_description = trans('admin/LeadTypes/general.page.show.description'); // "Displaying LeadType: :name";

        return view('admin.LeadTypes.show', compact('LeadType', 'page_title', 'page_description'));

        return view('admin.LeadTypes.show', compact('LeadType', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/LeadTypes/general.page.create.title'); // "Admin | LeadType | Create";
        $page_description = trans('admin/LeadTypes/general.page.create.description'); // "Creating a new LeadType";

        $LeadType = new \App\Models\LeadType();
        $perms = $this->permission->all();

        return view('admin.LeadTypes.create', compact('LeadType', 'perms', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name'          => 'required|unique:lead_types',
        ]);

        $attributes = $request->all();
        Audit::log(Auth::user()->id, trans('admin/LeadTypes/general.audit-log.category'), trans('admin/LeadTypes/general.audit-log.msg-store', ['name' => $attributes['name']]));

        $LeadType = $this->LeadType->create($attributes);

        Flash::success(trans('admin/LeadTypes/general.status.created')); // 'LeadType successfully created');

        return redirect('/admin/leadtypes');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $LeadType = $this->LeadType->find($id);

        Audit::log(Auth::user()->id, trans('admin/LeadTypes/general.audit-log.category'), trans('admin/LeadTypes/general.audit-log.msg-edit', ['name' => $LeadType->name]));

        $page_title = trans('admin/LeadTypes/general.page.edit.title'); // "Admin | LeadType | Edit";
        $page_description = trans('admin/LeadTypes/general.page.edit.description', ['name' => $LeadType->name]); // "Editing LeadType";

        if (! $LeadType->isEditable() && ! $LeadType->canChangePermissions()) {
            abort(403);
        }

        return view('admin.LeadTypes.edit', compact('LeadType', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name'          => 'required|unique:lead_types,name,'.$id,
        ]);

        $LeadType = $this->LeadType->find($id);

        Audit::log(Auth::user()->id, trans('admin/LeadTypes/general.audit-log.category'), trans('admin/LeadTypes/general.audit-log.msg-update', ['name' => $LeadType->name]));

        $attributes = $request->all();

        if ($LeadType->isEditable()) {
            $LeadType->update($attributes);
        }

        Flash::success(trans('admin/LeadTypes/general.status.updated')); // 'LeadType successfully updated');

        return redirect('/admin/leadtypes');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $LeadType = $this->LeadType->find($id);

        if (! $LeadType->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/LeadTypes/general.audit-log.category'), trans('admin/LeadTypes/general.audit-log.msg-destroy', ['name' => $LeadType->name]));

        $this->LeadType->delete($id);

        Flash::success(trans('admin/LeadTypes/general.status.deleted')); // 'LeadType successfully deleted');

        return redirect('/admin/leadtypes');
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

        $LeadType = $this->LeadType->find($id);

        if (! $LeadType->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/LeadTypes/dialog.delete-confirm.title');

        $LeadType = $this->LeadType->find($id);
        $modal_route = route('admin.leadtypes.delete', ['id' => $LeadType->id]);

        $modal_body = trans('admin/LeadTypes/dialog.delete-confirm.body', ['id' => $LeadType->id, 'name' => $LeadType->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $LeadType = $this->LeadType->find($id);

        Audit::log(Auth::user()->id, trans('admin/LeadTypes/general.audit-log.category'), trans('admin/LeadTypes/general.audit-log.msg-enable', ['name' => $LeadType->name]));

        $LeadType->enabled = true;
        $LeadType->save();

        Flash::success(trans('admin/LeadTypes/general.status.enabled'));

        return redirect('/admin/leadtypes');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $LeadType = $this->LeadType->find($id);

        Audit::log(Auth::user()->id, trans('admin/LeadTypes/general.audit-log.category'), trans('admin/LeadTypes/general.audit-log.msg-disabled', ['name' => $LeadType->name]));

        $LeadType->enabled = false;
        $LeadType->save();

        Flash::success(trans('admin/LeadTypes/general.status.disabled'));

        return redirect('/admin/leadtypes');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkLeadTypes = $request->input('chkLeadType');

        Audit::log(Auth::user()->id, trans('admin/LeadTypes/general.audit-log.category'), trans('admin/LeadTypes/general.audit-log.msg-enabled-selected'), $chkLeadTypes);

        if (isset($chkLeadTypes)) {
            foreach ($chkLeadTypes as $LeadType_id) {
                $LeadType = $this->LeadType->find($LeadType_id);
                $LeadType->enabled = true;
                $LeadType->save();
            }
            Flash::success(trans('admin/LeadTypes/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/LeadTypes/general.status.no-LeadType-selected'));
        }

        return redirect('/admin/leadtypes');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkLeadTypes = $request->input('chkLeadType');

        Audit::log(Auth::user()->id, trans('admin/LeadTypes/general.audit-log.category'), trans('admin/LeadTypes/general.audit-log.msg-disabled-selected'), $chkLeadTypes);

        if (isset($chkLeadTypes)) {
            foreach ($chkLeadTypes as $LeadType_id) {
                $LeadType = $this->LeadType->find($LeadType_id);
                $LeadType->enabled = false;
                $LeadType->save();
            }
            Flash::success(trans('admin/LeadTypes/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/LeadTypes/general.status.no-LeadType-selected'));
        }

        return redirect('/admin/leadtypes');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $LeadTypes = $this->LeadType->where('name', 'LIKE', '%'.$query.'%')->get();

        foreach ($LeadTypes as $LeadType) {
            $id = $LeadType->id;
            $name = $LeadType->name;
            $email = $LeadType->email;

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
        $LeadType = $this->LeadType->find($id);

        return $LeadType;
    }
}
