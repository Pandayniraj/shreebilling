<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $brand;
    public function __construct(Brand $brand)
    {
        $this->brand=$brand;
    }

    public function index()
    {
        $page_title = 'Brands';
        $page_description = 'All Brands';

        $brands= $this->brand->orderBy('id', 'desc')->paginate(25);
        //  dd($airlines);

        return view('admin.brands.index', compact('page_title', 'page_description', 'brands'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Brand';
        $page_description = 'Create Brand';
        return view('admin.brands.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, ['name' => 'required',
        ]);

        $attributes = $request->all();

        $this->brand->create($attributes);

        Flash::success('Brand Successfully Created');
        return redirect(route('admin.brand.index'));

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = 'Edit Brand';
        $page_description = 'Edit Brand';

        $brand = $this->brand->find($id);
        return view('admin.brands.edit', compact('page_title', 'page_description','brand'));
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
        $attributes = $request->all();

        $brand = $this->brand->find($id);
        $brand->update($attributes);

        Flash::success('Brand Update Successfully');
        return redirect('admin/brands');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getModalDelete($id)
    {

        $error = null;

        $brand = $this->brand->find($id);


        $modal_title = "Delete Brand?";
        $brand = $this->brand->find($id);
        $modal_route = route('admin.brand.delete', array('id' => $brand->id));

        $modal_body = "Are You Sure Youy Want To Delete This? This Is Irreversible";
        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroy($id)
    {

        $brand = $this->brand->find($id);



        if (!$brand->isDeletable()) {
            abort(403);
        }

        $brand->delete($id);

        Flash::success('Brand successfully deleted');

        return redirect(route('admin.brand.index'));
    }
}
