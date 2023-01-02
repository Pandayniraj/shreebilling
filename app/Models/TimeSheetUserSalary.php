<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSheetUserSalary extends Model
{
    protected $table = 'timesheet_user_salary';

    protected $fillable = ['user_id', 'salary_template_id', 'assigned_by'];

    public function assignedBy()
    {
        return $this->belongsTo(\App\User::class, 'assigned_by', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function template()
    {
        return $this->belongsTo(\App\Models\TimeSheetSalary::class, 'salary_template_id', 'id');
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
