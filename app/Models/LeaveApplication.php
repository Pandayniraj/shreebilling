<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    /**
     * @var array
     */
    protected $table = 'tbl_leave_application';

    /**
     * @var array
     */
    protected $fillable = ['leave_application_id', 'user_id', 'leave_category_id', 'reason', 'leave_start_date', 'leave_end_date', 'leave_days', 'application_status', 'view_status', 'application_date', 'attachment', 'comments', 'approve_by', 'request_to','part_of_day',
        'time_off_start','time_off_end'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function approve()
    {
        return $this->belongsTo(\App\User::class, 'approve_by');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\LeaveCategory::class, 'leave_category_id', 'leave_category_id');
    }




    /**
     * @return bool
     */
    public function isEditable()
    {
        // if (! \Auth::user()->hasRole('admins') && ! \Auth::user()->hasRole('hr-manager') && \Auth::user()->id != $this->user_id && ! \Auth::user()->department_head == '1') {
        //     return false;
        // }

        // return true;

        $leave_user = \App\User::find($this->user_id);

        if(\Auth::user()->hasRole('admins') || \Auth::user()->hasRole('hr-manager') || (\Auth::user()->id == $leave_user->first_line_manager) ){
            return true;
        }else{
            return false;
        }

    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        if (\Auth::user()->hasRole('admins') || \Auth::user()->hasRole('hr-manager') || \Auth::user()->id == $this->user_id ) {
            return true;
        }

        return false;
    }

    public function hasPerm(Permission $perm)
    {
        // perm 'basic-authenticated' is always checked.
        if ('basic-authenticated' == $perm->name) {
            return true;
        }
        // Return true if the Intake has is assigned the given permission.
        if ($this->perms()->where('id', $perm->id)->first()) {
            return true;
        }
        // Otherwise
        return false;
    }


    public function partOfDay(){
        if($this->leave_category_id == env('TIME_OFF_ID')){


            return date('h:i A',strtotime($this->time_off_start)).'-'.date('h:i A',strtotime($this->time_off_end));
        }


        if($this->part_of_day == 1){

            return 'Full Leave';

        }elseif($this->part_of_day == 2){

            return '1st Half';

        }elseif($this->part_of_day == 3){

            return '2nd Half';
            
        }else{

            return '';
        }


    }
}
