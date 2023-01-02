<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class WeeklyBusinessReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'business:week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Weekly Business Report in admin mail';

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
        $one_week_ago = \Carbon\Carbon::now()->subDays(6)->format('Y-m-d');

        $leads = \App\Models\Lead::where('lead_type_id', '2')
        ->where('created_at', '>=', $one_week_ago)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get([
             DB::raw('Date(created_at) as date'),
             DB::raw('COUNT(*) as "count"'),
          ]);

        $targets = \App\Models\Lead::where('lead_type_id', '1')->where('created_at', '>=', $one_week_ago)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get([
             DB::raw('Date(created_at) as date'),
             DB::raw('COUNT(*) as "count"'),
          ]);

        $clients = \App\Models\Client::where('created_at', '>=', $one_week_ago)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get([
             DB::raw('Date(created_at) as date'),
             DB::raw('COUNT(*) as "count"'),
          ]);

        $mark_tasks = \App\Models\Task::where('created_at', '>=', $one_week_ago)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get([
             DB::raw('Date(created_at) as date'),
             DB::raw('COUNT(*) as "count"'),
          ]);

        $followup = \App\Models\Lead::where('target_date', '>=', $one_week_ago)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get([
             DB::raw('Date(created_at) as date'),
             DB::raw('COUNT(*) as "count"'),
          ]);

        $converted = \App\Models\Lead::where('lead_type_id', '4')->where('updated_at', '>=', $one_week_ago)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get([
             DB::raw('Date(created_at) as date'),
             DB::raw('COUNT(*) as "count"'),
          ]);

        $phonelogs = \App\Models\Phonelogs::where('created_at', '>=', $one_week_ago)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get([
             DB::raw('Date(created_at) as date'),
             DB::raw('COUNT(*) as "count"'),
          ]);

        $proposals = \App\Models\Proposal::where('created_at', '>=', $one_week_ago)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get([
             DB::raw('Date(created_at) as date'),
             DB::raw('COUNT(*) as "count"'),
          ]);

        // FINANCE TRANSACTIONSTARTS

        $receipts = \App\Models\Entry::where('date', '>=', $one_week_ago)
                    ->where('entrytype_id', '1')
                    ->sum('cr_total');

        $payments = \App\Models\Entry::where('date', '>=', $one_week_ago)
                    ->where('entrytype_id', '2')
                    ->sum('cr_total');

        $quotes = \App\Models\Orders::where('bill_date', '>=', $one_week_ago)
                    ->sum('total_amount');

        $invoices = \App\Models\Invoice::where('bill_date', '>=', $one_week_ago)
                    ->sum('total_amount');

        $purchase = \App\Models\PurchaseOrder::where('ord_date', '>=', $one_week_ago)
                    ->sum('total');

        // CASES AND PROJECTS
        $ptasks = \App\Models\ProjectTask::where('created_at', '>=', $one_week_ago)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get([
             DB::raw('Date(created_at) as date'),
             DB::raw('COUNT(*) as "count"'),
          ]);

        $cases = \App\Models\Cases::where('created_at', '>=', $one_week_ago)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get([
             DB::raw('Date(created_at) as date'),
             DB::raw('COUNT(*) as "count"'),
          ]);

        //HUMAN RESOURCES

        $cvs = \App\Models\JobApplication::where('created_at', '>=', $one_week_ago)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get([
             DB::raw('Date(created_at) as date'),
             DB::raw('COUNT(*) as "count"'),
          ]);

        $events = \App\Models\Event::where('event_start_date', '>=', $one_week_ago)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get([
             DB::raw('Date(created_at) as date'),
             DB::raw('COUNT(*) as "count"'),
          ]);

        $training = \App\Models\Training::where('start_date', '>=', $one_week_ago)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get([
             DB::raw('Date(created_at) as date'),
             DB::raw('COUNT(*) as "count"'),
          ]);

        $leaves = \App\Models\LeaveApplication::where('leave_start_date', '>=', $one_week_ago)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get([
             DB::raw('Date(created_at) as date'),
             DB::raw('COUNT(*) as "count"'),
          ]);

        //send to env('REPORT_EMAIL')
        // $m is used within and $mailcontent is used in view file
        Mail::send('emails.businessereport',
                ['leads'      => $leads,
                'targets'     => $targets,
                'clients'     => $clients,
                'mark_tasks'  => $mark_tasks,
                'followup'    => $followup,
                'converted'   => $converted,
                'phonelogs'   => $phonelogs,
                'proposals'   => $proposals,
                'receipts'    => $receipts,
                'payments'    => $payments,
                'quotes'      => $quotes,
                'invoices'    => $invoices,
                'purchase'    => $purchase,
                'ptasks'      => $ptasks,
                'cases'       => $cases,
                'cvs'         => $cvs,
                'events'      => $events,
                'training'    => $training,
                'leaves'      => $leaves,

                ], function ($m) use ($leads, $targets, $clients, $mark_tasks, $followup, $phonelogs, $proposals,
                     $receipts, $payments, $quotes, $invoices,$purchase, $ptasks, $cases, $cvs, $events,
                     $training, $leaves

                ) {
                    $m->from('info@meronetwork.com', env('APP_COMPANY'));
                    $m->to(env('REPORT_EMAIL'), env('REPORT_EMAIL'))->subject(env('APP_COMPANY').' Business Report!');
                });
    }
}
