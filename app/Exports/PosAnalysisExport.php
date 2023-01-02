<?php

namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PosAnalysisExport implements FromView, ShouldAutoSize
{

    use Exportable;

    protected $productTypeMaster, $start_date, $end_date;

    public function __construct($foodSalesByMasterArr, $month_amountSummary, $month_paid_by_total, $today_amountSummary, $today_paid_by_total, $year_amountSummary, $year_paid_by_total, $totalByPaidToday, $totalByPaidMonth, $totalByPaidYear, $today_date, $page_title, $outlets, $room_sales)
    {

        $this->foodSalesByMasterArr = $foodSalesByMasterArr;
        $this->month_amountSummary = $month_amountSummary;
        $this->month_paid_by_total = $month_paid_by_total;
        $this->today_amountSummary = $today_amountSummary;
        $this->today_paid_by_total = $today_paid_by_total;
        $this->year_amountSummary = $year_amountSummary;
        $this->year_paid_by_total = $year_paid_by_total;
        $this->totalByPaidToday = $totalByPaidToday;
        $this->totalByPaidMonth = $totalByPaidMonth;
        $this->totalByPaidYear = $totalByPaidYear;
        $this->today_date = $today_date;
        $this->room_sales = $room_sales;
    }


    public function view(): View
    {
        return view('possalesanalysis-export', [
            'foodSalesByMasterArr' => $this->foodSalesByMasterArr,
            'month_amountSummary' => $this->month_amountSummary,
            'month_paid_by_total' => $this->month_paid_by_total,
            'today_amountSummary' => $this->today_amountSummary,
            'today_paid_by_total' => $this->today_paid_by_total,
            'year_amountSummary' => $this->year_amountSummary,
            'year_paid_by_total' => $this->year_paid_by_total,
            'totalByPaidToday' => $this->totalByPaidToday,
            'totalByPaidMonth' => $this->totalByPaidMonth,
            'totalByPaidYear' => $this->totalByPaidYear,
            'today_date' => $this->today_date,
            'room_sales' => $this->room_sales,

        ]);

        // return $this->viewFile ;
    }
}
