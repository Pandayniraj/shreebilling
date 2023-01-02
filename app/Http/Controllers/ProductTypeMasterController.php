<?php

namespace App\Http\Controllers;


use App\Models\COALedgers;
use App\Models\Role as Permission;
use App\Models\Audit as Audit;
use App\Models\ProductTypeMaster;
use App\Models\PosMenu;
use Flash;
use DB;
use Auth;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductTypeMasterController extends Controller
{

    //echo 1 ; exit;
    /**
     * @var producttypemasters
     */
    private $ProductCategory;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param producttypemasters $producttypemasters
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(ProductTypeMaster $producttypemasters, Permission $permission)
    {
        parent::__construct();
        $this->producttypemasters = $producttypemasters;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $producttypemasters = \App\Models\ProductTypeMaster::all()->where('org_id', \Auth::user()->org_id);
        $page_title = 'Product Type Master';
        $page_description = 'Add Product Type Master for the current company';
        //dd($pcategories);

        return view('admin.producttypemaster.index', compact('producttypemasters', 'page_title', 'page_description'));
    }


    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $producttypemasters = $this->producttypemasters->find($id);


        $page_title = "Admin | Category | Show";
        $page_description = "Displaying Category";

        return view('admin.producttypemaster.show', compact('ProductCategory', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title =  "Admin | Product category | Create";
        $page_description =  "Creating a new Product Category";

        $producttypemasters = new \App\Models\ProductTypeMaster();
        $menus = PosMenu::all();
        $perms = $this->permission->all();

        return view('admin.producttypemaster.create', compact('producttypemasters', 'perms', 'menus', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $this->validate($request, array('name' => 'required'));

        $attributes = $request->all();

        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['asset_ledger_id']= $request->assets_ledger_id;
        $attributes['service_ledger_id']= $request->service_ledger_id;
        $producttypemasters = $this->producttypemasters->create($attributes);

        Flash::success('Product Type Master Successfully Created');

        return redirect('/admin/producttypemaster');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $producttypemasters = $this->producttypemasters->find($id);
        $page_title =  "Admin | Product Category | Edit";
        $page_description = "Editing Product Category";

        $menus = PosMenu::all();

        if (!$producttypemasters->isEditable() &&  !$producttypemasters->canChangePermissions()) {
            abort(403);
        }
        $sale_ledger=$producttypemasters->saleLedger;
        $purchase_ledger=$producttypemasters->purchaseLedger;
        $cogs_ledger=$producttypemasters->cogsLedger;
        $assets_ledger=$producttypemasters->assetLedger;
        $service_ledger=$producttypemasters->serviceLedger;
        $sales_return_ledger=$producttypemasters->salesreturnLedger;
        $purchase_return_ledger=$producttypemasters->purchasereturnLedger;

        return view('admin.producttypemaster.edit', compact('sale_ledger','purchase_ledger','cogs_ledger','assets_ledger','service_ledger','producttypemasters', 'menus', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, array('name' => 'required'));

        $producttypemasters = $this->producttypemasters->find($id);

        $attributes = $request->all();
        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['asset_ledger_id']= $request->assets_ledger_id;
        $attributes['service_ledger_id']= $request->service_ledger_id;
        if ($producttypemasters->isEditable()) {
            $producttypemasters->update($attributes);
        }

        Flash::success('Product Type Master Successfully updated');

        return redirect('/admin/producttypemaster');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $producttypemasters = $this->producttypemasters->find($id);

        if (!$producttypemasters->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/leadstatus/general.audit-log.category'), trans('admin/leadstatus/general.audit-log.msg-destroy', ['name' => $producttypemasters->name]));

        $this->producttypemasters->find($id)->delete();

        Flash::success("Products Category successfully deleted'"); // 'LeadStatus successfully deleted');

        return redirect('/admin/producttypemaster');
    }

    /**
     * Delete Confirm
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalDelete($id)
    {
        $error = null;

        $producttypemasters = $this->producttypemasters->find($id);

        if (!$producttypemasters->isdeletable()) {
            abort(403);
        }

        $modal_title = "Delete Product Type Master?";

        $productcats = $this->producttypemasters->find($id);
        $modal_route = route('admin.producttypemaster.delete',  $producttypemasters->id);

        $modal_body = 'Are you sure that you want to delete Product Type Master ID ' . $id . ' with the name "' . $producttypemasters->name . '"? This operation is irreversible.';

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
