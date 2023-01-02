<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StickyNote extends Model
{
    protected $table = 'sticky_note';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'title', 'description', 'color', 'org_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
