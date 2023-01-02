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
                    <strong>All Time Change Request</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>EMP ID</th>
                            <th>Name</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Status</th>
                            <th class="hidden-print">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timechange as $hk => $hv)
                        <?php $user = TaskHelper::getUser($hv->user_id); ?>
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->first_name." ".$user->last_name }}</td>
                            <td>{{ date('h:i a', strtotime($hv->clockin_edit)) }}</td>
                            <td>{{ date('h:i a', strtotime($hv->clockout_edit)) }}</td>
                            <td>
                                @if($hv->status == 1)
                                <span class="label label-warning">Pending</span>
                                @elseif($hv->status == 2)
                                <span class="label label-success">Accepted</span>
                                @else
                                <span class="label label-danger">Rejected</span>
                                @endif
                            </td>
                            <td class="hidden-print"><a href="/admin/timechange_request_modal/{{ $hv->clock_history_id }}" class="btn btn-primary btn-xs" title="Edit" data-toggle="modal" data-placement="top" data-target="#myModal"><span class="fa fa-edit"></span></a></td>
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
                    setTimeout(function() {$('#myModal').modal('hide');}, 2000);
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
