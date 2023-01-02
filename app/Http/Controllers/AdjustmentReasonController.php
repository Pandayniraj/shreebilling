<?php

namespace App\Http\Controllers;

use App\Models\AdjustmentReason;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class AdjustmentReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $reason;
    public function __construct(AdjustmentReason $reason)
    {
        $this->reason=$reason;
    }

    public function index()
    {
        $page_title = 'Admin | Adjustment Reason';
        $reasons = $this->reason->orderBy('id', 'desc')->paginate(30);

        return view('admin.adjustmentReason.index', compact('page_title', 'reasons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin|Adjustment Reason';
        $page_description = 'Create new budget';

        return view('admin.adjustmentReason.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->reason->create($request->all());

        Flash::success('Reason SuccessFully Created');
        return redirect('/admin/adjustment-reason');
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
        $page_title = 'Admin|Adjustment Reason';
        $page_description = 'Create new Reason';
        $reason = $this->reason->find($id);

        return view('admin.adjustmentReason.edit', compact('page_title', 'page_description','reason'));
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

        $this->reason->find($id)->update($request->all());

        Flash::success('Reason Update Successfully');
        return redirect('/admin/adjustment-reason');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reason = $this->reason->find($id);

        if (! $reason->isdeletable()) {
            abort(403);
        }

        $reason->delete();


        Flash::success('Reason deleted.');


        return redirect('/admin/adjustment-reason');
    }

    public function deleteModal($id)
    {
        $error = null;

        $reason = $this->reason->find($id);

        if (! $reason->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Adjustment Reason';

        $reason = $this->reason->find($id);
        if (\Request::get('type')) {
            $modal_route = route('admin.adjustment-reason.delete', ['id' => $reason->id]).'?type='.\Request::get('type');
        } else {
            $modal_route = route('admin.adjustment-reason.delete', ['id' => $reason->id]);
        }

        $modal_body = 'Are you sure you want to delete this Reason?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}