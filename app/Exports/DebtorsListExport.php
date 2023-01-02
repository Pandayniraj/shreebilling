<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class DebtorsListExport implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $data,$excel_name,$selected_customer;

	public function __construct($data,$excel_name,$selected_customer){

		$this->data = $data;
		$this->excel_name = $excel_name;
		$this->selected_customer = $selected_customer;
	}


    

    public function view(): View
    {

    	 return view('admin.debtors.list-export',['data'=>$this->data,'excel_name'=>$this->excel_name,
             'selected_customer'=>$this->selected_customer
             ]);

        // return $this->viewFile ;
    }
}