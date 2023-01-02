<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    /**
     * @var array
     */

    /**
     * @var array
     */
    protected $fillable = ['status', 'user_id','approved_by','date','department_id','req_date','comment'];

    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class, 'department_id','departments_id');
    }

    public function destination()
    {
        return $this->belongsTo(\App\Models\ProductLocation::class, 'destination_id');
    }
    public function products()
    {
        return $this->hasMany(\App\Models\Requisition_Detail::class, 'requisition_id');
    }


}
