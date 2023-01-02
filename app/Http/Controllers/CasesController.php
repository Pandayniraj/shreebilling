<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Cases as Cases;
use App\Models\Cases as CasesModel;
use App\Models\MasterComments;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\File as Files;
use Illuminate\Support\Facades\Response;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class CasesController extends Controller
{
    /**
     * @var Client
     */
    private $cases;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $case
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Cases $cases, Permission $permission)
    {
        parent::__construct();
        $this->cases = $cases;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/cases/general.audit-log.category'), trans('admin/cases/general.audit-log.msg-index'));
        $case_status = ['new' => 'New', 'assigned' => 'Assigned', 'closed' => 'Closed', 'pending' => 'Pending', 'rejected' => 'Rejected'];
        $users = \App\User::pluck('username as name', 'id')->all();
        $customers = \App\Models\Client::where('org_id', Auth::user()->org_id)->pluck('name', 'id')->all();
        $casesIDs = [];
        if (!Auth::user()->hasRole('admins')) {
            $generatedcases = CasesModel::orderBy('id', 'desc')
                ->where('user_id', Auth::user()->id)
                // ->where('assigned_to',\Auth::user()->id)
                ->get()
                ->pluck('id');

            $assignedcases = CasesModel::orderBy('id', 'desc')
                //->where('user_id',\Auth::user()->id)
                ->where('assigned_to', Auth::user()->id)
                ->get()
                ->pluck('id');

            $casesIDs = array_merge($generatedcases->toArray(), $assignedcases->toArray());
        }

        $cases = CasesModel::where(function ($query) {
            if (!Auth::user()->hasRole('admins')) {
                return $query->whereIn('id', $casesIDs);
            }
        })->where(function ($query) {
            if (\Request::get('start_date') != '' && \Request::get('end_date')) {
                return $query->where('created_at', '>=', \Request::get('start_date'))
                    ->where('created_at', '<=', \Request::get('end_date'));
            }
        })->where(function ($query) {
            if (\Request::get('case_status') && \Request::get('case_status') != '') {
                return $query->where('status', \Request::get('case_status'));
            }
        })->where(function ($query) {
            if (\Request::get('client_id') && \Request::get('client_id') != '') {
                return $query->where('client_id', \Request::get('client_id'));
            }
        })->where(function ($query) {
            if (\Request::get('user_id') && \Request::get('user_id') != '') {
                return $query->where('user_id', \Request::get('user_id'));
            }
        })->orderBy('id', 'desc')
            ->paginate(20);

        // dd($cases);

        $page_title = trans('admin/cases/general.page.index.title');
        $page_description = trans('admin/cases/general.page.index.description');

        return view('admin.cases.index', compact('cases', 'page_title', 'page_description', 'case_status', 'users', 'customers'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $case = $this->cases->find($id);
        if ($case->viewed == 0) {
            DB::table('cases')->where('id', $id)->update(['viewed' => 1]);
        }

        Audit::log(Auth::user()->id, trans('admin/cases/general.audit-log.category'), trans('admin/cases/general.audit-log.msg-show', ['name' => $case->name]));

        $page_title = trans('admin/cases/general.page.show.title'); // "Admin | Client | Show";
        $page_description = trans('admin/cases/general.page.show.description'); // "Displaying client: :name";

        $comments = MasterComments::where('type', 'case')->where('master_id', $id)->get();
        $casepart1 = \App\Models\CasePart1::where('case_id', $id)->get();
        $casepart2 = \App\Models\CasePart2::where('case_id', $id)->get();

        return view('admin.cases.show', compact('case', 'page_title', 'page_description', 'comments', 'casepart1', 'casepart2'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/cases/general.page.create.title'); // "Admin | Client | Create";
        $page_description = trans('admin/cases/general.page.create.description'); // "Creating a new client";

        $case = new \App\Models\Cases();
        $clients = \App\Models\Client::where('org_id', Auth::user()->org_id)->get();
        $perms = $this->permission->all();
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');
        $dealer = \App\Models\Client::where('org_id', Auth::user()->org_id)
            ->where('relation_type', 'dealer')
            ->get();
        $job_no = time();
        $producs = \App\Models\Product::all();

        return view('admin.cases.create', compact('case', 'dealer', 'perms', 'page_title', 'page_description', 'users', 'clients', 'job_no', 'producs'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $attributes = $request->all();

        $attributes['user_id'] = Auth::user()->id;
        $attributes['org_id'] = Auth::user()->org_id;

        // $temp_client = Client::where('name', $request['client_id'])->first();
        // if(!$temp_client)
        // {
        //     $attributes['client_id'] = '0';
        // }
        // else
        //     $attributes['client_id'] = $temp_client->id;

        // $temp_contact = Contact::where('full_name', $request['contact_id'])->first();
        // if(!$temp_contact)
        // {
        //     $attributes['contact_id'] = '0';
        // }
        // else
        //     $attributes['contact_id'] = $temp_contact->id;

        if (!isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        // Save the attachment data and file

        $case = $this->cases->create($attributes);

        $files = $request->file('attachment');
        foreach ($files as $key => $doc_) {
            if ($doc_) {
                $doc_name = time() . '' . $doc_->getClientOriginalName();
                $destinationPath = public_path('/case_attachments/');
                $doc_->move($destinationPath, $doc_name);
                $case_attachment = ['case_id' => $case->id, 'attachment' => $doc_name, 'user_id' => \Auth::user()->id];
                \App\Models\CaseAttachment::create($case_attachment);
            }
        }
        foreach ($request->part_code as $key => $pr) {
            $case1 = new \App\Models\CasePart1();
            $case1->part_code = ($request->part_code)[$key];
            $case1->description = ($request->description1)[$key];
            $case1->quantity = ($request->quantity)[$key];
            $case1->rate = ($request->rate)[$key];
            $case1->amount = ($request->amount)[$key];
            $case1->remark = ($request->remark)[$key];
            $case1->case_id = $case->id;
            $case1->save();
        }
        foreach ($request->visit_date_time as $key => $pr) {
            $case2 = new \App\Models\CasePart2();
            $case2->visit_date_time = ($request->visit_date_time)[$key];
            $case2->service_engineer = ($request->service_engineer)[$key];
            $case2->call_status = ($request->call_status)[$key];
            $case2->peding_reasons = ($request->peding_reasons)[$key];
            $case2->remarks = ($request->remarks)[$key];
            $case2->case_id = $case->id;
            $case2->save();
        }

        Audit::log(Auth::user()->id, trans('admin/cases/general.audit-log.category'), trans('admin/cases/general.audit-log.msg-store', ['name' => $temp_contact->full_name]));

        Flash::success(trans('admin/cases/general.status.created')); // 'Client successfully created');

        return redirect('/admin/cases');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $case = $this->cases->find($id);

        if ($case->viewed == 0) {
            DB::table('cases')->where('id', $id)->update(['viewed' => 1]);
        }

        Audit::log(Auth::user()->id, trans('admin/cases/general.audit-log.category'), trans('admin/cases/general.audit-log.msg-edit', ['name' => $case->name]));

        $page_title = trans('admin/cases/general.page.edit.title'); // "Admin | Client | Edit";
        $page_description = trans('admin/cases/general.page.edit.description', ['name' => $case->name]); // "Editing client";
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');

        if (!$case->isEditable() && !$case->canChangePermissions()) {
            abort(403);
        }
        $dealer = \App\Models\Client::where('org_id', Auth::user()->org_id)
            ->where('relation_type', 'dealer')
            ->get();
        $cases_attachment = \App\Models\CaseAttachment::where('case_id', $id)->get();
        $clients = \App\Models\Client::where('org_id', Auth::user()->org_id)->get();
        $casepart1 = \App\Models\CasePart1::where('case_id', $id)->get();
        $casepart2 = \App\Models\CasePart2::where('case_id', $id)->get();
        $producs = \App\Models\Product::all();
        $model = \App\Models\ProductModel::where('product_id', $case->product)->get();
        $serial_num = \App\Models\ProductSerialNumber::where('model_id', $case->model_no)->get();

        return view('admin.cases.edit', compact('case', 'users', 'dealer', 'page_title', 'page_description', 'clients', 'casepart1', 'casepart2', 'producs', 'model', 'serial_num', 'cases_attachment'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $attributes = $request->all();

        if (!isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        $cases = $this->cases->find($id);
        if ($cases->isEditable()) {
            // Save the attachment data and file
            $files = $request->file('attachment');
            foreach ($files as $key => $doc_) {
                if ($doc_) {
                    $doc_name = time() . '' . $doc_->getClientOriginalName();
                    $destinationPath = public_path('/case_attachments/');
                    $doc_->move($destinationPath, $doc_name);
                    $visa_attachment = ['case_id' => $cases->id, 'attachment' => $doc_name, 'user_id' => \Auth::user()->id];
                    \App\Models\CaseAttachment::create($visa_attachment);
                }
            }
            \App\Models\CasePart1::where('case_id', $id)->delete();
            \App\Models\CasePart2::where('case_id', $id)->delete();
            foreach ($request->part_code as $key => $pr) {
                $case1 = new \App\Models\CasePart1();
                $case1->part_code = ($request->part_code)[$key];
                $case1->description = ($request->description1)[$key];
                $case1->quantity = ($request->quantity)[$key];
                $case1->rate = ($request->rate)[$key];
                $case1->amount = ($request->amount)[$key];
                $case1->remark = ($request->remark)[$key];
                $case1->case_id = $cases->id;
                $case1->save();
            }
            foreach ($request->visit_date_time as $key => $pr) {
                $case2 = new \App\Models\CasePart2();
                $case2->visit_date_time = ($request->visit_date_time)[$key];
                $case2->service_engineer = ($request->service_engineer)[$key];
                $case2->call_status = ($request->call_status)[$key];
                $case2->peding_reasons = ($request->peding_reasons)[$key];
                $case2->remarks = ($request->remarks)[$key];
                $case2->case_id = $cases->id;
                $case2->save();
            }
            $cases->update($attributes);
        } else {
            Flash::error("You Dont't have permission to update !!");

            return redirect()->back();
        }

        Audit::log(Auth::user()->id, trans('admin/cases/general.audit-log.category'), trans('admin/cases/general.audit-log.msg-update', ['name' => $cases->name]));

        Flash::success(trans('admin/cases/general.status.updated')); // 'Client successfully updated');

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $cases = $this->cases->find($id);

        if (!$cases->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/cases/general.audit-log.category'), trans('admin/cases/general.audit-log.msg-destroy', ['name' => $cases->name]));

        if ($cases->attachment != '') {
            // Delete the File from its location
            $fileUrl = public_path() . '/case_attachments/' . $cases->attachment;
            Files::delete($fileUrl);
        }

        $cases->delete($id);
        \App\Models\CasePart1::where('case_id', $id)->delete();
        \App\Models\CasePart2::where('case_id', $id)->delete();

        MasterComments::where('type', 'case')->where('master_id', $id)->delete();

        Flash::success(trans('admin/cases/general.status.deleted')); // 'Client successfully deleted');

        return redirect('/admin/cases');
    }

    public function deleteImg($id)
    {
        $cases = \App\Models\CaseAttachment::find($id);
        $image_path = public_path() . '/case_attachments/' . $cases->attachment;  // Value is not URL but directory
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
        $cases->delete();

        return ['status' => 1];
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

        $cases = $this->cases->find($id);

        if (!$cases->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/cases/dialog.delete-confirm.title');

        $cases = $this->cases->find($id);

        $modal_route = route('admin.cases.delete', ['caseId' => $cases->id]);

        $modal_body = trans('admin/cases/dialog.delete-confirm.body', ['id' => $cases->id, 'name' => $cases->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $cases = $this->cases->find($id);

        Audit::log(Auth::user()->id, trans('admin/cases/general.audit-log.category'), trans('admin/cases/general.audit-log.msg-enable', ['name' => $cases->name]));

        $cases->enabled = true;
        $cases->save();

        Flash::success(trans('admin/cases/general.status.enabled'));

        return redirect('/admin/cases');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $cases = $this->cases->find($id);

        Audit::log(Auth::user()->id, trans('admin/cases/general.audit-log.category'), trans('admin/cases/general.audit-log.msg-disabled', ['name' => $cases->name]));

        $cases->enabled = false;
        $cases->save();

        Flash::success(trans('admin/cases/general.status.disabled'));

        return redirect('/admin/cases');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkcases = $request->input('chkClient');

        Audit::log(Auth::user()->id, trans('admin/cases/general.audit-log.category'), trans('admin/cases/general.audit-log.msg-enabled-selected'), $chkcases);

        if (isset($chkcases)) {
            foreach ($chkcases as $cases_id) {
                $cases = $this->cases->find($cases_id);
                $cases->enabled = true;
                $cases->save();
            }
            Flash::success(trans('admin/cases/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/cases/general.status.no-client-selected'));
        }

        return redirect('/admin/cases');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkcases = $request->input('chkClient');

        Audit::log(Auth::user()->id, trans('admin/cases/general.audit-log.category'), trans('admin/cases/general.audit-log.msg-disabled-selected'), $chkcases);

        if (isset($chkcases)) {
            foreach ($chkcases as $cases_id) {
                $cases = $this->cases->find($cases_id);
                $cases->enabled = false;
                $cases->save();
            }
            Flash::success(trans('admin/cases/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/cases/general.status.no-client-selected'));
        }

        return redirect('/admin/cases');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $cases = $this->cases->where('name', 'LIKE', '%' . $query . '%')->get();

        foreach ($cases as $cases) {
            $id = $cases->id;
            $name = $cases->name;
            $email = $cases->email;

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
        $cases = $this->cases->find($id);

        return $cases;
    }

    public function get_client()
    {
        $term = strtolower(Request::get('term'));
        $contacts = ClientModel::select('id', 'name')->where('name', 'LIKE', '%' . $term . '%')->groupBy('name')->take(5)->get();
        $return_array = [];

        foreach ($contacts as $v) {
            if (strpos(strtolower($v->name), $term) !== false) {
                $return_array[] = ['value' => $v->name, 'id' => $v->id];
            }
        }

        return Response::json($return_array);
    }

    public function clientinfo($id)
    {
        $clients = \App\Models\Client::find($id);

        return response()->json($clients);
    }

    public function search()
    {
        $term = trim(Request::get('search'));
        $cases = \App\Models\Cases::select(
            'cases.id',
            'cases.subject',
            'cases.status',
            'cases.cust_name',
            'cases.client_id',
            'cases.job_no',
            'cases.address',
            'cases.product',
            'cases.user_id',
            'cases.created_at'
        )
            ->leftjoin('clients', 'clients.id', '=', 'cases.client_id')
            ->leftjoin('products', 'products.id', '=', 'cases.product')
            ->Where('cases.id', $term)
            ->where('cases.org_id', Auth::user()->org_id)
            ->orWhere('cases.job_no', $term)
            ->orWhere('cases.cust_name', 'LIKE', '%' . $term . '%')
            ->orWhere('clients.name', 'LIKE', '%' . $term . '%')
            ->orWhere('products.name', 'LIKE', '%' . $term . '%')
            ->orWhere('cases.telephone', 'LIKE', '%' . $term . '%')
            ->orWhere('clients.email', 'LIKE', '%' . $term . '%')
            ->orderBy('cases.id', 'asc')->paginate(30);
        $page_title = 'Admin | Search';
        $page_description = 'List of jobs by Keyword: ' . $term;

        return view('admin.cases.index', compact('cases', 'page_title', 'page_description'));
    }

    public function productinfo($id)
    {
        $model = \App\Models\ProductModel::where('product_id', $id)->get();

        return ['model' => $model];
    }

    public function productserialnum($id)
    {
        $serial_num = \App\Models\ProductSerialNumber::where('model_id', $id)->get();

        return ['serial_num' => $serial_num];
    }
}
