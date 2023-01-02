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
              <input type="hidden" name="frequency_id" value="{{$payfrequency->id}}">
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover" id='filter-table'>
                <thead>
                  <tr>
                    <th colspan="5" style="text-align: center;background-color:#8FBC8F;">Employee Info</th>
                    <th colspan="2" style="text-align: center">Working Hours</th>
                    <th colspan="4" style="text-align: center;background-color:#8FBC8F;">Leave</th>

                    <th style="text-align: center;">Total</th>
                    <th style="text-align: center;background-color:#8FBC8F;">Remarks</th>
                  </tr>
                   <tr>
                  <th>Emp ID</th>
                  <th>User</th>
                  <th>Designation</th>
                  <th>Department</th>
                  <th>Time Entry</th>
                  <th>Regular</th>
                  <th>Overtime / Hrs <a href="/admin/payroll/over_time/" target="_blank"><i class="fa fa-plus"></i></a></th>
                  <th>Sick</th>
                  <th>Anual</th>
                  <th>Public</th>
                  <th>Other</th>
                     <th>Total</th>
                  <th>Remarks</th>
                </tr>
                </thead>
               
                <tbody  id="searchabletable">

              @if(isset($timecard) && count($timecard) > 0)
              @foreach($timecard as $tc)
              <tr class="payrolldetails">
                <input type="hidden" name="timecard_id" value="{{$tc->id}}">
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
                  <td>
                    <span class='editable sick_leave'>{{$tc->sick_leave}}</span>
                  </td>
                   <td >
                   <span class='editable annual_leave'>{{$tc->annual_leave}}</span>
                  </td>
                   <td >
                  <span class='editable public_holidays'>{{$tc->public_holidays}}</span>
                  </td>
                  <td>
                   <span class='editable others_leave'>{{$tc->other_leave}}</span>
                  </td>
                 
                    <td >
                      <span class="total">{{$tc->regular_days + ($tc->ot_hour / 24) - ($tc->sick_leave + $tc->annual_leave + $tc->public_holidays + $tc->other_leave)}}
                      </span>
                      </td>
                  <td title="{{$tc->remarks}}">
                    <span class="payrollremarks" style="max-width: 60px; max-height: 20px; position: absolute; text-overflow: ellipsis; overflow: hidden">
                      {{$tc->remarks}}
                    </span>
                  </td>
              </tr>
              @endforeach
            @endif
              </tbody></table><br>
                </a><a href="/admin/payroll/payfrequency/" type="button" class="btn btn-default btn-lg">
                <i class="fa fa-close"></i>&nbsp; Close
              </a>
            <!-- /.box-body -->
          </div>
                     

          <!-- /.box -->
        </div>
</div>
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
 <link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
    <script src="/bower_components/admin-lte/plugins/datatables/extra/export.js"></script>

<script type="text/javascript">
$(document).ready(function(){
  $("#searchbar").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#searchabletable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });


   var table = $('#filter-table').DataTable({ 
  
  dom: 'Bfrtip',
   
   buttons: [
            {
                extend: 'copyHtml5',
                title: 'Data export',

            },
            {
                extend: 'excelHtml5',
                title: 'Data export'
            },
            {
                extend: 'csvHtml5',
                title: 'Data export'
            },
            {
                extend: 'pdfHtml5',
                footer: true,
                orientation: 'landscape',
              customize: function(doc) {
                doc.content.splice(0, 1, {
                  text: [{
                    text: 'Company: {{env('APP_COMPANY')}} \n PAN: {{\Auth::user()->organization->tpid}}\nAddress:{{\Auth::user()->organization->address}}',
                    bold: true,
                    fontSize: 12,
                    alignment: 'left'
                  }, {
                    text: '\nPayroll Timecard \n {{date('dS M Y',strtotime($payfrequency->period_start_date) )}} - {{date('dS M Y',strtotime($payfrequency->period_end_date)) }} \n Check Date - {{date('dS M Y',strtotime($payfrequency->check_date))}}\n\n',
                    bold: false,
                    fontSize: 12,
                    alignment:'center'
                  }
                  ]
               
                });
              }
            }
        ],
  });

});
$.fn.editable.defaults.ajaxOptions = {type: "GET"};
$('.payrollremarks').each(function(){
  var parent = $(this).parent().parent();
  $(this).editable({
    type:'textarea',
    pk:1,
    url:'/admin/payroll/payfrequency/view_timecard/{{$frequency_id}}?id='+parent.find('input[name=timecard_id]').val(),
    title: `Timcard Remarks`,
  });
});



</script>

@endsection