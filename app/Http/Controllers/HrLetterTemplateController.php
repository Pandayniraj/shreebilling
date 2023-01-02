<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\HrLetterTemplate;
use Flash;
use Illuminate\Http\Request;

class HrLetterTemplateController extends Controller
{
    public function __construct(HrLetterTemplate $hrlettertemplate)
    {
        $this->hrlettertemplate = $hrlettertemplate;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title = 'Admin | hrlettertemplate |Index';
        $page_description = 'HrLetter Templates';
        $hrlettertemplate = $this->hrlettertemplate->where('org_id', \Auth::user()->org_id)->orderBy('id', 'desc')->paginate(30);

        return view('admin.hrlettertemplate.index', compact('hrlettertemplate', 'page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title = 'Admin | HrLetter Templates |Create';
        $page_description = 'Create a HrLetter Templates';

        return view('admin.hrlettertemplate.create', compact('page_title', 'page_description'));
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
        Flash::success('Template SucessFully Created');
        $this->hrlettertemplate->create($attributes);

        return redirect('/admin/hrlettertemplate/');
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
        $page_title = 'Admin | HrLetter Templates | Edit';
        $page_description = 'Edit a Template';
        $hrlettertemplate = $this->hrlettertemplate->find($id);

        return view('admin.hrlettertemplate.edit', compact('hrlettertemplate', 'page_title', 'page_description'));
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
        $hrlettertemplate = $this->hrlettertemplate->find($id);
        $attributes = $request->all();
        if (! $hrlettertemplate->isEditable()) {
            abort(404);
        }
        $hrlettertemplate->update($attributes);
        Flash::success('Template Updated');

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
        $hrlettertemplate = $this->hrlettertemplate->find($id);
        if (! $hrlettertemplate->isDeletable()) {
            abort(404);
        }
        $hrlettertemplate->delete();
        Flash::success('hrlettertemplate deleted');

        return redirect('/admin/hrlettertemplate/');
    }

    public function getModalDelete($id)
    {
        $error = null;

        $hrlettertemplate = $this->hrlettertemplate->find($id);
        $modal_title = 'Delete Template';
        $modal_body = 'Are you sure you want to delte hrlettertemplate with name '.$hrlettertemplate->name.' and Id'.$id;
        $modal_route = route('admin.hrlettertemplate.delete', ['id' => $id]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
