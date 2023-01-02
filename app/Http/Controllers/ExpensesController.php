<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Audit as Audit;
use App\Models\Cases;
use App\Models\Cashbook;
use App\Models\Client as Client;
use App\Models\Client as ClientModel;
use App\Models\Contact;
use App\Models\Expense;
use App\Models\IncomeExpenseCategory;
use App\Models\Proposal;
use App\Models\Role as Permission;
use Auth;
use DB;
use Flash;
use Illuminate\Http\Request;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class ExpensesController extends Controller
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Permission
     */
    private $permission;
    public $required_account;

    /**
     * @param Client $client
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Expense $expense, Permission $permission)
    {
        parent::__construct();
        $this->expense = $expense;
        $this->permission = $permission;
        $this->required_account = ['expense' => \FinanceHelper::get_ledger_id('EXPENSES_PARENT_LEDGER_GROUP'),
            'bill_payment' => \FinanceHelper::get_ledger_id(
                'LIABILITIES_PARENT_LEDGER_GROUP'),
            'advance_payment' => \FinanceHelper::get_ledger_id('ASSETS_PARENT_LEDGER_GROUP'),
            'sales_return' => \FinanceHelper::get_ledger_id('LIABILITIES_PARENT_LEDGER_GROUP'),
        ];

    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $filterUser = function ($query) {
            $user = \Request::get('user');
            if ($user) {

                return $query->where('user_id', $user);
            }

        };

        $filterdate = function ($query) {

            $start_date = \Request::get('start_date');

            $end_date = \Request::get('end_date');

            if ($start_date && $end_date) {

                return $query->whereDate('date', '>=', $start_date)
                    ->whereDate('date', '<=', $end_date);
            }

        };


        $filterPaidThrough = function ($query) {
            $paid_through = \Request::get('paid_through');
            if ($paid_through) {

                return $query->where('paid_through', $paid_through);
            }

        };

        $filterTags = function ($query) {

            $tags = \Request::get('tags');

            if ($tags) {

                return $query->where('tag_id', $tags);
            }

        };
        Audit::log(Auth::user()->id, trans('admin/clients/general.audit-log.category'), trans('admin/clients/general.audit-log.msg-index'));

        $clients = Expense::latest()->where('org_id', \Auth::user()->org_id)
            ->where(function ($query) use ($filterdate) {

                return $filterdate($query);

            })
            ->where(function ($query) use ($filterUser) {

                return $filterUser($query);

            })
            ->where(function ($query) use ($filterPaidThrough) {

                return $filterPaidThrough($query);

            })
            ->where(function ($query) use ($filterTags) {

                return $filterTags($query);

            });


        $tags = \App\Models\IncomeExpenseCategory::orderBy('name', 'ASC')->whereType('expense')->pluck('name', 'id')->all();
        $paid_through = \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id', \FinanceHelper::get_ledger_id('CASH_EQUIVALENTS'))->where('org_id', \Auth::user()->org_id)->pluck('name', 'id');
        if (\Auth::user()->hasRole(['admins'])) {
            $users = \App\User::select('first_name', 'last_name', 'id')->get();
            $clients = $clients->orderBy('date', 'DESC');
        } else {
            $users = [];
            $clients = $clients->orderBy('date', 'DESC')->where('user_id', auth()->user()->id);
        }
        $isExcel = false;
        if (\Request::get('submit') == 'excel') {
            $clients = $clients->get();
            $view = view('admin.expenses.expenseindex', compact('clients', 'isExcel'));
            return \Excel::download(new \App\Exports\ExcelExportFromView($view), 'expensedownload.xlsx');

        }
        $clients = $clients->paginate(30);


        $page_title = 'Expenses List';
        $page_description = 'All records';

        return view('admin.expenses.index', compact('clients', 'page_title', 'page_description', 'tags', 'paid_through', 'users', 'isExcel'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $expenses = $this->expense->find($id);

        \TaskHelper::authorizeOrg($expenses);

        Audit::log(Auth::user()->id, trans('admin/clients/general.audit-log.category'), trans('admin/clients/general.audit-log.msg-edit', ['name' => $expenses->name]));

        $page_title = trans('admin/clients/general.page.edit.title'); // "Admin | Client | Edit";
        $page_description = trans('admin/clients/general.page.edit.description', ['name' => $expenses->name]); // "Editing client";
        $vendors = \App\Models\Client::pluck('name', 'id')->all();
        $projects = \App\Models\Projects::orderBy('name', 'ASC')->pluck('name', 'id')->all();
        $history = \App\Models\ExpenseEditHistory::where('expense_id', $id)->get();
        if (!$expenses->isEditable() && !$expenses->canChangePermissions()) {
            abort(403);
        }

        $expenses_groups = \App\Models\COAgroups::where('parent_id', \FinanceHelper::get_ledger_id('EXPENSE_LEDGER_GROUP'))->pluck('name', 'id')->all();
        foreach ($expenses_groups as $id => $value) {
            $expenses_ledger[$value] = \App\Models\COALedgers::where('group_id', $id)->get();
        }
        $showpage = true;
        $required_account = $this->required_account;
        $tags = \App\Models\IncomeExpenseCategory::orderBy('name', 'ASC')->whereType('expense')->pluck('name', 'id')->all();
        return view('admin.expenses.show', compact('expenses', 'vendors', 'page_title', 'page_description', 'projects', 'history', 'showpage', 'expenses_ledger', 'required_account', 'tags'));
    }

    public function showModal($id)
    {
        $expenses = Expense::where('id', $id)->first();

        $data = '<div class="panel panel-custom">
                <div class="panel-heading">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Expense Details</h4>
                </div>
                <div class="modal-body wrap-modal wrap">
                    <div class="panel-body form-horizontal">
                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label"><strong>Expenses Account :</strong></label>
                            </div>
                            <div class="col-sm-8">
                                <p class="form-control-static">' . $expenses->ledger->name . '</p>
                            </div>
                        </div>
                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label">Amount :</label>
                            </div>

                            <div class="col-sm-5">
                                <p class="form-control-static">' . number_format($expenses->amount, 2) . '</p>
                            </div>
                        </div>
                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label">Paid Through :</label>
                            </div>

                            <div class="col-sm-5">
                                <p class="form-control-static">' . $expenses->paidledger->name . '</p>
                            </div>
                        </div>
                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label">Vendor :</label>
                            </div>

                            <div class="col-sm-5">
                                <p class="form-control-static">' . $expenses->vendor->name . '</p>
                            </div>
                        </div>
                         <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label">Reference:</label>
                            </div>

                            <div class="col-sm-5">
                                <p class="form-control-static">' . $expenses->reference . '</p>
                            </div>
                        </div>

                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label"><strong>Date :</strong></label>
                            </div>
                            <div class="col-sm-8">
                                <p class="form-control-static"><span class="text-danger">' . date('dS M Y', strtotime($expenses->date)) . '</span>
                                </p>
                            </div>
                        </div>';

        $data .= '

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>';

        return $data;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Expense'; // "Admin | Client | Create";
        $page_description = 'Enter Expenses details'; // "Creating a new client";

        $vendors = \App\Models\Client::where('relation_type', 'supplier')->pluck('name', 'id')->all();
        
        $tags = \App\Models\IncomeExpenseCategory::orderBy('name', 'ASC')->whereType('expense')->pluck('name', 'id')->all();
        $currencies = \App\Models\Currency::pluck('currency', 'code')->all();

        // $expenses_groups = \App\Models\COAgroups::where('parent_id',\FinanceHelper::get_ledger_id('EXPENSE_LEDGER_GROUP'))->pluck('name','id')->all();
        // foreach ($expenses_groups as $id => $value) {
        //   $expenses_ledger [$value] = \App\Models\COALedgers::where('group_id',$id)->get();
        // }
        $expenses = new \App\Models\Expense();
        $required_account = $this->required_account;

        // foreach ($required_account as $key => $value) {
        //   $parent_group  = $value->id;
        //   $child_group =
        // }
        $perms = $this->permission->all();

        return view('admin.expenses.create', compact('tags', 'expenses', 'vendors', 'perms', 'page_title', 'page_description', 'required_account','currencies'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function convertdate($date)
    {
        $cal = new \App\Helpers\NepaliCalendar();
        $converted = explode('-', $date);
        $converted = $cal->nep_to_eng($converted[0], $converted[1], $converted[2]);
        $converted_date = $converted['year'] . '-' . $converted['month'] . '-' . $converted['date'];

        return $converted_date;
    }

    public function store(Request $request)
    {

        DB::beginTransaction();
        $this->validate($request, [
//           'paid_through' => 'required',
           'expenses_account' => 'required',
           'amount' => 'required',
           'vendor_id' => 'required',
            ]);
        if ($request->make_payment==1)
            $request->validate(['paid_through'=>'required']);

        $attributes = $request->all();
        if ($request->datetype == 'nep') {
            $attributes['date'] = $this->convertdate($attributes['date']);
        }
        $attributes['user_id'] = \Auth::user()->id;
        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;

        if ($request->file('attachment')) {
            $stamp = time();

            $file = $request->file('attachment');
            //dd($file);
            $destinationPath = public_path().'/attachment/';
            $filename = $file->getClientOriginalName();
            $request->file('attachment')->move($destinationPath, $stamp.'_'.$filename);

            $attributes['attachment'] = $stamp.'_'.$filename;
        }

        // dd($attributes);
        $expenses = $this->expense->create($attributes);
        Audit::log(Auth::user()->id, trans('admin/clients/general.audit-log.category'), trans('admin/clients/general.audit-log.msg-store', ['name' => $attributes['name'] ?? '--']));

        Flash::success('Expenses Created Successfully'); // 'Client successfully created');
        //update ledger
        $total_amt=$attributes['amount']+$attributes['tax_amount'];
        $attributes['entrytype_id'] = '18'; //expenses
        $attributes['tag_id'] = '18'; //expenditure
        $attributes['user_id'] = \Auth::user()->id;
        $attributes['org_id'] = \Auth::user()->org_id;
        $type=\App\Models\Entrytype::find(18);
        $attributes['number'] = \TaskHelper::generateId($type);
        $attributes['date'] = $attributes['date'];
        $attributes['dr_total'] = request()->make_payment == 1 ?$total_amt+$total_amt:$total_amt;
        $attributes['cr_total'] = request()->make_payment == 1 ?$total_amt+$total_amt:$total_amt;
        $attributes['currency_id'] = $attributes['currency_id'];
        $attributes['source'] = 'AUTO_EXPENSE';
        $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
        $entry = \App\Models\Entry::create($attributes);

        //supplier account
        $supplier=Client::find($request->vendor_id);
        $sub_amount = new \App\Models\Entryitem();
        $sub_amount->entry_id = $entry->id;
        $sub_amount->user_id = \Auth::user()->id;
        $sub_amount->org_id = \Auth::user()->org_id;
        $sub_amount->dc = 'C';
        $sub_amount->ledger_id = $supplier->ledger_id; //
        $sub_amount->amount = $attributes['amount']+$attributes['tax_amount'];
        $sub_amount->narration = $attributes['reference']; //$request->user_id
        $sub_amount->save();

        $sub_amount = new \App\Models\Entryitem();
        $sub_amount->entry_id = $entry->id;
        $sub_amount->user_id = \Auth::user()->id;
        $sub_amount->org_id = \Auth::user()->org_id;
        $sub_amount->dc = 'D';
        $sub_amount->ledger_id = $attributes['expenses_account']; //
        $sub_amount->amount = $attributes['amount'];
        $sub_amount->narration = $attributes['reference']; //$request->user_id
        $sub_amount->save();

        //vat account
        if ($request->tax_type==13) {
            $sub_amount = new \App\Models\Entryitem();
            $sub_amount->entry_id = $entry->id;
            $sub_amount->user_id = \Auth::user()->id;
            $sub_amount->org_id = \Auth::user()->org_id;
            $sub_amount->dc = 'D';
            $sub_amount->ledger_id = \FinanceHelper::get_ledger_id('PURCHASE_TAX_PAYABLE'); // Expense ledger if selected or ledgers from .env
            $sub_amount->amount = $attributes['tax_amount'];
            $sub_amount->narration = $attributes['reference']; //$request->user_id
            $sub_amount->save();
        }

        //expense account

        if ($request->make_payment==1) {
            //supplier account
            $supplier=Client::find($request->vendor_id);
            $sub_amount = new \App\Models\Entryitem();
            $sub_amount->entry_id = $entry->id;
            $sub_amount->user_id = \Auth::user()->id;
            $sub_amount->org_id = \Auth::user()->org_id;
            $sub_amount->dc = 'D';
            $sub_amount->ledger_id = $supplier->ledger_id; //
            $sub_amount->amount = $attributes['amount']+$attributes['tax_amount'];
            $sub_amount->narration = $attributes['reference']; //$request->user_id
            $sub_amount->save();

            // Paid through account
            $cash_amount = new \App\Models\Entryitem();
            $cash_amount->entry_id = $entry->id;
            $cash_amount->user_id = \Auth::user()->id;
            $cash_amount->org_id = \Auth::user()->org_id;
            $cash_amount->dc = 'C';
            $cash_amount->ledger_id = $attributes['paid_through']; //select checking account from dropdown
            $cash_amount->amount = $attributes['amount']+$attributes['tax_amount'];
            $cash_amount->narration = $attributes['reference'];
            $cash_amount->save();
        }
        //now update entry_id in expense row
        $expenses->update(['entry_id'=>$entry->id]);

        DB::commit();

        return redirect('/admin/expenses');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $expenses = $this->expense->find($id);

        \TaskHelper::authorizeOrg($expenses);

        Audit::log(Auth::user()->id, trans('admin/clients/general.audit-log.category'), trans('admin/clients/general.audit-log.msg-edit', ['name' => $expenses->name]));
        $required_account = $this->required_account;
        $page_title = 'Expense'; // "Admin | Client | Edit";
        $page_description = 'edit window'; // "Editing client";
        $vendors = \App\Models\Client::pluck('name', 'id')->all();
        $tags = \App\Models\IncomeExpenseCategory::orderBy('name', 'ASC')->whereType('expense')->pluck('name', 'id')->all();
        $currencies = \App\Models\Currency::pluck('name', 'id')->all();
        $history = \App\Models\ExpenseEditHistory::where('expense_id', $id)->get();
        if (!$expenses->isEditable() && !$expenses->canChangePermissions()) {
            abort(403);
        }

        return view('admin.expenses.edit', compact('expenses', 'vendors', 'page_title', 'page_description', 'history', 'required_account', 'tags','currencies'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

        DB::beginTransaction();

        $this->validate($request, [
//           'paid_through' => 'required',
            'expenses_account' => 'required',
            'amount' => 'required',
            'vendor_id' => 'required',
        ]);
        if ($request->make_payment==1)
            $request->validate(['paid_through'=>'required']);


        $attributes = $request->all();
        if ($request->datetype == 'nep') {
            $attributes['date'] = $this->convertdate($attributes['date']);
        }
        $attributes['user_id'] = \Auth::user()->id;
        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['paid_through']=$request->make_payment==1?$request->paid_through:0;

        $expenses = $this->expense->find($id);

        \TaskHelper::authorizeOrg($expenses);

        if ($request->file('attachment')) {
            $stamp = time();

            $file = $request->file('attachment');
            //dd($file);
            $destinationPath = public_path() . '/attachment/';
            $filename = $file->getClientOriginalName();
            $request->file('attachment')->move($destinationPath, $stamp . '_' . $filename);

            $attributes['attachment'] = $stamp . '_' . $filename;
        }
        $current_expenses_amount = $expenses->amount;
        if ($expenses->isEditable()) {
            $expenses->update($attributes);
            $total_amt= $attributes['amount'] +$attributes['tax_amount'];

            $attributes['entrytype_id'] = '18'; //Purchase
            $attributes['tag_id'] = '18'; //expenditure
            $attributes['user_id'] = \Auth::user()->id;
            $attributes['org_id'] = \Auth::user()->org_id;
            $attributes['date'] = $attributes['date'];
            $attributes['dr_total'] = request()->make_payment == 1 ?$total_amt+$total_amt:$total_amt;
            $attributes['cr_total'] = request()->make_payment == 1 ?$total_amt+$total_amt:$total_amt;
            $attributes['source'] = 'AUTO_EXPENSE';
            $entry = tap(\App\Models\Entry::find($expenses->entry_id))->update($attributes);

            \App\Models\Entryitem::where('entry_id', $expenses->entry_id)->delete();

            //supplier account
            $supplier=Client::find($request->vendor_id);
            $sub_amount = new \App\Models\Entryitem();
            $sub_amount->entry_id = $entry->id;
            $sub_amount->user_id = \Auth::user()->id;
            $sub_amount->org_id = \Auth::user()->org_id;
            $sub_amount->dc = 'C';
            $sub_amount->ledger_id = $supplier->ledger_id; //
            $sub_amount->amount = $attributes['amount']+$attributes['tax_amount'];
            $sub_amount->narration = $attributes['reference']; //$request->user_id
            $sub_amount->save();

            $sub_amount = new \App\Models\Entryitem();
            $sub_amount->entry_id = $entry->id;
            $sub_amount->user_id = \Auth::user()->id;
            $sub_amount->org_id = \Auth::user()->org_id;
            $sub_amount->dc = 'D';
            $sub_amount->ledger_id = $attributes['expenses_account']; //
            $sub_amount->amount = $attributes['amount'];
            $sub_amount->narration = $attributes['reference']; //$request->user_id
            $sub_amount->save();

            //vat account
            if ($request->tax_type==13) {
                $sub_amount = new \App\Models\Entryitem();
                $sub_amount->entry_id = $entry->id;
                $sub_amount->user_id = \Auth::user()->id;
                $sub_amount->org_id = \Auth::user()->org_id;
                $sub_amount->dc = 'D';
                $sub_amount->ledger_id = \FinanceHelper::get_ledger_id('PURCHASE_TAX_PAYABLE'); // Expense ledger if selected or ledgers from .env
                $sub_amount->amount = $attributes['tax_amount'];
                $sub_amount->narration = $attributes['reference']; //$request->user_id
                $sub_amount->save();
            }

            //expense account

            if ($request->make_payment==1) {
                //supplier account
                $supplier=Client::find($request->vendor_id);
                $sub_amount = new \App\Models\Entryitem();
                $sub_amount->entry_id = $entry->id;
                $sub_amount->user_id = \Auth::user()->id;
                $sub_amount->org_id = \Auth::user()->org_id;
                $sub_amount->dc = 'D';
                $sub_amount->ledger_id = $supplier->ledger_id; //
                $sub_amount->amount = $attributes['amount']+$attributes['tax_amount'];
                $sub_amount->narration = $attributes['reference']; //$request->user_id
                $sub_amount->save();

                // Paid through account
                $cash_amount = new \App\Models\Entryitem();
                $cash_amount->entry_id = $entry->id;
                $cash_amount->user_id = \Auth::user()->id;
                $cash_amount->org_id = \Auth::user()->org_id;
                $cash_amount->dc = 'C';
                $cash_amount->ledger_id = $attributes['paid_through']; //select checking account from dropdown
                $cash_amount->amount = $attributes['amount']+$attributes['tax_amount'];
                $cash_amount->narration = $attributes['reference'];
                $cash_amount->save();
            }
            //expense history
            \App\Models\ExpenseEditHistory::create([
                'expense_id' => $expenses->id,
                'from_amount' => $current_expenses_amount,
                'to_amount' => $attributes['amount'],
                'user_id' => \Auth::user()->id,
            ]);
        }

        Audit::log(Auth::user()->id, trans('admin/clients/general.audit-log.category'), trans('admin/clients/general.audit-log.msg-update', ['name' => $expenses->name]));

        Flash::success('Expenses successfully updated '); // 'Client successfully updated');

        DB::commit();
        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $expenses = $this->expense->find($id);
        \TaskHelper::authorizeOrg($expenses);
        if (!$expenses->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/clients/general.audit-log.category'), trans('admin/clients/general.audit-log.msg-destroy', ['name' => $expenses->id]));
        $entry_id = $expenses->entry_id;
        \App\Models\Entry::where('id', $entry_id)->delete();
        \App\Models\Entryitem::where('entry_id', $entry_id)->delete();
        \App\Models\ExpenseEditHistory::where('expense_id', $expenses->id)->delete();
        $expenses->delete($id);

        Flash::success('Expenses successfully deleted');

        return redirect('/admin/expenses');
    }

    /**
     * Delete Confirm.
     *
     * @param int $id
     * @return  View
     */
    public function getModalDelete($id)
    {
        $error = null;

        $clients = $this->expense->find($id);

        if (!$clients->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Expenses';

        $clients = $this->expense->find($id);
        $modal_route = route('admin.expenses.delete', $clients->id);

        $modal_body = 'Are You sure You want to delete this Expense?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $clients = $this->expense->find($id);

        Audit::log(Auth::user()->id, trans('admin/clients/general.audit-log.category'), trans('admin/clients/general.audit-log.msg-enable', ['name' => $clients->name]));

        Flash::success(trans('admin/clients/general.status.enabled'));

        return redirect('/admin/clients');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $clients = $this->client->find($id);
        \TaskHelper::authorizeOrg($clients);
        Audit::log(Auth::user()->id, trans('admin/clients/general.audit-log.category'), trans('admin/clients/general.audit-log.msg-disabled', ['name' => $clients->name]));

        $clients->enabled = false;
        $clients->save();

        Flash::success(trans('admin/clients/general.status.disabled'));

        return redirect('/admin/clients');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkclients = $request->input('chkClient');

        Audit::log(Auth::user()->id, trans('admin/clients/general.audit-log.category'), trans('admin/clients/general.audit-log.msg-enabled-selected'), $chkclients);

        if (isset($chkclients)) {
            foreach ($chkclients as $clients_id) {
                $clients = $this->client->find($clients_id);
                $clients->enabled = true;
                $clients->save();
            }
            Flash::success(trans('admin/clients/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/clients/general.status.no-client-selected'));
        }

        return redirect('/admin/clients');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkclients = $request->input('chkClient');

        Audit::log(Auth::user()->id, trans('admin/clients/general.audit-log.category'), trans('admin/clients/general.audit-log.msg-disabled-selected'), $chkclients);

        if (isset($chkclients)) {
            foreach ($chkclients as $clients_id) {
                $clients = $this->client->find($clients_id);
                $clients->enabled = false;
                $clients->save();
            }
            Flash::success(trans('admin/clients/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/clients/general.status.no-client-selected'));
        }

        return redirect('/admin/clients');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $clients = $this->client->pushCriteria(new clientsWhereDisplayNameLike($query))->all();

        foreach ($clients as $clients) {
            $id = $clients->id;
            $name = $clients->name;
            $email = $clients->email;

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
        $clients = $this->client->find($id);
        \TaskHelper::authorizeOrg($clients);
        return $clients;
    }

    public function get_client()
    {
        $term = strtolower(\Request::get('term'));
        $contacts = ClientModel::select('id', 'name')->where('name', 'LIKE', '%' . $term . '%')->where('enabled', '1')->groupBy('name')->take(5)->get();
        $return_array = [];

        foreach ($contacts as $v) {
            if (strpos(strtolower($v->name), $term) !== false) {
                $return_array[] = ['value' => $v->name, 'id' => $v->id];
            }
        }

        return \Response::json($return_array);
    }

    public function postCreateModal(Request $request){
        $category=IncomeExpenseCategory::create(['name'=>$request->name,'type'=>'expense']);
        return ['data'=>$category];
    }
}
