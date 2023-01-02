@extends('layouts.master')

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {!! $page_title ?? "Page Title" !!}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {{-- Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong> --}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body ">
                <form method="post" action="{{route('admin.flight.location.store')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Country</label>
                            <div class="input-group">
                                <input type="text" name="country" class="form-control" placeholder="Country" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Location</label>
                            <div class="input-group">
                                <input type="text" name="location" class="form-control" placeholder="Location" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Latitude</label>
                            <div class="input-group">
                                <input type="text" name="latitude" class="form-control" placeholder="Latitude" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Longitude</label>
                            <div class="input-group">
                                <input type="text" name="longitude" class="form-control" placeholder="Longitude" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Status</label>
                            <div class="input-group">
                                <select name='status' class="form-control" style="width:400px;">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                                <a href="{!! route('admin.flight.location.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
