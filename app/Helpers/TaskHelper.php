<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\Biom_log;
use App\Models\COAgroups;
use App\Models\COALedgers;
use App\Models\Entryitem;
use App\Models\Fiscalyear;
use App\Models\Lead;
use App\Models\LeaveApplication;
use App\Models\Projects;
use App\Models\ProjectTask;
use App\Models\ProjectUser;
use App\Models\UserTargetPivot;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Entry;

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


    public static function ShowRoom($id)
    {
        $query = \App\Models\Reservation::where('parent_res', $id)->get();
        $rooms = "";
        foreach ($query as $room) {
            $rooms .= $room->room_num . ($room->room->room_type->type_code ?? '') . " ";
        }
        return $rooms;
    }

    public static function ReservationTotalRoom($id)
    {

        $total = \App\Models\Reservation::where('parent_res', $id)->count();

        $total = $total + 1;

        return $total;
    }

    public static function ReservationDueAmount($id)
    {


        $total_folio_amount = \App\Models\Folio::where('reservation_id', $id)->sum('total_amount');
        $total_paid_amount = \App\Models\Payment::where('reservation_id', $id)->sum('amount');
        $total = $total_folio_amount - $total_paid_amount;

        return $total;
    }


    public static function getUser($user_id)
    {
        return DB::table('users')
        ->where('id', $user_id)
        ->first();
    }

    public static function GetOrgName($org_id)
    {
        return DB::table('organizations')
        ->where('id', $org_id)
        ->first();
    }

    public static function getOutletName($outlet_id)
    {
        $temp = \App\Models\PosOutlets::select('name')->where('id', $outlet_id)->first();

        return $temp->name;
    }

    public static function getReservationName($resv_id)
    {
        $temp =  \App\Models\Reservation::select('*')->where('id', $resv_id)->first();

        return $temp;
    }

    public static function GetTimeDifference($time_from, $time_to)
    {
        $from_time = strtotime($time_from);
        $to_time = strtotime($time_to);
        $difference = strtotime($time_to) - strtotime($time_from);

        return $difference;
    }

    public static function getUserName($user_id)
    {
        if (Session::has('userName_' . $user_id)) {
            return Session::get('userName_' . $user_id);
        } else {
            $temp = DB::table('users')
            ->where('id', $user_id)
            ->first();

            Session::put('userName_' . $user_id, $temp->first_name . ' ' . $temp->last_name);

            return $temp->first_name . ' ' . $temp->last_name;
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
            $ledger_code = $group_code . '-' . $new_index;
            //dd($ledger_code);
        } else {
            $ledger_code = $group_code . '-0001';
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

    public static function getFolioPaymentAmount($id)
    {

        $paid_amount = DB::table('payments')->where('reservation_id', $id)->sum('amount');

        if ($paid_amount > 0) {
            return $paid_amount;
        }

        return 0.00;
    }

    public static function getProfileImage($user_id)
    {
        if (Session::has('profileImage_' . $user_id)) {
            echo Session::get('profileImage_' . $user_id);
        } else {
            $temp = DB::table('users')
            ->select('image')
            ->where('id', $user_id)
            ->first();

            if ($temp->image != '') {
                Session::put('profileImage_' . $user_id, asset('/images/profiles/' . $temp->image));

                return asset('/images/profiles/' . $temp->image);
            } else {
                Session::put('profileImage_' . $user_id, asset('/images/logo.png'));

                return asset('/images/logo.png');
            }
        }
    }

    public static function userTarget($targetId, $courseId, $userId, $year)
    {
        $userTarget = UserTargetPivot::where('user_target_id', $targetId)->where('product_id', $courseId)->first();
        if ($userTarget) {
            $approvedLeads = Lead::where('user_id', $userId)->where('product_id', $courseId)->where('status_id', '27')->where('enabled', '1')->whereBetween('created_at', [$year . '-01-01 00:00:00', $year . '-12-30 23:59:59'])->count();
            //echo $approvedLeads; exit;
            /*if($courseId == '15' && $userId == '3' && $year == '2018')
            echo $approvedLeads; exit;*/
            if ($approvedLeads != 0) {
                if ($userTarget->target > $approvedLeads) {
                    return '<span style="color:green;">' . ($userTarget->target - $approvedLeads) . '</span> &nbsp;<span style="text-decoration: line-through; color:red;">' . $userTarget->target . '</span>';
                } else {
                    return '<span style="color:green;">' . ($approvedLeads - $userTarget->target) . '</span> &nbsp;<span style="text-decoration: line-through; color:red;">' . $userTarget->target . '</span>';
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

    public static function getPurchasePaymentAmountWithoutTds($id){

        $paid_amount = DB::table('payments')->where('purchase_id', $id)->sum('bill_amount');

        if ($paid_amount > 0) {
            return $paid_amount;
        }
        return 0.00;


    }

    public static function getUserAttendance($user_id, $date_in)
    {
        $start_date = $date_in . '-01';
        $end_date = date("Y-m-t", strtotime($start_date));
        $attendance = Attendance::where('user_id', $user_id)->where(
            'date_in',
            '>=',
            $start_date
        )->where('date_in', '<=', $end_date)->orderBy('date_in', 'asc')->get();

        return $attendance;
    }

    public static function getUserAttendanceHistroy($user_id, $date_in)
    {
        $date_in = explode('.', $date_in);

        $start_date = $date_in[0];
        $end_date = $date_in[1];
        $attendance = Attendance::where('user_id', $user_id)->where(
            'date_in',
            '>=',
            $start_date
        )->where('date_in', '<=', $end_date)->orderBy('date_in', 'asc')->get();

        return $attendance;
    }

    public static function findDays($start_date, $end_date)
    {
        $interval = date_diff(date_create($start_date), date_create($end_date));

        return $interval->format('%a') + 1;
    }

    public static function userLeave($userId, $leaveCatId, $year)
    {
        $userLeaves = LeaveApplication::select(DB::raw('sum(leave_days) as total'))->where('user_id', $userId)->where('leave_category_id', $leaveCatId)->where('application_status', '2')->where('leave_start_date', '>=', $year . '-01-01')->where('leave_start_date', '<=', $year . '-12-31')->first();

        //dd($userLeaves);

        if ($userLeaves && $userLeaves->total) {
            return $userLeaves->total;
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

        $url = 'http://ip-api.com/json/' . $ip;
        $tz = file_get_contents($url);
        $tz = json_decode($tz, true)['timezone'];

        return $tz;
    }

    public static function getLedger($id)
    {
        $entry_item_table = new Entryitem();
        $coa_table = new COALedgers();
        $prefix = '';

        if (\Request::get('fiscal_year')) {
            $current_fiscal = \App\Models\Fiscalyear::where('current_year', 1)->first();
            $fiscal_year = \Request::get('fiscal_year') ? \Request::get('fiscal_year') : $current_fiscal->fiscal_year;
            if ($fiscal_year != $current_fiscal->fiscal_year) {
                $prefix = Fiscalyear::where('fiscal_year', $fiscal_year)->first()->numeric_fiscal_year . '_';
            }
            $new_table = $prefix . $entry_item_table->getTable();
            $new_coa_table = $prefix . $coa_table->getTable();
            $entry_item_table->setTable($new_table);
            $coa_table->setTable($new_coa_table);
        }
        $rawentryitems = $entry_item_table->where('entry_id', $id)->get();
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
        $dr_detail = $coa_table->where('id', $dr_ledger_id)->first();

        $cr_detail = $coa_table->where('id', $cr_ledger_id)->first();

        $dr_name = '[' . $dr_detail->name . '] ' . $dr_detail->code;

        $cr_name = '[' . $cr_detail->name . '] ' . $cr_detail->code;

        if (strlen($dr_name) > 7) {
            $dr_name = substr($dr_name, 0, 10) . '..';
        }
        if (strlen($cr_name) > 7) {
            $cr_name = substr($cr_name, 0, 10) . '..';
        }

        /* if more than one ledger on dr / cr then add [+] sign */
        if ($dr_count > 1) {
            $dr_name = $dr_name . ' [+]';
        }
        if ($cr_count > 1) {
            $cr_name = $cr_name . ' [+]';
        }

        $ledgerstr = 'Dr ' . $dr_name . ' / ' . 'Cr ' . $cr_name;

        return $ledgerstr;
    }
    public static function getDynamicEntryLedger($id)
    {
        $entry_item_table = new Entryitem();
        $coa_table = new COALedgers();
        $prefix = '';

        if (\Session::get('selected_fiscal_year')) {
            $prefix = '';
            $current_fiscal_year = \App\Models\Fiscalyear::where('current_year', 1)->first();
            $selected_fiscal_year = \Session::get('selected_fiscal_year') ?? $current_fiscal_year->numeric_fiscal_year;

            if ($selected_fiscal_year != $current_fiscal_year->numeric_fiscal_year) {
                $prefix = $selected_fiscal_year . '_';
                $new_entry_items_table = $prefix . $entry_item_table->getTable();
                $entry_item_table->setTable($new_entry_items_table);
                $new_coa_table = $prefix . $coa_table->getTable();
                $coa_table->setTable($new_coa_table);
            }
        }
        $rawentryitems = $entry_item_table->where('entry_id', $id)->get();
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
        $dr_detail = $coa_table->where('id', $dr_ledger_id)->first();

        $cr_detail = $coa_table->where('id', $cr_ledger_id)->first();

        $dr_name = '[' . $dr_detail->name . '] ' . $dr_detail->code;

        $cr_name = '[' . $cr_detail->name . '] ' . $cr_detail->code;

        if (strlen($dr_name) > 7) {
            $dr_name = substr($dr_name, 0, 10) . '..';
        }
        if (strlen($cr_name) > 7) {
            $cr_name = substr($cr_name, 0, 10) . '..';
        }

        /* if more than one ledger on dr / cr then add [+] sign */
        if ($dr_count > 1) {
            $dr_name = $dr_name . ' [+]';
        }
        if ($cr_count > 1) {
            $cr_name = $cr_name . ' [+]';
        }

        $ledgerstr = 'Dr ' . $dr_name . ' / ' . 'Cr ' . $cr_name;

        return $ledgerstr;
    }

    public static function getFinalLedgerBalance($id, $opening_blc, $start_date, $end_date)
    {


        if ($id == '') {
            return 0;
        }
        $entry_item_table = new Entryitem();
        $prefix = '';

        if (\Request::get('fiscal_year')) {
            $current_fiscal = \App\Models\Fiscalyear::where('current_year', 1)->first();
            $fiscal_year = \Request::get('fiscal_year') ? \Request::get('fiscal_year') : $current_fiscal->fiscal_year;
            if ($fiscal_year != $current_fiscal->fiscal_year) {
                $prefix = Fiscalyear::where('fiscal_year', $fiscal_year)->first()->numeric_fiscal_year . '_';
            }
            $new_table = $prefix . $entry_item_table->getTable();
            $entry_item_table->setTable($new_table);
        }

        $op_total = 0;
        $total_amount['dc'] = $opening_blc['dc'];

        $total_amount['amount'] = $opening_blc['amount'];

//            $dr_total = 0;
//            $cr_total = 0;
//            $dr_total_dc = 0;
//            $cr_total_dc = 0;

        //Debit Amount
        $total = $entry_item_table->where('ledger_id', $id)
        ->leftjoin($prefix . 'entries', $prefix . 'entryitems.entry_id', '=', $prefix . 'entries.id')

//            ->join($prefix . 'entries', function ($join) use ($prefix) {
//                $join->on($prefix . 'entryitems.entry_id', '=', $prefix . 'entries.id');
//            })
        ->when($start_date, function ($q) use ($prefix, $start_date) {
            $q->where($prefix .'entries.date','>=',$start_date);
//                $q->whereDate($prefix . 'entryitems.created_at', '>=', $start_date);
        })
        ->when($end_date, function ($q) use ($prefix, $end_date) {
            $q->where($prefix .'entries.date','<=',$end_date);
//                $q->where($prefix . 'entryitems.created_at', '<=', $end_date);
        })
        ->where('entries.is_approved','=', 1)
        ->get();

//            dd($total);
        foreach ($total as $ei) {
            $total_amount = \TaskHelper::calculate_withdc($total_amount['amount'], $total_amount['dc'],
                $ei['amount'], $ei['dc']);
        }
//            if (empty($total)) {
//                $dr_total = 0;
//            } else {
//                $dr_total = $total;
//            }
//
//            //Credit Amount
//            $total = \App\Models\Entryitem::select('amount)')->where('ledger_id', $id)
//                ->when($start_date,function ($q) use ($start_date) {
//                    $q->whereDate('created_at','>=',$start_date);
//                })
//                ->when($end_date,function ($q) use ($end_date) {
//                    $q->whereDate('created_at','<=',$end_date);
//                })
//                    ->where('dc', 'C')->sum('amount');
//
//            if (empty($total)) {
//                $cr_total = 0;
//            } else {
//                $cr_total = $total;
//            }
//
//            /* Add opening balance */
//            if ($op_total_dc == 'D') {
//                $dr_total_dc = $op_total + $dr_total;
//                $cr_total_dc = $cr_total;
//            } else {
//                $dr_total_dc = $dr_total;
//                $cr_total_dc = $op_total + $cr_total;
//            }
//            /* $this->calculate and update closing balance */
//            $cl = 0;
//            $cl_dc = '';
//
//            $remaining_amt=$dr_total_dc-$cr_total_dc;
//
//            $cl = ['dc' => $remaining_amt>=0?'Dr ':'Cr ', 'amount' => abs($remaining_amt)];

        return $total_amount;
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
            $total = \App\Models\Entryitem::select('amount')->where('ledger_id', $id)->where('dc', 'D')
            ->leftJoin('entries','entries.id','=','entryitems.entry_id')
            ->where('entries.org_id',auth()->user()->org_id)
            ->where('entries.is_approved','=', 1)
            ->sum('amount');

            if (empty($total)) {
                $dr_total = 0;
            } else {
                $dr_total = $total;
            }

            //Credit Amount
            $total = \App\Models\Entryitem::select('amount)') ->leftJoin('entries','entries.id','=','entryitems.entry_id')
            ->where('entries.org_id',auth()->user()->org_id)->where('ledger_id', $id)->where('dc', 'C')->where('entries.is_approved','=', 1)
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
        $ledger_balance = $prefix . 'Dr' . ' ' . number_format($ledger_bal, 2) . $suffix;
    } elseif ($data1->cl->dc == 'C') {
        $ledger_balance = $prefix . 'Cr' . ' ' . number_format($ledger_bal, 2) . $suffix;
    } else {
        $ledger_balance = '-';
    }

    return $ledger_balance;
}

public static function getLedgerDebitCredit($id)
{
//         $ledgers=COALedgers::all();
//         $total = 0;
//             $dr_amount=0;
//             $cr_amount=0;
//         foreach ($ledgers as $key => $value) {
//
//             $dr_amount=$value['op_balance_dc'] == 'D'?($dr_amount+$value['op_balance']):($dr_amount+0);
//             $cr_amount=$value['op_balance_dc'] == 'C'?($cr_amount+$value['op_balance']):($cr_amount+0);
//             $entry_balance['amount'] = $value['op_balance'];
//             $entry_balance['dc'] = $value['op_balance_dc'];
//              $total=$entry_balance['op_balance_dc'] == 'C'?($total-$entry_balance['amount']):($total+$entry_balance['amount']);
//            $entry_items=\App\Models\Entryitem::where('ledger_id',$value['id'])->get();
////             if ($value['id']==1534)
////                 dd($value['op_balance']);
//            foreach ($entry_items as $key1 => $entry) {
//
//               $entry_balance = \TaskHelper::calculate_withdc($entry_balance['amount'],
//                     $entry_balance['dc'],
//                     $entry['amount'], $entry['dc']);
//                 if ($entry['dc']=='D')$dr_amount=$dr_amount+$entry['amount'];
//                 else $cr_amount=$cr_amount+$entry['amount'];
//                  $total=$entry['dc'] == 'C'?($total-$entry['amount']):($total+$entry['amount']);
//            }
//
//         }
//         dd(['total'=>$total,'cr'=>$cr_amount,'dr'=>$dr_amount]);

    if ($id == '') {
        return 0;
    }
    $start_date = \Request::get('start_date');

    $end_date = \Request::get('end_date');

    $ledgers = \App\Models\COALedgers::where('id', $id)->first();

    if (!$ledgers) {
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
        ->where(function ($query) use ($start_date, $end_date) {
            if ($start_date && $end_date) {
                return $query->where('entries.date', '>=', $start_date)
                ->where('entries.date', '<=', $end_date);
            }
        })->leftjoin('entries', 'entries.id', '=', 'entryitems.entry_id')
        ->where('entries.is_approved','=', 1)
        ->sum('amount');

//            if ($id==13)
//                dd($total);
        if (empty($total)) {
            $dr_total = 0;
        } else {
            $dr_total = $total;
        }

            //Credit Amount
        $total = \App\Models\Entryitem::select('entryitems.amount')->where('entryitems.ledger_id', $id)
        ->where('dc', 'C')
        ->where(function ($query) use ($start_date, $end_date) {
            if ($start_date && $end_date) {
                return $query->where('entries.date', '>=', $start_date)
                ->where('entries.date', '<=', $end_date);
            }
        })->leftjoin('entries', 'entries.id', '=', 'entryitems.entry_id')
        ->where('entries.is_approved','=', 1)
        ->sum('amount');
        if (empty($total)) {
            $cr_total = 0;
        } else {
            $cr_total = $total;
        }

//            if ($id==23)
//                dd($total);
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
            if (($cl->dc ?? '') == 'C') {
                $status = 'neg';
            }
        }

        /* Return closing balance */
        $cl = [
            'dc' => (string)$cl['dc'],
            'amount' => $cl['amount'],
            'status' => $status,
            'dr_total' => $dr_total,
            'cr_total' => $cr_total
        ];
    }

    $data1 = json_decode(json_encode($cl), true);
        //dd($data1);
    return $data1;
}


public static function getLedgerDrCr($ledger,$fiscal,$start_date=null,$end_date=null)
{

    if (!$ledger) {
        $cl = ['dr_total'=>0,'cr_total'=>0];
    } else {
        $ei_table=new \App\Models\Entryitem();
        $prefix='';

        if ($fiscal){
            $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
            $fiscal_year = $fiscal?  $fiscal->fiscal_year: $current_fiscal->fiscal_year ;
            if ($fiscal_year!=$current_fiscal->fiscal_year){
                $prefix=\App\Models\Fiscalyear::where('fiscal_year',$fiscal_year)->first()->numeric_fiscal_year.'_';
                $new_ei=$prefix.'entryitems';
                $ei_table->setTable($new_ei);
            }
        }

        $dr_cr_amount = $ei_table
        ->select(
            DB::raw('SUM(if (dc="D", '.$prefix.'entryitems.amount, 0)) as dr_amount'),
            DB::raw('SUM(if (dc="C", '.$prefix.'entryitems.amount, 0)) as cr_amount'),
        )
        ->where($prefix.'entryitems.ledger_id', $ledger->id)
        ->leftjoin($prefix.'entries', $prefix.'entryitems.entry_id', '=', $prefix.'entries.id')
        ->when($start_date&&$end_date,function ($q) use ($start_date,$end_date,$prefix) {
            $q->whereDate($prefix.'entries.date','>=',$start_date)
            ->whereDate($prefix.'entries.date','<=', $end_date);
        })
        ->where('entries.is_approved','=', 1)->first();
//            if($ledger->id==279)
//                dd($dr_cr_amount->dr_amount);
        $cl = ['dr_total'=>$dr_cr_amount->dr_amount,'cr_total'=>$dr_cr_amount->cr_amount];
    }
    return $cl;
}

public static function getOpeningInventoryDrCr($ledger,$fiscal,$start_date=null,$end_date=null)
{
    if (!$ledger) {
        $cl = ['dr_total'=>0,'cr_total'=>0];
    } else {
        $dr_cr_amount = \App\Models\StockAdjustment::selectRaw('sum(total_amount) as dr_amount')
        ->where('transaction_date','>=',$start_date)
        ->where('transaction_date','<=', $end_date)
        ->first();
        // dd($dr_cr_amount);

        $cl = ['dr_total'=>$dr_cr_amount->dr_amount, 'cr_total' => 0];
    }
    // dd($cl);
    return $cl;
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

public static function getLedgerClosing($opening,$dr_amount,$cr_amount){
    $dc1=self::calculate_withdc($opening['amount'],$opening['dc'],$dr_amount,'D');
    $final=self::calculate_withdc($dc1['amount'],$dc1['dc'],$cr_amount,'C');
    return $final;

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
    if (!$fiscal_year) {
        $fiscal_year = \FinanceHelper::cur_fisc_yr()->id;
    }
    $number = [];
    for ($i = 11; $i >= 0; $i--) {
        $date = new DateTime('now');
        $date->modify('first day of -' . $i . ' month');
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
        'entries.currency as currency'
    )
    ->rightJoin('coa_ledgers', 'entryitems.ledger_id', '=', 'coa_ledgers.id')
    ->rightJoin('coa_groups', 'coa_ledgers.group_id', '=', 'coa_groups.id')
    ->rightJoin('entries', 'entryitems.entry_id', '=', 'entries.id')
    ->where('entries.fiscal_year_id', $fiscal_year)
    ->groupBy('month')
    ->whereIn('coa_groups.id', $parent_ids)
    ->whereBetween('entries.date', [$startyear, $endyear])
    ->where('entries.is_approved','=', 1)
    ->get();

        //dd($q);

    return $q;
}

public static function getTotalMonthly($id, $fiscal_year = false)
{
    if (!$fiscal_year) {
        $fiscal_year = \FinanceHelper::cur_fisc_yr()->id;
    }
    $number = [];
    for ($i = 11; $i >= 0; $i--) {
        $date = new DateTime('now');
        $date->modify('first day of -' . $i . ' month');
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
        'entries.currency as currency'
    )
    ->rightJoin('coa_ledgers', 'entryitems.ledger_id', '=', 'coa_ledgers.id')
    ->rightJoin('coa_groups', 'coa_ledgers.group_id', '=', 'coa_groups.id')
    ->rightJoin('entries', 'entryitems.entry_id', '=', 'entries.id')
    ->where('entries.fiscal_year_id', $fiscal_year)
    ->groupBy('days')
    ->whereIn('coa_groups.id', $parent_ids)
    ->whereBetween('entries.date', [$firstmonthDate, $lastmonthDate])
    ->where('entries.is_approved','=', 1)
    ->get();

        //dd($q);

    return $q;
}


public static function getTotalByGroups($id,$start_date=null,$end_date=null,$fiscal)
{
    $start_date = \Request::get('start_date')??$start_date;

    $end_date = \Request::get('end_date')??$end_date;
    $ei_table=new Entryitem();
    $ledger_table=new COALedgers();
    $prefix='';

    if ($fiscal){
        $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
        $fiscal_year = $fiscal?  $fiscal->fiscal_year: $current_fiscal->fiscal_year ;
        if ($fiscal_year!=$current_fiscal->fiscal_year){
            $prefix=Fiscalyear::where('fiscal_year',$fiscal_year)->first()->numeric_fiscal_year.'_';
            $new_ei=$prefix.'entryitems';
            $new_coa=$prefix.'coa_ledgers';
            $ei_table->setTable($new_ei);
            $ledger_table->setTable($new_coa);
        }
    }

        // build our category list only once
    $cats = [];

    $cats = \App\Models\COAgroups::orderBy('id', 'asc')->get();

    $parent_ids = \TaskHelper::buildTree($cats, $id);

    $cont = count($parent_ids);



    $parent_ids[$cont] = $id;
//        $parent_id=[1,5,6,13,14,15,16,17,18,19,20,21,22,50,51,52,53,56,57,58,59,67,70,71,2,7,72,73,23,24,25,61,69,60,62,63,3,11,68,4,12,64,65,66,74,75,76];


    $dr_cr_amount = $ei_table
    ->select(
        DB::raw('SUM(if (dc="D", '.$prefix.'entryitems.amount, 0)) as dr_amount'),
        DB::raw('SUM(if (dc="C", '.$prefix.'entryitems.amount, 0)) as cr_amount'),
    )
    ->leftJoin($prefix.'coa_ledgers', $prefix.'entryitems.ledger_id', '=', $prefix.'coa_ledgers.id')
    ->leftjoin($prefix.'entries', $prefix.'entryitems.entry_id', '=', $prefix.'entries.id')
    ->whereIn($prefix.'coa_ledgers.group_id', $parent_ids)
    ->when($start_date&&$end_date,function ($q) use ($start_date,$end_date,$prefix) {
        $q->whereDate($prefix.'entries.date','>=',$start_date)
        ->whereDate($prefix.'entries.date','<=', $end_date)
        ->where($prefix.'entries.is_approved','=', 1);
    })->first();

    $opening_balance=$ledger_table->select(
        DB::raw('SUM(if (op_balance_dc="D", op_balance, 0)) - SUM(if (op_balance_dc="C", op_balance, 0)) as opening_balance')
    )->whereIn('group_id',$parent_ids)->first();

    $opening_entry_items=[];
    if ($start_date){
        $opening_entry_items=$ei_table->leftjoin($prefix.'entries', $prefix.'entryitems.entry_id', '=', $prefix.'entries.id')
        ->leftJoin($prefix.'coa_ledgers', $prefix.'entryitems.ledger_id', '=', $prefix.'coa_ledgers.id')
        ->where(function ($q) use ($parent_ids,$prefix) {
            $q->whereIn($prefix.'coa_ledgers.group_id',$parent_ids);
        })
        ->when($start_date,function ($q) use ($fiscal, $start_date,$prefix) {
            $q->whereDate($prefix.'entries.date','<',$start_date);
            $q->whereDate($prefix.'entries.date','>=',$fiscal->start_date);
            $q->where($prefix.'entries.is_approved','=', 1);

        })->get();
    }
    $op_blc['amount']=abs($opening_balance->opening_balance);
    $op_blc['dc']=$opening_balance->opening_balance>0?'D':'C';
    foreach ($opening_entry_items as $ei) {
        $op_blc = \App\Helpers\TaskHelper::calculate_withdc(abs($op_blc['amount']), $op_blc['dc'],
            $ei['amount'], $ei['dc']);
    }

    $result = ['dr_amount'=>$dr_cr_amount->dr_amount,'cr_amount'=>$dr_cr_amount->cr_amount,'opening_balance'=>$op_blc];


    return $result;
}
public static function getDrCrByGroups($id,$start_date=null,$end_date=null,$fiscal)
{
    $start_date = \Request::get('start_date')??$start_date;

    $end_date = \Request::get('end_date')??$end_date;
    $ei_table=new Entryitem();
    $ledger_table=new COALedgers();
    $prefix='';

    if ($fiscal){
        $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
        $fiscal_year = $fiscal?  $fiscal->fiscal_year: $current_fiscal->fiscal_year ;
        if ($fiscal_year!=$current_fiscal->fiscal_year){
            $prefix=Fiscalyear::where('fiscal_year',$fiscal_year)->first()->numeric_fiscal_year.'_';
            $new_ei=$prefix.'entryitems';
            $new_coa=$prefix.'coa_ledgers';
            $ei_table->setTable($new_ei);
            $ledger_table->setTable($new_coa);
        }
    }
        // build our category list only once
    $cats = [];

    $cats = \App\Models\COAgroups::orderBy('id', 'asc')->get();

    $parent_ids = \TaskHelper::buildTree($cats, $id);

    $cont = count($parent_ids);



    $parent_ids[$cont] = $id;
    $dr_cr_amount = $ei_table
    ->select(
        DB::raw('SUM(if (dc="D", '.$prefix.'entryitems.amount, 0)) as dr_amount'),
        DB::raw('SUM(if (dc="C", '.$prefix.'entryitems.amount, 0)) as cr_amount'),
    )
    ->leftJoin($prefix.'coa_ledgers', $prefix.'entryitems.ledger_id', '=', $prefix.'coa_ledgers.id')
    ->leftjoin($prefix.'entries', $prefix.'entryitems.entry_id', '=', $prefix.'entries.id')
    ->whereIn($prefix.'coa_ledgers.group_id', $parent_ids)
    ->when($start_date&&$end_date,function ($q) use ($start_date,$end_date,$prefix) {
        $q->whereDate($prefix.'entries.date','>=',$start_date)
        ->whereDate($prefix.'entries.date','<=', $end_date)
        ->where($prefix.'entries.is_approved','=', 1);
    })->first();
    if ($id==286)
        dd($dr_cr_amount);
    $result = ['dr_amount'=>$dr_cr_amount->dr_amount,'cr_amount'=>$dr_cr_amount->cr_amount];


    return $result;
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
        Session::flash('fail', 'Failed to connect to MySQL: ' . mysqli_connect_error());

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
        $result = mysqli_query($con, 'SELECT * FROM ' . $table);
        $num_fields = mysqli_num_fields($result);

            //$return.= 'DROP TABLE '.$table.';';
        $row2 = mysqli_fetch_row(mysqli_query($con, 'SHOW CREATE TABLE ' . $table));
        $return .= "\n\n" . str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $row2[1]) . ";\n\n";

        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = mysqli_fetch_row($result)) {
                $return .= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = preg_replace("/\n/", '\\n', $row[$j]);
                    if (isset($row[$j])) {
                        $return .= '"' . $row[$j] . '"';
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

    $backup_name = date('Y-m-d-His') . '.sql';
        //save file
    $handle = fopen(storage_path('laravel-backups') . '/' . $backup_name, 'w+');

    fwrite($handle, $return);
    fclose($handle);

    return $backup_name;
}

public static function getBiometricUserAttendance($userid, $date_in)
{
    $date_start = $date_in . '-01';
    $totaldays = \Carbon\Carbon::parse($date_start)->daysInMonth;
    $date_end = $date_in . '-' . $totaldays;
    $attendance = Biom_log::where('machine_userid', $userid)->where('datetime', '>=', $date_start)->where('datetime', '<=', $date_end)->orderBy('datetime', 'asc')->get();

    return $attendance;
}

public static function getTranslations($id)
{
    $transations = DB::table('product_stock_moves')
    ->where('product_stock_moves.stock_id', $id)
    ->leftjoin('products', 'products.id', '=', 'product_stock_moves.stock_id')
    ->leftjoin('pos_outlets', 'pos_outlets.id', '=', 'product_stock_moves.store_id')
    ->select('product_stock_moves.*', 'products.name', 'pos_outlets.name')
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
        $total_stock = $StockIn + $StockOut;
        
    }
    return $total_stock;
   
}

public static function new_getTranslations($id,$store_id=0)
{
    $transations = DB::table('product_stock_moves')
    ->where('product_stock_moves.stock_id', $id)
    ->where('product_stock_moves.store_id', $store_id)
    ->leftjoin('products', 'products.id', '=', 'product_stock_moves.stock_id')
    ->leftjoin('pos_outlets', 'pos_outlets.id', '=', 'product_stock_moves.store_id')
    ->select('product_stock_moves.*', 'products.name', 'pos_outlets.name')
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
    if (count($q) > 0) {
        $codes = [];
        foreach ($q as $c) {
            $code = $c->code;
            $codearr = explode('-', $code);
            array_push($codes, (int)$codearr[count($codearr) - 1]);
        }
        $new_index = max($codes) + 1;
        $new_index = sprintf("%04d", $new_index);
        return $group_code . "-" . $new_index;
    } else {

        return $group_code . "-0001";
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
        // if ($request->type == 1) {
        //     $detail->type = $request->type;
        // } else {
    $detail->type = 0;
        // }
        // if ($request->reconciliation == 1) {
        //     $detail->type = $request->reconciliation;
        // } else {
    $detail->reconciliation = 0;
        // }
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

    foreach ($topmenu as $k => $v) {
        echo '<a href="' . $v['url'] . '">' . $v['name'] . '</a>';
        if (next($copy)) {
                echo ' | '; // Add comma for all elements instead of last
            }
        }
    }

    public static function RoomCharges($id)
    {

        $folios = \App\Models\Folio::where('reservation_id', $id)->get();
        $total_amount = 0;

        foreach ($folios as $folio) {
            $total = \App\Models\FolioDetail::where('folio_id', $folio->id)->whereIn('flag', ['accommodation', 'discount'])->sum('total');
            $total_amount = $total_amount + $total;
        }

        return $total_amount;
    }

    public static function OtherCharges($id)
    {

        $folios = \App\Models\Folio::where('reservation_id', $id)->get();
        $total_amount = 0;

        foreach ($folios as $folio) {

            $total = \App\Models\FolioDetail::where('folio_id', $folio->id)->whereIn('flag', ['extracharge', 'restaurant'])->sum('total');

            $total_amount = $total_amount + $total;
        }

        return $total_amount;
    }

    public static function CreditAmount($id)
    {

        $total_folio_amount = \App\Models\Folio::where('reservation_id', $id)->sum('total_amount');

        $total_paid_amount = \App\Models\Payment::where('reservation_id', $id)->sum('amount');

        $balance = $total_folio_amount - $total_paid_amount;

        $data = ['credit' => $total_folio_amount, 'balance' => $balance];

        return $data;
    }

    public static function rmNights($id)
    {

        $today_date = \Carbon\Carbon::today()->format('Y-m-d');
        $week_earlier = \Carbon\Carbon::today()->subDays(7)->format('Y-m-d');

        $reservation = \App\Models\Reservation::where('check_in', '<=', $today_date)->where('check_in', '>=', $week_earlier)->where('source_id', $id)->whereIn('reservation_status', ['3', '6'])->get();

        //dd($reservation);

        $total_days = 0;
        foreach ($reservation as $rev) {

            if ($rev->check_out <= $today_date) {

                $total_days = $total_days + $rev->number_of_days;
            } else {

                $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $rev->check_in . '00:00:00');

                if (\Carbon\Carbon::now()->diffInDays($from)) {

                    $total_days = $total_days + \Carbon\Carbon::now()->diffInDays($from);
                } else {

                    $total_days = $total_days + 1;
                }
            }
        }

        //dd($reservation);

        return $total_days;
    }

    public static function ADR($id)
    {

        $today_date = \Carbon\Carbon::today()->format('Y-m-d');
        $week_earlier = \Carbon\Carbon::today()->subDays(7)->format('Y-m-d');

        $reservation = \App\Models\Reservation::where('check_in', '<=', $today_date)->where('check_in', '>=', $week_earlier)->where('source_id', $id)->whereIn('reservation_status', ['3', '6'])->get();

        //dd($reservation);

        $total_rooms_sold = 0;
        $total_rooms_revenue = 0;
        foreach ($reservation as $rev) {

            if ($rev->check_out <= $today_date) {

                $total_rooms_sold = $total_rooms_sold + $rev->number_of_days;
                $total_rooms_revenue = $total_rooms_revenue + ($rev->room_rate * $rev->number_of_days);
            } else {

                $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $rev->check_in . '00:00:00');

                if (\Carbon\Carbon::now()->diffInDays($from)) {

                    $total_rooms_sold = $total_rooms_sold + \Carbon\Carbon::now()->diffInDays($from);
                    $total_rooms_revenue = $total_rooms_revenue + $rev->room_rate * \Carbon\Carbon::now()->diffInDays($from);
                } else {

                    $total_rooms_sold = $total_rooms_sold + 1;
                    $total_rooms_revenue = $total_rooms_revenue + (1 * $rev->room_rate);
                }
            }
        }

        if ($total_rooms_revenue == 0 && $total_rooms_sold == 0) {

            $adr = 0;
        } else {
            $adr = $total_rooms_revenue / $total_rooms_sold;
        }

        //dd($total_rooms_sold);


        return $adr;
    }

    public static function GRR($id)
    {

        return $id;
    }

    public static function checkcustomerontable($table_id)
    {


        $check_blank = \App\Models\OrderProxy::orderBy('id', 'desc')->where('table', $table_id)->whereNotIn('ready_status', ['checkedout'])->whereNull('is_splitted')->first();

        // dd($check_blank);
        if ($check_blank) {
            if ($check_blank->ready_status == 'merged') {

                return ['status' => 2, 'order' => $check_blank];
            } elseif ($check_blank->ready_status != 'checkedout') {
                return 0;
            } else {

                return 1;
            }
        } else {
            return 1;
        }


    }

    public static function getSplittedBill()
    {


    }


    public static function getOrderIdfromTable($table_id)
    {

        $order = \App\Models\OrderProxy::orderBy('id', 'desc')->where('table', $table_id)->where('ready_status', '!=', 'checkedout')
        ->whereNull('is_splitted')
        ->first();
        // dd($check_blank);
        return $order->id;
    }

    public static function getOrderProxyTotalfromTable($table_id)
    {

        $order = \App\Models\OrderProxy::orderBy('id', 'desc')->where('table', $table_id)->where('ready_status', '!=', 'checkedout')
        ->whereNull('is_splitted')
        ->first();
        // dd($check_blank);
        return $order->total_amount ?? '';
    }

    public static function getOrderTotalfromTable($table_id)
    {

        $order = \App\Models\Orders::orderBy('id', 'desc')->where('table', $table_id)->where('ready_status', '!=', 'checkedout')->first();
        // dd($check_blank);
        return $order->total_amount;
    }

    public static function getTables($table_area)
    {
        $tables = \App\Models\PosTable::where('table_area_id', $table_area)
        ->where('enabled', 1)
        ->groupBy('id')
        ->get();

        return $tables;
    }


    public static function spilltedOrderByArea($table_area)
    {

        $orders = \App\Models\OrderProxy::with('restauranttable')->select('fin_orders_proxy.*')
        ->leftjoin('pos_tables', 'fin_orders_proxy.table', '=', 'pos_tables.id')
        ->where('pos_tables.table_area_id', $table_area)
        ->where('fin_orders_proxy.ready_status', '!=', 'checkedout')
        ->where('fin_orders_proxy.is_splitted', '1')
        ->get();
        //return [];
        // dd($orders,$table_area);
        return $orders;


    }

    public static function tablefromlist($tables, $outlet_id)
    {

        //  dd($tables);

        $datafrom = [];
        foreach ($tables as $table) {
            //dd($table->id);
            $check_blank = \App\Models\OrderProxy::orderBy('id', 'desc')->where('table', $table->id)->where('ready_status', '!=', 'checkedout')->whereNull('is_splitted')->first();
            if (count($check_blank) > 0) {
                if ($check_blank->ready_status != 'checkedout') {
                    $array = ['table_id' => $table->id, 'outlet_id' => $outlet_id];
                    array_push($datafrom, $array);
                }
            }
        }
        return $datafrom;
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

    public static function tabletolist($tables, $outlet_id)
    {

        $datato = [];
        foreach ($tables as $table) {
            //dd($table->id);
            $check_blank = \App\Models\OrderProxy::orderBy('id', 'desc')->where('table', $table->id)->where('ready_status', '!=', 'checkedout')->whereNull('is_splitted')->first();
            if (count($check_blank) > 0) {
                if ($check_blank->ready_status != 'checkedout') {
                } else {
                    $array = ['table_id' => $table->id, 'outlet_id' => $outlet_id];
                    array_push($datato, $array);
                }
            } else {
                $array = ['table_id' => $table->id, 'outlet_id' => $outlet_id];
                array_push($datato, $array);
            }
        }

        return $datato;
    }

    public static function change_eng_nepdateFormatted($date)
    {

        $cal = new \App\Helpers\NepaliCalendar();
        $exp = explode("-", $date);
        $converted = $cal->eng_to_nep($exp[0], $exp[1], $exp[2]);
        $nepdate = $converted['year'] . '-' . $converted['nmonth'] . '-' . $converted['date'];
        return $nepdate;
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

    public static function minutesToHours($minutes)
    {
        $hours = (int)($minutes / 60);
        $minutes -= $hours * 60;
        return sprintf("%d:%02.0f", $hours, $minutes);
    }

    public static function authorizeOrg($attributes)
    {
        if ($attributes->org_id != \Auth::user()->org_id) {
            abort(403);
        }
        return true;
    }

    public static function getAvatarAttribute($first, $second = '')
    {
        return new \YoHang88\LetterAvatar\LetterAvatar($first . ' ' . $second);
    }


    public static function getReportFields($type)
    {

        if ($type == 'user')
            return $fieldIntable = ['email', 'departments', 'designations', 'phone', 'dob', 'work_station', 'division', 'position', 'first_line_manager', 'second_line_manager'];
        if ($type == 'user_details')
            return $fieldInDetails = ['contract_start_date', 'contract_end_date', 'blood_group', 'father_name', 'mother_name', 'present_address', 'gender', 'bank_name', 'marital_status', 'bank_name', 'bank_account_name', 'bank_account_no', 'bank_account_branch', 'job_title', 'ethnicity', 'working_status'];

        if ($type == 'computed')
            return $fieldComputed = ['age', 'tenure'];


    }


    public static function cur_leave_yr()
    {
        $current_yr = \App\Models\Leaveyear::where('current_year', '1')
        ->where('org_id', \Auth::user()->org_id)
        ->first();

        return $current_yr;
    }


    public static function getCarryForwardLeave($userId, $leaveyear = false)
    {

        if (!$leaveyear) {
            $currentLeave = self::cur_leave_yr();
        }


        $forwardLeave = \App\Models\LeaveCarryForward::where('from_leave_year_id', $currentLeave->id)->where('user_id', $userId)->first();

        return $forwardLeave->num_of_carried ?? 0;

    }

    public static function getNepaliDate($date)
    {


        $nepCal = new  \App\Helpers\NepaliCalendar();

        return $nepCal->full_nepali_from_eng_date($date);


    }

    public static function getNepaliDateFormatted($date)
    {


        $nepCal = new  \App\Helpers\NepaliCalendar();

        return $nepCal->formated_nepali_from_eng_date($date);


    }
    public static function getDynamicEntryLedgerName($id)
    {
        $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();

        $fiscal_year = \Request::get('fiscal_year')?  \Request::get('fiscal_year'): $current_fiscal->fiscal_year ;
        $selected_fiscal_year= Fiscalyear::where('fiscal_year',$fiscal_year)->first();

        $prefix='';
        if ($fiscal_year!=$current_fiscal->fiscal_year){
            $prefix=$selected_fiscal_year->numeric_fiscal_year.'_';
        }
        $coa_table = new COALedgers();
        $new_ledger_table=$prefix.$coa_table->getTable();
        $coa_table->setTable($new_ledger_table);

        $ledger = $coa_table->where('id', $id)->first();

        return $ledger;
    }


    public static function getSalesByPaymethod($start_date, $end_date)
    {

        $totalSalesCount = \App\Models\Orders::select('fin_orders.*')
        ->where('fin_orders.bill_date', '>=', $start_date)
        ->where('fin_orders.bill_date', '<=', $end_date)
        ->where(function ($query) {
            if (\Request::get('outlet_id')) {
                return $query->where('outlet_id', \Request::get('outlet_id'));
            }

        })
        ->get();

        $totalSales = \App\Models\Orders::with('payments')->select('fin_orders.*', 'fin_orders_meta.is_bill_active')
        ->where('fin_orders.bill_date', '>=', $start_date)
        ->where('fin_orders.bill_date', '<=', $end_date)
        ->where(function ($query) {

            if (\Request::get('outlet_id')) {
                return $query->where('fin_orders.outlet_id', \Request::get('outlet_id'));
            }

        })
        ->leftjoin('fin_orders_meta', 'fin_orders.id', '=', 'fin_orders_meta.order_id')
        ->groupBy('fin_orders.id')
        ->get();


        $amountSummary = [
            'totalSalesCount' => count($totalSalesCount),
            'totalDiscount' => 0,
            'totalSubtotal' => 0,
            'netSalesCount' => 0,
            'service_charge' => 0,
            'taxable_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => 0,
            'coverCounts' => 0
        ];


        $paid_by_total = [
            'cash' => 0,
            'city-ledger' => 0,
            'credit-cards' => 0,
            'check' => 0,
            'travel-agent' => 0,
            'complementry' => 0,
            'staff' => 0,
            'room' => 0,
            'e-sewa' => 0,
            'others' => 0,
            'edm' => 0,
            'mnp' => 0,
        ];

        $totalByPaid = 0;

        foreach ($totalSales as $key => $value) {

            if ($value->is_bill_active == '1') {

                $amountSummary['totalSubtotal'] += (float)$value->subtotal;
                $amountSummary['totalDiscount'] += (float)$value->discount_amount;
                $amountSummary['service_charge'] += (float)$value->service_charge;
                $amountSummary['taxable_amount'] += (float)$value->taxable_amount;
                $amountSummary['tax_amount'] += (float)$value->tax_amount;
                $amountSummary['total_amount'] += (float)$value->total_amount;
                $amountSummary['netSalesCount']++;
                $amountSummary['coverCounts'] += (float)$value->covers;

                $paid_by = $value->payments;


                $paid_by_total['cash'] += $paid_by->where('paid_by', 'cash')->sum('amount');

                $paid_by_total['city-ledger'] += $paid_by->where('paid_by', 'city-ledger')->sum('amount');

                $paid_by_total['credit-cards'] += $paid_by->where('paid_by', 'credit-cards')->sum('amount');

                $paid_by_total['check'] += $paid_by->where('paid_by', 'check')->sum('amount');

                $paid_by_total['complementry'] += $paid_by->where('paid_by', 'complementry')->sum('amount');

                $paid_by_total['staff'] += $paid_by->where('paid_by', 'staff')->sum('amount');

                $paid_by_total['room'] += $paid_by->where('paid_by', 'room')->sum('amount');

                $paid_by_total['e-sewa'] += $paid_by->where('paid_by', 'e-sewa')->sum('amount');

                $paid_by_total['edm'] += $paid_by->where('paid_by', 'edm')->sum('amount');

                $paid_by_total['mnp'] += $paid_by->where('paid_by', 'mnp')->sum('amount');

                $otherPayments = array_keys($paid_by_total);

                $paid_by_total['others'] += $paid_by->whereNotIn('paid_by', $otherPayments)->sum('amount');


                $totalByPaid += $value->total_amount;
            }

        }

        return ['amountSummary' => $amountSummary, 'paid_by_total' => $paid_by_total, 'totalByPaid' => $totalByPaid];
    }

    public static function getTotalByOutlet($engdate)
    {
        $start_date=\Request::get('start_date');
        $end_date=\Request::get('end_date');
        $totalSales = \App\Models\Orders::select(
            DB::raw('SUM(fin_orders.taxable_amount) as taxable_total,SUM(fin_orders.discount_amount) as discount_total,SUM(fin_orders.service_charge) as service_total'))
//                'fin_orders.*,fin_order_detail.product_id,fin_order_detail.order_id,
//        sum(fin_orders.taxable_amount) as taxable_total,sum(fin_orders.discount_amount) as discount_total,sum(fin_orders.service_charge) as service_total')
        ->whereDate('fin_orders.bill_date','>=',$engdate)
        ->whereDate('fin_orders.bill_date','<=',$end_date)
//            ->leftJoin('fin_order_detail','fin_order_detail.order_id','=','fin_orders.id')
//            ->where('fin_order_detail.product_id','!=',0)

        ->where(function($query){
            if(\Request::get('outlet_id')){
                return $query->where('fin_orders.outlet_id',\Request::get('outlet_id'));
            }
        })
        ->first();

        return $totalSales;

    }

    public static function getDateDifferenceFromToday($end_date, $standing_date)
    {
        $date1 = $standing_date;
        $date2 = Carbon::parse($end_date);
        $interval = $date1->diff($date2);
        $days = $interval->format('%a');
        return $days;

    }


    public static function get_ledger_ids($parentId)
    {
        $ledgers_data = collect();
        $parent = COAgroups::find($parentId);
        $ledgers_data = COALedgers::where('group_id', $parentId)->where('org_id', \Auth::user()->org_id)->get();
        // dd($parent);
        $subcategories = COAgroups::where('parent_id', $parent->id)->where('org_id', \Auth::user()->org_id)->get();

        foreach ($subcategories as $subcategory) {
            $ledgers_data = $ledgers_data->merge(COALedgers::where('group_id', $subcategory->id)->where('org_id', \Auth::user()->org_id)->get());
            $ledgers_data = \App\Helpers\TaskHelper::get_ledger_next_level($subcategory->id, $ledgers_data);
        }

        return ($ledgers_data->pluck(id));
    }

    public static function get_ledger_next_level($parentId, $ledgers_data)
    {
        $parent = COAgroups::find($parentId);
        $ledgers_data = $ledgers_data->merge(COALedgers::where('group_id', $parentId)->where('org_id', \Auth::user()->org_id)->get());
        $subcategories = COAgroups::where('parent_id', $parent->id)->where('org_id', \Auth::user()->org_id)->get();

        foreach ($subcategories as $subcategory) {
            $ledgers_data = $ledgers_data->merge(COALedgers::where('group_id', $subcategory->id)->where('org_id', \Auth::user()->org_id)->get());
            \App\Helpers\TaskHelper::get_ledger_next_level($subcategory->id, $ledgers_data);
        }
        return $ledgers_data;
    }

    public static function getLedgerTotal($id)
    {
        $entry_items = \App\Models\Entryitem::where('ledger_id', $id)->get();

        $entry_balance = '';

        foreach ($entry_items as $ei) {

            $entry_balance = \App\Helpers\TaskHelper::calculate_withdc($entry_balance['amount'], $entry_balance['dc'],
                $ei['amount'], $ei['dc']);
        }

        return $entry_balance;
    }


    public static function getTotalByGroupsaffectsGross($id, $affects_gross)
    {

        $number = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = new DateTime('now');
            $date->modify('first day of -' . $i . ' month');
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

        $cats = \App\Models\COAgroups::orderBy('id', 'asc')->where('affects_gross', $affects_gross)->where('org_id', auth()->user()->org_id)->get();

        $mytime = \Carbon\Carbon::now();

        $thisyear = date('Y', strtotime($mytime));

        $startOfYear = $mytime->copy()->startOfYear();

        $startyear = date('Y-m-d', strtotime($startOfYear));

        //dd($startyear);

        $endOfYear = $mytime->copy()->endOfYear();
        $endyear = date('Y-m-d', strtotime($endOfYear));

        if ($start_date && $end_date) {
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
        ->where('entries.is_approved','=', 1)
        ->get();
        // if ($q[0]->dr_amount == $q[0]->cr_amount) {
        //     $q[0]->dr_amount = 0;
        //     $q[0]->cr_amount = 0;
        // }
        $q = json_decode(json_encode($q), true);

        return $q;
    }

    public static function getTotalByGroupsId($Id)
    {
        set_time_limit(0);
        $ledgers_ids = \App\Helpers\TaskHelper::get_ledger_ids($Id);

        // $total = $total = \App\Models\Entryitem::select('entryitems.amount')->whereIn('entryitems.ledger_id', $ledgers_ids)->where('dc', 'D')

        //       ->sum('entryitems.amount');


        //  return $total;

        //   dd($total[0]->dr_amount);


        $ledgers_ids = \App\Helpers\TaskHelper::get_ledger_ids($Id);
        $dr_sum = 0;
        $cr_sum = 0;
        foreach ($ledgers_ids as $key => $value) {
            $closing_balance = \App\Helpers\TaskHelper::getLedgerTotal($value);
            if ($closing_balance['dc'] == 'D') {
                $dr_sum += $closing_balance['amount'];
            } else {
                $cr_sum += $closing_balance['amount'];
            }
        }
        return (['dr_amount' => $dr_sum, 'cr_amount' => $cr_sum]);


    }
    public static function getLedgersOpeningBalance($ledger,$start_date,$fiscal){
        $ei_table=new \App\Models\Entryitem();
        $ledger_table=new \App\Models\COALedgers();
        $prefix='';

        if ($fiscal){
            $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
            $fiscal_year = $fiscal?  $fiscal->fiscal_year: $current_fiscal->fiscal_year ;
            if ($fiscal_year!=$current_fiscal->fiscal_year){
                $prefix=\App\Models\Fiscalyear::where('fiscal_year',$fiscal_year)->first()->numeric_fiscal_year.'_';
                $new_ei=$prefix.'entryitems';
                $new_coa=$prefix.'coa_ledgers';
                $ei_table->setTable($new_ei);
                $ledger_table->setTable($new_coa);
            }
        }

        $entry_items=[];
        if ($start_date) {
            $entry_items =$ei_table->where('ledger_id', $ledger->id)
            ->leftJoin($prefix.'entries', $prefix.'entryitems.entry_id', '=', $prefix.'entries.id')
            ->when($start_date, function ($q) use ($fiscal, $start_date,$prefix) {
                $q->whereDate($prefix.'entries.date', '<', $start_date);
                $q->whereDate($prefix.'entries.date', '>=', $fiscal->start_date);
                $q->where($prefix.'entries.is_approved','=', 1);
            })->get();
        }
        return self::check_opening_balance1($ledger,$entry_items);

    }
    public static function check_opening_balance1($ledgers_data, $entry_items)
    {
        $entry_balance['amount'] = $ledgers_data['op_balance'] ?? '';
        $entry_balance['dc'] = $ledgers_data['op_balance_dc'] ?? '';

        foreach ($entry_items as $ei) {
            $entry_balance = \App\Helpers\TaskHelper::calculate_withdc($entry_balance['amount'], $entry_balance['dc'],
                $ei['amount'], $ei['dc']);
        }
        // dd($entry_balance);
        return $entry_balance;
    }
    public static function generateIdnew($alias)
    {
        $numbering=\App\Models\Entrytype::where('label',$alias)->first();
        // dd($numbering);
        if($numbering) {
            return self::generateAndCheckDuplicatenew($numbering,strtoupper($numbering->code));
        }
        return 0;
    }

    private static function generateAndCheckDuplicatenew($numbering,$code)
    {
        $generated_id = '';
        if ($code) {

                $latest_row = Entry::where('number', 'like', $code.'-' . '%')->orderby('id','desc')->first();
// dd($latest_row);
            $new_number = 1;
            if ($latest_row) {
                $explode = explode('-', $latest_row->number);
                $new_number = $explode[1] + 1;
            }

            $formatted_number = function ($number) {
                return str_pad(($number), 4, '0', STR_PAD_LEFT);
            };

            $generated_id = $code . '-' . $formatted_number($new_number);
            while (Entry::where('number', $generated_id)->exists()) {
                $new_number++;
                $generated_id = $code . '-' . $formatted_number($new_number);
            }
        }
        return $generated_id;
    }

    public static function generateId($type)
    {
        if ($type) {
            return self::generateAndCheckDuplicate($type);
        }
        return 0;
    }

    private static function generateAndCheckDuplicate($type)
    {
        $generated_id = '';
        if ($type) {
            $current_fiscal_year = \App\Models\Fiscalyear::where('current_year', 1)->first();
            $selected_fiscal_year = \Session::get('selected_fiscal_year') ?? $current_fiscal_year->numeric_fiscal_year;

            $prefix = '';
            $entries_table = new \App\Models\Entry();

            if ($selected_fiscal_year != $current_fiscal_year->numeric_fiscal_year) {
                $prefix = $selected_fiscal_year . '_';
                $new_entries_table = $prefix . $entries_table->getTable();
                $entries_table->setTable($new_entries_table);
            }

            $latest_row = $entries_table->latest()->where('number', 'like', $type->code . '%')->first();
            $new_number = 1;
            if ($latest_row) {
                $explode = explode('-', $latest_row->number);
                $new_number = $explode[1] + 1;
            }

            $formatted_number = function ($number) {
                return str_pad(($number), 4, '0', STR_PAD_LEFT);
            };

            $generated_id = $type->code . '-' . $formatted_number($new_number);

            while ($entries_table->where('number', $generated_id)->exists()) {
                $new_number++;
                $generated_id = $type->code . '-' . $formatted_number($new_number);
            }
        }
        return $generated_id;
    }
    public static function product_sales_data($product_type_id,$start_date,$end_date,$field = 'total'){
        $org_id = Auth::user()->org_id;
        $start_date=date('y-m-d',strtotime($start_date));
        $end_date=date('y-m-d',strtotime($end_date));
        $outlet_id=request('outlet_id');
        if(request('outlet_id')){
            $product_sales = \DB::select("SELECT fin_order_detail.product_id, products.name,products.org_id,fin_orders_meta.is_bill_active,
                SUM(fin_order_detail.{$field}) as total ,fin_orders.bill_date
                FROM fin_order_detail
                LEFT JOIN products ON products.id = fin_order_detail.product_id
                LEFT JOIN fin_orders ON fin_orders.id = fin_order_detail.order_id
                LEFT JOIN fin_orders_meta ON fin_orders_meta.order_id = fin_orders.id
                WHERE fin_orders_meta.is_bill_active = '1'
                AND fin_order_detail.product_id != '0'
                AND fin_order_detail.product_type_id='{$product_type_id}'
                AND fin_orders.outlet_id='{$outlet_id}'
                AND Date(fin_orders.bill_date) >= '{$start_date}'
                AND Date(fin_orders.bill_date) <= '{$end_date}'
                AND products.org_id = '1'
                GROUP BY fin_order_detail.product_id" );

        }
        else {
            $product_sales = \DB::select("SELECT fin_order_detail.product_id, products.name,products.org_id,fin_orders_meta.is_bill_active,
                SUM(fin_order_detail.{$field}) as total ,fin_orders.bill_date
                FROM fin_order_detail
                LEFT JOIN products ON products.id = fin_order_detail.product_id
                LEFT JOIN fin_orders ON fin_orders.id = fin_order_detail.order_id
                LEFT JOIN fin_orders_meta ON fin_orders_meta.order_id = fin_orders.id
                WHERE fin_orders_meta.is_bill_active = '1'
                AND fin_order_detail.product_id != '0'
                AND fin_order_detail.product_type_id='{$product_type_id}'
                AND Date(fin_orders.bill_date) >= '{$start_date}'
                AND Date(fin_orders.bill_date) <= '{$end_date}'
                AND products.org_id = '1'
                GROUP BY fin_order_detail.product_id");
        }
        $product_sales_data = array();


        $total=0;
        foreach ($product_sales as $key => $value) {
            $total+=$value->total;
        }
        return $total;

//        $product_sales_data = array_values($product_sales_data);
//
//        array_multisort( array_column($product_sales_data, "y"), SORT_DESC, $product_sales_data );
//
//
//        if(!\Request::get('limit')){
//
//
//            $product_sales_data = array_slice($product_sales_data,0,50);
//
//
//
//        }

        // dd($product_sales_data);


//        return $product_sales_data;

    }


    public static function countInGroup($group_id){

        return DB::table('clients')
        ->where('customer_group', $group_id)
        ->count();

    }

    public static function getRmbPurch($id){

        $total_rmb = DB::table('purch_order_details')->where('order_no', $id)->sum('rmb');


        return $total_rmb;

    }



    public static function getRmbPaidPurch($id){

        $total_rmb = DB::table('payments')->where('purchase_id', $id)->sum('rmb');



        return $total_rmb;


    }


    public static function getPurchaseTDS($id)
    {
        $tds = DB::table('payments')->where('purchase_id', $id)->sum('tds');
        if ($tds > 0) {
            return $tds;
        }

        return 0.00;
    }


    public static function reduceLeaveFromTimeoff($days,$userId,$start_date,$end_date){


        $time_off_leave= LeaveApplication::select('*')->where('user_id', $userId)->where('leave_category_id', env('TIME_OFF_ID'))->where('application_status', '2')->where('leave_start_date', '>=', $start_date)->where('leave_end_date', '<=', $end_date)->get();

        $totalLeaves = 0;

        foreach ($time_off_leave as $key => $value) {

            $to_time = strtotime($value->time_off_start);

            $from_time = strtotime($value->time_off_end);

            $time_off_time = round(abs($to_time - $from_time) / 60,2);

                $totalLeaves += $time_off_time / env('TOTAL_WORKING_HOURS',480); //convert minutes to days in term of total office hours


            }

            return ($days-$totalLeaves);
        }


        public static function checkTimeOffMonthly($userId){

            $start_date = date('Y-m-01');
            $end_date = date('Y-m-t');

            $timeoff = LeaveApplication::select('*')->where('user_id', $userId)->where('leave_category_id', env('TIME_OFF_ID'))->where('application_status', '2')->where('leave_start_date', '>=', $start_date)->where('leave_end_date', '<=', $end_date)->get();

            $totaltime = 0;

            foreach ($timeoff as $key => $value) {

                $to_time = strtotime($value->time_off_start);

                $from_time = strtotime($value->time_off_end);

                $time_off_time = round(abs($to_time - $from_time) / 60,2);

                $totaltime += $time_off_time;
            }

            return $totaltime;

        }
        public static function countEarnedLeave($userId){
            $start_date = date('Y-m-01');

            $end_date = date('Y-m-t');

            $leave_yr = self::cur_leave_yr();

            $earned_leave = \App\User::find($userId)->earned_accrued;

            $carryLeave = \App\Models\LeaveCarryForward::where('from_leave_year_id',$leave_yr->id)->where('user_id',$userId)->first();

            $totalBalance = $carryLeave->num_of_carried + $earned_leave;

            $totalBalance = self::reduceLeaveFromTimeoff($totalBalance,$userId,$start_date,$end_date);

            return $totalBalance;

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


public static function getDateAttribute($first)
{
    return new \YoHang88\LetterAvatar\LetterAvatar($first.' '.strrev ($first));
}


public static function getUserImages($userId){

    $user= \App\User::find($userId);

    if($user && $user->images){

        return "/images/profiles/{$user->images}";
    }

    return self::getAvatarAttribute($user->first_name,$user->last_name);


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


public static function getTaxInvoicePaidAmount($id){

    return \App\Models\InvoicePayment::where('invoice_id',$id)->sum('amount');

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

public static function getIncomeExpensesYearly($type, $fiscal_year = false)
{
    $entryTypeId = 0;
    $dc = 'C';
    if($type == 'income')
    {
        $dc = 'C';
        $entryType = \App\Models\Entrytype::select('id')->where('name', 'Receipt')->first();
        if($entryType) {
            $entryTypeId = $entryType->id;
        } else {
            $entryTypeId = 13;
        }
    } else if($type == 'expense') {
        $dc = 'D';
        $entryType = \App\Models\Entrytype::select('id')->where('name', 'Payment')->first();
        if($entryType) {
            $entryTypeId = $entryType->id;
        } else {
            $entryTypeId = 14;
        }
    }

    $mytime = \Carbon\Carbon::now();

    $thisyear = date('Y', strtotime($mytime));

    $startOfYear = $mytime->copy()->startOfYear();
    $startyear = date('Y-m-d', strtotime($startOfYear));

    $endOfYear = $mytime->copy()->endOfYear();
    $endyear = date('Y-m-d', strtotime($endOfYear));

    $q = DB::table('entryitems')
    ->select(
        DB::raw('YEAR(entries.date) as year'),
        DB::raw('MONTH(entries.date) as month'),
        DB::raw('SUM(entryitems.amount) as sum'),
        'entries.currency as currency'
    )
    ->rightJoin('entries', 'entryitems.entry_id', '=', 'entries.id')
    ->where('entries.fiscal_year_id', $fiscal_year)
    ->where('entries.entrytype_id', $entryTypeId)
    ->where('entryitems.dc', $dc)
    ->whereBetween('entries.date', [$startyear, $endyear])
    ->groupBy('month')
    ->get();

    return $q;
}
public static function getIncomeExpensesMonthly($type, $fiscal_year = false)
    {
        $entryTypeId = 0;
        $dc = 'C';
        if($type == 'income')
        {
            $dc = 'C';
            $entryType = \App\Models\Entrytype::select('id')->where('name', 'Receipt')->first();
            if($entryType) {
                $entryTypeId = $entryType->id;
            } else {
                $entryTypeId = 13;
            }
        } else if($type == 'expense') {
            $dc = 'D';
            $entryType = \App\Models\Entrytype::select('id')->where('name', 'Payment')->first();
            if($entryType) {
                $entryTypeId = $entryType->id;
            } else {
                $entryTypeId = 14;
            }
        }

        $startDay = \Carbon\Carbon::now();
        $firstDay = $startDay->firstOfMonth();
        $firstmonthDate = date('Y-m-d', strtotime($firstDay));

        $lastDay = $startDay->endOfMonth();
        $lastmonthDate = date('Y-m-d', strtotime($lastDay));

        $q = DB::table('entryitems')
        ->select(
            DB::raw('YEAR(entries.date) as year'),
            DB::raw('MONTH(entries.date) as month'),
            DB::raw('DAY(entries.date) as days'),
            DB::raw('SUM(entryitems.amount) as sum'),
            'entries.currency as currency'
        )
        ->rightJoin('entries', 'entryitems.entry_id', '=', 'entries.id')
        ->where('entries.fiscal_year_id', $fiscal_year)
        ->where('entries.entrytype_id', $entryTypeId)
        ->where('entryitems.dc', $dc)
        ->whereBetween('entries.date', [$firstmonthDate, $lastmonthDate])
        ->groupBy('days')
        ->get();

            //dd($q);

        return $q;
    }




}
