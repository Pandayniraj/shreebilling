<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveCarryForward extends Model
{
    use HasFactory;

    protected $table = 'leave_carry_forward';


    protected $fillable = ['user_id','from_leave_year_id','num_of_carried','num_days_balance'];


       public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

}
