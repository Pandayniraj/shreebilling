<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeKeeping extends Model
{
    protected $table = 'time_keeping';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'time_entry_method', 'pay_frequency', 'pay_type'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function payfrequecny()
    {
        return $this->belongsTo(\App\Models\PayFrequency::class, 'pay_frequency_id', 'id');
    }
}
