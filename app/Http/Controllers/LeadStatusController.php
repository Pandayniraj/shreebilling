<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\LeadStatus as LeadStatus;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadStatusController extends Controller
{
    /**
     * @var LeadStatus
     */
    private $LeadStatus;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param LeadStatus $LeadStatus
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(LeadStatus $LeadStatus, Permission $permission)
    {
        parent::__construct();
        $this->LeadStatus = $LeadStatus;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/leadstatus/general.audit-log.category'), trans('admin/leadstatus/general.audit-log.msg-index'));

        $allLeadStatus = $this->LeadStatus->orderBy('name', 'asc')->paginate(10);
        $page_title = trans('admin/leadstatus/general.page.index.title');
        $page_description = trans('admin/leadstatus/general.page.index.description');

        return view('admin.leadStatus.index', compact('allLeadStatus', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $LeadStatus = $this->LeadStatus->find($id);

        Audit::log(Auth::user()->id, trans('admin/leadstatus/general.audit-log.category'), trans('admin/leadstatus/general.audit-log.msg-show', ['name' => $LeadStatus->name]));

        $page_title = trans('admin/leadstatus/general.page.show.title'); // "Admin | LeadStatus | Show";
        $page_description = trans('admin/leadstatus/general.page.show.description'); // "Displaying LeadStatus: :name";

        return view('admin.leadStatus.show', compact('LeadStatus', 'page_title', 'page_description'));

        return view('admin.leadStatus.show', compact('LeadStatus', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/leadstatus/general.page.create.title'); // "Admin | LeadStatus | Create";
        $page_description = trans('admin/leadstatus/general.page.create.description'); // "Creating a new LeadStatus";

        $LeadStatus = new \App\Models\LeadStatus();
        $perms = $this->permission->all();

        return view('admin.leadStatus.create', compact('LeadStatus', 'perms', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name'          => 'required|unique:lead_status',]);

        $attributes = $request->all();
        Audit::log(Auth::user()->id, trans('admin/leadstatus/general.audit-log.category'), trans('admin/leadstatus/general.audit-log.msg-store', ['name' => $attributes['name']]));

        $LeadStatus = $this->LeadStatus->create($attributes);

        Flash::success(trans('admin/leadstatus/general.status.created')); // 'LeadStatus successfully created');

        return redirect('/admin/leadstatus');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $LeadStatus = $this->LeadStatus->find($id);

        Audit::log(Auth::user()->id, trans('admin/leadstatus/general.audit-log.category'), trans('admin/leadstatus/general.audit-log.msg-edit', ['name' => $LeadStatus->name]));

        $page_title = trans('admin/leadstatus/general.page.edit.title'); // "Admin | LeadStatus | Edit";
        $page_description = trans('admin/leadstatus/general.page.edit.description', ['name' => $LeadStatus->name]); // "Editing LeadStatus";

        if (!$LeadStatus->isEditable() && !$LeadStatus->canChangePermissions()) {
            abort(403);
        }

        return view('admin.leadStatus.edit', compact('LeadStatus', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name'          => 'required|unique:lead_status,name,' . $id,]);

        $LeadStatus = $this->LeadStatus->find($id);

        Audit::log(Auth::user()->id, trans('admin/leadstatus/general.audit-log.category'), trans('admin/leadstatus/general.audit-log.msg-update', ['name' => $LeadStatus->name]));

        $attributes = $request->all();

        if ($LeadStatus->isEditable()) {
            $LeadStatus->update($attributes);
        }

        Flash::success(trans('admin/leadstatus/general.status.updated')); // 'LeadStatus successfully updated');

        return redirect('/admin/leadstatus');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $LeadStatus = $this->LeadStatus->find($id);

        if (!$LeadStatus->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/leadstatus/general.audit-log.category'), trans('admin/leadstatus/general.audit-log.msg-destroy', ['name' => $LeadStatus->name]));

        $LeadStatus->delete($id);

        Flash::success(trans('admin/leadstatus/general.status.deleted')); // 'LeadStatus successfully deleted');

        return redirect('/admin/leadstatus');
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

        $LeadStatus = $this->LeadStatus->find($id);

        if (!$LeadStatus->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/leadstatus/dialog.delete-confirm.title');

        $LeadStatus = $this->LeadStatus->find($id);
        $modal_route = route('admin.leadstatus.delete', ['leadstatusId' => $LeadStatus->id]);

        $modal_body = trans('admin/leadstatus/dialog.delete-confirm.body', ['id' => $LeadStatus->id, 'name' => $LeadStatus->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $LeadStatus = $this->LeadStatus->find($id);

        Audit::log(Auth::user()->id, trans('admin/leadstatus/general.audit-log.category'), trans('admin/leadstatus/general.audit-log.msg-enable', ['name' => $LeadStatus->name]));

        $LeadStatus->enabled = true;
        $LeadStatus->save();

        Flash::success(trans('admin/leadstatus/general.status.enabled'));

        return redirect('/admin/leadstatus');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $LeadStatus = $this->LeadStatus->find($id);

        Audit::log(Auth::user()->id, trans('admin/leadstatus/general.audit-log.category'), trans('admin/leadstatus/general.audit-log.msg-disabled', ['name' => $LeadStatus->name]));

        $LeadStatus->enabled = false;
        $LeadStatus->save();

        Flash::success(trans('admin/leadstatus/general.status.disabled'));

        return redirect('/admin/leadstatus');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkLeadStatus = $request->input('chkLeadStatus');

        Audit::log(Auth::user()->id, trans('admin/leadstatus/general.audit-log.category'), trans('admin/leadstatus/general.audit-log.msg-enabled-selected'), $chkLeadStatus);

        if (isset($chkLeadStatus)) {
            foreach ($chkLeadStatus as $LeadStatus_id) {
                $LeadStatus = $this->LeadStatus->find($LeadStatus_id);
                $LeadStatus->enabled = true;
                $LeadStatus->save();
            }
            Flash::success(trans('admin/leadstatus/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/leadstatus/general.status.no-LeadStatus-selected'));
        }

        return redirect('/admin/leadstatus');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkLeadStatus = $request->input('chkLeadStatus');

        Audit::log(Auth::user()->id, trans('admin/leadstatus/general.audit-log.category'), trans('admin/leadstatus/general.audit-log.msg-disabled-selected'), $chkLeadStatus);

        if (isset($chkLeadStatus)) {
            foreach ($chkLeadStatus as $LeadStatus_id) {
                $LeadStatus = $this->LeadStatus->find($LeadStatus_id);
                $LeadStatus->enabled = false;
                $LeadStatus->save();
            }
            Flash::success(trans('admin/leadstatus/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/leadstatus/general.status.no-LeadStatus-selected'));
        }

        return redirect('/admin/leadstatus');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $LeadStatus = $this->LeadStatus->where('name', '%' . $query . '%')->get();

        foreach ($LeadStatus as $LeadStatus) {
            $id = $LeadStatus->id;
            $name = $LeadStatus->name;
            $email = $LeadStatus->email;

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
        $LeadStatus = $this->LeadStatus->find($id);

        return $LeadStatus;
    }
}
