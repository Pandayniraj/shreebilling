<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\MasterComments;
use App\Models\OrderDetail;
use App\Models\Orders;
use App\Models\Product;
use App\Models\Role as Permission;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\SalesPayment;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class SalesAccountController extends Controller
{
    /**
     * @var Client
     */
    private $salesOrder;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $bug
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Permission $permission, SalesPayment $payment, SalesOrder $salesOrder)
    {
        parent::__construct();
        $this->permission = $permission;
        $this->salesOrder = $salesOrder;
        $this->payment = $payment;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function invoiceindex()
    {
        $page_title = 'Sales Invoice';
        $page_description = 'Manage Invoice';
        $orders = SalesOrder::orderBy('order_no', 'DESC')->where('trans_type', '202')->get();

        return view('admin.salesaccount.invoice_index', compact('orders', 'page_title', 'page_description'));
    }

    public function paymentindex()
    {
        $orders = SalesOrder::orderBy('order_no', 'DESC')->where('trans_type', '202')->get();
        $page_title = 'Payment';
        $page_description = 'Manage Payment';
        $paymentlist = SalesPayment::orderby('payment_date', 'desc')->get();

        //   dd($paymentlist);

        return view('admin.salesaccount.payment_index', compact('orders', 'page_title', 'page_description', 'paymentlist'));
    }

    public function quotationindex()
    {
        $quotation = SalesOrder::orderBy('order_no', 'DESC')->where('invoice_type', 'directOrder')->get();
        //dd($quotation);
        $page_title = 'Quotation ';
        $page_description = 'Manage Quotation';
        //$orderData = $this->order->getAllSalseOrderQuotation(NULL, NULL, NULL, NULL);
        //  dd($orderData);

        return view('admin.salesaccount.quotation_index', compact('quotation', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function invoiceShow($orderNo, $invoiceNo)
    {
        //    $ord = SalesOrder::find($id);
        $saleDataOrder = SalesOrder::where('order_no', '=', $orderNo)->first();
        $saleDataInvoice = SalesOrder::where('order_no', '=', $invoiceNo)->first();
        $invoiceType = $saleDataInvoice->invoice_type;
        $salesDetails = SalesOrderDetail::orderBy('id', 'desc')->where('order_no', $invoiceNo)->get();
        $page_title = 'Invoice';
        $page_description = 'View Invoice';
        //  $orderDetails = OrderDetail::where('order_id', $id)->get();

        return view('admin.salesaccount.invoice_show', compact('saleDataOrder', 'invoiceType', 'orderNo', 'invoiceNo', 'salesDetails', 'saleDataInvoice', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function paymentShow($orderNo, $invoiceNo)
    {
        $ord = SalesPayment::find($id);
        $page_title = 'Payment';
        $page_description = 'View Payment';
        $orderDetails = OrderDetail::where('order_id', $id)->get();

        return view('admin.orders.show', compact('ord', 'page_title', 'page_description', 'orderDetails'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function quotationShow($id)
    {
        //    $ord = SalesOrder::find($id);
        $saleDataOrder = SalesOrder::where('order_no', '=', $id)->first();
        //  dd($saleDataOrder);
        $salesDetails = SalesOrderDetail::orderBy('id', 'desc')->where('order_no', $id)->get();
        //  dd($salesDetails);
        $page_title = 'Quotation';
        $page_description = 'View Quotation';
        //  $orderDetails = OrderDetail::where('order_id', $id)->get();

        return view('admin.salesaccount.quotation_show', compact('saleDataOrder', 'page_title', 'page_description', 'salesDetails'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function invoicecreate()
    {
        $page_title = 'Sales Invoice';
        $page_description = 'Add Invoice';
        $order = null;
        $orderDetail = null;
        $products = Product::select('id', 'name')->get();
        $clients = Client::select('id', 'name', 'location')->orderBy('id', DESC)->get();
        $leads = \App\Models\Lead::orderBy('id', 'desc')->get();
        $invoice_count = DB::table('sales_orders')->where('trans_type', SALESINVOICE)->count();
        if ($invoice_count > 0) {
            $invoiceReference = DB::table('sales_orders')->where('trans_type', SALESINVOICE)->select('reference')->orderBy('order_no', 'DESC')->first();
            // dd($invoiceReference);

            $ref = explode('-', $invoiceReference->reference);
            // dd($ref);
            $invoice_count = (int) $ref[1];
        //dd($invoice_count);
        } else {
            $invoice_count = 0;
            //dd($data);
        }

        return view('admin.salesaccount.invoice_create', compact('page_title', 'leads', 'invoice_count', 'page_description', 'order', 'orderDetail', 'products', 'clients'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function paymentcreate($orderNo, $invoiceNo)
    {
        $page_title = 'Payment';
        $page_description = 'Create Payment';
        $order = null;
        $orderDetail = null;
        $products = Product::select('id', 'name')->get();
        $clients = Client::select('id', 'name', 'location')->orderBy('id', DESC)->get();
        $leads = \App\Models\Lead::orderBy('id', 'desc')->get();

        $saleDataInvoice = SalesOrder::where('order_no', '=', $invoiceNo)->first();
        //  dd($saleDataInvoice);

        $saleDataOrder = SalesOrder::where('order_no', '=', $orderNo)->first();
        //  dd($saleDataOrder);

        return view('admin.salesaccount.payment_create', compact('page_title', 'orderNo', 'invoiceNo', 'saleDataInvoice', 'saleDataOrder', 'page_description', 'leads', 'order', 'orderDetail', 'products', 'clients'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function quotationcreate()
    {
        $page_title = 'Quotation';
        $page_description = 'Add Quotation';
        $order = null;
        $orderDetail = null;
        $products = Product::select('id', 'name')->get();
        $clients = Client::select('id', 'name', 'location')->orderBy('id', DESC)->get();
        $leads = \App\Models\Lead::orderBy('id', 'desc')->get();

        // d($data['salesType'],1);
        $order_count = DB::table('sales_orders')->where('trans_type', SALESORDER)->count();

        if ($order_count > 0) {
            $orderReference = DB::table('sales_orders')->where('trans_type', SALESORDER)->select('reference')->orderBy('order_no', 'DESC')->first();
            $ref = explode('-', $orderReference->reference);
            $order_count = (int) $ref[1];
        } else {
            $order_count = 0;
        }

        return view('admin.salesaccount.quotation_create', compact('page_title', 'order_count', 'page_description', 'order', 'leads', 'orderDetail', 'products', 'clients'));
    }

    public function invoicestore(Request $request)
    {
        $userId = Auth::user()->id;
        $ord_date = \Carbon\Carbon::parse($request->ord_date);

        $this->validate($request, []);

        $itemQuantity = $request->item_quantity;
        $itemIds = $request->item_id;
        $itemDiscount = $request->discount;
        $taxIds = $request->tax_id;
        $unitPrice = $request->total;
        $stock_id = $request->stock_id;
        $description = $request->description;
        // create salesOrder start
        $orderReferenceNo = DB::table('sales_orders')->where('trans_type', SALESORDER)->count();

        if ($orderReferenceNo > 0) {
            $orderReference = DB::table('sales_orders')->where('trans_type', SALESORDER)->select('reference')->orderBy('order_no', 'DESC')->first();
            $ref = explode('-', $orderReference->reference);
            $orderCount = (int) $ref[1];
        } else {
            $orderCount = 0;
        }

        $salesOrder['lead_id'] = $request->lead_id;
        $salesOrder['branch_id'] = $request->lead_id;
        $salesOrder['payment_id'] = $request->payment_id;
        $salesOrder['user_id'] = $userId;
        $salesOrder['reference'] = 'SO-'.sprintf('%04d', $orderCount + 1);
        $salesOrder['comments'] = $request->comments;
        $salesOrder['trans_type'] = SALESORDER;
        $salesOrder['invoice_type'] = 'indirectOrder';
        $salesOrder['ord_date'] = $ord_date;
        $salesOrder['from_stk_loc'] = $request->from_stk_loc;
        $salesOrder['total'] = $request->final_total;
        $salesOrder['created_at'] = date('Y-m-d H:i:s');
        $salesOrderId = DB::table('sales_orders')->insertGetId($salesOrder);
        // create salesOrder end

        // Create salesOrder Invoice start
        $salesOrderInvoice['order_reference_id'] = $salesOrderId;
        $salesOrderInvoice['order_reference'] = $salesOrder['reference'];
        $salesOrderInvoice['trans_type'] = SALESINVOICE;
        $salesOrderInvoice['invoice_type'] = 'directInvoice';
        $salesOrderInvoice['reference'] = $request->reference;
        $salesOrderInvoice['lead_id'] = $request->lead_id;
        $salesOrderInvoice['branch_id'] = $request->lead_id;
        $salesOrderInvoice['payment_id'] = $request->payment_id;
        $salesOrderInvoice['user_id'] = $userId;
        $salesOrderInvoice['comments'] = $request->comments;
        $salesOrderInvoice['ord_date'] = $ord_date;
        $salesOrderInvoice['from_stk_loc'] = $request->from_stk_loc;
        $salesOrderInvoice['total'] = $request->final_total;
        $salesOrderInvoice['payment_term'] = $request->payment_term;
        $salesOrderInvoice['created_at'] = date('Y-m-d H:i:s');
        $orderInvoiceId = DB::table('sales_orders')->insertGetId($salesOrderInvoice);
        // Create salesOrder Invoice end

        // Inventory Items Start
        if (! empty($description)) {
            foreach ($description as $key => $item) {
                // create salesOrderDetail Start
                $salesOrderDetail['order_no'] = $salesOrderId;
                $salesOrderDetail['stock_id'] = $stock_id[$key];
                $salesOrderDetail['description'] = $item;
                $salesOrderDetail['quantity'] = $itemQuantity[$key];
                $salesOrderDetail['trans_type'] = SALESORDER;
                $salesOrderDetail['discount_percent'] = $itemDiscount[$key];
                $salesOrderDetail['tax_type_id'] = $taxIds[$key];
                $salesOrderDetail['unit_price'] = $unitPrice[$key];
                $salesOrderDetail['is_inventory'] = 1;
                DB::table('sales_order_details')->insertGetId($salesOrderDetail);

                // Create salesOrderDetailInvoice Start
                $salesOrderDetailInvoice['order_no'] = $orderInvoiceId;
                $salesOrderDetailInvoice['stock_id'] = $stock_id[$key];
                $salesOrderDetailInvoice['description'] = $description[$key];
                $salesOrderDetailInvoice['quantity'] = $itemQuantity[$key];
                $salesOrderDetailInvoice['trans_type'] = SALESINVOICE;
                $salesOrderDetailInvoice['discount_percent'] = $itemDiscount[$key];
                $salesOrderDetailInvoice['tax_type_id'] = $taxIds[$key];
                $salesOrderDetailInvoice['unit_price'] = $unitPrice[$key];
                $salesOrderDetailInvoice['is_inventory'] = 1;
                DB::table('sales_order_details')->insertGetId($salesOrderDetailInvoice);
                // Create salesOrderDetailInvoice End
            }
        }
        // Inventory Items End

        // Custom items
        $tax_id_custom = $request->tax_id_custom;
        $custom_items_discount = $request->custom_items_discount;
        $custom_items_name = $request->custom_items_name;
        $custom_items_rate = $request->custom_items_rate;
        $custom_items_qty = $request->custom_items_qty;
        $custom_items_amount = $request->custom_items_amount;
        // d($custom_items_name,1);
        if (! empty($custom_items_name)) {
            foreach ($custom_items_name as $key => $value) {
                // custom item order detail
                $itemsOrder['order_no'] = $salesOrderId;
                $itemsOrder['trans_type'] = SALESORDER;
                $itemsOrder['tax_type_id'] = $tax_id_custom[$key];
                $itemsOrder['discount_percent'] = $custom_items_discount[$key];
                $itemsOrder['description'] = $custom_items_name[$key];
                $itemsOrder['unit_price'] = $custom_items_amount[$key];
                $itemsOrder['quantity'] = $custom_items_qty[$key];
                $itemsOrder['is_inventory'] = 0;
                DB::table('sales_order_details')->insert($itemsOrder);
                // custom item invoice detail
                $itemsInvoice['order_no'] = $orderInvoiceId;
                $itemsInvoice['trans_type'] = SALESINVOICE;
                $itemsInvoice['tax_type_id'] = $tax_id_custom[$key];
                $itemsInvoice['discount_percent'] = $custom_items_discount[$key];
                $itemsInvoice['description'] = $custom_items_name[$key];
                $itemsInvoice['unit_price'] = $custom_items_amount[$key];
                $itemsInvoice['quantity'] = $custom_items_qty[$key];
                $itemsInvoice['is_inventory'] = 0;
                DB::table('sales_order_details')->insert($itemsInvoice);
            }
        }

        if (! empty($orderInvoiceId)) {
            Session::flash('success', trans('message.success.save_success'));

            return redirect('/admin/sales/list');
        }
    }

    public function paymentstore(Request $request)
    {
        $payment_date = \Carbon\Carbon::parse($request->payment_date);
        $userId = Auth::user()->id;
        $this->validate($request, []);
        // Transaction Table
        $data['account_no'] = $request->account_no;
        $data['trans_date'] = $payment_date;
        $data['description'] = $request->description;
        $data['amount'] = abs($request->amount);
        $data['category_id'] = $request->category_id;
        $data['reference'] = $request->reference;
        // dd($userId);
        $data['person_id'] = $userId;
        $data['trans_type'] = 'cash-in-by-sale';
        $data['payment_method'] = $request->payment_type_id;
        $data['created_at'] = date('Y-m-d H:i:s');
        $transactionId = DB::table('bank_trans')->insertGetId($data);
        // Payment Table
        $payment['transaction_id'] = $transactionId;
        $payment['invoice_reference'] = $request->invoice_reference;
        $payment['order_reference'] = $request->order_reference;
        $payment['payment_type_id'] = $request->payment_type_id;
        $payment['amount'] = abs($request->amount);
        $payment['payment_date'] = $payment_date;
        $payment['reference'] = $request->reference;
        $payment['person_id'] = $userId;
        $payment['customer_id'] = $request->customer_id;
        $orderNo = $request->order_no;
        $invoiceNo = $request->invoice_no;
        // dd($payment);
        $payment = DB::table('payment_history')->insertGetId($payment);
        if (! empty($payment)) {
            $paidAmount = $this->payment->updatePayment($request->invoice_reference, $request->amount);
            Session::flash('success', trans('message.success.save_success'));

            return redirect()->intended('/admin/invoice/show/'.$orderNo.'/'.$invoiceNo);
        }
    }

    public function quotationstore(Request $request)
    {
        $userId = Auth::user()->id;
        $ord_date = \Carbon\Carbon::parse($request->ord_date);
        $this->validate($request, []);
        $itemQuantity = $request->item_quantity;
        $itemIds = $request->item_id;
        $itemDiscount = $request->discount;
        $taxIds = $request->tax_id;
        $unitPrice = $request->unit_price;
        $description = $request->description;
        $stock_id = $request->stock_id;
        $item_quantity = $request->item_quantity;

        // create salesOrder
        $salesOrder['lead_id'] = $request->lead_id;
        $salesOrder['branch_id'] = $request->lead_id;
        $salesOrder['payment_id'] = $request->payment_id;
        $salesOrder['user_id'] = $userId;
        $salesOrder['reference'] = $request->reference;
        $salesOrder['comments'] = $request->comments;
        $salesOrder['trans_type'] = SALESORDER;
        $salesOrder['invoice_type'] = 'directOrder';
        $salesOrder['ord_date'] = $ord_date;
        $salesOrder['from_stk_loc'] = $request->from_stk_loc;
        $salesOrder['payment_term'] = 2;
        $salesOrder['total'] = $request->final_total;
        $salesOrder['created_at'] = date('Y-m-d H:i:s');
        // d($salesOrder,1);
        $salesOrderId = DB::table('sales_orders')->insertGetId($salesOrder);
        if (! empty($description)) {
            foreach ($description as $key => $item) {
                // create salesOrderDetail
                $salesOrderDetail['order_no'] = $salesOrderId;
                $salesOrderDetail['stock_id'] = $stock_id[$key];
                $salesOrderDetail['description'] = $description[$key];
                $salesOrderDetail['qty_sent'] = 0;
                $salesOrderDetail['quantity'] = $itemQuantity[$key];
                $salesOrderDetail['trans_type'] = SALESORDER;
                $salesOrderDetail['discount_percent'] = $itemDiscount[$key];
                $salesOrderDetail['tax_type_id'] = $taxIds[$key];
                $salesOrderDetail['unit_price'] = $unitPrice[$key];
                $salesOrderDetail['is_inventory'] = 1;

                DB::table('sales_order_details')->insertGetId($salesOrderDetail);
            }
        }
        // Custom items
        $tax_id_custom = $request->tax_id_custom;
        $custom_items_discount = $request->custom_items_discount;
        $custom_items_name = $request->custom_items_name;
        $custom_items_rate = $request->custom_items_rate;
        $custom_items_qty = $request->custom_items_qty;
        $custom_items_amount = $request->custom_items_amount;
        // d($custom_items_name,1);

        if (! empty($custom_items_name)) {
            foreach ($custom_items_name as $key => $value) {
                // custom item order detail
                $itemsOrder['order_no'] = $salesOrderId;
                $itemsOrder['trans_type'] = SALESORDER;
                $itemsOrder['tax_type_id'] = $tax_id_custom[$key];
                $itemsOrder['discount_percent'] = $custom_items_discount[$key];
                $itemsOrder['description'] = $custom_items_name[$key];
                $itemsOrder['unit_price'] = $custom_items_amount[$key];
                $itemsOrder['quantity'] = $custom_items_qty[$key];
                $itemsOrder['is_inventory'] = 0;
                DB::table('sales_order_details')->insert($itemsOrder);
            }
        }
        if (! empty($salesOrderId)) {
            Session::flash('success', trans('message.success.save_success'));

            return redirect()->intended('/admin/order/list');
        }
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function invoiceedit($id)
    {
        $page_title = 'Sales Invoice';
        $page_description = 'Edit Invoice';
        $order = SalesOrder::where('order_no', $id)->first();
        $orderDetails = SalesOrderDetail::where('order_no', $id)->get();
        $products = Product::select('id', 'name')->get();
        $clients = Client::select('id', 'name', 'location')->get();

        $leads = \App\Models\Lead::orderBy('id', 'desc')->pluck('name', 'id');

        return view('admin.salesaccount.invoice_edit', compact('page_title', 'leads', 'page_description', 'order', 'orderDetails', 'products', 'clients'));
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function paymentedit($id)
    {
        $page_title = 'Payment';
        $page_description = 'Edit Payment';
        $order = Orders::where('id', $id)->first();
        $orderDetails = OrderDetail::where('order_id', $id)->get();
        $products = Product::select('id', 'name')->get();
        $clients = Client::select('id', 'name', 'location')->get();

        $leads = \App\Models\Lead::orderBy('id', 'desc')->get();

        return view('admin.salesaccount.payment_edit', compact('page_title', 'leads', 'page_description', 'order', 'orderDetails', 'products', 'clients'));
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function quotationedit($id)
    {
        $page_title = 'Quotation';
        $page_description = 'Edit Quotation';
        $order = SalesOrder::where('order_no', $id)->first();
        $orderDetails = SalesOrderDetail::where('order_no', $id)->get();
        $products = Product::select('id', 'name')->get();
        $clients = Client::select('id', 'name', 'location')->get();

        $leads = \App\Models\Lead::orderBy('id', 'desc')->pluck('name', 'id');

        return view('admin.salesaccount.quotation_edit', compact('page_title', 'leads', 'page_description', 'order', 'orderDetails', 'products', 'clients'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function invoiceUpdate(Request $request, $id)
    {
        $userId = Auth::user()->id;
        $ord_date = \Carbon\Carbon::parse($request->ord_date);
        $order_no = $request->order_no;
        $order_ref_no = $request->order_reference_id;
        $this->validate($request, []);
        //d($request->all(),1);
        $itemQty = $request->item_quantity;
        $unitPrice = $request->unit_price;
        $taxIds = $request->tax_id;
        $itemDiscount = $request->discount;
        $itemPrice = $request->item_price;
        $description = $request->description;

        $itemRowIds = $request->item_rowid;
        //  dd($itemRowIds);
        // update sales_order table

        $salesOrder['ord_date'] = $ord_date;
        $salesOrder['payment_term'] = $request->payment_term;
        $salesOrder['from_stk_loc'] = $request->from_stk_loc;
        $salesOrder['lead_id'] = $request->lead_id;
        $salesOrder['comments'] = $request->comments;
        $salesOrder['total'] = $request->final_total;
        $salesOrder['updated_at'] = date('Y-m-d H:i:s');

        DB::table('sales_orders')->where('order_no', $id)->update($salesOrder);
        //  dd($itemRowIds);

        if (count($itemRowIds) > 0) {
            $orderItemRowIds = DB::table('sales_order_details')->where('order_no', $order_no)->pluck('id');
            // Delete items from order if no exists on updated orders
            foreach ($orderItemRowIds as $key => $orderItemRowId) {
                if (! in_array($orderItemRowId, $itemRowIds)) {
                    $stock = DB::table('sales_order_details')->where('id', $orderItemRowId)->first();
                    DB::table('sales_order_details')->where(['id' => $orderItemRowId, 'order_no' => $order_no])->delete();
                }
            }

            foreach ($itemRowIds as $key => $value) {
                // update sales_order_details table
                $salesOrderDetail['description'] = $description[$key];
                $salesOrderDetail['tax_type_id'] = $taxIds[$key];
                $salesOrderDetail['unit_price'] = $unitPrice[$key];
                $salesOrderDetail['quantity'] = $itemQty[$key];
                $salesOrderDetail['discount_percent'] = $itemDiscount[$key];
                DB::table('sales_order_details')->where(['id' => $value])->update($salesOrderDetail);
                // Update stock_move table
            }
        }
        //  dd($request->description_new);

        if ($request->description_new != null) {

            // dd($request->description_new);
            $itemQtyNew = $request->item_quantity_new;
            $itemIdsNew = $request->item_id_new;
            $unitPriceNew = $request->unit_price_new;
            $taxIdsNew = $request->tax_id_new;
            $itemDiscountNew = $request->discount_new;
            $itemPriceNew = $request->item_price_new;
            $descriptionNew = $request->description_new;
            $stockIdNew = $request->stock_id_new;

            foreach ($descriptionNew as $key => $value) {
                // Insert new sales order detail
                $salesOrderDetailNew['trans_type'] = SALESORDER;
                $salesOrderDetailNew['order_no'] = $order_no;
                $salesOrderDetailNew['stock_id'] = $stockIdNew[$key];
                $salesOrderDetailNew['description'] = $descriptionNew[$key];
                $salesOrderDetailNew['quantity'] = $itemQtyNew[$key];
                $salesOrderDetailNew['discount_percent'] = $itemDiscountNew[$key];
                $salesOrderDetailNew['tax_type_id'] = $taxIdsNew[$key];
                $salesOrderDetailNew['unit_price'] = $itemPriceNew[$key];
                $salesOrderDetailNew['is_inventory'] = 1;
                DB::table('sales_order_details')->insertGetId($salesOrderDetailNew);
            }
        }

        // Custom items Start
        $tax_id_custom = $request->tax_id_custom;
        $custom_items_discount = $request->custom_items_discount_new;
        $custom_items_name = $request->custom_items_name_new;
        $custom_items_rate = $request->custom_items_rate_new;
        $custom_items_qty = $request->custom_items_qty_new;
        $custom_items_amount = $request->custom_items_amount_new;
        if (! empty($custom_items_name)) {
            foreach ($custom_items_name as $key => $value) {
                $items['order_no'] = $order_no;
                $items['trans_type'] = SALESINVOICE;
                $items['tax_type_id'] = $tax_id_custom[$key];
                $items['discount_percent'] = $custom_items_discount[$key];
                $items['description'] = $custom_items_name[$key];
                $items['unit_price'] = $custom_items_rate[$key];
                $items['quantity'] = $custom_items_qty[$key];
                $items['is_inventory'] = 0;
                DB::table('sales_order_details')->insert($items);
            }
        }

        Session::flash('success', trans('message.success.save_success'));

        return redirect('/admin/sales/list');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function paymentUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'customer_id' => 'required',
            'order_type' => 'required',
            'status' => 'required',
            'name' => 'required',
        ]);

        //dd($request->all());
        $order = $this->orders->find($id);
        //dd($order);
        if ($order->isEditable()) {
            $order_attributes = $request->all();

            $order_attributes['user_id'] = Auth::user()->id;
            $order_attributes['client_id'] = $request->customer_id;
            $order_attributes['total'] = $request->final_total;

            $order->update($order_attributes);

            OrderDetail::where('order_id', $id)->delete();

            $product_ids = $request->product_id;
            $price = $request->price;
            $quantity = $request->quantity;
            $tax = $request->tax;
            $tax_amount = $request->tax_amount;
            $total = $request->total;
            foreach ($product_ids as $key => $value) {
                if ($value != '') {
                    $detail = new OrderDetail();
                    $detail->client_id = $request->customer_id;
                    $detail->order_id = $order->id;
                    $detail->product_id = $value;
                    $detail->price = $price[$key];
                    $detail->quantity = $quantity[$key];
                    $detail->tax = $tax[$key];
                    $detail->tax_amount = $tax_amount[$key];
                    $detail->total = $total[$key];
                    $detail->date = date('Y-m-d H:i:s');
                    $detail->save();
                }
            }

            Flash::success('Order updated Successfully.');

            if (\Request::get('order_type') && \Request::get('order_type') == 'quotation') {
                $type = 'quotation';
            } elseif (\Request::get('order_type') && \Request::get('order_type') == 'proforma_invoice') {
                $type = 'invoice';
            } elseif (\Request::get('order_type') && \Request::get('order_type') == 'purchase_order') {
                $type = 'po';
            } else {
                $type = 'quotation';
            }

            return redirect('/admin/orders?type='.$type);
        } else {
            Flash::success('Error in updating Order.');
        }

        return Redirect::back()->withInput(\Request::all());
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function quotationUpdate(Request $request, $id)
    {
        $userId = Auth::user()->id;
        $order_no = $request->order_no;
        $ord_date = \Carbon\Carbon::parse($request->ord_date);
        //dd($ord_date);
        $this->validate($request, []);

        $itemQty = $request->item_quantity;
        $itemIds = $request->item_id;
        $unitPrice = $request->unit_price;
        $taxIds = $request->tax_id;
        $itemDiscount = $request->discount;
        $itemPrice = $request->item_price;
        $stock_id = $request->stock_id;
        $description = $request->description;
        $itemRowIds = $request->item_rowid;
        // d($itemRowIds,1);
        // update sales_order table
        $salesOrder['ord_date'] = $ord_date;
        $salesOrder['lead_id'] = $request->lead_id;
        $salesOrder['trans_type'] = SALESORDER;
        $salesOrder['branch_id'] = $request->lead_id;
        $salesOrder['payment_id'] = $request->payment_id;

        $salesOrder['from_stk_loc'] = $request->from_stk_loc;
        $salesOrder['comments'] = $request->comments;
        $salesOrder['total'] = $request->final_total;
        $salesOrder['updated_at'] = date('Y-m-d H:i:s');
        //d($salesOrder,1);

        //  dd($salesOrder);

        DB::table('sales_orders')->where('order_no', $order_no)->update($salesOrder);

        if (count($itemRowIds) > 0) {
            //  dd($itemRowIds);

            $orderItemRowIds = DB::table('sales_order_details')->where('order_no', $order_no)->pluck('id');
            // Delete items from order if no exists on updated orders
            foreach ($orderItemRowIds as $orderItemRowId) {
                if (! in_array($orderItemRowId, $itemRowIds)) {
                    DB::table('sales_order_details')->where(['id' => $orderItemRowId, 'order_no' => $order_no])->delete();
                }
            }

            foreach ($itemRowIds as $key => $value) {
                $salesOrderDetail['tax_type_id'] = $taxIds[$key];
                $salesOrderDetail['description'] = $description[$key];
                $salesOrderDetail['unit_price'] = $unitPrice[$key];
                $salesOrderDetail['quantity'] = $itemQty[$key];
                $salesOrderDetail['discount_percent'] = $itemDiscount[$key];
                DB::table('sales_order_details')->where(['id' => $value])->update($salesOrderDetail);
            }
        } else {
            DB::table('sales_order_details')->where('order_no', $order_no)->delete();
            DB::table('sales_orders')->where('order_no', $order_no)->delete();
        }

        if ($request->item_quantity_new != null) {
            // dd($request->item_quantity);
            $itemQty = $request->item_quantity_new;
            //    $itemIdsNew = $request->item_id;
            $unitPriceNew = $request->unit_price_new;
            $taxIdsNew = $request->tax_id_new;
            $itemDiscountNew = $request->discount_new;
            $itemPriceNew = $request->item_price_new;
            $descriptionNew = $request->description_new;

            foreach ($itemQty as $key => $value) {

                // Insert new sales order detail
                $salesOrderDetailNew['trans_type'] = SALESORDER;
                $salesOrderDetailNew['order_no'] = $order_no;
                $salesOrderDetailNew['stock_id'] = $stock_id_new[$key];
                $salesOrderDetailNew['description'] = $descriptionNew[$key];
                $salesOrderDetailNew['qty_sent'] = $value;
                $salesOrderDetailNew['quantity'] = $value;
                $salesOrderDetailNew['discount_percent'] = $itemDiscountNew[$key];
                $salesOrderDetailNew['tax_type_id'] = $taxIdsNew[$key];
                $salesOrderDetailNew['unit_price'] = $itemPriceNew[$key];
                $salesOrderDetailNew['is_inventory'] = 1;
                DB::table('sales_order_details')->insertGetId($salesOrderDetailNew);
            }
        }

        // Custom items Start
        $tax_id_custom = $request->tax_id_custom;
        $custom_items_discount = $request->custom_items_discount;
        $custom_items_name = $request->custom_items_name;
        $custom_items_rate = $request->custom_items_rate;
        $custom_items_qty = $request->custom_items_qty;
        $custom_items_amount = $request->custom_items_amount;
        if (! empty($custom_items_name)) {
            foreach ($custom_items_name as $key => $value) {
                $items['order_no'] = $order_no;
                $items['trans_type'] = SALESORDER;
                $items['tax_type_id'] = $tax_id_custom[$key];
                $items['discount_percent'] = $custom_items_discount[$key];
                $items['description'] = $custom_items_name[$key];
                $items['unit_price'] = $custom_items_amount[$key];
                $items['quantity'] = $custom_items_qty[$key];
                $items['is_inventory'] = 0;
                DB::table('sales_order_details')->insert($items);
            }
        }
        // Custom items End

        Session::flash('success', trans('message.success.save_success'));

        return redirect()->intended('/admin/order/list');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $orders = $this->orders->find($id);

        if (! $orders->isdeletable()) {
            abort(403);
        }

        $this->orders->delete($id);
        OrderDetail::where('order_id', $id)->delete($id);

        MasterComments::where('type', 'orders')->where('master_id', $id)->delete();

        Flash::success('Order successfully deleted.');

        if (\Request::get('type')) {
            return redirect('/admin/orders?type='.\Request::get('type'));
        }

        return redirect('/admin/orders?type=quotation');
    }

    public function getProductDetailAjax($productId)
    {
        $product = Product::select('id', 'name', 'price', 'cost')->where('id', $productId)->first();

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

        $orders = $this->orders->find($id);

        if (! $orders->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Order';

        $orders = $this->orders->find($id);
        if (\Request::get('type')) {
            $modal_route = route('admin.orders.delete', ['id' => $orders->id]).'?type='.\Request::get('type');
        } else {
            $modal_route = route('admin.orders.delete', ['id' => $orders->id]);
        }

        $modal_body = 'Are you sure you want to delete this order?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function printInvoice($orderNo, $invoiceNo)
    {
        //    $ord = SalesOrder::find($id);
        $saleDataOrder = SalesOrder::where('order_no', '=', $orderNo)->first();
        $saleDataInvoice = SalesOrder::where('order_no', '=', $invoiceNo)->first();
        $invoiceType = $saleDataInvoice->invoice_type;
        $salesDetails = SalesOrderDetail::orderBy('id', 'desc')->where('order_no', $invoiceNo)->get();

        //  $orderDetails = OrderDetail::where('order_id', $id)->get();
        return view('admin.salesaccount.invoice_print', compact('saleDataOrder', 'saleDataInvoice', 'invoiceType', 'salesDetails'));
    }

    public function printPayment($id)
    {
        $ord = $this->orders->find($id);
        $orderDetails = OrderDetail::where('order_id', $id)->get();
        //dd($orderDetails);
        return view('admin.salesaccount.payment_print', compact('ord', 'orderDetails'));
    }

    public function printQuotation($id)
    {
        //    $ord = SalesOrder::find($id);
        $saleDataOrder = SalesOrder::where('order_no', '=', $id)->first();
        //  dd($saleDataOrder);
        $salesDetails = SalesOrderDetail::orderBy('id', 'desc')->where('order_no', $id)->get();

        return view('admin.salesaccount.quotation_print', compact('saleDataOrder', 'salesDetails'));
    }

    public function generatePDFInvoice($orderNo, $invoiceNo)
    {
        $saleDataOrder = SalesOrder::where('order_no', '=', $orderNo)->first();
        $saleDataInvoice = SalesOrder::where('order_no', '=', $invoiceNo)->first();
        $invoiceType = $saleDataInvoice->invoice_type;
        $salesDetails = SalesOrderDetail::orderBy('id', 'desc')->where('order_no', $invoiceNo)->get();

        $pdf = \PDF::loadView('admin.salesaccount.invoice_pdf', compact('saleDataOrder', 'saleDataInvoice', 'invoiceType', 'salesDetails'));
        $file = $orderNo.'_'.$saleDataInvoice->lead->name.'_'.str_replace(' ', '_', $saleDataInvoice->lead->id).'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function generatePDFPayment($id)
    {
        $ord = $this->orders->find($id);
        $orderDetails = OrderDetail::where('order_id', $id)->get();

        $pdf = \PDF::loadView('admin.orders.generateInvoicePDF', compact('ord', 'orderDetails'));
        $file = $id.'_'.$ord->name.'_'.str_replace(' ', '_', $ord->client->name).'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function generatePDFQuotation($id)
    {
        //    $ord = SalesOrder::find($id);
        $saleDataOrder = SalesOrder::where('order_no', '=', $id)->first();
        //  dd($saleDataOrder);
        $salesDetails = SalesOrderDetail::orderBy('id', 'desc')->where('order_no', $id)->get();

        $pdf = \PDF::loadView('admin.salesaccount.quotation_pdf', compact('saleDataOrder', 'salesDetails'));
        $file = $orderNo.'_'.$saleDataOrder->lead->name.'_'.str_replace(' ', '_', $saleDataOrder->lead->id).'.pdf';

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
        $term = strtolower(\Request::get('term'));
        $contacts = ClientModel::select('id', 'name')->where('name', 'LIKE', '%'.$term.'%')->groupBy('name')->take(5)->get();
        $return_array = [];

        foreach ($contacts as $v) {
            if (strpos(strtolower($v->name), $term) !== false) {
                $return_array[] = ['value' => $v->name, 'id' => $v->id];
            }
        }

        return Response::json($return_array);
    }

    // public function salesBook(){
    //     $sales_book = \App\Models\Invoice::paginate(50);
    //     // return  response()->json($purchase_book);
    //     return view('admin.accountreport.sales-book',compact('sales_book'));
    // }

    public function salesBook(Request $request)
    {
        $page_title = 'Admin | salesBook';
        $outlets = \TaskHelper::getUserOutlets();
        $op = \Request::get('op');

        $fiscal_year = (\App\Models\Fiscalyear::where('org_id',\Auth::user()->org_id)
                        ->where('current_year', '1')->first())->fiscal_year;

        if ($request->filter == 'nep') {                 //for nepali
            $startdate = $request->nepstartdate;
            $enddate = $request->nependdate;
            $cal = new \App\Helpers\NepaliCalendar();
            $startdate = $cal->nep_to_eng_digit_conversion($startdate);
            $date = $cal->nep_to_eng($startdate[0], $startdate[1], $startdate[2]);
            $startdate = $date['year'].'-'.$date['month'].'-'.$date['date'];
            $enddate = $cal->nep_to_eng_digit_conversion($enddate);
            $date = $cal->nep_to_eng($enddate[0], $enddate[1], $enddate[2]);
            $enddate = $date['year'].'-'.$date['month'].'-'.$date['date'];
        } else {
            $startdate = $request->engstartdate;
            $enddate = $request->engenddate;
        }
        $sales_book = \App\Models\Invoice::
        when($startdate&&$enddate,function ($q) use ($enddate, $startdate) {
            $q->where('bill_date', '>=', $startdate)
                ->where('bill_date', '<=', $enddate);
        })
            ->where('org_id',\Auth::user()->org_id)

        ->when(!$startdate&&!$enddate&&!$request->greater_than_1_lakh,function ($q) use ($enddate, $startdate) {
            $q->orderBy('id','desc');
        })
        ->when($startdate&&$enddate&&!$request->greater_than_1_lakh,function ($q) use ($enddate, $startdate) {
            $q->orderBy('bill_date','Asc');
        });
        if ($op == 'pdf') {
            if($request->greater_than_1_lakh) {
                $sales_book = $sales_book->orderBy('client_id', 'Desc')->get();
                $sales_book=$this->filterBook($sales_book);
            }
            else {
                $sales_book = $sales_book->get();
            }
            $report_title = 'Sales Book';
            $pdf = \PDF::loadView('pdf.filteredsales', compact('sales_book', 'fiscal_year', 'startdate', 'enddate','report_title'))->setPaper('a4', 'landscape');
            $file = 'Report_salebook_filtered'.date('_Y_m_d').'.pdf';
            if (File::exists('reports/'.$file)) {
                File::Delete('reports/'.$file);
            }

            return $pdf->download($file);
        }
        if ($op == 'print') {
            if($request->greater_than_1_lakh) {
                $sales_book = $sales_book->orderBy('client_id', 'Desc')->get();
                $sales_book=$this->filterBook($sales_book);
            }
            else {
                $sales_book = $sales_book->get();
            }
            return view('print.salesbook-print', compact('sales_book', 'fiscal_year', 'startdate', 'enddate'));
        }
        if ($op == 'excel') {
            if($request->greater_than_1_lakh) {
                $sales_book = $sales_book->orderBy('client_id', 'Desc')->get();
                $sales_book=$this->filterBook($sales_book);
            }
            else {
                $sales_book = $sales_book->get();
            }

            $allData = [];
            $pos_total_amount = 0;
            $pos_taxable_amount = 0;
            $pos_tax_amount = 0;
            $totalDiscount = 0;
            foreach ($sales_book as $key => $o) {


                $data['SN'] = ++$key;
                $data['Bill Date'] = $o->bill_date;
                $data['Bill No'] = env('SALES_BILL_PREFIX'). $o->bill_no;
                $data['Customer Name'] = $o->client?$o->client->name:$o->name;
                $data['Customer PAN'] = $o->client?$o->client->vat:$o->customer_pan;

                $data['Total Sales'] = $o->total_amount;
                $data['Non Tax Sales'] = '';
                $data['Export Sales'] = '';
                $data['Discount'] = $o->discount_amount;

                $data['Taxable Amount'] = $o->taxable_amount;

                $data['TAX'] = $o->tax_amount;

                $allData [] = $data;

                $pos_total_amount = $pos_total_amount + $o->total_amount;
                $totalDiscount += $o->discount_amount;

                $pos_taxable_amount = $pos_taxable_amount + $o->taxable_amount;

                $pos_tax_amount = $pos_tax_amount + $o->tax_amount;

                if ($o->invoicemeta->is_bill_active === 0) {
                    $data = [];
                    $data['SN'] = $key;
                    $data['Bill Date'] = $o->bill_date;
                    $data['Bill No'] = "Ref of" . env('SALES_BILL_PREFIX') . $o->bill_no . "CN" . $o->credit_note_no;
                    $data['Customer Name'] = $o->client?$o->client->name:$o->name;
                    $data['Customer PAN'] = $o->client?$o->client->vat:$o->customer_pan;



                    $data['Total Sales'] = '-' . $o->total_amount;
                    $data['Non Tax Sales'] = '';
                    $data['Export Sales'] = '';


                    $data['Discount'] = $o->discount_amount;
                    $data['Taxable Amount'] = '-' . $o->taxable_amount;
                    $data['TAX'] = '-' . $o->tax_amount;

                    $allData[] = $data;
                    $pos_total_amount = $pos_total_amount - $o->total_amount;
                    $totalDiscount -= $o->discount_amount;
                    $pos_taxable_amount = $pos_taxable_amount - $o->taxable_amount;
                    $pos_tax_amount = $pos_tax_amount - $o->tax_amount;
                }

            }


            $date = date('Y-m-d');
            // $total = [

            //     'SN' => '',
            //     'Bill Date' => '',
            //     'Bill No' => '',
            //     'Customer Name' => '',
            //     'Customer PAN' => 'Total Amount',
            //     'Total Sales' => $pos_total_amount,
            //     'Non Tax Sales' => '',
            //     'Export Sales' => '',
            //     'Discount' => $totalDiscount,
            //     'Taxable Amount' => $pos_taxable_amount,
            //     'TAX' => $pos_tax_amount,
            // ];

            // array_push($allData, $total);
            // return \Excel::download(new \App\Exports\ExcelExport($allData), "sales_book_{$date}.csv");
            return \Excel::download(new \App\Exports\SalesExport($allData, 'Sales Book'), "sales_book_{$date}.xls");
        }

        if($request->greater_than_1_lakh) {
            $sales_book = $sales_book->orderBy('client_id', 'Desc')->get();
            $data=$this->filterBook($sales_book);
            $sales_book=$this->arrayPaginator($data,$request);
        }
        else {
            $sales_book = $sales_book->paginate(100);
        }

        return view('admin.accountreport.sales-book', compact('sales_book', 'page_title', 'fiscal_year','outlets'));
    }

    public function filterBook($sales_book){
        $data = [];
        foreach ($sales_book as $value) {
            $total_sum = $sales_book->where('client_id', $value->client_id)->sum('taxable_amount');
            if ($total_sum > 100000) {
                $data[] = $value;
            }
        }
        return $data;
    }
    public function arrayPaginator($array, $request)
    {
        $page = $request->page?? 1;
        $perPage = 20;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }
    public function SalesBookByMonths($month)
    {
        
        $page_title = 'Admin | purchasebook';
        $fiscal_year = (\App\Models\Fiscalyear::where('org_id', \Auth::user()->org_id)->where('current_year', '1')->first())->fiscal_year;

        //dd($fiscal_year);
        $fiscal_y = substr($fiscal_year, 0, 4);
        $cal = new \App\Helpers\NepaliCalendar();
        $days_list = $cal->bs;
        if ($month < 4) {
            $year = $fiscal_y + 1;
        } else {
            $year = $fiscal_y;
        }
        $year_array = $days_list[$year - 2000];
        $start = $cal->nep_to_eng($year, $month, 1);
        $end = $cal->nep_to_eng($year, $month, $year_array[(int) $month]);
        $startdate = $start['year'].'-'.$start['month'].'-'.$start['date'];
        $enddate = $end['year'].'-'.$end['month'].'-'.$end['date'];

        // dd($startdate);

        if (\Request::has('op')) {
            $op = \Request::get('op');
            $months = (int) $month;
            if ($op == 'pdf') {
                $sales_book = \App\Models\Invoice::where('bill_date', '>=', $startdate)
                    ->where('bill_date', '<=', $enddate)
                    ->where(function($query){

                        if(\Request::get('outlet_id')){
                            return $query->where('outlet_id',\Request::get('outlet_id'));
                        }

                    })
                    ->get();
                 $report_title = 'Sales Book';
                $pdf = \PDF::loadView('pdf.filteredsales', compact('sales_book', 'fiscal_year', 'months','report_title'));
                $file = 'Report_salebook_filtered'.date('_Y_m_d').'.pdf';
                if (File::exists('reports/'.$file)) {
                    File::Delete('reports/'.$file);
                }

                return $pdf->download($file);
            }
            if ($op == 'excel') {

                $data = DB::select("SELECT invoice.id as SN , invoice.bill_no as 'Bill Num', clients.name as 'Customers Name',clients.vat as 'Customers PAN No','' as 'Total Sales','' as 'Non Tax Sales','' as 'Export Sales','' as Discount ,invoice.taxable_amount as Amount,invoice.tax_amount as 'Tax(Rs)' from invoice LEFT JOIN clients ON clients.id = invoice.client_id where invoice.bill_date >= '$startdate' AND invoice.bill_date <= '$enddate'");
                $data = json_decode(json_encode($data), true);

                return \Excel::download(new \App\Exports\ExcelExport($data), 'salesbook.csv');
            }
        }
        $sales_book = \App\Models\Invoice::where('bill_date', '>=', $startdate)
            ->where('bill_date', '<=', $enddate)
            ->where(function($query){

                        if(\Request::get('outlet_id')){
                            return $query->where('outlet_id',\Request::get('outlet_id'));
                        }

                    })
            ->paginate(100);

        return view('admin.accountreport.sales-book', compact('sales_book', 'page_title', 'fiscal_year'));
    }
}
