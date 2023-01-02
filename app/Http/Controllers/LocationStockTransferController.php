<?php

namespace App\Http\Controllers;

use App\Models\LocationStockTransfer;
use App\Models\LocationStockTransferDetail;
use App\Models\StockMove;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class LocationStockTransferController extends Controller
{
    public function index()
    {
        $locationstocktransfer = \App\Models\LocationStockTransfer::orderBy('id', 'desc')->get();

        $page_title = 'Admin | Location Stock Transfer | Index';
        $page_description = 'lists of Transfers';

        return view('admin.locationstocktransfer.index', compact('page_title', 'page_description', 'locationstocktransfer'));
    }

    public function create()
    {
        $page_title = 'Admin | Location Stock Transfer | Create';
        $page_description = 'for creating stock transfer';

        $users = \App\User::where('enabled', '1')->pluck('username', 'id')->all();
        $products = \App\Models\Product::select('id', 'name')->get();
        // $locations = \App\Models\ProductLocation::pluck('location_name', 'id')->all();
        $locations  = \App\Models\PosOutlets::pluck('name', 'id')->all();


        return view('admin.locationstocktransfer.create', compact('page_title', 'page_description', 'users', 'locations', 'products'));
    }

    public function store(Request $request)
    {
        $attributes = $request->all();

        if ($request->source_id == $request->destination_id) {
            Flash::error('Source and Destination Cannot Be Same');

            return redirect()->back();
        }

        //dd($attributes);

        $locationstocktransfer = LocationStockTransfer::create($attributes);

        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $reason = $request->reason;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new LocationStockTransferDetail();
                $detail->location_stock_transfer_id = $locationstocktransfer->id;
                $detail->product_id = $product_id[$key];
                $detail->reason = $reason[$key];
                $detail->quantity = $quantity[$key];
                $detail->save();

                $stockMove = new StockMove();
                $stockMove->stock_id = $product_id[$key];
                $stockMove->trans_type = STOCKMOVEIN;
                $stockMove->tran_date = \Carbon\Carbon::now();
                $stockMove->user_id = Auth::user()->id;
                $stockMove->reference = 'store_in_'.$locationstocktransfer->id;
                $stockMove->transaction_reference_id = $locationstocktransfer->id;
                $stockMove->store_id = $request->destination_id;
                $stockMove->qty = $quantity[$key];
                $stockMove->save();

                $stockMove = new StockMove();
                $stockMove->stock_id = $product_id[$key];
                $stockMove->tran_date = \Carbon\Carbon::now();
                $stockMove->user_id = Auth::user()->id;
                $stockMove->reference = 'store_out_'.$locationstocktransfer->id;
                $stockMove->transaction_reference_id = $locationstocktransfer->id;
                $stockMove->qty = '-'.$quantity[$key];
                $stockMove->trans_type = STOCKMOVEOUT;
                $stockMove->order_no = $locationstocktransfer->id;
                $stockMove->store_id = $request->source_id;
                $stockMove->order_reference = $locationstocktransfer->id;
                $stockMove->save();
            }
        }

        Flash::success('Location Transfer Successfully Done');

        return redirect('/admin/location/stocktransfer');
    }

    public function show(Request $request, $id)
    {
        $page_title = 'Admin | Location Stock Transfer | Show';
        $page_description = '';

        $locationstocktransfer = LocationStockTransfer::find($id);
        $locationstocktransferdetail = LocationStockTransferDetail::where('location_stock_transfer_id', $locationstocktransfer->id)->get();

        $users = \App\User::where('enabled', '1')->pluck('username', 'id')->all();
        $products = \App\Models\Product::select('id', 'name')->get();
        $locations = \App\Models\ProductLocation::pluck('location_name', 'id')->all();

        return view('admin.locationstocktransfer.show', compact('page_title', 'page_description', 'locationstocktransfer', 'locationstocktransferdetail', 'users', 'products', 'locations'));
    }

    public function edit(Request $request, $id)
    {
        $page_title = 'Admin | Location Stock Transfer | Edit';
        $page_description = '';

        $locationstocktransfer = LocationStockTransfer::find($id);
        $locationstocktransferdetail = LocationStockTransferDetail::where('location_stock_transfer_id', $locationstocktransfer->id)->get();

        $users = \App\User::where('enabled', '1')->pluck('username', 'id')->all();
        $products = \App\Models\Product::select('id', 'name')->get();
        $locations  = \App\Models\PosOutlets::pluck('name', 'id')->all();
        // $locations = \App\Models\ProductLocation::pluck('location_name', 'id')->all();

        return view('admin.locationstocktransfer.edit', compact('page_title', 'page_description', 'locationstocktransfer', 'locationstocktransferdetail', 'users', 'products', 'locations'));
    }

    public function print($id)
    {
        $ord = LocationStockTransfer::find($id);
        $orderDetails = LocationStockTransferDetail::where('location_stock_transfer_id', $id)->get();

        $imagepath = Auth::user()->organization->logo;

        return view('admin.locationstocktransfer.print', compact('ord', 'imagepath', 'orderDetails', 'print_no'));
    }

    public function pdf($id)
    {
        $ord = LocationStockTransfer::find($id);
        $orderDetails = LocationStockTransferDetail::where('location_stock_transfer_id', $id)->get();
        $imagepath = Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.locationstocktransfer.pdf', compact('ord', 'imagepath', 'orderDetails'));
        $file = 'stocktransfer-'.$id.'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function update(Request $request, $id)
    {
        $attributes = $request->all();

        $locationstocktransfer = LocationStockTransfer::find($id)->update($attributes);

        $transfers_details = LocationStockTransferDetail::where('location_stock_transfer_id', $id)->get();

        foreach ($transfers_details as $td) {
            StockMove::where('trans_type', STOCKMOVEIN)->where('stock_id', $td->product_id)->where('reference', 'store_in_'.$id)->delete();
            StockMove::where('trans_type', STOCKMOVEOUT)->where('reference', 'store_out_'.$id)->where('stock_id', $td->product_id)->delete();
        }

        LocationStockTransferDetail::where('location_stock_transfer_id', $id)->delete();

        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $reason = $request->reason;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new LocationStockTransferDetail();
                $detail->location_stock_transfer_id = $id;
                $detail->product_id = $product_id[$key];
                $detail->reason = $reason[$key];
                $detail->quantity = $quantity[$key];
                $detail->save();

                $stockMove = new StockMove();
                $stockMove->stock_id = $product_id[$key];
                $stockMove->trans_type = STOCKMOVEIN;
                $stockMove->tran_date = \Carbon\Carbon::now();
                $stockMove->user_id = Auth::user()->id;
                $stockMove->reference = 'store_in_'.$id;
                $stockMove->transaction_reference_id = $id;
                $stockMove->location = $request->destination_id;
                $stockMove->qty = $quantity[$key];
                $stockMove->save();

                $stockMove = new StockMove();
                $stockMove->stock_id = $product_id[$key];
                $stockMove->tran_date = \Carbon\Carbon::now();
                $stockMove->user_id = Auth::user()->id;
                $stockMove->reference = 'store_out_'.$id;
                $stockMove->transaction_reference_id = $id;
                $stockMove->qty = '-'.$quantity[$key];
                $stockMove->trans_type = STOCKMOVEOUT;
                $stockMove->order_no = $id;
                $stockMove->location = $request->source_id;
                $stockMove->order_reference = $id;
                $stockMove->save();
            }
        }

        Flash::success('Location Transfer Successfully Updated');

        return redirect('/admin/location/stocktransfer');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $locationstocktransfer = LocationStockTransfer::find($id);

        $transfers_detail = LocationStockTransferDetail::where('location_stock_transfer_id', $id)->get();

        foreach ($transfers_detail as $td) {
            StockMove::where('trans_type', STOCKMOVEIN)->where('stock_id', $td->product_id)->where('reference', 'store_in_'.$id)->delete();
            StockMove::where('trans_type', STOCKMOVEOUT)->where('reference', 'store_out_'.$id)->where('stock_id', $td->product_id)->delete();
        }

        $locationstocktransfer->delete();

        LocationStockTransferDetail::where('location_stock_transfer_id', $id)->delete();

        Flash::success('Location Stock Transfer successfully deleted');

        return redirect('/admin/location/stocktransfer');
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

        $locationstocktransfer = LocationStockTransfer::find($id);

        $modal_title = 'Delete Transfer';

        $modal_route = route('admin.location.stocktransfer.delete', ['id' => $locationstocktransfer->id]);

        $modal_body = 'Are you sure that you want to delete Location Stock Transfer id '.$locationstocktransfer->id.' with the number? This operation is irreversible';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function getStockAvailability(Request $request)
    {
        $product_id = $request->product_id;
        $source_id = $request->source_id;
        // dd($product_id,$source_id);
        $transations = DB::table('product_stock_moves')
                              ->where('product_stock_moves.stock_id', $product_id)
                              ->leftjoin('products', 'products.id', '=', 'product_stock_moves.stock_id')
                              ->leftjoin('pos_outlets', 'pos_outlets.id', '=', 'product_stock_moves.store_id')
                              ->select('product_stock_moves.*', 'products.name')
                              ->orderBy('product_stock_moves.tran_date', 'DESC')
                              ->where('product_stock_moves.store_id', $source_id)
                              ->get();
            $StockIn = 0;
            $StockOut = 0;


        if (count($transations) > 0) {
            foreach ($transations as $result) {
                if ($result->qty > 0) {
                    $StockIn += $result->qty;
                }
                if ($result->qty < 0) {
                    $StockOut += $result->qty;
                }
            }
        }

        $available = $StockIn + $StockOut;

        return ['available' => $available];
    }
}
