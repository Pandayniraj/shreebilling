@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
 @include('partials._head_extra_select2_css')

@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

                {!! Form::open( ['route' => 'admin.producttypemaster.store', 'id' => 'form_edit_productcat'] ) !!}

                @include('partials._product_type_master_form')

                <div class="form-group">
                    {!! Form::button( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="{!! route('admin.producttypemaster.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>

                {!! Form::close() !!}

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <!-- form submit -->
    @include('partials._body_bottom_submit_lead_productcats_edit_form_js')
@endsection
