<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTaskStatus extends Model
{
    protected $table = 'project_task_status';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'project_id', 'enabled'];

    public function isEditable()
    {
        // Protect the admins and users Intakes from editing changes
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }
}
