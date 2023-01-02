<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PosSummaryExport implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $productTypeMaster,$start_date,$end_date ;

	public function __construct($productTypeMaster,$start_date,$end_date,$amountSummary,$paid_by_total){

		$this->productTypeMaster = $productTypeMaster;
      $this->start_date = $start_date;
      $this->end_date = $end_date;
      $this->amountSummary = $amountSummary;
      $this->paid_by_total = $paid_by_total;
	}




    public function view(): View
    {
         $productTypeMaster = $this->productTypeMaster;
         $start_date = $this->start_date;
         $end_date = $this->end_date;
        $amountSummary = $this->amountSummary;
         $paid_by_total = $this->paid_by_total;

    	 return view('possalessummary-export',[
                        'productTypeMaster'=>$productTypeMaster,
                        'start_date'=>$start_date,
                        'end_date'=>$end_date,
                        'amountSummary'=>$amountSummary,
                        'paid_by_total'=>$paid_by_total,
                     ]);

        // return $this->viewFile ;
    }
}
