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
                <form method="post" action="{{route('admin.tours.types.settings.update',$tourtypes->id)}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Name</label>
                            <div class="input-group">
                                <input type="text" name="sett_name" class="form-control" placeholder="Name" required="" value="{{$tourtypes->sett_name}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Type</label>
                            <div class="input-group">
                                <input type="text" name="sett_type" class="form-control" placeholder="Type" required="" value="{{$tourtypes->sett_type}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Selected</label>
                            <div class="input-group">
                                <select name="sett_selected" class="form-control" style="width:400px;">
                                    <option value="">Select Status</option>
                                    <option value="Yes" @if($tourtypes->sett_selected == 'Yes') selected @endif>Yes</option>
                                    <option value="No" @if($tourtypes->sett_selected == 'No') selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Status</label>
                            <div class="input-group">
                                <select name="sett_status" class="form-control" style="width:400px;">
                                    <option value="Yes" @if($tourtypes->sett_status == 'Yes') selected @endif>Yes</option>
                                    <option value="No" @if($tourtypes->sett_status == 'No') selected @endif>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Image</label>
                            <div class="input-group">
                                <input type="file" name="sett_img">
                            </div>
                        </div>
                        @if($tourtypes->sett_img)
                        <img src="/tourtype/{{$tourtypes->sett_img}}" width="100px;">
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                                <a href="{!! route('admin.tours.types.settings.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
