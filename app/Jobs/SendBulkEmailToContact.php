<?php

namespace App\Jobs;

use App\Models\Contact;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendBulkEmailContact;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class SendBulkEmailToContact extends Job implements ShouldQueue
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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {

        // $contact = $this->contact;
        // $request = $this->request;
        // $mail_to = $this->mail_to;
        // $fields = $this->fields;
        // $mail_from = env('APP_EMAIL');
        // try {
        //     $mailer->send('emails.send-bulk-email-contact', ['contact' => $contact, 'request' => $request], function ($message) use ($request, $mail_to, $fields, $contact,$mail_from) {
        //         $message->subject($request['subject']);
        //         $message->from($mail_from, env('APP_COMPANY'));
        //         $message->to($mail_to, $contact->full_name);
        //         if ($fields != '') {
        //             $message->attach('sent_attachments/'.$fields['file']);
        //         }
        //     });
        // } catch (\Exception $e) {
        // }

            $contacts = \App\Models\Contact::where('email_1', 'like', '%_@__%.__%')->get();

            foreach ($contacts as $k => $v) {

                    try{

                    $email = new SendBulkEmailContact($this->details);
                    Mail::to($v->email_1 ?: [])->send($email, $v->name);

                    } catch(Exception $exception) {
                        // do something with $exception that contains the error message
                    }

            
            }
    }
}
