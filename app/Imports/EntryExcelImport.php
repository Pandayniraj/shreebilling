<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Entry;
use App\Models\Entryitem;
use App\Models\Entrytype;
use App\Models\COALedgers;
use Auth;
// use App\Helpers\\FinanceHelper;

class EntryExcelImport implements ToCollection
{

    protected $fiscal_year_id,$currency_code;

    public function  __construct($fiscal_year_id,$currency_code)
    {
        $this->fiscal_year_id = $fiscal_year_id;
        $this->currency_code = $currency_code;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // dd($collection->first()[0]);
        $org_id = Auth::user()->org_id;
        $user_id = Auth::user()->id;
        $number = trim(str_replace("ReceiptEntry:","",$collection[1][0]));
        $date = trim(str_replace("Date:","",$collection[2][0]));
        $entrytype = trim(str_replace("ReceiptType:","",$collection->first()[0]));
        $entrytype_id = Entrytype::where('name',$entrytype)->first()->id;

        $entry = Entry::create([
                'org_id'         => $org_id,
                'user_id'        => $user_id,
                'dr_total'       => $collection->last()[2],
                'source'         => 'Imports Entry',
                'cr_total'       => $collection->last()[2],
                'fiscal_year_id' => $this->fiscal_year_id, 
                'entrytype_id'   => $entrytype_id, 
                'currency'       => $this->currency_code, 
                'date'           => $date,
                'number'         => $number

            ]);

               $numItems = count($collection);
                foreach ($collection as $key => $value) {
                          if($key > 3 && $key < $numItems-1){
                            // $name = preg_match('#\[(.*?)\]#', $value[1], $match);
                            $ledger_id = COALedgers::where('code',$value[1])->first()->id;
                                $data = Entryitem::create([
                                    'entry_id' => $entry->id,
                                    'org_id'   => $org_id,
                                    'user_id'  => $user_id,
                                    'dc'      => $value[0],
                                    'ledger_id'=> $ledger_id,
                                    'amount'=> $value[2] ?? $value[3],
                                    'narration'=> $value[4] ?? "no data provided",
                                 ]);
                          }
                    }
        }

      
    
}
