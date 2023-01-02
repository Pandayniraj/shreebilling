@extends('layouts.master')

@section('content')

<style type="text/css">
  .blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title" }}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {{ TaskHelper::topSubMenu('topsubmenu.payroll')}}
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>


<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Monthly Pay Frequency
</h3>

        </div>
        <div class="box-body">
               <form id="payment_form" role="form" enctype="multipart/form-data" action="/admin/payroll/run/timecard_review" method="post" class="form-horizontal form-groups-bordered">

                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Pay Frequency<span
                                class="required">*</span></label>
                        <div class="col-sm-5">
                            <select required name="frequency_id" id="frequency" class="form-control select_box">
                                <option value="">Select Frequency</option>
                                @foreach($payfrequecny as $dk => $dv)
                                <optgroup label="{{$time_entry_method[$dk]}}">
                                  @if(count($dv) > 0)
                                  @foreach($dv as $value)
                                  <option value="{{ $value->id }}" >{{ $value->name }} [{{ $frequency[$value->frequency] }}]</option>
                                  @endforeach
                                  @else
                                  <option value="" disabled="">Not Found</option>
                                  @endif
                                </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="payment_month" class="col-sm-3 control-label">Start Date<span class="required"> *</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input  type="text" class="form-control" name="start_date" id="start_date" readonly="" required='true'>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="form-group">
                        <label for="payment_month" class="col-sm-3 control-label">End Date<span class="required"> *</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input required type="text" class="form-control payment_month"  name="end_date" id="end_date" readonly="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"></label>
                        <div class="col-sm-5 ">
                            <button type="submit" id="sbtn" class="btn btn-primary">  <i class="fa fa-play"></i>&nbsp; Start Payroll</button>
                        </div>
                    </div>

                </form>
       <!--    <div class="row">
            <div class="col-xs-12 text-center">
              <a href="/admin/payroll/run/timecard_review" type="button" class="btn btn-default btn-lrg ajax">
                <i class="fa fa-play"></i>&nbsp; Start Payroll
              </a>
            </div>
          </div> -->
          <div class="ajax-content">
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>


     




 <script type="text/javascript">
   function getdates(id){
    $.get('/admin/payroll/run/step1',{id:id},function(data){

       $('#start_date').val(data.data.period_start_date);
       $('#end_date').val(data.data.period_end_date);
      
    });
   } 
         
    $(function(){

      $('#frequency').change(function(){
        let id = $(this).val();
        $('#start_date').val('');
        $('#end_date').val('');
        getdates(id);
      });

    })
 </script>


@endsection