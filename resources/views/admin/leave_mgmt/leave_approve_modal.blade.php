<div class="modal-content">
    <div id="printableArea">
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">Leave Request
                <small>View Leave request</small>
                <div class="pull-right ">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </h4>
        </div>
        <div class="modal-body wrap-modal wrap">
            <form method="post" action="{{route('admin.leave_request.update',$leaveapp->leave_application_id) }}">
                {{ csrf_field() }}
            <table class="table table-striped">
                <thead>
                    <tr class="bg-primary">
                        <th colspan="2">Leave Request Info</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Employee Name:</th>
                        <td>{{ $leaveapp->user->first_name }} {{ $leaveapp->user->last_name }}</td>
                    </tr>

                    <tr>
                        <th>Attendance Date:</th>
                        <td>{{ date('dS M Y',strtotime($attendance->date)) }}</td>
                    </tr>

                    <tr>
                        <th>Actual Time:</th>
                        <td>{{ date('h:i A',strtotime($leaveapp->actual_time ))}}</td>
                    </tr>

                    <tr>
                        <th>Request Time:</th>
                      <td>{{ date('h:i A',strtotime($leaveapp->requested_time ))}}</td>
                    </tr>

                    <tr>
                            <th>Reason</th>
                            <td> {{ $leaveapp->reason }} </td>

                    </tr>
                 @if(\Auth::user()->canapprove_or_deny  == 1 && $leaveapp->is_forwarded == 2 && \Auth::user()->id == $forwaded->to_id)
                    <tr>
                            <th>Leave Forward Note</th>
                            <td> {!! $forwaded->note !!} </td>

                    </tr>
                     <tr>
                            <th>Leave Forward From</th>
                            <td> {!! \TaskHelper::getUserData($forwaded->from_id)->username  !!} </td>

                    </tr>
                 @endif   

                    <tr>

                        <th>Status:</th>
                        <td>
                        @if($leaveapp->application_status == 1)
                        <span class="label label-warning">Pending</span>
                        @elseif($leaveapp->application_status == 2)
                        <span class="label label-success">Accepted</span>
                        @else
                        <span class="label label-danger">Rejected</span>
                        @endif
                        </td>

                    </tr>

                    <tr>

                        @if($leaveapp->application_status == 2 || $leaveapp->application_status == 3)

                            <th>Approved By:</th>
                            <td> {{ $leaveapp->approve->first_name }}   {{ $leaveapp->approve->last_name }}</td>

                        @else
                          @if((\Auth::user()->hasRole(['admins','hr-staff']))||(\Auth::user()->canapprove_or_deny  == 1 && Auth::user()->id != $leaveapp->user_id && $leaveapp->is_forwarded == null ))
                            <th>Action:</th>
                            <td>

                                {!!  Form::select('application_status',['2'=>'Accept','3'=>'Reject'],$leaveapp->application_status,['class'=>'form-control'] ) !!}
                            </td>
                        @elseif(\Auth::user()->canapprove_or_deny  == 1 && $leaveapp->is_forwarded == 2 && \Auth::user()->id == $forwaded->to_id)
                       
                          <th>Action:</th>
                            <td>

                                {!!  Form::select('application_status',['2'=>'Accept','3'=>'Reject'],$leaveapp->status,['class'=>'form-control'] ) !!}
                            </td>
                        @endif
                        @endif
                    </tr>

                </tbody>


            </table>


                   <div class="row">
                    <div class="col-md-12">
                        <div class="form-group pull-right">
                            @if($leaveapp->application_status == 1)
                               @if((\Auth::user()->hasRole(['admins','hr-staff']))||(\Auth::user()->canapprove_or_deny  == 1 && \Auth::user()->id != $leaveapp->user_id && $leaveapp->is_forwarded == null ))
                               <input value="Update" type="submit"  class="btn btn-primary">
                             @else
                              @if(\Auth::user()->canapprove_or_deny  == 1 && $leaveapp->is_forwarded == 2 && \Auth::user()->id == $forwaded->to_id)
                                 <input value="Update" type="submit"  class="btn btn-primary">
                                @else
                                 <input value="Update" {{'disabled'}} type="submit"  class="btn btn-primary">
                              @endif   
                            @endif
                            @endif
                        <button  type="button"  class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                        </div>
                        </div>
                 </div>

            </form>
        </div>
    </div>
</div>
