@extends('layouts.master')

@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')


@php

$isQuot = \Request::get('type') == 'quotation' ? true : false;
$isInvoice = \Request::get('type') == 'invoice' ? true : false;

@endphp
<style>
    .panel .mce-panel {
        border-left-color: #fff;
        border-right-color: #fff;
    }

    .panel .mce-toolbar,
    .panel .mce-statusbar {
        padding-left: 20px;
    }

    .panel .mce-edit-area,
    .panel .mce-edit-area iframe,
    .panel .mce-edit-area iframe html {
        padding: 0 10px;
        min-height: 350px;
    }

    .mce-content-body {
        color: #555;
        font-size: 14px;
    }

    .panel.is-fullscreen .mce-statusbar {
        position: absolute;
        bottom: 0;
        width: 100%;
        z-index: 200000;
    }

    .panel.is-fullscreen .mce-tinymce {
        height: 100%;
    }

    .panel.is-fullscreen .mce-edit-area,
    .panel.is-fullscreen .mce-edit-area iframe,
    .panel.is-fullscreen .mce-edit-area iframe html {
        height: 100%;
        position: absolute;
        width: 99%;
        overflow-y: scroll;
        overflow-x: hidden;
        min-height: 100%;
    }

    .footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        background-color: #efefef;
        color: white;
        text-align: center;
    }
    input.form-control{
        min-width: 55px !important;
    }
    select{
        min-width: 80px !important;

    }
    .p_sn{
        max-width: 3px !important;
    }
    @media only screen and (max-width: 770px) {
        input.total{
            width: 80px !important;
        }
        .fa-trash{
            float: left !important;
            margin-left: 5px;
        }
    }
</style>
@endsection

@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        New Sales {{ $_GET['type']}}
        <small id="ajax_status"></small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box box-header">
            <div id="orderFields" style="display: none;">
                <table class="table">
                    <tbody id="more-tr">
                        <tr>
                            <td class="p_sn"></td>
                            <td>

                                <select class="form-control input-sm product_id hiddensearchable" name="product_id[]" required="required">
                                    <option value="">Select Product</option>
                                    @foreach($products as $key => $pk)
                                    <option value="{{ $pk->id }}" @if(isset($orderDetail->product_id) && $orderDetail->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" class="description form-control input-sm" readonly="">
                            </td>

                            <td>
                                <input type="number" class="form-control input-sm quantity" name="quantity[]" placeholder="Quantity" step=".01" required="required" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" class="form-control input-sm price" name="price[]" placeholder="Fare" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required" autocomplete="off">
                            </td>
                            <td>
                                <select class="form-control input-sm units" name="unit[]">
                                    <option value="">Unit</option>
                                    @foreach($units as $unit)
                                    <option value="{{$unit->id}}">{{$unit->symbol}}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="number" class="form-control input-sm total" name="total[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly" style="float:left; width:80%;">
                                <a href="javascript::void(1);" style="width: 10%;">
                                    <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="CustomOrderFields" style="display: none;">
                <table class="table">
                    <tbody id="more-custom-tr">
                        <tr>
                           <td class="p_sn"></td>
                           <td>
                            <input type="text" class="form-control input-sm product" name="custom_items_name[]" value="" placeholder="Product" autocomplete="off">
                        </td>
                        <td>
                            <input type="text" class="description form-control" readonly="">
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm quantity" name="custom_items_qty[]" placeholder="Quantity" step=".01" required="required" autocomplete="off">
                        </td>

                        <td>
                            <input type="text" class="form-control input-sm price" name="custom_items_price[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required" autocomplete="off">
                        </td>


                        <td>
                            <select class="form-control input-sm" name="custom_unit[]">
                                <option value="">Select Unit</option>
                                @foreach($units as $unit)
                                <option value="{{$unit->id}}">{{$unit->name}}</option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <input type="number" class="form-control input-sm total" name="custom_total[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly" style="float:left; width:80%;">
                            <a href="javascript::void(1);" style="width: 10%;">
                                <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="termFields" style="display: none;">
            <table class="table">
                <tbody id="more-tr">
                    <tr>
                        <td class="p_sn"></td>

                        <td>
                            <input type="date" class="form-control input-sm " name="term_date[]">
                        </td>

                        <td>
                            <input type="text" class="form-control input-sm " name="term_condition[]" placeholder="Term Condition" autocomplete="off">
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" name="term_amount[]" placeholder="Amount" autocomplete="off" step="any" style="float:left; width:80%;">
                            <a href="javascript::void(1);" style="width: 10%;">
                                <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="col-md-12">
            <div class="">
                <form method="POST" action="/admin/multiple_orders">
                    {{ csrf_field() }}

                    <div class="">

                        @if(\Request::get('type') == 'invoice')
                        <div class="col-md-12">
                            <div class="callout bg-info">
                                <label id='select_label'>Select Clients <i class="imp">*</i></label>
                                <select class="customer_id select2" name="customer_id" required="required" id="customers_id">

                                    <option value="">Select Clients</option>
                                    @foreach($customer_clients as $key=>$cl)
                                    <option value="{{ $cl->id }}">{{ '('.env('APP_CODE'). $cl->id.')'  }} {{ $cl->name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="source" value='client'>
                                <span id='create_lead_or_clients' >
                                    <a href="#" onClick="openwindow('client')" style="color: black;"><i class="fa fa-plus"></i> New Customer</a>
                                </span>
                            </div>
                        </div>
                        @else

                        <div class="col-md-12">
                            <div class="callout bg-info">

                                <label>Select Source <i class="imp">*</i></label>
                                Lead <input type="radio" checked="checked" name="source" value="lead">
                                Client <input type="radio" name="source" value="client">


                                &nbsp; &nbsp;

                                <label id='select_label'>Select Leads <i class="imp">*</i></label>

                                <select class="customer_id select2" name="customer_id" required="required" id="customers_id">
                                    <option value="">Select Leads</option>
                                    @if(isset($lead_clients))
                                    @foreach($lead_clients as $key => $uk)
                                    <option value="{{ $uk->id }}" @if($orderDetail && $uk->id ==
                                        $orderDetail->customer_id){{ 'selected="selected"' }}@endif>{{ '('.env('APP_CODE'). $uk->id.') '.$uk->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>

                                    &nbsp;&nbsp;

                                    <span id='create_lead_or_clients'>
                                        <a href="#" onClick="openwindow('lead')"  style="color: black;"><i class="fa fa-plus"></i> New Lead</a>
                                    </span>
                                    To send tax invoice <a href="/admin/invoice1" target="_blank"  style="color: black;">Goto Invoice</a>

                                </div>
                            </div>
                            @endif

                            <div class="clearfix"></div>
                            <div class="col-md-12">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('bill_no', 'Bill Number.') !!}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clone"></i>
                                            </div>
                                            {!! Form::number('bill_no',$bill_no, ['class' => 'form-control input-sm','min'=>1,'readonly'=>'readonly']) !!}
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                       {!! Form::label('terms', 'Payment Terms') !!}
                                       <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        {!! Form::select('terms',
                                        [
                                        'custom' => 'Custom','net15'=>'Net 15', 'net30'=>'Net 30', 'net45'=>'Net 45','net60'=>'Net 60'],
                                        null, ['class' => 'form-control input-sm input-sm']) !!}
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>






                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Bill Date:</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control input-sm input-sm pull-right datepicker date-toggle-nep-eng" name="bill_date" value="{{\Carbon\Carbon::now()->toDateString()}}" id="bill_date">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>



                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Due Date:</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-plus-o"></i>
                                        </div>
                                        <input type="text" class="form-control input-sm pull-right datepicker date-toggle-nep-eng" name="due_date" value="{{\Carbon\Carbon::now()->addDays(14)->toDateString()}}" id="due_date" >
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                        </div>


                        <div class="col-md-12">
                           <div class="col-md-3">
                            <div class="form-group">
                                <label>PAN Number:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-file"></i>
                                    </div>
                                    <input type="text" name="customer_pan" value="{{ old('customer_pan')}}" class="form-control input-sm pull-right" id="pan_no" onKeyUp="if(this.value>999999999){this.value='999999999';}else if(this.value<0){this.value='0';}">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Is Taxable:</label>
                                    <select type="text" class="form-control input-sm pull-right " name="vat_type" id="vat_type">
                                        <option value="yes">Yes(13%)</option>
                                        <option value="no">No</option>

                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>
                            @if(!$isQuot)
                            <div class="col-md-3 form-group" style="">
                                <label for="comment">Person Name</label>
                                <input type="text" name="name" id="name" class="form-control input-sm input-sm" value="">
                            </div>

                            <div class="col-md-3 form-group" style="">
                                <label for="position">Position</label>
                                <input type="text" name="position" class="form-control input-sm input-sm" id="position" value="Manager">
                            </div>
                            @if(Auth::user()->hasRole('admins'))
                            <div class="col-md-3 form-group" style="">
                                <label for="user_id"> <i class="fa fa-user"></i> Salesperson</label>
                                {!! Form::select('user_id', $users, \Auth::user()->id, ['class' => 'form-control input-sm input-sm', 'id'=>'user_id']) !!}
                            </div>
                            @else
                            <input type="hidden" name="user_id" value="{{\Auth::user()->id  }}">
                            @endif



                        </div>

                        <div class="col-md-12">
                            <div class="col-md-3 form-group" style="">
                                <label for="user_id">Status</label>
                                {!! Form::select('status',['Active'=>'Active','Canceled'=>'Canceled','Invoiced'=>'Invoiced'],null, ['class' => 'form-control input-sm input-sm', 'id'=>'user_id']) !!}
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Is renewal:</label>
                                    <select type="text" class="form-control input-sm pull-right " name="is_renewal" id="is_renewal">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Vat:</label>
                                    <select type="text" class="form-control input-sm pull-right " name="vat_type" id="vat_type">
                                        <option value="yes">Yes(13%)</option>
                                        <option value="no">No</option>

                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>

                        </div>

                        @endif

                        @if(\Request::get('type') && \Request::get('type') == 'quotation')
                        <input type="hidden" name="order_type" value="quotation">
                        @elseif(\Request::get('type') && \Request::get('type') == 'invoice')
                        <input type="hidden" name="order_type" value="proforma_invoice">
                        @elseif(\Request::get('type') && \Request::get('type') == 'order')
                        <input type="hidden" name="order_type" value="order">
                        @else
                        <input type="hidden" name="order_type" value="quotation">
                        @endif

                        <div class="clearfix"></div><br /><br />

                        <div class="col-md-12">
                            <div class="btn-group pull-right">
                                <a href="javascript::void(0)" class="btn btn-primary btn-sm" id="addMore" >
                                    <i class="fa fa-plus"></i> <span>Add Products Item</span>
                                </a> &nbsp;
                                <a href="javascript::void(0)" class="btn btn-success btn-sm" id="addCustomMore" >
                                    <i class="fa fa-plus"></i> <span>Add Custom Products Item</span>
                                </a>
                            </div>
                        </div>


                        <hr />
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="bg-gray">
                                       <th class="col-md-1" style="width: 2px;">S.N</th>
                                       <th>Particulars*</th>
                                       <th>Description</th>
                                       <th class="col-md-1">Qty*</th>
                                       <th class="col-md-1">Price*</th>
                                       <th >Unit</th>
                                       <th >Total</th>
                                   </tr>
                               </thead>

                               <tbody id='multipleDiv'>
                                <tr class="multipleDiv">

                                </tr>
                            </tbody>

                            <tfoot>

                                <tr>
                                    <td colspan="5" style="text-align: right;">Amount</td>
                                    <td id="sub-total">0.00</td>
                                    <td>&nbsp; <input type="hidden" name="subtotal" id="subtotal" value="0"></td>
                                </tr>

                                <tr>
                                    <td colspan="5" style="text-align: right;">Order Discount (%)</td>
                                    <td><input type="number" min="0" name="discount_percent" id="discount_amount" value="" onKeyUp="if(this.value>99){this.value='99';}else if(this.value<0){this.value='0';}"></td>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td colspan="5" style="text-align: right;">Taxable Amount</td>
                                        <td id="taxable-amount">0.00</td>
                                        <td>&nbsp; <input type="hidden" name="taxable_amount" id="taxableamount" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="text-align: right;">Tax Amount (13%)</td>
                                        <td id="taxable-tax">0.00</td>
                                        <td>&nbsp; <input type="hidden" name="taxable_tax" id="taxabletax" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="text-align: right; font-weight: bold;font-weight: 16px">Total Amount ({{ env('APP_CURRENCY')}})</td>
                                        <td style="font-size: 16px;font-weight: bold;" id="total">0.00</td>
                                        <td>
                                            <input type="hidden" name="total_tax_amount" id="total_tax_amount" value="0">
                                            <input type="hidden" name="final_total" id="total_" value="0">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <br />

                        @if($_GET['type'] != "quotation")
                        <div class="table-responsive">
                            <h3>EMI Payment Terms</h3>
                            <table class="table table-striped">
                                <thead>
                                    <tr class="bg-gray">
                                       <th class="col-md-1" style="width: 2px;">S.N</th>
                                       <th>Date</th>
                                       <th >Condition</th>
                                       <th>Amount</th>
                                   </tr>
                               </thead>

                               <tbody id='multiplePayDiv'>
                                <tr>
                                    <td class="p_sn">1</td>

                                    <td>
                                        <input type="date" class="form-control input-sm" name="term_date[]" value="{{\Carbon\Carbon::now()->toDateString()}}">
                                    </td>

                                    <td>
                                        <input type="text" class="form-control input-sm " name="term_condition[]" placeholder="Term Condition" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control input-sm" name="term_amount[]" placeholder="Amount" autocomplete="off" step="any" style="float:left; width:80%;">
                                    </td>
                                </tr>
                                <tr class="multiplePayDiv">

                                </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="4">
                                        <a href="javascript::void(0)" class="btn btn-success btn-sm" id="addMoreTerm" style="float: right" >
                                            <i class="fa fa-plus"></i> <span>Add More Terms</span>
                                        </a>

                                    </td>
                                </tr>


                            </tfoot>
                        </table>
                    </div>
                    <br />
                    @endif

                    <div class="box">
                        <div class="box-header with-border">

                            <div class="col-md-6 form-group" style="margin-top:5px;">
                                <label for="comment">Terms & Conditions Comment </label>
                                <small class="text-muted">Will be displayed on the invoice
                                </small>
                                <textarea class="form-control input-sm TextBox comment" name="comment">@if(isset($order->comment)){{ $order->comment }}@endif</textarea>
                            </div>

                            <div class="col-md-6 form-group" style="margin-top:5px;">
                                <label for="address">Address</label>
                                <textarea class="form-control input-sm TextBox address" name="address" id='physical_address'>@if(isset($orderDetail->address)){{ $orderDetail->address }}@endif</textarea>
                            </div>
                        </div>
                    </div>



                </div>
                <div class="panel-footer footer">
                    <button type="submit" class="btn btn-social btn-foursquare">
                        <i class="fa fa-save"></i>Save {{ $_GET['type']}}
                    </button>

                    <a class="btn btn-social btn-foursquare" href="/admin/orders/?type={{ $_GET['type']}}"> <i class="fa fa-times"></i> Cancel </a>

                </div>
            </form>
        </div>
    </div>

</div><!-- /.box-body -->
</div><!-- /.col -->

</div><!-- /.row -->
@endsection

@section('body_bottom')
<!-- form submit -->
@include('partials._body_bottom_submit_bug_edit_form_js')
@include('partials._date-toggle')
<script type="text/javascript">
    // $(function() {
    //     $('.datepicker').datetimepicker({
    //       //inline: true,
    //       format: 'YYYY-MM-DD',
    //       sideBySide: true,
    //       allowInputToggle: true
    //     });

    //   });
    $('.date-toggle-nep-eng').nepalidatetoggle();
    $(document).on('hidden.bs.modal', '#modal_dialog' , function(e){
        $('#modal_dialog .modal-content').html('');
    });

</script>

<script>
    const dateRange = {
        <?php $currentFiscalyear = FinanceHelper::cur_fisc_yr();?>
        minDate: `{{ $currentFiscalyear->start_date }}`,
        maxDate: `{{ $currentFiscalyear->end_date }}`
    }
    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
    function getSn(){

     $('#multipleDiv tr').each(function(index,val){

        if(index > 0){
            $(this).find('.p_sn').html(index);
        }

    });
 }
 function getTermSn(){

     $('#multiplePayDiv tr').each(function(index,val){

        if(index > 0){
            $(this).find('.p_sn').html(index);
        }

    });
 }

 $(document).on('change', '.product_id', function() {
    var parentDiv = $(this).parent().parent();
    if (this.value != 'NULL') {
        var _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "POST"
            , contentType: "application/json; charset=utf-8"
            , url: "/admin/products/GetProductDetailAjax/" + this.value + '?_token=' + _token
            , success: function(result) {



                var obj = jQuery.parseJSON(result.data);
                parentDiv.find('.price').val(obj.cost);
                parentDiv.find('.units').val("");
                parentDiv.find('.units').val(result.units?result.units.id:'').change();
                parentDiv.find('.description').val(result.description);
                if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
                    var total = parentDiv.find('.quantity').val() * obj.cost;
                } else {
                    var total = obj.cost;
                }

                var tax = parentDiv.find('.tax_rate').val();
                if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
                    tax_amount = total * Number(tax) / 100;
                    parentDiv.find('.tax_amount').val(tax_amount);
                    total = total + tax_amount;
                } else
                parentDiv.find('.tax_amount').val('0');

                parentDiv.find('.total').val(total);
                calcTotal();
            }
        });
    } else {
        parentDiv.find('.price').val('');
        parentDiv.find('.total').val('');
        parentDiv.find('.tax_amount').val('');
        calcTotal();
    }
});

 $(document).on('change', '.customer_id', function() {
    if (this.value != '') {
        $(".quantity").each(function(index) {
            var parentDiv = $(this).parent().parent();
            if (isNumeric($(this).val()) && $(this).val() != '')
                var total = $(this).val() * parentDiv.find('.price').val();
            else
                var total = parentDiv.find('.price').val();

            var tax = parentDiv.find('.tax_rate').val();
            if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
                tax_amount = total * Number(tax) / 100;
                parentDiv.find('.tax_amount').val(tax_amount);
                total = total + tax_amount;
            } else
            parentDiv.find('.tax_amount').val('0');

            if (isNumeric(total) && total != '') {
                parentDiv.find('.total').val(total);
                calcTotal();
            }
                //console.log( index + ": " + $(this).text() );
            });
    } else {
        $('.total').val('0');
        $('.tax_amount').val('0');
        calcTotal();
    }
});

 $(document).on('change', '.quantity', function() {
    var parentDiv = $(this).parent().parent();
    if (isNumeric(this.value) && this.value != '') {
        if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
            var total = parentDiv.find('.price').val() * this.value;
        } else
        var total = '';
    } else
    var total = '';

    var tax = parentDiv.find('.tax_rate').val();
    if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
        tax_amount = total * Number(tax) / 100;
        parentDiv.find('.tax_amount').val(tax_amount);
        total = total + tax_amount;
    } else
    parentDiv.find('.tax_amount').val('0');

    parentDiv.find('.total').val(total);
    calcTotal();
});

 $(document).on('change', '.price', function() {
    var parentDiv = $(this).parent().parent();
    if (isNumeric(this.value) && this.value != '') {
        if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
            var total = parentDiv.find('.quantity').val() * this.value;
        } else
        var total = '';
    } else
    var total = '';

    var tax = parentDiv.find('.tax_rate').val();
    if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
        tax_amount = total * Number(tax) / 100;
        parentDiv.find('.tax_amount').val(tax_amount);
        total = total + tax_amount;
    } else
    parentDiv.find('.tax_amount').val('0');

    parentDiv.find('.total').val(total);
    calcTotal();
});

 $(document).on('change', '.tax_rate', function() {
    var parentDiv = $(this).parent().parent();

    if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
        var total = parentDiv.find('.price').val() * Number(parentDiv.find('.quantity').val());
    } else
    var total = '';

    var tax = $(this).val();
    if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
        tax_amount = total * Number(tax) / 100;
        parentDiv.find('.tax_amount').val(tax_amount);
        total = total + tax_amount;
    } else
    parentDiv.find('.tax_amount').val('0');

    parentDiv.find('.total').val(total);
    calcTotal();
});



 $("#addMore").on("click", function() {

    $(".multipleDiv").after($('#orderFields #more-tr').html());
    $(".multipleDiv").next('tr').find('.product_id').select2({
        width: '100%'
    });
    $(".multipleDiv").next('tr').find('.quantity').val('1');
    getSn();

});
 $("#addMoreTerm").on("click", function() {

    $(".multiplePayDiv").after($('#termFields #more-tr').html());
    getTermSn();

});
 $("#addCustomMore").on("click", function() {
        //$($('#orderFields').html()).insertBefore(".multipleDiv");
        $(".multipleDiv").after($('#CustomOrderFields #more-custom-tr').html());
        $(".multipleDiv").next('tr').find('.quantity').val('1');
        getSn();



    });

 $(document).on('click', '.remove-this', function() {
    $(this).parent().parent().parent().remove();
    calcTotal();
    getSn();
});
 $(document).on('change', '#vat_type', function() {
    calcTotal();
});

 function calcTotal() {
        //alert('hi');
        var subTotal = 0;
        var taxableAmount = 0;

        //var tax = Number($('#tax').val().replace('%', ''));
        var total = 0;
        var tax_amount = 0;
        var taxableTax = 0;
        $(".total").each(function(index) {
            if (isNumeric($(this).val()))
                subTotal = Number(subTotal) + Number($(this).val());
        });
        $(".tax_amount").each(function(index) {
            if (isNumeric($(this).val()))
                tax_amount = Number(tax_amount) + Number($(this).val());
        });
        $('#sub-total').html(subTotal);
        $('#subtotal').val(subTotal);

        $('#taxable-amount').html(subTotal);
        $('#taxableamount').val(subTotal);

        var discount_amount = $('#discount_amount').val();
        var vat_type = $('#vat_type').val();
        if (isNumeric(discount_amount) && discount_amount != 0) {

            taxableAmount = subTotal - (Number(discount_amount) / 100 * subTotal);

        } else {
            total = subTotal;
            taxableAmount = subTotal;
        }

        if (vat_type == 'no' || vat_type == '') {

            total = taxableAmount;
            taxableTax = 0;

        } else {

            total = taxableAmount + Number(13 / 100 * taxableAmount);
            taxableTax = Number(13 / 100 * taxableAmount);
        }



        $('#taxableamount').val(taxableAmount);
        $('#taxable-amount').html(taxableAmount);

        $('#total_tax_amount').val(tax_amount);

        $('#taxabletax').val(taxableTax);
        $('#taxable-tax').html(taxableTax);

        $('#total').html(total);
        $('#total_').val(total);
    }

    $(document).on('keyup', '#discount_amount', function() {
        calcTotal();
    });

</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2({width: '300px'});

    });

    $(function() {
        $('.datepicker').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD'
            , sideBySide: true
            , allowInputToggle: true,
            minDate: dateRange.minDate,
            maxDate: dateRange.maxDate,
        });

    });

    function openwindow(source) {
        if (source == 'lead') {
            var win = window.open('/admin/leads/create/modal?source=' + source, '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=600, height=650');
        } else {
            var win = window.open('/admin/clients/modals?relation_type=customer', '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=600, height=650');
        }
    }
    var lead_clients = <?php echo json_encode($lead_clients->map(function($attr){
      return $attr->only(['id', 'name']);
  })); ?>;
    var customer_clients = <?php echo json_encode($customer_clients->map(function($attr){
      return $attr->only(['id', 'name']);
  })); ?>;


    function HandlePopupResult(result) {
       $('.customer_id.select2').select2('destroy');
       if(result){
        let clients = result.clients;
        let types = $(`input[name=source]:checked`).val();
        if( types == 'lead'){
            lead_clients = clients;
        }
        else{
            customer_clients = clients;
        }
        var option = '';
        for(let c of clients){
            option = option + `<option value='${c.id}'>${c.name}</option>`;
        }
        $('#customers_id').html(option);
        setTimeout(function(){
            $('#customers_id').val(result.lastcreated);
            $("#ajax_status").after("<span style='color:green;' id='status_update'>client sucessfully created</span>");
            $('#status_update').delay(3000).fadeOut('slow');

            $('.customer_id.select2').select2({width: '300px'});

        },500);

    }
    else{
        $("#ajax_status").after("<span style='color:red;' id='status_update'>failed to create clients</span>");
        $('#status_update').delay(3000).fadeOut('slow');
    }
}

$(`input[name = source]`).change(function(){
   $('.customer_id.select2').select2('destroy');
   let type = $(this).val();

   if(type == 'lead'){
    var text = 'Lead';
    obj = lead_clients;
}
else{
    var text ='Clients';
    obj = customer_clients;
}

var option = `<option value = ''> Select ${text}</option>`;
for(let c of obj){
    option = option + ` <option value = '${c.id}' > ${c.name}</option>`;
}
$('#select_label').html(`Select ${text}<i class="imp">*</i>`);
$('#create_lead_or_clients').html(` <a href = "#"onClick = "openwindow('${type}')" > <i class="fa fa-plus" style='color: black;'></i>New ${text}</a>`);
$('#customers_id').html(option);

$('.customer_id.select2').select2({width: '300px'});

});

$('#customers_id').change(function(){

    if($('input[name=source]:checked').val() != 'lead' ){
        let cust_id = $(this).val();
        $.get('/admin/getpanno/'+cust_id,function(data,status){
            $('#pan_no').val(data.pan_no);
            console.log(data);
            $('#physical_address').val(data.physical_address);
        });
    }

});

</script>
@endsection
