<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['event_type', 'event_name', 'venue_id', 'event_start_date', 'event_end_date', 'amount_paid', 'potential_cost', 'calculated_cost', 'edited_cost', 'extra_cost', 'event_status', 'num_participants', 'user_id', 'menu_items', 'other_details', 'comments'];
}
