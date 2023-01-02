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

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class EntryController extends Controller
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

    
    public function entries_verfied(Request $request){
        // dd($request->all());
        $current_fiscal_year = \App\Models\Fiscalyear::where('current_year', 1)->first();
        $selected_fiscal_year = \Session::get('selected_fiscal_year') ?? $current_fiscal_year->numeric_fiscal_year;

        $prefix = '';
        $entries_table = new \App\Models\Entry();

        if ($selected_fiscal_year != $current_fiscal_year->numeric_fiscal_year) {
            $prefix = $selected_fiscal_year . '_';
            $new_entries_table = $prefix . $entries_table->getTable();
            $entries_table->setTable($new_entries_table);
        }

        $entriesid=explode(',',$request->entryIds);
        $approved_by=$request->approved_by;
        $remarks=$request->remarks;

        foreach($entriesid as $entryid){
            $entries_table->where('id',$entryid)->update(['is_approved'=>1,'approved_by'=>$approved_by,'remarks'=>$remarks]);
        }
        Flash::success($request->entryIds.' entries are verified and approved succcessfully.');
        return redirect()->back();
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

        $page_title = 'Entries';
        $page_description = 'List of all Entries';
        $entriestype = Entrytype::orderBy('id', 'asc')->where('restriction_bankcash','!=',1)->get();
        $entries = $entries_table->select($prefix.'entries.*')
            ->leftjoin($prefix.'entryitems', $prefix.'entryitems.entry_id', '=', $prefix.'entries.id')
            ->where($prefix.'entries.org_id', \Auth::user()->org_id)
            ->where(function ($query) use ($prefix) {
                if (\Request::get('start_date') != '' && \Request::get('end_date') != '') {
                    return $query->where($prefix.'entries.date', '>=', \Request::get('start_date'))->where($prefix.'entries.date', '<=', \Request::get('end_date'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('tag_id') && \Request::get('tag_id') != '') {
                    return $query->where('tag_id', \Request::get('tag_id'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('entries_type_id') && \Request::get('entries_type_id') != '') {
                    return $query->where('entrytype_id', \Request::get('entries_type_id'));
                }
            })
            ->where(function ($query) {
                if (!is_null(\Request::get('status'))) {
                    return $query->where('entries.is_approved', '=', (\Request::get('status')));
                }
            })
            ->where(function ($query) use ($prefix) {
                if (\Request::get('legder_id') && \Request::get('legder_id') != '') {
                    return $query->where($prefix.'entryitems.ledger_id', \Request::get('legder_id'));
                }
            })
            ->where(function ($query) use ($prefix) {
                if (\Request::get('user_id') && \Request::get('user_id') != '') {
                    return $query->where($prefix.'entries.user_id', \Request::get('user_id'));
                }
            })
            ->when(\Request::get('only_missing_entries'), function ($q) use ($entries_table,$prefix) {
                $q->whereHas($prefix.'entry_items', function ($q) use ($prefix) {
                    $q->selectRaw("SUM(case when ".$prefix."entryitems.dc = 'D' then amount else 0 END) as dr_amount,
                    SUM(case when ".$prefix."entryitems.dc = 'C' then amount else 0 END) as cr_amount")
                        ->havingRaw('dr_amount != cr_amount');
                });
                $q->orwhereHas($prefix.'entry_items', function ($q) use ($prefix) {
                    $q->where($prefix."entryitems.ledger_id",0);
                    $q->orWhereDoesntHave('ledgerdetail');
                    //
                });

                $q->orWhereDoesntHave($prefix.'entry_items');
            })
            ->orderBy($prefix.'entries.id', 'desc')
            ->groupBy($prefix.'entries.id')
            ->paginate(30);
        $tags = Tag::pluck('title as name', 'id')->all();
        $entries_type = Entrytype::where('org_id', \Auth::user()->org_id)->pluck('name', 'id')->all();
        $ledgers = COALedgers::where('org_id', \Auth::user()->org_id)->pluck('name', 'id')->all();
        $users = User::where('org_id', \Auth::user()->org_id)->pluck('username as name', 'id')->all();

        return view('admin.entries.index', compact('page_title', 'page_description', 'entriestype', 'entries', 'tags', 'entries_type', 'ledgers', 'users'));
    }


    public function Create($label)
    {
        $page_title = 'Entry Add';
        $page_description = 'create entry';

        $tags = Tag::orderBy('id', 'desc')->get();
        $currency = \App\Models\Country::select('*')->whereEnabled('1')->orderByDesc('id')->get();
        $current_fiscal = \App\Models\Fiscalyear::where('current_year', 1)->first();
        $fiscal_year = \Request::get('fiscal_year') ? \Request::get('fiscal_year') : $current_fiscal->fiscal_year;

        $allFiscalYear = \App\Models\Fiscalyear::latest()->pluck('fiscal_year', 'fiscal_year')->all();

        $selected_currency = 'NPR';
        return view('admin.entries.create', compact('fiscal_year', 'allFiscalYear', 'page_title', 'page_description', 'tags', 'currency', 'selected_currency'));
    }

    public function show($label, $id)
    {
        $page_title = 'Entry Show';
        $page_description = 'show entries';

        $entry_table=new Entry();
        $entry_item_table=new Entryitem();
        $prefix='';

        if (\Request::get('fiscal_year')){
            $current_fiscal_year = \App\Models\Fiscalyear::where('current_year', 1)->first();
            // $selected_fiscal_year = \Request::get('fiscal_year') ?? $current_fiscal_year->numeric_fiscal_year;
        $selected_fiscal_year = \Session::get('selected_fiscal_year') ?? $current_fiscal_year->numeric_fiscal_year;

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
        return view('admin.entries.show', compact('page_title', 'page_description', 'entries', 'entriesitem'));
    }

    public function DownloadPdf($label, $id)
    {
        $entries = Entry::where('id', $id)->first();
        $entriesitem = Entryitem::orderBy('id', 'asc')->where('entry_id', $entries->id)->get();

        $imagepath = \Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.entries.entryPDF', compact('entries', 'entriesitem', 'imagepath'));
        $file = $id . '_' . $entries->number . '.pdf';

        if (\File::exists('reports/' . $file)) {
            \File::Delete('reports/' . $file);
        }

        return $pdf->download($file);
    }

    public function PrintEntry($label, $id)
    {
        $page_title = 'Entry Show';
        $page_description = 'show entries';
        $imagepath = \Auth::user()->organization->logo;

        $entries = Entry::where('id', $id)->first();
        $entriesitem = Entryitem::orderBy('id', 'asc')->where('entry_id', $entries->id)->get();

        return view('admin.entries.print', compact('entries', 'entriesitem', 'imagepath'));
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        $this->validate($request, [
            'date' => 'required',
        ]);

        if ($request->dr_total != $request->cr_total) {
            Flash::error('Credit Debit Not Matches ');

            return redirect()->back();
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

        $fiscal_year=\App\Models\Fiscalyear::where('numeric_fiscal_year', $selected_fiscal_year)->first();
        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['user_id'] = \Auth::user()->id;
        $attributes['dr_total'] = $request->dr_total;
        $attributes['source'] = 'Manual Entry';
        $attributes['cr_total'] = $request->cr_total;
        $attributes['fiscal_year_id'] = $fiscal_year->id;
        $attributes['currency'] = $request->currency;

        $type = \App\Models\Entrytype::find($request->entrytype_id);

        $attributes['number'] = \TaskHelper::generateId($type);

        $entry = $entries_table->create($attributes);

        $dc = $request->dc;
        $ledger_id = $request->ledger_id;
        $dr_amount = $request->dr_amount;
        $cr_amount = $request->cr_amount;
        $narration = $request->narration;

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
                $detail['cheque_no'] = $request->cheque_no[$key]??'';
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

        Flash::success('Entry Created Successfully');

        DB::commit();
        return redirect('/admin/entries');
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
    public function edit($label, $id)
    {
        $page_title = 'Entry Edit';
        $page_description = 'edit entry';

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

        $tags = Tag::orderBy('id', 'desc')->get();
        $types = Entrytype::orderBy('id', 'desc')->get();

        $entries = $entries_table->orderBy('id', 'desc')->where('id', $id)->first();

        $entriesitem = $entry_items_table->orderBy('id', 'asc')->where('entry_id', $id)->get();

        $currency = \App\Models\Country::select('*')->whereEnabled('1')->orderByDesc('id')->get();
        $selected_currency = $entries->currency;

        return view('admin.entries.edit', compact('page_title', 'page_description', 'entries', 'entriesitem', 'tags', 'currency', 'selected_currency', 'types'));
    }

    public function Update(Request $request, $id)
    {
        DB::beginTransaction();
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

        $this->validate($request, [

            'date' => 'required',

        ]);

        if ($request->dr_total != $request->cr_total) {
            Flash::error('Credit Debit Not Matches ');

            return redirect()->back();
        }

        $entry = $entries_table->find($id);

        $image = $entry->image;
        if ($request->hasFile('photo')) {
            $image = $this->uplaodImage($request->photo);
        }
        $attributes = $request->all();
        $attributes['image'] = $image;

        $attributes['dr_total'] = $request->dr_total;
        $attributes['cr_total'] = $request->cr_total;
        $attributes['currency'] = $request->currency;
        $attributes['tag_id'] = $request->tag_id;
        $attributes['entrytype_id'] = $request->entrytype_id;

        $entry = $entries_table->find($id)->update($attributes);

        $entry_items_table->where('entry_id', $id)->delete();

        $dc = $request->dc;
        $ledger_id = $request->ledger_id;
        $dr_amount = $request->dr_amount;
        $cr_amount = $request->cr_amount;
        $narration = $request->narration;

        foreach ($ledger_id as $key => $value) {
            if ($value != '') {
                $detail = new \App\Models\Entryitem();
                if ($selected_fiscal_year != $current_fiscal_year->numeric_fiscal_year) {
                    $prefix = $selected_fiscal_year . '_';
                    $new_entry_items_table = $prefix . $detail->getTable();
                    $detail->setTable($new_entry_items_table);
                }
                $detail->entry_id = $id;
                $detail->dc = $request->dc[$key];
                $detail->ledger_id = $request->ledger_id[$key];
                $detail->amount = $request->dr_amount[$key];
                // $detail->cheque_no = $request->cheque_no[$key];
                $detail->narration = $request->narration[$key];
                $detail->save();
            }
        }

        Flash::success('Entry Created Successfully');

        DB::commit();
        return redirect('/admin/entries');
    }

//    public function ajaxAddEntry(Request $request)
//    {
//        $i = time() + rand(0, time()) + rand(0, time()) + rand(0, time());
//
//        $dc_option_val = $request->dc_option_val;
//        $cur_ledger_id = $request->cur_ledger_id;
//        $amount = $request->amount;
//        $narration = $request->narration;
//        $ledger_option = $request->ledger_option;
//        $dc_option = $request->dc_option;
//        $ledger_balance = $request->ledger_balance;
//        $cheque_no = $request->cheque_no;
//
//        if ($cur_ledger_id == '') {
//            $data = '<tr class="danger"><td colspan="7" style="text-align:center">Please Select a Ledger.</td></tr>';
//
//            return $data;
//        }
//        if ($amount == '') {
//            $data = '<tr class="danger"><td colspan="7" style="text-align:center">Amount is required.</td></tr>';
//
//            return $data;
//        }
//        if (!is_numeric($amount)) {
//            $data = '<tr class="danger"><td colspan="7" style="text-align:center">Invalid Amount.</td></tr>';
//
//            return $data;
//        }
//        if ($narration == '') {
//            $data = '<tr class="danger"><td colspan="7" style="text-align:center">Narration is required.</td></tr>';
//
//            return $data;
//        }
//
//        $data = '<tr class="' . $i . '">
//               <td class="' . $dc_option_val . '">' . $dc_option . '<input type="hidden" name="dc[]" value="' . $dc_option_val . '"></td>
//               <td class="' . $cur_ledger_id . '" id="cur_ledger">' . $ledger_option . '<input type="hidden" name="ledger_id[]" value="' . $cur_ledger_id . '"></td>';
//
//        if ($dc_option_val == 'D') {
//            $data .= '<td>' . $amount . '<input type="hidden" name="dr_amount[]" value="' . $amount . '" class="dr-item"></td>
//               <td><strong>-</strong></td>';
//        } else {
//            $data .= '<td><strong>-</strong></td><td>' . $amount . '<input type="hidden" name="dr_amount[]" value="' . $amount . '" class="cr-item"></td>
//               ';
//        }
//
//        $data .= '<td>' . $cheque_no . '<input type="hidden" name="cheque_no[]" value="' . $cheque_no . '"></td>
//        <td>' . $narration . '<input type="hidden" name="narration[]" value="' . $narration . '"></td>
//               <td class="ledger-balance">
//               <div>' . $ledger_balance . '</div><input type="hidden" name="ledger_balance[]" value="' . $ledger_balance . '">
//               </td>
//               <td><span class="deleterow " escape="false"><i class="fa fa-trash deletable"></i></span></td>
//              </tr>';
//
//        return $data;
//    }
//
//    public function ajaxcl(Request $request)
//    {
//        $id = $request->cur_ledger_id;
//
//        if ($id == '') {
//            return 0;
//        }
//        $ledgers = COALedgers::where('id', $id)->get();
//        if (count($ledgers) == 0) {
//            $cl = ['cl' => ['dc' => '', 'amount' => '']];
//        } else {
//            $op = COALedgers::find($id);
//
//            $op_total = 0;
//            $op_total_dc = $op->op_balance_dc;
//
//            if (empty($op->op_balance)) {
//                $op_total = 0;
//            } else {
//                $op_total = $op['op_balance'];
//            }
//
//            $dr_total = 0;
//            $cr_total = 0;
//            $dr_total_dc = 0;
//            $cr_total_dc = 0;
//
//            //Debit Amount
//            $total = Entryitem::select('amount')->where('ledger_id', $request->cur_ledger_id)->where('dc', 'D')
//                ->sum('amount');
//
//            if (empty($total)) {
//                $dr_total = 0;
//            } else {
//                $dr_total = $total;
//            }
//
//            //Credit Amount
//            $total = Entryitem::select('amount)')->where('ledger_id', $request->cur_ledger_id)->where('dc', 'C')
//                ->sum('amount');
//
//            if (empty($total)) {
//                $cr_total = 0;
//            } else {
//                $cr_total = $total;
//            }
//
//            /* Add opening balance */
//            if ($op_total_dc == 'D') {
//                $dr_total_dc = $op_total + $dr_total;
//                $cr_total_dc = $cr_total;
//            } else {
//                $dr_total_dc = $dr_total;
//                $cr_total_dc = $op_total + $cr_total;
//            }
//
//            /* $this->calculate and update closing balance */
//            $cl = 0;
//            $cl_dc = '';
//            if ($dr_total_dc > $cr_total_dc) {
//                $cl = $dr_total_dc - $cr_total_dc;
//                $cl_dc = 'D';
//            } elseif ($cr_total_dc == $dr_total_dc) {
//                $cl = 0;
//                $cl_dc = $op_total_dc;
//            } else {
//                $cl = $cr_total_dc - $dr_total_dc;
//                $cl_dc = 'C';
//            }
//
//            $cl = ['dc' => $cl_dc, 'amount' => $cl, 'dr_total' => $dr_total, 'cr_total' => $cr_total];
//
//            $status = 'ok';
//            if ($op->type == 1) {
//                if ($cl->dc == 'C') {
//                    $status = 'neg';
//                }
//            }
//
//            /* Return closing balance */
//            $cl = ['cl' => [
//                'dc' => $cl['dc'],
//                'amount' => $cl['amount'],
//                'status' => $status,
//            ]];
//        }
//
//        return json_encode($cl);
//    }


    public function ajaxAddEntry(Request $request)
    {
        $i = time() + rand(0, time()) + rand(0, time()) + rand(0, time());

        $dc_option_val = $request->dc_option_val;

        $cur_ledger_id = $request->cur_ledger_id;
        $amount = $request->amount;
        $narration = $request->narration;
        $ledger_option = $request->ledger_option;
        $dc_option = $request->dc_option;
        
        $ledger_balance = 'Dr --';
        $data = '<tr class="'.$i.'" id='.$i.'>
               <td class="'.$dc_option_val.' "><select name="dc[]" class="form-select form-select-sm  dr_cr_toggle"><option value="D">Dr</option><option value="C" '.(  $dc_option_val == 'C' ? "selected":'' ).' >Cr</option></select>'
            .'</td><td class="'.$cur_ledger_id.' ledger-td form-select" id="cur_ledger">'.$ledger_option.'<input type="hidden" name="ledger_id[]" value="'.$cur_ledger_id.'"></td>';

        if ($dc_option_val == 'D') {
            $data .= '<td class="dr_row">'.'<input type="number" name="dr_amount[]" id="dr_amount" step="any" value="'.$amount.'" class="form-control form-control-solid dr-item line-amounts input-sm"></td>
               <td class="cr_row"><strong>-</strong></td>';
        } else {
            $data .= '<td class="dr_row"><strong>-</strong></td><td class="cr_row">'.'<input type="text" id="dr_amount2" name="dr_amount[]" value="'.$amount.'" class="form-control form-control-solid cr-item line-amounts input-sm"></td>
               ';
        }

        $data .= '<td><input type="text" name="narration[]" value="'.$narration.'" class="form-control input-sm"></td>
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
        $current_fiscal_year = \App\Models\Fiscalyear::where('current_year', 1)->first();
        $selected_fiscal_year = \Session::get('selected_fiscal_year') ?? $current_fiscal_year->numeric_fiscal_year;

        $prefix = '';
        $entries_table = new \App\Models\Entry();
        if ($selected_fiscal_year != $current_fiscal_year->numeric_fiscal_year) {
            $prefix = $selected_fiscal_year . '_';
            $new_entries_table = $prefix . $entries_table->getTable();
            $entries_table->setTable($new_entries_table);
        }

        $modal_title = 'Delete Entity';

        $groups = $entries_table->find($id);

        $modal_route = route('admin.entries.delete', ['id' => $groups->id]);

        $modal_body = 'Are you sure you want to delete this Entry?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroyEntry($id)
    {
        $current_fiscal_year = \App\Models\Fiscalyear::where('current_year', 1)->first();
        $selected_fiscal_year = \Session::get('selected_fiscal_year') ?? $current_fiscal_year->numeric_fiscal_year;

        $prefix = '';
        $entries_table = new \App\Models\Entry();
        $entry_items_table = new \App\Models\Entryitem();

        if ($selected_fiscal_year != $current_fiscal_year->numeric_fiscal_year) {
            $prefix = $selected_fiscal_year . '_';
            $new_entries_table = $prefix . $entries_table->getTable();
            $new_entry_items_table = $prefix . $entry_items_table->getTable();
            $entries_table->setTable($new_entries_table);
            $entry_items_table->setTable($new_entry_items_table);
        }

        if ($id == '') {
            Flash::error('Entry Not Specified.');

            return redirect('/admin/entries');
        }

        $entries_table->find($id)->delete();
        $entry_items_table->where('entry_id', $id)->delete();

        Flash::success('Entry successfully deleted.');

        return redirect('/admin/entries');
    }
}
