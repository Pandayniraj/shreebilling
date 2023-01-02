<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\COAgroups;
use App\Models\COALedgers;
use App\Models\Department;
use App\Models\Role as Permission;
use App\User;
use Auth;
use DB;
use Flash;
use Illuminate\Http\Request;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class FinanceBoardController extends Controller
{
    /**
     * @var Permission
     */
    private $permission;
    private $org_id;

    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
        $this->middleware(function ($request, $next) {
            $this->org_id = \Auth::user()->org_id;

            return $next($request);
        });
    }

    public function index()
    {
        $page_title = 'Finance Dashboard';

        $page_description = 'List of all funds this financial year';

        $all_fiscal_year = \App\Models\Fiscalyear::where('org_id', $this->org_id)->orderBy('numeric_fiscal_year', 'desc')->pluck('fiscal_year as name', 'id')->all();
     
        if (\Request::get('fiscal_year') && \Request::get('fiscal_year') != '') {
            $curr_fiscal_yr = \App\Models\Fiscalyear::find(\Request::get('fiscal_year'));
        } else {
            $curr_fiscal_yr = \FinanceHelper::cur_fisc_yr();
        }
        
        $parent_groups = \DB::select("
            Select coa_groups.name, coa_groups.id, SUM(entryitems.amount) as num
            FROM entryitems 
            LEFT JOIN coa_ledgers
            ON entryitems.ledger_id = coa_ledgers.id
            LEFT JOIN entries 
            ON entries.id = entryitems.entry_id
            LEFT JOIN coa_groups
            ON coa_ledgers.group_id = coa_groups.id
            WHERE coa_groups.org_id = '$this->org_id'
            AND entries.fiscal_year_id = '$curr_fiscal_yr->id'
            group by coa_groups.id"
          );

        $parent_groupsData = [];
        foreach ($parent_groups as $v) {
            array_push($parent_groupsData,
            [
              'name'=>$v->name,
              'y'=>(float) $v->num,
              ]
        );
        }

        $sundry_creditor = \FinanceHelper::calculateLedgerGroupOpeningBalance(\FinanceHelper::get_ledger_id('SUPPLIER_LEDGER_GROUP'), $curr_fiscal_yr->id);
        $sundry_creditor = json_encode($sundry_creditor);
        $sundry_debitors = \FinanceHelper::calculateLedgerGroupOpeningBalance(\FinanceHelper::get_ledger_id('CUSTOMER_LEDGER_GROUP'), $curr_fiscal_yr->id);
        $sundry_debitors = json_encode($sundry_debitors);
        $parent_groupsData = json_encode($parent_groupsData);

        $parent_ledgers = \DB::select("
           Select coa_ledgers.name, SUM(entryitems.amount) as amount
              FROM entryitems 
              LEFT JOIN coa_ledgers
              ON entryitems.ledger_id = coa_ledgers.id
              LEFT JOIN coa_groups
              ON coa_ledgers.group_id = coa_groups.id
              LEFT JOIN entries 
              ON entries.id = entryitems.entry_id
              WHERE coa_groups.org_id='$this->org_id'
              AND entries.fiscal_year_id = '$curr_fiscal_yr->id'
              group by coa_ledgers.id");

        $parent_ledgersData = [];
        foreach ($parent_ledgers as $v) {
            array_push($parent_ledgersData,
                [
                  'name'=>$v->name,
                  'y'=>(float) $v->amount,
                  ]
            );
        }
        $parent_ledgersData = json_encode($parent_ledgersData);

        ///////** BANK AND CASH ACCOUNTS CALC *****************/////

        // $creditTotal = \DB::select("SELECT SUM(entries.cr_total) as cr FROM entries");
        // dd($creditTotal);

        // $debitTotal = (string) \DB::select("SELECT SUM(entries.dr_total) as dr FROM entries");

        // dd($creditTotal);

        $bankandcash = \DB::select(
                            "Select coa_ledgers.name, 
                                SUM(if (dc='D', entryitems.amount, 0)) - SUM(if (dc='C', entryitems.amount, 0)) as amount
                            FROM entryitems
                            LEFT JOIN coa_ledgers
                            ON entryitems.ledger_id = coa_ledgers.id
                            LEFT JOIN entries 
                            ON entries.id = entryitems.entry_id
                            LEFT JOIN coa_groups
                            ON coa_ledgers.group_id = coa_groups.id
                            where coa_groups.id = 13 AND 
                            coa_groups.org_id='$this->org_id'
                            AND entries.fiscal_year_id = '$curr_fiscal_yr->id'
                            group by  coa_ledgers.id");

        // dd($bankandcash);
        ///////** BANK AND CASH ACCOUNTS CALC ENDS *****************/////

        $incomeCashFlow = \TaskHelper::getIncomeExpensesYearly('income', $curr_fiscal_yr->id);
        // dd($incomeCashFlow);
   

        $months = [];
        $incomeCashFlowMonth = [];

        foreach ($incomeCashFlow as $v) {
            $months[] = $v->month;
        }

        //dd($months);

        for ($i = 1; $i <= 12; $i++) {
            if (in_array($i, $months)) {
                foreach ($incomeCashFlow as $v) {
                    if ($v->month == $i) {
                        array_push($incomeCashFlowMonth,
                  (float) $v->sum
                 );
                    }
                }
            } else {
                array_push($incomeCashFlowMonth,
                0
               );
            }
        }

        $incomeCashFlowMonth = json_encode($incomeCashFlowMonth);

        // dd($incomeCashFlowMonth);

        $expenseCashFlow = \TaskHelper::getIncomeExpensesYearly('expense', $curr_fiscal_yr->id);

        //dd($TotalYearlyExpense);

         // dd($expenseCashFlow);

        $expensemonths = [];
        $expenseCashFlowMonth = [];

        foreach ($expenseCashFlow as $v) {
            $expensemonths[] = $v->month;
        }

        for ($i = 1; $i <= 12; $i++) {
            if (in_array($i, $expensemonths)) {
                foreach ($expenseCashFlow as $v) {
                    if ($v->month == $i) {
                        array_push($expenseCashFlowMonth,
                  (float) $v->sum
                 );
                    }
                }
            } else {
                array_push($expenseCashFlowMonth,
                0
               );
            }
        }

        $expenseCashFlowMonth = json_encode($expenseCashFlowMonth);

        //dd($expenseCashFlowMonth);

        //first date and last date of current month

        $startDay = \Carbon\Carbon::now();

        $firstDay = $startDay->firstOfMonth();

        $firstmonthDate = date('Y-m-d', strtotime($firstDay));

        $lastDay = $startDay->endOfMonth();

        $lastmonthDate = date('Y-m-d', strtotime($lastDay));

        $incomeCashFlowMonthly = \TaskHelper::getIncomeExpensesMonthly('income', $curr_fiscal_yr->id);

        // dd($incomeCashFlowMonthly);

        // dd($TotalMonthlyIncome);

        //dd($incomeCashFlowMonthly);

        $days = [];
        $incomeCashFlowMonthlyData = [];

        foreach ($incomeCashFlowMonthly as $v) {
            $days[] = $v->days;
        }

        //dd($days);
        $lastday = date('d', strtotime($lastDay));

        for ($i = 1; $i <= $lastday; $i++) {
            if (in_array($i, $days)) {
                foreach ($incomeCashFlowMonthly as $v) {
                    if ($v->days == $i) {
                        array_push($incomeCashFlowMonthlyData,
                  (float) $v->sum
                 );
                    }
                }
            } else {
                array_push($incomeCashFlowMonthlyData,
                0
               );
            }
        }

        $incomeCashFlowMonthlyData = json_encode($incomeCashFlowMonthlyData);

        //dd($incomeCashFlowMonthlyData);

        $expenseCashFlowMonthly = \TaskHelper::getIncomeExpensesMonthly('expense', $curr_fiscal_yr->id);

        // dd($TotalMonthlyExpense);

        $expensedays = [];
        $expenseCashFlowMonthlyData = [];

        foreach ($expenseCashFlowMonthly as $v) {
            $expensedays[] = $v->days;
        }

        //dd($expensedays);
        $lastday = date('d', strtotime($lastDay));

        for ($i = 1; $i <= $lastday; $i++) {
            if (in_array($i, $expensedays)) {
                foreach ($expenseCashFlowMonthly as $v) {
                    if ($v->days == $i) {
                        array_push($expenseCashFlowMonthlyData,
                  (float) $v->sum
                 );
                    }
                }
            } else {
                array_push($expenseCashFlowMonthlyData,
                0
               );
            }
        }

        $expenseCashFlowMonthlyData = json_encode($expenseCashFlowMonthlyData);

        // dd($expenseCashFlowMonthlyData);

        $TotalYearlyIncome = [];
        $TotalYearlyExpense = [];

        $TotalMonthlyIncome = [];
        $TotalMonthlyExpense = [];

        $TotalWeeklyIncome = [];
        $TotalWeeklyExpense = 0;

        $TotalTodayIncome = 0;
        $TotalTodayExpense = 0;
        
        // $currency = \App\Models\Country::select('*')->whereEnabled('1')->orderByDesc('id')->get();

        // foreach ($currency as $key => $value) {
        //     $currencyCode = $value->currency_code;
        //     dd($currencyCode);
        //     $incomeCashFlowByCurrency =  $incomeCashFlow->where('currency',$currencyCode);   
 
        //     $expenseCashFlowByCurrency = $expenseCashFlow->where('currency',$currencyCode);   

        //     $incomeCashFlowMonthlyByCurrency = $incomeCashFlowMonthly->where('currency',$currencyCode);

        //     $expenseCashFlowMonthlyByCurrency = $expenseCashFlowMonthly->where('currency',$currencyCode);

        //     $sum = 0;
        //     foreach ($incomeCashFlowByCurrency as $i) {

        //         $TotalYearlyIncome[$currencyCode] = $sum + $i->sum;
              
        //     }

        //     $sum = 0;
        //     foreach ($expenseCashFlowByCurrency as $i) {
        //         $TotalYearlyExpense[$currencyCode] = $sum + $i->sum;
        //     }

        //     $sum = 0;
        //     foreach ($incomeCashFlowMonthlyByCurrency as $i) {
        //         $TotalMonthlyIncome[$currencyCode] = $sum + $i->sum;
        //     }

        //     $sum = 0;
        //     foreach ($expenseCashFlowMonthlyByCurrency as $i) {
        //         $TotalMonthlyExpense[$currencyCode] = $sum + $i->sum;
        //     }
        // }

        $sum = 0;
        foreach ($incomeCashFlow as $i) {
            $TotalYearlyIncome['NPR'] = $sum + $i->sum;
        }

        $sum = 0;
        foreach ($expenseCashFlow as $i) {
            $TotalYearlyExpense['NPR'] = $sum + $i->sum;
        }

        $sum = 0;
        foreach ($incomeCashFlowMonthly as $i) {
            $TotalMonthlyIncome['NPR'] = $sum + $i->sum;
        }

        $sum = 0;
        foreach ($expenseCashFlowMonthly as $i) {
            $TotalMonthlyExpense['NPR'] = $sum + $i->sum;
        }  
       
        $startDay = \Carbon\Carbon::now();
        $todaydate = date('Y-m-d', strtotime($startDay));

        // dd($todaydate);

        // foreach($incomeCashFlowMonthly as $i){

        // //  $TotalTodayIncome=

        // }
        // dd($TotalYearlyIncome);

        return view('financeboard', compact('page_title', 'page_description', 'parent_groupsData', 'bankandcash', 'parent_ledgersData', 'incomeCashFlowMonth', 'expenseCashFlowMonth', 'incomeCashFlowMonthlyData', 'expenseCashFlowMonthlyData', 'TotalYearlyIncome', 'TotalYearlyExpense', 'TotalMonthlyIncome', 'TotalMonthlyExpense', 'all_fiscal_year', 'sundry_creditor', 'sundry_debitors'));
    }
}
