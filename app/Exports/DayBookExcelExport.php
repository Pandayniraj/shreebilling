<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class DayBookExcelExport implements FromView ,ShouldAutoSize
{

    use Exportable;

    protected $title;

    public function __construct($title,$entries,$start_date,$end_date){

        $this->title = $title;
        $this->entries = $entries;
        $this->start_date = $start_date;
        $this->end_date = $end_date;

    }
    public function view(): View
    {
        $title = $this->title;
        $entries = $this->entries;
        $start_date = $this->start_date;
        $end_date = $this->end_date;


        return view('admin.accountreport.exceldaybook',[
            'title'=>$title,
            'entries'=>$entries,
            'start_date'=>$start_date,
            'end_date'=>$end_date,
        ]);

          // return $this->viewFile ;
    }
}
