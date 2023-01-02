<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollTimeCardReview extends Model
{
    protected $table = 'payroll_timecard_review';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'pay_frequency_id', 'regular_days', 'ot_hour', 'weekend', 'public_holiday_work', 'sick_leave', 'annual_leave', 'public_holidays', 'other_leave', 'time_entry_method', 'remarks', 'issued_by'];

    public function payfrequency()
    {
        return $this->belongsTo(\App\Models\PayFrequency::class, 'pay_frequency_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(\App\User::class, 'issued_by', 'id');
    }
}
