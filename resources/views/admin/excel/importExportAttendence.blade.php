@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

                <a href="{{ URL::to('admin/downloadExcel/attendance/xls') }}"><button class="btn btn-success">Download Excel xls</button></a>

                <a href="{{ URL::to('admin/downloadExcel/attendance/xlsx') }}"><button class="btn btn-success">Download Excel xlsx</button></a>

                <a href="{{ URL::to('admin/downloadExcel/attendance/csv') }}"><button class="btn btn-success">Download CSV</button></a>

                <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('admin/importexport/attendance_report') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="file" name="import_file" />
                    <br/>
                    <button class="btn btn-primary">Import File</button>

                </form>

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

