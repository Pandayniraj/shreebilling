<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Team;
use Flash;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __construct(Team $teams)
    {
        $this->teams = $teams;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title = 'Admin | Team |Index';
        $page_description = 'Folder Index';
        $teams = $this->teams->where('org_id', \Auth::user()->org_id)->orderBy('id', 'desc')->paginate(30);

        return view('admin.teams.index', compact('teams', 'page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title = 'Admin | Team |Create';
        $page_description = 'Create a Folder';

        return view('admin.teams.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $check = $this->teams->where('name', 'LIKE', $request->name)->first();
        if ($check) {
            Flash::error('Team Name Must Be Unique');

            return redirect()->back();
        }
        $attributes = $request->all();
        $attributes['user_id'] = \Auth::user()->id;
        $attributes['org_id'] = \Auth::user()->org_id;
        Flash::success('Teams SucessFully Created');
        $this->teams->create($attributes);

        return redirect('/admin/teams/');
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
        $page_title = 'Admin | Team | Edit';
        $page_description = 'Edit a Folder';
        $teams = $this->teams->find($id);

        return view('admin.teams.edit', compact('teams', 'page_title', 'page_description'));
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
        $check = $this->teams->where('id', '!=', $id)
                ->where('name', 'LIKE', $request->name)
                ->first();
        if ($check) {
            Flash::error('Team Name Must Be Unique');

            return redirect()->back();
        }
        $teams = $this->teams->find($id);
        $attributes = $request->all();
        if (! $teams->isEditable()) {
            abort(404);
        }
        $teams->update($attributes);
        Flash::success('Team Updated');

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
        $teams = $this->teams->find($id);
        if (! $teams->isDeletable()) {
            abort(404);
        }
        $teams->delete();
        Flash::success('Team deleted');

        return redirect('/admin/teams/');
    }

    public function getModalDelete($id)
    {
        $error = null;

        $teams = $this->teams->find($id);
        $modal_title = 'Delete Teams';
        $modal_body = 'Are you sure you want to delte teams with name '.$teams->name.' and Id'.$id;
        $modal_route = route('admin.teams.delete', ['id' => $id]);

        return view('modal_confirmation', compact( 'modal_route', 'modal_title', 'modal_body'));
    }

    public function addUser($id)
    {
        $page_title = 'Admin | Team | '.$id;
        $team = $this->teams->findOrFail($id);
        $team_users = \App\Models\UserTeam::where('team_id', $id)->get();
        $current_user_id = $team_users->pluck('user_id');
        $users = \App\User::where('enabled', '1')->whereNotIn('id', $current_user_id)->get();

        return view('admin.teams.view_add_users', compact('page_title','users','team',
            'team_users'));
    }

    public function postUser(Request $request, $teamid)
    {
        $attributes = $request->all();
        \App\Models\UserTeam::create($attributes);
        Flash::success('User SucessFully Added On Team');

        return redirect()->back();
    }

    public function removeTeamMemberModal($id)
    {
        $teams = \App\Models\UserTeam::find($id);
        $modal_title = 'Remove Team Member';
        $modal_body = 'Are you sure you want to Remove teams with name '.$teams->user->username;
        $modal_route = route('admin.users.teams.delete', ['id' => $id]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function removeTeamMember($id)
    {
        $teams = \App\Models\UserTeam::find($id);
        if (! $teams->isDeletable()) {
            abort(404);
        }
        $teams->delete();
        Flash::success('Team Member Removed');

        return redirect()->back();
    }
}
