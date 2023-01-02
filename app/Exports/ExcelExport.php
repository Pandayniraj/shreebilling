<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ExcelExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $exceldata = [];
    protected $excelcolumns = [];
    protected $companyheading;

    public function __construct(array $exceldata, $companyheading = true)
    {
        $this->exceldata = $exceldata;
        if (count($this->exceldata) > 0) {
            $this->excelcolumns = array_keys($exceldata[0]);
        }

        $this->companyheading = $companyheading;
    }

    public function collection()
    {
        return collect([
            $this->exceldata,
        ]);
    }

    public function headings(): array
    {
        if ($this->companyheading) {
            $heading = [
                ['Company PAN', \Auth::user()->organization->vat_id],
                ['Company Address', \Auth::user()->organization->address],
                ['Company Name', env('APP_COMPANY')],
                $this->excelcolumns,
            ];
        } else {
            $heading = [$this->excelcolumns];
        }

        return $heading;
    }

    public function registerEvents(): array
    {
        return [
                AfterSheet::class => function (AfterSheet $event) {
                    $event->sheet->getStyle('A4:K4')->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                    ]);
                },
            ];
    }

  
}
