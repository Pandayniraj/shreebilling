@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')
<style>
    .box-comment { margin-bottom: 5px; padding-bottom: 5px; border-bottom: 1px solid #eee; }
    .box-comment img {float: left; margin-right:10px;}
    .username { font-weight: bold; }
    .comment-text span{display: block;}
</style>
    <div class='row'>
        <div class='col-md-9'>
        	<div class="box box-primary">
            	<div class="box-header with-border bg-danger text-capitalize">
                	<h3> &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-bug"></i>  <abbr title="This is a Bug subject">{!! $bug->subject !!}</abbr> </h3>
                    <p>{!! $bug->description !!}</p>

                </div>

                <div class="box-body">
    
                    {!! Form::model($bug, ['route' => 'admin.bugs.index', 'method' => 'GET']) !!}
    
                    <div class="content">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('user_id', trans('admin/bugs/general.columns.user_id')) !!}
                                {!! Form::text('user_id', $bug->user->first_name.' '.$bug->user->last_name, ['class' => 'form-control', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('priority', trans('admin/bugs/general.columns.priority')) !!}
                                {!! Form::text('priority', $bug->priority, ['class' => 'form-control', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('status', trans('admin/bugs/general.columns.status')) !!}
                                {!! Form::text('status', null, ['class' => 'form-control', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('type', trans('admin/bugs/general.columns.type')) !!}
                                {!! Form::text('type', null, ['class' => 'form-control', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('found_in_release', trans('admin/bugs/general.columns.found_in_release')) !!}
                                {!! Form::text('found_in_release', null, ['class' => 'form-control', 'readonly']) !!}
                            </div>

                            


                        </div>

                        <div class="col-md-6">

                            

                            <div class="form-group">
                                {!! Form::label('resolution', trans('admin/bugs/general.columns.resolution')) !!}
                                {!! Form::textarea('resolution', null, ['class' => 'form-control', 'rows'=>'5', 'readonly']) !!}
                            </div>

                             <div class="form-group">
                                {!! Form::label('category', trans('admin/bugs/general.columns.category')) !!}
                                {!! Form::text('category', null, ['class' => 'form-control', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('assigned_to', trans('admin/bugs/general.columns.assigned_to')) !!}
                                {!! Form::text('assigned_to', $bug->assigned->first_name.' '.$bug->assigned->last_name, ['class' => 'form-control', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('fixed_in_release', trans('admin/bugs/general.columns.fixed_in_release')) !!}
                                {!! Form::text('fixed_in_release', null, ['class' => 'form-control', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('source', trans('admin/bugs/general.columns.source')) !!}
                                {!! Form::text('source', null, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                    {!! Form::checkbox('enabled', '1', $bug->enabled, ['disabled']) !!} {!! trans('general.status.enabled') !!}
                                </label>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            {!! Form::submit(trans('general.button.close'), ['class' => 'btn btn-primary']) !!}
                            @if ( $bug->isEditable() || $bug->canChangePermissions() )
                                <a href="{!! route('admin.bugs.edit', $bug->id) !!}" title="{{ trans('general.button.edit') }}" class='btn btn-default'>{{ trans('general.button.edit') }}</a>
                            @endif
                        </div>


                        {!! Form::close() !!}
                    </div><!-- /.content -->
    
                </div><!-- /.box-body -->
            </div>

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
                  <input type="hidden" name="type" value="bugs">
                  <input type="hidden" name="master_id" value="{{$bug->id}}">
                  <button type="submit" style="padding: 5px; margin-left: 3px;" name="submit_comment" class="btn btn-info btn-xs">Send</button>
                  <div class="clearfix"></div>
                </div>
              </form>
            </div>



        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection

@section('body_bottom')
    <!-- Select2 js -->
    @include('partials._body_bottom_select2_js_user_search')
@endsection
