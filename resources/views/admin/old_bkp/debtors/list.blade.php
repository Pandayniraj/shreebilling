@extends('layouts.master')

<!-- jVectorMap 1.2.2 -->
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.css") }}" rel="stylesheet" type="text/css" />
<style>
    .filter_date {
        font-size: 14px;
    }
</style>
@section('content')
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet"/>
<?php
$fiscal=\App\Models\Fiscalyear::where('fiscal_year',$fiscal_year)->first();
?>

<?php
$startOfYear = $startdate?$startdate:$fiscal->start_date;

$endOfYear   = $enddate?$enddate:$fiscal->end_date;
?>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        Debtors List
    </h1>
</section>
<div class="box box-primary">
    <div class="box-header with-border">
        <div class='row'>
            <div class='col-md-12 d-flex'>
                <div class="wrap" style="margin-top:5px;">
                    <form method="get" action="/admin/debtors_lists">
                        <div class="filter form-inline" style="margin:0 30px 0 0;">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Select Debtor</label>
                                    <br>
                                    <select class="form-control input-sm select2" style="width: 260px;"
                                    name="ledger_id">
                                    <option value="">Select Debtor</option>
                                    @foreach($all_debtors as $key=>$deb)
                                    <option value="{{$deb['id']}}"
                                    @if(Request::get('ledger_id') == $deb['id']) selected="" @endif>{{$deb['name']}} </option>
                                    @endforeach
                                </select>
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="">Fiscal Year</label>
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
                            <div class="col-md-3">
                                <label></label> <div class="form-group"
                                style="margin-top: 24px;"> <button
                                class="btn btn-primary btn-sm"
                                id="btn-submit-filter" type="submit"> <i
                                class="fa fa-list"></i> Filter </button>
                                <a href="/admin/debtors_lists" class="btn
                                btn-danger btn-sm" id="btn-filter-clear">
                                <i class="fa fa-close"></i> Clear </a>
                                <button class="btn btn-success btn-sm"
                                id="btn-submit-export" type="submit"
                                name="export" value="true"> <i class="fa
                                fa-file-excel-o"></i> Export To Excel
                            </button> </div> </div> </div> </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="debtors_list-tabs" style="position: relative;">
                <div class="nav-tabs-custom">
                    <!-- Tabs within a box -->

                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="{{route('admin.debtors_lists')}}" aria-expanded="true">List View</a>
                        </li>
                        <li class="">
                            <a href="{{route('admin.debtors_lists.ageing')}}" aria-expanded="false">Ageing View
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content bg-white">

                        <div class="tab-pane active" id="my_debtors_list">
                            <div>

                                <span id="index_lead_ajax_status"></span>

                                <div>
                                  <table class="table table-hover table-striped table-responsive" id="clients-table">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th style="width: 5%;text-align: center;">#.</th>
                                            <th style="width: 12%;">Code</th>
                                            <th>Customer</th>
                                            <th style="width: 10%; text-align: center">Opening B/c(DR)</th>
                                            <th style="width: 10%; text-align: center">Opening B/c(CR)</th>
                                            <th style="width: 10%; text-align: center">Debit Amt</th>
                                            <th style="width: 10%; text-align: center">Credit Amt</th>
                                            <th style="width: 10%; text-align: center">Closing B/c</th>
                                            @if($current_fiscal->fiscal_year==$fiscal_year)
                                            <th style="width: 10%;text-align: center">Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($debtorsLists as $key=>$entries)

                                        <tr>
                                            <td style="text-align: center">{{ $key+1  }}.</td>
                                            <td style="text-align: center">{{ $entries['code']  }}</td>
                                            <td style="font-size: 16.5px">
                                                <a href="/admin/accounts/reports/ledger_statement?ledger_id={{ $entries['id'] }}"
                                                target="_blank">    {{ $entries['name'] }}</a>
                                            </td>
                                            <td style="text-align: center;font-size: 16.5px">
                                            {{$entries['opening_blc_dc']=='D'?number_format($entries['opening_blc'],2):0 }}</td>
                                            <td style="text-align: center;font-size: 16.5px">
                                            {{$entries['opening_blc_dc']=='C'?number_format($entries['opening_blc'],2):0 }}</td>
                                            <td style="text-align: center;font-size: 16.5px">
                                            {{ number_format($entries['dr_amount'],2) }}</td>
                                            <td style="text-align: center;font-size: 16.5px">
                                            {{ number_format($entries['cr_amount'],2) }}</td>
                                            <td style="text-align: center;font-size: 16.5px">
                                            {{ number_format($entries['amount'],2) }}</td>
                                            @if($current_fiscal->fiscal_year==$fiscal_year)
                                            <td style="text-align: center">

                                                <a href="/admin/debtors_pay/{{ $entries['id'] }}"
                                                class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#modal_dialog">Receive </a>
                                            </td>
                                            @endif
                                        </tr>
                                        @php $totalSum +=  $entries['amount']; @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-right" colspan="3" style="font-size: 20.5px">Total</td>
                                            <td colspan="5" class="text-right" style="font-size: 20.5px;">{{ env('APP_CURRENCY') }} {{ number_format($totalSum,2) }} </td>
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

<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/extra/dataTables.buttons.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/extra/jszip.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/extra/pdfmake.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/extra/vfs_fonts.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/extra/buttons.html5.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/extra/print.min.js") }}"></script>

<script>
    $('.datepicker').datetimepicker({
        format: 'YYYY-MM-DD',
        sideBySide: true
    });
    $('.select2').select2();

    $(document).ready(function() {
        $("#example").DataTable();
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
<script>
    $(function() {
        $('#clients-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
            'csv', 'excel', 'pdf', 'print'
            ],
            'pageLength'  : 65,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : true,
            "paging"      : false
        });
    });

</script>
@endsection
