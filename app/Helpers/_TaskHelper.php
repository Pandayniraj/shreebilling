<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\Biom_log;
use App\Models\COAgroups;
use App\Models\Lead;
use App\Models\LeaveApplication;
use App\Models\Projects;
use App\Models\ProjectTask;
use App\Models\ProjectUser;
use App\Models\UserTargetPivot;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class TaskHelper
{
    protected $glob;

    public static function getLeadNameById($post_id)
    {
        $temp = DB::table('leads')
                 ->where('id', $post_id)
                 ->first();

        return $temp->name;
    }

    public static function getUser($user_id)
    {
        return DB::table('users')
                 ->where('id', $user_id)
                 ->first();
    }

  public static function getTaxInvoicePaidAmount($id){

        return \App\Models\InvoicePayment::where('invoice_id',$id)->sum('amount');

    }

    public static function GetOrgName($org_id)
    {
        return DB::table('organizations')
                 ->where('id', $org_id)
                 ->first();
    }

    public static function GetTimeDifference($time_from, $time_to)
    {
        $from_time = strtotime($time_from);
        $to_time = strtotime($time_to);
        $difference = strtotime($time_to) - strtotime($time_from);

        return $difference;
    }

    public static function minutesToHours($minutes)
    {
        $hours = (int) ($minutes / 60);
        $minutes -= $hours * 60;

        return sprintf('%d:%02.0f', $hours, $minutes);
    }

    public static function getUserName($user_id)
    {
        if (Session::has('userName_'.$user_id)) {
            return Session::get('userName_'.$user_id);
        } else {
            $temp = DB::table('users')
                 ->where('id', $user_id)
                 ->first();

            Session::put('userName_'.$user_id, $temp->first_name.' '.$temp->last_name);

            return $temp->first_name.' '.$temp->last_name;
        }
    }

    public static function AddCityLedgerCustomer($name)
    {
        $group = \App\Models\COAgroups::where('name', 'City Ledger')->first();

        $attributes_ledger = new \App\Models\COALedgers();
        $attributes_ledger->group_id = $group->id;
        $attributes_ledger->name = $name;
        $attributes_ledger->user_id = Auth::user()->id;
        $attributes_ledger->org_id = Auth::user()->org_id;
        $attributes_ledger->op_balance = 0.00;
        $attributes_ledger->op_balance_dc = D;
        $attributes_ledger->type = 0;
        $attributes_ledger->reconciliation = 0;
        $attributes_ledger->notes = $name;
        // For ledger Code

        $group_id = $group->id;
        $group_data = \App\Models\COAgroups::find($group_id);
        $group_code = $group_data->code;

        $q = \App\Models\COALedgers::where('group_id', $group_id)->get();

        if ($q) {
            $last = $q->last();
            $last = $last->code;
            $l_array = explode('-', $last);
            $new_index = end($l_array);
            $new_index += 1;
            $new_index = sprintf('%04d', $new_index);
            $ledger_code = $group_code.'-'.$new_index;
        //dd($ledger_code);
        } else {
            $ledger_code = $group_code.'-0001';
        }
        $attributes_ledger->code = $ledger_code;

        $attributes_ledger->save();

        return $attributes_ledger->id;
    }

    public static function getCourseName($course_id)
    {
        $temp = DB::table('products')
                 ->where('id', $product_id)
                 ->first();

        return $temp->name;
    }

    public static function getIntakeName($intake_id)
    {
        $temp = DB::table('lead_intakes')
                 ->where('id', $intake_id)
                 ->first();

        return $temp->name;
    }

    public static function getPurchasePaymentAmount($id)
    {
        $paid_amount = DB::table('payments')->where('purchase_id', $id)->sum('amount');

        if ($paid_amount > 0) {
            return $paid_amount;
        }

        return 0.00;
    }

    public static function getSalesPaymentAmount($id)
    {
        $paid_amount = DB::table('payments')->where('sale_id', $id)->sum('amount');

        if ($paid_amount > 0) {
            return $paid_amount;
        }

        return 0.00;
    }

    public static function getProfileImage($user_id)
    {
        if (Session::has('profileImage_'.$user_id)) {
            echo Session::get('profileImage_'.$user_id);
        } else {
            $temp = DB::table('users')
                 ->select('image')
                 ->where('id', $user_id)
                 ->first();

            if ($temp->image != '') {
                Session::put('profileImage_'.$user_id, asset('/images/profiles/'.$temp->image));

                return asset('/images/profiles/'.$temp->image);
            } else {
                Session::put('profileImage_'.$user_id, asset('/images/logo.png'));

                return asset('/images/logo.png');
            }
        }
    }

    public static function userTarget($targetId, $courseId, $userId, $year)
    {
        $userTarget = UserTargetPivot::where('user_target_id', $targetId)->where('product_id', $courseId)->first();
        if ($userTarget) {
            $approvedLeads = Lead::where('user_id', $userId)->where('product_id', $courseId)->where('status_id', '27')->where('enabled', '1')->whereBetween('created_at', [$year.'-01-01 00:00:00', $year.'-12-30 23:59:59'])->count();
            //echo $approvedLeads; exit;
            /*if($courseId == '15' && $userId == '3' && $year == '2018')
                echo $approvedLeads; exit;*/
            if ($approvedLeads != 0) {
                if ($userTarget->target > $approvedLeads) {
                    return '<span style="color:green;">'.($userTarget->target - $approvedLeads).'</span> &nbsp;<span style="text-decoration: line-through; color:red;">'.$userTarget->target.'</span>';
                } else {
                    return '<span style="color:green;">'.($approvedLeads - $userTarget->target).'</span> &nbsp;<span style="text-decoration: line-through; color:red;">'.$userTarget->target.'</span>';
                }
            }

            return $userTarget->target;
        } else {
            return '<div style="background: #ccc;">&nbsp;</div>';
        }
    }

    /* public static function getUserAttendance($user_id, $leave_category_id, $date_in)
    {
        $attendance = Attendance::where('user_id', $user_id)->where('leave_category_id', $leave_category_id)->where('date_in', '>=', $date_in.'-01')->where('date_in', '<=', $date_in.'-32')->orderBy('date_in', 'asc')->get();

        return $attendance;
    } */

    public static function getUserAttendance($user_id, $date_in)
    {
        $start_date = $date_in.'-01';
        $end_date = date('Y-m-t', strtotime($start_date));
        $attendance = Attendance::where('user_id', $user_id)->where('date_in', '>=',
            $start_date)->where('date_in', '<=', $end_date)->orderBy('date_in', 'asc')->get();

        return $attendance;
    }

    public static function getUserAttendanceHistroy($user_id, $date_in)
    {
        $date_in = explode('.', $date_in);

        $start_date = $date_in[0];
        $end_date = $date_in[1];
        $attendance = Attendance::where('user_id', $user_id)->where('date_in', '>=',
            $start_date)->where('date_in', '<=', $end_date)->orderBy('date_in', 'asc')->get();

        return $attendance;
    }

    public static function findDays($start_date, $end_date)
    {
        $interval = date_diff(date_create($start_date), date_create($end_date));

        return $interval->format('%a') + 1;
    }



    public static function reduceLeaveFromTimeoff($days,$userId,$start_date,$end_date){


        $time_off_leave= LeaveApplication::select('*')->where('user_id', $userId)->where('leave_category_id', env('TIME_OFF_ID'))->where('application_status', '2')->where('leave_start_date', '>=', $start_date)->where('leave_end_date', '<=', $end_date)->get();
        $totalLeaves = 0;
        foreach ($time_off_leave as $key => $value) {

                $to_time = strtotime($value->time_off_start);

                $from_time = strtotime($value->time_off_end);

                $time_off_time = round(abs($to_time - $from_time) / 60,2);

                $totalLeaves += $time_off_time / env('TOTAL_WORKING_HOURS'); //convert minutes to days in term of total office hours


        }
        return ($days-$totalLeaves);


    }

    public static function userLeave($userId, $leaveCatId, $year = false)
    {

        $current_yr = self::cur_leave_yr();
        $start_date = $current_yr->start_date;
        $end_date = $current_yr->end_date;

        $userLeaves = LeaveApplication::select('*')->where('user_id', $userId)->where('leave_category_id', $leaveCatId)->where('application_status', '2')->where('leave_start_date', '>=', $start_date)->where('leave_end_date', '<=', $end_date)->get();

        $totalLeaves = 0;
        foreach ($userLeaves as $key => $value) {

            $earlier = new \DateTime($value->leave_start_date);
            $later = new \DateTime($value->leave_end_date);

            $diff = $later->diff($earlier)->format("%a") + 1;

            if($value->leave_category_id == env('TIME_OFF_ID')){

                $to_time = strtotime($value->time_off_start);

                $from_time = strtotime($value->time_off_end);

                $time_off_time = round(abs($to_time - $from_time) / 60,2);

                $totalLeaves += $time_off_time;

            }
            elseif($value->part_of_day == 1){
                $t = $diff * 1;

                $totalLeaves += $t; //full leave
            }else{
                 $t = $diff * 0.5;

                $totalLeaves += $t; //half leave
            }



        }
       if($leaveCatId == env("LEAVE_TO_REDUCE_FROM_TIME_OFF")){

        $totalLeaves = self::reduceLeaveFromTimeoff($totalLeaves,$userId,$start_date,$end_date);

       }

        //dd($userLeaves);

        if ($totalLeaves) {
            return $totalLeaves;
        } else {
            return '0';
        }
    }

    public static function userLeaveALLReport($userId, $leaveCatId, $startdate, $enddate)
    {

         //dd($startdate);

        $userLeaves = LeaveApplication::select(DB::raw('sum(leave_days) as total'))->where('user_id', $userId)->where('leave_category_id', $leaveCatId)->where('application_status', '2')->where('leave_start_date', '>=', $startdate)->where('leave_start_date', '<=', $enddate)->first();

        //dd($userLeaves);

        if ($userLeaves && $userLeaves->total) {
            return $userLeaves->total;
        } else {
            return '0';
        }
    }

    public static function getGravatarAttribute($email)
    {
        $hash = md5(strtolower(trim($email)));

        return "http://www.gravatar.com/avatar/$hash";
    }

    public static function getProjectUsers($projectId)
    {
        $users = ProjectUser::where('project_id', $project_id)->get();

        return $users;
    }

    public static function getProjectNameFromComments($taskId)
    {

         //find project from task id
        $projecttask = ProjectTask::where('id', $taskId)->first();
        $project = Projects::where('id', $projecttask->project_id)->first();

        return $project->name;
    }

    public static function gettodaysFolowUpLeads($stage_id, $date)
    {
        if (Request::get('follow_date') && Request::get('follow_date') != '') {
            $date = Request::get('follow_date');
        }

        $tasksDueToday = \App\Models\Task::whereHas('lead', function ($leadQuery) use ($stage_id, $date) {
            $leadQuery->where('stage_id', $stage_id);
        })
                        ->whereDate('task_due_date', '=', $date)
                        ->get();

        return $tasksDueToday;
    }

    public static function gettodaysNextAction($stage_id, $date)
    {
        if (Request::get('follow_date') && Request::get('follow_date') != '') {
            $date = Request::get('follow_date');
        }

        $leads = \App\Models\Lead::where('stage_id', $stage_id)->whereDate('target_date', '=', $date)->get();

        return $leads;
    }

    public static function getCountByProduct($id, $type_id)
    {
        $productcount = \App\Models\Lead::where('lead_type_id', $type_id)->where('product_id', $id)
                        ->count();

        return $productcount;
    }

    public static function getChildAndSubChild($childs)
    {
        $childs = \App\Models\COAgroups::where('parent_id', $childs->parent_id)->get();

        return $childs;
    }

    public static function get_local_time()
    {
        $ip = file_get_contents('https://www.customers.meronetwork.com');

        $url = 'http://ip-api.com/json/'.$ip;
        $tz = file_get_contents($url);
        $tz = json_decode($tz, true)['timezone'];

        return $tz;
    }

    public static function getLedger($id)
    {
        $rawentryitems = \App\Models\Entryitem::where('entry_id', $id)->get();
        $dr_count = 0;
        $cr_count = 0;
        $dr_ledger_id = '';
        $cr_ledger_id = '';

        foreach ($rawentryitems as $row => $entryitem) {
            if ($entryitem->dc == 'D') {
                $dr_ledger_id = $entryitem->ledger_id;
                $dr_count++;
            } else {
                $cr_ledger_id = $entryitem->ledger_id;
                $cr_count++;
            }
        }

        /* Get ledger name */
        $dr_detail = \App\Models\COALedgers::where('id', $dr_ledger_id)->first();

        $cr_detail = \App\Models\COALedgers::where('id', $cr_ledger_id)->first();

        $dr_name = '['.$dr_detail->name.'] '.$dr_detail->code;

        $cr_name = '['.$cr_detail->name.'] '.$cr_detail->code;

        if (strlen($dr_name) > 15) {
            $dr_name = substr($dr_name, 0, 12).'..';
        }
        if (strlen($cr_name) > 15) {
            $cr_name = substr($cr_name, 0, 12).'..';
        }

        /* if more than one ledger on dr / cr then add [+] sign */
        if ($dr_count > 1) {
            $dr_name = $dr_name.' [+]';
        }
        if ($cr_count > 1) {
            $cr_name = $cr_name.' [+]';
        }

        $ledgerstr = 'Dr '.$dr_name.' / '.'Cr '.$cr_name;

        return  $ledgerstr;
    }

    public static function getLedgerBalance($id)
    {
        if ($id == '') {
            return 0;
        }

        $ledgers = \App\Models\COALedgers::where('id', $id)->where('org_id', Auth::user()->org_id)->get();

        if (count($ledgers) == 0) {
            $cl = ['cl' => ['dc' => '', 'amount' => '']];
        } else {
            $op = \App\Models\COALedgers::find($id);
            $op_total = 0;
            $op_total_dc = $op->op_balance_dc;

            if (empty($op->op_balance)) {
                $op_total = 0;
            } else {
                $op_total = $op['op_balance'];
            }

            $dr_total = 0;
            $cr_total = 0;
            $dr_total_dc = 0;
            $cr_total_dc = 0;

            //Debit Amount
            $total = \App\Models\Entryitem::select('amount')->where('ledger_id', $id)->where('dc', 'D')->where('org_id', Auth::user()->org_id)
                         ->sum('amount');

            if (empty($total)) {
                $dr_total = 0;
            } else {
                $dr_total = $total;
            }

            //Credit Amount
            $total = \App\Models\Entryitem::select('amount)')->where('ledger_id', $id)->where('dc', 'C')->where('org_id', Auth::user()->org_id)
                         ->sum('amount');

            if (empty($total)) {
                $cr_total = 0;
            } else {
                $cr_total = $total;
            }

            /* Add opening balance */
            if ($op_total_dc == 'D') {
                $dr_total_dc = $op_total + $dr_total;
                $cr_total_dc = $cr_total;
            } else {
                $dr_total_dc = $dr_total;
                $cr_total_dc = $op_total + $cr_total;
            }
            /* $this->calculate and update closing balance */
            $cl = 0;
            $cl_dc = '';
            if ($dr_total_dc > $cr_total_dc) {
                $cl = $dr_total_dc - $cr_total_dc;

                $cl_dc = 'D';
            } elseif ($cr_total_dc == $dr_total_dc) {
                $cl = 0;
                $cl_dc = $op_total_dc;
            } else {
                $cl = $cr_total_dc - $dr_total_dc;
                $cl_dc = 'C';
            }

            $cl = ['dc' => $cl_dc, 'amount' => $cl, 'dr_total' => $dr_total, 'cr_total' => $cr_total];

            $status = 'ok';
            // if ($ledgers->type == 1) {
            //     if ($cl->dc == 'C') {
            //         $status = 'neg';
            //     }
            // }

            /* Return closing balance */
            $cl = ['cl' => [
                              'dc' => $cl['dc'],
                              'amount' => $cl['amount'],
                              'status' => $status,
                            ],
                        ];
        }

        $data = json_encode($cl);

        $data1 = json_decode($data);

        $ledger_bal = $data1->cl->amount;

        $prefix = '';
        $suffix = '';
        if ($data1->cl->status == 'neg') {
            $prefix = '<span class="error-text">';
            $suffix = '</span>';
        }
        if ($data1->cl->dc == 'D') {
            $ledger_balance = $prefix.'Dr'.' '.$ledger_bal.$suffix;
        } elseif ($data1->cl->dc == 'C') {
            $ledger_balance = $prefix.'Cr'.' '.$ledger_bal.$suffix;
        } else {
            $ledger_balance = '-';
        }

        return $ledger_balance;
    }

    public static function getLedgerDebitCredit($id)
    {
        if ($id == '') {
            return 0;
        }
        $start_date = \Request::get('start_date');

        $end_date = \Request::get('end_date');

        $ledgers = \App\Models\COALedgers::where('id', $id)->first();

        if (count($ledgers) == 0) {
            $cl = ['cl' => ['dc' => '', 'amount' => '']];
        } else {
            $op = \App\Models\COALedgers::find($id);
            $op_total = 0;
            $op_total_dc = $op->op_balance_dc;

            if (empty($op->op_balance)) {
                $op_total = 0;
            } else {
                $op_total = $op['op_balance'];
            }

            $dr_total = 0;
            $cr_total = 0;
            $dr_total_dc = 0;
            $cr_total_dc = 0;

            //Debit Amount
            $total = \App\Models\Entryitem::select('entryitems.amount')->where('entryitems.ledger_id', $id)->where('dc', 'D')
                ->where(function($query) use ($start_date,$end_date){
                    if($start_date && $end_date){
                        return $query->where('entries.date','>=',$start_date)
                            ->where('entries.date','<=',$end_date);
                    }
                })->leftjoin('entries','entries.id','=','entryitems.entry_id')
                ->groupBy('entryitems.id')
                ->sum('entryitems.amount');

            if (empty($total)) {
                $dr_total = 0;
            } else {
                $dr_total = $total;
            }

            //Credit Amount
            $total = \App\Models\Entryitem::select('entryitems.amount')->where('entryitems.ledger_id', $id)->where('dc', 'C')
                ->where(function($query) use ($start_date,$end_date){
                    if($start_date && $end_date){
                        return $query->where('entries.date','>=',$start_date)
                            ->where('entries.date','<=',$end_date);
                    }
                })->leftjoin('entries','entries.id','=','entryitems.entry_id')
                ->groupBy('entries.id')
                ->sum('entryitems.amount');
            if (empty($total)) {
                $cr_total = 0;
            } else {
                $cr_total = $total;
            }

            /* Add opening balance */
            if ($op_total_dc == 'D') {
                $dr_total_dc = $op_total + $dr_total;
                $cr_total_dc = $cr_total;
            } else {
                $dr_total_dc = $dr_total;
                $cr_total_dc = $op_total + $cr_total;
            }
            /* $this->calculate and update closing balance */
            $cl = 0;
            $cl_dc = '';
            if ($dr_total_dc > $cr_total_dc) {
                $cl = $dr_total_dc - $cr_total_dc;

                $cl_dc = 'D';
            } elseif ($cr_total_dc == $dr_total_dc) {
                $cl = 0;
                $cl_dc = $op_total_dc;
            } else {
                $cl = $cr_total_dc - $dr_total_dc;
                $cl_dc = 'C';
            }

            //dd($ledgers);

            $cl = ['dc' => $cl_dc, 'amount' => $cl, 'dr_total' => $dr_total, 'cr_total' => $cr_total];

            $status = 'ok';
            if ($ledgers->type == 1) {
                if ($cl->dc == 'C') {
                    $status = 'neg';
                }
            }

            /* Return closing balance */
            $cl =[
                  'dc' =>(string) $cl['dc'],
                  'amount' => $cl['amount'],
                  'status' => $status,
                ];
        }

        $data1 = json_decode(json_encode($cl), true);
        //dd($data1);
        return $data1;
    }

    public static function calculate_withdc($param1, $param1_dc, $param2, $param2_dc)
    {
        $result = 0;
        $result_dc = 'D';

        if ($param1_dc == 'D' && $param2_dc == 'D') {
            $result = $param1 + $param2;
            $result_dc = 'D';
        } elseif ($param1_dc == 'C' && $param2_dc == 'C') {
            $result = $param1 + $param2;
            $result_dc = 'C';
        } else {
            if ($param1 > $param2) {
                $result = $param1 - $param2;
                $result_dc = $param1_dc;
            } else {
                $result = $param2 - $param1;
                $result_dc = $param2_dc;
            }
        }

        return ['amount' => $result, 'dc' => $result_dc];
    }

    public static function balancesummary($id)
    {
        if ($id == 0) {
            $id = null;
            $name = 'None';
        } else {
            $group = COAgroups::where('id', $id)->first();

            $id = $group->id;
            $name = $group->name;
            $code = $group->code;
            $g_parent_id = $group->parent_id;
            $g_affects_gross = $group->affects_gross;
        }

        $op_total = 0;
        $op_total_dc = 'D';
        $dr_total = 0;
        $cr_total = 0;
        $cl_total = 0;
        $cl_total_dc = 'D';

        $totalbalance = 0;

        /* If affects_gross set, add sub-ledgers to only affects_gross == 0 */
        if ($group->affects_gross == 1) {
            /* Skip adding sub-ledgers if affects_gross is set and value == 1 */
        } else {
            $ledgers = \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id', $group->id)->get();

            if (count($ledgers > 0)) {
                foreach ($ledgers as $ledger) {
                    $entries = \App\Models\Entryitem::where('ledger_id', $ledger->id)->get();
                    if (count($entries) > 0) {
                        foreach ($entries as $entry) {
                            $totalbalance = $totalbalance + $entry->amount;
                        }
                    }
                }
            }

            $total = \TaskHelper::CategoryTree($group->id, $totalbalance);
        }
    }

    public static function CategoryTree($parent_id, $totalbalance)
    {
        $groups = \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id', $parent_id)->get();

        if (count($groups) > 0) {
            $price = null;
            foreach ($groups as $group) {
                $ledgers = \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id', $group->id)->get();

                if (count($ledgers > 0)) {
                    foreach ($ledgers as $ledger) {
                        $entries = \App\Models\Entryitem::where('ledger_id', $ledger->id)->get();
                        if (count($entries) > 0) {
                            foreach ($entries as $entry) {
                                $totalbalance = $totalbalance + $entry->amount;
                            }
                        }
                    }
                }

                $total = \TaskHelper::CategoryTree($group->id, $totalbalance);
            }
        }

        return $totalbalance;
    }

    public static function getTotalYearly($id, $fiscal_year = false)
    {
        if (! $fiscal_year) {
            $fiscal_year = \FinanceHelper::cur_fisc_yr()->id;
        }
        $number = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = new DateTime('now');
            $date->modify('first day of -'.$i.' month');
            $number[$date->format('Y-m')] = 0;
        }

        if ($id == 0) {
            $xAxis = array_keys($number);
            foreach ($xAxis as $key => $offset) {
                $new_offset = date('M Y', strtotime($offset));
                $xAxis[$key] = $new_offset;
            }

            return $xAxis;
        }

        // build our category list only once
        $cats = [];

        $cats = \App\Models\COAgroups::orderBy('id', 'asc')->get();

        $mytime = \Carbon\Carbon::now();

        $thisyear = date('Y', strtotime($mytime));

        $startOfYear = $mytime->copy()->startOfYear();
        $startyear = date('Y-m-d', strtotime($startOfYear));

        //dd($startyear);

        $endOfYear = $mytime->copy()->endOfYear();
        $endyear = date('Y-m-d', strtotime($endOfYear));

        $parent_ids = \TaskHelper::buildTree($cats, $id);

        // dd($parent_ids);

        $q = DB::table('entryitems')
                          ->select(
                            DB::raw('YEAR(entries.date) as year'),
                            DB::raw('MONTH(entries.date) as month'),
                            DB::raw('SUM(entryitems.amount) as sum'),
                            'entries.currency as currency',
                        )
                        ->rightJoin('coa_ledgers', 'entryitems.ledger_id', '=', 'coa_ledgers.id')
                        ->rightJoin('coa_groups', 'coa_ledgers.group_id', '=', 'coa_groups.id')
                        ->rightJoin('entries', 'entryitems.entry_id', '=', 'entries.id')
                        ->where('entries.fiscal_year_id', $fiscal_year)
                        ->groupBy('month')
                        ->whereIn('coa_groups.id', $parent_ids)
                        ->whereBetween('entries.date', [$startyear, $endyear])
                        ->get();

        //dd($q);

        return $q;
    }

    public static function getTotalMonthly($id, $fiscal_year = false)
    {
        if (! $fiscal_year) {
            $fiscal_year = \FinanceHelper::cur_fisc_yr()->id;
        }
        $number = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = new DateTime('now');
            $date->modify('first day of -'.$i.' month');
            $number[$date->format('Y-m')] = 0;
        }

        if ($id == 0) {
            $xAxis = array_keys($number);
            foreach ($xAxis as $key => $offset) {
                $new_offset = date('M Y', strtotime($offset));
                $xAxis[$key] = $new_offset;
            }

            return $xAxis;
        }

        // build our category list only once
        $cats = [];

        $cats = \App\Models\COAgroups::orderBy('id', 'asc')->get();

        $startDay = \Carbon\Carbon::now();

        $firstDay = $startDay->firstOfMonth();

        $firstmonthDate = date('Y-m-d', strtotime($firstDay));

        //dd($firstmonthDate);

        $lastDay = $startDay->endOfMonth();

        $lastmonthDate = date('Y-m-d', strtotime($lastDay));
        // dd($lastmonthDate);

        $parent_ids = \TaskHelper::buildTree($cats, $id);

        // dd($parent_ids);

        $q = DB::table('entryitems')
                          ->select(
                            DB::raw('YEAR(entries.date) as year'),
                            DB::raw('MONTH(entries.date) as month'),
                            DB::raw('DAY(entries.date) as days'),
                            DB::raw('SUM(entryitems.amount) as sum'),
                            'entries.currency as currency',
                        )
                        ->rightJoin('coa_ledgers', 'entryitems.ledger_id', '=', 'coa_ledgers.id')
                        ->rightJoin('coa_groups', 'coa_ledgers.group_id', '=', 'coa_groups.id')
                        ->rightJoin('entries', 'entryitems.entry_id', '=', 'entries.id')
                         ->where('entries.fiscal_year_id', $fiscal_year)
                        ->groupBy('days')
                        ->whereIn('coa_groups.id', $parent_ids)
                        ->whereBetween('entries.date', [$firstmonthDate, $lastmonthDate])
                        ->get();

        //dd($q);

        return $q;
    }

    public static function getTotalByGroups($id)
    {
        $number = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = new DateTime('now');
            $date->modify('first day of -'.$i.' month');
            $number[$date->format('Y-m')] = 0;
        }

        if ($id == 0) {
            $xAxis = array_keys($number);
            foreach ($xAxis as $key => $offset) {
                $new_offset = date('M Y', strtotime($offset));
                $xAxis[$key] = $new_offset;
            }

            return $xAxis;
        }
        $start_date = \Request::get('start_date');

        $end_date = \Request::get('end_date');
        // build our category list only once
        $cats = [];

        $cats = \App\Models\COAgroups::orderBy('id', 'asc')->get();

        $mytime = \Carbon\Carbon::now();

        $thisyear = date('Y', strtotime($mytime));

        $startOfYear = $mytime->copy()->startOfYear();

        $startyear = date('Y-m-d', strtotime($startOfYear));

        //dd($startyear);

        $endOfYear = $mytime->copy()->endOfYear();
        $endyear = date('Y-m-d', strtotime($endOfYear));

        if($start_date && $end_date){
            $startyear = $start_date;
            $endyear = $end_date;
        }
        $parent_ids = \TaskHelper::buildTree($cats, $id);

        $cont = count($parent_ids);

        //dd($parent_ids);

        $parent_ids[$cont] = $id;

        $q = DB::table('entryitems')
                          ->select(
                            // DB::raw('SUM(if (dc="D", entryitems.amount, 0)) - SUM(if (dc="C", entryitems.amount, 0)) as amount'),
                            DB::raw('SUM(if (dc="D", entryitems.amount, 0)) as dr_amount'),
                            DB::raw('SUM(if (dc="C", entryitems.amount, 0)) as cr_amount'),
                            DB::raw('SUM(if (coa_ledgers.op_balance_dc="D", coa_ledgers.op_balance, 0)) - SUM(if (coa_ledgers.op_balance_dc="C", coa_ledgers.op_balance, 0)) as opening_balance')
                        )
                        ->leftJoin('coa_ledgers', 'entryitems.ledger_id', '=', 'coa_ledgers.id')
                        ->leftJoin('coa_groups', 'coa_ledgers.group_id', '=', 'coa_groups.id')
                        ->leftjoin('entries', 'entryitems.entry_id', '=', 'entries.id')
                        ->whereIn('coa_groups.id', $parent_ids)
                        //->groupBy('opening_balance')
                        ->whereBetween('entries.date', [$startyear, $endyear])
                        ->get();

        // dd($q);

        $q = json_decode(json_encode($q), true);

        return $q;
    }

    public static function buildTree($elements, $parentId = 0)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                $children = self::buildTree($elements, $element->id);

                if ($children) {
                    $branch = array_merge($branch, $children);
                }
                $branch[] = $element->id;
            }
        }

        return $branch;
    }

    public static function getItemQtyByLocationName($location_id, $stock_id)
    {

       // dd($stock_id);

        $qty = DB::table('product_stock_moves')
                                  ->where(['location'=>$location_id, 'stock_id'=>$stock_id])
                                  ->sum('qty');
        if (empty($qty)) {
            $qty = 0;
        }

        return $qty;
    }

    public static function backup_tables($host, $user, $pass, $name, $port, $tables = '*')
    {

      //dd($port);
        try {
            $con = mysqli_connect($host, $user, $pass, $name, $port);
        } catch (Exception $e) {
        }
        //mysqli_select_db($name,$link);

        // dd($con);

        if (mysqli_connect_errno()) {
            Session::flash('fail', 'Failed to connect to MySQL: '.mysqli_connect_error());

            return 0;
        }

        //get all of the tables
        if ($tables == '*') {
            $tables = [];
            $result = mysqli_query($con, 'SHOW TABLES');
            while ($row = mysqli_fetch_row($result)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        //cycle through
        $return = '';
        foreach ($tables as $table) {
            $result = mysqli_query($con, 'SELECT * FROM '.$table);
            $num_fields = mysqli_num_fields($result);

            //$return.= 'DROP TABLE '.$table.';';
            $row2 = mysqli_fetch_row(mysqli_query($con, 'SHOW CREATE TABLE '.$table));
            $return .= "\n\n".str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $row2[1]).";\n\n";

            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysqli_fetch_row($result)) {
                    $return .= 'INSERT INTO '.$table.' VALUES(';
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = preg_replace("/\n/", '\\n', $row[$j]);
                        if (isset($row[$j])) {
                            $return .= '"'.$row[$j].'"';
                        } else {
                            $return .= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $return .= ',';
                        }
                    }
                    $return .= ");\n";
                }
            }

            $return .= "\n\n\n";
        }

        $backup_name = date('Y-m-d-His').'.sql';
        //save file
        $handle = fopen(storage_path('laravel-backups').'/'.$backup_name, 'w+');
        fwrite($handle, $return);
        fclose($handle);

        return $backup_name;
    }

    public static function getBiometricUserAttendance($userid, $date_in)
    {
        $date_start = $date_in.'-01';
        $totaldays = \Carbon\Carbon::parse($date_start)->daysInMonth;
        $date_end = $date_in.'-'.$totaldays;
        $attendance = Biom_log::where('machine_userid', $userid)->where('datetime', '>=', $date_start)->where('datetime', '<=', $date_end)->orderBy('datetime', 'asc')->get();

        return $attendance;
    }

    public static function getTranslations($id)
    {
        $transations = DB::table('product_stock_moves')
                               ->where('product_stock_moves.stock_id', $id)
                              ->leftjoin('products', 'products.id', '=', 'product_stock_moves.stock_id')
                              ->leftjoin('product_location', 'product_location.id', '=', 'product_stock_moves.location')
                              ->select('product_stock_moves.*', 'products.name', 'product_location.location_name')
                              ->orderBy('product_stock_moves.tran_date', 'DESC')
                              ->get();

        $sum = 0;
        $StockIn = 0;
        $StockOut = 0;

        foreach ($transations as $result) {
            if ($result->qty > 0) {
                $StockIn += $result->qty;
            }
            if ($result->qty < 0) {
                $StockOut += $result->qty;
            }
        }

        $total_stock = $StockIn + $StockOut;

        //   dd($total_stock);

        return $total_stock;
    }

    public static function getNextCodeLedgers($id)
    {
        $group_data = \App\Models\COAgroups::find($id);
        $group_code = $group_data->code;

        $q = \App\Models\COALedgers::where('group_id', $id)->where('org_id', \Auth::user()->org_id)->where('code', '!=', 'null')->get();

        if ($q) {
            $last = $q->last();
            $last = $last->code;
            $l_array = explode('-', $last);
            $new_index = end($l_array);
            $new_index += 1;
            $new_index = sprintf('%04d', $new_index);

            return $group_code.'-'.$new_index;
        } else {
            return $group_code.'-0001';
        }
    }

    public static function PostLedgers($name, $id)
    {
        $detail = new \App\Models\COALedgers();
        $staff_or_company_id = \App\Models\COAgroups::find($id);
        $detail->group_id = $id;

        $detail->org_id = \Auth::user()->org_id;
        $detail->user_id = \Auth::user()->id;

        $detail->code = self::getNextCodeLedgers($id);
        $detail->name = $name;
        $detail->op_balance_dc = 'D';
        $detail->op_balance = 0.00;
        $detail->notes = $name;
        $detail->ledger_type = $staff_or_company_id->name;
        $detail->staff_or_company_id = $staff_or_company_id->parent_id;
        if ($request->type == 1) {
            $detail->type = $request->type;
        } else {
            $detail->type = 0;
        }
        if ($request->reconciliation == 1) {
            $detail->type = $request->reconciliation;
        } else {
            $detail->reconciliation = 0;
        }
        $detail->save();

        return $detail->id;
    }

    public static function overtimesal($uid, $date_start, $date_end)
    {
        $total_user_time = self::overtimedays($uid, $date_start, $date_end);
        $overtime_money = self::overtimemoney($uid, $total_user_time);

        return $overtime_money;
    }

    public static function overtimemoney($uid, $total_user_time)
    {
        $overtimesal = \App\Models\EmployeePayroll::where('user_id', $uid)->first();
        $overtime_money = ($total_user_time / 60 / 60) * $overtimesal->template->overtime_salary;
        $overtime_money = ceil($overtime_money);

        return $overtime_money;
    }

    public static function overtimedays($uid, $date_start, $date_end)
    {
        if ($date_start && $date_end) {
            $overtime = \App\Models\TimeSheet::where('date', '>=', $date_start)
                    ->where('date', '<=', $date_end)
                    ->where('employee_id', $uid)
                    ->get();
        } else {
            $overtime = \App\Models\TimeSheet::where('employee_id', $uid)->get();
        }
        $total_user_time = 0;
        foreach ($overtime as $key => $o) {
            $total_time = \TaskHelper::GetTimeDifference($o->time_from, $o->time_to);
            $total_user_time = $total_user_time + $total_time;
        }

        return $total_user_time;
    }

    public static function topSubMenu($configArrayName)
    {
        $topmenu = Config::get($configArrayName);
        $copy = $topmenu;

        foreach ($topmenu  as $k => $v) {
            echo '<a href="'.$v['url'].'">'.$v['name'].'</a>';
            if (next($copy)) {
                echo ' | '; // Add comma for all elements instead of last
            }
        }
    }

    public static function getShift($user_id, $date)
    {
        $shifts = \App\Models\ShiftMap::orderBy('id', 'desc')->where('map_from_date', '<=', $date)->where('map_to_date', '>=', $date)->get();

        $shift_final = [];

        foreach ($shifts as $sm) {
            $shifts_user = \App\Models\ShiftMap::find($sm->id)->user_id;
            $shift_user = explode(',', $shifts_user);

            //dd($shift_user);

            foreach ($shift_user as $su) {
                if ($su == $user_id) {
                    $shift_map = \App\Models\ShiftMap::find($sm->id);

                    array_push($shift_final, $shift_map);
                }
            }
        }

        return $shift_final;
    }

    public static function make_name_slug($string)
    {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", '', $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", ' ', $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", '_', $string);

        return $string;
    }

    public static function getLocDetails($location)
    { //eg(location['latitude'])
        $api_key = 'AIzaSyBH1VwCF9e5KWE-_C9zl-7odmf4sTgWOgw';
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$location['latitude']},{$location['longitude']}&key={$api_key}";
        $api_loc = file_get_contents($url);
        $data = json_decode($api_loc, true);
        $address = $data['results']['0'];
        $location['street_name'] = $address['address_components']['1']['long_name'];
        $location['formatted_address'] = $address['formatted_address'];

        return $location;
    }

    public static function cur_leave_yr()
    {
        $current_yr = \App\Models\Leaveyear::where('current_year', '1')
                    ->where('org_id', \Auth::user()->org_id)
                    ->first();

        return $current_yr;
    }

    public static function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $dLat = ($lat2 - $lat1) *
                    M_PI / 180.0;
        $dLon = ($lon2 - $lon1) *
                    M_PI / 180.0;

        // convert to radians
        $lat1 = ($lat1) * M_PI / 180.0;
        $lat2 = ($lat2) * M_PI / 180.0;

        // apply formulae
        $a = pow(sin($dLat / 2), 2) +
             pow(sin($dLon / 2), 2) *
                 cos($lat1) * cos($lat2);
        $rad = 6371;
        $c = 2 * asin(sqrt($a));

        return $rad * $c;
    }

    public static function getAvatarAttribute($first, $second = '')
    {
        return new \YoHang88\LetterAvatar\LetterAvatar($first.' '.$second);
    }

    public static function authorizeOrg($attributes){
        if($attributes->org_id != \Auth::user()->org_id){
            abort(403);
        }
        return true;
    }


    public static function getReportFields($type){

        if($type == 'user')
        return $fieldIntable = ['email','departments','designations','phone','dob','work_station','division','position','first_line_manager','second_line_manager'];
        if($type == 'user_details')
        return $fieldInDetails = ['contract_start_date','contract_end_date','blood_group','father_name','mother_name','present_address','gender','bank_name','marital_status','bank_name','bank_account_name','bank_account_no','bank_account_branch','job_title','ethnicity','working_status'];

        if($type == 'computed')
        return $fieldComputed = ['age','tenure'];




    }



    public static function birthdayWishgreet(){


        return [

            'Hoping your birthday brings you many happy reasons to celebrate!',
            'Wishing you a great birthday and a memorable year. From all of us',
            'Wishing you the best on your birthday and everything good in the year ahead',
            'Hope your day is filled with happiness.',
            'Wishing you a happy birthday and a wonderful year',
            'Our whole team is wishing you the happiest of birthdays.',
            'Happy Birthday and all the best to you in the year to come!',
            'Wishing you a relaxing birthday and happiness in the year to come',
            'The whole team wishes you the happiest of birthdays and a great year.',

        ];




    }


    public static function  watchNewEvents(){


        $watch_status_key = date('Y-m-d').'-news_feed_watch';

        $greettemp = self::birthdayWishgreet();

        if(!\Cache::get($watch_status_key)){


            $postBirthDay = function(){

            $greettemp = self::birthdayWishgreet();

            $birthdayUser =  \App\User::orderBy('id', 'desc')->whereMonth('dob',date('m'))->whereDay('dob',date('d'))->get();

              foreach ($birthdayUser as $key => $value) {
                $temp = $greettemp[array_rand($greettemp)];

                $greeting = "Happy Birthday  {$value->first_name} {$value->last_name}, {$temp}";

                \App\Models\NewsFeed::create([
                    'user_id'=>1,
                    'body'=>$greeting,
                    'view_level'=>'dept',
                    'schedule'=>null,
                    'auto_created'=>1,

                ]);

              }
            };


            $postBirthDay();



        \Cache::put($watch_status_key, true, now()->addMinutes(1440));

        }
        return 1;

    }

public static function getRmbPurch($id){

        $total_rmb = DB::table('purch_order_details')->where('order_no', $id)->sum('rmb');


        return $total_rmb;

    }



     public static function getRmbPaidPurch($id){

        $total_rmb = DB::table('payments')->where('purchase_id', $id)->sum('rmb');



        return $total_rmb;


    }

public static function getPurchasePaymentAmountWithoutTds($id){

        $paid_amount = DB::table('payments')->where('purchase_id', $id)->sum('bill_amount');



        if ($paid_amount > 0) {

            return $paid_amount;

        }

        return 0.00;


    }

    public static function getNepaliDate($date){


        $nepCal = new  \App\Helpers\NepaliCalendar();

        return $nepCal->full_nepali_from_eng_date($date);


    }

public static function getPurchaseTDS($id)

    {

        $tds = DB::table('payments')->where('purchase_id', $id)->sum('tds');

        if ($tds > 0) {

            return $tds;

        }



        return 0.00;

    }

    public static function getUserOutlets(){


        if (\Auth::user()->hasRole('admins')) {
            $outlets = \App\Models\PosOutlets::get();
            // dd($outlets);
        } else {
            $outletusers = \App\Models\OutletUser::where('user_id', \Auth::user()->id)->get()->pluck('outlet_id');
            $outlets = \App\Models\PosOutlets::whereIn('id', $outletusers)
                ->orderBy('id', 'DESC')
                ->where('enabled', 1)
                ->get();
        }

        return $outlets;
    }

}
