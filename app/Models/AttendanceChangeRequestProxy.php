<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceChangeRequestProxy extends Model
{
    protected $table = 'attendance_change_requests_proxy';

    protected $fillable = ['id', 'user_id','shift_id', 'date', 'clock_time', 'attendance_status', 'status', 'is_forwarded', 'approved_by', 'reason','end_time','groups'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(\App\User::class, 'approved_by');
    }
}
