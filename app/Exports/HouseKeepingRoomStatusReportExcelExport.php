<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class HouseKeepingRoomStatusReportExcelExport implements FromView ,ShouldAutoSize
{

	use Exportable;

	protected $rooms,$house_status,$business_date;

	public function __construct($rooms,$house_status,$business_date){

		$this->rooms = $rooms;
		$this->house_status = $house_status;
		$this->business_date = $business_date;
	}




	public function view(): View
	{
		$rooms = $this->rooms;
		$house_status = $this->house_status;
		$business_date = $this->business_date;

		return view('admin.hotel.house-keeping-report.excelroomstatus',[
			'rooms'=>$rooms,
			'house_status'=>$house_status,
			'business_date'=>$business_date,
		]);

		  // return $this->viewFile ;
	}
}

