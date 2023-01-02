<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\InvoiceDetail;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteDetails;


class DispatchSheetExport implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $products;
    protected $perdaypurchasedetail;
    protected $perdayproducttotal;
    protected $perdaysalesdetails;
    protected $asopeningstock; 
    protected $foropening; 
    protected $totalremaining;
    protected $excel_name;
    
	public function __construct($products, $perdaypurchasedetail, $perdayproducttotal, $perdaysalesdetails,  $asopeningstock, $foropening, $totalremaining, $excel_name){
		$this->products = $products;
        $this->perdaypurchasedetail = $perdaypurchasedetail;
        $this->perdayproducttotal = $perdayproducttotal;
        $this->perdaysalesdetails = $perdaysalesdetails;
        $this->asopeningstock = $asopeningstock;
        $this->foropening = $foropening;
		$this->totalremaining = $totalremaining;
	}


    public function view(): View
    {
    	return view('deliverynote.dispatchsheetexcel',['products'=>$this->products, 'foropening'=>$this->foropening, 'perdaypurchasedetail'=>$this->perdaypurchasedetail, 'perdayproducttotal'=>$this->perdayproducttotal, 'perdaysalesdetails'=>$this->perdaysalesdetails, 'asopeningstock'=>$this->asopeningstock, 'totalremaining'=>$this->totalremaining, 'excel_name'=>$this->excel_name]);
        // return $this->viewFile ;
    }
}
