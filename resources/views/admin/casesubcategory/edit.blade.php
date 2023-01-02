@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="/bower_components/tags/js/tag-it.js"></script>
    <link href="/bower_components/tags/css/jquery.tagit.css" rel="stylesheet" type="text/css"/>
    <link href="/bower_components/tags/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
        <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {{ $page_title ?? 'Page Title' }}
                <small>{{$page_description ?? 'Page Description'}}</small>
            </h1>
           
        </section>


     <div class='row'>
       <div class='col-md-12'>
          <div class="box">
         <div class="box-body ">
          <form method="post" action="/admin/casessubcategory/{{$edit->id}}/update" enctype="multipart/form-data">  
          {{ csrf_field() }}                 

                 <div class="row">
                  <div class="col-md-4">
                      <label class="control-label">Category Select</label>
                        <select class="form-control" name="parent_id">
                            <option value="">Select Category</option>
                            @foreach($category as $cc)
                            <option value="{{$cc->id}}" @if($edit->parent_id == $cc->id) selected @endif>{{$cc->name}}</option>
                            @endforeach
                            
                        </select>
                  </div>

                    <div class="col-md-4">
                        <label class="control-label">Sub Category Name</label>
                        <input type="text" name="name" placeholder="Sub Category Name" id="name" value="{{$edit->name}}" class="form-control" >
                    </div>

                    <div class="col-md-4">
                        <label class="control-label">Subject</label>
                        <input type="text" name="subject" placeholder="Subject" id="name" value="{{$edit->subject}}" class="form-control" >
                    </div>
                </div> 

                 <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">Description</label>
                        <textarea class="form-control" name="description">{{$edit->description}}</textarea> 
                    </div>
                </div> 

         </div>
       </div>

            <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                          <a href="{!! route('admin.casescategory.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                      </div>
                   </div>
              </div>

          </form>
    </div>
  </div>
        
   
@endsection

@section('body_bottom')

<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
 <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>






<script type="text/javascript">

    $(document).ready(function() {
        $("#skills").tagit();
    });

</script>

<script type="text/javascript">
  
     $(function() {
      $('.datepicker').datetimepicker({
          //inline: true,
          //format: 'YYYY-MM-DD',
          format: 'YYYY-MM-DD', 
              sideBySide: true,
              allowInputToggle: true
        });
    });

</script>
@endsection
