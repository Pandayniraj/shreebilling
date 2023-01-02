@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

                <div class="tab-pane active col-md-6" id="tab_details">
                    <div class="form-group">
                        {!! Form::label('first_name', trans('admin/users/general.columns.first_name')) !!}
                        {!! Form::text('first_name', null, ['class' => 'form-control', 'readonly']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('last_name', trans('admin/users/general.columns.last_name')) !!}
                        {!! Form::text('last_name', null, ['class' => 'form-control', 'readonly']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('username', trans('admin/users/general.columns.username')) !!}
                        {!! Form::text('username', null, ['class' => 'form-control', 'readonly']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('email', trans('admin/users/general.columns.email')) !!}
                        {!! Form::text('email', null, ['class' => 'form-control', 'readonly']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('password', trans('admin/users/general.columns.password')) !!}
                        {!! Form::password('password', ['class' => 'form-control', 'readonly']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('password_confirmation', trans('admin/users/general.columns.password_confirmation')) !!}
                        {!! Form::password('password_confirmation', ['class' => 'form-control', 'readonly']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('auth_type', trans('admin/users/general.columns.type')) !!}
                        {!! Form::text('auth_type', null, ['class' => 'form-control', 'readonly']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                	@if(\Auth::user()->image)
                    <label>Profile Photo:</label><br/>
                    <img src="/images/profiles/{!! \Auth::user()->image !!}" alt="{!! \Auth::user()->first_name !!}" />
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <a href="/admin/dashboard" class='btn btn-primary'>Close</a>
                        @if ($user->isEditable())
                            <a href="{!! route('admin.myprofile.edit') !!}" title="{{ trans('general.button.edit') }}" class='btn btn-default'>{{ trans('general.button.edit') }}</a>
                        @endif
                    </div>
                </div>

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection

@section('body_bottom')
    <!-- Select2 js -->
    @include('partials._body_bottom_select2_js_role_search')
@endsection
