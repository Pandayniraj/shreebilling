<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketResponseMessage extends Model
{
    protected $table = 'ticket_response_messages';

    protected $fillable = ['ticket_id', 'user_id', 'message'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
