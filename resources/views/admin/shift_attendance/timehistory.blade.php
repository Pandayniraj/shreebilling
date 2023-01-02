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
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
               {{ $page_title ?? "Page Title"}}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <br/>


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
<div class="row"> 
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Time History Report</strong>
                </div>
            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/timeHistory" method="POST" 
                class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="user_id" class="col-sm-3 control-label">Employee<span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select required name="user_id" id="user_id" class="form-control select_box">
                                <option value="">Select Employee</option>
                                @foreach($users as $uv)
                                <option value="{{ $uv->id }}" @if($user_id == $uv->id) selected="selected" @endif>{{ $uv->first_name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="date_in" class="col-sm-3 control-label">Date<span class="required">*</span></label>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input required="" type="text" class="form-control date_in date-toggle" value="{{ isset($start_date) ? $start_date : '' }}" name="start_date" placeholder="Start Date...">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                         <div class="col-sm-3">
                            <div class="input-group">
                                <input required="" type="text" class="form-control date_in date-toggle" value="{{ isset($end_date) ? $end_date : '' }}" name="end_date" 
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
                            <button type="submit" id="sbtn" class="btn btn-primary">Search</button>
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
                       
                    </h3>
                </div>
               
                <div class="table-responsive">
                @foreach($allReport as $key => $shifts )

                <?php 

                        $thisshiftDates = $shifts['data_by_date'];

                        $thisshiftbreak = $shifts['breakduration']['breakInfo'];

                    ?>
                    <table class="table">
                        <thead>
                             <tr class="bg-primary" >
                               <th >Shift Name: {{$shifts['shift_name']}} </th>
                               <th style="text-align: right;">Start: 
                                {{ date('h:i A',strtotime($shifts['shift']->shift_time)) }}</th>
                               <th style="text-align: right;">End: 
                                {{ date('h:i A',strtotime($shifts['shift']->end_time)) }}</th>
                           </tr>
                        </thead>
                    </table>
                    
               
                <table class="table table-bordered std_table">
                   <thead>
                        
                       <tr>
                            
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Late By </th>
                            <th>Early By </th>
                           @foreach($thisshiftbreak as $tb=>$brks)

                               @for($i= 0; $i<2;$i++)
                                    <th class="bg-default">
                                    {{ $brks['name'] }} {{ $i ==0 ? 'In':'Out' }} <i class="{{ $brks['icon'] }}"></i><br>
                                    {{ date('h:i A',strtotime($brks[ $i ==0 ? 'start':'end'])) }}
                                    </th>
                                @endfor   
                            @endforeach
                            
                            <th>Clock Out </th>
                            <th>Break Taken </th>
                            <th>Working Hours </th>
                            <th title="Overtime Hours">OT Hours</th>
                            <th>Action</th>

                       </tr>
                   </thead>
                    <tbody>

                    @foreach($thisshiftDates as $ts => $records)
                     <?php 
                           $value = $records['data'];
                           $thisdate = $records['date'];
                    ?>
                       <tr  @if(!$value['clockin'])class="bg-danger" 
                            @elseif($value['summary']['message']) class="bg-maroon" @endif>
                           <td>{{$thisdate}}</td>
                          
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
                 </table>

                 @endforeach

            </div>
        </div>
    </div>
</div>
</div>









@endif

@endsection
@section('body_bottom')
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>
<link rel="stylesheet" type="text/css" href="/x-editable/bootstrap-editable.css">
<script  src="/x-editable/bootstrap-editable.min.js"></script>
@include('partials._date-toggle')
<script type="text/javascript">
    
       $(function() {

        $('.date_in').datetimepicker({
            format: 'YYYY-MM-DD',
            sideBySide: true
        });
        
        $('.date-toggle').nepalidatetoggle()
        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();
    });





</script>

@include('admin.shift_attendance.fixattendance')
@endsection