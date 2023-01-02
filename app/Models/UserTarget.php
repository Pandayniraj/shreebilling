<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class UserTarget extends Model
{
    /**
     * @var array
     */
    protected $table = 'user_target';

    protected $fillable = ['user_id', 'year'];

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
        // Protect the admins and users Leads from deletion
        /*if ( (\Auth::user()->id != $this->user_id  && \Auth::user()->id != 1 && \Auth::user()->id != 5 && \Auth::user()->id != 4 )) {
            return false;
        } */

        if (! \Auth::user()->hasRole('admins')) {
            return false;
        }

        return true;
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
