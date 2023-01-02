<?php

namespace App\Console\Commands;

use App\Models\OrderPaymentTerms;
use App\Notifications\SendEmiDueNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EmiDueNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:emiDuesNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $to_mail_term_date=Carbon::today()->addDays(3)->format('Y-m-d');

        OrderPaymentTerms::whereDate('term_date',$to_mail_term_date)
            ->whereHas('orders',function ($q){
                $q->where('order_type','proforma_invoice');
            })->get()->each(function ($q){
                $q->orders->client->email?$q->orders->client->notify(new SendEmiDueNotification($q)):'';
                return $q->update(['is_client_notified'=>1]);
            });

    }
}
