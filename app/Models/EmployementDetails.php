<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
class EmployementDetails extends Model
{
    use HasFactory;

    protected $table = 'employee_details';

    protected $fillable = ["org_id",'user_id',"departments_id","designations_id","job_title",
    	"employment_type","work_station","change_type","first_line_manager","scope_of_work","start_date","end_date","responsibility","project_id",'is_current'
    ];


    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designations_id', 'designations_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'departments_id', 'departments_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(\App\Models\Projects::class, 'project_id', 'id');
    }

      public function firstLineManger(){

        return $this->belongsTo(\App\User::class, 'first_line_manager');

    }

       public function isEditable()
    {
        // Protect the root user from deletion.
        if ('root' == $this->username) {
            return false;
        }
        // Prevent user from deleting his own account.
        if (Auth::check() && (Auth::user()->id == $this->id)) {
            return false;
        }
        // Otherwise
        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the root user from deletion.
        if ('root' == $this->username) {
            return false;
        }
        // Prevent user from deleting his own account.
        if (Auth::check() && (Auth::user()->id == $this->id)) {
            return false;
        }
        // Otherwise
        return true;
    }


}
