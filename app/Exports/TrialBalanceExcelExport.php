<?php

namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class TrialBalanceExcelExport implements FromView, ShouldAutoSize
{

    use Exportable;

    protected $groups, $allFiscalYear, $fiscal_year, $fiscal, $start_date, $end_date;

    public function __construct($groups, $allFiscalYear, $fiscal_year, $fiscal, $start_date, $end_date)
    {

        $this->groups = $groups;
        $this->allFiscalYear = $allFiscalYear;
        $this->fiscal_year = $fiscal_year;
        $this->fiscal = $fiscal;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }


    public function view(): View
    {
        $groups = $this->groups;
        $allFiscalYear = $this->allFiscalYear;
        $fiscal_year = $this->fiscal_year;
        $fiscal = $this->fiscal;
        $start_date = $this->start_date;
        $end_date = $this->end_date;

        return view('admin.accountreport.trialbalance.excel', [
            'groups' => $groups,
            'allFiscalYear' => $allFiscalYear,
            'fiscal_year' => $fiscal_year,
            'fiscal' => $fiscal,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        // return $this->viewFile ;
    }
}
