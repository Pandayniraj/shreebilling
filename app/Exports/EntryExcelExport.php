<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class EntryExcelExport implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $id;

	public function __construct($id){

		$this->id = $id;
	}


    

    public function view(): View
    {
         $entry_id = $this->id;
         $entry = Entry::where('id', $entry_id)->first();
         $entryitems = Entryitem::orderBy('id', 'asc')->with('ledgerdetail')->where('entry_id', $entry->id)->get();
    	 return view('admin.entries.export',['entryitems'=>$entryitems,'entry'=>$entry]);

        // return $this->viewFile ;
    }
}