<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class TaxInvoiceExport implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $data,$excel_name;

	public function __construct($data,$excel_name){

		$this->data = $data;
		$this->excel_name = $excel_name;
	}


    public function view(): View
    {
    	return view('admin.invoice.excel',['data'=>$this->data,'excel_name'=>$this->excel_name]);

        // return $this->viewFile ;
    }
}