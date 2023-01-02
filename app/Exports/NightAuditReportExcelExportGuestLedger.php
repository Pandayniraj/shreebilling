<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class NightAuditReportExcelExportGuestLedger implements FromView ,ShouldAutoSize
{

	use Exportable;

	protected $imagepath,$business_date,$guest_ledger;

	public function __construct($imagepath,$business_date,$guest_ledger){
		$this->imagepath = $imagepath;
		$this->business_date = $business_date;
		$this->guest_ledger = $guest_ledger;
	}

	public function view(): View
	{
		$imagepath = $this->imagepath;
		$business_date = $this->business_date;
		$guest_ledger = $this->guest_ledger;
		return view('admin.hotel.night-audit-report.excelnightauditsgeneralledger',[
			'imagepath'=>$imagepath,
			'business_date'=>$business_date,
			'guest_ledger'=>$guest_ledger,
		]);

	}
}
