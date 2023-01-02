<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class SamplePurchaseExcelExport implements FromView ,ShouldAutoSize
{

	 use Exportable;

    public function view(): View
    {
    	 return view('admin.entries.exportsample');
    }
}