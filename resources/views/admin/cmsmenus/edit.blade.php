@extends('layouts.master')

@section('content')
@include('partials._head_extra_select2_css')
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
                <form method="post" action="{{route('admin.cms.menus.update',$cmsmenu->id)}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Name</label>
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" placeholder="Name" required="" value="{{$cmsmenu->name}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">URL</label>
                            <div class="input-group">
                                <input type="text" name="url" class="form-control" placeholder="URL" required="" value="{{$cmsmenu->url}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Parent</label>
                            <div class="input-group">
                                <select name="parent_id" class="form-control select2" style="width:400px;">
                                    <option value="">Select Parent</option>
                                    @foreach($parent as $parent)
                                    <option value="{{$parent->id}}" @if($cmsmenu->parent_id == $parent->id) selected @endif>{{$parent->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Order</label>
                            <div class="input-group">
                                <input type="text" name="order_id" class="form-control" placeholder="Order Id" required="" value="{{$cmsmenu->order_id}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">User</label>
                            <div class="input-group">
                                <select name="user_id" class="form-control select2" style="width:400px;">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}" @if($cmsmenu->user_id == $user->id) selected @endif>{{$user->username}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Enabled</label>
                            <div class="input-group">
                                <input type="checkbox" name="enabled" value="1" @if($cmsmenu->enabled) checked @endif>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                                <a href="{!! route('admin.cms.menus.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
    <script>
        $('.select2').select2();

    </script>
    @endsection
