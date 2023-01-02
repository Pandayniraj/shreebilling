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
		     	<form method="post" action="/admin/leavecategory/{{$leavecategory->leave_category_id}}/update" enctype="multipart/form-data">  
		     	{{ csrf_field() }}                 

                 <div class="row">

				 <div class="col-md-4">
                 	    <label class="control-label">Leave Code</label>
                        <input type="text" name="leave_code" placeholder="Leave Code" id="leave_code" value="{{$leavecategory->leave_code}}" class="form-control" >
                 	</div>

                 	<div class="col-md-4">
                 	    <label class="control-label">Category Name</label>
                        <input type="text" name="leave_category" placeholder="Leave Category" id="leave_category" value="{{$leavecategory->leave_category}}" class="form-control" >
                 	</div>

                    <div class="col-md-4">
                        <label class="control-label">Leave Quota</label>
                        <input type="text" name="leave_quota" placeholder="Leave Quota" id="leave_quota" value="{{$leavecategory->leave_quota}}" class="form-control" >
                    </div>

					<div class="col-md-4">
                 	    <label class="control-label">Leave Type</label>
                        <input type="text" name="leave_type" placeholder="Leave Category" id="leave_type" value="{{$leavecategory->leave_type}}" class="form-control" >
                 	</div>

					 <div class="col-md-4">
                 	    <label class="control-label">Lapse Type</label>
                        <input type="text" name="lapse_type" placeholder="Lapse Type" id="lapse_type" value="{{$leavecategory->lapse_type}}" class="form-control" >
                 	</div>
                 	
                </div>       
		     </div>
		   </div>

		        <div class="row">
	                <div class="col-md-12">
	                    <div class="form-group">
	                        {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
	                        <a href="{!! route('admin.leavecategory') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
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
