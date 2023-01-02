<?php

namespace App\Mail;

use App\Browser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Sujip\Ipstack\Ipstack;

/**
 * Class IpTracker
 * @package App\Mail
 */
class IpTracker extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var mixed
     */
    protected $authorize;

    /**
     * Create a new message instance.
     *
     * @param $authorize
     *  @return void
     */
    public function __construct($authorize)
    {
        $this->authorize = $authorize;
        // $this->browser = new Browser;
    }

    /**
     * @return mixed
     */
    public function setBrowser()
    {
        $this->authorize->browser = \App\Helpers\UserSystemInfoHelper::get_browsers();

        return $this;
    }

    /**
     * @return mixed
     */
    public function setToken()
    {
        $this->authorize->token = guid();

        return $this;
    }

    /**
     * @return mixed
     */
    public function setLocation()
    {
        $location = with(new Ipstack(
            $this->authorize->ip_address
        ))->formatted();
        // dd($location);

        $this->authorize->location = $location ?? 'null';

        return $this;
    }

    /**
     * @return mixed
     */
    public function setPlatform()
    {
        $this->authorize->os = \App\Helpers\UserSystemInfoHelper::get_os();

        return $this;
    }

    public function saveAuthorize()
    {
        $this->authorize->save();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this
            ->setBrowser()
            ->setToken()
            ->setLocation()
            ->setPlatform()
            ->saveAuthorize();

        return $this->authorize;
            // ->view('emails.auth.authorize')
            // ->with(['authorize' => $this->authorize]);
    }
}