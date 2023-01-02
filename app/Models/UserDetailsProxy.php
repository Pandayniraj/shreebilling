<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetailsProxy extends Model
{


    protected $table = 'user_detail_proxies';

    protected $fillable = [ 'user_id', 'user_detail_id', 'temp_data', 'approved_by' ];

    
}
