<style>
.modal-mail {
    margin: 30px auto !important;
    width: 800px !important;
}
.modal-mail {
    margin: 10px;
    position: relative;
    width: auto;
}
h2 { font-size:23px; margin-top:0 !important; margin-left:10px;}

.form-horizontal .control-label{
    text-align: left !important;
}

</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ asset ("/js/tinymce/tinymce.min.js") }}"></script>
<script>
    tinymce.init({
		relative_urls: false,
		remove_script_host: false,
		selector: "textarea#compose-textarea",
		theme: "modern",
		skin: 'light',
		media_strict: false,
		media_filter_html: false,
		extended_valid_elements: "i[class],div[class|style],iframe[src|width|height|name|align], embed[width|height|name|flashvars|src|bgcolor|align|play|loop|quality|allowscriptaccess|type|pluginspage],script[type|src|async]",
		//height: 200,
		menubar:true,
		//statusbar: false,
		menu: {
			file: {title: 'File', items: 'newdocument'},
			edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall'},
			insert: {title: 'Insert', items: 'link media | template hr'},
			view: {title: 'View', items: 'visualaid'},
			format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
			table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
			tools: {title: 'Tools', items: 'spellchecker code'}
		},

		subfolder : "",
		plugins: [
			 "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
			 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			 "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
	   ],
	   image_advtab: true,
	   filemanager_title:"MeroCRM Filemanager",
		external_filemanager_path:"/js/tinymce/plugins/filemanager/",
		external_plugins: { "filemanager" : "/js/tinymce/plugins/filemanager/plugin.min.js"},
	  // content_css: "css/content.css",
	   toolbar: "forecolor | bold italic | blockquote alignleft aligncenter alignright alignjustify | bullist numlist | link image |" + " code"
	 });

</script>
<div class="modal-header bg-blue">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <span class="lead">Compose New Email</span>
</div>
<div class="modal-body">
  @if($error)
  	<div>{{{ $error }}}</div>
  @else
      {!! Form::open( ['url' => $modal_route, 'id' => 'mail-modal', 'class'=>'form-horizontal','enctype' => 'multipart/form-data'] ) !!}
      <div class="mail-body">
        
        <div class="box-body">
          <div class="form-group">
            <label class="control-label">From: </label>
            <div class="controls">
            <input placeholder="From:" class="form-control" name="mail_from" value="{!! env('APP_EMAIL') !!}" required>
            </div>
          </div>
          <div class="form-group">
            <label>To: </label>
            <input placeholder="To:" class="form-control" name="mail_to" value="{!! $to_email !!}" required>
          </div>
          <div class="form-group">
          	<label>Subject: </label>
            <input placeholder="Subject:" name="subject" class="form-control" value="{!! env('APP_COMPANY') !!} HR Letter for you" required>
          </div>
          <div class="form-group">
            <textarea rows="3" name="message" class="form-control" id="compose-textarea" placeholder="Type your message.">{!! $hrletter->body !!}</textarea>
          </div>
        </div>
      </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('general.button.cancel') }}</button>
          <button type="submit" class="btn btn-primary">{{ trans('general.button.send') }}</button>
        </div>
      {!! Form::close() !!}
  @endif
</div>

