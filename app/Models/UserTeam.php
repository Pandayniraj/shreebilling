<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTeam extends Model
{
    /**
     * @var array
     */
    protected $table = 'user_teams';

    /**
     * @var array
     */
    protected $fillable = ['team_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function isDeletable()
    {
        // Protect the admins and users Intakes from deletion
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }
}
