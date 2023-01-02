<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventSpace;
use App\Models\EventVenues;
use App\User;
use Flash;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function index()
    {
        $page_title = 'Admin | Events';
        $page_description = 'Lists of Events';
        $events = Event::select('events.id as eid', 'events.event_type', 'event_venues.venue_name', 'events.event_name', 'events.event_start_date', 'events.event_status', 'events.num_participants', 'events.event_start_date', 'users.username')->leftjoin('users', 'users.id', '=', 'events.user_id')->leftjoin('event_venues', 'event_venues.id', '=', 'events.venue_id')->orderBy('events.event_start_date', 'desc')->paginate(30);

        return view('admin.events.index', compact('events', 'page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin | Events | Create';
        $page_description = 'Events create page';
        $venue = EventVenues::all();
        $events_type = ['concert', 'dinner', 'lunch', 'hightea', 'cocktail', 'picnic', 'party', 'seminar', 'conference', 'workshop', 'galas', 'csr', 'expo', 'other'];
        $event_status = ['registered', 'paid', 'cancelled', 'postpone'];
        $users = User::all();

        return view('admin.events.createevent', compact('events_type', 'venue', 'event_status', 'users', 'page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event = $request->all();
        unset($event['_token']);
        Event::insert($event);
        Flash::success('Event Added');

        return redirect('/admin/events');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = Event::find($id);
        $page_title = 'Admin | Events';
        $page_description = 'Edit event #' . $id;
        $venue = EventVenues::all();
        $events_type = ['concert', 'dinner', 'lunch', 'hightea', 'cocktail', 'picnic', 'party', 'seminar', 'conference', 'workshop', 'galas'];
        $event_status = ['registered', 'paid', 'cancelled', 'postpone'];
        $users = User::all();

        return view('admin.events.editevent', compact('events_type', 'venue', 'event_status', 'users', 'edit', 'page_title', 'page_description'));
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
        $event = $request->all();
        unset($event['_token']);
        Event::find($id)->update($event);
        Flash::success('Event Updated !!');

        return redirect('/admin/events');
    }

    public function getModalDelete($id)
    {
        $error = null;
        $event = Event::find($id);

        // if (!$event->isdeletable())
        // {
        //     abort(403);
        // }

        $modal_title = 'Delete Event';
        $modal_body = 'Are you sure that you want to delete event ID ' . $event->id . ' with the name ' . $event->event_name . '? This operation is irreversible';
        // $lead = $this->lead->find($id);
        // $type = \Request::get('type');
        $modal_route = route('delete-event', $event->id);

        // $modal_body = trans('admin/leads/dialog.delete-confirm.body', ['id' => $lead->id, 'name' => $lead->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Event::find($id)->delete();
        Flash::success('Event Deleted !!');

        return redirect('admin/events');
    }

    public function showVenue()
    {
        $page_title = 'Admin | Venue | Index';
        $page_description = 'List of Venue';
        $venue = EventVenues::select('event_venues.id', 'event_venues.venue_name', 'event_venues.venue_facilities', 'event_venues.other_details', 'users.username')->leftjoin('users', 'event_venues.user_id', '=', 'users.id')->orderBy('id', 'desc')->paginate(30);

        return view('admin.events.event-venue', compact('venue', 'page_title', 'page_description'));
    }

    public function createVenues()
    {
        $page_title = 'Admin | Events';
        $page_description = 'create event venue';
        $users = User::all();

        return view('admin.events.add-venue', compact('users', 'page_title', 'page_description'));
    }

    public function storeVenues(Request $request)
    {
        $venue = $request->all();
        unset($venue['_token']);
        EventVenues::insert($venue);
        Flash::success('Venue Added');

        return redirect('admin/event-venues');
    }

    public function editVenues($id)
    {
        $edit = EventVenues::find($id);
        $page_title = 'Admin | Events';
        $page_description = 'Edit event venue #' . $id;
        $users = User::all();

        return view('admin.events.edit-venue', compact('edit', 'users', 'page_title', 'page_description'));
    }

    public function updateVenues(Request $request, $id)
    {
        $venue = $request->all();
        unset($event['_token']);
        EventVenues::find($id)->update($venue);
        Flash::success('Venue Updated !!');

        return redirect('/admin/event-venues');
    }

    public function getVenueDelete($id)
    {
        $error = null;
        $venue = EventVenues::find($id);

        // if (!$event->isdeletable())
        // {
        //     abort(403);
        // }

        $modal_title = 'Delete Venue';
        $modal_body = 'Are you sure that you want to delete venue ID ' . $venue->id . ' with the name ' . $venue->venue_name . '? This operation is irreversible';
        $modal_route = route('delete-venue', $venue->id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroyVenue($id)
    {
        $eventvenue =  EventVenues::find($id);
        $eventvenue->delete();
        Flash::success('Venue Deleted  SucessFully!!');

        return redirect('/admin/event-venues');
    }

    public function showSpace()
    {
        $page_title = 'Admin | Event | Space';
        $page_description = 'List of Event Space';
        $spaces = EventSpace::select('events.event_name', 'event_space.id', 'event_space.room_name', 'event_space.room_capability', 'event_space.daily_rate', 'event_space.occupied_date_from', 'event_space.occupied_date_to', 'event_space.booking_status', 'event_space.other_details', 'users.username')->leftjoin('users', 'users.id', '=', 'event_space.user_id')->leftjoin('events', 'events.id', '=', 'event_space.event_id')->orderBy('event_space.id', 'desc')->paginate(30);

        return view('admin.events.event-space', compact('spaces', 'page_title', 'page_description'));
    }

    public function createSpace()
    {
        $page_title = 'Admin | Event';
        $page_description = 'create new event space';
        $event = Event::all();
        $users = User::all();
        $bstatus = ['confirmed', 'provisional'];

        return view('admin.events.add-space', compact('event', 'bstatus', 'users', 'page_title', 'page_description'));
    }

    public function storeSpace(Request $request)
    {
        $space = $request->all();
        unset($space['_token']);
        EventSpace::create($space);
        Flash::success('Event Space added SucessFully!!');

        return redirect('/admin/event-space');
    }

    public function editSpace($id)
    {
        $edit = EventSpace::find($id);
        $page_title = 'Admin | Event';
        $page_description = 'Edit event space #' . $id;
        $event = Event::all();
        $users = User::all();
        $bstatus = ['confirmed', 'provisional'];

        return view('admin.events.edit-space', compact('edit', 'event', 'users', 'bstatus', 'page_description', 'page_title'));
    }

    public function updateSpace(Request $request, $id)
    {
        $venue = $request->all();
        unset($event['_token']);
        EventSpace::find($id)->update($venue);
        Flash::success('Space Updated !!');

        return redirect('/admin/event-venues');
    }

    public function getspaceDelete($id)
    {
        $error = null;

        //dd($id);
        $space = EventSpace::find($id);

        // dd($space);

        // if (!$event->isdeletable())
        // {
        //     abort(403);
        // }  

        $modal_title = 'Delete Venue';
        $modal_body = 'Are you sure that you want to delete venue ID ' . $space->id . ' with the room name ' . $space->room_name . '? This operation is irreversible';
        $modal_route = route('delete-space', $space->id);

        // dd($modal_route);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroySpace($id)
    {
        EventSpace::find($id)->delete();
        Flash::success('Venue Deleted  SucessFully!!');

        return redirect('/admin/event-space');
    }
}
