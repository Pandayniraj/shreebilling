<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\StickyNote;
use Illuminate\Http\Request;

class StickyNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes = StickyNote::where('user_id', \Auth::user()->id)
                ->where(function ($query) {
                    $term = \Request::get('term');
                    if ($term && $term != '') {
                        return $query->where('title', 'LIKE', '%'.$term.'%')
                                    ->orWhere('description', 'LIKE', '%'.$term.'%');
                    }
                })
                ->orderBy('id', 'desc')
                ->paginate(23);
        $page_title = 'Admin|Notes';
        $page_description = 'Add & view Notes';

        return view('admin.stickynote.index', compact('notes', 'page_description', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $notes = StickyNote::find($request->id);
        if ($notes) {
            $type = $request->type;
            switch ($type) {
                case 'description':
                    $update['description'] = $request->data;
                    break;
                case 'color':
                     $update['color'] = $request->data;
                     break;
                case 'title':
                     $update['title'] = $request->data;
                     break;
                default:
                    // code...
                    break;

            }
            if ($update) {
                $notes->update($update);
            }
        } else {
            $attributes['user_id'] = \Auth::user()->id;
            $attributes['org_id'] = \Auth::user()->org_id;
            $note = StickyNote::create($attributes);

            return ['status'=>1, 'note'=>$note];
        }
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = StickyNote::find($id);

        if ($note->user_id == \Auth::user()->id) {
            $note->delete();

            return ['status'=>true];
        } else {
            abort(404);
        }
    }
}
