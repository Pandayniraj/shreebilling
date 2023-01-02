@extends('layouts.master')
@section('content')

<link href="/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css rel="stylesheet" type="text/css" />
<link href="/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

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
                {{$page_title ?? "Page Title"}}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
<div class="row">
    <div class="col-sm-12" data-offset="0">
        <div class="panel panel-custom">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <div class="panel-title">
                    <div class="pull-left hidden-print">
                        <strong>All Circulars</strong>
                    </div>
                    <div class="pull-right hidden-print">
                        <a href="javascript::void(0)" class="btn btn-primary btn-xs" data-toggle="modal" data-placement="top" data-target="#recruitment_modal" id="create_new"><span class="fa fa-plus"></span> New Circular</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- Table -->
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="col-sm-1">SL</th>
                        <th>Job Title</th>
                        <th>Designation</th>
                        <th># of Vacancy</th>
                        <th>Posted Date</th>
                        <th>Last Date</th>
                        <th>Status</th>
                        <th class="col-sm-1 hidden-print">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($circulars as $ak => $av)
                    <tr>
                        <td>{{ $ak+1 }}</td>
                        <td>{{ $av->job_title }}</td>
                        <td>{{ $av->designation_id }}</td>
                        <td>{{ $av->vacancy_no }}</td>
                        <td>{{ $av->posted_date }}</td>
                        <td>{{ $av->last_date }}</td>
                        <td>@if($av->status == 'published') Published @elseif($av->status == 'unpublished') Unpublished  @endif</td>
                        <td class="hidden-print">
                            <a href="/admin/job_posted/show/{{ $av->job_circular_id }}"  data-toggle="modal" data-target="#training_show" data-placement="top" class="showmodal"><i class="fa fa-list"></i></a>
                            <a href="/admin/job_posted/edit/{{ $av->job_circular_id }}"  data-toggle="modal" data-placement="top" data-target="#recruitment_modal" class="editmodal" @if(!$av->isEditable()) disabled @endif><i class="fa fa-edit"></i></a>
                            <a href="/admin/job_posted/delete/{{ $av->job_circular_id }}"  data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" @if(!$av->isDeletable()) disabled @endif><i class="fa fa-trash deletable"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<div class="modal fade" id="recruitment_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @include('admin.recruitment.modal')
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

<script src="/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/bower_components/admin-lte/plugins/daterangepicker/moment.js" type="text/javascript"></script>
<script src="/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js" type="text/javascript"></script>

<script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
        });

        $('[data-toggle="tooltip"]').tooltip();

        // Setting for Datatables
        var total_header = ($('table#DataTables th:last').index());
        var testvar = [];
        for (var i = 0; i < total_header; i++) {
            testvar[i] = i;
        }
        var length_options = [10, 25, 50, 100];
        var length_options_names = [10, 25, 50, 100];

        var tables_pagination_limit =25;
        tables_pagination_limit = parseFloat(tables_pagination_limit);

        if ($.inArray(tables_pagination_limit, length_options) == -1) {
            length_options.push(tables_pagination_limit)
            length_options_names.push(tables_pagination_limit)
        }
        length_options.sort(function (a, b) {
            return a - b;
        });
        length_options_names.sort(function (a, b) {
            return a - b;
        });
        
    $('#create_new').on('click', function() {
        $('#recruitment_modal #myModalLabel').html('New Circular');
        $('#sbtn').html(' Save ');
    });

    $(document).on('focus', '.datepicker', function() {
        $(this).datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
        }, "show");
    });

    // clear modal box
    $(document).on("hidden.bs.modal", '#recruitment_modal', function(event) { 
        $(this).find("input,textarea,select").val('').end()
                .find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
    });


    $(document).ready(function () {

        $(document).on('click', '.close', function() { 

            $(this).parent().removeClass('fileinput-exists');
            $(this).parent().addClass('fileinput-new');

            $(this).parent().find('input[type="hidden"]').attr('name', $(this).parent().find('input[type="file"]').attr('name'));
            $(this).parent().find('input[type="file"]').attr('name', '');
            $(this).parent().find('.fileinput-filename').html('');

            $(this).parent().find('input[type="file"]').reset();
        });

        $('.showmodal').click(function(){   
        $('#modal_dialog .modal-content').html(''); 
        let url = $(this).attr('href');
        $('#modal_dialog .modal-content').load(url,function(result){
         $('#modal_dialog').modal({show:true}); 
        });
        return false;
      });

      $('.editmodal').click(function(){   
        $('#modal_dialog .modal-content').html(''); 
        let url = $(this).attr('href');
        $('#modal_dialog .modal-content').load(url,function(result){
         $('#modal_dialog').modal({show:true}); 
        });
        return false;
      });

    // clear modal box
    $(document).on("hidden.bs.modal", '#announcement_modal', function(event) { 
        $(this).find("input,textarea,select").val('').end()
                .find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
        
    });
    });
});    
</script>
@endsection
