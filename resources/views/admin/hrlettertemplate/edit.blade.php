@extends('layouts.master')

@section('content')
<link href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {{ $page_title ?? 'Page Title' }}
                <small>{{$page_description ?? 'Page Description'}}</small>
            </h1>
           
        </section>
<script src='{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}'></script>
<div class='row'>
       <div class='col-md-12'>
          <div class="box">
         <div class="box-body ">
          <form method="post" action="{{route('admin.hrlettertemplate.update',$hrlettertemplate->id)}}" enctype="multipart/form-data" class="form-horizontal" style="visibility: hidden;">  
          {{ csrf_field() }}   
          
                     <div class="form-group">
                      <label class="control-label col-sm-2">Template Name</label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" name="name" class="form-control" placeholder="Template Name" required="" value="{{$hrlettertemplate->name}}">
                          <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-database"></i></a>
                            </div>
                        </div>
                      </div>
                     </div>   
                               
                     <div class="form-group">
                      <label class="control-label col-sm-2">Template Descriptions</label>
                         <div class="col-sm-10">
                     
                          <textarea  name="description" class="form-control" placeholder="Template Descriptions" id='body' style="width: 100%" rows="20">{!! $hrlettertemplate->description !!}</textarea>
                   
                        
                      </div>
                     </div>   

                  <div class="form-group">        
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-primary">{{trans('general.button.create')}}</button>
                      <a href="{!! route('admin.hrlettertemplate.index') !!}" class="btn btn-default">Cancel</a>
                    </div>
                  </div>
                 
          </form>
         </div>
     </div>
    </div>
  </div>
    <script type="text/javascript">
       $('textarea').each(function(){
            $(this).val($(this).val().trim());
        }
    );
       $('.form-horizontal').css('visibility','visible');
     $('textarea#body').wysihtml5();
    </script>

@endsection