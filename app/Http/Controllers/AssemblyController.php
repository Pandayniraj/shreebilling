<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Client;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\SupplierReturnDetail;
use App\ProductLocation;
use App\User;
use Auth;
use DB;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Input;

/**
FOR ONLINE ENQUIRY

 **/
class AssemblyController extends Controller
{
    public function index()
    {
        $page_title = 'Admin | Assembly | Index';
        $page_description = 'Manage Assemble Products';

        $assembly = \App\Models\Assembly::all();

        return view('admin.assemblies.index', compact('page_title', 'page_description', 'assembly'));
    }

    public function create()
    {
        $page_title = 'Admin | Assembly | Create';
        $page_description = 'Creates Assembly';

        $products = Product::select('id', 'name')->get();
        $users = \App\User::where('enabled', '1')->where('org_id', \Auth::user()->org_id)->pluck('first_name', 'id');
        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();
        $clients = Client::select('id', 'name', 'location')->orderBy('id', DESC)->get();

        return view('admin.assemblies.create', compact('page_title', 'page_description', 'products', 'users', 'productlocation', 'clients'));
    }

    public function show($id)
    {
        $page_title = 'Show Supplier Return';

        $page_description = 'Detail of Return';

        $ord = \App\Models\Assembly::find($id);
        $orderDetails = \App\Models\AssemblyDetail::where('assembly_id', $ord->id)->get();

        return view('admin.assemblies.show', compact('page_title', 'page_description', 'ord', 'orderDetails'));
    }

    public function store(Request $request)
    {
        $attributes = $request->all();

        // dd($attributes);

        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['user_id'] = \Auth::user()->id;

        $attributes['total_amount'] = $request->final_total;

        $attributes['product'] = \App\Models\Product::where('name', $request->product)->first()->id;

        $assembly = \App\Models\Assembly::create($attributes);

        $product_id = $request->product_id;
        $units = $request->units;
        $quantity = $request->quantity;
        $wastage_qty = $request->wastage_qty;
        $cost_price = $request->cost_price;
        $total = $request->total;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new \App\Models\AssemblyDetail();
                $detail->assembly_id = $assembly->id;
                $detail->product_id = $product_id[$key];
                $detail->units = $units[$key];
                $detail->quantity = $quantity[$key];
                $detail->wastage_qty = $wastage_qty[$key];
                $detail->cost_price = $cost_price[$key];
                $detail->total = $total[$key];

                $detail->save();
            }
        }

        Flash::success('Assembly created Successfully.');

        return redirect('/admin/assembly');
    }

    public function edit(Request $request, $id)
    {
        $page_title = 'Edit Assembly Return';

        $page_description = '';

        $assembly = \App\Models\Assembly::find($id);
        $assembly_detail = \App\Models\AssemblyDetail::where('assembly_id', $assembly->id)->get();

        $products = Product::select('id', 'name')->get();
        $users = \App\User::where('enabled', '1')->where('org_id', \Auth::user()->org_id)->pluck('first_name', 'id');
        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();
        $clients = Client::select('id', 'name', 'location')->orderBy('id', DESC)->get();

        return view('admin.assemblies.edit', compact('page_title', 'page_description', 'purchasereturn', 'purchase_return_detail', 'products', 'users', 'productlocation', 'clients', 'assembly', 'assembly_detail'));
    }

    public function update(Request $request, $id)
    {
        $assembly = \App\Models\Assembly::find($id);

        $attributes = $request->all();

        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['user_id'] = \Auth::user()->id;

        $attributes['total_amount'] = $request->final_total;

        $attributes['product'] = \App\Models\Product::where('name', $request->product)->first()->id;

        $assembly->update($attributes);

        \App\Models\AssemblyDetail::where('assembly_id', $assembly->id)->delete();

        $product_id = $request->product_id;
        $units = $request->units;
        $quantity = $request->quantity;
        $wastage_qty = $request->wastage_qty;
        $cost_price = $request->cost_price;
        $total = $request->total;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new \App\Models\AssemblyDetail();
                $detail->assembly_id = $assembly->id;
                $detail->product_id = $product_id[$key];
                $detail->units = $units[$key];
                $detail->quantity = $quantity[$key];
                $detail->wastage_qty = $wastage_qty[$key];
                $detail->cost_price = $cost_price[$key];
                $detail->total = $total[$key];

                $detail->save();
            }
        }

        Flash::success('Assembly Updated Successfully.');

        return redirect('/admin/assembly');
    }

    public function pdf($id)
    {
        $ord = \App\Models\Assembly::find($id);
        $orderDetails = \App\Models\AssemblyDetail::where('assembly_id', $ord->id)->get();
        $imagepath = \Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.assemblies.pdf', compact('ord', 'imagepath', 'orderDetails'));
        $file = 'Assembly-'.$ord->id.'.pdf';

        if (\File::exists('reports/'.$file)) {
            \File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function print($id)
    {
        $ord = \App\Models\Assembly::find($id);
        $orderDetails = \App\Models\AssemblyDetail::where('assembly_id', $ord->id)->get();

        $imagepath = \Auth::user()->organization->logo;

        return view('admin.assemblies.print', compact('ord', 'imagepath', 'orderDetails', 'print_no'));
    }

    public function destroy($id)
    {

        //dd($id);
        $ord = \App\Models\Assembly::find($id);

        \App\Models\Assembly::find($id)->delete();
        \App\Models\AssemblyDetail::where('assembly_id', $id)->delete();

        Flash::success('Assembly successfully deleted.');

        return redirect('/admin/assembly');
    }

    public function getModalDelete($id)
    {

        //dd($id);
        $error = null;

        $ord = \App\Models\Assembly::find($id);

        $modal_title = 'Delete Assembly ';

        $assembly = \App\Models\SupplierReturn::find($id);

        $modal_route = route('admin.assembly.delete', ['id' => $id]);

        $modal_body = 'Are you sure you want to delete this Assembly?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    private function updateEntries($orderId)
    {
        $purchasereturn = \App\Models\SupplierReturn::find($orderId);

        if ($purchasereturn->entry_id && $purchasereturn->entry_id != '0') { //update the ledgers
            $attributes['entrytype_id'] = '8'; //Purchase Return
            $attributes['tag_id'] = '9'; //Debit  Memos
            $attributes['user_id'] = \Auth::user()->id;
            $attributes['org_id'] = \Auth::user()->org_id;
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
            $sub_amount->user_id = \Auth::user()->id;
            $sub_amount->org_id = \Auth::user()->org_id;
            $sub_amount->dc = 'D';
            $sub_amount->ledger_id = \App\Models\Client::find($purchasereturn->supplier_id)->ledger_id; //Client ledger
            $sub_amount->amount = $purchasereturn->total_amount;
            $sub_amount->narration = 'Supplier Return'; //$request->user_id
            //dd($sub_amount);
            $sub_amount->update();

            // Debitte to Bank or cash account that we are already in
            $cash_amount = \App\Models\Entryitem::where('entry_id', $purchasereturn->entry_id)->where('dc', 'C')->first();
            $cash_amount->entry_id = $entry->id;
            $cash_amount->user_id = \Auth::user()->id;
            $cash_amount->org_id = \Auth::user()->org_id;
            $cash_amount->dc = 'C';
            $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('PURCHASE_LEDGER_ID'); // Purchase ledger if selected or ledgers from .env
            // dd($cash_amount);
            $cash_amount->amount = $purchasereturn->total_amount;
            $cash_amount->narration = 'Supplier Return';
            $cash_amount->update();
        } else {                               //create the new entry items
            $attributes['entrytype_id'] = '8'; //Credit Notes
            $attributes['tag_id'] = '9'; //Credit Memos
            $attributes['user_id'] = \Auth::user()->id;
            $attributes['org_id'] = \Auth::user()->org_id;
            $attributes['number'] = $purchasereturn->id;
            $attributes['date'] = \Carbon\Carbon::today();
            $attributes['dr_total'] = $purchasereturn->total_amount;
            $attributes['cr_total'] = $purchasereturn->total_amount;
            $attributes['source'] = 'AUTO_SN';
            $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
            $entry = \App\Models\Entry::create($attributes);

            // Creddited to Customer or Interest or eq ledger
            $sub_amount = new \App\Models\Entryitem();
            $sub_amount->entry_id = $entry->id;
            $sub_amount->user_id = \Auth::user()->id;
            $sub_amount->org_id = \Auth::user()->org_id;
            $sub_amount->dc = 'D';
            $sub_amount->ledger_id = \App\Models\Client::find($purchasereturn->supplier_id)->ledger_id; //Client ledger
            $sub_amount->amount = $purchasereturn->total_amount;
            $sub_amount->narration = 'Supplier Return'; //$request->user_id
            //dd($sub_amount);
            $sub_amount->save();

            // Debitte to Bank or cash account that we are already in

            $cash_amount = new \App\Models\Entryitem();
            $cash_amount->entry_id = $entry->id;
            $cash_amount->user_id = \Auth::user()->id;
            $cash_amount->org_id = \Auth::user()->org_id;
            $cash_amount->dc = 'C';
            $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('PURCHASE_LEDGER_ID'); // Sales ledger if selected or ledgers from .env
            // dd($cash_amount);
            $cash_amount->amount = $purchasereturn->total_amount;
            $cash_amount->narration = 'Supplier Return';
            $cash_amount->save();

            //now update entry_id in income row
            $purchasereturn->update(['entry_id'=>$entry->id]);
        }

        return 0;
    }

    public function getProductName(Request $request)
    {
        $term = strtolower(\Request::get('term'));

        //dd($term);
        $products = \App\Models\Product::select('id', 'name')->where('assembled_product', '1')->where('name', 'LIKE', '%'.$term.'%')->take(5)->get();
        $return_array = [];

        foreach ($products as $v) {
            $return_array[] = ['value' =>$v->name, 'id' =>$v->id];
        }

        return \Response::json($return_array);
    }

    public function getComponentProduct(Request $request)
    {
        $term = strtolower(\Request::get('term'));

        $product_name = \Request::get('product_name');

        $product_id = \App\Models\Product::where('name', $product_name)->first()->id;

        $products = \App\Models\Product::select('id', 'name')->where('id', '!=', $product_id)->where('component_product', '1')->where('name', 'LIKE', '%'.$term.'%')->take(5)->get();
        $return_array = [];

        foreach ($products as $v) {
            $return_array[] = ['value' =>$v->name, 'id' =>$v->id];
        }

        return \Response::json($return_array);
    }

    public function getComponentProductInfo()
    {
        $component_product = \Request::get('component_product');
        $quantity = \Request::get('quantity');
        $wastage_qty = \Request::get('wastage_qty');

        //dd($wastage_qty);

        $product = \App\Models\Product::where('name', $component_product)->first();

        $total_amount = ($quantity + $wastage_qty) * $product->cost;

        $data = '<tr>  
                <td>
                <input type="text" class="form-control" name="product_name"  value="'.$product->name.'" readonly>
                  <input type="hidden"  name="product_id[]" value="'.$product->id.'" required="required" readonly>   
                </td>
                <td>
                    <input type="text" class="form-control " placeholder="Unit" value="'.$product->unitname->name.'" required="required" readonly>
                    <input  type="hidden" name="units[]" value="'.$product->unitname->id.'">

                </td>

                <td>
                    <input type="number" class="form-control quantity" name="quantity[]" placeholder="Quantity"  value="'.$quantity.'" required="required" >
                </td>
               

                <td>
                    <input type="number" class="form-control wastage_quantity" name="wastage_qty[]" placeholder="Wastage Quantity"  value="'.$wastage_qty.'" required="required" >
                </td>

                <td>
                    <input type="number" class="form-control price" name="cost_price[]" placeholder="Cost Price" min="1" value="'.$product->cost.'" required="required" readonly>
                </td>

                <td>
                    <input type="text" class="form-control total" name="total[]" placeholder="Total" value="'.$total_amount.'"style="float:left; width:80%;">
                    <a href="javascript::void(1);" style="width: 10%;" readonly>
                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                    </a>
                </td>
            </tr>';

        return ['purchasedetailinfo'=>$data];
    }
}
