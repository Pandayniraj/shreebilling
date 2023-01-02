<?php

namespace App\Http\Controllers\Auth;

use Flash;
use App\Models\Authorize;
use App\Http\Controllers\Controller;
use App\Mail\IpTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Mail\IpTracker as IpTrackerMail;
/**
 * Class AuthorizeController
 * @package App\Http\Controllers\Auth
 */
class AuthorizeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *  Validate the token for the Authorization.
     *
     * @param $token
     * @return \Illuminate\Http\Response
     */
    public function verify($token = null)
    {
        if (Authorize::validateToken($token)) {
            Flash::success('Awesome ! you are now authorized !');
            return Redirect::route('home');
        }
        $page_title = 'Authorize New Device';
        Flash::error('Oh snap ! the authorization token is either expired or invalid. Click on Email didn\'t arraive ? again');
        return view('auth.authorize',compact('page_title'));
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if (Authorize::inactive() && auth()->check()) {
            $authorize = Authorize::make();
            $tomail = $request->user()->email;
            $authorize = new IpTrackerMail($authorize);
            $authorize = $authorize->build();
            try{
                $from = env('APP_EMAIL');
                $to = $tomail;
                
                $mail = Mail::send('emails.auth.authorize',compact('authorize'),function ($message) use ($from, $to) {
                    $message->subject('Authorize New Device');
                    $message->from($from, env('APP_COMPANY'));
                    $message->to($to, '');
                });
            }
            catch(\Exception $e){
                
            }
            Flash::success('Mail resend successfully. Please, Check your spam folder if mail not found in inbox !');
            $authorize->increment('attempt');
            
            $page_title = 'Authorize New Device';
            return view('auth.authorize',compact('page_title'));
        }else{
            Flash::success('Awesome ! you are already authorized !');
            return Redirect::route('home');
        }

    }
}