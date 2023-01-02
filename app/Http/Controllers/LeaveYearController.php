<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Leaveyear;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveYearController extends Controller
{
    /**
     * @var LeaveYear
     */
    private $LeaveYear;

    /**
     * @var Permission
     */
    private $permission;
    private $org_id;

    /**
     * @param LeaveYear $LeaveYear
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Leaveyear $LeaveYear, Permission $permission)
    {
        parent::__construct();
        $this->LeaveYear = $LeaveYear;
        $this->permission = $permission;
        $this->middleware(function ($request, $next) {
            $this->org_id = \Auth::user()->org_id;

            return $next($request);
        });
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/LeaveYear/general.audit-log.category'), trans('admin/LeaveYear/general.audit-log.msg-index'));

        $allLeaveYear = $this->LeaveYear->orderBy('leave_year', 'desc')->where('org_id', $this->org_id)->paginate(10);
        $page_title = 'Leave Year';
        $page_description = 'All Years';

        return view('admin.leaveyear.index', compact('allLeaveYear', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $LeaveYear = $this->LeaveYear->find($id);

        if ($LeaveYear->org_id != $this->org_id) {
            abort(404);
        }
        Audit::log(Auth::user()->id, trans('admin/LeaveYear/general.audit-log.category'), trans('admin/LeaveYear/general.audit-log.msg-show', ['name' => $LeaveYear->name]));

        $page_title = trans('admin/LeaveYear/general.page.show.title'); // "Admin | LeaveYear | Show";
        $page_description = trans('admin/LeaveYear/general.page.show.description'); // "Displaying LeaveYear: :name";

        return view('admin.leaveyear.show', compact('LeaveYear', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Admin | LeaveYear | Create';
        $page_description = 'Creating a new LeaveYear';

        $LeaveYear = new \App\Models\Leaveyear();
        $perms = $this->permission->all();

        return view('admin.leaveyear.create', compact('LeaveYear', 'perms', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, ['leave_year' => 'required|unique:leaveyears']);

        $attributes = $request->all();
        $attributes['org_id'] = $this->org_id;
        $attributes['user_id'] = \Auth::user()->id;
        Audit::log(Auth::user()->id, trans('admin/LeaveYear/general.audit-log.category'), trans('admin/LeaveYear/general.audit-log.msg-store', ['leave_year' => $attributes['leave_year']]));

        $LeaveYear = $this->LeaveYear->create($attributes);
        if ($LeaveYear->current_year) {
            DB::update("update LeaveYears set current_year = (case when id = '$LeaveYear->id' then '1' else '0' end)");
        }
        Flash::success('LeaveYear successfully created'); // 'LeaveYear successfully created');

        return redirect('/admin/leaveyear');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $LeaveYear = $this->LeaveYear->find($id);
        if ($LeaveYear->org_id != $this->org_id) {
            abort(404);
        }

        Audit::log(Auth::user()->id, trans('admin/LeaveYear/general.audit-log.category'), trans('admin/LeaveYear/general.audit-log.msg-edit', ['leave_year' => $LeaveYear->leave_year]));

        $page_title = 'Admin | LeaveYear | Edit';
        $page_description = 'Editing LeaveYear';

        if (!$LeaveYear->isEditable() && !$LeaveYear->canChangePermissions()) {
            abort(403);
        }

        return view('admin.leaveyear.edit', compact('LeaveYear', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['leave_year' => 'required|unique:leaveyears,leave_year,' . $id,]);

        $LeaveYear = $this->LeaveYear->find($id);

        Audit::log(Auth::user()->id, trans('admin/LeaveYear/general.audit-log.category'), trans('admin/LeaveYear/general.audit-log.msg-update', ['leave_year' => $LeaveYear->leave_year]));

        $attributes = $request->all();

        if ($LeaveYear->isEditable()) {
            $LeaveYear->update($attributes);
            $chkLeaveYear = $request->current_year;
            if ($chkLeaveYear) {
                DB::update("UPDATE LeaveYears set current_year = (case when id = '$chkLeaveYear' then '1' else '0' end) 
                    WHERE org_id = '$this->org_id'");
            }
        }

        Flash::success('LeaveYear successfully updated'); // 'LeaveYear successfully updated');

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $LeaveYear = $this->LeaveYear->find($id);

        if (!$LeaveYear->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/LeaveYear/general.audit-log.category'), trans('admin/LeaveYear/general.audit-log.msg-destroy', ['name' => $LeaveYear->name]));

        $LeaveYear->delete();

        Flash::success(trans('admin/LeaveYear/general.status.deleted')); // 'LeaveYear successfully deleted');

        return redirect('/admin/leaveyear');
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

        $LeaveYear = $this->LeaveYear->find($id);

        if (!$LeaveYear->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Leave Year';

        $LeaveYear = $this->LeaveYear->find($id);
        $modal_route = route('admin.leaveyear.delete', ['leaveyearId' => $LeaveYear->id]);

        $modal_body = 'Are You sure you want to leave year ?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $LeaveYear = $this->LeaveYear->find($id);

        Audit::log(Auth::user()->id, trans('admin/LeaveYear/general.audit-log.category'), trans('admin/LeaveYear/general.audit-log.msg-enable', ['name' => $LeaveYear->name]));

        $LeaveYear->enabled = true;
        $LeaveYear->save();

        Flash::success(trans('admin/LeaveYear/general.status.enabled'));

        return redirect('/admin/leaveyear');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $LeaveYear = $this->LeaveYear->find($id);

        Audit::log(Auth::user()->id, trans('admin/LeaveYear/general.audit-log.category'), trans('admin/LeaveYear/general.audit-log.msg-disabled', ['name' => $LeaveYear->name]));

        $LeaveYear->enabled = false;
        $LeaveYear->save();

        Flash::success(trans('admin/LeaveYear/general.status.disabled'));

        return redirect('/admin/leaveyear');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkLeaveYear = $request->input('chkLeaveYear');

        Audit::log(Auth::user()->id, trans('admin/LeaveYear/general.audit-log.category'), trans('admin/LeaveYear/general.audit-log.msg-enabled-selected'), [$chkLeaveYear]);

        //make all database current_year column to null
        //DB::update("update LeaveYears set current_year = NULL")
        //and then update
        DB::update("UPDATE leaveyears set current_year = (case when id = '$chkLeaveYear' then '1' else '0' end) 
                    WHERE org_id = '$this->org_id'");

        Flash::success(trans('Leave years activated'));

        return redirect('/admin/leaveyear');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkLeaveYear = $request->input('chkLeaveYear');

        Audit::log(Auth::user()->id, trans('admin/LeaveYear/general.audit-log.category'), trans('admin/LeaveYear/general.audit-log.msg-disabled-selected'), $chkLeaveYear);

        if (isset($chkLeaveYear)) {
            foreach ($chkLeaveYear as $LeaveYear_id) {
                $LeaveYear = $this->LeaveYear->find($LeaveYear_id);
                $LeaveYear->enabled = false;
                $LeaveYear->save();
            }
            Flash::success(trans('admin/LeaveYear/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/LeaveYear/general.status.no-LeaveYear-selected'));
        }

        return redirect('/admin/leaveyear');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $LeaveYear = $this->LeaveYear->pushCriteria(new LeaveYearWhereDisplayNameLike($query))->all();

        foreach ($LeaveYear as $LeaveYear) {
            $id = $LeaveYear->id;
            $name = $LeaveYear->name;
            $email = $LeaveYear->email;

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
        $LeaveYear = $this->LeaveYear->find($id);

        return $LeaveYear;
    }
}
