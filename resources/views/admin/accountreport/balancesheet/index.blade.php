@extends('layouts.master')
@section('content')
    <style type="text/css">
        .balancesheettable th,.balancesheettable td{


            padding: 4px !important;

        }
        table{
            font-size: 12px;
        }
        /*.f-16{*/
        /*    font-size: 16.5px;*/
        /*}*/
    </style>
    <?php

    function CategoryTree($parent_id=null,$sub_mark='',$actype,$start_date,$end_date,$fiscal){
        $total = 0;
        $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)
            ->where('org_id',auth()->user()->org_id)->get();

        if(count($groups)>0){
            foreach($groups as $group){

                $cashbygroup = \TaskHelper::getTotalByGroups($group->id,$start_date,$end_date,$fiscal);
                if($cashbygroup['dr_amount'] == null && $cashbygroup['cr_amount'] == null&& $cashbygroup['opening_balance']['amount'] == 0){
                    echo '<tr data-toggle="collapse"
                 data-target=".ledgers'.$group->id.'" style="cursor: pointer" class="accordion-toggle">
                    <td><b><a target="_blank" href="'.route('admin.chartofaccounts.detail.groups', $group->id).'" style="color:black">'.$sub_mark.'['.$group->code.']'.$group->name.(count($group->ledgers))>0?' <i class="fa fa-chevron-down" style="font-size: 10px"></i>':''.'</b></td>
                    <td><b><span>0.00</span></b></td>
                 </tr>';
                }else{
                    // $sum=$cashbygroup['dr_amount']-$cashbygroup['cr_amount'];
                    // $closing_balance=$cashbygroup['opening_balance']['dc']=='D'?($sum+$cashbygroup['opening_balance']['amount']):
                    //     ($sum-$cashbygroup['opening_balance']['amount']);
                        $sum=$cashbygroup['dr_amount']-$cashbygroup['cr_amount'];
                    $closing_balance=$cashbygroup['opening_balance']['dc']=='D'?($sum+$cashbygroup['opening_balance']['amount']):
                    ($sum-$cashbygroup['opening_balance']['amount']);
                    if($closing_balance>0){
                        echo '<tr data-toggle="collapse"
                 data-target=".ledgers'.$group->id.'" class="accordion-toggle" style="cursor: pointer">
                        <td><b><a target="_blank" href="'.route('admin.chartofaccounts.detail.groups', $group->id).'" style="color:black">'.$sub_mark.'['.$group->code.']'.$group->name.(is_countable($group->ledgers))>0?' <i class="fa fa-chevron-down" style="font-size: 10px"></i>':''.'</b></td>
                        <td><b><span>Dr '.number_format(abs($closing_balance),2).'</span></b></td>
                     </tr>';
                    }else
                    {
                        echo '<tr data-toggle="collapse"
                 data-target=".ledgers'.$group->id.'" style="cursor: pointer" class="accordion-toggle">
                 <td><b><a target="_blank" href="'.route('admin.chartofaccounts.detail.groups', $group->id).'" style="color:black">'.$sub_mark.'['.$group->code.']'.$group->name.(is_countable($group->ledgers)>0?' <i class="fa fa-chevron-down" style="font-size: 10px"></i>':'').'</b></td>
                        <td><b><span>Cr '.number_format(abs($closing_balance),2).'</span></b></td>
                     </tr>';
                    }
                }

                $ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)
                    ->where('group_id',$group->id)
                    ->get();
                if( count( $ledgers) > 0 ) {

                    $submark= $sub_mark;
                    $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                    foreach($ledgers as $ledger){
                        // $closing_balance =TaskHelper::getLedgerDebitCredit($ledger->id);
                        $opening_balance =TaskHelper::getLedgersOpeningBalance($ledger,$start_date,$fiscal);
                        $dr_cr =TaskHelper::getLedgerDrCr($ledger,$fiscal,$start_date,$end_date);
                        $closing_balance=\App\Helpers\TaskHelper::getLedgerClosing($opening_balance,$dr_cr['dr_total'],$dr_cr['cr_total']);

                // if ($closing_balance['amount'] > 0) {

                            if( $closing_balance['dc'] == 'D'){

                                echo '<tr style="color: #3c8dbc;" class="hiddenRow accordian-body collapse ledgers'.$ledger->group_id.'">

                      <td class="bg-warning"><a href="/admin/accounts/reports/ledger_statement?ledger_id='.$ledger->id.'">'.$sub_mark.'['.$ledger->code.']'.$ledger->name.'</a></td>
                       <td class="bg-warning f-16">Dr <span class="dr'.$actype.' dr'.$actype. $index .'">'.
                                    number_format($closing_balance['amount'],2).'</span></td>
                     </tr>';
                                $total += $closing_balance['amount'];
                            }else{
                                echo '<tr style="color: #3c8dbc;" class="hiddenRow accordian-body collapse ledgers'.$ledger->group_id.'">

                        <td class="bg-danger"><a href="/admin/accounts/reports/ledger_statement?ledger_id='.$ledger->id.'">'.$sub_mark.'['.$ledger->code.']'.$ledger->name.'</a></td>
                         <td class="bg-danger f-16">Cr <span class="cr'.$actype.'">'.
                                    number_format($closing_balance['amount'],2).'</span></td>
                     </tr>';
                                $total -= $closing_balance['amount'] ;
                            }
                // }

                    }

                    $sub_mark=$submark;
                }
                CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$actype,$start_date,$end_date,$fiscal);
            }
        }

    }

    ?>

    <link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        <h1>
            {{ $page_title }}
            <small>  {!! $page_description ?? "Page description" !!}</small>
        </h1>
        {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
{{--        <br>--}}
{{--        <a href="{{route('admin.accounts.reports.balancesheet.pdf')}}" class="btn btn-primary">PDF</a>--}}
{{--    <!-- <a href="{{route('admin.accounts.reports.balancesheet.excel')}}" class="btn btn-primary">Excel</a> -->--}}
{{--        <br>--}}
    </section>

    <div class="row">
        <form method="get" action="/admin/accounts/reports/balancesheet">
            <div style="display: inline-flex;float: left;padding-left: 20px">
                <div class="form-group">
                    <label>Fiscal Year</label>
                    <div class="input-group">
                        <select id="fiscal_year_id" class="form-control input-sm" name="fiscal_year" required="required">
                            @foreach($allFiscalYear as $key => $pk)
                                <option value="{{ $pk->fiscal_year }}" {{$fiscal_year==$pk->fiscal_year?'selected':''}}>{{ $pk->fiscal_year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- /.input group -->
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Start Date</label>
                    <div class="input-group">
                        <input id="ReportStartdate" type="text" name="start_date" class="form-control input-sm datepicker" value="{{ $start_date }}">
                        <div class="input-group-addon">
                            <i>
                                <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : Leave start date as empty if you want statement from the start of the financial year.">
                                </div>
                            </i>
                        </div>
                    </div>
                    <!-- /.input group -->
                </div>
                <!-- /.form group -->
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>End Date</label>

                    <div class="input-group">
                        <input id="ReportEnddate" type="text" name="end_date" class="form-control input-sm datepicker" value="{{ $end_date }}">
                        <div class="input-group-addon">
                            <i>
                                <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : Leave end date as empty if you want statement till the end of the financial year.">
                                </div>
                            </i>
                        </div>
                    </div>
                    <!-- /.input group -->
                </div>
                <!-- /.form group -->
            </div>
            <div>

                <!-- /.form group -->
            </div><div style="padding: 0;display: inline-block">
                <div>
                    <label for="">
                        <label for=""></label>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-sm">Filter</button>

            </div>
{{--            <div class="" style="padding:0;padding-left: 5px;display: inline-block">--}}
{{--                <div>--}}
{{--                    <label for="">--}}
{{--                        <label for=""></label>--}}
{{--                    </label>--}}
{{--                </div>--}}
{{--                <a href="{{route('admin.accounts.reports.trialbalance.excel',['start_date'=>$start_date,'end_date'=>$end_date,'fiscal_year'=>$fiscal_year])}}" class="btn btn-success btn-sm">Excel</a>--}}

{{--            </div>--}}
        </form>
       </div>


    <div class='row'>
        <div class='col-md-6'>
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered balancesheettable" >
                            <thead>
                            <tr class="bg-primary">

                                <th>Assets</th>

                                <th>Amount(Rs)</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{ CategoryTree(1,'','assets',$start_date,$end_date,$fiscal) }}
                            </tbody>
                            <tfoot>
                            <tr style=" font-size: 13px; font-weight: bold; background-color: silver">

                                <th>Total Assets</th>

                                <td>
                                    <?php
                                    $asset_total = \TaskHelper::getTotalByGroups(1,$start_date,$end_date,$fiscal);
                                    $sum=$asset_total['dr_amount']-$asset_total['cr_amount'];
                                    $asset_closing_balance=$asset_total['opening_balance']['dc']=='D'?($sum+$asset_total['opening_balance']['amount']):
                                        ($sum-$asset_total['opening_balance']['amount']);
                                    ?>
                                    {{$asset_closing_balance>0?'Dr '.number_format($asset_closing_balance,2):'Cr '.number_format(abs($asset_closing_balance),2)}}
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div> <!-- table-responsive -->
                </div><!-- /.box-body -->
            </div><!-- /.col -->
        </div>
        <div class='col-md-6'>
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered balancesheettable">
                            <thead>
                            <tr class="bg-maroon">

                                <th>Liabilities and Owners Equity (Cr)</th>

                                <th>Amount (Rs)</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{ CategoryTree(2,'','libalities',$start_date,$end_date,$fiscal) }}
                            </tbody>
                            <tfoot>
                                 <tr style=" font-size: 13px; font-weight: bold; background-color: skyblue">
                                <td>Profit Before Tax</td>
                                @php
                                    $income = \TaskHelper::getDrCrByGroups(\FinanceHelper::get_ledger_id('INCOME_LEDGER_GROUP'), $start_date, $end_date, $fiscal);
                                    $return = \TaskHelper::getDrCrByGroups(\FinanceHelper::get_ledger_id('SALES_RETURN_GROUP'), $start_date, $end_date, $fiscal);
                                    $total_income = $income['cr_amount'] - $return['dr_amount'];
                                    $cogs = \TaskHelper::getDrCrByGroups($group_id = \FinanceHelper::get_ledger_id('COST_OF_GOODS_GROUP'), $start_date, $end_date, $fiscal);
                                    $cost_of_sales = $cogs['dr_amount'];
                                    $gross_profit = $total_income - $cost_of_sales;

                                    $group_id = \FinanceHelper::get_ledger_id('INDIRECT_INCOME_LEDGER_GROUP');
                                    $other_income_totals = \TaskHelper::getDrCrByGroups($group_id, $start_date, $end_date, $fiscal);
                                    $other_income = $other_income_totals['cr_amount'];

                                    $indirect_expense_groups = \App\Models\COAgroups::where('parent_id', \FinanceHelper::get_ledger_id('INDIRECT_EXPENSES_LEDGER_GROUP'))->get();
                                    $indirect_exp_total = 0;
                                    foreach($indirect_expense_groups as $group){
                                        $expenses = \TaskHelper::getDrCrByGroups($group->id, $start_date, $end_date, $fiscal);
                                        $indirect_exp_total += $expenses['dr_amount'];
                                    }
                                    $profit_before_tax = $gross_profit + $other_income - $indirect_exp_total;
                                @endphp
                                <td>{{'Cr '.number_format($profit_before_tax,2)}}</td>
                            </tr>
                            <tr style=" font-size: 13px; font-weight: bold; background-color: silver">
                                <td>Total Liabilities and Owners Equity</td>
                                <td> <?php
                                    $liabilities_total = \TaskHelper::getTotalByGroups(2,$start_date,$end_date,$fiscal);
                                    $sum=$liabilities_total['dr_amount']-$liabilities_total['cr_amount'];
                                    $liab_closing_balance=$liabilities_total['opening_balance']['dc']=='D'?($sum+$liabilities_total['opening_balance']['amount']):
                                        ($sum-$liabilities_total['opening_balance']['amount']);
                                    $total_liabilities=abs($liab_closing_balance)+$profit_before_tax;
                                    ?>
                                    {{$liab_closing_balance>0?'Dr '.number_format($total_liabilities,2):'Cr '.number_format(abs($total_liabilities),2)}}
                                </td>
                            </tr>
                            {{-- <tr style=" font-size: 13px; font-weight: bold; background-color: skyblue">
                                <td>Profit & Loss Account (Net Profit)</td>
                                <td>{{number_format(($asset_closing_balance-$liab_closing_balance),2)}}</td>
                            </tr> --}}
{{--                            <tr style=" font-size: 13px; font-weight: bold;">--}}
{{--                                <th>Total</th>--}}
{{--                                <td class="assetsTotal"></td>--}}
{{--                            </tr>--}}
                            </tfoot>
                        </table>
                    </div> <!-- table-responsive -->

                </div><!-- /.box-body -->
            </div>
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')

    <script type="text/javascript">
        $(function(){

            const allTotal = {


                drassets: 0,
                drassets1: 0,
                crassets: 0,
                drlibalities: 0,
                crlibalities: 0,

            }
            $('.drassets').each(function(){
                var number=$(this).text().replace(/\,/g,'')
                allTotal.drassets += Number(number);

            });

            $('.crassets').each(function(){
                var number=$(this).text().replace(/\,/g,'')

                allTotal.crassets += Number(number);

            });

            $('.drlibalities').each(function(){
                var number=$(this).text().replace(/\,/g,'')

                allTotal.drlibalities += Number(number);


            });

            $('.crlibalities').each(function(){
                var number=$(this).text().replace(/\,/g,'')

                allTotal.crlibalities += Number(number);

            });

            var assetsTotal = allTotal.crassets -  allTotal.drassets;
            var libalitiesTotal = allTotal.drlibalities - allTotal.crlibalities;

            if((assetsTotal) >= 0)
            {
                $('.assetsTotal').text('Cr '+ assetsTotal.toFixed(3)  );
            }else
            {
                var assetsTotal = -assetsTotal;
                $('.assetsTotal').text('Dr '+ assetsTotal.toFixed(3)  );
            }

            if(libalitiesTotal >= 0)
            {
                $('#libalitiesTotal').text('Dr '+libalitiesTotal.toFixed(3)  );
            }else
            {
                var libalitiesTotal = -libalitiesTotal;
                $('#libalitiesTotal').text('Cr '+libalitiesTotal.toFixed(3)  );
            }


            console.log(allTotal.crassets -  allTotal.drassets);


            $('#netProfit').text(((allTotal.crassets -  allTotal.drassets)-(allTotal.drlibalities - allTotal.crlibalities)).toFixed(3) );
        });
    </script>

    <script type="text/javascript">


        $(function(){
            $('.datepicker').datepicker({
                //inline: true,
                dateFormat: 'yy-mm-dd',
                sideBySide: false,
            });
        });
        $(document).ready(function() {
            $('.customer_id').select2();
        });
        $(document).on('change','#fiscal_year_id',function () {
            var fiscal_year = $(this).val()
            var fiscal_detail = ''
            var all_fiscal_years = {!! json_encode($allFiscalYear); !!}
            all_fiscal_years.forEach((item) => {
                if (item.fiscal_year == fiscal_year)
                    fiscal_detail = item
            });

            $('#ReportStartdate').val(fiscal_detail.start_date)
            $('#ReportEnddate').val(fiscal_detail.end_date)

        })
    </script>
@endsection
