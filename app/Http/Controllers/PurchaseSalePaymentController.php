<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Audit as Audit;
use App\Models\Department;
use App\Models\EmployeeAward;
use App\Models\EmployeePayroll;
use App\Models\Paymentmethod;
use App\Models\Role as Permission;
use App\Models\SalaryAllowance;
use App\Models\SalaryDeduction;
use App\Models\SalaryPayment;
use App\Models\SalaryPaymentAllowance;
use App\Models\SalaryPaymentDeduction;
use App\Models\SalaryPaymentDetail;
use App\Models\SalaryPayslip;
use App\Models\SalaryTemplate;
use App\User;
use Auth;
use DB;
use Flash;
use Illuminate\Http\Request;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class PurchaseSalePaymentController extends Controller
{
    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
    }

    public function PurchasePaymentlist(Request $request, $id)
    {
        $purchase_id = $id;
        $purchase_detail = \App\Models\PurchaseOrder::find($id);
        $purchase_name = $purchase_detail->client->name ?? ''; 

        // dd($purchase_name);

        $payment_list = \App\Models\Payment::where('purchase_id', $id)->orderby('id', 'desc')->get();


        $page_title = 'Purchase Payment List';

        $page_description = 'Payment List Of ' . $purchase_name . '';

        return view('admin.purchase.paymentlist', compact('page_title', 'page_description', 'purchase_id', 'payment_list'));
    }

    public function SalePaymentlist(Request $request, $id)
    {
        $purchase_id = $id;
        $payment_list = \App\Models\Payment::where('sale_id', $id)->orderby('id', 'desc')->get();

        $order_detail = \App\Models\Orders::find($id);

        $lead_name = $order_detail->lead->name;

        $page_title = 'Sales Payment List';

        $page_description = 'Payment List Of ' . $lead_name . ' Order No ' . $id . '';

        return view('admin.orders.paymentlist', compact('page_title', 'page_description', 'purchase_id', 'payment_list'));
    }

    public function PurchasePaymentcreate(Request $request, $id)
    {
        $page_title = 'Purchase Payment Create';
        $page_description = 'Create payments of purchase TDS 1.5% for VAT 15% for PAN (TDS is mainly used for services only)';
        $purchase_id = $id;

        $payment_method = Paymentmethod::orderby('id')->pluck('name', 'id');

        $purchase_order = \App\Models\PurchaseOrder::where('id', $id)->first();
        $purchase_total = $purchase_order->total;
        $paid_amount = DB::table('payments')->where('purchase_id', $id)->sum('amount');
        $payment_remain = $purchase_total - $paid_amount;

        return view('admin.purchase.paymentcreate', compact('page_title', 'payment_remain', 'page_description', 'purchase_id', 'payment_method','purchase_order'));
    }

    public function SalePaymentcreate(Request $request, $id)
    {
        $page_title = 'Sale Payment Create';
        $page_description = 'create payments of purchase';
        $sale_id = $id;

        $payment_method = Paymentmethod::orderby('id')->pluck('name', 'id');

        $purchase_order = \App\Models\Orders::where('id', $id)->first();
        if(!$purchase_order){

            Flash::error("Purchase Not found");
            return redirect()->back();
        }

        $purchase_total = $purchase_order->total_amount;
        $source = $purchase_order->source;
        $paid_amount = DB::table('payments')->where('sale_id', $id)->sum('amount');
        $payment_remain = $purchase_total - $paid_amount;

        return view('admin.orders.paymentcreate', compact('page_title', 'page_description', 'sale_id', 'payment_method', 'payment_remain', 'source','purchase_order'));
    }
    public function creatOrUpdatePuchPaymentEntries($payments,$paid_amount,$purchase_order,$request){
        $attributes['entrytype_id'] = '2'; //receipt
        $attributes['tag_id'] = '24'; //bill payment
        $attributes['user_id'] = \Auth::user()->id;
        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['number'] = $payments->id;
        $attributes['date'] = \Carbon\Carbon::today();
        $attributes['dr_total'] = $paid_amount;
        $attributes['cr_total'] = $paid_amount;
        $attributes['source'] = 'Auto Payment';
        $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
        $entry = \App\Models\Entry::find($payments->entry_id);

        if($entry){ //updateEntrues
          
            $entry = $entry->update($attributes);
            $entry = \App\Models\Entry::find($payments->entry_id);
        }else{

           $entry = \App\Models\Entry::create($attributes);
           $payments = \App\Models\Payment::find($payments->id);
           $payments->update(['entry_id'=>$entry->id]);
        }
        \App\Models\Entryitem::where('entry_id',$payments->entry_id)->delete(); 

             //Purchase account
        $sub_amount =new  \App\Models\Entryitem();
        $sub_amount->entry_id = $entry->id;
        $sub_amount->user_id = \Auth::user()->id;
        $sub_amount->org_id = \Auth::user()->org_id;
        $sub_amount->dc = 'D';
        $sub_amount->ledger_id = $purchase_order->client->ledger_id ?? null; //Material Purchased Ledger
        $sub_amount->amount = $paid_amount;
        $sub_amount->narration = 'Order Receipts';
        $sub_amount->save();

        $creditTds = $request->tds;
        $createPaymentWithTds =(float) $paid_amount - (float)$creditTds;
      
        // cash account
        $cash_amount = new \App\Models\Entryitem();
        $cash_amount->entry_id = $entry->id;
        $cash_amount->user_id = \Auth::user()->id;
        $cash_amount->org_id = \Auth::user()->org_id;
        $cash_amount->dc = 'C';
        $cash_amount->ledger_id = $request->payment_method; //
        $cash_amount->amount = $createPaymentWithTds;
        $cash_amount->narration = 'being payment made';
        $cash_amount->save();

        $cash_amount = new \App\Models\Entryitem();
        $cash_amount->entry_id = $entry->id;
        $cash_amount->user_id = \Auth::user()->id;
        $cash_amount->org_id = \Auth::user()->org_id;
        $cash_amount->dc = 'C';
        $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('TDS_LEDGER'); //
        $cash_amount->amount = $creditTds;
        $cash_amount->narration = 'Tds amount added';
        $cash_amount->save();

        return 0;

    }




    public function PurchasePaymentPost(Request $request, $id)
    {
        $attributes = $request->all();

        $attributes['paid_by'] = $request->payment_method;

        $attributes['created_by'] = \Auth::user()->id;



        $attributes['bill_amount'] = (float) $request->amount - (float) $request->tds; 

        //this amount will be used to adjust bill amount

        if ($request->file('attachment')) {
            $stamp = time();
            $file = $request->file('attachment');
            //dd($file);
            $destinationPath = public_path() . '/attachment/';
            $filename = $file->getClientOriginalName();
            $request->file('attachment')->move($destinationPath, $stamp . '_' . $filename);

            $attributes['attachment'] = $stamp . '_' . $filename;
        }

        $payment = \App\Models\Payment::create($attributes);

        $paid_amount = DB::table('payments')->where('purchase_id', $id)->sum('amount');

        $purchase_order = \App\Models\PurchaseOrder::find($id);

        if ($paid_amount >= $purchase_order->total) {
            $attributes_purchase['payment_status'] = 'Paid';
            $purchase_order->update($attributes_purchase);
        } elseif ($paid_amount <= $purchase_order->total && $paid_amount > 0) {
            $attributes_purchase['payment_status'] = 'Partial';
            $purchase_order->update($attributes_purchase);
        } else {
            $attributes_purchase['payment_status'] = 'Pending';
            $purchase_order->update($attributes_purchase);
        }

        $paid_amount = $request->amount;

        $this->creatOrUpdatePuchPaymentEntries($payment,$paid_amount,$purchase_order,$request);
   




        Flash::success('Payment Created');

        return redirect('/admin/payment/purchase/' . $id . '');
    }

    public function SalePaymentPost(Request $request, $id)
    {
        $attributes = $request->all();
        $sale_order = \App\Models\Orders::find($id);
        // dd($sale_order->source);
        if (trim($sale_order->order_type) == '' || trim($sale_order->source) == '') {
            Flash::error('Order Not Valid');

            return \Redirect::back();
        } elseif (trim($sale_order->order_type) == 'quotation' && $sale_order->source == 'lead') {
            $client = $this->convertToClients($sale_order->client_id);
            $attributes_purchase['client_id'] = $client;
            $ledger_entry = $this->postLedger($sale_order->id, $client);
        } elseif (trim($sale_order->order_type) == 'quotation') {
            $ledger_entry = $this->postLedger($sale_order->id, $sale_order->client_id);
        }

        $attributes_purchase['source'] = 'client';
        $attributes_purchase['order_type'] = 'proforma_invoice'; //always makes invoice

        $attributes['created_by'] = \Auth::user()->id;

        if ($request->file('attachment')) {
            $stamp = time();
            $file = $request->file('attachment');
            //dd($file);
            $destinationPath = public_path() . '/attachment/';
            $filename = $file->getClientOriginalName();
            $request->file('attachment')->move($destinationPath, $stamp . '_' . $filename);

            $attributes['attachment'] = $stamp . '_' . $filename;
        }
        // dd($attributes);
        \App\Models\Payment::create($attributes);

        $paid_amount = DB::table('payments')->where('sale_id', $id)->sum('amount');

        if ($paid_amount >= $sale_order->total_amount) {
            $attributes_purchase['payment_status'] = 'Paid';
            $sale_order->update($attributes_purchase);
        } elseif ($paid_amount <= $sale_order->total_amount && $paid_amount > 0) {
            $attributes_purchase['payment_status'] = 'Partial';
            $sale_order->update($attributes_purchase);
        } else {
            $attributes_purchase['payment_status'] = 'Pending';
            $sale_order->update($attributes_purchase);
        }

        //Now insert value in ledger
        //Cash account -> credit &&& sales account -> debit
        //add values in entries and entryitems tables
        //SAVE IN ENTRIES
        $attributes['entrytype_id'] = '1'; //receipt
        $attributes['tag_id'] = '8'; //revenue
        $attributes['user_id'] = \Auth::user()->id;
        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['number'] = $id;
        $attributes['date'] = \Carbon\Carbon::today();
        $attributes['dr_total'] = $paid_amount;
        $attributes['cr_total'] = $paid_amount;
        $attributes['source'] = 'Auto Orders';
        $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
        $entry = \App\Models\Entry::create($attributes);

        //Sales account
        $sub_amount = new \App\Models\Entryitem();
        $sub_amount->entry_id = $entry->id;
        $sub_amount->user_id = \Auth::user()->id;
        $sub_amount->org_id = \Auth::user()->org_id;
        $sub_amount->dc = 'C';
        $sub_amount->ledger_id = $sale_order->client->ledger_id;
        $sub_amount->amount = $paid_amount;
        $sub_amount->narration = 'Order Receipt Made';
        $sub_amount->save();

        // cash account
        $cash_amount = new \App\Models\Entryitem();
        $cash_amount->entry_id = $entry->id;
        $cash_amount->user_id = \Auth::user()->id;
        $cash_amount->org_id = \Auth::user()->org_id;
        $cash_amount->dc = 'D';
        $cash_amount->ledger_id = $request->payment_method; //
        $cash_amount->amount = $paid_amount;
        $cash_amount->narration = 'being sales made';
        $cash_amount->save();

        Flash::success('Payment Created');

        return redirect('/admin/payment/orders/' . $id . '');
    }

    public function PurchasePaymentedit(Request $request, $id, $payment_id)
    {
        $page_title = 'Purchase Payment Edit';
        $page_description = 'Edit payments of purchase TDS 1.5% for VAT 15% for PAN';
        $purchase_id = $id;
        $payments = \App\Models\Payment::where('id', $payment_id)->first();
        $payment_method = Paymentmethod::orderby('id')->pluck('name', 'id');

        return view('admin.purchase.paymentedit', compact('page_title', 'page_description', 'purchase_id', 'payment_method', 'payments'));
    }

    public function SalePaymentedit(Request $request, $id, $payment_id)
    {
        $page_title = 'Sales Payment Edit';
        $page_description = 'create payments of sales';
        $sale_id = $id;

        $payments = \App\Models\Payment::where('id', $payment_id)->first();
        $payment_method = Paymentmethod::orderby('id')->pluck('name', 'id');

        return view('admin.orders.paymentedit', compact('page_title', 'page_description', 'sale_id', 'payment_method', 'payments'));
    }

    public function PurchasePaymentUpdate(Request $request, $id, $payment_id)
    {
        $payment = \App\Models\Payment::find($payment_id);

        //dd($payment);
        $attributes = $request->all();
        $attributes['paid_by'] = $request->payment_method;
        $attributes['created_by'] = \Auth::user()->id;
        $attributes['bill_amount'] = $request->amount - ($request->tds ?? $payment->tds) ;
        if ($request->file('attachment')) {
            $stamp = time();
            $file = $request->file('attachment');
            //dd($file);
            $destinationPath = public_path() . '/attachment/';
            $filename = $file->getClientOriginalName();
            $request->file('attachment')->move($destinationPath, $stamp . '_' . $filename);

            $attributes['attachment'] = $stamp . '_' . $filename;
        }
       
        $payment->update($attributes);

        $paid_amount = DB::table('payments')->where('purchase_id', $id)->sum('amount');

        $purchase_order = \App\Models\PurchaseOrder::find($id);

        if ($paid_amount >= $purchase_order->total) {
            $attributes_purchase['payment_status'] = 'Paid';
            $purchase_order->update($attributes_purchase);
        } elseif ($paid_amount <= $purchase_order->total && $paid_amount > 0) {
            $attributes_purchase['payment_status'] = 'Partial';
            $purchase_order->update($attributes_purchase);
        } else {
            $attributes_purchase['payment_status'] = 'Pending';
            $purchase_order->update($attributes_purchase);
        }

        $paid_amount = $request->amount;
        
        $this->creatOrUpdatePuchPaymentEntries($payment,$paid_amount,$purchase_order,$request);

        Flash::success('Payment Updated Successfully');

        return redirect('/admin/payment/purchase/' . $id . '');
    }

    public function SalePaymentUpdate(Request $request, $id, $payment_id)
    {
        $payment = \App\Models\Payment::find($payment_id);

        //dd($payment);

        $attributes = $request->all();
        $attributes['created_by'] = \Auth::user()->id;

        if ($request->file('attachment')) {
            $stamp = time();
            $file = $request->file('attachment');
            //dd($file);
            $destinationPath = public_path() . '/attachment/';
            $filename = $file->getClientOriginalName();
            $request->file('attachment')->move($destinationPath, $stamp . '_' . $filename);

            $attributes['attachment'] = $stamp . '_' . $filename;
        }

        $payment->update($attributes);

        $paid_amount = DB::table('payments')->where('sale_id', $id)->sum('amount');

        $sale_order = \App\Models\Orders::find($id);

        if ($paid_amount >= $sale_order->total_amount) {
            $attributes_purchase['payment_status'] = 'Paid';
            $sale_order->update($attributes_purchase);
        } elseif ($paid_amount <= $sale_order->total_amount && $paid_amount > 0) {
            $attributes_purchase['payment_status'] = 'Partial';
            $sale_order->update($attributes_purchase);
        } else {
            $attributes_purchase['payment_status'] = 'Pending';
            $sale_order->update($attributes_purchase);
        }

        Flash::success('Payment Updated Successfully');

        return redirect('/admin/payment/orders/' . $id . '');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $payment = \App\Models\Payment::find($id);

        if (!$payment->isdeletable()) {
            abort(403);
        }

        $payment->delete();

        Flash::success('Payment successfully deleted.');

        return redirect()->back();
    }

    public function destroySalePayment($id)
    {
        $payment = \App\Models\Payment::find($id);

        if (!$payment->isdeletable()) {
            abort(403);
        }

        $payment->delete();

        Flash::success('Payment successfully deleted.');

        return redirect()->back();
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

        $payment = \App\Models\Payment::find($id);

        if (!$payment->isdeletable()) {
            abort(403);
        }
        $modal_title = 'Delete Payment';
        $payment = \App\Models\Payment::find($id);
        $modal_route = route('admin.payment.purchase.orders.delete', ['id' => $payment->id]);
        $modal_body = 'Are you sure you want to delete this Payment?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * Delete Confirm.
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalDeleteSalePayment($id)
    {
        $error = null;

        $payment = \App\Models\Payment::find($id);

        if (!$payment->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Payment';
        $payment = \App\Models\Payment::find($id);
        $modal_route = route('admin.payment.purchase.orders.delete', ['id' => $payment->id]);
        $modal_body = 'Are you sure you want to delete this Payment?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function ajaxPurchaseStatus(Request $request)
    {
        $purchase_order = \App\Models\PurchaseOrder::find($request->id);

        // dd($purchase_order);

        $attributes['status'] = $request->purchase_status;

        $purchase_order->update($attributes);

        return ['status' => 1];
    }

    public function ajaxSaleStatus(Request $request)
    {
        $sale_order = \App\Models\Orders::find($request->id);

        $attributes['status'] = $request->purchase_status;

        $sale_order->update($attributes);

        return ['status' => 1];
    }

    private function convertToClients($id)
    {
        $leads = \App\Models\lead::find($id);
        if ($leads->company->name) {
            $clients_name = $leads->name;
        } else {
            $clients_name = $leads->name;
        }

        $clients = [
            'org_id' => \Auth::user()->id,
            'name' => $clients_name,
            'phone' => $leads->mob_phone ?? '',
            'email' => $leads->email,
            'type' => $leads->leadType->name,
            'enabled' => '1',
        ];
        $client = \App\Models\client::create($clients);
        $id = $client->id;
        $full_name = $client->name;
        $_ledgers = \TaskHelper::PostLedgers($full_name, \FinanceHelper::get_ledger_id('CUSTOMER_LEDGER_GROUP'));
        $attributes['ledger_id'] = $_ledgers;
        $client->update($attributes);
        $leads->update(['moved_to_client' => '1']);
        Audit::log(Auth::user()->id, trans('admin/clients/general.audit-log.category'), trans('admin/clients/general.audit-log.msg-store', ['name' => $client->name]));

        return $id;
    }

    private function postLedger($ord_id, $client_id)
    {
        $order = \App\Models\Orders::find($ord_id);

        $entry = \App\Models\Entry::create([
            'tag_id' => '6',
            'entrytype_id' => '5',
            'number' => $order->id,
            'org_id' => \Auth::user()->org_id,
            'user_id' => \Auth::user()->id,
            'date' => date('Y-m-d'),
            'dr_total' => $order->total_amount,
            'cr_total' => $order->total_amount,
            'fiscal_year_id' => \FinanceHelper::cur_fisc_yr()->id,
            'source' => 'Auto Order Invoice',
        ]);

        $clients = \App\Models\Client::find($client_id);

        //send total to sales ledger
        $entry_item = \App\Models\Entryitem::create([
            'entry_id' => $entry->id,
            'dc' => 'C',
            'ledger_id' => \FinanceHelper::get_ledger_id('SALES_LEDGER_ID'), //Sales Ledger
            'amount' => $order->total_amount,
            'narration' => 'Sales being made',
        ]);
        //send amount before tax to customer ledger

        $entry_item = \App\Models\Entryitem::create([
            'entry_id' => $entry->id,
            'dc' => 'D',
            'ledger_id' => $clients->ledger_id,
            'amount' => $order->taxable_amount,
            'narration' => 'Sales being made',
        ]);

        //send the taxable amount to SALES TAX LEDGER
        $entry_item = \App\Models\Entryitem::create([
            'entry_id' => $entry->id,
            'dc' => 'D',
            'ledger_id' => \FinanceHelper::get_ledger_id('SALES_TAX_LEDGER'), //Sales Tax Ledger
            'amount' => $order->tax_amount,
            'narration' => 'Tax to pay',
        ]);

        $order->update(['entry_id' => $entry->id]);

        return 0;
    }
}