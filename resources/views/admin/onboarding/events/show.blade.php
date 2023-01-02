@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

<link href="/bower_components/tags/css/jquery.tagit.css" rel="stylesheet" type="text/css"/>
<link href="/bower_components/tags/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css"/>

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
		     	<h4>Onboard Events</h4>

                     <div class="row">
	                   	<div class="col-md-8 form-group">
	                   	    <label class="control-label">Name</label>
                            <input type="text" name="name" placeholder="Events name" id="name" value="{{$events->name}}" class="form-control" required="" readonly="">
	                   	</div>
                     </div>
                   
                     <div class="row">
                      <div class="col-md-8 form-group">
                          <label class="control-label">Location</label>
                            <input type="text" name="location" placeholder="Event Location" id="location" value="{{$events->location}}" class="form-control" readonly="">
                      </div>
                     </div>
                       <div class="row">
                        <div class="col-md-8 form-group">
                          <label class="control-label">Due date</label>
                            <input type="text" name="due_date" placeholder="Due date" id="due_date" value="{{$events->due_date}}" class="form-control datepicker" readonly="">
                      </div>
                     </div>
                       <div class="row">
                 <div class="col-md-8 form-group">
                  <label for="subject" class="control-label">Participants</label>
                
                    <span class="txtfield"><ul id="peoples"></ul></span>
                    <input type="hidden" class="form-control peoples" name="participants" id="peoplesField" value="{{$events->participants}}" readonly="">
        
                </div>
                     </div>
                       <div class="row">
                 <div class="col-md-8">
                  <label for="subject" class="control-label">Owner</label>
                
                    <span class="txtfield"><ul id="owner"></ul></span>
                    <input type="hidden" class="form-control peoples" name="owner" id="ownerField" value="{{$events->owner}}" readonly="">
        
                </div>
                     </div>
	               
       <br>
		        <div class="row">
	                <div class="col-md-12">
	                    <div class="form-group">
                        <button class="btn btn-danger">View Tasks</button>
	                     @if ( $events->isEditable()  )
                       <a href="{!! route('admin.onboard.events.edit',$events->id) !!}" class="btn btn-danger">Edit</a>
                       @else
                         <button class="btn btn-danger" disabled="">Edit</button>
                       @endif
	                        <a href="{!! route('admin.onboard.events') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
	                    </div>
	                 </div>
	            </div>

	        </form>
		</div>
	</div>
        
   
@endsection

@section('body_bottom')
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="/bower_components/tags/js/tag-it.js"></script>
<script type="text/javascript">
	
     $(function() {
       jQuery("#peoples").tagit({
      singleField: true,
      singleFieldNode: $('#peoplesField'),
      allowSpaces: true,
      minLength: 2,
      tagLimit: 5,
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
var currentlyValidTags =['root']
  jQuery("#owner").tagit({
      singleField: true,
      singleFieldNode: $('#ownerField'),
      allowSpaces: true,
      minLength: 2,
      tagLimit: 5,
      placeholderText: 'Enter owner Name',
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
      },
       
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
