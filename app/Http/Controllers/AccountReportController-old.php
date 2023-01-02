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
        
        $fin_year = Fiscalyear::pluck('fiscal_year', 'id')->all();

        $tags = Tag::pluck('title', 'id')->all();

      //  $ledgers_data = COALedgers::find($id);

        // $entry_items= Entryitem::where('ledger_id',$id)->get();

        $currency = \App\Models\Country::select('*')->whereEnabled('1')->orderByDesc('id')->get();

        return view('admin.accountreport.ledgerstatement.viewledgerstatement', compact('page_title', 'page_description', 'groups',   'fin_year', 'tags','currency'));
    }

    public function DetailLedgers(Request $request)
    {
        $page_title = 'Ledgers Detail';
        $page_description = 'Detail Ledgers';
        $id = $request->ledger_id;
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $selected_currency = $request->currency;
        $groups = COAgroups::orderBy('code', 'asc')->get();
        $ledgers_data = COALedgers::find($request->ledger_id);
        //dd($id);
        $currency = \App\Models\Country::select('*')->whereEnabled('1')->orderByDesc('id')->get();


    if(\Request::get('startdate') && \Request::get('enddate')){
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $entry_items = Entryitem::where('ledger_id',$id)->leftjoin('entries','entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate)->paginate(30);
      }
      else{
        $entry_items= Entryitem::where('ledger_id',$id)->paginate(30);
      }
            
    return view('admin.accountreport.ledgerstatement.ledgerstatementdetail', compact('startdate', 'enddate', 'page_title', 'page_description', 'groups', 'ledgers_data', 'entry_items', 'id','selected_currency','currency'));
    }

    public function listLedgersEntries()
    {
        $page_title = 'Ledgers Entries Search';
        $page_description = 'Detail Ledgers Entries';

        $groups = COAgroups::orderBy('code', 'asc')->get();
        $ledgers_data = COALedgers::find($id);
        // $entry_items= Entryitem::where('ledger_id',$id)->get();

        return view('admin.accountreport.ledgerentries.list', compact('page_title', 'page_description', 'groups', 'ledgers_data', 'entry_items', 'id'));
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

        $groups = COAgroups::orderBy('code', 'asc')->get();

        return view('admin.accountreport.trialbalance.index', compact('page_title', 'page_description', 'groups'));
    }

    public function balancesheetindex(Request $request)
    {
        $page_title = 'Balance Sheet';
        $page_description = 'Balance Sheet Description';

        $groups = COAgroups::orderBy('code', 'asc')->get();

        return view('admin.accountreport.balancesheet.index', compact('page_title', 'page_description', 'groups'));
    }

    public function profitlossindex(Request $request)
    {
        $page_title = 'Profit Loss';
        $page_description = 'Profit Loss Description';
        $groups = COAgroups::orderBy('code', 'asc')->get();

        return view('admin.accountreport.profitloss.index', compact('page_title', 'page_description', 'groups'));
    }
}
