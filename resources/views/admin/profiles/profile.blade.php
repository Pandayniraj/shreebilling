@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{$user->first_name}} {{$user->last_name}}
                <small>            {{ PayrollHelper::getDepartment($user->departments_id) }}, 
            {{ PayrollHelper::getDesignation($user->designations_id) }}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<section class="content">

      <div class="row">
        <div class="col-md-4">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">


              @if($user->image)
              <img class="profile-user-img img-responsive img-circle" src="/images/profiles/{{$user->image}}" alt="User profile picture" style="margin: 0 auto;width: 100px; border: 3px solid #d2d6de;">
              @else
              <img class="profile-user-img img-responsive img-circle" src="{{ Gravatar::get(Auth::user()->email , 'tiny') }}}" alt="User profile picture" style="margin: 0 auto;width: 100px;">
              @endif


              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Name:</b> <a class="pull-right">{{$user->first_name}} {{$user->last_name}}</a>
                </li>
              
                <li class="list-group-item">
                  <b>Gender</b> <a class="pull-right">{{$user_detail->gender}}</a>
                </li>
                <li class="list-group-item">
                  <b>Birthday:</b> <a class="pull-right"> {{ date('F d',strtotime($user->dob)) }}   </a>
                </li>
                <li class="list-group-item">
                  <b>Joined Since:</b> <a class="pull-right">
             
                      @if(strtotime($user_detail->join_date))
                      @php 
                      $today = new DateTime( date('Y-m-d') );

                      $join_date = new DateTime($user_detail->join_date);

                      $interval = $join_date->diff($today);

                      echo $interval->format('%y years %m months');

                      @endphp
                      @endif



                  </a>
                </li>
                 <li class="list-group-item">
                  <b>Organization:</b> <a class="pull-right">{{ $user->organization->organization_name }}</a>
                </li>
                 <li class="list-group-item">
                  <b>Department</b> <a class="pull-right">{{$user->department->deptname??''}}</a>
                </li>
                <li class="list-group-item">
                  <b>Designation</b> <a class="pull-right">{{$user->designation->designations??''}}</a>
                </li>
                 <li class="list-group-item">
                  <b>Level</b> <a class="pull-right">{{$user->designation->designations??''}}</a>
                </li>
                <li class="list-group-item">
                  <b>Branch</b> <a class="pull-right">{{$user->work_station}}</a>
                </li>
                @php  $supervisor = $user->firstLineManger @endphp
                <li class="list-group-item">
                  <b>Supervisor</b> <a class="pull-right">{{$supervisor->first_name ?? ''}} {{$supervisor->last_name ?? ''}}</a>
                </li>
                 <li class="list-group-item">
                  <b>Primary Email</b> <a class="pull-right" title="{{$user->email}}">{{ mb_substr($user->email,0,20) }}
                    @if(strlen($user->email) >20) ..@endif
                  </a>
                </li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border bg-info">
              <h3 class="box-title">About Me</h3>   
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Education</strong>

              <p class="text-muted">
                {{$user_detail->education}}
              </p>
              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
              <p class="text-muted">{{$user_detail->present_address}}</p>
              <hr>

              <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>

               <?php 
                   $skills = explode(',', $user_detail->skills);     
                ?>
              <p>
               @foreach($skills as $key => $value)
                <span  @if($key % 2 == 0) class="label label-info" @elseif($key % 3 == 0) class="label label-success" @else class="label label-danger" @endif>{{$value}} </span>&nbsp;
                @endforeach
              </p>

              <hr>

              <strong><i class="fa fa-file-text-o margin-r-5"></i> Bank Info</strong>

              <h6><strong>Name:</strong> {{$user_detail->bank_name}}</h6>
              <h6> <strong>Account Name:</strong>  {{$user_detail->bank_account_name}}</h6>
              <h6><strong>Account No:</strong>  {{$user_detail->bank_account_no}}</h6>
              <h6><strong>Account Branch:</strong>  {{$user_detail->bank_account_branch}}</h6>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-8">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs bg-danger">
             {{--  <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="false">Leads</a></li>
              <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="true">Lead Tasks</a></li> --}}
              <li class="active"><a href="#settings" data-toggle="tab">Project Tasks</a></li>

              <li><a href="#subordinates" data-toggle="tab">Subordinates</a></li>
            </ul>
            <div class="tab-content">
            {{--   <div class="tab-pane active" id="activity">
                <!-- Post -->
                <table class="table table-hover table-no-border table-striped" id="leads-table">
                    <thead>
                        <tr>
                            <th>{{ trans('admin/leads/general.columns.id') }}</th>
                            <th>{{ trans('admin/leads/general.columns.name') }}</th>
                            <th>{{ trans('admin/leads/general.columns.course_name') }}</th>
                            <th>{{ trans('admin/leads/general.columns.mob_phone') }}</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                     
                            @foreach($leads as $lead)
                            <tr>
                               <td>{{ env('APP_CODE')}}{{ $lead->id }}</td>                                  
                                <td style="float:lef; font-size: 16px">
                                       {{$lead->name}} {{$lead->company->name}}
                                </td>
                                    <td><span class="label label-default">{{ mb_substr($lead->course->name,0,13) }}..</span></td>
                                    <td>{{ $lead->mob_phone }}</td>
                                    <td><span class="label label-success">{{ $lead->communication->name }}
                                        </span>
                                    </td>
                                    <td>{{$lead->status->name}}</td>

                                    @if( $lead->rating == 'active')
                                    <td class="" style="background-color: #4B77BE">{{ $lead->rating }}</td>
                                    @endif
                                    @if( $lead->rating == 'failed')
                                    <td class="" style="background-color: #8F1D21">{{ $lead->rating }}</td>
                                    @endif
                                    @if( $lead->rating == 'acquired')
                                    <td class="" style="background-color: #26A65B">{{ $lead->rating }}</td>
                                    @endif
                                    @if( $lead->rating == 'blacklist')
                                    <td class="" style="background-color: #000000">{{ $lead->rating }}</td>
                                    @endif
                            </tr>
                              @endforeach

                    </tbody>
                </table>


 
                <!-- /.post -->
              </div> --}}
              <!-- /.tab-pane -->
            {{--   <div class="tab-pane " id="timeline">
                <!-- The timeline -->
                <table class="table table-hover table-no-border" id="leads-table">
                   <thead>
                     <tr><th>Id</th>
                        <th>{{ trans('admin/tasks/general.columns.lead') }}</th>
                        <th>{{ trans('admin/tasks/general.columns.task_subject') }}</th>
                        <th>{{ trans('admin/tasks/general.columns.task_status') }}</th>
                        <th>{{ trans('admin/tasks/general.columns.task_owner') }}</th>
                        <th>{{ trans('admin/tasks/general.columns.assigned_to') }}</th>
                        <th>{{ trans('admin/tasks/general.columns.task_priority') }}</th>
                        <th>{{ trans('admin/tasks/general.columns.task_due_date') }}</th>
                     </tr>
                 </thead>
                 <tbody>
                  @foreach($tasks as $task)
                  <tr>
                    <td>{{env('APP_CODE')}}{{$task->id}}</td>
                    <td class="bg-success">{{ $task->lead->name }}</td>
                    <td ><a href="/admin/tasks/{{$task->id}}">{{ mb_substr($task->task_subject,0,25) }}..</a></td>

                    @if( $task->task_status == 'Started')
                    <td class="" style="background-color: #4B77BE">{{ $task->task_status }}</td>
                    @endif
                    @if( $task->task_status == 'Completed')
                    <td class="" style="background-color: #26A65B">{{ $task->task_status }}</td>
                    @endif
                    @if( $task->task_status == 'Open')
                    <td class="" style="background-color: #8F1D21">{{ $task->task_status }}</td>
                    @endif
                    @if( $task->task_status == 'Processing')
                    <td class="" style="background-color: pink">{{ $task->task_status }}</td>
                    @endif
                    <td class="">
                       {{$task->owner->full_name}}
                    </td>
                    <td><span class="label label-default">{{$task->assigned_to->full_name}}</span></td>
                    <td><span class="label label-success">{{ $task->task_priority }}
                        </span>
                    </td>
                    <td>{!! date('dS M y', strtotime($task->task_due_date)) !!}
                    </td>
                        
                  </tr>
                  @endforeach
                 </tbody>
  
                </table>
               
              </div> --}}
              <!-- /.tab-pane -->

              <div class="tab-pane active" id="settings">

                <table class="table table-hover table-no-border" >
                   <thead>
                     <tr> 
                        <th>ID</th>
                        <th>Project</th>
                        <th>Project Task</th>
                        <th>Status</th>      
                        <th>Start Date</th>
                        <th>End Date</th>
                     </tr>
                   </thead>
                   <tbody>
                    @foreach($projects_tasks  as $lead)
                     <tr>
                         <td>{{env('APP_CODE')}}{{ $lead->id }}</td>
                         <td>
                              {{ $lead->project->name }}
                         </td>
                         <td><a href="/admin/project_task/{{$lead->id}}" target="_blank">{{ $lead->subject }}</a></td>

                         @if($lead->status =='new')
                        <td><span class="label label-primary">{{ $lead->status }}</span></td>
                        @elseif($lead->status =='ongoing')
                        <td><span class="label label-info">{{ $lead->status }}</span></td>
                        @else($lead->status =='completed')
                        <td><span class="label label-success">{{ $lead->status }}</span></td>

                        @endif

                        <td>{{ date('dS M y', strtotime($lead->start_date))}}</td>
                        <td>{{ date('dS M y', strtotime($lead->end_date))}}</td>

                       
                     </tr>
                     @endforeach
                   </tbody>
                 </table>


                
                  

              </div>



              <div class="tab-pane" id="subordinates">
                <table class="table table-hover table-no-border">
                    <thead>
                      <tr>
                      <th>Name</th>
                      <th>Job Title</th>
                      <th>Level</th>
                      <th>Status</th>
                      <th>Join Date</th>
                    </tr>
                    </thead>
                    <tbody>
                      @if(isset($sub_ordinates))
                      @foreach($sub_ordinates as $key=>$sub_user)
                        <tr>
                          <td>
                        <div class="media ">
                          
                          <div class="media-left">
                            <a href="/admin/profile/show/{{ $sub_user->id }}">
                              <img class="img-circle img-bordered-sm" 
                              src="{{ $sub_user->image?'/images/profiles/'.$sub_user->image:$sub_user->avatar }}" style="height: 28px; width: 28px;margin-top: 5px;" alt="{{ $sub_user->username }}"> 
                            </a>
                          </div>
                          
                          
                          <div class="media-body media-body-profile-name">
                                <a href="/admin/profile/show/{{ $sub_user->id }}">
                                    <span class="media-heading link-profile-name" title="Sushma  Joshi">
                                     {{$sub_user->first_name}} {{$sub_user->last_name}}
                                    </span>
                                    
                                      <span class="department-profile-name text-muted">
                                        <span title="HR"><br>
                                          
                                          {{$sub_user->designation->designations}}
                                          
                                        </span>

                                        
                                      </span>
                                    
                                </a>
                          </div>
                          
                        </div>
                          </td>@php $userDetails = $sub_user->userDetail;  @endphp
                          <td>{{$sub_user->designation->designations}}</td>

                          <td>{{$sub_user->designation->designations}}</td>
                          <td> {{ ucfirst($userDetails->employemnt_type) }} </td>
                          <td style="white-space: nowrap;"> {{ $userDetails->join_date }} </td>
                        </tr>


                      @endforeach
                      @endif
                    </tbody>

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

           
    

@endsection

@section('body_bottom')
    <!-- Select2 js -->
    @include('partials._body_bottom_select2_js_role_search')
@endsection
