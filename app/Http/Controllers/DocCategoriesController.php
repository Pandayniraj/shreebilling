<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\DocCategory;
use Flash;
use Illuminate\Http\Request;

class DocCategoriesController extends Controller
{
    public function __construct(DocCategory $doc_category)
    {
        $this->doc_category = $doc_category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title = 'Admin | DocCategory |Index';
        $page_description = 'DocCategory Index';
        $doc_categorys = $this->doc_category->where('org_id', \Auth::user()->org_id)->orderBy('id', 'desc')->paginate(30);

        return view('admin.doc_category.index', compact('doc_categorys', 'page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title = 'Admin | DocCategory |Create';
        $page_description = 'Create a DocCategory';

        return view('admin.doc_category.create', compact('page_title', 'page_description'));
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
        Flash::success('Categoris SucessFully Created');
        $this->doc_category->create($attributes);

        return redirect('/admin/doc_category/');
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
        $page_title = 'Admin | DocCategory | Edit';
        $page_description = 'Edit a DocCategory';
        $doc_category = $this->doc_category->find($id);

        return view('admin.doc_category.edit', compact('doc_category', 'page_title', 'page_description'));
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
        $doc_category = $this->doc_category->find($id);
        $attributes = $request->all();
        if (! $doc_category->isEditable()) {
            abort(404);
        }
        $doc_category->update($attributes);
        Flash::success('DocCategory Updated');

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
        $doc_category = $this->doc_category->find($id);
        if (! $doc_category->isDeletable()) {
            abort(404);
        }
        $doc_category->delete();
        Flash::success('Doc Categoris deleted');

        return redirect('/admin/doc_category/');
    }

    public function getModalDelete($id)
    {
        $error = null;

        $doc_category = $this->doc_category->find($id);
        $modal_title = 'Delete DocCategory';
        $modal_body = 'Are you sure you want to delte doc_category with name '.$doc_category->name.' and Id'.$id;
        $modal_route = route('admin.doc_category.delete', ['id' => $id]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
