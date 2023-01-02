<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeChangeRequestForward extends Model
{
    /**
     * @var array
     */
    protected $table = 'shift_request_forwards';

    /**
     * @var array
     */
    protected $fillable = ['change_id','from_id','to_id','status','note','forward_source'];


    // public function siftRequestForward()
    // {
    //     return $this->belongsTo(AttendanceChangeRequest::class,'change_id');
    // }

   
}
