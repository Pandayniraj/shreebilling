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

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0"> 
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Attendance Report</strong>
                         &nbsp;
                       <!--  <a class="btn btn-primary btn-sm" href="{!! route('admin.importexport.attendance_report') !!}" title="Import/Export Attendence">
                            <i class="fa fa-download">&nbsp;</i> Import/Export Attendence
                        </a> -->

                </div>

            </div>  
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/all_attendance_report" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                   
                    <div class="form-group">
                        <label for="date_in" class="col-sm-3 control-label">Month<span class="required"> *</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input required="" type="text" class="form-control date_in" value="{{ isset($date_in) ? $date_in : '' }}" name="date_in" id="date_in">
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
                        <strong>Attendance List of {{ date('F-Y', strtotime($date_in)) }} </strong>
                        <div class="pull-right hidden-print">
                            <a href="/admin/all/attendance_report/generatePDF/{{ $date_in }}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF" target="_blank"><span><i class="fa fa-file-pdf"></i></span></a>
                            <a href="/admin/all/attendance_report/print/{{ $date_in }}" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print"><span><i class="fa fa-print"></i></span></a>  
                        </div>
                    </h3>
                </div>
                <table id="" class="table table-bordered std_table">
                    <thead>
                    <tr>
                        <th style="width: 100%" class="col-sm-3">Name</th>
                        @for($i=1; $i<32; $i++)
                        <th class="std_p">{{ $i }}</th>
                        @endfor
                    </tr>
                    </thead>
                        <tbody>
                            <?php $flag = 0; ?>
                            @foreach($attendance as $ak => $av)
                            <tr>
                                <?php $userAtt = \TaskHelper::getUserAttendanceHistroy($av->user_id, $date_in); ?>
                            
                                <td style="width: 100%" class="col-sm-3">{{ $av->user_name }}</td>
                                @for($i=1; $i<32; $i++)
                                    <?php $data = '<td></td>'; ?>
                                    @if(date('l', strtotime($date_in.'-'.$i)) == 'Saturday')
                                        <?php $data = '<td data-toggle="tooltip" data-placement="top" title="Saturday"><span style="padding:2px; 4px" class="label label-info std_p">H</span></td>'; ?>
                                    @else
                                        <?php
                                            $holidayFlag = 0;
                                        ?>
                                        @foreach($holidays as $hk => $hv)
                                            @if(strtotime($date_in.'-'.$i) >= strtotime($hv->start_date) && strtotime($date_in.'-'.$i) <= strtotime($hv->end_date))
                                                <?php
                                                    $data = '<td data-toggle="tooltip" data-placement="top" title="'.$hv->event_name.'"><span style="padding:2px; 4px" class="label label-info std_p">H</span></td>';
                                                    $holidayFlag++;
                                                    break;
                                                ?>
                                            @endif
                                        @endforeach
                                        @if(!$holidayFlag)
                                            @foreach($userAtt as $uk => $uv)
                                                @if(strtotime($uv->date_in) == strtotime($date_in.'-'.$i))
                                                    <?php $data = '<td><span style="padding:2px; 4px" class="label label-success std_p">P</span></td>'; ?>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                    <?php echo $data; ?>
                                @endfor
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@endsection  


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>


<!-- SELECT2-->
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>

<script type="text/javascript">
    $(function() {
        $('#date_in').datetimepicker({
            format: 'YYYY-MM',
            sideBySide: true
        });

        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
