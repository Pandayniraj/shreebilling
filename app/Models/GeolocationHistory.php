<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeolocationHistory extends Model
{
    protected $table = 'geolocation_history';

    protected $fillable = ['latitude', 'longitude', 'ip_address', 'tracked_date', 'user_id', 'street_name', 'formatted_address'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
}
