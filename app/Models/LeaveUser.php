<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveUser extends Model
{
    use HasFactory;


    protected $table = 'leave_users';

    protected $fillable = ['user_id','leave_id'];

      public function user()
    {
        return $this->belongsTo(\App\User::class);
    }


}
