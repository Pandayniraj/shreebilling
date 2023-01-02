@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
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
            <div class="box box-body">

                {!! Form::model( $FiscalYear, ['route' => ['admin.fiscalyear.update', $FiscalYear->id], 'method' => 'PATCH', 'id' => 'form_edit_fiscalyear'] ) !!}
				
                @include('partials._fiscalyear_form')

                <div class="form-group">
                    {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="{!! route('admin.fiscalyear.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>

                {!! Form::close() !!}



            </div><!-- /.box-body -->


        </div><!-- /.col -->


    </div><!-- /.row -->
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

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




