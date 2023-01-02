@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<style type="text/css">
    .form-group{
        position: relative;
    }
</style>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Add New Job  
                <small><label class="label label-success">Job No.&nbsp;<span>#{{$job_no}}</span></label></small>
            </h1>
             <span> Tickets can be submitted from external portal by clicking <a target="_blank" href="/ticket">here</a></span> App for field staff <a target="_blank" href="https://play.google.com/store/apps/details?id=meronetwork.app.fieldservice&hl=en">mobile apps</a>

             <br/>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row box'>
  
        <div class='col-md-12'> 
            <div class="box-body">

                {!! Form::open( ['route' => 'admin.cases.store', 'id' => 'form_edit_case', 'enctype' => 'multipart/form-data'] ) !!}
                <input type="hidden" name = "job_no" value="{{$job_no}}">
     <div class="row" style="margin-top: -10px">
                    
                </div>
                @include('partials._case_form')

                <div class="row">
                <div class="col-md-6">
                    <h4>CUSTOMER SERVICE DEPARTMENTS</h4>

         
          <div class="form-group">
            {!! Form::label('address', 'Address') !!}
            {!! Form::text('address', null, ['class' => 'form-control input-sm','id'=>'address']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('city', 'City') !!}
            {!! Form::text('city', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('telephone', 'Telephone') !!}
            {!! Form::text('telephone', null, ['class' => 'form-control input-sm' ,'id'=>'telephone']) !!}
        </div>
        
         <div class="form-group" id='dealer_id'>
        <label>Select Dealer <i class="imp">Or</i></label>&nbsp;<small><a href="javascript::void()" 
                onclick="createcustomer('dealer')">create new dealer</a></small>
            </label>
                        <select id="dealer" class="form-control lead_id select2" name="dealer_name">
                        <option class="input input-lg" value="">Select Dealer</option>

                        @if(isset($dealer))
                            @foreach($dealer as $key => $v)
                                <option value="{{ $v->id }}" @if($v->id == $case->client_id){{ 'selected="selected"' }}@endif>{{ $v->name }}</option>
                            @endforeach
                        @endif
                        </select>
                    </div>
        <div class="form-group">
            {!! Form::label('preferred', 'Preferred Date/Time') !!}
            {!! Form::text('preff_d_t', \Carbon\Carbon::now(), ['class' => 'form-control preferred input-sm' , 'id'=>'preferred']) !!}
        </div>
          <div class="form-group">
            {!! Form::label('customerreq', 'CUSTOMER REQUIREMENTS') !!}
            {!! Form::text('cust_req', null, ['class' => 'form-control input-sm']) !!}
        </div>
          <div class="form-group">
            {!! Form::label('type', 'Problem Observersation') !!}
            {!! Form::textarea('prob_obs', null, ['class'=>'form-control input-sm', 'rows'=>'3']) !!}
        </div>
        </div>
        <div class="col-md-6">
        <h4>JOB CARD / INSTALLATION SHEET</h4>
        <div class="form-group">
            {!! Form::label('Cal Date','Cal Date/Time' ) !!}
            {!! Form::text('cal_date', \Carbon\Carbon::now(), ['class' => 'form-control caldate input-sm']) !!}
        </div>
        <div class="form-group">
        <label>Select Products <i class="imp">*</i></label>
                        <select id="product_id" class="form-control lead_id select2" name="product" >
                        <option class="input input-lg" value="">Select Products</option>

                        @if(isset($producs))
                            @foreach($producs as $key => $v)
                                <option value="{{ $v->id }}" @if($v->id == $case->client_id){{ 'selected="selected"' }}@endif>{{ $v->name }}</option>
                            @endforeach
                        @endif
                        </select>
        </div>
        <div class="form-group">
        <label>Select Model <i class="imp">*</i></label>
            <select id="model_number" class="form-control lead_id select2" name="model_no">
            <option class="input input-lg" value="">Select model</option>
            </select>
        </div>
         <div class="form-group">
        <label>Serial number<i class="imp">*</i></label>
            <select id="serial_number" class="form-control lead_id select2" name="serial_no">
            <option class="input input-lg" value="">Select serial number</option>
            </select>
        </div>
        <div class="form-group">
            {!! Form::label('dateofpur', 'Date Of Puchase') !!}
            {!! Form::text('dop', date('Y-m-d'), ['class' => 'form-control dop input-sm']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('dateofamc', 'Date Of AMC') !!}
            {!! Form::text('do_amc', date('Y-m-d'), ['class' => 'form-control amc input-sm']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('sysstatus', 'System Status') !!}
            {!! Form::text('sys_status', null, ['class' => 'form-control input-sm']) !!}
        </div>
        </div>
            </div>
            <div class="row">

            </div>

            <div class="row">
       
        <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('action', 'Action Taken') !!}
            {!! Form::textarea('action_taken', null, ['class'=>'form-control input-sm', 'rows'=>'3']) !!}
        </div>
    </div>

            </div>
            <div class="row">
                <div class="col-md-12"><button class="btn bg-maroon btn-xs addmore" type="button"><i class="fa fa-plus"></i>  Add Parts</button>
                <table class="table" style="float: left;">
                    <tbody class="moretr">
                    <tr>
                        <th>Part`s Code</th>
                        <th>Description</th>
                        <th>Quanity</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Remark</th>
                    </tr>
                    <tr>
                        <td><input class="form-control input-sm" type="text" name="part_code[]" placeholder="parts code" required=""></td>
                        <td><input class="form-control input-sm" type="text" name="description1[]" placeholder="description"></td>
                        <td><input class="form-control quanity input-sm" type="text" name="quantity[]" placeholder="quanity"></td>
                        <td><input class="form-control rate input-sm" type="text" name="rate[]" placeholder="rate"></td>
                        <td><input class="form-control amount input-sm" type="text" name="amount[]" placeholder="amount"></td>
                        <td><input class="form-control input-sm" type="text" name="remark[]" placeholder="remark"></td>
                    </tr>
                     <tr class="multipleDiv">
                     </tr>
                     <tr>
                        <td><span>Customer`s Comments</span></td>
                        <td><input class="form-control input-sm" type="text" name="cust_comments" value="{{\Request::old('cust_comments')}}"></td>
                        <td><span>Payment details</span></td>
                        <td><input class="form-control input-sm" type="text" name="payment_details" value="{{\Request::old('payment_details')}}"></td>
                     
                     </tr>
                     <tr>
                        <td colspan="3"></td>
                        <td style="float: right">Total</td>
                        <td><input class="form-control totalamount input-sm" type="number" name="total_amount" id="totalamount" readonly=""></td>
                        <td><input class="form-control input-sm" type="text" name="total_amount_rem" placeholder="remark"></td>
                     </tr>
                     <tr>
                        <td colspan="3"></td>
                        <td style="float: right;">Labour</td>
                        <td><input class="form-control input-sm" type="text" name="labour" id="labour"></td>
                        <td><input class="form-control input-sm" type="text" name="labour_rem" placeholder="remark"></td>
                     </tr>
                       <tr>
                        <td colspan="3"></td>
                        <td style="float: right">Transport</td>
                         <td><input class="form-control input-sm" type="text" name="transport" id="transport"></td>
                         <td><input class="form-control input-sm" type="text" name="transport_rem" placeholder="remark"></td>
                     </tr>
                     <tr>
                        <td colspan="3"></td>
                        <td style="float: right">AMC</td>
                         <td><input class="form-control input-sm" type="text" name="amc"  id="amc"></td>
                        <td><input class="form-control input-sm" type="text" name="amc_rem" placeholder="remark"></td>
                     </tr>
                     <tr>
                        <td colspan="3"></td>
                        <td style="float: right">TAX Percent</td>
                        <td><input class="form-control input-sm" type="text" id="tax_percent" value="0"></td>
                        <td>%</td>

                     </tr>
                       <tr>
                        <td colspan="3"></td>
                        <td style="float: right">TAX Amount</td>
                         <td><input class="form-control input-sm" type="text" name="tax" id="tax" readonly=""></td>
                         <td><input class="form-control input-sm" type="text" name="tax_rem" placeholder="remark"></td>
                     </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td style="float: right">Net Total</td>
                         <td><input class="form-control input-sm" type="text" name="net_total" id="nettotal" readonly=""></td>
                        <td><input class="form-control input-sm" type="text" name="net_total_rem" placeholder="remark"></td>
                     </tr>

                   </tbody>
                </table>
            </div>

            </div>
            <div class="row">
                <div class="col-md-12"><button class="btn bg-maroon btn-xs addmore1" type="button"><i class="fa fa-plus"></i>  Add Visits</button>
                <table class="table" style="float: left;">
                    <tbody class="moretr1">
                        <tr>
                            <th>Visit Date/time</th>
                            <th>Service Enginner</th>
                            <th>Call status</th>
                            <th>Pending reasons</th>
                            <th>Remarks</th>
                        </tr>
                        <tr>
                            <td><input class="form-control visitdatetime input-sm" type="text" name="visit_date_time[]" placeholder="Visit date/time" required="" value="{{\Carbon\Carbon::now()}}"></td>
                            <td><input class="form-control input-sm" type="text" name="service_engineer[]" placeholder="Service Enginner"></td>
                            <td><input class="form-control input-sm" type="text" name="call_status[]" placeholder="Call status"></td>
                            <td><input class="form-control input-sm" type="text" name="peding_reasons[]" placeholder="Pending reasons"></td>
                            <td><input class="form-control input-sm" type="text" name="remarks[]" placeholder="Remaks"></td>
                        </tr>
                    <tr class="multipleDiv1">
                     </tr>

                    </tbody>
                </table>

            </div>
        </div>
           <div class="row">
            <div class="col-md-6">
                 
        <button class="btn  bg-success btn-xs" id='more-button' type="button"><i class="fa fa-plus"></i>  Add More Files</button>
             
                  <br>
                  <div class="more-tr">
                     <table class="table more table-hover table-no-border" style="width: 100%;" cellspacing="2">
                        <tbody style="float: left">
                          <thead>
                            <tr>
                              <th>Upload Case Document</th>
                              <th colspan="2"></th>
                            </tr>
                          </thead>
                       
                           <tr class="multipleDiv-attachment" style="float: left">
                           </tr>
                               <tr>
                              <td class="moreattachment" style=""> 
                                 <input type="file" name="attachment[]" class="attachment" >
                              </td>
                              <td class="w-25" >
                                 <img src=""  style="max-height: 100px;float: right;margin-left: 30px" class='uploads'>
                              </td>
                              <td >
                                 <a href="javascript:void()" style="font-size: 20px; float: right" class="remove-this-attachment"> <i class="fa fa-close deletable"></i></a>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
              </div>
        </div>
<div class="row">
    <div class="col-md-12">
                <div class="form-group">
                    {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="{!! route('admin.cases.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>
            </div>
</div>
                {!! Form::close() !!}

            </div><!-- /.box-body -->
        </div><!-- /.col -->

        </div>



    

@endsection

@section('body_bottom')
    <!-- form submit -->
   
    <script type="text/javascript">
        $('.addmore').click(function(){
            $(".multipleDiv").after($('#moretrdata #more-custom-tr').html());
        })
         $('.addmore1').click(function(){
        $(".multipleDiv1").after($('#moretrdata #more-custom-tr1').html());
        $('.visitdatetime').datetimepicker({
            format: 'YYYY-MM-DD  hh:mm:ss',
            sideBySide: true, 
        });
        })
    function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
    }
    $(document).on('click', '.remove-this', function () {
        $(this).parent().parent().parent().remove();
        caltotal();
    });
    function caltotal(){
        var subtotal = 0;
        $('.amount').each(function(){
            var val = $(this).val();
            if(isNumeric(val))
                subtotal = Number(subtotal) + Number(val)
        });
        $('.totalamount').val(subtotal);
     nettotal();
    }
    function nettotal(){
        var tax = 0;
        var totalamount = 0;
        var transport = 0;
        var amc = 0;
        var labour = 0;

        if(isNumeric($('#totalamount').val())){
            totalamount = Number($('#totalamount').val());
            if(isNumeric($('#tax_percent').val())){
                tax = totalamount * (Number($('#tax_percent').val()) / 100) ;
                $('#tax').val(tax);
            }
        }

        
        
        if(isNumeric($('#transport').val())){
            transport = Number($('#transport').val());
        }
        if(isNumeric($('#amc').val())){
            amc = Number($('#amc').val());
        }
        if(isNumeric($('#labour').val())){
            labour = Number($('#labour').val());
        }
        $('#nettotal').val(tax + totalamount + transport + amc + labour);
    }
    $(document).on('change','#tax , #labour, #totalamount , #transport, #amc, #tax_percent',function(){
        nettotal();
        });
    $(document).on('change','.quanity , .rate',function(){
        var parent = $(this).parent().parent();
        let rate = parent.find('.rate').val();
        let quanity = parent.find('.quanity').val();
        let amount = Number(rate) * Number(quanity);
        parent.find('.amount').val(amount);
        caltotal();
     });
    // $('#tax_percent').change(function(){
    //     if(isNumeric($(this).val()) && isNumeric($('#totalamount').val()) ){
    //         let taxamount =  
    //     }
    // })
        $(function() {
        $('.caldate').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD HH:mm:ss',
          sideBySide: true,

        });

        $('.dop').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,

        });
        $('#preferred').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            sideBySide: true,
        });

        $('.amc').datetimepicker({
            format: 'YYYY-MM-DD',
            sideBySide: true,
        });
        $('.visitdatetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            sideBySide: true, 
        });
      });

        $('#cust_id').on('change',function(){
        var id = $(this).val();
        var _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
              type: "POST",
              contentType: "application/json; charset=utf-8",
              url: "/admin/cases/clientinfo/"+id+'?_token='+_token,
              success: function (result) {
                $('#cust_name').val(result.name);
                $('#address').val(result.location);
                $('#telephone').val(result.phone);
        }
    });
    });
$('#product_id').select2();
$('#dealer').select2();
$('#model_number').select2();
$('#serial_number').select2();
$('select[name=assigned_to]').select2();
function ucfirst(str) {
    var firstLetter = str.substr(0, 1);
    return firstLetter.toUpperCase() + str.substr(1);
}
$('#product_id').change(function(){
       var id = $(this).val();
       $('#model_number').html('<option class="input input-lg" value="">Select model</option>');
       $('#serial_number').html('<option class="input input-lg" value="">Select serial number</option>');
        var _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
              type: "POST",
              contentType: "application/json; charset=utf-8",
              url: "/admin/cases/product_info/"+id+'?_token='+_token,
              success: function (result) {
                var model = result.model;
                var serial_number = result.serial_num;
                var serialOptions = '<option value="">Select serial number</option>';
                var modelOptions='<option value="">Select model</option>';
                for (let m of model){
                    modelOptions = modelOptions + '<option value="'+m.id+'">'+ucfirst(m.model_name)+'</option>';
                }
                $('#model_number').html(modelOptions);
                $('#serial_number').html(serialOptions);
        }
    });
});
$('#model_number').change(function(){
    var id = $(this).val();
    $('#serial_number').html('<option class="input input-lg" value="">Select serial number</option>');
        var _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
              type: "POST",
              contentType: "application/json; charset=utf-8",
              url: "/admin/cases/model_serial_no/"+id+'?_token='+_token,
              success: function (result) {
                var serial_number = result.serial_num;
               var serialOptions = '<option value="">Select serial number</option>';
                for (let m of serial_number){
                    serialOptions = serialOptions + '<option value="'+m.id+'">'+ucfirst(m.serial_num)+'</option>';
                }
                $('#serial_number').html(serialOptions);
        }
    });
});





function createcustomer(type){
    var win =  window.open(`/admin/clients/modals?relation_type=${type}`, '_blank','toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=600, height=650');
    
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
    if(result.relation_type == 'customer'){var select_id = 'customers_id'} else {var select_id = 'dealer_id'}
    $(`#${select_id} select`).html(option);
    setTimeout(function(){
        $(`#${select_id} select`).val(result.lastcreated);
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
$('#more-button').click(function(){
       $(".multipleDiv-attachment").after($('#morefiles #more-custom-tr').html());
});
$(document).on('click','.remove-this-attachment',function(){
      $(this).parent().parent().remove();
    });
const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
$(document).on('change','.attachment',function(){
  var input = this;
  // console.log('done');
   var parent = $(this).parent().parent();
      if (input.files && input.files[0]) {
        var fileType = input.files[0]['type'];
        var reader = new FileReader();
        reader.onload = function (e) {
          if (validImageTypes.includes(fileType)) {
            parent.find('.uploads')
                .attr('src', e.target.result)
                .width(150)
                .height(200);
            }
          else{
             parent.find('.uploads')
                .attr('src','')
                .width(0)
                .height(0);
          }
       
        };

        reader.readAsDataURL(input.files[0]);
    }
});
$(document).on('click','.remove-this',function(){
  $(this).parent().parent().remove();
});



</script>

</div>
</div>




<div id="morefiles" style="display: none">
   <table class="table">
      <tbody id="more-custom-tr">
         <tr>
            <td class="moreattachment" style=""> 
               <input type="file" name="attachment[]" class="attachment" >
            </td>
            <td class="w-25" >
               <img src=""  style="max-height: 100px;float: right;margin-left: 30px" class='uploads'>
            </td>
            <td >
               <a href="javascript:void()" style="font-size: 20px; float: right" class="remove-this-attachment"> <i class="fa fa-close deletable"></i></a>
            </td>
         </tr>
      </tbody>
   </table>
</div>


<div style="display: none" id="moretrdata">
<table class="table">
<tbody id="more-custom-tr">
        <tr>
            <td><input class="form-control input-sm" type="text" name="part_code[]" placeholder="parts code" required=""></td>
            <td><input class="form-control input-sm" type="text" name="description1[]" placeholder="description"></td>
            <td><input class="form-control quanity input-sm" type="text" name="quantity[]" placeholder="quanity"></td>
            <td><input class="form-control rate input-sm" type="text" name="rate[]" placeholder="rate"></td>
            <td><input class="form-control amount input-sm" type="text" name="amount[]" placeholder="amount"></td>
            <td ><input type="text" name="remark[]" class="form-control input-sm" style="width: 80%;float: left" placeholder="remark">
            <a href="javascript::void(1);" style="width: 10%; float: right">
                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                    </a></td>
        </tr>
    </tbody>
</table>
<table class="table">
<tbody id="more-custom-tr1">
        <tr>
            <td><input class="form-control visitdatetime input-sm" type="text" name="visit_date_time[]" placeholder="Visit date/time" required=""></td>
            <td><input class="form-control input-sm" type="text" name="service_engineer[]" placeholder="Service Enginner"></td>
            <td><input class="form-control input-sm" type="text" name="call_status[]" placeholder="Call status"></td>
            <td><input class="form-control input-sm" type="text" name="peding_reasons[]" placeholder="Pending reasons"></td>
            <td><input class="form-control input-sm" type="text" name="remarks[]" placeholder="Remaks"  style="width: 80%;float: left" >
            <a href="javascript::void(1);" style="width: 10%; float: right">
            <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
            </a></td>
        </tr>
    </tbody>
</table>
    </div>
@endsection
