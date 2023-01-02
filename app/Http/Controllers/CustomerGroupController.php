<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\CustomerGroup;
use Flash;
use Illuminate\Http\Request;

class CustomerGroupController extends Controller
{
    public function __construct(CustomerGroup $customergroups)
    {
        $this->customergroups = $customergroups;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title = 'Admin | Customer & Supplier Groups |Index';
        $page_description = 'Group Index';
        $customergroups = $this->customergroups->where('org_id', \Auth::user()->org_id)->orderBy('id', 'desc')->paginate(30);

        return view('admin.customergroups.index', compact('customergroups', 'page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title = 'Admin | Customer & Supplier Groups |Create';
        $page_description = 'Create a Group';

        return view('admin.customergroups.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $attributes = $request->all();
        $attributes['user_id'] = \Auth::user()->id;
        $attributes['org_id'] = \Auth::user()->org_id;
        Flash::success('Group SucessFully Created');
        $this->customergroups->create($attributes);

        return redirect('/admin/customergroup/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $page_title = 'Admin | Customer & Supplier Groups | Edit';
        $page_description = 'Edit a Group';
        $customergroups = $this->customergroups->find($id);

        return view('admin.customergroups.edit', compact('customergroups', 'page_title', 'page_description'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $customergroups = $this->customergroups->find($id);
        $attributes = $request->all();
        if (! $customergroups->isEditable()) {
            abort(404);
        }
        $customergroups->update($attributes);
        Flash::success('Group Updated');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $customergroups = $this->customergroups->find($id);
        if (! $customergroups->isDeletable()) {
            abort(404);
        }
        $customergroups->delete();
        Flash::success('customergroups deleted');

        return redirect('/admin/customergroup/');
    }

    public function getModalDelete($id)
    {
        $error = null;

        $customergroups = $this->customergroups->find($id);
        $modal_title = 'Delete Groups';
        $modal_body = 'Are you sure you want to delte customergroups with name '.$customergroups->name.' and Id'.$id;
        $modal_route = route('admin.customergroup.delete', ['id' => $id]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
