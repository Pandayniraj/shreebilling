<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Mail\SendBulkMailAll;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class SendBulkEmailToAll extends Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 300; // default is 60sec. 

    protected $lead;
    protected $request;
    protected $mail_to;
    protected $fields;
    protected $details;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        // $this->lead = $lead;
        // $this->request = $request;
        // $this->mail_to = $mail_to;
        // $this->fields = $fields;
         $this->details = $details;

        //dd($details);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        // sleep(20);

        // $lead = $this->lead;
        // $request = $this->request;
        // $mail_to = $this->mail_to;
        // $fields = $this->fields;
        // $mail_from = env('APP_EMAIL');
        // $next_mail = (new self($lead, $request, $mail_to, $fields));
        // // \App\Models\TestJobs::create(['test'=>'testing'])
        // try {
        //     $mailer->send('emails.email-send-bulk-all', ['lead' => $lead, 'request' => $request], function ($message) use ($request, $mail_to, $fields,
        //     $mail_from) {
        //         $message->subject($request['subject']);
        //         $message->from($mail_from, env('APP_COMPANY'));
        //         $message->to($mail_to, '');
        //         if ($fields != '') {
        //             $message->attach('sent_attachments/'.$fields['file']);
        //         }
        //     });
        // } catch (\Exception $e) {
        // }

        $leads = \App\Models\Lead::where('email', 'like', '%_@__%.__%')
                    //->orderBy('id', DESC)
                    ->get();
          
            foreach ($leads as $k => $v) {

                    try{
                      
                    $email = new SendBulkMailAll($this->details);
                    Mail::to($v->email ?: [])->send($email, $v->name);

                    } catch(Exception $exception) {
                        // do something with $exception that contains the error message
                    }

            
            }



    }
}
