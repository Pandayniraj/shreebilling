<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Audit as Audit;
use App\Models\LeadTransfer;
use App\Models\Organization;
use App\Models\Query;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * @var Audit
     */
    protected $audit;

    /**
     * @var App
     */
    protected $app;

    protected $context;

    protected $context_help_area;

    public function __construct()
    {
        /*$this->audit = $audit;
        $this->global_audits = $this->audit->pushCriteria(new AuditByCreatedDateDescending())->paginate(10);
        View::share('global_audits', $this->global_audits);*/
        //dd(\Carbon\Carbon::now()->addDays(30));
        $this->due_marketing_tasks = null;
        $this->not_viewed_leads = null;
        $this->not_received_emi = null;
        $this->not_viewed_cases = null;
        $this->attendance_log = null;
        $this->org = null;
        $this->announce = null;
        $this->transfer = null;
        $this->next_action_query  = null;
        if (\Auth::check()) {
            if (\Cache::has('due_marketing_tasks')) {
                $this->due_marketing_tasks = \Cache::get('due_marketing_tasks');
            } else {
                $this->due_marketing_tasks = \Cache::remember('due_marketing_tasks', 2, function () {
                    return \App\Models\Task
                        ::whereIn('task_assign_to', [\Auth::user()->id])
                        ->where('enabled', '1')
                        ->where('task_status', '!=', 'Completed')
                        ->whereBetween('task_due_date', [\Carbon\Carbon::yesterday(), \Carbon\Carbon::now()->addDays(30)])
                        ->orderBy('id', 'DESC')
                        ->take(25)->get();
                });
            }
            if (\Cache::has('not_viewed_leads')) {
                $this->not_viewed_leads = \Cache::get('not_viewed_leads');
            } else {
                $this->not_viewed_leads = \Cache::remember('not_viewed_leads', 2, function () {
                    return         \App\Models\Lead::where('viewed', '0')
                        ->where('rating', 'active')
                        ->where('enabled', '1')
                        ->where('org_id', \Auth::user()->org_id)
                        ->orderBy('id', 'desc')->take(100)->get();
                });
            }
            
            if (\Cache::has('not_received_emi')) {
                $this->not_received_emi = \Cache::get('not_received_emi');
            } else {
                $this->not_received_emi = \Cache::remember('not_received_emi', 2, function () {
                    return    \App\Models\OrderPaymentTerms::where('term_date','<', date('Y-m-d'))
                        ->orderBy('id', 'desc')->take(100)->get();
                });
            }
            if (\Cache::has('not_viewed_cases')) {
                $this->not_viewed_cases = \Cache::get('not_viewed_cases');
            } else {
                $this->not_viewed_cases = \Cache::remember('not_viewed_cases', 2, function () {
                    return \App\Models\Cases::where('status', '!=', 'closed')
                        ->where('org_id', \Auth::user()->org_id)
                        ->where('enabled', '1')
                        ->orderBy('id', 'desc')->take(100)->get();
                });
            }

            // $this->attendance_log = Attendance::where('user_id', \Auth::user()->id)->where('clocking_status', '1')->first();

            $this->announce = Announcement::orderBy('announcements_id', 'DESC')->where('org_id', \Auth::user()->org_id)
                ->where('status', 'published')
                ->first();
            // dd($this->announce);

            $this->transfer = LeadTransfer::orderBy('id', 'DESC')
                ->where('to_user_id', \Auth::user()->id)
                ->where('notify', '0')
                ->get();

            $this->next_action_query = Query::orderBy('id', 'DESC')
                ->where('user_id', \Auth::user()->id)
                ->where('next_action_date', \Carbon\Carbon::today())
                ->where('notify_next_action', '0')
                ->get();

            $unseen_message = \DB::table('messages')
                                        ->where('messages.is_seen', '!=', '1')
                                        ->where('messages.user_id', '!=', \Auth::user()->id)
                ->leftjoin('conversations', 'messages.conversation_id', '=', 'conversations.id')
                ->where(function ($query) {
                    return  $query->where('conversations.user_one', \Auth::user()->id)
                                  ->orWhere('conversations.user_two', \Auth::user()->id);
                })
                ->groupBy('messages.conversation_id')
                ->get();

            $this->unseen_message = count($unseen_message);

            \View::share('total_unseen_message', $this->unseen_message);
            $this->org = Organization::where('id',\Auth::user()->org_id)->first();
   
        }else{
            $this->org = Organization::find($id = 1);
        }

        // $this->all_projects = Projects::where('enabled', 1)->where('org_id', \Auth::user()->org_id)->lists('name', 'id');
        

        \View::share('due_marketing_tasks', $this->due_marketing_tasks);
        \View::share('not_viewed_leads', $this->not_viewed_leads);
        \View::share('not_received_emi', $this->not_received_emi);
        \View::share('not_viewed_cases', $this->not_viewed_cases);
        // \View::share('all_projects', $this->all_projects);

        // \View::share('attendance_log', $this->attendance_log);

        \View::share('organization', $this->org);

        \View::share('announce', $this->announce);

        \View::share('transfer', $this->transfer);

        \View::share('next_action_query', $this->next_action_query);
    }
}
