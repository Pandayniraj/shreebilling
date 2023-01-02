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

                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <form method="POST" action="/admin/payment">
                            {{ csrf_field() }}

                          <input type="hidden" name="invoice_reference" value="{{$saleDataInvoice->reference}}">
                          <input type="hidden" name="order_reference" value="{{$saleDataOrder->reference}}">
                          <input type="hidden" name="customer_id" value="{{$saleDataInvoice->lead_id}}">

                          <input type="hidden" name="order_no" value="{{$orderNo}}">
                          <input type="hidden" name="invoice_no" value="{{$invoiceNo}}">
                          <input type="hidden" name="category_id" value="1">

                            <div class="panel-body">

                                <div class="col-md-3" style="margin-bottom:;">
                                    <div class="form-group">
                                        <label>Select Payment Type<i class="imp">*</i></label>
                                          {!! Form::select('payment_type_id', ['1'=>'Online', '2'=>'E-sewa', '3'=>'Everest bank', '4'=>'NIC Bank'], $saleDataInvoice->payment_id, ['class' => 'form-control input-sm']) !!}
                                    </div>
                                </div>

                                <div class="col-md-3" style="margin-bottom:;">
                                    <div class="form-group">
                                        <label>Bank Account <i class="imp">*</i></label>
                                        <select class="form-control customer_id select2" name="account_no" required="required">
                                        <option class="" value="">Select Leads</option>
                                        <option class="" value="1">Everest Bank</option>
                                        <option class="" value="2">NIC Asia Bank</option>
                                        <option class="" value="3">Prabhu Bank</option>

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
                                        <label>Amount <i class="imp">*</i></label>
                                        <input type="text" class="form-control" name="amount" id="amount" value="{{$saleDataInvoice->total-$saleDataInvoice->paid_amount}}" placeholder="Date">

                                    </div>
                                </div>

                                <div class="col-md-3" style="margin-bottom:;">
                                    <div class="form-group">
                                        <label>Payment Date <i class="imp">*</i></label>
                                        <input type="text" class="form-control" name="payment_date" id="datepicker" value="" placeholder="payment Date">
                                    </div>
                                </div>


                                <div class="col-md-3" style="margin-bottom:;">
                                    <div class="form-group">
                                        <label>Description <i class="imp">*</i></label>
                                        <input type="text" class="form-control" name="description" id="description" value="<?php echo "Payment for ".$saleDataInvoice->reference;?>" placeholder="Description" readonly>

                                    </div>
                                </div>

                                <div class="col-md-3" style="margin-bottom:;">
                                    <div class="form-group">
                                        <label>Reference <i class="imp">*</i></label>
                                        <input type="text" class="form-control" name="reference" id="reference" value="" placeholder="Reference">

                                    </div>
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
@endsection
