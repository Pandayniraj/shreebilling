@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<style>
	select { width:200px !important; }
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
		height: 200,
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

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {{$page_title ?? "Page Title"}}
                <small>Total Valid Email Address {{$valid_addresses}} </small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body">
                {!! Form::open( ['route' => 'admin.mail.post-send-bulk-email-all', 'id' => 'form_edit_lead', 'enctype'=>"multipart/form-data"] ) !!}   

                <div class="content col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('title', 'Campaign Title') !!}
                                <input placeholder="Title" name="title" class="form-control" value="" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('subject', 'Email Subject') !!}
                                <input placeholder="Subject:" name="subject" class="form-control" value="" required>
                            </div>a
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('message', 'Email Message') !!}
                                <textarea rows="4" name="message" class="form-control" id="compose-textarea" placeholder="Type your message.">
                                <div dir="ltr">
                                	<p>&nbsp;</p>
                                    <p>Best Regards,</p>
                                	<div style="font-size:12px">
                                        <b style="font-size:small;font-family:tahoma,sans-serif">
                                            <font color="#ff0000">{!! env('APP_OWNER') !!}</font>
                                        </b>
                                    </div><p>&nbsp;</p>
                                    <div style="font-size:12px">
                                    	<img src="{{env('APP_URL')}}/{{ '/org/'.$organization->logo }}" style="font-size:small" width="20%" class="CToWUd">
                                    </div>
                                    <div style="font-size:12px">
                                    	<font color="#666666">
                                        	<span style="font-family:tahoma,sans-serif;font-size:small">{!! env('APP_ADDRESS1') !!}</span></font>
                                    </div>
                                    <div style="font-size:12px">
                                    	<font color="#666666">
                                        	<span style="font-family:tahoma,sans-serif;font-size:small">{!! env('APP_ADDRESS2') !!}</span></font>
                                    </div>
                                    <div style="font-size:12px">
                                    	<font color="#666666">
                                        	<span style="font-family:tahoma,sans-serif;font-size:small">Phone : {!! env('APP_PHONE1') !!}
</span></font>
                                    </div>
                                    <div style="font-size:12px">
                                    	<font color="#666666">
                                        	<span style="font-family:tahoma,sans-serif;font-size:small">Mobile : {!! env('APP_PHONE2') !!}
</span></font>
                                    </div>
                                    <div style="font-size:12px">
                                    	<font color="#666666">
                                        	<span style="font-family:tahoma,sans-serif;font-size:small">Web: <a href="{!! env('APP_URL') !!}">{!! env('APP_URL') !!}</a></span></font>
                                    </div>
                                    <div style="font-size:12px">
                                    	<font color="#666666">
                                        	<span style="font-family:tahoma,sans-serif;font-size:small">Email: <a href="mailto:{!! env('APP_EMAIL') !!}">{!! env('APP_EMAIL') !!}</a></span></font>
                                    </div>
                                    
                                 </div>
                                
                                </textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-12" style="">
                            <div class="form-group">
                                {!! Form::label('attachment', 'Attachment') !!}
                                <input type="file" name="attachment">
                            </div>
                            <p class="help-block">Max. 32MB</p>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit('Send Email', ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                            </div>
                        </div>
                    </div>                         
                </div>
                {!! Form::close() !!}


                 <div class="col-md-3">
                          <div class="callout callout-info">
        <h4>Mail Stat!</h4>

        <p>
            Total valid mail addresses: <strong>{{ $valid_addresses }} </strong><br/>
            Total Leads: <strong>{{ $total_leads }} </strong>

        </p>
      </div>
                       

                        <div class="callout callout-default">
        <h4>Customers Type!</h4>

        <p>You are currently sending mails to leads only, you can configure if you want to send mails for post sales clients.</p>
      </div>
                        </div>
            </div>
        </div><!-- /.box-body -->

    </div><!-- /.col -->



</div><!-- /.row -->
@endsection

@section('body_bottom')
 
@endsection
