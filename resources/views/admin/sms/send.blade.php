@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

                {!! Form::open( ['route' => 'admin.post_send_sms', 'id' => 'form_edit_sms'] ) !!}

                <?php $readonly = ''; ?>

                <div class="content">
                    <div class="form-group">
                        {!! Form::label('recipient', trans('admin/sms/general.columns.recipient')) !!}
                        {!! Form::text('recipient', null, ['class' => 'form-control', $readonly]) !!}
                    </div>
                    
                    <div class="form-group">
                    	{!! Form::label('message', trans('admin/sms/general.columns.message')) !!}
                        {!! Form::textarea('message', null, ['class' => 'form-control', 'id'=>'lead_note', 'rows'=>'3']) !!}
                    </div>
                </div><!-- /.content -->

                <div class="form-group">
                    {!! Form::submit( 'Send SMS', ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                </div>

                {!! Form::close() !!}

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

