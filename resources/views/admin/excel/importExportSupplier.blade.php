@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
               Import or Export Data for Suppliers
               
            </h1>
            <p> For error free upload. First download CSV or Excel file, populate the data in the same order and upload it. If you do not know the org_id put the value 1. </p>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
    <div class='row'>
        <div class='col-md-12 box'>
            <div class="box-body">

                <a href="{{ URL::to('admin/downloadExcelclients/xls') }}"><button class="btn btn-success">Download Excel xls</button></a>

                <a href="{{ URL::to('admin/downloadExcelclients/xlsx') }}"><button class="btn btn-success">Download Excel xlsx</button></a>

                <a href="{{ URL::to('admin/downloadExcelclients/csv') }}"><button class="btn btn-success">Download CSV</button></a>

                <a href="{!! route('admin.download.supplier.pdf.index') !!}"><button class="btn btn-info"> <i class="fa  fa-file-pdf-o">&nbsp;</i> PDF Export</button></a>

                <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('/admin/clients/importExcelClients') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="file" name="import_file" />
                    <br/>
                    <button class="btn btn-primary">Import File</button>

                </form>

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

