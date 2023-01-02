<?php

namespace App\Jobs;

use App\Models\Lead;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkEmailToCampainLead extends Job implements ShouldQueue
{
    use  InteractsWithQueue, SerializesModels;

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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $lead = $this->lead;
        $request = $this->request;
        $mail_to = $this->mail_to;
        $fields = $this->fields;
        $mail_from = env('APP_EMAIL');
        $next_mail = (new SendBulkEmailToAll($lead, $request, $mail_to, $fields));
        // \App\Models\TestJobs::create(['test'=>'testing'])
        try {
            $mailer->send('emails.email-send-bulk-all', ['lead' => $lead, 'request' => $request], function ($message) use ($request, $mail_to, $fields,
            $mail_from) {
                $message->subject($request['subject']);
                $message->from($mail_from, env('APP_COMPANY'));
                $message->to($mail_to, '');
                if ($fields != '') {
                    $message->attach('sent_attachments/'.$fields['file']);
                }
            });
        } catch (\Exception $e) {
        }
    }
}
