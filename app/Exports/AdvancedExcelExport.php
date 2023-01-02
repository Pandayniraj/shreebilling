<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdvancedExcelExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize,WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $exceldata = [];
    protected $excelcolumns = [];
    protected $companyheading;

    public function __construct(array $exceldata) //this is new excel export that accept array as parameter
    {

        $this->exceldata = $exceldata['data'];

        if (count($this->exceldata) > 0) {
            $this->excelcolumns = array_keys($this->exceldata[0]);
        }

        $this->companyheading = $exceldata['companyHeading'];
        $this->styles = $exceldata['style'];
        $this->extraHeader = $exceldata['extraHeader'];

    }

    public function collection()
    {
        return collect([
            $this->exceldata,
        ]);
    }

    public function headings(): array
    {
        $heading =[];
        
        if ($this->companyheading) {
            $heading = [
                ['Company PAN', \Auth::user()->organization->vat_id],
                ['Company Address', \Auth::user()->organization->address],
                ['Company Name', env('APP_COMPANY')],
                // $this->excelcolumns,
            ];
        }

        if( $this->extraHeader &&  count($this->extraHeader) > 0 ){

            foreach ($this->extraHeader as $key => $value) {
               array_push($heading,$value);
            }
            
        } 

        array_push($heading,$this->excelcolumns);
        return $heading;
    }

    public function registerEvents(): array
    {
        return [
                // AfterSheet::class => function (AfterSheet $event) {
                //     $event->sheet->getStyle('A4:K4')->applyFromArray([
                //         'font' => [
                //             'bold' => true,
                //         ],
                //     ]);
                // },
            ];
    }

     public function styles(Worksheet $sheet)
    {
        
        return $this->styles;
        // return [
        //     // Style the first row as bold text.
        //     4    => ['font' => ['bold' => true]],

        
        // ];
    }
}
