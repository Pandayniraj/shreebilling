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
        Edit Sales {{ $_GET['type']}}
        <small>
            {{ $_GET['type']}} # : {{ $order->id }} - Source : {{ ucfirst($order->source) }}
        </small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box box-header">


            <div id="orderFields" style="display: none;">
                <table class="table table-striped">
                    <tbody id="more-tr">
                        <tr>
                            <td class="p_sn"></td>
                            <td>
                                <select class="form-control  product_id" name="product_id_new[]" required="required">
                                    @if(isset($products))
                                    <option value="">Select Products</option>
                                    @foreach($products as $key => $pk)
                                    <option value="{{ $pk->id }}">{{ $pk->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control quantity" name="quantity_new[]" placeholder="Quantity" step=".01" required="required" autocomplete="off">
                            </td>

                            <td>
                                <input type="text" class="form-control price" name="price_new[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required" autocomplete="off">
                            </td>


                            <td>
                                <select class="form-control units" name="unit_new[]">
                                    <option value="">Unit</option>
                                    @foreach($units as $unit)
                                    <option value="{{$unit->id}}">{{$unit->name}}</option>
                                    @endforeach
                                </select>
                            </td>



                            <td>
                                <input type="number" class="form-control total" name="total_new[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly" style="float:left; width:80%;">
                                <a href="javascript::void(1);" style="width: 10%;">
                                    <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="CustomOrderFields" style="display: none;">
                <table class="table  table-striped">
                    <tbody id="more-custom-tr">
                        <tr>
                                <td class="p_sn"></td>
                            <td>
                                <input type="text" class="form-control product" name="custom_items_name_new[]" value="" placeholder="Product" autocomplete="off">
                            </td>

                            <td>
                                <input type="number" class="form-control quantity" name="custom_items_qty_new[]" placeholder="Quantity" step=".01" required="required" autocomplete="off">
                            </td>

                            <td>
                                <input type="text" class="form-control price" name="custom_items_price_new[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required" autocomplete="off">
                            </td>



                            <td>
                                <select class="form-control" name="custom_unit_new[]">
                                    <option value="">Unit</option>
                                    @foreach($units as $unit)
                                    <option value="{{$unit->id}}">{{$unit->name}}</option>
                                    @endforeach
                                </select>
                            </td>



                            <td>
                                <input type="number" class="form-control total" name="custom_total_new[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly" style="float:left; width:80%;">
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
                <div class="panel panel-bordered">
                    {!! Form::model( $order, ['route' => ['admin.orders.update', $order->id], 'method' => 'PUT'] ) !!}

                    <div class="panel-body">


                        <div class="callout bg-info">
                            <div class="form-group">
                                <label>Select Customer <i class="imp">*</i></label>
                                <select class="form-control customer_id select2 searchable" name="customer_id" required="required" id='customers_id'>
                                    <option class="input input-lg" value="">Select Customer</option>
                                    @if(isset($clients))
                                    @foreach($clients as $key => $uk)
                                    <option value="{{ $uk->id }}" @if($order && $uk->id == $order->client_id){{ 'selected="selected"' }}@endif>{{ '('.$uk->id.') '.$uk->name.' ('.$uk->location.')' }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('bill_no', 'Bill Number.') !!}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clone"></i>
                                            </div>
                                            {!! Form::number('bill_no',$order->bill_no, ['class' => 'form-control input-sm','min'=>1]) !!}
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('terms', 'Payment Terms') !!}


                                        {!! Form::select('terms',
                                        [
                                        'custom' => 'Custom','net15'=>'Net 15', 'net30'=>'Net 30', 'net45'=>'Net 45','net60'=>'Net 60'],
                                        $order->terms, ['class' => 'form-control input-sm']) !!}


                                </div>
                            </div>



                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Bill Date:</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right datepicker date-toggle-nep-eng" name="bill_date" value="{{ $order->bill_date }}" id="bill_date">
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
                                        <input type="text" class="form-control pull-right datepicker date-toggle-nep-eng" name="due_date" value="{{ $order->due_date }}" id="due_date">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Is Taxable:</label>
                                    <select type="text" class="form-control input-sm pull-right " name="vat_type" id="vat_type">
                                        <option value="yes" @if($order && $order->vat_type == 'yes'){{ 'selected="selected"' }}@endif>Yes(13%)</option>
                                        <option value="no" @if($order && $order->vat_type == 'no'){{ 'selected="selected"' }}@endif>No</option>

                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>

                        </div>
                        @if(!$isQuot)
                        <div class="col-md-12" style="background-color: #efefef; padding-top: 5px;padding-bottom: 5px">
                            <div class="col-md-3 form-group" style="">
                                <label for="comment">Person/Company Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $order->name }}" required="required">
                            </div>

                            <div class="col-md-3 form-group" style="">
                                <label for="position">Position</label>
                                <input type="text" name="position" class="form-control" id="position" value="{{ $order->position }}">
                            </div>
                            <div class="col-md-3 form-group" style="">
                                <label for="user_id">Invoice Owner</label>
                                {!! Form::select('user_id', $users, \Auth::user()->user_id, ['class' => 'form-control input-sm', 'id'=>'user_id']) !!}
                            </div>

                            <div class="col-md-3 form-group" style="">
                                <label for="comment">Status</label>
                                {!! Form::select('status',['Active'=>'Active','Canceled'=>'Canceled','Invoiced'=>'Invoiced'],$order->status, ['class' => 'form-control input-sm', 'id'=>'user_id']) !!}
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
                                        <input type="text" name="customer_pan" value="{{ $order->customer_pan }}" class="form-control pull-right" id="pan_no" onKeyUp="if(this.value>999999999){this.value='999999999';}else if(this.value<0){this.value='0';}">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>



                            <div class="col-md-3 form-group" style="">
                                <label for="user_id">Location</label>
                                {!! Form::select('from_stock_location', [''=>'Select']+$productlocation, null, ['class' => 'form-control input-sm']) !!}
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Vat:</label>
                                    <select type="text" class="form-control input-sm " name="vat_type" id="vat_type">
                                        <option value="yes">Yes(13%)</option>
                                        <option value="no" @if($order->vat_type == 'no') selected @endif>No</option>

                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Is renewal:</label>
                                    <select type="text" class="form-control pull-right " name="is_renewal" id="is_renewal">
                                        <option value="0">No</option>
                                        <option value="1" @if($order->is_renewal == '1') selected @endif>Yes</option>
                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>

                        </div>
                        @endif

                        <input type="hidden" name="order_type" value="{{$order->order_type}}">


                        <div class="clearfix"></div>
                        <div class="row">
                        <div class="col-md-12" style="padding-bottom: 10px;padding-top: 10px;">
                            <div class="btn-group pull-right">
                            <a href="javascript::void(0)" class="btn btn-primary btn-sm" id="addMore" >
                                <i class="fa fa-plus"></i> <span>Add Products Item</span>
                            </a> &nbsp;
                            <a href="javascript::void(0)" class="btn btn-success btn-sm" id="addCustomMore" >
                                <i class="fa fa-plus"></i> <span>Add Custom Products Item</span>
                            </a>
                        </div>
                        </div>
                    </div>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="bg-gray">
                                       <th>S.N</th>
                                        <th>Particulars*</th>
                                        <th class="col-md-1">Qty*</th>
                                        <th class="col-md-1">Price*</th>
                                        <th class="col-xs-1">Unit</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>

                                <tbody id='multipleDiv'>
                                     <tr class="multipleDiv"></tr>
                                    @foreach($orderDetails as $odk => $odv)
                                    @if($odv->is_inventory == 1)
                                    <tr>
                                        <td class="p_sn"></td>
                                        <td>
                                            <select class="form-control select2 product_id" name="product_id[]" required="required">
                                                @if(isset($products))
                                                <option value="">Select Products</option>
                                                @foreach($products as $key => $pk)
                                                <option value="{{ $pk->id }}" @if($odv->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </td>


                                        <td>
                                            <input type="number" class="form-control quantity" name="quantity[]" placeholder="Quantity" step=".01" value="{{ $odv->quantity }}" required="required" autocomplete="off">
                                        </td>
                                         <td>
                                            <input type="text" class="form-control price" name="price[]" placeholder="Price" value="{{ $odv->price }}" required="required" autocomplete="off">
                                        </td>

                                        <td>
                                            <select class="form-control units" name="unit[]">
                                                <option value="">Unit</option>
                                                @foreach($units as $unit)
                                                <option value="{{$unit->id}}" @if($odv->unit == $unit->id ) selected @endif >{{$unit->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <input type="number" class="form-control total" name="total[]" placeholder="Total" value="{{ $odv->total }}" readonly="readonly" style="float:left; width:80%;">
                                            <a href="javascript::void(1);" style="width: 10%;">
                                                <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @elseif($odv->is_inventory == 0)

                                    <tr>
                                        <td class="p_sn"></td>
                                        <td>

                                            <input type="text" class="form-control product" name="description_custom[]" value="{{ $odv->description }}" placeholder="Product" autocomplete="off">

                                        </td>
                                        <td>
                                            <input type="number" class="form-control quantity" name="quantity_custom[]" placeholder="Quantity" step=".01" value="{{ $odv->quantity }}" required="required" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control price" name="price_custom[]" placeholder="Fare" value="{{ $odv->price }}" required="required" autocomplete="off">
                                        </td>
                                        <td>
                                            <select class="form-control" name="unit_custom[]">
                                                <option value="">Unit</option>
                                                @foreach($units as $unit)
                                                <option value="{{$unit->id}}" @if($odv->unit == $unit->id ) selected @endif>{{$unit->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>


                                        <td>
                                            <input type="number" class="form-control total" name="total_custom[]" placeholder="Total" value="{{ $odv->total }}" readonly="readonly" style="float:left; width:80%;">
                                            <a href="javascript::void(1);" style="width: 10%;">
                                                <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    @endif
                                    @endforeach

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="5" style="text-align: right;">Subtotal</td>
                                        <td id="sub-total">{{ $order->subtotal }}</td>
                                        <td>&nbsp; <input type="hidden" name="subtotal" id="subtotal" value="{{ $order->subtotal }}"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="text-align: right;">Order Discount (%)</td>
                                        <td><input type="number" class="form-control input-sm" placeholder="Discount Percentage" min="0" name="discount_percent" id="discount_amount" value="{{$order->discount_percent }}" onKeyUp="if(this.value>99){this.value='99';}else if(this.value<0){this.value='0';}"></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="text-align: right;">Taxable Amount</td>
                                        <td id="taxable-amount">{{ $order->taxable_amount }}</td>
                                        <td>&nbsp;
                                            <input type="hidden" name="taxable_amount" id="taxableamount" value="{{ $order->taxable_amount }}"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="text-align: right;">Tax Amount (13%)</td>
                                        <td id="taxable-tax">{{ $order->tax_amount }}</td>
                                        <td>&nbsp; <input type="hidden" name="taxable_tax" id="taxabletax" value="{{ $order->tax_amount }}"></td>
                                    </tr>

                                    <tr>
                                        <td colspan="5" style="text-align: right;"><strong>TOTAL</strong></td>
                                        <td id="total">{{ $order->total_amount }}</td>
                                        <td>
                                            <input type="hidden" name="total_tax_amount" id="total_tax_amount" value="{{ $order->tax_amount }}">
                                            <input type="hidden" name="final_total" id="total_" value="{{ $order->total_amount }}">
                                        </td>


                                    </tr>
                                </tfoot>
                            </table>
                        </div>
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
                                    @foreach($orderPaymentTerms as $odk => $odv)
                                        <tr>
                                            <td class="p_sn">{{ $odk+1 }}</td>

                                             <td>
                                                <input type="date" class="form-control input-sm" name="term_date[]" value="{{$odv->term_date}}">
                                            </td>

                                            <td>
                                                <input type="text" class="form-control input-sm " name="term_condition[]" placeholder="Term Condition" autocomplete="off" value="{{$odv->term_condition}}">
                                            </td>
                                             <td>
                                                <input type="number" class="form-control input-sm" name="term_amount[]" placeholder="Amount" autocomplete="off" step="any" style="float:left; width:80%;" value="{{$odv->term_amount}}">
                                                @if($odk != 0)
                                                    <a href="javascript::void(1);" style="width: 10%;">
                                                        <i class="remove-term btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                                    </a>

                                                @endif


                                            </td>
                                        </tr>
                                    @endforeach
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


                        @if(!$isInvoice)
                        <div class="box">
                            <div class="box-header with-border">

                                <div class="col-md-6 form-group" style="margin-top:5px;">
                                    <label for="comment">Terms & Conditions Comment</label>
                                    <textarea class="form-control TextBox comment" name="comment">@if(isset($order)){{ $order->comment }}@endif</textarea>
                                </div>

                                <div class="col-md-6 form-group" style="margin-top:5px;">
                                    <label for="address">Address</label>
                                    <textarea class="form-control TextBox address" name="address" id='physical_address'>{{ $order->address }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endif


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
    $(document).ready(function (){
        getSn()
        getTermSn()
    })
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


</script>
@endsection
