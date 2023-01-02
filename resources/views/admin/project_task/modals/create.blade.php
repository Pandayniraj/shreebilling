  
@extends('layouts.dialog')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
   <script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>

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


</script>
<section class="content-header" style="margin-top: -35px; margin-bottom: -5px">
        
            <span style="font-size: 18px;font-weight: bold">
                Create Quick Task
            </span>
           
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

             
              <form method="post" id="form_tasks" onsubmit="return submitform()">

                <div class="modal-body">



                        	<div class="form-group">
                          
                              <div class="col-sm-11">
                                  {!! Form::text('subject', $task->subject, ['class' => 'form-control input-sm required', 'id'=>'subject','placeholder'=>'Type Task Subject', 'required'=>'required']) !!}
                              </div>
                            </div>


                    <div class="col-md-6">

                      

                        
                        <div class="form-group">
                            <label for="priority" class="col-sm-4 control-label">
                            <i style="color:red" class="fa fa-exclamation"></i> {{ trans('admin/projects/general.columns.priority') }}
                            </label>
                            <div class="col-sm-8">
                            {!! Form::select('priority', ['Low'=>'Low', 'Medium'=>'Medium', 'High'=>'High'], $task->priority, ['class' => 'form-control input-sm']) !!}
                        </div>
                        </div>
                          
                        
                        <div class="form-group">
                            <label for="subject" class="col-sm-4 control-label">
                            <i class="fa fa-clock-o"></i> {!! Form::label('duration', trans('admin/projects/general.columns.estimated_duration')) !!}
                              </label>
                              <div class="col-sm-8">
                                  {!! Form::text('duration', '1d', ['class' => 'form-control','placeholder'=>'Type hour or days e.g 2d or 5h', 'id'=>'duration']) !!}
                              </div>
                            </div>


                    </div>
                    <input type="hidden" name="project_id" value="{{\Request::segment(5)}}">
                    <div class="col-md-6">

                       <div class="form-group">
                            <label for="subject" class="col-sm-4 control-label">
                            <i class="fa fa-calendar"></i> {{ trans('admin/projects/general.columns.start_date') }}
                            </label>
                              <div class="col-sm-8">
                            {!! Form::text('start_date', date('Y-m-d'), ['class' => 'form-control input-sm required', 'id'=>'start_date']) !!}
                        </div>
                        </div>


                        <div class="form-group">
                            <label for="subject" class="col-sm-4 control-label">
                            <i style="color:red" class="fa fa-calendar"></i> {{ trans('admin/projects/general.columns.end_date') }}
                            </label>
                              <div class="col-sm-8">
                            {!! Form::text('end_date', \Carbon\Carbon::now()->addDay(1)->format('Y-m-d'), ['class' => 'form-control input-sm ', 'id'=>'end_date']) !!}
                        </div>
                        </div>

                        <div class="form-group">
                            <label for="subject" class="col-sm-4 control-label">
                            <i class="fa fa-users"></i> {{ trans('admin/projects/general.columns.people') }}
                            </label>
                            <div class="col-sm-8">
                            <span class="txtfield"><ul id="peoples"></ul></span>
                            <input type="hidden" class="form-control peoples" name="peoples" id="peoplesField" value="{{\Auth::user()->username}}" >
                        </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>

              </div>

                 <input type="hidden" name="enabled" value="1">
                <div class="form-group">
                    <!-- {!! Form::button( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-create'] ) !!} -->
                    <button class="btn btn-primary" type="submit">{{  trans('general.button.create') }}</button>
                    <a href="#" onClick="window.close()"class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>

               </form>
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
    <script type="text/javascript">

    $('#sheduled').change(function() {
        $('#timespan').toggle();
    });
    </script>
<script>

function submitform(projectid){
  $("#overlay").fadeIn(300);　
  var obj ={};
  var data = JSON.stringify( $('#form_tasks').serializeArray() ); //  <-----------
  var paramObj = {};
  $.each($('form').serializeArray(), function(_, kv) {
  paramObj[kv.name] = kv.value;
  });
  paramObj['_token']= $('meta[name="csrf-token"]').attr('content')
   $.post("/admin/project_tasks/store/modals",paramObj,function(data,status){
      if(status == 'success'){
       var  result = data;
        try  {
        window.opener.HandlePopupResult(result);
        }
        catch (err) {
          console.log(err);
        } 
      }else{
        alert("Failed to save Task Try Again!!");
      }
      $("#overlay").fadeOut(300);
      window.close();
      return false;
    });
  return false;
}
$('#start_date,#end_date').on('change',function(){
    var start =new Date ($('#start_date').val());
    var end = new Date($('#end_date').val());
    var diff = new Date(end - start);
    var days = diff/1000/60/60/24;
    $('#duration').val(days+'d');
});


</script>
@endsection
