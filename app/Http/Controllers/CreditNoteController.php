<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CreditNote;
use App\Models\CreditNoteDetail;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\MasterComments;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Role as Permission;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class CreditNoteController extends Controller
{
    /**
     * @var Client
     */
    private $creditnote;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $bug
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Permission $permission, CreditNote $creditnote)
    {
        parent::__construct();
        $this->permission = $permission;
        $this->creditnote = $creditnote;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = \App\Models\CreditNote::orderBy('id', 'desc')->where('org_id', Auth::user()->org_id)->get();
        $page_title = 'Admin | Credit Note';
        $page_description = 'Manage Credit Note';

        return view('admin.creditnote.index', compact('orders', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $ord = CreditNote::find($id);
        $page_title = 'Credit Note';
        $page_description = 'View Credit Note';
        $orderDetails = CreditNoteDetail::where('credit_note_id', $id)->get();
        $imagepath = Auth::user()->organization->logo;

        return view('admin.creditnote.show', compact('ord', 'imagepath', 'page_title', 'page_description', 'orderDetails'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Credit Note';
        $page_description = 'Add Credit Note';
        $order = null;
        $orderDetail = null;
        $products = Product::select('id', 'name')->get();
        $users = \App\User::where('enabled', '1')->where('org_id', Auth::user()->org_id)->pluck('first_name', 'id');

        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();

        $clients = \App\Models\Client::select('id', 'name')->orderBy('id', 'DESC')->get();

        return view('admin.creditnote.create', compact('page_title', 'users', 'page_description', 'order', 'orderDetail', 'products', 'clients', 'productlocation'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'customer_id' => 'required',
        ]);

        $ckfiscalyear = \App\Models\Fiscalyear::where('current_year', '1')
                        ->where('start_date', '<=', date('Y-m-d'))
                        ->where('end_date', '>=', date('Y-m-d'))
                        ->first();
        if (! $ckfiscalyear) {
            return Redirect::back()->withErrors(['Please update fiscal year <a href="/admin/fiscalyear/create">Click Here</a>!']);
        }
        $bill_no = \App\Models\CreditNote::select('bill_no')
                    ->where('fiscal_year', $ckfiscalyear->fiscal_year)
                    ->orderBy('bill_no', 'desc')
                    ->first();
        $bill_no = $bill_no->bill_no + 1;
        $order_attributes = $request->all();

        //  $order_attributes['user_id'] = Auth::user()->id;
        $order_attributes['org_id'] = Auth::user()->org_id;
        $order_attributes['client_id'] = $request->customer_id;
        $order_attributes['tax_amount'] = $request->taxable_tax;
        $order_attributes['total_amount'] = $request->final_total;
        $order_attributes['bill_no'] = $bill_no;
        $order_attributes['fiscal_year'] = $ckfiscalyear->fiscal_year;
        $order_attributes['is_bill_active'] = 1;
        $order_attributes['fiscal_year_id'] = $ckfiscalyear->id;

        //dd($order_attributes);
        $creditnote = \App\Models\CreditNote::create($order_attributes);

        $product_id = $request->product_id;
        //dd($product_ids);
        $price = $request->price;
        $quantity = $request->quantity;
        $tax = $request->tax;
        $tax_amount = $request->tax_amount;
        $credit_total = $request->credit_total;

        $returnable = $request->returnable;
        $credit_qty = $request->credit_qty;
        $credit_price = $request->credit_price;
        $reason = $request->reason;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new CreditNoteDetail();
                $detail->client_id = $request->customer_id;
                $detail->credit_note_id = $creditnote->id;
                $detail->product_id = $product_id[$key];
                $detail->price = $price[$key];
                $detail->quantity = $quantity[$key];
                $detail->tax = $tax[$key];
                $detail->tax = $tax[$key];
                $detail->tax_amount = $tax_amount[$key];
                $detail->credit_total = $credit_total[$key];

                $detail->returnable = $returnable[$key];
                $detail->credit_qty = $credit_qty[$key];
                $detail->credit_price = $credit_price[$key];
                $detail->reason = $reason[$key];

                $detail->date = date('Y-m-d H:i:s');
                $detail->is_inventory = 1;
                $detail->save();
            }
        }

        // Custom items
        $tax_id_custom = $request->custom_tax_amount;
        $custom_items_name = $request->custom_items_name;
        $custom_items_rate = $request->custom_items_rate;
        $custom_items_qty = $request->custom_items_qty;
        $custom_items_price = $request->custom_items_price;

        $custom_tax_amount = $request->custom_tax_amount;
        $custom_credit_total = $request->custom_credit_total;

        $custom_returnable = $request->custom_returnable;
        $custom_credit_qty = $request->custom_credit_qty;
        $custom_credit_price = $request->custom_credit_price;
        $custom_reason = $request->custom_reason;

        foreach ($custom_items_name as $key => $value) {
            if ($value != '') {
                $detail = new CreditNoteDetail();
                $detail->client_id = $request->customer_id;
                $detail->credit_note_id = $creditnote->id;
                $detail->description = $custom_items_name[$key];
                $detail->price = $custom_items_price[$key];
                $detail->quantity = $custom_items_qty[$key];
                $detail->tax = $tax_id_custom[$key];
                $detail->tax_amount = $custom_tax_amount[$key];
                $detail->credit_total = $custom_credit_total[$key];

                $detail->returnable = $custom_returnable[$key];
                $detail->credit_qty = $custom_credit_qty[$key];
                $detail->credit_price = $custom_credit_price[$key];
                $detail->reason = $custom_reason[$key];

                $detail->date = date('Y-m-d H:i:s');
                $detail->is_inventory = 0;
                //  dd($detail);
                $detail->save();
            }
        }

        $this->updateEntries($creditnote->id);

        Flash::success('Credit Note created Successfully.');

        return redirect('/admin/creditnote');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $page_title = 'Credit Note';
        $page_description = 'Edit credit Notes';
        $order = \App\Models\CreditNote::where('id', $id)->first();
        $orderDetails = \App\Models\CreditNoteDetail::where('credit_note_id', $id)->get();

        $products = Product::select('id', 'name')->get();
        $users = \App\User::where('enabled', '1')->where('org_id', Auth::user()->org_id)->pluck('first_name', 'id');

        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();

        $clients = \App\Models\Client::select('id', 'name')->orderBy('id', DESC)->get();

        return view('admin.creditnote.edit', compact('page_title', 'order', 'orderDetails', 'products', 'users', 'productlocation', 'clients', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'customer_id' => 'required',
        ]);

        //dd($request->all());
        $creditnote = CreditNote::find($id);
        //dd($order);
        if ($creditnote->isEditable()) {
            $order_attributes = $request->all();

            $order_attributes['org_id'] = Auth::user()->org_id;
            $order_attributes['client_id'] = $request->customer_id;

            $order_attributes['tax_amount'] = $request->taxable_tax;
            $order_attributes['total_amount'] = $request->final_total;

            //dd($order_attributes);

            $creditnote->update($order_attributes);

            CreditNoteDetail::where('credit_note_id', $id)->delete();

            $product_id = $request->product_id;

            $price = $request->price;
            $quantity = $request->quantity;
            $tax = $request->tax;
            $tax_amount = $request->tax_amount;

            $credit_total = $request->credit_total;

            $returnable = $request->returnable;
            $credit_qty = $request->credit_qty;
            $credit_price = $request->credit_price;
            $reason = $request->reason;

            foreach ($product_id as $key => $value) {
                if ($value != '') {
                    $detail = new CreditNoteDetail();
                    $detail->client_id = $request->customer_id;
                    $detail->credit_note_id = $creditnote->id;
                    $detail->product_id = $product_id[$key];
                    $detail->price = $price[$key];
                    $detail->quantity = $quantity[$key];
                    $detail->tax = $tax[$key];
                    $detail->tax_amount = $tax_amount[$key];

                    $detail->credit_total = $credit_total[$key];

                    $detail->returnable = $returnable[$key];
                    $detail->credit_qty = $credit_qty[$key];
                    $detail->credit_price = $credit_price[$key];
                    $detail->reason = $reason[$key];

                    $detail->is_inventory = 1;
                    $detail->date = date('Y-m-d H:i:s');
                    $detail->save();
                }
            }

            // Custom items
            $tax_id_custom = $request->custom_tax_amount;
            $custom_items_name = $request->custom_items_name;
            $custom_items_rate = $request->custom_items_rate;
            $custom_items_qty = $request->custom_items_qty;
            $custom_items_price = $request->custom_items_price;

            $custom_tax_amount = $request->custom_tax_amount;
            $custom_credit_total = $request->custom_credit_total;

            $custom_returnable = $request->custom_returnable;
            $custom_credit_qty = $request->custom_credit_qty;
            $custom_credit_price = $request->custom_credit_price;
            $custom_reason = $request->custom_reason;

            foreach ($custom_items_name as $key => $value) {
                if ($value != '') {
                    $detail = new CreditNoteDetail();
                    $detail->client_id = $request->customer_id;
                    $detail->credit_note_id = $creditnote->id;
                    $detail->description = $custom_items_name[$key];
                    $detail->price = $custom_items_price[$key];
                    $detail->quantity = $custom_items_qty[$key];
                    $detail->tax = $tax_id_custom[$key];
                    $detail->tax_amount = $custom_tax_amount[$key];
                    $detail->credit_total = $custom_credit_total[$key];

                    $detail->returnable = $custom_returnable[$key];
                    $detail->credit_qty = $custom_credit_qty[$key];
                    $detail->credit_price = $custom_credit_price[$key];
                    $detail->reason = $custom_reason[$key];

                    $detail->date = date('Y-m-d H:i:s');
                    $detail->is_inventory = 0;
                    //  dd($detail);
                    $detail->save();
                }
            }

            if ($request->product_id_new != null) {

        // dd($request->description_new);
                $product_id_new = $request->product_id_new;
                $ticket_new = $request->ticket_new;
                $price_new = $request->price_new;
                $quantity_new = $request->quantity_new;
                $flight_date_new = $request->flight_date_new;
                $tax_new = $request->tax_new;
                $tax_amount_new = $request->tax_amount_new;
                $total_new = $request->total_new;

                foreach ($product_id_new as $key => $value) {
                    $detail = new CreditNoteDetail();
                    $detail->client_id = $request->customer_id;
                    $detail->credit_note_id = $creditnote->id;
                    $detail->product_id = $product_id_new[$key];
                    $detail->price = $price_new[$key];
                    $detail->quantity = $quantity_new[$key];
                    $detail->tax = $tax_new[$key];
                    $detail->tax_amount = $tax_amount_new[$key];
                    $detail->total = $total_new[$key];
                    $detail->is_inventory = 1;
                    $detail->date = date('Y-m-d H:i:s');
                    $detail->save();
                }
            }

            // Custom items Start
            $custom_items_name_new = $request->custom_items_name_new;
            $custom_ticket_new = $request->custom_ticket_new;
            $custom_items_price_new = $request->custom_items_price_new;
            $custom_items_qty_new = $request->custom_items_qty_new;
            $custom_flight_date_new = $request->custom_flight_date_new;
            $tax_id_custom_new = $request->tax_id_custom_new;
            $custom_tax_amount_new = $request->custom_tax_amount_new;
            $custom_total_new = $request->custom_total_new;

            if (! empty($custom_items_name_new)) {
                foreach ($custom_items_name_new as $key=>$value) {
                    $detail = new CreditNoteDetail();
                    $detail->client_id = $request->customer_id;
                    $detail->credit_note_id = $creditnote->id;
                    $detail->description = $custom_items_name_new[$key];
                    $detail->price = $custom_items_price_new[$key];
                    $detail->quantity = $custom_items_qty_new[$key];
                    $detail->tax = $tax_id_custom_new[$key];
                    $detail->tax_amount = $custom_tax_amount_new[$key];

                    $detail->credit_total = $custom_credit_total[$key];

                    $detail->returnable = $custom_returnable[$key];
                    $detail->credit_qty = $custom_credit_qty[$key];
                    $detail->credit_price = $custom_credit_price[$key];
                    $detail->reason = $custom_reason[$key];

                    $detail->is_inventory = 0;
                    $detail->date = date('Y-m-d H:i:s');
                    $detail->save();
                }
            }

            $this->updateEntries($creditnote->id);

            Flash::success('Credit Note updated Successfully.');

            return redirect('/admin/creditnote');
        } else {
            Flash::success('Error in updating CreditNote.');
        }

        return Redirect::back()->withInput(Request::all());
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $creditnotes = CreditNote::find($id);

        if (! $creditnotes->isdeletable()) {
            abort(403);
        }

        if ($creditnotes->entry_id && $creditnotes->entry_id != '0') {
            $entries = \App\Models\Entry::find($creditnotes->entry_id);
            \App\Models\Entryitem::where('entry_id', $entries->id)->delete();
            \App\Models\Entry::find($creditnotes->entry_id)->delete();
        }

        CreditNote::find($id)->delete();
        CreditNoteDetail::where('credit_note_id', $id)->delete();

        MasterComments::where('type', 'orders')->where('master_id', $id)->delete();

        Flash::success('Credit Note successfully deleted.');

        return redirect('/admin/creditnote');
    }

    public function getProductDetailAjax($productId)
    {
        $product = Course::select('id', 'name', 'price', 'cost')->where('id', $productId)->first();

        return ['data' => json_encode($product)];
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

        $creditnotes = CreditNote::find($id);

        if (! $creditnotes->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Order';

        $orders = CreditNote::find($id);

        $modal_route = route('admin.creditnote.delete', ['id' => $creditnotes->id]);

        $modal_body = 'Are you sure you want to delete this Credit Note?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function printCreditNote($id)
    {
        $ord = $this->creditnote->find($id);
        $orderDetails = CreditNoteDetail::where('credit_note_id', $id)->get();
        //dd($orderDetails);
        $imagepath = Auth::user()->organization->logo;
        // $print_no = \App\Models\Invoiceprint::where('invoice_id',$id)->count();
        // $attributes = new \App\Models\Invoiceprint();
        // $attributes->invoice_id = $id;
        // $attributes->printed_date = \Carbon\Carbon::now();
        // $attributes->printed_by = Auth::user()->id;
        // $attributes->save();
        // $ord->update(['is_bill_printed'=>1]);

        return view('admin.creditnote.print', compact('ord', 'imagepath', 'orderDetails', 'print_no'));
    }

    public function generatePDF($id)
    {
        $ord = $this->creditnote->find($id);
        $orderDetails = CreditNoteDetail::where('credit_note_id', $id)->get();
        $imagepath = Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.creditnote.pdf', compact('ord', 'imagepath', 'orderDetails'));
        $file = $id.'_'.$ord->name.'_'.str_replace(' ', '_', $ord->client->name).'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $orders = $this->orders->pushCriteria(new ordersWhereDisplayNameLike($query))->all();

        foreach ($orders as $orders) {
            $id = $orders->id;
            $name = $orders->name;
            $email = $orders->email;

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
        $orders = $this->orders->find($id);

        return $orders;
    }

    public function get_client()
    {
        $term = strtolower(Request::get('term'));
        $contacts = ClientModel::select('id', 'name')->where('name', 'LIKE', '%'.$term.'%')->groupBy('name')->take(5)->get();
        $return_array = [];

        foreach ($contacts as $v) {
            if (strpos(strtolower($v->name), $term) !== false) {
                $return_array[] = ['value' => $v->name, 'id' =>$v->id];
            }
        }

        return Response::json($return_array);
    }

    public function postOrdertoInvoice(Request $request, $id)
    {

        //dd($id);
        $order = \App\Models\Orders::find($id);
        $orderdetails = OrderDetail::where('order_id', $order->order_id)->get();
        $ckfiscalyear = \App\Models\Fiscalyear::where('current_year', '1')
                        ->where('start_date', '<=', date('Y-m-d'))
                        ->where('end_date', '>=', date('Y-m-d'))
                        ->first();
        if (! $ckfiscalyear) {
            return Redirect::back()->withErrors(['Please update fiscal year <a href="/admin/fiscalyear/create">Click Here</a>!']);
        }
        $bill_no = \App\Models\Invoice::select('bill_no')
                    ->where('fiscal_year', $ckfiscalyear->fiscal_year)
                    ->orderBy('bill_no', 'desc')
                    ->first();
        $bill_no = $bill_no->bill_no + 1;
        //dd($orderdetails);
        $invoice = new Invoice();

        $invoice->bill_no = $bill_no;
        $invoice->user_id = Auth::user()->id;
        $invoice->client_id = $order->client_id;
        $invoice->org_id = $order->org_id;
        $invoice->name = $order->name;
        $invoice->position = $order->position;
        $invoice->address = $order->address;
        $invoice->comment = $order->comment;
        $invoice->ship_date = $order->ship_date;
        $invoice->require_date = $order->require_date;
        $invoice->sales_tax = $order->sales_tax;
        $invoice->status = $order->status;
        $invoice->bill_date = $order->bill_date;
        $invoice->due_date = $order->due_date;
        $invoice->amount = $order->amount;
        $invoice->total_amount = $order->total_amount;
        $invoice->subtotal = $order->subtotal;
        $invoice->discount_amount = $order->discount_amount;
        $invoice->discount_note = $order->discount_note;
        $invoice->trans_type = $order->trans_type;
        $invoice->fiscal_year = $order->fiscal_year;
        $invoice->customer_pan = $order->customer_pan;
        $invoice->discount_percent = $order->discount_percent;

        //dd($invoice);
        $invoice->save();

        foreach ($orderdetails as $orderdetail) {
            $invoicedetail = new InvoiceDetail();
            $invoicedetail->client_id = $orderdetail->client_id;
            $invoicedetail->invoice_id = $orderdetail->order_id;
            $invoicedetail->product_id = $orderdetail->product_id;
            $invoicedetail->description = $orderdetail->description;
            $invoicedetail->price = $orderdetail->price;
            $invoicedetail->quantity = $orderdetail->quantity;
            $invoicedetail->total = $orderdetail->total;
            $invoicedetail->bill_date = $orderdetail->bill_date;
            $invoicedetail->date = $orderdetail->date;
            $invoicedetail->tax = $orderdetail->tax;
            $invoicedetail->tax_amount = $orderdetail->tax_amount;
            $invoicedetail->is_inventory = $orderdetail->is_inventory;
            $invoicedetail->save();
        }
        $order->update([
             'status'  => 'Invoiced',
          ]);

        $entry = \App\Models\Entry::create([
            'tag_id'=>env('SALES_TAG_ID'),
            'entrytype_id'=>\FinanceHelper::get_entry_type_id('journal'),
            'number'=>$invoice->id,
            'org_id'=>\Auth::user()->org_id,
            'user_id'=>\Auth::user()->id,
            'date'=>date('Y-m-d'),
            'dr_total'=>$invoice->total_amount,
            'cr_total'=>$invoice->total_amount,
            'fiscal_year_id'=> \FinanceHelper::cur_fisc_yr()->id,
        ]);

        $clients = \App\Models\Client::find($invoice->client_id);
        $entry_item = \App\Models\Entryitem::create([
            'entry_id'=>$entry->id,
            'dc'=>'C',
            'ledger_id'=>$clients->ledger_id,
            'amount'=>$invoice->total_amount,
            'narration'=>'Purchase being made',
        ]);

        $entry_item = \App\Models\Entryitem::create([
            'entry_id'=>$entry->id,
            'dc'=>'D',
            'ledger_id'=>\FinanceHelper::get_ledger_id('SALES_LEDGER_ID'),
            'amount'=>$invoice->total_amount,
            'narration'=>'Purchase being made',
        ]);

        return redirect('/admin/invoice');
    }

    private function updateEntries($orderId)
    {
        $creditnote = CreditNote::find($orderId);

        if ($creditnote->entry_id && $creditnote->entry_id != '0') { //update the ledgers

            // dd($creditnote);
            $attributes['entrytype_id'] = \FinanceHelper::get_entry_type_id('creditnote'); //Credit Notes
            $attributes['tag_id'] = '3'; //Credit Memos
            $attributes['user_id'] = Auth::user()->id;
            $attributes['org_id'] = Auth::user()->org_id;
            $attributes['number'] = $creditnote->id;
            $attributes['date'] = \Carbon\Carbon::today();
            $attributes['dr_total'] = $creditnote->total_amount;
            $attributes['cr_total'] = $creditnote->total_amount;
            $attributes['source'] = 'AUTO_CN';
            $entry = \App\Models\Entry::find($creditnote->entry_id);
            // $entry->update($attributes);

            // Creddited to Customer or Interest or eq ledger
            $sub_amount = \App\Models\Entryitem::where('entry_id', $creditnote->entry_id)->where('dc', 'C')->first();
            $sub_amount->entry_id = $entry->id;
            $sub_amount->user_id = \Auth::user()->id;
            $sub_amount->org_id = \Auth::user()->org_id;
            $sub_amount->dc = 'C';
            $sub_amount->ledger_id = \App\Models\Client::find($creditnote->client_id)->ledger_id; //Client ledger
            $sub_amount->amount = $creditnote->total_amount;
            $sub_amount->narration = 'Credit Note'; //$request->user_id
            //dd($sub_amount);
            $sub_amount->update();

            // Debitte to Bank or cash account that we are already in
            $cash_amount = \App\Models\Entryitem::where('entry_id', $creditnote->entry_id)->where('dc', 'C')->first();
            $cash_amount->entry_id = $entry->id;
            $cash_amount->user_id = \Auth::user()->id;
            $cash_amount->org_id = \Auth::user()->org_id;
            $cash_amount->dc = 'C';
            $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('SALES_TAX_LEDGER'); // Purchase ledger if selected or ledgers from .env
            // dd($cash_amount);
            $cash_amount->amount = $creditnote->tax_amount;
            $cash_amount->narration = 'Credit Note';
            $cash_amount->update();

            // Debitte to Bank or cash account that we are already in
            $cash_amount = \App\Models\Entryitem::where('entry_id', $creditnote->entry_id)->where('dc', 'D')->first();
            $cash_amount->entry_id = $entry->id;
            $cash_amount->user_id = \Auth::user()->id;
            $cash_amount->org_id = \Auth::user()->org_id;
            $cash_amount->dc = 'D';
            $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('SALES_LEDGER_ID'); // Purchase ledger if selected or ledgers from .env
            // dd($cash_amount);
            $cash_amount->amount = $creditnote->taxable_amount;
            $cash_amount->narration = 'Credit Note';
            $cash_amount->update();
        } else {

            //dd(\App\Models\Client::find($creditnote->client_id)->ledger_id);

            //create the new entry items
            $attributes['entrytype_id'] = \FinanceHelper::get_entry_type_id('creditnote'); //Credit Notes
            $attributes['tag_id'] = '3'; //Credit Memos
            $attributes['user_id'] = \Auth::user()->id;
            $attributes['org_id'] = \Auth::user()->org_id;
            $attributes['number'] = $creditnote->id;
            $attributes['date'] = \Carbon\Carbon::today();
            $attributes['dr_total'] = $creditnote->total_amount;
            $attributes['cr_total'] = $creditnote->total_amount;
            $attributes['source'] = 'AUTO_CN';
            $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
            $entry = \App\Models\Entry::create($attributes);

            // Creddited to Customer or Interest or eq ledger
            $sub_amount = new \App\Models\Entryitem();
            $sub_amount->entry_id = $entry->id;
            $sub_amount->user_id = \Auth::user()->id;
            $sub_amount->org_id = \Auth::user()->org_id;
            $sub_amount->dc = 'C';
            $sub_amount->ledger_id = \App\Models\Client::find($creditnote->client_id)->ledger_id; //Client ledger
            $sub_amount->amount = $creditnote->total_amount;
            $sub_amount->narration = 'Credit Note'; //$request->user_id
            //dd($sub_amount);
            $sub_amount->save();

            // Debitte to Bank or cash account that we are already in
            $cash_amount = new \App\Models\Entryitem();
            $cash_amount->entry_id = $entry->id;
            $cash_amount->user_id = \Auth::user()->id;
            $cash_amount->org_id = \Auth::user()->org_id;
            $cash_amount->dc = 'D';
            $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('SALES_TAX_LEDGER'); // Purchase ledger if selected or ledgers from .env
            // dd($cash_amount);
            $cash_amount->amount = $creditnote->tax_amount;
            $cash_amount->narration = 'Credit Note';
            $cash_amount->save();

            // Debitte to Bank or cash account that we are already in

            $cash_amount = new \App\Models\Entryitem();
            $cash_amount->entry_id = $entry->id;
            $cash_amount->user_id = \Auth::user()->id;
            $cash_amount->org_id = \Auth::user()->org_id;
            $cash_amount->dc = 'D';
            $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('SALES_LEDGER_ID'); // Sales ledger if selected or ledgers from .env
            // dd($cash_amount);
            $cash_amount->amount = $creditnote->taxable_amount;
            $cash_amount->narration = 'Credit Note';
            $cash_amount->save();

            //now update entry_id in income row
            $creditnote->update(['entry_id'=>$entry->id]);
        }

        return 0;
    }

    public function getInvoiceId()
    {
        $term = strtolower(Request::get('term'));
        $salesinvoice = \App\Models\Orders::where('order_type', 'proforma_invoice')->select('id')->where('id', 'LIKE', '%'.$term.'%')->take(5)->get();
        $return_array = [];

        foreach ($salesinvoice as $v) {
            $return_array[] = ['value' =>sprintf('%08d', $v->id), 'id' =>$v->id];
        }

        return Response::json($return_array);
    }
public function getbillinfo(Request $request){
    $order = \App\Models\Invoice::where('bill_no', \Request::get('bill_no'))->where('fiscal_year', \Request::get('fiscal_year'))->first();
    return $order;
}
    public function getInvoiceInfo(Request $request)
    {
        $invoiceinfo = \App\Models\Orders::find($request->salesinvoice_id);

        if ($invoice->source == 'lead') {
            $customer_name = \App\Models\Lead::find($invoiceinfo->client_id)->name;
        } else {
            $customer_name = \App\Models\Client::find($invoiceinfo->client_id)->name;
        }

        // dd($customer_name);
        $invoicedetailinfo = \App\Models\OrderDetail::where('order_id', $invoiceinfo->id)->get();

        $products = Product::select('id', 'name')->get();
        $data = '';

        foreach ($invoicedetailinfo as $idi) {
            if ($idi->is_inventory == 1) {
                $name = \App\Models\Product::find($idi->product_id)->name;

                $data .= '<tr>  
                                <td>
                                <input type="text" class="form-control product_id" name="product"  value="'.$name.'" readonly>
                                  <input type="hidden"  name="product_id[]" value="'.$idi->product_id.'" required="required" readonly>
                                
                                   
                                </td>
                                <td>
                                    <input type="text" class="form-control invoice_price" name="price[]" placeholder="Fare" value="'.$idi->price.'" required="required" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control invoice_quantity" name="quantity[]" placeholder="Quantity" min="1" value="'.$idi->quantity.'" required="required" readonly>
                                </td>
                               

                                <td>
                                    <input type="number" class="form-control returnable" name="returnable[]" placeholder="Returnable" min="1" value="'.$idi->quantity.'" required="required" readonly>
                                </td>

                                <td>
                                    <input type="number" class="form-control quantity" name="credit_qty[]" placeholder="Credit Qty" min="1" value="'.$idi->quantity.'" required="required" >
                                </td>
                                <td>
                                    <input type="number" class="form-control price" name="credit_price[]" placeholder="Credit Price" min="1" value="'.$idi->price.'" required="required" >
                                </td>

                                 <td>
                                     <input type="number" class="form-control total" name="credit_total[]" placeholder="Total" value="'.$idi->total.'" readonly="readonly">
                                </td>

                                <td>
                                    <input type="text" class="form-control reason" name="reason[]" placeholder="Reason" value=""style="float:left; width:80%;">
                                    <a href="javascript::void(1);" style="width: 10%;">
                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                    </a>
                                </td>
                            </tr>';
            } elseif ($idi->is_inventory == 0) {
                $data .= '<tr>
                                <td>
                                  <input type="text" class="form-control product" name="custom_items_name[]" value="'.$idi->description.'" placeholder="Product" readonly>
                                </td>
                                 
                                <td>
                                    <input type="text" class="form-control invoice_price" name="custom_items_price[]" placeholder="Price" value="'.$idi->price.'" required="required" readonly>
                                </td>
                               
                                <td>
                                    <input type="number" class="form-control invoice_quantity" name="custom_items_qty[]" placeholder="Quantity" min="1" value="'.$idi->quantity.'" required="required" readonly>
                                </td>

                                <td>
                                    <input type="number" class="form-control returnable" name="custom_returnable[]" placeholder="Returnable" min="1" value="'.$idi->quantity.'" required="required" readonly>
                                </td>

                                <td>
                                    <input type="number" class="form-control quantity" name="custom_credit_qty[]" placeholder="Credit Qty" min="1" value="'.$idi->quantity.'" required="required" >
                                </td>

                                <td>
                                    <input type="number" class="form-control price" name="custom_credit_price[]" placeholder="Credit Price" min="1" value="'.$idi->price.'" required="required" >
                                </td>
                                <td>
                                 <input type="number" class="form-control total" name="custom_credit_total[]" placeholder="Total" value="'.$idi->total.'" readonly="readonly" >
                                 </td>

                                 
                                <td>
                                    <input type="text" class="form-control reason" name="custom_reason[]" placeholder="Reason" value="" style="float:left; width:80%;">
                                    <a href="javascript::void(1);" style="width: 10%;">
                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                    </a>
                                </td>
                            </tr>';
            }
        }

        return ['invoiceinfo'=>$invoiceinfo, 'invoicedetailinfo'=>$data, 'customer_name'=>$customer_name];
    }
}
