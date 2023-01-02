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
.control-sidebar.control-sidebar-dark{
  display:   none !important;
}
.payrollremarks:hover{
  cursor: pointer;
}

</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title" }}
                @if(isset($timecard))
                <small >
                <i style="color: red">
                Showing Timecard Modified at {{date('dS Y M',strtotime($timecard[0]->updated_at))}} by:
                </i>
                <b>&nbsp;{{$timecard[0]->issuedBy->username}}</b>
                </small>

                @else
                 <small >{!! $page_description ?? "Page description" !!}</small>
                @endif

            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>


<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">
                Monthly: {{date_format(date_create($payfrequency->period_start_date),'Y/m/d')}} 
                To: {{date_format(date_create($payfrequency->period_end_date),'Y/m/d')}}  
                Check date: {{date_format(date_create($payfrequency->check_date),'Y/m/d')}}
              </h3>

              <div class="box-tools">
                <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search" id="searchbar">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <form method="POST" action="/admin/payroll/run/enter_payroll" id='runpayroll'>
              {{ csrf_field() }}
              <input type="hidden" name="frequency_id" value="{{$payfrequency->id}}">
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th colspan="5" style="text-align: center;background-color:#8FBC8F;">Employee Info</th>
                    <th colspan="2" style="text-align: center">Working Hours</th>

                    <th style="text-align: center;background-color:#8FBC8F;">Total</th>
                    <th style="text-align: center;">Remarks</th>
                  </tr>
                </thead>
                <tr>
                  <th>Emp ID</th>
                  <th>User</th>
                  <th>Designation</th>
                  <th>Department</th>
                  <th>Time Entry</th>
                  <th>Regular / Hrs</th>
                  <th>Overtime / Hrs </th>
                  <th>Total /Hrs</th>
                  <th>Remarks</th>
                </tr>
                <tbody  id="searchabletable">

              @if(isset($timecard) && count($timecard) > 0)
              @foreach($timecard as $tc)
              <tr class="payrolldetails">
                <td>{{ env('SHORT_NAME') }} <span class="user_id">{{$tc->user_id}}</span></td>
                <td>{{$tc->user->first_name .' '.$tc->user->last_name}}</td>
                <td title="{{$tc->user->designation->designations}}">
                  @if(strlen($tc->user->designation->designations) > 10)
                  {{ substr($tc->user->designation->designations,0,15) .'...'}}
                    @else
                    {{$tc->user->designation->designations}}
                    @endif
                </td>
                <td title="{{$tc->user->designation->designations}}">
                    @if(strlen($tc->user->designation->designations) > 10)
                    {{ substr($tc->user->designation->designations,0,15).'...' }}
                    @else
                    {{ substr($tc->user->designation->designations,0,15) }}
                    @endif
                </td>
                 <td class="time_entry_method">{{$tc->time_entry_method}}</td>
                 <td>
                    <span class="editable regular">{{$tc->regular_days}}</span>
                  </td>
                  <td style=" text-indent: 30px;">
                    <span class="label overtime label-success editable ">{{$tc->ot_hour}}</span>
                  </td>
                   </td>
                     <td >
                      <span class="total">{{$tc->regular_days + ($tc->ot_hour ) }}
                      </span>
                      </td>
                  <td title="{{$tc->remarks}}">
                    <span class="payrollremarks" style="max-width: 60px; max-height: 20px; position: absolute; text-overflow: ellipsis; overflow: hidden">
                      {{$tc->remarks}}
                    </span>
                  </td>
              </tr>
              @endforeach
              @else 
                @foreach($result as $r)
                <tr class='payrolldetails'>
                  <td>{{ env('SHORT_NAME') }}<span class="user_id">{{ $r['user_id'] }}</span></td>
                  <td>{{ $r['username'] }}</td>
                  <td title="{{$r['designation']}}">
                    @if(strlen($r['designation']) > 10){{ substr($r['designation'],0,15) .'...'}}
                    @else
                    {{$r['designation']}}
                    @endif
                  </td>
                  <td title="{{$r['departments']}}">
                    @if(strlen($r['departments']) > 10)
                    {{ substr($r['departments'],0,15).'...' }}
                    @else
                    {{ substr($r['departments'],0,15) }}
                    @endif
                  </td>
                  <td class="time_entry_method">{{$entrymethod[$r['time_entry_method']]}}</td>
                  <td data-container="body"data-toggle="tooltip" data-placement="bottom" data-html="true" title="{{implode('<br>',$r['work_report'][0])}}">
                    <span class="editable regular">{{$r['regular']}}</span>
                  </td>
                  <td style=" text-indent: 30px;" data-container="body"data-toggle="tooltip" data-placement="bottom" data-html="true" title="{{implode('<br>',$r['work_report'][1])}}">
                    <span class="label overtime label-success editable ">{{$r['overtime']}}</span>
                  </td>
                    <td ><span class="total">{{$r['total']}}</span></td>
                  <td>
                    <span class="payrollremarks" style="max-width: 60px; max-height: 20px; position: absolute; text-overflow: ellipsis; overflow: hidden;"></span>
                  </td>
                
                  </tr>
                @endforeach
            @endif
              </tbody></table>

              <a href="#" type="button" class="btn btn-default btn-lg" onClick="submitRun('later')">
                <i class="fa fa-pause"></i>&nbsp; Finish Later
              </a><!-- <a href="/admin/payroll/run/enter_payroll" type="button" class="btn btn-primary btn-lg">
                <i class="fa fa-play"></i>&nbsp; Next
              </a> -->
              <a href="#" type="button" class="btn btn-primary btn-lg" onClick="submitRun('next')">
                <i class="fa fa-play"></i>&nbsp; Next
              </a>

            </div>
          </form>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
</div>
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function(){
  $("#searchbar").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#searchabletable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

$('.editable').each(function(){
  var parent = $(this).parent().parent();
  var title = $(this).attr("class").split(/\s+/);
  $(this).editable({
    type: 'text',
      pk: 1,
      url: null,
      title: `Adjust ${title[1]}`,
      success: function(response, newValue) {
        setTimeout(function(){
           let regular = parent.find('.regular').text();
           let overtime = parent.find('.overtime').text();
           let total = Number(regular) + Number(overtime);
           parent.find('.total').text(total.toFixed(3));

        });
      },
      validate: function(value) {
          if ($.isNumeric(value) == '') {
              return 'Only Numberical value is allowed';
          }
      }
    });
});

});
$('.payrollremarks').each(function(){
  $(this).editable({
    type:'textarea',
    pk:1,
    url:null,
    title: `Timcard Remarks`,
  })
});
function submitRun(runtype){
  let c = confirm('Are You Sure Information Is correct');
  if(!c){
    return false;
  }
  var formElement = document.querySelector("#runpayroll");
  var formData = new FormData(formElement);
  var data = [];
  $('.payrolldetails').each(function(){
    let parent = $(this);
    let obj = {
      user_id:parent.find('.user_id').text(),
      regular_days:parent.find('.regular').text(),
      ot_hour:parent.find('.overtime').text(),
      time_entry_method: parent.find('.time_entry_method').text(),
      remarks:parent.find('.payrollremarks').text(),
    }
    data.push(obj);
  });
  let jsondata = JSON.stringify(data);
  $('<input type="hidden" name="data"/>').val(jsondata).appendTo('#runpayroll');
  $('<input type="hidden" name="runtype"/>').val(runtype).appendTo('#runpayroll');
  $("#runpayroll").submit();
}
</script>

@endsection