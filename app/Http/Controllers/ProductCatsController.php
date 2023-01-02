<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\ProductCategory;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCatsController extends Controller
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
        $pcategories = ProductCategory::all()->where('org_id', Auth::user()->org_id);
        $page_title = 'Product categories';
        $page_description = 'Add categories for the current company';
        //dd($pcategories);

        return view('admin.productcats.index', compact('pcategories', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $ProductCategory = $this->ProductCategory->find($id);

        $page_title = 'Admin | Category | Show';
        $page_description = 'Displaying Category';

        return view('admin.productcats.show', compact('ProductCategory', 'page_title', 'page_description'));

        return view('admin.productcats.show', compact('ProductCategory', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Admin | Product category | Create';
        $page_description = 'Creating a new Product Category';

        $ProductCategory = new \App\Models\ProductCategory();
        $perms = $this->permission->all();

        return view('admin.productcats.create', compact('ProductCategory', 'perms', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);

        $attributes = $request->all();

        $attributes['org_id'] = Auth::user()->org_id;
//        dd($attributes);
        $ProductCategory = $this->ProductCategory->create($attributes);

        Flash::success('created'); // 'LeadStatus successfully created');

        return redirect('/admin/productcats');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $ProductCategory = $this->ProductCategory->find($id);

        $page_title = 'Admin | Product Category | Edit';
        $page_description = 'Editing Product Category';

        if (!$ProductCategory->isEditable() && !$ProductCategory->canChangePermissions()) {
            abort(403);
        }

        return view('admin.productcats.edit', compact('ProductCategory', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name' => 'required']);

        $ProductCategory = $this->ProductCategory->find($id);

        $attributes = $request->all();
        $attributes['org_id'] = Auth::user()->org_id;

        if ($ProductCategory->isEditable()) {
            $ProductCategory->update($attributes);
        }

        Flash::success('updated'); // 'ProductCategory successfully updated');

        return redirect('/admin/productcats');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $productcats = $this->ProductCategory->find($id);

        if (!$productcats->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/leadstatus/general.audit-log.category'), trans('admin/leadstatus/general.audit-log.msg-destroy', ['name' => $productcats->name]));

        $this->ProductCategory->find($id)->delete();

        Flash::success("Products Category successfully deleted'"); // 'LeadStatus successfully deleted');

        return redirect('/admin/productcats');
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

        $productcats = $this->ProductCategory->find($id);

        if (!$productcats->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/leadstatus/dialog.delete-confirm.title');

        $productcats = $this->ProductCategory->find($id);
        $modal_route = route('admin.productcats.delete', ['productcatsId' => $productcats->id]);

        $modal_body = trans('admin/leadstatus/dialog.delete-confirm.body', ['id' => $productcats->id, 'name' => $productcats->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $LeadStatus = $this->ProductCategory->find($id);

        Audit::log(Auth::user()->id, trans('admin/leadstatus/general.audit-log.category'), trans('admin/leadstatus/general.audit-log.msg-enable', ['name' => $LeadStatus->name]));

        $LeadStatus->enabled = true;
        $LeadStatus->save();

        Flash::success('Product Category Enabled');

        return redirect('/admin/productcats');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $LeadStatus = $this->ProductCategory->find($id);

        Audit::log(Auth::user()->id, trans('admin/leadstatus/general.audit-log.category'), trans('admin/leadstatus/general.audit-log.msg-disabled', ['name' => $LeadStatus->name]));

        $LeadStatus->enabled = false;
        $LeadStatus->save();

        Flash::success('Product Category Disabled');

        return redirect('/admin/productcats');
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
                $LeadStatus = $this->ProductCategory->find($LeadStatus_id);
                $LeadStatus->enabled = true;
                $LeadStatus->save();
            }
            Flash::success('Product Category Enabled');
        } else {
            Flash::warning('Product Category Cannot Enabled');
        }

        return redirect('/admin/productcats');
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
                $LeadStatus = $this->ProductCategory->find($LeadStatus_id);
                $LeadStatus->enabled = false;
                $LeadStatus->save();
            }
            Flash::success('Product Category Disabled');
        } else {
            Flash::warning(trans('admin/leadstatus/general.status.no-LeadStatus-selected'));
        }

        return redirect('/admin/productcats');
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
