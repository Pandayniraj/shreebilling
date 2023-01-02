<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class TimeSheetSalary extends Model
{
    protected $table = 'timesheet_salary_templates';

    /**
     * @var array
     */
    protected $fillable = ['salary_grade', 'salary_per_hour', 'overtime_salary_per_hour', 'other_salary_per_hour', 'remarks', 'enabled', 'user_id','org_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

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
}
