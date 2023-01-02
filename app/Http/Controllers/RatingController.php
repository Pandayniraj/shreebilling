<?php

namespace App\Http\Controllers;

use Flash;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Admin | Rating';

        $page_description = 'List of Rating';

        $ratings = \App\Models\Rating::orderBy('id', 'desc')->get();

        return view('admin.ratings.index', compact('ratings', 'page_title', 'page_description'));
    }

    public function create()
    {
        $page_title = 'Create | Rating';
        $page_description = '';

        return view('admin.ratings.create', compact('page_title', 'page_description'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required',

        ]);

        $attributes = $request->all();
        if (! isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }
        //dd($attributes);

        $rating = \App\Models\Rating::create($attributes);
        Flash::success('Rating  sucessfully added');

        return redirect('/admin/rating');
    }

    public function edit($id)
    {
        $rating = \App\Models\Rating::find($id);

        $page_title = 'Edit Rating Setup';
        $page_description = '';

        return view('admin.ratings.edit', compact('rating', 'page_title', 'page_description'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'      => 'required',

        ]);

        $attributes = $request->all();

        if (! isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        \App\Models\Rating::find($id)->update($attributes);

        Flash::success('Rating sucessfully updated');

        return redirect('/admin/rating');
    }

    public function getModalDelete($id)
    {
        $error = null;
        $rating = \App\Models\Rating::find($id);

        $modal_title = 'Delete Rating';
        $modal_body = 'Are you sure that you want to delete Rating id '.$rating->id.' with the number '.$rating->name.'? This operation is irreversible';

        $modal_route = route('admin.rating.delete', $rating->id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroy($id)
    {
        $rating = \App\Models\Rating::find($id)->delete();
        Flash::success('Rating  sucessfully deleted');

        return redirect('/admin/rating');
    }
}
