<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsFeedComments extends Model
{
    use HasFactory;

        protected $table = 'news_feeds_comments';

    /**
     * @var array
     */
    protected $fillable = ['user_id','comment', 'news_feeds_id'];


     public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function createdtime(){

    	return \Carbon\Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans();
    }


     public function isEditable()
    {
         // Protect the admins and users Leadtypes from deletion
        $auth = \Auth::user();

        if($auth->hasRole('admins') || $auth->id == $this->user_id ){

            return true;
        }

        return false;
    }


    public function news(){


        return $this->belongsTo(\App\Models\NewsFeed::class, 'news_feeds_id');

    }

      public function isDeletable()
    {
        // Protect the admins and users Leadtypes from deletion
        $auth = \Auth::user();

        if($auth->hasRole('admins') || $auth->id == $this->user_id || $auth->id == $this->news->user_id ){
 
            return true;
        }

        return false;
    }


}
