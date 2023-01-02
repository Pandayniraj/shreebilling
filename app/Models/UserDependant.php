<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class UserDependant extends Model
{
    /**
     * @var array
     */
    protected $table = 'user_dependents';

    protected $fillable = ['user_detail_id', 'name', 'relationship', 'dob'];

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Leads from editing changes
        if (\Auth::user()->id != 1 && \Auth::user()->id != 5 && \Auth::user()->id != 4) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        if (! \Auth::user()->hasRole('admins')) {
            return false;
        }

        return true;
    }

    public function userdetail()
    {
        return $this->belongsTo(\App\Models\UserDetail::class);
    }
}
