<style>
.modal-mail {
    margin: 30px auto !important;
    width: 600px !important;
}
.modal-mail {
    margin: 10px;
    position: relative;
    width: auto;
}
h2 { font-size:23px; margin-top:0 !important; margin-left:10px;}
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
	   filemanager_title:"eWcms Enterprise Filemanager",
		external_filemanager_path:"/js/tinymce/plugins/filemanager/",
		external_plugins: { "filemanager" : "/js/tinymce/plugins/filemanager/plugin.min.js"},
	  // content_css: "css/content.css",
	   toolbar: "forecolor | bold italic | blockquote alignleft aligncenter alignright alignjustify | bullist numlist | link image |" + " code"
	 });

</script>
<div class="modal-header bg-orange">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title">{{ $modal_title }}</h4>
</div>
<div class="modal-body"> 
  @if($error)
  	<div>{{{ $error }}}</div>
  @else
      {!! Form::open( ['url' => $modal_route, 'id' => 'mail-modal', 'enctype' => 'multipart/form-data'] ) !!}
      <div class="mail-body">
        <h2 class="box-title">Compose New Proposal</h2>
        <div class="box-body">
          <div class="form-group">
            <label>From: </label>
            <input placeholder="From:" class="form-control" name="mail_from" value="{!! env('APP_EMAIL') !!}" required>
          </div>
          <div class="form-group">
            <label>To: </label>
            <input placeholder="To:" class="form-control" name="mail_to" value="{!! $to_email !!}" required>
          </div>
          <div class="form-group">
          	<label>Subject: </label>
            <input placeholder="Subject:" name="subject" class="form-control" value="Thanks from {{ env('APP_COMPANY') }}" required>
          </div>
          <div class="form-group">        
            <textarea rows="7" name="message" class="form-control" id="compose-textarea" placeholder="Type your message.">
              Dear {{ $Lead->name}}<br/><br/>

              
              Thanks for choosing <br/>
              <strong>{{ env('APP_COMPANY') }}.</strong><br/>
                    Phone: {{ env('APP_PHONE1') }}<br/>
                    Whatsapp: {{ env('APP_WHATSAPP') }}<br/>
                    Email: {{ env('APP_EMAIL') }}<br/>
                    Web: <a href="{{ env('APP_WEBSITE') }}">{{ env('APP_WEBSITE') }}</a><br/>
                    {{ env('APP_ADDRESS1') }}<br/>
                    {{ env('APP_ADDRESS2') }}<br/>
            </textarea>        
          </div>
          <div class="form-group">
            <div class="btn btn-default btn-file"> <i class="fa fa-paperclip"></i> Attachment
              <input type="file" name="attachment">
            </div>
            <p class="help-block">Max. 32MB</p>
          </div>          
        </div>
      </div>
        <div class="modal-footer">
          <button type="submit" class="btn bg-orange">Send</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      {!! Form::close() !!}
  @endif
</div>