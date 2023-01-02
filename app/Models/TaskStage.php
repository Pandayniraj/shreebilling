<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class TaskStage extends Model
{
    /**
     * @var array
     */
    protected $table = 'task_stages';

    /**
     * @var array
     */
    protected $fillable = ['name', 'bg_color'];
}
