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
                {!! Form::model( $task, ['route' => ['admin.tasks.update', $task->id], 'method' => 'PATCH', 'id' => 'form_edit_lead'] ) !!}
                <div class="content col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('lead', trans('admin/tasks/general.columns.lead')) !!}
                                {!! Form::text('lead', $task->lead->name, ['class' => 'form-control', 'id'=>'lead_id']) !!}
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group">
                                 {!! Form::label('task_subject', trans('admin/tasks/general.columns.task_subject')) !!}
                                {!! Form::text('task_subject', null, ['class' => 'form-control']) !!}
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
                                {!! Form::text('task_complete_percent', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('task_alert', trans('admin/tasks/general.columns.task_alert')) !!}<br/>
                                {!! Form::radio('task_alert', 1, ['class' => 'form-control']) !!} Yes
                                {!! Form::radio('task_alert', 0, ['class' => 'form-control']) !!} No
                            </div>
                        </div>  
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php
                                    if($task->task_start_date != '0000-00-00 00:00:00')
                                        $start_date = date('d-m-Y G:i', strtotime($task->task_start_date));
                                    else
                                        $start_date = '';
                                ?>
                                {!! Form::label('task_start_date', trans('admin/tasks/general.columns.task_start_date')) !!}
                                {!! Form::text('task_start_date', $start_date, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php
                                    if($task->task_due_date != '0000-00-00 00:00:00')
                                        $due_date = date('d-m-Y G:i', strtotime($task->task_due_date));
                                    else
                                        $due_date = '';
                                ?>
                                {!! Form::label('task_due_date', trans('admin/tasks/general.columns.task_due_date')) !!}
                                {!! Form::text('task_due_date', $due_date, ['class' => 'form-control']) !!}
                            </div>
                        </div>                      
                    </div>
                    <div class="row">
                        
                    </div>       
                </div><!-- /.content -->
                
                <div class="content col-md-3">
                    <div class="form-group">
                        {!! Form::label('task_status', trans('admin/tasks/general.columns.task_status')) !!}
                        {!! Form::select('task_status', ['Open'=>'Open', 'Processing'=>'Processing', 'Completed'=>'Completed'], null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('task_owner', trans('admin/tasks/general.columns.task_owner')) !!}
                        {!! Form::select('task_owner',  $users, $task->owner->name, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('task_assign_to', trans('admin/tasks/general.columns.task_assign_to')) !!}
                        {!! Form::select('task_assign_to',  $users, $task->assigned_to->name, ['class' => 'form-control']) !!}
                    </div> 
                    <div class="form-group">
                        {!! Form::label('task_priority', trans('admin/tasks/general.columns.task_priority')) !!}
                        {!! Form::select('task_priority', ['Low'=>'Low', 'Medium'=>'Medium', 'High'=>'High'], null, ['class' => 'form-control']) !!}
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
@endsection