<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    protected $table = 'task_users';

    protected $fillable = ['task_id', 'user_id'];

    public function task()
    {
        return $this->belongsTo(\App\Models\Task::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
