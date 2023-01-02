<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Entry;
use App\Models\Entryitem;

class PurchaseOrdersExport implements FromView ,ShouldAutoSize
{

	 use Exportable;

	protected $order_items;

	public function __construct($order_items){
		$this->order_items = $order_items;
	}




    public function view(): View
    {

    	 return view('admin.orders.purchase-order-export',['order_items'=>$this->order_items]);

    }
}
