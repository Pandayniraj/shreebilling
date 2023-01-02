@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')
<style>
	.mail-wrap:hover { cursor:pointer; }
  .box-comment { margin-bottom: 5px; padding-bottom: 5px; border-bottom: 1px solid #eee; }
  .box-comment img {float: left; margin-right:10px;}
  .username { font-weight: bold; }
  .comment-text span{display: block;}
</style>

<div class='row'>
    <div class='col-md-12'>
        <div class="box box-primary">
            <div class="box-header with-border">
              <p> <i class="fa fa-user"></i> From: {!! $task->owner->username !!} <i class="fa fa-user"></i> To: {!! $task->task_assign_to !!} </p>
                <h2>{!! $task->task_subject !!}</h2>
                <p class="lead">{!! $task->task_detail !!} </p>
                <p> <i class="fa fa-clock"></i> Starts on: {!!  date('d M y,  H:s',strtotime($task->task_start_date)) !!} 
                     <i class="fa fa-clock"></i>  Ends on: {!!  date('d M y,  H:s',strtotime($task->task_due_date)) !!} 
                     <i class="fa fa-stopwatch"></i>  Status: {!!  date('d M y,  H:s',strtotime($task->task_status)) !!}
                   </p>
            </div>
            <div class="box-body">

                {!! Form::model($task, ['route' => 'admin.tasks.index', 'class' => 'form-horizontal', 'method' => 'GET']) !!}

                <div class="col-md-9">
                    <div class="row">

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">
                          Lead
                          </label><div class="col-sm-10">
                          @if($task->lead_id != '' && $task->lead_id != '0')
                          {!! Form::text('lead', $task->lead->name, ['class' => 'form-control', 'readonly']) !!}
                          @else
                          {!! Form::text('name', null, ['class' => 'form-control', 'readonly']) !!}
                          @endif
                        </div>
                        </div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="col-md-6">
                         <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">
                          {!! Form::label('lead', trans('admin/tasks/general.columns.contact')) !!}
                          </label><div class="col-sm-10">
                          @if($task->contact_id != '' && $task->contact_id != '0')
                          {!! Form::text('contact_id', $task->contact->full_name, ['class' => 'form-control', 'readonly']) !!}
                          @else
                          {!! Form::text('name', null, ['class' => 'form-control', 'readonly']) !!}
                          @endif
                        </div>
                        </div>
                      </div>

                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">
                        {!! Form::label('task_priority', trans('admin/tasks/general.columns.task_priority')) !!}
                        </label><div class="col-sm-10">
                        {!! Form::text('task_priority', null, ['class' => 'form-control', 'readonly']) !!}
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">
                        </label><div class="col-sm-10">
                        {!! Form::checkbox('enabled', 1, $task->enabled, ['disabled']) !!} {{ trans('general.status.enabled') }}
                        
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">    
                    <div class="form-group">
                        <a href="/admin/tasks" class="btn btn-danger">Close</a>
                        @if ( $task->isEditable() || $task->canChangePermissions() )
                            <a href="{!! route('admin.tasks.edit', $task->id) !!}" class='btn btn-warning'>{{ trans('general.button.edit') }}</a>
                        @endif
                    </div>
                </div>
                {!! Form::close() !!}

                <div class="col-md-12">
                  <div class="box-footer box-comments">
                    <h4><strong>Comments: </strong></h4>
                    @foreach($comments as $ck => $cv)
                      <div class="box-comment">
                        <!-- User image -->
                        <img class="img-circle img-sm" height="64" width="64" src="{{ TaskHelper::getProfileImage($cv->user_id) }}" alt="User Image">

                        <div class="comment-text">
                              <span class="username">
                                {{ $cv->user->first_name }}
                                <span class="text-muted pull-right"> {{ date('dS M y', strtotime($cv->created_at)) }} </span>
                              </span><!-- /.username -->
                          {{ $cv->comment_text }}
                        </div>
                        <div class="clearfix"></div>
                      </div>
                    @endforeach
                  </div>

                  <div class="box-footer">
                    <form action="/admin/post_comment" method="post">
                      {{csrf_field()}}
                      <!-- .img-push is used to add margin to elements next to floating images -->
                      <div class="img-push">
                        <input type="text" style="width:90%; float: left;" class="form-control input-sm" name="comment_text" placeholder="Press enter to post comment">
                        <input type="hidden" name="type" value="task">
                        <input type="hidden" name="master_id" value="{{$task->id}}">
                        <button type="submit" style="padding: 5px; margin-left: 3px;" name="submit_comment" class="btn btn-info btn-xs">Send</button>
                        <div class="clearfix"></div>
                      </div>
                    </form>
                  </div>
                </div>
            </div><!-- /.content -->

        </div><!-- /.box-body -->
    </div>
</div><!-- /.col -->

@endsection
