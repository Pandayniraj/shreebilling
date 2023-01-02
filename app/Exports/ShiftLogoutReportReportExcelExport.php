<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class ShiftLogoutReportReportExcelExport implements FromView ,ShouldAutoSize
{

	use Exportable;

	protected $orders,$logintime,$logouttime;

	public function __construct($orders,$logintime,$logouttime){

		$this->orders = $orders;
		$this->logintime = $logintime;
		$this->logouttime = $logouttime;
	}




	public function view(): View
	{
		$orders = $this->orders;
		$logintime = $this->logintime;
		$logouttime = $this->logouttime;

		return view('admin.shift_attendance.shift_logout_excel',[
			'orders'=>$orders,
			'logintime'=>$logintime,
			'logouttime'=>$logouttime,
		]);

		  // return $this->viewFile ;
	}
}

