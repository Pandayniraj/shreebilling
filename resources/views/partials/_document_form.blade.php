<?php $readonly = ($document->isEditable())? '' : 'readonly'; ?>

<div class="content" style="padding-left: 0;">
    <div class="col-md-6" style="padding-left: 0;">
          <div class="form-group">
            <label for="foldername">{{trans('admin/documents/general.columns.folder') }}</label>
            {!! Form::select('folder_id', $folders, null, ['class' => 'form-control']) !!}
               <small style="color:red;display: none;" id='folder_id-valid'><i>{{ trans('admin/documents/general.warning.select_folder')}}*</i></small>
        </div>
        <div class="form-group">
            {!! Form::label('doc_cats', trans('admin/documents/general.columns.doc_cats')) !!}
            {!! Form::select('doc_cats',$categories, null, ['class' => 'form-control','placeholder'=>'Select Category']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('doc_desc', trans('admin/documents/general.columns.doc_desc')) !!}
            {!! Form::textarea('doc_desc', null, ['class'=>'form-control', 'rows'=>'3']) !!}
        </div>

        <div class="form-group">
            <label>
                {!! Form::checkbox('show_in_portal', '1', $document->show_in_portal) !!} {!! trans('admin/documents/general.columns.show_in_portal') !!}
            </label>
        </div>
    </div>

    <div class="col-md-6" >

        <div class="form-group">
            {!! Form::label('file', trans('admin/documents/general.columns.file')) !!} *
            <input type="file" name="file_name">
            @if($document->file != '')
            <label>{{ trans('admin/documents/general.columns.current_file') }}: </label>&nbsp;&nbsp;&nbsp;<a target="_blank" href="{{ '/documents/'.$document->file }}">{{ $document->doc_name }}</a>
            @endif
             <small style="color:red;display: none;"  id='file_name-valid'><i>{{ trans('admin/documents/general.warning.choose_file')}}*</i></small>
        </div>

        <div class="form-group">
            {!! Form::label('doc_name', trans('admin/documents/general.columns.doc_name')) !!} *
            {!! Form::text('doc_name', null, ['class' => 'form-control', 'id'=>'doc_name']) !!}
             <small style="color:red;display: none;"  id='doc_name-valid'><i>{{ trans('admin/documents/general.warning.enter_doc')}}*</i></small>
        </div>
        <input type="hidden" name='doc_type' value="{{ $document->doc_type ?? $type}}">
      {{--   <div class="form-group">
            {!! Form::label('doc_type', trans('admin/documents/general.columns.doc_type')) !!}
            {!! Form::select('doc_type', ['docs'=>'Docs', 'note'=>'Note'], null, ['class' => 'form-control']) !!}
        </div> --}}

      

        <div class="form-group">
            <label>
                {!! Form::checkbox('enabled', '1', $document->enabled) !!} {!! trans('general.status.enabled') !!}
            </label>
        </div>
        
    </div>

</div>

<div class="clearfix"></div>
</div><!-- /.content -->

<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $("#contact_id").autocomplete({
            source: "/admin/getContacts",
            minLength: 1
      });

      $("#client_id").autocomplete({
            source: "/admin/getClients",
            minLength: 1
      });
    });
</script>
