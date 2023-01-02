<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class AppraisalPerformanceData extends Model
{
    /**
     * @var array
     */
    protected $table = 'appraisal_performance_data';

    /**
     * @var array
     */
    protected $guarded = ['id'];
    

    public function performance()
    {
        return $this->belongsTo(\App\Models\AppraisalPerformance::class, 'appraisal_performance_id');
    }
    /**
     * @return bool
     */
    public function isEditable()
    {
        // Prevent user from deleting his own account.
        if (Auth::check() && Auth::user()->hasRole('admins')) {
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
        if (Auth::check() && Auth::user()->hasRole('admins')) {
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
