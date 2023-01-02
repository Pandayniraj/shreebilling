<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EventsListExport implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $data,$excel_name,$start_date,$end_date,$status;

	public function __construct($data,$excel_name,$start_date,$end_date,$status){

		$this->data = $data;
		$this->excel_name = $excel_name;
		$this->start_date = $start_date;
		$this->end_date = $end_date;
		$this->status = $status;
	}




    public function view(): View
    {

    	 return view('admin.events.event-export',['data'=>$this->data,'excel_name'=>$this->excel_name,
             'start_date'=>$this->start_date,'end_date'=>$this->end_date,'status'=>$this->status
             ]);

        // return $this->viewFile ;
    }
}
