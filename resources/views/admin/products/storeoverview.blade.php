@extends('layouts.master')

@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? "Page Description" }}
        </small>
    </h1>

</section>

<?php
$url = \Request::query();
if($url){
    $url = \Request::getRequestUri() .'&';
}
else{
    $url = \Request::getRequestUri() .'?';
}
?>

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->
        <div class="box box-primary">

            <div class="box-header with-border">

                Current Fiscal Year: {{\App\Models\Fiscalyear::where('current_year',1)->first()->fiscal_year}}
                <div class="col-md-12" style="margin-top:5px;">
                    <div class="filter form-inline" style="margin:0 30px 0 0;">
                        {!! Form::text('start_date', \Request::get('start_date'), ['style' => 'width:120px;', 'class' => 'form-control input-sm', 'id'=>'start_date', 'placeholder'=>'Start Date']) !!}&nbsp;&nbsp;

                        {!! Form::text('end_date', \Request::get('end_date'), ['style' => 'width:120px; display:inline-block;', 'class' => 'form-control input-sm', 'id'=>'end_date', 'placeholder'=>'End Date']) !!}&nbsp;&nbsp;

                        {!! Form::select('fiscal_year',$allFiscalYear,$fiscal_year,['id'=>'fiscal_year_id', 'class'=>'form-control searchable input-sm', 'style'=>'width:150px; display:inline-block;'])  !!}

                        {!! Form::select('outlet_id', ['' => 'Select Outlets'] + $outlets , \Request::get('outlet_id'), ['id'=>'outlet_id', 'class'=>'form-control searchable input-sm', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;


                        {!! Form::select('category_id', ['' => 'Select Category'] + $categories , \Request::get('category_id'), ['id'=>'category_id', 'class'=>'form-control searchable input-sm', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                        {{-- <div class="col-md-12 form-group">
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">--SELECT--</option>
                                @if(isset($catgories) && $catgories)
                                @foreach($catgories as $key=> $value)
                                <option value="{{ $key }}" {{ \Request::get('category_id') != null && \Request::get('category_id') == $key ? 'selected' : ''  }} >{{ $value }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div> --}}


                        <span class="btn btn-primary btn-sm" id="btn-submit-filter">
                            <i class="fa fa-list"></i> Filter
                        </span>

                        <span class="btn btn-danger btn-sm" id="btn-filter-clear">
                            <i class="fa fa-close"></i> Reset
                        </span>

                    </div>
                </div>

                <div class="col-md-12" style="margin-top:5px;">

                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" placeholder="सुरु  मिति " class="form-control input-sm" id="start_date_nep" name="start_date_nep" value="{{\Request::get('start_date_nep')}}" data-single='true'>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" placeholder="अन्तिम मिति" class="form-control input-sm" id="end_date_nep" name="end_date_nep" value="{{\Request::get('end_date_nep')}}" data-single='true'>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm" id="btn-submit-filter-nep"> <i class="fa fa-list"></i> नतिजा </button>
                    <a href="/admin/product/stocks_overview" class="btn btn-success btn-sm"> Reset</a>
                </div>
                @if(Request::get('search'))
                {{-- <a href="{{$url}}op=print" class="btn btn-default float-right" target="_blank">Print</a>
                <a href="{{$url}}op=pdf" class="btn btn-default float-right">PDF</a> --}}
                <a href="{{$url}}op=excel" class="btn btn-default float-right">Excel</a>
                @endif

            </div>
            @if(Request::get('search'))
            <div class="box-body">

                <span id="index_lead_ajax_status"></span>

                <div class="table-responsive ">

                @include('admin.products.storeoverviewtable')
                </div> <!-- table-responsive -->

            </div><!-- /.box-body -->
            @endif

        </div><!-- /.box -->
        <input type="hidden" name="order_type" id="order_type" value="{{\Request::get('type')}}">

    </div><!-- /.col -->

</div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkClient[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }

</script>


<script type="text/javascript">
    $(document).on('change', '#order_status', function() {

        var id = $(this).closest('tr').find('.index_sale_id').val();
        var purchase_status = $(this).val();
        $.post("/admin/ajax_order_status", {
            id: id
            , purchase_status: purchase_status
            , _token: $('meta[name="csrf-token"]').attr('content')
        }
        , function(data, status) {
            if (data.status == '1')
                $("#index_lead_ajax_status").after("<span style='color:green;' id='index_status_update'>Status is successfully updated.</span>");
            else
                $("#index_lead_ajax_status").after("<span style='color:red;' id='index_status_update'>Problem in updating status; Please try again.</span>");

            $('#index_status_update').delay(3000).fadeOut('slow');
                //alert("Data: " + data + "\nStatus: " + status);
            });

    });

</script>
<script type="text/javascript">
    $("#btn-submit-filter").on("click", function() {

        outlet_id = $("#outlet_id").val();
        category_id = $("#category_id").val();
        start_date = $("#start_date").val();
        end_date = $("#end_date").val();
        user_id = $("#user_id").val();
        fiscal_year = $("#fiscal_year_id").val();
        window.location.href = "{!! url('/') !!}/admin/product/stocks_overview?start_date=" + start_date + "&end_date=" + end_date + "&outlet_id=" + outlet_id+'&search=true&'
        +  "&fiscal_year=" + fiscal_year + "&category_id=" + category_id;

    });

    $("#btn-submit-filter-nep").on("click", function() {

        outlet_id = $("#outlet_id").val();
        category_id = $("#category_id").val();
        start_date = $("#start_date_nep").val();
        end_date = $("#end_date_nep").val();
        user_id = $("#user_id").val();
        fiscal_year = $("#fiscal_year_id").val();
        window.location.href = "{!! url('/') !!}/admin/product/stocks_overview?start_date_nep=" + start_date + "&end_date_nep=" + end_date + "&outlet_id=" + outlet_id+'&search=true&'
         + "&fiscal_year=" + fiscal_year + "&category_id=" + category_id;

    });


    $("#btn-filter-clear").on("click", function() {

        window.location.href = "{!! url('/') !!}/admin/product/stocks_overview";
    });

</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.customer_id').select2();
    });

</script>

<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/nepali-date-picker/nepali-date-picker.min.css">
<script src="/nepali-date-picker/nepali-date-picker.js"></script>
<script type="text/javascript">
    $(function() {
        $('#date1').datepicker({
            //inline: true,
            //format: 'YYYY-MM-DD',
            dateFormat: 'yy-m-d'
            , sideBySide: true
            , beforeShow: function() {
                setTimeout(function() {
                    $('.ui-datepicker').css('z-index', 99999999999999);
                }, 0);
            }
        });
    });

</script>


<script>
    $(function() {
        $('#start_date').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD'
            , sideBySide: true
        });
        $('#end_date').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD'
            , sideBySide: true
        });
    });
    $("#start_date_nep").nepaliDatePicker();
    $("#end_date_nep").nepaliDatePicker();

</script>


<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('.searchable').select2();

    });

</script>


@endsection
