<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class WeeklyTaskReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Weekly Task Report in admin mail';

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
        $lastweek = DB::select('
                 SELECT project_tasks.id, projects.name, users.first_name, users.email, project_tasks.subject,project_tasks.created_at,project_tasks.end_date, project_tasks.status
                from project_tasks
                LEFT JOIN users ON project_tasks.user_id = users.id
                LEFT JOIN projects ON project_tasks.project_id = projects.id
                WHERE project_tasks.end_date
                BETWEEN SUBDATE(CURDATE(), INTERVAL 7 DAY) AND NOW()
                ORDER BY end_date
                ');

        $lastweekstart = DB::select('
                 SELECT project_tasks.id, projects.name, users.first_name, users.email, project_tasks.subject,project_tasks.created_at,project_tasks.end_date, project_tasks.status
                from project_tasks
                LEFT JOIN users ON project_tasks.user_id = users.id
                LEFT JOIN projects ON project_tasks.project_id = projects.id
                WHERE project_tasks.created_at
                BETWEEN SUBDATE(CURDATE(), INTERVAL 7 DAY) AND NOW()
                ORDER BY end_date
                ');

        $from = env('APP_EMAIL');
        $tomail = env('REPORT_EMAIL');
        $cc = env('CC_EMAIL');

        //send to env('REPORT_EMAIL')

        Mail::send('emails.weeklytaskreport', ['lastweek'=> $lastweek, 'lastweekstart'=> $lastweekstart],
      function ($message) use ($lastweek, $tomail, $cc, $from) {
          $message->subject('Tasks Report Weekly ');
          $message->from($from, env('APP_COMPANY'));
          $message->to($tomail, '');
          $message->cc($cc, '');
      });
    }
}
