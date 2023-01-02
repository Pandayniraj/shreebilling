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
                    <strong>My Time History Report</strong>
                </div>
            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/mytimehistory" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="user_id" class="col-sm-3 control-label">Employee</label>
                        <div class="col-sm-5">
                         <strong>{{ucfirst(\Auth::user()->username)}}</strong>
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

@if($history)

<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <strong>{{ TaskHelper::getUserName($user_id) }} Time Logs :: {{ date('F-Y', strtotime($date_in)) }} </strong>
                        <div class="pull-right hidden-print">
                            <a href="/admin/time_history/generatePDF/{{ $user_id }}/{{ $date_in }}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF" target="_blank"><span><i class="fa fa-download"></i></span></a>
                            <a href="/admin/time_history/print/{{ $user_id }}/{{ $date_in }}" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print"><span><i class="fa fa-print"></i></span></a>
                        </div>
                    </h3>
                </div>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Clock In Time</th>
                            <th>Clock Out Time</th>
                            <th>IP address</th>
                            <th>Hours</th>
                            <th class="hidden-print">Action</th> 
                        </tr>
                    </thead>
                    <tbody> 
                        @foreach($history as $hk => $hv)
                        <tr>
                            <td colspan="5" style="background: rgba(233, 237, 228, 0.73);font-weight: bold">Date In  : {{ $hv->date_in }}, Date Out : {{ $hv->date_out }}</td>
                        </tr>
                        <tr>
                            <td>{{ date('h:i a', strtotime($hv->clockin_time)) }}</td>
                            <td>{{ date('h:i a', strtotime($hv->clockout_time)) }}</td>
                            <td>{{ $hv->ip_address }}</td>
                            <?php
                                $startTime = \Carbon\Carbon::parse(date('Y-m-d H:i:s', strtotime($hv->date_in.' '.$hv->clockin_time)));
                                $finishTime = \Carbon\Carbon::parse(date('Y-m-d H:i:s', strtotime($hv->date_out.' '.$hv->clockout_time)));

                                $totalDuration = $finishTime->diffInSeconds($startTime);
                                if(gmdate('d', $totalDuration) != '01')
                                    $hour = ((gmdate('d', $totalDuration) - 1) * 24) + gmdate('H', $totalDuration);
                                else
                                    $hour = gmdate('H', $totalDuration);

                                $minute = gmdate('i', $totalDuration);

                            ?>
                            <td>{{ $hour.':'.$minute.' m' }}</td>
                            <td class="hidden-print"><a href="/admin/time_history/edit_time/{{ $hv->clock_id }}" class="btn btn-primary btn-xs" title="Edit" ><span class="fa fa-edit"></span></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .datepicker {
        z-index: 1050 !important;
    }

    .bootstrap-timepicker-widget.dropdown-menu.open {
        display: inline-block;
        z-index: 99999 !important;
    }
</style>
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

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

<!-- Timepicker -->
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/timepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/timepicker.js") }}" type="text/javascript"></script>

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

    $(document).on('focusin', '#clockin_edit', function(){
      $(this).timepicker();
    });
    $(document).on('focusin', '#clockout_edit', function(){
        $(this).timepicker({
            // minuteStep: 1,
            // showSeconds: false,
            // showMeridian: false,
            // defaultTime: false
        });
    });


    $(document).on('click', '#request_update', function () {
        $('.reason_error').hide();
        if($('#reason').val() == '')
        {
            $("#reason").attr('placeholder', 'Please mention your reason.');
            $("#reason").after("<span style='color:red;' class='reason_error'>Please mention your reason.</span>");
            return false;
        }

        var formData = new FormData();
        formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
        formData.append("clockin_edit", $('#clockin_edit').val());
        formData.append("clockout_edit", $('#clockout_edit').val());
        formData.append("clockin_old", $('#clockin_old').val());
        formData.append("clockout_old", $('#clockout_old').val());
        formData.append("reason", $('#reason').val());

        $.ajax({
            type: 'POST',
            url: $('#time_update_request').attr('action'),
            data:formData,
            contentType: false,
            processData: false, 
            success: function(response)
            {
                if(response.success == '1')
                {
                    $("#reason").after("<span style='color:green;' class='reason_error'>Time udpate request has been sent.</span>");
                    setTimeout(function() {$('#myModal').modal('hide');}, 1000);
                }
                else
                    $("#reason").after("<span style='color:red;' class='reason_error'>Problem in time update. Please try again.</span>");
            },
            fail: function(xhr, textStatus, errorThrown){
                $("#reason").after("<span style='color:red;' class='reason_error'>Request failed.</span>");
            }
        })


        alert(JSON.stringify(data));
    });
</script>
@endsection
