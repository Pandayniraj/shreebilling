<?php

namespace App\Http\Controllers;

use App\Models\CityMaster;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class CitymasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $city;
    public function __construct(CityMaster $city)
    {
     $this->city=$city ;
    }

    public function index()
    {
        $page_title = 'Admin | All Cities';
        $cities = $this->city->orderBy('id', 'desc')->paginate(30);

        return view('admin.city-master.index', compact('page_title', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin|City Master';
        $page_description = 'Create new job sheet';
        $countries = \App\Models\Country::pluck('name', 'id');

        return view('admin.city-master.create', compact('page_title', 'page_description','countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

        $city=$this->city->create($request->all());

        Flash::success('City SuccessFully Created');
        return redirect()->to('/admin/cities');
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
        $page_title = 'Admin| City';
        $page_description = 'Create new City';
        $city = $this->city->find($id);
        $countries = \App\Models\Country::pluck('name', 'id');

        return view('admin.city-master.edit', compact('page_title', 'page_description','city','countries'));
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

        $city=$this->city->find($id)->update($request->all());

        Flash::success('City Update Successfully');
        return redirect()->to('/admin/cities');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $city = $this->city->find($id);

//        if (! $jobSheet->isdeletable()) {
//            abort(403);
//        }
        $city->delete();


        Flash::success('City deleted.');


        return redirect()->back();
    }

    public function deleteModal($id)
    {
        $error = null;

        $city = $this->city->find($id);

//        if (! $jobSheet->isdeletable()) {
//            abort(403);
//        }

        $modal_title = 'Delete City';

        $city = $this->city->find($id);
        if (\Request::get('type')) {
            $modal_route = route('admin.city.delete', $city->id).'?type='.\Request::get('type');
        } else {
            $modal_route = route('admin.city.delete',$city->id);
        }

        $modal_body = 'Are you sure you want to delete this City?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
