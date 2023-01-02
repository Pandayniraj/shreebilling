<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class ProductSalesReportExport implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $data,$excel_name,$start_date,$end_date,$search,$product_type,$sort_by;

	public function __construct($data,$excel_name,$start_date,$end_date,$search,$product_type,$sort_by){

        $this->data = $data;
		$this->excel_name = $excel_name;
		$this->start_date = $start_date;
		$this->end_date = $end_date;
		$this->search = $search;
		$this->product_type = $product_type;
		$this->sort_by = $sort_by;
	}


    

    public function view(): View
    {

    	 return view('admin.reports.product-sales-report-export',['data'=>$this->data,'excel_name'=>$this->excel_name,
             'start_date'=>$this->start_date,'end_date'=>$this->end_date,'search'=>$this->search,'product_type'=>$this->product_type,
             'sort_by'=>$this->sort_by
             ]);

        // return $this->viewFile ;
    }
}