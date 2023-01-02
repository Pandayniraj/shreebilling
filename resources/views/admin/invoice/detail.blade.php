@extends('layouts.master')

@section('head_extra')
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
            width: 90px !important;

        }
        .fa-trash{
            float: left !important;
            margin-left: 5px;
        }
        .hide_on_tablet{
            display: none;
        }
         .btn-group{
            margin-top: -15px;
        }
    }
</style>
@endsection

@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        Tax Invoice
        <small>
            Tax invoice Detail, compatible with IRD guidelines.
        </small>
    </h1>
    <p> Go <a target="_blank" href="#"> there</a> for regular sales or non tax invoice</p>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>
<div class='row'>
    <div class='col-md-12'>
        <div class="box-body">
            <div id="orderFields" style="display: none;">
                <table class="table">
                    <tbody id="more-tr">
                        <tr>
                             <td class="p_sn"></td>
                            <td>
                                <select class="form-control input-sm select2 product_id select-product-id-2" name="product_id[]" required="required">
                                    <option value="">Select Product</option>
                                    @foreach($products as $key => $pk)
                                    <option value="{{ $pk->id }}" @if(isset($orderDetail->product_id) && $orderDetail->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control input-sm price" name="price[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required" autocomplete="off">
                            </td>
                            <td>
                                <input type="number" class="form-control input-sm quantity" name="quantity[]" placeholder="Quantity" step=".01" required="required" autocomplete="off">
                            </td>
                            <td>
                                <input type="number" name="dis_amount[]" class="form-control input-sm discount_amount_line input-sm" placeholder="Discount" step="any">
                            </td>
                           
                            <td>
                                <select class="form-control input-sm units" name="unit[]">
                                    <option value="">Unit</option>
                                    @foreach($units as $unit)
                                    <option value="{{$unit->id}}">{{$unit->name}}({{ $unit->symbol }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="col-sm-1">
                                <select class="form-control input-sm tax_rate_line input-sm" name="tax[]">
                                    <option value="0">Exempt(0)</option>
                                    <option value="13">VAT(13)</option>
                                </select>
                            </td>

                            <td style="display:block;">
                                <input type="number" class="form-control input-sm tax_amount_line input-sm" name="tax_amount[]" value="0" readonly="readonly" />
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
                                <input type="text" class="form-control input-sm price" name="custom_items_price[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required" autocomplete="off">
                            </td>

                            <td>
                                <input type="number" class="form-control input-sm quantity" name="custom_items_qty[]" placeholder="Quantity" step=".01" required="required" autocomplete="off">
                            </td>
                            <td>
                                <select class="form-control input-sm" name="custom_unit[]">
                                    <option value="">Unit</option>
                                    @foreach($units as $unit)
                                    <option value="{{$unit->id}}">{{$unit->name}}({{ $unit->symbol }})</option>
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
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                    <form method="POST" action="#" id="myinvoiceform">
                        {{ csrf_field() }}

                        <div class="">

                            <div class="col-md-12">
                                <div class="callout bg-info">
                                    <label>Select Customer <i class="imp">*</i></label>
                                    <select class="customer_id select2" name="customer_id" required="required" id='client_id'>
                                        <option class="form-control input-sm input input-lg" value="">Select Customer</option>
                                        @if(isset($clients))
                                        @foreach($clients as $key => $uk)
                                        <option value="{{ $uk->id }}" @if($invoice && $uk->id ==
                                            $invoice->client_id){{ 'selected="selected"' }}@endif>{{ '('.env('APP_CODE'). $uk->id.') '.$uk->name.' -'.$uk->organization }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    &nbsp;&nbsp;
                                </div>
                            </div>

                            <div class="hide_on_tablet">
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {!! Form::label('termsbill_no', 'Bill Number.') !!}
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clone"></i>
                                                </div>
                                                {!! Form::number('bill_no',$invoice->bill_no, ['class' => 'form-control input-sm','min'=>1]) !!}
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {!! Form::label('terms', 'Payment Terms') !!}
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-file"></i>
                                                </div>
                                                  {!! Form::select('terms',[
                                                'custom' => 'Custom','net15'=>'Net 15', 'net30'=>'Net 30', 'net45'=>'Net 45','net60'=>'Net 60'],
                                                $invoice->terms, ['class' => 'form-control input-sm']) !!}
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>






                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Invoice Date:</label>

                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control input-sm pull-right datepicker date-toggle-nep-eng"  name="bill_date" value="{{  $invoice->bill_date}}" id="bill_date" required="">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Dipatch Date:</label>

                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control input-sm pull-right datepicker date-toggle-nep-eng" name="due_date" value="{{ $invoice->due_date }}" id="due_date">
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
                                                <input type="text" name="customer_pan" value="{{ old('customer_pan') ??  $invoice->customer_pan }}" class="form-control input-sm pull-right" id="pan_no" onKeyUp="if(this.value>999999999){this.value='999999999';}else if(this.value<0){this.value='0';}">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>

                                    <div class="col-md-3 form-group" style="">
                                        <label for="comment">Person Name</label>
                                        <input type="text" name="name" id="name" class="form-control input-sm" value="{{ $invoice->name }}">
                                    </div>

                                    <div class="col-md-3 form-group" style="">
                                        <label for="position">Position</label>
                                        <input type="text" name="position" class="form-control input-sm" id="position" value="{{ $invoice->position }}">
                                    </div>

                                    <div class="col-md-3 form-group" style="">
                                        <label for="user_id">Salesperson</label>
                                        <input type="text" name="user_id" value="{{$invoice->sales_person}}" class="form-control input-sm input-sm" id="user_id">
                                        {{-- {!! Form::select('user_id', $users, \Auth::user()->id, ['class' => 'form-control input-sm input-sm', 'id'=>'user_id']) !!} --}}
                                    </div>

                                    <div class="col-md-3 form-group" style="">
                                        <label for="user_id">Location</label>
                                        {!! Form::select('from_stock_location', [''=>'Select']+$productlocation, $invoice->from_stock_location, ['class' => 'form-control input-sm label-default']) !!}
                                    </div>

                                    <div class="col-md-3 form-group" style="">
                                        <label for="user_id">Is renewal</label>
                                        {!! Form::select('is_renewal', ['0'=>'No','1'=>'Yes'], $invoice->is_renewal, ['class' => 'form-control input-sm']) !!}
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label >Outlets</label>
                                        <select name="outlet_id" class="form-control input-sm searchable">

                                            @foreach($outlets as $key=>$out)
                                                <option value="{{ $out->id }}" @if( $out->id ==  $invoice->outlet_id) selected="" @endif>
                                                    {{$out->name}}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="col-md-3 form-group" style="">
                                        <label for="position">Invoice Number</label>
                                        <input type="text" readonly="readonly" name="position" class="form-control input-sm" id="position" value="{{ $invoice->bill_no }}">
                                    </div>

                                    <div class="col-md-3 form-group" style="">
                                        <label for="position">Fiscal Year</label>
                                        <input type="text" readonly="readonly" name="position" class="form-control input-sm" id="position" value="{{ $invoice->fiscal_year }}">
                                    </div>

                                    <div class="col-md-3 form-group" style="">
                                        <label for="position"><i class="fa fa-database"></i> CBMS Server Sync </label>
                                        <input type="text" readonly="readonly" name="position" class="form-control input-sm" id="position" value="{{ $invoice->sync_with_ird }}">
                                    </div>

                                </div>
                            </div>

                            <div class="clearfix"></div><br /><br />
                            <hr />
                            <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="bg-primary">
                                        <th class="col-md-1">S.N</th>
                                        <th >Particulars *</th>
                                        <th class="col-md-1">Price *</th>
                                        <th class="col-md-1">Quantity *</th>
                                        <th class="col-md-1" title="Discount">Dis</th>
                                        <th class="col-md-2">Unit</th>
                                        <th>Tax Rate</th>
                                        <th>Tax Amount</th> 
                                        <th class="col-md-2">Total</th>
                                    </tr>
                                </thead>

                                <tbody id='multipleDiv'>
                                    <tr class="multipleDiv">

                                    </tr>
                                    <?php
                                        $count=1;
                                    ?>
                                    @foreach($invoice_details as $key=>$details)
                                    @if($details->is_inventory)
                                    <tr>
                                         <td class="p_sn">{{$count++}}</td>
                                        <td>
                                            <select class="form-control input-sm select2 product_id select-product-id" name="product_id[]" required="required">
                                                <option value="">Select Product</option>
                                                @foreach($products as $key => $pk)
                                                <option value="{{ $pk->id }}" @if(isset($details->product_id) && $details->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control input-sm price" name="price[]" placeholder="Price" value="@if(isset($details->price)){{ $details->price }}@endif" required="required">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control input-sm quantity" name="quantity[]" placeholder="Quantity" step=".01" required="required" value="{{ $details->quantity }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control input-sm discount_amount_line" name="dis_amount[]" value="{{$details->discount}}">
                                        </td>   
                                        <td>
                                            <select class="form-control input-sm units" name="unit[]">
                                                <option value="">Unit</option>
                                                @foreach($units as $unit)
                                                <option value="{{$unit->id}}" @if($details->unit == $unit->id)selected @endif>{{$unit->name}}({{ $unit->symbol }})</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        
                                        <td>
                                            <select class="form-control input-sm tax_rate_line input-sm" name="tax[]">
                                                <option value="0" @if($details->tax== "0")selected @endif>Exempt(0)</option>
                                                <option value="13" @if($details->tax== "13")selected @endif>VAT(13)</option>
                                            </select>
                                        </td>
                                        <td style="display:block;">
                                            <input type="number" class="form-control input-sm tax_amount_line input-sm" name="tax_amount[]" value="{{$details->tax_amount}}" readonly="readonly" />
                                        </td>
                                        <td>
                                            <input type="number" class="form-control input-sm total" name="total[]" placeholder="Total" value="@if(isset($details->total)){{ $details->total }}@endif" readonly="readonly" style="float:left; width:80%;">
                                            <a href="javascript::void(1);" style="width: 10%;">
                                                <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @else
                                    <tr>
                                         <td class="p_sn"></td>
                                        <td>
                                            <input type="text" class="form-control input-sm product" name="custom_items_name[]" value="{{ $details->description }}" placeholder="Product">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control input-sm price" name="custom_items_price[]" placeholder="Price" value="@if(isset($details->price)){{ $details->price }}@endif" required="required">
                                        </td>

                                        <td>
                                            <input type="number" class="form-control input-sm quantity" name="custom_items_qty[]" placeholder="Quantity" step=".01" value="{{  $details->quantity }}" required="required">
                                        </td>
                                        <td>
                                            <select class="form-control input-sm" name="custom_unit[]">
                                                <option value="">Unit</option>
                                                @foreach($units as $unit)
                                                <option value="{{$unit->id}}" @if($details->unit == $unit->id)selected @endif>{{$unit->name}}({{ $unit->symbol }})</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <input type="number" class="form-control input-sm total" name="custom_total[]" placeholder="Total" value="@if(isset($details->total)){{ $details->total }}@endif" readonly="readonly" style="float:left; width:80%;">
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
                                        <td colspan="8" style="text-align: right;">Amount</td>
                                        <td id="sub-total">{{ $invoice->total_amount }}</td>
                                        <td>&nbsp; <input type="hidden" name="subtotal" id="subtotal" value="{{ $invoice->total_amount }}"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" style="text-align: right;">Order Discount</td>
                                        <td id='discount-amount'>0.00</td>
                                        <td>
                                            <input type="hidden" name="discount_amount" value="0" id='discount_amount'>
                                        </td>
                                    </tr>

                                    {{-- <tr>
                                        <td colspan="5" style="text-align: right;">Order Discount (%)</td>
                                        <td><input type="number" min="0" name="discount_percent" id="discount_percent" value="{{ $invoice->discount_percent }}" onKeyUp="if(this.value>99){this.value='99';}else if(this.value<0){this.value='0';}"></td>
                                        <td>&nbsp;</td>
                                    </tr>

                                     <tr>
                                        <td colspan="5" style="text-align: right;">Order Discount Amount</td>
                                        <td><input type="number" min="0" name="discount_amount" id="discount_amount" value="{{ $invoice->discount_amount }}" ></td>
                                        <td>&nbsp;</td>
                                    </tr> --}}
                                    <tr>
                                        <td colspan="8" style="text-align: right;">Non Taxable Amount</td>
                                        <td id="non-taxable-amount">@if($invoice->tax_amount>0) 0 @else{{$invoice->total_amount}} @endif</td>
                                        <td>&nbsp;<input type="hidden" name="non_taxable_amount"
                                            id="nontaxableamount" value="@if($invoice->tax_amount>0) 0 @else{{$invoice->total_amount}}@endif">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" style="text-align: right;">Taxable Amount</td>
                                        <td id="taxable-amount">{{ $invoice->taxable_amount }}</td>
                                        <td>&nbsp; <input type="hidden" name="taxable_amount" id="taxableamount" value="{{  $invoice->taxable_amount }}"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" style="text-align: right;">Tax Amount (13%)</td>
                                        <td id="taxable-tax">{{ $invoice->tax_amount }}</td>
                                        <td>&nbsp; <input type="hidden" name="taxable_tax" id="taxabletax" value="{{  $invoice->tax_amount }}"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" style="text-align: right; font-weight: bold;font-size: 16px">Total Amount ({{ env('APP_CURRENCY')}}) </td>
                                        <td style="font-size: 16px;font-weight: bold;" id="total">{{ $invoice->total_amount }}</td>
                                        <td>
                                            <input type="hidden" name="total_tax_amount" id="total_tax_amount" value="{{  $invoice->tax_amount }}">
                                            <input type="hidden" name="final_total" id="total_" value="{{  $invoice->total_amount }}">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                            <br />


                        <div class="hide_on_tablet">
                            <div class="col-md-6 form-group" style="margin-top:5px;">
                                <label for="comment">Customer Notes</label>
                                <textarea class="form-control input-sm TextBox comment" name="comment">@if(isset($invoice->comment)){{ $invoice->comment }}@endif</textarea>
                            </div>

                            <div class="col-md-6 form-group" style="margin-top:5px;">
                                <label for="address">Address</label>
                                <textarea id="physical_address" class="form-control input-sm TextBox address" name="address">@if(isset($invoice->address)){{ $invoice->address }}@endif</textarea>
                            </div>
                        </div>


                        </div>
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

</script>

<script>

    function getSn(){

       $('#multipleDiv tr').each(function(index,val){

            if(index > 0){
                $(this).find('.p_sn').html(index);
            }

       });
    }

    const dateRange = {
    <?php $currentFiscalyear = FinanceHelper::cur_fisc_yr();?>
    minDate: `{{ $currentFiscalyear->start_date }}`,
    maxDate: `{{ $currentFiscalyear->end_date }}`
}
 $('.date-toggle-nep-eng').nepalidatetoggle();
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
                    parentDiv.find('.units').val("");
                    parentDiv.find('.units').val(result.units?.id);
                    parentDiv.find('.description').val(result.description);
                     parentDiv.find('.discount_amount_line').val();
                    if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
                        var total = parentDiv.find('.quantity').val() * obj.price - parentDiv.find('.discount_amount_line').val();
                    } else {
                        var total = obj.price - parentDiv.find('.discount_amount_line').val();
                    }

                    var tax = parentDiv.find('.tax_rate_line').val();
                    
                    if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
                        tax_amount = total * Number(tax) / 100;
                        parentDiv.find('.tax_amount').val(tax_amount);
                        total = total + tax_amount - parentDiv.find('.discount_amount_line').val();
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
                    var total = $(this).val() * parentDiv.find('.price').val() - parentDiv.find('.discount_amount_line').val();
                else
                    var total = parentDiv.find('.price').val() - parentDiv.find('.discount_amount_line').val();

                var tax = parentDiv.find('.tax_rate').val();
                if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
                    tax_amount = total * Number(tax) / 100;
                    parentDiv.find('.tax_amount').val(tax_amount.toFixed(2));
                    total = total + tax_amount - parentDiv.find('.discount_amount_line').val();
                } else
                    parentDiv.find('.tax_amount').val('0');

                if (isNumeric(total) && total != '') {
                    parentDiv.find('.total').val(total.toFixed(2));
                    calcTotal();
                }
                //console.log( index + ": " + $(this).text() );
            });
        } else {
            $('.total').val('0');
            $('.tax_amount').val('0');
            calcTotal();
        }

        let cust_id = $(this).val();
        $.get('/admin/getpanno/' + cust_id, function(data, status) {
            $('#pan_no').val(data.pan_no);
            $('#physical_address').val(data.physical_address);
        });
    });

    $(document).ready(function(){
        $('.select-product-id').select2({
            width:"100%"
        })
    })
    $(document).on('change', '.quantity', function() {
        var parentDiv = $(this).parent().parent();
        if (isNumeric(this.value) && this.value != '') {
            if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
                var total = parentDiv.find('.price').val() * this.value - parentDiv.find('.discount_amount_line').val();
            } else
                var total = '';
        } else
            var total = '';

        var tax = parentDiv.find('.tax_rate').val();
        if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
            tax_amount = total * Number(tax) / 100;
            parentDiv.find('.tax_amount').val(tax_amount.toFixed(2));
            total = total + tax_amount - parentDiv.find('.discount_amount_line').val();
        } else
            parentDiv.find('.tax_amount').val('0');

        parentDiv.find('.total').val(total.toFixed(2));
        calcTotal();
    });

    $(document).on('change', '.price', function() {
        var parentDiv = $(this).parent().parent();
        if (isNumeric(this.value) && this.value != '') {
            if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
                var total = parentDiv.find('.quantity').val() * this.value - parentDiv.find('.discount_amount_line').val();
            } else
                var total = '';
        } else
            var total = '';

        var tax = parentDiv.find('.tax_rate').val();
        if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
            tax_amount = total * Number(tax) / 100;
            parentDiv.find('.tax_amount').val(tax_amount.toFixed(2));
            total = total + tax_amount- parentDiv.find('.discount_amount_line').val();
        } else
            parentDiv.find('.tax_amount').val('0');

        parentDiv.find('.total').val(total.toFixed(2));
        calcTotal();
    });

    $(document).on('change', '.tax_rate', function() {
        var parentDiv = $(this).parent().parent();

        if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
            var total = parentDiv.find('.price').val() * Number(parentDiv.find('.quantity').val())- parentDiv.find('.discount_amount_line').val();
        } else
            var total = '';

        var tax = $(this).val();
        if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
            tax_amount = total * Number(tax) / 100;
            parentDiv.find('.tax_amount').val(tax_amount.toFixed(2));
            total = total + tax_amount - parentDiv.find('.discount_amount_line').val();
        } else
            parentDiv.find('.tax_amount').val('0');

        parentDiv.find('.total').val(total.toFixed(2));
        calcTotal();
    });

    function adjustTotalNonTaxable(){


var  taxableAmount = 0;

var nontaxableAmount = 0;

var taxAmount = 0;



var taxableAmount = 0;

var nontaxableAmount = 0;
// let tds_rate = Number(parent.find('.tds_rate_line').val());

$('.tax_rate_line').each(function(){

    let parent = $(this).parent().parent();

    let tax_rate = Number(parent.find('.tax_amount_line').val());
    var total = Number(parent.find('.total').val());

    if($(this).val() == 0 ){


        nontaxableAmount += total;

    }else{

        taxableAmount +=  (Number(parent.find('.price').val())* Number(parent.find('.quantity').val()))- Number(parent.find('.discount_amount_line').val());

        taxAmount += tax_rate;
    }

});

$('#non-taxable-amount').text(nontaxableAmount.toLocaleString());

$('#nontaxableamount').val(nontaxableAmount);

$('#taxable-amount').text((taxableAmount ).toFixed(2).toLocaleString());

$('#taxableamount').val((taxableAmount ).toFixed(2) );

$('#taxabletax').val(taxAmount.toFixed(2));

$('#taxable-tax').text(taxAmount.toFixed(2).toLocaleString());

var totalDiscount = 0;
$('.discount_amount_line').each(function(){

    totalDiscount += Number($(this).val());


});
$('#discount-amount').text(totalDiscount);
$('#discount_amount').val(totalDiscount);
}
$(document).on('change', '.total', function() {

var parentDiv = $(this).parent().parent();
if (isNumeric(this.value) && this.value != '') {
    var total = this.value;
    if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
        var price = total / parentDiv.find('.quantity').val();
    } else
    var price = '';
} else
var price = '';

var tax = parentDiv.find('.tax_rate_line').val();
if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
    tax_amount = total * Number(tax) / 100;
    parentDiv.find('.tax_amount_line').val(tax_amount);
    total = total + tax_amount - parentDiv.find('.discount_amount_line').val();
} else
parentDiv.find('.tax_amount_line').val('0');

parentDiv.find('.price').val(price);
adjustTax($(this));
calcTotal();
});

function adjustTax(ev){

        let parent = ev.parent().parent();

        let total = Number(parent.find('.total').val());

        let discount = Number(parent.find('.discount_amount_line').val());
        console.log(discount);
        let total_with_discount = total - discount;

        parent.find('.total').val(total_with_discount);
        console.log(total_with_discount);
        let tax_rate = Number(parent.find('.tax_rate_line').val());

        let tax_amount = (tax_rate / 100 * total_with_discount);
        parent.find('.tax_amount_line').val(tax_amount.toFixed(2));

        let amount_with_tax = total_with_discount;

        parent.find('.total').val(amount_with_tax);
        // let tds_rate = Number(parent.find('.tds_rate_line').val());

        // let tds_amount = (tds_rate /100 * total_with_discount);
        // parent.find('.tds_rate_amount').val(tds_amount.toFixed(2));
}
    $(document).on('change', '.tax_rate_line', function() {
					var parentDiv = $(this).parent().parent();

					if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
						var total = parentDiv.find('.price').val() * Number(parentDiv.find('.quantity').val()) - parentDiv.find('.discount_amount_line').val();
					} else
					var total = '';

					var tax = $(this).val();
                   
					if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
						tax_amount = total * Number(tax) / 100;
						parentDiv.find('.tax_amount_line').val(tax_amount);
						total = total - parentDiv.find('.discount_amount_line').val();
						// + tax_amount
					} else
					parentDiv.find('.tax_amount_line').val('0');

					parentDiv.find('.total').val(total);
					calcTotal();
	});
    $(document).on('change','.tax_rate_line',function(){
        let parent = $(this).parent().parent();
        parent.find('.quantity').trigger('change');
    });


    // $("#addMore").on("click", function() {
    //     //$($('#orderFields').html()).insertBefore(".multipleDiv");
    //     $(".multipleDiv").after($('#orderFields #more-tr').html());
    //     $(".multipleDiv").next('tr').find('.select-product-id-2').select2({
    //         width: '100%'
    //     });
    //      $(".multipleDiv").next('tr').find('.quantity').val('1');
    //     getSn();
    // });
    // $("#addCustomMore").on("click", function() {
    //     //$($('#orderFields').html()).insertBefore(".multipleDiv");
    //     $(".multipleDiv").after($('#CustomOrderFields #more-custom-tr').html());
    //      $(".multipleDiv").next('tr').find('.quantity').val('1');
    //     getSn();
    // });

    $(document).on('click', '.remove-this', function() {
        $(this).parent().parent().parent().remove();
        calcTotal();
        getSn();
    });

    function calcTotal() {

    adjustTotalNonTaxable();
    var subTotal = 0;
    var taxableAmount = 0;

    var total = 0;
    var tax_amount = 0;
    var taxableTax = 0;
    var tds= 0;
    var netreceivable=0;
    $(".total").each(function(index) {
        if (isNumeric($(this).val()))
            subTotal = Number(subTotal) + Number($(this).val());
    });
    $(".tax_amount").each(function(index) {
        if (isNumeric($(this).val()))
            tax_amount = Number(tax_amount) + Number($(this).val());
    });

    $('#sub-total').html(subTotal.toLocaleString());
    $('#subtotal').val(subTotal);


    total = Number( $('#nontaxableamount').val() ) + Number( $("#taxableamount").val() ) +
    Number($('#taxabletax').val());

    var discount_amount = $('#discount_amount').val();

    var vat_type = $('#vat_type').val();

    console.log(discount_amount);

    $('#total_tax_amount').val(tax_amount);

    $('#total').html(total.toLocaleString());
    $('#total_').val(total);

    $(".tds_rate_amount").each(function(index) {
        if (isNumeric($(this).val()))
            tds = Number(tds) + Number($(this).val());
    });	
    $('#tdstotal').html(tds.toLocaleString());
    $('#tds_total').val(tds);

    netreceivable= total - Number( $("#tds_total").val()) ;
    $('#netreceivable').html(netreceivable.toLocaleString());
    $('#net_receivable').val(netreceivable);

}

    $(document).on('keyup', '#discount_amount', function() {
        calcTotal();
    });

</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.customer_id').select2();

         $('.searchable').select2();
    });

</script>

<script type="text/javascript">
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

    function openwindow() {
        var win = window.open('/admin/clients/modals?relation_type=customer', '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=600, height=650');
    }

    function HandlePopupResult(result) {
        if (result) {
            let clients = result.clients;
            var option = '';
            for (let c of clients) {
                option = option + `<option value='${c.id}'>${c.name}</option>`;
            }
            $('#client_id').html(option);
            setTimeout(function() {
                $('.customer_id').select2('destroy');
                $('#client_id').val(result.lastcreated);
                $("#ajax_status").after("<span style='color:green;' id='status_update'>Client sucessfully created</span>");
                $('#status_update').delay(3000).fadeOut('slow');
                 $('.customer_id').select2();
            }, 500);
        } else {
            $("#ajax_status").after("<span style='color:red;' id='status_update'>failed to create clients</span>");
            $('#status_update').delay(3000).fadeOut('slow');
        }
    }
    $(document).on('change','.discount_amount_line',function(){

        let parent = $(this).parent().parent();

        parent.find('.quantity').trigger('change');

})
//     $('#discount_percent').on('change keyup',function(){
//         var parentDiv = $(this).parent().parent();
//         let val = $(this).val();

//         if(val.trim() == ''){
//             $('#discount_amount').attr('readonly',false);
//         }else{
//             $('#discount_amount').attr('readonly',true);
//         }

//         let subtotal = Number($('#subtotal').val());

//         let discountAmount = (val / 100) * subtotal;
//         console.log(discountAmount);
//         // $('#discount_amount').val(discountAmount.toFixed(2));
        

//         calcTotal();
// });
async function checkvalidation(){
        const oldHtml = $('#invoiceSubmitButton').html()

        const collection = document.getElementsByClassName("product_id");

        if(collection.length<=1)
        {
            alert('Select Product Please');
            return false;
        }
        $('#invoiceSubmitButton').html('<i class="fa fa-spinner fa-spin"></i> Processing')
        for (let i = 1; i < collection.length; i++)
        {
            const response = await $.get(`/admin/invoice/AjaxValidation/${collection[i].value}`)
            if (response.flags == false) {
                alert(`please link product type master and product division for ${response.name}`)
                $('#invoiceSubmitButton').html(oldHtml)
                return false;
            }
        }
        console.log($('#myinvoiceform'))
        $('#myinvoiceform').submit()
        return true;
    }

</script>
@endsection
