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
            height:100%;
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

        .col-md-4 { background: skyblue; border: 1px solid #ccc; }
    </style>
@endsection

@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">


                <div id="orderFields" style="display: none;">
                    <table class="table">
                        <tbody id="more-tr">
                            <tr>
                                <td>
                                    <select class="form-control product_id" name="description[]" required="required">
                                    @if(isset($products))
                                            <option value="">Select Product</option>
                                        @foreach($products as $key => $pk)

                                            <option data-tokens="{{ $pk->name}}" value="{{ $pk->id }}"@if(isset($orderDetail->product_id) && $orderDetail->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}</option>
                                        @endforeach
                                    @endif
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control price" name="unit_price[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required">
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity" name="item_quantity[]" placeholder="Quantity" min="1" value="1" required="required">
                                </td>
                                <td>
                                    <select required name="tax_id[]" class="form-control tax_rate">
                                        @foreach(config('tax.taxes') as $dk => $dv)

                                        <option value="{{ $dk }}" @if(isset($orderDetail->total) && $orderDetail->tax_amount == $dk) selected="selected" @endif>{{ $dv }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control tax_amount" name="tax_amount[]" @if(isset($orderDetail->tax_amount)) value="{{ $orderDetail->tax_amount }}" @else value="0" @endif readonly="readonly">
                                </td>
                                <td>
                                    <input type="number" class="form-control total" name="total[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly" style="float:left; width:80%;">
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
                                  <input type="text" class="form-control product" name="custom_items_name[]" value="" placeholder="Product">
                                </td>
                                <td>
                                    <input type="text" class="form-control price" name="custom_items_amount[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required">
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity" name="custom_items_qty[]" placeholder="Quantity" min="1" value="1" required="required">
                                </td>
                                <td>
                                    <select required name="tax_id_custom[]" class="form-control tax_rate">
                                        @foreach(config('tax.taxes') as $dk => $dv)
                                        <option value="{{ $dk }}" @if(isset($orderDetail->total) && $orderDetail->tax_amount == $dk) selected="selected" @endif>{{ $dv }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control tax_amount" name="tax_amount[]" @if(isset($orderDetail->tax_amount)) value="{{ $orderDetail->tax_amount }}" @else value="0" @endif readonly="readonly">
                                </td>
                                <td>
                                    <input type="number" class="form-control total" name="custom_total[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly" style="float:left; width:80%;">
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
                        <form method="post" action="/admin/sales">
                            {{ csrf_field() }}

                            <div class="panel-body">
                                <div class="col-md-3" style="margin-bottom:;">
                                    <div class="form-group">
                                        <label>Select Payment Method <i class="imp">*</i></label>
                                        <select class="form-control customer_id select2" name="payment_id" required="required">
                                        <option  value="">Select payment Method</option>

                                        <option value="1">Online</option>
                                        <option value="2">E-sewa</option>
                                        <option value="3">Everest bank</option>
                                        <option value="4">NIC Bank</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3" style="margin-bottom:;">
                                    <div class="form-group">
                                        <label>Select Leads <i class="imp">*</i></label>
                                        <select class="form-control customer_id select2" name="lead_id">
                                        <option class="input input-lg" value="">Select Leads</option>
                                        @if(isset($leads))
                                            @foreach($leads as $key => $uk)
                                                <option value="{{ $uk->id }}">{{ '('.$uk->id.') '.$uk->name.' ('.$uk->location.')' }}</option>
                                            @endforeach
                                        @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3" style="margin-bottom:;">
                                    <div class="form-group">
                                        <label>Status <i class="imp">*</i></label>
                                        <select class="form-control status select2" name="payment_term" required="required">
                                        <option value="2" @if(isset($orderDetail->product_id) && $orderDetail->product_id == $pk->id) selected="selected"@endif>Unpaid</option>
                                        <option value="3">Partial Payment</option>
                                        <option value="1">Paid</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3" style="margin-bottom:;">
                                    <div class="form-group">
                                        <label>Date <i class="imp">*</i></label>
                                        <input type="text" class="form-control" name="ord_date" id="datepicker" value="" placeholder="Date">
                                    </div>
                                </div>


                                <div class="clearfix"></div>
                                 <div class="col-md-3">
                                    <a href="javascript::void(0)" class="btn btn-primary btn-xs" id="addCustomMore" style="float: left;">
                                        <i class="fa fa-plus"></i> <span>Add Custom Products Item</span>
                                    </a>
                                </div>

                                <div class="col-md-3">
                                    <a href="javascript::void(0)" class="btn btn-primary btn-xs" id="addMore" style="float: left;">
                                        <i class="fa fa-plus"></i> <span>Add Products Item</span>
                                    </a>
                                </div>

                               

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product *</th>
                                            <th>Price *</th>
                                            <th>Quantity *</th>
                                            <th>Tax(%) *</th>
                                            <th>Tax({{ env('APP_CURRENCY') }})</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr class="multipleDiv">
                                            <td>
                                                <select class="form-control select2 product_id" name="description[]" required="required">
                                                @if(isset($products))
                                                        <option value="">Select Product</option>
                                                    @foreach($products as $key => $pk)

                                                        <option value="{{ $pk->name }}"@if(isset($orderDetail->product_id) && $orderDetail->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}</option>
                                                    @endforeach
                                                @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control price" name="unit_price[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control quantity" name="item_quantity[]" placeholder="Quantity" min="1" value="1" required="required">
                                            </td>
                                            <td>
                                                <select required name="tax_id[]" class="form-control tax_rate">
                                                    @foreach(config('tax.taxes') as $dk => $dv)
                                                    <option value="{{ $dk }}" @if(isset($orderDetail->tax) && $orderDetail->tax == $dk) selected="selected" @endif>{{ $dv }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control tax_amount" name="tax_amount[]" @if(isset($orderDetail->tax_amount)) value="{{ $orderDetail->tax_amount }}" @else value="0" @endif readonly="readonly">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control total" name="total[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly">
                                            </td>
                                        </tr>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="3" style="text-align: right;">Subtotal</td>
                                            <td id="sub-total">0.00</td>
                                            <td>&nbsp; <input type="hidden" name="subtotal" id="subtotal" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="text-align: right;">Order Discount</td>
                                            <td><input type="number" min="0" name="discount" id="discount_amount" value=""></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="text-align: right;">Discount Note</td>
                                            <td>
                                                <input type="text" name="discount_note" id="discount_note" value="">
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="text-align: right; font-weight: bold;">Total</td>
                                            <td id="total">0.00</td>
                                            <td>
                                                <input type="hidden" name="total_tax_amount" id="total_tax_amount" value="0">
                                                <input type="hidden" name="final_total" id="total_" value="0">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>


                                <div class="col-md-4">
                                  <div class="form-group">
                                      <label for="exampleInputEmail1">Reference<span class="text-danger"> *</span></label>
                                      <div class="input-group">
                                         <div class="input-group-addon">INV-</div>

                                         <input id="reference_no" class="form-control" name="reference" value="{{ sprintf('%04d', $invoice_count+1)}}" type="text">
                                         <input type="hidden"  name="reference" id="reference_no_write" value="">

                                      </div>
                                      <span id="errMsg" class="text-danger"></span>
                                  </div>
                                </div>

                                <div class="col-md-3 form-group" style="margin-top:5px;">
                                    <label for="comment">Comment</label>
                                    <textarea class="form-control TextBox comment" name="comments">@if(isset($order->comment)){{ $order->comment }}@endif</textarea>
                                </div>

                                <div class="col-md-3 form-group" style="margin-top:5px;">
                                    <label for="address">Address</label>
                                    <textarea class="form-control TextBox address" name="address">@if(isset($orderDetail->address)){{ $orderDetail->address }}@endif</textarea>
                                </div>

                            </div>
                            <div class="panel-footer">
                                <button type="submit" class="btn btn-social btn-foursquare">
                                    <i class="fa fa-save"></i>Save Order
                                </button>
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

<script>

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

$(document).on('change', '.product_id', function() {
    var parentDiv = $(this).parent().parent();
    if(this.value != 'NULL')
    {
        var _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
              type: "POST",
              contentType: "application/json; charset=utf-8",
              url: "/admin/products/GetProductDetailAjax/"+this.value+'?_token='+_token,
              success: function (result) {
                var obj = jQuery.parseJSON(result.data);
                parentDiv.find('.price').val(obj.price);

                if(isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '')
                {
                    var total = parentDiv.find('.quantity').val() * obj.price;
                }
                else
                {
                    var total = obj.price;
                }

                var tax = parentDiv.find('.tax_rate').val();
                if(isNumeric(tax) && tax != 0 && (total != 0 || total != ''))
                {
                    tax_amount = total * parseInt(tax) / 100;
                    parentDiv.find('.tax_amount').val(tax_amount);
                    total = total + tax_amount;
                }
                else
                    parentDiv.find('.tax_amount').val('0');

                parentDiv.find('.total').val(total);
                calcTotal();
              }
         });
    }
    else
    {
        parentDiv.find('.price').val('');
        parentDiv.find('.total').val('');
        parentDiv.find('.tax_amount').val('');
        calcTotal();
    }
});

$(document).on('change', '.customer_id', function() {
    if(this.value != '')
    {
        $(".quantity").each(function(index) {
            var parentDiv = $(this).parent().parent();
            if(isNumeric($(this).val()) && $(this).val() != '')
                var total = $(this).val() * parentDiv.find('.price').val();
            else
                var total = parentDiv.find('.price').val();

            var tax = parentDiv.find('.tax_rate').val();
            if(isNumeric(tax) && tax != 0 && (total != 0 || total != ''))
            {
                tax_amount = total * parseInt(tax) / 100;
                parentDiv.find('.tax_amount').val(tax_amount);
                total = total + tax_amount;
            }
            else
                parentDiv.find('.tax_amount').val('0');

            if(isNumeric(total) && total != '')
            {
                parentDiv.find('.total').val(total);
                calcTotal();
            }
            //console.log( index + ": " + $(this).text() );
        });
    }
    else
    {
        $('.total').val('0');
        $('.tax_amount').val('0');
        calcTotal();
    }
});

$(document).on('change', '.quantity', function() {
    var parentDiv = $(this).parent().parent();
    if(isNumeric(this.value) && this.value != '')
    {
        if(isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '')
        {
            var total = parentDiv.find('.price').val() * this.value;
        }
        else
            var total = '';
    }
    else
        var total = '';

    var tax = parentDiv.find('.tax_rate').val();
    if(isNumeric(tax) && tax != 0 && (total != 0 || total != ''))
    {
        tax_amount = total * parseInt(tax) / 100;
        parentDiv.find('.tax_amount').val(tax_amount);
        total = total + tax_amount;
    }
    else
        parentDiv.find('.tax_amount').val('0');

    parentDiv.find('.total').val(total);
    calcTotal();
});

$(document).on('change', '.price', function() {
    var parentDiv = $(this).parent().parent();
    if(isNumeric(this.value) && this.value != '')
    {
        if(isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '')
        {
            var total = parentDiv.find('.quantity').val() * this.value;
        }
        else
            var total = '';
    }
    else
        var total = '';

    var tax = parentDiv.find('.tax_rate').val();
    if(isNumeric(tax) && tax != 0 && (total != 0 || total != ''))
    {
        tax_amount = total * parseInt(tax) / 100;
        parentDiv.find('.tax_amount').val(tax_amount);
        total = total + tax_amount;
    }
    else
        parentDiv.find('.tax_amount').val('0');

    parentDiv.find('.total').val(total);
    calcTotal();
});

$(document).on('change', '.tax_rate', function() {
    var parentDiv = $(this).parent().parent();

    if(isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '')
    {
        var total = parentDiv.find('.price').val() * parseInt(parentDiv.find('.quantity').val());
    }
    else
        var total = '';

    var tax = $(this).val();
    if(isNumeric(tax) && tax != 0 && (total != 0 || total != ''))
    {
        tax_amount = total * parseInt(tax) / 100;
        parentDiv.find('.tax_amount').val(tax_amount);
        total = total + tax_amount;
    }
    else
        parentDiv.find('.tax_amount').val('0');

    parentDiv.find('.total').val(total);
    calcTotal();
});

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

$("#addMore").on("click", function () {
     //$($('#orderFields').html()).insertBefore(".multipleDiv");
     $(".multipleDiv").after($('#orderFields #more-tr').html());
});

$("#addCustomMore").on("click", function () {
     //$($('#orderFields').html()).insertBefore(".multipleDiv");
     $(".multipleDiv").after($('#CustomOrderFields #more-custom-tr').html());
});

$(document).on('click', '.remove-this', function () {
    $(this).parent().parent().parent().remove();
    calcTotal();
});

function calcTotal()
{
    //alert('hi');
    var subTotal = 0;
    //var tax = parseInt($('#tax').val().replace('%', ''));
    var total = 0;
    var tax_amount = 0;
    $(".total").each(function(index) {
        if(isNumeric($(this).val()))
            subTotal = parseInt(subTotal) + parseInt($(this).val());
    });
    $(".tax_amount").each(function(index) {
        if(isNumeric($(this).val()))
            tax_amount = parseInt(tax_amount) + parseInt($(this).val());
    });
    $('#sub-total').html(subTotal);
    $('#subtotal').val(subTotal);

    var discount_amount = $('#discount_amount').val();

    if(isNumeric(discount_amount) && discount_amount != 0)
    {
        total = subTotal - parseInt(discount_amount);
    }
    else
    {
        total = subTotal;
    }
    $('#total').html(total);
    $('#total_tax_amount').val(tax_amount);
    $('#total_').val(total);
}

$(document).on('keyup', '#discount_amount', function () {
    calcTotal();
});
</script>

<script type="text/javascript">
         $(document).ready(function() {
    $('.customer_id').select2();
});
    </script>


    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script>
 $( function() {
   $( "#datepicker" ).datepicker();
 } );
 </script>

 <script type="text/javascript">

 var taxOptionList = "{!! $tax_type !!}";
    var taxOptionListCustom = "{!! $tax_type_custom !!}";
    $(document).ready(function(){
      var refNo ='INV-'+$("#reference_no").val();
      $("#reference_no_write").val(refNo);

      $("#customer").on('change', function(){
      var debtor_no = $(this).val();
      $.ajax({
        method: "POST",
        url: SITE_URL+"/sales/get-branches",
        data: { "debtor_no": debtor_no,"_token":token }
      })
        .done(function( data ) {
          var data = jQuery.parseJSON(data);
          if(data.status_no == 1){
            $("#branch").html(data.branchs);
          }
        });
      });
    });


    $(document).on('keyup', '#reference_no', function () {

        var val = $(this).val();

        if(val == null || val == ''){
         $("#errMsg").html("{{ trans('message.invoice.exist') }}");
          $('#btnSubmit').attr('disabled', 'disabled');
          return;
         }else{
          $('#btnSubmit').removeAttr('disabled');
         }

        var ref = 'INV-'+$(this).val();
        $("#reference_no_write").val(ref);
      // Check Reference no if available
      $.ajax({
        method: "POST",
        url: SITE_URL+"/sales/reference-validation",
        data: { "ref": ref,"_token":token }
      })
        .done(function( data ) {
          var data = jQuery.parseJSON(data);
          if(data.status_no == 1){
            $("#errMsg").html("{{ trans('message.invoice.exist') }}");
          }else if(data.status_no == 0){
            $("#errMsg").html("{{ trans('message.invoice.available') }}");
          }
        });
    });

 </script>

@endsection
