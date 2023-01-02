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
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

                {!! Form::model( $case, ['route' => ['admin.cases.update', $case->id], 'method' => 'PUT', 'id' => 'form_edit_case', 'enctype' => 'multipart/form-data'] ) !!}
				<div class="row" style="margin-top: -10px">
                    <div class="col-md-6">
                        <h4>
                        <label>Job No.&nbsp;</label><span>#{{$case->job_no}}</span>
                    </h4>
                    </div>
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
            {!! Form::text('telephone', null, ['class' => 'form-control input-sm','id'=>'telephone']) !!}
        </div>
       
         <div class="form-group">
        <label>Select Dealer <i class="imp">Or</i></label>&nbsp;<small><a href="javascript::void()" 
                onclick="createcustomer('dealer')">create new dealer</a></small>
            </label>
                        <select id="dealer" class="form-control lead_id select2" name="dealer_name" >
                        <option class="input input-lg" value="">Select Dealer</option>

                        @if(isset($dealer))
                            @foreach($dealer as $key => $v)
                                <option value="{{ $v->id }}" @if($v->id == $case->dealer_name){{ 'selected="selected"' }}@endif>{{ $v->name }}</option>
                            @endforeach
                        @endif
                        </select>
                    </div>
        <div class="form-group">
            {!! Form::label('preferred', 'Preferred Date/Time') !!}
            {!! Form::text('preff_d_t', null, ['class' => 'form-control preferred input-sm']) !!}
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
            {!! Form::text('cal_date', null, ['class' => 'form-control caldate input-sm', 'id'=>'subject']) !!}
        </div>
         <div class="form-group">
        <label>Select Products <i class="imp">*</i></label>
                        <select id="product_id" class="form-control lead_id select2" name="product" >
                        <option class="input input-lg" value="">Select Products</option>

                        @if(isset($producs))
                            @foreach($producs as $key => $v)
                                <option value="{{ $v->id }}" @if($v->id == $case->product){{ 'selected="selected"' }}@endif>{{ $v->name }}</option>
                            @endforeach
                        @endif
                        </select>
        </div>
          <div class="form-group">
        <label>Select Model <i class="imp">*</i></label>
            <select id="model_number" class="form-control lead_id select2" name="model_no">
            <option class="input input-lg" value="">Select model</option>
            @foreach($model as $md)
            <option value="{{$md->id}}" @if($md->id == $case->model_no) selected @endif>{{ucfirst($md->model_name)}}</option>
            @endforeach
            </select>
        </div>
         <div class="form-group">
        <label>Serial number<i class="imp">*</i></label>
            <select id="serial_number" class="form-control lead_id select2" name="serial_no">
            <option class="input input-lg" value="">Select serial number</option>
            @foreach($serial_num as $sn)
            <option value="{{$md->id}}" @if($sn->id == $case->serial_no) selected @endif>{{ucfirst($sn->serial_num)}}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group">
            {!! Form::label('dateofpur', 'Date Of Puchase') !!}
            {!! Form::text('dop', null, ['class' => 'form-control dop input-sm', 'id'=>'subject']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('dateofamc', 'Date Of AMC') !!}
            {!! Form::text('do_amc', null, ['class' => 'form-control amc input-sm', 'id'=>'subject']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('sysstatus', 'System Status') !!}
            {!! Form::text('sys_status', null, ['class' => 'form-control input-sm', 'id'=>'subject']) !!}
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
                <div class="col-md-12"><button class="btn btn-default btn-xs addmore" type="button"><i class="fa fa-plus"></i>  Add more</button>
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
                    @foreach($casepart1 as $case1)
                    <tr>
                        <td><input class="form-control input-sm" type="text" name="part_code[]" placeholder="parts code" value="{{$case1->part_code}}"></td>
                        <td><input class="form-control input-sm" type="text" name="description1[]" placeholder="description" value="{{$case1->description}}"></td>
                        <td><input class="form-control quanity input-sm" type="text" name="quantity[]" placeholder="quanity" value="{{$case1->quantity}}"></td>
                        <td><input class="form-control rate input-sm" type="text" name="rate[]" placeholder="rate" value="{{$case1->rate}}"></td>
                        <td><input class="form-control amount input-sm" type="text" name="amount[]" placeholder="amount" value="{{$case1->amount}}"></td>
                        <td ><input type="text" name="remark[]" class="form-control input-sm" style="width: 80%;float: left" placeholder="remark" value="{{$case1->remark}}">
                        <a href="javascript::void(1);" style="width: 10%; float: right">
                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                        </a></td>
                    </tr>
                    @endforeach
                     <tr class="multipleDiv">
                     </tr>
                     <tr>
                        <td><span>Customer`s Comments</span></td>
                        <td><input class="form-control input-sm" type="text" name="cust_comments" value="{{$case->cust_comments}}"></td>
                        <td><span>Payment details</span></td>
                        <td><input class="form-control input-sm" type="text" name="payment_details" value="{{$case->payment_details}}"></td>
                     
                     </tr>
                     <tr>
                        <td colspan="3"></td>
                        <td style="float: right">Total</td>
                        <td><input class="form-control totalamount input-sm" type="number" name="total_amount" id="totalamount" readonly="" value="{{$case->total_amount}}"></td>
                        <td><input class="form-control input-sm" type="text" name="total_amount_rem" placeholder="remark" value="{{$case->total_amount_rem}}"></td>
                     </tr>
                     <tr>
                        <td colspan="3"></td>
                        <td style="float: right;">Labour</td>
                        <td><input class="form-control input-sm" type="text" name="labour" id="labour" value="{{$case->labour}}"></td>
                        <td><input class="form-control input-sm" type="text" name="labour_rem" placeholder="remark" value="{{$case->labour_rem}}"></td>
                     </tr>
                       <tr>
                        <td colspan="3"></td>
                        <td style="float: right">Transport</td>
                         <td><input class="form-control input-sm" type="text" name="transport" id="transport" value="{{$case->transport}}"></td>
                         <td><input class="form-control input-sm" type="text" name="transport_rem" placeholder="remark" value="{{$case->transport_rem}}"></td>
                     </tr>
                     <tr>
                        <td colspan="3"></td>
                        <td style="float: right">AMC</td>
                         <td><input class="form-control input-sm" type="text" name="amc"  id="amc" value="{{$case->amc}}"></td>
                        <td><input class="form-control input-sm" type="text" name="amc_rem" placeholder="remark" value="{{$case->amc_rem}}"></td>
                     </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td style="float: right">TAX Percent</td>
                        <td><input class="form-control input-sm" type="number" id="tax_percent" 
                            value="{{ ($case->tax / $case->total_amount)*100 }}" step="any"></td>
                        <td>%</td>
                     </tr>
                       <tr>
                        <td colspan="3"></td>
                        <td style="float: right">TAX</td>
                         <td><input class="form-control input-sm" type="text" name="tax" id="tax" value="{{$case->tax}}" 
                            readonly=""></td>
                         <td><input class="form-control input-sm" type="text" name="tax_rem" placeholder="remark" value="{{$case->tax_rem}}"></td>
                     </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td style="float: right">Net Total</td>
                         <td><input class="form-control input-sm" type="text" name="net_total" id="nettotal" readonly="" value="{{$case->net_total}}"></td>
                        <td><input class="form-control input-sm" type="text" name="net_total_rem" placeholder="remark" value="{{$case->net_total_rem}}"></td>
                     </tr>

                   </tbody>
                </table>
            </div>

            </div>
            <div class="row">
                <div class="col-md-12">
            <button class="btn btn-default btn-xs addmore1" type="button">
                <i class="fa fa-plus"></i> Add more
            </button>
                <table class="table" style="float: left;">
                    <tbody class="moretr1">
                        <tr>
                            <th>Visit Date/time</th>
                            <th>Service Enginner</th>
                            <th>Call status</th>
                            <th>Pending reasons</th>
                            <th>Remarks</th>
                        </tr>
                        @foreach($casepart2 as $case2)
                        <tr>
                            <td><input class="form-control visitdatetime input-sm" type="text" name="visit_date_time[]" placeholder="Visit date/time" value="{{$case2->visit_date_time}}"></td>
                            <td><input class="form-control input-sm" type="text" name="service_engineer[]" placeholder="Service Enginner" value="{{$case2->service_engineer}}"></td>
                            <td><input class="form-control input-sm" type="text" name="call_status[]" placeholder="Call status" value="{{$case2->call_status}}"></td>
                            <td><input class="form-control input-sm" type="text" name="peding_reasons[]" placeholder="Pending reasons" value="{{$case2->peding_reasons}}"></td>
                            <td><input class="form-control input-sm" type="text" name="remarks[]" placeholder="Remaks" value="{{$case2->remarks}}" style="float: left;width: 80%;"><a href="javascript::void(1);" style="width: 10%; float: right">
                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                        </a> </td>
                        </tr>
                        @endforeach
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
                <div class="form-group">
                    {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="{!! route('admin.cases.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>

                {!! Form::close() !!}

<!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->

           <div class="box-footer">
    <ul class="mailbox-attachments clearfix">
       @foreach($cases_attachment as $pf)
                @if(is_array(getimagesize(public_path().'/case_attachments/'.$pf->attachment)))
                      <li>
                  <span class="mailbox-attachment-icon has-img"><img src="{{'/case_attachments/'.$pf->attachment }}" alt="TSk.#{{ $pf->id }}" style="height: 120px"></span>

                  <div class="mailbox-attachment-info">
                    <a href="{{ asset('/case_attachments/'.$pf->attachment) }}" class="mailbox-attachment-name" onclick="downloadPDF(this)"><i class="fa fa-camera"></i> {{ substr($pf->attachment,-14) }}</a>
                        <span class="mailbox-attachment-size">
                          {{ round(filesize(public_path().'/case_attachments/'.$pf->attachment) * 0.000001,2) }} MB
                          <a href="javascript::void()" class="btn btn-default btn-xs pull-right"    onclick="remove(this,{{ $pf->id }})"><i class="fa fa-trash deletable"></i></a>
                        </span>
                  </div>
                </li>
            @else
                <li>
                  <span class="mailbox-attachment-icon" style="height: 120px"><i class="fa fa-file-pdf-o"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="{{ asset('/case_attachments/'.$pf->attachment) }}" class="mailbox-attachment-name" onclick="downloadPDF(this)"><i class="fa fa-paperclip"></i> {{ substr($pf->attachment,-20) }}</a>
                        <span class="mailbox-attachment-size">
                          <?php 
                            $filesize =filesize(public_path().'/case_attachments/'.$pf->attachment);
                          ?>
                          @if(($filesize * 0.000001) > 0.1))
                            {{ ($filesize * 0.000001) }} MB 
                          @else

                          {{ round(filesize(public_path().'/case_attachments/'.$pf->attachment)/1024)  }} KB
                          @endif
                          <a href="javascript::void()" class="btn btn-default btn-xs pull-right"  onclick="remove(this,{{ $pf->id }})"><i class="fa fa-trash deletable"></i></a>
                        </span>
                  </div>
                </li>
         @endif
          @endforeach 
        </ul>
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
        });
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


function downloadPDF(thisobj) {        
    $.fileDownload($(thisobj).attr("href"));
}

 function remove(el,id){
      let parent = $(el).parent().parent().parent();
      console.log(id);

        let c = confirm('Are you sure you want to delete');
        if(c){
            $.get(`/admin/cases/${id}/deleteimg`,function(){
          });
          parent.hide(300);
        }
       
      }
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
