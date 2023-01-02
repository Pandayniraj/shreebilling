<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftAttendance extends Model
{
    protected $table = 'shift_attendance';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'shift_id', 'date', 'time', 'attendance_status', 'location', 'ip_address', 'source', 'remarks'];

    public function clockinstart()
    {
        $attendance = $this->where('shift_id', $this->shift_id)
            ->where('user_id', $this->user_id)
            ->where('date', $this->date)
            ->orderBy('attendance_status', 'asc')
            ->first();

        return $attendance;
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function shift()
    {
        return $this->belongsTo(\App\Models\Shift::class, 'shift_id');
    }
}
