<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveChangeRequestForward extends Model
{
    /**
     * @var array
     */
    protected $table = 'leave_request_forwards';

    /**
     * @var array
     */
    protected $fillable = ['leave_id','from_id','to_id','application_status','note','forward_source'];


    // public function siftRequestForward()
    // {
    //     return $this->belongsTo(AttendanceChangeRequest::class,'change_id');
    // }

   
}
