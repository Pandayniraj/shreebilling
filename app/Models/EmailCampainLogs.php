<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampainLogs extends Model
{
    protected $table = 'bulk_email_campaign';

    /**
     * @var array
     */
    protected $fillable = ['title', 'subject', 'messgae', 'product_id', 'status_id'];

    public function course()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\Leadstatus::class, 'status_id');
    }
}
