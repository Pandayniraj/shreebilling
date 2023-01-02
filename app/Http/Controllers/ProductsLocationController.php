<?php

namespace App\Http\Controllers;

use App\Models\ProductLocation;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Admin | index';
        $location = \App\Models\ProductLocation::all();

        return view('admin.product_location.index', compact('location', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin | create';
        $description = 'Create Product Locations';
        return view('admin.product_location.create', compact('page_title','description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loc = $request->all();
        $loc['org_id'] = Auth::user()->org_id;
        if ($request->enabled) {
            $loc['enabled'] = 1;
        } else {
            $loc['enabled'] = 0;
        }
        \App\Models\ProductLocation::create($loc);
        Flash::success('Poduct Location  SucessFully Added !!');

        return redirect('/admin/product-location/index/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_title = 'Admin | show';
        $show = ProductLocation::find($id);

        return view('admin.product_location.show', compact('show', 'page_title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = 'Admin | edit';
        $edit = ProductLocation::find($id);

        return view('admin.product_location.edit', compact('edit', 'page_title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $loc = $request->all();
        if ($request->enabled) {
            $loc['enabled'] = 1;
        } else {
            $loc['enabled'] = 0;
        }
        $edit = ProductLocation::find($id)->update($loc);
        Flash::success('Poduct Location  SucessFully Updated !!');

        return redirect('/admin/product-location/index/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteModal($id)
    {
        $error = null;
        $product_loc = ProductLocation::find($id);
        $modal_title = 'Delete product location';
        $modal_body = 'Are you sure that you want to delete product location '.$product_loc->id.'? This operation is irreversible';
        $modal_route = route('admin.product-location.delete', $product_loc->id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroy($id)
    {
        $product_loc = ProductLocation::find($id)->delete();
        Flash::success('Poduct Location  SucessFully deleted !!');

        return redirect('/admin/product-location/index/');
    }
}
