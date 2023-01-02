<?php

namespace App\Http\Controllers;

use App\Models\ProductsUnit;
use Flash;
use Illuminate\Http\Request;

class ProductUnitController extends Controller
{
    public function productUnitIndex()
    {
        $page_title = 'Admin | show';
        $productunit = ProductsUnit::orderBy('id', 'desc')->get();

        return view('admin.productunit.product-unit-index', compact('productunit', 'page_title'));
    }

    public function productUnit()
    {
        $page_title = 'Admin | create';
        $description = 'Create product unit';

        return view('admin.productunit.product-unit', compact('description', 'page_title'));
    }

    public function storeProductUnit(Request $request)
    {
        $_unit = $request->all();
        $product_unit = ProductsUnit::create($_unit);
        Flash::success('Product unit Added');

        return redirect('/admin/production/product-unit-index/');
    }

    public function deleteproductUnitModal($id)
    {
        $error = null;
        $product_unit = ProductsUnit::find($id);
        $modal_title = 'Delete product unit';
        $modal_body = 'Are you sure that you want to delete product unit '.$product_unit->id.'? This operation is irreversible';
        $modal_route = route('admin.productunit.delete-produnit', $product_unit->id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function deleteproductUnit($id)
    {
        $d = ProductsUnit::find($id)->delete();
        Flash::success('Product unit SucessFully Deleted !!');

        return redirect('/admin/production/product-unit-index/');
    }

    public function editproductUnit($id)
    {
        $page_title = 'Admin | edit';
        $description = 'Edit product unit';
        $edit = ProductsUnit::find($id);

        return view('admin.productunit.product-unit', compact('description', 'edit', 'page_title'));
    }

    public function updateprodUnit($id, Request $request)
    {
        $product_unit = $request->all();
        ProductsUnit::find($id)->update($product_unit);
        Flash::success('Product unit SucessFully Updated !!');

        return redirect('/admin/production/product-unit-index/');
    }
}
