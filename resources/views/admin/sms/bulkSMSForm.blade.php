@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<style>
	select { width:200px !important; }
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<script>
$(function() {
	$('#start_date').datetimepicker({
			//inline: true,
			format: 'YYYY-MM-DD HH:mm',
			sideBySide: true
		});
	$('#end_date').datetimepicker({
			//inline: true,
			format: 'YYYY-MM-DD HH:mm',
			sideBySide: true
		});
});
</script>
<script>
	/*var text_max = 138;
	$('.char-cnt').html(text_max + ' characters remaining');*/

	$(document).ready(function() {
		/*$('#message-textarea').keyup(function() {
			var text_length = $('#message-textarea').val().length;
			var text_remaining = text_max - text_length;
			$('.char-cnt').html(text_remaining + ' characters remaining');
		});*/
		
		$(".modal").on("hidden.bs.modal", function(){
			//$(".modal-body1").html("");
		});
	});
</script>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Bulk SMS
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
             <span> Contact support to connect any SMS vendors of your choice</span><br/>

           {{ TaskHelper::topSubMenu('topsubmenu.crm')}}

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body">
                {!! Form::open( ['route' => 'admin.mail.post-send-bulk-sms', 'id' => 'form_edit_lead'] ) !!}        
                <div class="content col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('course_id', trans('admin/leads/general.columns.course_name')) !!}

                                <select name="course_id" id="course_id" class="form-control">
                                    <option value="0">Select for All Products</option>
                                    @foreach($courses as $ck => $cv)
                                    <option value="{{ $cv->id }}">{{ $cv->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('status_id', trans('admin/leads/general.columns.status_id')) !!}
                                <select name="status_id" id="status_id" class="form-control">
                                    <option value="0">Select for All Status</option>
                                    @foreach($lead_status as $ck => $cv)
                                    <option value="{{ $cv->id }}">{{ $cv->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group" style="margin-bottom:5px;">
                                {!! Form::text('start_date', \Request::get('start'), ['class' => 'form-control', 'id'=>'start_date', 'placeholder'=>'Start Date']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="margin-bottom:5px;">
                                {!! Form::text('end_date', \Request::get('end'), ['class' => 'form-control', 'id'=>'end_date', 'placeholder'=>'End Date']) !!}
                            </div>                    
                        </div>
                        
                        <div class="col-md-12">
                        	<div class="form-group">
                            	<span>Note: Maximum 138 character limit *</span><br>
                                <!-- <textarea rows="3" name="message" class="form-control" id="message-textarea" onblur="countChar(this)" placeholder="Type your message." maxlength="138"></textarea> -->
                                <textarea rows="3" name="message" class="form-control" id="message-textarea" placeholder="Type your message." required="required"></textarea>
                                <!-- <span class="char-cnt">138 characters remaining</span>    -->     
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit('Send SMS', ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                            </div>
                        </div>
                    </div>                         
                </div>
                {!! Form::close() !!}
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.col -->

</div><!-- /.row -->
@endsection

@section('body_bottom')
 
@endsection
