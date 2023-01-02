<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\ProductCategory;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    //echo 1 ; exit;
    /**
     * @var ProductCategory
     */
    private $ProductCategory;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param ProductCategory $ProductCategory
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(ProductCategory $ProductCategory, Permission $permission)
    {
        parent::__construct();
        $this->ProductCategory = $ProductCategory;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tags = \App\Models\Tag::all()->where('org_id', Auth::user()->org_id);

        $page_title = 'Entry Tags List';
        $page_description = 'Add Entry Tags for the current company';

        return view('admin.tags.index', compact('tags', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Admin | Entry Tags | Create';
        $page_description = 'Creating a new entry Tags';

        return view('admin.tags.create', compact(  'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, ['title'=> 'required']);

        $attributes = $request->all();
        $attributes['org_id'] = Auth::user()->org_id;

        //dd($attributes);

        $tags = \App\Models\Tag::create($attributes);
        Flash::success('Entry Tag Successfully Created');

        return redirect('/admin/tags');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $tags = \App\Models\Tag::find($id);

        $page_title = 'Admin | Entry Tags | Edit';
        $page_description = 'Editing Entry Tags';

        return view('admin.tags.edit', compact('tags', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['title' => 'required']);

        $tags = \App\Models\Tag::find($id);
        $attributes = $request->all();
        $attributes['org_id'] = Auth::user()->org_id;

        //dd($attributes);

        $tags->update($attributes);

        Flash::success('Entry Tags Successfully Updated');

        return redirect('/admin/tags');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $tags = \App\Models\Tag::find($id);
        //dd($tags);

        if (! $tags->isdeletable()) {
            abort(403);
        }

        \App\Models\Tag::find($id)->delete();

        Flash::success("Entry Tag Successfully Deleted'"); // 'LeadStatus successfully deleted');

        return redirect('/admin/tags');
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

        $tags = \App\Models\Tag::find($id);

        //dd($tags);

        if (! $tags->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Entry Tag?';

        $tags = \App\Models\Tag::find($id);

        $modal_route = route('admin.tags.delete', ['id' => $tags->id]);

        $modal_body = trans('Are You Sure', ['id' => $tags->id, 'name' => $tags->title]);

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

        $LeadStatus = $this->LeadStatus->pushCriteria(new LeadStatusWhereDisplayNameLike($query))->all();

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
