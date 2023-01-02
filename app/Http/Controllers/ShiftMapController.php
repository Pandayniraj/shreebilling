<?php

namespace App\Http\Controllers;

use Flash;
use Illuminate\Http\Request;

class ShiftMapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Admin | Shift | Map';
        $page_description = 'List of Shifts';
        $shifts = \App\Models\ShiftMap::orderBy('id', 'desc')->get();

        return view('admin.shiftmaps.index', compact('shifts', 'page_title', 'page_description'));
    }

    public function create()
    {
        $page_title = 'Create | Shift | Map';
        $page_description = '';

        $shifts = \App\Models\Shift::orderBy('id', 'desc')->select('id', 'shift_name')->get();
        $departments = \App\Models\Department::pluck('deptname as name', 'departments_id as id');
        $users = \App\User::where('enabled', '1')->get();

        return view('admin.shiftmaps.create', compact('page_title', 'page_description', 'shifts', 'departments', 'users'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'shift_id'      => 'required',

        ]);

        $attributes = $request->all();

        $tags = $request->user_id;

        $user_id = [];

        foreach ($tags as $value) {
            $username_id = \App\User::where('id', $value)->first()->id;

            if ($username_id) {
                array_push($user_id, $username_id);
            }
        }

        $attributes['user_id'] = implode(',', $user_id);

        $shifts = \App\Models\ShiftMap::create($attributes);
        Flash::success('Shift Map  sucessfully added');

        return redirect('/admin/shifts/maps');
    }

    public function edit($id)
    {
        $shiftmaps = \App\Models\ShiftMap::find($id);

        $page_title = 'Edit Shift Map';
        $page_description = '';

        $shifts = \App\Models\Shift::orderBy('id', 'desc')->select('id', 'shift_name')->get();

        $user_id = explode(',', $shiftmaps->user_id);

        //dd($user_id);

        $username_final = [];

        foreach ($user_id as $user) {
            $username_id = \App\User::find($user)->id;
            if ($username_id) {
                array_push($username_final, $username_id);
            }
        }

        $user_id = $username_final;

        $departments = \App\Models\Department::where(function ($query) use ($shiftmaps) {
            if ($shiftmaps->departments) {
                return $query->where('departments_id', $shiftmaps->departments);
            }
        })->pluck('deptname as name', 'departments_id as id');
        $users = \App\User::where('enabled', '1')->get();

        return view('admin.shiftmaps.edit', compact('shifts', 'page_title', 'page_description', 'shiftmaps', 'user_id', 'departments', 'users'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'shift_id'      => 'required',

        ]);

        $attributes = $request->all();

        //dd($attributes);
        $tags = $request->user_id;

        $user_id = [];

        foreach ($tags as $value) {
            $username_id = \App\User::where('id', $value)->first()->id;

            if ($username_id) {
                array_push($user_id, $username_id);
            }
        }

        $attributes['user_id'] = implode(',', $user_id);

        \App\Models\ShiftMap::find($id)->update($attributes);

        Flash::success('Shift Map sucessfully updated');

        return redirect()->back();
    }

    public function getModalDelete($id)
    {
        $error = null;
        $shiftmap = \App\Models\ShiftMap::find($id);

        $modal_title = 'Delete Shift Map';
        $modal_body = 'Are you sure that you want to delete Shift Map id '.$Shiftmap->id.'? This operation is irreversible';

        $modal_route = route('admin.shift.maps.delete', $shiftmap->id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroy($id)
    {
        $trasnactiongroups = \App\Models\ShiftMap::find($id)->delete();
        Flash::success('Shift Map sucessfully deleted');

        return redirect('/admin/shifts/maps');
    }

    public function bulkindex()
    {
        $page_title = 'Admin | Shifts';
        $projects = \App\Models\Projects::all();

        return view('admin.shiftmaps.bulkcreate', compact('projects', 'page_title'));
    }

    public function bulkcreate(Request $request)
    {
        $page_title = 'Admin | Timesheet';
        $projects = \App\Models\Projects::all();
        $project_id = $request->project_id;
        $users = \App\User::where('project_id', $project_id)->orderBy('username', 'ASC')->where('enabled', '1')->get();
        $shifts = \App\Models\Shift::orderBy('id', 'desc')->pluck('shift_name as name', 'id')->all();

        return view('admin.shiftmaps.bulkcreate', compact('projects', 'users', 'project_id', 'shifts', 'page_title'));
    }

    public function bulkstore(Request $request)
    {
        foreach ($request->user_id as $key => $value) {
            $attributes[] = ['user_id'=>$value, 'map_from_date'=>($request->map_from_date)[$key], 'map_to_date'=>($request->map_to_date)[$key], 'shift_id'=>($request->shift_id)[$key]];
        }
        \App\Models\ShiftMap::insert($attributes);
        Flash::success('TimeSheet Created');

        return redirect('/admin/shifts/maps/');
    }
}
