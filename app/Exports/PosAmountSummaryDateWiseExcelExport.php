<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PosAmountSummaryDateWiseExcelExport implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $productTypeMaster, $outlets,$start_date,$end_date ;

	public function __construct($productTypeMaster,$outlets,$start_date,$end_date){

		$this->productTypeMaster = $productTypeMaster;
		$this->outlets = $outlets;
      $this->start_date = $start_date;
      $this->end_date = $end_date;
	}




    public function view(): View
    {
         $productTypeMaster = $this->productTypeMaster;
         $outlets = $this->outlets;
         $start_date = $this->start_date;
         $end_date = $this->end_date;

    	 return view('possalessummaryDateWiseexcel',[
                        'productTypeMaster'=>$productTypeMaster,
                        'outlets'=>$outlets,
                        'start_date'=>$start_date,
                        'end_date'=>$end_date,
                     ]);

        // return $this->viewFile ;
    }
}
