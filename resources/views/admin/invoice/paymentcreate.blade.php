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
                   <form action="{{route('admin.payment.invoice.create',$invoice_id)}}" method="post" enctype="multipart/form-data">

                    {{ csrf_field() }}
            
                <div class="content col-md-9">
                   <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-6">Payment Date</label>
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
                                    <input type="text" name="reference_no" placeholder="Reference No" id="" 
                                    value="{{ old('company_id') ?? date('Ymds')  }}" class="form-control"  required>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-building"></i></a>
                                    </div>
                                </div>

                            </div>   
                       </div>  

                  </div>
                  
                  <input type="hidden" name="invoice_id"  value="{{$invoice_id}}">

                    <div class="row">

                        <div class="col-md-6">
                          <div class="form-group">  
                           <label class="control-label col-sm-6">Amount</label>
                            <div class="input-group ">
                                <input type="text" name="amount" placeholder="Amount" id="price_value" value="{{ $payment_remain }}" class="form-control" required="required">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-credit-card"></i></a>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-4">Paying In</label>
                                <div class="col-md-8">
                                  <select class = 'form-control searchable select2' name='payment_method' >
                
                    <?php 
                    //Sunny_deptors
                    $groups= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id','13')->where('org_id',\Auth::user()->org_id)->get();   
                    foreach($groups as $grp)
                    {
                        echo '<option value="'.$grp->id.'"'.
                        (($grp->name==$client->type)?'selected="selected"':"").
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
                                <div class="input-group ">
                                    <input type="file" name="attachment" >
                                  
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">  
                            <label class="control-label col-sm-4">TDS</label>
                                <div class="input-group ">
                                    <input type="number" step="any" name="attachment" >
                                  
                                </div>
                            </div>
                        </div>
                    </div>

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
                       <button type="Submit" class="btn btn-success">Create Payment</button>
                        <a href="{!! route('admin.invoice.payment',$invoice_id) !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
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

