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
               Import  Attendance Report
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
                    <strong> Import Attendance Report</strong>
                         &nbsp;
                </div>

            </div>
            <div class="panel-body">
                <form method="post" action="{{route('admin.shiftAttendance.storeexcelimport')}}" role="form" enctype="multipart/form-data"
                action="" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Add Attendance File<span
                                class="required"></span></label>

                        <div class="col-sm-5">
                            <input type="file" name="import_file" class="form-control " required="">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"></label>
                        <div class="col-sm-5 ">
                            <button type="submit" id="sbtn" class="btn btn-primary">Import</button>
                             <a href="{{route('admin.shiftAttendance.excelimport')}}" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
</script>


@endsection
