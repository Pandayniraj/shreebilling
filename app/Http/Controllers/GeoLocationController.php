<?php

namespace App\Http\Controllers;

use App\Models\GeolocationHistory;
use App\Models\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GeoLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Admin | Geolocations';
        $users = \App\User::where('enabled', '1')->get();

        return view('admin.geolocations.index', compact('users', 'page_title'));
    }

    public function filter(Request $request)
    {
        $page_title = 'Admin | Geolocations';
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user_id = $request->user_id;
        $users = \App\User::where('enabled', '1')->get();
        $user = \App\User::find($user_id);
        $locations = GeolocationHistory::where('user_id', $user_id)
                    ->where('tracked_date', '>=', $start_date)
                    ->where('tracked_date', '<=', $end_date)
                    ->orderBy('id', 'desc')
                    ->get();

        return view('admin.geolocations.index', compact('locations', 'user', 'start_date', 'users', 'page_title', 'end_date'));
    }

    public function monitorlocation(Request $request)
    {
        $page_title = 'Admin | Geolocations';
        $locdata = UserLocation::all();
        $userloc = [];
        foreach ($locdata as $users) {
            if (Cache::has('user-is-online-'.$users->user_id)) {
                $data['isonline'] = true;
            } else {
                $data['isonline'] = false;
            }
            $data['id'] = $users->id;
            $data['lat'] = $users->latitude;
            $data['lng'] = $users->longitude;
            $data['name'] = ucfirst($users->user->username);
            $time = date('dS M y h:i', strtotime($users->updated_at));
            $data['info'] = "<strong>{$users->street_name}</strong><br>{$users->formatted_address}<br>Last active: {$time}<br><a href='https://www.google.com/maps/search/?api=1&query={$users->latitude},{$users->longitude}' target='_blank'>Get Directions</a>";
            array_push($userloc, $data);
        }
        if ($request->ajax()) {
            return response()->json($userloc);
        }

        return view('admin.geolocations.monitor', compact('userloc', 'page_title'));
    }
}
