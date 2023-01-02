<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\GrnDetail;
use App\ProductLocation;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Models\StockMove;
/**
FOR ONLINE ENQUIRY

 **/
class GrnController extends Controller
{
    public function index()
    {
        $purchasereturn = \App\Models\Grn::orderBy('id', 'desc')->paginate(20);

        $page_title = 'Admin | Good Receipt Note';
        $page_description = 'Manage Good Receipt Note';

        return view('admin.grn.index', compact('page_title', 'page_description', 'purchasereturn'));
    }

    public function create()
    {
        $page_title = 'Admin | Good Receipt Note | Create';
        $page_description = 'Creates Good Receipt Note';

        $products = Product::select('id', 'name')->get();
        $users = \App\User::where('enabled', '1')->where('org_id', Auth::user()->org_id)->pluck('first_name', 'id');
        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();
        $clients = Client::select('id', 'name', 'location')->orderBy('id', DESC)->get();

        return view('admin.grn.create', compact('page_title', 'page_description', 'products', 'users', 'productlocation', 'clients'));
    }

    public function show($id)
    {
        $page_title = 'Show Good Receipt Note';

        $page_description = 'Detail of Return';

        $ord = \App\Models\Grn::find($id);
        $orderDetails = \App\Models\GrnDetail::where('supplier_return_id', $ord->id)->get();

        return view('admin.grn.show', compact('page_title', 'page_description', 'ord', 'orderDetails'));
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        $attributes = $request->all();

        $attributes['supplier_id'] = $request->client_id;

        $attributes['org_id'] = Auth::user()->org_id;
        $attributes['client_id'] = $request->customer_id;

        $attributes['tax_amount'] = $request->taxable_tax;
        $attributes['total_amount'] = $request->final_total;


        $purchase_data = \App\Models\Grn::create($attributes);

        $purchase = \App\Models\PurchaseOrder::find($purchase_data->purchase_bill_no);
        $purchase->status = 'GRN Created';
        $purchase->save();

        $product_id = $request->product_id;
        $units = $request->units;
        $purchase_quantity = $request->purchase_quantity;
        $return_quantity = $request->return_quantity;
        $purchase_price = $request->purchase_price;
        $return_price = $request->return_price;
        $return_total = $request->return_total;
        $reason = $request->reason;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new GrnDetail();
                $detail->supplier_return_id = $purchase_data->id;
                $detail->product_id = $product_id[$key];
                $detail->units = $units[$key];
                $detail->purchase_quantity = $purchase_quantity[$key];
                $detail->return_quantity = $return_quantity[$key];
                $detail->purchase_price = $purchase_price[$key];
                $detail->return_price = $return_price[$key];
                $detail->return_total = $return_total[$key];
                $detail->reason = $reason[$key];
                $detail->is_inventory = 1;
                $detail->save();

                // // stockMove information
                // $stockMove = new StockMove();
                // $stockMove->stock_id = $product_id[$key];
                // $stockMove->trans_type = PURCHINVOICE;
                // $stockMove->tran_date = \Carbon\Carbon::now();
                // $stockMove->user_id = \Auth::user()->id;
                // $stockMove->reference = 'store_in_'.$purchase_data->id;
                // $stockMove->order_reference = $purchase_data->id;
                // $stockMove->transaction_reference_id = $purchase_data->id;
                // $stockMove->location = $request->into_stock_location ?? 0;
                // $stockMove->qty = $return_quantity[$key] * \StockHelper::getUnitPrice($detail->units[$key]);
                // $stockMove->price = $return_price[$key];
                // $stockMove->save();
            }
        }

        $custom_items_name = $request->custom_items_name;
        $custom_units = $request->custom_units;
        $custom_purchase_qty = $request->custom_purchase_qty;
        $custom_return_qty = $request->custom_return_qty;
        $custom_purchase_price = $request->custom_purchase_price;
        $custom_return_price = $request->custom_return_price;
        $custom_return_total = $request->custom_return_total;
        $custom_reason = $request->custom_reason;

        foreach ($custom_items_name as $key => $value) {
            if ($value != '') {
                $detail = new GrnDetail();
                $detail->supplier_return_id = $purchase_data->id;
                $detail->description = $custom_items_name[$key];
                $detail->units = $custom_units[$key];
                $detail->purchase_quantity = $custom_purchase_qty[$key];
                $detail->return_quantity = $custom_return_qty[$key];
                $detail->purchase_price = $custom_purchase_price[$key];
                $detail->return_price = $custom_return_price[$key];
                $detail->return_total = $custom_return_total[$key];
                $detail->reason = $custom_reason[$key];
                $detail->is_inventory = 0;
                $detail->save();
            }
        }

        $this->updateEntries($purchase_data->id);
        \DB::commit();

        Flash::success('Good Receipt Note created Successfully.');

        return redirect('/admin/grn');
    }


    public function edit(Request $request, $id)
    {
        $page_title = 'Edit Good Receipt Note';

        $page_description = '';

        $purchasereturn = \App\Models\Grn::find($id);
        $purchase_return_detail = \App\Models\GrnDetail::where('supplier_return_id', $purchasereturn->id)->get();

        $products = Product::select('id', 'name')->get();
        $users = \App\User::where('enabled', '1')->where('org_id', Auth::user()->org_id)->pluck('first_name', 'id');
        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();
        $clients = Client::select('id', 'name', 'location')->orderBy('id', DESC)->get();

        return view('admin.grn.edit', compact('page_title', 'page_description', 'purchasereturn', 'purchase_return_detail', 'products', 'users', 'productlocation', 'clients'));
    }

    public function update(Request $request, $id)
    {
        \DB::beginTransaction();
        $purchasereturn = \App\Models\Grn::find($id);

        $attributes = $request->all();

        $attributes['supplier_id'] = $request->client_id;

        $attributes['org_id'] = Auth::user()->org_id;
        $attributes['client_id'] = $request->customer_id;

        $attributes['tax_amount'] = $request->taxable_tax;
        $attributes['total_amount'] = $request->final_total;

        $purchasereturn->update($attributes);

        \App\Models\GrnDetail::where('supplier_return_id', $purchasereturn->id)->delete();
        // $stockmove = StockMove::where('trans_type', PURCHINVOICE)->where('reference', 'store_in_' . $purchasereturn->id)->delete();

        $product_id = $request->product_id;
        $units = $request->units;
        $purchase_quantity = $request->purchase_quantity;
        $return_quantity = $request->return_quantity;
        $purchase_price = $request->purchase_price;
        $return_price = $request->return_price;
        $return_total = $request->return_total;
        $reason = $request->reason;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new \App\Models\GrnDetail();
                $detail->supplier_return_id = $purchasereturn->id;
                $detail->product_id = $product_id[$key];
                $detail->units = $units[$key];
                $detail->purchase_quantity = $purchase_quantity[$key];
                $detail->return_quantity = $return_quantity[$key];
                $detail->purchase_price = $purchase_price[$key];
                $detail->return_price = $return_price[$key];
                $detail->return_total = $return_total[$key];
                $detail->reason = $reason[$key];
                $detail->is_inventory = 1;
                $detail->save();

                // stockMove information
                // $stockMove = new StockMove();
                // $stockMove->stock_id = $product_id[$key];
                // $stockMove->trans_type = PURCHINVOICE;
                // $stockMove->tran_date = \Carbon\Carbon::now();
                // $stockMove->user_id = \Auth::user()->id;
                // $stockMove->reference = 'store_in_'.$purchasereturn->id;
                // $stockMove->order_reference = $purchasereturn->id;
                // $stockMove->transaction_reference_id = $purchasereturn->id;
                // $stockMove->location = $request->into_stock_location ?? 0;
                // $stockMove->qty = $return_quantity[$key] * \StockHelper::getUnitPrice($detail->units[$key]);
                // $stockMove->price = $return_price[$key];
                // $stockMove->save();

            }
        }

        $custom_items_name = $request->custom_items_name;
        $custom_units = $request->custom_units;
        $custom_purchase_qty = $request->custom_purchase_qty;
        $custom_return_qty = $request->custom_return_qty;
        $custom_purchase_price = $request->custom_purchase_price;
        $custom_return_price = $request->custom_return_price;
        $custom_return_total = $request->custom_return_total;
        $custom_reason = $request->custom_reason;

        foreach ($custom_items_name as $key => $value) {
            if ($value != '') {
                $detail = new \App\Models\GrnDetail();
                $detail->supplier_return_id = $purchasereturn->id;
                $detail->description = $custom_items_name[$key];
                $detail->units = $custom_units[$key];
                $detail->purchase_quantity = $custom_purchase_qty[$key];
                $detail->return_quantity = $custom_return_qty[$key];
                $detail->purchase_price = $custom_purchase_price[$key];
                $detail->return_price = $custom_return_price[$key];
                $detail->return_total = $custom_return_total[$key];
                $detail->reason = $custom_reason[$key];
                $detail->is_inventory = 0;
                $detail->save();
            }
        }

        $this->updateEntries($id);
        \DB::commit();

        Flash::success('Good Receipt Note Updated Successfully.');

        return redirect('/admin/grn');
    }

    public function pdf($id)
    {
        $ord = \App\Models\Grn::find($id);
        $orderDetails = \App\Models\GrnDetail::where('supplier_return_id', $ord->id)->get();
        $imagepath = Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.grn.pdf', compact('ord', 'imagepath', 'orderDetails'));
        $file = $id.'_'.$ord->name.'_'.str_replace(' ', '_', $ord->supplier->name).'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function print($id)
    {
        $ord = \App\Models\Grn::find($id);
        $orderDetails = \App\Models\GrnDetail::where('supplier_return_id', $ord->id)->get();

        $imagepath = Auth::user()->organization->logo;

        return view('admin.grn.print', compact('ord', 'imagepath', 'orderDetails', 'print_no'));
    }

    public function destroy($id)
    {

        //dd($id);
        $ord = \App\Models\Grn::find($id);

        if (! $ord->isdeletable()) {
            abort(403);
        }

        if ($ord->entry_id && $ord->entry_id != '0') {
            $entries = \App\Models\Entry::find($ord->entry_id);
            \App\Models\Entryitem::where('entry_id', $entries->id)->delete();
            \App\Models\Entry::find($ord->entry_id)->delete();
        }

        \App\Models\Grn::find($id)->delete();
        \App\Models\GrnDetail::where('supplier_return_id', $id)->delete();

        Flash::success('Good Receipt Note successfully deleted.');

        return redirect('/admin/grn');
    }

    public function getModalDelete($id)
    {

        //dd($id);
        $error = null;

        $ord = \App\Models\Grn::find($id);

        if (! $ord->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Good Receipt Note';

        $return = \App\Models\Grn::find($id);

        $modal_route = route('admin.supplierreturn.delete', ['id' => $return->id]);

        $modal_body = 'Are you sure you want to delete this Good Receipt Note?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function getPurchaseBillId()
    {
        $term = strtolower(\Request::get('term'));
        $purchasebills = \App\Models\PurchaseOrder::where('purchase_type', 'bills')->select('id')->where('id', 'LIKE', '%'.$term.'%')->take(5)->get();
        $return_array = [];

        foreach ($purchasebills as $v) {
            $return_array[] = ['value' =>sprintf('%08d', $v->id), 'id' =>$v->id];
        }

        return Response::json($return_array);
    }

    public function getPurchaseBillInfo(Request $request)
    {
        $purchasebillsinfo = \App\Models\PurchaseOrder::find($request->purchasebills_id);

        $customer_name = \App\Models\Client::find($purchasebillsinfo->supplier_id)->name;

        // dd($customer_name);
        $purchasedetailinfo = \App\Models\PurchaseOrderDetail::where('order_no', $purchasebillsinfo->id)->get();

        $products = Product::select('id', 'name')->get();
        $data = '';

        foreach ($purchasedetailinfo as $idi) {
            $unit_name = \App\Models\ProductsUnit::find($idi->units)->name;

            if ($idi->is_inventory == 1) {
                $name = \App\Models\Product::find($idi->product_id)->name;

                $data .= '<tr>  
                                <td>
                                <input type="text" class="form-control product_id" name="product"  value="'.$name.'" readonly>
                                  <input type="hidden"  name="product_id[]" value="'.$idi->product_id.'" required="required" readonly>   
                                </td>
                                <td>
                                    <input type="text" class="form-control invoice_price" placeholder="Unit" value="'.$unit_name.'" required="required" readonly>
                                    <input  type="hidden" name="units[]" value="'.$idi->units.'">

                                </td>
                                <td>
                                    <input type="number" class="form-control purchase_quantity" name="purchase_quantity[]" placeholder="Quantity" min="1" value="'.$idi->qty_invoiced.'" required="required" readonly>
                                </td>
                               

                                <td>
                                    <input type="number" class="form-control quantity" name="return_quantity[]" placeholder="Return Quantity" min="1" value="'.$idi->qty_invoiced.'" required="required" >
                                </td>

                                <td>
                                   <input type="number" class="form-control purchase_price" name="purchase_price[]" placeholder="Purchase Price" min="1" value="'.$idi->unit_price.'" required="required" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control price" name="return_price[]" placeholder="Credit Price" min="1" value="'.$idi->unit_price.'" required="required" >
                                </td>

                                 <td>
                                     <input type="number" class="form-control total" name="return_total[]" placeholder="Total" value="'.$idi->total.'" readonly="readonly">
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
                                    <input type="text" class="form-control invoice_price" placeholder="Unit" value="'.$unit_name.'"  readonly>

                                    <input  type="hidden" name="custom_units[]" value="'.$idi->units.'">
                                </td>
                                 
                                <td>
                                    <input type="number" class="form-control purchase_quantity" name="custom_purchase_qty[]" placeholder="Quantity" min="1" value="'.$idi->qty_invoiced.'"  readonly>
                                </td>

                                <td>
                                    <input type="number" class="form-control quantity" name="custom_return_qty[]" placeholder="Return Quantity" min="1" value="'.$idi->qty_invoiced.'" required="required">
                                </td>

                                <td>
                                    <input type="number" class="form-control purchase_price" name="custom_purchase_price[]" placeholder="Credit Qty" min="1" value="'.$idi->unit_price.'" required="required" readonly>
                                </td>

                                <td>
                                    <input type="number" class="form-control price" name="custom_return_price[]" placeholder="Credit Price" min="1" value="'.$idi->unit_price.'" required="required" >
                                </td>
                                <td>
                                 <input type="number" class="form-control total" name="custom_return_total[]" placeholder="Total" value="'.$idi->total.'" readonly="readonly" >
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

        return ['purchasebillsinfo'=>$purchasebillsinfo, 'purchasedetailinfo'=>$data, 'customer_name'=>$customer_name];
    }

    private function updateEntries($orderId)
    {
        $purchasereturn = \App\Models\Grn::find($orderId);

        if ($purchasereturn->entry_id && $purchasereturn->entry_id != '0') { //update the ledgers
            $attributes['entrytype_id'] = \FinanceHelper::get_entry_type_id('debitnote'); //Purchase Return
            $attributes['tag_id'] = '9'; //Debit  Memos
            $attributes['user_id'] = Auth::user()->id;
            $attributes['org_id'] = Auth::user()->org_id;
            $attributes['number'] = $purchasereturn->id;
            $attributes['date'] = \Carbon\Carbon::today();
            $attributes['dr_total'] = $purchasereturn->total_amount;
            $attributes['cr_total'] = $purchasereturn->total_amount;
            $attributes['source'] = 'AUTO_SN';
            $entry = \App\Models\Entry::find($purchasereturn->entry_id);
            $entry->update($attributes);

            // Creddited to Customer or Interest or eq ledger
            $sub_amount = \App\Models\Entryitem::where('entry_id', $purchasereturn->entry_id)->where('dc', 'D')->first();
            $sub_amount->entry_id = $entry->id;
            $sub_amount->user_id = Auth::user()->id;
            $sub_amount->org_id = Auth::user()->org_id;
            $sub_amount->dc = 'D';
            $sub_amount->ledger_id = \App\Models\Client::find($purchasereturn->supplier_id)->ledger_id; //Client ledger
            $sub_amount->amount = $purchasereturn->total_amount;
            $sub_amount->narration = 'Good Receipt Note'; //$request->user_id
            //dd($sub_amount);
            $sub_amount->update();

            // Debitte to Bank or cash account that we are already in
            $cash_amount = \App\Models\Entryitem::where('entry_id', $purchasereturn->entry_id)->where('dc', 'C')->first();
            $cash_amount->entry_id = $entry->id;
            $cash_amount->user_id = Auth::user()->id;
            $cash_amount->org_id = Auth::user()->org_id;
            $cash_amount->dc = 'C';
            $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('PURCHASE_LEDGER_ID'); // Purchase ledger if selected or ledgers from .env
            // dd($cash_amount);
            $cash_amount->amount = $purchasereturn->total_amount;
            $cash_amount->narration = 'Good Receipt Note';
            $cash_amount->update();
        } else {                               //create the new entry items
            $attributes['entrytype_id'] = \FinanceHelper::get_entry_type_id('debitnote'); //Credit Notes
            $attributes['tag_id'] = '9'; //Credit Memos
            $attributes['user_id'] = Auth::user()->id;
            $attributes['org_id'] = Auth::user()->org_id;
            $attributes['number'] = $purchasereturn->id;
            $attributes['date'] = \Carbon\Carbon::today();
            $attributes['dr_total'] = $purchasereturn->total_amount;
            $attributes['cr_total'] = $purchasereturn->total_amount;
            $attributes['source'] = 'AUTO_SN';
            $entry = \App\Models\Entry::create($attributes);

            // Creddited to Customer or Interest or eq ledger
            $sub_amount = new \App\Models\Entryitem();
            $sub_amount->entry_id = $entry->id;
            $sub_amount->user_id = Auth::user()->id;
            $sub_amount->org_id = Auth::user()->org_id;
            $sub_amount->dc = 'D';
            $sub_amount->ledger_id = \App\Models\Client::find($purchasereturn->supplier_id)->ledger_id; //Client ledger
            $sub_amount->amount = $purchasereturn->total_amount;
            $sub_amount->narration = 'Good Receipt Note'; //$request->user_id
            //dd($sub_amount);
            $sub_amount->save();

            // Debitte to Bank or cash account that we are already in

            $cash_amount = new \App\Models\Entryitem();
            $cash_amount->entry_id = $entry->id;
            $cash_amount->user_id = Auth::user()->id;
            $cash_amount->org_id = Auth::user()->org_id;
            $cash_amount->dc = 'C';
            $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('PURCHASE_LEDGER_ID'); // Sales ledger if selected or ledgers from .env
            // dd($cash_amount);
            $cash_amount->amount = $purchasereturn->total_amount;
            $cash_amount->narration = 'Good Receipt Note';
            $cash_amount->save();

            //now update entry_id in income row
            $purchasereturn->update(['entry_id'=>$entry->id]);
        }

        return 0;
    }
    public function post(Request $request,$id)
    {
        \DB::beginTransaction();
        $purchase = \App\Models\PurchaseOrder::find($id)->toArray();
        $purchase_order = \App\Models\PurchaseOrderDetail::where('order_no',$id)->get()->toArray();
        $purchase['purchase_bill_no'] = $purchase['id'];
        $purchase['total_amount'] = $purchase['total'];
        $purchase['purchase_order_date'] = $purchase['ord_date'];
        $purchase['return_date'] = date('Y-m-d');
        $purchase_data = \App\Models\Grn::create($purchase);

        $product_id = $request->product_id;
        $units = $request->units;
        $purchase_quantity = $request->purchase_quantity;
        $return_quantity = $request->return_quantity;
        $purchase_price = $request->purchase_price;
        $return_price = $request->return_price;
        $return_total = $request->return_total;
        $reason = $request->reason;

        foreach ($purchase_order as $key => $value) {
            $value['purchase_quantity'] = $value['quantity_ordered'];
            $value['return_quantity'] = $value['quantity_recieved'];
            $value['purchase_price'] = $value['unit_price'];
            $value['return_price'] = $value['unit_price'];
            $value['return_total'] = $value['total'];
            $value['supplier_return_id'] = $purchase_data->id;

            $detail = GrnDetail::create($value);
            if ($value->is_inventory == 1) {
                
               

                // stockMove information
                $stockMove = new StockMove();
                $stockMove->stock_id = $value->product_id;
                $stockMove->trans_type = PURCHINVOICE;
                $stockMove->tran_date = \Carbon\Carbon::now();
                $stockMove->user_id = \Auth::user()->id;
                $stockMove->reference = 'store_in_'.$value->order_no;
                $stockMove->order_reference = $value->order_no;
                $stockMove->transaction_reference_id = $value->order_no;
                $stockMove->location = $request->into_stock_location ?? 0;
                $stockMove->qty = $value->qty_invoiced * \StockHelper::getUnitPrice($value->units);
                $stockMove->price = $value->unit_price;
                $stockMove->save();
            }
        }
        $this->updateEntries($purchase_data->id);

        $purchase = \App\Models\PurchaseOrder::find($id);
        $purchase->status = 'GRN Created';
        $purchase->save();


        \DB::commit();

        Flash::success('Good Receipt Note created Successfully.');

        return redirect('/admin/grn');
    }

}
