<?php

namespace App\Http\Controllers;

use App\Models\COALedgers;
use App\Models\Entry;
use App\Models\Entryitem;
use App\Models\Entrytype;
use App\Models\Role as Permission;
use App\Models\Tag;
use App\Traits\ReorderTablesWithFiscalYear;
use App\User;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use DB;

class BankCashVoucherController extends Controller
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

    // index
    public function index()
    {
        $current_fiscal_year = \App\Models\Fiscalyear::where('current_year', 1)->first();
        $selected_fiscal_year = \Session::get('selected_fiscal_year') ?? $current_fiscal_year->numeric_fiscal_year;

        $prefix = '';
        $entries_table = new \App\Models\Entry();

        if ($selected_fiscal_year != $current_fiscal_year->numeric_fiscal_year) {
            $prefix = $selected_fiscal_year . '_';
            $new_entries_table = $prefix . $entries_table->getTable();
            $entries_table->setTable($new_entries_table);
        }

        $page_title = 'Bank and Cash Voucher';
        $page_description = 'List of all Bank and Cash voucher';
        $entriestype = Entrytype::orderBy('id', 'asc')->where('org_id', \Auth::user()->org_id)->get();
        $entries = $entries_table->select($prefix.'entries.*')
            ->leftjoin($prefix.'entryitems', $prefix.'entryitems.entry_id', '=', $prefix.'entries.id')
            ->where($prefix.'entries.org_id', \Auth::user()->org_id)
            ->where(function ($query) {
                    return $query->whereIn('do',['receive','pay','contra'])->orwhereIn('entrytype_id',[13,14,17]);
            })
            ->orderBy($prefix.'entries.id', 'desc')
            ->groupBy($prefix.'entries.id')
            ->paginate(30);
        $tags = Tag::pluck('title as name', 'id')->all();
        $entries_type = Entrytype::where('org_id', \Auth::user()->org_id)->pluck('name', 'id')->all();
        $ledgers = COALedgers::where('org_id', \Auth::user()->org_id)->pluck('name', 'id')->all();
        $users = User::where('org_id', \Auth::user()->org_id)->pluck('username as name', 'id')->all();

        return view('admin.bank-cash-voucher.index', compact('page_title', 'page_description', 'entriestype', 'entries', 'tags', 'entries_type', 'ledgers', 'users'));
    }


    public function create()
    {
        $page_title = 'Bank and Cash Voucher Add';
        $page_description = 'create Bank and Cash Voucher';

        $tags = Tag::orderBy('id', 'desc')->whereNotIn('title',['Payroll','Labor','Borrowing','Travel','Advance'])->get();
        $currency = \App\Models\Country::select('*')->whereEnabled('1')->orderByDesc('id')->get();
        $current_fiscal = \App\Models\Fiscalyear::where('current_year', 1)->first();
        $fiscal_year = \Request::get('fiscal_year') ? \Request::get('fiscal_year') : $current_fiscal->fiscal_year;

        $allFiscalYear = \App\Models\Fiscalyear::latest()->pluck('fiscal_year', 'fiscal_year')->all();
        $bankandcash_ledgers = \App\Models\COALedgers::whereIn('group_id',[18,19])->pluck('name','id');
        $selected_currency = 'NPR';
        return view('admin.bank-cash-voucher.create', compact('bankandcash_ledgers','fiscal_year', 'allFiscalYear', 'page_title', 'page_description', 'tags', 'currency', 'selected_currency'));
    }

    public function show($id)
    {
        $page_title = 'Bank and Cash Voucher';
        $page_description = 'show Bank and Cash voucher';

        $entry_table=new Entry();
        $entry_item_table=new Entryitem();
        $prefix='';

        if (\Request::get('fiscal_year')){
            $current_fiscal_year = \App\Models\Fiscalyear::where('current_year', 1)->first();
            $selected_fiscal_year = \Request::get('fiscal_year') ?? $current_fiscal_year->numeric_fiscal_year;
            if ($selected_fiscal_year!=$current_fiscal_year->numeric_fiscal_year){
                $prefix=$selected_fiscal_year.'_';
                $new_entry=$prefix.'entries';
                $new_entry_items=$prefix.'entryitems';
                $entry_table->setTable($new_entry);
                $entry_item_table->setTable($new_entry_items);
            }
        }


        $entries = $entry_table->where('id', $id)->first();
        $entriesitem = $entry_item_table->orderBy('id', 'asc')->where('entry_id', $entries->id)->get();

        return view('admin.bank-cash-voucher.show', compact('page_title', 'page_description', 'entries', 'entriesitem'));
    }

    public function DownloadPdf($id)
    {
        $entries = Entry::where('id', $id)->first();
        $entriesitem = Entryitem::orderBy('id', 'asc')->where('entry_id', $entries->id)->get();

        $imagepath = \Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.bank-cash-voucher.entryPDF', compact('entries', 'entriesitem', 'imagepath'));
        $file = $id . '_' . $entries->number . '.pdf';

        if (\File::exists('reports/' . $file)) {
            \File::Delete('reports/' . $file);
        }

        return $pdf->download($file);
    }

    // public function PrintEntry($label, $id)
    // {
    //     $page_title = 'Entry Show';
    //     $page_description = 'show entries';
    //     $imagepath = \Auth::user()->organization->logo;

    //     $entries = Entry::where('id', $id)->first();
    //     $entriesitem = Entryitem::orderBy('id', 'asc')->where('entry_id', $entries->id)->get();

    //     return view('admin.entries.print', compact('entries', 'entriesitem', 'imagepath'));
    // }

    public function store(Request $request)
    {

        DB::beginTransaction();

        $this->validate($request, [
            'date' => 'required',
        ]);
        if($request->ledger_id[0] && $request->ledger_id[0]==''){
            Flash::error('Entries missing');
            return redirect()->back();
        }
        //pay
        if($request->entrytype_id=="14"){
            $bankandcash_total=$request->dr_total-$request->cr_total??0;
            if($request->dr_total==0){
                Flash::error('Entries missing');
                return redirect()->back();
            }
            elseif ( $request->dr_total != ($request->cr_total+$bankandcash_total)) {
                Flash::error('Credit Debit Not Matches ');
                return redirect()->back();
            }
        }
        //contra...rec
        else{
            $bankandcash_total=$request->cr_total-$request->dr_total??0;
            if((int)$request->cr_total==0){
                Flash::error('Entries missing');
                return redirect()->back();
            }
            elseif ((int)($request->dr_total+$bankandcash_total) != (int)$request->cr_total  ) {
                Flash::error('Credit Debit Not Matches ');
                return redirect()->back();
            }

        }


        $current_fiscal_year = \App\Models\Fiscalyear::where('current_year', 1)->first();
        $selected_fiscal_year = \Session::get('selected_fiscal_year') ?? $current_fiscal_year->numeric_fiscal_year;

        $prefix = '';
        $ledgers_table = new \App\Models\COALedgers();
        $entries_table = new \App\Models\Entry();
        $entry_items_table = new \App\Models\Entryitem();

        if ($selected_fiscal_year != $current_fiscal_year->numeric_fiscal_year) {
            $prefix = $selected_fiscal_year . '_';
            $new_ledger_table = $prefix . $ledgers_table->getTable();
            $new_entries_table = $prefix . $entries_table->getTable();
            $new_entry_items_table = $prefix . $entry_items_table->getTable();
            $ledgers_table->setTable($new_ledger_table);
            $entries_table->setTable($new_entries_table);
            $entry_items_table->setTable($new_entry_items_table);
        }

        $image = null;
        if ($request->hasFile('photo')) {
            $image = $this->uplaodImage($request->photo);
        }

        $attributes = $request->all();

        $attributes['image'] = $image;
        $attributes['bill_no'] = 0;

        $fiscal_year=\App\Models\Fiscalyear::where('numeric_fiscal_year', $selected_fiscal_year)->first();
        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['user_id'] = \Auth::user()->id;
        $attributes['dr_total'] = $request->dr_total>$request->cr_total?$request->dr_total:$request->cr_total;
        $attributes['source'] = 'Manual Entry';
        $attributes['cr_total'] = $request->dr_total>$request->cr_total?$request->dr_total:$request->cr_total;
        $attributes['fiscal_year_id'] = $fiscal_year->id;
        $attributes['currency'] = $request->currency;
        // $attributes['payment_month'] = $request->payment_month;
        if($request->entrytype_id==13){
            $attributes['do'] = "receive";
        }
        elseif($request->entrytype_id==14){
            $attributes['do'] = "pay";
        }
        elseif($request->entrytype_id==17){
            $attributes['do'] = "contra";
        }

        $type = \App\Models\Entrytype::find($request->entrytype_id);

        $attributes['number'] = \TaskHelper::generateId($type);

        $entry = $entries_table->create($attributes);

        $dc = $request->dc;
        $ledger_id = $request->ledger_id;
        $dr_amount = $request->dr_amount;
        $cr_amount = $request->cr_amount;
        $narration = $request->narration;

        $coa_ledger_id=$request->bankandcash;
        $ledger=$ledgers_table->find($coa_ledger_id);
        if (!$ledger){
            $existing_ledger=\App\Models\COALedgers::find($coa_ledger_id);
            $attributes=json_decode($existing_ledger, true);
            $attributes['op_balance']=0;
            $attributes['op_balance_dc']='D';
            $attributes['id']=$existing_ledger->id;
            $ledgers_table->insert([$attributes]);
        }
        $abovedetail['entry_id'] = $entry->id;
        $abovedetail['org_id'] = \Auth::user()->org_id;
        $abovedetail['user_id'] = \Auth::user()->id;

        if($request->entrytype_id=="14")
        $abovedetail['dc'] = 'C';
        else
        $abovedetail['dc'] = 'D';

        $abovedetail['ledger_id'] = $coa_ledger_id;
        $abovedetail['amount'] = $bankandcash_total;
        $abovedetail['cheque_no'] = $request->cheque_no;
        $abovedetail['narration'] = "Bank and Cash Voucher being ".$request->do;
        $entry_item=$entry_items_table->create($abovedetail);
        if ($selected_fiscal_year != $current_fiscal_year->numeric_fiscal_year) {
            $old_ledger=$this->collectLedgerClosingStock($coa_ledger_id,$entry_item);
            \App\Models\COALedgers::where('id',$coa_ledger_id)->update([
                'op_balance'=>$old_ledger['amount'],
                'op_balance_dc'=>$old_ledger['dc']
            ]);
        }

        foreach ($ledger_id as $key => $value) {
            if ($value != '') {
                $coa_ledger_id=$request->ledger_id[$key];
                $ledger=$ledgers_table->find($coa_ledger_id);
                if (!$ledger){
                    $existing_ledger=\App\Models\COALedgers::find($coa_ledger_id);
                    $attributes=json_decode($existing_ledger, true);
                    $attributes['op_balance']=0;
                    $attributes['op_balance_dc']='D';
                    $attributes['id']=$existing_ledger->id;
                    $ledgers_table->insert([$attributes]);
                }
                $detail['entry_id'] = $entry->id;
                $detail['org_id'] = \Auth::user()->org_id;
                $detail['user_id'] = \Auth::user()->id;
                $detail['dc'] = $request->dc[$key];
                $detail['ledger_id'] = $coa_ledger_id;
                $detail['amount'] = $request->dr_amount[$key];
                // $detail['cheque_no'] = $request->cheque_no[$key];
                $detail['employee_id'] = $request->employee_id[$key];
                // $detail['assign_to'] = $request->assign_to[$key];
                $detail['narration'] = $request->narration[$key];
                $entry_item=$entry_items_table->create($detail);
                if ($selected_fiscal_year != $current_fiscal_year->numeric_fiscal_year) {
                    $old_ledger=$this->collectLedgerClosingStock($coa_ledger_id,$entry_item);
                    \App\Models\COALedgers::where('id',$coa_ledger_id)->update([
                        'op_balance'=>$old_ledger['amount'],
                        'op_balance_dc'=>$old_ledger['dc']
                    ]);

                }
                }
        }

        Flash::success('Bank and Cash voucher Created Successfully');

        DB::commit();
        return redirect('/admin/bank-cash-voucher');
    }

    public function uplaodImage($photo){
        $extension = $photo->getClientOriginalExtension();
        $filename = date('ymdhis').''.rand(888, 8888) . '.' . $extension;
        $photo->move('images/voucher/', $filename);
        return 'images/voucher/'. $filename;
    }
    public function collectLedgerClosingStock($ledger_id,$entry)
    {
        $ledger=\App\Models\COALedgers::where('id',$ledger_id)
            ->first();
        $ledger_list=\TaskHelper::calculate_withdc($ledger['op_balance'], $ledger['op_balance_dc'],
            $entry['amount'], $entry['dc']);
        return $ledger_list;
    }
    public function edit($id)
    {
        $page_title = 'Bank and Cash Voucher Edit';
        $page_description = 'edit Bank and Cash voucher';

        $tags = Tag::orderBy('id', 'desc')->get();
        $types = Entrytype::orderBy('id', 'desc')->get();

        $entries = Entry::orderBy('id', 'desc')->where('id', $id)->first();

        $entriesitem = Entryitem::orderBy('id', 'asc')->where('entry_id', $id)->get();

        $currency = \App\Models\Country::select('*')->whereEnabled('1')->orderByDesc('id')->get();
        $selected_currency = $entries->currency;

        $users = \App\User::where('enabled', 1)->get();

        return view('admin.bank-cash-voucher.edit', compact('page_title', 'page_description', 'entries', 'entriesitem', 'tags', 'currency', 'selected_currency', 'types', 'users'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        DB::beginTransaction();
        $this->validate($request, [

            'date' => 'required',

        ]);

        if ($request->dr_total != $request->cr_total) {
            Flash::error('Credit Debit Not Matches ');

            return redirect()->back();
        }

        $entry = Entry::find($id);

        $image = $entry->image;
        if ($request->hasFile('photo')) {
            $image = $this->uplaodImage($request->photo);
        }
        $attributes = $request->all();
        $attributes['image'] = $image;

        $attributes['dr_total'] = $request->dr_total;
        $attributes['cr_total'] = $request->cr_total;
        $attributes['currency'] = $request->currency;
        $attributes['payment_month'] = $request->payment_month;
        $attributes['tag_id'] = $request->tag_id;
        $attributes['entrytype_id'] = $request->entrytype_id;

        $entry = Entry::find($id)->update($attributes);

        Entryitem::where('entry_id', $id)->delete();

        $dc = $request->dc;
        $ledger_id = $request->ledger_id;
        $dr_amount = $request->dr_amount;
        $cr_amount = $request->cr_amount;
        $narration = $request->narration;

        foreach ($ledger_id as $key => $value) {
            if ($value != '') {
                $detail = new Entryitem();
                $detail->entry_id = $id;
                $detail->dc = $request->dc[$key];
                $detail->ledger_id = $request->ledger_id[$key];
                $detail->amount = $request->dr_amount[$key];
                $detail->cheque_no = $request->cheque_no[$key];
                $detail->employee_id = $request->employee_id[$key];
                $detail->assign_to = $request->assign_to[$key];
                $detail->narration = $request->narration[$key];
                $detail->save();
            }
        }

        Flash::success('Bank and Cash Voucher updated Successfully');

        DB::commit();
        return redirect('/admin/bank-cash-voucher');
    }



public function get_coa_ledger_list(Request $request){
    if($request->do=="receive"){
        if($request->op=="D"){
            $groups=[18,19];
            $ledgers = \App\Models\COALedgers::whereIn('group_id',$groups)->select('name','id')->get()->toarray();
        }else{
            $ledgers = \App\Models\COALedgers::select('name','id')->get()->toarray();
        }
    }elseif($request->do=="pay"){
        if($request->op=="D"){
            $ledgers = \App\Models\COALedgers::select('name','id')->get()->toarray();
        }else{
            $groups=[18,19];
            $ledgers = \App\Models\COALedgers::whereIn('group_id',$groups)->select('name','id')->get()->toarray();
        }
    }elseif($request->do=="contra"){
            $groups=[18,19];
            $ledgers = \App\Models\COALedgers::whereIn('group_id',$groups)->select('name','id')->get()->toarray();

    }else{
        $ledgers = \App\Models\COALedgers::select('name','id')->get()->toarray();
    }

      return $ledgers;
}

    public function ajaxAddBankCashVoucher(Request $request)
    {
        $i = time() + rand(0, time()) + rand(0, time()) + rand(0, time());

        $dc_option_val = $request->dc_option_val;

        $cur_ledger_id = $request->cur_ledger_id;
        $amount = $request->amount;
        $narration = $request->narration;
        $ledger_option = $request->ledger_option;
        $dc_option = $request->dc_option;

        $users = '';
        $us = \App\User::where('enabled', 1)->get();
        foreach ($us as $user){
            // $users[$user->id] = $user->first_name.' '.$user->last_name;
            $users = $users .'<option value="'. $user->id .'">'. $user->first_name.' '. $user->last_name .'</option>';
        }

        $ledger_balance = 'Dr --';
        $data = '<tr class="'.$i.'" id='.$i.'>
               <td class="'.$dc_option_val.'"><select name="dc[]" class="form-control dr_cr_toggle"><option value="D">Dr</option><option value="C" '.(  $dc_option_val == 'C' ? "selected":'' ).' >Cr</option></select>'
            .'</td><td class="'.$cur_ledger_id.' ledger-td" id="cur_ledger">'.$ledger_option.'<input type="hidden" name="ledger_id[]" value="'.$cur_ledger_id.'"></td>';

        if ($dc_option_val == 'D') {
            $data .= '<td class="dr_row">'.'<input type="number" name="dr_amount[]"  step="any" value="'.$amount.'" class="form-control dr-item line-amounts input-sm"></td>
               <td class="cr_row"><strong>-</strong></td>';
        } else {
            $data .= '<td class="dr_row"><strong>-</strong></td><td class="cr_row">'.'<input type="text" name="dr_amount[]" value="'.$amount.'" class="form-control cr-item line-amounts input-sm"></td>
               ';
        }

        $data .= '<td><select class="form-control" name="employee_id[]"><option value="">Select Partner</option>'. $users .'</select></td>
        <td><input type="text" name="narration[]" value="'.$narration.'" class="form-control input-sm"></td>
               <td class="ledger-balance">
               <div>'.$ledger_balance.'</div><input type="hidden" name="ledger_balance[]" value="'.$ledger_balance.'">
               </td>
               <td><span class="deleterow " escape="false"><i class="fa fa-trash deletable"></i></span></td>
              </tr>';
        return $data;
    }

    public function ajaxcl(Request $request)
    {
        $id = $request->cur_ledger_id;

        if ($id == '') {
            return 0;
        }
        $ledgers = COALedgers::where('id', $id)->where('org_id', \Auth::user()->org_id)->get();
        if (count($ledgers) == 0) {
            $cl = ['cl' => ['dc' => '', 'amount' => '']];
        } else {
            $op = COALedgers::find($id);

            $op_total = 0;
            $op_total_dc = $op->op_balance_dc;

            if (empty($op->op_balance)) {
                $op_total = 0;
            } else {
                $op_total = $op['op_balance'];
            }

            $dr_total = 0;
            $cr_total = 0;
            $dr_total_dc = 0;
            $cr_total_dc = 0;

            //Debit Amount
            $total = Entryitem::select('amount')->where('ledger_id', $request->cur_ledger_id)->where('dc', 'D')
                ->sum('amount');

            if (empty($total)) {
                $dr_total = 0;
            } else {
                $dr_total = $total;
            }

            //Credit Amount
            $total = Entryitem::select('amount)')->where('ledger_id', $request->cur_ledger_id)->where('dc', 'C')
                ->sum('amount');

            if (empty($total)) {
                $cr_total = 0;
            } else {
                $cr_total = $total;
            }

            /* Add opening balance */
            if ($op_total_dc == 'D') {
                $dr_total_dc = $op_total + $dr_total;
                $cr_total_dc = $cr_total;
            } else {
                $dr_total_dc = $dr_total;
                $cr_total_dc = $op_total + $cr_total;
            }

            /* $this->calculate and update closing balance */
            $cl = 0;
            $cl_dc = '';
            if ($dr_total_dc > $cr_total_dc) {
                $cl = $dr_total_dc - $cr_total_dc;
                $cl_dc = 'D';
            } elseif ($cr_total_dc == $dr_total_dc) {
                $cl = 0;
                $cl_dc = $op_total_dc;
            } else {
                $cl = $cr_total_dc - $dr_total_dc;
                $cl_dc = 'C';
            }

            $cl = ['dc' => $cl_dc, 'amount' => $cl, 'dr_total' => $dr_total, 'cr_total' => $cr_total];

            $status = 'ok';
            if ($op->type == 1) {
                if ($cl->dc == 'C') {
                    $status = 'neg';
                }
            }

            /* Return closing balance */
            $cl = [
                'cl' => [
                    'dc' => $cl['dc'],
                    'amount' => $cl['amount'],
                    'status' => $status,
                ]];
        }

        return json_encode($cl);
    }

    public function getModalEntry($id)
    {
        $groups = Entry::find($id);

        $modal_title = 'Delete Entity';

        $groups = Entry::find($id);

        $modal_route = route('admin.entries.delete', ['id' => $groups->id]);

        $modal_body = 'Are you sure you want to delete this Entry?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroyEntry($id)
    {
        $groups = Entry::find($id);

        if ($id == '') {
            Flash::error('Entry Not Specified.');

            return redirect('/admin/entries');
        }

        Entry::find($id)->delete();
        Entryitem::where('entry_id', $id)->delete();

        Flash::success('Entry successfully deleted.');

        return redirect('/admin/entries');
    }

    public function PrintEntry($id)
    {
        $page_title = 'Bank and Cash Voucher Show';
        $page_description = 'show Bank and Cash voucher';
        $imagepath = \Auth::user()->organization->logo;

        $entries = Entry::where('id', $id)->first();
        $entriesitem = Entryitem::orderBy('id', 'asc')->where('entry_id', $entries->id)->get();

        return view('admin.bank-cash-voucher.print', compact('entries', 'entriesitem', 'imagepath'));
    }
}
