<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class DartaChalaniFile extends Model
{
    protected $table = 'darta_chalani_files';

    /**
     * @var array
     */
    protected $fillable = ['type', 'parent_id', 'attachment'];

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
}
