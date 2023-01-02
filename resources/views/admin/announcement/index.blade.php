@extends('layouts.master')
@section('content')

<style>
    .required {
        color: red;
    }

    .panel-custom .panel-heading {
        border-bottom: 2px solid #1797be;
    }

    .panel-custom .panel-heading {
        margin-bottom: 10px;
    }

    .select2-container--bootstrap .select2-results__group {
        font-size: 15px !important;
        padding: 6px 3px !important;
    }

    .select2-container--bootstrap .select2-results__option .select2-results__option {
        color: #777 !important;
    }

</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title ?? "Page Title" }}

    </h1>

</section>

<div class="row">
    <div class="col-sm-12" data-offset="0">
        <div class="">
            <!-- Default panel contents -->
            <div class="">
                <div class="">
                    <div class="float-left">

                        <a href="/admin/announcements/generatePdf" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF" target="_blank"><span>PDF <i class="fa fa-file-pdf-o"></i></span></a>
                        <a href="/admin/announcements/print" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print"><span>PRINT <i class="fa fa-print"></i></span></a>
                    </div>

                    <div class="float-right">
                        @if(\Auth::user()->hasRole('admins'))
                        <a href="javascript::void(0)" class="btn btn-primary btn-xs" data-toggle="modal" data-placement="top" data-target="#announcement_modal" id="create_new"><span class="fa fa-plus"></span> New Announcement</a>
                        @endif
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>

            <!-- Table -->
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="col-sm-1">SL</th>
                        <th>Title</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Place</th>
                        <th>Admin</th>


                        <th class="col-sm-1 hidden-print">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($announcements as $ak => $av)
                    <tr>
                        <td>AN{{ $av->announcements_id }}</td>
                        <td>
                            <h4><a href="/admin/announcements/show/{{ $av->announcements_id }}" data-toggle="modal" data-target="#announcement_show" data-placement="top" class="showmodal">{{ $av->title }}</a></h4>
                        </td>
                        <td>{{ date('D, dS M y', strtotime($av->start_date)) }}
                            <?php
                                        $temp_date = explode(" ",$av->start_date );
                                        $temp_date1 = explode("-",$temp_date[0]);
                                        $cal = new \App\Helpers\NepaliCalendar();
                                        //nepali date
                                        $a = $temp_date1[0];
                                        $b = $temp_date1[1];
                                        $c = $temp_date1[2];
                                        $d = $cal->eng_to_nep($a,$b,$c);
                                    
                                         $nepali_date = $d['date'].' '.$d['nmonth'] .', '.$d['year'];
                                        ?>

                            <small>/ {!! $nepali_date !!}</small>

                        </td>

                        <td>{{ date('D, dS M y', strtotime($av->end_date)) }}

                            <?php
                                        $temp_date = explode(" ",$av->end_date );
                                        $temp_date1 = explode("-",$temp_date[0]);
                                        $cal = new \App\Helpers\NepaliCalendar();
                                        //nepali date
                                        $a = $temp_date1[0];
                                        $b = $temp_date1[1];
                                        $c = $temp_date1[2];
                                        $d = $cal->eng_to_nep($a,$b,$c);
                                    
                                         $nepali_date = $d['date'].' '.$d['nmonth'] .', '.$d['year'];
                                        ?>

                            <small>/ {!! $nepali_date !!}</small>
                        </td>
                        <td>{{ $av->placement }}</td>
                        <td>{{ $av->user->first_name }}</td>


                        <td class="hidden-print">
                            <a href="/admin/announcements/edit/{{ $av->announcements_id }}" class="btn btn-primary btn-xs editmodal" @if(!$av->isEditable()) disabled @endif><span class="fa fa-edit"></span></a>
                            <a href="/admin/announcements/delete/{{ $av->announcements_id }}" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" @if(!$av->isDeletable()) disabled @endif><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<div class="modal fade" id="announcement_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @include('admin.announcement.modal')
        </div>
    </div>
</div>

<div class="modal fade" id="announcement_show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>

@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')

<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD'
            , sideBySide: true
        });

        $('#DataTables').DataTable({
            pageLength: 50
        });

        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#create_new').on('click', function() {
        $('#announcement_modal #myModalLabel').html('New Announcement');
        $('#sbtn').html(' Save ');
    });

    $(document).on('focus', '#start_date, #end_date', function() {
        $(this).datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD'
            , sideBySide: true
        , }, "show");
    });

    $('.editmodal').click(function() {
        $('#modal_dialog .modal-content').html('');
        let url = $(this).attr('href');
        $('#modal_dialog .modal-content').load(url, function(result) {
            $('#modal_dialog').modal({
                show: true
            });
            $(`#modal_dialog  input[name='share_with']`).trigger('change');

        });
        return false;
    });

    $('.showmodal').click(function() {
        $('#modal_dialog .modal-content').html('');
        let url = $(this).attr('href');
        $('#modal_dialog .modal-content').load(url, function(result) {
            $('#modal_dialog').modal({
                show: true
            });

        });
        return false;
    });

    // clear modal box
    $(document).on("hidden.bs.modal", '#announcement_modal', function(event) {
        $(this).find("input,textarea,select").val('').end()
            .find("input[type=checkbox], input[type=radio]").prop("checked", "").end();

    });


    $(document).ready(function() {
        $(document).on('change', `#modal_dialog  input[name='share_with']`, function() {
            var radioValue = $("#modal_dialog input[name='share_with']:checked").val();

            if (radioValue == 'team') {

                $("#modal_dialog #department_announce").hide();
                $("#modal_dialog #team_announce").show();

            } else if (radioValue == 'department') {
                $("#modal_dialog #department_announce").show();
                $("#modal_dialog #team_announce").hide();
            } else {
                $("#modal_dialog #department_announce").hide();
                $("#modal_dialog #team_announce").hide();
            }
        })
        $("input[name='share_with']").change(function() {
            var radioValue = $("input[name='share_with']:checked").val();

            if (radioValue == 'team') {

                $("#department_announce").hide();
                $("#team_announce").show();

            } else if (radioValue == 'department') {
                $("#department_announce").show();
                $("#team_announce").hide();
            } else {
                $("#department_announce").hide();
                $("#team_announce").hide();
            }

        });
    });

</script>
@endsection
