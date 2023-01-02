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
		     	<form method="post" action="/admin/shift" enctype="multipart/form-data">  
		     	{{ csrf_field() }}   

		     	    <div class="row">

	                   	<div class="col-md-4">
	                   	    <label class="control-label">Shift Name</label>
                            <input type="text" name="shift_name" placeholder="Shift Name" id="name" value="" class="form-control " >
	                   	</div>
	                   	<div class="col-md-4">
	                   	    <label class="control-label">Shift Time</label>
                            <input type="text" name="shift_time" placeholder="Shift Time" id="min" value="" class="form-control datepicker" >
	                   	</div>
	                   	<div class="col-md-4">
	                   	    <label class="control-label">End Time</label>
                            <input type="text" name="end_time" placeholder="End Time" id="max" value="" class="form-control datepicker" >
	                   	</div>
	                   
                    </div>

                    <div class="row">
                    	<div class="col-md-4">
	                   	    <label class="control-label">Shift Margin Start</label> 
                            <input type="text" name="shift_margin_start" placeholder="Shift Margin Start" id="setup" value="" class="form-control " >
	                   	</div>
	                   	<div class="col-md-4">
	                   	    <label class="control-label">Shift Margin End</label>
                            <input type="text" name="shift_margin_end" placeholder="Shift Margin End" id="setdown" value="" class="form-control " >
	                   	</div>
	                   <div class="col-md-4">
	                   	<label class="control-label">Color</label>
                           {!! Form::select('color', ['danger'=>'Danger','info'=>'Info','primary'=>'Primary','success'=>'Sucess'], null, ['class'=>'form-control']) !!}
	                   </div>
	                   <div class="col-md-4">
	                   	    <label class="control-label">Shift Type</label>
                            <select class="form-control" name='is_night'>
                            	<option value="0">Day</option>
                            	<option value="1">Night</option>
                            </select>
	                   	</div>
	                   
                        
                          
	                   	</div>
	                   		<div class="col-md-4">
	                   		<div class="checkbox">
	                   			<label>
	                   				  {!! Form::checkbox('enabled', '1', null) !!}
	                   				Enabled
	                   			</label>
	                   		</div>
                    	
                    </div>
                   


               </div>
		    </div>

                <div class="row">
	                <div class="col-md-12">
	                    <div class="form-group">
	                        {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
	                        <a href="{!! route('admin.shift.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
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