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
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">


                <div id="orderFields" style="display: none;">
                    <table class="table">
                        <tbody id="more-tr">
                            <tr>
                                <td>
                                    <select class="form-control product_id" name="product_id[]" required="required">
                                    @if(isset($products))
                                            <option value="">Select Product</option>
                                        @foreach($products as $key => $pk)
                                            <option data-tokens="{{ $pk->name}}" value="{{ $pk->id }}"@if(isset($orderDetail->product_id) && $orderDetail->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}</option>
                                        @endforeach
                                    @endif
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control price" name="price[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required">
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity" name="quantity[]" placeholder="Quantity" min="1" value="1" required="required">
                                </td>
                                <td>
                                    <select required name="tax[]" class="form-control tax_rate">
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
                        <tbody id="Custom-more-tr">
                            <tr>
                              <td>
                                <input type="text" class="form-control product" name="product_id[]" value="" placeholder="Product">
                              </td>
                                <td>
                                    <input type="text" class="form-control price" name="price[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required">
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity" name="quantity[]" placeholder="Quantity" min="1" value="1" required="required">
                                </td>
                                <td>
                                    <select required name="tax[]" class="form-control tax_rate">
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

                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        {!! Form::model( $order, ['route' => ['admin.orders.update', $order->id], 'method' => 'PUT'] ) !!}

                            <div class="panel-body">
                              <div class="col-md-3" style="margin-bottom:;">
                                  <div class="form-group">
                                      <label>Select Payment Method <i class="imp">*</i></label>
                                      <select class="form-control customer_id select2" name="customer_id" required="required">
                                      <option  value="">Select payment Method</option>

                                      <option value="online">Online</option>
                                      <option value="e-sewa">E-sewa</option>
                                      <option value="everest-bank">Everest bank</option>
                                      <option value="nic-bank">NIC Bank</option>

                                      </select>
                                  </div>
                              </div>

                              <div class="col-md-3" style="margin-bottom:;">
                                  <div class="form-group">
                                      <label>Select Leads <i class="imp">*</i></label>
                                      <select class="form-control customer_id select2" name="lead_id" required="required">
                                      <option class="input input-lg" value="">Select Leads</option>
                                      @if(isset($leads))
                                          @foreach($leads as $key => $uk)
                                              <option value="{{ $uk->id }}" @if($orderDetail && $uk->id == $orderDetail->customer_id){{ 'selected="selected"' }}@endif>{{ '('.$uk->id.') '.$uk->name.' ('.$uk->location.')' }}</option>
                                          @endforeach
                                      @endif
                                      </select>
                                  </div>
                              </div>

                              <div class="col-md-3" style="margin-bottom:;">
                                  <div class="form-group">
                                      <label>Status <i class="imp">*</i></label>
                                      <select class="form-control status select2" name="status" required="required">
                                      <option value="unpaid">Unpaid</option>
                                      <option value="paid">Paid</option>
                                      <option value="partial">Partial Payment</option>
                                      </select>
                                  </div>
                              </div>

                              <div class="col-md-3" style="margin-bottom:;">
                                  <div class="form-group">
                                      <label>Date <i class="imp">*</i></label>
                                      <input type="text" class="form-control" name="date" id="datepicker" value="" placeholder="Date">

                                  </div>
                              </div>


                                <div class="clearfix"></div>

                                <div class="col-md-3">
                                    <a href="javascript::void(0)" class="btn btn-primary btn-xs" id="addMore" style="float: left;">
                                        <i class="fa fa-plus"></i> <span>Add Products Item</span>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="javascript::void(0)" class="btn btn-primary btn-xs" id="CustomAddMore" style="float: left;">
                                        <i class="fa fa-plus"></i> <span>Add Custom Products Item</span>
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
                                        @foreach($orderDetails as $odk => $odv)
                                        <tr>
                                            <td>
                                                <select class="form-control select2 product_id" name="product_id[]" required="required">
                                                @if(isset($products))
                                                    <option value="">Select Product</option>
                                                    @foreach($products as $key => $pk)
                                                    <option value="{{ $pk->id }}"@if($odv->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}</option>
                                                    @endforeach
                                                @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control price" name="price[]" placeholder="Price" value="{{ $odv->price }}" required="required">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control quantity" name="quantity[]" placeholder="Quantity" min="1" value="{{ $odv->quantity }}" required="required">
                                            </td>
                                            <td>
                                                <select required name="tax[]" class="form-control tax_rate">
                                                    @foreach(config('tax.taxes') as $dk => $dv)
                                                    <option value="{{ $dk }}" @if(isset($odv->tax) && $odv->tax == $dk) selected="selected" @endif>{{ $dv }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control tax_amount" name="tax_amount[]" @if(isset($odv->tax_amount)) value="{{ $odv->tax_amount }}" @else value="0" @endif readonly="readonly">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control total" name="total[]" placeholder="Total" value="{{ $odv->total }}" readonly="readonly" style="float:left; width:80%;">
                                                @if($odk != '0')
                                                <a href="javascript::void(1);" style="width: 10%;">
                                                    <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr class="multipleDiv"></tr>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="3" style="text-align: right;">Subtotal</td>
                                            <td id="sub-total">{{ $order->subtotal }}</td>
                                            <td>&nbsp; <input type="hidden" name="subtotal" id="subtotal" value="{{ $order->subtotal }}"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="text-align: right;">Order Discount</td>
                                            <td><input type="number" min="0" name="discount_amount" id="discount_amount" value="{{ $order->discount_amount }}"></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="text-align: right;">Discount Note</td>
                                            <td>
                                                <input type="text" name="discount_note" id="discount_note" value="{{ $order->discount_note }}">
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="text-align: right;"><strong>TOTAL</strong></td>
                                            <td id="total">{{ $order->total }}</td>
                                            <td>
                                                <input type="hidden" name="total_tax_amount" id="total_tax_amount" value="{{ $order->tax_amount }}">
                                                <input type="hidden" name="final_total" id="total_" value="{{ $order->total }}">
                                            </td>

                                            <td>&nbsp; <input type="hidden" name="final_total" id="total_" value="{{ $order->total }}"></td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="col-md-4 form-group" style="">
                                    <label for="comment">Person Name</label>
                                    <input type="text" name="name" id="name" value="{{ $order->name }}" required="required">
                                </div>

                                <div class="col-md-4 form-group" style="">
                                    <label for="position">Position</label>
                                    <input type="text" name="position" id="position" value="{{ $order->position }}">
                                </div>





                                <div class="col-md-6 form-group" style="margin-top:5px;">
                                    <label for="comment">Comment</label>
                                    <textarea class="form-control TextBox comment" name="comment">@if(isset($order)){{ $order->comment }}@endif</textarea>
                                </div>

                                <div class="col-md-6 form-group" style="margin-top:5px;">
                                    <label for="address">Address</label>
                                    <textarea class="form-control TextBox address" name="address">{{ $order->address }}</textarea>
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

$("#CustomAddMore").on("click", function () {
     //$($('#orderFields').html()).insertBefore(".multipleDiv");
     $(".multipleDiv").after($('#CustomOrderFields #Custom-more-tr').html());
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
@endsection
