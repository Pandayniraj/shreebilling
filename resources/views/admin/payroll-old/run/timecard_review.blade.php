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
            <p> Adjust Staff working hours days and leaves before the payroll begins. There may be a match checking against leave approval.
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>


<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <p class=""><?php $active_year = date('Y',strtotime($payfrequency->period_start_date)); ?>
                <i class="fa fa-calendar"></i> Monthly: {{date('d M Y',strtotime($payfrequency->period_start_date))}}  
                To: {{date('d M Y',strtotime($payfrequency->period_end_date))}}  
                &nbsp; | &nbsp; <i class="fa fa-money"></i> Check date: {{date('d M Y',strtotime($payfrequency->check_date))}}  
                &nbsp; | &nbsp; <i class="fa fa-random"></i> Method: {{ $result?$entrymethod[$result[0]['time_entry_method']]:'' }} {{$timecard[0]->time_entry_method}} 
              </p>

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
                    <th colspan="4" style="text-align: center" class="bg-success">Employee Info</th>
                     <th colspan="1" style="text-align: center" class="bg-primary" > Days</th>
                    <th colspan="1" style="text-align: center" class="bg-danger" > OT</th>
                    <th colspan="2" style="text-align: center" class="bg-info">Work on Leave</th>
                    <th colspan="4" style="text-align: center" class="bg-primary">Leave</th>
                    <th style="text-align: center" class="bg-success">Total</th>
                    <th style="text-align: center;" class="bg-info">Remarks</th>
                  </tr>
                </thead>
                <tr>
                  <th>Emp ID</th>
                  <th>User</th>
                  <th>Designation</th>
                  <th>Department</th>
                  <th>Regular</th>
                  <th>Overtime / Hrs <a href="/admin/payroll/over_time/" target="_blank"><i class="fa fa-plus"></i></a></th>
                  <th>Weekend</th>
                  <th>Public Holiday</th>
                  <th>Sick</th>
                  <th>Anual</th>
                  <th>Public</th>
                  <th>Other</th>
                     <th>Total</th>
                  <th>Remarks</th>
               
                </tr>
                <tbody  id="searchabletable">

              @if(isset($timecard) && count($timecard) > 0)
              @foreach($timecard as $tc)
              <tr class="payrolldetails">
                <td class="bg-success">
                  {{ env('SHORT_NAME') }} <span class="user_id">{{$tc->user_id}}</span>
                </td>
                <td>{{$tc->user->first_name .' '.$tc->user->last_name}}</td>
                <td title="{{$tc->user->designation->designations}}">
                  @if(strlen($tc->user->designation->designations) > 10)
                  {{ substr($tc->user->designation->designations,0,10) .'...'}}
                    @else
                    {{$tc->user->designation->designations}}
                    @endif
                </td>
                <td title="{{$tc->user->department->deptname}}">
                    @if(strlen($tc->user->department->deptname) > 10)
                    {{ substr($tc->user->department->deptname,0,10).'...' }}
                    @else
                    {{ substr($tc->user->department->deptname,0,10) }}
                    @endif
                </td>
                 
                 <td>
                    <span class="editable regular">{{$tc->regular_days}}</span>
                  </td>
                  
                  <td style=" text-indent: 30px;">
                    <span class="label overtime label-success editable ">{{$tc->ot_hour}}</span>
                  </td>
                  <td><span class="editable weekend">{{$tc->weekend}}</span></td>
                  <td><span class="editable public_holiday_work">{{$tc->public_holiday_work}}</span></td>
                  <td>
                    <span class='editable sick_leave'>{{$tc->sick_leave?$tc->sick_leave:0}}</span>
                  <span title="Sick Leave Exceeds" class="sick_leave_warning" style=
                  "
                  @if(\TaskHelper::userLeave($tc->user_id, $leavecollection['sick_leave']['id'], $active_year) > $leavecollection['sick_leave']['quota'])
                  visibility: visible;
                  @else
                  visibility: hidden;
                  @endif
                  ">
                  <a href="#" onclick="openleavereport()"><i style="color: orange" class="fa fa-exclamation-triangle"></i></a>
                </span>

                  </td>
                   <td >
                   <span class='editable annual_leave'>{{$tc->annual_leave?$$tc->annual_leave:0}}</span>
                <span title="Anual Leave Exceeds" class="annual_leave_warning" style=
                  "
                  @if(\TaskHelper::userLeave($tc->user_id, $leavecollection['annual_leave']['id'], $active_year) > $leavecollection['annual_leave']['quota'])
                  visibility: visible;
                  @else
                  visibility: hidden;
                  @endif
                  ">
                  <a href="#" onclick="openleavereport()"><i style="color: orange" class="fa fa-exclamation-triangle"></i></a>
                </span>
                  </td>
                   <td >
                  <span class='editable public_holidays'>{{$tc->public_holidays?$tc->public_holidays:0}}</span>
                  <span title="Anual Leave Exceeds" class="public_holidays_warning" style=
                  "
                  @if(\TaskHelper::userLeave($tc->user_id, $leavecollection['public_holidays']['id'], $active_year) > $leavecollection['public_holidays']['quota'])
                  visibility: visible;
                  @else
                  visibility: hidden;
                  @endif
                  ">
                  <a href="#" onclick="openleavereport()"><i style="color: orange" class="fa fa-exclamation-triangle"></i></a>
                </span>
                  </td>
                  <td>
                   <span class='editable others_leave'>{{$tc->other_leave?$tc->other_leave:0}}</span>
                  <span title="Anual Leave Exceeds" class="others_warning" style=
                  "
                  @if(\TaskHelper::userLeave($tc->user_id, $leavecollection['others']['id'], $active_year) > $leavecollection['others']['quota'])
                  visibility: visible;
                  @else
                  visibility: hidden;
                  @endif
                  ">
                  <a href="#" onclick="openleavereport()"><i style="color: orange" class="fa fa-exclamation-triangle"></i></a>
                </span>
                  </td>
                  </td>
                    <td >
                      <span class="total">
                        {{round($tc->regular_days + $tc->weekend + $tc->public_holiday_work +($tc->ot_hour / 24) - ($tc->sick_leave + $tc->annual_leave + $tc->public_holidays + $tc->other_leave),2)}}
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
                  <td class="bg-danger">
                    {{ env('SHORT_NAME') }}<span class="user_id">{{ $r['user_id'] }}</span>
                  </td>
                  <td>{{ $r['username'] }}</td>
                  <td title="{{$r['designation']}}">
                    @if(strlen($r['designation']) > 10){{ substr($r['designation'],0,10) .'...'}}
                    @else
                    {{$r['designation']}}
                    @endif
                  </td>
                  <td title="{{$r['departments']}}">
                    @if(strlen($r['departments']) > 10)
                    {{ substr($r['departments'],0,10).'...' }}
                    @else
                    {{ substr($r['departments'],0,10) }}
                    @endif
                  </td>
                  
                  <td data-container="body"data-toggle="tooltip" data-placement="bottom" data-html="true" title="{{implode('<br>',$r['regular'][1])}}">
                    <span class="editable regular">{{$r['regular'][0]}}</span>
                  </td>
                  <td style=" text-indent: 30px;" data-container="body"data-toggle="tooltip" data-placement="bottom" data-html="true" title="{{implode('<br>',$r['overtime'][1])}}">
                    <span class="label overtime label-success editable ">{{$r['overtime'][0]}}</span>
                  </td>
                    <td data-container="body"data-toggle="tooltip" data-placement="bottom" data-html="true" title="{{implode('<br>',$r['weekend'][1])}}">
                      <span class="editable weekend">{{$r['weekend'][0]}}</span>
                    </td>
                  <td  data-container="body"data-toggle="tooltip" data-placement="bottom" data-html="true" title="{{implode('<br>',$r['public_holiday_work'][1])}}">
                  <span class="editable public_holiday_work">{{$r['public_holiday_work'][0]?$r['public_holiday_work'][0]:0}}</span>
                  </td>
                  <td  data-container="body"data-toggle="tooltip" data-placement="bottom" data-html="true" title="{{implode('<br>',$r['sick_leave'][1])}}">
                    <span class='editable sick_leave'>{{$r['sick_leave'][0]?$r['sick_leave'][0]:0}}</span>

                  <span title="Sick Leave Exceeds" class="sick_leave_warning" style=
                  "
                  @if(\TaskHelper::userLeave($r['user_id'], $leavecollection['sick_leave']['id'], $active_year) > $leavecollection['sick_leave']['quota'])
                    visibility: visible;
                  @else
                  visibility: hidden;
                  @endif
                  ">
                 <a href="#" onclick="openleavereport()"><i style="color: orange" class="fa fa-exclamation-triangle"></i></a>
                </span>
                  </td>
                  <td  data-container="body"data-toggle="tooltip" data-placement="bottom" data-html="true" title="{{implode('<br>',$r['annual_leave'][1])}}" >
                   <span class='editable annual_leave'>{{$r['annual_leave'][0]?$r['annual_leave'][0]:0}}</span>
                  <span title="Anual Leave Exceeds" class="annual_leave_warning" style=
                  "
                  @if(\TaskHelper::userLeave($r['user_id'], $leavecollection['annual_leave']['id'], $active_year) > $leavecollection['annual_leave']['quota'])
                  visibility: visible;
                  @else
                  visibility: hidden;
                  @endif
                  ">
                    <a href="#" onclick="openleavereport()"><i style="color: orange" class="fa fa-exclamation-triangle"></i></a>
                </span>
                  </td>
                  <td  data-container="body"data-toggle="tooltip" data-placement="bottom" data-html="true" title="{{implode('<br>',$r['public_holidays'][1])}}" >
                  <span class='editable public_holidays'>{{$r['public_holidays'][0]?$r['public_holidays'][0]:0}}</span>
                  <span title="Public Leave Exceeds" class="public_holidays_warning" style=
                  "
                  @if(\TaskHelper::userLeave($r['user_id'], $leavecollection['public_holidays']['id'], $active_year) > $leavecollection['public_holidays']['quota'])
                  visibility: visible;
                  @else
                  visibility: hidden;
                  @endif
                  ">
                <a href="#" onclick="openleavereport()"><i style="color: orange" class="fa fa-exclamation-triangle"></i></a> 
                </span>
                  </td>
                  <td  data-container="body"data-toggle="tooltip" data-placement="bottom" data-html="true" title="{{implode('<br>',$r['others'][1])}}">
                   <span class='editable others_leave'>{{$r['others'][0]?$r['others'][0]:0}}</span>
                  <span title="Other Leave Exceeds" class="others_warning" style=
                  "
                  @if(\TaskHelper::userLeave($r['user_id'], $leavecollection['others']['id'], $active_year) > $leavecollection['others']['quota'])
                  visibility: visible;
                  @else
                  visibility: hidden;
                  @endif
                  ">
                  <a href="#" onclick="openleavereport()"><i style="color: orange" class="fa fa-exclamation-triangle"></i></a>
                </span>
                  </td>
                    <td ><span class="total">{{$r['total']}}</span></td>
                  <td>
                    <span class="payrollremarks" style="max-width: 60px; max-height: 20px; position: absolute; text-overflow: ellipsis; overflow: hidden;"></span>
                  </td>
                
                  </tr>
                @endforeach
            @endif
              </tbody>


            </table>
            <hr/>

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
  function openleavereport(){
    window.open("/admin/leavereport", "_blank");
  }
$(document).ready(function(){
  $("#searchbar").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#searchabletable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
  const leave_quota = <?php echo json_encode($leavecollection);?>;
  console.log(leave_quota);
function setleavewarning(title,parent,newValue){
  let leave_type = ['sick_leave','annual_leave','public_holidays','others_leave'];
  if(leave_type.includes(title)){
    if(title == 'others_leave') title = 'others';  
    let quota = leave_quota[title]['quota'];
    if(Number(newValue) > Number(quota))
      parent.find(`.${title}_warning`).css('visibility','visible');
    else
       parent.find(`.${title}_warning`).css('visibility','hidden');
  }
}
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
           let sick_leave = parent.find('.sick_leave').text();
           let annual_leave = parent.find('.annual_leave').text();
           let public_holidays = parent.find('.public_holidays').text();
           let weekend = parent.find('.weekend').text();
           let public_holiday_work = parent.find('.public_holiday_work').text();
           let others = parent.find('.others_leave').text();
           let absent = Number(sick_leave) + Number(annual_leave) + Number(public_holidays) + Number(others);
           let total = Number(regular) + Number(weekend) + Number(public_holiday_work) +Number(overtime/24) - absent;
           parent.find('.total').text(total.toFixed(2));
           setleavewarning(title[1],parent,newValue);
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
      weekend: parent.find('.weekend').text(),
      public_holiday_work: parent.find('.public_holiday_work').text(),
      sick_leave:parent.find('.sick_leave').text(),
      annual_leave: parent.find('.annual_leave').text(),
      public_holidays: parent.find('.public_holidays').text(),
      other_leave: parent.find('.others_leave').text(),
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