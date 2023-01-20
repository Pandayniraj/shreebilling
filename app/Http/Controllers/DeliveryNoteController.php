<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Flash;

class DeliveryNoteController extends Controller
{
    public function index()
    {
        $deliverynote = \App\Models\DeliveryNote::orderBy('id', 'DESC')->paginate(20);
        // dd($deliverynote);

        $page_title = 'Admin | Delivery Note';
        $page_description = 'Manage Delivery Note';

        return view('deliverynote.index', compact('page_title', 'page_description', 'deliverynote'));
    }
    public function create()
    {
        $page_title = 'Admin | Delivery Note | Create';
        $page_description = 'Creates Delivery Note';

        $products = \App\Models\Product::select('id', 'name')->get();
        $users = \App\User::where('enabled', '1')->where('org_id', \Auth::user()->org_id)->pluck('first_name', 'id');
        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();
        $clients = \App\Models\Client::select('id', 'name', 'location')->orderBy('id', 'DESC')->get();

        return view('deliverynote.create', compact('page_title', 'page_description', 'products', 'users', 'productlocation', 'clients'));
    }

    public function getSalesBillId()
    {
        $term = strtolower(\Request::get('term'));
        $salesbills = \App\Models\InvoiceDetail::select('id')->where('invoice_id', 'LIKE', '%'.$term.'%')->take(5)->get();
        $return_array = [];

        foreach ($salesbills as $v) {
            $return_array[] = ['value' =>sprintf('%08d', $v->id), 'id' =>$v->id];
        }

        return Response::json($return_array);
    }
    public function store(Request $request)
    {
        \DB::beginTransaction();
        $attributes['sales_bill_no'] =$request->sales_bill_no;
        $attributes['client_id']=$request->client_id;
        $attributes['delivery_note_date']=$request->return_date;
        $attributes['sales_bill_date']=$request->sales_date;
        $attributes['user_id']=\Auth::user()->id;
        $attributes['org_id']=\Auth::user()->org_id;
        $attributes['pan_no']=$request->pan_no;
        $attributes['vat_type']=$request->vat_type;
        $attributes['is_renewal']=$request->is_renewal;
        $attributes['into_stock_location']=$request->into_stock_location;
        $attributes['subtotal']=$request->subtotal;
        $attributes['discount_percent'] = $request->discount_percent;
        $attributes['taxable_amount'] = $request->taxable_amount;
        $attributes['tax_amount'] = $request->taxable_tax;
        $attributes['total_amount'] = $request->final_total;

        $delivery_note = \App\Models\DeliveryNote::create($attributes);

        $product_id = $request->product_id;
        $units = $request->units;
        $sales_quantity = $request->sales_quantity;
        $invoiced_quantity = $request->invoiced_quantity;
        $sales_price = $request->sales_price;
        $return_price = $request->return_price;
        $return_total = $request->return_total;
        $reason = $request->reason;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new \App\Models\DeliveryNoteDetails();
                $detail->deliverynote_id = $delivery_note->id;
                $detail->product_id = $product_id[$key];
                $detail->unit = $units[$key];
                $detail->sales_quantity = $sales_quantity[$key];
                $detail->invoiced_quantity = $invoiced_quantity[$key];
                $detail->sales_price = $sales_price[$key];
                $detail->return_price = $return_price[$key];
                $detail->return_total = $return_total[$key];
                $detail->reason = $reason[$key];
                $detail->save();

            }
        }

        
        \DB::commit();

        Flash::success('Delivery Note created Successfully.');

        return redirect('/admin/deliverynote');
    }

    public function edit(Request $request, $id)
    {
        $page_title = 'Edit Delivery Note';

        $page_description = '';

        $delivery = \App\Models\DeliveryNote::find($id);
        $deliverydetail = \App\Models\DeliveryNoteDetails::where('deliverynote_id', $delivery->id)->get();

        $products = \App\Models\Product::select('id', 'name')->get();
        $users = \App\User::where('enabled', '1')->where('org_id', \Auth::user()->org_id)->pluck('first_name', 'id');
        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();
        $clients = \App\Models\Client::select('id', 'name', 'location')->orderBy('id', 'DESC')->get();

        return view('deliverynote.edit', compact('page_title', 'page_description', 'delivery', 'deliverydetail', 'products', 'users', 'productlocation', 'clients'));
    }

    public function update(Request $request, $id)
    {
        \DB::beginTransaction();
        $delivery = \App\Models\DeliveryNote::find($id);

        $attributes['sales_bill_no'] =$request->sales_bill_no;
        $attributes['client_id']=$request->client_id;
        $attributes['delivery_note_date']=$request->return_date;
        $attributes['sales_bill_date']=$request->sales_date;
        $attributes['user_id']=\Auth::user()->id;
        $attributes['org_id']=\Auth::user()->org_id;
        $attributes['pan_no']=$request->pan_no;
        $attributes['vat_type']=$request->vat_type;
        $attributes['is_renewal']=$request->is_renewal;
        $attributes['into_stock_location']=$request->into_stock_location;
        $attributes['subtotal']=$request->subtotal;
        $attributes['discount_percent'] = $request->discount_percent;
        $attributes['taxable_amount'] = $request->taxable_amount;
        $attributes['tax_amount'] = $request->taxable_tax;
        $attributes['total_amount'] = $request->final_total;

        $delivery->update($attributes);

        \App\Models\DeliveryNoteDetails::where('deliverynote_id', $delivery->id)->delete();
        // $stockmove = StockMove::where('trans_type', PURCHINVOICE)->where('reference', 'store_in_' . $purchasereturn->id)->delete();

        $product_id = $request->product_id;
        $units = $request->units;
        $sales_quantity = $request->sales_quantity;
        $invoiced_quantity = $request->invoiced_quantity;
        $sales_price = $request->sales_price;
        $return_price = $request->return_price;
        $return_total = $request->return_total;
        $reason = $request->reason;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new \App\Models\DeliveryNoteDetails();
                $detail->deliverynote_id = $delivery->id;
                $detail->product_id = $product_id[$key];
                $detail->unit = $units[$key];
                $detail->sales_quantity = $sales_quantity[$key];
                $detail->invoiced_quantity = $invoiced_quantity[$key];
                $detail->sales_price = $sales_price[$key];
                $detail->return_price = $return_price[$key];
                $detail->return_total = $return_total[$key];
                $detail->reason = $reason[$key];
                $detail->save();

            }
        }

        \DB::commit();

        Flash::success('Delivery Note Updated Successfully.');

        return redirect('/admin/deliverynote');
    }
    public function getModalDelete($id)
    {
        $error = null;

        $note = \App\Models\DeliveryNote::find($id);

        $modal_title = 'Delete Delivery Note';

        $modal_route = route('admin.deliverynote.delete', ['id' => $note->id]);

        $modal_body = 'Are you sure you want to delete this Delivery Note?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
    public function destroy($id)
    {

        $note = \App\Models\DeliveryNote::find($id);

        $note->delete();
        \App\Models\DeliveryNoteDetails::where('deliverynote_id', $id)->delete();
        
        Flash::success('Delviery Note successfully deleted.');
        return redirect('/admin/deliverynote');
    }
    public function comparisionreport(Request $request){
        $page_title="Calculation";
        $page_description="Statement of Calculation up to";
        $startdate=$request->start_date;
        $enddate=$request->end_date;
        $details=\App\Models\Invoice::with('invoiceDetail','deliverynote.notedetails')->where('bill_date','>=', $request->start_date)->where('bill_date','<=',$request->end_date)->get();    
        if($request->excel == "excel"){
            $data = \App\Models\Invoice::with('invoiceDetail','deliverynote.notedetails')->where('bill_date','>=', $startdate)->where('bill_date','<=',$enddate)->get();
            $data = json_decode(json_encode($data), true);
            // return \Excel::download(new \App\Exports\PurchaseExport($data,'Purchase Book List View'), 'purchase.csv');
            return \Excel::download(new \App\Exports\DeliveryNoteExport($data,'Delivery Note View'), "DeliveryNote.xls");
        }
        return view('deliverynote.report', compact('page_title','details', 'page_description','comparisionvalue','startdate','enddate'));
    }
    public function print($id)
    {
        $ord = \App\Models\DeliveryNote::find($id);
        $orderDetails = \App\Models\DeliveryNoteDetails::where('deliverynote_id', $ord->id)->get();
        // $client= \App\Models\Client::where('id', $ord->client_id)->first()->name;
        $cusdata= \App\Models\Invoice::where('id', $ord->sales_bill_no)->select('name','sales_person', 'customer_pan', 'address')->first();
        $year=\App\Models\Fiscalyear::where('current_year', 1)->first()->fiscal_year;
        $current_year=str_replace("2","",$year);
        $print_no = \App\Models\DeliveryPrint::where('deliverynote_id', $id)->count();
        $attributes = new \App\Models\DeliveryPrint();
        $attributes->deliverynote_id = $id;
        $attributes->print_date = \Carbon\Carbon::now();
        $attributes->print_by = \Auth::user()->id;
        $attributes->save();
        // $ord->update(['is_bill_printed' => 1]);
        $imagepath = \Auth::user()->organization->logo;

        return view('deliverynote.print', compact('ord','cusdata', 'imagepath', 'orderDetails', 'current_year','print_no'));
    }
    // public function excel(){
    //     $data = \App\Models\Invoice::with('invoiceDetail','deliverynote.notedetails')->get();
    //     $data = json_decode(json_encode($data), true);
    //     // return \Excel::download(new \App\Exports\PurchaseExport($data,'Purchase Book List View'), 'purchase.csv');
    //     return \Excel::download(new \App\Exports\DeliveryNoteExport($data,'Delivery Note View'), "DeliveryNote.xls");
    // } 
    public function getSalesBillInfo(Request $request)
    { 
        $salesbillinfo = \App\Models\Invoice::find((int)$request->sales_bill_no);
        
        $customer_name = \App\Models\Client::find($salesbillinfo->client_id)->name;
        $purchasedetailinfo = \App\Models\InvoiceDetail::where('invoice_id', $salesbillinfo->id)->get();

        $products = \App\Models\Product::select('id', 'name')->get();
        $data = '';

        foreach ($purchasedetailinfo as $idi) {
            $unit_name = \App\Models\ProductsUnit::find($idi->unit)->name;
            if ($idi->is_inventory == 1) {
                $name = \App\Models\Product::find($idi->product_id)->name;

                $data .= '<tr>  
                                <td>
                                <input type="text" class="form-control product_id" name="product"  value="'.$name.'" readonly>
                                  <input type="hidden"  name="product_id[]" value="'.$idi->product_id.'" required="required" readonly>   
                                </td>
                                <td>
                                    <input type="text" class="form-control invoice_price" placeholder="Unit" value="'.$unit_name.'" required="required" readonly>
                                    <input  type="hidden" name="units[]" value="'.$idi->unit.'">

                                </td>
                                <td>
                                    <input type="number" class="form-control purchase_quantity" name="sales_quantity[]" placeholder="Quantity" min="1" value="'.$idi->quantity.'" required="required" readonly>
                                </td>
                               

                                <td>
                                    <input type="number" class="form-control quantity" name="invoiced_quantity[]" placeholder="Quantity" min="1" value="'.$idi->quantity.'" required="required" >
                                </td>

                                <td>
                                   <input type="number" class="form-control purchase_price" name="sales_price[]" placeholder="Price" min="1" value="'.$idi->price.'" required="required" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control price" name="return_price[]" placeholder="Price" min="1" value="'.$idi->price.'" step="0.01" required="required" >
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


        return ['purchasebillsinfo'=>$salesbillinfo, 'purchasedetailinfo'=>$data, 'customer_name'=>$customer_name];
    }
    public function dispatchreport()
    {
        $page_title="Daily Dispatch Sheet";
        $page_description="Detail-daily report of all product";
       
        $products= \App\Models\Product::with('unit')->where('product_division', 'raw_material')->select('name', 'id','product_unit')->get();
       $perdaypurchasedetail=\App\Models\StockMove::select(\DB::raw("SUM(CASE WHEN unit_id = 15 THEN qty / 12
       WHEN unit_id = 20 THEN qty / 24 
       ELSE qty
   END) as totalpurchaseqty, stock_id"))
       ->whereDate('tran_date', \carbon\Carbon::now())->where('trans_type', 102)->groupby('stock_id')->get()->groupby('stock_id');
       
       $perdayproducttotal= \App\Models\InvoiceDetail::select(\DB::raw("SUM(CASE WHEN unit = 15 THEN quantity / 12
       WHEN unit = 20 THEN quantity / 24 
       ELSE quantity
   END) as totalproductqty, product_id"))
       ->whereDate('date', \carbon\Carbon::now())->groupby('product_id')->get()->groupby('product_id');

        $perdaysalesdetails=\App\Models\InvoiceDetail::select(\DB::raw("SUM(CASE WHEN unit = 15 THEN quantity / 12
        WHEN unit = 20 THEN quantity / 24 
        ELSE quantity
    END) as totalsalesqty, client_id, product_id"))
        ->whereDate('date', \carbon\Carbon::now())
        ->groupBy(['client_id','product_id'])->get();
        $perdaysalesdetails=$perdaysalesdetails->groupBy('client_id');

        $asopeningstock= \App\Models\StockMove::select(\DB::raw("SUM(CASE WHEN unit_id = 15 THEN qty / 12
        WHEN unit_id = 20 THEN qty / 24 
        ELSE qty
    END) as asopeningstock, stock_id"))
        ->where('trans_type', 401)->where(function($query){
           return $query->whereDate('tran_date', \carbon\Carbon::now());
        }) 
        ->groupby('stock_id')->get()->groupby('stock_id');
        $foropening = \App\Models\StockMove::select(\DB::raw("SUM(CASE WHEN unit_id = 15 THEN qty / 12
        WHEN unit_id = 20 THEN qty / 24 
        ELSE qty
    END) as removestock, stock_id"))->whereDate('tran_date','<=', \carbon\Carbon::yesterday())->whereIn('trans_type', [401,402])->groupby('stock_id')->get()->groupby('stock_id');
        
        $totalremaining=\App\Models\StockMove::select(\DB::raw("SUM(CASE WHEN unit_id = 15 THEN qty / 12
        WHEN unit_id = 20 THEN qty / 24 
        ELSE qty
    END) as totalremainingqty, stock_id"))->whereDate('tran_date','<=', \carbon\Carbon::yesterday())->whereIn('trans_type', [102, 203])->groupby('stock_id')->get()->groupby('stock_id');
       
        return view('deliverynote.dispatchreport',compact('page_title','totalremaining','asopeningstock', 'foropening', 'perdayproducttotal', 'page_description','perdaypurchasedetail','products','perdaysalesdetails'));
    }
    public function dispatchreportExcel()
    {
        $page_title="Daily Dispatch Sheet";
        $page_description="Detail-daily report of all product";
       
        $products= \App\Models\Product::with('unit')->where('product_division', 'raw_material')->select('name', 'id','product_unit')->get();
       $perdaypurchasedetail=\App\Models\StockMove::select(\DB::raw("SUM(CASE WHEN unit_id = 15 THEN qty / 12
       WHEN unit_id = 20 THEN qty / 24 
       ELSE qty
   END) as totalpurchaseqty, stock_id"))
       ->whereDate('tran_date', \carbon\Carbon::now())->where('trans_type', 102)->groupby('stock_id')->get()->groupby('stock_id');
       
       $perdayproducttotal= \App\Models\InvoiceDetail::select(\DB::raw("SUM(CASE WHEN unit = 15 THEN quantity / 12
       WHEN unit = 20 THEN quantity / 24 
       ELSE quantity
   END) as totalproductqty, product_id"))
       ->whereDate('date', \carbon\Carbon::now())->groupby('product_id')->get()->groupby('product_id');

       $perdaysalesdetails=\App\Models\InvoiceDetail::select(\DB::raw("SUM(CASE WHEN unit = 15 THEN quantity / 12
       WHEN unit = 20 THEN quantity / 24 
       ELSE quantity
   END) as totalsalesqty, client_id, product_id"))
       ->whereDate('date', \carbon\Carbon::now())
       ->groupBy(['client_id','product_id'])->get();
       $perdaysalesdetails=$perdaysalesdetails->groupBy('client_id');

        $asopeningstock= \App\Models\StockMove::select(\DB::raw("SUM(CASE WHEN unit_id = 15 THEN qty / 12
        WHEN unit_id = 20 THEN qty / 24 
        ELSE qty
    END) as asopeningstock, stock_id"))
        ->where('trans_type', 401)->where(function($query){
           return $query->whereDate('tran_date', \carbon\Carbon::now());
        }) 
        ->groupby('stock_id')->get()->groupby('stock_id');
        $foropening = \App\Models\StockMove::select(\DB::raw("SUM(CASE WHEN unit_id = 15 THEN qty / 12
        WHEN unit_id = 20 THEN qty / 24 
        ELSE qty
    END) as removestock, stock_id"))->whereDate('tran_date','<=', \carbon\Carbon::yesterday())->whereIn('trans_type', [401,402])->groupby('stock_id')->get()->groupby('stock_id');
        
        $totalremaining=\App\Models\StockMove::select(\DB::raw("SUM(CASE WHEN unit_id = 15 THEN qty / 12
        WHEN unit_id = 20 THEN qty / 24 
        ELSE qty
    END) as totalremainingqty, stock_id"))->whereDate('tran_date','<=', \carbon\Carbon::yesterday())->whereIn('trans_type', [102, 203])->groupby('stock_id')->get()->groupby('stock_id');
        // dd($foropening, $totalremaining);
        return \Excel::download(new \App\Exports\DispatchSheetExport($products,$perdaypurchasedetail,$perdayproducttotal,$perdaysalesdetails,$asopeningstock,$foropening,$totalremaining,'Dispatch Sheet'), "DispatchSheet.xls");
    }
}
