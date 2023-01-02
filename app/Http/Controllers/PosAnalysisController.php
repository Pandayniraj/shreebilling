<?php

namespace App\Http\Controllers;

use App\Models\COALedgers;
use App\Models\Entryitem;
use App\Models\Fiscalyear;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Orders;
use Flash;

class PosAnalysisController extends Controller
{

    public function posAmountSummary(){


        if(\Request::get('start_date') &&  \Request::get('end_date')){

            $start_date = \Request::get('start_date');
            $end_date =  \Request::get('end_date');

        }else{

            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        }
        // $start_date = date('Y-m-d');

        if (\Auth::user()->hasRole('admins')) {
            $outlets = \App\Models\PosOutlets::orderBy('id', 'DESC')
            ->where('enabled', 1)
            ->get();
        } else {
            $outletusers = \App\Models\OutletUser::where('user_id', \Auth::user()->id)->get()->pluck('outlet_id');
            $outlets = \App\Models\PosOutlets::whereIn('id', $outletusers)
            ->orderBy('id', 'DESC')
            ->where('enabled', 1)
            ->get();
        }

        $amountSummaryAndPaidTotal = $this->getSalesByPaymethod($start_date,$end_date);

        $amountSummary  = $amountSummaryAndPaidTotal['amountSummary'];

        $paid_by_total  = $amountSummaryAndPaidTotal['paid_by_total'];

        $productTypeMaster = \App\Models\Product::where(function($query){

            $outlet_id = \Request::get('outlet_id');
            if($outlet_id){

                return $query->where('outlet_id',$outlet_id);
            }

        })->get()->unique('product_type_id');

        $page_title = 'Admin | Pos Summary';
        if (\Request::get('export')==true)
            return \Excel::download(new \App\Exports\PosSummaryExport($productTypeMaster,$start_date,$end_date,$amountSummary,$paid_by_total), 'Product Sales Summary '.date('Y-m-d').'.xls');

        return view('possalessummary',compact('start_date','end_date','productTypeMaster','amountSummary','paid_by_total','outlets','page_title'));


    }




    public function salesByproduct($product_type_id,$start_date,$end_date){

        $outlet_id = \Request::get('outlet_id');

        if($outlet_id){

            $product_sales = \DB::select("SELECT SUM(fin_order_detail.total) as total
                FROM fin_order_detail
                LEFT JOIN products ON products.id = fin_order_detail.product_id
                LEFT JOIN fin_orders ON fin_orders.id = fin_order_detail.order_id
                LEFT JOIN fin_orders_meta ON fin_orders.id = fin_orders_meta.order_id
                WHERE fin_order_detail.product_id != '0' AND products.org_id = '1'
                AND fin_orders.outlet_id = '{$outlet_id}'
                AND products.product_type_id='{$product_type_id}'
                AND fin_orders.bill_date >= '{$start_date}'
                AND fin_orders.bill_date <= '{$end_date}'
                AND fin_orders_meta.is_bill_active = 1");


        }else{

            $product_sales = \DB::select("SELECT SUM(fin_order_detail.total) as total
                FROM fin_order_detail
                LEFT JOIN products ON products.id = fin_order_detail.product_id
                LEFT JOIN fin_orders ON fin_orders.id = fin_order_detail.order_id
                LEFT JOIN fin_orders_meta ON fin_orders.id = fin_orders_meta.order_id
                WHERE fin_order_detail.product_id != '0' AND products.org_id = '1'
                AND products.product_type_id='{$product_type_id}'
                AND fin_orders.bill_date >= '{$start_date}'
                AND fin_orders.bill_date <= '{$end_date}'
                AND fin_orders_meta.is_bill_active = 1");


        }



        return $product_sales[0]->total ?? 0;



    }



    public function getRoomSales($start_date,$end_date){

        $roomSales = \App\Models\Orders::where('reservation_id','!=','0')
        ->where('fin_orders.bill_date','>=',$start_date)
        ->where('fin_orders.bill_date','<=',$end_date)
        ->where('fin_orders_meta.is_bill_active','1')
        ->leftjoin('fin_orders_meta','fin_orders.id','=','fin_orders_meta.order_id')
        ->sum('fin_orders.total_amount');


        return $roomSales;



    }



    public function posSummaryAnalysis(Request $request){

     if (\Auth::user()->hasRole('admins')) {
        $outlets = \App\Models\PosOutlets::orderBy('id', 'DESC')
        ->where('enabled', 1)
        ->get();
    } else {
        $outletusers = \App\Models\OutletUser::where('user_id', \Auth::user()->id)->get()->pluck('outlet_id');
        $outlets = \App\Models\PosOutlets::whereIn('id', $outletusers)
        ->orderBy('id', 'DESC')
        ->where('enabled', 1)
        ->get();
    }
    if(trim($request->date)){
        $end_date = $request->date;
    }else{
        $end_date = date('Y-m-d');
    }

    $nepali_date = \ReservationHelper::findStartDate($end_date);
    $monthDates = $nepali_date['month'];

    $yearDates = $nepali_date['year'];

    $today= $end_date;

    $room_sales =[

        'today'=>$this->getRoomSales($today,$today),
        'months'=>$this->getRoomSales($monthDates,$end_date),
        'years'=>$this->getRoomSales($yearDates,$end_date),

    ];



    $productTypeMaster = \App\Models\Product::with('producttypemaster')
    ->select('product_type_id')
    ->distinct('product_type_id')
    ->where(function($query){
        $outlet_id = \Request::get('outlet_id');
        if($outlet_id){

            return $query->where('outlet_id',$outlet_id);
        }
    })
    ->whereNotIn('product_type_id',[12,13,14])
    ->get();

        //donot show food purchase , drink purchase & cleaning

    $foodSalesByMasterArr = [];


    foreach($productTypeMaster as $key=>$value)
    {
        $ids = $value->product_type_id;

        $foodSalesByMasterArr [] =[
            'name'=>$value->producttypemaster->name,
            'today'=>$this->salesByproduct($ids,$today,$today),
            'months'=>$this->salesByproduct($ids,$monthDates,$end_date),
            'years'=>$this->salesByproduct($ids,$yearDates,$end_date),
        ];


    }
    $foodSalesByMasterArr = collect($foodSalesByMasterArr);



    $amountSummaryAndPaidTotalToday = $this->getSalesByPaymethod($today,$today);

    $today_amountSummary = $amountSummaryAndPaidTotalToday['amountSummary'];
    $today_paid_by_total = $amountSummaryAndPaidTotalToday['paid_by_total'];
    $totalByPaidToday = $amountSummaryAndPaidTotalToday['totalByPaid'] ;

    $amountSummaryAndPaidTotalMonth = $this->getSalesByPaymethod($monthDates,$end_date);

    $month_amountSummary = $amountSummaryAndPaidTotalMonth['amountSummary'];

    $month_paid_by_total = $amountSummaryAndPaidTotalMonth['paid_by_total'];
    $totalByPaidMonth = $amountSummaryAndPaidTotalMonth['totalByPaid'];

    $amountSummaryAndPaidTotalYear = $this->getSalesByPaymethod($yearDates,$end_date);
    $year_amountSummary = $amountSummaryAndPaidTotalYear['amountSummary'];

    $year_paid_by_total = $amountSummaryAndPaidTotalYear['paid_by_total'];
    $totalByPaidYear = $amountSummaryAndPaidTotalYear['totalByPaid'];

//        $today_date = $nepali_date['today_date'];
    $nepaliCalendar = new \App\Helpers\NepaliCalendar();

    $date_arr = explode('-',$today);

    $nepali_date = $nepaliCalendar->eng_to_nep($date_arr[0],$date_arr[1],$date_arr[2]);
    $today_date = $nepali_date;
    $today_date = $today_date['year'].'-'.$today_date['month'].'-'.$today_date['date'];

//        $page_title = 'Pos Sales Analisis '.$today_date['year'].'-'.$today_date['month'].'-'.$today_date['date'];
    $page_title = 'Pos Sales Analysis '.$today_date;


    if (\Request::get('export')==true)
        return \Excel::download(new \App\Exports\PosAnalysisExport($foodSalesByMasterArr,$month_amountSummary,$month_paid_by_total,$today_amountSummary,$today_paid_by_total,$year_amountSummary,$year_paid_by_total,$totalByPaidToday,$totalByPaidMonth,$totalByPaidYear,$today_date,$page_title,$outlets,$room_sales), 'Product Sales Analysis '.date('Y-m-d').'.xls');

    return view('possalesanalysis',compact('foodSalesByMasterArr','month_amountSummary','month_paid_by_total','today_amountSummary','today_paid_by_total','year_amountSummary','year_paid_by_total','totalByPaidToday','totalByPaidMonth','totalByPaidYear','today_date','page_title','outlets','room_sales'));



}

public function debtors_lists(Request $request){

    if(\Request::get('paid_type')){
        $paid_type = \Request::get('paid_type');
    }else{
        $paid_type = 'city-ledger';
    }
    $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
    $fiscal_year = \Request::get('fiscal_year') ?  \Request::get('fiscal_year'): $current_fiscal->fiscal_year ;
    $allFiscalYear = \App\Models\Fiscalyear::select('id','fiscal_year','start_date','end_date')->latest()->get();

    $prefix='';
    // if ($fiscal_year!=$current_fiscal->fiscal_year){
    //     $prefix=Fiscalyear::where('fiscal_year',$fiscal_year)->first()->numeric_fiscal_year.'_';
    // }
    $entry_table=new Entryitem();
    $ledger_table=new COALedgers();
    $new_table=$prefix.$entry_table->getTable();
    $new_ledger_table=$prefix.$ledger_table->getTable();
    $entry_table->setTable($new_table);
    $ledger_table->setTable($new_ledger_table);

    $selected_fiscal_year= Fiscalyear::where('fiscal_year',$fiscal_year)->first();

    $startdate = $request->startdate?$request->startdate:$selected_fiscal_year->start_date;
    $enddate = $request->enddate?$request->enddate:$selected_fiscal_year->end_date;

    // dd($startdate,$enddate);
    $sub_groups = \App\Models\COAgroups::where('parent_id', 19)->pluck('id')->toArray();

    $debtors_listsLedger = $ledger_table->select($prefix.'coa_ledgers.id', 'name','code','op_balance','op_balance_dc')
    ->whereIn('group_id',$sub_groups)
    ->groupBy($prefix.'coa_ledgers.id')
    ->when($request->ledger_id,function ($q) use ($prefix, $request) {
        $q->where($prefix.'coa_ledgers.id',$request->ledger_id);
    })
    ->when($startdate&&$enddate,function ($q) use ($request,$enddate,$startdate,$prefix) {
        $q->leftJoin($prefix.'entryitems',$prefix.'entryitems.ledger_id','=',$prefix.'coa_ledgers.id');
        $q->leftJoin($prefix.'entries',$prefix.'entries.id','=',$prefix.'entryitems.entry_id');
        // $q->where(function ($query) use ($prefix,$startdate,$enddate) {
        //     // $query->whereDate($prefix.'entries.date','>=',$startdate);
        //     // $query->whereDate($prefix.'entries.date','<=',$enddate);
        //     $query->whereBetween($prefix.'entries.date',array($startdate,$enddate));
        //     // $query->orWhereNull($prefix.'entries.date');
        // });
    })
    ->get();


    $debtorsLists=[];
    // dd($debtors_listsLedger);

    foreach ($debtors_listsLedger as $ledger) {
        $opening_balance_with_prev['amount'] = $ledger['op_balance'];
        $opening_balance_with_prev['dc'] = $ledger['op_balance_dc'];

        // if ($startdate&&$enddate){
        //     $previousEntries = $entry_table->where('ledger_id',$ledger->id)
        //     ->when($startdate&&$enddate,function ($q) use ($request,$enddate,$startdate,$prefix) {
        //         $q->leftJoin($prefix.'entries',$prefix.'entries.id','=',$prefix.'entryitems.entry_id');
        //         $q->where($prefix.'entries.date','<',$startdate);
        //     })
        //     ->get();

        //     foreach ($previousEntries as $key => $entry) {
        //         $opening_balance_with_prev = \TaskHelper::calculate_withdc($opening_balance_with_prev['amount'],
        //             $opening_balance_with_prev['dc'],
        //             $entry['amount'], $entry['dc']);
        //     }
        // }

        $debtorsEntries = $entry_table->where('ledger_id',$ledger->id)
        ->when($startdate&&$enddate,function ($q) use ($request,$enddate,$startdate,$prefix) {
            $q->rightJoin($prefix.'entries',$prefix.'entries.id','=',$prefix.'entryitems.entry_id');
            $q->whereDate($prefix.'entries.date','>=',$startdate);
            $q->whereDate($prefix.'entries.date','<=',$enddate);
        })
        ->get();
        // dd($debtorsEntries);

        $total = 0;
        $dr_amount=0;
        $cr_amount=0;
        $entry_balance['amount'] = $opening_balance_with_prev['amount'];
        $entry_balance['dc'] = $opening_balance_with_prev['dc'];
        $total = $entry_balance['dc'] == 'D'?($total+$entry_balance['amount']):0;
        foreach ($debtorsEntries as $key => $entry) {
            $entry_balance = \TaskHelper::calculate_withdc($entry_balance['amount'],$entry_balance['dc'],
                $entry['amount'], $entry['dc']);

            if ($entry['dc']=='D')
                $dr_amount+=$entry['amount'];
            else
                $cr_amount+=$entry['amount'];

            if($entry_balance['dc'] == 'D'){
                $total = $entry_balance['amount'];
            }else{
                $total = -$entry_balance['amount'];
            }
        }
        // if($ledger->id == '510')
        // {
        //     dd($total);

        // }
        // dd($total);
        if($total != 0){
            $debtorsLists[]  =[
                'id'=>$ledger->id,
                'code'=>$ledger->code,
                'name' =>  $ledger->name,
                'amount'=> $total,
                'opening_blc'=>$opening_balance_with_prev['amount'],
                'opening_blc_dc'=>$opening_balance_with_prev['dc'],
                'dr_amount'=>$dr_amount,
                'cr_amount'=>$cr_amount,
            ];
        }
    }
    $name = array_column($debtorsLists, 'name');
    array_multisort($name, SORT_ASC, $debtorsLists);
    $selected_ledger=$request->ledger_id?$ledger_table->find($request->ledger_id):null;
    $page_title = 'Debots|List';
    if ($request->export){
        return \Excel::download(new \App\Exports\DebtorsListExport($debtorsLists,'Debtors List View',$selected_ledger
    ), "debtors_list_view".date('d M Y').".xls");
    }
    $all_debtors = $ledger_table->select('id', 'name','code','op_balance','op_balance_dc')
    ->whereIn('group_id',$sub_groups)
    ->get();
    
    // dd($debtors_listsLedger);
    return view('admin.debtors.list',compact('startdate','enddate','all_debtors','debtorsLists','page_title','fiscal_year','allFiscalYear','current_fiscal'));

}
public function ageingView(Request $request){
    $page_title = 'Ageing|View';

    $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
    $fiscal_year = \Request::get('fiscal_year') ?  \Request::get('fiscal_year'): $current_fiscal->fiscal_year ;
    $allFiscalYear = \App\Models\Fiscalyear::select('id','fiscal_year','start_date','end_date')->latest()->get();

    $prefix='';
    if ($fiscal_year!=$current_fiscal->fiscal_year){
        $prefix=Fiscalyear::where('fiscal_year',$fiscal_year)->first()->numeric_fiscal_year.'_';
    }
    $entry_table=new Entryitem();
    $ledger_table=new COALedgers();
    $new_table=$prefix.$entry_table->getTable();
    $new_ledger_table=$prefix.$ledger_table->getTable();
    $entry_table->setTable($new_table);
    $ledger_table->setTable($new_ledger_table);

    $selected_fiscal_year= Fiscalyear::where('fiscal_year',$fiscal_year)->first();

    $startdate = $request->startdate?$request->startdate:$selected_fiscal_year->start_date;
    $enddate = $request->enddate?$request->enddate:$selected_fiscal_year->end_date;


    $sub_groups = \App\Models\COAgroups::where('parent_id', 275)->pluck('id')->toArray();
    // dd($sub_groups);

    $debtors_listsLedger = $ledger_table->select($prefix.'coa_ledgers.id', 'name','code','op_balance','op_balance_dc','entries.date')
    ->whereIn('group_id',$sub_groups)
    ->groupBy($prefix.'coa_ledgers.id')
    ->when($request->ledger_id,function ($q) use ($prefix, $request) {
        $q->where($prefix.'coa_ledgers.id',$request->ledger_id);
    })
    ->when($startdate&&$enddate,function ($q) use ($request,$enddate,$startdate,$prefix) {
        $q->leftJoin($prefix.'entryitems',$prefix.'entryitems.ledger_id','=',$prefix.'coa_ledgers.id');
        $q->leftJoin($prefix.'entries',$prefix.'entries.id','=',$prefix.'entryitems.entry_id');
        // $q->whereExists(function ($query) use ($prefix,$startdate,$enddate) {
        //     // $query->whereDate($prefix.'entries.date','>=',$startdate);
        //     // $query->whereDate($prefix.'entries.date','<=',$enddate);
        //     $query->whereBetween($prefix.'entries.date',array($startdate,$enddate));
        //     // $query->orWhereNull($prefix.'entries.date');
        // });
    })
    ->get();
    $debtorsLists=[];
    // dd($debtors_listsLedger);

    foreach ($debtors_listsLedger as $ledger) {
        $opening_balance_with_prev['amount'] = $ledger['op_balance'];
        $opening_balance_with_prev['dc'] = $ledger['op_balance_dc'];

        $debtorsEntries = $entry_table->where('ledger_id',$ledger->id)
        ->when($startdate&&$enddate,function ($q) use ($request,$enddate,$startdate,$prefix) {
            $q->rightJoin($prefix.'entries',$prefix.'entries.id','=',$prefix.'entryitems.entry_id');
            $q->whereDate($prefix.'entries.date','>=',$startdate);
            $q->whereDate($prefix.'entries.date','<=',$enddate);
        })
        ->get();

        $total = 0;
        $debtor=[];
        $debtor['1-30']=0;
        $debtor['31-60']=0;
        $debtor['61-90']=0;
        $debtor['91-180']=0;
        $debtor['181-365']=0;
        $debtor['1 year +']=0;
        $debtor['paid_amount']=0;
        $entry_balance['amount'] = $opening_balance_with_prev['amount'];
        $entry_balance['dc'] = $opening_balance_with_prev['dc'];
        // $total = $entry_balance['dc'] == 'D'?($total+$entry_balance['amount']):0;

        $standing_date=Carbon::now();
        if ($entry_balance['dc'] == 'D'){
            $diff = \TaskHelper::getDateDifferenceFromToday($ledger['created_at'],$standing_date);
            // $debtor['1-30']=$diff>=0&&$diff<=30?$ledger['op_balance']:0;
            // $debtor['31-60']=$diff>=31&&$diff<=60?$ledger['op_balance']:0;
            // $debtor['61-90']=$diff>=61&&$diff<=90?$ledger['op_balance']:0;
            // $debtor['91-180']=$diff>=91&&$diff<=180?$ledger['op_balance']:0;
            // $debtor['181-365']=$diff>=181&&$diff<=365?$ledger['op_balance']:0;
            // $debtor['1 year +']=$diff>365?$ledger['op_balance']:0;

            $debtor['1-30']=$diff>=0&&$diff<=30?($total+$entry_balance['amount']):0;
            $debtor['31-60']=$diff>=31&&$diff<=60?($total+$entry_balance['amount']):0;
            $debtor['61-90']=$diff>=61&&$diff<=90?($total+$entry_balance['amount']):0;
            $debtor['91-180']=$diff>=91&&$diff<=180?($total+$entry_balance['amount']):0;
            $debtor['181-365']=$diff>=181&&$diff<=365?($total+$entry_balance['amount']):0;
            $debtor['1 year +']=$diff>365?($total+$entry_balance['amount']):0;
        }
        elseif ($entry_balance['dc']=='C'){
            $debtor['paid_amount'] += $entry_balance['amount'];
        }
        $total = $entry_balance['dc'] == 'D'?($total+$entry_balance['amount']):0;
        foreach ($debtorsEntries as $key => $entry) {
            $entry_balance = \TaskHelper::calculate_withdc($entry_balance['amount'],
                $entry_balance['dc'],
                $entry['amount'], $entry['dc']);
            if($entry['dc']=='D'){
                $diff=\TaskHelper::getDateDifferenceFromToday($entry['created_at'],$standing_date);
                $debtor['1-30']+=$diff>=0&&$diff<=30?$entry['amount']:0;
                $debtor['31-60']+=$diff>=31&&$diff<=60?$entry['amount']:0;
                $debtor['61-90']+=$diff>=61&&$diff<=90?$entry['amount']:0;
                $debtor['91-180']+=$diff>=91&&$diff<=180?$entry['amount']:0;
                $debtor['181-365']+=$diff>=181&&$diff<=365?$entry['amount']:0;
                $debtor['1 year +']+=$diff>365?$entry['amount']:0;
            }else{
                $debtor['paid_amount'] += $entry['amount'];
            }
            if($entry_balance['dc'] == 'D'){
                $total = $entry_balance['amount'];
            }else{
                $total = -$entry_balance['amount'];
            }

        }
        if ($debtor['paid_amount']>0){
            if ($debtor['1 year +']-$debtor['paid_amount']>=0){
                $debtor['1 year +']=$debtor['1 year +']-$debtor['paid_amount'];
            }
            else{
                $debtor['paid_amount']=$debtor['paid_amount']-$debtor['1 year +'];
                $debtor['1 year +']=0;
                if ($debtor['181-365']-$debtor['paid_amount']>=0){
                    $debtor['181-365']=$debtor['181-365']-$debtor['paid_amount'];
                }
                else{
                    $debtor['paid_amount']=$debtor['paid_amount']-$debtor['181-365'];
                    $debtor['181-365']=0;
                    if ($debtor['91-180']-$debtor['paid_amount']>=0){
                        $debtor['91-180']=$debtor['91-180']-$debtor['paid_amount'];
                    }
                    else{
                        $debtor['paid_amount']=$debtor['paid_amount']-$debtor['91-180'];
                        $debtor['91-180']=0;
                        if ($debtor['61-90']-$debtor['paid_amount']>=0){
                            $debtor['61-90']=$debtor['61-90']-$debtor['paid_amount'];
                        }
                        else{
                            $debtor['paid_amount']=$debtor['paid_amount']-$debtor['61-90'];
                            $debtor['61-90']=0;
                            if ($debtor['31-60']-$debtor['paid_amount']>=0){
                                $debtor['31-60']=$debtor['31-60']-$debtor['paid_amount'];
                            }
                            else{
                                $debtor['paid_amount']=$debtor['paid_amount']-$debtor['31-60'];
                                $debtor['31-60']=0;
                                $debtor['1-30']=$debtor['1-30']-$debtor['paid_amount'];
                            }
                        }
                    }
                }
            }
        }
            // dd($total,$debtor);

        // if ( $debtor['1-30']>0||$debtor['31-60']>0||$debtor['61-90']>0||$debtor['91-180']>0||$debtor['181-365']>0||$debtor['1 year +']>0) {
        if($total != 0){

            //  $debtorsLists[]  =[
            //     'id'=>$ledger->id,
            //     'code'=>$ledger->code,
            //     'name' =>  $ledger->name,
            //     'total'=> $debtor['1-30']+$debtor['31-60']+$debtor['61-90']+$debtor['91-180']+$debtor['181-365']+$debtor['1 year +'],
            // ];


            $debtor['id'] = $ledger->id;
            $debtor['code']=$ledger->code;
            $debtor['name'] = $ledger->name;
            $debtor['total'] = $debtor['1-30']+$debtor['31-60']+$debtor['61-90']+$debtor['91-180']+$debtor['181-365']+$debtor['1 year +'];

            $debtorsLists[] = $debtor;
        }
    }
    $name = array_column($debtorsLists, 'name');
    array_multisort($name, SORT_ASC, $debtorsLists);

    if ($request->export){
        $selected_ledger=$request->ledger_id?$ledger_table->find($request->ledger_id):null;

        return \Excel::download(new \App\Exports\DebtorsAgeingExport($debtorsLists,'Debtors Ageing View',
            $selected_ledger,$standing_date), "debtors_ageing_view".date('d M Y').".xls");
    }
    $all_debtors = $ledger_table->select('id', 'name','code','op_balance','op_balance_dc')
    ->whereIn('group_id',$sub_groups)
    ->get();
    return view('admin.debtors.ageingview',compact('startdate','enddate','all_debtors','fiscal_year','allFiscalYear','debtorsLists','page_title','current_fiscal'));
}


public function debtorsPay($ledger_id){

    $group_id = \FinanceHelper::get_ledger_id('CASH_EQUIVALENTS');

    $selectLeger = \App\Models\COALedgers::find($ledger_id);
    $ledgers = \App\Models\COALedgers::where('group_id',$group_id)->get();

    return view('admin.debtors.paynow',compact('ledgers','ledger_id','selectLeger'));
}


public function debtorsPaySubmit(Request $request){

    $attributes = $request->all();

    $attributes['org_id'] = \Auth::user()->org_id;
    $attributes['user_id'] = \Auth::user()->id;
    $attributes['dr_total'] = $attributes['amount'] + $attributes['tds_amount'];
    $attributes['source'] = 'Manual Entry';
    $attributes['cr_total'] = $attributes['amount']+ $attributes['tds_amount'];
    $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
    $attributes['currency'] = 'NPR';
    $attributes['date'] = \Carbon\Carbon::today();
    $type=\App\Models\Entrytype::where('label','receipt')->first();
    $attributes['number'] = \TaskHelper::generateId($type);
    $attributes['entrytype_id'] = $type->id;
    $attributes['tag_id'] = 32;

    $entry = \App\Models\Entry::create($attributes);


    $cash_amount = new \App\Models\Entryitem();
    $cash_amount->entry_id = $entry->id;
    $cash_amount->dc = 'C';
    $cash_amount->ledger_id = $attributes['ledger_id'];
    $cash_amount->amount = $attributes['amount'] + $attributes['tds_amount'];
    $cash_amount->narration = 'being payment received from debtors';
    $cash_amount->save();

        //cash amount
    $cash_amount = new \App\Models\Entryitem();
    $cash_amount->entry_id = $entry->id;
    $cash_amount->dc = 'D';
    $cash_amount->ledger_id = $attributes['ledger_type'];
    $cash_amount->amount = $attributes['amount'];
    $cash_amount->narration = 'being payment received from debtors';
    $cash_amount->save();

        //tds amount
    if ($attributes['tds_amount']){
        $cash_amount = new \App\Models\Entryitem();
        $cash_amount->entry_id = $entry->id;
        $cash_amount->dc = 'D';
        $cash_amount->ledger_id =\FinanceHelper::get_ledger_id('TDS_RECEIVABLE');
        $cash_amount->amount = $attributes['tds_amount'];
        $cash_amount->narration = 'being TDS made from debtors';
        $cash_amount->save();

    }

    Flash::success("Payment success");

    return redirect()->back();
}
public function creditors_lists(Request $request){

    if(\Request::get('paid_type')){

        $paid_type = \Request::get('paid_type');

    }else{

        $paid_type = 'city-ledger';

    }


    $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
    $fiscal_year = \Request::get('fiscal_year') ?  \Request::get('fiscal_year'): $current_fiscal->fiscal_year ;
    $allFiscalYear = \App\Models\Fiscalyear::select('id','fiscal_year','start_date','end_date')->latest()->get();
    $selected_fiscal_year= Fiscalyear::where('fiscal_year',$fiscal_year)->first();

    $startdate = $request->startdate?$request->startdate:$selected_fiscal_year->start_date;
    $enddate = $request->enddate?$request->enddate:$selected_fiscal_year->end_date;
    // dd($enddate);

    $prefix='';
    if ($fiscal_year!=$current_fiscal->fiscal_year){
        $prefix=Fiscalyear::where('fiscal_year',$fiscal_year)->first()->numeric_fiscal_year.'_';
    }
    $entry_table=new Entryitem();
    $ledger_table=new COALedgers();
    $new_table=$prefix.$entry_table->getTable();
    $new_ledger_table=$prefix.$ledger_table->getTable();
    $entry_table->setTable($new_table);
    $ledger_table->setTable($new_ledger_table);

    // $sub_groups = \App\Models\COAgroups::where('parent_id',\Config::get('restro.CREDITOR_LEDGER','61'))->pluck('id')->toArray();
    $sub_groups = \App\Models\COAgroups::where('parent_id',25)->pluck('id')->toArray();

    $creditors_listsLedger=$ledger_table->select($prefix.'coa_ledgers.id', 'name','code','op_balance','op_balance_dc','entries.date')
    ->whereIn('group_id',$sub_groups)
    ->groupBy($prefix.'coa_ledgers.id')
    ->when($request->ledger_id,function ($q) use ($request,$prefix) {
        $q->where($prefix.'coa_ledgers.id',$request->ledger_id);
    })
    ->when($startdate&&$enddate,function ($q) use ($request,$enddate,$startdate,$prefix) {
        $q->leftJoin($prefix.'entryitems',$prefix.'entryitems.ledger_id','=',$prefix.'coa_ledgers.id');
        $q->leftJoin($prefix.'entries',$prefix.'entries.id','=',$prefix.'entryitems.entry_id');
        // $q->whereExists(function ($query) use ($prefix,$startdate,$enddate) {
        //     // $query->whereDate($prefix.'entries.date','>=',$startdate);
        //     // $query->whereDate($prefix.'entries.date','<=',$enddate);
        //     $query->whereBetween($prefix.'entries.date',array($startdate,$enddate));
        //     $query->orWhereNull($prefix.'entries.date');
        // });
    })
    ->get();

    $creditorsLists=[];

    foreach ($creditors_listsLedger as $ledger) {
        $opening_balance_with_prev['amount'] = $ledger['op_balance'];
        $opening_balance_with_prev['dc'] = $ledger['op_balance_dc'];
        if ($startdate&&$enddate){
            $previousEntries = $entry_table->where('ledger_id',$ledger->id)
            ->when($startdate&&$enddate,function ($q) use ($request,$enddate,$startdate,$prefix) {
                $q->leftJoin($prefix.'entries',$prefix.'entries.id','=',$prefix.'entryitems.entry_id');
                $q->where($prefix.'entries.date','<',$startdate);
            })
            ->get();
            foreach ($previousEntries as $key => $entry) {
                $opening_balance_with_prev = \TaskHelper::calculate_withdc($opening_balance_with_prev['amount'],
                    $opening_balance_with_prev['dc'],
                    $entry['amount'], $entry['dc']);
            }

        }
        $creditorsEntries = $entry_table->where('ledger_id',$ledger->id)
        ->when($startdate&&$enddate,function ($q) use ($request,$enddate,$startdate,$prefix) {
            $q->rightJoin($prefix.'entries',$prefix.'entries.id','=',$prefix.'entryitems.entry_id');
            $q->whereDate($prefix.'entries.date','>=',$startdate);
            $q->whereDate($prefix.'entries.date','<=',$enddate);
        })
        ->get();
        $total = 0;
        $dr_amount=0;
        $cr_amount=0;
        $entry_balance['amount'] = $opening_balance_with_prev['amount'];
        $entry_balance['dc'] = $opening_balance_with_prev['dc'];
        $total=$entry_balance['dc'] == 'C'?($total+$entry_balance['amount']):0;
        foreach ($creditorsEntries as $key => $entry) {
            $entry_balance = \TaskHelper::calculate_withdc($entry_balance['amount'],
                $entry_balance['dc'],
                $entry['amount'], $entry['dc']);
            if ($entry['dc']=='D')$dr_amount+=$entry['amount'];
            else $cr_amount+=$entry['amount'];

            if( $entry_balance['dc'] == 'C'){

                $total = $entry_balance['amount'];
            }else{
                $total = 0;
            }
        }
        if($total > 0){
            $creditorsLists[]  =[
                'id'=>$ledger->id,
                'code'=>$ledger->code,
                'name' =>  $ledger->name,
                'amount'=> $total,
                'opening_blc'=>$opening_balance_with_prev['amount'],
                'opening_blc_dc'=>$opening_balance_with_prev['dc'],
                'dr_amount'=>$dr_amount,
                'cr_amount'=>$cr_amount,
            ];
        }
    }
    $page_title = 'Creditors | List';
    $name = array_column($creditorsLists, 'name');
    array_multisort($name, SORT_ASC, $creditorsLists);
    if ($request->export){
        $selected_ledger=$request->ledger_id?$ledger_table->find($request->ledger_id):null;
        return \Excel::download(new \App\Exports\DebtorsListExport($creditorsLists,'Creditors List View',
            $selected_ledger), "creditors_list_view".date('d M Y').".csv");
    }
    $all_creditors = $ledger_table->select('id', 'name','code','op_balance','op_balance_dc')
    ->whereIn('group_id',$sub_groups)
    ->get();

    return view('admin.creditors.list',compact('startdate','enddate','all_creditors','current_fiscal','allFiscalYear','fiscal_year','creditorsLists','page_title'));

}
public function creditorageingView(Request $request){
    $page_title = 'Ageing | View';

    $sub_groups = \App\Models\COAgroups::where('parent_id',44)->pluck('id')->toArray();

    $startdate = $request->startdate;
    $enddate = $request->enddate;
    $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
    $fiscal_year = \Request::get('fiscal_year') ?  \Request::get('fiscal_year'): $current_fiscal->fiscal_year ;
    $allFiscalYear = \App\Models\Fiscalyear::latest()->pluck('fiscal_year','fiscal_year')->all();

    $prefix='';
    if ($fiscal_year!=$current_fiscal->fiscal_year){
        $prefix=Fiscalyear::where('fiscal_year',$fiscal_year)->first()->numeric_fiscal_year.'_';
    }
    $entry_table=new Entryitem();
    $ledger_table=new COALedgers();
    $new_table=$prefix.$entry_table->getTable();
    $new_ledger_table=$prefix.$ledger_table->getTable();
    $entry_table->setTable($new_table);
    $ledger_table->setTable($new_ledger_table);



    $creditors_listsLedger = $ledger_table->select($prefix.'coa_ledgers.id',$prefix.'coa_ledgers.created_at', 'name','code','op_balance','op_balance_dc')
    ->when($request->ledger_id,function ($q) use ($prefix, $request) {
        $q->where($prefix.'coa_ledgers.id',$request->ledger_id);
    })
    ->whereIn('group_id',$sub_groups)
    ->get();
    $creditorsLists=[];

    foreach ($creditors_listsLedger as $ledger) {
        $creditorsEntries = $entry_table->where('ledger_id',$ledger->id)
        ->when($startdate&&$enddate,function ($q) use ($request,$enddate,$startdate,$prefix) {
            $q->rightJoin($prefix.'entries',$prefix.'entries.id','=',$prefix.'entryitems.entry_id');
            $q->whereDate($prefix.'entries.date','>=',$startdate);
            $q->whereDate($prefix.'entries.date','<=',$enddate);
        })
        ->get();
        $total = 0;
        $creditor=[];
        $creditor['1-30']=0;
        $creditor['31-60']=0;
        $creditor['61-90']=0;
        $creditor['91-180']=0;
        $creditor['181-365']=0;
        $creditor['1 year +']=0;
        $creditor['paid_amount']=0;

        $standing_date=Carbon::now();
        if ($ledger['op_balance_dc']=='C'&&$ledger['op_balance']>0){
            $diff=\TaskHelper::getDateDifferenceFromToday($ledger['created_at'],$standing_date);
            $creditor['1-30']=$diff>=0&&$diff<=30?$ledger['op_balance']:0;
            $creditor['31-60']=$diff>=31&&$diff<=60?$ledger['op_balance']:0;
            $creditor['61-90']=$diff>=61&&$diff<=90?$ledger['op_balance']:0;
            $creditor['91-180']=$diff>=91&&$diff<=180?$ledger['op_balance']:0;
            $creditor['181-365']=$diff>=181&&$diff<=365?$ledger['op_balance']:0;
            $creditor['1 year +']=$diff>365?$ledger['op_balance']:0;
        }
        elseif ($ledger['op_balance_dc']=='D'){
            $creditor['paid_amount']+=$ledger['op_balance'];
        }
//            if ()
        $entry_balance = $ledger['op_balance'];
        $entry_balance['dc'] = $ledger['op_balance_dc'];

        foreach ($creditorsEntries as $key => $ei) {
            if( $ei['dc'] == 'C'){
                $diff=\TaskHelper::getDateDifferenceFromToday($ei['created_at'],$standing_date);
                $creditor['1-30']+=$diff>=0&&$diff<=30?$ei['amount']:0;
                $creditor['31-60']+=$diff>=31&&$diff<=60?$ei['amount']:0;
                $creditor['61-90']+=$diff>=61&&$diff<=90?$ei['amount']:0;
                $creditor['91-180']+=$diff>=91&&$diff<=180?$ei['amount']:0;
                $creditor['181-365']+=$diff>=181&&$diff<=365?$ei['amount']:0;
                $creditor['1 year +']+=$diff>365?$ei['amount']:0;
            }else{
                $creditor['paid_amount']+=$ei['amount'];
            }

        }
        if ($creditor['paid_amount']>0){
            if ($creditor['1 year +']-$creditor['paid_amount']>=0){
                $creditor['1 year +']=$creditor['1 year +']-$creditor['paid_amount'];
            }
            else{
                $creditor['paid_amount']=$creditor['paid_amount']-$creditor['1 year +'];
                $creditor['1 year +']=0;
                if ($creditor['181-365']-$creditor['paid_amount']>=0){
                    $creditor['181-365']=$creditor['181-365']-$creditor['paid_amount'];
                }
                else{
                    $creditor['paid_amount']=$creditor['paid_amount']-$creditor['181-365'];
                    $creditor['181-365']=0;
                    if ($creditor['91-180']-$creditor['paid_amount']>=0){
                        $creditor['91-180']=$creditor['91-180']-$creditor['paid_amount'];
                    }
                    else{
                        $creditor['paid_amount']=$creditor['paid_amount']-$creditor['91-180'];
                        $creditor['91-180']=0;
                        if ($creditor['61-90']-$creditor['paid_amount']>=0){
                            $creditor['61-90']=$creditor['61-90']-$creditor['paid_amount'];
                        }
                        else{
                            $creditor['paid_amount']=$creditor['paid_amount']-$creditor['61-90'];
                            $creditor['61-90']=0;
                            if ($creditor['31-60']-$creditor['paid_amount']>=0){
                                $creditor['31-60']=$creditor['31-60']-$creditor['paid_amount'];
                            }
                            else{
                                $creditor['paid_amount']=$creditor['paid_amount']-$creditor['31-60'];
                                $creditor['31-60']=0;
                                $creditor['1-30']=$creditor['1-30']-$creditor['paid_amount'];
                            }
                        }
                    }
                }
            }
        }
        if ( $creditor['1-30']>0||$creditor['31-60']>0||$creditor['61-90']>0||$creditor['91-180']>0||$creditor['181-365']>0||$creditor['1 year +']>0) {
            $creditor['id'] = $ledger->id;
            $creditor['name'] = $ledger->name;
            $creditor['total'] = $creditor['1-30']+$creditor['31-60']+$creditor['61-90']+$creditor['91-180']+$creditor['181-365']+$creditor['1 year +'];
            $creditor['code']=$ledger->code;

            $creditorsLists[] = $creditor;
        }
    }
    $name = array_column($creditorsLists, 'name');
    array_multisort($name, SORT_ASC, $creditorsLists);
    if ($request->export){
        $selected_ledger=$request->ledger_id?$ledger_table->find($request->ledger_id):null;
        return \Excel::download(new \App\Exports\DebtorsAgeingExport($creditorsLists,'Creditors Ageing View',
            $selected_ledger,$standing_date), "creditors_ageing_view".date('d M Y').".xls");
    }
    $all_creditors = $ledger_table->select('id', 'name','code','op_balance','op_balance_dc')
    ->whereIn('group_id',$sub_groups)
    ->get();
    return view('admin.creditors.ageingview',compact('all_creditors','allFiscalYear','fiscal_year','creditorsLists','page_title'));
}

public function creditorsPay($ledger_id){

    $group_id = \FinanceHelper::get_ledger_id('CASH_EQUIVALENTS');

    $selectLeger = \App\Models\COALedgers::find($ledger_id);
    $ledgers = \App\Models\COALedgers::where('group_id',$group_id)->get();

    return view('admin.creditors.paynow',compact('ledgers','ledger_id','selectLeger'));
}


public function creditorsPaySubmit(Request $request){

    $attributes = $request->all();

    $attributes['org_id'] = \Auth::user()->org_id;
    $attributes['user_id'] = \Auth::user()->id;
    $attributes['dr_total'] = $attributes['amount'] + $attributes['tds_amount'];
    $attributes['source'] = 'Manual Entry';
    $attributes['cr_total'] = $attributes['amount']+ $attributes['tds_amount'];
    $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
    $attributes['currency'] = 'NPR';
    $attributes['date'] = \Carbon\Carbon::today();
    $type=\App\Models\Entrytype::where('label','payment')->first();
    $attributes['number'] = \TaskHelper::generateId($type);
    $attributes['entrytype_id'] = $type->id;
    $attributes['tag_id'] = 33;
    $entry = \App\Models\Entry::create($attributes);


    $cash_amount = new \App\Models\Entryitem();
    $cash_amount->entry_id = $entry->id;
    $cash_amount->dc = 'D';
    $cash_amount->ledger_id = $attributes['ledger_id'];
    $cash_amount->amount = $attributes['amount']+$attributes['tds_amount'];
    $cash_amount->narration = 'being amount paid to creditor';
    $cash_amount->save();

        //cash amount
    $cash_amount = new \App\Models\Entryitem();
    $cash_amount->entry_id = $entry->id;
    $cash_amount->dc = 'C';
    $cash_amount->ledger_id = $attributes['ledger_type'];
    $cash_amount->amount = $attributes['amount'];
    $cash_amount->narration = 'being amount paid to creditor';
    $cash_amount->save();

        //tds amount
    if ($attributes['tds_amount']){
        $cash_amount = new \App\Models\Entryitem();
        $cash_amount->entry_id = $entry->id;
        $cash_amount->dc = 'C';
        $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('TDS_PAYABLE');
        $cash_amount->amount = $attributes['tds_amount'];
        $cash_amount->narration = 'being amount paid to creditor';
        $cash_amount->save();

    }

    Flash::success("Payment success");

    return redirect()->back();
}




public function posAmountSummaryDateWise(){

    if(\Request::get('start_date') &&  \Request::get('end_date')){

        $start_date = \Request::get('start_date');
        $end_date =  \Request::get('end_date');

    }else{

        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
    }

    if (\Request::get('start_date_nep') != '' && \Request::get('end_date_nep') != '') {
        $start_date = \Request::get('start_date_nep');
        $end_date = \Request::get('end_date_nep');
        $cal = new \App\Helpers\NepaliCalendar();
        $startdate = explode('-', $start_date);
        $date = $cal->nep_to_eng($startdate[0], $startdate[1], $startdate[2]);
        $startdate = $date['year'] . '-' . $date['month'] . '-' . $date['date'];
        $enddate = explode('-', $end_date);
        $date = $cal->nep_to_eng($enddate[0], $enddate[1], $enddate[2]);
        $enddate = $date['year'] . '-' . $date['month'] . '-' . $date['date'];

    } else {
        $startdate = \Request::get('start_date');
        $enddate = \Request::get('end_date');
    }
    // dd($startdate);
        // $start_date = date('Y-m-d');

    if (\Auth::user()->hasRole('admins')) {
        $outlets = \App\Models\PosOutlets::orderBy('id', 'DESC')
        ->where('enabled', 1)
        ->get();
    } else {
        $outletusers = \App\Models\OutletUser::where('user_id', \Auth::user()->id)->get()->pluck('outlet_id');
        $outlets = \App\Models\PosOutlets::whereIn('id', $outletusers)
        ->orderBy('id', 'DESC')
        ->where('enabled', 1)
        ->get();
    }
    $productTypeMaster = \App\Models\Product::where(function($query){

        $outlet_id = \Request::get('outlet_id');
        $outlet_ids = explode(',', $outlet_id);
        // dd($outlet_ids);
        if(isset($outlet_ids) && $outlet_ids){
            return $query->whereIn('outlet_id',$outlet_ids);
        }

    })->get()->unique('product_type_id');
        // dd($productTypeMaster);

    $page_title = 'Admin | Pos Summary';


    return view('possalessummaryDateWise',compact('startdate','enddate','productTypeMaster','outlets','page_title'));


}

public function posAmountSummaryDateWiseExcel(Request $request)
{
    if(\Request::get('start_date') &&  \Request::get('end_date')){

        $start_date = \Request::get('start_date');
        $end_date =  \Request::get('end_date');

    }else{

        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
    }


        // $start_date = date('Y-m-d');

    if (\Auth::user()->hasRole('admins')) {
        $outlets = \App\Models\PosOutlets::orderBy('id', 'DESC')
        ->where('enabled', 1)
        ->get();
    } else {
        $outletusers = \App\Models\OutletUser::where('user_id', \Auth::user()->id)->get()->pluck('outlet_id');
        $outlets = \App\Models\PosOutlets::whereIn('id', $outletusers)
        ->orderBy('id', 'DESC')
        ->orderBy('id', 'DESC')
        ->where('enabled', 1)
        ->get();
    }
    $productTypeMaster = \App\Models\Product::where(function($query){

        $outlet_id = \Request::get('outlet_id');
        if($outlet_id){

            return $query->where('outlet_id',$outlet_id);
        }

    })->get()->unique('product_type_id');

    $page_title = 'Admin | Pos Summary';

    return \Excel::download(new \App\Exports\PosAmountSummaryDateWiseExcelExport($productTypeMaster,$outlets,$start_date,$end_date), 'Datewise Product Sales '.date('Y-m-d').'.xls');
}

}
