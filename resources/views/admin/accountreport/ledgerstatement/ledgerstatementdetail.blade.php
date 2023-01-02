
@extends('layouts.master')
@section('content')


    <?php
    function CategoryTree($parent_id=null,$sub_mark='',$ledgers_data){

        $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)->where('parent_id',$parent_id)->get();

        if(count($groups)>0){
            foreach($groups as $group){
                echo '<option value="'.$group->id.'" disabled><strong>'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</strong></option>';

                $ledgers= \App\Models\COALedgers::with('group:id,name')->orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)->where('group_id',$group->id)
                    ->get();
                if(count($ledgers)>0){
                    $submark= $sub_mark;
                    $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                    foreach($ledgers as $ledger){

                        if( ($ledgers_data->id??'') == $ledger->id){
                            echo '<option selected value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->group->name.']'.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';
//                            echo '<option selected value="'.$ledger->id.'"><strong>'.$sub_mark.$ledger->name.'</strong></option>';
                        }else{

                            echo '<option value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->group->name.']'.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';
                        }

                    }
                    $sub_mark=$submark;

                }
                CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$ledgers_data);
            }
        }
    }

    ?>
    <?php
    $fiscal=\App\Models\Fiscalyear::where('fiscal_year',$fiscal_year)->first();
    ?>

    <?php
    $startOfYear = $startdate?$startdate:$fiscal->start_date;

    $endOfYear   = $enddate?$enddate:$fiscal->end_date;
    ?>
    <style type="text/css">
        #entry_list td,#entry_list th{


            font-size: 12px;

        }
    </style>
    <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>


    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Report > Ledger Statement</h3>
                @if($entry_items)
                    <a href="/admin/accounts/reports/ledger_statement?startdate={{$startdate??$fiscal->start_date}}&enddate={{$enddate??$fiscal->end_date}}&ledger_id={{$ledgers_data->id}}&currency={{\Request::get('currency')}}&fiscal_year={{\Request::get('fiscal_year')}}&excel=true"
                       class="btn btn-success btn-sm pull-right"><i class="fa fa-file-excel-o"></i>  Export to Excel</a>
                @endif
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- <div id="accordion"> -->
                <!-- <h3>Options</h3> -->

                <div class="balancesheet form">
                    <form  method="GET" action="/admin/accounts/reports/ledger_statement"
                           id='ledgerstatementfilterform'>

                        <div class="row" style="display: flex">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Ledger Account</label>
                                    <select class="form-control input-sm customer_id select2" required id="ReportLedgerId" name="ledger_id" aria-hidden="true">
                                        <option value="">Select</option>
                                        {{ CategoryTree($parent_id=null,$sub_mark='',$ledgers_data) }}
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <div class="input-group">
                                        <input id="ReportStartdate" type="text" name="startdate" class="form-control input-sm datepicker" value="{{$startOfYear}}">
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
                                        <input id="ReportEnddate" type="text" name="enddate" class="form-control input-sm datepicker" value="{{$endOfYear}}">
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
                            <div >
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
                                <!-- /.form group -->
                            </div>
                            {{--<div class="col-md-2">
                                <div class="form-group">
                                    <label>Currency</label>

                                    <div class="input-group">
                            <select class="form-control input-sm select2 currency" name="currency" >

                                @foreach($currency as $k => $v)
                                <option value="">Select Currency</option>
                                <option value="{{ $v->currency_code }}" @if(isset($v->currency_code) && $v->currency_code == $selected_currency) selected="selected"@endif>
                                    {{ $v->name }} {{ $v->currency_code }} ({{ $v->currency_symbol }})</option>
                                @endforeach

                            </select>

                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div> --}}

                            <div style="margin-left: 10px">
                                <label>
                                    <label></label>
                                </label>
                                <div class="form-group">

                                    <a type="reset" href="/admin/accounts/reports/ledger_statement?ledger_id={{$ledgers_data->id}}" class="btn btn-danger pull-right btn-sm" style="margin-left: 5px;" value="Clear">Clear</a>
                                    <input  type="submit" class="btn btn-primary pull-right btn-sm" value="Submit">
                                </div>
                            </div>
                        </div></form>
                </div>
                <!-- </div> -->
                <div class="subtitle">
                    <strong>[{{$ledgers_data->code??''}}] {{$ledgers_data->name??''}}</strong>
                    <br>
                    Transaction Date &nbsp;from <strong> {{date('d-M-Y', strtotime($startOfYear))}} </strong> to <strong>  {{ date('d-M-Y', strtotime($endOfYear))}} </strong>

                </div>
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-6">
                        <table class="summary table table-hover table-bordered">
                            <tbody>
                            <tr>
                                <td class="td-fixwidth-summary">Bank or cash account</td>
                                <td>
                                    <?php
                                    echo (isset($ledger_data) && $ledger_data->type == 1) ? 'Yes' : 'No';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-fixwidth-summary">Notes</td>
                                <td>{{$ledgers_data->notes??''}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="summary table table-hover table-bordered">
                            <tbody>
                            <tr>
                                <td class="td-fixwidth-summary">Opening balance as on <strong> {{date('d-M-Y', strtotime($startOfYear))}}</strong></td>
                                <td style="font-size:16px">
                                    @if($opening_balance['dc']=='D') Dr @else Cr @endif{{is_numeric($opening_balance['amount']) ?  number_format($opening_balance['amount'],2) : '-'}}</td>
                            </tr>
                              <?php
                             $closing_balance =TaskHelper::getFinalLedgerBalance($id,$opening_balance,$startdate,$enddate);

                              ?>

                              <tr>
                                  <td class="td-fixwidth-summary">Closing balance as on <strong>{{date('d-M-Y', strtotime($endOfYear))}}</strong></td>
                                  <td style="font-size:16px">{{$closing_balance['dc']=='D'?'Dr ':'Cr '}}{{number_format($closing_balance['amount'],2)}}</td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <table class="table table-hover table-bordered">

                    <tbody id='entry_list'>
                    <tr class="bg-orange">
                        <th>Date</th>
                        <th>Miti</th>
                        <th>Ref.</th>
                        <th>Bill No.</th>
                        <th>Description</th>
                        <th>Cheque No.</th>
                        <th>Type</th>
                        <th>Tag</th>
                        <th>(Dr)</th>
                        <th>(Cr)</th>
                        <th>Balance ({{ $selected_currency }})</th>
                        <th></th>
                    </tr>
                    <?php
                        $total_opening=\TaskHelper::calculate_withdc(is_numeric($opening_balance['amount'])?$opening_balance['amount']:0,$opening_balance['dc'],
                            $previous_closing['amount'], $previous_closing['dc'])
                    ?>
                    <tr class="tr-highlight">
                        <td colspan="8">Current opening balance</td>
                        <td style="font-weight: bold">@if($total_opening['dc']=='D') Dr {{is_numeric($total_opening['amount']) ?  number_format($total_opening['amount'],2) : '-'}}@else - @endif</td>
                        <td style="font-weight: bold">@if($total_opening['dc']=='C') Cr {{is_numeric($total_opening['amount']) ?  number_format($total_opening['amount'],2) : '-'}}@else - @endif</td>
                        <td></td>
                    </tr>

                    <?php
                    /* Current opening balance */
                    $entry_balance['amount'] = $total_opening['amount'] ?? '';
                    $entry_balance['dc'] = $total_opening['dc'] ??'';
                    $dr_total=0;
                    $cr_total=0;
                    ?>

                    @foreach($entry_items as $ei)
                        <?php

                        $entry_balance = TaskHelper::calculate_withdc($entry_balance['amount'], $entry_balance['dc'],
                            $ei['amount'], $ei['dc']);

                        $getledger= TaskHelper::getLedger($ei->entry_id);
                         $cr_total+=$ei->dc=='C'?$ei->amount:0;
                         $dr_total+=$ei->dc=='D'?$ei->amount:0;
                        ?>

                        <tr>
                            <td style="white-space: nowrap;">{{$ei->dynamicEntry()->date}}</td>
                            <td style="white-space: nowrap;">{{ TaskHelper::getNepaliDate($ei->dynamicEntry()->date) }}</td>
                            <td>{{   $ei->dynamicEntry()->number}}</td>

                                <?php
               
                                    if ($ei->source == 'AUTO_PURCHASE_ORDER') {
                                        $href = "/admin/purchase/". $ei->ref_id."?type=bills";
                      
                                    } elseif ($ei->source == 'TAX_INVOICE') {
                                        $href = "/admin/invoice1/". $ei->ref_id ;
                                  } else {
                                        $href = "/admin/orders/". $ei->ref_id ;
                                    }
                                  ?>
                            <td>
                                
                                <a href="{{ $href }}" target="_blank">{{ $ei->bill_no }}</a>
                            </td>

                            @php $getEntryType = $ei->dynamicEntry()->getDynamicEntryType();    @endphp
                            <td>

                                {{$getledger}} \ {{  $getEntryType['type']}} No. 

                                [{{ $getEntryType['order']->bill_no }}]


                            <div style="font-size: 14px;color:grey">{{$ei->narration}}</div>
                            </td>
                            <td>{{$ei->cheque_no??'-'}}</td>
                            <td>{{$ei->dynamicEntry()->entrytype->name??''}}</td>
                            <td>
					    		<span class="tag" style="color:#f51421;">
					    			<span style="color: #f51421;">
					    				{{$ei->dynamicEntry()->tagname->title}}
					    			</span>
					    	    </span>
                            </td>
                            @if($ei->dc=='D')
                                <td>{{$ei->currency}} {{$ei->amount}}</td>
                                <td>-</td>
                            @else
                                <td>-</td>
                                <td>{{$ei->currency}} {{$ei->amount}}</td>
                            @endif

                            <td>@if($entry_balance['dc']=='D') Dr @else Cr @endif
                                {{
                                is_numeric($entry_balance['amount']) ? number_format($entry_balance['amount'],2) : '-'  }}</td>

                            <td>
                                <a href="/admin/entries/show/{{$ei->entry->entrytype->label}}/{{$ei->entry_id}}" class="no-hover" escape="false" title='View'><i class="glyphicon glyphicon-log-in"></i></a>
                                <span class="link-pad"></span>
                                <a href="/admin/entries/edit/{{$ei->entry->entrytype->label}}/{{$ei->entry_id}}" class="no-hover" escape="false" title="Edit"><i class="fa fa-edit"></i></a>
                                <span class="link-pad"></span>
                                <a href="/admin/entries/{{$ei->entry_id}}/confirm-delete" class="no-hover"  data-toggle="modal" data-target="#modal_dialog" escape="false" title="Delete"><i class="fa fa-trash deletable"></i></a>
                            </td>

                        </tr>

                    @endforeach

                    <tr class="tr-highlight">
                        <td colspan="8" class="text-right" style="font-weight: 600;">Total</td>
                        <td style="font-size: 14px;font-weight: 600;">
                            {{ number_format($dr_total,2) }}
                        </td>
                        <td style="font-size: 14px;font-weight: 600;">
                            {{ number_format($cr_total,2) }}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="tr-highlight">
                        <td colspan="10">Current closing balance</td>
                        <td style="font-size: 14px;font-weight: 600;">@if($entry_balance['dc']=='D') Dr @else Cr @endif
                            {{ is_numeric($entry_balance['amount']) ?  number_format($entry_balance['amount'],2) : '-' }}

                        </td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
                <div align="align">{!! $entry_items->appends(\Request::except('page'))->render() !!}</div>
                <br>



{{--                <a href="{{route('admin.chartofaccounts.pdf',$requestData)}}" class="btn btn-primary">PDF</a>--}}

{{--                <a href="{{route('admin.chartofaccounts.print',$requestData)}}" class="btn btn-primary">Print</a>--}}

            </div>


    <script type="text/javascript">
        $(function() {
            $('.datepicker').datetimepicker({
                //inline: true,
                format: 'YYYY-MM-DD',
                sideBySide: true,
                allowInputToggle: true
            });

        });
    </script>

    <script type="text/javascript">
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


        function downloadfile(type){

            let data = $('#ledgerstatementfilterform').serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            let startdate = data.startdate;

            let enddate = data.enddate;

            location.href = `/admin/chartofaccounts/${type}/{{$ledgers_data->id??''}}?startdate=${startdate}&enddate=${enddate}`;

        }


    </script>


@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')


@endsection
