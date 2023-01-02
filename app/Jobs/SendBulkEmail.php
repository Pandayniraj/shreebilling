<?php

namespace App\Jobs;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class SendBulkEmail extends Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 300; // default is 60sec. 

    protected $lead;
    protected $request;
    protected $mail_to;
    protected $fields;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Lead $lead, $request, $mail_to, $fields)
    {
        $this->lead = $lead;
        $this->request = $request;
        $this->mail_to = $mail_to;
        $this->fields = $fields;
        $this->mail_from = env('APP_EMAIL');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        // $lead = $this->lead;
        // $request = $this->request;
        // $mail_to = $this->mail_to;
        // $fields = $this->fields;
        // $mail_from = $this->mail_from;
        // try {
        //     $mailer->send('emails.email-send-bulk', ['lead' => $lead, 'request' => $request], function ($message) use ($request, $mail_to, $fields,$mail_from) {
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
                    ->where('product_id', $this->request['course_no'])
                    ->where('status_id', $this->request['status_no'])
                    ->get();

            foreach ($leads as $k => $v) {

                    try{

                    $email = new SendBulkMailProductWise($this->details);
                    Mail::to($v->email ?: [])->send($email, $v->name);

                    } catch(Exception $exception) {
                        // do something with $exception that contains the error message
                    }

            
            }
    }

}
