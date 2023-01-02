@extends('layouts.dialog')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<div class="alert alert-danger alert-dismissable" style="display: none;" id="errormodal">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4>
            <i class="icon fa fa-ban"></i> Errors
        </h4>
        <div id="modalserrors">
        </div>
    </div>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h2>
                Create  Contacts
            </h2>

          <i class="fa fa-info"></i> Quickly add Accountant, Marketing, Developer or HR of this company
         

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-header">
                <div class="content">
                <form method="post" class ='form-horizontal' id='form_contact' accept-charset="multipart/form-data" > 
                 @include('partials._contact_form')
                <div class="form-group">
                    {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-lg btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="{!! route('admin.contacts.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-lg btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>
                
            </form>
            </div>

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
    <div id="overlay">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>
           <script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/intl-tel-input/build/css/intlTelInput.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset("/bower_components/intl-tel-input/build/js/intlTelInput-jquery.min.js") }}"></script>
   <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />

<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
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

<script type="text/javascript">
    

$('#form_contact').submit(function(evt){
    $("#overlay").fadeIn(300);　
    evt.preventDefault();
    var data = new FormData();
    var form_data = $('#form_contact').serializeArray();
    $.each(form_data, function (key, input) {
        data.append(input.name, input.value);
    });

    //File data
    var file_data = $('input[name="file_name"]')[0].files;
    for (var i = 0; i < file_data.length; i++) {
        data.append("file_name", file_data[i]);
    }

    $.ajax({
        url: "/admin/contacts/create/modals", // NB: Use the correct action name
        type: "POST",
        data: data,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        processData: false,
        contentType: false,
        success: function(response) {
            try  {
                window.opener.HandlePopupResult(response);
            }
            catch (err) {
              console.log(err);
            } 
            $("#overlay").fadeOut(300);
            window.close();

         
        },
        error: function(response) {
            $("#overlay").fadeOut(300);
            $('#errormodal').css("display", "block")
            let errors = Object.values(JSON.parse(response.responseText));
            errors = errors.flat();
            err = '';
            errors.forEach(function(value){
            err = err + `<li>${value}</li>`;
            })
            $('#modalserrors').html(err);
            $(window).scrollTop(0);
            return false;
        }
    });
    return false;
});
</script>
    
@endsection

    <!-- form submit -->


