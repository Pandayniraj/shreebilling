@extends('layouts.master')

<!-- jVectorMap 1.2.2 -->
<link href="{{ asset("/bower_components/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css") }}" rel="stylesheet"
      type="text/css"/>
<style>
    .filter_date {
        font-size: 14px;
    }

    .table-bordered > thead > tr > th, .table-bordered > thead > tr > td {
        font-size: 14px;
    }
</style>
@section('content')
    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
    <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet"/>

    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        <h1>
            Creditors List
        </h1>
    </section>
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class='row'>
                <div class='col-md-12 d-flex'>
                    <div class="wrap" style="margin-top:5px;">
                        <form method="get" action="/admin/creditors_lists/ageing">
                            <div class="filter form-inline" style="margin:0 30px 0 0;">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="">Select Creditor</label>
                                        <br>
                                        <select class="form-control input-sm select2" style="width: 260px;"
                                                name="ledger_id">
                                            <option value="">Select Creditor</option>
                                            @foreach($all_creditors as $key=>$cred)
                                                <option value="{{$cred['id']}}"
                                                        @if(Request::get('ledger_id') == $cred['id']) selected="" @endif>{{$cred['name']}} </option>
                                            @endforeach
                                        </select>
                                    </div>
{{--                                    <div class="col-md-2">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label>Start Date</label>--}}
{{--                                            <div class="input-group">--}}
{{--                                                <input id="ReportStartdate" type="text" name="startdate" class="form-control input-sm datepicker" value="{{\Request::get('startdate')}}">--}}
{{--                                                <div class="input-group-addon">--}}
{{--                                                    <i>--}}
{{--                                                        <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : Leave start date as empty if you want statement from the start of the financial year.">--}}
{{--                                                        </div>--}}
{{--                                                    </i>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <!-- /.input group -->--}}
{{--                                        </div>--}}
{{--                                        <!-- /.form group -->--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-2">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label>End Date</label>--}}

{{--                                            <div class="input-group">--}}
{{--                                                <input id="ReportEnddate" type="text" name="enddate" class="form-control input-sm datepicker" value="{{\Request::get('enddate')}}">--}}
{{--                                                <div class="input-group-addon">--}}
{{--                                                    <i>--}}
{{--                                                        <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : Leave end date as empty if you want statement till the end of the financial year.">--}}
{{--                                                        </div>--}}
{{--                                                    </i>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <!-- /.input group -->--}}
{{--                                        </div>--}}
{{--                                        <!-- /.form group -->--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-2" style="margin-left: 5px;">--}}
{{--                                        <label for="">Select Standing Date</label>--}}
{{--                                        <br>--}}
{{--                                        {!! Form::text('date', \Request::get('date'), ['style' => 'width:170px;', 'class' => 'form-control input-sm  datepicker', 'id'=>'start_date', 'placeholder'=>'Select Standing Date','autocomplete' =>'off']) !!}--}}
{{--                                        &nbsp;&nbsp;--}}

{{--                                    </div>--}}
                                    <div class="col-md-2">
                                        <label for="">Fiscal Year</label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                {!! Form::select('fiscal_year',$allFiscalYear,$fiscal_year,['id'=>'fiscal_year_id', 'class'=>'form-control searchable input-sm', 'style'=>'width:150px; display:inline-block;'])  !!}

                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                        <!-- /.form group -->
                                    </div>
                                    <div class="col-md-3">
                                        <label></label>
                                        <div class="form-group" style="margin-top: 24px;">
                                            <button class="btn btn-primary btn-sm" id="btn-submit-filter" type="submit">
                                                <i class="fa fa-list"></i> Filter
                                            </button>
                                            <a href="/admin/creditors_lists/ageing" class="btn btn-danger btn-sm"
                                               id="btn-filter-clear">
                                                <i class="fa fa-close"></i> Clear
                                            </a>
                                            <a href="/admin/creditors_lists/ageing?ledger_id={{Request::get('ledger_id')}}&date={{Request::get('date')}}&export=true&fiscal_year={{Request::get('fiscal_year')}}"
                                               class="btn btn-success btn-sm" id="btn-filter-clear">
                                                <i class="fa fa-file-export"></i> Export To Excel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="creditors_list-tabs" style="position: relative;">
                <div class="nav-tabs-custom">
                    <!-- Tabs within a box -->

                    <ul class="nav nav-tabs">
                        <li>
                            <a href="{{route('admin.creditors_lists')}}" aria-expanded="true">List View</a>
                        </li>
                        <li class="active">
                            <a href="{{route('admin.creditors_lists.ageing')}}" aria-expanded="false">Ageing View
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content bg-white">

                        <div class="tab-pane active" id="my_requisition">
                            <div>

                                <span id="index_lead_ajax_status"></span>

                                <div>
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                        <tr class="bg-primary">
                                            <th style="width: 9%;text-align: center">Customer Code</th>
                                            <th style="width: 22%;">Name</th>
                                            <th style="width: 10%;text-align: center">Total ({{ env('APP_CURRENCY') }}
                                                .)
                                            </th>
                                            <th style="width: 10%;text-align: center">Past 0-30 days
                                                ({{ env('APP_CURRENCY') }}.)
                                            </th>
                                            <th style="width: 10%;text-align: center">Past 31-60 days
                                                ({{ env('APP_CURRENCY') }}.)
                                            </th>
                                            <th style="width: 10%;text-align: center">Past 61-90 days
                                                ({{ env('APP_CURRENCY') }}.)
                                            </th>
                                            <th style="width: 10%;text-align: center">Past 91-180<br>days
                                                ({{ env('APP_CURRENCY') }}.)
                                            </th>
                                            <th style="width: 11%;text-align: center">Past 181-365
                                                days({{ env('APP_CURRENCY') }}.)
                                            </th>
                                            <th style="width: 10%;text-align: center">Past 1 year plus
                                                ({{ env('APP_CURRENCY') }}.)
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $totalSum = 0;
                                        $totalSum1_30 = 0;
                                        $totalSum31_60 = 0;
                                        $totalSum61_90 = 0;
                                        $totalSum91_180 = 0;
                                        $totalSum181_365 = 0;
                                        $totalSum1yearplus = 0;
                                        ?>
                                        @foreach($creditorsLists as $key=>$entries)

                                            <tr>
                                                <td style="text-align: center">{{ $entries['code']  }}</td>
                                                <td>
                                                    <a href="/admin/accounts/reports/ledger_statement?ledger_id={{ $entries['id'] }}"
                                                       target="_blank">
                                                        {{ $entries['name']  }}</a></td>
                                                <td style="text-align: center">{{ number_format($entries['total'],2)  }}</td>
                                                <td style="text-align: center">{{ number_format($entries['1-30'],2)  }}</td>
                                                <td style="text-align: center">{{ number_format($entries['31-60'],2)  }}</td>
                                                <td style="text-align: center">{{ number_format($entries['61-90'],2)  }}</td>
                                                <td style="text-align: center">{{ number_format($entries['91-180'],2)  }}</td>
                                                <td style="text-align: center">{{ number_format($entries['181-365'],2)  }}</td>
                                                <td style="text-align: center">{{ number_format($entries['1 year +'],2)  }}</td>

                                            @php
                                                $totalSum +=  $entries['total'];
                                                $totalSum1_30+=$entries['1-30'];
                                                $totalSum31_60+=$entries['31-60'];
                                                $totalSum61_90+=$entries['61-90'];
                                                $totalSum91_180+=$entries['91-180'];
                                                $totalSum181_365+=$entries['181-365'];
                                                $totalSum1yearplus+=$entries['1 year +'];

                                            @endphp
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="2" class="text-right" style="font-weight: bold;font-size: 20.5px">Total</td>
                                            <td style="font-weight: bold">{{ number_format($totalSum,2) }} </td>
                                            <td style="font-weight: bold">{{ number_format($totalSum1_30,2) }} </td>
                                            <td style="font-weight: bold">{{ number_format($totalSum31_60,2) }} </td>
                                            <td style="font-weight: bold">{{ number_format($totalSum61_90,2) }} </td>
                                            <td style="font-weight: bold">{{ number_format($totalSum91_180,2) }} </td>
                                            <td style="font-weight: bold">{{ number_format($totalSum181_365,2) }} </td>
                                            <td style="font-weight: bold">{{ number_format($totalSum1yearplus,2) }} </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        $('.datepicker').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD',
            sideBySide: true
        });
        $('.select2').select2();
    </script>
@endsection
