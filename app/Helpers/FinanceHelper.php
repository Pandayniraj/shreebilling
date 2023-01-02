<?php

namespace App\Helpers;

use App\Models\COAgroups;
use App\Models\COALedgers;
use App\Models\Entry;
use App\Models\Fiscalyear;
use Illuminate\Support\Facades\DB;

class FinanceHelper
{
    public static function cur_fisc_yr()
    {
        $current_yr = Fiscalyear::where('current_year', '1')
      ->where('org_id', \Auth::user()->org_id)
      ->first();

        return $current_yr;
    }

    public static function get_last_entry_number($entryType)
    {

        $lastEntry = Entry::select('id')->orderBy('id','desc')->first()->id + 1;
        $entryType = \App\Models\Entrytype::find($entryType);
        return $entryType->code.'-'.$lastEntry;
    }

    public static function get_entry_type_name($id, $org_id = false)
    {
        if (! $org_id) {
            $org_id = \Auth::user()->org_id;
        }

$entrytype = \App\Models\Entrytype::where(DB::raw("REPLACE(TRIM(id),'\r\n','')"), $id)
      ->where('org_id', $org_id)
      ->first();

        return $entrytype->name ?? null;
    }

     public static function cur_leave_yr()
    {
        $leave_yr = \App\Models\Leaveyear::where('current_year', '1')
                    ->where('org_id', \Auth::user()->org_id)
                    ->first();

        return $leave_yr;
    }

	    public static function get_entry_type_label($id, $org_id = false)
    {
        if (! $org_id) {
            $org_id = \Auth::user()->org_id;
        }

    $entrytype = \App\Models\Entrytype::where(DB::raw("REPLACE(TRIM(id),'\r\n','')"), $id)
      ->where('org_id', $org_id)
      ->first();
   
        return $entrytype->label ?? null;
    }
    

    public static function get_ledger_id($name, $org_id = false)
    {
        if (! $org_id) {
            $org_id = \Auth::user()->org_id;
        }
        //dd($name, $org_id);
        $name = trim($name);
        $ledger = \App\Models\LedgerSettings::where('org_id', $org_id)
      ->where(DB::raw("REPLACE(TRIM(ledger_name),'\r\n','')"), $name)
      ->first();
        if ($ledger) {
            return $ledger->ledger_id;
        } else {
            return null;
        }
    }

    public static function getNextCodeLedgers($id, $org_id = false)
    {
        if (! $org_id) {
            $org_id = \Auth::user()->org_id;
        }
        $group_data = COAgroups::find($id);
        $group_code = $group_data->code;
        $q = COALedgers::where('group_id', $id)->where('org_id', $org_id)->where('code', '!=', 'null')->get();
        if ($q) {
            $last = $q->last();
            $last = $last->code;
            $l_array = explode('-', $last);
            $new_index = end($l_array);
            $new_index += 1;
            $new_index = sprintf('%04d', $new_index);

            return $group_code.'-'.$new_index;
        } else {
            return $group_code.'-0001';
        }
    }

    public static function get_entry_type_id($label, $org_id = false)
    {
        if (! $org_id) {
            $org_id = \Auth::user()->org_id;
        }

        $entrytype = \App\Models\Entrytype::where(DB::raw("REPLACE(TRIM(label),'\r\n','')"), $label)
      ->where('org_id', $org_id)
      ->first();

        return $entrytype->id ?? null;
    }

    public static function ledgerGroupsOptionshtml($id = false, $parents_id = null)
    {
        $groups = \App\Models\COAgroups::orderBy('code', 'asc')
      ->where('parent_id', $parents_id)
      ->where('org_id', \Auth::user()->org_id)
      ->where(function ($query) use ($id) {
          if ($id) {
              return $query->where('id', $id);
          }
      })
      ->get();

        foreach ($groups as $key => $child_group) {
            echo "<optgroup  label='{$child_group->name}'>";
            $ledgers = \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id', $child_group->id)->get();
            foreach ($ledgers as $key => $value) {
                echo  "<option value = '{$value->id}'>[{$value->code}] {$value->name} </option>";
            }
            echo '</optgroup>';
            self::ledgerGroupsOptionshtml(false, $child_group->id);
        }
    }

    private static function calculateledgerbalance($groupid, $fiscal_year, $item = [])
    {
        $ledgers = \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id', $groupid)->get();

        foreach ($ledgers as $key => $value) {
            $entries = \App\Models\Entryitem::select('entryitems.*')
        ->where('ledger_id', $value->id)
        ->leftjoin('entries', 'entries.id', '=', 'entryitems.entry_id')
        ->where('entries.fiscal_year_id', $fiscal_year)
        ->groupBy('entryitems.id')
        ->get();

            $totalbalance = 0;

            if (count($entries) > 0) {
                foreach ($entries as $entry) {
                    $totalbalance = $totalbalance + $entry->amount;
                }
            }

            $item[] = ['name' => $value->name, 'y' => $totalbalance];
        }

        return $item;
    }

    public static function calculateLedgerGroupOpeningBalance(
    $parent_id,
    $fiscal_year,
    $item = [],
    $dept = 0
  ) {
        $groups = \App\Models\COAgroups::orderBy('code', 'asc')
                ->where('parent_id', $parent_id)
                ->where('org_id',\Auth::user()->org_id)
                ->get();

        if ($dept == 0) {
            $item = self::calculateledgerbalance($parent_id, $fiscal_year, $item);
        }

        foreach ($groups as $key => $child_group) {
            $item = self::calculateledgerbalance($child_group->id, $fiscal_year, $item);

            $dept++;

            if ($dept > 100) { //stop infinite loop when wrong id is passed
                return $item;
            }

            self::calculateLedgerGroupOpeningBalance($child_group->id,$fiscal_year, $item, $dept);
        }

        return $item;
    }

    public static function getAccountingPrefix($name)
    {
        $value = \App\Models\AccountingPrefix::where('name', $name)->first()->value;

        return $value;
    }
}
