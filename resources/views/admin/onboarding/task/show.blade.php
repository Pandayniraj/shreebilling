@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection
<style>
    .box-comment { margin-bottom: 5px; padding-bottom: 5px; border-bottom: 1px solid #eee; }
    .box-comment img {float: left; margin-right:10px;}
    .username { font-weight: bold; }
    .comment-text span{display: block;}
</style>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
     <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />

@section('content')
        <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {{ $page_title ?? 'Page Title' }}
                <small>{{$page_description ?? 'Page Description'}}</small>
            </h1>
           
        </section>


     <div class='row'>
       <div class='col-md-11'>
          <div class="box">
            <div class="box-header with-border">
                  <h3> Task: {!! $tasks->name !!} <small>
                        
                            <a href="{!! route('admin.onboard.task') !!}" class='btn btn-primary btn-xs'>{{ trans('general.button.close') }}</a>
                            @if ( $tasks->isEditable() || $tasks->canChangePermissions() )
                                <a href="{!! route('admin.onboard.task.edit', $tasks->id) !!}" title="{{ trans('general.button.edit') }}" class='btn btn-default btn-xs'>{{ trans('general.button.edit') }}</a>
                            @endif
                    </small>
                    </h3>
                    <span class="pull-right"> By: <label>{{ ucfirst($tasks->user->username) }}</label>
                    <img class="img-circle img-sm" height="24" width="24" src="{{ TaskHelper::getProfileImage($case->user_id) }}" alt="">
                    </span> 
                </div>

         <div class="box-body "> 
                     <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Events</label>
                           <div class="input-group ">
                            <input type="text" name="evnet" placeholder="Task name" value="{{$tasks->event->name}}" class="form-control" id="" readonly="">
                              <div class="input-group-addon">
                                   <a href="#"><i class="fa   fa-archive"></i></a>
                                </div> 
                        </div>
                    </div>
                  </div>
                        
                     </div>
                      <h4>Task info</h4>
                       <div class="row">
                     <!--    <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Name</label>
                           <div class="input-group ">
                            <input type="text" name="name" placeholder="Task name" value="{{$tasks->name}}" class="form-control" id="tname" readonly="">
                              <div class="input-group-addon">
                                   <a href="#"><i class="fa  fa-tasks"></i></a>
                                </div> 
                        </div>
                    </div>
                  </div> -->
                  <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Notify Email</label>
                           <div class="input-group ">
                            <input type="email" name="notify_mail" placeholder="Notify Email" id="temail" value="{{$tasks->notify_mail}}" class="form-control" readonly="" >
                              <div class="input-group-addon">
                                   <a href="#"><i class="fa  fa-envelope"></i></a>
                                </div> 
                        </div>
                    </div>
                  </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Notify days</label>
                           <div class="input-group ">
                            <input type="number" name="notified_before" placeholder="Notify before x days" id="tnotified_before" value="{{$tasks->notified_before}}" class="form-control" readonly="">
                              <div class="input-group-addon">
                                   <a href="#"><i class="fa fa-calendar-times-o"></i></a>
                                </div> 
                        </div>
                    </div>
                  </div>
                    
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Owner</label>
                           <div class="input-group ">
                            <input type="text" name="owner" placeholder="Task name" value="{{ucfirst($tasks->owner->username)}}" class="form-control" id="tname" readonly="">
                              <div class="input-group-addon">
                                   <a href="#"><i class="fa  fa-user"></i></a>
                                </div> 
                        </div>
                    </div>
                  
                      </div>
                    </div>
                 <h4>Schedule</h4>
                  <div class="row">
                      
                         <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Effective date</label>
                           <div class="input-group ">
                            <input type="text" name="effective_date" placeholder="Effective date" id="" value="{{$tasks->effective_date}}" class="form-control datepicker" readonly="">
                              <div class="input-group-addon">
                                   <a href="#"><i class="fa  fa-calendar-check-o"></i></a>
                                </div> 
                        </div>
                    </div>
                  </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Due date</label>
                           <div class="input-group ">
                            <input type="text" name="due_date" placeholder="Due date" id="" value="{{$tasks->due_date}}" class="form-control datepicker" readonly="readonly">
                              <div class="input-group-addon">
                                   <a href="#"><i class="fa fa-calendar"></i></a>
                                </div> 
                        </div>
                    </div>
                  </div>
                         <div class="col-md-4 form-group">
                          <label class="control-label" >Priority</label>
                             <div class="input-group ">
                            <input type="text" placeholder="Priority" class="form-control" readonly="" value="{{ucfirst($tasks->priority)}}">
                      <div class="input-group-addon">
                                   <a href="#"><i class="fa fa-balance-scale"></i></a>
                                </div> 
                              </div>
                      </div>
                     </div>
                     <h4>Pariticipants</h4>
                     <div class="row">
                           <div class="col-md-4 form-group">
                 
                    <span class="txtfield"><ul id="peoples"></ul></span>
                    <input type="hidden" class="form-control peoples" name="participants" id="peoplesField" value="{{$tasks->participants}}" >
                  </div>
                     </div>
                     <div class="row">
                        <div class="col-md-4 form-group">
                              <label for="inputEmail3" class="control-label">
                        Task  Description
                        </label>
                          <textarea class="form-control" name="description" id="tdescription" placeholder="Task Type Description" readonly="">{!! $tasks->description !!}</textarea>
                        </div>
                        
                      </div>
       <br>
           
    </div>
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
                  <input type="hidden" name="type" value="onboard_task">
                  <input type="hidden" name="master_id" value="{{$tasks->id}}">
                  <button type="submit" style="padding: 5px; margin-left: 3px;" name="submit_comment" class="btn btn-info btn-xs">Send</button>
                  <div class="clearfix"></div>
                </div>
              </form>
            </div>
@endsection

@section('body_bottom')

  
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
  <link href="/bower_components/tags/css/jquery.tagit.css" rel="stylesheet" type="text/css"/>
<link href="/bower_components/tags/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css"/>

  <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/bower_components/tags/js/tag-it.js"></script>
<script type="text/javascript">
  
     $(function() {
   
         jQuery("#peoples").tagit({
      singleField: true,
      singleFieldNode: $('#peoplesField'),
      allowSpaces: true,
      minLength: 2,
      placeholderText: 'Enter Participants name',
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
                    console.log(item);
                      return {
                          label: item.username + `(#${item.id})`,
                          value: item.username,
                          id: item.id
                      }
                  }));
              }
          });
      }
  });

$('.datepicker').datetimepicker({
//inline: true,
//format: 'YYYY-MM-DD',
format: 'YYYY-MM-DD', 
sideBySide: true,
allowInputToggle: true
});
    
 });


</script>
@endsection
