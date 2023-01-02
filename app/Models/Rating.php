<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    /**
     * @var array
     */
    protected $table = 'ratings';

    /**
     * @var array
     */
    protected $fillable = ['name', 'bg_color', 'enabled'];
}
