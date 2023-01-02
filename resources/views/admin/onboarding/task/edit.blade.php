@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

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
         <div class="box-body ">
          <form method="post" action="/admin/onboard/task/edit/{{$tasks->id}}" enctype="multipart/form-data">  
          {{ csrf_field() }}                 
         

                     <div class="row">
                    <div class="col-md-6 form-group">
                          <label class="control-label">Events</label>
                            <select name="event_id" class="form-control searchable" >
                       <option value="">Select events</option>
                       @foreach($task_event as $te)
                       <option value="{{$te->id}}" @if($tasks->event_id == $te->id) selected @endif>{{ucfirst($te->name)}}(#{{$te->id}})</option>
                       @endforeach
                      </select>
                      </div>
                        
                     </div>
                      <h4>Task info</h4>
                       <div class="row">
                        <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Name</label>
                           <div class="input-group ">
                            <input type="text" name="name" placeholder="Task name" value="{{$tasks->name}}" class="form-control" id="tname">
                              <div class="input-group-addon">
                                   <a href="#"><i class="fa  fa-tasks"></i></a>
                                </div> 
                        </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Notify Email</label>
                           <div class="input-group ">
                            <input type="email" name="notify_mail" placeholder="Notify Email" id="temail" value="{{$tasks->notify_mail}}" class="form-control" >
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
                            <input type="number" name="notified_before" placeholder="Notify before x days" id="tnotified_before" value="{{$tasks->notified_before}}" class="form-control" >
                              <div class="input-group-addon">
                                   <a href="#"><i class="fa fa-calendar-times-o"></i></a>
                                </div> 
                        </div>
                    </div>
                  </div>
                       
                     </div>
                     <div class="row">
                         <div class="col-md-4 form-group">
                          <label class="control-label" >Owner</label>
                            <select name="task_owner" class="form-control" id="towner_id">
                       <option value="">Select Task Type Owner</option>
                       @foreach($owner as $o)
                       <option value="{{$o->id}}" @if($tasks->task_owner == $o->id) selected @endif >{{ucfirst($o->username)}}</option>
                       @endforeach
                      </select>
                      </div>
                      </div>
                 <h4>Schedule</h4>
                  <div class="row">
                      
                         <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Effective date</label>
                           <div class="input-group ">
                            <input type="text" name="effective_date" placeholder="Effective date" id="" value="{{$tasks->effective_date}}" class="form-control datepicker" >
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
                            <input type="text" name="due_date" placeholder="Due date" id="" value="{{$tasks->due_date}}" class="form-control datepicker" >
                              <div class="input-group-addon">
                                   <a href="#"><i class="fa fa-calendar"></i></a>
                                </div> 
                        </div>
                    </div>
                  </div>
                         <div class="col-md-4 form-group">
                          <label class="control-label" >Priority</label>
                             <div class="input-group ">
                            <select name="priority" class="form-control" >
                       <option value="">Select priority</option>
                       @foreach($priority as $p)
                       <option value="{{$p}}" @if($tasks->priority == $p) selected @endif>{{ucfirst($p)}}</option>
                       @endforeach
                      </select>
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
                          <textarea class="form-control" name="description" id="tdescription" placeholder="Task Type Description">{!! $tasks->description !!}</textarea>
                        </div>
                        
                      </div>
       <br>
            <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                          <a href="{!! route('admin.onboard.task') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                      </div>
                   </div>
              </div>

          </form>
    </div>
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
    $('.searchable').select2();
    $('#towner_id').select2();
    });
 
$(document).on("keydown", "form", function(event) { 
    return event.key != "Enter";
});
$('#task_owner').change(function(){
  let id = $(this).val();
  if(id){
       $.ajax({
              url: `/admin/onboard/task/tasktype/${id}`,
              dataType: "json",
              success: function( data ) {
                  $('#tname').val(data.name);
                  $('#temail').val(data.notify_email);
                  $('#tnotified_before').val(data.notified_before);
                  $('#towner_id').select2('destroy');
                  $('#towner_id').val(data.owner_id);
                  $('#towner_id').select2();
                  $('#tdescription').val(data.description.trim());
              }
          });
     }
  else{
      $('#tname').val('');
      $('#temail').val('');
      $('#tnotified_before').val(''); 
      $('#towner_id').select2('destroy');
      $('#towner_id').val('');
      $('#towner_id').select2();
      $('#tdescription').val('');
  }
})
</script>
@endsection
