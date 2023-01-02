<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\LeaveApplication as LeaveAppRep;
use App\Models\LeaveCategory;
use App\Models\Role as Permission;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class LeaveManagementController extends Controller
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $client
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(LeaveAppRep $leaveAppRep, Permission $permission)
    {
        parent::__construct();
        $this->leaveAppRep = $leaveAppRep;
        $this->permission = $permission;
    }

    public function index()
    {
        $page_title = 'Leave';
        $page_description = 'Leave Lists';
        $page_status = 'add';

        $myleaves = LeaveApplication::where('user_id', Auth::user()->id)->orderBy('leave_start_date', 'desc')->get();
       
        // $allleaves = LeaveApplication::orderBy('leave_start_date', 'desc')->get();



        $categories = LeaveCategory::get(); // check time off 

        $allLeaveCategory = LeaveCategory::where(function($query){
            if(!\Auth::user()->hasRole('admins')){

                $checktimeoff = \TaskHelper::checkTimeOffMonthly(Auth::user()->id);

                if($checktimeoff >= env('MAX_TIME_OFF',120)){
                    
                    return $query->where('leave_category_id','!=',env('TIME_OFF_ID'));
                }

            }
          

            })->get(); // check time off 

     
        // foreach($dynamicLeave as $key=> $cv){

        //     dd(Auth::user()[$cv['field']]);
        // }

      //\  dd(\Auth::user()['sick_accrued']);

        $users = User::select('first_name', 'last_name', 'id', 'email', 'phone', 'username')->where('enabled', '1')->get();

        $request_to = \App\Models\Team::where('org_id', \Auth::user()->org_id)
                        ->select('name as username', 'id')->get();
        // dd($request_to);
        $department = \App\Models\Department::all();


        $monthly_leave = [ 1, 8, 7 ];


        $monthly_day_leave = [1,7];



        
        return view('admin.leave_mgmt.index', compact('page_title', 'page_description', 'page_status', 'myleaves',  'categories', 'users', 'request_to', 'department','monthly_leave','monthly_day_leave','allLeaveCategory'));
    }

    public function store(Request $request)
    {
        //dd(Request::all());
        $attributes = $request->all();


        $attributes['user_id'] = isset($request->user_id) ? $request->user_id : \Auth::user()->id;
        
        $attributes['request_to'] = \Auth::user()->first_line_manager;

        if(!env('TIME_OFF_ID')){

            Flash::warning("Please contact administration to set Timeoff");
            return redirect()->back();
        }

        if($request->leave_category_id == env('TIME_OFF_ID')){

            $attributes['leave_end_date'] = $attributes['leave_start_date'];
            if(strtotime($request->time_off_start) > strtotime($request->time_off_end)){
                Flash::error("Plese enter valid start time & end time");
                return redirect()->back();   
            } 
        }
        
        $attributes['attachment'] = '';
        $stamp = time();

        $user_detail = \App\User::find($request->user_id);

        $files = $request->file('upload_file');
        $destinationPath = public_path().'/leave_files/';

        $request_to_email = \App\User::find($request->request_to)->email;

        if ($files) {
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $file->move($destinationPath, $stamp.'_'.$filename);
                if ($attributes['attachment'] == '') {
                    $attributes['attachment'] = $stamp.'_'.$filename;
                } else {
                    $attributes['attachment'] = $attributes['attachment'].','.$stamp.'_'.$filename;
                }
            }
        }
        $attributes['leave_days'] = \TaskHelper::findDays($request->leave_start_date, $request->leave_end_date);
        $this->leaveAppRep->create($attributes);

        try {
            Mail::send('emails.email-leaverequest', compact('tequest_to'), function ($message) use ($attributes, $request_to_email, $request) {
                $message->subject('New Leave has been Request');
                $message->from(env('APP_EMAIL'), env('APP_COMPANY'));
                $message->to($request_to_email);
                $message->cc(env('REPORT_EMAIL'));
            });

            Flash::success('Email has been sent successfully.');
        } catch (\Exception $e) {
            Flash::error('Error in sending mails : Invaild Email');
        }

        return Redirect::back();
    }

    public function destroy($id)
    {
        $application = LeaveApplication::where('leave_application_id', $id)->first();
        if (! $application->isDeletable()) {
            Flash::warning('Sorry! You do not have enough privilege to delete this.');

            return Redirect::back();
        }

        $attachment = $application->attachment;
        LeaveApplication::where('leave_application_id', $id)->delete();

        if ($attachment != '' && $attachment != null) {
            foreach (explode(',', $attachment) as $lfv) {
                if (File::exists('leave_files/'.$lfv)) {
                    File::Delete('leave_files/'.$lfv);
                }
            }
        }

        Flash::success('Leave has been deleted successfully.');

        return Redirect::back();
    }

    public function edit($id)
    {
        $leaveApp = LeaveApplication::where('leave_application_id', $id)->first();
        
        //dd("HGG");
        if (! $leaveApp || ! $leaveApp->isEditable()) {
            Flash::warning('Sorry! You do not have enough privilege to edit this.');
            abort(403);
        } elseif (! Auth::user()->hasRole('admins')) {
            $authorizeTeam = $empRequest->request_team;
            $authorize = \App\Models\UserTeam::where('team_id', $leaveApp->request_to)
                        ->where('user_id', \Auth::user()->id)
                        ->first();
            if (! $authorize) {
                Flash::warning('Sorry! You do not have enough privilege to edit this.');

                return redirect()->back();
            }
        }

        $page_title = 'Leave';
        $page_description = 'Leave Lists';
        $page_status = 'edit';

        $myleaves = LeaveApplication::where('user_id', Auth::user()->id)->orderBy('leave_start_date', 'desc')->get();

        $allleaves = LeaveApplication::orderBy('leave_start_date', 'desc')->get();

        $categories = LeaveCategory::get();
        $users = User::select('first_name', 'last_name', 'id', 'email', 'phone', 'username')->where('enabled', '1')->get();

        return view('admin.leave_mgmt.index', compact('leaveApp', 'page_title', 'page_description', 'page_status', 'myleaves', 'allleaves', 'categories', 'users'));
    }

    public function changeStatus($applId, $status)
    {
        $leaveApp = LeaveApplication::where('leave_application_id', $applId)->first();
        if ($status == 1) {
            $status_det = 'Pending';
        } elseif ($status == 2) {
            $status_det = 'Approved';
        } else {
            $status_det = 'Rejected';
        }
        if (! $leaveApp || ! $leaveApp->isEditable()) {
            $data = '<div class="panel panel-custom">
                        <div class="panel-heading">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                    class="sr-only">Close</span></button>
                                    <h4 class="modal-title"
                                id="myModalLabel">Change Status To  '.$status_det.'</h4>
                        </div>
                        <div class="modal-body wrap-modal wrap">
                            <p class="alert alert-warging">Sorry! You do not have enough privilege to edit the status.</p>
                            <br/>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>';
        } else {
            $data = '<div class="panel panel-custom">
                    <div class="panel-heading">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                class="sr-only">Close</span></button>
                                <h4 class="modal-title"
                            id="myModalLabel">Change Status To  '.$status_det.'</h4>
                    </div>
                    <div class="modal-body wrap-modal wrap">
                        <form id="form_validation"
                              action="/admin/leave_management/set_action/'.$applId.'/'.$status.'"
                              method="post" class="form-horizontal form-groups-bordered">
                              <input type="hidden" name="_token" id="_token" value="">
                            <div class="form-group ">
                                <label for="field-1" class="col-sm-3 control-label row">Give Comment: </label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="comment">'.$leaveApp->comments.'</textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>';
        }

        return ['data' => $data];
    }

    public function setStatus($applId, $status, Request $request)
    {
        $leaveApp = LeaveApplication::where('leave_application_id', $applId)->first();
        
        $getLeaveCatInfo = $leaveApp->category;


        // dd("STOP");
       
        if (! $leaveApp || ! $leaveApp->isEditable()) {
            Flash::warning('Sorry! You do not have enough privilege to change the status of this.');
            abort(403);
        }

        $calculateLeaveDays = function() use( $leaveApp ){
            $leaveDays = $leaveApp->leave_days;

            if($leaveApp->part_of_day > 1){

                $leaveDays = $leaveDays / 2;

            }

            return $leaveDays;
        };

        if($status == 2){

            if($getLeaveCatInfo->leave_code == env('SICK_LEAVE_CODE','SKL')){

                $user = \App\User::find($leaveApp->user_id);
                
                
                $leaveAmount = $user->sick_accrued - $calculateLeaveDays();

                $user->safeUpdate([

                    'sick_accrued' => $leaveAmount,

                ]);

            }
            if($getLeaveCatInfo->leave_code == env('EARNED_LEAVE_CODE','ERL')){

                $user = \App\User::find($leaveApp->user_id);
                
                
                $leaveAmount = $user->earned_accrued - $calculateLeaveDays();

                $user->safeUpdate([

                    'earned_accrued' => $leaveAmount,

                ]);

            }

        }
  
        
        LeaveApplication::where('leave_application_id', $applId)->update(['application_status' => $status, 'comments' => $request->comment, 'approve_by' => Auth::user()->id]);
        
        Flash::success('Leave Status has been updated.');

        return redirect('/admin/leave_management/detail/'.$applId);
    }

    public function checkAvailableLeave()
    {
        $user_id = \Request::get('user_id');
        $start_date = \Request::get('start_date');
        $end_date = \Request::get('end_date');


        $leave_category_id = \Request::get('leave_category_id');




        if($leave_category_id == env('TIME_OFF_ID')){
            $end_date = $start_date;
            $start_time = \Request::get('start_time');
            $end_time = \Request::get('end_time');

            $to_time = strtotime($start_time);
            
            $from_time = strtotime($end_time);
            if($to_time > $from_time){

                 return ['status' => 1, 'msg' => 'Start time cannot be greater'];

            }


            $tminutes = round(abs($to_time - $from_time) / 60,2);
            if($tminutes  > 120){


                return ['status' => 1, 'msg' => 'Time exceeded'];


            }

            $checktimeoff = \TaskHelper::checkTimeOffMonthly($user_id) + $tminutes;

            if($checktimeoff >= env('MAX_TIME_OFF',120)){
                
                return ['status' => 1, 'msg' =>'Time Off exceeded'];
            }

            return ['status' => 1, 'msg' => ''];

           

        }


        $days = \TaskHelper::findDays($start_date, $end_date);




        $category = LeaveCategory::select('leave_category_id', 'leave_category', 'leave_quota','leave_code')->where('leave_category_id', $leave_category_id)->first();
        //$userLeaves = LeaveApplication::select(DB::raw('sum(leave_days) as total'))->where('user_id', $user_id)->where('leave_category_id', $leave_category_id)->where('application_status', '2')->first();
        $userLeaves = \TaskHelper::userLeave($user_id, $leave_category_id, date('Y'));
        $leaveRemain = $category->leave_quota - $userLeaves;

        if($category->leave_code == env('SICK_LEAVE_CODE','SKL')){

            $leaveRemain = \App\User::find($user_id)->sick_accrued;

        }elseif($category->leave_code  == env('EARNED_LEAVE_CODE','ERL')){

            $leaveRemain = \TaskHelper::countEarnedLeave($user_id);
        }
    
        if ($days > $leaveRemain) {
            return ['status' => 1, 'msg' => 'Sorry!! Leave exceeded'];
        } else {
            return ['status' => 1, 'msg' => ''];
        }
    }

    public function allpendingleave(Request $request)
    {
        if (\Request::has('filter')) {
            if ($request->filter == 'nep') {                 //for nepali
                $startdate = $request->nepstartdate;
                $enddate = $request->nependdate;
                $cal = new \App\Helpers\NepaliCalendar();
                $startdate = explode('-', $startdate);
                $enddate = explode('-', $enddate);
                $date = $cal->nep_to_eng($startdate[0], $startdate[1], $startdate[2]);
                $startdate = $date['year'].'-'.$date['month'].'-'.$date['date'];

                $date = $cal->nep_to_eng($enddate[0], $enddate[1], $enddate[2]);
                $enddate = $date['year'].'-'.$date['month'].'-'.$date['date'];
            } else {
                $startdate = $request->engstartdate;
                $enddate = $request->engenddate;
            }
        } else {
            $startdate = null;
            $enddate = null;
        }

        $filterdates = function ($query) use ($startdate,$enddate) {
            if ($startdate && $enddate) {
                return $query->where('tbl_leave_application.leave_start_date', $startdate)
                  ->where('tbl_leave_application.leave_end_date', $enddate);
            }
        };
        $filterusers = function ($query) use ($request) {
            if ($request->user_id && $request->user_id != '') {
                return $query->where('tbl_leave_application.user_id', $request->user_id);
            }
        };

        $filterleaveType = function($query) use($request){


            if($request->leave_status && $request->leave_status == '1'){

                return $query->where('application_status','1');

            }elseif($request->leave_status && $request->leave_status == '1'){

                return $query->where('application_status','1');
            }elseif($request->leave_status && $request->leave_status == '2'){

                return $query->where('application_status','2');
            }elseif($request->leave_status && $request->leave_status == '3'){

                return $query->where('application_status','3');
            }
            elseif($request->leave_status && $request->leave_status == '4'){

                return 1;
            }else{
                 return $query->where('application_status','1');
            }


        };

        


        if (Auth::user()->hasRole('admins')) {

            $users = User::select('first_name', 'last_name', 'id', 'email', 'phone', 'username')->where('enabled', '1')->get();

            $allleaves = LeaveApplication::orderBy('leave_start_date', 'desc')

                        ->where(function ($query) use ($filterdates) {
                            return $filterdates($query);
                        })->where(function ($query) use ($filterusers) {
                            return $filterusers($query);
                        })->where(function ($query) use ($filterleaveType) {
                            return $filterleaveType($query);
                        })->paginate(25);
        } else {


            $users = User::where(function($query){

                if(!\Auth::user()->hasRole('hr-manager')){

                    return $query->where('first_line_manager',\Auth::user()->id);
                }



            })->get();
            
            $userViewAble = $users->pluck('id')->toArray();



            $allleaves = LeaveApplication::select('tbl_leave_application.*')
                       ->where(function ($query) use ($filterleaveType) {
                            return $filterleaveType($query);
                        })
                      
                        ->whereIn('tbl_leave_application.user_id',$userViewAble)
                        // ->where('user_teams.user_id', \Auth::user()->id)
                        ->where(function ($query) use ($filterdates) {
                            return $filterdates($query);
                        })->where(function ($query) use ($filterusers) {
                            return $filterusers($query);
                        })->where(function ($query) use ($filterleaveType) {
                            return $filterleaveType($query);
                        })->orderBy('leave_start_date', 'desc')
                        ->paginate(25);
        }
  
        $categories = LeaveCategory::get();

    

        $page_title = 'All Pending Leaves';

        $page_description = 'Pending Leaves Lists';

        //dd($allleaves);

        return view('admin.leave_mgmt.allpendingleaves', compact('allleaves', 'page_title', 'categories', 'users', 'page_description', 'startdate', 'enddate'));
    }

    public function leavereport(Request $request)
    {
        //dd($request->all());
        $allleaves = LeaveApplication::orderBy('leave_start_date', 'desc')->get();

        $categories = LeaveCategory::get();


        $leave_years = \App\Models\Leaveyear::where('org_id', \Auth::user()->org_id)
                    ->pluck('leave_year as name', 'id');
        
        if(\Auth::user()->hasROle('admins')){
            $userlists = User::where('enabled', '1')->get();
        } else{

            $departments_list = \App\Models\Department::where('departments_id',\Auth::user()
                            ->department_head)->get()->pluck('departments_id')->toArray();
            
            if(count($departments_list) == 0 ){
                
                $userlists =  \App\User::where('user_id',\Auth::user()->id)
                              ->get();
        
            }else{

                $userlists =  \App\User::whereIn('departments_id', $departments_list)->get();

            }

        }


        if (\Request::get('user_id') != '') {
            $users = User::select('first_name', 'last_name', 'id', 'email', 'phone', 'username','first_line_manager')->where('enabled', '1')->where('id', \Request::get('user_id'))->paginate(25);
        } else {
            $users = User::select('first_name', 'last_name', 'id', 'email', 'phone', 'username','first_line_manager')->where('enabled', '1')->paginate(25);
        }

        $page_title = 'Leave Report';

        $page_description = 'All the Leaves Of the Users';
        $nepstart_date = null;$nepend_date = null;
        if (\Request::has('filter_type')) {
            if ($request->filter_type == 'annual_year') {
                $_years = \App\Models\Leaveyear::find($request->leave_years);
                $startdate = $_years->start_date;
                $enddate = $_years->end_date;
            } elseif ($request->date_type == 'nep') {                 //for nepali
                $nepstart_date = $request->start_date;
                $nepend_date = $request->end_date;
                $cal = new \App\Helpers\NepaliCalendar();
                $startdate = explode('-', $nepstart_date);
                $enddate = explode('-', $nepend_date);
                $date = $cal->nep_to_eng($startdate[0], $startdate[1], $startdate[2]);
                $startdate = $date['year'].'-'.$date['month'].'-'.$date['date'];
                $date = $cal->nep_to_eng($enddate[0], $enddate[1], $enddate[2]);
                $enddate = $date['year'].'-'.$date['month'].'-'.$date['date'];
            } else {
                $startdate = $request->start_date;
                $enddate = $request->end_date;
            }
        } else {
            $_years = \App\Models\Leaveyear::where('current_year', '1')
                    ->where('org_id', \Auth::user()->org_id)
                    ->first();
            $startdate = $_years->start_date;
            $enddate = $_years->end_date;
        }
        //dd($nepstartdate);

        //dd($enddate);

        return view('admin.leave_mgmt.leavereport', compact('allleaves', 'page_title', 'categories', 'users', 'nepstart_date', 'nepend_date', 'page_description', 'leave_years', 'startdate', 'enddate', 'userlists'));
    }

    public function bulkleave()
    {
        $page_title = 'Bulk | Leave';
        $department = \App\Models\Department::all();
        $users = User::select('first_name', 'last_name', 'id', 'email', 'phone', 'username')
                ->where('enabled', '1')
                ->where(function ($query) {
                    if (\Request('depid')) {
                        return $query->where('departments_id', \Request::get('depid'));
                    }
                })
                ->where('enabled', '1')->get();
        $route = 'bulk/leave_management';
        $depid = \Request::get('dep_id');

        return view('admin.leave_mgmt.bulkleave', compact('department', 'users',   'route', 'depid', 'page_title'));
    }

    public function getbulkleave(Request $request)
    {
        $page_title = 'Bulk | Leave';
        if (! is_array($request->user_id)) {
            Flash::error('Please Select At Least One User');

            return redirect()->back();
        }
        $depid = \Request::get('dep_id');

        $department = \App\Models\Department::all();
        $selectedusers = User::select('first_name', 'last_name', 'id', 'email', 'phone', 'username')->whereIn('id', $request->user_id)->get();
        $leave_category = LeaveCategory::get();
        $route = 'bulk/leave_management/store';
        foreach ($leave_category as $key => $lc) {
            $leave_category_opt .= "<option value='{$lc->leave_category_id}'>{$lc->leave_category}</option>";
        }
        $request_to = \App\Models\Team::where('org_id', \Auth::user()->org_id)
                        ->select('name as username', 'id')->get();
        foreach ($request_to as $key => $lc) {
            $request_to_opt .= "<option value='{$lc->id}'>{$lc->username}(#{$lc->id})</option>";
        }

        return view('admin.leave_mgmt.bulkleave', compact('department', 'selectedusers', 'leave_category_opt', 'request_to_opt', 'route', 'depid', 'page_title'));
    }

    public function storebulkleave(Request $request)
    {
        $attributes = [];
        foreach ($request->user_id as $key => $value) {
            $data = [
                'user_id' => $value,
                'leave_category_id' => ($request->leave_category_id)[$key],
                'leave_category_id' => ($request->leave_category_id)[$key],
                'request_to' => ($request->request_to)[$key],
                'leave_start_date' => ($request->start_date)[$key],
                'leave_end_date' => ($request->end_date)[$key],
                'reason' => ($request->reason)[$key],
                'created_at' => date('Y-m-d h:i'),

            ];
            array_push($attributes, $data);
        }
        if (count($data) > 0) {
            $this->leaveAppRep->insert($attributes);
            Flash::success('Bulk Leave Posted');
        } else {
            Flash::error('No any Leave Was added');
        }

        return redirect('/admin/bulk/leave_management');
    }



    public function addUserEarnedLeave(){


        $page_title = 'Bulk | Earned Leave';
        $department = \App\Models\Department::all();
        $currentYear = \TaskHelper::cur_leave_yr();
        $carrayOverLeave = \App\Models\LeaveCarryForward::where('from_leave_year_id',$currentYear->id)->get();


        $users = User::select('first_name', 'last_name', 'id', 'email', 'phone', 'username')
                ->whereNotIn('id',$carrayOverLeave->pluck('user_id')->toArray())
                ->where('enabled', '1')
                ->where(function ($query) {
                    if (\Request('depid')) {
                        return $query->where('departments_id', \Request::get('depid'));
                    }
                })
                ->where('enabled', '1')->get();
        $route = 'add_earned_leave';
        $depid = \Request::get('dep_id');


        return view('admin.leave_mgmt.earned_leave',compact('page_title','department','users','route','carrayOverLeave','currentYearea'));




    }

    public function addUserEarnedLeaveNext(Request $request){

        $page_title = 'Bulk | Leave';
        if (! is_array($request->user_id)) {
            Flash::error('Please Select At Least One User');

            return redirect()->back();
        }
        $depid = \Request::get('dep_id');

        $department = \App\Models\Department::all();
        
        $selectedusers = User::select('first_name', 'last_name', 'id', 'email', 'phone', 'username')->whereIn('id', $request->user_id)->get();

        $leaveYear = \App\Models\Leaveyear::where('org_id',\Auth::user()->org_id)->get();
        $currentYear = \TaskHelper::cur_leave_yr();
        $route = 'add_earned_leave/store';

        return view('admin.leave_mgmt.earned_leave', compact('department', 'selectedusers', 'leave_category_opt', 'request_to_opt', 'route', 'depid', 'page_title','leaveYear','currentYear'));
    }


    public function addUserEarnedLeaveStore(Request $request){

        $attributes = $request->all();
           
        foreach ($request->user_id as $key => $user_id) {
            
            $form_data = [
                'user_id'=>$user_id,
                'from_leave_year_id'=>$request->from_leave_year_id[$key],
                'num_of_carried'=>$request->num_of_carried[$key],
                'num_days_balance'=>$request->num_of_carried[$key],
            ];

            \App\Models\LeaveCarryForward::create($form_data);
        }

        Flash::success("Earned Leave Added");

        return redirect()->back();
        

    }


    public function editUserEarnedLeave($id){


        $earnedLeave = \App\Models\LeaveCarryForward::find($id);

        return view('admin.leave_mgmt.edit_earned_leave',compact('earnedLeave'));


    }

    public function updateUserEarnedLeave(Request $request,$id){

        $attributes = $request->all();
        $earnedLeave = \App\Models\LeaveCarryForward::find($id);
        $earnedLeave->update($attributes);
        Flash::success("Balance Updated");
        return redirect()->back();


    }


    public function destroyUserEarnedLeave($id){

        $earnedLeave = \App\Models\LeaveCarryForward::find($id);
        
        $earnedLeave->delete();

        Flash::success("Leave Carry Deleted");
        
        return redirect()->back();


    }

    public function getuserLeaveStatus($id){


        $userIdStatus = \App\User::find($id);

        $categories = LeaveCategory::get();

        $html = view('admin.leave_mgmt.leave_status',compact('userIdStatus','categories'))->render();

        return ['html'=>$html];


    }
}

