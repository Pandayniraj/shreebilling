@extends('layouts.master')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title"}} 
                <small> {{ $page_description ?? "Page Description" }}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>

 <div class='row'>
       <div class='col-md-12'>
          <div class="box">
		     <div class="box-body ">
		     	<form method="post" action="/admin/time_history/update_time/{{$clock->attendance_id}}" enctype="multipart/form-data">  
		     	{{ csrf_field() }}   

		     	    <div class="row">
		     	    	<div class="col-md-4">
	                   	    <label class="control-label">Old Time In </label>
                             <div class="input-group">
                                  <p class="form-control-static">{{date('h:i a', strtotime($clock->clockin_time))}}</p>
                             </div>
	                   	</div>

	                   	<div class="col-md-4">
	                   	    <label class="control-label">New Time In </label>
                             <div class="input-group">
                                 <input type="text" name="clockin_edit" id="clockin_edit" class="form-control datepicker"
                                               value="{{date('h:i a', strtotime($clock->clockin_time))}}">
                                  <input type="hidden" name="clockin_old" id="clockin_old" value="{{ date('h:i a', strtotime($clock->clockin_time)) }}">
                                 <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-clock"></i></a>
                                 </div>
                             </div>
	                   	</div>

                    </div>

                    <div class="row">

                    	<div class="col-md-4">
	                   	    <label class="control-label">Old Time Out</label>
                             <div class="input-group">
                                  <p class="form-control-static">{{date('h:i a', strtotime($clock->clockout_time))}}</p>
                             </div>
	                   	</div>

	                   	<div class="col-md-4">
	                   	    <label class="control-label">Old Time Out</label>
                             <div class="input-group">
                                        <input type="text" name="clockout_edit" id="clockout_edit" class="form-control datepicker"
                                               value="{{date('h:i a', strtotime($clock->clockout_time))}}">
                                        <input type="hidden" name="clockout_old" id="clockout_old" value="{{ date('h:i a', strtotime($clock->clockout_time)) }}">
                                        <div class="input-group-addon">
                                            <a href="#"><i class="fa fa-clock"></i></a>
                                        </div>
                             </div>
	                   	</div>
                    	
                    </div>
                    <div class="row">
                    	<div class="col-md-4">
                    		<label class="control-label">Reason <span
                                            class="text-red">*</span></label>
                    		<textarea class="form-control" name="reason" id="reason" rows="6" required="required"></textarea>
                    		
                    	</div>
                    	
                    </div>

                   
                   
               </div>
		    </div>
                <div class="row">
	                <div class="col-md-12">
	                    <div class="form-group">
	                        {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
	                        <a href="/admin/time_history" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
	                    </div>
	                 </div>
	            </div>
		     </form>
		
	</div>
</div>

    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
	<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

	<!-- Timepicker -->
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/timepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/timepicker.js") }}" type="text/javascript"></script>


  <script type="text/javascript">
    $(function(){
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'hh:mm A',   
          sideBySide: true,
          allowInputToggle: true
        });

      });
</script>




@endsection