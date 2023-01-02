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
    <div class='row'>
        <div class='col-md-12'>
            <div class="box">
				<div class="box-body">
                	{!! Form::open( ['route' => 'admin.tasks.store', 'id' => 'form_edit_task'] ) !!}

                <div class="content col-md-9">
                	<div class="row">
                    	<div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('lead', trans('admin/tasks/general.columns.lead')) !!} Autocompletes
                                <?php if(isset($_GET['lead_id'])) { ?>
									<input type="text" name="lead" class="form-control" id="lead_id" value="{!! TaskHelper::getLeadNameById($_GET['lead_id']) !!}">
                                    <input type="hidden" name="leadId" value="{!! $_GET['lead_id'] !!}">
								<?php } else { ?>
                                {!! Form::text('lead', null, ['class' => 'form-control', 'id'=>'lead_id']) !!}
                                <input type="hidden" name="leadId" value="0">
                                <?php } ?>
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group">
                                 {!! Form::label('task_subject', trans('admin/tasks/general.columns.task_subject')) !!}
                                {!! Form::text('task_subject', null, ['class' => 'form-control', 'placeholder'=>'Write Tasks Subject']) !!}
                            </div>
                    	</div>
                    </div>
                    <div class="row">
                    	<div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('task_detail', trans('admin/tasks/general.columns.task_detail')) !!}
                                {!! Form::textarea('task_detail', null, ['class' => 'form-control', 'rows'=>'5']) !!}
                            </div>
                        </div>   

                        <div class="col-md-6">     
                            <div class="form-group">
                                {!! Form::label('task_complete_percent', trans('admin/tasks/general.columns.task_complete_percent')) !!}
                                {!! Form::text('task_complete_percent', null, ['class' => 'form-control','placeholder' => '10, 20, 30 Out of 100 percent']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('task_alert', trans('admin/tasks/general.columns.task_alert')) !!}<br/>
                                {!! Form::radio('task_alert', 1, true) !!} Yes
                                {!! Form::radio('task_alert', 0, false) !!} No
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                    	<div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('task_start_date', trans('admin/tasks/general.columns.task_start_date')) !!}
                                {!! Form::text('task_start_date', null, ['class' => 'form-control', 'id'=>'task_start_date']) !!}
                            </div>
                    	</div>
                    	<div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('task_due_date', trans('admin/tasks/general.columns.task_due_date')) !!}
                                {!! Form::text('task_due_date', null, ['class' => 'form-control', 'id'=>'task_due_date', 'placeholder' => 'Due or expected end date is required']) !!}
                            </div>
                       	</div>                    	
                    </div>
                    <div class="row">
                    	
                    </div>       
                </div><!-- /.content -->
                <div class="content col-md-3">
                	<div class="form-group">
                        {!! Form::label('task_status', trans('admin/tasks/general.columns.task_status')) !!}
                        {!! Form::select('task_status', ['Started'=>'Started', 'Open'=>'Open', 'Processing'=>'Processing', 'Completed'=>'Completed'], null, ['class' => 'form-control']) !!}
                    </div>
                	<div class="form-group">
                    	{!! Form::label('task_owner', trans('admin/tasks/general.columns.task_owner')) !!}
                        {!! Form::select('task_owner',  $users, \Auth::user()->id, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                    	{!! Form::label('task_assign_to', trans('admin/tasks/general.columns.task_assign_to')) !!}
                        {!! Form::select('task_assign_to',  $users, \Auth::user()->id, ['class' => 'form-control']) !!}
                    </div> 
                    <div class="form-group">
                        {!! Form::label('task_priority', trans('admin/tasks/general.columns.task_priority')) !!}
                        {!! Form::select('task_priority', ['Low'=>'Low', 'Medium'=>'Medium', 'High'=>'High'], 'High', ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="hidden" name="enabled" value="1">
                            </label>
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::button( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}  
                        <a href="{!! route('admin.leads.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                    </div>
                </div>

                	{!! Form::close() !!}
				</div>
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

<script>
	$(function() {
		$('#task_start_date').datetimepicker({
                //inline: true,
				//format: 'YYYY-MM-DD HH:mm',
				format: 'DD-MM-YYYY HH:mm',
                sideBySide: true
            });
		$('#task_due_date').datetimepicker({
                //inline: true,
				//format: 'YYYY-MM-DD HH:mm',
				format: 'DD-MM-YYYY HH:mm',
                sideBySide: true
            });	
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#lead_id").autocomplete({
			source: "/admin/getLeads",
			minLength: 1
		});
	});
</script>
<!-- form submit -->
<script type="text/javascript">
	$("#btn-submit-edit").on("click", function () {
		// Post form.
		$("#form_edit_task").submit();
	});
</script>
@endsection
