<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Lead;
use App\Models\MasterComments;
use App\Models\Order as Order;
use App\Models\OrderDetail;
use App\Models\OrderPaymentTerms;
use App\Models\Orders;
use App\Models\Product;
use App\Models\Role as Permission;
use App\Models\StockMove;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Models\Invoice;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class OrdersController extends Controller
{
    /**
     * @var Client
     */
    private $orders;
    private $invoice;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $bug
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Permission $permission, Orders $order, Invoice $invoice)
    {
        parent::__construct();
        $this->permission = $permission;
        $this->orders = $order;
        $this->invoice = $invoice;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = \App\Models\Orders::orderBy('id', 'desc')
            ->where('org_id', \Auth::user()->org_id)
            ->where(function ($query) {
                if (\Request::get('type') && \Request::get('type') == 'quotation') {
                    return $query->where('order_type', 'quotation');
                }
            })
            ->where(function ($query) {
                if (\Request::get('type') && \Request::get('type') == 'invoice') {
                    return $query->where('order_type', 'proforma_invoice');
                }
            })
            ->where(function ($query) {
                if (\Request::get('type') && \Request::get('type') == 'order') {
                    return $query->where('order_type', 'order');
                }
            })
            ->where(function ($query) {
                if (\Request::get('fiscal_id') && \Request::get('fiscal_id') != '') {

                    return $query->where('fiscal_year_id',\Request::get('fiscal_id'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('status') && \Request::get('status') != '') {
                    return $query->where('status', \Request::get('status'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('customer_id') && \Request::get('customer_id') != '') {
                    return $query->where('source','lead')->where('client_id', \Request::get('customer_id'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('client_id') && \Request::get('client_id') != '') {
                    return $query->where('source','client')->where('client_id', \Request::get('client_id'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('location_id') && \Request::get('location_id') != '') {
                    return $query->where('from_stock_location', \Request::get('location_id'));
                }
            })
            ->where(function($query){
                if(!Auth::user()->hasRole('admins')){
                    return $query->where('user_id',Auth::user()->id);
                }
            })->where(function($query){

                $pay_status = \Request::get('pay_status');
                if($pay_status == 'Pending'){

                    return $query->where('payment_status','Pending')
                            ->orWhereNull('payment_status')
                            ->orWhere('payment_status','');

                }elseif($pay_status == 'Partial'){

                   return $query->where('payment_status','Partial');

                }elseif($pay_status == 'Paid'){

                  return  $query->where('payment_status','Paid');
                }

            })
            ->where(function($query){
                $payments = \Request::get('payment');
                if($payments == 'pending_partial'){
                    return $query->whereIn('payment_status',['Pending','Partial'])
                            ->orWhereNull('payment_status')
                            ->orWhere('payment_status','');
                }
            })
            ->paginate(25);

        //dd($orders);

        $locations = \App\Models\ProductLocation::where('enabled', '1')
            ->where('org_id', \Auth::user()->org_id)
            ->pluck('location_name', 'id')->all();

        $leads = Lead::pluck('name', 'id')->all();
        $clients = Client::pluck('name', 'id')->all();

        $fiscalyears = \App\Models\Fiscalyear::orderBy('fiscal_year', 'desc')->pluck('fiscal_year', 'id')->all();

        // dd($fiscalyears);

        $page_title = 'Orders';
        $page_description = 'Manage Orders';

        return view('admin.orders.index', compact('orders', 'page_title', 'page_description', 'locations', 'leads', 'fiscalyears','clients'));
    }

    //renewals

    public function renewals()
    {
        $orders = \App\Models\Orders::orderBy('id', 'desc')
            ->where('org_id', \Auth::user()->org_id)
            ->where('is_renewal', '1')
            ->where(function ($query) {
                if (\Request::get('type') && \Request::get('type') == 'quotation') {
                    return $query->where('order_type', 'quotation');
                }
            })
            ->where(function ($query) {
                if (\Request::get('type') && \Request::get('type') == 'invoice') {
                    return $query->where('order_type', 'proforma_invoice');
                }
            })
            ->where(function ($query) {
                if (\Request::get('type') && \Request::get('type') == 'order') {
                    return $query->where('order_type', 'order');
                }
            })
            ->where(function ($query) {
                if (\Request::get('fiscal_id') && \Request::get('fiscal_id') != '') {
                    $start_date = \App\Models\Fiscalyear::where('id', \Request::get('fiscal_id'))->first()->start_date;
                    $end_date = \App\Models\Fiscalyear::where('id', \Request::get('fiscal_id'))->first()->end_date;

                    return $query->whereBetween('bill_date', [$start_date, $end_date]);
                }
            })
            ->where(function ($query) {
                if (\Request::get('status') && \Request::get('status') != '') {
                    return $query->where('status', \Request::get('status'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('customer_id') && \Request::get('customer_id') != '') {
                    return $query->where('client_id', \Request::get('customer_id'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('location_id') && \Request::get('location_id') != '') {
                    return $query->where('from_stock_location', \Request::get('location_id'));
                }
            })
            ->paginate(25);

        $locations = \App\Models\ProductLocation::where('enabled', '1')
            ->where('org_id', \Auth::user()->org_id)
            ->pluck('location_name', 'id')->all();

        $leads = Lead::pluck('name', 'id')->all();

        $fiscalyears = \App\Models\Fiscalyear::orderBy('fiscal_year', 'desc')->pluck('fiscal_year', 'id')->all();

        // dd($fiscalyears);

        $page_title = 'Renewals';
        $page_description = 'Renewal Orders';

        return view('admin.orders.renewals', compact('orders', 'page_title', 'page_description', 'locations', 'leads', 'fiscalyears'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $ord = $this->orders->find($id);
        \TaskHelper::authorizeOrg($ord);
        $page_title = 'Orders';
        $page_description = 'View Order';
        $orderDetails = OrderDetail::where('order_id', $ord->id)->get();
        $OrderPaymentTerms = OrderPaymentTerms::where('order_id', $ord->id)->get();
        //dd($orderDetails);
        $imagepath = \Auth::user()->organization->logo;
        // dd($imagepath);

        return view('admin.orders.show', compact('ord', 'imagepath', 'page_title', 'page_description', 'orderDetails','OrderPaymentTerms'));
    }

    public function printInvoice($id)
    {
        $ord = $this->orders->find($id);
        $orderDetails = OrderDetail::where('order_id', $ord->id)->get();
        $OrderPaymentTerms = OrderPaymentTerms::where('order_id', $ord->id)->get();
        //dd($orderDetails);
        $imagepath = \Auth::user()->organization->logo;

        return view('admin.orders.print', compact('ord', 'imagepath', 'orderDetails','OrderPaymentTerms'));
    }

    public function copyDoc($id)
    {
        $order = Orders::where('id', $id)->first();
        $orderdetail = OrderDetail::where('order_id', $id)->get();

        $new_item = $order->replicate(); //copy attributes
        $new_item->push();
        foreach ($orderdetail as $ord) {
            $neworderdetails = $ord->toArray();
            unset($neworderdetails['id']);
            $neworderdetails['order_id'] = $new_item->id;
            OrderDetail::create($neworderdetails);
        }

        Flash::success('Quotation successfully duplicated.');

        return redirect('/admin/orders?type=quotation');
    }

    public function generatePDF($id)
    {
        $ord = $this->orders->find($id);
        $orderDetails = OrderDetail::where('order_id', $ord->id)->get();
        $OrderPaymentTerms = OrderPaymentTerms::where('order_id', $ord->id)->get();
        $imagepath = \Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.orders.generateInvoicePDF', compact('ord', 'imagepath', 'orderDetails','OrderPaymentTerms'));
        $file = $ord->bill_no . '_' . $ord->name . '_' . str_replace(' ', '_', ($ord->source=='client'?$ord->client->name:$ord->lead->name)) . '.pdf';

        if (\File::exists('reports/' . $file)) {
            \File::Delete('reports/' . $file);
        }

        return $pdf->download($file);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $org_id = \Auth::user()->org_id;
        $ckfiscalyear = \App\Models\Fiscalyear::where('current_year', '1')
                        ->where('org_id', $org_id)
                        ->first();
        if(!$ckfiscalyear){
            Flash::error("Please Set Fiscal Year First");
            return redirect()->back();
        }
        $bill_no = \DB::select("SELECT MAX(Convert(`bill_no`,SIGNED)) as last_bill from fin_orders WHERE fiscal_year = '$ckfiscalyear->fiscal_year' AND  org_id = '$org_id'  limit 1");
        $bill_no = $bill_no[0]->last_bill + 1;
        $page_title = 'Orders';
        $page_description = 'Add Orders';
        $order = null;
        $orderDetail = null;
        $products = Product::select('id', 'name')->get();
        $users = \App\User::where('enabled', '1')->where('org_id', \Auth::user()->org_id)->pluck('first_name', 'id');

        $last_order = Orders::orderBy('id', 'Desc')->first();

        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();

        $units = \App\Models\ProductsUnit::orderBy('id', 'desc')->get();

        //dd($units);

        $lead_clients = \App\Models\Lead::select('id', 'name')->where('org_id', \Auth::user()->org_id)->orderBy('id', 'DESC')->get();
        $customer_clients = \App\Models\Client::where('enabled', '1')->where('relation_type','!=','supplier')->where('org_id', \Auth::user()->org_id)->orderBy('id', 'desc')->get();

        return view('admin.orders.create', compact('page_title', 'users', 'productlocation', 'page_description', 'order', 'orderDetail', 'products', 'lead_clients', 'units', 'customer_clients', 'last_order','bill_no'));
    }

    public function store(Request $request)
    {
       $org_id = \Auth::user()->org_id;
        $ckfiscalyear = \App\Models\Fiscalyear::where('current_year', '1')
                        ->where('org_id', $org_id)
                        ->first();
        if(!$ckfiscalyear){
            Flash::error("Please Set Fiscal Year First");
            return redirect()->back();
        }
        $bill_no = $request->bill_no;

        $this->validate($request, [
            'customer_id' => 'required',
            'bill_no' => ['required',function($attribute, $value,$fail) use($bill_no,$ckfiscalyear){
                if(\App\Models\Orders::where([['bill_no', $bill_no],['fiscal_year', $ckfiscalyear->fiscal_year]])->first()){
                    $fail('Sorry Bill Number Already used in this Fiscal year.');
                }
            }],
        ]);
        //dd($request->all());
        $order_attributes = $request->all();

          $order_attributes['user_id'] = \Auth::user()->id;
        $order_attributes['org_id'] = \Auth::user()->org_id;
        $order_attributes['client_id'] = $request->customer_id;
        $order_attributes['tax_amount'] = $request->taxable_tax;
        $order_attributes['total_amount'] = $request->final_total;
        $order_attributes['bill_no'] = $bill_no;
        $order_attributes['fiscal_year'] = $ckfiscalyear->fiscal_year;
        $order_attributes['fiscal_year_id'] = $ckfiscalyear->id;
        // dd($order_attributes);
        $order = $this->orders->create($order_attributes);

        if ($order->order_type == 'proforma_invoice') {
            $this->postLedger($order->id); //post invoice to ledegr
        }

        $product_id = $request->product_id;
        $price = $request->price;
        $unit = $request->unit;
        $quantity = $request->quantity;
        $tax = $request->tax;
        $tax_amount = $request->tax_amount;
        $total = $request->total;

        foreach ($product_id ?? [] as $key => $value) {
            if ($value != '') {
                $detail = new OrderDetail();
                $detail->order_id = $order->id;
                $detail->product_id = $product_id[$key];
                $detail->price = $price[$key];
                $detail->quantity = $quantity[$key];
                $detail->unit = $unit[$key];
                $detail->tax = $tax[$key] ?? null;
                $detail->tax = $tax[$key] ?? null;
                $detail->tax_amount = $tax_amount[$key] ?? null;
                $detail->total = $total[$key];
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
        $custom_unit = $request->custom_unit;
        $custom_tax_amount = $request->custom_tax_amount;
        $custom_total = $request->custom_total;

        foreach ($custom_items_name ?? [] as $key => $value) {
            if ($value != '') {
                $detail = new OrderDetail();
                $detail->order_id = $order->id;
                $detail->description = $custom_items_name[$key];
                $detail->price = $custom_items_price[$key];
                $detail->quantity = $custom_items_qty[$key];
                $detail->unit = $custom_unit[$key];
                $detail->tax = $tax_id_custom[$key];
                $detail->tax_amount = $custom_tax_amount[$key];
                $detail->total = $custom_total[$key];
                $detail->date = date('Y-m-d H:i:s');
                $detail->is_inventory = 0;
                //  dd($detail);
                $detail->save();
            }
        }

        //payment terms
        $term_date = $request->term_date;
        $term_condition = $request->term_condition;
        $term_amount = $request->term_amount;
         foreach ($term_condition ?? [] as $key => $value) {
            if ($value != '') {
                $detail = new OrderPaymentTerms();
                $detail->term_condition = $value;
                $detail->order_id = $order->id;
                $detail->term_date = $term_date[$key];
                $detail->term_amount = $term_amount[$key];
                $detail->status = 0;
                $detail->save();
            }
        }

        Flash::success('Order created Successfully.');

        if (\Request::get('order_type') && \Request::get('order_type') == 'quotation') {
            $type = 'quotation';
        } elseif (\Request::get('order_type') && \Request::get('order_type') == 'proforma_invoice') {
            $type = 'invoice';
        } elseif (\Request::get('order_type') && \Request::get('order_type') == 'order') {
            $type = 'order';
        } else {
            $type = 'quotation';
        }

        return redirect('/admin/orders?type=' . $type);
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $page_title = 'Orders';
        $page_description = 'Edit Orders';
        $order = Orders::where('id', $id)->first();
        $orderPaymentTerms = OrderPaymentTerms::where('order_id', $id)->get();
         \TaskHelper::authorizeOrg($order);
        $orderDetails = OrderDetail::where('order_id', $order->id)->get();
        $fiscal_year = \FinanceHelper::cur_fisc_yr();
        $products = Product::select('id', 'name')->get();
        // $clients = Client::select('id', 'name', 'location')->get();
        if ($order->source == 'lead') {
            $clients = \App\Models\Lead::select('id', 'name')->where('org_id', \Auth::user()->org_id)->orderBy('id', 'DESC')->get();
        } else {
            $clients = \App\Models\Client::where('enabled', '1')->where('org_id', \Auth::user()->org_id)->orderBy('id', 'desc')->get();
        }
        $users = \App\User::where('enabled', '1')->where('org_id', \Auth::user()->org_id)->pluck('first_name', 'id');

        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();

        $units = \App\Models\ProductsUnit::orderBy('id', 'desc')->get();

        return view('admin.orders.edit', compact('page_title', 'users', 'page_description', 'units', 'productlocation', 'order', 'orderDetails', 'products', 'clients','orderPaymentTerms'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $org_id = \Auth::user()->org_id;
        $ckfiscalyear = \App\Models\Fiscalyear::where('current_year', '1')
                        ->where('org_id', $org_id)
                        ->first();
        if(!$ckfiscalyear){
            Flash::error("Please Set Fiscal Year First");
            return redirect()->back();
        }
        $bill_no = $request->bill_no;

        $this->validate($request, [
            'customer_id' => 'required',
            'bill_no' => ['required',function($attribute, $value,$fail) use($id,$bill_no,$ckfiscalyear){
                if(\App\Models\Orders::where([['bill_no', $bill_no],['fiscal_year', $ckfiscalyear->fiscal_year],['id','!=',$id]])->first()){
                    $fail('Sorry Bill Number Already used in this Fiscal year.');
                }
            }],
        ]);

//        dd($request->all());
        $order = $this->orders->find($id);
         \TaskHelper::authorizeOrg($order);
        //dd($order);
        if ($order->isEditable()) {
            $order_attributes = $request->all();

            $order_attributes['org_id'] = \Auth::user()->org_id;
            $order_attributes['client_id'] = $request->customer_id;

            $order_attributes['tax_amount'] = $request->taxable_tax;
            $order_attributes['total_amount'] = $request->final_total;

            //dd($order_attributes);

            $order->update($order_attributes);
            if ($order->order_type == 'proforma_invoice') {
                $this->postLedger($order->id); //post invoice to ledegr
            }
            $orderdetail = OrderDetail::where('order_id', $order->id)->get();

            foreach ($orderdetail as $od) {
                $stockmove = StockMove::where('trans_type', SALESINVOICE)->where('reference', 'store_out_' . $order->id)->where('stock_id', $od->product_id)->delete();
            }

            OrderDetail::where('order_id', $order->id)->delete();
            OrderPaymentTerms::where('order_id', $order->id)->delete();

            $product_id = $request->product_id;
            $price = $request->price;
            $quantity = $request->quantity;
            $tax = $request->tax;
            $unit = $request->unit;
            $tax_amount = $request->tax_amount;
            $total = $request->total;

            foreach ($product_id ?? [] as $key => $value) {
                if ($value != '') {
                    $detail = new OrderDetail();
                    $detail->order_id = $order->id;
                    $detail->product_id = $product_id[$key];
                    $detail->price = $price[$key];
                    $detail->quantity = $quantity[$key];
                    $detail->tax = $tax[$key] ?? null;
                    $detail->unit = $unit[$key] ?? null;
                    $detail->tax_amount = $tax_amount[$key] ?? null;
                    $detail->total = $total[$key];
                    $detail->is_inventory = 1;
                    $detail->date = date('Y-m-d H:i:s');
                    $detail->save();


                }
            }

            $description_custom = $request->description_custom;
            $price_custom = $request->price_custom;
            $quantity_custom = $request->quantity_custom;
            $tax_custom = $request->tax_custom;
            $unit_custom = $request->unit_custom;
            $tax_amount_custom = $request->tax_amount_custom;
            $total_custom = $request->total_custom;

            foreach ($description_custom ?? [] as $key => $value) {
                if ($value != '') {
                    $detail = new OrderDetail();
                    $detail->order_id = $order->id;
                    $detail->description = $description_custom[$key];
                    $detail->price = $price_custom[$key];
                    $detail->quantity = $quantity_custom[$key];
                    $detail->tax = $tax_custom[$key];
                    $detail->unit = $unit_custom[$key];
                    $detail->tax_amount = $tax_amount_custom[$key];
                    $detail->total = $total_custom[$key];
                    $detail->is_inventory = 0;
                    $detail->date = date('Y-m-d H:i:s');
                    $detail->save();
                }
            }

            if ($request->product_id_new != null) {
                $product_id_new = $request->product_id_new;
                $ticket_new = $request->ticket_new;
                $price_new = $request->price_new;
                $quantity_new = $request->quantity_new;
                $unit_new = $request->unit_new;
                $flight_date_new = $request->flight_date_new;
                $tax_new = $request->tax_new;
                $tax_amount_new = $request->tax_amount_new;
                $total_new = $request->total_new;

                foreach ($product_id_new as $key => $value) {
                    $detail = new OrderDetail();
                    $detail->order_id = $order->id;
                    $detail->product_id = $product_id_new[$key];
                    $detail->price = $price_new[$key];
                    $detail->quantity = $quantity_new[$key];
                    $detail->unit = $unit_new[$key];
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
            $custom_unit_new = $request->custom_unit_new;
            $custom_flight_date_new = $request->custom_flight_date_new;
            $tax_id_custom_new = $request->tax_id_custom_new;
            $custom_tax_amount_new = $request->custom_tax_amount_new;
            $custom_total_new = $request->custom_total_new;

            if (!empty($custom_items_name_new)) {
                foreach ($custom_items_name_new as $key => $value) {
                    $detail = new OrderDetail();
                    $detail->order_id = $order->id;
                    $detail->description = $custom_items_name_new[$key];
                    $detail->price = $custom_items_price_new[$key];
                    $detail->quantity = $custom_items_qty_new[$key];
                    $detail->unit = $custom_unit_new[$key];
                    $detail->tax = $tax_id_custom_new[$key];
                    $detail->tax_amount = $custom_tax_amount_new[$key];
                    $detail->total = $custom_total_new[$key];
                    $detail->is_inventory = 0;
                    $detail->date = date('Y-m-d H:i:s');
                    $detail->save();
                }
            }

            //payment terms
            $term_date = $request->term_date;
            $term_condition = $request->term_condition;
            $term_amount = $request->term_amount;
             foreach ($term_condition ?? [] as $key => $value) {
                if ($value != '') {
                    $detail = new OrderPaymentTerms();
                    $detail->term_condition = $value;
                    $detail->order_id = $order->id;
                    $detail->term_date = $term_date[$key];
                    $detail->term_amount = $term_amount[$key];
                    $detail->status = 0;
                    $detail->save();
                }
            }

            Flash::success('Order updated Successfully.');
            if (\Request::get('order_type') && \Request::get('order_type') == 'quotation') {
                $type = 'quotation';
            } elseif (\Request::get('order_type') && \Request::get('order_type') == 'proforma_invoice') {
                $type = 'invoice';
            } elseif (\Request::get('order_type') && \Request::get('order_type') == 'order') {
                $type = 'order';
            } else {
                $type = 'quotation';
            }

            return redirect()->back();
        } else {
            Flash::success('Error in updating Order.');
        }

        return \Redirect::back()->withInput(\Request::all());
    }

    private function postLedger($ord_id)
    {
        $order = \App\Models\Orders::find($ord_id);
        //dd($order);

        if ($order->entry_id && $order->entry_id != '0') {
            //upadte entries
            $entry = \App\Models\Entry::find($order->entry_id);
            $entry_attr = [
                'tag_id' => '6',
                'entrytype_id' => \FinanceHelper::get_entry_type_id('sales'),
                'number' => \FinanceHelper::get_last_entry_number($entrytype_id),
                'org_id' => \Auth::user()->org_id,
                'user_id' => \Auth::user()->id,
                'date' => date('Y-m-d'),
                'dr_total' => $order->total_amount,
                'ref_id' => $order->id,
                'bill_no' => $order->bill_no,
                'cr_total' => $order->total_amount,
                'source' => 'SALESINVOICE',
            ];
            $entry->update($entry_attr);
            $clients = \App\Models\Client::find($order->client_id);
            $entry_item_c = \App\Models\Entryitem::where('entry_id', $order->entry_id)->where('dc', 'C')->first();
            $item_c = [
                'entry_id' => $entry->id,
                'org_id' => \Auth::user()->org_id,
                'dc' => 'C',
                'ledger_id' => \FinanceHelper::get_ledger_id('SALES_LEDGER_ID'), //Sales Ledger
                'amount' => $order->taxable_amount,
                'narration' => 'Sales being made',
            ];
            $entry_item_c->update($item_c);

            $entry_item_d_tax = \App\Models\Entryitem::where('entry_id', $order->entry_id)
                    ->where('dc', 'C')->where('ledger_id',
                        \FinanceHelper::get_ledger_id('SALES_TAX_LEDGER'))->first();
            $item_d_tax = [
                'entry_id' => $entry->id,
                'org_id' => \Auth::user()->org_id,
                'dc' => 'C',
                'ledger_id' => \FinanceHelper::get_ledger_id('SALES_TAX_LEDGER'),
                'amount' => $order->tax_amount,
                'narration' => 'Sales being made',
            ];
            $entry_item_d_tax->updateOrCreate($item_d_tax);

            $entry_item_d = \App\Models\Entryitem::where('entry_id', $order->entry_id)->where('dc', 'D')->where('ledger_id', $clients->ledger_id)->first();
            $item_d = [
                'entry_id' => $entry->id,
                'org_id' => \Auth::user()->org_id,
                'dc' => 'D',
                'ledger_id' => $clients->ledger_id,
                'amount' => $order->total_amount,
                'narration' => 'Sales being made',
            ];
            $entry_item_d->update($item_d);

            return 0;
        } else {
            $entry = \App\Models\Entry::create([
                'tag_id' => '6',
                'entrytype_id' => \FinanceHelper::get_entry_type_id('sales'),
                'number' => \FinanceHelper::get_last_entry_number($entrytype_id),
                'org_id' => \Auth::user()->org_id,
                'user_id' => \Auth::user()->id,
                'date' => date('Y-m-d'),
                'dr_total' => $order->total_amount,
                'cr_total' => $order->total_amount,
                'ref_id' =>   $order->id,
                'bill_no' => $order->bill_no,
                'fiscal_year_id' => \FinanceHelper::cur_fisc_yr()->id,
                'source' => 'SALESINVOICE',
            ]);

            $clients = \App\Models\Client::find($order->client_id);
            //send total to sales ledger
            $entry_item = \App\Models\Entryitem::create([
                'entry_id' => $entry->id,
                'org_id' => \Auth::user()->org_id,
                'dc' => 'C',
                'ledger_id' => \FinanceHelper::get_ledger_id('SALES_LEDGER_ID'), //Sales Ledger
                'amount' => $order->taxable_amount,
                'narration' => 'Sales being made',
            ]);

            //send the taxable amount to SALES TAX LEDGER
            $entry_item = \App\Models\Entryitem::create([
                'entry_id' => $entry->id,
                'org_id' => \Auth::user()->org_id,
                'dc' => 'C',
                'ledger_id' =>  \FinanceHelper::get_ledger_id('SALES_TAX_LEDGER'), //Sales Tax Ledger
                'amount' => $order->tax_amount,
                'narration' => 'Tax to pay',
            ]);
            //send amount before tax to customer ledger
            $entry_item = \App\Models\Entryitem::create([
                'entry_id' => $entry->id,
                'org_id' => \Auth::user()->org_id,
                'dc' => 'D',
                'ledger_id' => $clients->ledger_id,
                'amount' => $order->total_amount,
                'narration' => 'Sales being made',
            ]);

            $order->update(['entry_id' => $entry->id]);

            return 0;
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $order = $this->orders->find($id);
         \TaskHelper::authorizeOrg($order);
        $orderDetail = OrderDetail::where('order_id', $order->id)->get();

        if (!$order->isdeletable()) {
            abort(403);
        }
        $data=$order->toarray();
        $data['delete_by']=\Auth::user()->id;
        \App\Models\OrdersTrash::create($data);

        $order->delete($id);

        OrderDetail::where('order_id', $id)->delete($id);

        foreach ($orderDetail as $od) {
            $stockmove = StockMove::where('trans_type', SALESINVOICE)->where('stock_id', $od->product_id)->where('reference', 'store_out_' . $order->id)->delete();
// dd($id);
        }

        if ($order->entry_id != '') {
            $entries = \App\Models\Entry::find($order->entry_id);
            if($entries->id!=''){
                \App\Models\Entryitem::where('entry_id', $entries->id)->delete();
            }

            \App\Models\Entry::find($order->entry_id)->delete();
        }
        MasterComments::where('type', 'orders')->where('master_id', $id)->delete();

        Flash::success('Order successfully deleted.');

        if (\Request::get('type')) {
            return redirect('/admin/orders?type=' . \Request::get('type'));
        }

        return redirect('/admin/orders?type=quotation');
    }

    public function getProductDetailAjax($productId)
    {
        $product = Product::select('id', 'name', 'price', 'cost','product_unit')
                ->where('id', $productId)
                ->first();
        $available = \TaskHelper::getTranslations($productId);

        $units = $product->product_unit;
        $inStock = \StockHelper::getRemaingStocks($product->id);
        $description = "In Stock: {$inStock} , Purch Pr: {$product->cost}";

        return ['data' => json_encode($product),'available'=>$available,'units'=>$units,'description'=>$description];
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

        // dd($orders);

        if (!$orders->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Order';

        //dd($modal_title);

        $orders = $this->orders->find($id);
        if (\Request::get('type')) {
            $modal_route = route('admin.orders.delete', ['orderId' => $orders->id]) . '?type=' . \Request::get('type');
        } else {
            $modal_route = route('admin.orders.delete', ['orderId' => $orders->id]);
        }

        $modal_body = 'Are you sure you want to delete this order?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
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
        $contacts = ClientModel::select('id', 'name')->where('name', 'LIKE', '%' . $term . '%')->groupBy('name')->take(5)->get();
        $return_array = [];

        foreach ($contacts as $v) {
            if (strpos(strtolower($v->name), $term) !== false) {
                $return_array[] = ['value' => $v->name, 'id' => $v->id];
            }
        }

        return \Response::json($return_array);
    }


    public function SalesPayment()
    {
        $page_title = 'Tax Invoice Payment List';
        $page_description = 'Listing of all tax sales Payments';

        $payment_list = \App\Models\InvoicePayment::orderBy('id', 'desc')->whereNotNull('invoice_id')->get();

        return view('admin.orders.salespaymentlist', compact('page_title', 'page_description', 'payment_list'));
    }

    public function paymentlist(){

        $page_title = 'Sales Payment List';
        $page_description = 'Listing of all Sales Payments';

        $payment_list = \App\Models\Payment::orderBy('id', 'desc')
                        ->whereNotNull('sale_id')->get()->groupBy('sale_id');

        $purchaseToPay = $this->orders->whereIn('payment_status',['Pending','Partial'])
                ->orWhereNull('payment_status')
                ->orWhere('payment_status','')
                ->where('order_type','proforma_invoice')
                ->get();

        return view('admin.orders.allsalespaymentlist', compact('page_title', 'page_description', 'payment_list','purchaseToPay'));



    }

    public function SalesPaymentPdf()
    {
        $payment_list = \App\Models\Payment::orderBy('id', 'desc')->whereNotNull('sale_id')->get();
        $pdf = \PDF::loadView('admin.orders.salespaymentpdf', compact('payment_list'));
        $file = 'Sales_payment' . date('_Y_m_d') . '.pdf';

        if (\File::exists('reports/' . $file)) {
            \File::Delete('reports/' . $file);
        }

        return $pdf->download($file);
    }

    public function convertToPI($id)
    {
        //dd($id);

        $orders = \App\Models\Orders::find($id);

        if (count($orders) == 0) {
            Flash::error('Order Not Found #' . $id . '');

            return Redirect::back();
        }

        $leads = \App\Models\Lead::find($orders->client_id);
        // dd($leads);
        if ($leads->company->name) {
            $clients_name = $leads->name;
        } else {
            $clients_name = $leads->name;
        }

        //dd($clients_name);
        if ($leads->mob_phone) {
            $mobile = $leads->mob_phone;
        } else {
            $mobile = '';
        }

        //dd($moblie);
        $clients = [
            'org_id' => \Auth::user()->org_id,
            'name' => $clients_name,
            'phone' => $mobile,
            'email' => $leads->email,
            'type' => $leads->leadType->name,
            'enabled' => '1',
        ];

        //dd($clients);
        $client = \App\Models\Client::create($clients);
        $full_name = $client->name;
        $_ledgers = \TaskHelper::PostLedgers($full_name, \FinanceHelper::get_ledger_id('CUSTOMER_LEDGER_GROUP'));
        $attributes['ledger_id'] = $_ledgers;
        $client->update($attributes);

        $orders->update(['client_id' => $client->id, 'order_type' => 'proforma_invoice', 'source' => 'client']);
        $leads->update(['moved_to_client' => '1']);

        $this->postLedger($id); //post invoice to ledegr

        //Audit::log(Auth::user()->id, trans('admin/clients/general.audit-log.category'), trans('admin/clients/general.audit-log.msg-store', ['name' => $client->name]));
        Flash::success('Quotation  #' . $id . ' Successfully moved to Porforma Invoice');

        return Redirect::back();
    }

    public function ConfirmconvertToPI($id)
    {
        $error = null;

        $orders = \App\Models\Orders::find($id);

        $modal_title = 'Convert to Quotation to Porforma Invoice';

        $type = \Request::get('type');
        $modal_route = route('admin.orders.convert_to_pi', ['id' => $orders->id]);

        $modal_body = 'Are you sure you want to convert To Performa Invoice ';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    
     public function showcreditnote($id)
    {
        $ord = $this->invoice->find($id);
        $page_title = 'Credit Note';
        $page_description = 'View Order';
        $orderDetails = OrderDetail::where('order_id', $ord->id)->get();
        //dd($orderDetails);
        $imagepath = \Auth::user()->organization->logo;
        // dd($imagepath);
        return view('admin.orders.credit_note_show', compact('ord', 'imagepath', 'page_title', 'page_description', 'orderDetails'));
    }

    public function printcreditnote($id, $type)
    {
        $ord = $this->invoice->find($id);

        $orderDetails = OrderDetail::where('order_id', $ord->id)->get();

        $imagepath = \Auth::user()->organization->logo;

        if($type == 'print'){


            return view('admin.orders.credit_note_print', compact('ord', 'imagepath', 'page_title', 'page_description', 'orderDetails'));


        }


        return view('admin.orders.generatecreditnotePDF', compact('ord', 'imagepath', 'orderDetails'));
        $file = $id . '_' . $ord->name . '_' . str_replace(' ', '_', $ord->client->name) . '.pdf';
        if (\File::exists('reports/' . $file)) {
            \File::Delete('reports/' . $file);
        }
        return $pdf->download($file);
    }
    public function paymentTermUpdate($id)
    {
        $payment_terms = OrderPaymentTerms::find($id);
        $payment_terms->status = 1;
        $payment_terms->save();
        Flash::success('Payment Terms Successfully Updated');
        return Redirect::back();

    }
    public function paymentTermList(Request $request)
    {
        $payment_terms = [];
        $start_date = \Request::get('start_date');
        $end_date = \Request::get('end_date');
        $status = \Request::get('status');

        if($start_date && $end_date){
            $payment_terms = OrderPaymentTerms::where('term_date','>=',$start_date)
                    ->where('term_date','<=',$end_date)
                    ->where('status',$status)
                    ->get();
        }
        return view('admin.orders.emi_list', compact('payment_terms'));

    }


}
