<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventVenues extends Model
{
    protected $table = 'event_venues';
    protected $fillable = ['venue_name', 'venue_facilities', 'other_details', 'user_id'];
}
