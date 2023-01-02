<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    /**
     * @var array
     */
    protected $table = 'shifts';

    /**
     * @var array
     */
    protected $fillable = ['shift_name', 'shift_time', 'end_time', 'shift_margin_start', 'shift_margin_end', 'enabled', 'color', 'is_night'];

    public function breaks()
    {
        return $this->hasMany(\App\Models\ShiftBreak::class, 'shift_id', 'id');
    }
}
