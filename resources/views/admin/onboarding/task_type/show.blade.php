@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="/bower_components/tags/js/tag-it.js"></script>
    <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />

@section('content')
        <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {{ $page_title ?? 'Page Title' }}
                <small>{{$page_description ?? 'Page Description'}}</small>
            </h1>
           
        </section>


     <div class='row'>
       <div class='col-md-11'>
          <div class="box">
		     <div class="box-body ">
		     	<form method="post" action="/admin/onboard/tasktype/edit/{{$tasktype->id}}" enctype="multipart/form-data">  
		     	{{ csrf_field() }}                 
		     	<h4>Task types</h4>

                     <div class="row">
	                   	<div class="col-md-8 form-group">
	                   	    <label class="control-label">Name</label>
                            <input type="text" name="name" placeholder="Task type name" id="task_name" value="{{$tasktype->name}}" class="form-control" readonly="">
	                   	</div>
                     </div>
                     <div class="row">
                    <div class="col-md-8 form-group">
                          <label class="control-label">Owner</label>
                          <input type="text" name="name" placeholder="Task type name" id="task_name" value="{{ucfirst($tasktype->owner->username)}}" class="form-control" readonly="">
                      </div>
                     </div>
                       <div class="row">
                        <div class="col-md-8 form-group">
                          <label class="control-label">Notify days</label>
                            <input type="number" name="notified_before" placeholder="Notify before x days" id="place_of_visit" value="{{$tasktype->notified_before}}" class="form-control" readonly="">
                      </div>
                     </div>
                       <div class="row">
                  <div class="col-md-8 form-group">
                          <label class="control-label">Notify email</label>
                            <input type="email" name="notify_email" placeholder="Email to send notifications" id="place_of_visit" value="{{$tasktype->notify_email}}" class="form-control" readonly="">
                      </div>
                     </div>
                     <div class="row">
                        <div class="col-md-8 form-group">
                              <label for="inputEmail3" class="control-label">
                        Description
                        </label>
                          <textarea class="form-control" name="description" id="" placeholder="Task Type Description" readonly="">
                            {!! $tasktype->description !!}
                          </textarea>
                        </div>
                      </div>
	               
       <br>
		        <div class="row">
	                <div class="col-md-12">
	                    <div class="form-group">
	                         <a href="{!! route('admin.onboard.tasktype.edit',$tasktype->id) !!}" class='btn btn-danger'>Edit</a>
	                        <a href="{!! route('admin.onboard.tasktype') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
	                    </div>
	                 </div>
	            </div>

	        </form>
		</div>
	</div>
        
   
@endsection

@section('body_bottom')

    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<script type="text/javascript">
	
     $(function() {
       $('.searchable').select2();
		});
 

</script>
@endsection
