<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biom_employee extends Model
{
    // protected $table = 'employees';
    protected $fillable = ['machine_userid', 'name', 'device_id', 'password', 'group', 'privilege', 'card', 'pin2', 'tz1', 'tz2', 'tz3'];
    public $timestamps = true;

    public function scopeEmp($query, $pin)
    {
        return $query->where('pin', $pin);
    }

    public function logs()
    {
        return $this->hasMany('App\Log', 'emp_id', 'pin');
    }
}
