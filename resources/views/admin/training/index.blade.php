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

    .select2-container--bootstrap .select2-results__group { font-size: 15px !important; padding: 6px 3px !important; }
    .select2-container--bootstrap .select2-results__option .select2-results__option { color: #777 !important; }

    .fileinput {
        margin-bottom: 9px;
        display: inline-block;
    }

    .fileinput-exists .fileinput-new, .fileinput-new .fileinput-exists {
        display: none;
    }
    .fileinput-filename { padding-left:  5px; }

    .fileinput .btn {
        vertical-align: middle;
    }

    .btn.btn-default {
        border-color: #ddd;
        background: #f4f4f4;
    }
    .btn-file {
        overflow: hidden;
        position: relative;
        vertical-align: middle;
    }

    .btn-file > input {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        opacity: 0;
        filter: alpha(opacity=0);
        transform: translate(-300px, 0) scale(4);
        font-size: 23px;
        direction: ltr;
        cursor: pointer;
    }
    input[type="file"] {
        display: block;
    }

    .close {
        float: right;
        font-size: 21px;
        font-weight: bold;
        line-height: 1;
        color: #000000;
        text-shadow: 0 1px 0 #ffffff;
        opacity: 0.2;
        filter: alpha(opacity=20);
    }

    .fileinput.fileinput-exists .close {
        opacity: 1;
        color: #dee0e4;
        position: relative;
        top: 3px;
        margin-left: 5px;
    }
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Trainings
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <p> Schedule all your company trainings here</p>

          {{ TaskHelper::topSubMenu('topsubmenu.hr')}}


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-sm-12" data-offset="0">
        <div class="panel panel-custom">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <div class="panel-title">
                    <div class="pull-left hidden-print">
                        <strong>All Trainings</strong>
                        <a href="/admin/trainings/generatePdf" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF" target="_blank"><span>
                            <i class="fa fa-download"></i></span></a>
                        <a href="/admin/trainings/print" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print"><span><i class="fa fa-print"></i></span></a>
                    </div>
                    <div class="pull-right hidden-print">
                        <a href="javascript::void(0)" class="btn btn-primary btn-xs" data-toggle="modal" data-placement="top" data-target="#training_modal" id="create_new"> + New Training</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- Table -->
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="col-sm-1">SL</th>
                        <th>Name</th>
                        <th>Course/Training</th>
                        <th>Vendor</th>
                        <th>Start Date</th>
                        <th>Finish Date</th>
                        <th>Status</th>
                        <th class="col-sm-1 hidden-print">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trainings as $ak => $av)
                    <tr>
                        <td>{{ $ak+1 }}</td>
                        <td>{{ TaskHelper::getUserName($av->user_id) }}</td>
                        <td>{{ $av->training_name }}</td>
                        <td>{{ $av->vendor_name }}</td>
                        <td>{{ $av->start_date }}</td>
                        <td>{{ $av->finish_date }}</td>
                        <td>@if($av->status == 0) Pending @elseif($av->status == 1) Started @elseif($av->status == 2) Completed @else Terminated @endif</td>
                        <td class="hidden-print">
                            <a href="/admin/trainings/show/{{ $av->training_id }}" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#training_show" data-placement="top"><i class="fa fa-list-alt"></i></a>
                            <a href="/admin/trainings/edit/{{ $av->training_id }}" class="btn btn-primary btn-xs" data-toggle="modal" data-placement="top" data-target="#training_modal" @if(!$av->isEditable()) disabled @endif><span class="fa fa-edit"></span></a>
                            <a href="/admin/trainings/delete/{{ $av->training_id }}" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" @if(!$av->isDeletable()) disabled @endif><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<div class="modal fade" id="training_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">
            @include('admin.training.modal')
        </div>
    </div>
</div>

<div class="modal fade" id="training_show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
        });

        $('#DataTables').DataTable({
            pageLength: 50
        });

        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#create_new').on('click', function() {
        $('#training_modal #myModalLabel').html('New Training');
        $('#sbtn').html(' Save ');
    });

    $(document).on('focus', '#start_date, #finish_date', function() {
        $(this).datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
        }, "show");
    });

    // clear modal box
    $(document).on("hidden.bs.modal", '#training_modal', function(event) { 
        $(this).find("input,textarea,select").val('').end()
                .find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
    });

</script>

<script type="text/javascript">
    $(document).ready(function () {

        $(document).on('change', 'input[type="file"]', function(e) { 
            var fileName = e.target.files[0].name;
            $(this).parent().parent().removeClass('fileinput-new');
            $(this).parent().parent().addClass('fileinput-exists');

            $(this).attr('name', $(this).parent().find('input[type="hidden"]').attr('name'));
            $(this).parent().find('input[type="hidden"]').attr('name', '');
            $(this).parent().parent().find('.fileinput-filename').html(fileName);
        });

        $(document).on('click', '.close', function() { 

            $(this).parent().removeClass('fileinput-exists');
            $(this).parent().addClass('fileinput-new');

            $(this).parent().find('input[type="hidden"]').attr('name', $(this).parent().find('input[type="file"]').attr('name'));
            $(this).parent().find('input[type="file"]').attr('name', '');
            $(this).parent().find('.fileinput-filename').html('');

            $(this).parent().find('input[type="file"]').reset();
        });

        $(document).on('click', 'input[type="file"]', function() { 
            if($(this).attr('name') != '')
            {
                $(this).parent().parent().removeClass('fileinput-exists');
                $(this).parent().parent().addClass('fileinput-new');

                $(this).parent().parent().find('input[type="hidden"]').attr('name', $(this).parent().find('input[type="file"]').attr('name'));
                $(this).parent().parent().find('input[type="file"]').attr('name', '');
                $(this).parent().parent().find('.fileinput-filename').html('');

                $(this).reset();
            }
        });

        var maxAppend = 0;
        $(document).on('click', '#add_more', function () {

            var add_new = $('<div class="form-group" style="margin-bottom: 0px">\n\
                                    <label for="field-1" class="col-sm-3 control-label">Attachment</label>\n\
                        <div class="col-sm-4">\n\
                        <div class="fileinput fileinput-new" data-provides="fileinput">\n\
                <span class="btn btn-default btn-file"><span class="fileinput-new" >Select file</span><span class="fileinput-exists" >Change</span><input type="hidden" value="" name="upload_file[]"><input type="file" name=""></span> <span class="fileinput-filename"></span><a href="javascript::undefined" class="close fileinput-exists" data-dismiss="fileinput" style="float: none;">&times;</a></div></div>\n\<div class="col-sm-3">\n\<strong>\n\
                <a href="javascript:void(0);" class="remCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div>');
            maxAppend++;
            $("#add_new").append(add_new);
        });

        $(document).on('click', 'input[name="permission"]', function () {
            if($(this).val() == 'custom_permission')
                $('#permission_user').css('display', 'block');
            else
                $('#permission_user').css('display', 'none');
        });

        $(document).on('click', '.remAttachments', function () {
            var attachments = $('#remove_attachments').val();
            remFile = $(this).parent().parent().find('.form-control-static').html();
            alert(remFile);
            if(attachments == '')
                attachments = remFile;
            else
                attachments = attachments + ',' +remFile;

            $('#remove_attachments').val(attachments);
            $(this).parent().parent().remove();
        });

        $(document).on('click', '.remCF', function () {
            $(this).parent().parent().parent().remove();
        });
        $('a.RCF').click(function () {
            $(this).parent().parent().remove();
        });
    });
</script>
@endsection
