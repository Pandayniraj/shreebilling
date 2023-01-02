<?php

namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CoaExport implements FromView, ShouldAutoSize
{

    use Exportable;

    protected $data;

    public function __construct($data)
    {

        $this->data = $data;

    }


    public function view(): View
    {
        return view('admin.coa.coa-export', [
            'data' => $this->data,
        ]);

        // return $this->viewFile ;
    }
}
