<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Flash;

class ExternalSalesController extends Controller
{
    public function index(Request $request)
    {
        $op = \Request::get('op');
        // dd($op, $request->all());
        $orders = \App\Models\ExternalSales::where(function($query){
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
                ->orderBy('id', 'desc');
               
        $page_title = 'External Quotation';
        $page_description = 'Manage External Quotation';
        $clients = \App\Models\Client::select('id', 'name')->where('org_id', \Auth::user()->org_id)->orderBy('id', 'DESC')->pluck('name','id')->all();
        $fiscal_years = \App\Models\Fiscalyear::pluck('fiscal_year as name', 'fiscal_year as id')->all();
        $outlets = $this->getUserOutlets();
        if( $op=="excel"){
            $externalsales= \App\Models\ExternalSales::with('externalsalesdetail')->where('bill_date','>=', \Request::get('start_date'))->where('bill_date','<=', \Request::get('end_date'))->get();
            $data = $externalsales;
            return \Excel::download(new \App\Exports\QuotationExport($data,'Quotation'), "Quoatation.xls");
        }
        $orders=$orders->paginate(30);

        return view('admin.externalsales.index', compact('orders', 'page_title', 'page_description','clients','fiscal_years','outlets'));
    }
    //
    // public function excel(Request $request){
      
    //     $data = json_decode(json_encode($data), true);
    //     return \Excel::download(new \App\Exports\QuotationExport($data,'Quotation'), "Quoatation.xls");
    // }
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
        $bill_no = \DB::select("SELECT MAX(Convert(`bill_no`,SIGNED)) as last_bill from externalsale WHERE fiscal_year = '$ckfiscalyear->fiscal_year' AND  org_id = '$org_id'  limit 1");
            $bill_no = $bill_no[0]->last_bill + 1;
        $page_title = 'External Invoice';
        $page_description = 'Add External invoice';
        $order = null;
        $orderDetail = null;
        $products = \App\Models\Product::select('id', 'name')->where('org_id',\Auth::user()->org_id)->where('product_division',"raw_material")->get();
        $users = \App\User::where('enabled', '1')->where('org_id', \Auth::user()->org_id)->pluck('first_name', 'id');

        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();

        $units = \App\Models\ProductsUnit::orderBy('id', 'desc')->get();
        //dd($airlines);
        //$clients = Client::select('id', 'name', 'location')->orderBy('id', DESC)->get();
        $clients = \App\Models\Client::select('id', 'name','location')->where('org_id', \Auth::user()->org_id)->orderBy('id', 'DESC')->get();

        $outlets = \App\Models\PosOutlets::get();

        return view('admin.externalsales.create', compact('page_title', 'users', 'page_description', 'order', 'units', 'orderDetail', 'products', 'clients', 'productlocation','outlets','bill_no'));
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
                if(\App\Models\ExternalSales::where([['bill_no', $bill_no],['fiscal_year', $ckfiscalyear->fiscal_year]])->first()){
                    $fail('Sorry Bill Number Already used in this Fiscal year.');
                }
            }],
        ]);

        $order_attributes = $request->all();
        $order_attributes['client_name'] = $request->customer_id;
        $order_attributes['order_type'] = $request->order_type;
        $order_attributes['user_id'] = \Auth::user()->id;
        $order_attributes['sales_person'] = $request->user_id;
        $order_attributes['org_id'] = \Auth::user()->org_id;
        $order_attributes['tax_amount'] = $request->taxable_tax;
        $order_attributes['total_amount'] = $request->final_total;
        $order_attributes['bill_no'] = $bill_no;
        $order_attributes['address'] = $request->from_stock_location;
        $order_attributes['fiscal_year'] = $ckfiscalyear->fiscal_year;
        $order_attributes['is_bill_active'] = 1;
        $order_attributes['fiscal_year_id'] = $ckfiscalyear->id;
        $invoice = \App\Models\ExternalSales::create($order_attributes);

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
                $detail = new \App\Models\ExternalSaleDetails();
                $detail->discount= $request->dis_amount[$key]??0;
                $detail->externalsales_id = $invoice->id;
                $detail->product_id = $product_id[$key];
                $detail->price = $price[$key];
                $detail->quantity = $quantity[$key];
                $detail->tax = $tax[$key] ?? null;
                $detail->unit = $unit[$key];
                $detail->tax_amount = $tax_amount[$key] ?? null;
                $detail->total = $total[$key];
                $detail->date = date('Y-m-d H:i:s');
                $detail->save();

               
            }
        }

        Flash::success('External Invoice created Successfully.');
       
        return redirect('/admin/externalsales');
    }
    public function edit($id)
    {
        $page_title = 'External Invoice';
        $page_description = 'Edit external invoice';
        $order = null;
        $orderDetail = null;
        $products = \App\Models\Product::select('id', 'name')->where('org_id',\Auth::user()->org_id)->where('product_division',"raw_material")->get();
        $users = \App\User::where('enabled', '1')->where('org_id', \Auth::user()->org_id)->pluck('first_name', 'id');
        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();
        //$clients = Client::select('id', 'name', 'location')->orderBy('id', DESC)->get();
        $clients = \App\Models\Client::select('id', 'name')->where('org_id', \Auth::user()->org_id)->orderBy('id', 'DESC')->get();
        $invoice = \App\Models\ExternalSales::find($id);
        // \TaskHelper::authorizeOrg($invoice);
        $invoice_details = \App\Models\ExternalSaleDetails::where('externalsales_id', $id)->get();
        $units = \App\Models\ProductsUnit::orderBy('id', 'desc')->get();

        $outlets = \App\Models\PosOutlets::get();
        return view('admin.externalsales.edit', compact('page_title', 'users', 'units', 'page_description', 'order', 'orderDetail', 'products', 'clients', 'productlocation', 'invoice', 'invoice_details','outlets'));
    }
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
                if(\App\Models\Externalsales::where([['bill_no', $bill_no],['fiscal_year', $ckfiscalyear->fiscal_year],['id','!=',$id]])->first()){
                    $fail('Sorry Bill Number Already used in this Fiscal year.');
                }
            }],
        ]);
        $invoice = \App\Models\ExternalSales::find($id);
        // \TaskHelper::authorizeOrg($invoice);
        $product_id = $request->product_id;
        //dd($product_ids);
        $price = $request->price;
        //dd($price);
        $quantity = $request->quantity;
        $tax = $request->tax;
        $unit = $request->unit;
        $tax_amount = $request->tax_amount;
        $total = $request->total;

        $invdetails = \App\Models\ExternalSaleDetails::where('externalsales_id', $id)->get();
        
        \App\Models\ExternalSaleDetails::where('externalsales_id', $id)->delete();
        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new \App\Models\ExternalSaleDetails();
                $detail->discount= $request->dis_amount[$key]??0;
                $detail->externalsales_id = $invoice->id;
                $detail->product_id = $product_id[$key];
                $detail->price = $price[$key];
                $detail->quantity = $quantity[$key];
                $detail->tax = $tax[$key] ?? null;
                $detail->unit = $unit[$key];
                $detail->tax_amount = $tax_amount[$key] ?? null;
                // $detail->excise_amount = $request->excise_amount[$key] ?? 0;
                $detail->total = $total[$key];
                $detail->date = date('Y-m-d H:i:s');
                $detail->save();
                // create stockMove
                // 
            }
        }

        // Custom items
      
        Flash::success('External Sales updated Successfully.');

        return redirect()->back();
    }
    public function destroy($id)
    {

        $orders = \App\Models\ExternalSales::findOrFail($id);
        $data=$orders->toArray();
        $data['delete_by']= \Auth::user()->id;
        // $orderDetail = \App\Models\ExternalSaleDetails::where('externalsales_id', $id)->get();
        // \TaskHelper::authorizeOrg($orders);

        // if (! $orders->isdeletable()) {
        //     abort(403);
        // }
    
        $orders->delete($id);
        \App\Models\ExternalSaleDetails::where('externalsales_id', $id)->delete($id);

        Flash::success('Externalsales successfully deleted.');

        return redirect('/admin/externalsales');
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

        $orders = \App\Models\ExternalSales::find($id);

        // if (! $orders->isdeletable()) {
        //     abort(403);
        // }

        $modal_title = 'Delete External Sales Invoice';

        $orders = \App\Models\ExternalSales::find($id);

        $modal_route = route('admin.externalsales.delete', ['id' => $orders->id]);


        $modal_body = 'Are you sure you want to delete this External Sales Invoice?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
    public function print($id)
    {
        $ord = \App\Models\ExternalSales::find($id);
         \TaskHelper::authorizeOrg($ord);
        $orderDetails = \App\Models\ExternalSaleDetails::where('externalsales_id', $id)->get();
        //dd($orderDetails);
        $imagepath = \Auth::user()->organization->logo;
        // $print_no = \App\Models\Invoiceprint::where('invoice_id', $id)->count();
        // $attributes = new \App\Models\Invoiceprint();
        // $attributes->invoice_id = $id;
        // $attributes->printed_date = \Carbon\Carbon::now();
        // $attributes->printed_by = \Auth::user()->id;
        // $attributes->save();
        // $ord->update(['is_bill_printed' => 1]);

        return view('admin.externalsales.print', compact('ord', 'imagepath', 'orderDetails'));
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
}
