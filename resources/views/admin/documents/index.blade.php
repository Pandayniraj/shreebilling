@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<style type="text/css">
    

.file-box {
  float: left;
  width: 220px;
  min-height: 220px;
}
.file-manager h5 {
  text-transform: uppercase;
}
.file-manager {
  list-style: none outside none;
  margin: 0;
  padding: 0;
}
.folder-list li a {
  color: #666666;
  display: block;
  padding: 5px 0;
}
.folder-list li {
  border-bottom: 1px solid #e7eaec;
  display: block;
}
.folder-list li i {
  margin-right: 8px;
  color: #3d4d5d;
}
.category-list li a {
  color: #666666;
  display: block;
  padding: 5px 0;
}
.category-list li {
  display: block;
}
.category-list li i {
  margin-right: 8px;
  color: #3d4d5d;
}
.category-list li a .text-navy {
  color: #1ab394;
}
.category-list li a .text-primary {
  color: #1c84c6;
}
.category-list li a .text-info {
  color: #23c6c8;
}
.category-list li a .text-danger {
  color: #EF5352;
}
.category-list li a .text-warning {
  color: #F8AC59;
}
.file-manager h5.tag-title {
  margin-top: 20px;
}
.tag-list li {
  float: left;
}
.tag-list li a {
  font-size: 10px;
  background-color: #f3f3f4;
  padding: 5px 12px;
  color: inherit;
  border-radius: 2px;
  border: 1px solid #e7eaec;
  margin-right: 5px;
  margin-top: 5px;
  display: block;
}
.file {
  border: 1px solid #e7eaec;
  padding: 0;
  background-color: #ffffff;
  position: relative;
  margin-bottom: 20px;
  margin-right: 20px;
}
.file-manager .hr-line-dashed {
  margin: 15px 0;
}
.file .icon,
.file .image {
  height: 100px;
  overflow: hidden;
}
.file .icon {
  padding: 15px 10px;
  text-align: center;
}
.file-control {
  color: inherit;
  font-size: 11px;
  margin-right: 10px;
}
.file-control.active {
  text-decoration: underline;
}
.file .icon i {
  font-size: 70px;
  color: #dadada;
}
.file .file-name {
  padding: 10px;
  background-color: #f8f8f8;
  border-top: 1px solid #e7eaec;
}
.file-name small {
  color: #676a6c;
}
ul.tag-list li {
  list-style: none;
}
.corner {
  position: absolute;
  display: inline-block;
  width: 0;
  height: 0;
  line-height: 0;
  border: 0.6em solid transparent;
  border-right: 0.6em solid #f1f1f1;
  border-bottom: 0.6em solid #f1f1f1;
  right: 0em;
  bottom: 0em;
}
a.compose-mail {
  padding: 8px 10px;
}
.mail-search {
  max-width: 300px;
}
.ibox {
  clear: both;
  margin-bottom: 25px;
  margin-top: 0;
  padding: 0;
}
.ibox.collapsed .ibox-content {
  display: none;
}
.ibox.collapsed .fa.fa-chevron-up:before {
  content: "\f078";
}
.ibox.collapsed .fa.fa-chevron-down:before {
  content: "\f077";
}
.ibox:after,
.ibox:before {
  display: table;
}
.ibox-title {
  -moz-border-bottom-colors: none;
  -moz-border-left-colors: none;
  -moz-border-right-colors: none;
  -moz-border-top-colors: none;
  background-color: #ffffff;
  border-color: #e7eaec;
  border-image: none;
  border-style: solid solid none;
  border-width: 3px 0 0;
  color: inherit;
  margin-bottom: 0;
  padding: 14px 15px 7px;
  min-height: 48px;
}
.ibox-content {
  background-color: #ffffff;
  color: inherit;
  padding: 15px 20px 20px 20px;
  border-color: #e7eaec;
  border-image: none;
  border-style: solid solid none;
  border-width: 1px 0;
}
.ibox-footer {
  color: inherit;
  border-top: 1px solid #e7eaec;
  font-size: 90%;
  background: #ffffff;
  padding: 10px 15px;
}
a:hover{
text-decoration:none;    
}

</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ trans('/admin/documents/general.page.index.table-title') }}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        
<div class="">
<div class="row">
    <div class="col-md-3">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="file-manager">
                   
                    {{-- <a  href="{!! route('admin.documents.create') !!}" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal_dialog">{{ trans('/admin/documents/general.button.upload_file') }}</a> --}}

                     <div class="btn-group" style="width: 100%;">
                      <button type="button" class="btn btn-primary btn-sm dropdown-toggle btn-block" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ trans('/admin/documents/general.button.upload_file') }} <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                      <li><a href="{!! route('admin.documents.create') !!}?type=docs" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-cloud-upload"></i>Upload</a></li>
                      <li><a href="javascript::void()" id='createNotes'><i class="fa  fa-sticky-note"></i>Create Notes</a></li> 
                      <li><a href="javascript::void()" id='createDocuments'><i class="fa  fa-file-word-o"></i>Create Document</a></li> 
                    </ul>
                    </div>


                    <hr>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="{{ trans('/admin/documents/general.placeholder.search_doc') }}.." id='search_inp'>
                      <div class="input-group-btn">
                        <button class="btn btn-default" type="button" id='search'>
                          <i class="glyphicon glyphicon-search"></i>
                        </button>
                      </div>
                    </div><hr>
                    <div class="hr-line-dashed"></div>
                    <h5>{{ trans('/admin/documents/general.columns.folder') }}</h5>
                    <ul class="folder-list" style="padding: 0">
                      <li @if(!\Request::get('folder'))class="bg-danger" @endif><a href="/admin/documents/"><i class="fa fa-home"></i>{{ trans('/admin/documents/general.columns.home') }}</a></li>
                      @foreach($folders as $f)
                      @if(\Request::get('folder') == $f->id) 
                        <li class="bg-danger"><a href="/admin/documents/?folder={{ $f->id }}"><i class="fa fa-folder"></i>{{ $f->name }}</a></li>
                      @else
                        <li><a href="/admin/documents/?folder={{ $f->id }}"><i class="fa fa-folder"></i>{{ $f->name }}</a></li>
                      @endif
                      @endforeach
                    </ul>
                    <h5 class="tag-title">{{ trans('/admin/documents/general.columns.category') }}</h5>
                    <ul class="tag-list" style="padding: 0">
                      @foreach($category as $c)
                      @if($c->category)
                        <li class="change-category" data-id='{{ $c->category->id }}' ><a href="#">{{ ucfirst($c->category->name) }}</a></li>
                      @endif
                      @endforeach
                    </ul>
                    <h5><button class="btn btn-xs btn-primary btn-block">{{ trans('/admin/documents/general.columns.doc_type') }}</button></h5>
                      <div class="radio">
                        <label><input type="radio" name="doctype" value="mydoc" checked>{{ trans('/admin/documents/general.columns.my_document') }}</label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" name="doctype" value="shared" 
                          @if(\Request::get('type') && \Request::get('type') == 'shared') checked="" 
                          @endif 
                          >
                        {{ trans('/admin/documents/general.columns.shared_document') }}</label>
                      </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <?php 
        $mime_images = ['png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml'];

        $mime_video =[
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

        ];
        $mime_audio = [ 'mp3' => 'audio/mpeg','audio/x-m4a'];

        $mime_office = [ 'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'ppt' => 'application/vnd.ms-powerpoint'];

        $mime_doc = ['application/msword'];
        $mime_excel = ['application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $mime_pdf = ['application/pdf','image/vnd.adobe.photoshop','application/postscript','application/postscript','application/postscript']; 

    ?>
    <div class="col-md-9 animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                @foreach($documents as $key=>$doc)
                <div class="file-box   @if($doc->doc_type == 'note') contextmenu-notes @elseif($doc->doc_type == 'noteDoc') contextmenu-notes-doc @else contextmenu @endif" data-id='{{ $doc->id }}'>
                    <a @if($doc->doc_type == 'note') class="triggercontextmenu"  href="javascript::void()" @elseif($doc->doc_type == 'noteDoc')  class="triggercontextmenu-doc"  href="javascript::void()" @else download='{{$doc->doc_name}}'  href="/documents/{{ $doc->file }}"  @endif >
                        <div class="file">
                            <span class="corner"></span>
                            <?php $filetype = mime_content_type(public_path().'/documents/'.$doc->file) ?>
                            @if(in_array($filetype, $mime_images))
                              <div class="image">
                                <img alt="image" class="img-responsive" src="/documents/{{ $doc->file }}" >
                              </div>
                            @else
                              <div class="icon">
                                @if($doc->doc_type == 'note')
                                  <i class="fa  fa-file-text"></i>

                                @elseif($doc->doc_type == 'noteDoc')
                                     <i class="fa  fa-file-word-o"></i>

                                @else
                                  @if(in_array($filetype, $mime_office))
                                    <i class="fa fa-bar-chart-o"></i>
                                  @elseif(in_array($filetype, $mime_audio))
                                    <i class="fa  fa-music"></i>
                                  @elseif(in_array($filetype, $mime_video))
                                    <i class="fa  fa-film"></i>
                                  @elseif(in_array($filetype, $mime_doc))
                                    <i class="fa  fa-file-word-o"></i>
                                  @elseif(in_array($filetype, $mime_excel))
                                   <i class="fa  fa-file-excel-o"></i>
                                  @elseif(in_array($filetype, $mime_pdf))
                                    <i class="fa fa-file-pdf-o"></i>
                                  @else
                                    <i class="fa  fa-file"></i>
                                  @endif
                                @endif  
                              </div>
                            @endif
                            <div class="file-name">
                                 <span class="btn btn-xs {{ $doc->category->color ?? '' }}" style="display: block;">{{ $doc->category->name ?? '' }}</span>
                                @if(strlen($doc->doc_name) < 15){{ $doc->doc_name }}@else {{ substr($doc->doc_name,0,15) }}.. @endif
                                <br>
                                <small>Added: {{ date('dS M Y',strtotime($doc->created_at)) }}@if($doc->doc_type == 'docs')
                          <span class="pull-right">
                        <?php 
                            $filesize =filesize(public_path().'/documents/'.$doc->file);
                          ?>
                          @if(($filesize * 0.000001) > 0.1))
                            {{ ($filesize * 0.000001) }} MB 
                          @else

                          {{ round(filesize(public_path().'/documents/'.$doc->file)/1024)  }} KB
                          @endif
                        </span>
                           @endif</small>
                                <br>
                                @if(\Request::get('type') && \Request::get('type') == 'shared')
                                  <small>Shared By : {{$doc->user->username}}</small>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach

            </div>
        </div>
        </div>
    </div>
</div>

    </div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
<link href="/contextmenu/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
<script src="/contextmenu/jquery.contextMenu.js" type="text/javascript"></script>
<script src="/contextmenu/jquery.ui.position.min.js" type="text/javascript"></script>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<link href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />

<script src='{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}'></script>
<script type="text/javascript">
  
   $(document).on('hidden.bs.modal', '#modal_dialog' , function(e){
        $('#modal_dialog .modal-content').html('');    
   });
    $(document).on('hidden.bs.modal', '#notesModal' , function(e){
        
        $('#notesModal .modal-content').html(''); 

   });

function getText(n) {
    var rv = '';;
    if (n.nodeType == 3) {
        rv = n.nodeValue;
    } else {
        for (var i = 0; i < n.childNodes.length; i++) {
            rv += getText(n.childNodes[i]);
        }
        var d = getComputedStyle(n).getPropertyValue('display');
        if (d.match(/^block/) || d.match(/list/) || n.tagName == 'BR') {
            rv += "\n";
        }
    }
    
    return rv;

};
$('#createNotes').click(function(){
  let url = `/admin/documents/create?type=note`;
  $(`#notesModal .modal-content`).load(url,function(result){
      $('#notesModal').modal({show:true,keyboard:false,backdrop:'static'}); 
  });

});

$('#createDocuments').click(function(){
  let url = `/admin/documents/create?type=noteDoc`;
  $(`#notesModal .modal-content`).load(url,function(result){
      $('#notesModal').modal({show:true,keyboard:false,backdrop:'static'}); 

  });

})
function downloadText(id){


  $.get(`/admin/documents/getInfo?id=${id}`,function(response){

    let text = response.doc_desc;

    let filename = response.doc_name+'.txt';

  var element = document.createElement('a');

  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);

  element.style.display = 'none';

  document.body.appendChild(element);

  element.click();

  document.body.removeChild(element);


  }).fail(function(){

    alert("Failed to download file");
  });


}


function downloadWord(id){

  $.get(`/admin/documents/getInfo?id=${id}`,function(response){

    let text = response.doc_desc;
    console.log(text);

    Export2Doc(text,response.doc_name);
  }).fail(function(){

    alert("Failed to download file");
  });


}
  function callbackMenu(key,id){

  

    if(key == 'delete'){
        let url = `/admin/documents/${id}/confirm-delete`;
        $(`#modal_dialog .modal-content`).load(url,function(result){
            $('#modal_dialog').modal({show:true});
        });
     
      return
    }

    else if(key == 'edit'){
        let url = `/admin/documents/${id}/edit`;
        $(`#modal_dialog .modal-content`).load(url,function(result){
            $('#modal_dialog').modal({show:true});
        });
     
      return
    }

    else if(key =='download' ){
      location.href = $(this).find('a').attr("href");
      return 
    }

    else if(key == 'share'){
      let  url = `/admin/documents/userlist/${id}`; 
      $(`#modal_dialog .modal-content`).load(url,function(result){
          $('#modal_dialog').modal({show:true});
      });
    }

    else if(key  == 'download-text'){
      downloadText(id);

      return;

    }else if(key== 'download-word'){
      downloadWord(id);
    }
     else if(key == 'edit-notes'){
        let url = `/admin/documents/${id}/edit`;
        $(`#notesModal .modal-content`).load(url,function(result){
            $('#notesModal').modal({show:true});
        });
     
      return
    }

  }


 $.contextMenu({
            selector: '.contextmenu', 
            callback: function(key, options) {
               let id = $(this).data('id');
               callbackMenu(key,id);
            },
            items: {
                "download": {name: "Download", icon: "fa-cloud-download"},
                "sep1": "---------",
                "edit": {name: "Edit", icon: "fa-edit"},
                "sep2": "---------",
                "delete": {name: "Delete", icon: "fa-trash deletable"}, 
                "sep3": "---------",
                "share": {name: "Share", icon: "fa-share"}
            }
  });


 $.contextMenu({
            selector: '.contextmenu-notes', 
            callback: function(key, options) {
               let id = $(this).data('id');
               callbackMenu(key,id);
            },
            items: {
                "download": {name: "Download", 
                items: {
                
                  'download-text': {name: "Download as Text File",icon:'fa-file-text'}

                },
                icon: "fa-cloud-download"
                },
                "sep1": "---------",
                "edit-notes": {name: "Edit", icon: "fa-edit"},
                "sep2": "---------",
                "delete": {name: "Delete", icon: "fa-trash deletable"}, 
                "sep3": "---------",
                "share": {name: "Share", icon: "fa-share"}
            }
  });


  $.contextMenu({
            selector: '.contextmenu-notes-doc', 
            callback: function(key, options) {
               let id = $(this).data('id');
               callbackMenu(key,id);
            },
            items: {
                "download": {name: "Download", 
                items: {
                    'download-word': {name: "Download as Word",icon:'fa-file-word-o'},

                },
                icon: "fa-cloud-download"
                },
                "sep1": "---------",
                "edit-notes": {name: "Edit", icon: "fa-edit"},
                "sep2": "---------",
                "delete": {name: "Delete", icon: "fa-trash deletable"}, 
                "sep3": "---------",
                "share": {name: "Share", icon: "fa-share"}
            }
  });




 $('.change-category').click(function(){
  let id = $(this).data('id');
  var folder_id = `{{ \Request::get('folder') }}`;
  if(folder_id)
    window.location = `/admin/documents?category=${id}&folders=${folder_id}`;
  else
    window.location = `/admin/documents?category=${id}`;

 });
 $('#search').click(function(){
  let term = $('#search_inp').val();
  window.location = `/admin/documents?term=${term}`;
 });

 $('input[name=doctype]').change(function(){
  let val = $(this).val();
  if(val == 'shared'){
    window.location = `/admin/documents?type=shared`;
  }
  else{
     window.location = `/admin/documents`;
  }
 });


 //donwload words
 function Export2Doc(element, filename = '') {
      let headclose = '<'+'/head>';
      var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' "+
            "xmlns:w='urn:schemas-microsoft-com:office:word' "+
            "xmlns='http://www.w3.org/TR/REC-html40'>"+
            "<head><meta charset='utf-8'><title>Export HTML to Word Document with JavaScript</title>"+ headclose +"<body>";

       var footer = "</body></html>";
       var sourceHTML = header+element+footer;

       var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
       var fileDownload = document.createElement("a");
       document.body.appendChild(fileDownload);
       fileDownload.href = source;
       fileDownload.download = filename+'.doc';
       fileDownload.click();
       document.body.removeChild(fileDownload);
}

$('.contextmenu-notes').click(function(){
  $(this).trigger('contextmenu');
});

$('.contextmenu-notes-doc').click(function(){
  $(this).trigger('contextmenu');
});
$(document).on('click', '[data-dismiss="modal"]', function(e) { 

  e.preventDefault();

  e.stopImmediatePropagation(); 
  return false;

});
</script>

<div id="notesModal" class="modal fade" role="dialog"  style="height: 100%;width: 100%;">
  <div class="modal-dialog" style=" width: 100%;
    height: 100%;
    margin-top: 0;
    padding: 0;">

    <!-- Modal content-->
    <div class="modal-content" style="height: auto;
    min-height: 100%;
    border: 0 none;
    border-radius: 0;
    box-shadow: none;">
      
    </div>
  </div>
</div>

@endsection
