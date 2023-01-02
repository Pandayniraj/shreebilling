<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $division;
    public function __construct(Division $division)
    {
        $this->division=$division;
    }

    public function index()
    {
        $page_title = 'Division';
        $page_description = 'All Division';

        $divisions = $this->division->orderBy('id', 'desc')->paginate(25);
        //  dd($airlines);

        return view('admin.division.index', compact('page_title', 'page_description', 'divisions'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Division Create';
        $page_description = 'Create Division';
        return view('admin.division.create', compact('page_title', 'page_description'));
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

        $this->division->create($attributes);

        Flash::success('Division Successfully Created');

        return redirect('admin/division');

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
        $page_title = 'Edit Division';
        $page_description = 'Edit Division';
        $division =$this->division->find($id);
        return view('admin.division.edit', compact('page_title', 'page_description','division'));
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

        $division =$this->division->find($id);

        if ($division) {
            $division->update($attributes);
        }
        Flash::success('Division Update Successfully');
        return redirect('admin/division');
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

        $division =$this->division->find($id);


        if (!$division->isDeletable()) {
            abort(403);
        }

        $modal_title = "Delete Division?";
        $division =$this->division->find($id);
        $modal_route = route('admin.division.delete', array('id' => $division->id));

        $modal_body = "Are You Sure Youy Want To Delete This? This Is Irreversible";
        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroy($id)
    {

        $division =$this->division->find($id);


        if (!$division->isDeletable()) {
            abort(403);
        }

        $division->delete($id);

        Flash::success('Division successfully deleted');

        return redirect('admin/division');
    }
}
