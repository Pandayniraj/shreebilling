<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class RemoteTaxDeduction extends Model
{
    /**
     * @var array
     */
    protected $table = 'remote_deductions';

    /**
     * @var array
     */

    protected $fillable = ['group_id','district_name'];


    public function group()
    {
    	return $this->belongsTo(\App\Models\RemoteTaxDeductionCategory::class,'group_id');
    }


}