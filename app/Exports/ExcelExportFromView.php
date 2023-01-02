<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelExportFromView implements FromView
{

	protected $viewFile;

	public function __construct($view){


		$this->viewFile = $view;
	}


    public function view(): View
    {
        return $this->viewFile ;
    }
}