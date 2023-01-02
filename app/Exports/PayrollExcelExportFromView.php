<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class PayrollExcelExportFromView implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $payroll,$payrolldetails;

	public function __construct($payroll,$payrolldetails){

		  $this->payroll = $payroll;
        $this->payrolldetails = $payrolldetails;
	}
    public function view(): View
    {   
    	 return view('admin.payrolllist.excel',[
                        'payroll'=>$this->payroll,
                        'payrolldetails'=>$this->payrolldetails,
                     ]);

        // return $this->viewFile ;
    }
}