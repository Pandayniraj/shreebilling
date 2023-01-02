@extends('layouts.master')
@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')
@endsection

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
   
</style>
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
   <h1>
      {{ $page_title ?? 'Page Title' }}
      <small>{{$page_description ?? 'Page Description'}}</small>
   </h1>
   <a href="/admin/employeeRequest/" class="btn btn-primary btn-xs" style="margin-top: 3px;"> << Back</a>
</section>

<div class="col-md-8">
   <div class="tab-content pl0 box">
      <div class="tab-pane active" id="appli_detail" style="position: relative;">
         <div class="row">
            <div class="col-sm-12">
               <div class="panel panel-custom">
                  <!-- Default panel contents -->
                  <div class="panel-heading">
                     <div class="panel-title">
                        <strong><span title="{{$empRequest->user->username}}">
                            {{$empRequest->user->first_name}}  {{$empRequest->user->last_name}} 
                            [{{$empRequest->user->id}}]</span> Request for &nbsp;<span class="text-danger">{{$request_types[$empRequest->request_type]}} </span> 
                        </strong>
                     </div>
                  </div>
                  <div class="panel-body row form-horizontal task_details">
                     <div class="r9 ribbon {{$status_color[$empRequest->status]}}"><span> 
                        {{$request_status[$empRequest->status]}} </span></div>
                     <div class="form-group ">
                        <label class="control-label col-sm-4"><strong>Title :</strong></label>
                        <div class="col-sm-8">
                           <p class="form-control-static ">{{$empRequest->title}}</p>
                        </div>
                     </div>
                     <div class="form-group ">
                        <label class="control-label col-sm-4"><strong>Request Type: </strong></label>
                        <div class="col-sm-8">
                           <p class="form-control-static ">
                            {{$request_types[$empRequest->request_type]}}</p>
                        </div>
                     </div>
                     @if($empRequest->request_type == 'festival')
                     <div class="form-group ">
                        <label class="control-label col-sm-4"><strong>Benefit Type: </strong></label>
                        <div class="col-sm-8">
                           <p class="form-control-static ">
                               {{$benefit_types[$empRequest->benefit_type]}}
                           </p>
                        </div>
                     </div>
                     <div class="form-group ">
                        <label class="control-label col-sm-4"><strong>Pay Type:</strong></label>
                        <div class="col-sm-8">
                           <p class="form-control-static ">
                            {{$pay_type[$empRequest->pay_type]}}</p>
                        </div>
                     </div>
                      <div class="form-group ">
                        <label class="control-label col-sm-4"><strong>Cost:</strong></label>
                        <div class="col-sm-8">
                           <p class="form-control-static ">
                               NPR. {{ $empRequest->cost }}
                            </p>
                        </div>
                     </div>
                     @if($empRequest->attachment)
                      <div class="form-group ">
                        <label class="control-label col-sm-4"><strong>Uploaded Bill:</strong></label>
                        <div class="col-sm-8">
                           <p class="form-control-static ">
                            <a href="{{ $empRequest->attachment }}__" ownload="{{ $empRequest->attachment }}"><i class='fa fa-download'></i> Download file</a></p>
                        </div>
                     </div>
                     @endif
                     @endif
                     
                     <div class="form-group ">
                        <label class="control-label col-sm-4"><strong>Description:</strong></label>
                        <div class="col-sm-8">
                           <blockquote style="font-size: 13px; margin-top: 5px">
                            {{$empRequest->description}}</blockquote>
                        </div>
                     </div>
                     <div class="form-group ">
                        <label class="control-label col-sm-4"><strong>Request Date:</strong></label>
                        <div class="col-sm-8">
                           <p class="form-control-static ">
                               {{ date('dS Y M',strtotime($empRequest->created_at)) }}
                            </p>
                        </div>
                     </div>
                     @if($empRequest->status == 'approve' || $empRequest->status == 'cancel')
                      <div class="form-group ">
                        <label class="control-label col-sm-4"><strong>Approved By</strong></label>
                        <div class="col-sm-8">
                           <p class="form-control-static " title="{{$empRequest->approvedUser->username}}">
                               {{ $empRequest->approvedUser->first_name }} 
                              &nbsp;{{ $empRequest->approvedUser->last_name }} 
                            </p>
                        </div>
                     </div>

                     <div class="form-group ">
                        <label class="control-label col-sm-4"><strong>Comment:</strong></label>
                        <div class="col-sm-8">
                           <blockquote style="font-size: 13px; margin-top: 5px">
                            {{$empRequest->comment}}</blockquote>
                        </div>
                     </div>
                     <div class="form-group ">
                        <label class="control-label col-sm-4"><strong>Approve Team</strong></label>
                        <div class="col-sm-8">
                           <p class="form-control-static " t>
                               {{ $empRequest->team->name }} 
                              
                            </p>
                        </div>
                     </div>
                     @else
                     <div class="form-group ">
                        <label class="control-label col-sm-4"><strong>Change Status:</strong></label>
                        <div class="col-sm-8">
                           <p class="form-control-static change_status ">
                              <span data-toggle="tooltip" data-placment="top" title="" data-original-title="You are about to approved the record. This cannot be undone. Are you sure?">
                              <a data-toggle="modal" data-target="#myModal" 
                              href="javascript:undefined"  class="btn btn-success ml" data-value='1'><i class="fa fa-thumbs-o-up"  ></i>Approved</a>
                              </span>
                              <a data-toggle="modal" data-target="#myModal" href="javascript:undefined" class="btn btn-danger ml"
                              data-value='2' ><i class="fa fa-times"></i> Rejected</a>
                           </p>
                        </div>
                     </div>
                     @endif
                  </div>
               </div>
            </div>
     
         </div>
      </div>
    
    
     
   </div>
</div>
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="panel panel-custom">
                    <div class="panel-heading">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Change Status To 
                          <span id='tochange_status_name'></span></h4>
                    </div>
                    <div class="modal-body wrap-modal wrap">
                        <form id="form_validation" 
                        action="{{route('admin.employeeRequest.accept_reject',$empRequest->id)}}" method="post" class="form-horizontal form-groups-bordered">
                              {{ csrf_field() }}
                              <input type="hidden" name="status" id='request_status'>
                            <div class="form-group ">
                                <label for="field-1" class="col-sm-3 control-label row">Give Comment: </label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="comment"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.change_status a').click(function() {
  
        if($(this).attr('data-value') == '1' ){
          $('#myModal #tochange_status_name').text("Approved");
          $('#myModal #request_status').val("approve");
        }else{
          $('#myModal #tochange_status_name').text("Rejected");
          $('#myModal #request_status').val("cancel");
        }  

    });
</script>
@endsection