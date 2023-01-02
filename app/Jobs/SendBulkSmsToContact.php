<?php

namespace App\Jobs;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkSmsToContact extends Job implements ShouldQueue
{
    use  InteractsWithQueue, SerializesModels;

    protected $number;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($number, $message)
    {
        $this->number = $number;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $number = $this->number;
        // $message = $this->message;


        try {
             $args = http_build_query(array(
                'token' => 'v2_DIQ04KIX1rW6BjmKYmMK4nhqvrv.Mq8G',
                'from' => 'Demo',
                'to' =>  $this->number,
                'text' => $this->message ));

               $url = "http://api.sparrowsms.com/v2/sms/";

                # Make the call using API.
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                // Response
                $response = curl_exec($ch);
                $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
        } catch (\Exception $e) {
        }
    }
}