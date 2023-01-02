<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class LeadgerExcelExport implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $entry_items,$opening_balance,$start_date,$end_date,$ledgers_data;

	public function __construct($entry_items ,$ledgers_data,$opening_balance,$start_date,$end_date){

		$this->entry_items = $entry_items;
        $this->opening_balance = $opening_balance;
        $this->ledgers_data = $ledgers_data;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
	}




    public function view(): View
    {
         $entry_items = $this->entry_items;
         $opening_balance = $this->opening_balance;
         $start_date = $this->start_date;
         $end_date = $this->end_date;

    	 return view('admin.accountreport.ledgerstatement.excelledgers',[
                        'entry_items'=>$entry_items,
                         'opening_balance' => $opening_balance,
                         'start_date' => $start_date,
                         'end_date' => $end_date,
                         'ledgers_data' => $this->ledgers_data,
                     ]);

        // return $this->viewFile ;
    }
}
