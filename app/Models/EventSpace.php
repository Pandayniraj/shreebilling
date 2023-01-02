<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSpace extends Model
{
    //
    protected $table = 'event_space';
    protected $fillable = ['event_id', 'room_name', 'room_capability', 'occupied_date_from', 'occupied_date_to', 'booking_status', 'daily_rate', 'user_id', 'other_details'];
}
