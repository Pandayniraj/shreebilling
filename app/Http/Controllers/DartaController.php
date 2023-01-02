<?php

namespace App\Http\Controllers;

use App\Models\Darta;
use App\Models\DartaChalaniFile;
use Flash;
use Illuminate\Http\Request;

class DartaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Darta $darta, DartaChalaniFile $files)
    {
        $this->darta = $darta;

        $this->files = $files;
        parent::__construct();
    }

    public function index()
    {
        $darta = $this->darta->where(function ($query) {
            $terms = \Request::get('term');
            if ($terms) {
                return $query->where('darta_num', $terms)
                                    ->orWhere('id', $terms)
                                    ->orWhere('subject', 'LIKE', '%'.$terms.'%');
            }
        })->orderBy('id', 'desc')->paginate(30);
        $page_title = 'Admin|Darta';

        return view('admin.darta.index', compact('page_title', 'darta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin|Darta';
        $page_description = 'Create new Darta';
        $users = \App\User::select('*', \DB::raw("CONCAT(first_name,' ',last_name,'[',id,']') as name"))->pluck('name', 'id');

        return view('admin.darta.create', compact('page_title', 'users', 'page_description'));
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
        $attributes['user_id'] = \Auth::user()->id;

        $darta = $this->darta->create($attributes);

        $files = $request->file('attachment');

        $destinationPath = public_path('/darta/');

        if (! \File::isDirectory($destinationPath)) {
            \File::makeDirectory($destinationPath, 0777, true, true);
        }

        foreach ($files as $key=>$doc_) {
            if ($doc_) {
                $doc_name = time().''.$doc_->getClientOriginalName();
                $doc_->move($destinationPath, $doc_name);
                $attachment = ['type'=>'darta', 'attachment'=>$doc_name, 'parent_id'=>$darta->id];
                $this->files->create($attachment);
            }
        }

        Flash::success('Darta SuccessFully Created');

        return redirect('/admin/darta');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_title = 'Admin|Darta';
        $page_description = 'Edit Darta';
        $users = \App\User::select('*', \DB::raw("CONCAT(first_name,' ',last_name,'[',id,']') as name"))->pluck('name', 'id');

        return view('admin.darta.create', compact('page_title', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = 'Admin|Darta';

        $page_description = "Edit Darta #{$id}";

        $darta = $this->darta->find($id);

        $dartaFile = $this->files->where('type', 'darta')->where('parent_id', $id)->get();

        $users = \App\User::select('*', \DB::raw("CONCAT(first_name,' ',last_name,'[',id,']') as name"))->pluck('name', 'id');

        return view('admin.darta.edit', compact('page_title', 'users', 'darta', 'dartaFile', 'page_description'));
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

        $darta = $this->darta->find($id);

        if (! $darta->isEditable()) {
            abort(404);
        }

        $files = $request->file('attachment');

        $destinationPath = public_path('/darta/');

        if (! \File::isDirectory($destinationPath)) {
            \File::makeDirectory($destinationPath, 0777, true, true);
        }

        foreach ($files as $key=>$doc_) {
            if ($doc_) {
                $doc_name = time().''.$doc_->getClientOriginalName();
                $doc_->move($destinationPath, $doc_name);
                $attachment = ['type'=>'darta', 'attachment'=>$doc_name, 'parent_id'=>$darta->id];
                $this->files->create($attachment);
            }
        }

        $darta = $darta->update($attributes);

        Flash::success('Darta SuccessFully Updated');

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
        $darta = $this->darta->find($id);

        if (! $darta->isEditable()) {
            abort(404);
        }

        $darta->delete();

        $this->files->where('parent_id', $id)->delete();

        Flash::success('Darta Successfully Deleted');

        return redirect()->back();
    }

    public function destroyFile($id)
    {
        $files = $this->files->find($id);

        if ($files->isEditable()) {
            \File::delete(public_path('darta/'.$files->attachment));
            $files->delete();
        } else {
            abort(404);
        }

        return ['success'=>true];
    }

    public function getModalDelete($id)
    {
        $error = null;

        $darta = $this->darta->find($id);
        $modal_title = 'Delete Darta';
        $modal_body = "Are you sure you want to detele darta with number {$darta->darta_num}";
        $modal_route = route('admin.darta.delete', $id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
