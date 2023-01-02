<?php

namespace App\Http\Controllers;

use App\Models\TravelRequest;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarvelRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Admin | Travel | Request';
        $page_description = 'Travel Request Lists';
        $travelrequest = TravelRequest::orderBy('id', 'desc')->paginate(30);

        return view('admin.travelrequest.index', compact('page_description', 'page_title', 'travelrequest'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin | Travel';
        $page_description = 'Travel request description';
        $client = \App\Models\Client::all();
        $staff = \App\User::where('org_id', Auth::user()->org_id)->where('enabled', '1')->get();

        return view('admin.travelrequest.create', compact('page_description', 'page_title', 'client', 'staff'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $travelrequest = $request->all();
        TravelRequest::create($travelrequest);
        Flash::success('Travel request created');

        return redirect('/admin/tarvelrequest');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_title = 'Admin | Travel';
        $page_description = 'Travel request show';
        $travelrequest = TravelRequest::find($id);
        $client = \App\Models\Client::all();
        $staff = \App\User::where('org_id', Auth::user()->org_id)->where('enabled', '1')->get();

        return view('admin.travelrequest.show', compact('page_description', 'page_title', 'client', 'staff', 'travelrequest'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = 'Admin | Travel';
        $page_description = 'Travel request edit';
        $travelrequest = TravelRequest::find($id);
        $client = \App\Models\Client::all();
        $staff = \App\User::where('org_id', Auth::user()->org_id)->where('enabled', '1')->get();

        return view('admin.travelrequest.edit', compact('page_description', 'page_title', 'client', 'staff', 'travelrequest'));
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
        $travelrequestnew = $request->all();
        $travelrequest = TravelRequest::find($id);
        $travelrequest->update($travelrequestnew);
        Flash::success('TravelRequest successfully updated');

        return \redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $travel = TravelRequest::find($id)->delete();
        Flash::success('successfully deleted');

        return \redirect('/admin/tarvelrequest');
    }

    public function getModalDelete(Request $request, $id)
    {
        $error = null;

        $travel = TravelRequest::find($id);

        if (! $travel->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete travel request';
        $type = $request->input('type');
        $modal_route = route('admin.tarvelrequest.delete', $id);

        $modal_body = 'Are you sure you want to delete travel request with client '.$travel->client->name.' and id '.$id;

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
