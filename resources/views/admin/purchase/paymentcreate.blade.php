@extends('layouts.master')

@section('head_extra')

 @include('partials._head_extra_select2_css')

@endsection
@section('content')
 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title !!}

                <small>{!! $page_description !!}</small>
            </h1>
            <span style="float: right;">
              <b>Real Amount to Pay:-</b> {{ $purchase_order->currency }}&nbsp;{!! number_format($purchase_order->total,2) !!}
              <?php $paid_amount = \TaskHelper::getPurchasePaymentAmount($purchase_order->id); ?>

              <b>Paid Amount:-</b>{{  number_format($paid_amount,2)  }}

              <input type="hidden" id='realAmount' value="{{ $purchase_order->total }}">
              <input type="hidden" id='paymentAmount' value="{{ $paid_amount }}">
            </span>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>

  <div class='row'>
        <div class='col-md-12'>
            <div class="box">
                <div class="box-body">
                   <form action="/admin/payment/purchase/{{$purchase_id}}" method="post" 
                   onsubmit="return validate_purchase()">

                    {{ csrf_field() }}
            
                <div class="content col-md-9">
                   <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-4">Date</label>
                              <div class="input-group ">
                                <input type="text" name="date" id="target_date" value="{{\Carbon\Carbon::now()->toDateString()}}" placeholder="Date" class="form-control datepicker" required="required">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                              </div>
                            </div>
                         </div>

                        <div class="col-md-6">
                            <div class="form-group">  
                                  <label class="control-label col-sm-4">Reference No</label>
                                <div class="input-group ">
                                    <input type="text" name="reference_no" placeholder="Reference No" id="" value="{{ date('YmdHis') }}" class="form-control"  required>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-building"></i></a>
                                    </div>
                                </div>

                            </div>   
                       </div>  

                  </div>


                  
                  <input type="hidden" name="purchase_id"  value="{{$purchase_id}}">

                    <div class="row">

                        <div class="col-md-6">
                          <div class="form-group">  
                           <label class="control-label col-sm-4">Pay Amount <small>(to supplier)</small></label>
                            <div class="input-group ">
                                <input type="text" name="amount" placeholder="Amount" id="price_value" value="{{ $payment_remain }}" class="form-control"  required="required">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-credit-card"></i></a>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-4">Paying By</label>
                                <div class="col-md-8">
                                  <select class = 'form-control searchable select2' name='payment_method' >
                
                    <?php 
                    //Sunny_deptors
                    $groups= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id','13')->where('org_id',\Auth::user()->org_id)->get();
                    foreach($groups as $grp)
                    {
                        echo '<option value="'.$grp->id.'"'.
                        (( isset($client) && $grp->name==$client->type)?'selected="selected"':"").
                        '>'
                        .$grp->name.'</option>';
                    }                    
                     ?>
              
                
            </select>

                                 </div>
                            </div>
                        </div>

                    </div>                    
                   
                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">  
                            <label class="control-label col-sm-4">Attachment</label>

                                <div class="col-md-8">
                                    <input type="file" name="attachment" >
                                  
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id='tdsDiv'>
                          <div class="form-group">  
                            <label class="control-label col-sm-4" title="Tax Deducted at Source">TDS <small>(optional)</small></label>
                                <div class="input-group ">
                                  <input type="number" name="tds" step="any" class="form-control" placeholder="Enter Tds amount" id='tds'>
                                  <div class="input-group-addon">
                                      <a href="#"><i class="fa fa-credit-card"></i></a>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>

                  {{--     <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">  
                            <label class="control-label col-sm-4">Real Bill Amount</label>
                                <div class="input-group ">
                                  <input type="number"  step="any" class="form-control" placeholder="Enter Bill amount" 
                                  value="" readonly id='bill_amount'>
                                  <div class="input-group-addon">
                                      <a href="#"><i class="fa fa-credit-card"></i></a>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                <div class="row">

                     <div class="col-md-12">
                        <label for="inputEmail3" class="control-label">
                        Note
                        </label>
                          
                          <textarea class="form-control" name="note" id="description" placeholder="Write Note">{!! \Request::old('note') !!}</textarea>
                        </div>
                </div>


                </div><!-- /.content -->

                <div class="col-md-12">
                    <div class="form-group">
                       <input type="Submit" class='btn btn-success btn-sm' value="Create Payment">
                        <a href="/admin/payment/purchase/{{$purchase_id}}" class='btn btn-default btn-sm'>{{ trans('general.button.cancel') }}</a>
                    </div>
                </div>

                  </form>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection

@section('body_bottom')
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

    <script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD', 
          sideBySide: true,
          allowInputToggle: true
        });


        // $('#price_value').keyup(function(){

        //   let price_value = Number($(this).val());
        //   console.log(price_value);
        //   let totalPaid = Number($('#paymentAmount').val());

        //   let total = Number($('#realAmount').val()) - totalPaid;

        //   if(price_value != total){

        //     $('#tds').val('0');
        //     $('#tdsDiv').hide();

        //   }else{

        //      $('#tdsDiv').show();
        //   }




        // });


      });

    function validate_purchase(){

      let price_value = Number($('#price_value').val());
      
      let tds = Number($('#tds').val());

      let total = price_value + tds;

      let totalPaid = total + Number($('#paymentAmount').val());

      if(totalPaid > Number($('#realAmount').val())){
        alert("AMOUNT NOT VALID");
        return false;
      }

      return true;



    }
</script>

 @endsection

