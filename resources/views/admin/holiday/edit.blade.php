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
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />

    <div class='row'>
        <div class='col-md-12'>
            <div class="box">
				<div class="box-body">
                {!! Form::model( $task, ['route' => ['admin.tasks.update', $task->id], 'method' => 'PATCH', 'id' => 'form_edit_lead'] ) !!}
                <div class="content col-md-9">
                    <div class="row">
                        <div class="col-md-6">


                            <div class="form-group">
                                 {!! Form::label('task_type', trans('admin/tasks/general.columns.task_type')) !!}
                                 {!! Form::select('task_type', 
                                 [
                                    'meeting'=>'Meeting', 
                                    'outbound'=>'Outbound Call', 
                                    'inbound'=> 'Inbound Call', 
                                    'todo'=>'To Do', 
                                    'appointment'=>'Appointment',
                                    'vacation' => 'Vacation'
                                    ], null, ['class' => 'form-control input-xs label-default']) !!}
                            </div>


                            <div class="form-group">
                                {!! Form::label('lead_id', trans('admin/tasks/general.columns.lead')) !!}
                                @if($task->lead_id != '' && $task->lead_id != '0')
                                {!! Form::text('lead_id', $task->lead->name, ['class' => 'form-control', 'id'=>'lead_id']) !!}
                                @else
                                {!! Form::text('lead_id', null, ['class' => 'form-control', 'id'=>'lead_id']) !!}
                                @endif
                            </div>


                            <div class="form-group">
                                {!! Form::label('contact_id', trans('admin/tasks/general.columns.contact')) !!}
                                @if($task->contact_id != '' && $task->contact_id != '0')
                                {!! Form::text('contact_id', $task->contact['full_name'], ['class' => 'form-control', 'id'=>'contact_id']) !!}
                                @else
                                {!! Form::text('contact_id', null, ['class' => 'form-control', 'id'=>'contact_id']) !!}
                                @endif
                            </div>

                        </div>    
                        <div class="col-md-6">
                            <div class="form-group">
                                 {!! Form::label('task_subject', trans('admin/tasks/general.columns.task_subject')) !!}
                                {!! Form::text('task_subject', null, ['class' => 'form-control']) !!}
                            </div>


                            <div class="form-group">
                                 {!! Form::label('location', trans('admin/tasks/general.columns.location')) !!}
                                {!! Form::text('location', null, ['class' => 'form-control']) !!}
                            </div>

                             <div class="form-group">
                                 {!! Form::label('duration', trans('admin/tasks/general.columns.duration')) !!}
                                {!! Form::text('duration', null, ['class' => 'form-control']) !!}
                            </div>


                            <div class="form-group">
                                {!! Form::label('task_complete_percent', trans('admin/tasks/general.columns.task_complete_percent')) !!}
                                {!! Form::text('task_complete_percent', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                   


                    </div>
                    <div class="row">
                        
                        
                    </div>
                    <div class="row">
                                              
                    </div>
                    <div class="row">
                       

                          <div class="col-md-8">
                            <div class="form-group">
                                {!! Form::label('task_detail', trans('admin/tasks/general.columns.task_detail')) !!}
                                {!! Form::textarea('task_detail', null, ['class' => 'form-control', 'rows'=>'5']) !!}
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
                </div><!-- /.content -->
                
                <div class="content col-md-3">
                	<div class="form-group">
                        {!! Form::label('task_status', trans('admin/tasks/general.columns.task_status')) !!}
                        {!! Form::select('task_status', 
                        ['Started'=>'Started',
                         'Processing'=>'Processing', 
                         'Completed'=>'Completed'], null, ['class' => 'form-control input-sm label-default']) !!}
                    </div>
                	<div class="form-group">
                    	{!! Form::label('task_owner', trans('admin/tasks/general.columns.task_owner')) !!}
                        {!! Form::select('task_owner',  $users, $task->owner->name, ['class' => 'form-control input-sm label-default']) !!}
                    </div>
                    <div class="form-group">
                         <label for="inputEmail3" class="col-sm-2 control-label">To</label>
                         <div class="clearfix"></div>
                        <div>
                            <span class="txtfield"><ul id="peoples"></ul></span>
                            <input type="hidden" class="form-control peoples" name="task_assign_to" id="peoplesField" 
                            value="{!! $task->task_assign_to !!}" >
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('task_priority', trans('admin/tasks/general.columns.task_priority')) !!}
                        {!! Form::select('task_priority', ['Low'=>'Low', 'Medium'=>'Medium', 'High'=>'High'], null, ['class' => 'form-control input-sm label-default']) !!}
                    </div>

                            <div>


                            <div class="form-group">
                                <label>Start Date</label>
                                <div class='input-group date' id='task_start_date'>
                                <?php
                                    if($task->task_start_date != '0000-00-00 00:00:00')
                                        $start_date = date('d-m-Y G:i', strtotime($task->task_start_date));
                                    else
                                        $start_date = '';
                                ?>


                                
                                {!! Form::text('task_start_date', $start_date, ['class' => 'form-control']) !!}

                                <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                </span>

                            
                            </div>
                        </div>

           
                        
                            <div class="form-group">
                                {!! Form::label('task_due_date', trans('admin/tasks/general.columns.task_due_date')) !!}
                                <div class='input-group date' id='task_due_date'>
                                <?php
                                    if($task->task_due_date != '0000-00-00 00:00:00')
                                        $due_date = date('d-m-Y G:i', strtotime($task->task_due_date));
                                    else
                                        $due_date = '';
                                ?>
                                
                                {!! Form::text('task_due_date', $due_date, ['class' => 'form-control']) !!}


                                <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                </span>
                            </div>
                            </div>

                    <div class="form-group">
                    	{!! Form::checkbox('enabled', 1, $task->enabled) !!} {{ trans('general.status.enabled') }}
                    </div>
                </div>
				
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                        <a href="{!! route('admin.tasks.index') !!}" class='btn btn-danger'>{{ trans('general.button.cancel') }}</a>
                    </div>
                </div>

                {!! Form::close() !!}
            	</div><!-- /.box-body -->
        	</div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<!-- <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script> -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<script>
	   $(function() {
        $('#task_start_date').datetimepicker({
                //inline: true,
                
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