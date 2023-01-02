<div class="modal-content">
    <div id="printableArea">
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">Time Change Request Forward
                <small>View Time Change request Forward</small>
                <div class="pull-right ">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </h4>
        </div>
        <div class="modal-body wrap-modal wrap">
            <form method="post" action="{{route('admin.timechange_request_forward.update',$timechange->id)}}">
                {{ csrf_field() }}
            <table class="table table-striped">
                <thead>
                    <tr class="bg-primary">
                        <th colspan="2">Time Change Info</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Employee Name:</th>
                        <td>{{ $timechange->user->first_name }} {{ $timechange->user->last_name }}</td>
                    </tr>

                    <tr>
                        <th>Attendance Date:</th>
                        <td>{{ date('dS M Y',strtotime($attendance->date)) }}</td>
                    </tr>

                    <tr>
                        <th>Shift Assigned:</th>
                        <td>{{ $attendance->shift->shift_name }}</td>
                    </tr>


                    <tr>
                        <th>Actual Time:</th>
                        <td>{{ date('h:i A',strtotime($timechange->actual_time ))}}</td>
                    </tr>

                    <tr>
                        <th>Request Time:</th>
                      <td>{{ date('h:i A',strtotime($timechange->requested_time ))}}</td>
                    </tr>

                    <tr>
                            <th>Reason</th>
                            <td> {{ $timechange->reason }} </td>

                    </tr>

                    <tr>

                        <th>Status:</th>
                        <td>
                        @if($timechange->status == 1)
                        <span class="label label-warning">Pending</span>
                        @elseif($timechange->status == 2)
                        <span class="label label-success">Accepted</span>
                        @else
                        <span class="label label-danger">Rejected</span>
                        @endif
                        </td>

                    </tr>
                    <tr>
                        <th>Note</th>
                        <td>
                        {!! Form::textarea('note',null,['class'=>'form-control','rows' => 2]) !!}
                        </td>
                    </tr>

                    <tr>

                        @if($timechange->status == 2 || $timechange->status == 3)

                            <th>Approved By:</th>
                            <td> {{ $timechange->approvedBy->first_name }}   {{ $timechange->approvedBy->last_name }}</td>

                        @else

                        <th>Forward to Line Manager:</th>
                        <td>
                           <select class="form-control" name="user_id">
                            @foreach($line_manager as $key=>$user)
                              <option value="{{$user->id}}">{{$user->first_name}}&nbsp;{{$user->last_name}}</option>
                            @endforeach
                           </select>
                            <!-- {!!  Form::select('user_id',['2'=>'Accept','3'=>'Reject'],$timechange->status,['class'=>'form-control'] ) !!} -->
                        </td>
                        @endif
                    </tr>

                </tbody>


            </table>


                   <div class="row">
                    <div class="col-md-12">
                     
                            @if($timechange->status == 1)
                           
                        <div class="form-group pull-right">
                            <input value="Update" type="submit"  class="btn btn-primary">
                          
                            @endif
                       
                         <button  type="button"  class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                        </div>
                    </div>
                 </div>

            </form>
        </div>
    </div>
</div>
