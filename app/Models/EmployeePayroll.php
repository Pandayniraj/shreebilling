<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class EmployeePayroll extends Model
{
    /**
     * @var array
     */
    protected $table = 'payroll_manage_employee_payroll';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'salary_template_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function template()
    {
        return $this->belongsTo(\App\Models\SalaryTemplate::class, 'salary_template_id', 'salary_template_id');
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        if (Auth::check() && \Auth::user()->hasRole('admins')) {
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
        if (Auth::check() && \Auth::user()->hasRole('admins')) {
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
