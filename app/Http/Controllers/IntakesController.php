<?php

namespace App\Http\Controllers;

use App\Models\Intake as Intake;
use App\Models\Role as Permission;
use App\Repositories\AuditRepository as Audit;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntakesController extends Controller
{
    /**
     * @var Intake
     */
    private $intake;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Intake $intake
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Intake $intake, Permission $permission)
    {
        parent::__construct();
        $this->intake = $intake;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/intakes/general.audit-log.category'), trans('admin/intakes/general.audit-log.msg-index'));

        $intakes = $this->intake->orderBy('name', 'desc')->paginate(10);
        $page_title = trans('admin/intakes/general.page.index.title');
        $page_description = trans('admin/intakes/general.page.index.description');

        return view('admin.intakes.index', compact('intakes', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $intake = $this->intake->find($id);

        Audit::log(Auth::user()->id, trans('admin/intakes/general.audit-log.category'), trans('admin/intakes/general.audit-log.msg-show', ['name' => $intake->name]));

        $page_title = trans('admin/intakes/general.page.show.title'); // "Admin | Intake | Show";
        $page_description = trans('admin/intakes/general.page.show.description'); // "Displaying intake: :name";

        return view('admin.intakes.show', compact('intake', 'page_title', 'page_description'));

        return view('admin.intakes.show', compact('intake', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/intakes/general.page.create.title'); // "Admin | Intake | Create";
        $page_description = trans('admin/intakes/general.page.create.description'); // "Creating a new intake";

        $intake = new \App\Models\Intake();
        $perms = $this->permission->all();

        return view('admin.intakes.create', compact('intake', 'perms', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name'          => 'required|unique:lead_intakes',
        ]);

        $attributes = $request->all();
        Audit::log(Auth::user()->id, trans('admin/intakes/general.audit-log.category'), trans('admin/intakes/general.audit-log.msg-store', ['name' => $attributes['name']]));

        $intake = $this->intake->create($attributes);

        Flash::success(trans('admin/intakes/general.status.created')); // 'Intake successfully created');

        return redirect('/admin/intakes');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $intake = $this->intake->find($id);

        Audit::log(Auth::user()->id, trans('admin/intakes/general.audit-log.category'), trans('admin/intakes/general.audit-log.msg-edit', ['name' => $intake->name]));

        $page_title = trans('admin/intakes/general.page.edit.title'); // "Admin | Intake | Edit";
        $page_description = trans('admin/intakes/general.page.edit.description', ['name' => $intake->name]); // "Editing intake";

        if (! $intake->isEditable() && ! $intake->canChangePermissions()) {
            abort(403);
        }

        return view('admin.intakes.edit', compact('intake', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name'          => 'required|unique:lead_intakes,name,'.$id,
        ]);

        $intake = $this->intake->find($id);

        Audit::log(Auth::user()->id, trans('admin/intakes/general.audit-log.category'), trans('admin/intakes/general.audit-log.msg-update', ['name' => $intake->name]));

        $attributes = $request->all();

        if ($intake->isEditable()) {
            $intake->update($attributes);
        }

        Flash::success(trans('admin/intakes/general.status.updated')); // 'Intake successfully updated');

        return redirect('/admin/intakes');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $intake = $this->intake->find($id);

        if (! $intake->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/intakes/general.audit-log.category'), trans('admin/intakes/general.audit-log.msg-destroy', ['name' => $intake->name]));

        $this->intake->delete($id);

        Flash::success(trans('admin/intakes/general.status.deleted')); // 'Intake successfully deleted');

        return redirect('/admin/intakes');
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

        $intake = $this->intake->find($id);

        if (! $intake->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/intakes/dialog.delete-confirm.title');

        $intake = $this->intake->find($id);
        $modal_route = route('admin.intakes.delete', ['id' => $intake->id]);

        $modal_body = trans('admin/intakes/dialog.delete-confirm.body', ['id' => $intake->id, 'name' => $intake->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $intake = $this->intake->find($id);

        Audit::log(Auth::user()->id, trans('admin/intakes/general.audit-log.category'), trans('admin/intakes/general.audit-log.msg-enable', ['name' => $intake->name]));

        $intake->enabled = true;
        $intake->save();

        Flash::success(trans('admin/intakes/general.status.enabled'));

        return redirect('/admin/intakes');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $intake = $this->intake->find($id);

        Audit::log(Auth::user()->id, trans('admin/intakes/general.audit-log.category'), trans('admin/intakes/general.audit-log.msg-disabled', ['name' => $intake->name]));

        $intake->enabled = false;
        $intake->save();

        Flash::success(trans('admin/intakes/general.status.disabled'));

        return redirect('/admin/intakes');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkIntakes = $request->input('chkIntake');

        Audit::log(Auth::user()->id, trans('admin/intakes/general.audit-log.category'), trans('admin/intakes/general.audit-log.msg-enabled-selected'), $chkIntakes);

        if (isset($chkIntakes)) {
            foreach ($chkIntakes as $intake_id) {
                $intake = $this->intake->find($intake_id);
                $intake->enabled = true;
                $intake->save();
            }
            Flash::success(trans('admin/intakes/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/intakes/general.status.no-intake-selected'));
        }

        return redirect('/admin/intakes');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkIntakes = $request->input('chkIntake');

        Audit::log(Auth::user()->id, trans('admin/intakes/general.audit-log.category'), trans('admin/intakes/general.audit-log.msg-disabled-selected'), $chkIntakes);

        if (isset($chkIntakes)) {
            foreach ($chkIntakes as $intake_id) {
                $intake = $this->intake->find($intake_id);
                $intake->enabled = false;
                $intake->save();
            }
            Flash::success(trans('admin/intakes/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/intakes/general.status.no-intake-selected'));
        }

        return redirect('/admin/intakes');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $intakes = $this->intake->where('name', 'LIKE', '%'.$query.'%')->get();

        foreach ($intakes as $intake) {
            $id = $intake->id;
            $name = $intake->name;
            $email = $intake->email;

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
        $intake = $this->intake->find($id);

        return $intake;
    }
}
