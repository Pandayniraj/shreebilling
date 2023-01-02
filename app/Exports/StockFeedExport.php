<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class StockFeedExport implements FromView ,ShouldAutoSize
{

	use Exportable;

	protected $transations,$excel_name,$startdate,$enddate;


	public function __construct($transations,$excel_name,$startdate,$enddate){
		// dd($enddate);

		$this->transations = $transations;
		$this->excel_name = $excel_name;
		$this->startdate = $startdate;
		$this->enddate = $enddate;
	}

	public function view(): View
	{

		return view('admin.products.stockcount-export',
			[
				'transations'=>$this->transations,
				'excel_name'=>$this->excel_name,
				'startdate'=>$this->startdate,
				'enddate'=>$this->enddate
			]
		);

        // return $this->viewFile ;
	}
}