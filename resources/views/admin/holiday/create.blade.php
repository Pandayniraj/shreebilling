@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<style>
	select { width:200px !important; }

.bootstrap-datetimepicker-widget.dropdown-menu {
  background-color: #30363b;
  color: #fff;
  border-coor: #2b3135;
  width: 100%;
}
.bootstrap-datetimepicker-widget.dropdown-menu:after,
.bootstrap-datetimepicker-widget.dropdown-menu:before {
  display: none !important;
}
.bootstrap-datetimepicker-widget.dropdown-menu .arrow {
  display: none !important;
}
.bootstrap-datetimepicker-widget.dropdown-menu .arrow:after,
.bootstrap-datetimepicker-widget.dropdown-menu .arrow:before {
  display: none !important;
}
.bootstrap-datetimepicker-widget.dropdown-menu a span:hover,
.bootstrap-datetimepicker-widget.dropdown-menu a span:focus {
  background-color: #32aa62 !important;
  color: #fff !important;
}

</style>

    <div class='row'>
        <div class='col-md-12'>
            <div class="box">
				<div class="box-body">
                	{!! Form::open( ['route' => 'admin.tasks.store', 'class' => 'form-horizontal', 'id' => 'form_edit_task'] ) !!}
                    <input type="hidden" name="task_type" value="{!! \Request::get('q') !!}">

                <div class="content col-md-9">
                <!-- <H3> Make <?php  //echo  $_GET['q'] ?></H3> -->
                	<div class="row">
                    	<div class="col-md-6">


                            <!--
                            <div class="form-group">
                                 {!! Form::label('task_type', trans('admin/tasks/general.columns.task_type')) !!}
                                 {!! Form::select('task_type', ['meeting'=>'Meeting', 'call'=>'Call', 'todo'=>'To Do', 'appointment'=>'Appointment','vacation' => 'Vacation'], null, ['class' => 'form-control input-lg']) !!}
                            </div> -->

                            @if($lead)
                            <div class="form-group">
                              <label for="inputEmail3" class="col-sm-2 control-label">
                               Lead ?
                                </label><div class="col-sm-10">
                                {!! Form::text('lead_id', $lead->name, ['class' => 'form-control', 'id'=>'lead_id', 'readonly']) !!}
                            </div>
                            </div>
                            @else
                            <div class="form-group">
                                 <label for="inputEmail3" class="col-sm-2 control-label">
                                {!! Form::label('lead_id', trans('admin/tasks/general.columns.lead')) !!}
                                </label><div class="col-sm-10">
                                {!! Form::text('lead_id', null, ['class' => 'form-control', 'id'=>'lead_id']) !!}
                            </div>
                            </div>
                            @endif


                            <div class="form-group">
                                 <label for="inputEmail3" class="col-sm-2 control-label">
                                {!! Form::label('contact_id', trans('admin/tasks/general.columns.contact')) !!}
                                </label><div class="col-sm-10">
                                {!! Form::text('contact_id', null, ['class' => 'form-control', 'id'=>'contact_id']) !!}
                            </div>
                            </div>

                            
                            <div class="form-group">
                                 <label for="inputEmail3" class="col-sm-2 control-label">
                                Percentage
                                </label><div class="col-sm-10">
                                {!! Form::text('task_complete_percent', null, ['class' => 'form-control']) !!}
                            </div>
                            </div>
                       

                        </div>    
                        <div class="col-md-6">
                            <div class="form-group">
                                 <label for="inputEmail3" class="col-sm-2 control-label">
                                 {!! Form::label('task_subject', trans('admin/tasks/general.columns.task_subject')) !!}
                                 </label><div class="col-sm-10">
                                {!! Form::text('task_subject', null, ['class' => 'form-control input-lg']) !!}
                            </div>
                            </div>


                            <div class="form-group">
                                 <label for="inputEmail3" class="col-sm-2 control-label">
                                 {!! Form::label('location', trans('admin/tasks/general.columns.location')) !!}
                                 </label><div class="col-sm-10">
                                {!! Form::text('location', null, ['class' => 'form-control']) !!}
                            </div>
                            </div>

                             <div class="form-group">
                                 <label for="inputEmail3" class="col-sm-2 control-label">
                                 {!! Form::label('duration', trans('admin/tasks/general.columns.duration')) !!}
                                 </label><div class="col-sm-10">
                                {!! Form::text('duration', null, ['class' => 'form-control']) !!}
                            </div>
                            </div>

                            
                            <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">
                            </label>
                            <div class="col-sm-10">
                                {!! Form::textarea('task_detail', null, ['class' => 'form-control', 'rows'=>'3','col'=>'4', 'placeholder'=> 'Task Detail']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">
                               Alert
                            </label>
                            <div class="col-sm-10">
                                
                                {!! Form::radio('task_alert', 1, true) !!} Yes
                                {!! Form::radio('task_alert', 0, false) !!} No
                            </div>
                            </div>
                        

                    	</div>


                    </div>
                    
                          
                </div><!-- /.content -->
                <div class="content col-md-3">
                	<div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">
                        {!! Form::label('task_status', trans('admin/tasks/general.columns.task_status')) !!}
                        </label><div class="col-sm-10">
                        {!! Form::select('task_status', 
                        ['Started'=>'Started', 
                         'Processing'=>'Processing', 
                         'Completed'=>'Completed'], null, ['class' => 'form-control input-sm']) !!}
                    </div>
                    </div>
                	<div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">
                    	{!! Form::label('task_owner', trans('admin/tasks/general.columns.task_owner')) !!}
                        </label><div class="col-sm-10">
                        {!! Form::select('task_owner',  $users, \Auth::user()->id, ['class' => 'form-control input-sm']) !!}
                    </div>
                    </div>
                    <div class="form-group">
                         <label for="inputEmail3" class="col-sm-2 control-label">
                        To
                        </label>
                        <div class="col-sm-10">
                            <span class="txtfield"><ul id="peoples"></ul></span>
                            @if(isset($task))
                            <input type="hidden" class="form-control peoples" name="task_assign_to" id="peoplesField" 
                            value="{!! $task->task_assign_to !!}" >
                            @else
                            <input type="hidden" class="form-control peoples" name="task_assign_to" id="peoplesField" value="" >
                            @endif
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="inputEmail3" class="col-sm-2 control-label">
                        {!! Form::label('task_priority', trans('admin/tasks/general.columns.task_priority')) !!}
                        </label><div class="col-sm-10">
                        {!! Form::select('task_priority', ['Low'=>'Low', 'Medium'=>'Medium', 'High'=>'High'], 'High', ['class' => 'form-control input-sm']) !!}
                    </div>
                    </div>


                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">
                                Start
                                </label><div class="col-sm-10">
                                <div class='input-group date' id='task_start_date'>
                                
                                {!! Form::text('task_start_date', null, ['class' => 'form-control', 'id'=>'task_start_date']) !!}

                                <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                </span>
                            </div>

                            </div>
                            </div>
                       
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">
                                    End
                                </label><div class="col-sm-10">
                                <div class='input-group date' id='task_due_date'>
                                
                                {!! Form::text('task_due_date', null, ['class' => 'form-control', 'id'=>'task_due_date']) !!}

                                <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                </span>
                            </div>

                            </div>
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
                        <a href="{!! route('admin.tasks.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
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
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<!-- <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script> -->
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
    
	$(document).ready(function() {
		$("#lead_id").autocomplete({
			source: "/admin/getLeads",
			minLength: 1
		});

        $("#contact_id").autocomplete({
            source: "/admin/getContacts",
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

<!-- include tags scripts and css -->

<script type="text/javascript" src="/bower_components/tags/js/tag-it.js"></script>
<link href="/bower_components/tags/css/jquery.tagit.css" rel="stylesheet" type="text/css"/>
<link href="/bower_components/tags/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css"/>
<!-- tags scripts and css ends -->

<script>
$(function() {
    jQuery("#peoples").tagit({
        singleField: true,
        singleFieldNode: $('#peoplesField'),
        allowSpaces: true,
        minLength: 1,
        tagLimit: 5,
        placeholderText: 'Enter First Name',
        allowNewTags: false,
        requireAutocomplete: true,

        removeConfirmation: true,
        tagSource: function( request, response ) {
            //console.log("1");
            $.ajax({
                url: "/admin/getUserTagsJson",
                data: { term:request.term },
                dataType: "json",
                success: function( data ) {
                    response( $.map( data, function( item ) {
                        return {
                            label: item.first_name ,
                            value: item.value
                        }
                    }));
                }
            });
        }
    });

});
</script>
@endsection
