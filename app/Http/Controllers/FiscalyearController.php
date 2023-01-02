<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Fiscalyear;
use App\Models\Role as Permission;
use App\Traits\ReorderTablesWithFiscalYear;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FiscalyearController extends Controller
{
    use ReorderTablesWithFiscalYear;

    /**
     * @var FiscalYear
     */
    private $FiscalYear;

    /**
     * @var Permission
     */
    private $permission;
    private $org_id;

    /**
     * @param FiscalYear $FiscalYear
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(FiscalYear $FiscalYear, Permission $permission)
    {
        parent::__construct();
        $this->FiscalYear = $FiscalYear;
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
        Audit::log(Auth::user()->id, trans('admin/FiscalYear/general.audit-log.category'), trans('admin/FiscalYear/general.audit-log.msg-index'));

        $allFiscalYear = $this->FiscalYear->orderBy('fiscal_year', 'desc')->where('org_id', $this->org_id)->paginate(10);
        $page_title = 'Fiscal Year';
        $page_description = 'All Years';

        return view('admin.fiscalyear.index', compact('allFiscalYear', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $FiscalYear = $this->FiscalYear->find($id);
        if ($FiscalYear->org_id != $this->org_id) {
            abort(404);
        }
        Audit::log(Auth::user()->id, trans('admin/FiscalYear/general.audit-log.category'), trans('admin/FiscalYear/general.audit-log.msg-show', ['name' => $FiscalYear->name]));

        $page_title = trans('admin/FiscalYear/general.page.show.title'); // "Admin | FiscalYear | Show";
        $page_description = trans('admin/FiscalYear/general.page.show.description'); // "Displaying FiscalYear: :name";

        return view('admin.FiscalYear.show', compact('FiscalYear', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Admin | FiscalYear | Create';
        $page_description = 'Creating a new FiscalYear';

        $FiscalYear = new \App\Models\FiscalYear();
        $perms = $this->permission->all();

        return view('admin.fiscalyear.create', compact('FiscalYear', 'perms', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, ['fiscal_year' => 'required|unique:fiscalyears']);

        $attributes = $request->all();
        $attributes['org_id'] = $this->org_id;
        Audit::log(Auth::user()->id, trans('admin/FiscalYear/general.audit-log.category'), trans('admin/FiscalYear/general.audit-log.msg-store', ['fiscal_year' => $attributes['fiscal_year']]));

        $FiscalYear = $this->FiscalYear->create($attributes);
        if ($FiscalYear->current_year) {
            DB::update("update fiscalyears set current_year = (case when id = '$FiscalYear->id' then '1' else '0' end)");
        }
        Flash::success('FiscalYear successfully created'); // 'FiscalYear successfully created');

        return redirect('/admin/fiscalyear');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $FiscalYear = $this->FiscalYear->find($id);
        if ($FiscalYear->org_id != $this->org_id) {
            abort(404);
        }
        Audit::log(Auth::user()->id, trans('admin/FiscalYear/general.audit-log.category'), trans('admin/FiscalYear/general.audit-log.msg-edit', ['fiscal_year' => $FiscalYear->fiscal_year]));

        $page_title = 'Admin | FiscalYear | Edit';
        $page_description = 'Editing FiscalYear';

        if (!$FiscalYear->isEditable() && !$FiscalYear->canChangePermissions()) {
            abort(403);
        }

        return view('admin.fiscalyear.edit', compact('FiscalYear', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['fiscal_year' => 'required|unique:fiscalyears,fiscal_year,' . $id,]);

        $FiscalYear = $this->FiscalYear->find($id);

        Audit::log(Auth::user()->id, trans('admin/FiscalYear/general.audit-log.category'), trans('admin/FiscalYear/general.audit-log.msg-update', ['fiscal_year' => $FiscalYear->fiscal_year]));

        $attributes = $request->all();

        if ($FiscalYear->isEditable()) {
            $FiscalYear->update($attributes);
            $chkFiscalYear = $request->current_year;
            if ($chkFiscalYear) {
                DB::update("UPDATE fiscalyears set current_year = (case when id = '$chkFiscalYear' then '1' else '0' end)
                    WHERE org_id = '$this->org_id'");
            }
        }

        Flash::success('FiscalYear successfully updated'); // 'FiscalYear successfully updated');

        return redirect('/admin/fiscalyear');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $FiscalYear = $this->FiscalYear->find($id);

        if (!$FiscalYear->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/FiscalYear/general.audit-log.category'), trans('admin/FiscalYear/general.audit-log.msg-destroy', ['name' => $FiscalYear->name]));

        $this->FiscalYear->delete($id);

        Flash::success(trans('admin/FiscalYear/general.status.deleted')); // 'FiscalYear successfully deleted');

        return redirect('/admin/fiscalyear');
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

        $FiscalYear = $this->FiscalYear->find($id);

        //dd($FiscalYear);

        if (!$FiscalYear->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/FiscalYear/dialog.delete-confirm.title');


        $modal_route = route('admin.fiscalyear.delete', ['fiscalyearId' => $FiscalYear->id]);

        //dd($modal_route);

        $modal_body = trans('admin/FiscalYear/dialog.delete-confirm.body', ['id' => $FiscalYear->id, 'name' => $FiscalYear->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $FiscalYear = $this->FiscalYear->find($id);

        Audit::log(Auth::user()->id, trans('admin/FiscalYear/general.audit-log.category'), trans('admin/FiscalYear/general.audit-log.msg-enable', ['name' => $FiscalYear->name]));

        $FiscalYear->enabled = true;
        $FiscalYear->save();

        Flash::success(trans('admin/FiscalYear/general.status.enabled'));

        return redirect('/admin/fiscalyear');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $FiscalYear = $this->FiscalYear->find($id);

        Audit::log(Auth::user()->id, trans('admin/FiscalYear/general.audit-log.category'), trans('admin/FiscalYear/general.audit-log.msg-disabled', ['name' => $FiscalYear->name]));

        $FiscalYear->enabled = false;
        $FiscalYear->save();

        Flash::success(trans('admin/FiscalYear/general.status.disabled'));

        return redirect('/admin/fiscalyear');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkFiscalYear = $request->input('chkFiscalYear');
        Audit::log(Auth::user()->id, trans('admin/FiscalYear/general.audit-log.category'), trans('admin/FiscalYear/general.audit-log.msg-enabled-selected'), [$chkFiscalYear]);

        //make all database current_year column to null
        //DB::update("update fiscalyears set current_year = NULL")
        //and then update
        DB::update("UPDATE fiscalyears set current_year = (case when id = '$chkFiscalYear' then '1' else '0' end)
                    WHERE org_id = '$this->org_id'");

        Flash::success(trans('Fiscal years activated'));

        return redirect('/admin/fiscalyear');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkFiscalYear = $request->input('chkFiscalYear');

        Audit::log(Auth::user()->id, trans('admin/FiscalYear/general.audit-log.category'), trans('admin/FiscalYear/general.audit-log.msg-disabled-selected'), $chkFiscalYear);

        if (isset($chkFiscalYear)) {
            foreach ($chkFiscalYear as $FiscalYear_id) {
                $FiscalYear = $this->FiscalYear->find($FiscalYear_id);
                $FiscalYear->enabled = false;
                $FiscalYear->save();
            }
            Flash::success(trans('admin/FiscalYear/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/FiscalYear/general.status.no-FiscalYear-selected'));
        }

        return redirect('/admin/fiscalyear');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $FiscalYear = $this->FiscalYear->pushCriteria(new FiscalYearWhereDisplayNameLike($query))->all();

        foreach ($FiscalYear as $FiscalYear) {
            $id = $FiscalYear->id;
            $name = $FiscalYear->name;
            $email = $FiscalYear->email;

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
        $FiscalYear = $this->FiscalYear->find($id);

        return $FiscalYear;
    }

    public function migrateData($table_name)
    {
        $tables_for_migration = [
            'coa_ledgers' => '\App\Models\COALedgers',
            'credit_notes' => '\App\Models\CreditNote',
            'credit_notes_detail' => '\App\Models\CreditNoteDetail',
            'product_stock_moves' => '\App\Models\StockMove',
            'product_stock_master' => '\App\Models\StockMaster',
            'entries' => '\App\Models\Entry',
            'entryitems' => '\App\Models\Entryitem',
            'entry_item_details' => '\App\Models\EntryItemDetail',
            'expenses' => '\App\Models\Expense',
            'expenses_edit_history' => '\App\Models\ExpenseEditHistory',
            'bank_income' => '\App\Models\BankIncome',
            'fin_orders' => '\App\Models\Orders',
            'fin_order_detail' => '\App\Models\OrderDetail',
            'fin_order_payment_terms' => '\App\Models\OrderPaymentTerms',
            'invoice' => '\App\Models\Invoice',
            'invoice_detail' => '\App\Models\InvoiceDetail',
            'invoice_meta' => '\App\Models\InvoiceMeta',
            'invoice_payment' => '\App\Models\InvoicePayment',
            'payments' => '\App\Models\Payment',
            'bill_print_invoice' => '\App\Models\Invoiceprint',
            'purch_orders' => '\App\Models\PurchaseOrder',
            'purch_order_details' => '\App\Models\PurchaseOrderDetail'

        ];
        if (!$tables_for_migration[$table_name]) {
            Flash::error('Table does not exist');
            return redirect()->back();
        }
        $table = $table_name;
        $model = $tables_for_migration[$table_name];
        $current_fiscal_year = Fiscalyear::where('org_id', \Auth::user()->org_id)->where('current_year', 1)->first();
        $last_fiscal_year = Fiscalyear::where('org_id', \Auth::user()->org_id)->where('current_year',0)->latest()->first();
        $table_name_to_update = $last_fiscal_year->numeric_fiscal_year . '_' . $table;
        if (Schema::hasTable($table_name_to_update)) {
            Flash::error('Table already exist');
            return redirect()->back();
        }
//            foreach ($tables_for_migration as $table => $model) {
//        DB::transaction(function () use ($current_fiscal_year, $last_fiscal_year, $table, $model, $table_name_to_update) {
        if ($table == 'coa_ledgers') {
            $list_closing_stock = $this->collectLedgerClosingStock($current_fiscal_year->start_date, $last_fiscal_year->end_date);
        } elseif ($table == 'product_stock_moves') {
            $list_closing_stock = $this->collectProductStockClosingStock($current_fiscal_year->start_date, $last_fiscal_year->end_date);
        }
        $remaining_list_to_shift = $model::
        whereDate('created_at', '>=', $current_fiscal_year->start_date);
        $remaining_list = [];
        foreach ($remaining_list_to_shift->get() as $data) {
            $item = json_decode($data, true);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', Carbon::parse($item['created_at']), new \DateTimeZone('GMT'));
            $date->setTimezone(new \DateTimeZone('Asia/Kathmandu'));
            $item['created_at'] = $date;
            $item['updated_at'] = $date;
            $remaining_list[] = $item;
        };
        $rename_table = $this->renameAndDuplicateTable($table, $last_fiscal_year->numeric_fiscal_year);
        if ($rename_table == true) {
            if ($table == 'fin_orders') {
                DB::statement('DROP TRIGGER IF EXISTS fin_order_delete');
                DB::statement('DROP TRIGGER IF EXISTS fin_order_update');
                DB::table($table_name_to_update)->whereDate('created_at', '>=', '2021-07-16')->delete();
                //DB::unprepared('CREATE TRIGGER ' . $last_fiscal_year->numeric_fiscal_year . '_fin_order_delete' . ' BEFORE DELETE ON ' . $table_name_to_update . ' FOR EACH ROW BEGIN SIGNAL SQLSTATE \'45000\' SET MESSAGE_TEXT = \'Cannot Delete a Bill  which has Been Created\'; END');
                DB::unprepared('CREATE TRIGGER `fin_order_update` BEFORE UPDATE ON `fin_orders` FOR EACH ROW BEGIN IF EXISTS (SELECT * FROM fin_orders_meta  WHERE fin_orders_meta.order_id = OLD.id AND fin_orders_meta.settlement = 1 ) THEN SIGNAL SQLSTATE \'45000\' SET MESSAGE_TEXT = \'Cannot Update a Pos  which has Been Settled\'; END IF; END');
                DB::unprepared('CREATE TRIGGER `fin_order_delete` BEFORE DELETE ON ' . $table . ' FOR EACH ROW BEGIN SIGNAL SQLSTATE \'45000\' SET MESSAGE_TEXT = \'Cannot Delete a Bill  which has Been Created\'; END');
            }
            if ($table == 'fin_order_detail') {
                DB::statement('DROP TRIGGER IF EXISTS fin_order_detail_delete');
                DB::statement('DROP TRIGGER IF EXISTS fin_order_detail_update');
                DB::table($table_name_to_update)->whereDate('created_at', '>=', '2021-07-16')->delete();
//                    DB::unprepared('CREATE TRIGGER '.$last_fiscal_year->numeric_fiscal_year . '_fin_order_detail_delete'. 'BEFORE DELETE ON '. $table_name_to_update.' FOR EACH ROW BEGIN IF EXISTS (SELECT * FROM fin_orders_meta  WHERE fin_orders_meta.order_id = OLD.order_id AND fin_orders_meta.settlement = 1 ) THEN SIGNAL SQLSTATE \'45000\' SET MESSAGE_TEXT = \'Cannot delete a Pos  which has Been Settled\';');
                DB::unprepared('CREATE TRIGGER `fin_order_detail_update` BEFORE UPDATE ON `fin_order_detail` FOR EACH ROW BEGIN IF EXISTS (SELECT * FROM fin_orders_meta  WHERE fin_orders_meta.order_id = OLD.order_id AND fin_orders_meta.settlement = 1 ) THEN SIGNAL SQLSTATE \'45000\' SET MESSAGE_TEXT = \'Cannot Update a Pos  which has Been Settled\'; END IF; END ');
                DB::unprepared('CREATE TRIGGER `fin_order_detail_delete` BEFORE DELETE ON `fin_order_detail` FOR EACH ROW BEGIN IF EXISTS (SELECT * FROM fin_orders_meta  WHERE fin_orders_meta.order_id = OLD.order_id AND fin_orders_meta.settlement = 1 ) THEN SIGNAL SQLSTATE \'45000\' SET MESSAGE_TEXT = \'Cannot delete a Pos  which has Been Settled\'; END IF; END');
            }
            if ($table == 'bill_reprints_pos') {
                DB::statement('DROP TRIGGER IF EXISTS bill_reprints_pos_delete');
                DB::table($table_name_to_update)->whereDate('created_at', '>=', '2021-07-16')->delete();
                DB::unprepared('CREATE TRIGGER `bill_reprints_pos_delete` BEFORE DELETE ON `bill_reprints_pos` FOR EACH ROW BEGIN SIGNAL SQLSTATE \'45000\' SET MESSAGE_TEXT = \'Cannot delete which has Been Created\'; END ');
            }
            if ($table == 'coa_ledgers' || $table == 'product_stock_moves') {
                foreach (array_chunk($list_closing_stock, 1000) as $t) {
                    $model::insert($t);
                }
            }
            foreach (array_chunk($remaining_list, 1000) as $t) {
                $model::insert($t);
            }
            if ($table != 'fin_orders' && $table != 'fin_order_detail'&&$table != 'bill_reprints_pos') {
                DB::table($table_name_to_update)->whereDate('created_at', '>=', '2021-07-16')->delete();
            }
        }
//        });
//            }
        Flash::success('New Table ' . $table_name . ' Created and Data Migrated');
        return redirect()->route('admin.fiscalyear.index');
    }
}
