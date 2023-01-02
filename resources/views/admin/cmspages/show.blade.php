@extends('layouts.master')
@section('content')
<link href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />
<script src='{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}'></script>

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
               
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Title</label>
                            <div class="input-group">
                                <input type="text" name="title" class="form-control" placeholder="Title" required="" value="{{$cmspage->title}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Heading</label>
                            <div class="input-group">
                                <input type="text" name="heading" class="form-control" placeholder="Heading" required="" value="{{$cmspage->heading}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Excerpt</label>
                            <div class="input-group">
                                <textarea name="excerpt" class="form-control" placeholder="Excerpt" cols="50" readonly>{!! $cmspage->excerpt !!}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Enabled</label>
                            <div class="input-group">
                                <input type="checkbox" name="enabled" value="1" @if($cmspage->enabled) checked @endif >

                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Photo</label>
                            
                            @if($cmspage->photo)
                            <img src="/cmspages/{{$cmspage->photo}}" width="50px;">
                            @endif
                        </div>  

                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">User</label>
                            <div class="input-group">
                                <select name="user_id" class="form-control select2" style="width:400px;" readonly>
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}" @if($user->id == $cmspage->user_id) selected @endif>{{$user->username}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-8 col-sm-12 form-group">
                            <label class="control-label">Body</label>
                            <div class="input-group">
                                <textarea name="body" id="body" class="form-control" placeholder="Body" readonly>{!! $cmspage->body !!}</textarea>
                            </div>
                        </div>
                         <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Slug</label>
                            <div class="input-group">
                                <input name="slug" id="slug" class="form-control" placeholder="slug" value="{{$cmspage->slug}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{!! route('admin.cms.pages.edit',['id'=>$cmspage->id]) !!}" class='btn btn-primary'>{{ trans('general.button.edit') }}</a>
                                <a href="{!! route('admin.cms.pages.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
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
        $(document).ready(function() {
            $('textarea#body').wysihtml5();
        });

    </script>
    @endsection
