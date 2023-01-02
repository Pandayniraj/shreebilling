@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection


@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<section class="content-header" style="margin-top: -35px;">
<h1>
  {!! $page_title ?? "Page title" !!}

    <small>{!! $page_description ?? "Page description" !!}</small>
</h1>
<p> Whether a supplier gift us some stock or some stocks are just got broken</p>
{!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>

<div class='row'>
       <div class='col-md-12'>
          <div class="box">
		     <div class="box-body ">
		     	<div id="orderFields" style="display: none;">
                    <table class="table">
                        <tbody id="more-tr">
                            <tr>
                                <td>
                                  <select class="form-control select2 product_id" name="product_id[]" required="required">
                                          <option value="">Select Product</option>
                                      @foreach($products as $key => $pk)
                                          <option value="{{ $pk->id }}"@if(isset($orderDetail->product_id) && $orderDetail->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}(In stock {{StockHelper::getRemaingStocks($pk->id)}})</option>
                                      @endforeach
                                  </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control price" name="price[]" placeholder="Fare" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required">
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity" name="quantity[]" placeholder="Quantity" min="1" value="1" required="required" step ='any'>
                                </td>
                                <td>
                                    <select name='units[]' class="form-control units">
                                      <option value="">Select Units</option>
                                      @foreach($units as $pu)
                                        <option value="{{ $pu->id }}">{{ $pu->name }}({{ $pu->symbol }})</option>
                                      @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control total" name="total[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly" style="float:left; width:80%;" step ='any'>
                                    <a href="javascript::void(1);" style="width: 10%;">
                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
		     	<form method="post" action="/admin/product/stock_adjustment" enctype="multipart/form-data">
		     	{{ csrf_field() }}

		     	    <div class="row">

	                   	<div class="col-md-3">
	                   	    <label class="control-label" >Adjustment Date</label>
                            <div class="form-group">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control input-sm pull-right datepicker date-toggle-nep-eng" name="date" value="{{date('Y-m-d')}}" id="ord_date">
                                </div>
                                <!-- /.Request group -->
                            </div>	                   	</div>
                        <div class="col-md-3">
	                   	    <label class="control-label" >Warehouse</label>
                            {!! Form::select('location_id',[''=>'Select Location']+$location, null, ['class'=>'form-control','required'=>'required']) !!}
	                   	</div>
	                   	<div class="col-md-3">
	                   	    <label class="control-label">Reason</label>
                             {!! Form::select('reason',[''=>'Select Reason']+$reasons, null, ['class'=>'form-control','required'=>'required']) !!}
	                   	</div>
{{--	                   <div class="col-md-4">--}}
{{--	                   	<label class="control-label">Account</label>--}}
{{--                           {!! Form::select('ledgers_id',[''=>'Select Account']+$account_ledgers, null, ['class'=>'form-control']) !!}--}}
{{--	                   </div>--}}

{{--                    </div> --}}
{{--                    <div class="row">--}}
                    	<div class="col-md-3">
	                   	<label class="control-label">Status</label>
                           {!! Form::select('status',[''=>'Select Status','active'=>'active','pending'=>'pending'], null, ['class'=>'form-control']) !!}
	                   </div>
                        <div class="clearfix"></div>

                    	<div class="col-md-3">
                        	<label class="control-label">Vat</label>
	                          <select type="text" class="form-control " name="vat_type" id="vat_type">
	                            <option value="yes">Yes(13%)</option>
	                            <option value="no">No</option>
	                          </select>
                        </div>
                    </div>
                    <div class="clearfix"></div><br/>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="javascript::void(0)" class="btn btn-default btn-xs" id="addMore" style="float: right;">
                                <i class="fa fa-plus"></i> <span>Add Inventory Item</span>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                    	<div class="col-md-12">
                    		<hr/>
                                <table class="table">
                                    <thead>
                                        <tr class="bg-info">
                                            <th>Products *</th>
                                            <th>Cost *</th>
                                            <th>Quantity *</th>
                                            <th>Unit</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr class="multipleDiv">

                                        </tr>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="3" style="text-align: right;">Amount</td>
                                            <td id="sub-total">0.00</td>
                                            <td>&nbsp; <input type="hidden" name="subtotal" id="subtotal" value="0"></td>
                                        </tr>
{{--                                        <tr>--}}
{{--                                            <td colspan="3" style="text-align: right;">Order Discount (%)</td>--}}
{{--                                            <td><input type="number" min="0" name="discount_percent" id="discount_amount" value="" onKeyUp="if(this.value>99){this.value='99';}else if(this.value<0){this.value='0';}" step ='any'></td>--}}
{{--                                            <td>&nbsp;</td>--}}
{{--                                        </tr>--}}
                                       <!--  <tr>
                                            <td colspan="3" style="text-align: right;">Discount Note</td>
                                            <td>
                                                <input type="text" name="discount_note" id="discount_note" value="">
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr> -->
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

                    	</div>

                    </div>
                    <div class="row">
                    	<div class="col-md-4">
                    		<label class="control-label">Comment</label>
                    		<textarea name="comments" class="form-control"></textarea>


                    	</div>

                    </div>

               </div>
		    </div>

                <div class="row">
	                <div class="col-md-12">
	                    <div class="form-group">
	                        {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
	                        <a href="{!! route('admin.products.stock_adjustment') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
	                    </div>
	                 </div>
	            </div>


		     </form>

	</div>
</div>



@endsection
@section('body_bottom')
    <!-- form submit -->
    @include('partials._body_bottom_submit_bug_edit_form_js')
    @include('partials._date-toggle')

    <script type="text/javascript">

        $(function() {
            $('.datepicker').datetimepicker({
                //inline: true,
                format: 'YYYY-MM-DD'
                , sideBySide: true
                , allowInputToggle: true,

            });

        });
        $('.date-toggle-nep-eng').nepalidatetoggle();

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
                parentDiv.find('.price').val(obj.cost);
                parentDiv.find('.units').val(result.units?.id);
                if(isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '')
                {
                    var total = parentDiv.find('.quantity').val() * obj.cost;
                }
                else
                {
                    var total = obj.cost;
                }

                var tax = parentDiv.find('.tax_rate').val();
                if(isNumeric(tax) && tax != 0 && (total != 0 || total != ''))
                {
                    tax_amount = total * Number(tax) / 100;
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
        tax_amount = total * Number(tax) / 100;
        parentDiv.find('.tax_amount').val(tax_amount.toFixed(2));
        total = total + tax_amount;
    }
    else
        parentDiv.find('.tax_amount').val('0');

    parentDiv.find('.total').val(total.toFixed(2));
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
        tax_amount = total * Number(tax) / 100;
        parentDiv.find('.tax_amount').val(tax_amount.toFixed(2));
        total = total + tax_amount;
    }
    else
        parentDiv.find('.tax_amount').val('0');

    parentDiv.find('.total').val(total.toFixed(2));
    calcTotal();
});



    	$("#addMore").on("click", function () {
		     //$($('#orderFields').html()).insertBefore(".multipleDiv");
		     $(".multipleDiv").after($('#orderFields #more-tr').html());

                $(".multipleDiv").next('tr').find('.product_id').select2({
                    width: '100%'
                });

		});

		$(document).on('click', '.remove-this', function () {
		    $(this).parent().parent().parent().remove();
		    calcTotal();
		});

		$(document).on('change', '#vat_type', function(){
		    calcTotal();
		});


function calcTotal()
{
    //alert('hi');
    var subTotal = 0;
    var taxableAmount =0;

    //var tax = Number($('#tax').val().replace('%', ''));
    var total = 0;
    var tax_amount = 0;
    var taxableTax = 0;
    $(".total").each(function(index) {
        if(isNumeric($(this).val()))
            subTotal = Number(subTotal) + Number($(this).val());
    });
    $(".tax_amount").each(function(index) {
        if(isNumeric($(this).val()))
            tax_amount = Number(tax_amount) + Number($(this).val());
    });
    $('#sub-total').html(subTotal.toFixed(2));
    $('#subtotal').val(subTotal.toFixed(2));

    $('#taxable-amount').html(subTotal.toFixed(2));
    $('#taxableamount').val(subTotal.toFixed(2));

    var discount_amount = $('#discount_amount').val();

    var vat_type = $('#vat_type').val();

    console.log(vat_type);

    if(isNumeric(discount_amount) && discount_amount != 0)
    {

        taxableAmount = subTotal - (Number(discount_amount)/100 * subTotal );

    }
    else
    {
        total = subTotal;
        taxableAmount = subTotal;
    }

    if(vat_type == 'no' || vat_type == '')
    {

       total = taxableAmount;
       taxableTax =  0;

    }
    else
    {

    total = taxableAmount + Number(13/100 * taxableAmount );
    taxableTax =  Number(13/100 * taxableAmount );
    }


    $('#taxableamount').val(taxableAmount.toFixed(2));
    $('#taxable-amount').html(taxableAmount.toFixed(2));

    $('#total_tax_amount').val(tax_amount.toFixed(2));

    $('#taxabletax').val(taxableTax.toFixed(2));
    $('#taxable-tax').html(taxableTax.toFixed(2));

    $('#total').html(total.toFixed(2));
    $('#total_').val(total.toFixed(2));
}

$(document).on('keyup', '#discount_amount', function () {
    calcTotal();
});


    </script>

@endsection
