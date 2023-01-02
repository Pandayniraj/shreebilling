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
                      {!! Form::model( $document, ['route' => ['admin.documents.update', $document->id], 'method' => 'PUT', 'id' => 'form_edit_document', 'enctype' => 'multipart/form-data'] ) !!} 

                            {{ csrf_field() }}


                        <div class="form-group">
                          <label for="email">{{ trans('admin/documents/general.columns.doc_name')}}</label>
                          {!! Form::text('doc_name', $document->doc_name, ['class' => 'form-control', 'id'=>'doc_name','placeholder'=>"Enter documents name"]) !!}
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                              <label for="email">{{ trans('admin/documents/general.columns.doc_cats')}}</label>
                              {!! Form::select('doc_cats',$categories, $document->doc_cats, ['class' => 'form-control','placeholder'=>'Select Category']) !!}
                            </div>
                            <div class="form-group col-md-6">
                              <label for="email">{{trans('admin/documents/general.columns.folder') }}</label>
                              {!! Form::select('folder_id', $folders, $document->folder_id, ['class' => 'form-control']) !!}
                            </div>
                        </div>



                        <div class="form-group">

        <textarea  name="doc_desc" class="form-control" placeholder="Template Descriptions" style="width: 100%" rows="25" id='body'>{!! $document->doc_desc !!}</textarea>

                        </div>

        <input type="hidden" name='doc_type' value="{{$type}}">
                    <div class="form-group">
                            {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                            <a href="#" title="{{ trans('general.button.update') }}" class='btn btn-default' data-dismiss="modal" aria-hidden="true">{{ trans('general.button.cancel') }}</a>
                        </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

<script>
    
 @if($type != 'note')
 setTimeout(function(){
        $('textarea#body').wysihtml5(
        {

        toolbar: {

            "link": false, // Button to insert a link.
            "image": false, // Button to insert an image.
          }

        });

       
    },500);
    @endif
</script>