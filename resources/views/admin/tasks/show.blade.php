@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')
<style>
  .mail-wrap:hover { cursor:pointer; }
</style>

<div class='row'>
    <div class='col-md-12'>
        <div class="box box-primary">
            <div class="box-header with-border">
                <label>{!! trans('admin/tasks/general.page.show.section-title') !!}</label>
            </div>
            <div class="box-body">

                {!! Form::model($task, ['route' => 'admin.tasks.index', 'method' => 'GET']) !!}

                <div class="content col-md-9">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          {!! Form::label('lead', trans('admin/tasks/general.columns.lead')) !!}
                          {!! Form::text('lead', $task->lead->name, ['class' => 'form-control', 'readonly']) !!}
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          {!! Form::label('task_subject', trans('admin/tasks/general.columns.task_subject')) !!}
                          {!! Form::text('task_subject', null, ['class' => 'form-control', 'readonly']) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                         {!! Form::label('task_detail', trans('admin/tasks/general.columns.task_detail')) !!}
                         {!! Form::textarea('task_detail', null, ['class' => 'form-control', 'rows'=>'5', 'readonly']) !!}
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          {!! Form::label('task_start_date', trans('admin/tasks/general.columns.task_start_date')) !!}
                          {!! Form::text('task_start_date', null, ['class' => 'form-control', 'readonly']) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          {!! Form::label('task_due_date', trans('admin/tasks/general.columns.task_due_date')) !!}
                          {!! Form::text('task_due_date', null, ['class' => 'form-control', 'readonly']) !!}
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          {!! Form::label('task_complete_percent', trans('admin/tasks/general.columns.task_complete_percent')) !!}
                          {!! Form::text('task_complete_percent', null, ['class' => 'form-control', 'readonly']) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          {!! Form::label('task_alert', trans('admin/tasks/general.columns.task_alert')) !!}<br/>
                          {!! Form::radio('task_alert', 1, ['disabled'=>'disabled']) !!} Yes
                          {!! Form::radio('task_alert', 0, ['disabled'=>'disabled']) !!} No
                        </div>
                      </div>
                    </div>
                </div>
                
                <div class="content col-md-3">
                    <div class="form-group">
                        {!! Form::label('task_status', trans('admin/tasks/general.columns.task_status')) !!}
                        {!! Form::text('task_status', null, ['class' => 'form-control', 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('task_owner', trans('admin/tasks/general.columns.task_owner')) !!}
                        {!! Form::text('task_owner', $task->owner->name, ['class' => 'form-control', 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('task_assign_to', trans('admin/tasks/general.columns.task_assign_to')) !!}
                        {!! Form::text('task_assign_to',  $task->assigned_to->name, ['class' => 'form-control', 'readonly']) !!}
                    </div> 
                    <div class="form-group">
                        {!! Form::label('task_priority', trans('admin/tasks/general.columns.task_priority')) !!}
                        {!! Form::text('task_priority', null, ['class' => 'form-control', 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::checkbox('enabled', 1, $task->enabled, ['disabled']) !!} {{ trans('general.status.enabled') }}
                    </div>
                </div>
                
                <div class="col-md-12">    
                    <div class="form-group">
                        {!! Form::submit(trans('general.button.close'), ['class' => 'btn btn-danger']) !!}
                        @if ( $task->isEditable() || $task->canChangePermissions() )
                            <a href="{!! route('admin.tasks.edit', $task->id) !!}" class='btn btn-warning'>{{ trans('general.button.edit') }}</a>
                        @endif
                    </div>
                </div>
                {!! Form::close() !!}
            </div><!-- /.content -->

        </div><!-- /.box-body -->
    </div>
</div><!-- /.col -->

@endsection
