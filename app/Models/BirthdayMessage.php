<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BirthdayMessage extends Model
{
    use HasFactory;
    protected $table='birthday_messages';
    protected $fillable=['title','body','active','message_type','user_id','post_id'];


    public function isEditable()
    {
        // Protect the root user from deletion.
//        if ('root' == $this->username) {
//            return false;
//        }
//        // Prevent user from deleting his own account.
//        if (Auth::check() && (Auth::user()->id == $this->id)) {
//            return false;
//        }
        // Otherwise
        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the root user from deletion.
//        if ('root' == $this->username) {
//            return false;
//        }
//        // Prevent user from deleting his own account.
//        if (Auth::check() && (Auth::user()->id == $this->id)) {
//            return false;
//        }
        // Otherwise
        return true;
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function fromuser(){
        return $this->belongsTo(User::class,'post_id');
    }
}
