<?php

namespace App\Http\Controllers;

use App\Models\Contacttag;
use Flash;
use Illuminate\Http\Request;

class ContactTagCOntroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Contacttag $contact_tag)
    {
        $this->contact_tag = $contact_tag;
    }

    public function index()
    {
        $page_title = 'Admin|Contact Tag';
        $contact_tags = $this->contact_tag->paginate(30);
                
        return view('admin.contact_tag.index', compact('page_title', 'contact_tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin|Contact Tag';

        return view('admin.contact_tag.create', compact('page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->contact_tag->create($request->all());

        Flash::success('contact tags Successfully created');

        return redirect('/admin/contact_tag');
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
        $contact_tag = $this->contact_tag->find($id);
        $page_title = 'Admin|contact tag';

        return view('admin.contact_tag.edit', compact('page_title', 'contact_tag'));
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
        $contact_tag = $this->contact_tag->find($id);
        if (! $contact_tag->isEditable()) {
            abort(404);
        }
        $attributes = $request->all();
        $contact_tag->update($attributes);
        Flash::success('contact_tag Successfully Updated');

        return redirect('/admin/contact_tag');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact_tag = $this->contact_tag->find($id);
        if (! $contact_tag->isDeletable()) {
            abort(404);
        }
        $contact_tag->delete();
        Flash::success('contact tag Successfully Deleted');

        return redirect()->back();
    }

    public function getModalDelete($id)
    {
        $contact_tag = $this->contact_tag->find($id);
        $modal_title = 'Delete Contact Tag';
        $modal_body = 'Are you sure want to delete contact tag with id: '.$id;
        $modal_route = route('admin.contact_tag.delete',$id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

}
