<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTaskUser extends Model
{
    /**
     * @var array
     */
    protected $table = 'project_task_user';

    protected $fillable = ['project_task_id', 'user_id'];

    public function project()
    {
        return $this->belongsTo(\App\Models\ProjectTask::class, 'project_task_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
