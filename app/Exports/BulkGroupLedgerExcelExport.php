<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class BulkGroupLedgerExcelExport implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $ledgers_data,$groups_data,$start_date,$end_date;

	public function __construct($ledgers_data ,$groups_data,$start_date,$end_date){

		$this->ledgers_data = $ledgers_data;
        $this->groups_data = $groups_data;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
	}




    public function view(): View
    {
         $ledgers_data = $this->ledgers_data;
        $groups_data = $this->groups_data;
         $start_date = $this->start_date;
         $end_date = $this->end_date;

    	 return view('admin.accountreport.groupledgerbulk.excelledgers',[
                         'groups_data' => $groups_data,
                         'start_date' => $start_date,
                         'end_date' => $end_date,
                         'ledgers_data' => $ledgers_data,
                     ]);

    }
}
