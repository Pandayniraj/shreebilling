<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imap extends Model
{
    protected $table = 'imap';

    protected $fillable = ['user_id', 'imap_email', 'imap_password'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
