@extends('layouts.master')
@section('content')

<style>
    .required { color: red; }
    .panel-custom .panel-heading {
        border-bottom: 2px solid #1797be;
    }
    .panel-custom .panel-heading {
        margin-bottom: 10px;
    }
    .std_table th{
        white-space: nowrap;
    }
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Remote Shift Attendance Report
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {{--  <span> Overall web report is <a target="_blank" href="/admin/all_attendance_report">here</a></span><br/> --}}

           {{ TaskHelper::topSubMenu('topsubmenu.hr')}}

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Attendance Report</strong>
                         &nbsp;
                      

                </div>

            </div>
            <div class="panel-body">
                <form id="filter_form" role="form" enctype="multipart/form-data" action="#" method="post" class="form-horizontal form-groups-bordered" >
                    {{ csrf_field() }}

                    <div class="form-group">
                      <label class="control-label col-sm-3" >Shifts:</label>
                      <div class="col-md-5">          
                        {!! Form::select('shift_id',$shifts,$selectedShift,['placeholder'=>'All 
                        Shift','class'=>'form-control searchable','id'=>'shifts']) !!}
                      </div>
                    </div>
                     
                     <div class="form-group">
                        <label for="date_in" class="col-sm-3 control-label">Date<span class="required">*</span></label>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input required="" type="text" class="form-control date_in date-toggle" value="{{ isset($start_date) ? $start_date : date('Y-m-d') }}" name="start_date" placeholder="Start Date...">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>

                         <div class="col-sm-3">
                            <div class="input-group">
                                <input required="" type="text" class="form-control date_in date-toggle" value="{{ isset($end_date) ? $end_date : date('Y-m-d') }}" name="end_date" 
                                placeholder="End Date...">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"></label>
                        <div class="col-sm-5 ">
                            <button type="submit"  class="btn btn-primary"
                           id='filter_submit'>Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($allReport)


<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <strong>Attendance List of {{ date('d F-Y', strtotime($start_date)) }} to 
                        {{ date('d F-Y', strtotime($end_date)) }}  </strong>
                        <div class="pull-right hidden-print">
                            <a href="javascript::void()" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="excel"  id='DownloadExcel'><span><i class="fa fa-file-pdf-o"></i></span></a>

                            <a href="javascript::void()" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="pdf"  id='DownloadPdf'><span><i class="fa fa-file-pdf-o"></i></span></a>
                  
                        </div>
                    </h3>
                </div>
               
                <div class="table-responsive">
                @foreach($allReport as $key => $shifts )

                <?php 

                        $thisshiftDates = $shifts['data_by_date'];

                        $thisshiftbreak = $shifts['shift_break']['breakInfo'];

                    ?>
                    <table class="table">
                        <thead>
                             <tr class="bg-primary" >
                               <th >Shift Name: {{$shifts['shift_name']}} </th>
                               <th style="text-align: left;">Start: 
                                {{ date('h:i A',strtotime($shifts['shift_info']->shift_time)) }}</th>
                               <th style="text-align: left;">End: 
                                {{ date('h:i A',strtotime($shifts['shift_info']->end_time)) }}</th>
                           </tr>
                        </thead>
                    </table>
                    
               
                <table class="table table-bordered std_table">
                   <thead>
                        
                       <tr class="bg-yellow">
                            <th>S.N</th>
                            <th>Username</th>
                            <th>Degination</th>
                            <th>Clock In</th>
                            <th>Late By </th>
                            <th>Early By </th>
                            <th colspan="{{count( $thisshiftbreak) *2}}" 
                            class="text-center">Break time</th>
                            
                            <th>Clock Out </th>
                            <th>Break Taken </th>
                            <th>Working Hours </th>
                            <th title="Overtime Hours">OT Hours</th>
                            <th>Action</th>
                       </tr>
                   </thead>
                    <tbody>

                    @foreach($thisshiftDates as $ts => $shiftsreportsBydate)

                        <tr>
                            
                            <th></th>
                        </tr>
                        <tr class="bg-olive">
                            <?php $thisdate = $shiftsreportsBydate['date']; ?>

                           <th colspan='6' style="vertical-align: middle;">Date: {{ $thisdate }} ({{$shifts['shift_name']}}) </th>

                           @foreach($thisshiftbreak as $tb=>$brks)

                               @for($i= 0; $i<2;$i++)
                                    <th class="bg-default">
                                    {{ $brks['name'] }} {{ $i ==0 ? 'In':'Out' }} <i class="{{ $brks['icon'] }}"></i><br>
                                    {{ date('h:i A',strtotime($brks[ $i ==0 ? 'start':'end'])) }}
                                    </th>
                                @endfor   
                            @endforeach

                            <th class="bg-olive" colspan="5"></th>

                       </tr>
                        <?php 
                        $attendancereport = $shiftsreportsBydate['data'];

                        ?>
                        @foreach($attendancereport as $ar=>$value)

                           
                        <tr 
                        
                        @if($value['summary']['message']) class="bg-maroon" title="No clock out"
                        @elseif(AttendanceHelper::checkUserLeave($value['user']->id,$thisdate))
                            style="background: #F7EE79; color: black;" 
                           title='On leave'
                        @elseif(!$value['clockin'])class="bg-danger" title="Absent" 
                        @endif 
                        >
                            <td>{{++$ar}}</td>
                            <td>{{$value['user']->first_name}} {{$value['user']->last_name}}
                            </td>
                            <td>{{$value['user']->designation->designations}}</td>
                            <td style="white-space: nowrap;">{{ $value['clockin'] ? date('H:i A',strtotime( $value['clockin'])):'-'}}</td>
                            <td style="white-space: nowrap;">
                                
                                {{ $value['lateby'] ?  TaskHelper::minutesToHours($value['lateby']).' Hrs':'-'  }}

                            </td>
                            <td style="white-space: nowrap;">
                                {{ $value['earlyby'] ?  TaskHelper::minutesToHours($value['earlyby']).' Hrs':'-'  }}
                            </td>
                            <?php   
                             $timeDifference = $value['summary']['timeDifference'];
                             $breakTaken = [];
                             foreach ($timeDifference as $_td => $_timediff) {
                                    if($_td % 2 != 0){
                                        $breakTaken [] = $_timediff;
                                    }
                                }
                            ?>
                               

                        @if(count($thisshiftbreak) > 0 )
                            @foreach($thisshiftbreak as $tb=>$brks)
                               
                                <td> 
                        {{ $breakTaken[$tb]['start'] ? date('h:i A',strtotime($breakTaken[$tb]['start'])):'-' }}
                                </td>
                                <td> 
                        {{ $breakTaken[$tb]['end'] ?  date('h:i A',strtotime($breakTaken[$tb]['end'])): '-' }}
                                </td>
                                   
                            @endforeach
                        @else

                            <td>-</td>
                        @endif
                            



                           {{--  <td>{{$value['breakduration']['formatted']}}</td> --}}
                            
                            <td style="white-space: nowrap;">{{ $value['clockout'] ?date('H:i A',strtotime( $value['clockout'])) : '-'}}</td>
                            <td style="white-space: nowrap;"> {{$value['summary'] ? TaskHelper::minutesToHours($value['summary']['breakTime']).' Hrs' : '-' }} </td>
                            
                            <td title="{{$value['summary']['message']}}" style="white-space: nowrap;"> {{$value['summary'] ? TaskHelper::minutesToHours($value['summary']['workTime']) .' Hrs': '-' }}  </td>

                            <td style="white-space: nowrap;">
                                {{ $value['overTime'] ?  TaskHelper::minutesToHours($value['overTime']).' Hrs':'-'  }}
                            </td>
                            <td>
                                
                <a href="/admin/shiftAttendance/{{$value['user']->id}}/{{$value['shift']->id}}/{{$thisdate}}" data-toggle="modal" data-target="#modal_dialog" ><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                    @endforeach
                 </table>
                 <br> <br>

                 @endforeach

            </div>
        </div>
    </div>
</div>
</div>

<div class="row">
    <div class="col-md-4">
        <table class="table table-striped">
            <tr style="background-color: #F2DEDE">
                <th>Symbol</th>
                <th>Meaning</th>
            </tr>
          
            <tr>
                <td><button class="btn btn-default btn-block  btn-xs">H</button></td>
                <th>Present</th>
            </tr>

            <tr>
                <td><button class="btn btn-block btn-maroon  btn-xs" style="background: #D81B60;color: white;">P</button></td>
                <th>No clockout</th>
            </tr>
            
              <tr>
                <td><button class="btn  btn-block  btn-xs" style="background: #F7EE79;color: black">L</button></td>
                <th>On Leave</th>
            </tr>
        </table>
    </div>
</div>



@endif
    



@endsection
@section('body_bottom')
@include('partials._date-toggle')
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>
<link rel="stylesheet" type="text/css" href="/x-editable/bootstrap-editable.css">
<script  src="/x-editable/bootstrap-editable.min.js"></script>
<script type="text/javascript">
      $('.date-toggle').nepalidatetoggle();

  $(function() {
        $('.date_in').datetimepicker({
            format: 'YYYY-MM-DD',
            sideBySide: true
        });

        $('.searchable').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();
        
$(document).on('hidden.bs.modal', '#modal_dialog' , function(e){
        $('#modal_dialog .modal-content').html('');    
   });


});



  $('#DownloadExcel').click(function(){

      let data = $('#filter_form').serializeArray().reduce(function(obj, item) {
        obj[item.name] = item.value;
        return obj;
    }, {});
    let action = `/admin/shiftAttendance/csv/download?${$.param(data)}`;
    location.href = action;
  });


  $('#DownloadPdf').click(function(){

      let data = $('#filter_form').serializeArray().reduce(function(obj, item) {
        obj[item.name] = item.value;
        return obj;
    }, {});
    let action = `/admin/shiftAttendance/pdf/download?${$.param(data)}`;
    location.href = action;
  });

</script>

@include('admin.shift_attendance.fixattendance')



@endsection