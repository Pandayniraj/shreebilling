<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsFeedLikes extends Model
{
    use HasFactory;



    protected $table = 'news_feeds_likes';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'news_feeds_id'];


    
   public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

}
