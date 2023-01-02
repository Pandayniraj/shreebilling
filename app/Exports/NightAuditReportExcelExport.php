<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class NightAuditReportExcelExport implements FromView ,ShouldAutoSize
{

	use Exportable;

	protected $imagepath,$business_date,$tarrif_collection,$extra_bed,$meal_plans,$fnb_outlets,$non_fnb_outlets,$payment_types,$total_refunds,$articles,$settlements,$brought_forward,$carried_forward;

	public function __construct($imagepath,$business_date,$tarrif_collection,$extra_bed,$meal_plans,$fnb_outlets,$non_fnb_outlets,$payment_types,$total_refunds,$articles,$settlements,$brought_forward,$carried_forward){

		$this->imagepath = $imagepath;
		$this->business_date = $business_date;
		$this->tarrif_collection = $tarrif_collection;
		$this->extra_bed = $extra_bed;
		$this->meal_plans = $meal_plans;
		$this->fnb_outlets = $fnb_outlets;
		$this->non_fnb_outlets = $non_fnb_outlets;
		$this->payment_types = $payment_types;
		$this->total_refunds = $total_refunds;
		$this->articles = $articles;
		$this->settlements = $settlements;
		$this->brought_forward = $brought_forward;
		$this->carried_forward = $carried_forward;
	}




	public function view(): View
	{
		$imagepath = $this->imagepath;
		$business_date = $this->business_date;
		$tarrif_collection = $this->tarrif_collection;
		$extra_bed = $this->extra_bed;
		$meal_plans = $this->meal_plans;
		$fnb_outlets = $this->fnb_outlets;
		$non_fnb_outlets = $this->non_fnb_outlets;
		$payment_types = $this->payment_types;
		$total_refunds = $this->total_refunds;
		$articles = $this->articles;
		$settlements = $this->settlements;
		$brought_forward = $this->brought_forward;
		$carried_forward = $this->carried_forward;

		return view('admin.hotel.night-audit-report.excelnightaudits',[
			'imagepath'=>$imagepath,
			'business_date'=>$business_date,
			'tarrif_collection'=>$tarrif_collection,
			'extra_bed'=>$extra_bed,
			'meal_plans'=>$meal_plans,
			'fnb_outlets'=>$fnb_outlets,
			'non_fnb_outlets'=>$non_fnb_outlets,
			'payment_types'=>$payment_types,
			'total_refunds'=>$total_refunds,
			'articles'=>$articles,
			'settlements'=>$settlements,
			'brought_forward'=>$brought_forward,
			'carried_forward'=>$carried_forward,
		]);

		  // return $this->viewFile ;
	}
}