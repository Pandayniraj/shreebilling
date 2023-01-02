<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\AuditRepository as Audit;
use App\Repositories\ContactRepository as Contact;
use App\Repositories\RoleRepository as Permission;
use App\User;
use Auth;
use DB;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Libraries\Talk\Talk;
use View;
use App\Libraries\Pusher\Pusher;
use Config;
/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class TalkController extends Controller
{
    /**
     * @var Permission
     */
    private $permission;
    public $APP_ID;
    public $PUSHER_KEY;
    public $APP_SECRET;
    public $APP_CLUSTER;
    public $CHANNEL;
    /**
     * @param contact $contact
     * @param Permission $permission
     * @param User $user
     */
    //   public function __construct( Permission $permission)
    //   {
    // parent::__construct();
    //       $this->permission = $permission;

    //         Talk::setAuthUserId(Auth::user()->id);

    //       View::composer('partials.peoplelist', function($view) {
    //           $threads = Talk::threads();
    //           $view->with(compact('threads'));
    //       });
    //   }

    protected $authUser;
    protected $talk;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->talk = new Talk;
            $this->APP_ID = Config::get('talk.app_id');
            $this->PUSHER_KEY = Config::get('talk.app_key');
            $this->APP_SECRET = Config::get('talk.app_secret');
            $this->APP_CLUSTER= Config::get('talk.cluster');
            $this->CHANNEL = Config::get('talk.chat_channel');
            $this->talk->setAuthUserId(Auth::user()->id);

            return $next($request);
        });
    }

    /**
     * @return \Illuminate\View\View
     */
    private function getuserProfilePic($user)
    {
        $profile = $user->image;
        if ($profile) {
            $profile = url('/').'/images/profiles/'.$profile;
        } else {
            $profile = url('/').'/images/logo.png';
        }

        return $profile;
    }

    public function index()
    {   
        // $pusher = new Pusher($this->PUSHER_KEY, $this->APP_SECRET,
        // $this->APP_ID, ['cluster' => $this->APP_CLUSTER]);
        $page_title = 'Admin | Chat';
        
        $unseen_message = \DB::table('messages')->where('messages.is_seen', '0')->where('messages.user_id', '!=', \Auth::user()->id)
              ->leftjoin('conversations', 'messages.conversation_id', '=', 'conversations.id')
              ->where('conversations.user_one', \Auth::user()->id)->orWhere('conversations.user_two', \Auth::user()->id)
              ->groupBy('messages.conversation_id')
              ->get();

        $users = User::where('enabled', '1')->get();

        $total_unseen_message = count($unseen_message);
        
        $threads = $this->talk->threads();
        
        $user_id = null;

        foreach ($threads as $key => $inbox) {
            if (! is_null($inbox->thread)) {
                $user_id = $inbox->withUser->id; //getting first user in chat list
                break;
            }
        }

        $messages = $this->getUserMessage($user_id);

        $totalMessage = count($messages);
        $messages = $messages->slice(-20); //get latest 20 messages

        $receiver = $user_id; //message receiver

        $rcv_user = User::find($user_id);
        $rcv_profile_img = $this->getuserProfilePic($rcv_user);
        $sender_profile_img = $this->getuserProfilePic(Auth::user());
        //dd($sender_profile_img);
        if (count($messages) > 0) {
            $messages = $messages->sortBy('id');
            $this->ajaxSeenMessage($messages[0]->conversation_id);
        }
        $messages = $messages->values();
    
        //dd($messages);
        return view('admin.talk.chathistory', compact('messages', 'rcv_user', 'user_id', 'totalMessage', 'threads', 'users', 'receiver', 'sender_profile_img', 'rcv_profile_img', 'page_title','total_unseen_message'));
    }

    // private function filterMessage($messages){
    //   $filterData = [];
    //   foreach($messages as $key=>$value){
    //     $messagesData = $value->toArray();
    //     $sender = $value->sender->only('id','username','first_name','last_name');
    //     $messagesData['sender'] = $sender;
    //     $filterData [] = $messagesData;
    //   }

    //   return $filterData;
    // }

    private function getUserMessage($id)
    {
        $messages = collect();
        $conversations = $this->talk->getMessagesByUserId($id);
        
        if ($conversations) {
            $messages = $conversations->messages;
        }

        return $messages;
    }

    public function chatHistory($id)
    {
        $page_title = 'Admin|Chat';
        $messages = $this->getUserMessage($id);
        // dd($messages);
        $totalMessage = count($messages);
        //dd($totalMessage);
        $messages = $messages->slice(-20); //get latest 20 messages
        $user = User::find($id);
        $receiver = $id;
        if (count($messages) > 0) {
            $messages = $messages->sortBy('id');
            $this->ajaxSeenMessage($messages[0]->conversation_id);
        }
        $rcv_profile_img = $this->getuserProfilePic($user);
        $sender_profile_img = $this->getuserProfilePic(Auth::user());
        $threads = $this->talk->threads();
        $users = User::where('enabled', '1')->get();
        $rcv_user = User::find($id);
        $messages = $messages->values();

        // return $threads;
        return view('admin.talk.chathistory', compact('messages', 'rcv_user', 'user_id', 'totalMessage', 'threads', 'users', 'receiver', 'sender_profile_img', 'rcv_profile_img', 'page_title'));
    }

    public function ajaxSyncMessage($id)
    {
        $messages = $this->getUserMessage($id);
        $messages = $messages->slice(-20); //get latest 20 messages

        return ['message'=>$messages];
    }

    public function moreMessage($id, $start, $end)
    {
        $messages = $this->getUserMessage($id);
        $messages = $messages->slice($start, $end);
        $messages = $messages->values();

        return response()->json(['status'=>'success', 'data'=>$messages], 200);
    }

    public function ajaxSendMessage(Request $request)
    {
        //dd("DOEN");
        $pusher = new Pusher($this->PUSHER_KEY, $this->APP_SECRET,
            $this->APP_ID, ['cluster' => $this->APP_CLUSTER]);
        $rules = [
          'message-data'=>'required',
          '_id'=>'required',
      ];
        //    dd($request->all());
        $attachment = [
        'attachment'=>$request->attachment,
        'att_type'=>$request->att_type,
        'file_name'=>$request->file_name,
        'extension'=>$request->extension,
      ];
        //dd($attachment);
        $this->validate($request, $rules);
        $body = $request->input('message-data');
        $userId = $request->input('_id');
        if ($messages = $this->talk->sendMessageByUserId($userId, $body, $attachment)) {
            $messages['sender'] = $messages->sender;
            $pusher->trigger($this->CHANNEL, 'chat-user-'.$userId.'-'.\Auth::user()->id, ['message' => $messages]);

            $pusher->trigger($this->CHANNEL, 'chat-recv_user-'.$userId,
            [
            'message' => $messages,
            'isOnline'=>\Cache::has('user-is-online-'.\Auth::user()->id) ? true : false,
            ]
          );

          //   try {
          //       $from = \Auth::user()->email;
          //       $to = \App\User::find($userId)->email;
          //       $message = $body ?? 'Send A attachment';
          //       $mail = \Mail::send('emails.email-send', compact('message'),
          //       function ($message) use ($from, $to) {
          //           $message->subject('You Have New message From '.$from);
          //           $message->from($from, '');
          //           $message->to($to, '');
          //       });
          //   } catch (\Exception $e) {
          //   }

            return ['sucess'=>true, 'id'=>$messages->id];
        }
    }

    public function ajaxDeleteMessage(Request $request, $id)
    {
        if ($request->ajax()) {
            if ($this->talk->deleteMessage($id)) {
                return response()->json(['status'=>'success', 'check'=>$id], 200);
            }

            return response()->json(['status'=>'errors', 'msg'=>'something went wrong'], 401);
        }
    }

    public function ajaxSeenMessage($cid)
    {
        $user = DB::table('messages')->where('conversation_id', $cid)->orderBy('created_at', 'desc')->first();
        $checked_user = $user->user_id;
        if ($checked_user != Auth::user()->id) {
            DB::table('messages')->where('user_id', $checked_user)->where('conversation_id', $cid)->update(['is_seen'=>true]);
        }

        return 0;
    }

    public function getPopoverMessage()
    {
        $threads = $this->talk->threads();

        $threads = $threads->slice(0, 20);
        $threads = $threads->values();
        $html = view('ajax.popovermessage', compact('threads'))->render();

        return ['message'=>$html];
    }

    public function typing($receiver_id)
    {
        $pusher = new Pusher($this->PUSHER_KEY, $this->APP_SECRET,
            $this->APP_ID, ['cluster' => $this->APP_CLUSTER]);
        $pusher->trigger($this->CHANNEL, 'chat-user-typing-'.$receiver_id.'-'.\Auth::user()->id, ['message' => 'typing']);

        return ['success'=>true];
    }
}
