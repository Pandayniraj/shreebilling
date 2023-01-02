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

<style type="text/css">
  label {
    font-weight: 500 !important;
}
</style>

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
                New Task
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>


<div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.projects.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">

                      <div class="btn-group">             
                   {{ TaskHelper::topSubMenu('topsubmenu.projects')}}

                </div>

                    </div>
                    
                </div><!-- /.box -->
            {!! Form::close() !!}
        </div><!-- /.col -->

    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

                {!! Form::model( $task, ['route' => ['admin.project_task.storeglobal' ], 'method' => 'POST', 'id' => 'form_create_task', 'onSubmit'=>'return validate();', 'enctype' => 'multipart/form-data'] ) !!}

                <div class="modal-body">



                        	<div class="form-group">
                            <label for="subject" class="col-sm-1 control-label">
                                {{ trans('admin/projects/general.columns.subject') }}
                              </label>
                              <div class="col-sm-11">
                                  {!! Form::text('subject', \Request::old('subject'), ['class' => 'form-control required', 'id'=>'subject','placeholder'=>'Type Task Subject', 'required'=>'required']) !!}
                              </div>
                            </div>



                    <div class="form-group">
                        {!! Form::label('description', trans('admin/projects/general.columns.description')) !!} 
                        <textarea rows="1" name="description" class="form-control required" id="description">{{\Request::old('description')}}</textarea>

                        <input type="hidden" name="project_id" id="project_id" value="{{ $projectId }}">
                    </div>

                    <div class="col-md-6">

                      <div class="form-group">
                            <label for="priority" class="col-sm-4 control-label">
                           {{ trans('admin/projects/general.columns.project') }}
                            </label>
                            <div class="col-sm-8">
                            {!! Form::select('project_id', [''=>"Select Project"]+$projects,$pid, ['class' => 'form-control ','required'=>'required']) !!}
                        </div>
                        </div>

                        <div class="form-group">
                          
                            {!! Form::label('percent_complete', trans('admin/projects/general.columns.progress')) !!}

                            {!! Form::input('range','percent_complete', $task->percent_complete, ['class' => ' custom-range', 'id'=>'percent_complete']) !!}

                        </div>


                        
                        <div class="form-group">
                            <label for="priority" class="col-sm-4 control-label">
                            {!! Form::label('priority', trans('admin/projects/general.columns.priority')) !!}
                            </label>
                            <div class="col-sm-8">
                            {!! Form::select('priority', ['Low'=>'Low', 'Medium'=>'Medium', 'High'=>'High'], $task->priority, ['class' => 'form-control input-sm']) !!}
                        </div>
                        </div>
                   

<br/><br/>
                       
                        
                        <div class="form-group">
                            <label for="subject" class="col-sm-4 control-label">
                            {!! Form::label('duration', trans('admin/project-task/general.columns.duration')) !!}
                              </label>
                              <div class="col-sm-8">
                                  {!! Form::text('duration', '1d', ['class' => 'form-control input-sm','placeholder'=>'Type hour or days e.g 2d or 5h', 'id'=>'duration']) !!}
                              </div>
                            </div>


                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('enabled', '1', '1') !!} {{ trans('general.status.enabled') }}
                            </label>
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
                            @if($task->attachment != '')
                                <strong>File: </strong>
                                <a href="{{url('/'). '/task_attachments/'.$task->attachment}}" target="_blank">{{$task->attachment}}</a>
                            @endif
                        </div>


                    </div>

                    <div class="col-md-6">

                       <div class="form-group">
                            <label for="subject" class="col-sm-4 control-label">
                            <i class="fa fa-calendar-alt"></i>{{ trans('admin/projects/general.columns.start_date') }}
                            </label>
                              <div class="col-sm-8">
                            {!! Form::text('start_date',date('Y-m-d'), ['class' => 'form-control required', 'id'=>'start_date']) !!}
                        </div>
                        </div>
                         <br /> <br/>

                        <div class="form-group">
                            <label for="subject" class="col-sm-4 control-label" style="font-style: normal;">
                           <i class="fa fa-calendar-alt"></i>{{ trans('admin/projects/general.columns.end_date') }}
                            </label>
                              <div class="col-sm-8">
                            {!! Form::text('end_date', date('Y-m-d', strtotime("+1 day")), ['class' => 'form-control required', 'id'=>'end_date']) !!}
                        </div>
                        </div>
 <br /> <br/>

                                        <div class="form-group">
                                            <label for="subject" class="col-sm-4 control-label">
                                            {{ trans('admin/projects/general.columns.staffs_involved') }}
                                            </label>
                              <div class="col-sm-8">
                                            <span class="txtfield"><ul id="peoples"></ul></span>
                                            <input type="hidden" class="form-control peoples" name="peoples" id="peoplesField" value="{{\Auth::user()->username}}" >
                                        </div>
                                        </div>
                                            <br /> <br />

                        <div class="form-group">
                            <label for="subject" class="col-sm-4 control-label">
                            {!! Form::label('status', trans('admin/project-task/general.columns.status')) !!}
                            </label>
                              <div class="col-sm-8">
                            {!! Form::select('status', $project_status, $task->status, ['class' => 'form-control input-sm']) !!}
                        </div>
                        </div>
                        <br /> <br />

                         <div class="form-group">
                            <label for="subject" class="col-sm-4 control-label">
                            {{ trans('admin/projects/general.columns.category') }}
                            </label>
                              <div class="col-sm-8">
                            {!! Form::select('category_id', $cat,null, ['class' => 'form-control label-primary']) !!}
                        </div>
                        </div>


                        <div class="form-group">


                          <input type="checkbox" name="schedule" id="sheduled" >
                            {!! Form::label('schedule', 'Schedule') !!}
                        </div>
                        <div class="form-group" id="timespan" style="display:none;">
                            {!! Form::label('weekly', 'Weekly') !!}
                            <input type="radio" name="timespan" value="weekly"><br>

                            {!! Form::label('Monthly', 'Monthly') !!}
                            <input type="radio" name="timespan" value="monthly"><br>

                            {!! Form::label('Twice a Month', 'Twice a Month') !!}
                            <input type="radio" name="timespan" value="twice_a_month"><br>

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
                    {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-create'] ) !!}
                     <a href="/admin/projects/{{ \Request::get('pid') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
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
//CKEDITOR.replace( 'description' );
CKEDITOR.config.height='140px';    
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

$('#start_date,#end_date').on('change',function(){
    var start =new Date ($('#start_date').val());
    var end = new Date($('#end_date').val());
    var diff = new Date(end - start);
    var days = diff/1000/60/60/24;
    $('#duration').val(days+'d');
});
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
