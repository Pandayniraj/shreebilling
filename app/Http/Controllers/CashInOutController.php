<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashInOutController extends Controller
{
    

	public function cash(){

		if(\Request::get('start_date') && \Request::get('end_date')){

			$start_date = \Request::get('start_date');

			$end_date = \Request::get('end_date');

		}else{

			$start_date = date('Y-m-d');

			$end_date = date('Y-m-d');

		}
		$types = ['customer_payment'=>'Customer Payment', 'customer_advance'=>'Customer Advance', 'sales_without_invoice'=>'Sales Without Invoice', 'other_income'=>'Other Income', 'interest_income'=>'Interest Income'];



		$payment = \App\Models\Payment::where('date','>=',$start_date)
					->where('date','<=',$end_date)
					->get();


		$bankingIncome = \App\Models\BankIncome::where('date_received','>=',$start_date)
					->where('date_received','<=',$end_date)
					->get();

		$expenses = \App\Models\Expense::where('date','>=',$start_date)
					->where('date','<=',$end_date)
					->get();

		$orders = \App\Models\Orders::where('bill_date','>=',$start_date)
					->where('bill_date','<=',$end_date)
					->where('order_type','proforma_invoice')
					->get();
					
		$purhcase = \App\Models\PurchaseOrder::where('bill_date','>=',$start_date)
					->where('bill_date','<=',$end_date)
					->get();


		//dd($expenses);

	//					dd($bankingIncome);

		$page_title = 'Day Book & Cash Flow';
		$page_description = 'Cash In Out Lsit';
		return view('admin.cashinout.list',compact('payment','bankingIncome','expenses','types','page_title','start_date','end_date','orders','purhcase','page_description'));


	}

	public function daybook(Request $request){

        $page_title = 'Daybook';
		if(\Request::get('start_date') && \Request::get('end_date')){

			$start_date = \Request::get('start_date');

			$end_date = \Request::get('end_date');

		}else{

			$start_date = date('Y-m-d');

			$end_date = date('Y-m-d');

		}
        $op = \Request::get('op');
        $prefix = '';
        $entries_table = new \App\Models\Entry();
        $current=\App\Models\Fiscalyear::first()->numeric_fiscal_year;
        // if ($selected_fiscal_year != \App\Models\Fiscalyear::first()->numeric_fiscal_year) {
        //     $prefix = $selected_fiscal_year . '_';
        //     $new_entries_table = $prefix . $entries_table->getTable();
        //     $entries_table->setTable($new_entries_table);
        // }

        $entries = $entries_table->select($prefix.'entries.*')
        ->leftjoin($prefix.'entryitems', $prefix.'entryitems.entry_id', '=', $prefix.'entries.id')
        ->where($prefix.'entries.org_id', \Auth::user()->org_id)
        ->where(function ($query) use ($prefix,$start_date,$end_date) {
            if ($start_date && $end_date) {
                return $query->whereDate($prefix.'entries.date', '>=', $start_date)->whereDate($prefix.'entries.date', '<=',$end_date);
            }
        })
        ->orderBy($prefix.'entries.id', 'desc')
        ->groupBy($prefix.'entries.id')
        ->paginate(30);
        if ($op == 'excel') {

            $date = date('Y-m-d');
            $title = 'Day Book';
            $entries = $entries_table->select($prefix.'entries.*')
                ->leftjoin($prefix.'entryitems', $prefix.'entryitems.entry_id', '=', $prefix.'entries.id')
                ->where($prefix.'entries.org_id', \Auth::user()->org_id)
                ->where(function ($query) use ($prefix,$start_date,$end_date) {
                    if ($start_date && $end_date) {
                        return $query->whereDate($prefix.'entries.date', '>=', $start_date)->whereDate($prefix.'entries.date', '<=',$end_date);
                    }
                })
                ->orderBy($prefix.'entries.id', 'desc')
                ->groupBy($prefix.'entries.id')
                ->get();
            return \Excel::download(new \App\Exports\DayBookExcelExport($title,$entries,$start_date,$end_date), "Daybook_{$date}.xls");

        }

        if ($op == 'pdf') {

            $date = date('Y-m-d');
            $title = 'Day Book';
            $entries = $entries_table->select($prefix.'entries.*')
                ->leftjoin($prefix.'entryitems', $prefix.'entryitems.entry_id', '=', $prefix.'entries.id')
                ->where($prefix.'entries.org_id', \Auth::user()->org_id)
                ->where(function ($query) use ($prefix,$start_date,$end_date) {
                    if ($start_date && $end_date) {
                        return $query->whereDate($prefix.'entries.date', '>=', $start_date)->whereDate($prefix.'entries.date', '<=',$end_date);
                    }
                })
                ->orderBy($prefix.'entries.id', 'desc')
                ->groupBy($prefix.'entries.id')
                ->get();
            
            $pdf = PDF::loadView('admin.accountreport.daybook-pdf', compact('title', 'entries', 'start_date', 'end_date'));
            return $pdf->download("daybook_view".date('d M Y').".pdf");

        }

        $organization = $this->getOrganization();

        return view('admin.cashinout.daybook',compact('start_date','end_date','entries','page_title', 'organization'));
    }

    public function gl(){

        $page_title = 'General Ledger';
        if(\Request::get('ledger_id') && \Request::get('ledger_id')!='')
        {
            $ledgers = \App\Models\COALedgers::where('id',\Request::get('ledger_id') )->paginate(50);
        }
        else{
        $ledgers = \App\Models\COALedgers::orderBy('id', 'DESC')->paginate(50);
        }

        $filter_legders_name=\App\Models\COALedgers::pluck('name','id');

        $organization = $this->getOrganization();

        if (\Request::get('download') == 'excel'){
            if(\Request::get('ledger_id') && \Request::get('ledger_id')!='')
            {
                $generalLedgers = \App\Models\COALedgers::where('id',\Request::get('ledger_id') )->get();
            }
            else{
                $generalLedgers = \App\Models\COALedgers::orderBy('id', 'DESC')->get();
            }
            ob_end_clean();

            return \Excel::download(new \App\Exports\Reports\GeneralLedgerExcelExport($generalLedgers, $filter_legders_name, \Request::get('ledger_id')), "General Ledger Report ".date('d M Y').".xls");
        }
        else if (\Request::get('download') == 'pdf'){
            if(\Request::get('ledger_id') && \Request::get('ledger_id')!='')
            {
                $generalLedgers = \App\Models\COALedgers::where('id',\Request::get('ledger_id') )->get();
            }
            else{
                $generalLedgers = \App\Models\COALedgers::orderBy('id', 'DESC')->get();
            }

            $ledgerId = \Request::get('ledger_id');

            $pdf = PDF::loadView('admin.hotel.reservationreports.pdf-reports.general-ledger-pdf', compact('generalLedgers', 'filter_legders_name', 'ledgerId'));
            return $pdf->download("General Ledger ".date('d M Y').".pdf");
        }

        return view('admin.cashinout.gl',compact('page_title', 'ledgers','filter_legders_name', 'organization'));
    }
	public function getOrganization()
	{
		return \App\Models\Organization::where('id', 1)->first();
	}

}
