@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Create {{ $_GET['relation_type']}}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>



            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-header">
                <div class="content">

                {!! Form::open( ['route' => 'admin.clients.store', 'class' => 'form-horizontal', 'id' => 'form_edit_client', 'files'=>'true'] ) !!}

                @include('partials._client_form')

                <div class="form-group">
                    {!! Form::button( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}

                    <a href="/admin/{{ $_GET['relation_type']}}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>Cancel</a>
                </div>
                <input type="hidden" name="relation_type", value="{{ $_GET['relation_type']}}">
                <input type="hidden" name="going_to", value="{{ $_GET['going_to']}}">

                {!! Form::close() !!}
            </div>

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <!-- form submit -->
    @include('partials._body_bottom_submit_client_edit_form_js')
@endsection
