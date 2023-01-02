<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    /**
     * @var array
     */
    protected $table = 'project_tasks';

    protected $fillable = ['project_id', 'user_id', 'category_id', 'subject', 'description', 'percent_complete', 'actual_duration', 'schedule', 'timespan', 'duration', 'estimated_effort', 'milestone', 'order', 'precede_tasks', 'priority', 'peoples', 'attachment', 'status', 'start_date', 'end_date', 'enabled', 'task_order', 'stage_id', 'color','org_id','sub_cat_id',
        'location','schedule_type'];

    public function project()
    {
        return $this->belongsTo(\App\Models\Projects::class, 'project_id');
    }

    public function tags()
    {
        return $this->belongsToMany(\App\User::class, 'project_task_user');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function stage()
    {
        return $this->belongsTo(\App\Models\TaskStage::class, 'stage_id');
    }

    public function category(){
            return $this->belongsTo(\App\Models\ProjectTaskCategory::class,'category_id');
         }
         public function tasksubcat(){
            return $this->belongsTo(\App\Models\TaskSubCat::class,'sub_cat_id');
         }

      public function projectTaskAttachent()
    {
        return $this->hasMany(\App\Models\ProjectTaskAttachment::class, 'task_id', 'id');
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Communication from editing changes

        if(\Auth::user()->hasRole('admins') || \Auth::user()->id == $this->user_id){


            return true;
        }

        return false;

        // // if (('admins' == $this->full_name) || ('users' == $this->full_name)) {
        // //     return false;
        // // }

        // return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the admins and users Communication from deletion
        if (('admins' == $this->full_name) || ('users' == $this->full_name)) {
            return false;
        }

        return true;
    }
}
