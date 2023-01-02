<?php

namespace App\Http\Controllers;

use App\Repositories\AuditRepository as Audit;
use App\Repositories\Criteria\LeadEnquiryMode\LeadEnquiryModeByNamesAscending;
use App\Repositories\Criteria\LeadEnquiryMode\LeadEnquiryModeWhereDisplayNameLike;
use App\Repositories\LeadEnquiryModeRepository as LeadEnquiryMode;
use App\Repositories\RoleRepository as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadEnquiryModeController extends Controller
{
    /**
     * @var LeadEnquiryMode
     */
    private $LeadEnquiryMode;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param LeadEnquiryMode $LeadEnquiryMode
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(LeadEnquiryMode $LeadEnquiryMode, Permission $permission)
    {
        parent::__construct();
        $this->LeadEnquiryMode = $LeadEnquiryMode;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/lead-enquiry-mode/general.audit-log.category'), trans('admin/lead-enquiry-mode/general.audit-log.msg-index'));

        $allLeadEnquiryMode = $this->LeadEnquiryMode->pushCriteria(new LeadEnquiryModeByNamesAscending())->paginate(10);
        $page_title = trans('admin/lead-enquiry-mode/general.page.index.title');
        $page_description = trans('admin/lead-enquiry-mode/general.page.index.description');

        return view('admin.leadEnquiryMode.index', compact('allLeadEnquiryMode', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $LeadEnquiryMode = $this->LeadEnquiryMode->find($id);

        Audit::log(Auth::user()->id, trans('admin/lead-enquiry-mode/general.audit-log.category'), trans('admin/lead-enquiry-mode/general.audit-log.msg-show', ['name' => $LeadEnquiryMode->name]));

        $page_title = trans('admin/lead-enquiry-mode/general.page.show.title'); // "Admin | LeadEnquiryMode | Show";
        $page_description = trans('admin/lead-enquiry-mode/general.page.show.description'); // "Displaying LeadEnquiryMode: :name";

        return view('admin.leadEnquiryMode.show', compact('LeadEnquiryMode', 'page_title', 'page_description'));

        return view('admin.leadEnquiryMode.show', compact('LeadEnquiryMode', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/lead-enquiry-mode/general.page.create.title'); // "Admin | LeadEnquiryMode | Create";
        $page_description = trans('admin/lead-enquiry-mode/general.page.create.description'); // "Creating a new LeadEnquiryMode";

        $LeadEnquiryMode = new \App\Models\Enquirymode();
        $perms = $this->permission->all();

        return view('admin.leadEnquiryMode.create', compact('LeadEnquiryMode', 'perms', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name'          => 'required|unique:lead_enquiry_modes',
        ]);

        $attributes = $request->all();
        Audit::log(Auth::user()->id, trans('admin/lead-enquiry-mode/general.audit-log.category'), trans('admin/lead-enquiry-mode/general.audit-log.msg-store', ['name' => $attributes['name']]));

        $LeadEnquiryMode = $this->LeadEnquiryMode->create($attributes);

        Flash::success(trans('admin/lead-enquiry-mode/general.status.created')); // 'LeadEnquiryMode successfully created');

        return redirect('/admin/leadenquirymode');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $LeadEnquiryMode = $this->LeadEnquiryMode->find($id);

        Audit::log(Auth::user()->id, trans('admin/lead-enquiry-mode/general.audit-log.category'), trans('admin/lead-enquiry-mode/general.audit-log.msg-edit', ['name' => $LeadEnquiryMode->name]));

        $page_title = trans('admin/lead-enquiry-mode/general.page.edit.title'); // "Admin | LeadEnquiryMode | Edit";
        $page_description = trans('admin/lead-enquiry-mode/general.page.edit.description', ['name' => $LeadEnquiryMode->name]); // "Editing LeadEnquiryMode";

        if (! $LeadEnquiryMode->isEditable() && ! $LeadEnquiryMode->canChangePermissions()) {
            abort(403);
        }

        return view('admin.leadEnquiryMode.edit', compact('LeadEnquiryMode', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name'          => 'required|unique:lead_enquiry_modes,name,'.$id,
        ]);

        $LeadEnquiryMode = $this->LeadEnquiryMode->find($id);

        Audit::log(Auth::user()->id, trans('admin/lead-enquiry-mode/general.audit-log.category'), trans('admin/lead-enquiry-mode/general.audit-log.msg-update', ['name' => $LeadEnquiryMode->name]));

        $attributes = $request->all();

        if ($LeadEnquiryMode->isEditable()) {
            $LeadEnquiryMode->update($attributes);
        }

        Flash::success(trans('admin/lead-enquiry-mode/general.status.updated')); // 'LeadEnquiryMode successfully updated');

        return redirect('/admin/leadenquirymode');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $LeadEnquiryMode = $this->LeadEnquiryMode->find($id);

        if (! $LeadEnquiryMode->isDeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/lead-enquiry-mode/general.audit-log.category'), trans('admin/lead-enquiry-mode/general.audit-log.msg-destroy', ['name' => $LeadEnquiryMode->name]));

        $this->LeadEnquiryMode->delete($id);

        Flash::success(trans('admin/lead-enquiry-mode/general.status.deleted')); // 'LeadEnquiryMode successfully deleted');

        return redirect('/admin/leadenquirymode');
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

        $LeadEnquiryMode = $this->LeadEnquiryMode->find($id);

        if (! $LeadEnquiryMode->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/lead-enquiry-mode/dialog.delete-confirm.title');

        $LeadEnquiryMode = $this->LeadEnquiryMode->find($id);
        $modal_route = route('admin.leadenquirymode.delete', ['id' => $LeadEnquiryMode->id]);

        $modal_body = trans('admin/lead-enquiry-mode/dialog.delete-confirm.body', ['id' => $LeadEnquiryMode->id, 'name' => $LeadEnquiryMode->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $LeadEnquiryMode = $this->LeadEnquiryMode->find($id);

        Audit::log(Auth::user()->id, trans('admin/lead-enquiry-mode/general.audit-log.category'), trans('admin/lead-enquiry-mode/general.audit-log.msg-enable', ['name' => $LeadEnquiryMode->name]));

        $LeadEnquiryMode->enabled = true;
        $LeadEnquiryMode->save();

        Flash::success(trans('admin/lead-enquiry-mode/general.status.enabled'));

        return redirect('/admin/leadenquirymode');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $LeadEnquiryMode = $this->LeadEnquiryMode->find($id);

        Audit::log(Auth::user()->id, trans('admin/lead-enquiry-mode/general.audit-log.category'), trans('admin/lead-enquiry-mode/general.audit-log.msg-disabled', ['name' => $LeadEnquiryMode->name]));

        $LeadEnquiryMode->enabled = false;
        $LeadEnquiryMode->save();

        Flash::success(trans('admin/lead-enquiry-mode/general.status.disabled'));

        return redirect('/admin/leadenquirymode');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkLeadEnquiryMode = $request->input('chkLeadEnquiryMode');

        Audit::log(Auth::user()->id, trans('admin/lead-enquiry-mode/general.audit-log.category'), trans('admin/lead-enquiry-mode/general.audit-log.msg-enabled-selected'), $chkLeadEnquiryMode);

        if (isset($chkLeadEnquiryMode)) {
            foreach ($chkLeadEnquiryMode as $LeadEnquiryMode_id) {
                $LeadEnquiryMode = $this->LeadEnquiryMode->find($LeadEnquiryMode_id);
                $LeadEnquiryMode->enabled = true;
                $LeadEnquiryMode->save();
            }
            Flash::success(trans('admin/lead-enquiry-mode/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/lead-enquiry-mode/general.status.no-LeadEnquiryMode-selected'));
        }

        return redirect('/admin/leadenquirymode');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkLeadEnquiryMode = $request->input('chkLeadEnquiryMode');

        Audit::log(Auth::user()->id, trans('admin/lead-enquiry-mode/general.audit-log.category'), trans('admin/lead-enquiry-mode/general.audit-log.msg-disabled-selected'), $chkLeadEnquiryMode);

        if (isset($chkLeadEnquiryMode)) {
            foreach ($chkLeadEnquiryMode as $LeadEnquiryMode_id) {
                $LeadEnquiryMode = $this->LeadEnquiryMode->find($LeadEnquiryMode_id);
                $LeadEnquiryMode->enabled = false;
                $LeadEnquiryMode->save();
            }
            Flash::success(trans('admin/lead-enquiry-mode/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/lead-enquiry-mode/general.status.no-LeadEnquiryMode-selected'));
        }

        return redirect('/admin/leadenquirymode');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $LeadEnquiryMode = $this->LeadEnquiryMode->pushCriteria(new LeadEnquiryModeWhereDisplayNameLike($query))->all();

        foreach ($LeadEnquiryMode as $LeadEnquiryMode) {
            $id = $LeadEnquiryMode->id;
            $name = $LeadEnquiryMode->name;
            $email = $LeadEnquiryMode->email;

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
        $LeadEnquiryMode = $this->LeadEnquiryMode->find($id);

        return $LeadEnquiryMode;
    }
}
