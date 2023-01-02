<?php

namespace App\Http\Controllers;

use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class TimeSheetController extends Controller
{
    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();

        $this->permission = $permission;
    }

    public function activityIndex(Request $request)
    {
        $page_title = 'Activity';

        $page_description = 'List All the Activities';

        $activities = \App\Models\Activity::orderBy('id', 'desc')->get();

        return view('admin.activity.index', compact('activities', 'page_title', 'page_description'));
    }

    public function activityCreate(Request $request)
    {
        $page_title = 'Create Activity';
        $page_description = 'Create  Activities';

        $projects = \App\Models\Projects::where('enabled', '1')->pluck('name', 'id')->all();

        //dd($projects);

        return view('admin.activity.create', compact('projects', 'page_title', 'page_description'));
    }

    public function activitySave(Request $request)
    {
        $attributes = $request->all();
        //  dd($attributes);
        \App\Models\Activity::create($attributes);

        Flash::success('Activity Created');

        return redirect('/admin/activity');
    }

    public function activityEdit(Request $request, $id)
    {

        //dd($id);
        $page_title = 'Edit Activity';
        $page_description = 'edit  Activities';
        $projects = \App\Models\Projects::where('enabled', '1')->pluck('name', 'id')->all();

        $activities = \App\Models\Activity::where('id', $id)->first();

        return view('admin.activity.edit', compact('projects', 'page_title', 'page_description', 'activities'));
    }

    public function activityUpdate(Request $request, $id)
    {
        $activity = \App\Models\Activity::find($id);

        $attributes = $request->all();

        $activity->update($attributes);

        Flash::success('Activity Updated Successfully');

        return redirect('/admin/activity');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroyActivity($id)
    {
        $activity = \App\Models\Activity::find($id);

        if (! $activity->isdeletable()) {
            abort(403);
        }
        $activity->delete();

        Flash::success('Activity successfully deleted.');

        return redirect()->back();
    }

    /**
     * Delete Confirm.
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalDeleteActivity($id)
    {
        $error = null;

        $activity = \App\Models\Activity::find($id);

        if (! $activity->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Activity';

        $activity = \App\Models\Activity::find($id);

        $modal_route = route('admin.activity.delete', ['id' => $activity->id]);
        $modal_body = 'Are you sure you want to delete this Activity?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function timesheetIndex(Request $request)
    {
        $page_title = 'TimeSheet';
        $page_description = 'List All the timesheet';

        $timesheets = \App\Models\TimeSheet::where('org_id',\Auth::user()->org_id)->orderBy('id', 'desc')->get();

        // $time_groups = DB::select("SELECT id, employee_id, date,
        //                                            time_from, time_to, ROUND(TIMESTAMPDIFF(MINUTE, time_from, time_to)/60, 2) as hours,
        //                                            comments, activity_id, date_submitted
        //                                            FROM timesheet
        //                                            order by employee_id");
        // $emp =  \App\Models\TimeSheet::select('employee_id')->distinct('employee_id')->get();
        // return ['d'=>count($emp)];
        $emp = \App\Models\TimeSheet::select('timesheet.employee_id')->leftjoin('users', 'timesheet.employee_id', '=', 'users.id')->where('timesheet.org_id',\Auth::user()->org_id)->where(function ($query) {
                if (\Request::get('term')) {
                    return $query->where('employee_id', \Request::get('term'))
                        ->orWhere('users.username', 'LIKE', '%'.\Request::get('term').'%');
                }
            })->groupBy('employee_id')->paginate(5);
        $data = [];
        foreach ($emp as $val) {
            $time_groups = \App\Models\TimeSheet::where('employee_id', $val->employee_id)->get();
            $data[] = $time_groups;
        }

        //dd($data);

        return view('admin.timesheet.index', compact('data', 'emp', 'page_title', 'page_description'));
    }

    public function timesheetCreate(Request $request)
    {
        $page_title = 'Create Timesheet';
        $page_description = 'Create  Timesheet';

        $users = \App\User::where('enabled', '1')->pluck('username', 'id')->all();
        $activity = \App\Models\Activity::pluck('code', 'id')->all();

        //dd($users);

        return view('admin.timesheet.create', compact('users', 'page_title', 'page_description', 'activity'));
    }

    public function timesheetSave(Request $request)
    {
        $attributes = $request->all();
        $attributes['org_id'] = \Auth::user()->org_id;
        // dd($attributes);
        \App\Models\TimeSheet::create($attributes);
        Flash::success('TimeSheet Created');

        return redirect('/admin/timesheet');
    }

    public function timesheetEdit(Request $request, $id)
    {
        $page_title = 'Edit TimeSheet';
        $page_description = 'edit  Timesheet';
        $users = \App\User::where('enabled', '1')->pluck('username', 'id')->all();
        $activity = \App\Models\Activity::pluck('code', 'id')->all();

        // dd($activity);
        $timesheet = \App\Models\TimeSheet::where('id', $id)->first();

        //dd($timesheet);

        return view('admin.timesheet.edit', compact('page_title', 'users', 'page_description', 'activity', 'timesheet'));
    }

    public function timesheetShow($employee_id)
    {
        $page_title = 'Admin | Timesheet | Show';
        $time_groups = \App\Models\TimeSheet::where('employee_id', $employee_id)
                            ->where(function ($query) {
                                if (\Request::get('start_date') && \Request::get('end_date')) {
                                    return $query->where('date', '>=', \Request::get('start_date'))
                                ->where('date', '<=', \Request::get('end_date'));
                                }
                            })->orderBy('id', 'desc')->get();

        return view('admin.timesheet.show', compact('page_description', 'page_title', 'time_groups'));
    }

    public function timesheetUpdate(Request $request, $id)
    {
        $timesheet = \App\Models\TimeSheet::find($id);
        $attributes = $request->all();
        $timesheet->update($attributes);
        Flash::success('TimeSheet Updated Successfully');

        return redirect('/admin/timesheet');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroyTimeSheet($id)
    {
        $timesheet = \App\Models\TimeSheet::find($id);

        if (! $timesheet->isdeletable()) {
            abort(403);
        }
        $timesheet->delete();

        Flash::success('Timesheet successfully deleted.');

        return redirect()->back();
    }

    /**
     * Delete Confirm.
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalDeleteTimeSheet($id)
    {
        $error = null;

        $timesheet = \App\Models\TimeSheet::find($id);

        if (! $timesheet->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete TimeSheet';

        $timesheet = \App\Models\TimeSheet::find($id);

        $modal_route = route('admin.timesheet.delete', ['id' => $timesheet->id]);
        $modal_body = 'Are you sure you want to delete this TimeSheet?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function costcenterIndex(Request $request)
    {
        $page_title = 'Cost Center';
        $page_description = 'List All the costcenter';

        $costcenter = \App\Models\CostCenter::orderBy('id', 'desc')->get();

        // dd($timesheets);

        return view('admin.costcenter.index', compact('costcenter', 'page_title', 'page_description'));
    }

    public function costcenterCreate(Request $request)
    {
        $page_title = 'Create Cost Center';
        $page_description = 'Create  cost center';
        $owners = \App\User::where('enabled', '1')->pluck('username', 'id')->all();

        return view('admin.costcenter.create', compact('owners', 'page_title', 'page_description'));
    }

    public function costcenterSave(Request $request)
    {
        $attributes = $request->all();

        \App\Models\CostCenter::create($attributes);

        Flash::success('Cost Center Created');

        return redirect('/admin/costcenter');
    }

    public function costcenterEdit(Request $request, $id)
    {
        $page_title = 'Edit Cost Center';
        $page_description = 'edit  cost center';
        $owners = \App\User::where('enabled', '1')->pluck('username', 'id')->all();

        $costcenter = \App\Models\CostCenter::where('id', $id)->first();

        return view('admin.costcenter.edit', compact('page_title', 'owners', 'page_description', 'costcenter'));
    }

    public function costcenterUpdate(Request $request, $id)
    {
        $costcenter = \App\Models\CostCenter::find($id);
        $attributes = $request->all();
        $costcenter->update($attributes);
        Flash::success('Cost Center Updated Successfully');

        return redirect('/admin/costcenter');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroyCostCenter($id)
    {
        $costcenter = \App\Models\CostCenter::find($id);
        if (! $costcenter->isdeletable()) {
            abort(403);
        }
        $costcenter->delete();
        Flash::success('CostCenter successfully deleted.');

        return redirect()->back();
    }

    /**
     * Delete Confirm.
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalDeleteCostCenter($id)
    {
        $error = null;
        $costcenter = \App\Models\CostCenter::find($id);

        if (! $costcenter->isdeletable()) {
            abort(403);
        }
        $modal_title = 'Delete CostCenter';

        $costcenter = \App\Models\CostCenter::find($id);

        $modal_route = route('admin.costcenter.delete', ['id' => $costcenter->id]);
        $modal_body = 'Are you sure you want to delete this CostCenter?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function bulkindex()
    {
        $page_title = 'Admin | Timesheet';
        $projects = \App\Models\Projects::all();

        return view('admin.timesheet.bulkcreate', compact('projects', 'page_title'));
    }

    public function bulkcreate(Request $request)
    {
        $page_title = 'Admin | Timesheet';
        $projects = \App\Models\Projects::all();
        $project_id = $request->project_id;
        $users = \App\User::where('project_id', $project_id)->orderBy('username', 'ASC')->where('enabled', '1')->get();
        $activity = \App\Models\Activity::pluck('code', 'id')->all();

        return view('admin.timesheet.bulkcreate', compact('projects', 'users', 'project_id', 'activity', 'page_title'));
    }

    public function bulkstore(Request $request)
    {
        foreach ($request->employee_id as $key => $value) {
            $attributes[] = ['employee_id'=>$value, 'date'=>($request->date)[$key], 'time_from'=>($request->time_from)[$key], 'time_to'=>($request->time_to)[$key], 'activity_id'=>($request->activity_id)[$key], 'date_submitted'=>$request->date_submitted, 'comments'=>$request->comments];
        }
        \App\Models\TimeSheet::insert($attributes);
        Flash::success('TimeSheet Created');

        return redirect('/admin/timesheet');
    }

    public function attendanceReport()
    {
        $page_title = 'Admin | Timesheet';
        $projects = \App\Models\Projects::all();

        return view('admin.timesheet.attendence_report', compact('projects', compact('page_title')));
    }

    public function attendanceReportDetails(Request $request)
    {
        $page_title = 'Admin | Timesheet';
        $projects = \App\Models\Projects::all();
        $users = \App\User::select('id', 'username')->where('project_id', $request->project_id)->where('enabled', '1')->orderBy('username', 'ASC')->get();
        $date_start = $request->date_in.'-01';
        $totaldays = \Carbon\Carbon::parse($date_start)->daysInMonth;
        $date_end = $request->date_in.'-'.$totaldays;
        $record = [];
        $date_in = $request->date_in;
        $project_id = $request->project_id;
        foreach ($users as $key => $value) {
            $record[$value->username] = \App\Models\TimeSheet::where('employee_id', $value->id)->where('date', '>=', $date_start)->where('date', '<=', $date_end)->get();
        }

        return view('admin.timesheet.attendence_report', compact('record', 'date_in', 'projects', 'project_id', 'page_title'));
    }
}
