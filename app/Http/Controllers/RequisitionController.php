<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\LocationStockTransfer;
use App\Models\LocationStockTransferDetail;
use App\Models\Product;
use App\Models\Requisition;
use App\Models\Requisition_Detail;
use App\Models\StockMove;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class RequisitionController extends Controller
{
    public function index()
    {
        $requisitions = \App\Models\Requisition::orderBy('id', 'desc')->get();

        $page_title = 'Admin | Requisition | Index';
        $page_description = 'lists of Transfers';

        return view('admin.requisition.index', compact('page_title', 'page_description', 'requisitions'));
    }

    public function create()
    {
        $page_title = 'Admin | Requisition | Create';
        $page_description = 'for creating Requisition';
        $departments=Department::select('departments_id','deptname')->get();
        $products = \App\Models\Product::select('id', 'name')->get();
        $units = \App\Models\ProductsUnit::select('id', 'name')->get();


        return view('admin.requisition.create', compact('page_title', 'page_description', 'products','units','departments'));
    }

    public function store(Request $request)
    {


        $attributes = $request->all();
        $attributes['user_id']=\auth()->user()->id;
        \DB::beginTransaction();

        $requisition = Requisition::create($attributes);

        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $reason = $request->reason;
        $unit = $request->unit;
        $required_by = $request->required_by;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new Requisition_Detail();
                $detail->requisition_id = $requisition->id;
                $detail->product_id = $product_id[$key];
                $detail->reason = $reason[$key];
                $detail->unit = $unit[$key];
                $detail->required_by = $required_by[$key];
                $detail->quantity = $quantity[$key];
                $detail->save();


            }
        }
        \DB::commit();

        Flash::success('Requisition created Successfully');

        return redirect('/admin/requisition');
    }

    public function show(Request $request, $id)
    {
        $page_title = 'Admin | Requisition | Show';
        $page_description = '';

        $requisition = Requisition::find($id);
        $requisitiondetail = Requisition_Detail::where('requisition_id', $requisition->id)->get();

        $users = \App\User::where('enabled', '1')->pluck('username', 'id')->all();
        $products = \App\Models\Product::select('id', 'name')->get();
        $locations = \App\Models\ProductLocation::pluck('location_name', 'id')->all();

        return view('admin.requisition.show', compact('page_title', 'page_description', 'locationstocktransfer', 'locationstocktransferdetail', 'users', 'products', 'locations'));
    }
    public function approve($id)
    {

        $requisition = Requisition::find($id);

        $requisition->update(['approved_by'=>\auth()->user()->id]);

        Flash::success('Requisition Approved Successfully');

        return redirect('/admin/requisition');
    }

    public function edit(Request $request, $id)
    {
        $page_title = 'Admin | Requisition | Edit';
        $page_description = '';
        $departments=Department::select('departments_id','deptname')->get();

        $requisition = Requisition::find($id);
        $requisitiondetail = Requisition_Detail::where('requisition_id', $requisition->id)->get();

        $products = \App\Models\Product::select('id', 'name')->get();
        $units = \App\Models\ProductsUnit::select('id', 'name')->get();

        return view('admin.requisition.edit', compact('page_title', 'page_description', 'requisition', 'requisitiondetail', 'products','units','departments'));
    }

    public function print($id)
    {
        $ord = Requisition::find($id);
        $orderDetails = Requisition_Detail::where('requisition_id', $id)->get();

        $imagepath = Auth::user()->organization->logo;

        return view('admin.requisition.print', compact('ord', 'imagepath', 'orderDetails', 'print_no'));
    }

    public function pdf($id)
    {
        $ord = Requisition::find($id);
        $orderDetails = Requisition_Detail::where('requisition_id', $id)->get();
        $imagepath = Auth::user()->organization->logo;

        $pdf = \PDF::loadview('admin.requisition.pdf', compact('ord', 'imagepath', 'orderDetails'));
        $file = 'stocktransfer-'.$id.'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function update(Request $request, $id)
    {
        $attributes = $request->all();
        \DB::beginTransaction();

        $requisition = Requisition::find($id)->update($attributes);

        $transfers_details = Requisition_Detail::where('requisition_id', $id)->get();

        foreach ($transfers_details as $td) {
            StockMove::where('trans_type', STOCKMOVEIN)->where('stock_id', $td->product_id)->where('reference', 'store_in_'.$id)->delete();
            StockMove::where('trans_type', STOCKMOVEOUT)->where('reference', 'store_out_'.$id)->where('stock_id', $td->product_id)->delete();
        }

        Requisition_Detail::where('requisition_id', $id)->delete();


        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $reason = $request->reason;
        $unit = $request->unit;
        $required_by = $request->required_by;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new Requisition_Detail();
                $detail->requisition_id = $id;
                $detail->product_id = $value;
                $detail->reason = $reason[$key];
                $detail->unit = $unit[$key];
                $detail->required_by = $required_by[$key];
                $detail->quantity = $quantity[$key];;
                $detail->save();
            }
        }
        \DB::commit();

        Flash::success('Requisition Updated');

        return redirect('/admin/requisition');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $requisition = Requisition::find($id);

        $transfers_detail = Requisition_Detail::where('requisition_id', $id)->get();

        foreach ($transfers_detail as $td) {
            StockMove::where('trans_type', STOCKMOVEIN)->where('stock_id', $td->product_id)->where('reference', 'store_in_'.$id)->delete();
            StockMove::where('trans_type', STOCKMOVEOUT)->where('reference', 'store_out_'.$id)->where('stock_id', $td->product_id)->delete();
        }

        $requisition->delete();

        Requisition_Detail::where('requisition_id', $id)->delete();

        Flash::success('Requisition successfully deleted');

        return redirect('/admin/requisition');
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

        $requisition = Requisition::find($id);

        $modal_title = 'Delete Requisition';

        $modal_route = route('admin.requisition.delete', ['id' => $requisition->id]);

        $modal_body = 'Are you sure that you want to delete Requisition'.$requisition->id.' with the number? This operation is irreversible';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function getStockUnit(Request $request)
    {
        $product_id = $request->product_id;
        $unit=Product::find($product_id)->product_unit;
        return ['unit' => $unit];
    }
}
