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
   <div id="ticketFields" style="display: none;">
      <table class="table table-striped">
          <tbody id="more-tr">
              <tr>
                  <td class="p_sn"></td>
                  <td>
                      <input type="text" class="form-control" name="model_no[]" placeholder="Model No" autocomplete="off">
                  </td>
                  <td>
                      <input type="text" class="form-control" name="serial_no[]" placeholder="Serial No" autocomplete="off">
                  </td>
                  <td>
                      <input type="text" class="form-control" name="issue_summary[]" placeholder="Issue Summary" autocomplete="off" style="float:left; width:89%;">
                      <a href="javascript::void(1);" style="width: 10%;">
                          <i class="remove-ticket btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                      </a>
                  </td>
              </tr>
          </tbody>
      </table>
    </div>
  <div class="box-body ">
    <form action="{{ route('admin.ticket.create') }}" method="post" enctype="multipart/form-data">
    	{{ csrf_field() }}

    	<div class="row">
    		<div class="col-md-6">
		    	<div class="panel panel-primary">

		    		<div class="panel-heading"><strong>{{ trans('admin/ticket/general.form_header.user_and_callaborations') }}</strong></div>
		    		 <div class="panel-body">
					  <div class="form-group">
					    <label class="control-label" for="email">{{ trans('admin/ticket/general.columns.user') }}</label>
			
						{!!  Form::select('user_id',$users,null,['class'=>'form-control searchable input-sm','placeholder'=>"Select Users"]) !!}					    
					  </div>
					  <div class="form-group">
					    <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.cc') }}</label>
					    
					     <select class="form-control input-sm searchable" name="cc_users[]"  multiple="multiple" placeholder='Select Users' id='cc_users'>
								@foreach($users as $key=>$urs)
									<option value="{{$key}}"> {{ $urs}} </option>
								@endforeach
					      </select>
			
					  </div>
					  <div class="form-group">
					    <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.notice') }}</label>
					  	{!! Form::select('notice',['1'=>'Alert All','0'=>'Dont`t Send Alert'],null,['class'=>'form-control input-sm'])  !!}
					    
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
			   	   	{!! Form::select('source',['phone'=>'Phone','email'=>'Email','others'=>'Others'],null,['class'=>'form-control input-sm'])  !!}
			  </div>
			  <div class="form-group">
			    <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.help_topic') }}</label>
			   	{!! Form::select('help_topic',['feedback'=>'Feedback','general_enquiry'=>'General Enquiry','report_problem'=>'Report Problem'],null,['class'=>'form-control input-sm','placeholder'=>'---Select Help Topic'])  !!}
			  </div>
			  <div class="form-group">
			    <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.department') }}</label>
			  	{!! Form::select('department_id',$department,null,['class'=>'searchable form-control','placeholder'=>"Select Department"]) !!}
			  </div>

			  <div class="form-group">
			    <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.sla_plan') }}</label>
			  	 	{!! Form::select('sla_plan',$sla_plan,null,['class'=>'form-control input-sm','placeholder'=>'Select SLA Plan'])  !!}
			 
			  </div>

			  <div class="form-group">
			    <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.due_date') }}</label>

			   		<input type="text" name="due_date" placeholder="Select Due date" class="form-control input-sm datepicker" >
			 
			  </div>

			   <div class="form-group">
			    <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.assigned_to') }}</label>
			  	{!!  Form::select('assign_to',$users,null,['class'=>'form-control searchable input-sm','placeholder'=>"Select Users"]) !!}				
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
            <label class="control-label" for="customer">Customer Name</label>
            {!!  Form::select('customer_id',$customer,null,['class'=>'form-control searchable input-sm','placeholder'=>"Select Customer"]) !!}  
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
                   
                </tr>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="4">
                        <a href="javascript::void(0)" class="btn btn-success btn-sm" id="addMoreTicket" style="float: right" >
                            <i class="fa fa-plus"></i> <span>Add More</span>
                        </a>

                    </td>
                </tr>

                
            </tfoot>
          </table>
			   <div class="form-group">
			    <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.detail_reason') }}</label>
			  
			   		<textarea class="form-control notepad" placeholder="Details Reason For Opening Tickets"name='detail_reason'></textarea>
			    
			  </div>

			      <div class="row">
            <div class="col-md-6 ">
                  <div class="more-tr">
                     <table class="table more table-hover table-no-border" style="width: 100%;" cellspacing="2">
                        <tbody style="float: left">
                          <thead>
                            <tr>
                              <th> <button class="btn  bg-maroon btn-xs" id='more-button' type="button"><i class="fa fa-plus"></i> {{ trans('admin/ticket/general.form_header.add_more_file') }}</button></th>
                              <th colspan="2"></th>
                            </tr>
                          </thead>
                       
                           <tr class="multipleDiv-attachment" style="float: left">
                           </tr>
                               <tr>
                              <td class="moreattachment" style=""> 
                                 <input type="file" name="attachment[]" class="attachment" >
                              </td>
                              <td class="w-25" >
                                 <img src=""  style="max-height: 100px;float: right;margin-left: 30px" class='uploads'>
                              </td>
                              <td >
                                 <a href="javascript:void()" style="font-size: 20px; float: right" class="remove-this-attachment"> <i class="fa fa-close deletable"></i></a>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
              </div>
        </div>


         <div class="form-group">
			    <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.ticket_status') }}</label>
			  		{!! Form::select('ticket_status',['1'=>'Open','2'=>'Resolved','3'=>'Closed'],null,['class'=>'form-control input-sm']) !!}
			  </div>

  			<div class="form-group">
			    <label class="control-label" for="pwd">{{ trans('admin/ticket/general.columns.internal_notes') }}</label>
			  
			   		<textarea class="form-control notepad" placeholder="Internal Notes" name="internal_notes"></textarea>
			    
			  </div>



    		 </div>
    	</div>


    	 <div class="row">
                    <div class="col-md-12 ">

                        <div class="form-group">
                            {!! Form::submit( trans('admin/ticket/general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                            <a href="/admin/ticket/" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                        </div>
                        </div>
                 </div> 

</form>
</div>
</div>


<div id="morefiles" style="display: none">
   <table class="table">
      <tbody id="more-custom-tr">
         <tr>
            <td class="moreattachment" style=""> 
               <input type="file" name="attachment[]" class="attachment" >
            </td>
            <td class="w-25" >
               <img src=""  style="max-height: 100px;float: right;margin-left: 30px" class='uploads'>
            </td>
            <td >
               <a href="javascript:void()" style="font-size: 20px; float: right" class="remove-this-attachment"> <i class="fa fa-close deletable"></i></a>
            </td>
         </tr>
      </tbody>
   </table>
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


  function getTicketSn(){

   $('#multipleTicketDiv tr').each(function(index,val){

        if(index > 0){
            $(this).find('.p_sn').html(index);
        }

   });
  }
  $("#addMoreTicket").on("click", function() {

        $(".multipleTicketDiv").after($('#ticketFields #more-tr').html());
        getTicketSn();
       
    });
  $(document).on('click', '.remove-ticket', function() {
      $(this).parent().parent().parent().remove();
      getTicketSn();
  });
</script>

@endsection