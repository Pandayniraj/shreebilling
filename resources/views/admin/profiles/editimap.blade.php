@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

                {!! Form::model( $imap, ['route' => ['admin.myprofile.updateimap'], 'method' => 'POST', 'id' => 'form_edit_imap'] ) !!}

                <div class="tab-pane active" id="tab_details">
                    <div class="form-group">
                        {!! Form::label('imap_email', trans('admin/users/general.columns.imap_email')) !!}
                        {!! Form::text('imap_email', null, ['class' => 'form-control']) !!}
                    </div>
                
                    <div class="form-group">
                        {!! Form::label('imap_password', trans('admin/users/general.columns.imap_password')) !!}
                        {!! Form::text('imap_password', null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="{!! route('admin.myprofile.showimap') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>

                {!! Form::close() !!}

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')

@endsection
