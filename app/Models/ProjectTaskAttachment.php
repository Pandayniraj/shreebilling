<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTaskAttachment extends Model
{
    protected $table = 'project_task_attachment';

    /**
     * @var array
     */
    protected $fillable = ['task_id', 'attachment', 'user_id'];
}
