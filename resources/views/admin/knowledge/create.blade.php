@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')


<link rel="stylesheet" type="text/css" href="{{ asset("/bower_components/bootstrap-wysihtml5/lib/css/bootstrap-wysihtml5.css") }}"></link>
<link rel="stylesheet" type="text/css" href="{{ asset("/bower_components/bootstrap-wysihtml5/lib/css/bootstrap.min.css") }}"></link>
<script src="{{ asset("/bower_components/bootstrap-wysihtml5/lib/js/wysihtml5-0.3.0.js") }}"></script>
<script src="{{ asset("/bower_components/bootstrap-wysihtml5/lib/js/jquery-1.7.2.min.js") }}"></script>
<script src="{{ asset("/bower_components/bootstrap-wysihtml5/lib/js/bootstrap.min.js") }}"></script>
<script src="{{ asset("/bower_components/bootstrap-wysihtml5/lib/js/wysihtml5-0.3.0.min.js") }}"></script>
<script type="text/javascript">
    
    $('.textarea').wysihtml5();

</script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title ?? "Page Title" !!}

                <small>{!! $page_description ?? "Page Description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>

    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-body">

                {!! Form::open( ['route' => 'admin.knowledge.store', 'id' => 'form_edit_knowledge'] ) !!}

                @include('partials._knowledge_form')
                
                <div class="form-group">
                    {!! Form::button( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="{!! route('admin.knowledge.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>
                
                {!! Form::close() !!}

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->



@endsection

@section('body_bottom')
    <!-- form submit -->
    @include('partials._body_bottom_submit_knowledge_edit_form_js')
@endsection
