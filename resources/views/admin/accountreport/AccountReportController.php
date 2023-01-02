<?php

namespace App\Http\Controllers;

use App\Models\COAgroups;
use App\Models\COALedgers;
use App\Models\Entry;
use App\Models\Entryitem;
use App\Models\Fiscalyear;
use App\Models\Tag;
use Flash;
use Illuminate\Http\Request;

class AccountReportController extends Controller
{
    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Permission $permission
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function ViewLedgers()
    {
        $page_title = 'Ledgers Statements';

        $page_description = 'Detail Ledgers';

        $groups = COAgroups::orderBy('code', 'asc')->get();

        $allFiscalYear = \App\Models\Fiscalyear::select('id','fiscal_year','start_date','end_date')->latest()->get();
        $fiscal_year = \Request::get('fiscal_year')?  \Request::get('fiscal_year'): \App\Models\Fiscalyear::where('current_year', 1)->first()->fiscal_year ;

        $tags = Tag::pluck('title', 'id')->all();

        //  $ledgers_data = COALedgers::find($id);

        // $entry_items= Entryitem::where('ledger_id',$id)->get();

        $currency = \App\Models\Country::select('*')->whereEnabled('1')->orderByDesc('id')->get();

        return view('admin.accountreport.ledgerstatement.viewledgerstatement', compact('allFiscalYear','fiscal_year','page_title', 'page_description', 'groups','tags','currency'));
    }

    public function DetailLedgers(Request $request)
    {
        $requestData = $request->all();
//        dd($requestData);
        $page_title = 'Ledgers Detail';
        $page_description = 'Detail Ledgers';
        $id = $request->ledger_id;

        //dd($id);
        $currency = \App\Models\Country::select('*')->whereEnabled('1')->orderByDesc('id')->get();
        $allFiscalYear = \App\Models\Fiscalyear::select('id','fiscal_year','start_date','end_date')->latest()->get();
        $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
        $fiscal_year = \Request::get('fiscal_year')?  \Request::get('fiscal_year'): $current_fiscal->fiscal_year ;

        $selected_currency = $request->currency;
        $groups = COAgroups::orderBy('code', 'asc')->get();
        $selected_fiscal_year= Fiscalyear::where('fiscal_year',$fiscal_year)->first();
        $startdate = $request->startdate?$request->startdate:$selected_fiscal_year->start_date;
        $enddate = $request->enddate?$request->enddate:$selected_fiscal_year->end_date;

        $prefix='';
        if ($fiscal_year!=$current_fiscal->fiscal_year){
            $prefix=$selected_fiscal_year->numeric_fiscal_year.'_';
        }
        $ledgers_data = new COALedgers();
        $entry_item_table=new Entryitem();
        $new_table=$prefix.$entry_item_table->getTable();
        $new_ledger_table=$prefix.$ledgers_data->getTable();
        $entry_item_table->setTable($new_table);
        $ledgers_data->setTable($new_ledger_table);

        $ledgers_data= $ledgers_data->find($request->ledger_id);

        if($startdate && $enddate){
            $end_date_opening = $startdate;

            //do for current fiscal year
            if(!empty($request->currency)){
                $entry_items = $entry_item_table->where('ledger_id',$id)->with(['entry'])
                    ->join($prefix.'entries',function($join) use ($selected_currency,$startdate,$enddate,$prefix){
                        $join->on($prefix.'entryitems.entry_id','=',$prefix.'entries.id')
                            ->whereDate($prefix.'entries.date','>=',$startdate)->whereDate($prefix.'entries.date','<=',$enddate)
                            ->where($prefix.'entries.currency','=',$selected_currency);
                    })->orderBy($prefix.'entries.date','asc')->orderBy($prefix.'entries.id','asc');

                $entry_items1 = $entry_item_table->where('ledger_id',$id)
                    ->join($prefix.'entries',function($join) use ($selected_currency,$end_date_opening,$prefix){
                        $join->on($prefix.'entryitems.entry_id','=',$prefix.'entries.id')
                            ->whereDate($prefix.'entries.date','<',$end_date_opening)
                            ->where($prefix.'entries.currency','=',$selected_currency);
                    });
                $entry_items1 = $entry_items1->with(['entry'])->get();

            }else{

                $entry_items = $entry_item_table->where('ledger_id',$id)->with(['entry'])
                    ->join($prefix.'entries',function($join) use ($startdate,$enddate,$prefix){
                        $join->on($prefix.'entryitems.entry_id','=',$prefix.'entries.id')
                            ->whereDate($prefix.'entries.date','>=',$startdate)->whereDate($prefix.'entries.date','<=',$enddate)
                        ;
                    })->orderBy($prefix.'entries.date','asc')->orderBy($prefix.'entries.id','asc');

                $entry_items1 = $entry_item_table->where('ledger_id',$id)->with(['entry'])
                    ->join($prefix.'entries',function($join) use ($end_date_opening,$prefix){
                        $join->on($prefix.'entryitems.entry_id','=',$prefix.'entries.id')
                            ->whereDate($prefix.'entries.date','<',$end_date_opening)
                        ;
                    });

                $entry_items1 = $entry_items1->get();

            }
        }
        else{
            if(!empty($request->currency)){
                $entry_items = $entry_item_table->where('ledger_id',$id)->with(['entry'])
                    ->join($prefix.'entries',function($join) use ($selected_currency,$prefix){
                        $join->on($prefix.'entryitems.entry_id','=',$prefix.'entries.id')->where($prefix.'entries.currency','=',$selected_currency);
                    })->orderBy($prefix.'entries.date','asc')->orderBy($prefix.'entries.id','asc');
            }else{
                $entry_items= $entry_item_table->where('ledger_id',$id)->join($prefix.'entries',function($join) use ($selected_currency,$prefix){
                    $join->on($prefix.'entryitems.entry_id','=',$prefix.'entries.id')->where($prefix.'entries.currency','=',$selected_currency);
                })->orderBy($prefix.'entries.date','asc')->orderBy($prefix.'entries.id','asc');

            }
        }

//);


        $paginate = 30;
        $previous_closing['amount']=0;
        $previous_closing['dc']='D';
        if (\Request::get('page')>1){
            $previous_entries=$entry_items->take((\Request::get('page')-1)*$paginate)->get();
            foreach ($previous_entries as $ei){
                $previous_closing = \TaskHelper::calculate_withdc($previous_closing['amount'], $previous_closing['dc'],
                    $ei['amount'], $ei['dc']);
            }
        }
        $opening_balance = \App\Helpers\TaskHelper::check_opening_balance1($ledgers_data,$entry_items1);

        if ($request->excel=='true'){
            $entry_items= $entry_items->get();
            return \Excel::download(new \App\Exports\LeadgerExcelExport($entry_items,$ledgers_data,$opening_balance,$startdate,$enddate), 'ledger_'.$ledgers_data->name.'.xls');

        }
        $entry_items =  $entry_items->paginate($paginate);

        return view('admin.accountreport.ledgerstatement.ledgerstatementdetail', compact('allFiscalYear','fiscal_year','previous_closing','startdate', 'enddate', 'page_title', 'page_description', 'groups', 'ledgers_data', 'entry_items', 'id','selected_currency','currency','requestData','opening_balance'));
    }

    public function listLedgersEntries()
    {
        $page_title = 'Ledgers Entries Search';
        $page_description = 'Detail Ledgers Entries';

        $groups = COAgroups::orderBy('code', 'asc')->get();
        //$ledgers_data = COALedgers::find($id);
        // $entry_items= Entryitem::where('ledger_id',$id)->get();

        return view('admin.accountreport.ledgerentries.list', compact('page_title', 'page_description', 'groups'));
    }

    public function detailLedgersEntries(Request $request)
    {
        $page_title = 'Ledgers Entries Detail';
        $page_description = 'Detail Ledgers';
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        // dd($request->ledger_id);
        $id = $request->ledger_id;
        $groups = COAgroups::orderBy('code', 'asc')->get();
        $ledgers_data = COALedgers::find($request->ledger_id);
        $entry_items = Entryitem::where('ledger_id', $request->ledger_id)->get();
        $entries = Entry::select('entries.id', 'entries.entrytype_id', 'entries.tag_id', 'entries.number', 'entries.date', 'entries.cr_total', 'entries.dr_total', 'entries.notes')
                 ->leftjoin('entryitems', 'entryitems.entry_id', '=', 'entries.id')
                 ->where('entryitems.ledger_id', $request->ledger_id)
                 ->where('entries.date', '>=', $startdate)
                 ->where('entries.date', '<=', $enddate)
                 ->orderBy('entries.id', 'desc')
                 ->get();
        //dd($entries);
        return view('admin.accountreport.ledgerentries.detail', compact('page_title', 'page_description', 'groups', 'ledgers_data', 'entry_items', 'id', 'entries'));
    }

    public function trialbalanceindex(Request $request)
    {
        $page_title = 'Trial Balance';
        $page_description = 'Trial Balance Description';


//        $groups=[];
//        if ($request->start_date&&$request->end_date)
        $groups = COAgroups::orderBy('code', 'asc')->get();
        $allFiscalYear=\App\Models\Fiscalyear::latest()->get();
        $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
        $fiscal_year = \Request::get('fiscal_year')?  \Request::get('fiscal_year'): $current_fiscal->fiscal_year ;
        $fiscal=\App\Models\Fiscalyear::where('fiscal_year',$fiscal_year)->first();
        $start_date = $request->start_date?:$fiscal->start_date;

        $end_date = $request->end_date?:$fiscal->end_date;
//dd($end_date);
        return view('admin.accountreport.trialbalance.index', compact('start_date','end_date','fiscal','allFiscalYear','fiscal_year','page_title', 'page_description', 'groups'));
    }
    public function trialbalanceexcel(Request $request)
    {
        $page_title = 'Trial Balance';
        $page_description = 'Trial Balance Description';


//        $groups=[];
//        if ($request->start_date&&$request->end_date)
        $groups = COAgroups::orderBy('code', 'asc')->get();
        $allFiscalYear=\App\Models\Fiscalyear::latest()->get();
        $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
        $fiscal_year = \Request::get('fiscal_year')?  \Request::get('fiscal_year'): $current_fiscal->fiscal_year ;
        $fiscal=\App\Models\Fiscalyear::where('fiscal_year',$fiscal_year)->first();
        $start_date = $request->start_date?:$fiscal->start_date;

        $end_date = $request->end_date?:$fiscal->end_date;
        return \Excel::download(new \App\Exports\TrialBalanceExcelExport($groups,$allFiscalYear,$fiscal_year,$fiscal,$start_date,$end_date), "Trial Balance".date('d M Y').".xls");

//        return view('admin.accountreport.trialbalance.index', compact('page_title', 'page_description', 'groups'));
    }
    public function trialbalancepdf(Request $request)
    {
        $page_title = 'Trial Balance';
        $page_description = 'Trial Balance Description';


//        $groups=[];
//        if ($request->start_date&&$request->end_date)
        $groups = COAgroups::orderBy('code', 'asc')->get();

        return view('admin.accountreport.trialbalance.index', compact('page_title', 'page_description', 'groups'));
    }

    public function balancesheetindex(Request $request)
    {
        $page_title = 'Balance Sheet';
        $page_description = 'Balance Sheet Description';

        $groups = COAgroups::orderBy('code', 'asc')->get();
        $allFiscalYear=\App\Models\Fiscalyear::latest()->get();
        $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
        $fiscal_year = \Request::get('fiscal_year')?  \Request::get('fiscal_year'): $current_fiscal->fiscal_year ;
        $fiscal=\App\Models\Fiscalyear::where('fiscal_year',$fiscal_year)->first();
        $start_date = $request->start_date?:$fiscal->start_date;
        $end_date = $request->end_date?:$fiscal->end_date;

        return view('admin.accountreport.balancesheet.index', compact('start_date','end_date','fiscal','allFiscalYear','fiscal_year','page_title', 'page_description', 'groups'));
    }

    public function profitlossindex(Request $request)
    {
            $page_title = 'Profit Loss';
            $page_description = 'Profit Loss Description';
            $groups = COAgroups::orderBy('code', 'asc')->get();
        $allFiscalYear=\App\Models\Fiscalyear::latest()->get();
        $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->first();
        $fiscal_year = \Request::get('fiscal_year')?  \Request::get('fiscal_year'): $current_fiscal->fiscal_year ;
        $fiscal=\App\Models\Fiscalyear::where('fiscal_year',$fiscal_year)->first();
        $start_date = $request->start_date?:$fiscal->start_date;

        $end_date = $request->end_date?:$fiscal->end_date;

        return view('admin.accountreport.profitloss.index', compact('start_date','end_date','fiscal','allFiscalYear','fiscal_year','page_title', 'page_description', 'groups'));
    }
}
