<?php

namespace App\Models;

use App\Models\ProjectTask;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MasterComments extends Model
{
    /**
     * @var array
     */
    protected $table = 'master_comments';

    protected $fillable = ['type', 'master_id', 'user_id', 'comment_text', 'file'];

    public function task()
    {
        return $this->belongsTo(\App\Models\ProjectTask::class, 'master_id');
    }

    public function bug()
    {
        return $this->belongsTo(\App\Models\Bugs::class, 'master_id');
    }

    public function kb()
    {
        return $this->belongsTo(\App\Models\Knowledge::class, 'master_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Communication from editing changes
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the admins and users Communication from deletion
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }
}
