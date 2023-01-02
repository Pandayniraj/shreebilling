@extends('layouts.dialog')

@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')

@endsection
<style type="text/css">
    body {
        margin-top: 20px;
        color: #1a202c;
        text-align: left;
        background-color: #ffffff !important;
    }

    .main-body {
        padding: 15px;
    }

    .card {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 0 solid rgba(0, 0, 0, .125);
        border-radius: .25rem;
    }

    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding: 1rem;
    }

    .gutters-sm {
        margin-right: -8px;
        margin-left: -8px;
    }

    .gutters-sm>.col,
    .gutters-sm>[class*=col-] {
        padding-right: 8px;
        padding-left: 8px;
    }

    .mb-3,
    .my-3 {
        margin-bottom: 1rem !important;
    }

    .bg-gray-300 {
        background-color: #e2e8f0;
    }

    .h-100 {
        height: 100% !important;
    }

    .shadow-none {
        box-shadow: none !important;
    }

    .autocompleteUserAvatar {
        width: 50px;
        height: 50px;
    }

    .editable-pre-wrapped {
    white-space: normal !important;

    .thumbnail{
    max-width: 100px !important;
    max-height: 100px !important;
    height: auto;
    float: left;
    }
}

</style>
@section('content')
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
<link href="/bower_components/tags/css/jquery.tagit.css" rel="stylesheet" type="text/css" />
<link href="/bower_components/tags/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css" />
<div class="container">
    <div class="main-body">

        <!-- Breadcrumb -->
        <span id='task_status' class="pull-right"></span>

        <!-- /Breadcrumb -->

        <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="{{ TaskHelper::getProfileImage($task->user_id) }}" class="rounded-circle" width="85" height="85" style="margin-left: 20px;">
                            <div class="mt-3">
                                <span style="font-size: 19px"> {{ $task->user->first_name}} {{ $task->user->last_name}}</span><br/>
                                <span class="text-secondary mb-1"> {{ PayrollHelper::getDepartment($task->user->departments_id) }}</span> &nbsp
                                <span class="text-muted font-size-sm">{{ PayrollHelper::getDesignation($task->user->designations_id) }}</span><br/><br/>
                                <a class="btn btn-default btn-xs" href="javascript:window.open('','_self').close()" type="button">{{ trans('admin/projects/general.button.save_close') }}</a>
                                <a class="btn btn-default  btn-xs" href="{!! route('admin.project_task.edit', $task->id) !!}">{{ trans('admin/projects/general.button.edit') }}</a>
                                @if ( $task->isDeletable() )
                                <a class="btn btn-default  btn-xs delete-task" href="#" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}">{{ trans('admin/projects/general.button.delete') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <a href="javascript:void()">
                                <i style="color: red" class="fa fa-calendar"></i> </a>
                            {{ trans('admin/projects/general.columns.due') }}: &nbsp;&nbsp;&nbsp;
                            <input class="datepicker_end_date" style="width: 120px;border:none;" value="{{date('d M y',strtotime($task->end_date))}}">

                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <a href="javascript:void()">
                                <i style="color: red" class="fa fa-exclamation"></i>
                            </a>&nbsp;
                            {{ trans('admin/projects/general.columns.priority') }}:&nbsp;&nbsp;&nbsp;<span id='taskpriority' data-type="select" data-pk="1" data-title="Select status"></span>

                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">


                            <a href="javascript:void()">
                                <i class="fa  fa-line-chart"></i>
                            </a>
                            {{ trans('admin/projects/general.columns.progress') }}:&nbsp;&nbsp;&nbsp;<span class="progress"> {!! $task->percent_complete !!}</span> %


                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <a href="javascript:void()">
                                <i class="fa fa-clock-o"></i></a>
                            {{ trans('admin/projects/general.columns.estimated_duration') }}: <span class="taskdays">{!! substr($task->duration, 0, -1) !!} </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <a href="javascript:void()">
                                <i class="fa fa-calendar"></i>
                            </a>
                            {{ trans('admin/projects/general.columns.start_date') }}: <input class="datepicker_start_date" style="width: 100px;border:none;" value="{{date('d M y',strtotime($task->start_date))}}">

                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <a href="javascript:void()">
                                <i class="fa  fa-tasks"></i>
                            </a>
                            {{ trans('admin/projects/general.columns.category') }}: <span id='category_id' data-type="select" data-pk="1" data-title="Select category"></span>

                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                          <a href="javascript:void()">
                          <i class="fa  fa-tasks"></i>
                         Sub Category: <span id='sub_cat' data-type="select" data-pk="1" data-title="Select sub category"></span>
                        </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <a href="javascript:void()">
                                <i class="fa   fa-industry"></i>
                            </a>
                            {{ trans('admin/projects/general.columns.project') }}: <span id='project_id' data-type="select" data-pk="1" data-title="Change Project"></span>


                        </li>

                    </ul>
                </div>
            </div>
            <div class="col-md-8">

                    <div class="">
                         <nav aria-label="" class="">
                                    <span style="font-size: 26px;font-weight: bold" class="breadcrumb">#{!! $task->id !!}. <span id="edit_task_subject">{!! $task->subject !!}</span> </span>

                                </nav>

                                <p style="margin-bottom:;font-size: 16.5px" placeholder="Write Description" class="task_description"> {!! $task->description !!} <br/>
                                </p>

                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                               <div class="col-md-9 label label-primary" style="width: 90px;font-weight: bold; font-size: 15px;padding: 10px">
                                <span id='task_status_update' data-type="select" data-pk="1" data-title="Change Status"></span>
                            </div>
                            </div>
                            <div class="col-md-10 text-secondary">
                                <span class="txtfield">
                                    <ul id="peoples"></ul>
                                </span>
                                <input type="hidden" class="form-control peoples" name="peoples" id="peoplesField" value="{{implode(',', $peoples)}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="margin: 0;padding: 3;margin-left: -8px;margin-top: -3px;">
                        <div class="card">
                            <div class="card-body">
                                <form action="/admin/post_comment" method="post" enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <!-- .img-push is used to add margin to elements next to floating images -->
                                    <div class="img-push">
                                        <div class="col-md-9">
                                            <textarea type="text" style="float: left;" class="form-control input-sm" name="comment_text" placeholder="{{ trans('admin/projects/general.placeholder.comment_post') }}"></textarea>
                                            <input type="hidden" name="type" value="project_task">
                                            <input type="hidden" name="master_id" id="master_id" value="{{$task->id}}">
                                            <br /><br /> <br />

                                            <input style="display: inline-block;" type="file" name="file_name" id="file_name">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" style="padding: 5px; margin-left: 3px;" name="submit_comment" class="btn btn-info btn-xs">Save Message</button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                                <hr>
                                @foreach($comments as $ck => $cv)
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-info clearfix">
                                         <img class="direct-chat-img" style="height: 25px; width: 25px" src="{{$cv->user->avatar}}" alt="photo">
                                        <span style="padding-left: 5px; margin-left: 0px" class="direct-chat-name pull-left"><strong>{{ mb_substr($cv->user->first_name,0,3) }} {{ $cv->user->first_name }}
                                                #({{ $cv->user->id }})</strong></span>
                                        <span class="direct-chat-timestamp pull-right"> {{ date('dS M y', strtotime($cv->created_at)) }} <a href="" data-toggle="modal" class="delete-comment" onclick="deleteComment({{$cv->id}},event)" data-target="#modal_dialog"  title="Delete"><i style="color: red" class="fa fa-trash deletable"></i></a></span>
                                    </div>
                                    <!-- /.direct-chat-info -->

                                    <!-- /.direct-chat-img -->
                                    <div class="" style="font-size: 16px">
                                        {!! nl2br($cv->comment_text) !!}
                                        <br>
                                        @if($cv->file)
                                        <br /><i class="fa fa-paperclip"></i> File: <a href="/files/{{ $cv->file }}" target="_blank">{{ $cv->file }}</a>
                                        @endif
                                    </div>

                                    <!-- /.direct-chat-text -->
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin: 0;padding: 3;margin-left: -8px;margin-top: -3px;">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <h5 class="d-flex align-items-center mb-3 col-md-4 "><i class="fa  fa-film"></i>
                                        &nbsp;{{ trans('admin/projects/general.columns.attachment') }}</h5>
                                    <form class="col-md-8 pull-right" action="{{route('admin.project_task.attachment.upload')}}" method="post" enctype="multipart/form-data" style="display: flex">
                                        @csrf
                                        <input name="attachment" type="file" style="width: 175px">
                                        <input name="task_id" value="{{$task->id}}" type="hidden">
                                        <input class="btn btn-primary" type="submit" value="upload">
                                    </form>
                                </div>

                                <div class="row">
                                    @if($task->attachment != '' && $task->attachment != 'Array')
                                    <div class="">
                                        <div class="thumbnail img-responsive img-enlargable">
                                            <a href="/task_attachments/{{$task->attachment}}" download="{{$ta->attachment}}">
                                                @if(is_array(getimagesize(public_path().'/task_attachments/'.$task->attachment)))
                                                <img src="/task_attachments/{{$task->attachment}}" alt="task_img" style="width:100%">
                                                @else
                                                <span class="mailbox-attachment-icon" style="height: 120px;"><i class="fa fa-file-pdf-o"></i></span>
                                                @endif
                                                <div class="caption" style="padding: 0 !important;">
                                                    <p style="padding: 0; margin: 0;">{{ substr($ta->attachment,-10) }}..</p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    @foreach($task_attachments as $key => $ta)
                                            <a href="" data-toggle="modal" class="delete-comment" onclick="deleteAttachment({{$ta->id}},event)" data-target="#modal_dialog"  title="Delete"><i style="color: red" class="fa fa-trash deletable"></i></a>
                                        @if(is_array(getimagesize(public_path().'/task_attachments/'.$ta->attachment)))
                                    <div class="col-md-5 col-sm-5">
                                        <div class="thumbnail">
                                            <a href="/task_attachments/{{$ta->attachment}}" download="{{$ta->attachment}}">

                                                <img src="/task_attachments/{{$ta->attachment}}" alt="task_img" style="height: 88px;">
                                                <div class="caption" style=" border-top: 1px solid black;">
                                                    <p style="padding: 0; margin: 0;">
                                                        {{ substr($ta->attachment,-10) }}..
                                                    </p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-md-5 col-sm-5">
                                        <div class="thumbnail">
                                            <a href="/task_attachments/{{$ta->attachment}}" download="{{$ta->attachment}}">

                                                <span class="mailbox-attachment-icon" style="height: 120px;"><i class="fa fa-file-pdf-o"></i></span>

                                                <div class="caption" style="padding: 0 !important;margin-top: -15px; border-top: 1px solid black;">
                                                    <p style="padding: 0; margin: 0;">
                                                        {{ substr($ta->attachment,-10) }}..
                                                    </p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<link href="/x-editable/bootstrap-editable.css" rel="stylesheet" />
<script src="/x-editable/bootstrap-editable.min.js"></script>
<script>
    const task_id = '{{$task->id}}';
    var isChanged = false;
    const currentPeople = $('#peoplesField').val();
    const task_status = [
        @foreach($project_status as $key => $stat) {
            value: '{{$key}}'
            , text: '{{$stat}}'
        }
        , @endforeach
    ];

    function deleteComment(id,event) {
        let c = confirm('Are you sure you want to delete the comment');
        if (c) {
            $.get('/admin/post_comment/delete/'+id, function(response) {
                if (response.status == 1) {
                    location.reload()
                }
            });
        }
        return false;

    }
    function deleteAttachment(id,event) {
        let c = confirm('Are you sure you want to delete the attachment?');
        if (c) {
            $.get('/admin/task/attachment/delete/'+id, function(response) {
                if (response.status == 1) {
                    location.reload()
                }
            });
        }
        return false;

    }
    $('#task_status_update').editable({
        value: '{{ $task->status }}'
        , source: task_status
        , success: function(response, newValue) {
            var taskId = $('#master_id').val();
            $.post("/admin/ajax_proj_task_status", {
                    id: taskId
                    , status: newValue
                    , _token: $('meta[name="csrf-token"]').attr('content')
                }
                , function(data, status) {
                    if (data.status == '1')
                        $("#task_status").after("<span style='color:green; margin-left:15px;' id='status_update'>Status is successfully updated.</span>");
                    else
                        $("#task_status").after("<span style='color:red; margin-left:15px;' id='status_update'>Problem in updating status; Please try again.</span>");

                    $('#status_update').delay(3000).fadeOut('slow');
                    isChanged = true;
                    //alert("Data: " + data + "\nStatus: " + status);
                });
            handleChange(newValue, 'priority');
        }
    , });

    function handleChange(value, type) {
        $.post("/admin/ajaxTaskUpdate", {
                id: task_id
                , update_value: value
                , type: type
                , _token: $('meta[name="csrf-token"]').attr('content')
            }
            , function(data) {
                if (data.status == '1')
                    $("#task_status").after("<span style='color:green; margin-left:15px;' id='status_update'>" + type + " is successfully updated.</span>");
                else
                    $("#task_status").after("<span style='color:red; margin-left:15px;' id='status_update'>Problem in updating status; Please try again.</span>");

                $('#status_update').delay(3000).fadeOut('slow');
                isChanged = true;
                //alert("Data: " + data + "\nStatus: " + status);
            });
    }

    $('.datepicker_end_date').datepicker({
        dateFormat: 'd M y'
        , sideBySide: true
        , onSelect: function(dateText) {
            handleChange(dateText, 'end_date');
        }
    });

    $('.datepicker_start_date').datepicker({
        dateFormat: 'd M y'
        , sideBySide: true
        , onSelect: function(dateText) {
            handleChange(dateText, 'start_date');
        }
    });
    $('.task_description').editable({
        type: 'textarea'
        , pk: 1
        , url: null
        , placement: 'bottom'
        , title: `Task description`
        , success: function(response, newValue) {
            handleChange(newValue, 'description');
        }
    , })
    $('#edit_task_subject').editable({
        type: 'textarea'
        , placement: 'bottom'
        , success: function(response, newValue) {
            handleChange(newValue, 'subject');
        }
    })
    $('.progress').editable({
        success: function(response, newValue) {
            handleChange(newValue, 'percent_complete');
        }
        , validate: function(value) {
            if ($.isNumeric(value) == '') {
                return 'Only Numberical value is allowed';
            } else if (Number(value) > 100 || Number(value) < 0) {
                return 'Task Progress Can Be Between 0 to 100';
            }
        }
    });
    $('.taskdays').editable({
        success: function(response, newValue) {
            handleChange(newValue, 'duration');
        }
    });
    // $('#taskpriority').change(function(){
    //   handleChange($(this).val(),'priority');
    // });

    $('#taskpriority').editable({
        value: '{{$task->priority}}'
        , source: [{
                value: 'Low'
                , text: 'Low'
            }
            , {
                value: 'Medium'
                , text: 'Medium'
            }
            , {
                value: 'High'
                , text: 'High'
            }
            , {
                value: 'Urgent'
                , text: 'Urgent'
            }
            , {
                value: 'None'
                , text: 'None'
            }
        ]
        , success: function(response, newValue) {
            handleChange(newValue, 'priority');
        }
    , });



    $('#category_id').editable({
            value: '{{$task->category_id}}'
            , source: <?php echo json_encode($cat) ?>,
    success: function(response, newValue) {
              location.reload();
    handleChange(newValue,'category');
  },
});

    $('#sub_cat').editable({
    value: '{{$task->sub_cat_id}}',
    source: <?php echo json_encode($sub_cat) ?>,
    success: function(response, newValue) {
    handleChange(newValue,'sub_cat_id');
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

    $('.delete-task').click(function() {
        let c = confirm('Are you sure you want to delete the task');
        if (c) {
            $.get('/admin/project_task/{{$task->id}}/delete', function(response) {
                if (response.status == 1) {
                    isChanged = true;
                    window.opener.UpdateChanges(isChanged);
                    window.close();
                }
            });
        }
        return false;
    });

    function addenlargeclass() {
        $('img[data-enlargable]').addClass('img-enlargable').click(function() {
            var src = $(this).attr('src');
            var modal;

            function removeModal() {
                modal.remove();
                $('body').off('keyup.modal-close');
            }
            modal = $('<div>').css({
                background: 'RGBA(0,0,0,.5) url(' + src + ') no-repeat center'
                , backgroundSize: 'contain'
                , width: '100%'
                , height: '100%'
                , position: 'fixed'
                , zIndex: '10000'
                , top: '0'
                , left: '0'
                , cursor: 'zoom-out'
            }).click(function() {
                removeModal();
            }).appendTo('body');
            //handling ESC
            $('body').on('keyup.modal-close', function(e) {
                if (e.key === 'Escape') {
                    removeModal();
                }
            });
        });
    }

</script>
