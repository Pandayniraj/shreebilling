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
                Web Attendance Report
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
             <span> Overall web report is <a target="_blank" href="/admin/all_attendance_report">here</a></span><br/>

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
                        <a class="btn btn-primary btn-sm" href="{!! route('admin.importexport.attendance_report') !!}" title="Import/Export Attendence">
                            <i class="fa fa-download">&nbsp;</i> Import/Export Attendence
                        </a>

                </div>

            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/attendance_report" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Department<span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select required name="department_id" id="department" class="form-control select_box">
                                <option value="">Select Department</option>
                                @foreach($departments as $dk => $dv)
                                <option value="{{ $dv->departments_id }}" @if($department == $dv->departments_id) selected="selected" @endif>{{ $dv->deptname }}</option>
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

@if($attendance)
<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <strong>Attendance List of {{ date('d F-Y', strtotime($start_date)) }} to 
                        {{ date('d F-Y', strtotime($end_date)) }}  </strong>
                        <div class="pull-right hidden-print">
                        {{--     <a href="/admin/attendance_report/generatePDF/{{ $department }}/{{ $date_in }}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF" target="_blank"><span><i class="fa fa-file-pdf-o"></i></span></a> --}}

                            <div class="btn-group">
                              <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Print Report"><i class="fa fa-file-pdf-o"></i></button>
                              <ul class="dropdown-menu" style="margin-left: -50px">
                               
                                <li> 
                                <a href="/admin/attendance_report/generatePDF/{{ $department }}/{{ $date_in }}?type=nepali">In Nepali</a>
                                </li> 
                              <li> 
                                <a href="/admin/attendance_report/generatePDF/{{ $department }}/{{ $date_in }}?type=english">In English</a>
                                </li> 
                              </ul>
                            </div>

                            <div class="btn-group">
                              <button type="button" class="btn btn-danger btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Print Report"><i class="fa fa-print"></i></button>
                              <ul class="dropdown-menu" style="margin-left: -50px">
                               
                                <li> 
                                <a href="/admin/attendance_report/print/{{ $department }}/{{ $date_in }}?type=nepali">In Nepali</a>
                                </li> 
                              <li> 
                                <a href="/admin/attendance_report/print/{{ $department }}/{{ $date_in }}?type=english">In English</a>
                                </li> 
                              </ul>
                            </div>
                          {{--   <a href="/admin/attendance_report/print/{{ $department }}/{{ $date_in }}" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print"><span><i class="fa fa-print"></i></span></a>
 --}}                            
                        </div>
                    </h3>
                </div>
                <?php
                    $begin = new DateTime($start_date);
                    $end = new DateTime($end_date);
                    $end->add(new \DateInterval('P1D'));
                    $interval = DateInterval::createFromDateString('1 day');
                    $period = new DatePeriod($begin, $interval, $end);
                    $cal = new \App\Helpers\NepaliCalendar();
                ?>
                <div class="table-responsive">
                <table id="" class="table table-bordered std_table">
                    <thead>
                    <tr class="bg-danger">
                        <th >Name</th>
                        @foreach ($period as $dt) 
                        <?php
                            $engdate = $dt->format("Y-m-d");
                            $nepdate = $cal->formated_nepali_from_eng_date($engdate);
                        ?>
                        <td class="std_p">{{$dt->format("M-d") }}/{{ $nepdate }}</td>
                        @endforeach
                    </tr>
                    </thead>
                        <tbody>
                            <?php $flag = 0; ?>
                            @foreach($attendance as $ak => $av)
                            <tr>
                                <?php 
                                 
                                    $userAtt = \TaskHelper::getUserAttendanceHistroy($av->user_id, $date_in);
                                 ?>
                            
                                <td >{{ $av->user_name }}</td>
                               @foreach ($period as $dt) 
                                    <?php $data = '<td></td>'; ?>
                                    @if(date('l', strtotime($dt->format("Y-m-d"))) == 'Saturday')
                                        <?php $data = '<td data-toggle="tooltip" data-placement="top" title="Saturday"><span style="padding:2px; 4px" class="label label-info std_p">H</span></td>'; ?>
                                    @else
                                        <?php
                                            $holidayFlag = 0;
                                        ?>
                                        @foreach($holidays as $hk => $hv)
                                            @if(strtotime($date_in.'-'.$i) >= strtotime($hv->start_date) && strtotime($dt->format("Y-m-d")) <= strtotime($hv->end_date))
                                                <?php
                                                    $data = '<td data-toggle="tooltip" data-placement="top" title="'.$hv->event_name.'"><span style="padding:2px; 4px" class="label label-info std_p">H</span></td>';
                                                    $holidayFlag++;
                                                    break;
                                                ?>
                                            @endif
                                        @endforeach
                                        @if(!$holidayFlag)
                                            @foreach($userAtt as $uk => $uv)
                                                @if(strtotime($uv->date_in) == strtotime( $dt->format("Y-m-d")))
                                                    <?php 
                                                    if($uv->date_out)
                                                    $data = '<td><span style="padding:2px; 4px" class="label label-success std_p">P</span></td>'; 
                                                    else
                                                        $data = '<td><span style="padding:2px; 4px" class="label label-warning std_p">P</span></td>'; 

                                                    ?>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                    <?php echo $data; ?>
                                @endforeach
                            </tr>
                            @endforeach
                    </tbody>
                </table>
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
                <td><button class="btn btn-info btn-block  btn-xs">H</button></td>
                <th>Holiday</th>
            </tr>
            <tr>
                <td><button class="btn btn-block btn-warning  btn-xs">P</button></td>
                <th>Present without clockout</th>
            </tr>
            <tr>
                <td><button class="btn btn-success btn-block  btn-xs">P</button></td>
                <th>Present with clockin</th>
            </tr>
        </table>
    </div>
</div>
@endif

@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
@include('partials._date-toggle')
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>


<!-- SELECT2-->
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>

<script type="text/javascript">
      $('.date-toggle').nepalidatetoggle()
    $(function() {
        $('.date_in').datetimepicker({
            format: 'YYYY-MM-DD',
            sideBySide: true
        });

        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
