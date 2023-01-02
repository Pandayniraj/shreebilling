<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Entrytype;
use Flash;
use Illuminate\Http\Request;

class EntryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Entrytype $entrytypes)
    {
        $this->entrytypes = $entrytypes;
    }

    public function index()
    {
        $page_title = 'Admin | EntryTypes | Index';
        $entrytypes = $this->entrytypes->where('org_id', \Auth::user()->org_id)->paginate(30);

        return view('admin.entrytypes.index', compact('entrytypes', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin | EntryTypes | Create';

        return view('admin.entrytypes.create', compact('page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
        'label'      => 'required',
        'name'     => 'required',
        ]);

        $check = $this->entrytypes->where('label', $request->label)->where('org_id', \Auth::user()->org_id)->exists();
        if ($check) {
            return \Redirect::back()->withErrors(['error'=>'Label Already Taken']);
        }
        $attributes = $request->all();
        $attributes['org_id'] = \Auth::user()->org_id;
        $this->entrytypes->create($attributes);
        Flash::success('Entry Types Added !!');

        return redirect()->route('admin.entrytype.index');
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
        $page_title = 'Admin | EntryTypes | Edit';
        $entrytype = $this->entrytypes->find($id);

        return view('admin.entrytypes.edit', compact('entrytype', 'page_title'));
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
        'label'      => 'required',
        'name'     => 'required',
        ]);
        $check = $this->entrytypes->where('label', $request->label)->where('org_id', \Auth::user()->org_id)->where('id', '!=', $id)->exists();
        if ($check) {
            return \Redirect::back()->withErrors(['error'=>'Label Already Taken']);
        }
        $entrytype = $this->entrytypes->find($id);
        if (! $entrytype->isEditable()) {
            abort(404);
        }
        $attributes = $request->all();
        $entrytype->update($attributes);
        Flash::success('Entry Type Successfully Updated');

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
        $types = $this->entrytypes->find($id);
        //dd($tags);

        if (! $types->isdeletable()) {
            abort(403);
        }

        $this->entrytypes->find($id)->delete();

        Flash::success("Entry Types Successfully Deleted'"); // 'LeadStatus successfully deleted');

        return redirect()->back();
    }

    public function getModalDelete($id)
    {
        $error = null;

        $types = $this->entrytypes->find($id);

        //dd($tags);

        if (! $types->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Entry Types ?';

        $types = $this->entrytypes->find($id);

        $modal_route = route('admin.entrytype.delete', ['id' => $types->id]);

        $modal_body = trans('Are You Sure', ['id' => $types->id, 'name' => $types->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
