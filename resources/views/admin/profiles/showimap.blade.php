@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">


                <div class="tab-pane active" id="tab_details">
                    <div class="form-group">
                        {!! Form::label('imap_email', trans('admin/users/general.columns.imap_email')) !!}
                        {!! Form::text('imap_email', $imap ? $imap->imap_email : '', ['class' => 'form-control', 'readonly']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('imap_password', trans('admin/users/general.columns.imap_password')) !!}
                        {!! Form::text('imap_password', $imap ? $imap->imap_password : '', ['class' => 'form-control', 'readonly']) !!}
                    </div>
                </div>
                <div class="form-group">
                    .<a href="{!! route('dashboard') !!}" title="{{ trans('general.button.close') }}" class='btn btn-primary'>{{ trans('general.button.close') }}</a>
                    <a href="{!! route('admin.myprofile.editimap') !!}" title="{{ trans('general.button.edit') }}" class='btn btn-default'>{{ trans('general.button.edit') }}</a>
                </div>


            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection

@section('body_bottom')

@endsection
