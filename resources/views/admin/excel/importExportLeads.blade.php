@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
               Import or Export Data
               
            </h1>
            <p> For error free upload. First download CSV or Excel file, populate the data in the same order and upload it. <hr/>
            Create a CSV file with following fields and populate data accordingly. Product id can be <a target="_blank" href="/admin/products">Found here</a>, <a target="_blank" href="">Campaign Ids</a><br/>   
           <strong> title, name, mob_phone, home_phone, email, description, product_id, campaign_id, city, country</strong>
 

                check your uploaded data at <a href="/admin/leads?type=3">Imported Leads</a> </p>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

                <a href="{{ URL::to('admin/downloadExcelLeads/xls') }}"><button class="btn btn-success">Download Excel xls</button></a>

                <a href="{{ URL::to('admin/downloadExcelLeads/xlsx') }}"><button class="btn btn-success">Download Excel xlsx</button></a>

                <a href="{{ URL::to('admin/downloadExcelLeads/csv') }}"><button class="btn btn-success">Download CSV</button></a>

                <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('admin/importExcelLeads') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="file" name="import_file" />
                    <br/>
                    <button class="btn btn-primary">Import File</button>

                </form>

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

