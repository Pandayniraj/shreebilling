<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollEnterPay extends Model
{
    protected $table = 'payroll_enter_pay';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'pay_frequency_id', 'basic_salary', 'regular_salary', 'overtime_salary', 'sick_salary', 'weekend_salary', 'public_holiday_work_salary', 'annual_leave_salary', 'public_holiday_salary', 'other_leave_salary', 'net_salary', 'tax_amount', 'tax_percent', 'gross_salary', 'issued_by', 'remarks', 'total_allowance', 'total_allowance_json', 'total_deduction', 'total_deduction_json', 'gratuity_salary'];

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
