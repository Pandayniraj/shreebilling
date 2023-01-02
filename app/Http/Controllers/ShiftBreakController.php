<?php

namespace App\Http\Controllers;

use App\Models\ShiftBreak;
use Flash;
use Illuminate\Http\Request;

class ShiftBreakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(ShiftBreak $shiftbreak)
    {
        $this->shiftbreak = $shiftbreak;
    }

    public function index()
    {
        $page_title = 'Shift | Breaks';
        $page_description = 'View All Shift Breaks';
        $shiftbreak = $this->shiftbreak->orderBy('id', 'desc')->get();

        return view('admin.shiftbreak.index', compact('shiftbreak', 'page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_description = 'Create New Shift Breaks';
        $page_title = 'Shift | Breaks';
        $shifts = \App\Models\Shift::pluck('shift_name as name', 'id');

        return view('admin.shiftbreak.create', compact('shifts', 'page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->all();
        $this->shiftbreak->create($attributes);
        Flash::success('Shift Break Created');

        return redirect('admin/shiftsBreaks');
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
        $page_title = 'Shift | Breaks';
        $page_description = 'Edit Shift Breaks #'.$id;
        $shifts = \App\Models\Shift::pluck('shift_name as name', 'id');
        $shiftbreak = $this->shiftbreak->find($id);
        if (! $shiftbreak->isEditable()) {
            abort(404);
        }

        return view('admin.shiftbreak.edit', compact('shiftbreak', 'shifts', 'page_title', 'page_description'));
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
        $shiftbreak = $this->shiftbreak->find($id);
        if (! $shiftbreak->isEditable()) {
            abort(404);
        }
        $attributes = $request->all();
        $shiftbreak->update($attributes);
        Flash::success('Shift Break Updated');

        return redirect()->back();
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
        $shift = $this->shiftbreak->find($id);

        $modal_title = 'Delete Shift Break';
        $modal_body = 'Are you sure that you want to delete Shift Break id '.$shift->id.' with the shift name '.$shift->shift->shift_name.'? This operation is irreversible';

        $modal_route = route('admin.shiftsBreaks.delete', $shift->id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroy($id)
    {
        $shiftbreak = $this->shiftbreak->find($id);

        if (! $shiftbreak->isDeletable()) {
            abort(404);
        }
        $shiftbreak->delete();
        Flash::success('Shift Breaks  sucessfully deleted');

        return redirect('/admin/shiftsBreaks');
    }
}
