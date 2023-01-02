@extends('layouts.dialog')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')
<style>
.header { padding:10px 0; }
.col-md-4 { padding-right: 0; padding-left: 5px; }
.lists { padding-left: 10px; }

.box-comment { margin-bottom: 5px; padding-bottom: 5px; border-bottom: 1px solid #eee; }
.box-comment img {float: left; margin-right:10px;}
.username { font-weight: bold; }
.comment-text span{display: block;}

      [data-letters]:before {
            content:attr(data-letters);
            display:inline-block;
            font-size:1em;
            width:2.5em;
            height:2.5em;
            line-height:2.5em;
            text-align:center;
            border-radius:50%;
            background:red;
            vertical-align:middle;
            margin-right:0.3em;
            color:white;
            }
  </style>


  <style type="text/css">
    /***
User Profile Sidebar by @keenthemes
A component of Metronic Theme - #1 Selling Bootstrap 3 Admin Theme in Themeforest: https://j.mp/metronictheme
Licensed under MIT
***/

body {
  background: #F1F3FA;
}

/* Profile container */
.profile {
  margin: 20px 0;
}

/* Profile sidebar */
.profile-sidebar {
  padding: 20px 0 10px 0;
  background: #fff;
}

.profile-userpic img {
  float: none;
  margin: 0 auto;
  width: 50%;
  height: 50%;
  -webkit-border-radius: 50% !important;
  -moz-border-radius: 50% !important;
  border-radius: 50% !important;
}

.profile-usertitle {
  text-align: center;
  margin-top: 20px;
}

.profile-usertitle-name {
  color: #5a7391;
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 7px;
}

.profile-usertitle-job {
  text-transform: uppercase;
  color: #5b9bd1;
  font-size: 12px;
  font-weight: 600;
  margin-bottom: 15px;
}

.profile-userbuttons {
  text-align: center;
  margin-top: 10px;
}

.profile-userbuttons .btn {
  text-transform: uppercase;
  font-size: 11px;
  font-weight: 600;
  padding: 6px 15px;
  margin-right: 5px;
}

.profile-userbuttons .btn:last-child {
  margin-right: 0px;
}
    
.profile-usermenu {
  margin-top: 30px;
}

.profile-usermenu ul li {
  border-bottom: 1px solid #f0f4f7;
}

.profile-usermenu ul li:last-child {
  border-bottom: none;
}

.profile-usermenu ul li a {
  color: #93a3b5;
  font-size: 14px;
  font-weight: 400;
}

.profile-usermenu ul li a i {
  margin-right: 8px;
  font-size: 14px;
}

.profile-usermenu ul li a:hover {
  background-color: #fafcfd;
  color: #5b9bd1;
}

.profile-usermenu ul li.active {
  border-bottom: none;
}

.profile-usermenu ul li.active a {
  color: #5b9bd1;
  background-color: #f6f9fb;
  border-left: 2px solid #5b9bd1;
  margin-left: -2px;
}

/* Profile Content */
.profile-content {
  padding: 20px;
  background: #fff;
  min-height: 460px;
}
/*.tagit-label{
  color: red;
}*/
a, button, code, div, img, input, label, li, p, pre, select, span, svg, table, td, textarea, th, ul {
    -webkit-border-radius: 0!important;
    -moz-border-radius: 0!important;
    border-radius: 0!important;
}
.dashboard-stat, .portlet {
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -ms-border-radius: 4px;
    -o-border-radius: 4px;
}
.portlet {
    margin-top: 0;
    margin-bottom: 25px;
    padding: 0;
    border-radius: 4px;
}
.portlet.bordered {
    border-left: 2px solid #e6e9ec!important;
}
.portlet.light {
    padding: 12px 20px 15px;
    background-color: #fff;
}
.portlet.light.bordered {
    border: 1px solid #e7ecf1!important;
}
.list-separated {
    margin-top: 10px;
    margin-bottom: 15px;
}
.profile-stat {
    padding-bottom: 20px;
    border-bottom: 1px solid #f0f4f7;
}
.profile-stat-title {
    color: #7f90a4;
    font-size: 25px;
    text-align: center;
}
.uppercase {
    text-transform: uppercase!important;
}

.profile-stat-text {
    color: #5b9bd1;
    font-size: 10px;
    font-weight: 600;
    text-align: center;
}
.profile-desc-title {
    color: #7f90a4;
    font-size: 17px;
    font-weight: 600;
}
.profile-desc-text {
    color: #7e8c9e;
    font-size: 14px;
}
.margin-top-20 {
    margin-top: 20px!important;
}
[class*=" fa-"]:not(.fa-stack), [class*=" glyphicon-"], [class*=" icon-"], [class^=fa-]:not(.fa-stack), [class^=glyphicon-], [class^=icon-] {
    display: inline-block;
    line-height: 14px;
    -webkit-font-smoothing: antialiased;
}
.profile-desc-link i {
    width: 22px;
    font-size: 19px;
    color: #abb6c4;
    margin-right: 5px;
}
.autocompleteUserAvatar{
  width: 50px;
  height: 50px;
}


  </style>
  <script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
<script src="{{ asset("/bower_components/admin-lte/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>
<!-- iCheck -->
<script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}" type="text/javascript"></script>
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />

<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<!-- include tags scripts and css -->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="/bower_components/tags/js/tag-it.js"></script>
<link href="/bower_components/tags/css/jquery.tagit.css" rel="stylesheet" type="text/css"/>
<link href="/bower_components/tags/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css"/>
  <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">

            <div class="col-lg-12">
                        <h1 class="content-max-width">   
                          #{!! $task->id !!}. 
                          <span id="edit_task_subject">{!! $task->subject !!}</span> 
                         
                        </h1>
                    </div>
                     {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>




<div class='row'>
    <div class='col-md-12'>


      <div class="col-md-3">
      <div class="profile-sidebar">
        <!-- SIDEBAR USERPIC -->
        <div class="profile-userpic">
          <img src="{{ TaskHelper::getProfileImage($task->user_id) }}" class="img-responsive" alt="">
        </div>
        <!-- END SIDEBAR USERPIC -->
        <!-- SIDEBAR USER TITLE -->
        <div class="profile-usertitle">
          <div class="profile-usertitle-name">
            {{ $task->user->first_name}}  {{ $task->user->last_name}}
          </div>
          <div class="profile-usertitle-job">

            {{ PayrollHelper::getDepartment($task->user->departments_id) }}, 
            {{ PayrollHelper::getDesignation($task->user->designations_id) }}
          </div>
        </div>
        <!-- END SIDEBAR USER TITLE -->
        <!-- SIDEBAR BUTTONS -->
        <div class="profile-userbuttons">
          <a href="javascript:window.open('','_self').close()" type="button" class="btn btn-success btn-sm">Close</a>
          
        </div>
        <!-- END SIDEBAR BUTTONS -->
        <!-- SIDEBAR MENU -->
        <div class="profile-usermenu">
          <ul class="nav">
            <li class="active">
              <a href="javascript:void()">
              <i style="color: red" class="fa fa-calendar"></i>
              Due: 
              <input class="datepicker_end_date" style="width: 120px;border:none;" 
              value="{{date('d M y',strtotime($task->end_date))}}">
              </a>
            </li>
            <li>
              <a href="javascript:void()">
              <i style="color: red" class="fa fa-exclamation"></i>
              Priority: <span id='taskpriority' data-type="select" data-pk="1" data-title="Select status"></span> 
              </a>
            </li>
            <li>
              
              <a href="javascript:void()">
              <i class="glyphicon glyphicon-ok"></i>
              Progress :<span class="progress"> {!! $task->percent_complete !!}</span> %</a>
            </li>
            <li>
              <a href="javascript:void()">
              <i class="fa fa-clock-o"></i>
              Estimated: <span class="taskdays">{!! substr($task->duration, 0, -1) !!} </span>Days</a>
            </li>
            <li>
              <a href="javascript:void()">
              <i class="fa fa-calendar"></i>
              Started: <input class="datepicker_start_date" style="width: 100px;border:none;" 
              value="{{date('d M y',strtotime($task->start_date))}}">
            </a>
            </li>
            <li>
              <a href="javascript:void()">
              <i class="fa  fa-tasks"></i>
              Category: <span id='category_id' data-type="select" data-pk="1" data-title="Select category"></span> 
            </a>
            </li>
            <li>
              <a href="javascript:void()">
              <i class="fa  fa-tasks"></i>
              Projects: <span id='project_id' data-type="select" data-pk="1" data-title="Change Project"></span> 
            </a>
            </li>
          </ul>
        </div>
        <!-- END MENU -->

      </div>
    </div>



      <div class="box-header col-md-9">
                                  
                        
                        @if ( $task->isEditable() || $task->canChangePermissions() )
                            <a href="{!! route('admin.project_task.edit', $task->id) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i> {{ trans('general.button.edit') }}</a>
                        @endif
                        @if ( $task->isDeletable() )
                            <a href="#" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}" class="btn btn-xs btn-default delete-task"> <i class="fa fa-trash"></i> {{ trans('general.button.delete') }}</a>
                        @endif
              
                     


                  
                      Change Status: 
                      {!! Form::select('status', $project_status, $task->status, ['class' => 'input-xs', 'id'=>'task_status']) !!}

                      

                      <p class="lead task_description"> {!! $task->description !!} </p>

        </span>

        @if($task->attachment)
        <span class="pull-right">
          <img style="height: 250px; width: auto;" src="/task_attachments/{{$task->attachment}}">
        </span>\
        @endif
                      
      </div>

      
     



      <div class="">
          <div class="col-md-8">
             <a href="javascript:void()" >
              <i class="fa fa-users"></i>
              Peoples
              <span class="txtfield"><ul id="peoples"></ul></span>
            <input type="hidden" class="form-control peoples" name="peoples" id="peoplesField" value="{{implode(',', $peoples)}}" >
            </a>
          </div>

          <div class="col-md-8">
            <div class="box-footer box-comments">
            <h4> <i class="fa fa-comments"></i> Discuss Updates</h4>
            @foreach($comments as $ck => $cv)
              <div class="box-comment">
                <!-- User image -->
               
                

                <div class="comment-text">
                      <span class="username">
                       <span data-letters="{{ mb_substr($cv->user->first_name,0,3) }}"> {{ $cv->user->first_name }}</span>
                       
                      </span><!-- /.username -->
                      {{ date('dS M y', strtotime($cv->created_at)) }}<br/>
                  {{ $cv->comment_text }}
                  @if($cv->file)
                    <br/><i class="fa fa-paperclip"></i> File: <a href="/files/{{ $cv->file }}" target="_blank">{{ $cv->file }}</a>
                  @endif
                </div>
                <div class="clearfix"></div>
              </div>
            @endforeach
              

            </div>

            <div class="box-footer">
              <form action="/admin/post_comment" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <!-- .img-push is used to add margin to elements next to floating images -->
                <div class="img-push">
                  <div class="col-md-9">
                    <input type="text" style="width:90%; float: left;" class="form-control input-sm" name="comment_text" placeholder="Press enter to post comment">
                    <input type="hidden" name="type" value="project_task">
                    <input type="hidden" name="master_id" id="master_id" value="{{$task->id}}">
                    <br/><br/>
                    <label for="file_name"><i class="fa fa-paperclip"></i> File</label>
                    <input style="display: inline-block;" type="file" name="file_name" id="file_name">
                  </div>
                  <div class="col-md-3">
                    <button type="submit" style="padding: 5px; margin-left: 3px;" name="submit_comment" class="btn btn-info btn-xs">Send</button>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </form>
            </div>
          </div>
          <div class="clearfix"></div>

         
    </div>

  </div>
</div>
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script>
  const task_id = '{{$task->id}}';
  var isChanged = false;
  const currentPeople = $('#peoplesField').val();
  $(document).on('change', '#task_status', function() {
    var taskId = $('#master_id').val();
    var taskStatus = $(this).val();

    $.post("/admin/ajax_proj_task_status",
    {id: taskId, status: taskStatus, _token: $('meta[name="csrf-token"]').attr('content')},
    function(data, status){
      if(data.status == '1')
          $("#task_status").after("<span style='color:green; margin-left:15px;' id='status_update'>Status is successfully updated.</span>");
      else
          $("#task_status").after("<span style='color:red; margin-left:15px;' id='status_update'>Problem in updating status; Please try again.</span>");

      $('#status_update').delay(3000).fadeOut('slow');
      isChanged = true;
      //alert("Data: " + data + "\nStatus: " + status);
    });
  });

    function handleChange(value,type){
      $.post("/admin/ajaxTaskUpdate",
      {id: task_id, update_value: value,type:type ,_token: $('meta[name="csrf-token"]').attr('content')},
      function(data){
        if(data.status == '1')
          $("#task_status").after("<span style='color:green; margin-left:15px;' id='status_update'>"+type+" is successfully updated.</span>");
        else
          $("#task_status").after("<span style='color:red; margin-left:15px;' id='status_update'>Problem in updating status; Please try again.</span>");

        $('#status_update').delay(3000).fadeOut('slow');
        isChanged = true; 
        //alert("Data: " + data + "\nStatus: " + status);
      });
    }

$('.datepicker_end_date').datepicker({
  dateFormat: 'd M y',
  sideBySide: true,
  onSelect: function(dateText){
    handleChange(dateText,'end_date');
  }
});

$('.datepicker_start_date').datepicker({
  dateFormat: 'd M y',
  sideBySide: true,
  onSelect: function(dateText){
    handleChange(dateText,'start_date');
  }
});
$('.task_description').editable({
    type:'textarea',
    pk:1,
    url:null,
    placement:'bottom',
    title: `Task description`,
  success: function(response, newValue) {
    handleChange(newValue,'description');
  },
})
$('#edit_task_subject').editable({
    type:'textarea',
  placement:'bottom',
  success: function(response, newValue) {
    handleChange(newValue,'subject');
  }
})
$('.progress').editable({
  success: function(response, newValue) {
    handleChange(newValue,'percent_complete');
  },
    validate: function(value) {
          if ($.isNumeric(value) == '') {
              return 'Only Numberical value is allowed';
          }else if(Number(value) > 100 || Number(value) < 0){
             return 'Task Progress Can Be Between 0 to 100';
          }
      }
});
$('.taskdays').editable({
  success: function(response, newValue) {
    handleChange(newValue,'duration');
  },
    validate: function(value) {
          if ($.isNumeric(value) == '') {
              return 'Only Numberical value is allowed';
          }
      }
});
// $('#taskpriority').change(function(){
//   handleChange($(this).val(),'priority');
// });

$('#taskpriority').editable({
    value: '{{$task->priority}}',
    source: [
            {value: 'Low', text: 'Low'},
              {value: 'Medium', text: 'Medium'},
              {value: 'High', text: 'High'},
              {value:'Urgent',text:'Urgent'},
              {value:'None',text:'None'}
           ],
    success: function(response, newValue) {
    handleChange(newValue,'priority');
  },
});
$('#category_id').editable({
    value: '{{$task->category_id}}',
    source: <?php echo json_encode($cat) ?>,
    success: function(response, newValue) {
    handleChange(newValue,'category');
  },
});
$('#project_id').editable({
    value: '{{$task->project_id}}',
    source: <?php echo json_encode($projects) ?>,
    success: function(response, newValue) {
    let c = confirm("Are sure you want to change project");
    if(c)
      handleChange(newValue,'projects');
    else
      return false;
  }
})

// $('#category_id').change(function(){
//   handleChange($(this).val(),'category');
// })
$('.searchable').select2({ dropdownAutoWidth: true });
  jQuery("#peoples").tagit({
      singleField: true,
      singleFieldNode: $('#peoplesField'),
      allowSpaces: true,
      minLength: 2,
      tagLimit: 5,
      placeholderText: 'Enter User Name',
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
                    console.log(item)
                      return {
                          label: item.username ,
                          value: item.value,
                          icon: item.icons
                      }
                  }));
              }
          });
      },
      onTagAdded:function(){
        tagitcss();
      }
  });
$( window ).unload(function() {
   var addedpeoples = $('#peoplesField').val();
   let a = addedpeoples.split(',');
   let p= currentPeople.split(',');
   var union = [...new Set([...a, ...p])];
   if((union.length > p.length) || (a.length < p.length )){
    window.opener.HandlePeopleChanges(addedpeoples,task_id,isChanged);
   }else{
    window.opener.UpdateChanges(isChanged);
   }
});
function tagitcss(){
  setTimeout(function(){
    $('.tagit .tagit-choice').attr('style','background-color:   #367FA9 !important')
    $('.tagit-label,.text-icon').attr('style','color: white !important'); 
},200)

}
monkeyPatchAutocomplete();

    function monkeyPatchAutocomplete()
    {
        $.ui.autocomplete.prototype._renderItem = function(ul, item) {
        var regexp = new RegExp(this.term);
        var highlightedVal = item.label.replace(regexp, "<span style='font-weight:bold;color:Blue;'>" + this.term + "</span>");
        return $("<li'></li>")
        .data("item.autocomplete", item)
        .append("<a><img class='autocompleteUserAvatar' src='" + item.icon + "' />" + highlightedVal + "</a>")
        .appendTo(ul);
        };
    }
   
$('.delete-task').click(function(){
  let c= confirm('Are you sure you want to delete the task');
  if(c){
    $.get('/admin/project_task/{{$task->id}}/delete',function(response){
        if(response.status == 1){
          isChanged = true;
          window.opener.UpdateChanges(isChanged);
          window.close();
        }
    });
  }
  return false;
})
</script>


@endsection

