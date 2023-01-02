<?php

namespace App\Http\Middleware;


use Illuminate\Http\Request;
use App\Exceptions\Handler;
use Closure;
use Flash;

use App\Models\Authorize;
use App\Mail\IpTracker as IpTrackerMail;
use Illuminate\Support\Facades\Mail;

/**
 * Class IpTracker
 * @package App\Http\Middleware
 */

class IpTracker
{
    /**
     * @var \App\Authorize
     */
    private $authorize;

    public function __construct(Authorize $authorize)
    {
        $this->authorize = $authorize;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(!env('REMOTE_ACCESS')){
             return $next($request);
        }

        if (Authorize::inactive() && auth()->check()) {
            $this->authorize = Authorize::make();
    
            if ($this->authorize->noAttempt()) {

                $tomail = $request->user()->email;
                $authorize = new IpTrackerMail($this->authorize);
                $authorize = $authorize->build();

                // dd($authorize->);
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
                // Mails::to($tomail)
                //     ->send(new IpTrackerMail($this->authorize));

                $this->authorize->increment('attempt');
            }

            if ($this->timeout()) {
                auth()->guard()->logout();

                $request->session()->invalidate();
                Flash::error('You are logged out of system, please follow the link we sent before 15 minutes to authorize your device, the link will be valid with same IP for 24hrs.');

                return redirect('/login');
            }

            $page_title = 'Authorize New Device';
            return response()->view('auth.authorize',compact('page_title'));
        }

        return $next($request);
    }

    /**
     * Determines if the authorize attempt is timed out.
     *
     * @return bool
     */
    private function timeout()
    {
        $waiting = $this->authorize
            ->created_at
            ->addMinutes(15);

        if (now() >= $waiting) {
            return true;
        }

        return false;
    }
}