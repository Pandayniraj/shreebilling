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
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>

  <div class='row'>
        <div class='col-md-12'>
            <div class="box">
                <div class="box-body">


                <form action="/admin/payment/orders/{{$sale_id}}/{{$payments->id}}" method="post">

                    {{ csrf_field() }}
            
                 <div class="content col-md-9">
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-3">Date</label>
                              <div class="input-group ">
                                <input type="text" name="date" id="target_date" value="{{ $payments->date }}" placeholder="Date" class="form-control datepicker" required="required">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar-alt"></i></a>
                                </div>
                              </div>
                            </div>
                         </div>

                        <div class="col-md-6">
                            <div class="form-group">  
                                  <label class="control-label col-sm-3">Reference No</label>
                                <div class="input-group ">
                                    <input type="text" name="reference_no" placeholder="Reference No" id="" value="{{ $payments->reference_no}}" class="form-control"  required>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-building"></i></a>
                                    </div>
                                </div>

                            </div>   
                       </div>  
                  </div>

                  <input type="hidden" name="sale_id"  value="{{ $payments->sale_id }}">

                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">  
                           <label class="control-label col-sm-3">Amount</label>
                            <div class="input-group ">
                                <input type="text" name="amount" placeholder="Amount" id="price_value" value="{{$payments->amount}}" class="form-control" required="required">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-credit-card"></i></a>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-3">Paying By</label>
                                <div class="col-md-9">
                                  {!! Form::select('paid_by', $payment_method, $payments->paid_by, ['class' => 'form-control select2','id'=>'products', 'placeholder' => 'Please Select']) !!}
                                </div>
                            </div>
                        </div>
                    </div>                    
                   
                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">  
                            <label class="control-label col-sm-3">Attachment</label>
                                <div class="input-group ">
                                    <input type="file" name="attachment" >
                                  
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="row">
                     <div class="col-md-12">
                        <label for="inputEmail3" class="control-label">
                        Note
                        </label>
                          <textarea class="form-control" name="note" id="description" placeholder="Write Note">{!! $payments->note !!}</textarea>
                        </div>
                </div>


                </div><!-- /.content -->

                <div class="col-md-12">
                    <div class="form-group">
                       <input type="Submit" value="Update Payment">
                        <a href="{!! route('admin.payment.orders.list',$sale_id) !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
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

      });
</script>

 @endsection

