<?php

$GLOBALS['closing_dr'] = 0;
$GLOBALS['closing_cr'] = 0;
$GLOBALS['closing_dr_log'] = '';
$GLOBALS['closing_cr_log'] = '';

function CategoryTree($parent_id = null, $sub_mark = '', $start_date, $end_date, $fiscal)
{
    $groups = \App\Models\COAgroups::orderBy('code', 'asc')
    ->where('parent_id', $parent_id)
    ->where('org_id', auth()->user()->org_id)
    ->get();

    if (count($groups) > 0) {
        foreach ($groups as $group) {
            $cashbygroup = \TaskHelper::getTotalByGroups($group->id, $start_date, $end_date, $fiscal);
            if ($cashbygroup['dr_amount'] == null && $cashbygroup['cr_amount'] == null && $cashbygroup['opening_balance']['amount'] == 0) {
                echo '<tr>
                <td style="text-align: left; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.$sub_mark.$group->code .'</td>
                <td style="text-align: left; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.$sub_mark.$group->name.'</td>
                <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">0.00</td>
                <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">0.00</td>
                <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">0.00</td>
                <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">0.00</td>
                <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">0.00</td>
                <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">0.00</td>
                </tr>';
            } else {
                $sum = $cashbygroup['dr_amount'] - $cashbygroup['cr_amount'];
                $closing_balance = $cashbygroup['opening_balance']['dc'] == 'D' ? $sum + $cashbygroup['opening_balance']['amount'] : $sum - $cashbygroup['opening_balance']['amount'];

                if ($closing_balance > 0) {
                    echo '<tr>
                    <td style="text-align: left; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.$sub_mark.$group->code .'</td>
                    <td style="text-align: left; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.$sub_mark.$group->name.'</td>
                    <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.($cashbygroup['opening_balance']['dc'] == 'D' ? number_format(abs($cashbygroup['opening_balance']['amount']), 2) : '0.00').'</td>
                    <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.($cashbygroup['opening_balance']['dc'] != 'D' ? number_format(abs($cashbygroup['opening_balance']['amount']), 2) : '0.00').'</td>
                    <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.number_format($cashbygroup['dr_amount'], 2).'</td>
                    <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.number_format($cashbygroup['cr_amount'], 2).'</td>
                    <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.number_format(abs($closing_balance), 2).'</td>
                    <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">0.00</td>
                    </tr>';

                    if($group->id==1||$group->id==2||$group->id==3||$group->id==4){
                        $GLOBALS['closing_dr'] += abs($closing_balance);
                        }
                } else {
                    echo '<tr>
                    <td style="text-align: left; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.$sub_mark.$group->code .'</td>
                    <td style="text-align: left; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.$sub_mark.$group->name.'</td>
                    <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.($cashbygroup['opening_balance']['dc'] == 'D' ? number_format(abs($cashbygroup['opening_balance']['amount']), 2) : '0.00').'</td>
                    <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.($cashbygroup['opening_balance']['dc'] != 'D' ? number_format(abs($cashbygroup['opening_balance']['amount']), 2) : '0.00').'</td>
                    <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.number_format($cashbygroup['dr_amount'], 2).'</td>
                    <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.number_format($cashbygroup['cr_amount'], 2).'</td>
                    <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">0.00</td>
                    <td style="text-align: right; font-weight: bold; background-color: #FFFF00; color: black; border: 1px solid black;">'.number_format(abs($closing_balance), 2).'</td>
                    </tr>';

                    if($group->id==1||$group->id==2||$group->id==3||$group->id==4){
                        $GLOBALS['closing_cr'] += abs($closing_balance);
                        }
                }
            }
            $ledger_table = new \App\Models\COALedgers();
            $prefix = '';

            if ($fiscal) {
                $current_fiscal = \App\Models\Fiscalyear::where('current_year', 1)->first();
                $fiscal_year = $fiscal ? $fiscal->fiscal_year : $current_fiscal->fiscal_year;
                if ($fiscal_year != $current_fiscal->fiscal_year) {
                    $prefix = \App\Models\Fiscalyear::where('fiscal_year', $fiscal_year)->first()->numeric_fiscal_year . '_';
                    $new_coa = $prefix . 'coa_ledgers';
                    $ledger_table->setTable($new_coa);
                }
            }

            $ledgers = $ledger_table
            ->orderBy('code', 'asc')
            ->where('group_id', $group->id)
            ->get();

            if (count($ledgers) > 0) {
                $submark = $sub_mark;
                $sub_mark = $sub_mark . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

                foreach ($ledgers as $ledger) {
                    $opening_balance = TaskHelper::getLedgersOpeningBalance($ledger, $start_date, $fiscal);
                    $dr_cr = TaskHelper::getLedgerDrCr($ledger, $fiscal, $start_date, $end_date);
                    $closing_balance = \App\Helpers\TaskHelper::getLedgerClosing($opening_balance, $dr_cr['dr_total'], $dr_cr['cr_total']);

                    if ($closing_balance['dc'] == 'D') {
                        echo '<tr>
                        <td style="text-align: left; color: black; border: 1px solid black;">'.$sub_mark.$ledger->code .'</td>
                        <td style="text-align: left; color: black; border: 1px solid black;">'.$ledger->name.'</td>
                        <td style="text-align: right; color: black; border: 1px solid black;">'.($opening_balance['dc'] == 'D' ? number_format($opening_balance['amount'], 2) : '0.00').'</td>
                        <td style="text-align: right; color: black; border: 1px solid black;">'.($opening_balance['dc'] != 'D' ? number_format($opening_balance['amount'], 2) : '0.00').'</td>
                        <td style="text-align: right; color: black; border: 1px solid black;">'.number_format($dr_cr['dr_total'], 2).'</td>
                        <td style="text-align: right; color: black; border: 1px solid black;">'.number_format($dr_cr['cr_total'], 2).'</td>
                        <td style="text-align: right; color: black; border: 1px solid black;">'.number_format($closing_balance['amount'], 2).'</td>
                        <td style="text-align: right; color: black; border: 1px solid black;">0.00</td>
                        </tr>';
                    } else {
                        echo '<tr>
                        <td style="text-align: left; color: black; border: 1px solid black;">'.$sub_mark.$ledger->code .'</td>
                        <td style="text-align: left; color: black; border: 1px solid black;">'.$ledger->name.'</td>
                        <td style="text-align: right; color: black; border: 1px solid black;">'.($opening_balance['dc'] == 'D' ? number_format($opening_balance['amount'], 2) : '0.00').'</td>
                        <td style="text-align: right; color: black; border: 1px solid black;">'.($opening_balance['dc'] != 'D' ? number_format($opening_balance['amount'], 2) : '0.00').'</td>
                        <td style="text-align: right; color: black; border: 1px solid black;">'.number_format($dr_cr['dr_total'], 2).'</td>
                        <td style="text-align: right; color: black; border: 1px solid black;">'.number_format($dr_cr['cr_total'], 2).'</td>
                        <td style="text-align: right; color: black; border: 1px solid black;">0.00</td>
                        <td style="text-align: right; color: black; border: 1px solid black;">'.number_format($closing_balance['amount'], 2).'</td>
                        </tr>';
                    }
                }
                $sub_mark = $submark;
            }

            CategoryTree($group->id, $sub_mark . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $start_date, $end_date, $fiscal);
        }
    }
}
?>


<table class="table table-hover table-bordered" id="orders-table">
    <thead>
        <tr class="bg-primary">
            <th rowspan="2" style="text-align: center; font-weight: bold; background-color: #0ca014; color: black; border: 1px solid black;">Account Code</th>
            <th rowspan="2" style="text-align: center; font-weight: bold; background-color: #0ca014; color: black; border: 1px solid black;">Account Name</th>
            <th colspan="2" style="text-align: center; font-weight: bold; background-color: #0ca014; color: black; border: 1px solid black;"> Opening Balance</th>
            <th colspan="2" style="text-align: center; font-weight: bold; background-color: #0ca014; color: black; border: 1px solid black;">Transaction Period</th>
            <th colspan="2" style="text-align: center; font-weight: bold; background-color: #0ca014; color: black; border: 1px solid black;">Closing Balance</th>
        </tr>
        <tr class="bg-primary">
            <th style="text-align: center; font-weight: bold; background-color: #0ca014; color: black; border: 1px solid black;">Debit ({{ env('APP_CURRENCY') }})</th>
            <th style="text-align: center; font-weight: bold; background-color: #0ca014; color: black; border: 1px solid black;">Credit ({{ env('APP_CURRENCY') }})</th>
            <th style="text-align: center; font-weight: bold; background-color: #0ca014; color: black; border: 1px solid black;">Debit ({{ env('APP_CURRENCY') }})</th>
            <th style="text-align: center; font-weight: bold; background-color: #0ca014; color: black; border: 1px solid black;">Credit ({{ env('APP_CURRENCY') }})</th>
            <th style="text-align: center; font-weight: bold; background-color: #0ca014; color: black; border: 1px solid black;">Debit ({{ env('APP_CURRENCY') }})</th>
            <th style="text-align: center; font-weight: bold; background-color: #0ca014; color: black; border: 1px solid black;">Credit ({{ env('APP_CURRENCY') }})</th>
        </tr>
    </thead>
    <tbody>
        @if (count($groups) > 0)
        {{ CategoryTree(null, '', $start_date, $end_date, $fiscal) }}
        <tr style=" font-weight: bold; text-align: center;">
            <?php
            $assetstotal = \TaskHelper::getTotalByGroups(1, $start_date, $end_date, $fiscal);
            $equitytotal = \TaskHelper::getTotalByGroups(2, $start_date, $end_date, $fiscal);
            $incometotal = \TaskHelper::getTotalByGroups(3, $start_date, $end_date, $fiscal);
            $expensestotal = \TaskHelper::getTotalByGroups(4, $start_date, $end_date, $fiscal);

            $total_dr_amount = $assetstotal['dr_amount'] + $equitytotal['dr_amount'] + $incometotal['dr_amount'] + $expensestotal['dr_amount'];
            $total_cr_amount = $assetstotal['cr_amount'] + $equitytotal['cr_amount'] + $incometotal['cr_amount'] + $expensestotal['cr_amount'];
            $opening_dr = ($assetstotal['opening_balance']['dc'] == 'D' ? $assetstotal['opening_balance']['amount'] : 0) + ($equitytotal['opening_balance']['dc'] == 'D' ? $equitytotal['opening_balance']['amount'] : 0) + ($incometotal['opening_balance']['dc'] == 'D' ? $incometotal['opening_balance']['amount'] : 0) + ($expensestotal['opening_balance']['dc'] == 'D' ? $expensestotal['opening_balance']['amount'] : 0);
            $opening_cr = ($assetstotal['opening_balance']['dc'] != 'D' ? $assetstotal['opening_balance']['amount'] : 0) + ($equitytotal['opening_balance']['dc'] != 'D' ? $equitytotal['opening_balance']['amount'] : 0) + ($incometotal['opening_balance']['dc'] != 'D' ? $incometotal['opening_balance']['amount'] : 0) + ($expensestotal['opening_balance']['dc'] != 'D' ? $expensestotal['opening_balance']['amount'] : 0);
            ?>
            <td colspan="2" style="text-align: center; font-weight: bold; color: black; border: 1px solid black;">Total</td>
            <td style="text-align: right; font-weight: bold; color: black; border: 1px solid black;">Dr {{ number_format($opening_dr, 2) }}</td>
            <td style="text-align: right; font-weight: bold; color: black; border: 1px solid black;">Cr {{ number_format($opening_cr, 2) }}</td>
            <td style="text-align: right; font-weight: bold; color: black; border: 1px solid black;">Dr {{ number_format($total_dr_amount, 2) }}</td>
            <td style="text-align: right; font-weight: bold; color: black; border: 1px solid black;">Cr {{ number_format($total_cr_amount, 2) }}</td>
            <td style="text-align: right; font-weight: bold; color: black; border: 1px solid black;">Dr {{ $GLOBALS['closing_dr'] }}</td>
            <td style="text-align: right; font-weight: bold; color: black; border: 1px solid black;">Cr {{ $GLOBALS['closing_cr'] }}</td>
        </tr>
        <tr style="text-align: center;">
            <td colspan="2" style="text-align: center; font-weight: bold; color: black; border: 1px solid black;"> Difference Amount</td>
            <td colspan="2" style="text-align: center; font-weight: bold; color: black; border: 1px solid black;">
                @if ($opening_dr != $opening_cr)
                {{ $opening_dr > $opening_cr ? 'Dr ' : 'Cr ' }}
                {{ number_format($opening_dr - $opening_cr, 2) }}
                @else
                0
                @endif
            </td>
            <td colspan="2" style="text-align: center; font-weight: bold; color: black; border: 1px solid black;">
                @if (round($total_dr_amount, 2) == round($total_cr_amount, 2))
                <i class="fa fa-check-circle text-success"></i>
                @else
                <i class="fa fa-close text-danger"></i>
                {{ $total_dr_amount - $total_cr_amount > 0 ? 'Dr' : 'Cr'  }}
                {{ number_format($total_dr_amount - $total_cr_amount, 2) }}
                @endif
            </td>
            <td colspan="2" style="text-align: center; font-weight: bold; color: black; border: 1px solid black;">
                @if(round($GLOBALS['closing_dr'],2) == round($GLOBALS['closing_cr'],2))
                <i class="fa fa-check-circle text-success"></i>
                @else
                <i class="fa fa-close text-danger"></i>
                {{ $GLOBALS['closing_dr']-$GLOBALS['closing_cr'] > 0 ? 'Dr' : 'Cr'  }}
                {{number_format($GLOBALS['closing_dr']-$GLOBALS['closing_cr'],2)}}
                @endif
            </td>

        </tr>
        @endif
    </tbody>
</table>
