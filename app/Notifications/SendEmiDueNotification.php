<?php

namespace App\Notifications;

use App\Models\Client;
use App\Models\OrderPaymentTerms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmiDueNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $orderPaymentTerms='';


    /**
     * Create a new notification instance.
     *
     * @param OrderPaymentTerms $orderPaymentTerms
     */
    public function __construct(OrderPaymentTerms $orderPaymentTerms)
    {
        $this->orderPaymentTerms=$orderPaymentTerms;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $from = env('APP_EMAIL');
        return (new MailMessage)
            ->from($from, env('APP_COMPANY'))
            ->view('emails.emi-dues-notification',['order_payment_term'=>$this->orderPaymentTerms])
            ->subject('Emi Due Notification');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
