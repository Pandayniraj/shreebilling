<?php 

namespace App\Http\Controllers;

use App\Models\Role as Permission;
use App\Models\Audit as Audit;
use Flash;
use DB;
use Auth;

use App\User;
use App\Models\COAgroups;
use App\Models\COALedgers;
use App\Models\Entryitem;
use App\Models\Entry;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER
 */

class ReconciliationController extends Controller
{
    // Stock Category
    public function index(Request $request)
    {
      $id = $request->ledger_id;
      $page_title = 'Reconciliation Leadgers';
      $page_description = 'All Lists of Reconciliation';
      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
      $ledgers_data= COALedgers::find($id);

      if(!empty($request->input('startdate')) && !empty($request->input('enddate'))){
        $startdate = $request->startdate;
        $enddate = $request->enddate;

        
         $entry_items = Entry::where('date','>=',$startdate)->where('date','<=',$enddate)  
                     ->join('entryitems',function($join) use ($id){
                              $join->on('entries.id','=','entryitems.entry_id')->where('ledger_id',$id);
                            })
                          ->get();
      }
      else{
        $entry_items= Entryitem::where('ledger_id',$id)->get();
      }
     return view('admin.coa.reconciliation', compact('page_title','page_description','groups','ledgers_data','entry_items','id'));

    }


    public function reconciliationdetail(Request $request)
    {
      $id = $request->ledger_id;
      $page_title = 'Reconciliation Leadgers';
      $page_description = 'All Lists of Reconciliation';
      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
      $ledgers_data= COALedgers::find($id);

      if(!empty($request->input('startdate')) && !empty($request->input('enddate'))){
        $startdate = $request->startdate;
        $enddate = $request->enddate;

        
         $entry_items = Entry::where('date','>=',$startdate)->where('date','<=',$enddate)  
                     ->join('entryitems',function($join) use ($id){
                              $join->on('entries.id','=','entryitems.entry_id')->where('ledger_id',$id);
                            })
                          ->get();
      }
      else{
        $entry_items= Entryitem::where('ledger_id',$id)->get();
      }
     return view('admin.coa.reconciliationdetail', compact('page_title','page_description','groups','ledgers_data','entry_items','id')); 
    }
    public function update(Request $request)
    {
        foreach($request['reconciliation_date'] as $index=>$date)
        {
            $entry_item = Entryitem::find($index);
            $attributes['reconciliation_date'] = $date;
            $entry_item->update($attributes);
        }

         Flash::success('Entry Items Reconciliation Updated Sucessfully');
            return redirect()->back();
    }


     public function DownloadPdf(Request $request ,$id){
     $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
     $currency_code = $request->currency;
     $ledgers_data= COALedgers::find($id);

      if(!empty($request->input('startdate')) && !empty($request->input('enddate'))){
          $startdate = $request->startdate;
          $enddate = $request->enddate;
         if(!empty($request->currency)){
                 $entry_items = Entryitem::where('ledger_id',$id)
               ->join('entries',function($join) use ($currency_code,$startdate,$enddate){
                  $join->on('entryitems.entry_id','=','entries.id')->where('entries.currency','=',$currency_code)->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate);
                })
              ->get();
            }else{
                 $entry_items = Entryitem::where('ledger_id',$id)
               ->join('entries',function($join) use ($startdate,$enddate){
                  $join->on('entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate);
                })
              ->get();
            }
        }else{
          if(!empty($request->currency)){
                 $entry_items = Entryitem::where('ledger_id',$id)
               ->join('entries',function($join) use ($currency_code){
                  $join->on('entryitems.entry_id','=','entries.id')->where('entries.currency','=',$currency_code);
                })
              ->get();
            }else{
               $entry_items= Entryitem::where('ledger_id',$id)->get();
            }
         
        }

         $imagepath=\Auth::user()->organization->logo;

         $app_currency = env(APP_CURRENCY);


        $pdf = \PDF::loadView('admin.coa.reconsilepdf', compact('app_currency','entries','entriesitem','groups','ledgers_data','entry_items','id','imagepath'));

        $file = $id.'_'.$ledgers_data->name.'.pdf';

        if (\File::exists('reports/'.$file))
        {
            \File::Delete('reports/'.$file);
        }
        return $pdf->download($file);

    }


     public function PrintLedgers(Request $request,$id)
     { 
        $page_title = 'Entry Show';
        $page_description = 'show entries'; 
        $imagepath=\Auth::user()->organization->logo;
        $currency_code = $request->currency;

        $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
        $ledgers_data= COALedgers::find($id);
        if(!empty($request->input('startdate')) && !empty($request->input('enddate'))){
          $startdate = $request->startdate;
          $enddate = $request->enddate;
          if(!empty($request->currency)){
                 $entry_items = Entryitem::where('ledger_id',$id)
               ->join('entries',function($join) use ($currency_code,$startdate,$enddate){
                  $join->on('entryitems.entry_id','=','entries.id')->where('entries.currency','=',$currency_code)->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate);
                })
              ->get();
            }else{
                 $entry_items = Entryitem::where('ledger_id',$id)
               ->join('entries',function($join) use ($startdate,$enddate){
                  $join->on('entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate);
                })
              ->get();
            }
        }else{
           if(!empty($request->currency)){
                 $entry_items = Entryitem::where('ledger_id',$id)
               ->join('entries',function($join) use ($currency_code){
                  $join->on('entryitems.entry_id','=','entries.id')->where('entries.currency','=',$currency_code);
                })
              ->get();
            }else{
               $entry_items= Entryitem::where('ledger_id',$id)->get();
            }
         
        }
         return view('admin.coa.reconsileprint', compact('entries','entriesitem','$groups','ledgers_data','entry_items','id','imagepath'));

    }

    public function downloadExcel(Request $request,$id){
      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
      $ledgers_data= COALedgers::find($id);

      $currency_code = $request->currency;

         if(!empty($request->input('startdate')) && !empty($request->input('enddate'))){
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');

              if(!empty($request->currency)){
                 $entry_items = Entryitem::where('ledger_id',$id)
               ->join('entries',function($join) use ($currency_code,$startdate,$enddate){
                  $join->on('entryitems.entry_id','=','entries.id')->where('entries.currency','=',$currency_code)->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate);
                })
              ->get();
            }else{
                $entry_items = Entryitem::where('ledger_id',$id)
               ->join('entries',function($join) use ($startdate,$enddate){
                  $join->on('entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate);
                })
               ->get();
            }
       
      }else{
         if(!empty($request->currency)){
            $entry_items= Entryitem::where('ledger_id',$id)
              ->join('entries',function($join) use ($currency_code){
                $join->on('entryitems.entry_id','=','entries.id')->where('entries.currency','=',$currency_code);
              })
              ->get();
            }else{
                $entry_items= Entryitem::where('ledger_id',$id)->get();
            }
          $mytime = \Carbon\Carbon::now();
          $startdate = $mytime->copy()->startOfYear();
          $enddate   = $mytime->copy()->endOfYear();
      }
    

      $company_name = env('APP_COMPANY');

     return \Excel::download(new \App\Exports\ReconsileLeadgerExcelExport($entry_items,$company_name,$ledgers_data,$startdate,$enddate), 'leadger_'.$ledgers_data->name.'.xls');
    }



}
