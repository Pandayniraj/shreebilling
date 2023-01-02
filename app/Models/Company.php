<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'lead_company';
    /**
     * @var array
     */
    protected $fillable = ['name'];

    public function lead()
    {
        return $this->hasMany(\App\Models\Lead::class);
    }
}
