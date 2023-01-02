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
          <h3 class="box-title">Monthly Pay Frequency</h3>

        </div>
        <div class="box-body">
               <form id="payment_form" role="form" enctype="multipart/form-data" 
               action="#" method="post" class="form-horizontal form-groups-bordered">

                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Select Users<span class="required">*</span></label>
                        <div class="col-sm-5">
                            <select required name="user_id" id="__users" class="form-control select_box">
                                <option value="">Select Users</option>
                                @foreach($users as $dk => $dv)
                                  <option value="{{$dv->id}}">
                                    {{ $dv->username }} [{{ $dv->id }}]
                                  </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                      <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Select Payfrequency<span
                                class="required">*</span></label>
                        <div class="col-sm-5">
                            <select required name="payfrequency_id" id="payfrequency_id" class="form-control select_box">
                                <option value="">Select Payfrequency</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"></label>
                        <div class="col-sm-5 ">
                            <button type="submit" id="sbtn" class="btn btn-primary">  <i class="fa fa-search"></i>&nbsp; Filter Payment</button>
                        </div>
                    </div>

                </form>
      
          </div> 
          
        </div>
       
    
<div class="result">
    <div align="center" style="display: none;" id='filter_spinner'>
      <i class="fa fa-spinner fa-spin" style="zoom: 2;"></i>
    </div>
    <div id='filter_result'>
    </div>
</div>



<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
 <script type="text/javascript">
  $(function(){
      $('.select_box').select2();
      $('#__users').change(function(){
        let id = $(this).val();
        var option = `<option value=''>Select Payfrequency </option>`;
        $('#payfrequency_id').html(option);
        $.get(`/admin/payroll/run/userPayfrequecny/${id}`,function(response){
          let frequency = response.frequency;
          for(let f of frequency){
            option += `<option value='${f.id}'>${f.name}[${f.period_start_date}-${f.period_end_date}]</option>`;
          }
          $('#payfrequency_id').html(option);
        }).fail(function(){
          alert("Failed To load");
        })
      });

      $('#payment_form').submit(function(ev){
        $('#filter_result').html('');
        $('#filter_spinner').show();
        let token = $('#payment_form input[name=_token]').val();
        let data = {
          _token: token,
          user_id:  $('#payment_form select[name=user_id]').val(),
          payfrequency_id: $('#payment_form select[name=payfrequency_id]').val(),
        };
        console.log(data)
        ev.preventDefault();
        $.post(`{{ route('admin.payroll.filter') }}`,data,function(response){
           $('#filter_spinner').hide();
           $('#filter_result').html(response.result)
        }).fail(function(){
          alert("failed");
        })
        return false;
      })
  });

 </script>


@endsection