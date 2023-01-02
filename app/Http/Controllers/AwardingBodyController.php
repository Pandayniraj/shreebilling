<?php

namespace App\Http\Controllers;

use App\Models\Awardingbody as AwardingBody;
use App\Models\Role as Permission;
use App\Repositories\AuditRepository as Audit;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AwardingBodyController extends Controller
{
    /**
     * @var awardingbody
     */
    private $awardingbody;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param AwardingBody $awardingbody
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(AwardingBody $awardingbody, Permission $permission)
    {
        parent::__construct();
        $this->awardingbody = $awardingbody;
        $this->permission = $permission;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/awardingbody/general.audit-log.category'), trans('admin/awardingbody/general.audit-log.msg-index'));
        $awardingbody = $this->awardingbody->paginate(10);
        $page_title = trans('admin/awardingbody/general.page.index.title');
        $page_description = trans('admin/awardingbody/general.page.index.description');

        return view('admin.awardingbody.index', compact('awardingbody', 'page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title = trans('admin/awardingbody/general.page.create.title');
        $page_description = trans('admin/awardingbody/general.page.create.description');

        $awardingbody = new \App\Models\Awardingbody();
        $perms = $this->permission->all();

        return view('admin.awardingbody.create', compact('awardingbody', 'perms', 'page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['title' => 'required',
        ]);

        $attributes = $request->all();

        Audit::log(Auth::user()->id, trans('admin/awardingbody/general.audit-log.category'), trans('admin/awardingbody/general.audit-log.msg-store', ['name' => $attributes['title']]));

        $awardingbody = $this->awardingbody->create($attributes);

        Flash::success(trans('admin/awardingbody/general.status.created'));

        return redirect('/admin/awardingbody');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $awardingbody = $this->awardingbody->find($id);

        Audit::log(Auth::user()->id, trans('admin/awardingbody/general.audit-log.category'), trans('admin/awardingbody/general.audit-log.msg-show', ['name' => $awardingbody->title]));

        $page_title = trans('admin/awardingbody/general.page.show.title');
        $page_description = trans('admin/awardingbody/general.page.show.description');

        return view('admin.awardingbody.show', compact('awardingbody', 'page_title', 'page_description'));

        return view('admin.awardingbody.show', compact('awardingbody', 'page_title', 'page_description'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $awardingbody = $this->awardingbody->find($id);

        Audit::log(Auth::user()->id, trans('admin/awardingbody/general.audit-log.category'), trans('admin/awardingbody/general.audit-log.msg-edit', ['name' => $awardingbody->title]));

        $page_title = trans('admin/awardingbody/general.page.edit.title');
        $page_description = trans('admin/awardingbody/general.page.edit.description', ['name' => $awardingbody->title]);

        if (! $awardingbody->isEditable() && ! $awardingbody->canChangePermissions()) {
            abort(403);
        }

        return view('admin.awardingbody.edit', compact('awardingbody', 'page_title', 'page_description'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['title'	=> 'required',
        ]);

        $awardingbody = $this->awardingbody->find($id);

        Audit::log(Auth::user()->id, trans('admin/awardingbody/general.audit-log.category'), trans('admin/awardingbody/general.audit-log.msg-update', ['name' => $awardingbody->title]));

        $attributes = $request->all();

        if ($awardingbody->isEditable()) {
            $awardingbody->update($attributes);
        }

        Flash::success(trans('admin/awardingbody/general.status.updated'));

        return redirect('/admin/awardingbody');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $awardingbody = $this->awardingbody->find($id);

        if (! $awardingbody->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/awardingbody/general.audit-log.category'), trans('admin/awardingbody/general.audit-log.msg-destroy', ['name' => $awardingbody->name]));

        $this->awardingbody->delete($id);

        Flash::success(trans('admin/awardingbody/general.status.deleted'));

        return redirect('/admin/awardingbody');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $awardingbody = $this->awardingbody->find($id);

        Audit::log(Auth::user()->id, trans('admin/awardingbody/general.audit-log.category'), trans('admin/awardingbody/general.audit-log.msg-enable', ['name' => $awardingbody->title]));

        $awardingbody->enabled = true;
        $awardingbody->save();

        Flash::success(trans('admin/awardingbody/general.status.enabled'));

        return redirect('/admin/awardingbody');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $awardingbody = $this->awardingbody->find($id);

        Audit::log(Auth::user()->id, trans('admin/awardingbody/general.audit-log.category'), trans('admin/awardingbody/general.audit-log.msg-disabled', ['name' => $awardingbody->title]));

        $awardingbody->enabled = false;
        $awardingbody->save();

        Flash::success(trans('admin/awardingbody/general.status.disabled'));

        return redirect('/admin/awardingbody');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkAwardingBody = $request->input('chkAwardingBody');

        Audit::log(Auth::user()->id, trans('admin/awardingbody/general.audit-log.category'), trans('admin/awardingbody/general.audit-log.msg-enabled-selected'), $chkAwardingBody);

        if (isset($chkAwardingBody)) {
            foreach ($chkAwardingBody as $awardingbody_id) {
                $awardingbody = $this->awardingbody->find($awardingbody_id);
                $awardingbody->enabled = true;
                $awardingbody->save();
            }
            Flash::success(trans('admin/awardingbody/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/awardingbody/general.status.no-awardingbody-selected'));
        }

        return redirect('/admin/awardingbody');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkAwardingBody = $request->input('chkAwardingBody');

        Audit::log(Auth::user()->id, trans('admin/awardingbody/general.audit-log.category'), trans('admin/awardingbody/general.audit-log.msg-disabled-selected'), $chkAwardingBody);

        if (isset($chkAwardingBody)) {
            foreach ($chkAwardingBody as $awardingbody_id) {
                $awardingbody = $this->awardingbody->find($awardingbody_id);
                $awardingbody->enabled = false;
                $awardingbody->save();
            }
            Flash::success(trans('admin/awardingbody/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/awardingbody/general.status.no-awardingbody-selected'));
        }

        return redirect('/admin/awardingbody');
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

        $awardingbody = $this->awardingbody->find($id);

        if (! $awardingbody->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/awardingbody/dialog.delete-confirm.title');

        $modal_route = route('admin.awardingbody.delete', ['id' => $awardingbody->id]);

        $modal_body = trans('admin/awardingbody/dialog.delete-confirm.body', ['id' => $awardingbody->id, 'name' => $awardingbody->title]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
