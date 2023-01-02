@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title ?? "Page Title" !!}

                <small>{!! $page_description ?? "Page Description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

                {!! Form::model( $knowledge, ['route' => ['admin.knowledgecat.update', $knowledge->id], 'method' => 'PUT', 'id' => 'form_edit_knowledge'] ) !!}
				
                @include('partials._knowledgecat_form')

                <div class="form-group">
                    {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="{!! route('admin.knowledgecat.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>

                {!! Form::close() !!}

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

