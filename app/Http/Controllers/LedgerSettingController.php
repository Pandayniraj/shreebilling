<?php

namespace App\Http\Controllers;

use App\Models\LedgerSettings;
use Flash;
use Illuminate\Http\Request;

class LedgerSettingController extends Controller
{
    public function index()
    {
        $page_title = 'Manage Legder Ids';
        $ledgersList = LedgerSettings::where('org_id', \Auth::user()->org_id)
                    ->orderBy('id', 'desc')
                    ->paginate(50);

        return view('admin.ledger_settings.index', compact('ledgersList', 'page_title'));
    }

    public function create()
    {
        $page_title = 'Create Legder Ids';
        $coa_groups = \App\Models\COAgroups::where('org_id', \Auth::user()->org_id)->get();
        $coa_legders = \App\Models\COALedgers::where('org_id', \Auth::user()->org_id)->get();
        $table = 'coa_groups';

        return view('admin.ledger_settings.create', compact('coa_groups', 'coa_legders', 'page_title', 'table'));
    }

    public function store(Request $request)
    {
        $ledger_name = \TaskHelper::make_name_slug($request->ledger_name);
        $ledger_name = strtoupper($ledger_name);
        $check = LedgerSettings::where('org_id', \Auth::user()->org_id)
                    ->where(\DB::raw("REPLACE(TRIM(ledger_name),'\r\n','')"), $ledger_name)
                    ->first();
        if ($check) {
            Flash::error('Ledger Label Cannot Be dublicate');

            return redirect()->back();
        }
        //everything is ok now create
        $attributes = [
            'org_id'=> \Auth::user()->org_id,
            'ledger_name'=>$ledger_name,
            'table_name'=>$request->table_name,
            'ledger_id'=>$request->ledger_id,
            'description'=>$request->description,
            'is_default'=>isset($request->is_default) ? '1' : '0',
        ];
        Flash::success('Ledger Setting Create');
        LedgerSettings::create($attributes);

        return redirect('/admin/ledgers/settings/');
    }

    public function edit($id)
    {
        $page_title = 'Edit Legder Ids';
        $ledger = LedgerSettings::find($id);
        $coa_groups = \App\Models\COAgroups::where('org_id', \Auth::user()->org_id)->get();
        $coa_legders = \App\Models\COALedgers::where('org_id', \Auth::user()->org_id)->get();
        $table = $ledger->table_name;

        return view('admin.ledger_settings.edit', compact('coa_groups', 'coa_legders', 'page_title', 'table', 'ledger'));
    }

    public function update(Request $request, $id)
    {
        $ledger = LedgerSettings::find($id);
        $ledger_name = \TaskHelper::make_name_slug($request->ledger_name);
        $ledger_name = strtoupper($ledger_name);
        $check = LedgerSettings::where('org_id', $ledger->org_id)
                    ->where(\DB::raw("REPLACE(TRIM(ledger_name),'\r\n','')"), $ledger_name)
                    ->where('id', '!=', $id)
                    ->first();
        if ($check) {
            Flash::error('Ledger Label Cannot Be dublicate');

            return redirect()->back();
        }
        //everything is ok now update
        $attributes = [
            'ledger_name'=>$ledger_name,
            'table_name'=>$request->table_name,
            'ledger_id'=>$request->ledger_id,
            'is_default'=>isset($request->is_default) ? '1' : '0',
            'description'=>$request->description,
        ];
        $ledger->update($attributes);
        Flash::success('Ledger Setting Updated');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $ledger = LedgerSettings::find($id);
        if (! $ledger->isDeletable()) {
            abort(404);
        }
        $ledger->delete();
        Flash::success('SuccessFully Deleted');

        return redirect()->back();
    }
}
