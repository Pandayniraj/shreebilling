<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsFeed extends Model
{
    use HasFactory;


        /**
     * @var array
     */
    protected $table = 'news_feeds';

    /**
     * @var array
     */
protected $fillable = ['user_id', 'body','view_level','schedule','auto_created'];


   public function user()
    {
        return $this->belongsTo(\App\User::class);
    }


    public function createdtime(){

    	return \Carbon\Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans();
    }


    public function files(){


    	return $this->hasMany(\App\Models\NewsFeedFiles::class, 'news_feeds_id', 'id');


    }

    public function checkLikes(){

        $userHasLiked = \App\Models\NewsFeedLikes::where('user_id',\Auth::user()->id)
                       ->where('news_feeds_id',$this->id)
                       ->first();
        return $userHasLiked;


    }


    public function topComments($limit){

        $topcomment = \App\Models\NewsFeedComments::where('news_feeds_id',$this->id)->orderBy('id','desc')->take($limit)->get();
        $topcomment = $topcomment->sortBy('id');

        return $topcomment;
    }

    public function getTotalLikes(){
        
        return $this->hasMany(\App\Models\NewsFeedLikes::class, 'news_feeds_id', 'id');
        
    }


    public function comments(){

        return $this->hasMany(\App\Models\NewsFeedComments::class, 'news_feeds_id', 'id');


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


      public function isDeletable()
    {
        // Protect the admins and users Leadtypes from deletion
        $auth = \Auth::user();

        if($auth->hasRole('admins') || $auth->id == $this->user_id ){

            return true;
        }

        return false;
    }



  




}
