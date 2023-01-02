  @extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')

<style>
.header { padding:10px 0; }
.col-md-4 { padding-right: 0; padding-left: 5px; }
.lists { padding-left: 10px; }

.lead > a {
    color: navy !important;
}

[data-letters]:before {
    content:attr(data-letters);
    display:inline-block;
    font-size:0.7em;
    width:2.1em;
    height:1.9em;
    line-height:1.8em;
    text-align:center;
    border-radius:90%;
    background:#5758BB;
    vertical-align:middle;
    margin-right:0.3em;
    color:white;
    }
.openlink:hover{
color: blue;
cursor: pointer;
}
.taskprogress-edit{
  color: transparent;
}
.progress:hover{
  cursor: pointer;
}

.progress.xs, .progress-xs {
    height: 20px !important;
}
.addtasks{
  z-index: 1 !important;
}
.taskstatus {
  text-transform: capitalize;
  overflow: hidden;
}
.blink_me {
  animation: blinker 1s infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>

<section class="content-header" style="margin-top: -50px; margin-bottom: 20px">

            <div class="col-lg-12">
                        <h1 class="content-max-width"> {!! $project->name !!} 
                        <small>{!! $project->tagline !!} </small>
                        <small id="ajax_status"> </small>
                        </h1>
                        <a href="{{ route('admin.projects.group',\Request::segment(3)) }}">Task Group +</a> | 
               {{ TaskHelper::topSubMenu('topsubmenu.projects')}}
                    </div>   

                       <span style="margin-top: -50px; margin-bottom: 10px" class="pull-right">
                <form method="GET" action="/admin/projects/search/tasks/">                          
                 <div class="input-group input-group-sm hidden-xs" style="width: 200px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="{{ trans('admin/projects/general.placeholder.search_project') }}">
                  
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                  </div>
                </div>
                </form>

                

   </span> 
        </section>



        <div class='row'>
        <div class='col-md-12'>

            <!-- Box -->
            {!! Form::open( array('route' => 'admin.projects.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">

                      

                      <div class="btn-group">

                      <a class="btn btn-primary btn-sm" href="#" onClick="openwindow({{\Request::segment(3)}})" title="{{ trans('admin/projects/general.button.create') }}">
                          <i class="fa fa-edit"></i> {{ trans('admin/projects/general.button.quick_task') }}
                      </a>

                      @if(\Auth::user()->hasRole('admins'))
                        <a class="btn btn-default btn-sm" href="{!! route('admin.projects.edit', $project->id) !!}" title="{{ trans('admin/projects/general.button.edit') }}">
                            <i class="fa fa-edit"></i> {{ trans('admin/projects/general.button.edit_project') }}
                        </a>

                         <a class="btn btn-primary btn-sm" href="/admin/projectsgroups/{{ $project->id }}" >
                          <i class="fa fa-plus"></i> Add Groups  
                      </a>
                      @endif

                      <a class="btn btn-primary btn-sm" href="/admin/task/create/global?pid={{\Request::segment(3)}}" title="">
                            <i class="fa fa-plus"></i> {{ trans('admin/projects/general.button.create_task') }}
                        </a>

                         <?php 

                          if(\Auth::user()->hasRole('admins'))
                            {
                                 $backlogs = App\Models\ProjectTask::where('project_id', \Request::segment('3'))->where('end_date','<=',\Carbon\Carbon::today())
                                 ->where('status','!=','completed')
                                 
                                 ->orderBy('id','desc')->count();  
                            }
                            else{

                                $backlogs = App\Models\ProjectTask::where('project_id', \Request::segment('3'))->where('end_date','<=',\Carbon\Carbon::today())->where('status','!=','completed')->where('user_id', \Auth::user()->id)->orderBy('id','desc')->count();

                            }


                         ?>

                      <a class="btn btn-info btn-sm" href="/admin/backlogs/tasks/{{ $project->id }}">
                               Backlogs  @if($backlogs > 0) <span class="blink_me label label-danger" style="color: red;"><strong>{{$backlogs}}</strong></span>@endif
                         </a>
                        <a class="btn btn-default btn-sm" href="#" 
                        onclick="window.open('/admin/project_task_activities/{{\Request::segment(3)}}', '_blank','toolbar=yes, scrollbars=yes, resizable=yes, top=0,left=100,width=999, height=660');">
                             <i class="fa fa-clock"></i>   {{ trans('admin/projects/general.button.activity') }}
                         </a> 
                      <span class="col-md-3 pull-left">
                        {!! Form::select('project_id', [''=>'Change Project'] + $projects, null, ['class' => 'form-control input-sm','id' => 'proj_id']) !!}
                      </span>
                        {!! Form::close() !!}
                </div>
              
            <span class="pull-right">
                      
                      <form method="get" action="{{route('admin.project.filter')}}" id='filter-form'><br>

                        {!! Form::select('location', ['' =>'Select Location','common-area'=>'Common Area','apartment'=>'Apartment','office'=>'Office'], null, ['class' => 'label-primary','id'=>'location']) !!}
                        <input required="" class="btn-xs datepicker" type="text"   name="start_date" id="start_date" placeholder="Start Date"  style="margin-left: 10px">   
                        <input required="" class="btn-xs datepicker" type="text"   name="end_date" id="end_date" placeholder="End Date" value="{{date('Y-m-d')}}"  style="margin-left: 10px"> 
                        <input type="hidden" name="pid" value="{{ $project->id }}">
                        <input type="submit" name="download_pdf" class="btn btn-primary btn-xs no-loading" value="PDF">
                        <input type="submit" name="download_lite" class="btn btn-primary btn-xs no-loading" value="Lite">
                        <button type="submit" onClick='return downloadWord()' class="btn btn-success btn-xs no-loading">Word</button>
                      </form>
                  </span>
                

                    </div>
                    

                    
                </div><!-- /.box -->
            
        </div><!-- /.col -->

    </div><!-- /.row -->
                                 
        
  
                        

<div class="row">

<div class="col-md-12">
<?php
$priority_label = ['Low'=>'info','Medium'=>'primary','High'=>'danger','None'=>'default','Urgent'=>'warning'];
?>
<div class="box">
            <div class="box-header">
              <h3  class="box-title task-title" data-id='new'>
          {{ $new_ongoing_completed['new']?ucwords($new_ongoing_completed['new']->description):'New Tasks' }}
            </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
       <table class="table table-striped"  style="width: 100 !important">
       
          <thead style="background-color: #f4f4f4">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>{{ trans('admin/projects/general.columns.task') }}</th>
                  <th>{{ trans('admin/projects/general.columns.due') }}</th>
                  <th>{{ trans('admin/projects/general.columns.priority') }}</th>
                  <th>{{ trans('admin/projects/general.columns.progress') }}</th>
                  <th style="width: 40px">{{ trans('admin/projects/general.columns.owner') }}</th>
                   <th style="width: 40px">{{ trans('admin/projects/general.columns.stage') }}</th>
                  <th style="width: 40px">{{ trans('admin/projects/general.columns.status') }}</th>
                </tr>
      </thead>
         <tbody  id="new" class="SortMe" style="font-size: 16px !important">  
            @if(isset($new_tasks) && !empty($new_tasks))
            @foreach($new_tasks as $tk => $task)
            @if($task->timespan == null)
                <tr id = "{{$task->id}}" draggable="true" class="swapable old">
                  <td><span class="taskid">{!! $task->id !!}</span>.</td>
                  <td><span class="openlink" id="{{$task->id}}" >{!! mb_substr($task->subject,0,300) !!}</span></td>
                  <td>
                    <input class="datepicker_end_date" style="width: 81px;border:none;" 
                    value="{{date('d M y',strtotime($task->end_date))}}">
                  </td>
                  <td>
                    <span class="label label-{{$priority_label[$task->priority]}} taskpriority" data-type="select" data-pk="1" data-title="Select status" data-value="{{$task->priority}}">
                    </span>
                  </td>
                  <td>
                    <div class="progress progress-xs progress-striped active  taskprogress">
                      <div class="progress-bar progress-bar-primary taskprogress-edit" style="width: {!! $task->percent_complete !!}%;color: transparent;">{{$task->percent_complete}}</div>
                    </div>
                  </td>
                  <td><span>
                     @if($task->user->image)
              <img class="profile-user-img img-responsive img-circle" src="/images/profiles/{{$task->user->image}}" alt="{{ $ov->user->first_name}}" style="margin: 0 auto;width: 25px; border: 1px solid #d2d6de;">
              @else
              <span data-letters="{{ mb_substr($task->user->first_name,0,1).' '. mb_substr($task->user->last_name,0,1) }}"></span>
              @endif
                    <!-- <a href="/admin/profile/show/{{ $task->user_id }}">{{ $task->user->first_name}}</a> -->
                  </span></td>
                    <td>
                         <span class="label {{$task->stage->bg_color}} taskstage" data-type="select" data-pk="1" data-title="Select stages" data-value="{{$task->stage_id}}"></span>
                    </td>
                <td>
                 <span class="taskstatus"  data-type="select" data-title="Select Status" data-value="{{$task->status}}"></span>
               </td>
                </tr>
                @elseif($task->timespan != null)
                @if($task->timespan <= $timespan)

                <tr id = "{{$task->id}}" draggable="true" class="swapable old">
                  <td><span class="taskid">{!! $task->id !!}</span>.</td>
                  <td><span class="openlink" id="{{$task->id}}">{!! mb_substr($task->subject,0,300) !!}</span></td>
                  <td>
                    <input class="datepicker_end_date" style="width: 60px;border:none;" 
                    value="{{date('d M y',strtotime($task->end_date))}}">
                  </td>
                  <td> <span class="label label-{{$priority_label[$task->priority]}} taskpriority" data-type="select" data-pk="1" data-title="Select status" data-value="{{$task->priority}}">{!! $task->priority !!}
                  </span></td>
                  <td>
                    <div class="progress progress-xs progress-striped active taskprogress">
                      <div class="progress-bar progress-bar-primary taskprogress-edit" style="width: {!! $task->percent_complete !!}%">{{$task->percent_complete}}</div>
                    </div>
                  </td>
                  <td><span >
                     @if($task->user->image)
              <img class="profile-user-img img-responsive img-circle" src="/images/profiles/{{$task->user->image}}" alt="{{ $task->user->first_name}}" style="margin: 0 auto;width: 25px; border: 1px solid #d2d6de;">
              @else
              <span data-letters="{{ mb_substr($task->user->first_name,0,1).' '.mb_substr($task->user->last_name,0,1) }}"></span>
              @endif
                    <!-- <a href="/admin/profile/show/{{ $task->user_id }}">{{ $task->user->first_name}}</a> -->
                  </span></td>
                  <td>
                 <span class="label {{$task->stage->bg_color}} taskstage" data-type="select" data-pk="1" data-title="Select stages" data-value="{{$task->stage_id}}"></span>
               </td>
                <td>
                 <span class="taskstatus"  data-type="select" data-title="Select Status" data-value="{{$task->status}}"></span>
               </td>
                </tr>

                @endif
                @endif

                @endforeach
                @endif
                 <!--  forajdustment -->
                <tr><td colspan="3">
                     <small id="ajax_new_task"></small> 
                </td>
                <td></td><td></td><td></td><td></td><td></td></tr>
              
              </tbody>
            </table>
             <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-plus"></i></span>
                <input  type="text" class="form-control addtasks" data-status='new' placeholder="Enter New Task Subjects">
                </div>
            </div>
            <!-- /.box-body -->

          </div>



</div>


<div class="col-md-12">

<div class="box">
            <div  class="box-header">
              <h3 class="box-title task-title" data-id='ongoing'>
          {{ $new_ongoing_completed['ongoing']?ucwords($new_ongoing_completed['ongoing']->description):'Ongoing Tasks' }}
              </h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding table-responsive">
      <table class="table  table-striped"  style="width: 100 !important">
       
          <thead style="background-color: #f4f4f4">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>{{ trans('admin/projects/general.columns.task') }}</th>
                  <th>{{ trans('admin/projects/general.columns.due') }}</th>
                  <th>{{ trans('admin/projects/general.columns.priority') }}</th>
                  <th>{{ trans('admin/projects/general.columns.progress') }}</th>
                  <th style="width: 40px">{{ trans('admin/projects/general.columns.owner') }}</th>
                   <th style="width: 40px">{{ trans('admin/projects/general.columns.stage') }}</th>
                  <th style="width: 40px">{{ trans('admin/projects/general.columns.status') }}</th>
                </tr>
      </thead>
               <tbody id="ongoing" class="SortMe" style="font-size: 16px !important">  
           @if(isset($ongoing_tasks) && !empty($ongoing_tasks))

            @foreach($ongoing_tasks as $ok => $ov)
                <tr id = "{{$ov->id}}" class="old" >
                  <td><span class="taskid">{!! $ov->id !!}</span>.</td>
                  <td><span class="openlink" id="{{$ov->id}}" >{!! $ov->subject !!}</span></td>
                   <td>
                       <input class="datepicker_end_date" style="width: 81px;border:none;" 
                    value="{{date('d M y',strtotime($ov->end_date))}}">                  
                  </td>
                    <td><span class="label label-{{$priority_label[$ov->priority]}} taskpriority" data-type="select" data-pk="1" data-title="Select status" data-value="{{$ov->priority}}">
                      {!! $ov->priority !!}</span></td>
                  <td>
                    <div class="progress progress-xs progress-striped active taskprogress">
                      <div class="progress-bar progress-bar-primary taskprogress-edit" style="width: {!! $ov->percent_complete !!}%">{{$ov->percent_complete}}</div>
                    </div>
                  </td>
                  <td>
                    <span >
                     
                      @if($ov->user->image)
              <img class="profile-user-img img-responsive img-circle" src="/images/profiles/{{$ov->user->image}}" alt="{{ $ov->user->first_name}}" style="margin: 0 auto;width: 25px; border: 1px solid #d2d6de;">
              @else
              <span data-letters="{{ mb_substr($ov->user->first_name,0,1).' '.mb_substr($task->user->last_name,0,1) }}">
                @endif
              </span>
                      <!-- <a href="/admin/profile/show/{{ $ov->user_id }}">{{ $ov->user->first_name}}</a> -->
                    </span>
                  </td>
                  <td>
                   <span class="label {{$ov->stage->bg_color}} taskstage" data-type="select" data-pk="1" data-title="Select stages" data-value="{{$ov->stage_id}}"></span>
                </td>
                 <td>
                 <span class="taskstatus"  data-type="select" data-title="Select Status" data-value="{{$ov->status}}"></span>
               </td>
                </tr>
                
                @endforeach
                  <tr><td colspan="3">
                     <small id="ajax_ongoing_task"></small> 
                </td>
                <td></td><td></td><td></td><td></td><td></td></tr>
                @endif
              </tbody></table>
               <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-plus"></i></span>
                <input  type="text" class="form-control addtasks" data-status='ongoing' placeholder="Enter  Task Subjects">
                </div>
            </div>

            <!-- /.box-body -->
          </div>


</div>



{{-- other project tasks from added groups --}}
@foreach($other_project_tasks as $key=>$value)




<div class="col-md-12">

<div class="box">
            <div  class="box-header">
              <h3 class="box-title task-title" data-id='{{ $key }}'>
                {{ $value }}
              </h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding table-responsive">
      <table class="table  table-striped"  style="width: 100 !important">
       
          <thead style="background-color: #f4f4f4">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>{{ trans('admin/projects/general.columns.task') }}</th>
                  <th>{{ trans('admin/projects/general.columns.due') }}</th>
                  <th>{{ trans('admin/projects/general.columns.priority') }}</th>
                  <th>{{ trans('admin/projects/general.columns.progress') }}</th>
                  <th style="width: 40px">{{ trans('admin/projects/general.columns.owner') }}</th>
                   <th style="width: 40px">{{ trans('admin/projects/general.columns.stage') }}</th>
                  <th style="width: 40px">{{ trans('admin/projects/general.columns.status') }}</th>
                </tr>
      </thead>
               <tbody id="{{ $key }}" class="SortMe" style="font-size: 16px !important">  
            @foreach($other_project_tasks_data[$key] as $ok => $ov)
                <tr id = "{{$ov->id}}" class="old" >
                  <td><span class="taskid">{!! $ov->id !!}</span>.</td>
                  <td><span class="openlink" id="{{$ov->id}}" >{!! $ov->subject !!}</span></td>
                   <td>
                       <input class="datepicker_end_date" style="width: 81px;border:none;" 
                    value="{{date('d M y',strtotime($ov->end_date))}}">                  
                  </td>
                    <td><span class="label label-{{$priority_label[$ov->priority]}} taskpriority" data-type="select" data-pk="1" data-title="Select status" data-value="{{$ov->priority}}">
                      {!! $ov->priority !!}</span></td>
                  <td>
                    <div class="progress progress-xs progress-striped active taskprogress">
                      <div class="progress-bar progress-bar-primary taskprogress-edit" style="width: {!! $ov->percent_complete !!}%">{{$ov->percent_complete}}</div>
                    </div>
                  </td>
                  <td>
                    <span >
                     
                      @if($ov->user->image)
              <img class="profile-user-img img-responsive img-circle" src="/images/profiles/{{$ov->user->image}}" alt="{{ $ov->user->first_name}}" style="margin: 0 auto;width: 25px; border: 1px solid #d2d6de;">
              @else
              <span data-letters="{{ mb_substr($ov->user->first_name,0,1).' '.mb_substr($task->user->last_name,0,1) }}">
                @endif
              </span>
                      <!-- <a href="/admin/profile/show/{{ $ov->user_id }}">{{ $ov->user->first_name}}</a> -->
                    </span>
                  </td>
                  <td>
                   <span class="label {{$ov->stage->bg_color}} taskstage" data-type="select" data-pk="1" data-title="Select stages" data-value="{{$ov->stage_id}}"></span>
                </td>
                   <td>
                 <span class="taskstatus"  data-type="select" data-title="Select Status" data-value="{{$ov->status}}"></span>
               </td>
                </tr>
                
                @endforeach
                  <tr><td colspan="3">
                    {{--  <small id="ajax_ongoing_task"></small>  --}}
                </td>
                <td></td><td></td><td></td><td></td><td></td></tr>
              </tbody></table>
            </div>

            <!-- /.box-body -->
          </div>


</div>
@endforeach
</div>


<hr/>

<div class="row">

 {{--    <div class="col-md-12">
        <h4 class="callout callout-info"> &nbsp; <i class="fa fa-file"></i> Project Files</h4>
     
    </div> --}}


 <div class="box-footer">
    <ul class="mailbox-attachments clearfix">
       @foreach($project_files as $pf)
                @if(is_array(getimagesize(public_path().'/task_attachments/'.$pf->attachment)))
                      <li>
                  <span class="mailbox-attachment-icon has-img"><img src="{{'/task_attachments/'.$pf->attachment }}" alt="TSk.#{{ $pf->id }}" style="height: 120px"></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> {{ substr($pf->attachment,-14) }}</a>
                        <span class="mailbox-attachment-size">
                          {{ round(filesize(public_path().'/task_attachments/'.$pf->attachment) * 0.000001,2) }} MB
                          <a href="{{'/task_attachments/'.$pf->attachment }}" class="btn btn-default btn-xs pull-right" target="_blank"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
            @else
                <li>
                  <span class="mailbox-attachment-icon" style="height: 120px"><i class="fa fa-file-pdf-o"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="#{{ $pf->id }}" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> {{ substr($pf->attachment,-20) }}</a>
                        <span class="mailbox-attachment-size">
                          <?php 
                          $filesize =filesize(public_path().'/task_attachments/'.$pf->attachment)
                          ?>
                          @if(($filesize * 0.000001) > 0.1))
                              {{$filesize * 0.000001}} MB 
                          @else

                          {{ round(filesize(public_path().'/task_attachments/'.$pf->attachment)/1024)  }} KB
                          @endif
                          <a href="/task_attachments/{{ $pf->attachment }}" class="btn btn-default btn-xs pull-right" target="_blank"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
         @endif
          @endforeach 
          @foreach($projectTaskAttachment as $pf)
                @if(is_array(getimagesize(public_path().'/task_attachments/'.$pf->attachment)))
                      <li>
                  <span class="mailbox-attachment-icon has-img"><img src="{{'/task_attachments/'.$pf->attachment }}" alt="TSk.#{{ $pf->task_id }}" style="height: 120px"></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> {{ substr($pf->attachment,-14) }}</a>
                        <span class="mailbox-attachment-size">
                          {{ round(filesize(public_path().'/task_attachments/'.$pf->attachment) * 0.000001,2) }} MB
                          <a href="{{'/task_attachments/'.$pf->attachment }}" class="btn btn-default btn-xs pull-right" target="_blank"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
            @else
                <li>
                  <span class="mailbox-attachment-icon" style="height: 120px"><i class="fa fa-file-pdf-o"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="#{{ $pf->id }}" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> {{ substr($pf->attachment,-20) }}</a>
                        <span class="mailbox-attachment-size">
                          <?php 
                          $filesize =filesize(public_path().'/task_attachments/'.$pf->attachment)
                          ?>
                          @if(($filesize * 0.000001) > 0.1))
                              {{$filesize * 0.000001}} MB 
                          @else

                          {{ round(filesize(public_path().'/task_attachments/'.$pf->attachment)/1024)  }} KB
                          @endif
                          <a href="/task_attachments/{{ $pf->attachment }}" class="btn btn-default btn-xs pull-right" target="_blank"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
         @endif
          @endforeach 
           @foreach($comments_files as $pf)
                @if(is_array(getimagesize(public_path().'/files/'.$pf->attachment)))
                      <li>
                  <span class="mailbox-attachment-icon has-img"><img src="{{'/files/'.$pf->attachment }}" alt="TSk.#{{ $pf->id }}" style="height: 120px"></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> {{ substr($pf->attachment,-14) }}</a>
                        <span class="mailbox-attachment-size">
                          {{ round(filesize(public_path().'/files/'.$pf->attachment) * 0.000001,2) }} MB
                          <a href="{{'/files/'.$pf->attachment }}" class="btn btn-default btn-xs pull-right" target="_blank"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
            @else
                <li>
                  <span class="mailbox-attachment-icon" style="height: 120px"><i class="fa fa-file-pdf-o"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="#{{ $pf->id }}" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> {{ substr($pf->attachment,-20) }}</a>
                        <span class="mailbox-attachment-size">
                          <?php 
                          $filesize =filesize(public_path().'/files/'.$pf->attachment)
                          ?>
                          @if(($filesize * 0.000001) > 0.1))
                              {{$filesize * 0.000001}} MB 
                          @else

                          {{ round(filesize(public_path().'/files/'.$pf->attachment)/1024)  }} KB
                          @endif
                          <a href="/files/{{ $pf->attachment }}" class="btn btn-default btn-xs pull-right" target="_blank"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
         @endif
          @endforeach  
              </ul>
            </div>

</div>

 
<script>
$(function() {

 
});


    $('[name="project_id"]').change(function(){
    var optionSelected = $("option:selected", this);
    optionValue = this.value;
    document.location.href="/admin/projects" + "/" + optionValue;
});

</script>

  
@endsection

@section('body_bottom')
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

   <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<link href="/x-editable/bootstrap-editable.css" rel="stylesheet" />
<script src="/x-editable/bootstrap-editable.min.js"></script>
<script type="text/javascript">

const project_id = '{{ \Request::segment(3) }}';
const project_status = <?php echo json_encode($project_status); ?>;
const project_stages = <?php echo json_encode($stages); ?>;

function updateTask(status, id) {
    $.post('/admin/ajax_proj_task_status', {
            id: id,
            status: status,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        function(data, status) {
            if (data.status == 1) {

            }
        });
}

function orderTask(orders) {
    // console.log(orders,id);
    $.post('/admin/ajax_proj_task_order', {
            task_order: orders,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        function(data, status) {
            if (data.status == 1) {

            }
        });
}


function HandlePeopleChanges(prams, task_ids, isChanged) { // this function is called from another window
    if (prams) {
        console.log(prams);
        $.post("/admin/ajaxTaskPeopleUpdate", {
                id: task_ids,
                peoples: prams,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            function(data) {
                console.log(data);
                //alert("Data: " + data + "\nStatus: " + status);
            });
    }
    if (isChanged) {
        location.reload();
    }

}

function UpdateChanges(isChanged) {
    if (isChanged) {
        location.reload();
    }
}

$(document).ready(function() {
    var target = "";
    var colors = {
        new: "#8BC34A",
        ongoing: '#FFEB3B',
        completed: "#FF4081"
    }
    $('.SortMe').sortable({
        disabled: false,
        axis: 'xy',
        forceHelperSize: true,
        start: function(event, ui) {
            target = event.target.id;
            ui.item.css('background-color', colors[target]);
        },
        connectWith: ".SortMe",
        update: function(event, ui) {
            var Newpos = ui.item.index() + 1;
            var id = ui.item.attr("id");
            var newtarget = event.target.id;
            if (newtarget != target) {
                if (project_status.includes(newtarget) && id != '') {
                    updateTask(newtarget, id);
                    orderTask([{pos:Newpos + 1, id: id}]);
                    $(`#${id} .taskstatus`).editable('setValue',newtarget);
                }
            }
            ui.item.css('background-color', 'white');
        },
        stop: function(event, ui) {
            var i = event.target.rows.length;
            var order =[];
            $(event.target.rows).each(function(e) {
                if (this.id) {
                  order.push({pos:e+1,id:this.id});
                    // x(e + 1, this.id);
                }
            });
            ui.item.css('background-color', 'white');
            orderTask(order);
        }

    }).disableSelection();
    $(function() {
        $('.swapable').resizable();
    });
});

function openwindow(id) {
    var win = window.open(`/admin/project_tasks/create/modals/${id}`, '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=400, height=560');

}

function HandlePopupResult(result) {
    if (result) {
        let tasks = result.task;
        var newtaskid = result.id;
        setTimeout(function() {
            $('#new').prepend(tasks);
            $("#ajax_status").after("<span style='color:green;' id='status_update'>Task sucessfully created</span>");
            $('#status_update').delay(3000).fadeOut('slow');
            jQueryTaskStuff(`.new${newtaskid}`);
        }, 500);
    } else {
        $("#ajax_status").after("<span style='color:red;' id='status_update'>failed to create Task</span>");
        $('#status_update').delay(3000).fadeOut('slow');
    }
}

$('.datepicker').datetimepicker({
    //inline: true,
    format: 'YYYY-MM-DD',
    sideBySide: true
});
//task updates
const priority_color = {
    'Low': 'info',
    'Medium': 'primary',
    'High': 'danger',
    'None': 'default',
    'Urgent': 'warning'
};

function makechanges(value, type, parent) {
    if (type == 'percent_complete') {
        parent.find('.taskprogress-edit').css("width", value.percent_complete + '%');
    } else if (type == 'priority') {
        let parent_el = parent.find('.taskpriority');
        var classes = (parent_el.attr("class").split(/\s+/));
        var newclass = 'label-' + priority_color[parent_el.text()];
        for (let i = 0; i < classes.length; i++) {
            if (classes[i].startsWith('label-')) {
                parent_el.toggleClass(classes[i] + ' ' + newclass);
                return 0;
            }
        }
        parent_el.toggleClass(newclass);
    } else if (type == 'stages') {
        let parent_el = parent.find('.taskstage');
        var classes = (parent_el.attr("class").split(/\s+/));
        var newclass = value.stages_color;
        for (let i = 0; i < classes.length; i++) {
            if (classes[i].startsWith('bg-')) {
                parent_el.toggleClass(classes[i] + ' ' + newclass);
                return 0;
            }
        }
        parent_el.toggleClass(newclass);
    } else if (type == 'status') {
        let trstatus = value.status;
        console.log(trstatus);
        let id = parent.find('.taskid').text();
        parent.find('.taskstatus').attr('data-value', trstatus);
        let html = `<tr id="${id}" draggable="true" class="swapable new${id}">${parent.html()}</tr>`;
        $(`.table #${trstatus}`).append(html);
        parent.remove();
        jQueryTaskStuff(`.new${id}`);
    }
}

function handleChange(task_id, value, type, parent) {
    $.post("/admin/ajaxTaskUpdate", {
            id: task_id,
            update_value: value,
            type: type,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        function(data) {
            if (data.status == '1') {
                makechanges(data.data, type, parent);
                $("#ajax_status").after("<span style='color:green;' id='status_update'>" + type + " sucessfully updated</span>");
                $('#status_update').delay(3000).fadeOut('slow');
            }
        });
}


function jQueryTaskStuff(type) {
    $(`${type} .taskprogress-edit`).each(function() {
        let p = $(this).parent().parent().parent();
        let task_id = p.find('.taskid').text();
        $(this).editable({
            toggle: 'manual',
            success: function(response, newValue) {
                handleChange(task_id, newValue, 'percent_complete', p);
            },
            validate: function(value) {
                if ($.isNumeric(value) == '') {
                    return 'Only Numberical value is allowed';
                } else if (Number(value) > 100 || Number(value) < 0) {
                    return 'Task Progress Can Be Between 0 to 100';
                }
            }
        });
    });

    $(`${type} .taskprogress`).each(function(event) {
        var parent = $(this).parent().parent();
        var editable = parent.find('.taskprogress-edit');
        $(this).click(function(e) {
            e.stopPropagation();
            $(editable).editable('toggle');
        });
    });
    $(`${type} .taskpriority`).each(function() {
        let parent = $(this).parent().parent();
        let task_id = parent.find('.taskid').text();
        $(this).editable({
            source: [{
                    value: 'Low',
                    text: 'Low'
                },
                {
                    value: 'Medium',
                    text: 'Medium'
                },
                {
                    value: 'High',
                    text: 'High'
                },
                {
                    value: 'Urgent',
                    text: 'Urgent'
                },
                {
                    value: 'None',
                    text: 'None'
                }
            ],
            success: function(response, newValue) {
                handleChange(task_id, newValue, 'priority', parent);
            },
        });
    })
    $(`${type} .taskstage`).each(function() {
        let parent = $(this).parent().parent();
        let task_id = parent.find('.taskid').text();
        $(this).editable({
            source: project_stages,
            success: function(response, newValue) {
                handleChange(task_id, newValue, 'stages', parent);
            },
        });
    })

    $(`${type} .datepicker_end_date`).each(function() {
        let parent = $(this).parent().parent();
        let task_id = parent.find('.taskid').text();
        $(this).datepicker({
            dateFormat: 'd M y',
            sideBySide: true,
            onSelect: function(dateText) {
                handleChange(task_id, dateText, 'end_date', parent);
            }
        });
    });
    $(`${type} .openlink`).click(function() {
        let id = this.id;
        //window.open('/admin/project_task/'+id);
        window.open('/admin/project_task/' + id, '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=0,left=100,width=999, height=660');
    });
    $(`${type} .taskstatus`).each(function() {
        let parent = $(this).parent().parent();
        let task_id = parent.find('.taskid').text();
        $(this).editable({
            source: project_status,
            success: function(response, newValue) {
                handleChange(task_id, newValue, 'status', parent);
            },
        });
    });
}

jQueryTaskStuff('.old');

$('.addtasks').keyup(function(e) {
    if (e.keyCode == 13) {
        var subject = $(this);
        if (subject.val().trim() != '') {
            var paramObj = {};
            var tasktype = subject.attr("data-status");
            paramObj['project_id'] = project_id;
            paramObj['subject'] = subject.val().trim();
            paramObj['status'] = tasktype;
            var tomorrow = new Date();
            tomorrow.setDate(new Date().getDate() + 1);
            paramObj['end_date'] = tomorrow.toJSON().slice(0, 10);
            paramObj['_token'] = $('meta[name="csrf-token"]').attr('content')
            $(subject).prop('disabled', true);
            $.post("/admin/project_tasks/store/modals", paramObj, function(result) {
                if (result) {
                    let tasks = result.task;
                    var newtaskid = result.id;
                    setTimeout(function() {
                        $(`#${tasktype}`).append(tasks);
                        $(`#ajax_${tasktype}_task`).after("<span style='color:green;' id='status_update'>Task sucessfully created</span>");
                        $('#status_update').delay(3000).fadeOut('slow');
                        jQueryTaskStuff(`.new${newtaskid}`);
                        subject.val('');
                    }, 500);
                }
                $(subject).prop('disabled', false);
            });
        }
    }
});

//changing task title
$.fn.editable.defaults.params = function(params) {
    params._token = $("meta[name=token]").attr("content");
    return params;
}
$('.task-title').each(function() {
    var name = $(this).attr('data-id');
    $(this).editable({
        type: 'text',
        pk: project_id,
        name: name,
        url: '/admin/ajaxTaskDescription'
    })
});



//donwload words
 function Export2Doc(element, filename = '') {
    let headclose = '<'+'/head>';
      var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' "+
            "xmlns:w='urn:schemas-microsoft-com:office:word' "+
            "xmlns='http://www.w3.org/TR/REC-html40'>"+
            "<head><meta charset='utf-8'><title>Export HTML to Word Document with JavaScript</title>"+ headclose +"<body>";

       var footer = "</body></html>";
       var sourceHTML = header+element+footer;

       var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
       var fileDownload = document.createElement("a");
       document.body.appendChild(fileDownload);
       fileDownload.href = source;
       fileDownload.download = filename+'.doc';
       fileDownload.click();
       document.body.removeChild(fileDownload);
}

function downloadWord(){
  var start_date = $('#filter-form #start_date');
  var end_date = $('#filter-form #end_date');
  if(start_date.val().trim() == ''){
    start_date.focus();
    return false;
  }
  if(end_date.val().trim() == ''){
    end_date.focus();
    return false;
  }
  let start = start_date.val();
  let end= end_date.val();
  let pid = $('#filter-form input[name=pid]').val();
  $.get(`/admin/projects/filter/monthly?pid=${pid}&start_date=${start}&end_date=${end}`,function(response){
      let html = response.html;
      let date = new Date();
      Export2Doc(html,`Report_salebook_filtered_${date.toDateString()}`);
  });
  return false;
}
</script>
@endsection
