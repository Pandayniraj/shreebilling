<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Cases;
use App\Models\MasterComments;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\ProjectUser;
use App\Models\Stock;
use App\Models\StockAssign;
use DB;
use Illuminate\Http\Request;

class ProjectboardController extends Controller
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

    public function lineChart($start = '', $end = '', $course_id = '', $year = '')
    {

        // Get the number of days to show data for, with a default of 30
        $days = \Request::get('days', 30);
        $range = \Carbon\Carbon::now()->subDays($days);
        $views = DB::table('project_tasks')
                   ->where('created_at', '>=', $range)
                   ->where('org_id', $this->org_id)
                   ->groupBy('date')
                   ->orderBy('date', 'DESC')
                   ->get([
                     DB::raw('Date(created_at) as date'),
                     DB::raw('COUNT(*) as value'),
                   ]);

        $labels = [];
        $viewDataset = [];
        foreach ($views as $view) {
            $old_date = $view->date;
            $old_date_timestamp = strtotime($old_date);
            $formatted_date = date('M d, Y', $old_date_timestamp);

            array_push($labels, $formatted_date);
            array_push($viewDataset, $view->value);
        }

        $viewData = ['labels'=>$labels, 'datasets'=> [
            ['fillColor'=> 'rgba(231,35,55,0.5)',
                'data'=>$viewDataset, ],
        ]];

        //dd($viewData);
        return json_encode($viewData);
    }

    public function index()
    {
        $page_title = 'Project Dashboard';
        $page_description = 'This is the project dashboard';

        $line = $this->lineChart();

        $taskByProject = DB::select("SELECT COUNT(*) AS `num`, projects.name FROM `projects` JOIN project_tasks ON project_tasks.project_id=projects.id where   projects.org_id = '$this->org_id' AND project_tasks.created_at >= '".date('Y')."-01-01 00:01' GROUP BY `name` ORDER BY num DESC");

        $taskByProjectData = [];
        foreach ($taskByProject as $projectview) {
            array_push($taskByProjectData,
            [
              'name'=>$projectview->name,
              'y'=>$projectview->num,
            ]
          );
        }
        $taskByProjectData = json_encode($taskByProjectData);

        $taskByStatus = DB::select("SELECT COUNT(*) AS `num`, project_tasks.status FROM `project_tasks` where project_tasks.org_id = '$this->org_id' AND project_tasks.created_at >= '".date('Y')."-01-01 00:01' GROUP BY `status` ORDER BY num DESC");

        $taskByStatusData = [];
        foreach ($taskByStatus as $statusview) {
            array_push($taskByStatusData,
            [
              'name'=>ucfirst($statusview->status),
              'y'=>$statusview->num,
            ]
          );
        }
        $taskByStatusData = json_encode($taskByStatusData);

        $taskByUser = DB::select("SELECT COUNT(*) AS `num`, users.first_name FROM `users` JOIN project_task_user ON project_task_user.user_id=users.id where users.org_id = '$this->org_id' AND  project_task_user.created_at >= '".date('Y')."-01-01 00:01' GROUP BY `first_name` ORDER BY num DESC");

        $taskByUserData = [];
        foreach ($taskByUser as $userview) {
            array_push($taskByUserData,
            [
              'name'=>$userview->first_name,
              'y'=>$userview->num,
            ]
          );
        }
        $taskByUserData = json_encode($taskByUserData);

        $comments = MasterComments::orderBy('id', 'desc')->take(10)->get();
        $cases = Cases::orderBy('id', 'desc')->where('org_id', $this->org_id)->take(5)->get();
        $inventory_in = Stock::orderBy('created_at', 'desc')->take(5)->get();
        //    dd($inventor_in);
        $inventory_out = StockAssign::orderBy('created_at', 'desc')->take(5)->get();
        // dd($inventory_out);

        return view('projectboard', compact('page_title', 'page_description', 'line', 'comments', 'cases', 'inventory_in', 'inventory_out', 'taskByProjectData', 'taskByStatusData', 'taskByUserData'));
    }
}
