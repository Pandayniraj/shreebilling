@extends('layouts.master')

@section('head_extra')

<?php
 function CategoryTree($parent_id=null,$sub_mark='',$ledgers_data){
    
    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)->get();

    if(count($groups)>0){
        foreach($groups as $group){
            echo '<optgroup  label="'.$sub_mark.'['.$group->code.']'.' '.$group->name.'"></optgroup>';

            $ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id',$group->id)->get(); 
              if(count($ledgers>0)){
                $submark= $sub_mark;
                $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 

                 foreach($ledgers as $ledger){

                 if($ledgers_data->id == $ledger->id){
                  echo '<option selected value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';
                  }else{

                  echo '<option value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';
               }

               }
               $sub_mark=$submark;

            }
            CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$ledgers_data);
        }
    }
 }

 ?>

<!-- Select2 css -->
@include('partials._head_extra_select2_css')

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

</style>
@endsection

@section('content')


<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        New Purchase {{ucfirst(trans(\Request::get('type')))}}
        <small> Purchase {{ucfirst(trans(\Request::get('type')))}}
        </small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12 box'>
        <div class="box-body">
            <div id="orderFields" style="display: none;">
                <table class="table">
                    <tbody id="more-tr">
                        <tr>
                            <td>
                                <select class="form-control select2 product_id" name="product_id[]" required="required">

                                    <option value="">Select Product</option>
                                    @foreach($products as $key => $pk)
                                    <option value="{{ $pk->id }}" @if(isset($orderDetail->product_id) && $orderDetail->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }} ( {{ $pk->product_code }})</option>
                                    @endforeach

                                </select>
                            </td>

                            <td>
                                <input type="text" name="available_qty" class="form-control available_qty" readonly="" >
                            </td>
                            <td>
                                <input type="number" class="form-control quantity" name="quantity[]" placeholder="Quantity" min="1" value="1" value="1" required="required" step='any' autocomplete="off">
                            </td>
                            <td>
                                <input type="number" name="rmb[]" class="form-control" style="width: 100px;" placeholder="Rmb..">
                            </td>
                            <td>
                                <select name='units[]' class="form-control">
                                    <option value="">Select Units</option>
                                    @foreach($prod_unit as $pu)
                                        <option value="{{ $pu->id }}">{{ $pu->name }}({{ $pu->symbol }})</option>
                                    @endforeach
                                </select>
                            </td>

                             <td>
                                <input type="number" class="form-control price" name="price[]" placeholder="Rate" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required" autocomplete="off">
                            </td>
                            <td>
                                <input type="number" class="form-control unit_attributes freight" name="freight[]" placeholder="Freight" value="@if(isset($orderDetail->freight)){{ $orderDetail->freight }}@endif" autocomplete="off" step='any'>
                            </td>
                            <td>
                                <input type="number" class="form-control unit_attributes custom" name="custom[]" placeholder="Custom" value="@if(isset($orderDetail->custom)){{ $orderDetail->custom }}@endif" autocomplete="off" step='any'>
                            </td>
                            <td>
                                <input type="number" class="form-control unit_attributes transport" name="transport[]" placeholder="Transport" value="@if(isset($orderDetail->transport)){{ $orderDetail->transport }}@endif" autocomplete="off" step='any'>
                            </td>
                            <td>
                                <input type="number" class="form-control unit_attributes commission" name="commission[]" placeholder="Commission" value="@if(isset($orderDetail->commission)){{ $orderDetail->commission }}@endif" autocomplete="off" step='any'>
                            </td>
                            <td>
                                <input type="number" class="form-control unit_price" name="unit_price[]" placeholder="Per Unit Price" value="@if(isset($orderDetail->unit_price)){{ $orderDetail->unit_price }}@endif" readonly="readonly" autocomplete="off" step='any'>
                            </td>
                            <td>
                                <input type="number" class="form-control total" name="total[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly" style="float:left; width:80%;" step='any'>
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
                            <td>
                                <input type="text" class="form-control product" name="custom_items_name[]" value="" placeholder="Product" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" name="" readonly="" class="form-control">
                            </td>
                            <td>
                                <input type="number" class="form-control quantity" name="custom_items_qty[]" placeholder="Quantity" min="1" value="1" required="required" step='any' autocomplete="off">
                            </td>
                            <td>
                                <input type="number" name="custom_rmb[]" class="form-control" style="width: 100px;" placeholder="Rmb..">
                            </td>

                            <td>
                                <select name='custome_unit[]' class="form-control">
                                    <option value="">Select Units</option>
                                    @foreach($prod_unit as $pu)
                                        <option value="{{ $pu->id }}">{{ $pu->name }}({{ $pu->symbol }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control price" name="custom_items_price[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required" autocomplete="off">
                            </td>
                            <td>
                                <input type="number" class="form-control unit_attributes freight" name="custom_items_freight[]" placeholder="Freight" value="@if(isset($orderDetail->freight)){{ $orderDetail->freight }}@endif" autocomplete="off" step='any'>
                            </td>
                            <td>
                                <input type="number" class="form-control unit_attributes custom" name="custom_items_custom[]" placeholder="Custom" value="@if(isset($orderDetail->custom)){{ $orderDetail->custom }}@endif" autocomplete="off" step='any'>
                            </td>
                            <td>
                                <input type="number" class="form-control unit_attributes transport" name="custom_items_transport[]" placeholder="Transport" value="@if(isset($orderDetail->transport)){{ $orderDetail->transport }}@endif" autocomplete="off" step='any'>
                            </td>
                            <td>
                                <input type="number" class="form-control unit_attributes commission" name="custom_items_commission[]" placeholder="Commission" value="@if(isset($orderDetail->commission)){{ $orderDetail->commission }}@endif" autocomplete="off" step='any'>
                            </td>
                            <td>
                                <input type="number" class="form-control unit_price" name="custom_items_unit_price[]" placeholder="Per Unit Price" value="@if(isset($orderDetail->unit_price)){{ $orderDetail->unit_price }}@endif" readonly="readonly" autocomplete="off" step='any'>
                            </td>

                           


                            <td>
                                <input type="number" class="form-control total" name="custom_total[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly" style="float:left; width:80%;" step='any'>
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
                    <form method="POST" action="/admin/purchase">
                        {{ csrf_field() }}

                        <div class="">

                            <div class="col-md-12" id='customers_id'>
                                <div class="bg-maroon" style="padding: 10px; margin-bottom: 15px">
                                    <label class="">
                                        From Clients
                                        <input type="radio" name="supplier_type" value='supplier' checked="">
                                    </label>
                                   {{--  OR
                                    <label class="">
                                        From Cash & Equivalents
                                        <input type="radio" name="supplier_type" value="cash_equivalent">
                                    </label> --}}
                                    <label>&nbsp;&nbsp;Select Supplier <i class="imp">*</i></label>
                                    <select class="customer_id select2" name="customer_id">
                                        <option value="">Select Supplier</option>
                                        @if(isset($clients))
                                        @foreach($clients as $key => $uk)
                                        <option value="{{ $uk->id }}" @if($orderDetail && $uk->id ==
                                            $orderDetail->customer_id){{ 'selected="selected"' }}@endif>{{ '('.env('APP_CODE'). $uk->id.') '.$uk->name.' -'.$uk->vat }}</option>
                                        @endforeach
                                        @endif

                                    </select>

                                    &nbsp;&nbsp;<a style="color: white" href="#" target="_blank" onClick='openwindow()' id='create_supplier'>+ Create Supplier</a>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            <div class="col-md-12">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Order Date:</label>

                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control input-sm pull-right datepicker" name="ord_date" value="" id="ord_date">
                                        </div>
                                        <!-- /.Request group -->
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Delivery Date:</label>

                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control input-sm pull-right datepicker" name="delivery_date" value="" id="delivery_date">
                                        </div>
                                        <!-- /.Request group -->
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">

                                        <label>Supplier Bill Date:</label>
                                        <select id="selectdatetype" name="datetype" class="label-success">
                                            <option value="eng">English</option>
                                            <option value="nep">Nepali</option>
                                        </select>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <div id='dateselectors'>
                                                <input type="text" class="form-control input-sm pull-right datepicker" name="bill_date" value="" id="bill_date" required="">
                                            </div>
                                        </div>
                                        <!-- /.Request group -->
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Supplier Payment Date:</label>

                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <div id='dateselectors1'>
                                                <input type="text" class="form-control input-sm pull-right datepicker" name="due_date" value="" id="due_date">
                                            </div>
                                        </div>
                                        <!-- /.Request group -->
                                    </div>
                                </div>




                            </div>

                            <div class="col-md-12">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status:</label>
                                        <select type="text" class="form-control input-sm pull-right " name="status" id="status">
                                            <option value="">Select</option>
                                            <option value="Placed">Placed</option>
                                            <option value="Parked">Parked</option>
                                            <option value="Recieved">Recieved</option>
                                        </select>
                                    </div>
                                    <!-- /.Request group -->
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>PAN NO:</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-credit-card"></i>
                                            </div>
                                            <div class="input-group">
                                                <input type="text" class="form-control input-sm pull-right " name="pan_no" value="{{ old('pan_no')}}" id="pan_no">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Bill NO:</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-check"></i>
                                            </div>
                                            <div class="input-group">
                                                <input type="text" class="form-control input-sm pull-right " name="bill_no" value="{{ old('bill_no')}}" id="bill_no">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Vat:</label>
                                        <select type="text" class="form-control pull-right " name="vat_type" id="vat_type">
                                            <option value="yes">Yes(13%)</option>
                                            <option value="no">No</option>

                                        </select>
                                    </div>
                                    <!-- /.Request group -->
                                </div>






                            </div>
                            <div class="row">
                                <div class="col-md-12" style="margin-left: 15px">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Is renewal:</label>
                                            <select type="text" class="form-control pull-right " name="is_renewal" id="is_renewal">
                                                <option value="0">No</option>
                                                <option value="1">Yes</option>
                                            </select>
                                        </div>
                                        <!-- /.Request group -->
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Location:</label>
                                            {!! Form::select('into_stock_location', [''=>'Select']+$productlocation, null, ['class' => 'form-control label-default']) !!}
                                        </div>
                                        <!-- /.Request group -->
                                    </div>

                                    <div class="col-md-3 form-group" style="">
                                        <label for="user_id">Purchase Owner</label>
                                        {!! Form::select('user_id', $users, \Auth::user()->id, ['class' => 'form-control Request-sm', 'id'=>'user_id']) !!}
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Reference<span class="text-danger"> *</span></label>
                                            <div class="input-group">
                                                <div class="input-group-addon">PO-</div>
                                                <input id="reference_no" class="form-control input-sm" value="{{ sprintf("%04d", $order_count+1)}}" type="text">


                                                <input type="hidden" name="reference" id="reference_no_write" value="">
                                            </div>
                                            <span id="errMsg" class="text-danger"></span>
                                        </div>
                                    </div>



                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12" style="margin-left: 15px">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Projects:</label>
                                            {!! Form::select('project_id', [''=>'Select']+$projects, null, ['class' => 'form-control label-default project_id']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Discount Type:</label>
                                            {!! Form::select('discount_type',['p'=>'Percentage','a'=>'Amount'], null, ['class' => 'form-control label-primary','id'=>'discount_type']) !!}
                                        </div>
                                    </div>

                                    @if(Auth::user()->hasRole('admins'))
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fiscal Year:</label>
                                            {!! Form::select('fiscal_year',$fiscal_years, \FinanceHelper::cur_fisc_yr()->id , ['class' => 'form-control label-danger','id'=>'fiscal_year']) !!}
                                        </div>
                                    </div>
                                    @endif

                                      <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Currency:</label>
                                             <select class="form-control select2 currency" name="currency" required="required">
                                    @foreach($currency as $k => $v)
                                    <option value="{{ $v->currency_symbol }}" @if(isset($v->currency_code) && $v->currency_code == $v->currency_code) selected="selected"@endif>
                                        {{ $v->name }} {{ $v->currency_code }} ({{ $v->currency_symbol }})</option>
                                    @endforeach

                                </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        @if(\Request::get('type') && \Request::get('type') == 'purchase_orders')
                        <input type="hidden" name="purchase_type" value="purchase_orders">
                        @elseif(\Request::get('type') && \Request::get('type') == 'request')
                        <input type="hidden" name="purchase_type" value="request">
                        @elseif(\Request::get('type') && \Request::get('type') == 'bills')
                        <input type="hidden" name="purchase_type" value="bills">
                        @elseif(\Request::get('type') == 'assets')
                        <input type="hidden" name="purchase_type" value="assets">
                        @else
                        <input type="hidden" name="purchase_type" value="purchase_orders">
                        @endif

                        <div class="clearfix"></div><br /><br />


                        <div class="col-md-12">
                            <a href="/admin/products/create" data-toggle="modal" data-target="#modal_dialog" class="btn btn-primary btn-xs" style="float: left; display: none;" title="Create a new Product" id='addmorProducts'>
                                <i class="fa fa-plus"></i> <span>Create New Product</span>
                            </a>
                            <a href="javascript::void(0)" class="btn btn-default btn-xs" id="addMore" style="float: right;">
                                <i class="fa fa-plus"></i> <span>Add Products Item</span>
                            </a>
                            &nbsp; &nbsp; &nbsp;
                            <a href="javascript::void(0)" class="btn btn-default btn-xs" id="addCustomMore" style="float: right;">
                                <i class="fa fa-plus"></i> <span> Add Custom Products Item</span>
                            </a>
                        </div>
                        <hr />
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-maroon">
                                    <th>Item Description *</th>
                                    <th>Available</th>
                                    <th>Quantity *</th>
                                    <th>RMB </th>
                                    <th>Unit </th>
                                    <th>Rate *</th>
                                    <th>Freight </th>
                                    <th>Custom </th>
                                    <th>Transport </th>
                                    <th>Commission </th>
                                    <th>Unit Price </th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody id='multipleDiv'>
                                <tr class="multipleDiv">

                                </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="3" style="text-align: right;">Amount</td>
                                    <td id="sub-total">0.00</td>
                                    <td>&nbsp; <input type="hidden" name="subtotal" id="subtotal" value="0"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right;" class="discount_type">Order Discount (%)</td>
                                    <td><input type="number" min="0" name="discount_percent" id="discount_amount" value="" step='any'></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right;">Taxable Amount</td>
                                    <td id="taxable-amount">0.00</td>
                                    <td>&nbsp; <input type="hidden" name="taxable_amount" id="taxableamount" value="0"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right;">Tax Amount</td>
                                    <td id="taxable-tax">0.00</td>
                                    <td>&nbsp; <input type="hidden" name="taxable_tax" id="taxabletax" value="0"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold;">Total Amount</td>
                                    <td id="total">0.00</td>
                                    <td>
                                        <input type="hidden" name="total_tax_amount" id="total_tax_amount" value="0">
                                        <input type="hidden" name="final_total" id="total_" value="0">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        <br />



                        <div class="col-md-6 form-group" style="margin-top:5px;">
                            <label for="comment">Narration</label>
                            <textarea class="form-control TextBox comment" name="comments">@if(isset($order->comment)){{ $order->comments }}@endif</textarea>
                        </div>

                        <div class="col-md-6 form-group" style="margin-top:5px;">
                            <label for="address">Address</label>
                            <textarea id="physical_address" class="form-control TextBox address" name="address">@if(isset($orderDetail->address)){{ $orderDetail->address }}@endif</textarea>
                        </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-social btn-foursquare" id='btnSubmit'>
                        <i class="fa fa-save"></i>Save Purchase
                    </button>
                    <a href="/admin/purchase?type={{\Request::get('type')}}" class="btn btn-default">Cancel</a>
                </div>
                </form>
            </div>
        </div>

    </div><!-- /.box-body -->
</div><!-- /.col -->

@endsection
<div class='supplier_options' style="display: none;">
    <div id='_supplier'>
        <option value="">Select Supplier</option>
        @if(isset($clients))
        @foreach($clients as $key => $uk)
        <option value="{{ $uk->id }}" @if($orderDetail && $uk->id ==
            $orderDetail->customer_id){{ 'selected="selected"' }}@endif>{{ '('.env('APP_CODE'). $uk->id.') '.$uk->name.' -'.$uk->vat }}</option>
        @endforeach
        @endif
    </div>
    <div id='_paid_through'>
        <option value="">Select Supplier</option>
        @if(isset($paid_through))
        @foreach($paid_through as $key => $uk)
        <option value="{{ $uk->id }}" @if($orderDetail && $uk->id ==
            $orderDetail->customer_id){{ 'selected="selected"' }}@endif>{{ '('.env('APP_CODE'). $uk->id.') '.$uk->name.' -'.$uk->organization }}</option>
        @endforeach
        @endif
    </div>
</div>
@section('body_bottom')
<!-- form submit -->
@include('partials._body_bottom_submit_bug_edit_form_js')

<script type="text/javascript">
    // $(function() {
    //     $('.datepicker').datetimepicker({
    //       //inline: true,
    //       format: 'YYYY-MM-DD',
    //       sideBySide: true,
    //       allowInputToggle: true
    //     });

    //   });

</script>
@include('admin.purchase.nep_eng_date_toogle')
<script>
    @if(\Request::get('type') == 'bills' || \Request::get('type') == 'assets')
    $('select[name=customer_id]').prop('required', true);
    @endif

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
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
                    parentDiv.find('.price').val(obj.price);
                    parentDiv.find('.available_qty').val(result.available)
                    if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
                        var total = parentDiv.find('.quantity').val() * obj.price;
                    } else {
                        var total = obj.price;
                    }

                    var tax = parentDiv.find('.tax_rate').val();
                    if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
                        tax_amount = total * Number(tax) / 100;
                        parentDiv.find('.tax_amount').val(tax_amount);
                        total = total + tax_amount;
                    } else
                        parentDiv.find('.tax_amount').val('0');

                    parentDiv.find('.total').val(total);
                    parentDiv.find('.unit_price').val(total);
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

        let supp_id = $(this).val();
        $.get('/admin/getpanno/' + supp_id, function(data, status) {
            $('#pan_no').val(data.pan_no);
            $('#physical_address').val(data.physical_address);


        });
    });

    $(document).on('keyup', '.quantity', function() {
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

    $(document).on('keyup', '.price', function() {
        var parentDiv = $(this).parent().parent();
        if (isNumeric(this.value) && this.value != '') {
            if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
                var total = parentDiv.find('.quantity').val() * this.value;
            } else
                var total = '';
        } else
            var total = '';

        unitPrice(this);
        parentDiv.find('.total').val(total);

        calcTotal();
    });

    $(document).on('keyup', '.unit_attributes', function() {
        unitPrice(this);
    });

    function unitPrice(main){

        var parentDiv = $(main).parent().parent();
        var unit_total = Number(parentDiv.find('.price').val());
        const freight = parentDiv.find('.freight').val();
        if (isNumeric(freight) && freight != 0 && (unit_total != 0 || unit_total != '')) {
            unit_total += Number(freight) ;
        }
        const custom = parentDiv.find('.custom').val();
        if (isNumeric(custom) && custom != 0 && (unit_total != 0 || unit_total != '')) {
            unit_total += Number(custom) ;
        }
        const transport = parentDiv.find('.transport').val();
        if (isNumeric(transport) && transport != 0 && (unit_total != 0 || unit_total != '')) {
            unit_total += Number(transport) ;
        }
        const commission = parentDiv.find('.commission').val();
        if (isNumeric(commission) && commission != 0 && (unit_total != 0 || unit_total != '')) {
            unit_total += Number(commission) ;
        }
        parentDiv.find('.unit_price').val(unit_total);
    }

    /*$('#discount').on('change', function() {
        if(isNumeric(this.value) && this.value != '')
        {
            if(isNumeric($('#sub-total').val()) && $('#sub-total').val() != '')
                parentDiv.find('.total').val($('#sub-total').val() - this.value).trigger('change');
        }
    });

    $("#sub-total").bind("change", function() {
        if(isNumeric($('#discount').val()) && $('#discount').val() != '')
            parentDiv.find('.total').val($('#sub-total').val() - $('#discount').val());
        else
            parentDiv.find('.total').val($('#sub-total').val());
    });*/

    $("#addMore").on("click", function() {
        //$($('#orderFields').html()).insertBefore(".multipleDiv");
        $(".multipleDiv").after($('#orderFields #more-tr').html());
        $(".multipleDiv").next('tr').find('select').select2({
            width: '100%'
        });
        $('#addmorProducts').show(300);

    });
    $("#addCustomMore").on("click", function() {
        //$($('#orderFields').html()).insertBefore(".multipleDiv");
        $(".multipleDiv").after($('#CustomOrderFields #more-custom-tr').html());
    });

    $(document).on('click', '.remove-this', function() {
        $(this).parent().parent().parent().remove();
        calcTotal();
        $("#multipleDiv .product_id").length > 0 ? $('#addmorProducts').show(300) : $('#addmorProducts').hide(300);
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

        console.log(vat_type);

        if (isNumeric(discount_amount) && discount_amount != 0) {
            if ($('#discount_type').val() == 'a') {
                taxableAmount = subTotal - Number(discount_amount);
            } else {
                taxableAmount = subTotal - (Number(discount_amount) / 100 * subTotal);
            }
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
        $('.customer_id').select2();
        $('.project_id').select2();
    });

</script>

<script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD'
            , sideBySide: true
            , allowInputToggle: true,

        });

    });

</script>

<script type="text/javascript">
    var refNo = 'PO-' + $("#reference_no").val();

    $("#reference_no_write").val(refNo);

    $(document).on('keyup', '#reference_no', function() {

        var val = $(this).val();

        if (val == null || val == '') {
            $("#errMsg").html("Already Exists");
            $('#btnSubmit').attr('disabled', 'disabled');
            return;
        } else {
            $('#btnSubmit').removeAttr('disabled');
        }

        var ref = 'PO-' + $(this).val();
        $("#reference_no_write").val(ref);
        $.ajax({
                method: "POST"
                , url: "/admin/purchase/reference-validation"
                , data: {
                    "ref": ref
                    , "_token": token
                }
            })
            .done(function(data) {
                var data = jQuery.parseJSON(data);
                if (data.status_no == 1) {
                    $("#errMsg").html('Already Exists!');
                } else if (data.status_no == 0) {
                    $("#errMsg").html('Available');
                }
            });
    });

    function openwindow() {
        var win = window.open('/admin/clients/modals?relation_type=supplier', '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=600, height=650');

    }

    function HandlePopupResult(result) {
        if (result) {
            let clients = result.clients;
            let types = $(`input[name=source]:checked`).val();
            if (types == 'lead') {
                lead_clients = clients;
            } else {
                customer_clients = clients;
            }
            var option = '<option value="">Select Supplier</option>';
            for (let c of clients) {
                option = option + `<option value='${c.id}'>${c.name}</option>`;
            }
            $('#customers_id select').html(option);
            $('.supplier_options #_supplier').html(option);
            setTimeout(function() {
                $('.customer_id').select2('destroy');
                $('#customers_id select').val(result.lastcreated);
                $('#pan_no').val(result.pan_no);
                $("#ajax_status").after("<span style='color:green;' id='status_update'>client sucessfully created</span>");
                $('#status_update').delay(3000).fadeOut('slow');
                $('.customer_id').select2();
            }, 500);
        } else {
            $("#ajax_status").after("<span style='color:red;' id='status_update'>failed to create clients</span>");
            $('#status_update').delay(3000).fadeOut('slow');
        }
    }

    $(document).on('hidden.bs.modal', '#modal_dialog', function(e) {
        $('#modal_dialog .modal-content').html('');
    });

    function handleProductModel(result) {
        var lastcreated = result.lastcreated;
        $('#modal_dialog').modal('hide');
        $('select.product_id').each(function() {
            let options = `<option value='${lastcreated.id}'>${lastcreated.name}</option>`
            $(this).append(options);
        });
        setTimeout(function() {
            alert("Product SuccessFully Added");
        }, 500);

    }

    $('#discount_type').change(function() {
        if ($(this).val() == 'a') {
            $('.discount_type').text('Order Discount (Amount)')
        } else {
            $('.discount_type').text('Order Discount (%)')
        }
        $('#discount_amount').val('')
        calcTotal();

    });
    $("input[name=supplier_type]").change(function() {
        //alert("DONE");
        if (!$(this).is(":checked")) {
            return;
        }
         $('.customer_id').select2('destroy');
        if ($(this).val() == 'supplier') {
            let option = $('.supplier_options #_supplier').html();
            $('select[name=customer_id]').html(option);
            $('#create_supplier').show();
        } else {
            let option = $('.supplier_options #_paid_through').html();
            $('select[name=customer_id]').html(option);
            $('#create_supplier').hide();
        }
        $('.customer_id').select2();

    });
    $('input[name=supplier_type]').trigger('change');

</script>
@endsection
