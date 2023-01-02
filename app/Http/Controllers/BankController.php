<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\BankAccount;
use Flash;
use Illuminate\Http\Request;
use DB;
class BankController extends Controller
{
    public function __construct(BankAccount $account)
    {
        $this->account = $account;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $filterdate = function ($query)  {
            
            $start_date = \Request::get('start_date'); 
            
            $end_date = \Request::get('end_date');

            if($start_date && $end_date){

                return $query->where('date_received','>=',$start_date)
                        ->where('date_received','<=',$end_date);
            }

        };
        $filterTags = function($query){

            $tags = \Request::get('tags');
            if($tags){

                return $query->where('tag_id',$tags);
            }

        };
        $filterIncomeType = function($query){

            $income_type = \Request::get('income_type');
            if($income_type){

                return $query->where('income_type',$income_type);
            }

        };
        // return view('emails.income-receipt');
        $page_title = 'Admin | Bank | Index';
        $page_descriptions = 'Bank Account List';
        $account = $this->account->where('org_id', \Auth::user()->org_id)->orderBy('id', 'desc')->paginate(30);
        $income = \App\Models\BankIncome::orderBy('id', 'desc')
                ->where(function($query) use ($filterdate){

                    return $filterdate($query);

                })
                ->where(function($query) use ($filterTags){

                    return $filterTags($query);

                })
                 ->where(function($query) use ($filterIncomeType){

                    return $filterIncomeType($query);

                })

                ->take(21)->get();
         $types = ['customer_payment'=>'Customer Payment', 'customer_advance'=>'Customer Advance', 'sales_without_invoice'=>'Sales Without Invoice', 'other_income'=>'Other Income', 'interest_income'=>'Interest Income'];
        $tag = \App\Models\IncomeExpenseCategory::pluck('name','id');
        return view('admin.bank.index', compact('account', 'page_descriptions', 'page_title','income','types','tag'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin | Bank | Create';
        $page_descriptions = 'Create New Account';

        return view('admin.bank.create', compact('page_title', 'page_descriptions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        $account = $request->all();
        $account['created_by'] = \Auth::user()->id;
        $account['org_id'] = \Auth::user()->org_id;
        $account = $this->account->create($account);
        //now create a bank ledger
        $group_id = \FinanceHelper::get_ledger_id('CASH_EQUIVALENTS'); //cash and cash equivalents
        $_ledgers = \TaskHelper::PostLedgers($request->account_name, $group_id);
        $attributes['ledger_id'] = $_ledgers;
        //dd($attributes);
        $account->update($attributes);

        Flash::success('Account Successfully Created');

        DB::commit();
        return redirect()->route('admin.bank.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $filterdate = function ($query)  {
            
            $start_date = \Request::get('start_date'); 
            
            $end_date = \Request::get('end_date');

            if($start_date && $end_date){

                return $query->where('date_received','>=',$start_date)
                        ->where('date_received','<=',$end_date);
            }

        };
        $filterTags = function($query){

            $tags = \Request::get('tags');
            if($tags){

                return $query->where('tag_id',$tags);
            }

        };
        $filterIncomeType = function($query){

            $income_type = \Request::get('income_type');
            if($income_type){

                return $query->where('income_type',$income_type);
            }

        };


        $page_title = 'Admin | Bank | Show';
        $page_descriptions = 'Bank Account #'.$id;
        $account = $this->account->find($id);
        \TaskHelper::authorizeOrg($account);
        $types = ['customer_payment'=>'Customer Payment', 'customer_advance'=>'Customer Advance', 'sales_without_invoice'=>'Sales Without Invoice', 'other_income'=>'Other Income', 'interest_income'=>'Interest Income'];
        $income = \App\Models\BankIncome::where('income_account', $id)->orderBy('id', 'desc')
                 ->where(function($query) use ($filterdate){

                    return $filterdate($query);

                })
                ->where(function($query) use ($filterTags){

                    return $filterTags($query);

                })
                 ->where(function($query) use ($filterIncomeType){

                    return $filterIncomeType($query);

                })
                ->paginate(20);
        $tag = \App\Models\IncomeExpenseCategory::pluck('name','id');

        return view('admin.bank.show', compact('account', 'page_descriptions', 'page_title', 'income', 'types', 'tag'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = 'Admin | Bank | Edit';
        $page_descriptions = 'Edit Account #'.$id;
        $account = $this->account->find($id);
        \TaskHelper::authorizeOrg($account);
        return view('admin.bank.edit', compact('account', 'page_title', 'page_descriptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $account = $this->account->find($id);
        \TaskHelper::authorizeOrg($account);
        if (! $account->isEditable()) {
            abort(403);
        }
        $update = $request->all();
        $account->update($update);
        Flash::success('Account Successfully Updated');

        DB::commit();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $account = $this->account->find($id);
        \TaskHelper::authorizeOrg($account);
        if (! $account->isDeletable()) {
            abort(403);
        }
        $account->delete();
        Flash::success('Account Successfully Deleted');

        return redirect()->back();
    }

    public function getModalDelete($id)
    {
        $error = null;
        $account = $this->account->find($id);
        \TaskHelper::authorizeOrg($account);
        $modal_title = 'Delete Account';
        $modal_body = 'Are you sure that you want to delete Account ID '.$account->id.' with the name '.$account->account_name.'? This operation is irreversible';

        $modal_route = route('admin.bank.delete', $account->id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function createIncome($acc_id)
    {
        DB::beginTransaction();
        $clients = \App\Models\Client::pluck('name', 'id')->all();
        $sales_invoice = \App\Models\COALedgers::where('group_id',
          \FinanceHelper::get_ledger_id('CUSTOMER_LEDGER_GROUP'))->get();
        $other_income_groups = \App\Models\COAgroups::where('parent_id', \FinanceHelper::get_ledger_id('INCOME_LEDGER_GROUP'))->pluck('name', 'id')->all();

        foreach ($other_income_groups as $key => $value) {
            $other_income[$value] = \App\Models\COALedgers::where('group_id', $key)->get();
        }
        $types = [''=>'Select Types', 'customer_payment'=>'Customer Payment', 'customer_advance'=>'Customer Advance', 'sales_without_invoice'=>'Sales Without Invoice', 'other_income'=>'Other Income', 'interest_income'=>'Interest Income'];
        $received_via = ['cash'=>'Cash', 'check' =>'Check', 'cc' =>'CC', 'transfer'=>'Transfer', 'remittance'=>'Remittance'];

        $tags = \App\Models\IncomeExpenseCategory::orderBy('name', 'ASC')->whereType('income')->pluck('name', 'id')->all();

        DB::commit();
        return  view('admin.bank.income.create', compact('clients', 'types', 'received_via', 'acc_id', 'sales_invoice', 'other_income','tags'));
    }
    

    private function convertdate($date)
    {
        $cal = new \App\Helpers\NepaliCalendar();
        $converted = explode('-', $date);
        $converted = $cal->nep_to_eng($converted[0], $converted[1], $converted[2]);
        $converted_date = $converted['year'].'-'.$converted['month'].'-'.$converted['date'];

        return $converted_date;
    }

    public function saveIncome(Request $request, $acc_id)
    {
        $this->validate($request, [
        'income_type'      => 'required',
        'amount'     => 'required',
        ]);
        DB::beginTransaction();
        $attributes = $request->all();

        if ($request->datetype == 'nep') {
            $attributes['date_received'] = $this->convertdate($attributes['date_received']);
        }

        $attributes['customer_id'] = 0;
        if ($attributes['income_type'] == 'customer_advance') {
            $attributes['income_type'] = 'customer_advance';
            $income_ledger = \FinanceHelper::get_ledger_id('CUSTOMER_ADVANCE_LEDGER');
        } elseif ($attributes['income_type'] == 'other_income') { //other income is general income ledger
            $attributes['income_type'] = 'other_income';
            $income_ledger = $attributes['other_ledgers_id'];
            $attributes['ledgers_id'] = $income_ledger;
        } elseif ($attributes['income_type'] == 'interest_income') {
            $attributes['income_type'] = 'interest_income';
            $income_ledger = \FinanceHelper::get_ledger_id('INTEREST_INCOME_LEDGER');
        } elseif ($attributes['income_type'] == 'sales_without_invoice') {
            $attributes['income_type'] = 'sales_without_invoice';
            $income_ledger = $attributes['ledgers_id'];
            $attributes['customer_id'] = \App\Models\Client::where('ledger_id', $income_ledger)->first()->id;
        } elseif ($attributes['income_type'] == 'customer_payment') {
            $attributes['income_type'] = 'customer_payment';
            $income_ledger = $attributes['ledgers_id'];
            $attributes['customer_id'] = \App\Models\Client::where('ledger_id', $income_ledger)->first()->id ?? null;
        }

        if ($attributes['customer_id'] === null) {
            Flash::warning('No Valid Customer Selected');

            return redirect()->back();
        }

        //$customer_ledger = \App\Models\Client::find($attributes['customer_id']);
        $current_selected_bank = \Request::segment(3);
        $bank_ledger = \App\Models\BankAccount::where('id', $current_selected_bank)->first()->ledger_id;

        $attributes['user_id'] = \Auth::User()->id;
        $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
        $income = \App\Models\BankIncome::create($attributes);

        //update entries
        $attributes['entrytype_id'] = '1'; //Receipt
        $attributes['tag_id'] = $request->tag_id;; //Revenue
        $attributes['user_id'] = \Auth::user()->id;
        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['number'] =\FinanceHelper::get_last_entry_number($attributes['entrytype_id']);
        $attributes['date'] = $attributes['date_received'];
        $attributes['dr_total'] = $attributes['amount'];
        $attributes['cr_total'] = $attributes['amount'];
        $attributes['source'] = 'AUTO_INCOME';
        $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
        $entry = \App\Models\Entry::create($attributes);

        // Creddited to Customer or Interest or eq ledger
        $sub_amount = new \App\Models\Entryitem();
        $sub_amount->entry_id = $entry->id;
        $sub_amount->user_id = \Auth::user()->id;
        $sub_amount->org_id = \Auth::user()->org_id;
        $sub_amount->dc = 'C';
        $sub_amount->ledger_id = $income_ledger; // customer ledger if selected or ledgers from .env
        $sub_amount->amount = $attributes['amount'];
        $sub_amount->narration = 'Amount received'; //$request->user_id
        //dd($sub_amount);
        $sub_amount->save();

        // Debitte to Bank or cash account that we are already in

        $cash_amount = new \App\Models\Entryitem();
        $cash_amount->entry_id = $entry->id;
        $cash_amount->user_id = \Auth::user()->id;
        $cash_amount->org_id = \Auth::user()->org_id;
        $cash_amount->dc = 'D';
        $cash_amount->ledger_id = $bank_ledger; //bank ledger
        // dd($cash_amount);
        $cash_amount->amount = $attributes['amount'];
        $cash_amount->narration = 'Amount Deposited';
        $cash_amount->save();

        //now update entry_id in income row
        $income->update(['entry_id'=>$entry->id]);

        Flash::success('Bank Income Added');
        DB::commit();
        return redirect('/admin/bank/'.$acc_id.'/show');
    }
    // direct add income
    public function addIncome()
    {
        $clients = \App\Models\Client::pluck('name', 'id')->all();
        $sales_invoice = \App\Models\COALedgers::where('group_id',
          \FinanceHelper::get_ledger_id('CUSTOMER_LEDGER_GROUP'))->get();
    
        $other_income_groups = \App\Models\COAgroups::where('parent_id', \FinanceHelper::get_ledger_id('INCOME_LEDGER_GROUP'))->pluck('name', 'id')->all();

        foreach ($other_income_groups as $key => $value) {
            $other_income[$value] = \App\Models\COALedgers::where('group_id', $key)->get();
        }
        $types = [''=>'Select Types', 'customer_payment'=>'Customer Payment', 'customer_advance'=>'Customer Advance', 'sales_without_invoice'=>'Sales Without Invoice', 'other_income'=>'Other Income', 'interest_income'=>'Interest Income'];
        $received_via = ['cash'=>'Cash', 'check' =>'Check', 'cc' =>'CC', 'transfer'=>'Transfer', 'remittance'=>'Remittance'];

        $tags = \App\Models\IncomeExpenseCategory::orderBy('name', 'ASC')->whereType('income')->pluck('name', 'id')->all();
         $account = $this->account->where('org_id', \Auth::user()->org_id)->pluck('account_name','id');

        return  view('admin.bank.income.add', compact('clients', 'types', 'received_via', 'sales_invoice', 'other_income','tags','account'));
    }
    public function storeIncome(Request $request)
    {
         $this->validate($request, [
        'income_type'      => 'required',
        'amount'     => 'required',
        ]);
        DB::beginTransaction();
        $attributes = $request->all();

        if ($request->datetype == 'nep') {
            $attributes['date_received'] = $this->convertdate($attributes['date_received']);
        }

        $attributes['customer_id'] = 0;
        if ($attributes['income_type'] == 'customer_advance') {
            $attributes['income_type'] = 'customer_advance';
            $income_ledger = \FinanceHelper::get_ledger_id('CUSTOMER_ADVANCE_LEDGER');
        } elseif ($attributes['income_type'] == 'other_income') { //other income is general income ledger
            $attributes['income_type'] = 'other_income';
            $income_ledger = $attributes['other_ledgers_id'];
            $attributes['ledgers_id'] = $income_ledger;
        } elseif ($attributes['income_type'] == 'interest_income') {
            $attributes['income_type'] = 'interest_income';
            $income_ledger = \FinanceHelper::get_ledger_id('INTEREST_INCOME_LEDGER');
        } elseif ($attributes['income_type'] == 'sales_without_invoice') {
            $attributes['income_type'] = 'sales_without_invoice';
            $income_ledger = $attributes['ledgers_id'];
            $attributes['customer_id'] = \App\Models\Client::where('ledger_id', $income_ledger)->first()->id;
        } elseif ($attributes['income_type'] == 'customer_payment') {
            $attributes['income_type'] = 'customer_payment';
            $income_ledger = $attributes['ledgers_id'];
            $attributes['customer_id'] = \App\Models\Client::where('ledger_id', $income_ledger)->first()->id ?? null;
        }

        if ($attributes['customer_id'] === null) {
            Flash::warning('No Valid Customer Selected');

            return redirect()->back();
        }

        // $customer_ledger = \App\Models\Client::find($attributes['customer_id']);
        $bank_ledger = $this->account->find($attributes['income_account'])->ledger_id;
        // $bank_ledger = \App\Models\BankAccount::where('id', $current_selected_bank)->first()->ledger_id;

        $attributes['user_id'] = \Auth::User()->id;
        $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
        $income = \App\Models\BankIncome::create($attributes);

        //update entries
        $attributes['entrytype_id'] = '1'; //Receipt
        $attributes['tag_id'] = $request->tag_id;; //Revenue
        $attributes['user_id'] = \Auth::user()->id;
        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['number'] =\FinanceHelper::get_last_entry_number($attributes['entrytype_id']);
        $attributes['date'] = $attributes['date_received'];
        $attributes['dr_total'] = $attributes['amount'];
        $attributes['cr_total'] = $attributes['amount'];
        $attributes['source'] = 'AUTO_INCOME';
        $attributes['fiscal_year_id'] = \FinanceHelper::cur_fisc_yr()->id;
        $entry = \App\Models\Entry::create($attributes);

        // Creddited to Customer or Interest or eq ledger
        $sub_amount = new \App\Models\Entryitem();
        $sub_amount->entry_id = $entry->id;
        $sub_amount->user_id = \Auth::user()->id;
        $sub_amount->org_id = \Auth::user()->org_id;
        $sub_amount->dc = 'C';
        $sub_amount->ledger_id = $income_ledger; // customer ledger if selected or ledgers from .env
        $sub_amount->amount = $attributes['amount'];
        $sub_amount->narration = 'Amount received'; //$request->user_id
        //dd($sub_amount);
        $sub_amount->save();

        // Debitte to Bank or cash account that we are already in

        $cash_amount = new \App\Models\Entryitem();
        $cash_amount->entry_id = $entry->id;
        $cash_amount->user_id = \Auth::user()->id;
        $cash_amount->org_id = \Auth::user()->org_id;
        $cash_amount->dc = 'D';
        $cash_amount->ledger_id = $bank_ledger; //bank ledger
        // dd($cash_amount);
        $cash_amount->amount = $attributes['amount'];
        $cash_amount->narration = 'Amount Deposited';
        $cash_amount->save();

        //now update entry_id in income row
        $income->update(['entry_id'=>$entry->id]);

        Flash::success('Bank Income Added');
        DB::commit();
        return redirect('/admin/bank/');
    }

    public function editIncome($id)
    {

        $income = \App\Models\BankIncome::find($id);

        \TaskHelper::authorizeOrg($income->banckAcc);
        $income_legder = null;
        if ($income->income_type == 'sales_without_invoice' || $income->income_type == 'customer_payment') {
            $income_legder = \App\Models\Entryitem::where('entry_id', $income->entry_id)->where('dc', 'C')->first();
        } elseif ($income->income_type == 'other_income') {
            $income_legder = \App\Models\Entryitem::where('entry_id', $income->entry_id)->where('dc', 'C')->first();
        }

        $sales_invoice = \App\Models\COALedgers::where('group_id',
          \FinanceHelper::get_ledger_id('CUSTOMER_LEDGER_GROUP'))->get();

        $other_income_groups = \App\Models\COAgroups::where('parent_id',
          \FinanceHelper::get_ledger_id('INCOME_LEDGER_GROUP'))->pluck('name', 'id')->all();
        $other_income = [];
        foreach ($other_income_groups as $key => $value) {
            $other_income[$value] = \App\Models\COALedgers::where('group_id', $key)->get();
        }

        $clients = \App\Models\Client::pluck('name', 'id')->all();

        $types = [''=>'Select Types', 'customer_payment'=>'Customer Payment', 'customer_advance'=>'Customer Advance', 'sales_without_invoice'=>'Sales Without Invoice', 'other_income'=>'Other Income', 'interest_income'=>'Interest Income'];

        $received_via = ['cash'=>'Cash', 'check' =>'Check', 'cc' =>'CC', 'transfer'=>'Transfer', 'remittance'=>'Remittance'];

        $history = \App\Models\IncomeEditHistory::where('income_id', $id)->get();
        $tags = \App\Models\IncomeExpenseCategory::orderBy('name', 'ASC')->whereType('income')->pluck('name', 'id')->all();

        return  view('admin.bank.income.edit', compact('clients', 'types', 'received_via', 'income', 'history', 'sales_invoice','income_legder' ,'other_income','tags'));
    }

    public function updateIncome(Request $request, $id)
    {
        DB::beginTransaction();
        $this->validate($request, [
        'income_type'      => 'required',
        'amount'     => 'required',
        ]);
        $attributes = $request->all();

        if ($request->datetype == 'nep') {
            $attributes['date_received'] = $this->convertdate($attributes['date_received']);
        }

        if ($attributes['income_type'] == 'customer_advance') {
            $attributes['income_type'] = 'customer_advance';
            $income_ledger = \FinanceHelper::get_ledger_id('CUSTOMER_ADVANCE_LEDGER');
        } elseif ($attributes['income_type'] == 'other_income') { //other income is general income ledger
            $attributes['income_type'] = 'other_income';
            $income_ledger = $attributes['other_ledgers_id'];
            $attributes['ledgers_id'] = $income_ledger;
        } elseif ($attributes['income_type'] == 'interest_income') {
            $attributes['income_type'] = 'interest_income';
            $income_ledger = \FinanceHelper::get_ledger_id('INTEREST_INCOME_LEDGER');
        } elseif ($attributes['income_type'] == 'sales_without_invoice') {
            $attributes['income_type'] = 'sales_without_invoice';
            $income_ledger = $attributes['ledgers_id'];
            $attributes['customer_id'] = \App\Models\Client::where('ledger_id', $income_ledger)->first()->id;
        } elseif ($attributes['income_type'] == 'customer_payment') {
            $attributes['income_type'] = 'customer_payment';
            $income_ledger = $attributes['ledgers_id'];
            $attributes['customer_id'] = \App\Models\Client::where('ledger_id', $income_ledger)->first()->id;
        }

        //$customer_ledger = \App\Models\Client::find($attributes['customer_id']);
        // dd($customer_ledger);

        $income = \App\Models\BankIncome::find($id);
        \TaskHelper::authorizeOrg($income->banckAcc);
        $bank_ledger = \App\Models\BankAccount::where('id', $income->income_account)->first()->ledger_id;
        $from_amount = $income->amount;
        $to_amount = $attributes['amount'];

        if ($income->isEditable()) {
            

            $attributes['entrytype_id'] = '1'; //Receipt
            $attributes['tag_id'] = $request->tag_id; //revenue
            $attributes['user_id'] = \Auth::user()->id;
            $attributes['org_id'] = \Auth::user()->org_id;
            $attributes['number'] =\FinanceHelper::get_last_entry_number($attributes['entrytype_id']);
            $attributes['date'] = $attributes['date_received'];
            $attributes['dr_total'] = $attributes['amount'];
            $attributes['cr_total'] = $attributes['amount'];
            $attributes['source'] = 'AUTO_INCOME';

            $entry = \App\Models\Entry::find($income->entry_id);

            if($entry){
                $entry->update($attributes);

            }else{
                $entry = \App\Models\Entry::create($attributes);
                $attributes['entry_id'] = $entry->id;
            }
        
            $income->update($attributes);
            //income account
            // dd($entry);
            $sub_amount = \App\Models\Entryitem::where('entry_id', $income->entry_id)->where('dc', 'C')->first() ?? 
                        new \App\Models\Entryitem();
            $sub_amount->entry_id = $entry->id;
            $sub_amount->user_id = \Auth::user()->id;
            $sub_amount->org_id = \Auth::user()->org_id;
            $sub_amount->dc = 'C';
            $sub_amount->ledger_id = $income_ledger; //
            $sub_amount->amount = $attributes['amount'];
            $sub_amount->narration = 'Income being made'; //$request->user_id
            $sub_amount->update();

            // Paid through account
            $cash_amount = \App\Models\Entryitem::where('entry_id', $income->entry_id)->where('dc', 'D')->first() ??
                        new \App\Models\Entryitem();
            $cash_amount->entry_id = $entry->id;
            $cash_amount->user_id = \Auth::user()->id;
            $cash_amount->org_id = \Auth::user()->org_id;
            $cash_amount->dc = 'D';
            $cash_amount->ledger_id = $bank_ledger; //select checking account from dropdown
            $cash_amount->amount = $attributes['amount'];
            $cash_amount->narration = 'Income being made';
            $cash_amount->update();
        }
        $history = [
            'income_id'=>$income->id,
            'from_amount'=>  $from_amount,
            'to_amount'=>$to_amount,
            'user_id'=>\Auth::User()->id,
        ];
        \App\Models\IncomeEditHistory::create($history);
        Flash::success('Income Successfully Updated');
        DB::commit();
        return redirect('/admin/bank/'.$income->income_account.'/show');
    }

    public function printIncome($id)
    {
        $income = \App\Models\BankIncome::find($id);

        \TaskHelper::authorizeOrg($income->banckAcc);
        
        if ($income->income_type == 'sales_without_invoice' || $income->income_type == 'customer_payment') {
            $income_legder = \App\Models\Entryitem::where('entry_id', $income->entry_id)->where('dc', 'D')->first();
        }
        $clients = \App\Models\Client::pluck('name', 'id')->all();
        $sales_invoice = \App\Models\COALedgers::where('group_id', '39')->get();
        $types = [''=>'Select Types', 'customer_payment'=>'Customer Payment', 'customer_advance'=>'Customer Advance', 'sales_without_invoice'=>'Sales Without Invoice', 'other_income'=>'Other Income', 'interest_income'=>'Interest Income'];
        $received_via = ['cash'=>'Cash', 'check' =>'Check', 'cc' =>'CC', 'transfer'=>'Transfer', 'remittance'=>'Remittance'];
        $pdf = \PDF::loadView('pdf.income-receipt', compact('clients', 'types', 'received_via', 'income', 'sales_invoice', 'income_legder'));
        $file = 'Report_income_#'.$id.date('_Y_m_d').'.pdf';
        if (\File::exists('reports/'.$file)) {
            \File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function sendmail($id)
    {
        $income = \App\Models\BankIncome::find($id);
        try {
            $from = env('APP_EMAIL');
            $to = $income->customers->email;
            $mail = \Mail::send('emails.income-receipt', compact('income'),
                function ($message) use ($data, $from, $to) {
                    $message->subject('Income Receipt - '.$data[0]->name.date('Y-m-d'));
                    $message->from($from, env('APP_COMPANY'));
                    $message->to($to, '');
                });
            Flash::success('Mail sucessfully sent');
        } catch (\Exception $e) {
            Flash::error('Error in sending mails : Invaild Email');
        }

        return redirect()->back();
    }
}
