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

        
    </style>
@endsection

@section('content')


<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px"> 
            <h1>
                 {{$page_title ?? "Page Title"}}
                <small> {{$page_description ?? "Page Description"}}
                </small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
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
                                          <option value="{{ $pk->id }}"@if(isset($orderDetail->product_id) && $orderDetail->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}</option>
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
                                    <select name='units[]' class="form-control">
                                      <option value="">Select Units</option>
                                      @foreach($prod_unit as $pu)
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

                <div id="CustomOrderFields" style="display: none;">
                    <table class="table">
                        <tbody id="more-custom-tr">
                            <tr>
                                <td>
                                  <input type="text" class="form-control product" name="custom_items_name[]" value="" placeholder="Product">
                                </td>
                                 
                                <td>
                                    <input type="text" class="form-control price" name="custom_items_price[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required">
                                </td>
                               
                                <td>
                                    <input type="number" class="form-control quantity" name="custom_items_qty[]" placeholder="Quantity" min="1" value="1" required="required" step ='any'>
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
                                    <input type="number" class="form-control total" name="custom_total[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly" style="float:left; width:80%;" step ='any'>
                                    <a href="javascript::void(1);" style="width: 10%;">
                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                   
                        <form method="POST" id="deliveryedit_form" action="/admin/deliverynote/update/{{$delivery->id}}">
                            {{ csrf_field() }}

                         

                             <div class="row">

                                <div class="col-md-12">

                                     <div class="col-md-3">
                                        <label>SB /Sales bill ID</label>
                                          <input type="text" class="form-control pull-right " name="sales_bill_no" value="{{$delivery->sales_bill_no}}" id="sales_bill_no" required="" readonly>
                                     </div>

                                      <div class="col-md-3">
                                        <label>Party Name:</label>
                                          <input type="text" class="form-control pull-right " name="client_name" value="{{\App\Models\Client::where('id',$delivery->client_id)->first()->name}}" id="customer_name" readonly>
                                          <input type="hidden" name="client_id" value="{{$delivery->client_id}}" id="client_id">
                                     </div>
                                    
                                     <div class="col-md-3">
                                        <label>Delivery 
                                            Note Date:</label>
                                          <input type="text" class="form-control pull-right datepicker" name="return_date" value="{{$delivery->delivery_note_date}}" id="return_date" required="" readonly>
                                     </div>

                                     <div class="col-md-3">
                                        <label>Sales Bill Date:</label>
                                        <input type="text" class="form-control pull-right datepicker" name="sales_date" value="{{$delivery->sales_bill_date}}" id="purchase_order_date" readonly>
                                     </div>

                                </div>

                                 <div class="col-md-12">

                                    {{-- <div class="col-md-3 form-group" style="">
                                        <label for="user_id">Purchase Owner</label>
                                        {!! Form::select('user_id',  $users, \Auth::user()->id, ['class' => 'form-control input-sm', 'id'=>'user_id']) !!}
                                     </div> --}}

                                    {{-- <div class="col-md-3">
                                        <div class="form-group">
                                         <label>Status:</label>
                                          <select type="text" class="form-control pull-right " name="status" id="status">
                                            <option value="parked">Parked</option>
                                            <option value="completed">Completed</option>
                                          </select>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-3">
                                          <label>PAN NO:</label>
                                          <input type="text" class="form-control pull-right " name="pan_no" value="{{$delivery->pan_no}}" id="pan_no" readonly>
                                    </div>

                                      <div class="col-md-3">
                                          <label>Vat:</label>
                                            <select type="text" class="form-control pull-right " name="vat_type" id="vat_type">
                                            <option value="yes" @if($delivery->vat_type == "yes") selected @endif>Yes(13%)</option>
                                              <option value="no" @if($delivery->vat_type == "no") selected @endif>No</option>
                                            </select>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Is renewal:</label>
                                              <select type="text" class="form-control pull-right " name="is_renewal" id="is_renewal">
                                                 <option value="0"@if($delivery->is_renewal == 0) selected @endif>No</option>
                                                 <option value="1"@if($delivery->is_renewal == 1) selected @endif>Yes</option>
                                              </select>
                                        </div>
                                    <!-- /.input group -->
                                    </div>

                                 </div>
                                <div class="row">
                                  <div class="col-md-12" style="margin-left: 15px">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                         <label>Location:</label>
                                           {!! Form::select('into_stock_location', [''=>'Select']+$productlocation, null, ['class' => 'form-control label-default','id'=>'location_id']) !!}
                                        </div>
                                    <!-- /.input group -->
                                    </div>
                                  </div>
                                </div>
<hr/>

                                <table class="table table-striped">
                                    <thead>
                                        <tr class="bg-maroon">
                                            <th>SN</th>
                                            <th>Particular</th>
                                            <th>Units</th>
                                            <th>Qty</th>
                                            <th>Receive Qty</th>
                                            <th>Receive Price</th>
                                            <th>Change Price</th>
                                            <th>Sub Total</th>
                                            <th>Reason</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        
                                        @foreach ($deliverydetail as $details)
                                        <?php
                                           $pname= \App\Models\Product::where('id', $details->product_id)->first()->name;
                                           
                                           $uname= App\Models\ProductsUnit::where('id', $details->unit)->first()->name;
                                            ?>
                                        <tr> <td>{{$loop->iteration}}</td> 
                                            <td>
                                            <input type="text" class="form-control product_id" name="product"  value="{{$pname}}" readonly>
                                              <input type="hidden"  name="product_id[]" value="{{$details->product_id}}" required="required" readonly>   
                                            </td>
                                            
                                            <td>
                                                <input type="text" class="form-control invoice_price" placeholder="Unit" value="{{$uname}}" required="required" readonly>
                                                <input  type="hidden" name="units[]" value="{{$details->unit}}">
            
                                            </td>
                                            <td>
                                                <input type="number" class="form-control purchase_quantity" name="sales_quantity[]" placeholder="Quantity" min="1" value="{{$details->sales_quantity}}" required="required" readonly>
                                            </td>
                                           
            
                                            <td>
                                                <input type="number" class="form-control quantity" name="invoiced_quantity[]" placeholder="Quantity" min="1" value="{{$details->invoiced_quantity}}" required="required" >
                                            </td>
            
                                            <td>
                                               <input type="number" class="form-control purchase_price" name="sales_price[]" placeholder="Price" min="1" value="{{$details->sales_price}}" required="required" readonly>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control price" name="return_price[]" placeholder="Price" min="1" value="{{$details->return_price}}" required="required" >
                                            </td>
            
                                             <td>
                                                 <input type="number" class="form-control total" name="return_total[]" placeholder="Total" value="{{$details->return_total}}" readonly="readonly">
                                            </td>
            
                                            <td>
                                                <input type="text" class="form-control reason" name="reason[]" placeholder="Reason" value="{{$details->reason}}"style="float:left; width:80%;">
                                                <a href="javascript::void(1);" style="width: 10%;">
                                                    <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                            
                                        @endforeach
                                        <tr class="multipleDiv">
                                            
                                        </tr>
                                    </tbody> 

                                    <tfoot>
                                        <tr>
                                            <td colspan="7" style="text-align: right;">Amount</td>
                                            <td id="sub-total">{{$delivery->subtotal}}</td>
                                            <td>&nbsp; <input type="hidden" name="subtotal" id="subtotal" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" style="text-align: right;">Order Discount (%)</td>
                                            <td><input type="number" min="0" name="discount_percent" id="discount_amount" value="{{$delivery->discount_percent}}" onKeyUp="if(this.value>99){this.value='99';}else if(this.value<0){this.value='0';}" step ='any'></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                       
                                        <tr>
                                            <td colspan="7" style="text-align: right;">Non Taxable Amount</td>
                                            <td id="taxable-amount">{{$delivery->taxable_amount}}</td>
                                            <td>&nbsp; <input type="hidden" name="taxable_amount" id="taxableamount" value="{{$delivery->taxable_amount}}"></td>
                                        </tr>
                                        {{-- <tr>
                                            <td colspan="7" style="text-align: right;">Tax Amount</td>
                                            <td id="taxable-tax">{{$delivery->tax_amount}}</td>
                                            <td>&nbsp; <input type="hidden" name="taxable_tax" id="taxabletax" value="{{$delivery->tax_amount}}"></td>
                                        </tr> --}}
                                        <tr>
                                            <td colspan="7" style="text-align: right; font-weight: bold;">Total Amount</td>
                                            <td id="total">{{$delivery->taxable_amount}}</td>
                                            <td>
                                                <input type="hidden" name="total_tax_amount" id="total_tax_amount" value="0">
                                                <input type="hidden" name="final_total" id="total_" value="{{$delivery->taxable_amount}}">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <br/>

                            </div>
                            <div class="panel-footer">
                                <button type="submit" class="btn btn-social btn-foursquare" id='btnSubmit'>
                                    <i class="fa fa-save"></i>Save DeliveryNote
                                </button>
                                <a href="/admin/deliverynote" class="btn btn-default">Close</a>
                            </div>
                        </form>
                    </div>
              
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <!-- form submit -->
    @include('partials._body_bottom_submit_bug_edit_form_js')

    <script type="text/javascript">
    document.getElementById("deliveryedit_form").onkeypress = function(e) {
    var key = e.charCode || e.keyCode || 0;     
    if (key == 13) {
      e.preventDefault();
    }
}
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
                            tax_amount = total * Number(tax) / 100;
                            parentDiv.find('.tax_amount').val(tax_amount);
                            total = total + tax_amount;
                        }
                        else{
                            parentDiv.find('.tax_amount').val('0');
                        }
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
                        tax_amount = total * Number(tax) / 100;
                        parentDiv.find('.tax_amount').val(tax_amount);
                        total = total + tax_amount;
                    }
                    else{
                        parentDiv.find('.tax_amount').val('0');
                    }    
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

            let supp_id = $(this).val();

            $.get('/admin/getpanno/'+supp_id,function(data,status){
                $('#pan_no').val(data.pan_no);


            });
        });

        $(document).on('change', '.quantity', function() {
            var parentDiv = $(this).parent().parent();
            if(isNumeric(this.value) && this.value != '')
            {
                if(isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '')
                {
                    var total = parentDiv.find('.price').val() * this.value;
                }
                else{
                    var total = '';
                }
            }
            else{
                var total = '';
            }
            var tax = parentDiv.find('.tax_rate').val();
            if(isNumeric(tax) && tax != 0 && (total != 0 || total != ''))
            {
                tax_amount = total * Number(tax) / 100;
                parentDiv.find('.tax_amount').val(tax_amount);
                total = total + tax_amount;
            }
            else{
                parentDiv.find('.tax_amount').val('0');
            }
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
                else{
                    var total = '';
                }
            }
            else{
                var total = '';
            }
            var tax = parentDiv.find('.tax_rate').val();
            if(isNumeric(tax) && tax != 0 && (total != 0 || total != ''))
            {
                tax_amount = total * Number(tax) / 100;
                parentDiv.find('.tax_amount').val(tax_amount);
                total = total + tax_amount;
            }
            else{
                parentDiv.find('.tax_amount').val('0');
            }
            parentDiv.find('.total').val(total);
            calcTotal();
        });

        $(document).on('change', '.tax_rate', function() {
            var parentDiv = $(this).parent().parent();

            if(isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '')
            {
                var total = parentDiv.find('.price').val() * Number(parentDiv.find('.quantity').val());
            }
            else{
                var total = '';
            }
            var tax = $(this).val();
            if(isNumeric(tax) && tax != 0 && (total != 0 || total != ''))
            {
                tax_amount = total * Number(tax) / 100;
                parentDiv.find('.tax_amount').val(tax_amount);
                total = total + tax_amount;
            }
            else{
                parentDiv.find('.tax_amount').val('0');
            }
            parentDiv.find('.total').val(total);
            calcTotal();
        });



        $("#addMore").on("click", function () {
            //$($('#orderFields').html()).insertBefore(".multipleDiv");
            $(".multipleDiv").after($('#orderFields #more-tr').html());
            $(".multipleDiv").next('tr').find('select').select2({ width: '100%' });
        });
        $("#addCustomMore").on("click", function () {
            //$($('#orderFields').html()).insertBefore(".multipleDiv");
            $(".multipleDiv").after($('#CustomOrderFields #more-custom-tr').html());
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
            
            var subTotal = 0;
            var taxableAmount =0;

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
            $('#sub-total').html(subTotal);
            $('#subtotal').val(subTotal);

            $('#taxable-amount').html(subTotal);
            $('#taxableamount').val(subTotal);

            var discount_amount = $('#discount_amount').val();

            var vat_type = $('#vat_type').val();


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

        
            $('#taxableamount').val(taxableAmount);
            $('#taxable-amount').html(taxableAmount);

            $('#total_tax_amount').val(tax_amount);

            $('#taxabletax').val(taxableTax);
            $('#taxable-tax').html(taxableTax);

            $('#total').html(total);
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

    <script type="text/javascript">
        $(function() {
                $('.datepicker').datetimepicker({
                //inline: true,
                format: 'YYYY-MM-DD',
                sideBySide: true,
                allowInputToggle: true,
                
                });
        });
    </script>

    <script type="text/javascript">
        var refNo ='PO-'+$("#reference_no").val();

        $("#reference_no_write").val(refNo);

        $(document).on('keyup', '#reference_no', function () {

            var val = $(this).val();

            if(val == null || val == '')
            {
                $("#errMsg").html("Already Exists");
                $('#btnSubmit').attr('disabled', 'disabled');
                return;
            }
            else
            {
                $('#btnSubmit').removeAttr('disabled');
            }

            var ref = 'PO-'+$(this).val();
            $("#reference_no_write").val(ref);
        $.ajax({
            method: "POST",
            url: "/admin/purchase/reference-validation",
            data: { "ref": ref,"_token":token }
        })
            .done(function( data ) {
            var data = jQuery.parseJSON(data);
            if(data.status_no == 1){
                $("#errMsg").html('Already Exists!');
            }else if(data.status_no == 0){
                $("#errMsg").html('Available');
            }
            });
        }); 

        function openwindow(){
            var win =  window.open('/admin/clients/modals', '_blank','toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=600, height=650');
            
        }  
        function HandlePopupResult(result) {
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
                $('#customers_id select').html(option);
                setTimeout(function(){
                    $('#customers_id select').val(result.lastcreated);
                    $('#pan_no').val(result.pan_no);
                    $("#ajax_status").after("<span style='color:green;' id='status_update'>client sucessfully created</span>");
                    $('#status_update').delay(3000).fadeOut('slow');
                },500);
            }
            else{
                $("#ajax_status").after("<span style='color:red;' id='status_update'>failed to create clients</span>");
                $('#status_update').delay(3000).fadeOut('slow');
            }
        }


    </script>

    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>

    <script type="text/javascript">
        
        $(document).ready(function() {
            
            $("#sales_bill_no").autocomplete({
                    source: "/admin/getSalesBillId",  
                    minLength: 1
                
            });
        
        });

        $('#sales_bill_no').on('change',function(){
            
                $.ajax(
                    {
                    url: "/admin/getSalesBillInfo",  
                    data: { sales_bill_no: $(this).val() }, 
                    dataType: "json", 
                        success: function( data ) { 
                            var purchasebillsinfo = data.purchasebillsinfo;
                            var purchasedetailinfo = data.purchasedetailinfo;
                            console.log(purchasedetailinfo);
                            var customer_name = data.customer_name;
                            populatePurchaseInfo(purchasebillsinfo);

                            $('#customer_name').val(customer_name).prop("readonly", true); 
                            $(".multipleDiv").after(purchasedetailinfo);
                        } 
                    }); 

        });

        function populatePurchaseInfo(invoiceinfo){
        
            // $('#bill_date').val(invoiceinfo.bill_date).prop("readonly", true); 
            // $('#due_date').val(invoiceinfo.due_date).prop("readonly", true); 
            // $('#name').val(invoiceinfo.name).prop("readonly", true);
            // $('#position').val(invoiceinfo.position).prop("readonly", true); 
            $('#client_id').val(invoiceinfo.client_id); 
            $('#purchase_order_date').val(invoiceinfo.bill_date);
            // $('#user_id').val(invoiceinfo.user_id).prop("readonly", true);   
            $('#location_id').val(invoiceinfo.into_stock_location); 
            $('#terms').val(invoiceinfo.terms); 
            $('#pan_no').val(invoiceinfo.pan_no).prop("readonly", true); 

            $('#comment').val(invoiceinfo.comment); 
            $('#address').val(invoiceinfo.address); 

            $('#vat_type').val(invoiceinfo.vat_type); 
            $('#is_renewal').val(invoiceinfo.is_renewal); 

            $('#discount_amount').val(invoiceinfo.discount_percent);

            $('#subtotal').val(invoiceinfo.subtotal);
            $('#sub-total').html(invoiceinfo.subtotal);

            $('#taxableamount').val(invoiceinfo.taxable_amount);
            $('#taxable-amount').html(invoiceinfo.taxable_amount);

            $('#taxabletax').val(invoiceinfo.tax_amount);
            $('#taxable-tax').html(invoiceinfo.tax_amount);

            $('#total').html(invoiceinfo.total_amount);
            $('#total_').val(invoiceinfo.total_amount);
        
            return 0;
    }

        $(document).on('change','#sales_bill_no',function(){
            $('.multipleDiv').nextAll('tr').remove();

        });


        $(document).on('change', '.quantity', function() {
            var parentDiv = $(this).parent().parent();
            if(isNumeric(this.value) && this.value != ''){
                var invoice_qty = parentDiv.find('.purchase_quantity').val();
                if(parseInt(this.value) > parseInt(invoice_qty)){
                    $(this).val(parentDiv.find('.purchase_quantity').val()); 

                    alert("Return Quantity Cannot Be Greater Than Purchase Quantity");  
                }    
            }
        
        });

    </script>
@endsection
