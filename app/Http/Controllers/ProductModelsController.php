<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use App\Models\ProductSerialNumber;
use Flash;
use Illuminate\Http\Request;

class ProductModelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addProdModel(Request $request, $pid)
    {
        foreach ($request->model as $name) {
            $model = ['product_id'=>$pid, 'model_name'=>$name];
            ProductModel::create($model);
        }
        Flash::success('Product Model successfully added');

        return redirect('/admin/products/'.$pid.'/edit')->with('panel_tab', '2');
    }

    public function confirmdeleteProdmodel($id)
    {
        $modal_title = 'Confirm Delete !!';
        $model = ProductModel::find($id);

        $modal_route = route('admin.delete-prod-model', ['id' => $id]);

        $modal_body = trans('admin/courses/dialog.delete-confirm.body', ['id' => $model->id, 'name' => $model->model_name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function deleteProdmodel($id)
    {
        $pmodel = \App\Models\ProductModel::find($id);
        $pid = $pmodel->product_id;
        $pmodel->delete();
        ProductSerialNumber::where('model_id', $id)->delete();
        Flash::success('Product Model successfully deleted');

        return redirect('/admin/products/'.$pid.'/edit')->with('panel_tab', '2');
    }

    public function addProdserialnum(Request $request, $pid)
    {
        foreach ($request->model_id as $key=>$model_id) {
            $serial_num = ['product_id'=>$pid, 'serial_num'=>$request->serial_num[$key], 'model_id'=>$model_id];
            ProductSerialNumber::create($serial_num);
        }
        $panel_tab = '3';
        Flash::success('Product Serial number successfully added');

        return redirect('/admin/products/'.$pid.'/edit')->with('panel_tab', $panel_tab);
    }

    public function confirmdeleteProdserialnum($id)
    {
        $modal_title = 'Confirm Delete !!';
        $model = ProductSerialNumber::find($id);
        $modal_route = route('admin.delete-prod-serial_num', ['id' => $id]);

        $modal_body = trans('admin/courses/dialog.delete-confirm.body', ['id' => $model->id, 'name' => $model->model_name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function deleteProdserialnum($id)
    {
        $serial_num = ProductSerialNumber::find($id);
        $pid = $serial_num->product_id;
        $serial_num->delete();
        Flash::success('Product Serial Number successfully deleted');

        return redirect('/admin/products/'.$pid.'/edit')->with('panel_tab', '3');
    }
}
