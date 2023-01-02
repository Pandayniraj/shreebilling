<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSubCat extends Model
{
    /**
     * @var array
     */

	protected $table = 'project_task_sub_cat';
	
	/**
     * @var array
     */
    protected $fillable = ['name','org_id','task_cat_id','enabled'];
	

    public function tasksubcat()
    {
        return $this->belongsTo('App\Models\Taskscat','task_cat_id');
    }


	/**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Intakes from editing changes
        if ( ('admins' == $this->name) || ('users' == $this->name) ) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the admins and users Intakes from deletion
        if ( ('admins' == $this->name) || ('users' == $this->name) ) {
            return false;
        }

        return true;
    }
	
	
	public function hasPerm(Permission $perm)
    {
        // perm 'basic-authenticated' is always checked.
        if ('basic-authenticated' == $perm->name) {
            return true;
        }
        // Return true if the Intake has is assigned the given permission.
        if ( $this->perms()->where('id' , $perm->id)->first() ) {
            return true;
        }
        // Otherwise
        return false;
    }
	



}