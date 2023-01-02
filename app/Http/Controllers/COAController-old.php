<?php namespace App\Http\Controllers;

use App\Models\Role as Permission;
use App\Models\Audit as Audit;
use Flash;
use DB;
use Auth;

use App\User;
use App\Models\Department;
use App\Models\Entryitem;
use App\Models\COAgroups;
use App\Models\COALedgers;
use App\Models\Designation;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER
 */

class COAController extends Controller
{
    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
    }

    // Stock Category
    public function index()
    {
        $page_title = 'Chart Of Account';

        $page_description = 'All Chart of accounts';

        $groups= COAgroups::orderBy('code', 'asc')->get();

        return view('admin.coa.index', compact('page_title','page_description','groups'));
    }
    public function CreateGroups(){

      $page_title = 'Groups Add';
      $page_description = 'create groups';

      $groups= COAgroups::orderBy('code','asc')->get();


    return view('admin.coa.creategroups', compact('page_title','page_description','groups'));

    }

    public function PostGroups(Request $request){

      $this->validate($request, array(
          'parent_id' => 'required',
          'name' => 'required'
        ));

      $check = COAgroups::where('code',$request->code)->where('org_id',\Auth::user()->org_id)->exists();
      if($check){
          return \Redirect::back()->withErrors(['error'=>'Code Already Taken']);
      }
      //dd($request->all());
      $detail = new COAgroups();
      $detail->parent_id= $request->parent_id;
      $detail->org_id = \Auth::user()->org_id;
      $detail->user_id = \Auth::user()->id;
      $detail->code = $request->code;
      $detail->name= $request->name;
      $detail->affects_gross= $request->affects_gross;
      $detail->save();

     Flash::success("Groups Created Successfully");

      return redirect('/admin/chartofaccounts');

    }

     public function CreateLedgers(){

      $page_title = 'Ledgers Add';
      $page_description = 'create Ledgers';

      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
      if(\Request::ajax()){
        $expenses_type = $_GET['expenses_type']; //for detrmining which select option
        $selectedgroup_value =$_GET['selected_value'];
        return view('admin.coa.modals.createledgers',compact('page_title','page_description','groups','expenses_type','selectedgroup_value'));
      }
     return view('admin.coa.createledgers', compact('page_title','page_description','groups'));

    }


     public function PostLedgers(Request $request){
      if(\Request::ajax()){
            $validator = \Validator::make($request->all(), [
              'code' => 'required | unique:coa_ledgers',
              'group_id' => 'required',
              'name' => 'required',
              'op_balance_dc' => 'required',
              'op_balance' => 'required'
            ]);
            if ($validator->fails()) {
                return ['error'=>$validator->errors()];
            }
        }
       $this->validate($request, array(
          'group_id' => 'required',
          'name' => 'required',
          'op_balance_dc' => 'required',
          'op_balance' => 'required'

         ));
      $check = COALedgers::where('code',$request->code)->where('org_id',\Auth::user()->org_id)->exists();
      if($check){
          return \Redirect::back()->withErrors(['error'=>'Code Already Taken']);
      }
      $detail = new COALedgers();
      $detail->group_id= $request->group_id;

      $detail->org_id = \Auth::user()->org_id;
      $detail->user_id = \Auth::user()->id;

      $detail->code= $request->code;
      $detail->name= $request->name;
      $detail->op_balance_dc= $request->op_balance_dc;
      $detail->op_balance= $request->op_balance;
      $detail->notes= $request->notes;

      if($request->type == 1){
         $detail->type= $request->type;
      }else{
        $detail->type=0;
      }
      if($request->reconciliation == 1){
         $detail->type= $request->reconciliation;
      }else{
        $detail->reconciliation=0;
      }

      $detail->save();


      if(\Request::ajax()){
        if(isset($_GET['selectedgroup'])){
          $selectedgroup = $_GET['selectedgroup'];
          $data = view('admin.coa.modals.ajaxledgergroup-select', compact('selectedgroup'))->render();
        }else{
           $lastcreated = $detail;
           $data = view('admin.coa.modals.ajaxledgergroup-select', compact('lastcreated'))->render();
        }
        return ['data'=>$data,'status'=>'success','lastcreated'=>$detail];
      }
      Flash::success("Ledgers Created Successfully");

      return redirect('/admin/chartofaccounts');

    }


     public function EditGroups($id){

       $page_title = 'Groups Edit';
       $page_description = 'edit groups';
       $groups= COAgroups::orderBy('code','asc')->get();

       $group_data= COAgroups::find($id);


       return view('admin.coa.editgroups', compact('page_title','page_description','groups','group_data'));

    }

     public function UpdateGroups(Request $request,$id){

        $coagroups = COAgroups::find($id);
        $original_value= COAGroups::where('id',$id)->first()->code;

        if($request->code != $original_value){

         $this->validate($request, array(
          'code' => 'required |  unique:coa_groups'
         ));

         }

         if($id<=4){
          Flash::error('Permission Denied To Update this Group');
          return redirect('/admin/chartofaccounts');
         }

        $this->validate($request, array(
          'name' => 'required',
          'parent_id' => 'required'
         ));

        $attributes = $request->all();
        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['user_id'] = \Auth::user()->id;

        $coagroups->update($attributes);
        Flash::success('Group Updated Successfully');

      return redirect('/admin/chartofaccounts');

    }



     public function EditLedgers($id){

      $page_title = 'Ledgers Edit';
      $page_description = 'edit Ledgers';

      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

      $ledgers_data= COALedgers::find($id);


     return view('admin.coa.editledgers', compact('page_title','page_description','groups','ledgers_data'));


    }

    public function UpdateLedgers(Request $request,$id){

      $coaledgers = COALedgers::find($id);

      $original_value= COALedgers::where('id',$id)->first()->code;

      if($request->code != $original_value){




         $this->validate($request, array(
          'code' => 'required |  unique:coa_ledgers'
         ));



       }

       $this->validate($request, array(
          'name' => 'required',
          'group_id' => 'required',
          'op_balance_dc' => 'required',
          'op_balance' => 'required'

         ));

      $attributes = $request->all();

      $attributes['org_id'] = \Auth::user()->org_id;
      $attributes['user_id'] = \Auth::user()->id;

      $attributes['type']= $request->type;
      $attributes['reconciliation']= $request->reconciliation;

      $coaledgers->update($attributes);
      Flash::success('Ledgers Updated Successfully');

    return redirect('/admin/chartofaccounts');

    }




  public function DownloadGroupPdf(Request $request)
  {
     $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
     // $ledgers_data= COALedgers::find($id);

           $groups_data = COAgroups::find($request->group_id);
           $ledgers_ids = $this->get_ledger_ids($request->group_id);


      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

       if(!empty($request->input('startdate')) && !empty($request->input('enddate'))){
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $entry_items = Entryitem::whereIn('ledger_id',$ledgers_ids)->leftjoin('entries','entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate)->get();
      }
      else{
        $entry_items= Entryitem::whereIn('ledger_id',$ledgers_ids)->get();
      }

         $imagepath=\Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.coa.groupDetailPdf', compact('entries','entriesitem','groups','groups_data','entry_items','id','imagepath'));

        $file = $groups_data->name.$entries->number.'.pdf';

        if (\File::exists('reports/'.$file))
        {
            \File::Delete('reports/'.$file);
        }
        return $pdf->download($file);
  }


  public function DownloadPdf(Request $request){

     $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
     $id = $request->ledger_id;
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

        $pdf = \PDF::loadView('admin.coa.pdf', compact('entries','entriesitem','groups','ledgers_data','entry_items','id','imagepath'));

        $file = $id.'_'.$entries->number.'.pdf';

        if (\File::exists('reports/'.$file))
        {
            \File::Delete('reports/'.$file);
        }
        return $pdf->download($file);

    }

    public function PrintGroups(Request $request)
    {
       $page_title = 'Entry Show';
       $page_description = 'show entries';
       $imagepath=\Auth::user()->organization->logo;


        $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();


       $groups_data = COAgroups::find($request->group_id);
       $ledgers_ids = $this->get_ledger_ids($request->group_id);


      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

      if(!empty($request->input('startdate')) && !empty($request->input('enddate'))){
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $entry_items = Entryitem::whereIn('ledger_id',$ledgers_ids)->leftjoin('entries','entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate)->get();
      }
      else{
        $entry_items= Entryitem::whereIn('ledger_id',$ledgers_ids)->get();
      }
         return view('admin.coa.printgroups', compact('entries','entriesitem','$groups','groups_data','entry_items','id','imagepath'));
    }

     public function PrintLedgers(Request $request)
     {
        $page_title = 'Entry Show';
        $page_description = 'show entries';
        $imagepath=\Auth::user()->organization->logo;
        $id = $request->ledger_id;
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
         return view('admin.coa.print', compact('entries','entriesitem','$groups','ledgers_data','entry_items','id','imagepath'));

    }

    public function downloadGroupExcel(Request $request)
    {
      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

           $groups_data = COAgroups::find($request->group_id);
           $ledgers_ids = $this->get_ledger_ids($request->group_id);

      // $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

      if(!empty($request->input('startdate')) && !empty($request->input('enddate'))){
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $entry_items = Entryitem::whereIn('ledger_id',$ledgers_ids)->leftjoin('entries','entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate)->get();
      }
      else{
        $entry_items= Entryitem::whereIn('ledger_id',$ledgers_ids)->get();
      }


      $mytime = \Carbon\Carbon::now();
      $startOfYear = $mytime->copy()->startOfYear();
      $endOfYear   = $mytime->copy()->endOfYear();
      // $type = ($groups_data->op_balance_dc == 'D')?'Dr':'Cr';
      // $closing_balance = \TaskHelper::getLedgerBalance($id)['ledger_balance'];
      $heading = [
        [
          'Bank or cash account',($groups_data->name),
        ],
      ];

      $total=0;
      foreach($entry_items as $ei){
        $entry_balance = \TaskHelper::calculate_withdc($entry_balance['amount'], $entry_balance['dc'],
          $ei['amount'], $ei['dc']);
        $getledger= \TaskHelper::getLedger($ei->entry_id);
        $type = $ei->dc=='D'?'Dr':'Cr';
        $entry[] = [
          'entry_date'=>$ei->entry->date,
          'entry_number'=>$ei->entry->number,
          'ledger'=>$ei->ledgerdetail->name,
          'Description'=>$getledger,
          'entry_type'=>$ei->entry->entrytype->name,
          'tagname'=>$ei->entry->tagname->title,
          'Dr'=>$ei->dc=='D'? $ei->amount:'0',
          'Cr'=>$ei->dc!='D'? $ei->amount:'0',
          'balance'=> $type.' '.$entry_balance['amount'],
        ];
        $total = $type.' '.$entry_balance['amount'];
     }
     $entry[] = ['','','','','','','Total',$total];
     return \Excel::download(new \App\Exports\ExcelExport($entry), 'group_'.$groups_data->name.'.csv');
    }


    public function downloadExcel(Request $request){

      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
      $id = $request->ledger_id;
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

     return \Excel::download(new \App\Exports\LeadgerExcelExport($entry_items,$company_name,$ledgers_data,$startdate,$enddate), 'leadger_'.$ledgers_data->name.'.xls');
    }


    public function GetNextCode(Request $request){

     $id=$request->id;
     $group_data= COAgroups::find($id);
     $group_code=$group_data->code;
     $g= COAgroups::where('parent_id',$id)->where('org_id',\Auth::user()->org_id)->where('code','!=','null')->get();
     if(count($g) > 0){
       $last= $g->last();
       $last = $last->code;
       $l_array = explode('-', $last);
       $new_index = end($l_array);
       $new_index += 1;
       $new_index = sprintf("%02d", $new_index);
       $code=$group_code."-".$new_index;
       return $code;
     }
     else{
      $code= $group_code."-01";
      return $code;
     }
    }


  public function getNextCodeLedgers(Request $request) {
    $id=$request->id;
    $group_data= COAgroups::find($id);
    $group_code=$group_data->code;
    $q= COALedgers::where('group_id',$id)->where('org_id',\Auth::user()->org_id)->where('code','!=','null')->get();
    if(count($q) > 0){
      $codes =[];
      foreach($q as $c){
        $code = $c->code;
        $codearr = explode('-', $code);
        array_push($codes,(int)$codearr[count($codearr)-1]);
      }
      $new_index = max($codes) + 1;
      $new_index = sprintf("%04d", $new_index);
      return $group_code."-".$new_index;
    }else{

      return $group_code."-0001";
    }

  }



  public function getModalDeleteGroups($id){

        $groups = COAgroups::find($id);

         $modal_title = 'Delete Group';

         $groups = COAgroups::find($id);

         $modal_route = route('admin.chartofaccounts.groups.delete', array('id' => $groups->id));

        $modal_body = 'Are you sure you want to delete this Group?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));


  }

  public function destroyGroups($id){

        $groups = COAgroups::find($id);

        if($id ==''){
          Flash::error('Group Not Specified.');
          return redirect('/admin/chartofaccounts');
        }

        if($id <='4'){
          Flash::error('Permission Denied You cannot Delete this Group');
          return redirect('/admin/chartofaccounts');
        }

         $no_of_child=COAgroups::where('parent_id',$id)->get();
          if(count($no_of_child)>0){

          Flash::error('Child Group Exists So cannot Delete Group');
          return redirect('/admin/chartofaccounts');
        }

         $no_of_ledgers=COALedgers::where('group_id',$id)->get();

          if(count($no_of_ledgers)>0){

          Flash::error('Ledgers Of Group Exists So cannot Delete Group');
          return redirect('/admin/chartofaccounts');
        }




        COAgroups::find($id)->delete();

        Flash::success('Group successfully deleted.');


        return redirect('/admin/chartofaccounts');

  }

  public function getModalDeleteLedgers($id){

        $ledgers = COALedgers::find($id);

         $modal_title = 'Delete Ledger';

         $ledgers = COALedgers::find($id);

         $modal_route = route('admin.chartofaccounts.ledgers.delete', $ledgers->id);

         $modal_body = 'Are you sure you want to delete this Ledgers?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));

  }

  public function destroyLedgers($id){

        $ledgers = COALedgers::find($id);


        if($id ==''){

          Flash::error('Ledger Not Specified.');
          return redirect('/admin/chartofaccounts');

        }

        $no_of_ledgers=\App\Models\Entryitem::where('ledger_id',$id)->get();

        if(count($no_of_ledgers)>0){

          Flash::error('Entries Of Ledger Exists So cannot Delete Ledger');
          return redirect('/admin/chartofaccounts');

        }

        COALedgers::find($id)->delete();

        Flash::success('Ledger successfully deleted.');

        return redirect('/admin/chartofaccounts');

  }

  public function excelLedger(){
    $page_title = 'Admin | Export | Import';
    $page_description = "Export Import Ledger";
    return view('admin.excel.importExportLedger',compact('page_description','page_title'));
  }

  public function exportLedger($type){
  $data = COALedgers::where('org_id',\Auth::user()->org_id)->get()->toArray();


   return \Excel::download(new \App\Exports\ExcelExport($data), "ledgers.{$type}");


  }
  public function importLedger(Request $request){
    if(\Request::hasFile('import_file')){
      $path = \Request::file('import_file')->getRealPath();

      $data  = \Excel::toCollection(new \App\Exports\ExcelImport(), \Request::file('import_file'));

      if(!empty($data) && $data->count()){
       $data = $data->first()->toArray();
        foreach ($data as $key => $value) {
          $value = (object) $value;
          $insert [] = [
            'group_id' => $value->group_id,
            'name' => $value->name,
            'op_balance_dc' => $value->op_balance_dc,
            'op_balance' => $value->op_balance,
            'org_id'=>\Auth::user()->org_id,
            'user_id'=>\Auth::user()->id,
            'type'=>$value->type ? '1' : '0',
            'reconciliation'=>$value->type ? '1' : '0',
            'notes'=>$value->notes
          ];

        }
        if(!empty($insert)){
          $ledger = COALedgers::insert($insert);
          $lastcreated = COALedgers::orderBy('id', 'desc')->take(count($insert))->get();
          $lastcreated = $lastcreated->reverse();
          foreach ($lastcreated as $key => $ledger) {
            $request->request->add(['id'=>$ledger->group_id]);
            $this->getNextCodeLedgers($request);
            $code = $this->getNextCodeLedgers($request);
            $ledger->update(['code'=>$code]);
          }
        }
      }
      Flash::success("Ledger successfully added");
      return redirect()->back();
    }
    Flash::success('Sorry no file is selected to import leads.');
    return redirect()->back();

  }
  public function excelLedgergroups(){
    $page_title = 'Admin | Export | Import';
    $page_description = "Export Import Ledger";
    return view('admin.excel.importExportLedgerGroup',compact('page_description','page_title'));
  }

  public function exportLedgergroups($type){
    $data = COAgroups::where('org_id',\Auth::user()->org_id)->get()->toArray();
    return \Excel::download(new \App\Exports\ExcelExport($data), "ledgers_groups.{$type}");
  }
  public function importLedgergroups(Request $request){
    if(\Request::hasFile('import_file')){
      $path = \Request::file('import_file')->getRealPath();

      $data  = \Excel::toCollection(new \App\Exports\ExcelImport(), \Request::file('import_file'));

      if(!empty($data) && $data->count()){
        $data = $data->first()->toArray();
        foreach ($data as $key => $value) {
          $value = (object) $value;
          $insert [] = [
            'parent_id'=> $value->parent_id,
            'name' => $value->name,
            'user_id'=>\Auth::user()->id,
            'org_id'=>\Auth::user()->org_id
          ];
        }

        if(!empty($insert)){
          $ledger = COAgroups::insert($insert);
          $lastcreated = COAgroups::orderBy('id', 'desc')->take(count($insert))->get();
          $lastcreated = $lastcreated->reverse();
          foreach ($lastcreated as $key => $ledgergrp) {
            $request->request->add(['id'=>$ledgergrp->parent_id]);
            $this->getNextCodeLedgers($request);
            $code = $this->GetNextCode($request);
            $ledgergrp->update(['code'=>$code]);
          }
        }
      }
      Flash::success("Ledgers Groups successfully added");
      return redirect()->back();
    }
      Flash::success('Sorry no file is selected to import leads.');
      return redirect()->back();
  }

  public function filterByGroups(){

          $parentgroups = \App\Models\COAgroups::where('parent_id',null)->get();

          $page_title = "Admin | COA | Filter | Parent | Groups";

          $page_description = "List of Groups and Ledgers By Parent Group";

          //dd($parentgroups);

         return view('admin.coa.filterbygroups',compact('parentgroups','page_title','page_description'));

  }

    public function filterByGroupPost(Request $request){

          //dd('done');

        $parent_id = $request->parent_id;

        //dd($parent_id);

        $parentgroups = \App\Models\COAgroups::where('parent_id',null)->where('org_id',\Auth::user()->org_id)->get();

        $maingroup = \App\Models\COAGroups::find($parent_id);

        //dd($maingroup);
        $maingroupledgers = \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id',$maingroup->id)->where('org_id',\Auth::user()->org_id)->get();

        //dd($maingroupledgers);

        $page_title = "Admin | COA | Filter | Parent | Groups";

        $page_description = "List of Groups and Ledgers By Parent Group";


      return view('admin.coa.filterbygroupdetail',compact('page_title','page_description','parentgroups','parent_id','maingroup','maingroupledgers'));

    }
    public function get_ledger()

  {

        $term = strtolower(\Request::get('term'));

        $sub_groups = \App\Models\COAgroups::where('parent_id',20)->pluck('id')->toArray();

        //dd($sub_groups);

        //dd($term);

        $ledgers = \App\Models\COALedgers::select('id', 'name')
                  ->whereIn('group_id',$sub_groups)
                  ->where('name', 'LIKE', '%'.$term.'%')
                  ->groupBy('name')
                  ->take(10)->get();



      //dd($ledgers);

        $return_array = array();



        foreach ($ledgers as $v) {

        if (strpos(strtolower($v->name), $term) !== FALSE) {

                $return_array[] = array('value' => $v->name , 'id' =>$v->id);

            }

        }

        return \Response::json($return_array);

    }


       public function DetailLedgers($id,Request $request){

        $requestData = $request->except('ledger_id');
        $page_title = 'Ledgers Detail';
        $page_description = 'Detail Ledgers';
        $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

        if(!empty($request->input('ledger_id')))
        {
          $requestData['ledger_id'] = $request->ledger_id;
          $ledgers_data= COALedgers::find($request->ledger_id);
          $ledger_id = $request->ledger_id;
        }else{
          $requestData['ledger_id'] = $id;
          $ledgers_data= COALedgers::find($id);
          $ledger_id = $id;
        }


        $ledgers_data= COALedgers::find($ledger_id);

         if(!empty($request->input('startdate')) && !empty($request->input('enddate'))){
          $startdate = $request->startdate;
          $enddate = $request->enddate;
          $entry_items = Entryitem::where('ledger_id',$ledger_id)->leftjoin('entries','entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate)->get();
        }
        else{
          $entry_items= Entryitem::where('ledger_id',$ledger_id)->get();
        }
       return view('admin.coa.detailledgers', compact('page_title','page_description','groups','ledgers_data','entry_items','ledger_id','requestData'));
    }



    public function DetailGroups(Request $request ,$id)
    {

      $requestData = $request->except('group_id');

      $page_title = 'Groups Detail';
      $page_description = 'Detail Groups';

      // $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

       if(!empty($request->input('group_id')))
      {
       $requestData['group_id'] = $request->group_id;
       $groups_data = COAgroups::find($request->group_id);
       $ledgers_ids = $this->get_ledger_ids($request->group_id);
      }else{
       $groups_data = COAgroups::find($id);
       $ledgers_ids = $this->get_ledger_ids($id);
       $requestData['group_id'] = $id;
      }


      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

      if(!empty($request->input('startdate')) && !empty($request->input('enddate'))){
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $entry_items = Entryitem::whereIn('ledger_id',$ledgers_ids)->leftjoin('entries','entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate)->get();


      }
      else{
        $entry_items= Entryitem::whereIn('ledger_id',$ledgers_ids)->get();
      }
     return view('admin.coa.detailgroups', compact('page_title','page_description','groups','groups_data','entry_items','id','requestData'));
    }





    public function get_ledger_ids($parentId)
    {
        $ledgers_data = collect();
        $parent =  COAgroups::find($parentId);
        $ledgers_data = COALedgers::where('group_id',$parentId)->where('org_id',\Auth::user()->org_id)->get();
        // dd($parent);
        $subcategories = COAgroups::where('parent_id',$parent->id)->where('org_id',\Auth::user()->org_id)->get();

        foreach ($subcategories as $subcategory) {
              $ledgers_data = $ledgers_data->merge(COALedgers::where('group_id',$subcategory->id)->where('org_id',\Auth::user()->org_id)->get());
              $ledgers_data = $this->get_ledger_next_level($subcategory->id ,$ledgers_data);
            }

            return ($ledgers_data->pluck(id));
    }


    public function get_ledger_next_level($parentId , $ledgers_data)
    {
        $parent =  COAgroups::find($parentId);
        $ledgers_data = $ledgers_data->merge(COALedgers::where('group_id',$parentId)->where('org_id',\Auth::user()->org_id)->get());
        $subcategories = COAgroups::where('parent_id',$parent->id)->where('org_id',\Auth::user()->org_id)->get();

        foreach ($subcategories as $subcategory) {
              $ledgers_data = $ledgers_data->merge(COALedgers::where('group_id',$subcategory->id)->where('org_id',\Auth::user()->org_id)->get());
              $this->get_ledger_next_level($subcategory->id ,$ledgers_data);
            }
            return $ledgers_data;
    }

        public function groupLedgerBulk(Request $request)
    {
        $requestData = $request->except('group_id');

        $page_title = 'Bulk Groups Ledger Statement';
        $page_description = 'Bulk Ledger';
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $groups = COAgroups::orderBy('code', 'asc')->get();
        $allFiscalYear = \App\Models\Fiscalyear::select('id','fiscal_year','start_date','end_date')->latest()->where('org_id',auth()->user()->org_id)->get();
        $current_fiscal=\App\Models\Fiscalyear::where('current_year', 1)->where('org_id',auth()->user()->org_id)->first();
        $fiscal_year = \Request::get('fiscal_year')?  \Request::get('fiscal_year'): $current_fiscal->fiscal_year ;

        if(!$request->group_id)
            return view('admin.accountreport.groupledgerbulk.detailgroups', compact('fiscal_year','page_title','page_description','startdate','enddate','allFiscalYear'));


        $prefix='';
        if ($fiscal_year!=$current_fiscal->fiscal_year){
            $prefix=Fiscalyear::where('fiscal_year',$fiscal_year)->where('org_id',auth()->user()->org_id)->first()->numeric_fiscal_year.'_';
        }
        $ledgers_data = new COALedgers();
        $entry_item_table=new Entryitem();
        $new_table=$prefix.$entry_item_table->getTable();
        $new_ledger_table=$prefix.$ledgers_data->getTable();
        $entry_item_table->setTable($new_table);
        $ledgers_data->setTable($new_ledger_table);




        $requestData['group_id'] = $request->group_id;
        $groups_data = COAgroups::find($request->group_id);
        $ledgers_ids = $this->get_ledger_ids($request->group_id,$ledgers_data);



        $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

        $ledgers=COALedgers::whereIn('id',$ledgers_ids)->get();
        $ledgers_data=[];

        foreach ($ledgers as $ledger){
        if ($request->startdate && $request->enddate) {
            $startdate = $request->startdate;
            $enddate = $request->enddate;
            $entry_items = $entry_item_table->where('ledger_id', $ledger->id)
                ->leftjoin($prefix . 'entries', $prefix . 'entryitems.entry_id', '=', $prefix . 'entries.id')->where($prefix.'entries.org_id',auth()->user()->org_id)
                ->where($prefix . 'entries.date', '>=', $startdate)->where($prefix . 'entries.date', '<=', $enddate)
                ->orderBy($prefix . 'entries.date', 'asc')
                ->orderBy($prefix . 'entries.id', 'asc')->get();


            $entry_items1 = $entry_item_table->where('ledger_id', $ledger->id)->with(['entry'])
                ->join($prefix . 'entries', function ($join) use ($startdate, $prefix) {
                    $join->on($prefix . 'entryitems.entry_id', '=', $prefix . 'entries.id')
                        ->whereDate($prefix . 'entries.date', '<', $startdate);
                })->get();
        }
        else {
            $entry_items = $entry_item_table->where('ledger_id', $ledger->id)->join($prefix . 'entries', function ($join) use ($selected_currency, $prefix) {
                $join->on($prefix . 'entryitems.entry_id', '=', $prefix . 'entries.id');
            })->orderBy($prefix . 'entries.date', 'asc')
                ->orderBy($prefix . 'entries.id', 'asc')->get();
        }
            $data['dr_amount']=$entry_items->where('dc','D')->sum('amount');
            $data['cr_amount']=$entry_items->where('dc','C')->sum('amount');
            if ($data['dr_amount']>0||$data['cr_amount']>0){
                $opening = \App\Helpers\TaskHelper::check_opening_balance1($ledger,$entry_items1);
                $data['id']=$ledger->id;
                $data['code']=$ledger->code;
                $data['name']=$ledger->name;
                $data['op_balance']=$opening['amount'];
                $data['op_dc']=$opening['dc'];
                $data['dr_amount']=$entry_items->where('dc','D')->sum('amount');
                $data['cr_amount']=$entry_items->where('dc','C')->sum('amount');
                $closing_balance= \TaskHelper::getFinalLedgerBalance($ledger->id,$opening,$startdate,$enddate);
                $data['closing_balance']=$closing_balance['amount'];
                $data['closing_dc']=$closing_balance['dc'];
                $ledgers_data[]=$data;
            }

        }

        if ($request->excel=='true'){
            return \Excel::download(new \App\Exports\BulkGroupLedgerExcelExport($ledgers_data,$groups_data,$startdate,$enddate), 'group_ledger_bulk_'.$groups_data->name.'.xls');

        }

        return view('admin.accountreport.groupledgerbulk.detailgroups', compact('fiscal_year','startdate','enddate','allFiscalYear','page_title','page_description','groups','groups_data','ledgers_data'));
    }

        public function deleteSelected(Request $request){
        \App\Models\COALedgers::destroy($request->ledger_ids);

        Flash::success('Ledgers Deleted Successfully');
        return redirect()->back();
    }

    //    public function get_ledger_ids11($id)
    //       {
    //         try{
    //           $array = array();
    //           $data = COAgroups::find($id);


    //           // dd($data->children->pluck(id));
    //           if(count($data->children) > 0) {
    //               $getData = $this->recursive($data,$array);
    //               $array = array_push($array, $getData);
    //           }else {
    //               $array = array_push($array, COALedgers::where('group_id',$id)->get());
    //           }


    //           dd($array);
    //         }catch(\Exception $e)
    //         {
    //            return $e->getMessage().$e->getLine();
    //         }
    //       }

    //       public function recursive($data1, $array1)
    //       {
    //           foreach ($data1->children as $d){
    //               if(count($d->children) > 0 ) {
    //                   $getData1 = $this->recursive($d, $array1);
    //                   $array1 = array_push($array1, $getData1);
    //               }else {
    //                   $array1 = array_push($array1,COALedgers::where('group_id',$d->id)->pluck(id));
    //               }
    //             }

    //           return $array1;

    //       }

    //  public function get_ledger_ids($id)
    // {
    //      $groups_data = COAgroups::find($id);
    //      if(count($groups_data->children) > 0)
    //      {
    //        $group_ids = COAgroups::where('parent_id',$id)->pluck(id);
    //        $ledgers_ids = COALedgers::whereIn('group_id',$group_ids)->pluck(id);
    //      }else
    //      {
    //        $ledgers_ids = COALedgers::where('group_id',$id)->pluck(id);
    //      }
    //      return $ledgers_ids;
    // }
}
