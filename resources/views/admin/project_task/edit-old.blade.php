@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />

<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

<!-- include tags scripts and css -->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
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
                        return {
                            label: item.username ,
                            value: item.value
                        }
                    }));
                }
            });
        }
    });


    $('#end_date').datepicker({
        //inline: true,
         dateFormat: 'yy-mm-dd',
          sideBySide: true,
          allowInputToggle: true,
          changeMonth: true,
            changeYear: true,
            yearRange: "-2:+5"
    });

    $('#start_date').datepicker({
        //inline: true,
         dateFormat: 'yy-mm-dd',
          sideBySide: true,
          allowInputToggle: true,
          changeMonth: true,
            changeYear: true,
            yearRange: "-2:+5"
    });
});

function validate()
{
    $('.task_error').hide();
    var req_task = 0;

    var duration = $('#duration').val();
    if(duration.slice(-1) != 'd' && duration.slice(-1) != 'h')
    {
        $("#duration").after("<span style='color:red;' class='task_error'>Enter the Duration in days or hours. Ex: 5d or 5h).</span>");
        //return false;
        req_task++;
    }
    else
    {
        var numericReg = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;
        var duration_number = duration.slice(0, duration.length - 1);
        if(!numericReg.test(duration_number)) {
            $("#duration").after('<span style="color:red;" class="task_error">Enter number of days or hours before d or h for duration.</span>');
            //return false;
            req_task++;
        }
    }

    var actual_duration = $('#actual_duration').val();
    if(actual_duration.slice(-1) != 'd' && actual_duration.slice(-1) != 'h')
    {
        $("#actual_duration").after("<span style='color:red;' class='task_error'>Enter the Actual Duration in days or hours. Ex: 5d or 5h).</span>");
        //return false;
        req_task++;
    }
    else
    {
        var numericReg = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;
        var actual_duration_number = actual_duration.slice(0, actual_duration.length - 1);
        if(!numericReg.test(actual_duration_number)) {
            $("#actual_duration").after('<span style="color:red;" class="task_error">Enter number of days or hours before d or h for actual duration.</span>');
            //return false;
            req_task++;
        }
    }

    if(req_task != 0)
    {
        return false;
    }
    else return true;
}
</script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        
            <h1>
                Editing Task
              
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

                {!! Form::model( $task, ['route' => ['admin.project_task.update', $task->id], 'method' => 'PUT', 'id' => 'form_edit_task', 'onSubmit'=>'return validate();', 'enctype' => 'multipart/form-data'] ) !!}

                <div class="modal-body">
                    <div class="form-group">
                       
                        {!! Form::text('subject', $task->subject, ['class' => 'form-control input-lg required', 'id'=>'subject', 'required'=>'required']) !!}
                    </div>

                    <div class="form-group">
                        
                        <textarea rows="2" name="description" class="form-control required" id="description">{{$task->description}}</textarea>
                        <input type="hidden" name="project_id" id="project_id" value="{{ $task->project_id }}">
                    </div>

                    <div class="col-md-6">

                        <div class="form-group">
                            {!! Form::label('percent_complete', trans('admin/project-task/general.columns.percent_complete')) !!}

                            {!! Form::input('range','percent_complete', $task->percent_complete, ['class' => ' custom-range', 'id'=>'percent_complete']) !!}
                        </div>


                        <div class="form-group">
                            {!! Form::label('priority', trans('admin/project-task/general.columns.priority')) !!}
                            {!! Form::select('priority', ['Low'=>'Low', 'Medium'=>'Medium', 'High'=>'High'], $task->priority, ['class' => 'form-control input-sm']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('duration', trans('admin/project-task/general.columns.duration')) !!}
                            {!! Form::text('duration', $task->duration, ['class' => 'form-control', 'id'=>'duration']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('precede_tasks', trans('admin/project-task/general.columns.precede_tasks')) !!}
                            {!! Form::text('precede_tasks', $task->precede_tasks, ['class' => 'form-control', 'id'=>'precede_tasks']) !!}
                        </div>

                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('enabled', '1', $task->enabled) !!} {{ trans('general.status.enabled') }}
                            </label>
                        </div>

                        <div class="form-group">
                            {!! Form::label('attachment', 'Attachment') !!}
                          
                            @if($task->attachment != '' && $task->attachment != 'Array')

                                <strong>File: </strong>
                                <a href="{{url('/'). '/task_attachments/'.$task->attachment}}" target="_blank">{{$task->attachment}}</a>
                            @endif

                            @foreach($task_attachments as $key => $ta)
                            <br>
                            <i class="fa fa fa-paperclip"></i>
                                   <a href="{{url('/'). '/task_attachments/'.$ta->attachment}}" target="_blank">{{$ta->attachment}}</a>
                            @endforeach
                        </div>


                        <div class="form-group">
                            {!! Form::label('attachment', 'Attachment') !!} 
                            <a href="javascript::void"><i class="fa fa-plus" 
                            id='add-more'></i>
                          </a>
                            <div id='multiple-attachment'>
                              <table class="table">
                                <tr>
                                  <td>
                                    <input type="file" name="attachments[]" id="attachment">
                                  </td>
                                  <td>
                                    <a href="javascript::void()">
                                      <i class="fa fa-trash deletable remove-this"></i>
                                    </a>
                                  </td>
                                </tr>
                              </table>
                            </div>
                           
                        </div>


                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('task_order', trans('admin/project-task/general.columns.order')) !!} *
                            {!! Form::text('task_order', $task->order, ['class' => 'form-control', 'id'=>'order']) !!}
                        </div>

                        <div class="form-group">
                            Start Date
                            {!! Form::text('start_date', $task->start_date, ['class' => 'form-control', 'id'=>'start_date']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('end_date', trans('admin/project-task/general.columns.end_date')) !!} *
                            {!! Form::text('end_date', $task->end_date, ['class' => 'form-control', 'id'=>'end_date']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('peoples', trans('admin/project-task/general.columns.peoples')) !!}
                            <span class="txtfield"><ul id="peoples"></ul></span>
                            <input type="hidden" class="form-control peoples" name="peoples" id="peoplesField" value="{!! implode(',', $peoples) !!}" >
                        </div>

                        <div class="form-group">
                            {!! Form::label('actual_duration', trans('admin/project-task/general.columns.actual_duration')) !!}
                            {!! Form::text('actual_duration', $task->actual_duration, ['class' => 'form-control', 'id'=>'actual_duration']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('status', trans('admin/project-task/general.columns.status')) !!}
                            {!! Form::select('status', ['new'=>'New', 'ongoing'=>'On Going', 'completed'=>'Completed'], $task->status, ['class' => 'form-control input-sm']) !!}
                        </div>

                         <div class="form-group">
                            <label>Select Category</label>
                            {!! Form::select('category_id', $cat, $task->category_id, ['class' => 'form-control label-primary']) !!}
                        </div>

                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('milestone', '1', $task->milestone) !!} {!! trans('admin/project-task/general.columns.milestone') !!}
                            </label>
                        </div>
                        <div class="form-group">


                          <input type="checkbox" name="schedule" id="sheduled" value="{{$task->schedule}}">
                            {!! Form::label('schedule', 'Schedule') !!}
                        </div>
                        <div class="form-group" id="timespan" @if($task->schedule != 'on')  style="display:none;" @endif>
                          {!! Form::label('weekly', 'Weekly') !!}
                          <input type="radio" name="timespan" value="weekly" @if($task->schedule_type == 'weekly') selected @endif><br>

                          {!! Form::label('Monthly', 'Monthly') !!}
                          <input type="radio" name="timespan" value="monthly" @if($task->schedule_type == 'monthly') selected @endif><br>

                          {!! Form::label('Twice a Month', 'Twice a Month') !!}
                          <input type="radio" name="timespan" value="twice_a_month" @if($task->schedule_type == 'twice_a_month') selected @endif><br>

                          {!! Form::label('Three Months', 'Three Months') !!}
                          <input type="radio" name="timespan" value="three_months"><br>

                          {!! Form::label('Six Months', 'Six Months') !!}
                          <input type="radio" name="timespan" value="six_months"><br>

                          {!! Form::label('Two Months', 'Two Months') !!}
                          <input type="radio" name="timespan" value="two_months"><br>

                          {!! Form::label('Yearly', 'Yearly') !!}
                          <input type="radio" name="timespan" value="yearly"><br>

                        </div>

                    </div>
                    <div class="clearfix"></div>

              </div>

                <div class="form-group">
                    {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="/admin/project_task/{!! $task->id !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>

                {!! Form::close() !!}

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
    <script type="text/javascript">

    $('#sheduled').change(function() {
        $('#timespan').toggle();
    });
    </script>

 <div id="more-td" style="display: none">
    <table class="table">
          <tr>
            <td>
              <input type="file" name="attachments[]" id="attachment">
            </td>
            <td>
              <a href="javascript::void()">
                <i class="fa fa-trash deletable remove-this"></i>
              </a>
            </td>
          </tr>
      </table>
</div>

<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
<script>
    
CKEDITOR.replace( 'description', {
toolbar: [
{ name: 'document', items: [ 'Source', '-', 'NewPage', 'Preview', '-', 'Templates' ] }, // Defines toolbar group with name (used to create voice label) and items in 3 subgroups.
[ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],  // Defines toolbar group without name.
                                                                       // Line break - next group will be placed in new line.
{ name: 'basicstyles', items: [ 'Bold', 'Italic' ] },
{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Language' ] },
{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] }
]
});
</script>

<script type="text/javascript">
$('#add-more').click(function(){
  let html = $('#more-td table').html();

$('#multiple-attachment table').append(html);
return 0;

});
$(document).on('click','.remove-this',function(){
  $(this).parent().parent().parent().remove();
})
</script>
@endsection
