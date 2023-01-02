<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Models\OnboardEvents;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(OnboardEvents $events)
    {
        $this->events = $events;
    }

    public function index()
    {
        $page_title = 'Onboard | Event';
        $events = $this->events->orderBy('id', 'desc')->paginate(30);

        return view('admin.onboarding.events.index', compact('events', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Onboard | Event';
        $page_description = 'Create new Events';

        return view('admin.onboarding.events.create', compact('page_title', 'page_description'));
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
        $this->events->create($attributes);
        Flash::success('Event successfully created');

        return redirect('/admin/onboard/events');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_title = 'Onboard | Event';
        $page_description = "Showing onboard Events #{$id}";
        $events = $this->events->find($id);

        return view('admin.onboarding.events.show', compact('events', 'page_description', 'page_title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = 'Onboard | Event';
        $page_description = "Edit onboard Events #{$id}";
        $events = $this->events->find($id);

        return view('admin.onboarding.events.edit', compact('events', 'page_title', 'page_description'));
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
        $attributes['user_id'] = Auth::user()->id;
        $events = $this->events->find($id);
        $events->update($attributes);
        Flash::success('Event successfully updated');

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
        $this->events->find($id)->delete();
        Flash::success('Event successfully deleted');

        return redirect()->back();
    }

    public function getModalDelete($id)
    {
        $error = null;

        $events = $this->events->find($id);

        if (! $events->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Onboard Event';
        $modal_route = route('admin.onboard.events.delete', $id);

        $modal_body = "Are you sure you want to delete tasktype with name {$events->name} and id {$id}";

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
