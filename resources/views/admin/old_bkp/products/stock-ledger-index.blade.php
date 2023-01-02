@extends('layouts.master')
@section('content')

<style>
    .required { color: red; }
    .panel-custom .panel-heading {
        border-bottom: 2px solid #1797be;
    }
    .panel-custom .panel-heading {
        margin-bottom: 10px;
    }
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{$page_title ?? 'Page Title'}}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">

            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/product/stock-ledger/view" method="GET" class="form-horizontal form-groups-bordered">

                    <div class="row">

                        <div class="col-sm-3">
                          <label class="control-label">Select Product</label>
                            {!! Form::select('product_id', $products, $current_product, ['class'=>'form-control searchable','placeholder'=>'Select Product','required'=>'required']) !!}
                        </div>

                        <div class="col-sm-3">
                            <label class="control-label">Start date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ Request::get('start_date') }}">
                        </div>

                        <div class="col-sm-3">
                            <label class="control-label">End date</label>
                            <input type="date" name="end_date" class="form-control"  value="{{ Request::get('end_date') }}" >
                        </div>

                    </div>
                    <br>

                    <div class="row">
                        <div class="col-sm-4 ">
                            <button type="submit" id="sbtn" class="btn btn-primary btn-sm no-loading">Search</button>

{{--                              <button type='submit' name="submit" value="excel" class="btn btn-success btn-sm no-loading">Excel</button>--}}
                              <a href="/admin/product/stock-ledger" class="btn btn-danger btn-sm">Clear</a>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <strong> List of Stocks </strong>

                    </h3>
                </div>
{{--                @include('admin.products.product-stock-ledger')--}}
{{--                <div align="center">{!! $transations->render() !!}</div>--}}

                <table id="" class="table table-bordered std_table table-striped" style="width:100%;">
                    <thead>
                    <tr style="background: #3c8dbc;color: #FFFFFF;">
                        {{--        <th class="text-center" rowspan="2">Ord Num.</th>--}}
                        <th class="text-center" style="width: 10%;" rowspan="2">S.No</th>

                        <th class="text-center" style="width: 20%;" rowspan="2">Product</th>
{{--                        <th class="text-center" style="width: 10%;" rowspan="2">Bill No.</th>--}}

                        {{--        <th class="text-center" rowspan="2">Ref No.#</th>--}}
{{--                        <th class="text-center" style="width: 15%;" rowspan="2">Tran Type</th>--}}
                        {{--        <th class="text-center" rowspan="2">Date</th>--}}
                        {{--        <th class="text-center" rowspan="2">Location</th>--}}
                        <th class="text-center" style="width: 15%;" colspan="3">Opening</th>
                        <th class="text-center" style="width: 15%;" colspan="3">Inwards</th>
                        <th class="text-center" style="width: 15%;" colspan="3">Outwards</th>
                        <th class="text-center" style="width: 15%;" colspan="3"><i class="fa fa- fa-hand-paper-o"></i> Closing</th>
                    </tr>
                    <tr style="background: #3c8dbc;color: #FFFFFF;">
                        <td>Qty</td>
                        <td>Rate</td>
                        <td>Value</td>
                        <td>Qty</td>
                        <td>Rate</td>
                        <td>Value</td>
                        <td>Qty</td>
                        <td>Rate</td>
                        <td>Value</td>
                        <td>Qty</td>
                        <td>Rate</td>
                        <td>Value</td>
                    </tr>
                    </thead>
                    <tbody>

                    @if(count($transations)>0)
                        @foreach($transations as $key=>$result)
                            <?php
                            $closing_qty=0;
                            $closing_price=0;
                            ?>
                            <tr>
                                <td align="center">
                                    {{($key+1+((\Request::get('page')?\Request::get('page'):1)-1)*30)}}.
                                </td>
                                <td >
                                    <a href="/admin/product/stock-ledger/view?product_id={{$result->product_id}}">
                                   {{$result->name}}
                                    </a>
                                </td>
                                <td align="center">
                                   {{$result->op_qty}}
                                </td> <td align="center">
                                   {{number_format($result->op_price,2)}}
                                </td></td> <td align="center">
                                   {{number_format(abs($result->op_qty*$result->op_price),2)}}
                                </td>
                                <td align="center">
                                   {{$result->in_qty}}
                                </td> <td align="center">
                                   {{number_format($result->in_price,2)}}
                                </td></td> <td align="center">
                                   {{number_format(abs($result->in_qty*$result->in_price),2)}}
                                </td>
                                <td align="center">
                                   {{$result->out_qty}}
                                </td> <td align="center">
                                   {{number_format($result->out_price,2)}}
                                </td></td> <td align="center">
                                   {{number_format(abs($result->out_qty*$result->out_price),2)}}
                                </td>
                                <?php
                                $closing_qty=$result->op_qty+$result->in_qty+$result->out_qty;
                                $closing_price=($result->op_price+$result->in_price+$result->out_price)/$closing_qty;
                                ?>
                                <td align="center">
                                   {{$closing_qty}}
                                </td> <td align="center">
                                   {{number_format($closing_price,2)}}
                                </td></td> <td align="center">
                                   {{number_format(abs($closing_qty*$closing_price),2)}}
                                </td>

                            </tr>
                        @endforeach
{{--                        <tr class="bg-gray" style="font-weight: bold;">--}}
{{--                            <td colspan="4" align="right">Total</td>--}}
{{--                            <td align="center">{{number_format($StockIn,2)}}</td>--}}
{{--                            <td align="center">{{number_format($StockInAmount/$StockIn,2)}}</td>--}}
{{--                            <td align="center">{{number_format($StockInAmount,2)}}</td>--}}
{{--                            <td align="center">{{number_format(abs($StockOut),2)}}</td>--}}
{{--                            <td align="center">{{number_format($StockOutAmount/$StockOut,2)}}</td>--}}
{{--                            <td align="center">{{number_format($StockOutAmount,2)}}</td>--}}
{{--                            <td align="center">{{number_format($StockIn+$StockOut,2)}}</td>--}}
{{--                            <td align="center">{{number_format(($StockInAmount+$StockOutAmount)/($StockIn+$StockOut),2)}}</td>--}}
{{--                            <td align="center">{{number_format($StockInAmount+$StockOutAmount,2)}}</td>--}}
{{--                        </tr>--}}
                    @else
                        <tr>
                            <td colspan="9" class="text-center text-danger">No Transaction Yet</td>
                        </tr>
                    @endif

                    </tbody>
                </table>
                <div class="center">

                    {!!  $transations?$transations->appends(\Request::except('page'))->render():'' !!}

                </div>
            </div>
        </div>
    </div>
</div>


@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>


<!-- SELECT2-->
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>

<script type="text/javascript">
    $('.searchable').select2();
    $(function() {
        $('#date_in').datetimepicker({
            format: 'YYYY-MM',
            sideBySide: true
        });

        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
