<?php

namespace App\Traits;

use App\Models\COALedgers;
use Carbon\Carbon;
use db;
use Illuminate\Support\Facades\Schema;

trait ReorderTablesWithFiscalYear
{
    public function renameAndDuplicateTable($existing_table, $old_fiscal_year)
    {
        $table_name_to_update = $old_fiscal_year . '_' . $existing_table;

        if (!Schema::hasTable($table_name_to_update)) {
            Schema::rename($existing_table, $table_name_to_update);
            DB::statement("CREATE TABLE " . $existing_table . " LIKE " . $table_name_to_update);
            return true;
        }
        return false;
    }

    public function collectLedgerClosingStock($new_starting_date,$last_date=null)
    {
        $ledgers=\App\Models\COALedgers::
        when($last_date,function ($q) use ($last_date) {
          $q->whereDate('created_at','<=',$last_date);
        })
        ->get();
        $ledger_list=[];
        foreach ($ledgers as $ledger) {

            $total = 0;

            $entries = \App\Models\Entryitem::where('ledger_id',$ledger->id)
                ->when($last_date,function ($q) use ($last_date) {
                    $q->whereDate('created_at','<=',$last_date);
                })->get();

            $total = $ledger['op_balance_dc'] == 'D' ? ($total + $ledger['op_balance']) : ($total - $ledger['op_balance']);
            foreach ($entries as $key => $ei) {
                $total = $ei['dc'] == 'D' ? ($total + $ei['amount']) : ($total - $ei['amount']);
            }
            $date_time=\DateTime::createFromFormat('Y-m-d H:i:s',Carbon::parse($new_starting_date)->toDatetimeString(),new \DateTimeZone('GMT'));
            $date_time->setTimezone(new \DateTimeZone('Asia/Kathmandu'));
            $ledger_list[]=[
                'id'=>$ledger['id'],
                'group_id'=>$ledger['group_id'],
                'user_id'=>$ledger['user_id'],
                'org_id'=>$ledger['org_id'],
                'name'=>$ledger['name'],
                'code'=>$ledger['code'],
                'op_balance'=>abs($total),
                'op_balance_dc'=>$total>=0?'D':'C',
                'type'=>$ledger['type'],
                'ledger_type'=>$ledger['ledger_type'],
                'staff_or_company_id'=>$ledger['staff_or_company_id'],
                'reconciliation'=>$ledger['reconciliation'],
                'notes'=>$ledger['notes'],
                'created_at'=>$date_time,
                'updated_at'=>$date_time,
            ];
        }
        return $ledger_list;
    }
    public function collectProductStockClosingStock($new_starting_date,$last_date=null)
    {
        $stocks = \App\Models\StockMove:: when($last_date,function ($q) use ($last_date) {
            $q->whereDate('created_at','<=',$last_date);
        })->get()->groupBy('stock_id');

        $stock_openings = [];
        foreach ($stocks as $stock_id => $stock) {
            $total_qty = 0;
            foreach ($stock as $key => $item) {
                $total_qty += $item['qty'];
            }
            $date_time=\DateTime::createFromFormat('Y-m-d H:i:s',Carbon::parse($new_starting_date)->toDatetimeString(),new \DateTimeZone('GMT'));
            $date_time->setTimezone(new \DateTimeZone('Asia/Kathmandu'));

            $stock_openings[] = [
                'id'=>$stock['id'],
                'stock_id' => $stock_id,
                'trans_type' => 403,
                'tran_date' => $date_time,
                'user_id'=>1,
                'reference' => 'fiscal_year_opening_stock',
                'qty' => $total_qty,
                'created_at'=>$date_time,
                'updated_at'=>$date_time,
            ];
        }
        return $stock_openings;
    }
//'stock_id' => $stock_id,
//'order_no'=>0,
//'trans_type' => 403,
//'tran_date' => $date_time,
//'user_id'=>1,
//'order_reference'=>0,
//'reference' => 'fiscal_year_opening_stock',
//'transaction_reference_id' => 0,
//'note'=>null,
//'location'=>0,
//'price'=>0,
//'qty' => $total_qty,
//'created_at'=>$date_time,
//'updated_at'=>$date_time,
//    public function collectRemainingLedgers($start_date)
//    {
//        $ledgers=\App\Models\COALedgers::
//          whereDate('created_at','>=',$start_date)
//        ->get();
//        $ledger_list=[];
//        foreach ($ledgers as $ledger) {
//            $ledger_list[]=[
//                'group_id'=>$ledger['group_id'],
//                'org_id'=>$ledger['org_id'],
//                'name'=>$ledger['name'],
//                'code'=>$ledger['code'],
//                'op_balance'=>$ledger['op_balance'],
//                'op_balance_dc'=>$ledger['op_balance_dc'],
//                'type'=>$ledger['type'],
//                'ledger_type'=>$ledger['ledger_type'],
//                'staff_or_company_id'=>$ledger['staff_or_company_id'],
//                'reconciliation'=>$ledger['reconciliation'],
//                'notes'=>$ledger['notes'],
//                'created_at'=>$ledger['created_at'],
//                'updated_at'=>$ledger['updated_at'],
//            ];
//        }
//        return $ledger_list;
//    }
}
