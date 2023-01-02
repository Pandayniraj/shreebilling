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
<style>
  .mce-tinymce { position: static !important; }
</style>
<script>
    tinymce.init({
    relative_urls: false,
    remove_script_host: false,
    selector: "textarea#body",
    theme: "modern",
    skin: 'light',
    media_strict: false,
    media_filter_html: false,
    extended_valid_elements: "i[class],div[class|style],iframe[src|width|height|name|align], embed[width|height|name|flashvars|src|bgcolor|align|play|loop|quality|allowscriptaccess|type|pluginspage],script[type|src|async]",
    height: 500,
    menubar:false,
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
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body">
                {!! Form::open( ['route' => 'admin.mail.post-send-bulk-email', 'id' => 'form_edit_lead'] ) !!}        
                <div class="content col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                               <label> Mail Campaign Title</label>
                                <input placeholder="Title" name="title" class="form-control" value="" required>
                            </div>
                        </div>


                        
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for='product_id'>{{  trans('admin/leads/general.columns.course_name') }}
                               
                              </label>
                               <a href="javascript::void()" title="Download Lead" class="btn btn-success btn-xs"  onclick='downloadExcel()'>
                                  Download Lead  <i class="fa fa-download "></i> </a>
                                {!! Form::select('course_no', $courses, null, ['class' => 'form-control','id'=>'course_no']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('status_id', trans('admin/leads/general.columns.status_id')) !!}
                                {!! Form::select('status_no', $lead_status, 1, ['class' => 'form-control','id'=>'status_no']) !!}
                            </div>
                        </div>


                            <div class="col-md-4">
                          <div class="form-group">
                              <label for="template"> Template </label>

                              <select class="form-control template bg-blue" name="template" id="template">
                              <option class="input" value="">Select Template</option>
                              <option class="input" value="template1" @if($proposal && $proposal->template == "template1"){{ 'selected="selected"' }}@endif>Mail Template 1 </option>
                              <option class="input" value="template2" @if($proposal && $proposal->template == "template2"){{ 'selected="selected"' }}@endif>Mail Template 2 </option>
                              <option class="input" value="template3" @if($proposal && $proposal->template == "template3"){{ 'selected="selected"' }}@endif>Mail Template 3 </option>

                              </select>
                            
                          </div>
                        </div>
                    

                      <div class="row">
                          <div class="col-md-12">
                            <span style="text-align: center;font-size: 16.5px" class="bg-info" id="ajax_lead_count_status"></span>
                          </div>
                      </div>
                        
                      <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('subject', 'Mail Subject') !!}
                                <input placeholder="Subject:" name="subject" class="form-control" value="" required>
                            </div>
                      </div>

                      <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('message', 'Mail Message') !!}
                                {!! Form::textarea('body', null, ['class'=>'form-control body','placeholder'=>'Select Template', 'rows'=>'40', 'id' => 'body']) !!}
                            </div>
                      </div>



                        
                        <!-- <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('message', 'Message') !!}
                                <textarea rows="6" name="message" class="form-control" id="compose-textarea" placeholder="Type your message.">
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
                        </div> -->



                        
                        <div class="col-md-12" style="display:none;">
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
                  <div class="col-md-3">
                          <div class="callout callout-red">
        <h4>Email Gateway Tip!</h4>

        <p>make sure that you are connected to gateway like mailgun, mandrill, ses etc before sending bulk mail, there is no limit in sending emails</p>
      </div>
                       

                        <div class="callout callout-danger">
        <h4>Customers Type!</h4>

        <p>You are currently sending mails to leads only, you can configure if you want to send mails for post sales clients.</p>
      </div>
                        </div>

                         </div>
                {!! Form::close() !!}
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>

<script type="text/javascript">
    $(document).ready(function() {

      $('#template').on('change', function() {
        if($(this).val() != '')
        {
          var formData = new FormData();
          formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
          formData.append("template", $(this).val());

          $.ajax({
            type: 'POST',
            url: '/admin/post-send-bulk-email/loadTemplate',
            data: formData,
            contentType: false,
            processData: false, 
            success: function(response)
            {
                if(response.success == '1')
                  tinyMCE.get('body').setContent(response.data);
                else
                    alert('Sorry! No Template found. Select another Template.');
            }
          });
        }
        else
          tinyMCE.get('body').setContent(''); 
      });


       $('#course_no').on('change', function() {
            var course_no =  $('#course_no').val();
              var status_no =  $('#status_no').val();
            if($(this).val() != '')
            {
                $.ajax({
                    url: "/admin/email/getLeadsTotal",
                    data: { course_no : course_no , status_no : status_no },
                    dataType: "json",
                    success: function( data ) {
                        var result = data.data;
                        $('#ajax_lead_count_status').html('('+result+' leads.)');
                        $('#ajax_lead_count_status').css('display', 'block');
                        $('#ajax_lead_count_status').delay(3000).fadeOut('slow');
                    }
                });
            }
        });

       $('#status_no').on('change', function() {
              var course_no =  $('#course_no').val();
              var status_no =  $('#status_no').val();
            if($(this).val() != '')
            {
                $.ajax({
                    url: "/admin/email/getLeadsTotal",
                    data: { course_no : course_no , status_no : status_no },
                    dataType: "json",
                    success: function( data ) {
                        var result = data.data;
                        $('#ajax_lead_count_status').html('('+result+' leads.)');
                        $('#ajax_lead_count_status').css('display', 'block');
                        $('#ajax_lead_count_status').delay(6000).fadeOut('slow');
                    }

                });
            }
        });

    });
    function downloadExcel(){
      var course_no =  $('#course_no').val();
      var status_no =  $('#status_no').val();
      location.href = `/admin/email/downloadExcel?course_no=${course_no}&status_no=${status_no}`;

    }
</script>

            </div>
        </div><!-- /.box-body -->
    </div><!-- /.col -->

</div><!-- /.row -->
@endsection

@section('body_bottom')
 
@endsection
