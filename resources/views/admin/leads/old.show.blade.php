@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
    
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
    
<script>
$(function() {
	$('#task_due_date').datetimepicker({
			//inline: true,
			//format: 'YYYY-MM-DD HH:mm',
			format: 'DD-MM-YYYY HH:mm',
			sideBySide: true
		});
});

$(document).on('click', '#note_to_task', function() { 
	if($("#note_to_task").is(':checked'))
		$("#task_dates").show();
	else
		$("#task_dates").hide();
});
</script>
@endsection




@section('content')
<style>
	.task-wrap { border-top: 1px solid #ccc; }
	.danger {border-color:red;}

  p{
    font-size: 14px;
  }
  
}

.content {
  background-color: white !important;
}

</style>

 <style type="text/css">
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

      <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h2>@if($lead->logo == '')
             <a href="#" data-toggle="modal" data-target="#saveLogo">
              <small data-letters="{{mb_substr($lead->name,0,3)}}"></small>
            </a>
            @else
            <a href="#" data-toggle="modal" data-target="#saveLogo">
            <img src="/leads/{{$lead->logo}}" width="50px" height="50px" style="border-radius: 50%;">
            </a>
            @endif

            {{env('APP_CODE')}}{{$lead->id}}. {!! $lead->title !!} {!! $lead->name !!} <small> {!! $lead->organization !!}</small>
                </h2>

                   <p> {{ $lead->department}} {{ $lead->country}}
                  {!! $lead->description !!}</p>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section> 

        <div class="modal fade" id="saveLogo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Plese Select Profile For Leads</h4>
            </div>
            <form method="post" action="/admin/leads/{{$lead->id}}/storelogo"  enctype="multipart/form-data">  
              {{!! csrf_field() !!}
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">
                        Profle Photo
                         </label>
                         <div class="col-sm-10">
                           <input type="file" name="logo">
                            @if($lead->logo != '')
                             <label>Current Logo: </label>
                             <br/>
                            <img style=" width:100px;height: 100px;" src="{{ '/leads/'.$lead->logo }}">
                            @endif
                         </div>
                        </div>
                  </div>
                </div>  
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>

          </div>
        </div>
      </div>

    <div class='row'>

    	<div class="col-md-12">


        	&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;

          <a href="/admin/proposal/create" class="btn btn-default btn-xs" title="Proposal"><i class="fa fa-hourglass-half"></i> Proposal</a>

          <a class="btn btn-default btn-xs" href="/admin/tasks/create?lead_id={!! $lead->id !!}"> <i class="fa fa-tasks"></i> Create Task</a>

          <a href="/admin/mail/{!! $lead->id !!}/show-mailmodal" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal_mail">  <i class="fa fa-envelope"></i> Send Email</a>
            <a class="btn btn-default btn-xs" href="#" data-target="#sendSMS" data-toggle="modal">
              <i class="fa fa-mobile"></i> Send SMS</a>

          <a class="btn btn-default btn-xs" href="/admin/orders/create?type=quotation"> <i class="fa fa-book"></i>  Quote</a>

          @if(\Request::get('type') != 'customer')    
          <a class="btn btn-success btn-xs" href="/admin/convert_lead/{!! $lead->id !!}"> <i class="fa fa-angle-double-right"></i> Convert</a>
          @endif

            <a href="/admin/mail/{!! $lead->id !!}/show-offerlettermodal" class="btn btn-success btn-xs" data-target="#modal_dialog" data-toggle="modal"  title="Offer Letter">Send Offer Mail</a>
            <a href="/admin/mail/{!! $lead->id !!}/show-unsuccessfulapplicationmodal" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal_dialog" title="Reminder">Reminder Mail</a>
            <a href="/admin/mail/{!! $lead->id !!}/show-pendingmodal" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal_dialog" title="Pending">Thank You Mail</a>
            

          @if(\Request::get('type') == 'customer' || \Request::get('type') == 'contact' || \Request::get('type') == 'agent')   

           <a class="btn btn-default btn-xs" href="/admin/cashbook"> <i class="fa fa-book"></i>  Ledger</a>

            <a class="btn btn-default btn-xs" href="/admin/lead_query_list/{!! $lead->id !!}"> <i class="fa fa-history"></i> Query History</a> 

          <a class="btn btn-default btn-xs" href="/admin/lead_query/{!! $lead->id !!}"> <i class="fa fa-plus"></i> New Query</a>

          @endif

          @if(\Auth::user()->id == $lead->user_id)    
          <a class="btn btn-default btn-xs" href="/admin/transfer_lead/{!! $lead->id !!}"> <i class="fs fa-exchange-alt"></i> Transfer</a>
          @endif

          @if(!$lead->moved_to_client)
          <a class="btn btn-default btn-xs" href="{{route('admin.lead.convert_lead_clients-confirm',$lead->id)}}" data-toggle="modal" data-target="#modal_dialog"> <i class="fs fa-exchange-alt">Post to client</i></a>
          @endif
        </div>

        @if(isset($queries) && sizeof($queries)) 

        <style>
          #customers {
            border-collapse: collapse;
            width: 100%;
          }

          #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 3px;
          }

          #customers tr:nth-child(even){background-color: #f2f2f2;}
          #customers tr:hover {background-color: #ddd;}

          #customers th {
            padding-top: 5px;
            padding-bottom: 5px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
          }
          </style>

        <div class="row">
          <div class="col-sm-10">
            <div class="box-body">
              <h4 class="">
                <i class="fa fa-bullseye"></i> Recent Query Behaviour</h4>
                            <table id="customers" class="table table-hover table-no-border">
                                <thead>
                                    <tr>
                                        <th>{{ trans('admin/leads/general.columns.id') }}</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Contact Person</th>
                                        <th>Next Action Date</th>
                                         <th>Status</th>
                                         <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach($queries as $lk => $user)
                                    <tr>
                                      <td>
                                        {{$user->id}}
                                      </td>
                                       <td>
                                         <span style="font-weight: bold" class="">{{ $user->course->name }}</span>
                                      </td>
                                       <td>
                                        <span>{{ $user->price }}</span>
                                      </td>
                                       <td>
                                        <span class="label label-default">{{ $user->contact_person }}</span>
                                      </td>
                                     
                                       <td>
                                        {!! date('dS M y', strtotime($user->next_action_date)) !!}
                                      </td>
                                       <td>
                                        <span class="label label-primary">{{ $user->status }}</span>
                                      </td>
                                      <td>
                                        {!! date('dS M y', strtotime($user->created_at)) !!}
                                      </td>                                  
                                        <td>
                                            <?php 
                                                $datas = '';
                                                if ($user->isEditable())
                                                    $datas .= '<a href="'.route('admin.lead.query_edit', $user->id).'" title="Edit"> <i class="fa fa-edit"></i> </a>';
                                                else
                                                    $datas .= '<i class="fa fa-edit text-muted" title="{{ trans(\'admin/leads/general.error.cant-edit-this-lead\') }}"></i>';
                                                if ( $user->isDeletable() )
                                                    $datas .= '<a href="'.route('admin.lead.query-delete', $user->id).'"  title="Delete"><i class="fa fa-trash deletable"></i></a>'; 
                                                else
                                                    $datas .= '<i class="fa fa-trash text-muted" title="{{ trans(\'admin/leads/general.error.cant-delete-this-lead\') }}"></i>';
                                                echo $datas;
                                            ?>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                    </div><!-- /.box-body -->
          </div>
        </div>

        @endif


        <div class='col-md-4'>
        	<div class="callout callout-default">
            	<div class="callout callout-default">

                <div style="margin-bottom: 0px;" class="row">
                     <div class="pull-left col-md-2 rounded-circle">
                     <!--  <img class="rounded-circle" src="{!! TaskHelper::getGravatarAttribute($lead->email) !!}" /> -->
                    </div>
                </div>  

                    @if($lead->mob_phone != '')
               
                        @if(substr($lead->mob_phone,0,4)=='+977')
                         <?php $mobile_no = substr($lead->mob_phone,4,15)  ?>

                          <p> <i class="fa fa-mobile"></i> Phone: 
                            <a style="color: black" href="tel:{{ $lead->mob_phone }}"> {{ $lead->mob_phone }} </a> ..  

                             <a style="color: black" href="viber://chat?number=977{{$mobile_no}}"> <i class="fab fa-viber"> Viber </i> </a> .. 
                             <a style="color: black" href="https://api.whatsapp.com/send?phone=977{{$mobile_no}}">  <i style="color: black" class="fa fa-whatsapp"> Whatsapp </i></a>
                            <a style="color: black" href="tel:{{ $lead->home_phone }}"> {{ $lead->home_phone }} </a>
                          </p> 

                         @else

                          <p> <i class="fa fa-mobile"></i> Phone: 
                        <a style="color: black" href="tel:{{ $lead->mob_phone }}"> {{ $lead->mob_phone }} </a> ..  

                         <a style="color: black" href="viber://chat?number=977{{$lead->mob_phone}}"> <i class="fab fa-viber"> Viber </i> </a> .. 
                         <a style="color: black" href="https://api.whatsapp.com/send?phone=977{{$lead->mob_phone}}">  <i style="color: black" class="fab fa-whatsapp"> Whatsapp </i></a>

                        <a style="color: black" href="tel:{{ $lead->home_phone }}"> {{ $lead->home_phone }} </a>
                      </p>

                       @endif

                     @endif
                  

                  @if(!empty($lead->skype))
                  <p> <i class="fab fa-skype"></i> Skype: <a style="color: black" target="_blank" href="{{ $lead->skype }}">{{ $lead->skype }}</a></p>
                  @endif

                  @if(!empty($lead->homepage))
                  <p> <i class="fa fa-home"></i> Website: <a style="color: black" target="_blank" href="{{ $lead->skype }}">{{ $lead->homepage }}</a></p>
                  @endif

                  @if(!empty($lead->email))
                  <p> <i class="fa fa-envelope"></i> Email: {!! $lead->email !!}</p>
                  @endif

                  @if(!empty($lead->city))
                  <p> <i class="fa fa-map"></i> Location: {!! $lead->city !!}, {!! $lead->address_line_1 !!}</p>
                  @endif

                  @if($lead->target_date != '0000-00-00')
                  <p> <i class="fa fa-calendar"></i> Next Action Date:{!! date('dS M y', strtotime($lead->target_date)) !!}</p>
                  @endif

                  <p> <i class="fa fa-user"></i> Owner: {!! $lead->user->first_name !!} 
                    for  <strong>{{ $lead->course->name }}</strong></p> 

                </div>

                <div class="box-body">

                    <div class="content">

                        <div class="row">

                          <div class="col-md-6">
                            <div class="input-group">
                              
                              <span class="input-group-addon">Amount NPR</span>
                              {!! Form::text('amount', number_format($lead->amount, 2, '.', ','), ['class' => 'form-control', 'readonly']) !!} 
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="input-group">
                               <span class="input-group-addon">@</span>

                                @if($lead->communication_id != '' && $lead->communication_id != '0')
                                {!! Form::text('name', $lead->communication->name, ['class' => 'form-control', 'readonly']) !!}
                                @else
                                {!! Form::text('name', null, ['class' => 'form-control', 'readonly']) !!}
                                @endif
                            </div>
                         </div> 

                        </div>

                        <div class="row">

                          <div class="col-md-6">
                            <div class="form-group"> 

                              <br/> <span> Status </span>

                              {!! Form::select('status_id', $lead_status, $lead->status_id, ['class' => 'form-control label-default', 'id' => 'status_id']) !!}

                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <br/> <span> Rating </span>

                              {!! Form::select('rating', ['acquired'=>'Acquired', 'active'=>'Active', 'failed'=>'Failed','cancel'=>'Cancelled','shut'=>'ShutDown'], $lead->rating, ['class' => 'form-control label-default', 'id' => 'rating']) !!}

                            </div>
                          </div> 

                    <div class="clearfix"></div>
                    <div class="row">
                          <div class="col-md-12">
                            <span id="ajax_status"></span>
                          </div>
                        </div>
                                                  
                        </div>

                        <div class="row">
                          
                          <div class="col-md-6">
                          	<div class="form-group" style="margin-bottom:0;">
                                <label style="margin-top: 40px;">
                                {!! Form::checkbox('enabled', '1', $lead->enabled, ['disabled']) !!} Active
                                </label>
                            </div>
                          </div>                          
                        </div>                        
                        <div class="form-group">
                        	<a href="{!! route('admin.leads.index') !!}?type={{\Request::get('type')}}" class='btn btn-danger'>{{ trans('general.button.close') }}</a>
                        	@if ( $lead->isEditable() || $lead->canChangePermissions() )
	                                <a href="{!! route('admin.leads.edit', $lead->id) !!}?type={{\Request::get('type')}}" class='btn btn-success'>{{ trans('general.button.edit') }}</a>
	                            @endif
                        </div>

                          <div class="box">
                              <div class="box-header">
                                <h3 class="box-title"><i class="fa fa-mobile"></i> App Call Logs</h3>

                              </div>
                              <!-- /.box-header -->
                              <div class="box-body no-padding">
                                <table class="table table-striped">
                                  <tbody>
                                  <tr>
                                    <th style="width: 10px">#</th>
                                    <th>User</th>
                                    <th>Mobile No</th>
                                    <th style="width: 40px">created_at</th>
                                  </tr>
       
                               @if(isset($phone_logs) && !empty($phone_logs))
                                @foreach($phone_logs as  $phone_logs)
                                  <tr>
                                    <td>{!! $phone_logs->id !!}.</td>
                                    <td>{{ $phone_logs->user->username }}</td>
                                    <td>
                                      {{ $phone_logs->mob_phone }}
                                    </td>
                                    <td> {!! date('d M y', strtotime($phone_logs->created_at)) !!}</td>
                                  </tr>
                                  @endforeach
                                  @endif

                                </tbody>
                              </table> 
                             </div>
                          <!-- /.box-body -->
                        </div>

                    </div><!-- /.content -->

                </div><!-- /.box-body -->
            </div>
        </div><!-- /.col -->

        <div class='col-md-4'>
          <div class="callout callout-default">
            <div class="box-body">
                    
                    {!! Form::textarea('lead_note', $lead->lead_note, ['class' => 'form-control', 'id'=>'lead_note', 'placeholder' => 'Make a note for this lead', 'rows'=>'2']) !!}<br/>
                    <input type="checkbox" name="note_to_task" id="note_to_task" value="1"> &nbsp; Add Note to Task<br/>
                    <div id="task_dates" style="display:none;">
                    	<div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('task_due_date', trans('admin/tasks/general.columns.task_due_date')) !!} <i class="fa fa-calendar"></i> 
                                    {!! Form::text('task_due_date', $lead->task_due_date, ['class' => 'form-control', 'id'=>'task_due_date']) !!}
                                </div>
                            </div>  

                            <div class="col-md-6">
                            <div class="form-group">
                      {!! Form::label('task_assign_to', trans('admin/tasks/general.columns.task_assign_to')) !!} <i class="fa fa-user"></i> 
                        {!! Form::select('task_assign_to',  $users, \Auth::user()->first_name, ['class' => 'form-control input-sm', 'id'=>'task_assign_to']) !!}
                    </div>    </div>

                        </div>
                    </div>
                    {!! Form::button('Submit', ['class' => 'btn btn-default btn-xs', 'id'=>'submit-note']) !!}
                    <br/>

                <div class="note-list" id="note-list">
                	@foreach($notes as $k => $v)
                    	<div class="note-wrap" style="margin-top:10px; padding:0 15px; position: relative;">
                           
                            <p data-letters="{{mb_substr($v->note,0,3)}}">{!! $v->note !!}</p>
                            <i class="date">{!! ' ('.\Carbon\Carbon::createFromTimeStamp(strtotime($v->created_at))->diffForHumans().') by '.$v->user->first_name !!}</i>
                            @if(Auth::user()->id == $v->user_id)
                            <a title="Delete" data-target="#modal_dialog" data-toggle="modal" href="/admin/leadnotes/{!! $v->id !!}/confirm-delete" style="position:absolute; top:0; right:0;"><i class="fa fa-trash deletable"></i></a>
                            @endif
                        </div>
                        <hr style="margin:5px 0 0; border-color:#000;">
                    @endforeach
               </div>
            </div>
          </div>

         <div class="box box-default">
         	<div class="box-body">
            	<label>File: </label>
                {!! Form::file('lead_file', ['class' => 'lead_file', 'id'=>'lead_file']) !!}<br/>

                {!! Form::button('Upload', ['class' => 'btn btn-primary btn-xs', 'id'=>'upload-file']) !!}<br/>

                <div class="file-list" id="file-list">
                	@foreach($files as $key => $val)
                    	<div class="task-wrap" style="margin-top:10px; padding:0 15px; position: relative;">
                            <p style="margin-bottom:0; font-weight:bold;"><a href="/files/{!! $val->file !!}">{!! $val->file !!}</a></p>
                            <i class="date">{!! \Carbon\Carbon::createFromTimeStamp(strtotime($val->created_at))->diffForHumans().' by '.$val->user->first_name !!}</i>
                            @if(\Auth::user()->id == $val->user_id)
                            <a title="Delete" data-target="#modal_dialog" data-toggle="modal" href="/admin/leadfiles/{!! $val->id !!}/confirm-delete" style="position:absolute; top:0; right:0;"><i class="fa fa-trash deletable"></i></a>
                            @endif
                        </div>
                    @endforeach
               </div>

            </div>
         </div>

         <div class="box box-default">
         	<div class="box-body">
            	<label>Email: </label>
                <div class="file-list" id="file-list">
                	@foreach($emails as $ekey => $eval)
                    	<div style="margin-top:10px; padding:0 15px; position: relative;">
                        	<div class="mail-wrap" id="wrap-{!! $eval->id !!}">
                                <p style="margin-bottom:0; font-weight:bold;">
                                    {!! $eval->subject !!} &nbsp;&nbsp; @if(sizeof($eval->attachment)) (<i class="fa fa-paperclip"></i>) @endif
                                </p>
                                <i class="date">{!! \Carbon\Carbon::createFromTimeStamp(strtotime($eval->created_at))->diffForHumans().' by '.$eval->user->first_name !!}</i>
                            </div>
                            @if(\Auth::user()->id == $lead->user_id || \Auth::user()->id == $eval->user_id)
                            <a title="Delete" data-target="#modal_dialog" data-toggle="modal" href="/admin/mail/{!! $eval->id !!}/confirm-delete" style="position:absolute; top:0; right:0;"><i class="fa fa-trash deletable"></i></a>
                            @endif
                        </div>
                    @endforeach
               </div>
            </div>
         </div>
         
         <div class="box box-default">
            <div class="box-body">
                <div class="col-md-12">
                    <label>Tasks: </label>
                    <div style="float:right; display:inline-block;"><a class="btn btn-primary btn-xs" href="/admin/tasks/create?lead_id={!! $lead->id !!}">Create Task</a></div>                    
                </div>
                <div class="clearfix"></div>
                <div class="task-list" id="task-list">
                	@foreach($tasks as $tk => $tv)
                    	<div class="task-wrap" style="margin-top:10px; padding:0 15px; position: relative;">
                          <a href="/admin/tasks/{!! $tv->id !!}">{!! $tv->task_subject !!}</a><br/>
                          <i class="date">Assigned To: {!! $tv->assigned_to->first_name !!}</i>   
                          <?php
            								$due = date('Y-m-d',strtotime($tv->task_due_date));
            								if($due == date('Y-m-d'))
            									$d_date = '<span style="color:red;">'.$tv->task_due_date.'</span>';
            								else
            									$d_date = $tv->task_due_date;
						              ?>                                                     
                          <span style="position:absolute; top:0; right:0;">Due Date: {!! $d_date !!}</span>
                      </div>
                    @endforeach
               </div>
            </div>
          </div>

          <div class="box box-default">
            <div class="box-body">
              <label>SMS: </label>
                <div class="file-list" id="file-list">
                  @foreach($smses as $sk => $sv)
                      <div class="task-wrap" style="margin-top:10px; position: relative;">
                          <div class="col-md-8" style="padding: 0 !important;"> {{ $sv->message }}</div>
                          <?php
                            $s_due = date('Y-m-d',strtotime($sv->created_at));
                            if($s_due == date('Y-m-d'))
                              $s_date = '<span style="color:green;">'.$sv->created_at.'</span>';
                            else
                              $s_date = $sv->created_at;
                          ?>
                          <div class="col-md-4" style="position:absolute; top:0; right:0;">Sms Sent on: {!! $s_date !!}</div>
                          <div class="clearfix"></div>
                      </div>
                    @endforeach
               </div>
            </div>
         </div>
          
	     </div>

       <div class="col-md-4">

        <br/>


        @if(isset($proposal) && sizeof($proposal)) 
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-briefcase"></i> Recent Contracts and Proposals</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>


            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
               
               
                @foreach($proposal as $k)
                <li class="item">
                  <div class="product-img">
                    <span data-letters="{{mb_substr($k->product->name,0,3)}}"></span>
                  </div>
                 
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">{{ $k->product->name }}
                      <span class="label label-success pull-right">{{ ucfirst($k->status)}}</span></a>
                    <span class="product-description">
                          {!! link_to_route('admin.proposal.show', $k->subject, [$k->id], ['target' => '_blank']) !!}
                        </span>
                  </div>
                </li>
                @endforeach
               
              </ul>
            </div>
            <!-- /.box-body -->
           
          </div>
          @endif


           @if(isset($cases) && sizeof($cases)) 
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-briefcase"></i> Recent Cases</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
               
               
                @foreach($cases as $k)
                <li class="item">
                  <div class="product-img">
                    <span data-letters="{{mb_substr($k->type,0,3)}}"></span>
                  </div>
                 
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">{{ ucfirst($k->type)}}
                      <span class="label label-success pull-right">{{ ucfirst($k->status)}}</span></a>
                    <span class="product-description">
                          {!! link_to_route('admin.cases.show', $k->subject, [$k->id], ['target' => '_blank','style'=>'color:black;text-decoration:none']) !!}
                        </span>
                  </div>
                </li>
                @endforeach
               
              </ul>
            </div>
            <!-- /.box-body -->
           
          </div>
          @endif


     @if(isset($quotations) && sizeof($quotations)) 
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-briefcase"></i> Recent Quotations</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>


            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
               
               
                @foreach($quotations as $k)
                <li class="item">
                  <div class="product-img">
                    <span data-letters="{{mb_substr($k->total,0,3)}}"></span> 
                  </div>
                 
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">{{ ucfirst($k->status)}}
                      <h3 class="pull-right ">{{env('APP_CURRENCY')}} {{ number_format($k->total_amount,2)}}</h3></a>
                    <span class="product-description">
                          {!! link_to_route('admin.orders.show', $k->lead->name . ' #'.$k->id , [$k->id], []) !!}
                        </span>
                  </div>
                </li>
                @endforeach
               
              </ul>
            </div>
            <!-- /.box-body -->
           
          </div>
          @endif



  


 @if(isset($orders) && sizeof($orders)) 
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-briefcase"></i> Recent Orders</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>


            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
               
               
                @foreach($orders as $k)
                <li class="item">
                  <div class="product-img">
                    <span data-letters="{{mb_substr($k->total,0,3)}}"></span>
                  </div>
                 
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">{{ ucfirst($k->status)}}
                      <h3 class="pull-right ">{{ number_format($k->total,2)}}</h3></a>
                    <span class="product-description">
                          {!! link_to_route('admin.orders.show', $k->lead->name . ' #'.$k->id , [$k->id], []) !!}
                        </span>
                  </div>
                </li>
                @endforeach
               
              </ul>
            </div>
            <!-- /.box-body -->
           
          </div>
          @endif






       


       

       </div>
       <div class="clearfix"></div>

    </div><!-- /.row -->

    <div role="dialog" class="modal fade" id="sendSMS" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg" style="width:400px;">    
        <!-- Modal content-->
        {!! Form::open( array('route' => 'admin.leads.send-lead-sms') ) !!}
        <div class="modal-content">
          <div class="modal-header bg-orange">
            <button data-dismiss="modal" class="close" type="button">×</button>
            <h4 class="modal-title">Send SMS</h4>
          </div>
          <div class="modal-body">
                <div class="form-group">
                    <!-- <span>Note: Maximum 138 character limit</span><br/>
                    <textarea rows="3" name="message" class="form-control" id="message-textarea" placeholder="Type your message." maxlength="138" required></textarea> -->
                    <textarea rows="3" name="message" class="form-control" id="message-textarea" placeholder="Type your message." required></textarea>
                    <input type="hidden" name="lead_id" id="lead_id" value="{!! $lead->id !!}">
                    <input type="hidden" name="recipients_no" value="{!! $lead->mob_phone !!}">
                    <!-- <span class="char-cnt"></span> -->
                </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('general.button.cancel') }}</button>
              <button type="submit" class="btn btn-primary" name="submit">Send</button>
          </div>
        </div>
        <script>
			/*var text_max = 138;
        		$('.char-cnt').html(text_max + ' characters remaining');*/
			$(document).ready(function() {
				/*$('#message-textarea').keyup(function() {
    					var text_length = $('#message-textarea').val().length;
    					var text_remaining = text_max - text_length;
    					$('.char-cnt').html(text_remaining + ' characters remaining');
    				});*/
				
				$(".modal").on("hidden.bs.modal", function(){
					//$(".modal-body1").html("");
				});
			});
		</script>
        {!! Form::close() !!}    
      </div>
    </div>
    
@endsection

@section('body_bottom')
    <!-- Select2 js -->
    @include('partials._body_bottom_select2_js_user_search')
    <script>
		<!-- To submit the note for the Lead - by Ajax -->
		$(document).on('click', '#submit-note', function() {

			var token =  $('meta[name="csrf-token"]').attr('content');
			var user_id = {!! \Auth::user()->id !!};
			var lead_id = {!! $lead->id !!};
      var task_assign_to = $("#task_assign_to").val();
			var note = $("#lead_note").val();
			if(note != '')
			{
				$("#lead_note").removeClass("danger");
				
				// Check if 'Add Note to Task' is checked or not
				if($("#note_to_task").is(':checked'))
				{
					$("#task_due_date").removeClass("danger");
				
					var task_due_date = $("#task_due_date").val();
					
					if(task_due_date == '')
						$("#task_due_date").addClass("danger");
						
					if(task_due_date != '')
					{
						var datastring = '_token='+token+'&user_id='+user_id+'&lead_id='+lead_id+'&note='+note+'&task_due_date='+task_due_date+'&task_assign_to='+task_assign_to;
						$.ajax({
							url: '/admin/leadnotes',
							dataType: 'JSON',
							type: 'post',
							contentType: 'application/x-www-form-urlencoded',
							data: datastring,
							success: function(data){
							   document.getElementById('note-list').innerHTML = data.messages;
							},
							error: function( jqXhr, textStatus, errorThrown ){
								console.log( errorThrown );
							}
						});
					}
				}
				else
				{
					var datastring = '_token='+token+'&user_id='+user_id+'&lead_id='+lead_id+'&note='+note+'&task_due_date='+task_due_date+'&task_assign_to='+task_assign_to;
					$.ajax({
						url: '/admin/leadnotes',
						dataType: 'JSON',
						type: 'post',
						contentType: 'application/x-www-form-urlencoded',
						data: datastring,
						success: function(data){
						   document.getElementById('note-list').innerHTML = data.messages;
						},
						error: function( jqXhr, textStatus, errorThrown ){
							console.log( errorThrown );
						}
					});
				}
			}
			else
			{
				$("#lead_note").addClass("danger");
			}
		});

		<!-- To delete the note for the Lead -->
		$(document).on('click', '.delete-note', function() {

			var token =  $('meta[name="csrf-token"]').attr('content');
			var note_id = $(this).attr('id').split('-')[1];

			var datastring = '_token='+token+'&note_id='+note_id;
			$.ajax({
				url: '/admin/leadnotes/'+note_id+'/confirm-delete',
				dataType: 'JSON',
				type: 'get',
				contentType: 'application/x-www-form-urlencoded',
				data: datastring,
				success: function(data){
				   document.getElementById('note-list').innerHTML = data.messages;
				},
				error: function( jqXhr, textStatus, errorThrown ){
					console.log( errorThrown );
				}
			});
		});


		<!-- To submit the file for the Lead - by Ajax -->
		$(document).on('click', '#upload-file', function() {
			if($("#lead_file").val() !='')
			{
				$("#lead_file").removeClass("danger");
				var formData = new FormData();
				formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
				formData.append('lead_id', {!! $lead->id !!});
				formData.append('user_id', {!! \Auth::user()->id !!});
        formData.append('file_name', $('#lead_file')[0].files[0].name.split('.').shift());
				//formData.append('file', $('#lead_file').val());
        formData.append('file', $('#lead_file')[0].files[0]);
				//formData.append('file', $('input[type=file]')[0].files[0]);
        $.ajax({
					url: '/admin/leadfiles',
					data: formData,
					dataType: 'JSON',
					type: 'post',
					//async:false,
					processData: false,
					contentType: false,
					//contentType: 'application/x-www-form-urlencoded',
					success: function(data){
					   document.getElementById('file-list').innerHTML = data.messages;
					},
					error: function( jqXhr, textStatus, errorThrown ){
						console.log( errorThrown );
					}
				});
			}
			else
				$("#lead_file").addClass("danger");
		});

		<!-- To delete the note for the Lead -->
		$(document).on('click', '.delete-file', function() {

			var token =  $('meta[name="csrf-token"]').attr('content');
			var file_id = $(this).attr('id').split('-')[1];

			var datastring = '_token='+token+'&file_id='+file_id;
			$.ajax({
				url: '/admin/leadfiles/'+file_id+'/confirm-delete',
				dataType: 'JSON',
				type: 'get',
				contentType: 'application/x-www-form-urlencoded',
				data: datastring,
				success: function(data){
				   document.getElementById('file-list').innerHTML = data.messages;
				},
				error: function( jqXhr, textStatus, errorThrown ){
					console.log( errorThrown );
				}
			});
		});

		<!-- To show the detail of the email -->
		$(document).on('click', '.mail-wrap', function() {

			var token =  $('meta[name="csrf-token"]').attr('content');
			var mail_id = $(this).attr('id').split('-')[1];

			var datastring = '_token='+token+'&mail_id='+mail_id;
			$.ajax({
				url: '/admin/mail/from_lead/'+mail_id,
				//dataType: 'JSON',
				type: 'get',
				contentType: 'application/x-www-form-urlencoded',
				data: datastring,
				success: function(data){
					$('#modal_lead_mail').find(".modal-content").html(data.messages);
					$('#modal_lead_mail').modal();
				},
				error: function( jqXhr, textStatus, errorThrown ){
					console.log( errorThrown );
				}
			});
		});

		<!-- To clear the content of the modal box and reload another content -->
		$(document).ready(function() {
			$(".modal").on("hidden.bs.modal", function(){
				 $(this).removeData();
			});
		});

    $(document).on('change', '#status_id', function() {
       var id = $('#show_lead_id').val();
      var status_id = $(this).val();

      $.post("/admin/ajax_lead_status",
      {id: id, status_id: status_id, _token: $('meta[name="csrf-token"]').attr('content')},
      function(data, status){
        if(data.status == '1')
            $("#ajax_status").after("<span style='color:green;' id='status_update'>Status is successfully updated.</span>");
        else
            $("#ajax_status").after("<span style='color:red;' id='status_update'>Problem in updating status; Please try again.</span>");

        $('#status_update').delay(3000).fadeOut('slow');
        //alert("Data: " + data + "\nStatus: " + status);
      });
    });

    $(document).on('change', '#rating', function() {
      var id = $('#lead_id').val();
      var rating = $(this).val();

      $.post("/admin/ajax_lead_rating",
      {id: id, rating: rating, _token: $('meta[name="csrf-token"]').attr('content')},
      function(data, status){
        if(data.status == '1')
            $("#ajax_status").after("<span style='color:green;' id='status_update'>Rating is successfully updated.</span>");
        else
            $("#ajax_status").after("<span style='color:red;' id='status_update'>Problem in updating rating; Please try again.</span>");

        $('#status_update').delay(3000).fadeOut('slow');
        //alert("Data: " + data + "\nStatus: " + status);
      });
    });

	</script>
@endsection
