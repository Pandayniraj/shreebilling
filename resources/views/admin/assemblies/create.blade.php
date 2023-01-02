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
        <div class='col-md-12'>
            <div class="box-body">
                

                <div class="col-md-12">
  
                        <form method="POST" action="/admin/assembly">
                            {{ csrf_field() }}

                         
                             <div class="row">

                                    <div class="col-md-3"> 
                                          <label>Source Location</label>
                                          {!! Form::select('source',$productlocation, null,['class'=>'form-control','id'=>'source']) !!}
                                    </div>  

                                    <div class="col-md-3"> 
                                          <label>Destination Location</label>
                                          {!! Form::select('destination',$productlocation, null,['class'=>'form-control','id'=>'destination']) !!}
                                    </div>

                                    <div class="col-md-3"> 
                                          <label>Product</label>
                                          <input type="text" name="product" id="product_name" class="form-control">
                                    </div>  

                                      <div class="col-md-3"> 
                                          <label>Assembled Quantity</label>
                                          <input type="text" name="assembled_quantity" id="assembled_quantity" value="0" class="form-control">
                                     </div>   

                                </div>
                                 <div class="row">

                                    <div class="col-md-3"> 
                                          <label>Can Assemble Qty</label>
                                          <input type="text" name="can_assemble_qty" id="can_assemble_qty" class="form-control">
                                    </div>  

                                      <div class="col-md-3"> 
                                          <label>Can Assemble Qty (All levels) </label>
                                          <input type="text" name="can_assemble_qty_all_levels" id="can_assemble_qty_all_levels" value="0" class="form-control">
                                     </div> 

                                      <div class="col-md-3"> 
                                          <label>Total Cost</label>
                                          <input type="text" name="total_cost" id="total_cost" value="0"  class="form-control">
                                     </div>  

                                      <div class="col-md-3"> 
                                          <label>Assemble By</label>
                                          <input type="text" name="assemble_by" id="assemble_by" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" class="form-control datepicker">
                                     </div>   

                                </div>

                                <hr/>


                                <div class="row">
                                  <div class="col-md-4">
                                      <label>Component Product</label>
                                      <input type="text" class="form-control pull-right"  value="" id="component_product" readonly>  
                                  </div>

                                   <div class="col-md-3">
                                      <label>Quantity</label>
                                      <input type="number" class="form-control pull-right" value="" id="quantity"  readonly>
                                  </div>
                                   <div class="col-md-3">
                                      <label>Westage Qty</label>
                                      <input type="number" class="form-control pull-right" value="" id="wastage_qty"  readonly>
                                  </div>

                                  <div class="col-md-1">
                                      <label></label>
                                      <button type="button" class="form-control btn btn-primary btn-xs " id="addcomponentproduct" >Add</button>
                                    </div>
                                </div>

                             
                                <div class="row">

                             <hr/>
                                <table class="table table-striped">
                                    <thead>
                                        <tr class="bg-maroon">
                                            <th>Component Product </th>
                                            <th>Units</th>
                                            <th>Qty</th>
                                            <th>Wastage Qty</th>
                                            <th>Unit Cost</th>
                                            <th>Total Cost</th>
                                        </tr>
                                    </thead>

                                     <tbody>
                                      <tr class="multipleDiv">
                                        
                                      </tr>
                                        
                                    </tbody> 

                                    <tfoot>
                                       
                                        <tr>
                                            <td colspan="5" style="text-align: right; font-weight: bold;">Total Amount</td>
                                            <td id="total">0.00</td>
                                            <td>
                                                <input type="hidden" name="total_tax_amount" id="total_tax_amount" value="0">
                                                <input type="hidden" name="final_total" id="total_" value="0">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <br/>

                                
                                <div class="col-md-6 form-group" style="margin-top:5px;">
                                    <label for="comment">Comment</label>
                                    <textarea class="form-control TextBox comment" name="comments" id="comments">@if(isset($order->comments)){{ $order->comments }}@endif</textarea>
                                </div>

                                
                            </div>
                            <div class="panel-footer">
                                <button type="submit" class="btn btn-social btn-foursquare" id='btnSubmit'>
                                    <i class="fa fa-save"></i>Save
                                </button>
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
                tax_amount = total * Number(tax) / 100;
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
             console.log(parseFloat(this.value) + parseFloat(parentDiv.find('.wastage_quantity').val()));
            var total = parentDiv.find('.price').val() * (parseFloat(this.value) + parseFloat(parentDiv.find('.wastage_quantity').val()));
        }
        else
            var total = '';
    }
    else
        var total = '';

    parentDiv.find('.total').val(total);
    calcTotal();
});

$(document).on('change', '.wastage_quantity', function() {
    var parentDiv = $(this).parent().parent();
    if(isNumeric(this.value) && this.value != '')
    {
        if(isNumeric(parentDiv.find('.wastage_quantity').val()) && parentDiv.find('.wastage_quantity').val() != '')
        {

          console.log(parseFloat(this.value) + parseFloat(parentDiv.find('.quantity').val()));
            var total = parentDiv.find('.price').val() * (parseFloat(this.value) + parseFloat(parentDiv.find('.quantity').val()));
        }
        else
            var total = '';
    }
    else
        var total = '';


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
    //alert('hi');
    var subTotal = 0;
   
    $(".total").each(function(index) {
        if(isNumeric($(this).val()))
            subTotal = Number(subTotal) + Number($(this).val());
    });
   

    $('#total').html(subTotal);
    $('#total_').val(subTotal);
}


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

        if(val == null || val == ''){
         $("#errMsg").html("Already Exists");
          $('#btnSubmit').attr('disabled', 'disabled');
          return;
         }else{
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
    
}  function HandlePopupResult(result) {
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
     
      $("#product_name").autocomplete({
            source: "/admin/getProductName/assembly/",  
            minLength: 1

      });

    });

   $('#product_name').on('change',function(){

          $('#product_name').prop("readonly", true); 
          $('#component_product').prop("readonly", false); 

          $('#quantity').prop("readonly", false); 

          $('#wastage_qty').prop("readonly", false); 


         var product_name = $('#product_name').val();


            $("#component_product").autocomplete({

                source: "/admin/getComponentProduct/assembly/?product_name="+ $('#product_name').val(),  
               
                minLength: 1

            });


    });


   


    $(document).on('click', '#addcomponentproduct', function() {

      var component_product = $('#component_product').val();
      var  quantity  = $('#quantity').val();
      var  wastage_qty  = $('#wastage_qty').val();
      

         $.ajax(
            {
             url: "/admin/getComponentProductInfo/assembly/",  
             data: { component_product: component_product,quantity: quantity,wastage_qty: wastage_qty }, 
             dataType: "json", 
                success: function( data ) { 
                   
                    var purchasedetailinfo = data.purchasedetailinfo;
                    
                    $(".multipleDiv").after(purchasedetailinfo);
                    $('#component_product').val('');
                    $('#quantity').val('');
                    $('#wastage_qty').val('');

                    calcTotal();

                } 
            }); 

    });



   $(document).on('change','#purchase_bill_no',function(){

        $('.multipleDiv').nextAll('tr').remove();

    });


   

</script>
@endsection
