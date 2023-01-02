<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biom_log extends Model
{
    //
    protected $guarded = ['id'];
    protected $fillable = ['machine_attendence_id', 'datetime', 'verified', 'status', 'machine_userid', 'device_id', 'verification_mode', 'created_at', 'updated_at'];
    public $timestamps = true;

    public function scopedeviceLogs($query, $id)
    {
        return $query->where('device_id', $id);
    }

    public function employess()
    {
        return $this->hasMany('App\Employee', 'pin', 'emp_id');
    }
}
