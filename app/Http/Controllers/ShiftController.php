<?php

namespace App\Http\Controllers;

use Flash;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Admin | Shift';

        $page_description = 'List of Shifts';

        $shifts = \App\Models\Shift::orderBy('id', 'desc')->get();

        return view('admin.shifts.index', compact('shifts', 'page_title', 'page_description'));
    }

    public function create()
    {
        $page_title = 'Create | Shift';
        $page_description = '';

        return view('admin.shifts.create', compact('page_title', 'page_description'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'shift_name'      => 'required',

        ]);

        $attributes = $request->all();
        if (! isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }
        //dd($attributes);

        $shifts = \App\Models\Shift::create($attributes);
        Flash::success('Shift  sucessfully added');

        return redirect('/admin/shift');
    }

    public function edit($id)
    {
        $shift = \App\Models\Shift::find($id);

        $page_title = 'Edit Shift';
        $page_description = '';

        return view('admin.shifts.edit', compact('shift', 'page_title', 'page_description'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'shift_name'      => 'required',

        ]);

        $attributes = $request->all();
       // dd(\App\Models\Shift::find($id));
        if (! isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        \App\Models\Shift::find($id)->update($attributes);

        Flash::success('Shift sucessfully updated');

        return redirect('/admin/shift');
    }

    public function getModalDelete($id)
    {
        $error = null;
        $shift = \App\Models\Shift::find($id);

        $modal_title = 'Delete Shift';
        $modal_body = 'Are you sure that you want to delete Shift id '.$Shift->id.' with the number '.$shift->shift_name.'? This operation is irreversible';

        $modal_route = route('admin.shift.delete', $shift->id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroy($id)
    {
        $trasnactiongroups = \App\Models\Shift::find($id)->delete();
        Flash::success('Shift  sucessfully deleted');

        return redirect('/admin/shift');
    }

    public function shiftCalender()
    {
        $projects = \App\Models\Projects::all();
        $page_title = 'Employee Shift Calender';
        $page_description = 'Shows the Shift';

        return view('admin.shifts.calender', compact('projects', 'page_title', 'page_description'));
    }
}
