<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsFeedFiles extends Model
{
    use HasFactory;


    protected $table = 'news_feeds_files';

    /**
     * @var array
     */
    protected $fillable = ['news_feeds_id', 'images'];




    
}
