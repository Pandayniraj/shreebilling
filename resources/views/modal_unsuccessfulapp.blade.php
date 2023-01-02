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
<div class="modal-header  bg-red">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title">{{ $modal_title }}</h4>
</div>
<div class="modal-body"> 
  @if($error)
  	<div>{{{ $error }}}</div>
  @else
      {!! Form::open( ['url' => $modal_route, 'id' => 'mail-modal', 'enctype' => 'multipart/form-data'] ) !!}
      <div class="mail-body">
        <h2 class="box-title">Compose New Message</h2>  
        <div class="box-body">
          <div class="form-group">
            <label>From: </label>
            <input placeholder="From:" class="form-control" name="mail_from" value="{!! \Auth::user()->email !!}" required>
          </div>
          <div class="form-group">
            <label>To: </label>
            <input placeholder="To:" class="form-control" name="mail_to" value="{!! $to_email !!}" required>
          </div>
          <div class="form-group">
          	<label>Subject: </label>
            <input placeholder="Subject:" name="subject" class="form-control" value="Re: Gentle Reminder" required>
          </div>
          <div class="form-group">        
            <textarea rows="12" name="message" class="form-control" id="compose-textarea" placeholder="Type your message.">
            	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<!--[if gte mso 9]><xml>
<o:OfficeDocumentSettings>
<o:AllowPNG/>
<o:PixelsPerInch>96</o:PixelsPerInch>
</o:OfficeDocumentSettings>
</xml><![endif]-->
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0 " />
<meta name="format-detection" content="telephone=no"/>
<!--[if !mso]><!-->
<link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,700,700i,900,900i" rel="stylesheet" />
<!--<![endif]-->
<style type="text/css">
body {
  margin: 0;
  padding: 0;
  -webkit-text-size-adjust: 100% !important;
  -ms-text-size-adjust: 100% !important;
  -webkit-font-smoothing: antialiased !important;
}
img {
  border: 0 !important;
  outline: none !important;
}
p {
  Margin: 0px !important;
  Padding: 0px !important;
}
table {
  border-collapse: collapse;
  mso-table-lspace: 0px;
  mso-table-rspace: 0px;
}
td, a, span {
  border-collapse: collapse;
  mso-line-height-rule: exactly;
}
.ExternalClass * {
  line-height: 100%;
}
.em_blue a {text-decoration:none; color:#264780;}
.em_grey a {text-decoration:none; color:#434343;}
.em_white a {text-decoration:none; color:#ffffff;}

@media only screen and (min-width:481px) and (max-width:649px) {
.em_main_table {width: 100% !important;}
.em_wrapper{width: 100% !important;}
.em_hide{display:none !important;}
.em_aside10{padding:0px 10px !important;}
.em_h20{height:20px !important; font-size: 1px!important; line-height: 1px!important;}
.em_h10{height:10px !important; font-size: 1px!important; line-height: 1px!important;}
.em_aside5{padding:0px 10px !important;}
.em_ptop2 { padding-top:8px !important; }
}
@media only screen and (min-width:375px) and (max-width:480px) {
.em_main_table {width: 100% !important;}
.em_wrapper{width: 100% !important;}
.em_hide{display:none !important;}
.em_aside10{padding:0px 10px !important;}
.em_aside5{padding:0px 8px !important;}
.em_h20{height:20px !important; font-size: 1px!important; line-height: 1px!important;}
.em_h10{height:10px !important; font-size: 1px!important; line-height: 1px!important;}
.em_font_11 {font-size: 12px !important;}
.em_font_22 {font-size: 22px !important; line-height:25px !important;}
.em_w5 { width:7px !important; }
.em_w150 { width:150px !important; height:auto !important; }
.em_ptop2 { padding-top:8px !important; }
u + .em_body .em_full_wrap { width:100% !important; width:100vw !important;}
}
@media only screen and (max-width:374px) {
.em_main_table {width: 100% !important;}
.em_wrapper{width: 100% !important;}
.em_hide{display:none !important;}
.em_aside10{padding:0px 10px !important;}
.em_aside5{padding:0px 8px !important;}
.em_h20{height:20px !important; font-size: 1px!important; line-height: 1px!important;}
.em_h10{height:10px !important; font-size: 1px!important; line-height: 1px!important;}
.em_font_11 {font-size: 11px !important;}
.em_font_22 {font-size: 22px !important; line-height:25px !important;}
.em_w5 { width:5px !important; }
.em_w150 { width:150px !important; height:auto !important; }
.em_ptop2 { padding-top:8px !important; }
u + .em_body .em_full_wrap { width:100% !important; width:100vw !important;}
}
</style>

</head>
<body class="em_body" style="margin:0px auto; padding:0px;" bgcolor="#efefef">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="em_full_wrap" align="center"  bgcolor="#efefef">
    <tr>
      <td align="center" valign="top"><table align="center" width="650" border="0" cellspacing="0" cellpadding="0" class="em_main_table" style="width:650px; table-layout:fixed;">
          <tr>
            <td align="center" valign="top" style="padding:0 25px;" class="em_aside10"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td height="25" style="height:25px;" class="em_h20">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" valign="top">{{ env('APP_COMPANY') }}</td>
              </tr>
              <tr>
                <td height="28" style="height:28px;" class="em_h20">&nbsp;</td>
              </tr>
            </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="em_full_wrap" align="center" bgcolor="#efefef">
    <tr>
      <td align="center" valign="top" class="em_aside5"><table align="center" width="650" border="0" cellspacing="0" cellpadding="0" class="em_main_table" style="width:650px; table-layout:fixed;">
          <tr>
            <td align="center" valign="top" style="padding:0 25px; background-color:#ffffff;" class="em_aside10"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td height="45" style="height:45px;" class="em_h20">&nbsp;</td>
              </tr>
              <tr>
                <td class="em_blue em_font_22" align="center" valign="top" style="font-family: Arial, sans-serif; font-size: 26px; line-height: 29px; color:#264780; font-weight:bold;">Forgot your {{ $Lead->course->name }}?</td>
              </tr>
              <tr>
                <td height="14" style="height:14px; font-size:0px; line-height:0px;">&nbsp;</td>
              </tr>
              <tr>
                <td class="em_grey" align="center" valign="top" style="font-family: Arial, sans-serif; font-size: 16px; line-height: 26px; color:#434343;">I understand your position, but I wouldn’t follow up with you if I didn’t strongly think that {{ env('APP_COMPANY') }} can help your organization solve challenge by {{ $Lead->course->name }}.

</td>
              </tr>
              <tr>
                <td height="26" style="height:26px;" class="em_h20">&nbsp;</td>
              </tr>
              
              <tr>
                <td height="25" style="height:25px;" class="em_h20">&nbsp;</td>
              </tr>
              <tr>
                <td class="em_grey" align="center" valign="top" style="font-family: Arial, sans-serif; font-size: 16px; line-height: 26px; color:#434343;">Let me know if you want me to jump on a call so I can walk you through what we do.</td>
              </tr>
              <tr>
                <td height="44" style="height:44px;" class="em_h20">&nbsp;</td>
              </tr>
            </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="em_full_wrap" align="center" bgcolor="#efefef">
    <tr>
      <td align="center" valign="top"><table align="center" width="650" border="0" cellspacing="0" cellpadding="0" class="em_main_table" style="width:650px; table-layout:fixed;">
          <tr>
            <td align="center" valign="top" style="padding:0 25px;" class="em_aside10"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td height="40" style="height:40px;" class="em_h20">&nbsp;</td>
              </tr>
              
              <tr>
                <td height="16" style="height:16px; font-size:1px; line-height:1px; height:16px;">&nbsp;</td>
              </tr>
              <tr>
                <td class="em_grey" align="center" valign="top" style="font-family: Arial, sans-serif; font-size: 15px; line-height: 18px; color:#434343; font-weight:bold;">Problems or questions?</td>
              </tr>
              <tr>
                <td height="10" style="height:10px; font-size:1px; line-height:1px;">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" valign="top" style="font-size:0px; line-height:0px;"><table border="0" cellspacing="0" cellpadding="0" align="center">
                  <tr>
                    <td width="15" align="left" valign="middle" style="font-size:0px; line-height:0px; width:15px;"><a href="mailto:{{ env('APP_EMAIL') }}" style="text-decoration:none;"><img src="/assets/pilot/images/templates/email_img.png" width="15" height="12" alt="" border="0" style="display:block; max-width:15px;" /></a></td>
                    <td width="9" style="width:9px; font-size:0px; line-height:0px;" class="em_w5"><img src="/assets/pilot/images/templates/spacer.gif" width="1" height="1" alt="" border="0" style="display:block;" /></td>
                    <td class="em_grey em_font_11" align="left" valign="middle" style="font-family: Arial, sans-serif; font-size: 13px; line-height: 15px; color:#434343;"><a href="mailto:{{ env('APP_EMAIL') }}" style="text-decoration:none; color:#434343;">{{ env('APP_EMAIL') }}</a> </td>

                    <td class="em_grey em_font_11" align="left" valign="middle" style="font-family: Arial, sans-serif; font-size: 13px; line-height: 15px; color:#434343;"><a style="text-decoration:none; color:#434343;"> &nbsp; Phone: {{ env('APP_PHONE1') }} Whatsapp: {{ env('APP_WHATSAPP') }}
                    </a> 
                     
                    </td>
                    
                  </tr>
                </table>
                </td>
              </tr>
               <tr>
                <td height="9" style="font-size:0px; line-height:0px; height:9px;" class="em_h10"><img src="/assets/pilot/images/templates/spacer.gif" width="1" height="1" alt="" border="0" style="display:block;" /></td>
              </tr>
               <tr>
                <td align="center" valign="top"><table border="0" cellspacing="0" cellpadding="0" align="center">
                  <tr>
                    <td width="12" align="left" valign="middle" style="font-size:0px; line-height:0px; width:12px;"><a href="#" target="_blank" style="text-decoration:none;"><img src="/assets/pilot/images/templates/img_1.png" width="12" height="16" alt="" border="0" style="display:block; max-width:12px;" /></a></td>
                    <td width="7" style="width:7px; font-size:0px; line-height:0px;" class="em_w5">&nbsp;</td>
                    <td class="em_grey em_font_11" align="left" valign="middle" style="font-family: Arial, sans-serif; font-size: 13px; line-height: 15px; color:#434343;"><a href="#" target="_blank" style="text-decoration:none; color:#434343;">{{ env('APP_COMPANY') }}</a> &bull; {{ env('APP_ADDRESS1') }} &bull; {{ env('APP_ADDRESS2') }}</td>
                  </tr>
                </table>
                </td>
              </tr>
              <tr>
                <td height="35" style="height:35px;" class="em_h20">&nbsp;</td>
              </tr>
            </table>
            </td>
          </tr>
           <tr>
            <td height="1" bgcolor="#dadada" style="font-size:0px; line-height:0px; height:1px;"><img src="/assets/pilot/images/templates/spacer.gif" width="1" height="1" alt="" border="0" style="display:block;" /></td>
          </tr>
           <tr>
            <td align="center" valign="top" style="padding:0 25px;" class="em_aside10"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td height="16" style="font-size:0px; line-height:0px; height:16px;">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" valign="top"><table border="0" cellspacing="0" cellpadding="0" align="left" class="em_wrapper">
                  <tr>
                    <td class="em_grey" align="center" valign="middle" style="font-family: Arial, sans-serif; font-size: 11px; line-height: 16px; color:#434343;">&copy; {{ env('APP_COMPANY') }} 2019  &nbsp;|&nbsp; </td>
                  </tr>
                </table>
                </td>
              </tr>
              <tr>
                <td height="16" style="font-size:0px; line-height:0px; height:16px;">&nbsp;</td>
              </tr>
            </table>
            </td>
          </tr>
          <tr>
            <td class="em_hide" style="line-height:1px;min-width:650px;background-color:#efefef;"><img alt="" src="/assets/pilot/images/templates/spacer.gif" height="1" width="650" style="max-height:1px; min-height:1px; display:block; width:650px; min-width:650px;" border="0" /></td>
          </tr>
        </table>
      </td>
    </tr>
</table>
</body>
</html>
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
          <button type="submit" class="btn btn-danger">Send</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('general.button.cancel') }}</button>
        </div>
      {!! Form::close() !!}
  @endif
</div>