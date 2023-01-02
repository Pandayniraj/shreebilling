@extends('layouts.master')
@section('content')

<style>

    .panel-custom .panel-heading {
        margin-bottom: 10px;
    }
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Late Arrival Report
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
             <span> Overall web report is <a target="_blank" href="/admin/all_attendance_report">here</a></span><br/>


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
           
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data"
                action="{{route('admin.late_arrival.report')}}" method="post" class="form-horizontal form-groups-bordered" name='LateArrival'>
                    {{ csrf_field() }}

                    @if(count($departments) != 0)
                    <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Department<span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select  name="department_id" id="department" class="form-control select_box">
                                @if(count($departments) != 1)
                                <option value="">Select Department</option>
                                @endif
                                @foreach($departments as $dk => $dv)
                                <option value="{{ $dv->departments_id }}" @if($department == $dv->departments_id) selected="selected" @endif>{{ $dv->deptname }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    @endif

                     <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Shift<span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select  name="shift_id" id="department" class="form-control select_box">
                                <option value="">Select Shift</option>
                                @foreach($shifts as $dk => $dv)
                                <option value="{{ $dv->id }}" @if($shift == $dv->id) selected="selected" @endif>{{ $dv->shift_name }}</option>
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
                             <a href="/admin/attendanceReports" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@if($isFiltered)
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Late Arrival Report</h3>
        <span class="pull-right">
            <button class="btn btn-primary btn-xs" name="type" type="submit" form='attendance-form' value="excel" title="download Excel"> <i class="fa fa-file-excel-o"></i>  </button>
        </span>

    </div>
    <div class="box-body">
        <table class="table table-hover table-striped">
            <thead>
                <tr class="bg-yellow">
                    <th>Emp. Name</th>
                    <th>Shift</th>
                    <th>Clockin</th>
                    <th>Late By</th>

                    <th>Clockout</th>
                    <th>OverTime</th>
                    <th>Adjusted</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lateReport as $key=>$value)
                <tr>
                    <td>{{$value['user']  }}</td>
                    <td>{{$value['shift']}} ({{$value['shift_start']}}-{{$value['shift_end']}})</td>
                     <td>{{$value['clockin']  }}</td>
                    <td><mark>{{$value['lateby']  }}</mark></td>

                    <td>{{$value['clockout']  }}</td>
                    <td>{{$value['overTime']  }}</td>
                     <td>{{$value['isAdjusted'] ? 'Yes' :'No' }}</td>
                    <td>{{$value['remarks']  }}</td>


                </tr>


                @empty
                    <tr>
                        <td> <h3>No any record !!!</h3> </td>
                    </tr>


                @endforelse

            </tbody>
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



    $('.downloadReport').click(function(){

        let lang = $(this).attr('data-lang');
        let type = $(this).attr('data-type');
        let data = $('#attendance-form').serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});
        let action = `/admin/attendanceReports/${type}/download?lang=${lang}&${$.param(data)}`;
        location.href = action;
    });
</script>
@endsection
