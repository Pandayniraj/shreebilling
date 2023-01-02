@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
            Edit Contact
                <small>{!! $page_descriptions !!}</small>
            </h1>
            <p> Editing contact</p>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
    <div class='row'>
        <div class='col-md-12 box'>
            <div class="box-body">

                {!! Form::model( $contact, ['route' => ['admin.contacts.update', $contact->id], 'method' => 'PATCH', 'class' => 'form-horizontal',  'id' => 'form_edit_contact', 'enctype' => 'multipart/form-data'] ) !!}
			
                @include('partials._contact_form')

                <div class="form-group">
                    {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="{!! route('admin.contacts.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>

                {!! Form::close() !!}

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <!-- form submit -->

    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
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
          sideBySide: true,
          allowInputToggle: true
        });

      });

    $(document).ready(function() {
    $('#products').select2();
});
</script>
    
    @include('partials._body_bottom_submit_contact_edit_form_js')
@endsection

