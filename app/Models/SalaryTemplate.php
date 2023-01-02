<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class SalaryTemplate extends Model
{
    /**
     * @var array
     */
    protected $table = 'payroll_salary_template';

    /**
     * @var array
     */
    protected $fillable = ['salary_grade', 'basic_salary', 'overtime_salary', 'sick_salary', 'annual_leave_salary', 'public_holiday_salary', 'other_leave_salary', 'gratuity_salary'];

    public function allowance()
    {
        return $this->hasMany(\App\Models\SalaryAllowance::class);
    }

    public function deduction()
    {
        return $this->hasMany(\App\Models\SalaryDeduction::class, 'salary_template_id');
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
