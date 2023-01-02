<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\ProductLocation;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

/**
FOR ONLINE ENQUIRY

 **/
class BOMController extends Controller
{
    public function index()
    {
        $page_title = 'Admin | BOM | Index';
        $page_description = 'Manage Bom';

        $billofmaterials = \App\Models\BillOfMaterials::orderBy('id', 'desc')->get();

        return view('admin.billofmaterials.index', compact('page_title', 'page_description', 'billofmaterials'));
    }

    public function create()
    {
        $page_title = 'Admin | BOM | Create';
        $page_description = 'Creates Supplier Return';

        $products = Product::select('id', 'name')->get();
        $users = \App\User::where('enabled', '1')->where('org_id', Auth::user()->org_id)->pluck('first_name', 'id');
        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();
        $clients = Client::select('id', 'name', 'location')->orderBy('id', 'DESC')->get();

        return view('admin.billofmaterials.create', compact('page_title', 'page_description', 'products', 'users', 'productlocation', 'clients'));
    }

    public function show($id)
    {
        $page_title = 'Show Supplier Return';

        $page_description = 'Detail of Return';

        $billofmaterials = \App\Models\BillOfMaterials::find($id);
        $billofmaterials_details = \App\Models\BillOfMaterialsDetail::where('billofmaterials_id', $billofmaterials->id)->get();

        return view('admin.billofmaterials.show', compact('page_title', 'page_description', 'billofmaterials', 'billofmaterials_details'));
    }

    public function store(Request $request)
    {
        $attributes = $request->all();

        // dd($attributes);

        $attributes['org_id'] = Auth::user()->org_id;
        $attributes['user_id'] = Auth::user()->id;

        $attributes['total_amount'] = $request->final_total;

        $attributes['bill_date'] = \Carbon\Carbon::today()->format('Y-m-d');

        $attributes['product'] = \App\Models\Product::where('name', $request->product)->first()->id;

        //dd($attributes);

        $billofmaterials = \App\Models\BillOfMaterials::create($attributes);

        $product_id = $request->product_id;
        $units = $request->units;
        $quantity = $request->quantity;
        $wastage_qty = $request->wastage_qty;
        $cost_price = $request->cost_price;
        $total = $request->total;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new \App\Models\BillOfMaterialsDetail();
                $detail->billofmaterials_id = $billofmaterials->id;
                $detail->product_id = $product_id[$key];
                $detail->units = $units[$key];
                $detail->quantity = $quantity[$key];
                $detail->wastage_qty = $wastage_qty[$key];
                $detail->cost_price = $cost_price[$key];
                $detail->total = $total[$key];

                $detail->save();
            }
        }

        Flash::success('BOM created Successfully.');

        return redirect('/admin/billofmaterials');
    }

    public function edit(Request $request, $id)
    {
        $page_title = 'Edit BOM';

        $page_description = '';

        $billofmaterials = \App\Models\BillOfMaterials::find($id);
        $billofmaterials_details = \App\Models\BillOfMaterialsDetail::where('billofmaterials_id', $billofmaterials->id)->get();

        return view('admin.billofmaterials.edit', compact('page_title', 'page_description', 'billofmaterials', 'billofmaterials_details'));
    }

    public function update(Request $request, $id)
    {
        $billofmaterials = \App\Models\BillOfMaterials::find($id);

        $attributes = $request->all();

        $attributes['org_id'] = Auth::user()->org_id;
        $attributes['user_id'] = Auth::user()->id;

        $attributes['total_amount'] = $request->final_total;

        $attributes['bill_date'] = \Carbon\Carbon::today()->format('Y-m-d');
        $attributes['product'] = \App\Models\Product::where('name', $request->product)->first()->id;

        $billofmaterials->update($attributes);

        \App\Models\BillOfMaterialsDetail::where('billofmaterials_id', $billofmaterials->id)->delete();

        $product_id = $request->product_id;
        $units = $request->units;
        $quantity = $request->quantity;
        $wastage_qty = $request->wastage_qty;
        $cost_price = $request->cost_price;
        $total = $request->total;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new \App\Models\BillOfMaterialsDetail();
                $detail->billofmaterials_id = $billofmaterials->id;
                $detail->product_id = $product_id[$key];
                $detail->units = $units[$key];
                $detail->quantity = $quantity[$key];
                $detail->wastage_qty = $wastage_qty[$key];
                $detail->cost_price = $cost_price[$key];
                $detail->total = $total[$key];

                $detail->save();
            }
        }

        Flash::success('BOM Updated Successfully.');

        return redirect('/admin/billofmaterials');
    }

    public function pdf($id)
    {
        $billofmaterials = \App\Models\BillOfMaterials::find($id);
        $billofmaterials_details = \App\Models\BillOfMaterialsDetail::where('billofmaterials_id', $billofmaterials->id)->get();

        $imagepath = Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.billofmaterials.pdf', compact('billofmaterials', 'imagepath', 'billofmaterials_details'));
        $file = $billofmaterials->productname->name.'-BOM.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function print($id)
    {
        $billofmaterials = \App\Models\BillOfMaterials::find($id);
        $billofmaterials_details = \App\Models\BillOfMaterialsDetail::where('billofmaterials_id', $billofmaterials->id)->get();

        $imagepath = Auth::user()->organization->logo;

        return view('admin.billofmaterials.print', compact('billofmaterials', 'imagepath', 'billofmaterials_details', 'print_no'));
    }

    public function destroy($id)
    {

        //dd($id);
        $ord = \App\Models\BillOfMaterials::find($id);

        \App\Models\BillOfMaterials::find($id)->delete();
        \App\Models\BillOfMaterialsDetail::where('billofmaterials_id', $id)->delete();

        Flash::success('BOM successfully deleted.');

        return redirect('/admin/billofmaterials');
    }

    public function getModalDelete($id)
    {

        //dd($id);
        $error = null;

        $ord = \App\Models\BillOfMaterials::find($id);

        $modal_title = 'Delete BOM';

        $return = \App\Models\BillOfMaterials::find($id);

        $modal_route = route('admin.billofmaterials.delete', ['id' => $return->id]);

        $modal_body = 'Are you sure you want to delete this BOM?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
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
