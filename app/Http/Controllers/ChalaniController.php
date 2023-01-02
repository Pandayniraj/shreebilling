<?php

namespace App\Http\Controllers;

use App\Models\Chalani;
use App\Models\DartaChalaniFile;
use Flash;
use Illuminate\Http\Request;

class ChalaniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Chalani $chalani, DartaChalaniFile $files)
    {
        $this->chalani = $chalani;

        $this->files = $files;
    }

    public function index()
    {
        $chalani = $this->chalani->where(function ($query) {
            $terms = \Request::get('term');
            if ($terms) {
                return $query->where('letter_num', $terms)
                                    ->orWhere('id', $terms)
                                    ->orWhere('subject', 'LIKE', '%'.$terms.'%');
            }
        })->orderBy('id', 'desc')->paginate(30);
        $page_title = 'Admin|Chalani';

        return view('admin.chalani.index', compact('page_title', 'chalani'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin|Chalani';
        $page_description = 'Create new Chalani';
        $tickets = \App\Models\Ticket::select('*', \DB::raw("CONCAT('TKT-',ticket_number) as tkt_number"))->pluck('name as tkt_number', 'id');

        return view('admin.chalani.create', compact('page_title', 'tickets', 'page_description'));
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

        $chalani = $this->chalani->create($attributes);

        $files = $request->file('attachment');

        $destinationPath = public_path('/chalani/');

        if (! \File::isDirectory($destinationPath)) {
            \File::makeDirectory($destinationPath, 0777, true, true);
        }

        foreach ($files as $key=>$doc_) {
            if ($doc_) {
                $doc_name = time().''.$doc_->getClientOriginalName();
                $doc_->move($destinationPath, $doc_name);
                $attachment = ['type'=>'chalani', 'attachment'=>$doc_name, 'parent_id'=>$chalani->id];
                $this->files->create($attachment);
            }
        }

        Flash::success('Chalani SuccessFully Created');

        return redirect('/admin/chalani');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_title = 'Admin|Chalani';
        $page_description = 'Edit Chalani';
        $tickets = \App\Models\Ticket::select('*', \DB::raw("CONCAT('TKT-',ticket_number) as tkt_number"))->pluck('name as tkt_number', 'id');

        return view('admin.chalani.create', compact('page_title', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = 'Admin|Chalani';

        $page_description = "Edit Chalani #{$id}";

        $chalani = $this->chalani->find($id);

        $chalaniFile = $this->files->where('type', 'chalani')->where('parent_id', $id)->get();

        $tickets = \App\Models\Ticket::select('*', \DB::raw("CONCAT('TKT-',ticket_number) as tkt_number"))->pluck('name as tkt_number', 'id');

        return view('admin.chalani.edit', compact('page_title', 'tickets', 'chalani', 'chalaniFile', 'page_description'));
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

        $chalani = $this->chalani->find($id);

        if (! $chalani->isEditable()) {
            abort(404);
        }

        $files = $request->file('attachment');

        $destinationPath = public_path('/chalani/');

        if (! \File::isDirectory($destinationPath)) {
            \File::makeDirectory($destinationPath, 0777, true, true);
        }

        foreach ($files as $key=>$doc_) {
            if ($doc_) {
                $doc_name = time().''.$doc_->getClientOriginalName();
                $doc_->move($destinationPath, $doc_name);
                $attachment = ['type'=>'chalani', 'attachment'=>$doc_name, 'parent_id'=>$chalani->id];
                $this->files->create($attachment);
            }
        }

        $chalani = $chalani->update($attributes);

        Flash::success('Chalani SuccessFully Updated');

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
        $chalani = $this->chalani->find($id);

        if (! $chalani->isEditable()) {
            abort(404);
        }

        $chalani->delete();

        $this->files->where('parent_id', $id)->delete();

        Flash::success('Chalani Successfully Deleted');

        return redirect()->back();
    }

    public function destroyFile($id)
    {
        $files = $this->files->find($id);

        if ($files->isEditable()) {
            \File::delete(public_path('chalani/'.$files->attachment));
            $files->delete();
        } else {
            abort(404);
        }

        return ['success'=>true];
    }

    public function getModalDelete($id)
    {
        $error = null;

        $chalani = $this->chalani->find($id);
        $modal_title = 'Delete Chalani';
        $modal_body = "Are you sure you want to detele chalani with Letter  number {$chalani->letter_num}";
        $modal_route = route('admin.chalani.delete', ['id' => $id]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
