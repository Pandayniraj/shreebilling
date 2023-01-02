<?php

namespace App\Http\Controllers;

use App\Models\ProjectTaskStatus;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProjectGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $page_title = 'Admin | Project | Groups';
        $groups = ProjectTaskStatus::where('project_id', $id)->get();
        $project_name = \App\Models\Projects::find($id)->name;

        return view('admin.projects.groups.index', compact('groups', 'project_name', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $page_title = 'Admin | Project | Groups';
        $page_descriptions = 'Create a new projects groups';

        return view('admin.projects.groups.create', compact('page_descriptions', 'page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private static function make_name_slug($string)
    {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", '', $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", ' ', $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", '-', $string);

        return $string;
    }

    public function store(Request $request, $id)
    {
        $this->validate($request, [
            'name'      => 'required|max:100',
            'description'     => 'required',
        ]);
        $default = ['new', 'ongoing', 'completed'];
        $name = self::make_name_slug($request->name);
        $check = ProjectTaskStatus::where('name', $name)->where('project_id', $id)->exists();
        if ($check) {
            return Redirect::back()->withErrors(['Group Name cannot be duplicate']);
        }
        if (in_array($name, $default)) {
            return Redirect::back()->withErrors(['Please choose different name except '.implode(',', $default)]);
        }

        $attributes = $request->all();
        $attributes['name'] = $name;
        $attributes['enabled'] = isset($request->enabled) ? '1' : '0';
        $attributes['project_id'] = $id;
        ProjectTaskStatus::create($attributes);
        Flash::success('Project Group Created');

        return redirect('/admin/projectsgroups/'.$id);
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
    public function edit($project_id, $id)
    {
        $page_title = 'Admin | Project | Groups';
        $page_descriptions = 'Create a new projects groups';
        $group = ProjectTaskStatus::find($id);

        return view('admin.projects.groups.edit', compact('group', 'page_title', 'page_descriptions'));
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
        $this->validate($request, [
            'description'     => 'required',
        ]);

        $default = ['new', 'ongoing', 'completed'];
        $group = ProjectTaskStatus::find($id);
        $name = strtolower($request->name);
        $attributes['enabled'] = isset($request->enabled) ? '1' : '0';
        $attributes['description'] = $request->description;
        if (! $group->isEditable()) {
            abort(404);
        }
        $group->update($attributes);
        Flash::success('Group Description Updated');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function enabledisable($id)
    {
        $status = ProjectTaskStatus::find($id);
        if (! $status->isEditable()) {
            abort(404);
        }
        $attributes['enabled'] = $status->enabled ? '0' : '1';
        if ($attributes['enabled']) {
            Flash::success('Project Status Enabled');
        } else {
            Flash::success('Project Status Disabled');
        }
        $status->update($attributes);

        return redirect()->back();
    }
}
