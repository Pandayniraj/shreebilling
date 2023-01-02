@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Editing {{ $_GET['relation_type']}}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
    <div class='row'>
        <div class='col-md-12 box'>
            <div class="box-body">

                {!! Form::model( $client, ['route' => ['admin.clients.update', $client->id], 'method' => 'PATCH', 'id' => 'form_edit_client' ,'class' => 'form-horizontal','files'=>'true'] ) !!}

                @include('partials._client_form')

                <input type="hidden" name="relation_type" value="{{ $_GET['relation_type'] }}">
                <div class="form-group">
                    {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="/admin/{{ \Request::get('relation_type') }}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>

                {!! Form::close() !!}

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

