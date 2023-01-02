<?php

namespace App\Imports;

use App\Models\ExpenseSof;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Entry;
use App\Models\Entryitem;
use App\Models\Entrytype;
use App\Models\COALedgers;
use App\Models\ExpenseClass;
use App\Models\ExpenseTheme;
use App\Models\ExpenseCostCenter;
use App\Models\ExpenseCategory;
use App\Models\ExpenseActivity;
use App\Models\ExpensePaActivity;
use App\Models\PurchaseOrder;
use Auth;
use Flash;
use DB;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
// use App\Helpers\\FinanceHelper;

class PurchaseExcelImport implements ToCollection,WithCalculatedFormulas
{

    protected $fiscal_year_id;

    public function  __construct($fiscal_year_id)
    {
        $this->fiscal_year_id = $fiscal_year_id;
    }

    /**
    * @param Collection $collection
    */
    // public function collection(Collection $collection ){
       
    //     $p = new PurchaseOrder();
    //     $p->supplier_id = 776;
    //     // $po['org_id'] = 1;
    //     // $po['project_id'] = 0;
    //     // $po['bill_no'] = "1234";
    //     // $po['user_id'] = 64;
    //     // $po['ord_date'] = "2022-3-13";
    //     // $po['bill_date'] = "2022-3-13";
    //     // $po['delivery_date'] = "2022-3-13";
    //     // $po['into_stock_location'] = "1";
    //     // $po['total'] = 0;
    //     // $po['fiscal_year'] = "2078/79";
    //     // $po['fiscal_year_id'] = 8;
    //     // $po['entry_id'] = 0;
    //     // $po['is_import'] = 1;
    //        $p->save();
    //        DB::commit();
    //       dd($p->id);
        

    //     // $purchaseorders = \App\Models\PurchaseOrder::insert(['org_id' =>1,
    //     //         'supplier_id'=>776,
    //     //         'project_id'=>0,
    //     //         'bill_no'=>"1234",
    //     //         'user_id'=>64,
    //     //         'ord_date'=>2022-3-13,
    //     //         'bill_date'=>2022-3-13,
    //     //         'delivery_date'=>2022-3-13,
    //     //         'into_stock_location'=>1,
    //     //         'total'=>0,
    //     //         'fiscal_year'=>"2078/79",
    //     //         'fiscal_year_id'=>8,
    //     //         'entry_id'=>0,
    //     //         'is_import'=>1,
    //     // ]);
    //     // $order_attributes['org_id'] = \Auth::user()->org_id;
    //     // $order_attributes['supplier_id'] = $request->customer_id;
    //     // $order_attributes['tax_amount'] = $request->taxable_tax;
    //     // $order_attributes['taxable_amount'] = $request->taxable_amount;
    //     // $order_attributes['total'] = $request->final_total;
    //     // $order_attributes['ledger_id'] = (\App\Models\Client::find($request->customer_id))->ledger_id;
    //     // $order_attributes['fiscal_year'] = $fiscal_year->fiscal_year;
    //     // $order_attributes['fiscal_year_id'] = $fiscal_year->id;
    //     // $purchaseorder = PurchaseOrder::create($order_attributes);
       
    // }
     public function collection(Collection $collection)
    {
        // dd($collection);
        $type="bills";
        $org_id = Auth::user()->org_id;
        $user_id = Auth::user()->id;
        $supplier = trim(str_replace("Select Supplier:","",$collection[0][0]));
        $order = trim(str_replace("Order Date:","",$collection[1][0]));
        // dd($date);
        $delivery = trim(str_replace("Delivery Date:","",$collection[2][0]));
        $supplierdate = trim(str_replace("Supplier Bill Date:","",$collection[3][0]));
        $status = trim(str_replace("Status:","",$collection[4][0]));
        $supplierpayment= trim(str_replace("Supplier Payment Date:","",$collection[5][0]));
        $pan= trim(str_replace("PAN NO:","",$collection[6][0]));
        $bill= trim(str_replace("Bill NO:","",$collection[7][0]));
        $vat= trim(str_replace("VAT:","",$collection[8][0]));
        $isrenewal= trim(str_replace("Is Renewal:","",$collection[9][0]));
        $outlets= trim(str_replace("Outlets:","",$collection[10][0]));
        // dd($outlets);
        $purchaseowner= trim(str_replace("Purchase Owner:","",$collection[11][0]));
        $reference= trim(str_replace("Reference:","",$collection[12][0]));
        $fiscalyear= trim(str_replace("Fiscal Year:","",$collection[13][0]));
        $currency= trim(str_replace("Currency:","",$collection[14][0]));
        $country= trim(str_replace("Country:","",$collection[15][0]));
        $importdate= trim(str_replace("Import Date:","",$collection[16][0]));
        $documentno= trim(str_replace("Document No:","",$collection[17][0]));
        $isimport= trim(str_replace("Is Import:","",$collection[18][0]));
        // if ($request->datetype == 'nep') {
        //     $order_attributes['bill_date'] = $this->convertdate($order_attributes['bill_date']);
        //     $order_attributes['due_date'] = $this->convertdate($order_attributes['due_date']);
        // }

        // if ($request->fiscal_year && $request->fiscal_year != '' && \Auth::user()->hasRole('admins')) {
        //     $fiscal_year = \App\Models\Fiscalyear::findOrFail($request->fiscal_year);
        // } else {
        //     $fiscal_year = \App\Models\Fiscalyear::where('current_year', '1')->first();
        // }
        $findid=\App\Models\Client::where('name', $supplier)->first()->id;
        $findfiscalyear=\App\Models\Fiscalyear::where('fiscal_year', $fiscalyear)->first();

        $purchase = new PurchaseOrder();
        $purchase->org_id = $org_id;
        $purchase->supplier_id = $findid;
        $purchase->bill_no= $bill;
        $purchase->project_id = 0;
        $purchase->entry_id=0;
        $purchase->user_id= \App\User::where('enabled', '1')->where('org_id', \Auth::user()->org_id)->where('first_name', $purchaseowner)->first()->id;
        $purchase->ord_date= $order;
        $purchase->due_date= $supplierpayment;
        $purchase->bill_date= $supplierdate;
        $purchase->purchase_type = $type;
        $purchase->delivery_date=$delivery;
        $purchase->reference= $reference;
        $purchase->status= $status;
        $purchase->pan_no=$pan;
        $purchase->into_stock_location= \App\Models\PosOutlets::where('name', $outlets)->first()->id;
        $purchase->currency= $currency;
        if($isrenewal== "No"){
        $purchase->is_renewal= 0;
        }
        $purchase->tax_amount = 0;
        $purchase->taxable_amount = 0;
        $purchase->total = 0;
        $purchase->supplier_type = "supplier";
        $purchase->ledger_id = (\App\Models\Client::find($findid))->ledger_id;
        $purchase->fiscal_year = $findfiscalyear->fiscal_year;
        $purchase->fiscal_year_id = $findfiscalyear->id;
        $purchase->is_import= $isimport;
        $purchase->save();
        
        
        $tempid=0;
        foreach ($collection as $key => $value) {
            if($key > 19 ){
                if($value[0] && $value[0] != ""){
                    $purchasedetail = new \App\Models\PurchaseOrderDetail();
                    $purchasedetail->suplier_id = $findid;
                    $purchasedetail->order_no= $purchase->id;
                    $purchasedetail->product_id= \App\Models\Product::where('name', $value[0])->first()->id??0;
                    $purchasedetail->unit_price= $value[2];
                    $purchasedetail->qty_invoiced = $value[1];
                    $purchasedetail->quantity_ordered = $value[1];
                    $purchasedetail->quantity_recieved = $value[1];
                    $purchasedetail->tax_type_id = $value[5]== 1 ? 13: 0 ;
                    $purchasedetail->total = $value[7];
                    $purchasedetail->tax_amount = $value[6];
                    $purchasedetail->units =$value[4];
                    $purchasedetail->discount =$value[3];
                    $purchasedetail->is_inventory = 1;
                    $purchasedetail->save();

                        $landing_price=$value[7]/$value[1];
                        $stockMove = new \App\Models\StockMove();
                        $stockMove->stock_id = \App\Models\Product::where('name', $value[0])->first()->id??0;
                        $stockMove->trans_type = PURCHINVOICE;
                        $stockMove->tran_date = $supplierdate;
                        $stockMove->user_id = \Auth::user()->id;
                        $stockMove->reference = 'store_in_' . $purchase->id;
                        $stockMove->transaction_reference_id = $purchase->id;
                        $stockMove->store_id = $purchase->into_stock_location;
                        $stockMove->qty = $value[1];
                        $stockMove->price = $landing_price;
                        $stockMove->order_no = $purchase->id;
                        $stockMove->order_reference = $purchase->id;
                        $stockMove->save();

                }elseif($value[6]=="Amount" && $value[7] && $value[7]!=""){
                    $purchase= \App\Models\PurchaseOrder::find($purchase->id);
                    $purchase->subtotal = $collection[$key][7]??0;
                    $purchase->discount_amount = $collection[$key+1][7]??0;
                    $purchase->non_taxable_amount = $collection[$key+2][7]??0;
                    $purchase->taxable_amount = $collection[$key+3][7]??0;
                    $purchase->tax_amount = $collection[$key+4][7]??0;
                    $purchase->total = $collection[$key+5][7]??0;
                    $purchase->save();
                    break;
                }
                // dd($value[0],\App\Models\Product::where('name',$value[0])->first()); 
            }
           

        }
        DB::commit();
        
        // $costtype= [6=>'Banking Charges',7=>'Flight Charges',8=>'Custom duty',9=>'Warehouse',10=>'Clearing Charges',11=>'Landing Wages',12=>'CARRIAGE INWARD',13=>'INSURANCE'];
        $costtype= [8=>"BANK CHARGES",9=>"FLIGHT CHARGES",10=>"CUSTOM DUTY",11=>"WAREHOUSE",12=>"CLEARING CHARGES",13=>"LANDING WAGES", 14=>"CARRIAGE INWARD", 15=>"INSURANCE", 16=>"VAT"];
        foreach($collection as $key => $value){
            $count = count($value);
            // for($i=)
            if($key > 19){

                for($i=8; $i<$count;$i++){
                    if($value[$i] && $value[$i]!= ''){
                    $import= new \App\Models\ImportPurchase();
                    $import->purchase_order_id = $purchase->id;
                    $import->debit_account_ledger_id = 1236;
                    $import->credit_account_ledger_id = 1;
                    $import->cost_type= $costtype[$i];
                    $import->product_id= \App\Models\Product::where('name', $value[0])->first()->id??0;
                    // dd($value[$i]);
                    $import->amount= (double)$value[$i];
                    $import->method= "Quantity";
                    $import->save();
                }
                }

            }
        }
        DB::commit();


            $this->updateEntries($purchase->id);

        Flash::success('Purchase Order Import Successfully.');

        return redirect('/admin/purchase?type=bills');
    }
    private function updateEntries($orderId)
    {
        // dd($orderId);
        $purchaseorder = \App\Models\PurchaseOrder::find($orderId);
        // dd($purchaseorder);
        $totalAmountBeforeTax = $purchaseorder->taxable_amount + $purchaseorder->non_taxable_amount;
        if ($purchaseorder->supplier_type == 'cash_equivalent') {
            $supplier_ledger_id = $purchaseorder->supplier_id; //supplier_directly coems from ledgers
        } else {
            $supplier_ledger_id = \App\Models\Client::find($purchaseorder->supplier_id)->ledger_id;
        }
        if ($purchaseorder->entry_id && $purchaseorder->entry_id != '0') { //update the ledgers
            $attributes['entrytype_id'] = '6'; //Receipt
            $attributes['tag_id'] = '20'; //Material cost
            $attributes['user_id'] = \Auth::user()->id;
            $attributes['org_id'] = \Auth::user()->org_id;
            $attributes['number'] = \FinanceHelper::get_last_entry_number($attributes['entrytype_id']);
            $attributes['date'] = $purchaseorder->bill_date;
            $attributes['dr_total'] = $purchaseorder->total;
            $attributes['cr_total'] = $purchaseorder->total;
            $attributes['source'] = 'AUTO_PURCHASE_ORDER';
            $attributes['bill_no'] = $purchaseorder->bill_no;
            $attributes['ref_id'] = $purchaseorder->id;
            $attributes['currency'] = $purchaseorder->currency;
            $entry = \App\Models\Entry::find($purchaseorder->entry_id);
            $entry->update($attributes);

            // Creddited to Customer or Interest or eq ledger
            $sub_amount = \App\Models\Entryitem::where('entry_id', $purchaseorder->entry_id)->where('dc', 'C')->first();
            $sub_amount->entry_id = $entry->id;
            $sub_amount->user_id = \Auth::user()->id;
            $sub_amount->org_id = \Auth::user()->org_id;
            $sub_amount->dc = 'C';
            $sub_amount->ledger_id = \App\Models\Client::find($purchaseorder->supplier_id)->ledger_id; //Client ledger
            $sub_amount->amount = $purchaseorder->total;
            $sub_amount->narration = 'Amount to pay to supplier'; //$request->user_id
            //dd($sub_amount);
            $sub_amount->update();

            // Debitte to Bank or cash account that we are already in
            if ($purchaseorder->purchase_type == 'assets') {
                $this->createproductentries($purchaseorder, $entry);
            } else {
                $cash_amount = \App\Models\Entryitem::where('entry_id', $purchaseorder->entry_id)->where('dc', 'D')
                    ->where('ledger_id', \FinanceHelper::get_ledger_id('PURCHASE_LEDGER_ID'))->first();
                $cash_amount->entry_id = $entry->id;
                $cash_amount->user_id = \Auth::user()->id;
                $cash_amount->org_id = \Auth::user()->org_id;
                $cash_amount->dc = 'D';
                $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('PURCHASE_LEDGER_ID'); // Purchase ledger if selected or ledgers from .env
                $cash_amount->amount = $totalAmountBeforeTax;
                $cash_amount->narration = 'Actual Cost';
                $cash_amount->update();
            }

            //send to purchase tax ledger
            $tax_amount = \App\Models\Entryitem::where('entry_id', $purchaseorder->entry_id)->where('dc', 'D')
                ->where('ledger_id', \FinanceHelper::get_ledger_id('PURCHASE_TAX_PAYABLE'))->first();
            $tax_amount->entry_id = $entry->id;
            $tax_amount->user_id = \Auth::user()->id;
            $tax_amount->org_id = \Auth::user()->org_id;
            $tax_amount->dc = 'D';
            $tax_amount->ledger_id = \FinanceHelper::get_ledger_id('PURCHASE_TAX_PAYABLE'); // Purchase ledger if selected or ledgers from .env
            $tax_amount->amount = $purchaseorder->tax_amount;
            $tax_amount->narration = 'Vendor Tax amount';
            $tax_amount->update();

            if($purchaseorder->is_import==1){
                $dr_amount=0;
                $cr_amount=0;
                $importpurchase=\App\Models\ImportPurchase::where('purchase_order_id',$purchaseorder->id)->get();
                \App\Models\Entryitem::where('entry_id',$entry->id)->where(is_additional_cost,1)->delete();
                foreach ($importpurchase as $costitem){
                    //debit entry
                    $debit_entryitem = new \App\Models\Entryitem();
                    $debit_entryitem->entry_id = $entry->id;
                    $debit_entryitem->user_id = \Auth::user()->id;
                    $debit_entryitem->org_id = \Auth::user()->org_id;
                    $debit_entryitem->dc = 'D';
                    $debit_entryitem->ledger_id = $costitem->debit_account_ledger_id;
                    $debit_entryitem->amount = $costitem->amount;
                    $debit_entryitem->is_additional_cost = 1;
                    $debit_entryitem->narration = 'Import purchase '.$costitem->cost_type .' cost added';
                    $debit_entryitem->save();
                    $dr_amount+=$costitem->amount;

                    //credit entry
                    $credit_entryitem = new \App\Models\Entryitem();
                    $credit_entryitem->entry_id = $entry->id;
                    $credit_entryitem->user_id = \Auth::user()->id;
                    $credit_entryitem->org_id = \Auth::user()->org_id;
                    $credit_entryitem->dc = 'C';
                    $credit_entryitem->ledger_id = $costitem->credit_account_ledger_id;
                    $credit_entryitem->amount = $costitem->amount;
                    $credit_entryitem->is_additional_cost =1;
                    $credit_entryitem->narration = 'Import purchase '.$costitem->cost_type .' cost added';
                    $credit_entryitem->save();
                    $cr_amount+=$costitem->amount;
                }
                $entry_update=\App\Models\Entry::where('id',$entry->id)->update(['dr_total'=>$dr_amount,'cr_total'=>$cr_amount]);

            }

        } else {

            //create the new entry items
            $attributes['entrytype_id'] = '6'; //Receipt
            $attributes['tag_id'] = '20'; //Revenue
            $attributes['user_id'] = \Auth::user()->id;
            $attributes['org_id'] = \Auth::user()->org_id;
            $attributes['number'] = \FinanceHelper::get_last_entry_number($attributes['entrytype_id']);
            $attributes['date'] = $purchaseorder->bill_date;
            $attributes['dr_total'] = $purchaseorder->total;
            $attributes['cr_total'] = $purchaseorder->total;
            $attributes['bill_no'] = $purchaseorder->bill_no;
            $attributes['ref_id'] = $purchaseorder->id;
            $attributes['source'] = 'AUTO_PURCHASE_ORDER';
            $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
             $attributes['currency'] = $purchaseorder->currency;
            $entry = \App\Models\Entry::create($attributes);

            // Creddited to Customer or Interest or eq ledger
            $sub_amount = new \App\Models\Entryitem();
            $sub_amount->entry_id = $entry->id;
            $sub_amount->user_id = \Auth::user()->id;
            $sub_amount->org_id = \Auth::user()->org_id;
            $sub_amount->dc = 'C';
            $sub_amount->ledger_id = \App\Models\Client::find($purchaseorder->supplier_id)->ledger_id; //Client ledger
            $sub_amount->amount = $purchaseorder->total;
            $sub_amount->narration = 'Amount to pay to supplier'; //$request->user_id
            //dd($sub_amount);
            $sub_amount->save();

            // Debitte to Bank or cash account that we are already in
            if ($purchaseorder->purchase_type == 'assets') {
                $this->createproductentries($purchaseorder, $entry);
            } else {
                $cash_amount = new \App\Models\Entryitem();
                $cash_amount->entry_id = $entry->id;
                $cash_amount->user_id = \Auth::user()->id;
                $cash_amount->org_id = \Auth::user()->org_id;
                $cash_amount->dc = 'D';
                $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('PURCHASE_LEDGER_ID'); // Purchase ledger if selected or ledgers from .env
                $cash_amount->amount = $totalAmountBeforeTax;;
                $cash_amount->narration = 'Actual Cost';
                $cash_amount->save();
            }
            $tax_amount = new \App\Models\Entryitem();
            $tax_amount->entry_id = $entry->id;
            $tax_amount->user_id = \Auth::user()->id;
            $tax_amount->org_id = \Auth::user()->org_id;
            $tax_amount->dc = 'D';
            $tax_amount->ledger_id = \FinanceHelper::get_ledger_id('PURCHASE_TAX_PAYABLE'); // Puchase ledger if selected or ledgers from .env
            $tax_amount->amount = $purchaseorder->tax_amount;
            $tax_amount->narration = 'Vendor Tax Amount';
            $tax_amount->save();

            //now update entry_id in income row
            $purchaseorder->update(['entry_id' => $entry->id]);

             //import purchase
            if($purchaseorder->is_import==1){
                $dr_amount=0;
                $cr_amount=0;
                $importpurchase=\App\Models\ImportPurchase::where('purchase_order_id',$purchaseorder->id)->get();
                foreach ($importpurchase as $costitem){
                    //debit entry
                    $debit_entryitem = new \App\Models\Entryitem();
                    $debit_entryitem->entry_id = $entry->id;
                    $debit_entryitem->user_id = \Auth::user()->id;
                    $debit_entryitem->org_id = \Auth::user()->org_id;
                    $debit_entryitem->dc = 'D';
                    $debit_entryitem->ledger_id = $costitem->debit_account_ledger_id;
                    $debit_entryitem->amount = $costitem->amount;
                    $debit_entryitem->is_additional_cost =1;
                    $debit_entryitem->narration = 'Import purchase '.$costitem->cost_type .' cost added';
                    $debit_entryitem->save();
                    $dr_amount+=$costitem->amount;

                    //credit entry
                    $credit_entryitem = new \App\Models\Entryitem();
                    $credit_entryitem->entry_id = $entry->id;
                    $credit_entryitem->user_id = \Auth::user()->id;
                    $credit_entryitem->org_id = \Auth::user()->org_id;
                    $credit_entryitem->dc = 'C';
                    $credit_entryitem->ledger_id = $costitem->credit_account_ledger_id;
                    $credit_entryitem->amount = $costitem->amount;
                    $credit_entryitem->is_additional_cost =1;
                    $credit_entryitem->narration = 'Import purchase '.$costitem->cost_type .' cost added';
                    $credit_entryitem->save();
                    $cr_amount+=$costitem->amount;
                }
                $dr_amount+=$entry->dr_total;
                $cr_amount+=$entry->cr_total;
               $entry_update=\App\Models\Entry::where('id',$entry->id)->update(['dr_total'=>$dr_amount,'cr_total'=>$cr_amount]);

            }

        }
        return 0;
    }
    
}
