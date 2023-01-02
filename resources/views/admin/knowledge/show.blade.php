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
<div class='col-md-3'>

    <div class="box-body">
              <table class="table table-bordered">
                <tbody><tr>
                  
                  <th>Category</th>
                  <th style="width: 40px">Count</th>
                </tr>

            @foreach($cat as $k => $v)  
                <tr>
                  <td><a href="knowledge/cat/{{$v->id}}">{{ $v->name }}</a></td>
                  
                  <td><span class="badge bg-blue">{{ $v->knowl->count() }}</span></td>
                </tr>
            @endforeach    


              </tbody></table>
            </div>

</div>

        <div class='col-md-9'>
        	<div class="box box-primary">
            	<div class="box-header with-border bg-info">
                	<h3>KB Article (#{!! $knowledge->id !!}) {!! $knowledge->title !!} </h3>
                    <p class="lead"> {!! $knowledge->description !!} </p>
                </div>
                <div class="box-body ">
    
                    {!! Form::model($knowledge, ['route' => 'admin.knowledge.index', 'method' => 'GET']) !!}
    
                    <div class="content">
                    

                        <div class="col-md-12">
                            
                            <div class="form-group">
                               <p> {!! nl2br($knowledge->body) !!} </p>
                            </div>

                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                    {!! Form::checkbox('enabled', '1', $knowledge->enabled, ['disabled']) !!} {!! trans('general.status.enabled') !!}
                                </label>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            {!! Form::submit(trans('general.button.close'), ['class' => 'btn btn-primary']) !!}
                            @if ( $knowledge->isEditable() || $knowledge->canChangePermissions() )
                                <a href="{!! route('admin.knowledge.edit', $knowledge->id) !!}" title="{{ trans('general.button.edit') }}" class='btn btn-default'>{{ trans('general.button.edit') }}</a>
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
                  <input type="hidden" name="type" value="kb">
                  <input type="hidden" name="master_id" value="{{$knowledge->id}}">
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
