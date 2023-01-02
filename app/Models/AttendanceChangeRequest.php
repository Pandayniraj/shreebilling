<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceChangeRequest extends Model
{
    protected $table = 'attendance_change_requests';

    protected $fillable = ['attendance_id', 'user_id', 'actual_time', 'requested_time', 'approved_by', 'reason', 'status'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(\App\User::class, 'approved_by');
    }
}
