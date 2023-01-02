<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\MasterComments;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Role as Permission;
use App\Models\StockMove;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use PhpParser\Node\Expr\FuncCall;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class InvoiceController extends Controller
{
    /**
     * @var Client
     */
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
    public function __construct(Permission $permission, Invoice $invoice)
    {
        parent::__construct();
        $this->permission = $permission;
        $this->invoice = $invoice;
    }





    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $orders = Invoice::where(function($query){
                    $start_date = \Request::get('start_date');
                    $end_date = \Request::get('end_date');
                    if($start_date && $end_date){
                        return $query->where('bill_date','>=',$start_date)
                            ->where('bill_date','<=',$end_date);
                    }

                })
                ->where(function($query){
                    $bill_no = \Request::get('bill_no');
                    if($bill_no){
                        return $query->where('bill_no',$bill_no);
                    }

                })
                ->where(function($query){
                    $client_id = \Request::get('client_id');
                    if($client_id){
                        return $query->where('client_id',$client_id);
                    }
                })
                ->where(function($query){
                    $fiscal_year = \Request::get('fiscal_year');
                    if($fiscal_year){
                        return $query->where('fiscal_year',$fiscal_year);
                    }

                })->where(function($query){

                    $outlet_id = \Request::get('outlet_id');

                    if($outlet_id){

                        return $query->where('outlet_id',$outlet_id);
                    }

                })
                ->where('org_id', \Auth::user()->org_id)
                ->orderBy('id', 'desc')
                ->paginate(30);
        $page_title = 'Invoice';
        $page_description = 'Manage Invoice';
        $clients = \App\Models\Client::select('id', 'name')->where('org_id', \Auth::user()->org_id)->orderBy('id', 'DESC')->pluck('name','id')->all();
        $fiscal_years = \App\Models\Fiscalyear::pluck('fiscal_year as name', 'fiscal_year as id')->all();

        $outlets = $this->getUserOutlets();
        return view('admin.invoice.index', compact('orders', 'page_title', 'page_description','clients','fiscal_years','outlets'));
    }
    public function detailindex()
    {

        $orders = Invoice::where(function($query){
                    $start_date = \Request::get('start_date');
                    $end_date = \Request::get('end_date');
                    if($start_date && $end_date){
                        return $query->where('bill_date','>=',$start_date)
                            ->where('bill_date','<=',$end_date);
                    }

                })
                ->where(function($query){
                    $bill_no = \Request::get('bill_no');
                    if($bill_no){
                        return $query->where('bill_no',$bill_no);
                    }

                })
                ->where(function($query){
                    $client_id = \Request::get('client_id');
                    if($client_id){
                        return $query->where('client_id',$client_id);
                    }
                })
                ->where(function($query){
                    $fiscal_year = \Request::get('fiscal_year');
                    if($fiscal_year){
                        return $query->where('fiscal_year',$fiscal_year);
                    }

                })->where(function($query){

                    $outlet_id = \Request::get('outlet_id');

                    if($outlet_id){

                        return $query->where('outlet_id',$outlet_id);
                    }

                })
                ->where('org_id', \Auth::user()->org_id)
                ->orderBy('id', 'desc')
                ->paginate(30);
        $page_title = 'Invoice';
        $page_description = 'Manage Invoice';
        $clients = \App\Models\Client::select('id', 'name')->where('org_id', \Auth::user()->org_id)->orderBy('id', 'DESC')->pluck('name','id')->all();
        $fiscal_years = \App\Models\Fiscalyear::pluck('fiscal_year as name', 'fiscal_year as id')->all();

        $outlets = $this->getUserOutlets();
        return view('admin.invoice.detailindex', compact('orders', 'page_title', 'page_description','clients','fiscal_years','outlets'));
    }
    public function detail($id)
    {
        $page_title = 'Invoice';
        $page_description = 'Invoice Detail View';
        $order = null;
        $orderDetail = null;
        $products = Product::select('id', 'name')->where('org_id',\Auth::user()->org_id)->where('product_division',"raw_material")->get();
        $users = \App\User::where('enabled', '1')->where('org_id', \Auth::user()->org_id)->pluck('first_name', 'id');
        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();
        //$clients = Client::select('id', 'name', 'location')->orderBy('id', DESC)->get();
        $clients = \App\Models\Client::select('id', 'name')->where('org_id', \Auth::user()->org_id)->orderBy('id', 'DESC')->get();
        $invoice = $this->invoice->find($id);
        \TaskHelper::authorizeOrg($invoice);
        $invoice_details = \App\Models\InvoiceDetail::where('invoice_id', $id)->get();

        $units = \App\Models\ProductsUnit::orderBy('id', 'desc')->get();

        $outlets = \App\Models\PosOutlets::get();
        return view('admin.invoice.detail', compact('page_title', 'users', 'units', 'page_description', 'order', 'orderDetail', 'products', 'clients', 'productlocation', 'invoice', 'invoice_details','outlets'));
    }
    //renewals
    public function renewals()
    {
        $orders = Invoice::orderBy('id', 'desc')->where('is_renewal', '1')->where('org_id', \Auth::user()->org_id)->paginate(30);
        $page_title = 'Invoice Renewals';
        $page_description = 'Manage Invoice renewals';
        $fiscal_years = \App\Models\Fiscalyear::pluck('fiscal_year as name', 'fiscal_year as id')->all();

        $outlets = $this->getUserOutlets();
        // dd($outlets);
        return view('admin.invoice.index', compact('orders', 'page_title', 'page_description','outlets','fiscal_years'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $ord = Invoice::find($id);
        \TaskHelper::authorizeOrg($ord);
        $page_title = 'Invoice';
        $page_description = 'View Invoice';
        $orderDetails = InvoiceDetail::where('invoice_id', $id)->get();
        $paidAmount=  \App\Models\InvoicePayment::where('invoice_id', $id)->select(DB::raw("SUM(amount) as amount"))->first();

        $imagepath = \Auth::user()->organization->logo;
        // dd($imagepath);

        return view('admin.invoice.show', compact('ord','paidAmount', 'imagepath', 'page_title', 'page_description', 'orderDetails'));
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
        $bill_no = \DB::select("SELECT MAX(Convert(`bill_no`,SIGNED)) as last_bill from invoice WHERE fiscal_year = '$ckfiscalyear->fiscal_year' AND  org_id = '$org_id'  limit 1");
        
        $bill_no = $bill_no[0]->last_bill + 1;

        $page_title = 'Invoice';
        $page_description = 'Add invoice';
        $order = null;
        $orderDetail = null;
        $products = Product::select('id', 'name')->where('org_id',\Auth::user()->org_id)->where('product_division',"raw_material")->get();
        $users = \App\User::where('enabled', '1')->where('org_id', \Auth::user()->org_id)->pluck('first_name', 'id');

        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();

        $units = \App\Models\ProductsUnit::orderBy('id', 'desc')->get();
        //dd($airlines);
        //$clients = Client::select('id', 'name', 'location')->orderBy('id', DESC)->get();
        $clients = \App\Models\Client::select('id', 'name','location')->where('org_id', \Auth::user()->org_id)->orderBy('id', 'DESC')->get();

        $outlets = \App\Models\PosOutlets::get();

        return view('admin.invoice.create', compact('page_title', 'users', 'page_description', 'order', 'units', 'orderDetail', 'products', 'clients', 'productlocation','outlets','bill_no'));
    }
    public function ajaxvalidation($productid){
      
        $product = \App\Models\Product::where('id', $productid)->select('id','name', 'product_type_id','product_division')
        ->first();
        if(( $product->product_division ==null)   || ($product->product_type_id==null)){
            return ['flags'=>false, 'name'=>$product->name];
        }
        $producttypemaster=\App\Models\ProductTypeMaster::where('id', $product->product_type_id)->select('ledger_id','cogs_ledger_id','purchase_ledger_id')->first();
        $flag=false;
        if($producttypemaster->ledger_id && $producttypemaster->cogs_ledger_id && $producttypemaster->purchase_ledger_id && $product->product_division)
        {   
            $flag= true;
        }
       
        return ['flags'=>$flag, 'name' => $product->name];
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
                if(\App\Models\Invoice::where([['bill_no', $bill_no],['fiscal_year', $ckfiscalyear->fiscal_year]])->first()){
                    $fail('Sorry Bill Number Already used in this Fiscal year.');
                }
            }],
        ]);

        // $bill_no = \DB::select("SELECT MAX(Convert(`bill_no`,SIGNED)) as last_bill from invoice WHERE fiscal_year = '$ckfiscalyear->fiscal_year' AND  org_id = '$org_id'  limit 1");

        //dd($bill_no);
        $order_attributes = $request->all();
        //  $order_attributes['user_id'] = \Auth::user()->id;
        $order_attributes['user_id'] = \Auth::user()->id;
        $order_attributes['sales_person'] = $request->user_id;
        $order_attributes['org_id'] = \Auth::user()->org_id;
        $order_attributes['client_id'] = $request->customer_id;
        $order_attributes['tax_amount'] = $request->taxable_tax;
        $order_attributes['total_amount'] = $request->final_total;
        $order_attributes['bill_no'] = $bill_no;
        // $order_attributes['total_excise_duty']= $request->total_excise_duty;
        $order_attributes['fiscal_year'] = $ckfiscalyear->fiscal_year;
        $order_attributes['is_bill_active'] = 1;
        $order_attributes['fiscal_year_id'] = $ckfiscalyear->id;
        $invoice = $this->invoice->create($order_attributes);

        $product_id = $request->product_id;
        //dd($product_ids);
        $price = $request->price;
        $quantity = $request->quantity;
        $tax = $request->tax;
        $unit = $request->unit;
        $tax_amount = $request->tax_amount;
        $total = $request->total;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new InvoiceDetail();
                $detail->client_id = $request->customer_id;
                $detail->discount= $request->dis_amount[$key]??0;
                $detail->invoice_id = $invoice->id;
                $detail->product_id = $product_id[$key];
                $detail->price = $price[$key];
                $detail->quantity = $quantity[$key];
                $detail->tax = $tax[$key] ?? null;
                $detail->unit = $unit[$key];
                $detail->tax_amount = $tax_amount[$key] ?? null;
                // $detail->excise_amount = $request->excise_amount[$key] ?? 0;
                $detail->total = $total[$key];
                $detail->date = date('Y-m-d H:i:s');
                $detail->is_inventory = 1;
                $detail->save();

                // create stockMove

                $stockMove = new \App\Models\StockMove();

                $stockMove->stock_id = $product_id[$key];
                $stockMove->tran_date = $request->bill_date;
                $stockMove->user_id = \Auth::user()->id;
                $stockMove->reference = 'store_out_'.$invoice->id;
                $stockMove->transaction_reference_id = $invoice->id;
                $stockMove->qty = '-'.$quantity[$key];
                $stockMove->price = (double)$price[$key]-((double)$request->dis_amount[$key]/(double)$quantity[$key]);
                $stockMove->trans_type = OTHERSALESINVOICE;
                $stockMove->order_no = $invoice->id;
                $stockMove->store_id = $request->outlet_id;
                $stockMove->order_reference = $bill_no;
                $stockMove->save();
            }
        }

        // Custom items
        $tax_id_custom = $request->custom_tax_amount;
        $custom_items_name = $request->custom_items_name;
        $custom_items_rate = $request->custom_items_rate;
        $custom_items_qty = $request->custom_items_qty;
        $custom_unit = $request->custom_unit;
        $custom_items_price = $request->custom_items_price;

        $custom_tax_amount = $request->custom_tax_amount;
        $custom_total = $request->custom_total;

        foreach ($custom_items_name ??[] as $key => $value) {
            if ($value != '') {
                $detail = new InvoiceDetail();
                $detail->client_id = $request->customer_id;
                $detail->invoice_id = $invoice->id;
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
        //ENTRY FOR Total AMOUNT

        $invoicemeta = new \App\Models\InvoiceMeta();
        $invoicemeta->invoice_id = $invoice->id;
        $invoicemeta->sync_with_ird = 0;
        $invoicemeta->is_bill_active = 1;
        $invoicemeta->save();

        $this->updateentries($invoice->id, $request);
        Flash::success('Invoices created Successfully.');
        if(env('IS_IRD')){
            $this->postInvoicetoIRD($invoice->id);
        }else{
            Flash::warning('Bill not synced with ird');
        }
        return redirect('/admin/invoice1');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $page_title = 'Invoice';
        $page_description = 'Edit invoice';
        $order = null;
        $orderDetail = null;
        $products = Product::select('id', 'name')->where('org_id',\Auth::user()->org_id)->where('product_division',"raw_material")->get();
        $users = \App\User::where('enabled', '1')->where('org_id', \Auth::user()->org_id)->pluck('first_name', 'id');
        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();
        //$clients = Client::select('id', 'name', 'location')->orderBy('id', DESC)->get();
        $clients = \App\Models\Client::select('id', 'name')->where('org_id', \Auth::user()->org_id)->orderBy('id', 'DESC')->get();
        $invoice = $this->invoice->find($id);
        \TaskHelper::authorizeOrg($invoice);
        $invoice_details = \App\Models\InvoiceDetail::where('invoice_id', $id)->get();

        $units = \App\Models\ProductsUnit::orderBy('id', 'desc')->get();

        $outlets = \App\Models\PosOutlets::get();
        return view('admin.invoice.edit', compact('page_title', 'users', 'units', 'page_description', 'order', 'orderDetail', 'products', 'clients', 'productlocation', 'invoice', 'invoice_details','outlets'));
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
                if(\App\Models\Invoice::where([['bill_no', $bill_no],['fiscal_year', $ckfiscalyear->fiscal_year],['id','!=',$id]])->first()){
                    $fail('Sorry Bill Number Already used in this Fiscal year.');
                }
            }],
        ]);
        $invoice = $this->invoice->find($id);
        \TaskHelper::authorizeOrg($invoice);
        $product_id = $request->product_id;
        //dd($product_ids);
        $price = $request->price;
        //dd($price);
        $quantity = $request->quantity;
        $tax = $request->tax;
        $unit = $request->unit;
        $tax_amount = $request->tax_amount;
        $total = $request->total;

        $invdetails = InvoiceDetail::where('invoice_id', $id)->get();

        foreach ($invdetails as $pd) {
            $stockmove = \App\Models\StockMove::where('stock_id', $pd->product_id)->where('order_no', $id)->where('trans_type', 203)->delete();
        }

        InvoiceDetail::where('invoice_id', $id)->delete();
        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new InvoiceDetail();
                $detail->client_id = $request->customer_id;
                $detail->discount= $request->dis_amount[$key]??0;
                $detail->invoice_id = $invoice->id;
                $detail->product_id = $product_id[$key];
                $detail->price = $price[$key];
                $detail->quantity = $quantity[$key];
                $detail->tax = $tax[$key] ?? null;
                $detail->unit = $unit[$key];
                $detail->tax_amount = $tax_amount[$key] ?? null;
                // $detail->excise_amount = $request->excise_amount[$key] ?? 0;
                $detail->total = $total[$key];
                $detail->date = date('Y-m-d H:i:s');
                $detail->is_inventory = 1;
                $detail->save();

                // create stockMove

                $stockMove = new \App\Models\StockMove();

                $stockMove->stock_id = $product_id[$key];
                $stockMove->tran_date = $request->bill_date;
                $stockMove->user_id = \Auth::user()->id;
                $stockMove->reference = 'store_out_'.$id;
                $stockMove->transaction_reference_id = $id;
                $stockMove->price=$price[$key];
                $stockMove->qty = '-'.$quantity[$key];
                $stockMove->trans_type = OTHERSALESINVOICE;
                $stockMove->order_no = $id;
                $stockMove->store_id = $request->outlet_id;
                $stockMove->order_reference = $invoice->bill_no;
                $stockMove->save();
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
                $detail = new InvoiceDetail();
                $detail->client_id = $request->customer_id;
                $detail->invoice_id = $invoice->id;
                $detail->description = $custom_items_name[$key];
                $detail->price = $custom_items_price[$key];
                $detail->quantity = $custom_items_qty[$key];
                $detail->tax = $tax_id_custom[$key];
                $detail->unit = $custom_unit[$key];
                $detail->tax_amount = $custom_tax_amount[$key];
                $detail->total = $custom_total[$key];
                $detail->date = date('Y-m-d H:i:s');
                $detail->is_inventory = 0;
                //  dd($detail);
                $detail->save();
            }
        }

        $order_attributes = $request->all();
        $order_attributes['org_id'] = \Auth::user()->org_id;
        $order_attributes['client_id'] = $request->customer_id;
        $order_attributes['tax_amount'] = $request->taxable_tax;
        $order_attributes['total_amount'] = $request->final_total;
        $order_attributes['is_bill_active'] = 1;
        $invoice->update($order_attributes);
        $this->updateentries($id, $request);
        Flash::success('Invoices created Successfully.');

        return redirect()->back();
    }

    /**
     * @param $id
     * @return
     */


    public function getProductDetailAjax($productId)
    {
        $product = Course::select('id', 'name', 'price', 'cost')->where('id', $productId)->first();

        return ['data' => json_encode($product)];
    }

     public function destroy($id)
    {

        $orders = $this->invoice->findOrFail($id);
        $data=$orders->toArray();
        $data['delete_by']= \Auth::user()->id;
        $orderDetail = \App\Models\InvoiceDetail::where('invoice_id', $id)->get();
        \TaskHelper::authorizeOrg($orders);

        if (! $orders->isdeletable()) {
            abort(403);
        }
         foreach ($orderDetail as $od) {
            $stockmove = StockMove::where('trans_type', 203)->where('stock_id', $od->product_id)->where('order_no',$id)->delete();
        }
        \App\Models\InvoiceTrash::create($data);
        $orders->delete($id);
        \App\Models\InvoiceDetail::where('invoice_id', $id)->delete($id);

        $entries = \App\Models\Entry::findOrFail($orders->entry_id);        
        \App\Models\Entryitem::where('entry_id', $entries->id)->delete();
        \App\Models\Entry::find($orders->entry_id)->delete();

        Flash::success('Invoice successfully deleted.');

        return redirect('/admin/invoice1');
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

        $orders = $this->invoice->find($id);

        if (! $orders->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Invoice';

        $orders = $this->invoice->find($id);

        $modal_route = route('admin.invoice.delete', ['id' => $orders->id]);


        $modal_body = 'Are you sure you want to delete this Invoice?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * Delete Confirm.
     *
     * @param   int   $id
     * @return  View
     */


    public function printInvoice($id)
    {
        $ord = $this->invoice->find($id);
         \TaskHelper::authorizeOrg($ord);
        $orderDetails = InvoiceDetail::where('invoice_id', $id)->get();
        //dd($orderDetails);
        $imagepath = \Auth::user()->organization->logo;
        $print_no = \App\Models\Invoiceprint::where('invoice_id', $id)->count();
        $attributes = new \App\Models\Invoiceprint();
        $attributes->invoice_id = $id;
        $attributes->printed_date = \Carbon\Carbon::now();
        $attributes->printed_by = \Auth::user()->id;
        $attributes->save();
        $ord->update(['is_bill_printed' => 1]);

        return view('admin.invoice.print', compact('ord', 'imagepath', 'orderDetails', 'print_no'));
    }

    public function generatePDF($id)
    {
        $ord = $this->invoice->find($id);
        \TaskHelper::authorizeOrg($ord);
        $orderDetails = InvoiceDetail::where('invoice_id', $id)->get();
        $imagepath = \Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.invoice.generateInvoicePDF', compact('ord', 'imagepath', 'orderDetails'));
        $file = $id.'_'.$ord->name.'_'.str_replace(' ', '_', $ord->client->name).'.pdf';

        if (\File::exists('reports/'.$file)) {
            \File::Delete('reports/'.$file);
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
        \TaskHelper::authorizeOrg($orders);
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

        return \Response::json($return_array);
    }

    public function postOrdertoInvoice(Request $request, $id)
    {

        //dd($id);
        $order = \App\Models\Orders::find($id);
        \TaskHelper::authorizeOrg($order);

        $orderdetails = OrderDetail::where('order_id', $order->order_id)->get();
        $ckfiscalyear = \App\Models\Fiscalyear::where('current_year', '1')
            ->where('start_date', '<=', date('Y-m-d'))
            ->where('end_date', '>=', date('Y-m-d'))
            ->first();
        if (! $ckfiscalyear) {
            return \Redirect::back()->withErrors(['Please update fiscal year <a href="/admin/fiscalyear/create">Click Here</a>!']);
        }
        $bill_no = \App\Models\Invoice::select('bill_no')
            ->where('fiscal_year', $ckfiscalyear->fiscal_year)
            ->orderBy('bill_no', 'desc')
            ->first();
        $bill_no = $bill_no->bill_no + 1;
        //dd($orderdetails);
        $invoice = new Invoice();

        $invoice->bill_no = $bill_no;
        $invoice->user_id = \Auth::user()->id;
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
            'tag_id' => env('SALES_TAG_ID'),
            'entrytype_id' => \FinanceHelper::get_entry_type_id('journal'),
            'number' => $invoice->id,
            'org_id' => \Auth::user()->org_id,
            'user_id' => \Auth::user()->id,
            'date' => date('Y-m-d'),
            'fiscal_year_id' => \FinanceHelper::cur_fisc_yr()->id,
            'dr_total' => $invoice->total_amount,
            'cr_total' => $invoice->total_amount,
        ]);

        $clients = \App\Models\Client::find($invoice->client_id);
        $entry_item = \App\Models\Entryitem::create([
            'entry_id' => $entry->id,
            'dc' => 'C',
            'ledger_id' => $clients->ledger_id,
            'amount' => $invoice->total_amount,
            'narration' => 'Purchase being made',
        ]);

        $entry_item = \App\Models\Entryitem::create([
            'entry_id' => $entry->id,
            'dc' => 'D',
            'ledger_id' => \FinanceHelper::get_ledger_id('SALES_LEDGER_ID'),
            'amount' => $invoice->total_amount,
            'narration' => 'Purchase being made',
        ]);

        return redirect('/admin/invoice1');
    }

    /**
     * Delete Confirm.
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalConverttoInvoice($id)
    {
        $error = null;

        $orders = \App\Models\Orders::find($id);
        \TaskHelper::authorizeOrg($orders);
        $modal_title = 'Convert This to Invoice';

        $modal_route = route('admin.invoice.change', ['id' => $orders->id]);

        $modal_body = 'Are you Sure you convert This Invoice?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function invoiceVoid($id)
    {
        $error = null;

        $invoice = $this->invoice->find($id);
         \TaskHelper::authorizeOrg($invoice);
        $modal_title = 'Void invoice';

        $modal_route = route('admin.salesaccount.void', ['id' => $invoice->id]);

        $modal_body = 'Are you you want to mark invoice with ID: '.$id.'as void';

        return view('modal_void_reason', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function MakeVoid(Request $request, $id)
    {
        $invoice = $this->invoice->find($id);
        \TaskHelper::authorizeOrg($invoice);
        $invoice->update(['is_bill_active' => '0', 'void_reason' => $request->reason]);

        return redirect()->back();
    }

    public function makepayment($id)
    {
        $invoice_id = $id;

        $payment_list = \App\Models\InvoicePayment::where('invoice_id', $id)->orderby('id', 'desc')->get();

        $order_detail = \App\Models\Invoice::find($id);

        \TaskHelper::authorizeOrg($order_detail);

        $lead_name = $order_detail->lead->name;

        $page_title = 'Invoice Payment List';

        $page_description = 'Payment List Of '.$lead_name.' Invoice No '.$id.'';

        return view('admin.invoice.invoicepayment', compact('page_title', 'page_description', 'purchase_id', 'invoice_id', 'payment_list'));
    }

    public function invoicePaymentcreate($id)
    {
        $page_title = 'Invoice Payment Create';
        $page_description = 'create payments of purchase';
        $invoice_id = $id;

        $payment_method = \App\Models\Paymentmethod::orderby('id')->pluck('name', 'id');

        $purchase_order = \App\Models\Invoice::where('id', $id)->first();
        $purchase_total = $purchase_order->total_amount;
        $paid_amount = DB::table('invoice_payment')->where('invoice_id', $id)->sum('amount');
        $payment_remain = $purchase_total - $paid_amount;

        return view('admin.invoice.paymentcreate', compact('page_title', 'page_description', 'invoice_id', 'payment_method', 'payment_remain'));
    }

    public function InvoicePaymentPost(Request $request, $id)
    {
        $attributes = $request->all();
        $attributes['created_by'] = \Auth::user()->id;
        $invoice = \App\Models\Invoice::find($id);
        if ($request->file('attachment')) {
            $stamp = time();
            $file = $request->file('attachment');
            //dd($file);
            $destinationPath = public_path().'/attachment/';
            $filename = $file->getClientOriginalName();
            $request->file('attachment')->move($destinationPath, $stamp.'_'.$filename);

            $attributes['attachment'] = $stamp.'_'.$filename;
        }

        \App\Models\InvoicePayment::create($attributes);

        $paid_amount = DB::table('invoice_payment')->where('invoice_id', $id)->sum('amount');

        $sale_order = \App\Models\Invoice::find($id);

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

        $customer_ledger = $invoice->client->ledger_id;
        if(!$customer_ledger){

            Flash::error("Ceate custome Ledger first !!");

            return redirect()->back();


        }


        //ENTRY FOR Total AMOUNT
        $attributes['entrytype_id'] = \FinanceHelper::get_entry_type_id('receipt'); //receipt
        $attributes['tag_id'] = '19'; //Invoice Payment
        $attributes['user_id'] = \Auth::user()->id;
        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['number'] = $id;
        $attributes['date'] = \Carbon\Carbon::today();
        $attributes['dr_total'] = $request->amount;
        $attributes['cr_total'] = $request->amount;
        $attributes['source'] = 'Invoice Payment';
        $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
        $entry = \App\Models\Entry::create($attributes);

        //Sales account
        $sub_amount = new \App\Models\Entryitem();
        $sub_amount->entry_id = $entry->id;
        $sub_amount->user_id = \Auth::user()->id;
        $sub_amount->org_id = \Auth::user()->org_id;
        $sub_amount->dc = 'C';
        $sub_amount->ledger_id = $customer_ledger;
        $sub_amount->amount = $request->amount;
        $sub_amount->narration = 'Invoice Receipt Made';
        $sub_amount->save();

        // cash account
        $cash_amount = new \App\Models\Entryitem();
        $cash_amount->entry_id = $entry->id;
        $cash_amount->user_id = \Auth::user()->id;
        $cash_amount->org_id = \Auth::user()->org_id;
        $cash_amount->dc = 'D';
        $cash_amount->ledger_id = $request->payment_method; //
        $cash_amount->amount = $request->amount;
        $cash_amount->narration = 'Receipt';
        $cash_amount->save();

        Flash::success('Receipt Created');

        return redirect('/admin/invoice/payment/'.$id.'');
    }

    public function invoicePaymentshow($id)
    {
        $page_title = 'Invoice Receipt #'.$id;
        $page_description = 'showing payments of payment #'.$id;
        $invoice_id = $id;

        $payment_method = \App\Models\Paymentmethod::orderby('id')->pluck('name', 'id');

        $edit = \App\Models\InvoicePayment::find($id);

        return view('admin.invoice.showpayment', compact('page_title', 'page_description', 'edit'));
    }

    private function updateentries($invoice_id, $request)
    {
        $invoice = $this->invoice->find($invoice_id);
        $entrytype=\App\Models\Entrytype::where('id',\FinanceHelper::get_entry_type_id('sales'))->first();
        $stockentrytype=\App\Models\Entrytype::where('id',\FinanceHelper::get_entry_type_id('inv'))->first();

        if ($invoice->entry_id) {
            $entry = \App\Models\Entry::find($invoice->entry_id);
            $attributes = [
                'tag_id' => '6',
                'entrytype_id' => \FinanceHelper::get_entry_type_id('sales'),
                'number' =>$entrytype->code.'-'.$invoice->id,
                'ref_id' => $invoice->id,
                'bill_no' => $invoice->bill_no,
                'org_id' => \Auth::user()->org_id,
                'user_id' => \Auth::user()->id,
                'date' => date('Y-m-d'),
                'dr_total' => $request->final_total,
                'cr_total' => $request->final_total,
                'source' => 'TAX_INVOICE',
            ];
            $entry->update($attributes);
            $clients = \App\Models\Client::find($invoice->client_id);
            \App\Models\Entryitem::where('entry_id', $entry->id)->delete();
            $id=\App\Models\InvoiceDetail::where('invoice_id', $invoice->id)->get();
            foreach($id as $amount){
                $producttypemaster=\App\Models\Product::where('id', $amount->product_id)->select('product_type_id','product_division','cost')->first();
                    $entry_item = \App\Models\Entryitem::create([
                        'entry_id' => $entry->id,
                        'dc' => 'C',
                         'ledger_id' =>\App\Models\ProductTypeMaster::where('id', $producttypemaster->product_type_id)->first()->ledger_id,  //Sales Ledger 39
                         'amount' => $amount->total,
                        'narration' => 'Sales being made',
                    ]);
            }
            //send amount before tax to customer ledger
            $entry_item = \App\Models\Entryitem::create([
                'entry_id' => $entry->id,
                'dc' => 'D',
                'ledger_id' => $clients->ledger_id,
                'amount' => $request->final_total,
                'narration' => 'Sales being made',
            ]);

            //send the taxable amount to SALES TAX LEDGER
            $entry_item = \App\Models\Entryitem::create([
                'entry_id' => $entry->id,
                'dc' => 'C',
                'ledger_id' => \FinanceHelper::get_ledger_id('SALES_TAX_LEDGER'), //Sales Tax Ledger
                'amount' => $request->taxable_tax,
                'narration' => 'Tax to pay',
            ]);

            return 0;
        } else {
            $entry = \App\Models\Entry::create([
                'tag_id' => '6',
                'entrytype_id' => \FinanceHelper::get_entry_type_id('sales'),
                'number' => $entrytype->code.'-'.$invoice->id,
                'ref_id' => $invoice->id,
                'bill_no' => $invoice->bill_no,
                'org_id' => \Auth::user()->org_id,
                'user_id' => \Auth::user()->id,
                'date' => date('Y-m-d'),
                'dr_total' => $request->final_total,
                'cr_total' => $request->final_total,
                'fiscal_year_id' => \FinanceHelper::cur_fisc_yr()->id,
                'source' => 'TAX_INVOICE',
            ]);

            $clients = \App\Models\Client::find($invoice->client_id);
            $id=\App\Models\InvoiceDetail::where('invoice_id', $invoice->id)->get();
           //send total to sales ledger
            foreach($id as $amount){
                $producttypemaster=\App\Models\Product::where('id', $amount->product_id)->select('product_type_id','product_division','cost')->first();
               
                    $entry_item = \App\Models\Entryitem::create([
                        'entry_id' => $entry->id,
                        'dc' => 'C',
                        'ledger_id' =>\App\Models\ProductTypeMaster::where('id', $producttypemaster->product_type_id)->first()->ledger_id,  //Sales Ledger 39
                        'amount' => $amount->total,
                        'narration' => 'Sales being made',
                    ]);
             }
             //send amount before tax to customer ledger
            $entry_item = \App\Models\Entryitem::create([
                'entry_id' => $entry->id,
                'dc' => 'D',
                'ledger_id' => $clients->ledger_id,
                'amount' => $request->final_total,
                'narration' => 'Sales being made',
            ]);

            //send the taxable amount to SALES TAX LEDGER
            $entry_item = \App\Models\Entryitem::create([
                'entry_id' => $entry->id,
                'dc' => 'C',
                'ledger_id' =>  \FinanceHelper::get_ledger_id('SALES_TAX_LEDGER'), //Sales Tax Ledger
                'amount' => $request->taxable_tax,
                'narration' => 'Tax to pay',
            ]);

            $invoice->update(['entry_id' => $entry->id]);
        }
    }
    // private function updateentries($invoice_id, $request)
    // {
    //     $invoice = $this->invoice->find($invoice_id);
    //     $entrytype=\App\Models\Entrytype::where('id',\FinanceHelper::get_entry_type_id('sales'))->first();

    //     if ($invoice->entry_id) {
    //         $entry = \App\Models\Entry::find($invoice->entry_id);
    //         $attributes = [
    //             'tag_id' => '6',
    //             'entrytype_id' => \FinanceHelper::get_entry_type_id('sales'),
    //             'number' =>$entrytype->code.'-'.$invoice->id,
    //             'ref_id' => $invoice->id,
    //             'bill_no' => $invoice->bill_no,
    //             'org_id' => \Auth::user()->org_id,
    //             'user_id' => \Auth::user()->id,
    //             'date' => date('Y-m-d'),
    //             'dr_total' => $request->final_total,
    //             'cr_total' => $request->final_total,
    //             'source' => 'TAX_INVOICE',
    //         ];
    //         $entry->update($attributes);
    //         $clients = \App\Models\Client::find($invoice->client_id);
    //         \App\Models\Entryitem::where('entry_id', $entry->id)->delete();
    //         $id=\App\Models\InvoiceDetail::where('invoice_id', $invoice->id)->get();
    //         foreach($id as $amount){
    //             $producttypemaster=\App\Models\Product::where('id', $amount->product_id)->select('product_type_id','product_division','cost')->first();
               
    //             if($producttypemaster->product_division == 'raw_material'){
    //                 $entry_item = \App\Models\Entryitem::create([
    //                     'entry_id' => $entry->id,
    //                     'dc' => 'C',
    //                      'ledger_id' =>\App\Models\ProductTypeMaster::where('id', $producttypemaster->product_type_id)->first()->ledger_id,  //Sales Ledger 39
    //                      'amount' => $amount->price,
    //                     'narration' => 'Sales being made',
    //                 ]);
    //             $entry_item = \App\Models\Entryitem::create([
    //                 'entry_id' => $entry->id,
    //                 'dc' => 'D',
    //                  'ledger_id' =>\App\Models\ProductTypeMaster::where('id', $producttypemaster->product_type_id)->first()->cogs_ledger_id,  //Sales Ledger 39
    //                  'amount' => $producttypemaster->cost,
    //                 'narration' => 'impSales being made',
    //             ]);
    //             $entry_item = \App\Models\Entryitem::create([
    //                 'entry_id' => $entry->id,
    //                 'dc' => 'C',
    //                  'ledger_id' =>\App\Models\ProductTypeMaster::where('id', $producttypemaster->product_type_id)->first()->purchase_ledger_id,  //Sales Ledger 39
    //                  'amount' => $producttypemaster->cost,
    //                 'narration' => 'Sales being made',
    //             ]);
    //         }
    //         }

    //         //send the taxable amount to SALES TAX LEDGER
    //         $entry_item = \App\Models\Entryitem::create([
    //             'entry_id' => $entry->id,
    //             'dc' => 'C',
    //             'ledger_id' => \FinanceHelper::get_ledger_id('SALES_TAX_LEDGER'), //Sales Tax Ledger
    //             'amount' => $request->taxable_tax,
    //             'narration' => 'Tax to pay',
    //         ]);

    //         return 0;
    //     } else {
    //         $entry = \App\Models\Entry::create([
    //             'tag_id' => '6',
    //             'entrytype_id' => \FinanceHelper::get_entry_type_id('sales'),
    //             'number' => $entrytype->code.'-'.$invoice->id,
    //             'ref_id' => $invoice->id,
    //             'bill_no' => $invoice->bill_no,
    //             'org_id' => \Auth::user()->org_id,
    //             'user_id' => \Auth::user()->id,
    //             'date' => date('Y-m-d'),
    //             'dr_total' => $request->final_total,
    //             'cr_total' => $request->final_total,
    //             'fiscal_year_id' => \FinanceHelper::cur_fisc_yr()->id,
    //             'source' => 'TAX_INVOICE',
    //         ]);

    //         $clients = \App\Models\Client::find($invoice->client_id);
    //         $id=\App\Models\InvoiceDetail::where('invoice_id', $invoice->id)->get();
    //        //send total to sales ledger
    //         foreach($id as $amount){
    //         $producttypemaster=\App\Models\Product::where('id', $amount->product_id)->select('product_type_id','product_division','cost')->first();
           
    //         if($producttypemaster->product_division == 'raw_material'){
    //             $entry_item = \App\Models\Entryitem::create([
    //                 'entry_id' => $entry->id,
    //                 'dc' => 'C',
    //                  'ledger_id' =>\App\Models\ProductTypeMaster::where('id', $producttypemaster->product_type_id)->first()->ledger_id,  //Sales Ledger 39
    //                  'amount' => $amount->price,
    //                 'narration' => 'Sales being made',
    //             ]);
    //         $entry_item = \App\Models\Entryitem::create([
    //             'entry_id' => $entry->id,
    //             'dc' => 'D',
    //              'ledger_id' =>\App\Models\ProductTypeMaster::where('id', $producttypemaster->product_type_id)->first()->cogs_ledger_id,  //Sales Ledger 39
    //              'amount' => $producttypemaster->cost,
    //             'narration' => 'impSales being made',
    //         ]);
    //         $entry_item = \App\Models\Entryitem::create([
    //             'entry_id' => $entry->id,
    //             'dc' => 'C',
    //              'ledger_id' =>\App\Models\ProductTypeMaster::where('id', $producttypemaster->product_type_id)->first()->purchase_ledger_id,  //Sales Ledger 39
    //              'amount' => $producttypemaster->cost,
    //             'narration' => 'Sales being made',
    //         ]);
    //     }
    //     }
    //          //send amount before tax to customer ledger
    //         $entry_item = \App\Models\Entryitem::create([
    //             'entry_id' => $entry->id,
    //             'dc' => 'D',
    //             'ledger_id' => $clients->ledger_id,
    //             'amount' => $request->final_total,
    //             'narration' => 'Sales being made',
    //         ]);

    //         //send the taxable amount to SALES TAX LEDGER
    //         $entry_item = \App\Models\Entryitem::create([
    //             'entry_id' => $entry->id,
    //             'dc' => 'C',
    //             'ledger_id' =>  \FinanceHelper::get_ledger_id('SALES_TAX_LEDGER'), //Sales Tax Ledger
    //             'amount' => $request->taxable_tax,
    //             'narration' => 'Tax to pay',
    //         ]);

    //         $invoice->update(['entry_id' => $entry->id]);
    //     }
    // }

    private function convertdate($date)
    {
        $date = explode('-', $date);
        $cal = new \App\Helpers\NepaliCalendar();
        $converted = $cal->eng_to_nep($date[0], $date[1], $date[2]);
        $nepdate = $converted['year'].'.'.$converted['nmonth'].'.'.$converted['date'];

        return $nepdate;
    }

    public function postInvoicetoIRD($id)
    {
        $invoice = \App\Models\Invoice::find($id);

        //dd($invoice);
        $invoicemeta = \App\Models\InvoiceMeta::orderBy('id', 'desc')->where('invoice_id', $invoice->id)->first();

        if ($invoicemeta === null && $invoice) {
            $invoicemeta = new \App\Models\InvoiceMeta();
            $invoicemeta->invoice_id = $invoice->id;
            $invoicemeta->sync_with_ird = 0;
            $invoicemeta->is_bill_active = 1;
            $invoicemeta->save();
        }

        Audit::log(Auth::user()->id, ' Invoice', 'Final Bill Is Created: ID-'.$invoice->id.'');

        if ($invoice) {
            if ($invoice->client) {
                $guest_name = $invoice->client->name;
                $buyer_pan = $invoice->client->vat;
            } else {
                $guest_name = $invoice->name;
                $buyer_pan = $invoice->customer_pan;
            }

            // dd($guest_name, $buyer_pan);

            $bill_date_nepali = $this->convertdate($invoice->bill_date);
            $bill_today_date_nep = $this->convertdate(date('Y-m-d'));

            $data = json_encode(['username' => env('IRD_USERNAME'), 'password' => env('IRD_PASSWORD'), 'seller_pan' => env('SELLER_PAN'), 'buyer_pan' => $buyer_pan, 'fiscal_year' => $invoice->fiscal_year, 'buyer_name' => $guest_name, 'invoice_number' => env('SALES_BILL_PREFIX').$invoice->bill_no, 'invoice_date' => $bill_date_nepali, 'total_sales' => $invoice->total_amount, 'taxable_sales_vat' => $invoice->taxable_amount, 'vat' => $invoice->tax_amount, 'excisable_amount' => 0, 'excise' => 0, 'taxable_sales_hst' => 0, 'hst' => 0, 'amount_for_esf' => 0, 'esf' => 0, 'export_sales' => 0, 'tax_exempted_sales' => 0, 'isrealtime' => true, 'datetimeClient' => $bill_today_date_nep]);

            //dd($data);

            $irdsync = new \App\Models\NepalIRDSync();
            $response = $irdsync->postbill($data);

            if ($response == 200) {
                \App\Models\InvoiceMeta::where('invoice_id', $invoice->id)->first()->update(['sync_with_ird' => 1, 'is_realtime' => 1]);

                Audit::log(Auth::user()->id, 'Hotel Invoice', 'Successfully Posted to IRD, ID-'.env('HOTEL_BILL_PREFIX').$invoice->bill_no.' Response:'.$response.'');

                Flash::success(' Successfully Posted to IRD. Code: '.$response.'');

                return redirect()->back();
            } else {
                if ($response == 101) {
                    \App\Models\InvoiceMeta::where('invoice_id', $invoice->id)->first()->update(['sync_with_ird' => 1, 'is_realtime' => 1]);
                } else {
                    \App\Models\InvoiceMeta::where('invoice_id', $invoice->id)->first()->update(['is_realtime' => 1]);
                }
                Audit::log(Auth::user()->id, 'Invoice', 'Failed To post in IRD, ID-'.env('HOTEL_BILL_PREFIX').$invoice->bill_no.', Response:'.$response.'');
                Flash::error(' Post Cannot Due to Response Code: '.$response.'');

                return redirect()->back();
            }
        }

        Flash::error('Bill No Not Found');

        return \Redirect::back();
    }

    public function returnfromird()
    {
        $page_title = 'Admin | Invoice | Sales | Return';
        $fiscalyear = \App\Models\Fiscalyear::orderBy('id', 'desc')->where('org_id', \Auth::user()->org_id)->get();
        $description = 'Sales Return Book';
        $creditnum = \App\Models\InvoiceMeta::orderBy('credit_note_no', 'desc')->where('credit_note_no', '!=', 'null')->first()->credit_note_no; 
        $credit_note_no=isset($creditnum)?(int)$creditnum+1:0+1;
        return view('admin.invoice.invoicereturn', compact('credit_note_no', 'page_title','', 'fiscalyear','description'));
    }

    public function returnfromirdpost(Request $request)
    {
        
        $invoice = \App\Models\Invoice::where('org_id', \Auth::user()->org_id)->where('fiscal_year', $request->fiscal_year)->where('bill_no', $request->bill_no)->first();

        //  dd($invoice);
        $invoicemeta = \App\Models\InvoiceMeta::orderBy('id', 'desc')->where('invoice_id', $invoice->id)->first();
        // dd($invoicemeta);
        if ($invoicemeta === null && $invoice) {
            // dd('check');
            $invoicemeta = new \App\Models\InvoiceMeta();
            $invoicemeta->invoice_id = $invoice->id;
            $invoicemeta->sync_with_ird = 0;
            $invoicemeta->is_bill_active = 1;
            $invoicemeta->save();
        }

        // dd($invoicemeta);

        // dd($request->all());

        if (count($invoice) == 1) {
            
            if ($invoice->client) {
                
                $guest_name = $invoice->client->name;
                $guest_pan = $invoice->client->vat;
            } else {
                $guest_name = $invoice->name;
                $guest_pan = $invoice->customer_pan;
            }

            $bill_date_nepali = $this->convertdate($invoice->bill_date);
            $cancel_date = $this->convertdate($request->cancel_date);

            $bill_today_date_nep = $this->convertdate(date('Y-m-d'));

            // dd($bill_today_date_nep);

            //POSTING DATA TO IRD
            $data = json_encode(['username' => env('IRD_USERNAME'), 'password' => env('IRD_PASSWORD'), 'seller_pan' => env('SELLER_PAN'), 'buyer_pan' => $guest_pan, 'fiscal_year' => $invoice->fiscal_year, 'buyer_name' => $guest_name, 'ref_invoice_number' => env('SALES_BILL_PREFIX').''.$invoice->bill_no, 'credit_note_date' => $cancel_date, 'credit_note_number' => $request->credit_note_no, 'reason_for_return' => $request->void_reason, 'total_sales' => $invoice->total_amount, 'taxable_sales_vat' => $invoice->taxable_amount, 'vat' => $invoice->tax_amount, 'excisable_amount' => 0, 'excise' => 0, 'taxable_sales_hst' => 0, 'hst' => 0, 'amount_for_esf' => 0, 'esf' => 0, 'export_sales' => 0, 'tax_exempted_sales' => 0, 'isrealtime' => true, 'datetimeClient' => $bill_today_date_nep]);
            if(env('IS_IRD')){
            $irdsync = new \App\Models\NepalIRDSync();
            $response = $irdsync->returnbill($data);
            }
            else{
                $response=200;
            }

            if ($response == 200) {
                $clients = \App\Models\Client::find($invoice->client_id);

                if ($clients->ledger_id) {
                    $attributes_order['entrytype_id'] = 7; //crdeitnotes
                    $attributes_order['tag_id'] = 3; //crdeitnotes
                    $attributes_order['user_id'] = \Auth::user()->id;
                    $attributes_order['org_id'] = \Auth::user()->org_id;
                    $attributes_order['number'] = $invoice->$id;
                    // $attributes_order['resv_id'] = $invoice->reservation_id;
                    $attributes_order['source'] = 'Sales_Return';
                    $attributes_order['date'] = \Carbon\Carbon::today();
                    $attributes_order['notes'] = 'Credit Return: '.$invoice->id.'';
                    $attributes_order['dr_total'] = $invoice->total_amount;
                    $attributes_order['cr_total'] = $invoice->total_amount;
                    $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
                    $entry = \App\Models\Entry::create($attributes_order);

                    $cash_amount = new \App\Models\Entryitem();
                    $cash_amount->entry_id = $entry->id;
                    $cash_amount->dc = 'C';
                    $cash_amount->ledger_id = $clients->ledger_id;
                    $cash_amount->amount = $invoice->total_amount;
                    $cash_amount->narration = 'being sales Return ';
                    $cash_amount->save();

                    $cash_amount = new \App\Models\Entryitem();
                    $cash_amount->entry_id = $entry->id;
                    $cash_amount->dc = 'D';
                    $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('SALES_RETURN_LEDGER');
                    $cash_amount->amount = $invoice->total_amount;
                    $cash_amount->narration = 'being Sales Return';
                    $cash_amount->save();
                }

                //UPDATING THE ORDERS TABLE
                $invoicemeta->update(['is_bill_active' => 0, 'void_reason' => $request->void_reason, 'cancel_date' => $request->cancel_date, 'credit_note_no' => $request->credit_note_no, 'is_realtime' => 1, 'credit_user_id' => \Auth::user()->id]);
                //UPDATE AUDIT LOG
                Audit::log(Auth::user()->id, 'Invoice', 'Bill Is Returned To IRD: ID-'.$invoice->id.' Response :'.$response.'');
                Flash::success('Successfully Returned from IRD. Code: '.$response.'');

                return redirect()->back();
            } else {
                if ($response == 101) {
                    $invoicemeta->update(['is_bill_active' => 0, 'is_realtime' => 1]);
                } else {
                    $invoicemeta->update(['is_realtime' => 1]);
                }

                Audit::log(Auth::user()->id, 'Invoice', 'Bill Is Returned To IRD: ID-'.$invoice->bill_no.' Response :'.$response.'');
                Flash::error('Return Cannot Due to Response Code: '.$response.'');
                return redirect()->back();
            }
        } else {
            Audit::log(Auth::user()->id, 'Invoice', 'Bill Is Not Found: ID-'.$invoice->id.'');
            \Flash::warning('No Bill Found Of this Number');

            return redirect()->back();
        }

        return redirect()->back();
    }

    public function returnsales()
    {
        $page_title = 'Return Sales Book';
        $page_description = 'Return Sales Book';
        $users = \App\User::where('enabled', '1')->pluck('username', 'id')->all();
        $description = 'Return Sales List';
        return view('admin.invoice.returnsaleslist', compact( 'page_description', 'page_title', 'users','description'));
    }

    public function returnsaleslist(Request $request)
    {

    
        $page_title = 'Admin | POS | Sales Return Book';
        $users = \App\User::where('enabled', '1')->pluck('username', 'id')->all();

        $op = \Request::get('op');

        $startdate = $request->start_date;
        $enddate = $request->end_date;

        $invoice = \App\Models\Invoice::select( 'invoice_meta.*','invoice.*')
            ->leftjoin('invoice_meta', 'invoice.id', '=', 'invoice_meta.invoice_id')
            ->where('invoice.bill_date', '>=', $request->start_date)
            ->where('invoice.bill_date', '<=', $request->end_date)
            ->where('invoice.org_id', \Auth::user()->org_id)
            ->where('invoice_meta.is_bill_active', 0)
            ->where(function ($query) use ($request) {
                if ($request->user_id) {
                    return $query->where('invoice.user_id', $request->user_id);
                }
            })

            ->paginate(50);

        if ($op == 'print') {
            $invoice_print = \App\Models\Invoice::select('invoice_meta.*','invoice.*')
                ->leftjoin('invoice_meta', 'invoice.id', '=', 'invoice_meta.invoice_id')
                ->where('invoice.bill_date', '>=', $request->start_date)
                ->where('invoice.bill_date', '<=', $request->end_date)
                ->where('invoice.org_id', \Auth::user()->org_id)
                ->where('invoice_meta.is_bill_active', 0)
                ->where(function ($query) use ($request) { 
                    if ($request->user_id) {
                        return $query->where('invoice.user_id', $request->user_id);
                    }
                })
                ->get();

            return view('print.returnbook', compact('invoice_print', 'startdate', 'enddate'));
        } elseif ($op == 'pdf') {
            $invoice_pdf = \App\Models\Invoice::select('invoice_meta.*','invoice.*')
                ->leftjoin('invoice_meta', 'invoice.id', '=', 'invoice_meta.invoice_id')
                ->where('invoice.bill_date', '>=', $request->start_date)
                ->where('invoice.bill_date', '<=', $request->end_date)
                ->where('invoice.org_id', \Auth::user()->org_id)
                ->where('invoice_meta.is_bill_active', 0)
                ->where(function ($query) use ($request) {
                    if ($request->user_id) {
                        return $query->where('invoice.user_id', $request->user_id);
                    }
                })
                ->get();

            // dd($invoice_pdf);
            $pdf = \PDF::loadView('pdf.returnbook', compact('invoice_pdf', 'fiscal_year', 'startdate', 'enddate'))->setPaper('a4', 'landscape');
            $file = 'Report_returnbook_filtered'.date('_Y_m_d').'.pdf';
            if (File::exists('reports/'.$file)) {
                File::Delete('reports/'.$file);
            }

            return $pdf->download($file);
        }

        $request = $request->all();
        $page_description= 'Return Sales List';
        $description = $page_description;
        // dd($invoice);

        return view('admin.invoice.returnsaleslist', compact('page_description', 'page_title', 'users', 'request', 'invoice','description'));
    }

    public function materializeview()
    {
        $page_title = 'Admin | Invoice | Sales | Materialize | Search';

        $users = \App\User::where('enabled', '1')->pluck('username', 'username as id')->all();
        $description = 'Invoice Materialize View';
        return view('admin.invoice.sales_materalize', compact('page_title',  'users','description'));
    }

    public function materializeviewresult(Request $request)
    {
        $page_title = 'Admin | Invoice | Sales | Materialize | Results';

        //dd($page_title);

        $users = \App\User::where('enabled', '1')->pluck('username', 'username as id')->all();

        $op = \Request::get('op');
        $startdate = $request->start_date;
        $enddate = $request->end_date;

        $sales = \DB::table('invoice_materialize_view')->where('bill_date', '>=', $request->start_date)
            ->where('bill_date', '<=', $request->end_date)
            ->where(function ($query) use ($request) {
                if ($request->user_id) {
                    return $query->where('entered_by', $request->user_id);
                }
            })->paginate(50);

        if ($op == 'print') {
            $sales_print = DB::table('invoice_materialize_view')->where('bill_date', '>=', $request->start_date)
                ->where('bill_date', '<=', $request->end_date)
                ->where(function ($query) use ($request) {
                    if ($request->user_id) {
                        return $query->where('entered_by', $request->user_id);
                    }
                })->get();

            return view('print.materializebook', compact('sales_print', 'startdate', 'enddate'));
        } elseif ($op == 'pdf') {
            $sales_pdf = DB::table('invoice_materialize_view')->where('bill_date', '>=', $request->start_date)
                ->where('bill_date', '<=', $request->end_date)
                ->where(function ($query) use ($request) {
                    if ($request->user_id) {
                        return $query->where('entered_by', $request->user_id);
                    }
                })->get();

            // dd($invoice_pdf);
            $pdf = \PDF::loadView('pdf.materializebook', compact('sales_pdf', 'fiscal_year', 'startdate', 'enddate'))->setPaper('a4', 'landscape');
            $file = 'Report_materializebook_filtered'.date('_Y_m_d').'.pdf';
            if (File::exists('reports/'.$file)) {
                File::Delete('reports/'.$file);
            }

            return $pdf->download($file);
        }


        $request = $request->all();
        $description = 'Invoice Materialize View';
        return view('admin.invoice.sales_materalize', compact('page_title',  'users', 'sales', 'request','description'));
    }

     public function showcreditnote($id)
    {
        $ord = $this->invoice->find($id);
        
        $page_title = 'Credit Note';
        $page_description = 'View Order';
        $orderDetails = InvoiceDetail::where('invoice_id', $ord->id)->get();
        $returnnote= \App\Models\InvoiceMeta::where('invoice_id', $ord->id)->where('is_bill_active',0)->select('void_reason','credit_note_no')->first();
        //dd($orderDetails);
        $imagepath = \Auth::user()->organization->logo;
        // dd($imagepath);
        return view('admin.orders.credit_note_show', compact('returnnote','ord', 'imagepath', 'page_title', 'page_description', 'orderDetails'));
    }

    public function printcreditnote($id, $type)
    {
        $ord = $this->invoice->find($id);
        $orderDetails = InvoiceDetail::where('invoice_id', $ord->id)->get();
        $returnnote= \App\Models\InvoiceMeta::where('invoice_id', $ord->id)->where('is_bill_active',0)->select('void_reason','credit_note_no')->first();
        $imagepath = \Auth::user()->organization->logo;
        if($type == 'print'){
           // $pdf =  \PDF::loadView('admin.orders.credit_note_print', compact('ord', 'imagepath', 'orderDetails'));
           // return  $pdf->download($file);
            return view('admin.orders.credit_note_print', compact('ord', 'returnnote','imagepath', 'page_title', 'page_description', 'orderDetails'));


        }


        return view('admin.orders.generatecreditnotePDF', compact('ord', 'imagepath', 'orderDetails'));
        $file = $id . '_' . $ord->name . '_' . str_replace(' ', '_', $ord->client->name) . '.pdf';
        if (\File::exists('reports/' . $file)) {
            \File::Delete('reports/' . $file);
        }
        return $pdf->download($file);
    }
    public function getUserOutlets(){


        if (\Auth::user()->hasRole('admins')) {
            
            $outlets = \App\Models\PosOutlets::get();
            // dd($outlets);
        } else {
       
            $outletusers = \App\Models\OutletUser::where('user_id', \Auth::user()->id)->get()->pluck('outlet_id');
            
            $outlets = \App\Models\PosOutlets::whereIn('id', $outletusers)
                ->orderBy('id', 'DESC')
                ->where('enabled', 1)
                ->get();
        }

        return $outlets;
    }
    public function duepayment()
    {

        $orders = Invoice::where(function($query){
                    $start_date = \Request::get('start_date');
                    $end_date = \Request::get('end_date');
                    if($start_date && $end_date){
                        return $query->where('due_date','>=',$start_date)
                            ->where('due_date','<=',$end_date);
                    }

                })
                ->where('org_id', \Auth::user()->org_id)
                ->orderBy('id', 'desc')
                ->paginate(30);
        $page_title = 'Invoice Due Payment';
        $page_description = 'Manage Invoice Payment Due';

        $outlets = $this->getUserOutlets();
        return view('admin.invoice.due_payment', compact('orders', 'page_title', 'page_description','outlets'));
    }

    


}
