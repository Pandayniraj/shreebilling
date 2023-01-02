@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Create  Contacts
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>

          <i class="fa fa-info"></i> Contacts are associated with Customer or Suppliers therefore <strong>client</strong> field is mandatory. Contacts are used specialliy for sales agent commission<br/>
          <i class="fa fa-whatsapp"></i> To use whatsapp write number in format 9779849628088 dont use +977 or 00977
         

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-header">
                <div class="content">

                {!! Form::open( ['route' => 'admin.contacts.store', 'class' => 'form-horizontal', 'id' => 'form_edit_contact', 'enctype' => 'multipart/form-data'] ) !!}

                @include('partials._contact_form')
                
                <div class="form-group">
                    {!! Form::button( trans('general.button.create'), ['class' => 'btn btn-lg btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="{!! route('admin.contacts.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-lg btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>
                
                {!! Form::close() !!}
            </div>

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <!-- form submit -->

    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/intl-tel-input/build/css/intlTelInput.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset("/bower_components/intl-tel-input/build/js/intlTelInput-jquery.min.js") }}"></script>

    <style type="text/css">
        
        .intl-tel-input { width: 100%; }
        .intl-tel-input .iti-flag .arrow {border: none;}

    </style>

    <script type="text/javascript">
        $(document).ready(function() {
          $("#client_id").autocomplete({
                source: "/admin/getClients",
                minLength: 1
            });
        });
    </script>

      <script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          //showMonthAfterYear: true
          //changeYear: true
          //sideBySide: true,
          //allowInputToggle: true
        });

      });

</script>

    
    @include('partials._body_bottom_submit_contact_edit_form_js')
@endsection
