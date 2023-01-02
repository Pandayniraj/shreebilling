@extends('layouts.master')

@section('head_extra')
    <!-- jVectorMap 1.2.2 -->
    <link href="{{ asset("/bower_components/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css") }}" rel="stylesheet" type="text/css" />

       <style type="text/css">
                      [data-letters]:before {
                            content:attr(data-letters);
                            display:inline-block;
                            font-size:1em;
                            width:3.5em;
                            height:3.5em;
                            line-height:2.5em;
                            text-align:center;
                            background:#f47c11;
                            vertical-align:middle;
                            margin-right:0.1em;
                            color:white;
                            }
            .leave_tr:hover{
                cursor: pointer;
                background-color: #ECF0F5;
            }
.popover-content{
  padding: 0;}
      </style>
@endsection

@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Human Resource Development Board
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
             <p> A Customized HRDM Board</p>

           <!-- {{ TaskHelper::topSubMenu('topsubmenu.hr')}} -->

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
<!--contents here -->

<div class="row col-md-12">
  
<div class="col-lg-3 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-primary">
            <div class="inner">
              <h3>Events</h3>

              <p>New Events</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar"></i>
            </div>
            <a href="/admin/events" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>Learn</h3>

              <p>Knowledge Base</p>
            </div>
            <div class="icon">
              <i class="fa fa-book"></i>
            </div>
            <a href="/admin/knowledge" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>


        <div class="col-lg-3 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-maroon">
            <div class="inner">
              <h3>Recruit</h3>

              <p>New Vacancy</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="/admin/job_posted" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-olive">
            <div class="inner">
              <h3>Start</h3>

              <p>On-boarding</p>
            </div>
            <div class="icon">
              <i class="fa fa-plane"></i>
            </div>
            <a href="/admin/onboard/task" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-teal">
            <div class="inner">
              <h3>Project</h3>

              <p>New Tasks</p>
            </div>
            <div class="icon">
              <i class="fa fa-bars"></i>
            </div>
            <a href="/admin/projects" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>


        <div class="col-lg-3 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
              <h3 class="box-title">Payroll</h3>

              <p>Manage</p>
            </div>
            <div class="icon">
              <i class="fa fa-money"></i>
            </div>
            <a href="/admin/payroll/run/step1" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
              <h3 class="box-title">Attendance</h3>

              <p>Check</p>
            </div>
            <div class="icon">
              <i class="fa fa-clock-o"></i>
            </div>
            <a href="/admin/attendanceReports" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-maroon">
            <div class="inner">
              <h3 class="box-title">Roaming</h3>

              <p>Track visit history</p>
            </div>
            <div class="icon">
              <i class="fa fa-map-marker"></i>
            </div>
            <a href="/admin/geolocations" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>









</div>

 <div class='row'>

<div class="col-md-3">
         <div class="box box-default box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Web Attendence Summary</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="chart-responsive">
                    <canvas id="pieChart" height="143" width="214" style="width: 238px; height: 160px;"></canvas>
                  </div>
                  <!-- ./chart-responsive -->
                </div>
              
              </div>
              <!-- /.row -->  
            </div> 
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="javascript::void()" data-toggle="popover" title="Present Employee" data-type='presentUser' data-html="true" >Present
                  <span class="pull-right text-bold">{{ count($present_user) }}</span></a></li>
                <li><a href="javascript::void()"  data-toggle="popover" title="Absent Employee"  data-type='absentUser' data-html="true" >Absent <span class="pull-right text-bold">{{ count($absent_user) }}</span></a>
                </li>
                <li><a href="javascript::void()" data-type='leaveUser'  data-toggle="popover" title="Leave Employee"   data-html="true" >Leave 
                  <span class="pull-right text-bold"> {{ count($on_leave) }}</span></a></li>
              </ul>
            </div>
            <!-- /.footer -->
          </div> 
</div>


<div class="col-md-6">

      <div class="tab-pane @if(isset($page_status) &&  $page_status == 'add') active @endif" id="my_leave" style="position: relative;">
                <div class="nav-tabs-custom">
                    <!-- Tabs within a box -->

                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#recent_leave" data-toggle="tab" aria-expanded="true">Recent Leave</a>
                        </li>
                        <li class="">
                            <a href="#pending_leave" data-toggle="tab" aria-expanded="false">Pending Leave</a>
                        </li>
                    </ul>
                    <div class="tab-content bg-white">

                        <div class="tab-pane active" id="recent_leave">
                          <div class="table-responsive">
                              <table class="table table-condensed">
                               <tbody> 
                                <tr>
                                  <th>Employee</th>
                                  <th>Manager</th>
                                  <th>Status</th>
                                  <th style="width: 40px">Date</th>
                                </tr>
                                @foreach($allleaves as $al)
                                  <tr onclick="window.open('/admin/leave_management/detail/{{ $al->leave_application_id }}')" class="leave_tr">
                                    <td>
                                      <img src="{{ $al->user->image?'/images/profiles/'.$al->user->image:$al->user->avatar }}" alt="User Image" width="25px" height="25px" style="border-radius: 50%;max-width: 100%;height: auto;">
                                      {{$al->user->first_name}} {{$al->user->last_name}}</td>
                                    <td>
                                    @if($al->approve)
                                    <img src="{{ $al->approve->image?'/images/profiles/'.$al->approve->image:$al->approve->avatar }}" alt="User Image" width="25px" height="25px" style="border-radius: 50%;max-width: 100%;height: auto;"> {{ $al->approve->first_name }} {{ $al->approve->last_name }}
                                    @else
                                     -- 
                                    @endif
                                    </td>
                                    <td>
                                      <span class="label @if($al->application_status == 1) label-warning @elseif($al->application_status == 2) label-success @else label-danger @endif">@if($al->application_status == 1) Pending @elseif($al->application_status == 2) Accepted @else Rejected @endif</span>
                                    </td>
                                    <td>
                                      <span class="badge bg-orange">  {{ \Carbon\Carbon::createFromTimeStamp(strtotime($al->created_at))->diffForHumans() }}...</span>
                                    </td>
                                  </tr>
                                @endforeach
                                
                              </tbody>
                            </table>
                            <div class="box-footer clearfix">
                           <a href="/admin/allpendingleaves" class="btn btn-sm btn-default btn-flat pull-right">View All </a>
                         </div>
                          </div>
                        </div>

                        <div class="tab-pane" id="pending_leave">
                            <div class="table-responsive">
                              <table class="table table-condensed">
                                  <tbody><tr>
                                    <th>Employee</th>
                                    <th>Manager</th>
                                    <th style="width: 40px">Date</th>
                                    </tr>
                                  @foreach($pen_leave as $pl)
                                    <tr onclick="window.open('/admin/leave_management/detail/{{ $pl->leave_application_id }}')" class="leave_tr">
                                    <td><img src="{{ $pl->user->image?\TaskHelper::getProfileImage($pl->user->id):$pl->user->avatar }}" alt="User Image" width="25px" height="25px" style="border-radius: 50%;max-width: 100%;height: auto;">
                                      {{$pl->user->first_name??null}} {{$pl->user->last_name??null}}</td>
                                    <td>
                                    <img src="{{ ($pl->approve->image ?? null) ? \TaskHelper::getProfileImage($pl->approve->id):($pl->approve->avatar??null) }}" alt="User Image" width="25px" height="25px" style="border-radius: 50%;max-width: 100%;height: auto;"> {{ $pl->approve->first_name ?? null }} {{ $pl->approve->last_name ?? null }}</td>
                                    <td><span class="badge bg-orange">  {{ \Carbon\Carbon::createFromTimeStamp(strtotime($pl->created_at))->diffForHumans() }}...</span></td>
                                  </tr>
                              @endforeach

                              </tbody></table>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>
  </div>

<div class="col-md-3">  
<div class="box box-danger box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Contract Expiring</h3>

                  <div class="box-tools pull-right">
                    <span class="label label-danger">Total {{ count($leaving_employee) }}</span>
                    
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">
                    @foreach($leaving_employee as $le)
                    <li>
                      <img src="{{ $le->user->image?\TaskHelper::getProfileImage($le->user->id):$le->user->avatar }}" alt="User Image">
                      <a class="users-list-name" href="#"  data-toggle="tooltip" data-html="true"  
                      title="{{ $le->user->username }} <br> <em>{{ $le->user->first_name ?? null }} {{ $le->user->last_name }}</em>" data-placement="bottom">
                      {{ $le->user->first_name ?? null }} {{ $le->user->last_name }}
                      </a>
                      <span class="users-list-date">
                        {{ date('dS M',strtotime($le->contract_end_date)) }}</span>
                    </li>
                    @endforeach
                  </ul>
                  <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                <!-- /.box-footer -->
              </div>

                  <!-- /.box-header -->

              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-birthday-cake"></i> Birthdays</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">

                   @foreach($birthdays as $bd)  
                    <li>
                      <img src="{{ \TaskHelper::getProfileImage($bd->id) }}" alt="User Image" width="50px" height="50px" style="border-radius: 50%;max-width: 100%;height: auto;">
                      <a class="users-list-name" href="/admin/users/{{$bd->id}}">{{ $bd->first_name }}</a>
                    </li>
                    @endforeach


                  </ul>
                  <!-- /.users-list -->
                </div>
              
              </div>
</div>

</div>

<div class="row">
    <div class="col-md-3">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-clock-o"></i> On Leave</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">
                    @foreach($on_leave as $ol)
                    <li>
                      <img src="userlogo.jpg" alt="User Image" width="50px" height="50px" style="border-radius: 50%;max-width: 100%;height: auto;">
                       <a class="users-list-name" href="/admin/users/{{$ol->user->id}}">{{ $ol->user->first_name }}</a>
                    </li>
                    @endforeach
                  </ul>
                  <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                <!-- /.box-footer -->
              </div>
            <!-- Absent Employees -->
   

        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-calendar"></i> Holidays</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                @foreach($holidays as $hd)
                  <li class="item">
                    <div class="product-img">
                       <a href="#" data-toggle="modal" data-target="#saveLogo">
                          <small data-letters="{!! date('d M', strtotime($hd->start_date)) !!}"></small>
                       </a>
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">{{ $hd->event_name }}</a>
                      <span class="product-description">
                          {{ \Carbon\Carbon::createFromTimeStamp(strtotime($hd->start_date))->diffForHumans() }}...
                      </span>
                    </div>
                  </li>
                @endforeach
               
              </ul>
            </div>
            <!-- /.box-body -->
            <!-- /.box-footer -->
          </div>

    </div>

    <div class="col-md-6">

        <div class="box box-default box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Announcements</h3>
              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                      <tr>
                        <th>SL</th>
                        <th>Title</th>
                        <th>Start date</th>
                        <th>End Date</th>
                      </tr>
                  </thead>
                  <tbody> 
                  @if(isset($announcements))
                  @foreach($announcements as $av)   
                    <tr style="font-size: 13px;">
                      <td>AN<a>{{ $av->announcements_id }}</</a></td>
                      <td>{{ $av->title }}</td>
                      <td>{{ $av->start_date }}</td>
                      <td>{{ $av->start_date }}</td>
                    </tr>
                    @endforeach
                  @endif
                 
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="/admin/announcements" class="btn btn-sm btn-default btn-flat pull-right">View All </a>
            </div>
            <!-- /.box-footer -->
          </div>
    </div>
    <div class="col-md-3">

        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-flash"></i> Activity Stream</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">

                @foreach($activity as $act)
                <li class="item">
                  <div class="product-img">
                    <img src="userlogo.jpg" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">{{$act->user->first_name}} {{$act->user->last_name}}
                    </a>
                    <span class="product-description">
                     Requested <span style="text-transform: capitalize;">{{ $act->category->leave_category}}</span>
                    </span>
                    <span class="product-description">
                     {{ \Carbon\Carbon::createFromTimeStamp(strtotime($act->created_at))->diffForHumans() }}...
                    </span>
                  </div>
                </li>
                @endforeach
               
              </ul>
            </div>
            <!-- /.box-body -->
            <!-- /.box-footer -->
          </div>
        
    </div>

</div>
<div class="row">
      <div class="col-md-6">

      <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa  fa-user"></i> Employee By Gender</h3>
                    
                </div>

                 <div class="box-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="chart-responsive">
                         {{--  <canvas id="Z" height="195" width="303" style="width: 243px; height: 156px;"></canvas> --}}
                          <div id="pieChartgender" style="height: 400px"></div>
                        </div>
                        <!-- ./chart-responsive -->
                      </div>
                    
                    </div>
                    <!-- /.row -->  
                  </div>
                <!-- /.box-footer -->
          </div>
         
    </div>
    <div class="col-md-6">

      <div class="box box-warning box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa  fa-money"></i> Salary Invested By department</h3>
                    
                </div>

                 <div class="box-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="chart-responsive">
                         {{--  <canvas id="Z" height="195" width="303" style="width: 243px; height: 156px;"></canvas> --}}
                          <div id="pieChartSalary" style="height: 400px"></div>
                        </div>
                        <!-- ./chart-responsive -->
                      </div>
                    
                    </div>
                    <!-- /.row -->  
                  </div>
                <!-- /.box-footer -->
          </div>
         
    </div>
  </div>
<div class="row">


    <div class="col-md-6">

    <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">
                <i class="fa  fa-calendar-check-o"></i> 
                This Week TimeSheet
              <small class="text-bold">
                {{ date('dS Y M',strtotime($timesheet_info['start_date'])) }} to  {{ date('dS Y M',strtotime($timesheet_info['end_date'])) }}
              </small> 
              </h3>

              <div class="box-tools pull-right">
                <select id='timesheet_chart_option'>
                  <option value="emp">By Employee</option>
                  <option value="cost">By Cost</option>
                </select>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="chart-responsive">
                    @if(empty($timesheet_project_chart))
                      <div class="alert alert-warning" id="pieChart2">
                          <strong>Error!</strong> No any Time Employee Working Found This Week
                      </div>
                    @else
                          <div id="pieChart2" style="height: 400px"></div>
                    @endif

                    @if(empty($timesheet_project_chart_by_cost))
                        <div class="alert alert-warning" id="pieChart3" style="display: none;">
                          <strong>Error!</strong> No any Cost Invested Found This Week
                      </div>
                    @else
                        <div id="pieChart3" style="height: 400px;display: none"></div>
                    @endif
                
                
                  
                  </div>
                  <!-- ./chart-responsive -->
                </div>
                <!-- /.col -->
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#">Working Projects
                  <span class="pull-right text-bold">{{ $timesheet_info['total_active_project']}} / {{ $timesheet_info['total_project']}}</span></a></li>
                <li><a href="#">Working User <span class="pull-right text-bold">{{ $timesheet_info['total_user_involved'] }}</span></a>
                </li>
                <li><a href="#">Regular Cost
                  <span class="pull-right text-bold">{{ $timesheet_info['total_regular_cost'] }}</span></a>
                </li>
                 <li><a href="#">OverTime Cost
                  <span class="pull-right text-bold">{{ $timesheet_info['total_overtime_cost'] }}</span></a>
                </li>
                <li><a href="#"> TotalCost
                  <span class="pull-right text-bold">{{ $timesheet_info['total_cost'] }}</span></a>
                </li>
              </ul>
            </div>
            <!-- /.footer -->
          </div>
    </div>


    <div class="col-md-6">

      <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa  fa-users"></i> Employee By Department</h3>
                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>

                 <div class="box-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="chart-responsive">
                         {{--  <canvas id="Z" height="195" width="303" style="width: 243px; height: 156px;"></canvas> --}}
                          <div id="pieChart4" style="height: 400px"></div>
                        </div>
                        <!-- ./chart-responsive -->
                      </div>
                    
                    </div>
                    <!-- /.row -->  
                  </div>
               
            
                <!-- /.box-footer -->
            <div class="box-footer no-padding">
             {{--  <ul class="nav nav-pills nav-stacked">
                <li><a href="#">United States of America
                  <span class="pull-right text-red"><i class="fa fa-angle-down"></i> 12%</span></a></li>
                <li><a href="#">India <span class="pull-right text-green"><i class="fa fa-angle-up"></i> 4%</span></a>
                </li>
                <li><a href="#">China
                  <span class="pull-right text-yellow"><i class="fa fa-angle-left"></i> 0%</span></a></li>
              </ul> --}}
            </div>

          </div>
         
    </div>
</div>

<div class="row">
 
    <div class="col-md-12">
            <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">New Vacancy Announcement</h3>
                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <div class="table-responsive">
                      <table class="table no-margin">
                        <thead>
                            <tr>
                              <th>SL</th>
                              <th>Job Title</th>
                              <th>No of Vacancies</th>
                              <th>Posted date</th>
                              <th>Last date</th>
                            </tr>
                        </thead>
                        <tbody> 
                        
                        @foreach($circulars ?? []  as $av)   
                          <tr style="font-size: 13px;">
                            <td>JP{{ $av->job_circular_id }}</</a></td>
                            <td>{{ $av->job_title }}</td>
                            <td>{{ $av->vacancy_no }}</td>
                            <td>{{ $av->posted_date }}</td>
                            <td>{{ $av->last_date }}</td>
                          </tr>
                          @endforeach
                       
                        </tbody>
                      </table>
                    </div>
                    <!-- /.table-responsive -->
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer clearfix">
                    <a href="/admin/job_posted" class="btn btn-sm btn-default btn-flat pull-right">View All </a>
                  </div>
                  <!-- /.box-footer -->
                </div>        
    </div>
    
    
</div>
<div id='presentUser' style="display: none;">
  <div style="max-height: 500px; overflow-y: scroll;">
  <table class="table table-striped" >
    
    <tr>
      <td>Username</td>
      <td>Department</td>
    </tr>
     @foreach($present_user as $key=>$useratt)

     <tr style="font-size: smaller;">
       <td>{{ ++$key }}</td>
       <td>{{ $useratt->first_name }} {{ $useratt->last_name }}</td>
       <td>{{$useratt->department->deptname ?? '-'}}</td>
     </tr>
     @endforeach

  </table>
</div>
</div>

<div id='absentUser' style="display: none;">
  <div style="max-height: 500px; overflow-y: scroll;">
  <table class="table table-striped" >
    
    <tr>
      <td>S.N</td>
      <td>Username</td>
      <td>Department</td>
    </tr>
     @foreach($absent_user as $key=>$useratt)

     <tr style="font-size: smaller;">
      <td>{{ ++$key }}</td>
       <td>{{ $useratt->first_name }} {{ $useratt->last_name }}</td>
       <td>{{$useratt->department->deptname ?? '-'}}</td>
     </tr>
     @endforeach

  </table>

</div>
</div>


<div id='leaveUser' style="display: none;">
  <div style="max-height: 500px; overflow-y: scroll;">
  <table class="table table-striped" >
    
    <tr>
      <td>S.N</td>
      <td>Username</td>
      <td>Department</td>
    </tr>
     @foreach($on_leave as $key=>$useratt)
     <?php  $useratt = $useratt->user; ?>
     <tr style="font-size: smaller;">
      <td>{{ ++$key }}</td>
       <td>{{ $useratt->first_name }} {{ $useratt->last_name }}</td>
       <td>{{$useratt->department->deptname ?? '-'}}</td>
     </tr>
     @endforeach

  </table>

</div>
</div>


   
@endsection




</div>

@section('body_bottom')
    <!-- ChartJS -->
      <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset ("/bower_components/highcharts/highcharts.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/highcharts/funnel.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/highcharts/highcharts-3d.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/highcharts/exporting.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/highcharts/export-data.js") }}" type="text/javascript"></script>
    
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
  <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
   
        <script src="{{ asset ("/bower_components/admin-lte/plugins/chartjs/Chart.min.js") }}" type="text/javascript"></script>

     <script type="text/javascript">
    $(document).ready(function(){



        $('[data-toggle="popover"]').each(function(){

          
        let id = `#${$(this).attr('data-type')}`;
        var popovercont = $(this);
        $(this).attr('data-content',$(id).html());
        // setTimeout(function(){
        //    popovercont.popover({ container: 'body'});

        // },500);
       
        }); 

        $('[data-toggle="popover"]').popover({
            container: 'body'
        });  

       
    });
        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [
            {
                value: {{ count($absent_user) }},
                color: "#f56954",
                highlight: "#f56954",
                label: "Absent"
            },
            {
                value:  {{ count($present_user) }},
                color: "#00a65a",
                highlight: "#00a65a",
                label: "Present"
            },
            {
                value: {{ count($on_leave) }},
                color: "#f39c12",
                highlight: "#f39c12",
                label: "On Leave"
            }
        ];
        var pieOptions = {
            //Boolean - Whether we should show a stroke on each segment
            segmentShowStroke: true,
            //String - The colour of each segment stroke
            segmentStrokeColor: "#fff",
            //Number - The width of each segment stroke
            segmentStrokeWidth: 1,
            //Number - The percentage of the chart that we cut out of the middle
            percentageInnerCutout: 50, // This is 0 for Pie charts
            //Number - Amount of animation steps
            animationSteps: 100,
            //String - Animation easing effect
            animationEasing: "easeOutBounce",
            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate: true,
            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale: false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true,
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: false,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
            //String - A tooltip template
            tooltipTemplate: "<%=value %> <%=label%> users"
        };
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);
        //-----------------
        //- END PIE CHART -
        //-----------------
    </script>

    <script type="text/javascript">
  //Piechart for Communication
    const departmentdata = <?php echo json_encode($user_by_dep_data) ?>;
    const  timesheet_project_chart = <?php echo json_encode($timesheet_project_chart); ?>;
    const  timesheet_project_chart_by_cost = <?php echo json_encode($timesheet_project_chart_by_cost); ?>;
    const male_female_data =<?php echo json_encode($male_female_data); ?>;
    const dep_by_user_salary =<?php echo json_encode($dep_by_user_salary); ?>;
    console.log(male_female_data,dep_by_user_salary);
    $(function () {
      $('#pieChart4').highcharts({
      chart: {
        type: 'pie',
        options3d: {
          enabled: true,
          alpha: 45,
          beta: 0
        }
      },
      title: {
        text: 'Employee By Department'
      },
      tooltip: {
        pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> ({point.y})'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          depth: 35,
          dataLabels: {
            enabled: true,
            format: '{point.name} <br/> <b>{point.percentage:.1f}%</b> ({point.y})'
          }
        }
      },
      series: [{
        type: 'pie',
        name: 'Department',
        data: departmentdata
      }]
    });
  



if(timesheet_project_chart && timesheet_project_chart.length > 0){

    Highcharts.chart('pieChart2', {
      chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
      },
      title: {
        text: 'TimeSheet Report Based on Working Employee and Project'
      },
      tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      accessibility: {
        point: {
          valueSuffix: '%'
        }
      },
      tooltip: {
            pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> ({point.y})'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
            enabled: false
          },
          showInLegend: true
        }
      },
       legend: {
       layout: 'vertical',
       align: 'right',
       verticalAlign: 'middle',
       itemMarginTop: 10,
       itemMarginBottom: 10
     },
      series: [{
        name: 'Employee',
        colorByPoint: true,
        data: timesheet_project_chart,
      }]
    });

}


if(timesheet_project_chart_by_cost && timesheet_project_chart_by_cost.length > 0){
    Highcharts.chart('pieChart3', {
    chart: {
      plotBackgroundColor: null,
      plotBorderWidth: null,
      plotShadow: false,
      type: 'pie'
    },
    title: {
      text: 'TimeSheet Report Based on Employee Cost and Project '
    },
    accessibility: {
      point: {
        valueSuffix: '%'
      }
    },
     tooltip: {
          pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> ,{{ env('APP_CURRENCY') }}. {point.y} '
    },
    plotOptions: {
      pie: {
        allowPointSelect: true,
        cursor: 'pointer',
        dataLabels: {
          enabled: false
        },
        depth: 35,
        showInLegend: true
      }
    },
     legend: {
     layout: 'vertical',
     align: 'right',
     verticalAlign: 'middle',
     itemMarginTop: 10,
     itemMarginBottom: 10
   },
    series: [{
      name: 'Cost',
      colorByPoint: true,
      data: timesheet_project_chart_by_cost,
    }]
  });


}


 $('#pieChartgender').highcharts({
      chart: {
        type: 'pie',
        options3d: {
          enabled: true,
          alpha: 45,
          beta: 0
        }
      },
      title: {
        text: 'Employee By Gender'
      },
      tooltip: {
        pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> ({point.y})'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          depth: 35,
          dataLabels: {
            enabled: true,
            format: '{point.name} <br/> <b>{point.percentage:.1f}%</b> ({point.y})'
          }
        }
      },
      series: [{
        type: 'pie',
        name: 'Gender',
        data: male_female_data
      }]
    });

  $('#pieChartSalary').highcharts({
      chart: {
        type: 'pie',
        options3d: {
          enabled: true,
          alpha: 45,
          beta: 0
        }
      },
      title: {
        text: 'Salary Invested By Department'
      },
      tooltip: {
           pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> ,{{ env('APP_CURRENCY') }}.{point.y} '
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          depth: 35,
          dataLabels: {
            enabled: true,
            format: '{point.name} <br/> <b>{point.percentage:.1f}%</b> ,{{ env('APP_CURRENCY') }}.{point.y}'
          }
        }
      },
      series: [{
        type: 'pie',
        name: 'Salary',
        data: dep_by_user_salary
      }]
    });
  
$('#timesheet_chart_option').change(function(){
    if($(this).val()=='emp')
    {
      $('#pieChart2').show();
      $('#pieChart3').hide();
    }else{
      $('#pieChart2').hide();
      $('#pieChart3').show();
    }
});


});


    </script>
   

  

@endsection
