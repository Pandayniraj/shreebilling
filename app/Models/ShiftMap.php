<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class ShiftMap extends Model
{
    /**
     * @var array
     */
    protected $table = 'shift_maps';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'shift_id', 'map_from_date', 'map_to_date', 'departments'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function shift()
    {
        return $this->belongsTo(\App\Models\Shift::class, 'shift_id');
    }
}
