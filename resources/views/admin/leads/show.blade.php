@extends('layouts.master')

@section('head_extra')
@php 
    $user = isset($user) ? $user : null;
    $mobile_no = isset($mobile_no) ? $mobile_no : null;
    $courses = isset($courses) ? $courses : null;
@endphp
<!-- Select2 css -->
@include('partials._head_extra_select2_css')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
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
            format: 'DD-MM-YYYY HH:mm'
            , sideBySide: true
        });
    });

    $(document).on('click', '#note_to_task', function() {
        if ($("#note_to_task").is(':checked'))
            $("#task_dates").show();
        else
            $("#task_dates").hide();
    });

</script>
@endsection

@section('content')

<style type="text/css">
    [data-letters]:before {
        content: attr(data-letters);
        display: inline-block;
        font-size: 1em;
        width: 2.5em;
        height: 2.5em;
        line-height: 2.5em;
        text-align: center;
        border-radius: 50%;
        background: red;
        vertical-align: middle;
        margin-right: 0.3em;
        color: white;
    }

    .has-error {
        border-color: #f14668
    }

</style>
<input type="hidden" id='lead_id' value="{{ \Request::segment(3) }}">
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{$lead->name}}
        <small>{{ PayrollHelper::getDepartment($user->departments_id??'') }},
            {{ PayrollHelper::getDesignation($user->designations_id??'') }}</small>
    </h1>

    <span class="pull-left">

        <a class="btn btn-warning btn-xs" href="#" onClick="openmodal()" title="{{ trans('admin/leads/general.button.create') }}">
            <i class="fa fa-edit"></i> <strong> Quick {!! ucfirst($_GET['type']) !!}</strong>
        </a>
    </span>

    <span class="pull-right">

        <small id="ajax_status"></small>
        <a class="btn bg-orange btn-xs" href="#" data-target="#sendSMS" data-toggle="modal">
            <i class="fa fa-mobile"></i> Send SMS</a>
        @if($lead->stage_id == 3 || $lead->stage_id == 4)
        <a class="btn btn-default btn-xs quotations-proposal" href="/admin/orders/create?type=quotation"> <i class="fa fa-book"></i> Quote</a>
        @endif


        <a href="/admin/mail/{!! $lead->id !!}/show-offerlettermodal" class="btn btn-success btn-xs" data-target="#modal_dialog" data-toggle="modal" title="Offer Letter">Send Offer Mail</a>
        <a href="/admin/mail/{!! $lead->id !!}/show-unsuccessfulapplicationmodal" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal_dialog" title="Reminder">Reminder Mail</a>
        <a href="/admin/mail/{!! $lead->id !!}/show-pendingmodal" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal_dialog" title="Pending">Thank You Mail</a>



      
        <a class="btn btn-default btn-xs" href="/admin/transfer_lead/{!! $lead->id !!}"> <i class="fs fa-exchange-alt"></i> Reassign / Transfer</a>
        

        @if(!$lead->moved_to_client)
        <a class="btn btn-default btn-xs" href="{{route('admin.lead.convert_lead_clients-confirm',$lead->id)}}" data-toggle="modal" data-target="#modal_dialog"> <i class="fs fa-exchange-alt">Post to client</i></a>
        @endif

    </span>
</section>

<section class="content">

    <div class="row">
        <div class="col-md-4">

            <!-- Profile Image -->
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border bg-info">
                    <h3 class="box-title">New {{\Request::get('type')}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <table class="table  table-no-border ">
                        <tr class="bg-success">
                            <th> ID#</th>
                            <th> Name</th>

                            <th> Followup </th>

                        </tr>
                        <tbody id="leads-table">
                            @foreach($leads as $l)
                            <tr @if(\Request::segment(3)==$l->id) class="bg-info" @endif>
                                <td>{{\FinanceHelper::getAccountingPrefix('LEADS_PRE')}}{{ $l->id }}</td>
                                <td style="float:lef; font-size: 16px">
                                    <a href="/admin/leads/{{$l->id}}?type={{\Request::get('type')}}">{{$l->name}}</a>

                                </td>

                                <td>
                                    @if($l->target_date >= date('Y-m-d') OR $l->target_date == date('0000-00-00'))
                                    <span class="btn bg-success btn-xs">{{$l->target_date}}</span>
                                    @else
                                    <span class="btn btn-danger btn-xs">
                                        <i class="fa fa-clock-o fa-spin"></i>
                                        missed</span>
                                    @endif
                                </td>


                            </tr>
                            @endforeach

                    </table>
                    </tbody>


                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->


        
<div class="col-md-8">

<style>

.steps{min-height:90px;padding:30px 0 0 0;font-family:'Open Sans', sans-serif;position:relative}.steps .steps-container{background:#DDD;height:10px;width:100%;border-radius:10px   ;-moz-border-radius:10px   ;-webkit-border-radius:10px   ;-ms-border-radius:10px   ;margin:0;list-style:none}.steps .steps-container li{text-align:center;list-style:none;float:left}.steps .steps-container li .step{padding:0 50px}.steps .steps-container li .step .step-image{margin:-14px 0 0 0}.steps .steps-container li .step .step-image span{background-color:#DDD;display:block;width:37px;height:37px;margin:0 auto;border-radius:37px   ;-moz-border-radius:37px   ;-webkit-border-radius:37px   ;-ms-border-radius:37px   }.steps .steps-container li .step .step-current{font-size:11px;font-style:italic;color:#999;margin:8px 0 0 0}.steps .steps-container li .step .step-description{font-size:13px;font-style:italic;color:#538897}.steps .steps-container li.activated .step .step-image span{background-color:#5DC177}.steps .steps-container li.activated .step .step-image span:after{background-color:#FFF;display:block;content:'';position:absolute;z-index:1;width:27px;height:27px;margin:5px;border-radius:27px   ;-moz-border-radius:27px   ;-webkit-border-radius:27px   ;-ms-border-radius:27px   ;box-shadow: 4px 4px 0px 0px rgba(0,0,0,0.15) ;-moz-box-shadow: 4px 4px 0px 0px rgba(0,0,0,0.15) ;-webkit-box-shadow: 4px 4px 0px 0px rgba(0,0,0,0.15) }.steps .step-bar{background-color:#5DC177;height:10px;position:absolute;top:30px;border-radius:10px 0 0 10px;-moz-border-radius:10px 0 0 10px;-webkit-border-radius:10px 0 0 10px;-ms-border-radius:10px 0 0 10px}.steps .step-bar.last{border-radius:10px   ;-moz-border-radius:10px   ;-webkit-border-radius:10px   ;-ms-border-radius:10px   }

body {
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    color: #333;
}

</style>

<div class="steps">
    <ul class="steps-container">
        <li style="width:25%;" class="activated">
            <div class="step">
                <div class="step-image"><span></span></div>
                <div class="step-current">Etapa 1</div>
                <div class="step-description">New</div>
            </div>
        </li>
        <li style="width:25%;">
            <div class="step">
                <div class="step-image"><span></span></div>
                <div class="step-current">Etapa 2</div>
                <div class="step-description">Hot</div>
            </div>
        </li>
        <li style="width:25%;">
            <div class="step">
                <div class="step-image"><span></span></div>
                <div class="step-current">Etapa 3</div>
                <div class="step-description">Cold</div>
            </div>
        </li>
        <li style="width:25%;">
            <div class="step">
                <div class="step-image"><span></span></div>
                <div class="step-current">Etapa 4</div>
                <div class="step-description">Pre Sales</div>
            </div>
        </li>

    </ul>
    <div class="step-bar" style="width: 25%;"></div>
</div>

</div>

  
  <div class="col-md-8">


            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs bg-danger">
                    <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="false">Details</a></li>
                    <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="true">Follow up</a></li>
                    @if($lead->stage_id == 3 || $lead->stage_id == 4)
                    <li class="quotations-proposal"><a href="#quote" data-toggle="tab">Quotations</a></li>
                    @endif
                    <li><a href="#filendocs" data-toggle="tab">Files & Docs</a></li>
                    <li><a href="#meetings" data-toggle="tab">Meetings</a></li>
                    <li><a href="#logs" data-toggle="tab">Logs</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="activity">
                        <!-- Post -->

                        

                        <div class="">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <i class="fa fa-info"></i>

                                    <h3 class="box-title">{{ $lead->name }} <small>looking for</small>
                                        <span style=""><b>{{ $lead->product->name }}</b></span>
                                        <a href="{!! route('admin.leads.index') !!}?type={{\Request::get('type')}}" class='btn btn-danger btn-xs'>{{ trans('general.button.close') }}</a>
                                        @if ( $lead->isEditable() || $lead->canChangePermissions() )
                                        <a href="{!! route('admin.leads.edit', $lead->id) !!}?type={{\Request::get('type')}}" class='btn btn-success btn-xs'>{{ trans('general.button.edit') }}</a>
                                        @endif
                                    </h3>

                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <dl class="dl-horizontal">
                                        <dt>Stage</dt>
                                        <dd>
                                            {{ $lead->stage->name ??'' }} @if($lead->stage_id == 5 || $lead->stage_id == 6) ({{ $lead->reason->reason??'' }}) @endif
                                        </dd>
                                        @if($lead->mob_phone != '')

                                        <dt>Mobile Phone</dt>
                                        <dd><span id='mob_phone'>{{ $lead->mob_phone}}</span> &nbsp;
                                            <a _blank href="viber://chat?number=977{{$mobile_no}}">
                                                <span class="fa fa-viber"> Viber </span> </a> .. &nbsp;
                                            <a target="_blank" href="https://api.whatsapp.com/send?phone={{$mobile_no}}"> <i class="fa fa-whatsapp"> Whatsapp </i></a>

                                        </dd>
                                        @endif

                                        @if($lead->home_phone != '')
                                        <dt>Home Phone</dt>
                                        <dd><a href="tel:{{ $lead->home_phone }}"> {{ $lead->home_phone }} </a></dd>
                                        @endif


                                        @if(!empty($lead->homepage))
                                        <dt>Homepage</dt>
                                        <dd><a target="_blank" href="{{ $lead->homepage }}">
                                                Visit Website</a></dd>
                                        @endif

                                        @if(!empty($lead->email))
                                        <dt>Email</dt>
                                        <dd>
                                            <a href="mailto:{{ $lead->email }}" id='email-edit'>{{ $lead->email }}</a>
                                            <a href="javascript:void()" title="edit email" id='email-edit-button'><i class="fa fa-edit editable"></i></a>
                                        </dd>
                                        @endif

                                        @if(!empty($lead->city))
                                        <dt>Address</dt>
                                        <dd>
                                            {!! $lead->city !!}, {!! $lead->address_line_1 !!}
                                        </dd>
                                        @endif

                                        @if($lead->target_date != '0000-00-00')
                                        <dt>Next Action Date</dt>
                                        <dd>
                                            <input id="datepicker_follow_date" style="width: 60px;border:none;" value="{{date('d M y',strtotime($lead->target_date))}}">
                                        </dd>
                                        @endif

                                        @if($lead->rating)
                                        <dt>Rating</dt>
                                        <dd>
                                            <span id='rating' data-type='select' data-value='{{  $lead->rating }}'></span>
                                        </dd>
                                        @endif

                                        @if($lead->status_id)
                                        <dt>Status</dt>
                                        <dd>
                                            <span id='status_id' data-type='select' data-value='{!! $lead->status_id !!}'></span>
                                        </dd>
                                        @endif

                                        @if($lead->campaign_id)
                                        <dt>Campaign </dt>
                                        <dd>
                                            <span id='campaign_id' data-type='select' data-value='{{ $lead->campaign_id }}'></span>
                                        </dd>
                                        @endif

                                        @if($lead->communication_id)
                                        <dt>Source</dt>
                                        <dd>
                                            <span id="source_id" data-type='select' data-value='{!! $lead->communication_id !!}'> </span>
                                        </dd>
                                        @endif

                                        @if($lead->skype)
                                        <dt>Skype</dt>
                                        <dd>
                                            {!! $lead->skype !!}
                                        </dd>
                                        @endif

                                        @if($lead->price_value)
                                        <dt>Value</dt>
                                        <dd>
                                            {!! $lead->price_value !!}
                                        </dd>
                                        @endif

                                        @if($lead->dob)
                                        <dt>Date of Birth</dt>
                                        <dd>
                                            <input id="date_of_birth" style="width: 60px;border:none;" value="{{date('d M y',strtotime($lead->dob))}}">
                                        </dd>
                                        @endif

                                        <dt>Owner</dt>
                                        <dd>
                                            {!! $lead->user->first_name !!}
                                        </dd>

                                        <dt>Description</dt>
                                        <dd>
                                            <span style=" overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 4;-webkit-box-orient: vertical;" id='lead_description'>{!! $lead->description !!}</span>

                                        </dd>






                                    </dl>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>



                        <div class="box  box-primary">
                            <div class="box-header">

                                <h3 class="box-title">Update Follow up</h3>
                                <p><i class="fa fa-info-circle"></i> Tips: Record your customer interaction and iterate through stages. This will be added to the follow up feeds. If the lead in won or lost it will go to archive, however won leads will be copied to <a target="_blank" href="/admin/clients">clients</a> </p>
                            </div>

                            {!! Form::textarea('lead_note', $lead->lead_note, ['class' => 'form-control', 'id'=>'lead_note', 'placeholder' => 'Make a note for this lead', 'rows'=>'2']) !!}<br />
                            <input type="checkbox" name="note_to_task" id="note_to_task" value="1"> &nbsp; Add Note to Task<br />
                            <div id="task_dates" style="display: none">
                                <div class="row">
                                    <div class="col-md-4" id='task_due_date_div' style="display: none">
                                        <div class="form-group">
                                            {!! Form::label('task_due_date', trans('admin/tasks/general.columns.task_due_date')) !!} <i class="fa fa-calendar"></i>
                                            {!! Form::text('task_due_date', $lead->task_due_date, ['class' => 'form-control', 'id'=>'task_due_date']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4" id='closure_reason_div' style="display: none">
                                        <div class="form-group">
                                            {!! Form::label('closure_reason', 'Closure Reasons') !!} <i class="fa fa-edit"></i>
                                            {!! Form::select('closure_reason', [''=>'select reason']+$closure_reason, $lead->reason_id,['class' => 'form-control', 'id'=>'closure_reason']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('lead_stage', 'Lead Stage') !!} <i class="fa fa-user"></i>
                                            {!! Form::select('lead_stage_id', $stages, $lead->stage_id, ['class' => 'form-control input-sm', 'id'=>'lead_stage_id']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('task_assign_to', trans('admin/tasks/general.columns.task_assign_to')) !!} <i class="fa fa-user"></i>
                                            {!! Form::select('task_assign_to', $users, \Auth::user()->id, ['class' => 'form-control input-sm', 'id'=>'task_assign_to']) !!}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm', 'id'=>'submit-note']) !!}

                            <br><br>

                        </div>
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

                        <!-- /.post -->
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane " id="timeline">
                        <!-- The timeline -->
                        <ul class="timeline timeline-inverse">
                            <!-- timeline time label -->

                            @foreach($follow_up as $dates => $timeline)
                            <?php ++$loop ?>
                            <li class="time-label">
                                <span class="@if($loop  % 2 == 0) bg-red @else bg-blue @endif ">
                                    {{ date('dS Y M',strtotime($dates)) }}
                                </span>
                            </li>
                            <!-- /.timeline-label -->
                            <!-- timeline item -->
                            @foreach($timeline as $key=>$tm)
                            <li>
                                <i class="fa {{ $tm->icons }} {{ $tm->color }}"></i>

                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {!! \Carbon\Carbon::createFromTimeStamp(strtotime($tm->created_at))->diffForHumans() !!}</span>
                                    @if($tm->change_type == 'tasks')
                                    <h3 class="timeline-header"><a href="/admin/profile/show/{{$tm->user->id}}">{{ $tm->user->username }}</a> has assigned task to {{ $tm->assigned_to->username }}</h3>
                                    @elseif($tm->change_type =='closure')
                                    <h3 class="timeline-header"><a href="/admin/profile/show/{{$tm->user->id}}">{{ $tm->user->username }}</a> has Closed the lead</h3>
                                    @else
                                    <h3 class="timeline-header"><a href="/admin/profile/show/{{$tm->user->id}}">{{ $tm->user->username }}</a> changed {{ $tm->change_type }}</h3>
                                    @endif
                                    <div class="timeline-body">
                                        {!! ucfirst($tm->activity) !!}
                                    </div>

                                </div>
                            </li>
                            @endforeach
                            @endforeach
                            <!-- /.timeline-label -->
                            <!-- timeline item -->

                            {{-- <li>
                    <i class="fa fa-camera bg-purple"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                      <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                      <div class="timeline-body">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                      </div>
                    </div>
                  </li> --}}
                            <!-- END timeline item -->

                            <li>
                                <i class="fa fa-clock-o bg-gray"></i>
                            </li>
                        </ul>

                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="quote">

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
                                                <h3 class="pull-right ">{{env('APP_CURRENCY')}} {{ number_format($k->total_amount,2)}}</h3>
                                            </a>
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

                    <div class="tab-pane" id="filendocs">

                        <div class="box box-default">
                            <div class="box-body">
                                <label>File: </label>
                                {!! Form::file('lead_file', ['class' => 'lead_file', 'id'=>'lead_file']) !!}<br />

                                {!! Form::button('Upload', ['class' => 'btn btn-primary btn-xs', 'id'=>'upload-file']) !!}<br />

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

                    </div>

                    <div class="tab-pane" id="meetings">

                        <div class="box box-default">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <label>Tasks: </label>
                                    <div style="float:right; display:inline-block;"><a class="btn btn-primary btn-xs" href="/admin/tasks/create?lead_id={!! $lead->id !!}" data-target="#modal_dialog" data-toggle="modal" data-backdrop="static" data-keyboard="false">Create Task</a></div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="task-list" id="task-list">
                                    @foreach($tasks as $tk => $tv)
                                    <div class="task-wrap" style="margin-top:10px; padding:0 15px; position: relative;">
                                        <a href="/admin/tasks/{!! $tv->id !!}">{!! $tv->task_subject !!}</a><br />
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

                    </div>

                    <div class="tab-pane" id="logs">
                        <h4><i class="fa fa-mobile"></i> &nbsp;&nbsp; App Call Logs</h4>
                        <table class="table">
                            <tr>
                                <th>User</th>
                                <th>Mobile No.</th>
                                <th>Created At</th>
                            </tr>
                            @foreach($phone_logs as $pl)
                            <tr>
                                <td>{{ $pl->user->username }}</td>
                                <td><a href="tel:{{ $pl->mob_phone }}">{{ $pl->mob_phone }}</a></td>
                                <td>{{ date('dS Y M',strtotime($pl->created_at)) }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

</section>




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
                <button type="submit" class="btn bg-orange" name="submit">Send</button>
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

                $(".modal").on("hidden.bs.modal", function() {
                    //$(".modal-body1").html("");
                });
            });

        </script>
        {!! Form::close() !!}
    </div>
</div>
@endsection
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" />
@section('body_bottom')
<!-- Select2 js -->
@include('partials._body_bottom_select2_js_role_search')
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

<script>
    function HandlePopupResult(result) {
        let l = result.leads;

        let html = `<tr>
                <td>{{ env('APP_CODE')}} ${l.id}</td>                                  
                <td style="float:lef; font-size: 16px">
                <a href="/admin/leads/${l.id}?type={{\Request::get('type')}}">${l.name}</a>
                                      
                        </td>
                        <td></td>
                        <td>
                          <span class="btn btn-xs"></span>
                        </td>           
                      </tr>`;

        $('#leads-table').prepend(html);
    }

    function openmodal() {
        var win = window.open(`/admin/leads/create/modal?type={!! $_GET['type'] !!}`, '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=600, height=650');
    }
    const lead_reason_id = '{{$lead->reason_id ? $lead->reason_id : null}}';

    function handleLeadStage(lead_stage) {

        if (lead_stage == 5 || lead_stage == 6) {
            if (lead_stage == 5) {
                $('#closure_reason').val(lead_reason_id ? lead_reason_id : '11');
            }
            $('#closure_reason_div').css('display', 'block');
            $('#task_due_date_div').css('display', 'none');
        } else {
            $('#closure_reason_div').css('display', 'none');
            $('#task_due_date_div').css('display', 'block');
        }
    }
    $(function() {
            var lead_stage = '{{$lead->stage_id ? $lead->stage_id : null}}';
            handleLeadStage(lead_stage);
            $('#lead_stage_id').change(function() {
                let id = $(this).val();
                handleLeadStage(id);
            })
        })


        // To submit the note for the Lead - by Ajax 
        $(document).on('click', '#submit-note', function() {

            var token = $('meta[name="csrf-token"]').attr('content');
            var user_id = '{!!\Auth::user()->id!!}';
            var lead_id = '{!! $lead->id !!}';
            var task_assign_to = $("#task_assign_to").val();
            var note = $("#lead_note").val();
            var stage_id = $('#lead_stage_id').val();
            if (note != '') {
                $("#lead_note").removeClass("has-error");
                // Check if 'Add Note to Task' is checked or not
                if ($("#note_to_task").is(':checked')) {
                    $("#task_due_date").removeClass("has-error");

                    var task_due_date = $("#task_due_date").val();


                    if (stage_id == 5 || stage_id == 6) {
                        let c = confirm('Are you sure you want to make changes');
                        if (!c) {
                            return false;
                        }
                    } else {
                        if (task_due_date.trim() == '') {
                            $("#task_due_date").addClass("has-error");
                            return false;
                        }
                    }
                    let closure_reason = $('#closure_reason').val();
                    var datastring = '_token=' + token + '&user_id=' + user_id + '&lead_id=' + lead_id + '&note=' + note + '&task_due_date=' + task_due_date + '&task_assign_to=' + task_assign_to + '&stage_id=' + stage_id + '&closure_reason=' + closure_reason;
                    $.ajax({
                        url: '/admin/leadnotes'
                        , dataType: 'JSON'
                        , type: 'post'
                        , contentType: 'application/x-www-form-urlencoded'
                        , data: datastring
                        , success: function(data) {

                            if (stage_id == 4) //redirect to customer
                                location.href = `{!! url('/') !!}/admin/leads/${lead_id}?type=customer`;
                            else if (stage_id == 5 || stage_id == 6) //redirect to quotations
                                location.reload();
                            else
                                location.reload();
                            $("#lead_note").val('')
                            // document.getElementById('note-list').innerHTML = data.messages;
                        }
                        , error: function(jqXhr, textStatus, errorThrown) {
                            alert("Some Thing Went Wrong");
                            console.log(errorThrown);
                        }
                    });

                } else {
                    var datastring = '_token=' + token + '&user_id=' + user_id + '&lead_id=' + lead_id + '&note=' + note;
                    $.ajax({
                        url: '/admin/leadnotes'
                        , dataType: 'JSON'
                        , type: 'post'
                        , contentType: 'application/x-www-form-urlencoded'
                        , data: datastring
                        , success: function(data) {
                            document.getElementById('note-list').innerHTML = data.messages;
                        }
                        , error: function(jqXhr, textStatus, errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                }
            } else {
                $("#lead_note").addClass("has-error");
            }

        });


    $(document).on('click', '.delete-note', function() {

        var token = $('meta[name="csrf-token"]').attr('content');
        var note_id = $(this).attr('id').split('-')[1];

        var datastring = '_token=' + token + '&note_id=' + note_id;
        $.ajax({
            url: '/admin/leadnotes/' + note_id + '/confirm-delete'
            , dataType: 'JSON'
            , type: 'get'
            , contentType: 'application/x-www-form-urlencoded'
            , data: datastring
            , success: function(data) {
                document.getElementById('note-list').innerHTML = data.messages;
                $("#lead_note").val('');
            }
            , error: function(jqXhr, textStatus, errorThrown) {
                console.log(errorThrown);
                $("#lead_note").val('');
            }
        });
    });


    //To submit the file for the Lead - by Ajax 
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

    // $(document).on('change', '#status_id', function() {
    //    var id = $('#show_lead_id').val();
    //   var status_id = $(this).val();

    //   $.post("/admin/ajax_lead_status",
    //   {id: id, status_id: status_id, _token: $('meta[name="csrf-token"]').attr('content')},
    //   function(data, status){
    //     if(data.status == '1')
    //         $("#ajax_status").after("<span style='color:green;' id='status_update'>Status is successfully updated.</span>
    //");
    //     else
    //         $("#ajax_status").after("<span style='color:red;' id='status_update'>Problem in updating status; Please try again.</span>");

    //     $('#status_update').delay(3000).fadeOut('slow');
    //     //alert("Data: " + data + "\nStatus: " + status);
    //   });
    // });

    $(document).on('change', '#rating', function() {
        var id = $('#lead_id').val();
        var rating = $(this).val();

        $.post("/admin/ajax_lead_rating", {
                id: id
                , rating: rating
                , _token: $('meta[name="csrf-token"]').attr('content')
            }
            , function(data, status) {
                if (data.status == '1')
                    $("#ajax_status").after("<span style='color:green;' id='status_update'>Successfully updated.</span>");
                else
                    $("#ajax_status").after("<span style='color:red;' id='status_update'>Problem in updating ! Please try again.</span>");

                $('#status_update').delay(3000).fadeOut('slow');
                //alert("Data: " + data + "\nStatus: " + status);
            });
    });


    function makeChanges(type, value) {
        if (type == 'stages') {
            if (value == 3 || value == 4)
                $('.quotations-proposal').show();
            else
                $('.quotations-proposal').hide();
        }
    }

    function handleChange(lead_id, value, type, parent) {
        $.post("/admin/ajaxLeadUpdate", {
                id: lead_id
                , update_value: value
                , type: type
                , _token: $('meta[name="csrf-token"]').attr('content')
            }
            , function(data) {
                if (data.status == '1') {
                    makeChanges(type, value);
                    $("#ajax_status").after("<span style='color:green;' id='status_update'>" + type + " sucessfully updated</span>");
                    $('#status_update').delay(3000).fadeOut('slow');
                }

                //alert("Data: " + data + "\nStatus: " + status);
            });
    }

    var courses = @php echo json_encode($courses); @endphp;
    const active_lead_id = $('#lead_id').val();


    $('#courses_id').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = active_lead_id
        $(this).editable({
            source: courses
            , success: function(response, newValue) {
                handleChange(lead_id, newValue, 'courses', parent);
            }
        , });
    });
    $('#mob_phone').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = active_lead_id
        $(this).editable({
            success: function(response, newValue) {
                handleChange(lead_id, newValue, 'mob_phone', parent);
            }
        , });
    });

    $('#source_id').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = active_lead_id
        $('#source_id').editable({
            source: @php echo json_encode($sources) @endphp 
            , success: function(response, newValue) {
                handleChange(lead_id, newValue, 'sources', parent);
            }
        , });
    });

    $('#status_id').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = active_lead_id;
        $(this).editable({
            source: @php echo json_encode($lead_status) @endphp
            , success: function(response, newValue) {
                handleChange(lead_id, newValue, 'status', parent);
            }
        , });
    });

    $('#campaign_id').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = active_lead_id;
        $(this).editable({
            source: @php echo json_encode($campaigns) @endphp 
            , success: function(response, newValue) {
                handleChange(lead_id, newValue, 'campaign', parent);
            }
        , });
    });
    const lead_rating = @php echo json_encode($lead_rating); @endphp;
    $('#rating').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = active_lead_id;
        $(this).editable({
            source: lead_rating
            , success: function(response, newValue) {
                handleChange(lead_id, newValue, 'rating', parent);
            }
        , });
    });

    $('#email-edit').editable({
        toggle: 'manual'
        , success: function(response, newValue) {
            handleChange(active_lead_id, newValue, 'email', null);
        }
    , });

    $('#email-edit-button').click(function(e) {
        e.stopPropagation();
        $('#email-edit').editable('toggle');
    })

    $('#datepicker_follow_date').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = active_lead_id;
        $(this).datepicker({
            dateFormat: 'd M y'
            , sideBySide: true
            , onSelect: function(dateText) {
                handleChange(lead_id, dateText, 'target_date', parent);
            }
        });
    });

    $('#date_of_birth').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = active_lead_id;
        $(this).datepicker({
            dateFormat: 'd M y'
            , sideBySide: true
            , changeMonth: true
            , changeYear: true
            , yearRange: "-150:-0"
            , onSelect: function(dateText) {
                handleChange(lead_id, dateText, 'dob', parent);
            }
        });
    });


    $('#lead_description').editable({
        type: 'textarea'
        , placement: 'left'
        , title: 'Lead Description'
        , success: function(response, newValue) {
            handleChange(active_lead_id, newValue, 'description', null);
        }
    , });



    $(document).on('hidden.bs.modal', '#modal_dialog', function(e) {
        $('#modal_dialog .modal-content').html('');
    });


    function handleModalResults(result) {
        console.log(result.html);
        $('#task-list').prepend(result.html);
        $('#modal_dialog').modal('hide');
    }

</script>
@endsection
