<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class PaymentReportExcel implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $data,$excel_name,$selected_customer;

	public function __construct($data,$bank_cash_ledger_ids,$excel_name,$start_date,$end_date){

		$this->data = $data;
		$this->excel_name = $excel_name;
		$this->bank_cash_ledger_ids = $bank_cash_ledger_ids;
		$this->start_date = $start_date;
		$this->end_date = $end_date;
	}




    public function view(): View
    {
    	 return view('admin.accountreport.paymentsandreceipts.payment-export',['entryitems'=>$this->data,'excel_name'=>$this->excel_name,
             'bank_cash_ledger_ids'=>$this->bank_cash_ledger_ids,'start_date'=>$this->start_date,'end_date'=>$this->end_date
             ]);
    }
}
