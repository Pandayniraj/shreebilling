<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class PayrollExcelExportUserFromView implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $user_id,$payroll,$payrolldetails;

	public function __construct($user_id,$payroll,$payrolldetails,$allowances){

		  $this->user_id = $user_id;
          $this->payroll = $payroll;
        $this->payrolldetails = $payrolldetails;
        $this->allowances = $allowances;
	}
    public function view(): View
    {
    	 return view('admin.payrolllist.payrolluser',[
                        'user_id'=>$this->user_id,
                        'payroll'=>$this->payroll,
                        'payrolldetails'=>$this->payrolldetails,
                        'allowances'=>$this->allowances,
                     ]);

        // return $this->viewFile ;
    }
}
