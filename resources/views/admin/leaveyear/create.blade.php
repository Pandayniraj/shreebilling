@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    <style>

 .dropdown-menu { 
  
    z-index: 1000;
}

</style>
    @include('partials._head_extra_select2_css')
@endsection

@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {!! $page_title ?? "Fiscal Year" !!}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
        </section>

    <div class='row'>
        <div class='col-md-6'>
            <div class=" box box-body">
                {!! Form::open( ['route' => 'admin.leaveyear.store', 'id' => 'form_edit_leadstatus'] ) !!}
                @include('partials._leaveyear_form')  
                <div class="form-group">
                    {!! Form::button( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="{!! route('admin.leaveyear.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>
                {!! Form::close() !!}
            </div><!-- /.box-body -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
    <!-- form submit -->
    @include('partials._body_bottom_submit_lead_status_edit_form_js')


<script type="text/javascript">
    $(function(){
        $('.datepicker').datepicker({
          //inline: true,
          dateFormat: 'yy-mm-dd', 
          sideBySide: false,  
        });
      });
</script>

@endsection
