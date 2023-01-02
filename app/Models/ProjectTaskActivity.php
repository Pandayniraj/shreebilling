<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTaskActivity extends Model
{
    protected $table = 'project_task_activities';

    /**
     * @var array
     */
    protected $fillable = ['task_id', 'activity', 'user_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function task()
    {
        return $this->belongsTo(\App\Models\ProjectTask::class, 'user_id');
    }
}
