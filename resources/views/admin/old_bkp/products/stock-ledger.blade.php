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
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/product/stock-ledger" method="GET" class="form-horizontal form-groups-bordered">
                  
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
                @include('admin.products.product-stock-ledger')
                <div align="center">{!! $transations->render() !!}</div>
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
