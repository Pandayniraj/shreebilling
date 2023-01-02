<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TodayTaskRemind extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:today';

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
                SELECT users.first_name, users.email, project_tasks.subject,project_tasks.end_date from users
                left JOIN project_tasks ON project_tasks.user_id = users.id
                WHERE project_tasks.end_date = curdate()
                ');

        foreach ($followup as $key => $value) {
            $from = env('APP_EMAIL');

            Mail::send('emails.taskstoday', compact('value'), function ($message) use ($value,$from) {
                $message->subject('Task Deadline - '.$value->subject);
                $message->from($from, env('APP_COMPANY'));
                $message->to($value->email, '');
            });
        }
    }
}
