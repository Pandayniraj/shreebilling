<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LeadFollowupReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:followup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Daily Leads Followup reminder in mail';

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
        $followup = DB::select('
                SELECT users.first_name, users.email, leads.name from users
                left JOIN leads ON leads.user_id = users.id
                WHERE leads.target_date = curdate()
                ');

        foreach ($followup as $key => $value) {
            $from = env('APP_EMAIL');

            Mail::send('emails.leadfup', compact('value'), function ($message) use ($value,$from) {
                $message->subject('Followup reminder - '.$value->name);
                $message->from($from, env('APP_COMPANY'));
                $message->to($value->email, '');
            });
        }
    }
}
