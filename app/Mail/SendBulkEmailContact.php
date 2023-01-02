<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendBulkEmailContact extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
         $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if($this->details['attachments']){

            $destinationPath = public_path().'/sent_attachments/'.$this->details['attachments'];

             if(is_file($destinationPath)){

                return $this->view('emails.send-bulk-email-contact')->subject(\Request::get('title'))->with("details",$this->details)->attach($destinationPath);
            }
        }


        return $this->view('emails.send-bulk-email-contact')->subject(\Request::get('title'))->with("details",$this->details);
    }
}
