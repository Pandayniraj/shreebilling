<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class PerformanceIndicator extends Model
{
    /**
     * @var array
     */
    protected $table = 'performance_indicator';

    /**
     * @var array
     */
    protected $fillable = ['designations_id', 'customer_experiece_management', 'marketing', 'management', 'administration', 'presentation_skill', 'quality_of_work', 'efficiency', 'integrity', 'professionalism', 'team_work', 'critical_thinking', 'conflict_management', 'attendance', 'ability_to_meed_deadline'];

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
