<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    protected $fillable = ['user_id', 'latitude', 'longitude', 'ip_address', 'street_name', 'formatted_address'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
}
