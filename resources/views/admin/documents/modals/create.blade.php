<div class="modal-content">
    <div id="printableArea">
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">{{ trans('/admin/documents/general.page.create.section-title') }}
                <small>{{ trans('/admin/documents/general.page.create.description') }}</small>
                <div class="pull-right ">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </h4>
        </div>
        <div class="modal-body wrap-modal wrap">
            <div class='row'>
                <div class='col-md-12'>
                    <div class="box-body">
                        <form action="{{ route('admin.documents.store') }}" id = 'form_edit_document' name='form_edit_document' enctype='multipart/form-data' method="post">                   
                            {{ csrf_field() }}
                        @include('partials._document_form')
                        
                        <div class="form-group">
                            {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                            <a href="#" title="{{ trans('general.button.cancel') }}" class='btn btn-default' data-dismiss="modal" aria-hidden="true">{{ trans('general.button.cancel') }}</a>
                        </div>
                        
                        </form>


                    </div><!-- /.box-body -->
                </div><!-- /.col -->

            </div><!-- /.row -->

        </div>
    </div>
</div>
<script type="text/javascript">
    $("#btn-submit-edit").on("click", function () {
        // Post form.

        var cansubmit = true;
        if($('select[name=folder_id]').val().trim().length == 0){
            $('#folder_id-valid').show(200);
            cansubmit = false;
        }
        if($('input[name=file_name]').val().trim().length == 0){
            $('#file_name-valid').show(200);
            cansubmit = false;
        }
        if($('input[name=doc_name]').val().trim().length == 0){
            $('#doc_name-valid').show(200);
            cansubmit = false;
        }
        if(cansubmit){
            $("#form_edit_document").submit();
        }
        return false;
       
    });
</script>