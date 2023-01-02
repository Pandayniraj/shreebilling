<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class StoreOverviewExcelExport implements FromView ,ShouldAutoSize
{

    use Exportable;

    protected $title;
    protected $records;

    public function __construct($title,$records,$fiscalyear,$startdate,$enddate,$outlet_name){

        $this->title = $title;
        $this->records = $records;
        $this->fiscalyear = $fiscalyear;
        $this->startdate = $startdate;
        $this->enddate = $enddate;
        $this->outlet_name=$outlet_name;

    }




    public function view(): View
    {
        $title = $this->title;
        $records = $this->records;
        $fiscalyear = $this->fiscalyear;
        $startdate = $this->startdate;
        $enddate = $this->enddate;
        $outlet_name=$this->outlet_name;


        return view('admin.products.excelstoreoverview',[
            'title'=>$title,
            'records'=>$records,
            'fiscalyear'=>$fiscalyear,
            'startdate'=>$startdate,
            'enddate'=>$enddate,
            'outlet_name'=>$outlet_name,
        ]);

          // return $this->viewFile ;
    }
}
