<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Cache;
use Illuminate\Http\Request;
use App\Libraries\Talk\Talk;
use App\Libraries\Pusher\Pusher;
use Config;
class ChatApiController extends Controller
{
 
   
    public $APP_ID;
    public $PUSHER_KEY;
    public $APP_SECRET;
    public $APP_CLUSTER;
    public $CHANNEL;
    protected $talk;

    public function __construct(Request $request)
    {
        $this->APP_ID = Config::get('talk.app_id');
        $this->PUSHER_KEY = Config::get('talk.app_key');
        $this->APP_SECRET = Config::get('talk.app_secret');
        $this->APP_CLUSTER= Config::get('talk.cluster');
        $this->CHANNEL = Config::get('talk.chat_channel');
        $this->talk = new Talk;
        $this->talk->setAuthUserId($request->sender_id);
    }

    private $userinfo;

    private function authorizeToken($token)
    {
        $checktoken = \App\Models\Usertoken::where('token', $token)->first();

        if (! $checktoken) {
            abort(404);
        } else {
            $this->userinfo = $checktoken;
        }

        return $checktoken;
    }

    public function chatIndex(Request $request, $token)
    {
        $this->authorizeToken($token);

        $threads = $this->talk->threads();
        $users = \App\User::where('enabled', '1')->get();
        $usersList = [];
        $onlineUser = [];
        foreach ($users as  $key => $value) {
            $online = Cache::has("user-is-online-{$value->id}") ? true : false;
            $info = [
                'username'=>$value->username,
                'id'=>$value->id,
                'full_name'=>$value->first_name.' '.$value->last_name,
                'first_name'=>$value->first_name,
                'last_name'=>$value->last_name,
                'isOnline'=> $online,
            ];
            if ($online) {
                $onlineUser[] = $value->id;
            }
            $usersList[] = $info;
        }

        return ['threads'=>$threads, 'userlist'=>$usersList, 'onlineuser'=>$onlineUser];
    }

    public function chatHistory(Request $request, $token)
    {
        $this->authorizeToken($token);

        $messages = $this->getUserMessage($request->receiver_id);
        $tempmessage = $messages;
        $unseen = $tempmessage->where('user_id', $request->receiver_id)
                ->where('is_seen', '0')->pluck('id');
        if (count($unseen) > 0) {
            \DB::table('messages')->whereIn('id', $unseen)->update(['is_seen'=>'1']);
        }

        $totalMessage = count($messages);
        $messages = $messages->slice(-20); //get latest 20 messages
        $messages = $messages->sortBy('id');
        $messages = $messages->values();

        // dd($messages);
        // DB::table('messages')->where('user_id',$)->where('conversation_id',$cid)->update(['is_seen'=>true]);
        return ['messages'=>$messages, 'totalMessage'=>$totalMessage];
    }

    private function getUserMessage($id)
    {
        $messages = collect();
        $conversations = $this->talk->getMessagesByUserId($id); //dont know why always first getting 20 messages so....
        // $user = $conversations->withUser;
        if ($conversations) {
            $messages = $conversations->messages;
        }

        return $messages;
    }

    public function sendMessage(Request $request, $token)
    {
        $this->authorizeToken($token);
        $pusher = new Pusher($this->PUSHER_KEY, $this->APP_SECRET,
            $this->APP_ID, ['cluster' => $this->APP_CLUSTER]);
        $body = $request->message;
        $userId = $request->receiver_id;
        $sender_id = $request->sender_id;
        $attachment = [
            'attachment'=>$request->attachment,
            'att_type'=>$request->att_type,
            'file_name'=>$request->file_name,
            'extension'=>$request->extension,
        ];
        $messages = $this->talk->sendMessageByUserId($userId, $body, $attachment);
        $messages['sender'] = $messages->sender;
        $pusher->trigger($this->CHANNEL, 'chat-user-'.$userId.'-'.$sender_id, ['message' => $messages]);
        $pusher->trigger($this->CHANNEL, 'chat-recv_user-'.$userId,
            [
            'message' => $messages,
            'isOnline'=>\Cache::has('user-is-online-'.$sender_id) ? true : false,
            ]
          );

        return ['sucess'=>true, 'id'=>$messages->id];
    }

    public function typing(Request $request, $token)
    {
        $this->authorizeToken($token);
        $pusher = new Pusher($this->PUSHER_KEY, $this->APP_SECRET,
            $this->APP_ID, ['cluster' => $this->APP_CLUSTER]);
        $pusher->trigger($this->CHANNEL, 'chat-user-typing-'.$request->receiver_id.'-'.$request->sender_id, ['message' => 'typing']);

        return ['success'=>true];
    }

    public function moreMessage(Request $request, $token)
    {
        $this->authorizeToken($token);
        $messages = $this->getUserMessage($request->receiver_id);
        $messages = $messages->slice($request->start, $request->end);
        $messages = $messages->values();

        return ['status'=>'success', 'data'=>$messages];
    }
}
