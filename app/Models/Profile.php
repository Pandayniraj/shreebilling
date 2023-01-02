<?php

namespace App\Models;

use Auth;
use Config;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'password', 'auth_type', 'enabled', 'imap_email', 'imap_password', 'image'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The accessor to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name'];

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the root user from deletion.
        if ('root' == $this->username) {
            return false;
        }
        // Prevent user from deleting his own account.
        if (Auth::check() && (Auth::user()->id == $this->id)) {
            return false;
        }
        // Otherwise
        return true;
    }

    /**
     * @return bool
     */
    public function canBeDisabled()
    {
        // Protect the root user from being disabled.
        if ('root' == $this->username) {
            return false;
        }
        // Prevent user from disabling his own account.
        if (Auth::check() && (Auth::user()->id == $this->id)) {
            return false;
        }
        // Otherwise
        return true;
    }
}
