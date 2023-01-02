<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExcelImport implements ToCollection, WithHeadingRow
{
    protected $headingrows;

    public function __construct(int $headingrows = 1)
    {
        $this->headingrows = $headingrows;
    }

    public function collection(Collection $rows)
    {
        return $rows;
    }

    // headingRow function is use for specific row heading in your xls file
    public function headingRow(): int
    {
        return $this->headingrows;
    }


}
