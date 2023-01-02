@extends('layouts.master')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title }}
        <small>Create New Tickets</small>
    </h1>

    {{ TaskHelper::topSubMenu('topsubmenu.hr')}}


    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}

    @section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection
</section>
<link href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />

<script src='{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}'></script>
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<div class="box box-primary">
  <div class="box-body ">
    <form action="#" method="post" enctype="multipart/form-data" onsubmit="return false;">
    	{{ csrf_field() }}

    	<div class="row">
    		<div class="col-md-6">
		    	<div class="panel panel-primary">

		    		<div class="panel-heading"><strong>{{ trans('admin/ticket/general.form_header.user_and_callaborations') }}</strong></div>
		    		 <div class="panel-body">


    @if($ticket->form_source =='external')
      <div class="form-group">
       <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.full_name') }}</label>

            <input type="text" name="from_user" placeholder="Enquiry User Full Name" class="form-control input-sm" value="{{$ticket->from_user}}" disabled="true">
       
        </div>

        <div class="form-group">
          <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.email') }}</label>

            <input type="text" name="from_email" placeholder="Enquiry User Email" class="form-control input-sm" value="{{$ticket->from_email}}" disabled="true">
       
        </div>


          <div class="form-group">
           <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.phone_number') }}</label>

            <input type="text" name="from_email" placeholder="Enquiry User Phone Number" class="form-control input-sm" value="{{$ticket->from_phone}}"disabled="true">
       
        </div>

        @endif

					  <div class="form-group">
					        <label class="control-label" for="email">{{ trans('admin/ticket/general.columns.user') }}</label>
			
						{!!  Form::select('user_id',$users,$ticket->user_id,['class'=>'form-control searchable input-sm','placeholder'=>"Select Users",'disabled'=>'true']) !!}					    
					  </div>
					  <div class="form-group">
					     <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.cc') }}</label>
              
					    
					     <select class="form-control input-sm searchable" name="cc_users[]"  multiple="multiple" placeholder='Select Users' id='cc_users' disabled="true">
								@foreach($users as $key=>$urs)
									<option value="{{$key}}" @if(in_array($key,$cc_users)) selected="" @endif> {{ $urs}} </option>
								@endforeach
					      </select>
			
					  </div>
					  <div class="form-group">
					     <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.notice') }}</label>
					  	{!! Form::select('notice',['1'=>'Alert All','0'=>'Dont`t Send Alert'],$ticket->notice,['class'=>'form-control input-sm','disabled'=>'true'])  !!}
					    
					  </div>
					</div>
				</div>
			</div>

			<div class="col-md-6">

		<div class="panel panel-success">

    		<div class="panel-heading"><strong>{{ trans('admin/ticket/general.form_header.ticket_info_option') }}</strong></div>
    		 <div class="panel-body">
			  <div class="form-group">
			  <label class="control-label" >{{ trans('admin/ticket/general.columns.source') }}</label> {{-- Ticket Source --}}
			   	   	{!! Form::select('source',['phone'=>'Phone','email'=>'Email','others'=>'Others'],$ticket->source,['class'=>'form-control input-sm','disabled'=>'true'])  !!}
			  </div>
			  <div class="form-group">
			     <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.help_topic') }}</label>
			   	{!! Form::select('help_topic',['feedback'=>'Feedback','general_enquiry'=>'General Enquiry','report_problem'=>'Report Problem'],$ticket->help_topic,['class'=>'form-control input-sm','placeholder'=>'---Select Help Topic','disabled'=>'true'])  !!}
			  </div>
			  <div class="form-group">
			      <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.department') }}</label>
			  	{!! Form::select('department_id',$department,$ticket->department_id,['class'=>'searchable form-control','placeholder'=>"Select Department",'disabled'=>'true']) !!}
			  </div>

			  <div class="form-group">
			    <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.sla_plan') }}</label>
			  	 	{!! Form::select('sla_plan',$sla_plan,$ticket->sla_plan,['class'=>'form-control input-sm','placeholder'=>'Select SLA Plan','disabled'=>'true'])  !!}
			 
			  </div>

			  <div class="form-group">
			      <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.due_date') }}</label>

			   		<input type="text" name="due_date" placeholder="Select Due date" class="form-control input-sm datepicker" value="{{$ticket->due_date}}" disabled="true">
			 
			  </div>

			   <div class="form-group">
			        <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.assigned_to') }}</label>
			  	{!!  Form::select('assign_to',$users,$ticket->assign_to,['class'=>'form-control searchable input-sm','placeholder'=>"Select Users",'disabled'=>'true']) !!}				
			  </div>

			</div>
		</div>
			</div>

		</div>
		<div class="panel panel-default">

    		<div class="panel-heading">
    			<span>
    				<span style="font-size: 18px;font-weight: 600;">{{ trans('admin/ticket/general.form_header.ticket_detail') }}</span><br>
    				<small>{{ trans('admin/ticket/general.form_header.issue_describe') }}</small>
    			</span>
    
    			
    		</div>
    		 <div class="panel-body">

    		 <div class="form-group">
			         <label class="control-label" for="pwd">Customer Name</label>
			  
			   	<input type="text" name="customer" class="form-control input-sm" value="{{$ticket->customer}}" disabled="true" >

			  </div>
         <table class="table table-striped">
            <thead>
                <tr class="bg-gray">
                    <th class="col-md-1" style="width: 2px;">S.N</th>
                    <th>Serial Number</th>
                    <th >Model Number</th>
                    <th>Issue Summary</th>
                </tr>
            </thead>

            <tbody id='multipleTicketDiv'>
                <tr class="multipleTicketDiv">
                  <?php 
                      $model_no = json_decode($ticket->model_no);
                      $serial_no = json_decode($ticket->serial_no);
                      $issue_summary = json_decode($ticket->issue_summary);

                  ?>
                  @foreach($model_no as $key=>$val)
                    <tr>
                      <td>{{ $key+1 }}</td>
                      <td>{{ $serial_no[$key] }}</td>
                      <td>{{ $val }}</td>
                      <td>{{ $issue_summary[$key] }}</td>
                      
                    </tr>

                  @endforeach
                   
                </tr>
            </tbody>

            <tfoot>
                
                
            </tfoot>
          </table>

			   <div class="form-group">
			        <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.detail_reason') }}</label>
			  
			   		<textarea class="form-control notepad" placeholder="Details Reason For Opening Tickets"name='detail_reason' disabled="true">{!! $ticket->detail_reason!!}</textarea>
			    
			  </div>

			      <div class="row">
            <div class="col-md-6 ">
                  <div class="more-tr">
                     <table class="table more table-hover table-no-border" style="width: 100%;" cellspacing="2">
                        <tbody style="float: left">
                          <thead>
                            <tr>
                              <th> <button class="btn  bg-maroon btn-xs" id='more-button' type="button" disabled="true"><i class="fa fa-plus"></i>  {{ trans('admin/ticket/general.form_header.add_more_file') }}</button></th>
                              <th colspan="2"></th>
                            </tr>
                          </thead>
                       
                           <tr class="multipleDiv-attachment" style="float: left">
                           </tr>

                           @foreach($ticketFile as $key=>$files)
                               <tr>
                              <td class="moreattachment" style=""> 
                             <a href="/tickets/{{$files->attachment}}">   <i class="fa fa-paperclip"></i> {{mb_substr($files->attachment,0,20) }}...</a>
                              </td>
                              <td class="w-25" >
                                @if(is_array(getimagesize(public_path().'/tickets/'.$files->attachment)))
                                <a href="/tickets/{{$files->attachment}}" target="_blank">
                                 <img src="/tickets/{{$files->attachment}}"  style="max-height: 100px;float: right;margin-left: 30px" class='uploads'>
                                </a>
                      
                                 @endif
                              </td>
                            {{--   <td >
                                 <a href="javascript:void()" style="font-size: 20px; float: right" class="remove-this-attachment-stored" data-id='{{$files->id}}'> <i class="fa fa-close deletable"></i></a>
                                <span class="deleting" style="display: none;">
                                 <i class="fa fa-spinner fa-spin"></i>&nbsp;Deleting
                               </span>
                              </td> --}}
                           </tr>

                           @endforeach
                        </tbody>
                     </table>
                  </div>
              </div>
        </div>


         <div class="form-group">
			     <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.ticket_status') }}</label>
			  		{!! Form::select('ticket_status',['1'=>'Open','2'=>'Resolved','3'=>'Closed'],$ticket->ticket_status,['class'=>'form-control input-sm','disabled'=>'true']) !!}
			  </div>

  			<div class="form-group">
			 <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.internal_notes') }}</label>
			  
			   		<textarea class="form-control notepad" placeholder="Internal Notes" name="internal_notes" disabled="true">{!! $ticket->internal_notes !!}</textarea>
			    
			  </div>



    		 </div>
    	</div>



</form>

  <div class="row">

    <div class="col-md-12">
              <div class="box box-warning direct-chat direct-chat-warning">
                <div class="box-header with-border">
                  <h3 class="box-title">{{ trans('admin/ticket/general.form_header.ticket_threads') }}</h3>

                  <div class="box-tools pull-right">
                    <span data-toggle="tooltip" title="" class="badge bg-yellow" data-original-title="3 New Messages">{{ count($responseMessage) + 1  }}</span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="Contacts">
                      <i class="fa fa-comments"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="direct-chat-messages">
                    <div class="direct-chat-msg">
                      <div class="direct-chat-info clearfix">
                        <?php $userName = $ticket->from_user ??  $ticket->user->first_name.' '.$ticket->user->last_name;    ?>
                        <span class="direct-chat-name pull-left">{{ $userName }}</span>
                        <span class="direct-chat-timestamp pull-right">{{  date( 'dS M h:i A' ,strtotime($ticket->created_at)) }}</span>
                      </div>
                      <img class="direct-chat-img" src="{{ TaskHelper::getAvatarAttribute($userName) }}" alt="message user image">
                      <div class="direct-chat-text">
                        {!! $ticket->detail_reason  !!}
                      </div>
                    </div>
                    @foreach($responseMessage as $m=>$message)
                      <div class="direct-chat-msg right">
                        <div class="direct-chat-info clearfix">
                          <span class="direct-chat-name pull-right">{{$message->user->first_name}} {{$message->user->last_name}}</span>
                          <span class="direct-chat-timestamp pull-left">{{ date('dS M h:i A',strtotime($message->created_at)) }}</span>
                        </div>
                        <img class="direct-chat-img" src="{{ $message->user->avatar }}" alt="message user image">
                        <div class="direct-chat-text">
                          {!! $message->message  !!}
                        </div>
                      </div>
                    @endforeach

                  </div>
                </div>
              </div>
            </div>

  </div>



    <form method="post" action="{{ route('admin.ticket.sendResponse') }}">
      {{ csrf_field() }}
      <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
         <div class="form-group">
          <label class="control-label" for="pwd">{{ trans('admin/ticket/general.form_header.response') }}</label>
        
            <textarea class="form-control notepad" placeholder="Send a response to client"name='message' autofocus required=""></textarea>
          
        </div>
           <div class="row">
                    <div class="col-md-12 ">

                        <div class="form-group pull-right">
                            {!! Form::submit(trans('admin/ticket/general.button.update'), ['class' => 'btn btn-warning', 'id' => 'btn-submit-edit'] ) !!}
                            <a href="/admin/ticket/" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                        </div>
                        </div>
                 </div> 

    </form>
</div>
</div>


<script type="text/javascript">
	$('#more-button').click(function(){
       $(".multipleDiv-attachment").after($('#morefiles #more-custom-tr').html());
});

	$(document).on('click','.remove-this-attachment',function(){
      $(this).parent().parent().remove();
    });

    const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
$(document).on('change','.attachment',function(){
  var input = this;
  // console.log('done');
   var parent = $(this).parent().parent();
      if (input.files && input.files[0]) {
        var fileType = input.files[0]['type'];
        var reader = new FileReader();
        reader.onload = function (e) {
          if (validImageTypes.includes(fileType)) {
            parent.find('.uploads')
                .attr('src', e.target.result)
                .width(150)
                .height(200);
            }
          else{
             parent.find('.uploads')
                .attr('src','')
                .width(0)
                .height(0);
          }
       
        };

        reader.readAsDataURL(input.files[0]);
    }
});

$('.searchable').select2({});

$('select#cc_users').select2({

	 placeholder: "Search Users..",
    allowClear: true
});

   $('textarea.notepad').wysihtml5();


     $('.datepicker').datetimepicker({
          //inline: true,
          //format: 'YYYY-MM-DD',
          format: 'YYYY-MM-DD', 
              sideBySide: true,
              allowInputToggle: true,
               widgetPositioning: {
                    vertical: 'bottom'
                }
        });


     $('.remove-this-attachment-stored').click(function(){

      var id = $(this).attr('data-id');
      var parent =  $(this).parent().parent();
      parent.find('.deleting').show();
      let c = confirm('Are You Sure You want to delete');
      if(c){

        $.get(`/admin/ticket/delete-file/${id}`,function(response){
         parent.remove();
        }).fail(function(){
           parent.find('.deleting').hide();
        });
     

      }


     });

     $('html,body').animate({scrollTop: document.body.scrollHeight},"fast");
</script>

@endsection