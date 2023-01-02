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
                <form method="post" action="{{route('admin.flight.country.store')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">ISO</label>
                            <div class="input-group">
                                <input type="text" name="iso" class="form-control" placeholder="ISO" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Name</label>
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" placeholder="Name" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">NiceName</label>
                            <div class="input-group">
                                <input type="text" name="nicename" class="form-control" placeholder="NiceName" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">ISO3</label>
                            <div class="input-group">
                                <input type="text" name="iso3" class="form-control" placeholder="ISO3" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">NumCode</label>
                            <div class="input-group">
                                <input type="text" name="numcode" class="form-control" placeholder="NumCode" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">PhoneCode</label>
                            <div class="input-group">
                                <input type="text" name="phonecode" class="form-control" placeholder="PhoneCode" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                                <a href="{!! route('admin.flight.country.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
