<?php

namespace App\Libraries\Talk\Messages;


use Illuminate\Database\Eloquent\Model;


class Message extends Model 
{
    protected $table = 'messages';

    public $timestamps = true;

    protected $appends = ['humans_time'];

    
    public $fillable = [
        'message',
        'is_seen',
        'deleted_from_sender',
        'deleted_from_receiver',
        'user_id',
        'conversation_id',
        'attachment',
        'att_type',
        'file_name',
    ];
    /*
     * make dynamic attribute for human readable time
     *
     * @return string
     * */
    public function getHumansTimeAttribute()
    {
        $date = $this->created_at;
        $now = $date->now();

        return $date->diffForHumans($now, true);
    }

    /*
     * make a relation between conversation model
     *
     * @return collection
     * */
    public function conversation()
    {
        return $this->belongsTo('App\Libraries\Talk\Conversations\Conversation');
    }

    /*
   * make a relation between user model
   *
   * @return collection
   * */
    public function user()
    {
        return $this->belongsTo(
            config('talk.user.model', 'App\User'),
            config('talk.user.foreignKey'),
            config('talk.user.ownerKey')
        )->select(['id','username','first_name','last_name']);
    }

    /*
   * its an alias of user relation
   *
   * @return collection
   * */
    public function sender()
    {
        return $this->user();
    }

    /**
     * @return Htmlable
     */
    public function toHtmlString()
    {
        $embera = new Embera(['http' => ['curl' => [CURLOPT_SSL_VERIFYPEER => false]]]);

        return new HtmlString($this->message, $embera);
    }
    public function takeModel()
    {
        return Message::class;
    }

    public function deleteMessages($conversationId)
    {
        return (boolean) Message::where('conversation_id', $conversationId)->delete();
    }

    public function softDeleteMessage($messageId, $authUserId)
    {
        $message = $this->with(['conversation' => function ($q) use ($authUserId) {
            $q->where('user_one', $authUserId);
            $q->orWhere('user_two', $authUserId);
        }])->find($messageId);

        if (is_null($message->conversation)) {
            return false;
        }
        $attribute =[];
        if ($message->user_id == $authUserId) {
            $attribute['deleted_from_sender'] = 1;
        } else {
            $attribute['deleted_from_receiver'] = 1;
        }
        $update = $message->update($attribute);
        return (boolean) $update;
        
    }
}
