<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Communication as Communication;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunicationController extends Controller
{
    /**
     * @var communication
     */
    private $communication;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param communication $communication
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Communication $communication, Permission $permission)
    {
        parent::__construct();
        $this->communication = $communication;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/communication/general.audit-log.category'), trans('admin/communication/general.audit-log.msg-index'));

        $communications = $this->communication->orderBy('name', 'ASC')->paginate(30);
        $page_title = trans('admin/communication/general.page.index.title');
        $page_description = trans('admin/communication/general.page.index.description');

        return view('admin.communication.index', compact('communications', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $communication = $this->communication->find($id);

        Audit::log(Auth::user()->id, trans('admin/communications/general.audit-log.category'), trans('admin/communication/general.audit-log.msg-show', ['name' => $communication->name]));

        $page_title = trans('admin/communication/general.page.show.title'); // "Admin | communication | Show";
        $page_description = trans('admin/communication/general.page.show.description'); // "Displaying communication: :name";

        return view('admin.communication.show', compact('communication', 'page_title', 'page_description'));

        return view('admin.communication.show', compact('communication', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/communication/general.page.create.title'); // "Admin | communication | Create";
        $page_description = trans('admin/communication/general.page.create.description'); // "Creating a new communication";

        $communication = new \App\Models\communication();
        $perms = $this->permission->all();

        return view('admin.communication.create', compact('communication', 'perms', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required|unique:lead_communications',]);

        $attributes = $request->all();
        Audit::log(
            Auth::user()->id,
            trans('admin/communication/general.audit-log.category'),
            trans('admin/communication/general.audit-log.msg-store', ['name' => $attributes['name']])
        );

        $communication = $this->communication->create($attributes);

        Flash::success(trans('admin/communication/general.status.created')); // 'communication successfully created');

        return redirect('/admin/communication');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $communication = $this->communication->find($id);

        Audit::log(Auth::user()->id, trans('admin/communication/general.audit-log.category'), trans('admin/communication/general.audit-log.msg-edit', ['name' => $communication->name]));

        $page_title = trans('admin/communication/general.page.edit.title'); // "Admin | communication | Edit";
        $page_description = trans('admin/communication/general.page.edit.description', ['name' => $communication->name]); // "Editing communication";

        if (!$communication->isEditable() && !$communication->canChangePermissions()) {
            abort(403);
        }

        return view('admin.communication.edit', compact('communication', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name' => 'required|unique:lead_communications,name,' . $id,]);

        $communication = $this->communication->find($id);

        Audit::log(Auth::user()->id, trans('admin/communication/general.audit-log.category'), trans('admin/communication/general.audit-log.msg-update', ['name' => $communication->name]));

        $attributes = $request->all();

        if ($communication->isEditable()) {
            $communication->update($attributes);
        }

        Flash::success(trans('admin/communication/general.status.updated')); // 'communication successfully updated');

        return redirect('/admin/communication');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $communication = $this->communication->find($id);

        if (!$communication->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/communication/general.audit-log.category'), trans('admin/communication/general.audit-log.msg-destroy', ['name' => $communication->name]));

        $communication->delete($id);

        Flash::success(trans('admin/communication/general.status.deleted')); // 'communication successfully deleted');

        return redirect('/admin/communication');
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

        $communication = $this->communication->find($id);

        if (!$communication->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/communication/dialog.delete-confirm.title');

        $communication = $this->communication->find($id);
        $modal_route = route('admin.communication.delete', ['communicationId' => $communication->id]);

        $modal_body = trans('admin/communication/dialog.delete-confirm.body', ['id' => $communication->id, 'name' => $communication->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $communication = $this->communication->find($id);

        Audit::log(
            Auth::user()->id,
            trans('admin/communication/general.audit-log.category'),
            trans('admin/communication/general.audit-log.msg-enable', ['name' => $communication->name])
        );

        $communication->enabled = true;
        $communication->save();

        Flash::success(trans('admin/communication/general.status.enabled'));

        return redirect('/admin/communication');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $communication = $this->communication->find($id);

        Audit::log(Auth::user()->id, trans('admin/communication/general.audit-log.category'), trans('admin/communication/general.audit-log.msg-disabled', ['name' => $communication->name]));

        $communication->enabled = false;
        $communication->save();

        Flash::success(trans('admin/communication/general.status.disabled'));

        return redirect('/admin/communication');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkcommunication = $request->input('chkCommunication');

        Audit::log(Auth::user()->id, trans('admin/communication/general.audit-log.category'), trans('admin/communication/general.audit-log.msg-enabled-selected'), $chkcommunication);

        if (isset($chkcommunication)) {
            foreach ($chkcommunication as $communication_id) {
                $communication = $this->communication->find($communication_id);
                $communication->enabled = true;
                $communication->save();
            }
            Flash::success(trans('admin/communication/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/communication/general.status.no-communication-selected'));
        }

        return redirect('/admin/communication');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkcommunication = $request->input('chkCommunication');

        Audit::log(Auth::user()->id, trans('admin/communication/general.audit-log.category'), trans('admin/communication/general.audit-log.msg-disabled-selected'), $chkcommunication);

        if (isset($chkcommunication)) {
            foreach ($chkcommunication as $communication_id) {
                $communication = $this->communication->find($communication_id);
                $communication->enabled = false;
                $communication->save();
            }
            Flash::success(trans('admin/communication/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/communication/general.status.no-communication-selected'));
        }

        return redirect('/admin/communication');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $communication = $this->communication->where('name', 'LIKE', '%' . $query . '%');

        foreach ($communication as $communication) {
            $id = $communication->id;
            $name = $communication->name;
            $email = $communication->email;

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
        $communication = $this->communication->find($id);

        return $communication;
    }
}
