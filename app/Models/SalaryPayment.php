<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    /**
     * @var array
     */
    protected $table = 'payroll_salary_payment';

    /**
     * @var array
     */
    protected $fillable = ['salary_grade', 'user_id', 'payment_month', 'gross_salary', 'total_allowance', 'total_deduction', 'fine_deduction', 'payment_method', 'comments', 'paid_date', 'salary_template_id', 'overtime', 'gratuity_salary'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function template()
    {
        return $this->belongsTo(\App\Models\SalaryTemplate::class, 'salary_template_id', 'salary_template_id');
    }

    public function allowance()
    {
        return $this->hasMany(\App\Models\SalaryPaymentAllowance::class, 'salary_payment_id', 'salary_payment_id');
    }

    public function deduction()
    {
        return $this->hasMany(\App\Models\SalaryPaymentDeduction::class, 'salary_payment_id', 'salary_payment_id');
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Prevent user from deleting his own account.
        if (Auth::check() && (Auth::user()->id == $this->user_id || Auth::user()->id == $this->assigned_by || Auth::user()->id == 1)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Prevent user from deleting his own account.
        if (Auth::check() && (Auth::user()->id == $this->user_id || Auth::user()->id == $this->assigned_by || Auth::user()->id == 1)) {
            return true;
        }

        return false;
    }

    public function hasPerm(Permission $perm)
    {
        // perm 'basic-authenticated' is always checked.
        if ('basic-authenticated' == $perm->name) {
            return true;
        }
        // Return true if the Intake has is assigned the given permission.
        if ($this->perms()->where('id', $perm->id)->first()) {
            return true;
        }
        // Otherwise
        return false;
    }
}
