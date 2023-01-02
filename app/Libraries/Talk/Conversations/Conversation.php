<?php

namespace App\Libraries\Talk\Conversations;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversations';
    public $timestamps = true;
    public $fillable = [
        'user_one',
        'user_two',
        'status',
    ];

    /*
     * make a relation between message
     *
     * return collection
     * */
    public function messages()
    {
        return $this->hasMany('App\Libraries\Talk\Messages\Message', 'conversation_id')
            ->with('sender');
    }

    /*
     * make a relation between first user from conversation
     *
     * return collection
     * */
    public function userone()
    {
        return $this->belongsTo(config('talk.user.model', 'App\User'),  'user_one', config('talk.user.ownerKey'));
    }

    /*
   * make a relation between second user from conversation
   *
   * return collection
   * */
    public function usertwo()
    {
        return $this->belongsTo(config('talk.user.model', 'App\User'),  'user_two', config('talk.user.ownerKey'));
    }

       
        public function threads($user, $order, $offset, $take)
    {
        $conv = new Conversation();
        $conv->authUser = $user;
        $msgThread = $conv->with(
            [
                'messages' => function ($q) use ($user) {
                    return $q->where(
                        function ($q) use ($user) {
                            $q->where('user_id', $user)
                                ->where('deleted_from_sender', 0);
                        }
                    )
                        ->orWhere(
                            function ($q) use ($user) {
                                $q->where('user_id', '!=', $user);
                                $q->where('deleted_from_receiver', 0);
                            }
                        )
                        ->latest();
                }, 'messages.sender', 'userone', 'usertwo'
            ]
        )
            ->where('user_one', $user)
            ->orWhere('user_two', $user)
            ->offset($offset)
            ->take($take)
            ->orderBy('updated_at', $order)
            ->get();

        $threads = [];

        foreach ($msgThread as $thread) {
            $collection = (object)null;
            $conversationWith = ($thread->userone->id == $user) ? $thread->usertwo : $thread->userone;
            $collection->thread = $thread->messages->first();
            $collection->withUser = $conversationWith;
            $threads[] = $collection;
        }

        return collect($threads);
    }
        public function isExistsAmongTwoUsers($user1, $user2)
    {
        $conversation = Conversation::where(
            function ($query) use ($user1, $user2) {
                $query->where(
                    function ($q) use ($user1, $user2) {
                        $q->where('user_one', $user1)
                            ->where('user_two', $user2);
                    }
                )
                    ->orWhere(
                        function ($q) use ($user1, $user2) {
                            $q->where('user_one', $user2)
                                ->where('user_two', $user1);
                        }
                    );
            }
        );

        if ($conversation->exists()) {
            return $conversation->first()->id;
        }

        return false;
    }
        public function getMessagesById($conversationId, $userId, $offset, $take)
    {

        
        return Conversation::with(
            [
                'messages' => function ($query) use ($userId, $offset, $take) {
                    $query->where(
                        function ($qr) use ($userId) {
                            $qr->where('user_id', '=', $userId)
                                ->where('deleted_from_sender', 0);
                        }
                    )
                        ->orWhere(
                            function ($q) use ($userId) {
                                $q->where('user_id', '!=', $userId)
                                    ->where('deleted_from_receiver', 0);
                            }
                        );

                    // $query->offset($offset)->take($take);
                    $query->get();
                }
            ]
        )->with(['userone', 'usertwo'])->find($conversationId);

    }
}
