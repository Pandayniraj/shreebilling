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
                    <strong>Timesheet Attendance Report</strong>
                         &nbsp;
                </div>

            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="{{route('admin.timesheet.attendancereport')}}" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Project<span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                             <select required name="project_id" id="department" class="form-control select_box">
                                <option value="">Select Projects</option>
                                @foreach($projects as $dk => $dv)
                                <option value="{{ $dv->id }}" @if($project_id == $dv->id) selected="selected" @endif>{{ $dv->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
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

@if(count($record) > 0)
<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <strong>Timesheet Attendance List of {{ date('F-Y', strtotime($date_in)) }} </strong>
                      <!--   <div class="pull-right hidden-print">
                            <a href="/admin/attendance_report/generatePDF/{{ $department }}/{{ $date_in }}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF" target="_blank"><span><i class="fa fa-file-pdf"></i></span></a>
                            <a href="/admin/attendance_report/print/{{ $department }}/{{ $date_in }}" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print"><span><i class="fa fa-print"></i></span></a>
                        </div> -->
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
                        @foreach($record as $emp=>$rec)

                            <tr>
                                <td>{{$emp}}</td>
                                @for($i=1;$i<32;$i++)
                                <?php $flag = 0; ?>
                                    @foreach($rec as $r)
                                    @if(strtotime($r->date) == strtotime($date_in.'-'.$i))
                                    <?php  $flag++ ;?>
                                    <td data-toggle="tooltip" data-placement="top" title="{{$r->time_from}}-{{$r->time_to}}"><span style="padding:2px 4px" class="label label-success std_p">P</span></td>
                                    <?php break; ?>
                                    @endif
                                    @endforeach
                                    @if($flag == 0)
                                    <td></td>
                                    @endif
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
