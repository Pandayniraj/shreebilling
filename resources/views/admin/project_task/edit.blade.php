
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
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
            Create Task
                <small>{!! $page_descriptions !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

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

<link rel="stylesheet" type="text/css" href="/bootstrap-iso.css">
{!! Form::model( $task, ['route' => ['admin.project_task.update', $task->id], 'method' => 'PUT', 'id' => 'form_edit_task', 'onSubmit'=>'return validate();', 'enctype' => 'multipart/form-data'] ) !!}
<div class=" bootstrap-iso" style="min-height: 1416.81px;">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-6">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">General</h3>

              
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="inputName">Subject</label>
                {!! Form::text('subject', \Request::old('subject'), ['class' => 'form-control required', 'id'=>'subject','placeholder'=>'Type Task Subject', 'required'=>'required']) !!}
              </div>
              
              <div class="form-group">
                <label for="projectId">Project</label>
                {!! Form::select('project_id', [''=>"Select Project"]+$projects,$pid, ['class' => 'form-control searchable ','required'=>'required']) !!}
              </div>
              <div class="form-group">
                <label for="inputStatus">Status</label>
                {!! Form::select('status', $project_status, $task->status, ['class' => 'form-control input-sm']) !!}
              </div>
              <div class="row">
              <div class="col-md-6">
              <div class="form-group">
               <label for="subject">
                  <i class="fa fa-calendar-alt"></i>{{ trans('admin/projects/general.columns.start_date') }}
                </label>
                <?php  
                    $startstr =  strtotime($task->start_date);
                    $endstr = strtotime($task->end_date); 
                ?>
                {!! Form::text('start_date', $startstr ? date('Y-m-d',$startstr): null  , ['class' => 'form-control required', 'id'=>'start_date']) !!}
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
               <label for="subject">
                  <i class="fa fa-calendar-alt"></i>{{ trans('admin/projects/general.columns.end_date') }}
                </label>
               {!! Form::text('end_date',$endstr ? date('Y-m-d',$endstr): null, ['class' => 'form-control required', 'id'=>'end_date']) !!}
              </div>
            </div>

            </div>

            <div class="form-group">
                <label for="inputStatus">{{ trans('admin/projects/general.columns.staffs_involved') }}</label>

               <span class="txtfield"><ul id="peoples"></ul></span>
                <input type="hidden" class="form-control peoples" name="peoples" id="peoplesField" value="{!! implode(',', $peoples) !!}" >

              </div>
              <div class="form-group">
                <label for="inputDescription">Task Description</label>
                <textarea id="inputDescription" class="form-control" rows="4" name='description'>
                  {{$task->description}}
                </textarea>
              </div>
               <div class="form-group">
                <label>
                    {!! Form::checkbox('enabled', '1', '1') !!} {{ trans('general.status.enabled') }}
                </label>
              </div>

               <div class="form-group">

                        <label>
                          <input type="checkbox" name="schedule" id="sheduled" @if($task->schedule == 'on') checked="" @endif>
                          <label for="schedule">Schedule</label>
                        </label>
                        </div>
                        <div class="form-group" id="timespan" @if($task->schedule != 'on')  style="display:none;" @endif>
                          {!! Form::label('weekly', 'Weekly') !!}
                          <input type="radio" name="timespan" value="weekly" @if($task->schedule_type == 'weekly') checked="" @endif><br>

                          {!! Form::label('Monthly', 'Monthly') !!}
                          <input type="radio" name="timespan" value="monthly" @if($task->schedule_type == 'monthly') checked="" @endif><br>

                          {!! Form::label('Twice a Month', 'Twice a Month') !!}
                          <input type="radio" name="timespan" value="twice_a_month" @if($task->schedule_type == 'twice_a_month') schecked="" @endif><br>

                          {!! Form::label('Three Months', 'Three Months') !!}
                          <input type="radio" name="timespan" value="three_months" @if($task->schedule_type == 'three_months') checked="" @endif><br>

                          {!! Form::label('Six Months', 'Six Months') !!}
                          <input type="radio" name="timespan" value="six_months" @if($task->schedule_type == 'six_months') checked="" @endif><br>

                          {!! Form::label('Two Months', 'Two Months') !!}
                          <input type="radio" name="timespan" value="two_months" @if($task->schedule_type == 'two_months') checked="" @endif><br>

                          {!! Form::label('Yearly', 'Yearly') !!}
                          <input type="radio" name="timespan" value="yearly"  @if($task->schedule_type == 'yearly') checked="" @endif><br>

                        </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <div class="col-md-6">
          <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title">Productivity</h3>

             
            </div>
            <div class="card-body">
              <div class="form-group">
                 {!! Form::label('priority', trans('admin/projects/general.columns.priority')) !!}
                {!! Form::select('priority', ['Low'=>'Low', 'Medium'=>'Medium', 'High'=>'High'], $task->priority, ['class' => 'form-control input-sm']) !!}
              </div>
              
              <div class="form-group">
                 {!! Form::label('duration', trans('admin/project-task/general.columns.duration')) !!}
                  {!! Form::text('duration',  $task->duration , ['class' => 'form-control input-sm','placeholder'=>'Type hour or days e.g 2d or 5h', 'id'=>'duration']) !!}
              </div>
              
              <div class="form-group">
                {!! Form::label('percent_complete', trans('admin/projects/general.columns.progress')) !!}
                {!! Form::input('range','percent_complete', $task->percent_complete, ['class' => ' custom-range', 'id'=>'percent_complete']) !!}
              </div>
              
                    <div class="form-group">
                    <label>Select Location</label>
                     {!! Form::select('location', ['' =>'Please Select','common-area'=>'Common Area','apartment'=>'Apartment','office'=>'Office'], null, ['class' => 'form-control label-default','id'=>'location', 'required'=>'required']) !!}
                </div>
              <div class="form-group">
                 <label for="subject" class="control-label">
                   {{ trans('admin/projects/general.columns.category') }}
                  </label>  
                   {!! Form::select('category_id', $cat,null, ['class' => 'form-control label-primary','id'=>'category_id']) !!}
              </div>


                 

               <div class="form-group"> 
                         
                  <label>Select Sub Category</label>
                      <select name="sub_cat_id" class="form-control label-primary" id="sub_category_id" required>
                        <option>Please Select </option>

                          @if($sub_cat)
                            @foreach($sub_cat as $uk => $uv)
                            <option value="{{ $uv->id }}" @if($uv->id == $task->sub_cat_id) selected @endif>{{ $uv->name }}</option>
                            @endforeach
                          @endif
                      </select>
              </div>

             

             

            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Files
                &nbsp;
                <button class="btn btn-xs btn-primary"  id='add-more' type="button">Add File</button>
              </h3>

             
            </div>
            <div class="card-body p-0" id='multiple-attachment'>
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
            </div><hr>
            <div class="card-body p-0">
              <table class="table">
                <thead>
                  <tr>
                    <th>File Name</th>
                    <th>File Size</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                   @if($task->attachment != '' && $task->attachment != 'Array')
                  <tr>
                    <td><a href="{{url('/'). '/task_attachments/'.$task->attachment}}" target="_blank">{{$task->attachment}}</a></td>
                    <td>{{ round(filesize(public_path().'/task_attachments/'.$task->attachment) * 0.000001,2) }} MB</td>
                    <td class="text-right py-0 align-middle">
                      <div class="btn-group btn-group-sm">
                        <a href="#" class="btn btn-info" title="This attachment is created from previous single attachment concept"><i class="fa fa-eye"></i></a>
                      </div>
                    </td>
                  </tr>
                  @endif

                  @foreach($task_attachments as $key => $ta)
                    <tr>
                    <td><a href="{{url('/'). '/task_attachments/'.$ta->attachment}}" target="_blank">{{$ta->attachment}}</a></td>
                    <td>{{ round(filesize(public_path().'/task_attachments/'.$ta->attachment) * 0.000001,2) }} MB</td>
                    <td class="text-right py-0 align-middle">
                      <div class="btn-group btn-group-sm">
                       
                        <a href="javascript::void()" class="btn btn-danger remove-me" data-id='{{$ta->id}}' 
                        ><i class="fa fa-trash"></i></a>
                      </div>
                    </td>
                  </tr>

                  @endforeach

                </tbody>
              </table>
            </div> 

            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <br>
       <div class="row">
    <div class="col-md-12">
    <div class="form-group">
    {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-success', 'id' => 'btn-submit-create'] ) !!}
     <a href="/admin/projects/{{ \Request::get('pid') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
    </div>
    </div>
    </div>
  
    </section>
    <!-- /.content -->

  </div>


{!! Form::close() !!}
<script type="text/javascript">
    $('#sheduled').change(function() {
        $('#timespan').toggle();
    });
</script>
    <script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>

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
    <script type="text/javascript">
      CKEDITOR.config.height='140px';    
CKEDITOR.replace( 'inputDescription', {
toolbar: [
{ name: 'document', items: [ 'Source', '-', 'NewPage', 'Preview', '-', 'Templates' ] }, // Defines toolbar group with name (used to create voice label) and items in 3 subgroups.
[ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],  // Defines toolbar group without name.
                                                                 // Line break - next group will be placed in new line.
{ name: 'basicstyles', items: [ 'Bold', 'Italic' ] },
{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Language' ] },
{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] }
]
});
          $('#add-more').click(function(){
            let html = $('#more-td table').html();

          $('#multiple-attachment table').append(html);
          return 0;

          });
          $(document).on('click','.remove-this',function(){
            $(this).parent().parent().parent().remove();
          });
          $('.searchable').select2();

          const taskId = '{{$task->id}}';

          $('.remove-me').click(function(){

            let id = $(this).attr('data-id');
            let c= confirm("Are You sure you want to this attachment");
            if(c){
              location.href = `/admin/projectTask/destroy/${id}/${taskId}`;
            }
          });


   $(function () {
        $('#category_id').on('change', function() {   
            if($(this).val() != '')
            {
                $.ajax({
                    url: "/admin/tasks/ajax/GetSubCat",
                    data: { cat_id: $(this).val() },
                    dataType: "json",
                    success: function( data ) {
                        var result = data.data;
                        $('#sub_category_id').html(result);
                    }
                });
            }
        });
    });

    </script>
  @endsection