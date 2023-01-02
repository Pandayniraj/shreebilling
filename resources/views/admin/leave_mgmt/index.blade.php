@extends('layouts.master')

@section('content')
<style>
    .err { border: 1px solid red; }
    .text-muted { font-size: 12px; color: red !important; }
    .navbar-custom-nav {
        background: #FFFFFF;
        box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.15);
        margin-top: 10px !important;
    }
    .navbar-custom-nav li {
        border-bottom: 1px solid #cfdbe2;
    }
    .table > thead > tr > th {
        color: rgb(136, 136, 136);
        padding: 14px 8px;
    }

    .fileinput {
        margin-bottom: 9px;
        display: inline-block;
    }

    .fileinput-exists .fileinput-new, .fileinput-new .fileinput-exists {
        display: none;
    }
    .fileinput-filename { padding-left:  5px; }

    .fileinput .btn {
        vertical-align: middle;
    }

    .btn.btn-default {
        border-color: #ddd;
        background: #f4f4f4;
    }
    .btn-file {
        overflow: hidden;
        position: relative;
        vertical-align: middle;
    }

    .btn-file > input {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        opacity: 0;
        filter: alpha(opacity=0);
        transform: translate(-300px, 0) scale(4);
        font-size: 23px;
        direction: ltr;
        cursor: pointer;
    }
    input[type="file"] {
        display: block;
    }

    .close {
        float: right;
        font-size: 21px;
        font-weight: bold;
        line-height: 1;
        color: #000000;
        text-shadow: 0 1px 0 #ffffff;
        opacity: 0.2;
        filter: alpha(opacity=20);
    }

    .fileinput.fileinput-exists .close {
        opacity: 1;
        color: #dee0e4;
        position: relative;
        top: 3px;
        margin-left: 5px;
    }

    .panel {
        margin-bottom: 21px;
        background-color: #ffffff;
        border: 1px solid transparent;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.15);
    }

    .panel-custom .panel-heading {
        margin-bottom: 10px;
    }
    .panel .panel-heading {
        border-bottom: 0;
        font-size: 14px;
    }
    .panel-heading {
        padding: 10px 15px;
        border-bottom: 1px solid transparent;
        border-top-right-radius: 3px;
        border-top-left-radius: 3px;
    }
    .panel-title {
        margin-top: 0;
        margin-bottom: 0;
        font-size: 16px;
        color: inherit;
    }
    .panel-custom .panel-heading {
        border-bottom: 2px solid #1797be;
        font-size: 14px;
    }

    .panel-custom .panel-heading {
        margin-bottom: 10px;
    }
    .ribbon {
        position: absolute;
        right: 9px;
        top: -5px;
        z-index: 1;
        overflow: hidden;
        width: 75px;
        height: 75px;
        text-align: right;
    }
    .ribbon span {
        font-size: 11px;
        font-weight: 600;
        color: #FFF;
        text-transform: uppercase;
        text-align: center;
        line-height: 20px;
        transform: rotate(45deg);
        -webkit-transform: rotate(45deg);
        width: 100px;
        display: block;
        position: absolute;
        top: 19px;
        right: -21px;
    }
    .ribbon.danger span {
        background: #f05050;
    }
    .ribbon.warning span {
        background: #f0ad4e;
    }
    .ribbon.success span {
        background: #259B24;
    }

    .ribbon.danger span::before, .ribbon.danger span::after {
        border-left: 3px solid #f05050;
        border-top: 3px solid #f05050;
    }
    .ribbon.warning span::before, .ribbon.warning span::after {
        border-left: 3px solid #f0ad4e;
        border-top: 3px solid #f0ad4e;
    }
    .ribbon.success span::before, .ribbon.success span::after {
        border-left: 3px solid #1C841B;
        border-top: 3px solid #1C841B;
    }

    .ribbon span::before {
        content: "";
        position: absolute;
        left: 0px;
        top: 100%;
        z-index: -1;
        border-left: 3px solid #1C841B;
        border-right: 3px solid transparent;
        border-bottom: 3px solid transparent;
        border-top: 3px solid #1C841B;
    }
    .ribbon span::after {
        content: "";
        position: absolute;
        right: 0px;
        top: 100%;
        z-index: -1;
        border-left: 3px solid transparent;
        border-bottom: 3px solid transparent;
    }
    .task_details .form-group {
        margin-bottom: 0px;
    }
    .required { color: red; }
    .dataTables_length { float: left; }
    .dataTables_filter { float: right; }
</style>
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<div class="row ">

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Leave Manager
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
             Current Leave Year: <strong>{{ TaskHelper::cur_leave_yr()->leave_year}}</strong><br>
           {{ TaskHelper::topSubMenu('topsubmenu.hr')}}

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class="col-md-12">
        <div class="tab-content pl0 box">
            @if($page_status == 'edit' && $leaveApp)  
            <div class="tab-pane active" id="appli_detail" style="position: relative;">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="panel panel-custom">
                            <!-- Default panel contents -->
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <strong>{{ $leaveApp->user->first_name.' '.$leaveApp->user->last_name }} Applied From <span class="text-danger"> {{ $leaveApp->leave_start_date }}</span> To <span class="text-danger"> {{ $leaveApp->leave_end_date }}</span>
                                    </strong>
                                </div>
                            </div>

                            <div class="panel-body row form-horizontal task_details">
                                @if($leaveApp->application_status == 1) <div class="r9 ribbon warning"><span> Pending </span></div>@elseif($leaveApp->application_status == 2) <div class="r9 ribbon success"><span> Accepted </span></div>@else <div class="r9 ribbon danger"><span> Rejected </span></div> @endif
                                <div class="form-group ">
                                    <label class="control-label col-sm-4"><strong>Leave Category :</strong></label>
                                    <div class="col-sm-8">
                                        <p class="form-control-static ">{{ $leaveApp->category->leave_category }}</p>
                                    </div>
                                </div>
                                 <div class="form-group ">
                                    <label class="control-label col-sm-4"><strong>Part of day :</strong></label>
                                    <div class="col-sm-8">
                                        <p class="form-control-static ">
                                            @if($leaveApp->part_of_day == 1)
                                            Full Leave
                                            @elseif($leaveApp->part_of_day == 2)
                                            1st half
                                            @elseif($leaveApp->part_of_day == 3)
                                            2nd half
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-sm-4"><strong>Start Date :</strong></label>
                                    <div class="col-sm-8">
                                        <p class="form-control-static ">{{ $leaveApp->leave_start_date }}</p>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-sm-4"><strong>End Date:</strong></label>
                                    <div class="col-sm-8">
                                        <p class="form-control-static ">{{ $leaveApp->leave_end_date }}</p>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-sm-4"><strong>Applied On:</strong></label>
                                    <div class="col-sm-8">
                                        <p class="form-control-static ">{{ date('Y-m-d h:m F', strtotime($leaveApp->application_date)) }} 2019.01.31 at 10:11 AM</p>
                                    </div>
                                </div>
                                @if($leaveApp->application_status == 2) 
                                <div class="form-group ">
                                    <label class="control-label col-sm-4"><strong>Approved By :</strong></label>
                                    <div class="col-sm-8">
                                        <p class="form-control-static ">{{ $leaveApp->approve->first_name.' '.$leaveApp->approve->last_name }}</p>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group ">
                                    <label class="control-label col-sm-4"><strong>Reason:</strong></label>
                                    <div class="col-sm-8">
                                        <blockquote style="font-size: 12px; margin-top: 5px">{{ $leaveApp->reason }}</blockquote>
                                    </div>
                                </div>
                                @if($leaveApp->comment != '' && $leaveApp->comment != NULL)
                                <div class="form-group ">
                                    <label class="control-label col-sm-4"><strong>Comments:</strong></label>
                                    <div class="col-sm-8">
                                        <blockquote style="font-size: 12px; margin-top: 5px">{{ $leaveApp->comment }}</blockquote>
                                    </div>
                                </div>
                                @endif
                                @if($leaveApp->attachment != '' && $leaveApp->attachment != NULL) 
                                <div class="form-group ">
                                    <label class="control-label col-sm-4"><strong>Attachment:</strong></label>
                                    <div class="col-sm-8">
                                        @foreach(explode(',', $leaveApp->attachment) as $lfv)
                                        <a class="btn btn-xs btn-dark" data-toggle="tooltip" data-placement="top" title="" href="/leave_files/{{ $lfv }}" data-original-title="Download" target="_blank">
                                            <p class="form-control-static ">{{ $lfv }}</p>
                                        </a><br/>
                                        @endforeach
                                    </div>
                                </div>
                                @endif 
                                
                                @if(\Auth::user()->hasRole('admins') || \Auth::user()->hasRole('hr-manager') || \Auth::user()->department_head == '1' && $leaveApp->application_status != '2' ) 
                                <div class="form-group ">
                                    <label class="control-label col-sm-4"><strong>Change Status:</strong></label>
                                    <div class="col-sm-8">
                                        <p class="form-control-static change_status ">
                                            @if($leaveApp->application_status != '2')
                                            <span data-toggle="tooltip" data-placment="top" title="" data-original-title="You are about to approved the record. This cannot be undone. Are you sure?">
                                                <a data-toggle="modal" data-target="#myModal" href="javascript:undefined" data-href="/admin/leave_management/change_status/{{ $leaveApp->leave_application_id }}/2" class="btn btn-success ml"><i class="fa fa-thumbs-o-up"></i> Approved</a>
                                            </span>
                                            @endif 
                                            @if($leaveApp->application_status != '1' && $leaveApp->application_status != '2')
                                            <a data-toggle="modal" data-target="#myModal" href="javascript:undefined" data-href="/admin/leave_management/change_status/{{ $leaveApp->leave_application_id }}/1" class="btn btn-warning ml"><i class="fa fa-times"></i> Pending</a>
                                            @endif
                                            @if($leaveApp->application_status != '3' && $leaveApp->application_status != '2')
                                            <a data-toggle="modal" data-target="#myModal" href="javascript:undefined" data-href="/admin/leave_management/change_status/{{ $leaveApp->leave_application_id }}/3" class="btn btn-danger ml"><i class="fa fa-times"></i> Rejected</a>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @endif
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="panel panel-custom">
                            <!-- Default panel contents -->
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <strong>Detail of {{ $leaveApp->user->first_name.' '.$leaveApp->user->last_name }}</strong>
                                    <span class="pull-right">Balance</span>
                                </div>
                            </div>
                           <table class="table">
                                <tbody>
                                    

                                <?php $leaves = 0; $totalLeaves = 0; ?>
                                @foreach($categories->where('leave_flow','static') as $cv)
                                <tr>
                                    <td>{{ $cv->leave_category }}</td>
                                    <?php
                                        $leavesTaken = \TaskHelper::userLeave(\Auth::user()->id, $cv->leave_category_id, date('Y'));
                                        $leaves += $leavesTaken;
                                        $totalLeaves += ($cv->leave_quota - $leavesTaken) 
                                    ?>
                                    <td>{{  $cv->leave_quota - $leavesTaken  }}</td>
                                </tr>
                                @endforeach

                                @foreach($categories->where('leave_flow','dynamic') as $cv)
                                <tr>
                                    <td>{{ $cv->leave_category }}</td>
                                     <td>{{ \TaskHelper::userLeave(\Auth::user()->id, $cv->leave_category_id, date('Y')) }}</td>
                                </tr>
                                @endforeach

                          
                                <?php  $timeoff = \TaskHelper::userLeave(\Auth::user()->id,env('TIME_OFF_ID') , date('Y')); ?>
                                <tr>
                                    <td style="font-size: 14px; font-weight: bold;">
                                        <strong>Time Off</strong>:
                                    </td>
                                    <td style="font-size: 14px; font-weight: bold;"> {{ 120 - $timeoff }} Min. </td>
                                </tr>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="tab-pane @if($page_status == 'add') active @endif" id="my_leave" style="position: relative;">
                <div class="nav-tabs-custom">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs bg-danger">
                        <li class="active">
                            <a href="#manage" data-toggle="tab" aria-expanded="true">My Leave</a>
                        </li>
                        <li class="">
                            <a href="#create" data-toggle="tab" aria-expanded="false">+ New Leave</a>
                        </li>
                    </ul>
                    <div class="tab-content bg-white">
                        <div class="tab-pane active" id="manage">
                            <div class="table-responsive">
                                <table class="table table-striped DataTables " id="">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Part</th>
                                            <th>Leave Category</th>
                                            <th>Status</th>
                                            <th class="col-sm-2">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($myleaves as $lv)
                                        <tr>
                                            <td>{{ $lv->user->first_name.' '.$lv->user->last_name }}</td>
                                            <td>{{ $lv->leave_start_date }}</td>
                                            <td>{{ $lv->leave_end_date }}</td>
                                            <td>{{ $lv->partOfDay() }}  </td>
                                            <td>{{ $lv->category->leave_category }}</td>
                                            <td><span class="label @if($lv->application_status == 1) label-warning @elseif($lv->application_status == 2) label-success @else label-danger @endif">@if($lv->application_status == 1) Pending @elseif($lv->application_status == 2) Accepted @else Rejected @endif</span></td>
                                            @if($lv->application_status == 1)
                                            <td>
                                                <a href="/admin/leave_management/detail/{{ $lv->leave_application_id }}" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"><span class="fa fa-list-alt"></span></a>
                                                <a href="/admin/leave_management_delete/{{ $lv->leave_application_id }}" class="btn btn-danger btn-xs" title="" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                            @else
                                            <td>
                                                <a href="javascript:undefined" class="btn btn-info btn-xs disabled" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"><span class="fa fa-list-alt"></span></a>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="create">
                            <div class="row">
                                <div class="col-sm-8">
                                    <form id="form" action="/admin/leave_management" method="post" enctype="multipart/form-data" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <div class="panel_controls">

                                            <div class="form-group">
                                                <label for="field-1" class="col-sm-4 control-label">Leave Category<span class="required"> *</span></label>
                                                <div class="col-sm-8">
                                                    <select name="leave_category_id" style="width: 100%" class="form-control " id="leave_category" required="" tabindex="-1" aria-hidden="true">
                                                        <option value="">Select Leave Category</option>
                                                        @foreach($allLeaveCategory as $cv)
                                                        <option value="{{ $cv->leave_category_id }}">{{ $cv->leave_category }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-10">
                                                    <div class="required" id="username_result"></div>
                                                </div>
                                            </div>  

                                            @if(\Auth::user()->hasRole('admins') || \Auth::user()->id == 1 || \Auth::user()->hasRole('leavedepartmenthead') || \Auth::user()->hasRole('hr-manager') )

                                            <div class="form-group">
                                                <label for="field-1" class="col-sm-4 control-label">Deparment<span class="required"> *</span></label>
                                                <div class="col-sm-8">
                                                    <select name="dept_id" style="width: 100%" class="form-control searchable" id='dep_id'  tabindex="-1" aria-hidden="true">
                                                        <option value="">Select Deparment</option>
                                                        @foreach($department as $dep)   
                                                            <option value="{{ $dep->departments_id }}" @if(\Auth::user()->departments_id == $dep->departments_id) selected @endif>{{ $dep->deptname }}</option>
                                                        @endforeach
                                                    </select>   
                                                </div>
                                            </div> 

                                            <div class="form-group">
                                                <label for="field-1" class="col-sm-4 control-label">Leave User<span class="required"> *</span></label>
                                                <div class="col-sm-8">
                                                    <select name="user_id" style="width: 100%" class="form-control searchable"  required="" tabindex="-1" aria-hidden="true" id='leaveusers'>
                                                        <option value="">Select User</option>
                                                        @foreach($users as $cv)   
                                                        <option value="{{ $cv->id }}">{{ $cv->username }}(#{{ $cv->id }})</option>
                                                        @endforeach
                                                    </select>   
                                                </div>
                                               
                                            </div>

                                             <div class="form-group">
                                                <label class="col-sm-4 control-label">Request To First Line Manager<span class="required"> *</span></label>
                                                <div class="col-sm-8">
                                                    <select name="request_to" style="width: 100%" class="form-control"   tabindex="-1" aria-hidden="true"
                                                    id='request_to'>
                                                        <option value="">Select Request To</option>
                                                    </select>   
                                                </div>
                                            </div>
                                            @else

                                                <input type="hidden" value="{{ Auth::user()->id }}" id='leaveusers' name="user_id">
                                            @endif

                                           

                                        <div class="form-group" id='partOfDay'>
                                                <label class="col-sm-4 control-label">Part of day<span class="required"> *</span></label>
                                                <div class="col-sm-8">
                                                    <label class="control-label">
                                                      <input type="radio" name="part_of_day" 
                                                      value ='1' checked="">   Full Leave
                                                   </label><br>
                                                   <label class="control-label">
                                                       <input type="radio" name="part_of_day" value ='2'> 1<sup>st</sup>  Half Leave 
                                                   </label><br>
                                                   <label class="control-label">
                                                       <input type="radio" name="part_of_day" value ='3'>  2<sup>nd</sup>Half Leave
                                                   </label>
                                                </div>
                                                
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Start Date <span class="required"> *</span></label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <input type="text" name="leave_start_date" id="start_date" required="" class="form-control datepicker" value="" data-format="dd-mm-yyyy">
                                                        <div class="input-group-addon">
                                                            <a href="#"><i class="fa fa-calendar"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  
                                       
                                            <div class="form-group" id='end_date_div'>
                                                <label class="col-sm-4 control-label">End Date <span class="required"> *</span></label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <input type="text" name="leave_end_date" id="end_date" class="form-control datepicker" value="" data-format="dd-mm-yyyy">
                                                        <div class="input-group-addon">
                                                            <a href="#"><i class="fa fa-calendar"></i></a>
                                                        </div>
                                                    </div>
                                                    <span id='date_validate' style="color: red;"></span>
                                                </div>
                                            </div>
                                         <div id= 'time_off_toggle'>  
                                        </div>

                                            <div class="form-group">
                                                <label for="field-1" class="col-sm-4 control-label">Reason</label>

                                                <div class="col-sm-8">
                                                    <textarea id="reason" name="reason" class="form-control" rows="6"></textarea>
                                                </div>
                                            </div>

                                            <div id="add_new">
                                                <div class="form-group" style="margin-bottom: 0px">
                                                    <label for="field-1" class="col-sm-4 control-label">Attachment</label>
                                                    <div class="col-sm-5">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                             <span class="btn btn-default btn-file">
                                                                <span class="fileinput-new">Select File</span>
                                                                <span class="fileinput-exists">Change</span>
                                                                <input type="hidden" value="" name="upload_file[]">
                                                                <input type="file" name="">
                                                            </span>
                                                            <span class="fileinput-filename"></span>
                                                            <a href="javascript::undefined" class="close fileinput-exists" data-dismiss="fileinput" style="float: none;">&times;</a>
                                                        </div>
                                                        <div id="msg_pdf" style="color: #e11221"></div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <strong>
                                                            <a href="javascript:void(0);" id="add_more" class="addCF "><i class="fa fa-plus"></i>&nbsp;Add More </a>
                                                        </strong>
                                                    </div>
                                                </div>
                                            </div>

                                            <br/>
                                            <div class="form-group">
                                                <div class="col-sm-offset-4 col-sm-5">
                                                    <button type="submit" id="sbtn" name="sbtn" value="1" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                            <br>
                                        </div>
                                    </form>
                                </div>

                                @php  $userIdStatus = Auth::user();  @endphp
                                <div class="col-sm-4" id='leaveStatus'>
                                   @include('admin.leave_mgmt.leave_status')
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- @if(\Auth::user()->hasRole('admins') || \Auth::user()->hasRole('hr-manager'))
            <div class="tab-pane" id="all_leave" style="position: relative;">
                <div class="nav-tabs-custom">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#allmanage" data-toggle="tab" aria-expanded="true">All Leave</a>
                        </li>
                    </ul>
                    <div class="tab-content bg-white">
                        <div class="tab-pane active" id="allmanage">
                            <div class="table-responsive">
                                <table class="table table-striped" id="all_leave_table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Leave Category</th>
                                            <th>Status</th>
                                            <th class="col-sm-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allleaves as $lv)
                                        <tr>
                                            <td>{{ $lv->user->first_name.' '.$lv->user->last_name }}</td>
                                            <td>{{ $lv->leave_start_date }}</td>
                                            <td>{{ $lv->leave_end_date }}</td>
                                            <td>{{ $lv->category->leave_category }}</td>
                                            <td><span class="label @if($lv->application_status == 1) label-warning @elseif($lv->application_status == 2) label-success @else label-danger @endif">@if($lv->application_status == 1) Pending @elseif($lv->application_status == 2) Accepted @else Rejected @endif</span></td>
                                            <td>
                                                <a href="/admin/leave_management/detail/{{ $lv->leave_application_id }}" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"><span class="fa fa-list-alt"></span></a>
                                                <a href="/admin/leave_management_delete/{{ $lv->leave_application_id }}" class="btn btn-danger btn-xs" title="" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="leave_report" style="position: relative;">
                <div class="nav-tabs-custom">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#allmanage" data-toggle="tab" aria-expanded="true">All Leave Report</a>
                        </li>
                    </ul>
                    <div class="tab-content bg-white">
                        <div class="tab-pane active" id="allmanage">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            @foreach($categories as $cv)
                                            <th>{{ $cv->leave_category }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $uv)
                                        <tr>
                                            <td>{{ $uv->first_name.' '.$uv->last_name }}</td>
                                            @foreach($categories as $cv)
                                            <td>{{ \TaskHelper::userLeave($uv->id, $cv->leave_category_id, date('Y')) }}/{{ $cv->leave_quota }}</td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif --}}
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>

@endsection
<div id= 'time_off_toggle_options' style="display: none;">
  
    <div id='TimeOff'>
     <div class="form-group" >
        <label class="col-sm-4 control-label">Start time<span class="required"> *</span></label>
        <div class="col-sm-8">
            <div class="input-group">
                <input type="text" name="time_off_start"  required="" class="form-control timepicker"   id='time_off_start'>
                <div class="input-group-addon">
                    <a href="#"><i class="fa fa-calendar"></i></a>
                </div>
            </div>
            <span id='date_validate' style="color: red;"></span>
        </div>
    </div>
      <div class="form-group">
        <label class="col-sm-4 control-label">End time<span class="required"> *</span></label>
        <div class="col-sm-8">
            <div class="input-group">
                <input type="text" name="time_off_end" required="" class="form-control timepicker"   id='time_off_end'>
                <div class="input-group-addon">
                    <a href="#"><i class="fa fa-calendar"></i></a>
                </div>
            </div>
            <span id='date_validate' style="color: red;"></span>
        </div>
    </div>
    </div>
</div>

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<script>
    $(function() {

        $('[data-toggle="tooltip"]').tooltip();

        $('#all_leave_table').DataTable({
            pageLength: 25
        });

        $('.years').datetimepicker({
            //inline: true,
            format: 'YYYY',
            //sideBySide: true
        });

        $('#start_date, #end_date').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD',
            //minDate: $.now()
        })
        .on('dp.change', function(e){ check_available_leave() });;

        $(document).on('change', 'input[type="file"]', function(e) { 
            var fileName = e.target.files[0].name;
            $(this).parent().parent().removeClass('fileinput-new');
            $(this).parent().parent().addClass('fileinput-exists');

            $(this).attr('name', $(this).parent().find('input[type="hidden"]').attr('name'));
            $(this).parent().find('input[type="hidden"]').attr('name', '');
            $(this).parent().parent().find('.fileinput-filename').html(fileName);
        });

        $(document).on('click', '.close', function() { 

            $(this).parent().removeClass('fileinput-exists');
            $(this).parent().addClass('fileinput-new');

            $(this).parent().find('input[type="hidden"]').attr('name', $(this).parent().find('input[type="file"]').attr('name'));
            $(this).parent().find('input[type="file"]').attr('name', '');
            $(this).parent().find('.fileinput-filename').html('');

            $(this).parent().find('input[type="file"]').reset();
        });

        $(document).on('click', 'input[type="file"]', function() { 
            if($(this).attr('name') != '')
            {
                $(this).parent().parent().removeClass('fileinput-exists');
                $(this).parent().parent().addClass('fileinput-new');

                $(this).parent().parent().find('input[type="hidden"]').attr('name', $(this).parent().find('input[type="file"]').attr('name'));
                $(this).parent().parent().find('input[type="file"]').attr('name', '');
                $(this).parent().parent().find('.fileinput-filename').html('');

                $(this).reset();
            }
        });

        $('.change_status a').click(function() {
            $.ajax({
                url: $(this).attr('data-href'),
                data: { },
                dataType: "json",
                success: function( data ) {
                    $('#myModal .modal-content').html(data.data);
                    $('#_token').val($('meta[name="csrf-token"]').attr('content'));
                }
            });
        });

    });

    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more").click(function () {

            var add_new = $('<div class="form-group" style="margin-bottom: 0px">\n\
                                    <label for="field-1" class="col-sm-4 control-label">Attachment</label>\n\
                        <div class="col-sm-5">\n\
                        <div class="fileinput fileinput-new" data-provides="fileinput">\n\
                <span class="btn btn-default btn-file"><span class="fileinput-new" >Select file</span><span class="fileinput-exists" >Change</span><input type="hidden" value="" name="upload_file[]"><input type="file" name=""></span> <span class="fileinput-filename"></span><a href="javascript::undefined" class="close fileinput-exists" data-dismiss="fileinput" style="float: none;">&times;</a></div></div>\n\<div class="col-sm-3">\n\<strong>\n\
                <a href="javascript:void(0);" class="remCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div>');
            maxAppend++;
            $("#add_new").append(add_new);
        });

        $("#add_new").on('click', '.remCF', function () {
            $(this).parent().parent().parent().remove();
        });
        $('a.RCF').click(function () {
            $(this).parent().parent().remove();
        });
    });

    function check_available_leave()
    {
        var leave_category_id = $.trim($("#leave_category").val());
        var user_id = $.trim($("form [name=user_id]").val());
        console.log(user_id);
        var start_date = $.trim($("#start_date").val());
        var end_date = $.trim($("#end_date").val());
        if(end_date == '' ){

            end_date = start_date;

        }

        var time_off_start = $('form input#time_off_start').val();
        var time_off_end = $('form input#time_off_end').val();

        $('#username_result').html('');
        $('#date_validate').html('');
        //alert(leave_category_id+' '+start_date+' '+end_date);
        let d1 = new Date(start_date).getTime();
        let d2 = new Date(end_date).getTime();


        if(d1 > d2){
            $('#date_validate').html('Start Date Cannot Be greater than end date');
            document.getElementById('sbtn').disabled = true;
        }else{
            document.getElementById('sbtn').disabled = false;
        }
        if(leave_category_id != '' && start_date != '' && end_date != '')
        {

            let request = {

                user_id:user_id, start_date:start_date, 
                end_date:end_date, 
                leave_category_id:leave_category_id,
                start_time: time_off_start,
                end_time: time_off_end
            }

            console.log(request);

            $.ajax({
                url: "/admin/check_user_available_leave",
                data: request,
                dataType: "json",
                success: function( data ) {
                    var result = data.msg;
                    document.getElementById('username_result').innerHTML = result;

                    var msg = result.trim();
                    if (msg) {
                        document.getElementById('sbtn').disabled = true;
                    } else {
                        document.getElementById('sbtn').disabled = false;
                    }
                }
            });
        }
    }

    $(document).on('change', '.datepicker, #leave_category', function() { 
        check_available_leave();
    });

  



    $('.searchable').select2();

    $('#dep_id').change(function(){
        var depid = $(this).val();
        if(depid.trim() == ''){
            depid = 'all';
        }
        $.get(`/admin/usersbydep/${depid}`,function(res){
            let users = res.users;
            var options = '<option value="">Select Users</option>';
            $('.searchable').select2('destroy');
            for(let u of users){
                options = options + `<option value='${u.id}'>${u.username}(#${u.id})</option>`
            }
            $('#leaveusers').html(options);
            $('.searchable').select2();
        }).fail(function(){
            alert("Failed To Load");
        });
    });




    $('#leave_category').change(function(){



        if( $(this).val()  == '{{ env('TIME_OFF_ID') }}'){

            $('#time_off_toggle').html($('#TimeOff').html());
            $('#partOfDay').hide();
            $('#end_date_div').hide();
            $('#end_date').val( $('#start_date').val() );
            $('.timepicker').datetimepicker({
            //inline: true,
                 format: 'HH:mm',

            //minDate: $.now()
            }).on('dp.change', function (e) {  

                check_available_leave();

            });;
        }else{
           
            $('#partOfDay').show();
            $('#end_date_div').show();
        }


    });

    $('form #leaveusers').change(function(){


    let uid = $(this).val();

    if(!uid){

        $('#request_to').html('<option value="">Selet Line Manager</option>');
    }


    $.get(`/admin/get_line_manager/${uid}`,function(response){

        $('#request_to').html(`<option value="${response.first_line_mananger.id}">${response.first_line_mananger.first_name} ${response.first_line_mananger.last_name}</option>`);


    });


    });

$('select#leaveusers').change(function(){

    let userId = $(this).val();
    $.get('/admin/get_user_leave_status/'+userId,function(response){
        let html = response.html;
        $('#leaveStatus').html(html);


    });


})

</script>




@endsection
