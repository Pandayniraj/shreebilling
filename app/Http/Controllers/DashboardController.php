<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    private $org_id;

    public function __construct()
    {
        parent::__construct();
        // Protect all dashboard routes. Users must be authenticated.
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->org_id = \Auth::user()->org_id;

            return $next($request);
        });
    }

    public function salesboard()
    {
        $years = DB::table('leads')->select(DB::raw('YEAR(leads.created_at) as date'))
                            ->where('org_id', $this->org_id)
                           ->groupBy('date')
                           ->orderBy('date', 'ASC')
                           ->get();

        $communications = DB::table('lead_communications')
                            ->select(DB::raw('count(*) as num, lead_communications.name,lead_communications.id'))
                            ->where('org_id', $this->org_id)
                            ->join('leads', 'leads.communication_id', '=', 'lead_communications.id');

        $leadcourse = DB::table('products')
                            ->where('products.org_id', \Auth::user()->org_id)
                            ->select(DB::raw('count(*) as num, products.name,products.id'))
                            ->join('leads', 'leads.product_id', '=', 'products.id');

        $byStatus = DB::table('lead_status')
                            ->select(DB::raw('count(*) as num, lead_status.name,lead_status.id'))
                            ->where('org_id', $this->org_id)
                            ->join('leads', 'leads.status_id', '=', 'lead_status.id');

        $byRating = DB::table('leads')->select(DB::raw('count(*) as num, leads.rating'))->where('org_id', $this->org_id);

        $byCity = DB::table('leads')->select(DB::raw('count(*) as num, leads.city'))->where('org_id', $this->org_id);

        $top_poster = DB::table('users')
                      ->select(DB::raw('count(leads.id) as total, users.first_name as name'))

                      ->join('leads', 'users.id', '=', 'leads.user_id');

        $line = DB::table('leads')->where('org_id', $this->org_id);

        if (\Request::get('product_id') != '0' && \Request::get('product_id') != '') {
            $product_id = \Request::get('product_id');
            $communications->where('leads.product_id', $product_id);
            $leadcourse->where('leads.product_id', $product_id);
            $byStatus->where('leads.product_id', $product_id);
            $byRating->where('leads.product_id', $product_id);
            $byCity->where('leads.product_id', $product_id);
            $top_poster->where('leads.product_id', $product_id);

            $line->where('product_id', $product_id);
        }
        if (\Request::get('status_id') != '0' && \Request::get('status_id') != '') {
            $status_id = \Request::get('status_id');
            $communications->where('leads.status_id', $status_id);
            $leadcourse->where('leads.status_id', $status_id);
            $byStatus->where('leads.status_id', $status_id);
            $byRating->where('leads.status_id', $status_id);
            $byCity->where('leads.status_id', $status_id);
            $top_poster->where('leads.status_id', $status_id);

            $line->where('status_id', $status_id);
        }
        if (\Request::get('years') != '0' && \Request::get('years') != '') {
            $get_year = \Request::get('years');
            $communications->whereBetween('leads.created_at', [$get_year.'-01-01 00:01', $get_year.'-12-12 23:59']);
            $leadcourse->whereBetween('leads.created_at', [$get_year.'-01-01 00:01', $get_year.'-12-12 23:59']);
            $byStatus->whereBetween('leads.created_at', [$get_year.'-01-01 00:01', $get_year.'-12-12 23:59']);
            $byRating->whereBetween('leads.created_at', [$get_year.'-01-01 00:01', $get_year.'-12-12 23:59']);
            $byCity->whereBetween('leads.created_at', [$get_year.'-01-01 00:01', $get_year.'-12-12 23:59']);
            $top_poster->whereBetween('leads.created_at', [$get_year.'-01-01 00:01', $get_year.'-12-12 23:59']);

            $line->where('created_at', '>=', $get_year.'-01-01 00:01')
                    ->where('created_at', '<=', $get_year.'-12-30 23:59');
        } elseif (\Request::get('start_date') && \Request::get('end_date')) {
            $start = \Request::get('start_date');
            $end = \Request::get('end_date');

            $communications->whereBetween('leads.created_at', [$start, $end]);
            $leadcourse->whereBetween('leads.created_at', [$start, $end]);
            $byStatus->whereBetween('leads.created_at', [$start, $end]);
            $byRating->whereBetween('leads.created_at', [$start, $end]);
            $byCity->whereBetween('leads.created_at', [$start, $end]);
            $top_poster->whereBetween('leads.created_at', [$start, $end]);

            $line->where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end);
        } else {
            //$newDate = date('Y')."-01-01 00:01";
            $days = \Request::get('days', 30);
            $range = \Carbon\Carbon::now()->subDays($days);

            $communications->where('leads.created_at', '>=', $range);
            $leadcourse->where('leads.created_at', '>=', $range);
            $byStatus->where('leads.created_at', '>=', $range);
            $byRating->where('leads.created_at', '>=', $range);
            $byCity->where('leads.created_at', '>=', $range);
            $top_poster->where('leads.created_at', '>=', $range);

            $line->where('created_at', '>=', $range);
        }

        $communications = $communications->groupBy('name')->get();
        $leadcourse = $leadcourse->groupBy('name')->get();
        $byStatus = $byStatus->groupBy('name')->orderBy('num', 'desc')->get();
        $byRating = $byRating->groupBy('rating')->get();
        $byCity = $byCity->groupBy('city')->get();

        $top_poster = $top_poster->groupBy('users.id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $line->groupBy('target_date')
               ->orderBy('target_date', 'ASC')
               ->get([
                 DB::raw('Date(created_at) as date'),
                 DB::raw('COUNT(*) as value'),
               ]);

        $line = $line->get();

        $labels = [];
        $viewDataset = [];
        foreach ($line as $view) {

            $old_date = $view->date ?? null;
            $old_date_timestamp = strtotime($old_date);
            $formatted_date = date('M d, Y', $old_date_timestamp);

            array_push($labels, $formatted_date);
            array_push($viewDataset, $view->value ?? null);
        }

        $viewData = ['labels'=>$labels, 'datasets'=> [
            ['fillColor'=> 'rgba(231,35,55,0.5)',
                'data'=>$viewDataset, ],
        ]];
        $line = json_encode($viewData);

        $courses = \App\Models\Product::where('products.org_id', \Auth::user()->org_id)->where('enabled', '1')->pluck('name', 'id')->all();
        $lead_status = \App\Models\Leadstatus::where('enabled', '1')->pluck('name', 'id')->all();

        $communicationsData = [];
        foreach ($communications as $comview) {
            array_push($communicationsData,
                    [
                      'name'=>$comview->name,
                      'y'=>$comview->num,
                      'url'=>'/admin/leads?product_id=&source_id='.$comview->id.'&user_id=&rating=&enq_mode=undefined&start_date=&end_date=&status_id=&type=leads',
                      ]
          );
        }
        $communicationsData = json_encode($communicationsData);
        //dd($communicationsData);

        $leadcourseData = [];
        foreach ($leadcourse as $courseview) {
            array_push($leadcourseData,
                    [
                      'name'=>$courseview->name,
                      'cid'=>$courseview->id,
                      'y'=>$courseview->num,
                      'url'=>'/admin/leads?product_id='.$courseview->id.'&user_id=&rating=&enq_mode=undefined&start_date=&end_date=&status_id=&type=leads',
                      ]
          );
        }
        $leadcourseData = json_encode($leadcourseData);

        $byStatusData = [];
        foreach ($byStatus as $byStatusView) {
            array_push($byStatusData,
                    [
                      'name'=>$byStatusView->name,
                      'sid'=>$courseview->id,
                      'y'=>$byStatusView->num,
                      'url'=>'/admin/leads?product_id=&user_id=&rating=&enq_mode=undefined&start_date=&end_date=&status_id='.$byStatusView->id.'&type=leads',
                      ]
          );
        }
        $byStatusData = json_encode($byStatusData);

        $byRatingData = [];
        foreach ($byRating as $byRatingView) {
            array_push($byRatingData,
                    [
                      'name'=> ucfirst($byRatingView->rating),
                      'y'=>$byRatingView->num,
                       'url'=>'/admin/leads?product_id=&user_id=&rating='.$byRatingView->rating.'&enq_mode=undefined&start_date=&end_date=&status_id=&type=leads',
                      ]
          );
        }
        $byRatingData = json_encode($byRatingData);

        $byCityData = [];
        foreach ($byCity as $byCityView) {
            array_push($byCityData,
                    [
                      'name'=> ucfirst($byCityView->city),
                      'y'=>$byCityView->num,
                    ]
          );
        }
        $byCityData = json_encode($byCityData);

        $page_title = 'Sales Dashboard';
        $page_description = 'This is a marketing dashboard';

        return view('salesboard', compact('page_title', 'page_description', 'line', 'communicationsData', 'leadcourseData', 'byStatusData', 'byRatingData', 'byCityData', 'top_poster', 'courses', 'lead_status', 'years'));
    }

    // This is the function run everyminute to check the due task at current time everyminute
    public function dueNow()
    {
        $dueNow = \App\Models\Task::where('task_assign_to', \Auth::user()->id)->where('enabled', '1')->where('task_alert', '1')->where('task_due_date', '=', date('Y-m-d H:i:00'))->where('org_id', $this->org_id)->get();

        $modalData = '';

        $modalData .= '<table class="table table-hover table-bordered" id="dueNowTable">
                            <thead>
                                <tr style="background:#ccc;">
                                    <th>'.trans('admin/tasks/general.columns.lead').'</th>
                                    <th>'.trans('admin/tasks/general.columns.task_subject').'</th>
                                    <th>'.trans('admin/tasks/general.columns.task_status').'</th>
                                    <th>'.trans('admin/tasks/general.columns.task_owner').'</th>
                                    <th>'.trans('admin/tasks/general.columns.assigned_to').'</th>
                                    <th>'.trans('admin/tasks/general.columns.task_priority').'</th>
                                    <th>'.trans('admin/tasks/general.columns.task_start_date').'</th>
                                    <th>'.trans('admin/tasks/general.columns.task_due_date').'</th>
                                    <th>'.trans('admin/tasks/general.columns.task_complete_percent').'</th>
                                    <th>'.trans('admin/tasks/general.columns.task_enable').'</th>
                                    <th>'.trans('admin/tasks/general.columns.date').'</th>
                                    <th>'.trans('admin/tasks/general.columns.actions').'</th>
                                </tr>';

        if (count($dueNow) > 0) {
            foreach ($dueNow as $k => $v) :
                $modalData .= '<tr>';
            $modalData .= '<td><strong>'.\link_to_route('admin.leads.show', $v->lead->name, [$v->lead_id], []).'</strong></td>';
            $modalData .= '<td>'.$v->task_subject.'</td>';
            $modalData .= '<td>'.$v->task_status.'</td>';
            $modalData .= '<td>'.$v->owner->first_name.'</td>';
            $modalData .= '<td>'.$v->assigned_to->first_name.'</td>';
            $modalData .= '<td>'.$v->task_priority.'</td>';
            $modalData .= '<td>'.date('Y-m-d', strtotime($v->task_start_date)).'</td>';
            $modalData .= '<td><span style="color:red;">'.date('Y-m-d', strtotime($v->task_due_date)).'</span></td>';
            $modalData .= '<td>'.$v->task_complete_percent.'</td>';
            $modalData .= '<td>'.$v->enabled.'</td>';
            $modalData .= '<td>'.date('Y-m-d', strtotime($v->created_at)).'</td>';

            if ($v->isEditable()) {
                $modalData .= '<td><a href="'.route('admin.tasks.show', $v->id).'" title="'.trans('general.button.edit').'"> <i class="fas fa-edit"></i> </a></td>';
            } else {
                $modalData .= '<td><i class="fas fa-edit text-muted" title="'.trans('admin/tasks/general.error.cant-edit-this-lead').'"></i></td>';
            }
            $modalData .= '</tr>';
            endforeach;
        } else {
            $modalData .= '<tr><td colspan="12" style="text-align:center;"><h3>No any due task for now.</h3></td></tr>';
        }

        $modalData .= '</thead></table>';

        return ['total' => count($dueNow), 'modalData' => $modalData];
    }
}
