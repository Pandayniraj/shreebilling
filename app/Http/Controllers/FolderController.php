<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Folder;
use Flash;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function __construct(Folder $folder)
    {
        $this->folder = $folder;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title = 'Admin | Folder |Index';
        $page_description = 'Folder Index';
        $folders = $this->folder->where('org_id', \Auth::user()->org_id)->orderBy('id', 'desc')->paginate(30);

        return view('admin.folder.index', compact('folders', 'page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title = 'Admin | Folder |Create';
        $page_description = 'Create a Folder';
        $users = \App\User::where('enabled','1')->pluck('username as name','id');

        return view('admin.folder.create', compact('page_title', 'page_description','users'));
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

        $attributes['shared_user'] = json_encode($request->shared_user);
        Flash::success('Floder SucessFully Created');
        $this->folder->create($attributes);

        return redirect('/admin/folders/');
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
        $page_title = 'Admin | Folder | Edit';
        $page_description = 'Edit a Folder';
        $folder = $this->folder->find($id);
        $users = \App\User::where('enabled','1')->pluck('username as name','id');

        return view('admin.folder.edit', compact('folder', 'page_title', 'page_description','users'));
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
        $folder = $this->folder->find($id);
        $attributes = $request->all();
         $attributes['shared_user'] = json_encode($request->shared_user);
        if (! $folder->isEditable()) {
            abort(404);
        }
        $folder->update($attributes);
        Flash::success('Folder Updated');

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
        $folder = $this->folder->find($id);
        if (! $folder->isDeletable()) {
            abort(404);
        }
        $folder->delete();
        Flash::success('folder deleted');

        return redirect('/admin/folders/');
    }

    public function getModalDelete($id)
    {
        $error = null;

        $folder = $this->folder->find($id);
        $modal_title = 'Delete Folder';
        $modal_body = 'Are you sure you want to delte folder with name '.$folder->name.' and Id'.$id;
        $modal_route = route('admin.folders.delete', ['id' => $id]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
