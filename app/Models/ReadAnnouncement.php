<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class ReadAnnouncement extends Model
{
    /**
     * @var array
     */
    protected $table = 'read_announcement';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'announcement_id', 'read_announce'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Prevent user from deleting his own account.
        if (Auth::check() && (Auth::user()->id == $this->user_id)) {
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
        if (Auth::check() && (Auth::user()->id == $this->user_id)) {
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
