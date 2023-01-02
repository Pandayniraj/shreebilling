<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class DebtorsAgeingExport implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $data,$excel_name,$selected_customer,$standing_date;

	public function __construct($data,$excel_name,$selected_customer,$standing_date){

		$this->data = $data;
		$this->excel_name = $excel_name;
		$this->selected_customer = $selected_customer;
		$this->standing_date = $standing_date;
	}


    

    public function view(): View
    {

    	 return view('admin.debtors.ageing-export',['data'=>$this->data,'excel_name'=>$this->excel_name,
             'selected_customer'=>$this->selected_customer,'standing_date'=>$this->standing_date
             ]);

        // return $this->viewFile ;
    }
}