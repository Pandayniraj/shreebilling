<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class AppraisalPerformance extends Model
{
    /**
     * @var array
     */
    protected $table = 'appraisal_performance';

    /**
     * @var array
     */
    protected $guarded = ['id'];


    public function entryBy()
    {
        return $this->belongsTo(\App\User::class, 'entry_by');
    }

    public function employee()
    {
        return $this->belongsTo(\App\User::class, 'employee_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(\App\User::class, 'evaluator_id');
    }

    public function template()
    {
        return $this->belongsTo(\App\Models\AppraisalTemplate::class, 'template_id');
    }

    public function appraisalData()
    {
        return $this->hasMany(\App\Models\AppraisalPerformanceData::class, 'appraisal_performance_id');
    }

    public function selfAppraisalData()
    {
        return $this->hasMany(\App\Models\AppraisalPerformanceData::class, 'appraisal_performance_id');
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Prevent user from deleting his own account.
        if (Auth::check() && Auth::user()->hasRole(['admins','hr-staff'])) {
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
        if (Auth::check() && Auth::user()->hasRole(['admins','hr-staff'])) {
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
