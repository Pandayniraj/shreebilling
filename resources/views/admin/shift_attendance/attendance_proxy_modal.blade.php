<div class="modal-content">
    <div id="printableArea">
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">Attendance {{$page_title}}
                <small>View Attendance {{$page_title}}</small>
                <div class="pull-right ">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </h4>
        </div>
        <div class="modal-body wrap-modal wrap">
            <form method="post" action="{{route('admin.attendance_request_proxy.update',$timechange->id) }}">
                {{ csrf_field() }}
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="2">
                            <img src="{{ $timechange->user->image?'/images/profiles/'.$timechange->user->image:$timechange->user->avatar, }}" alt="User Image" width="50px" height="50px" style="border-radius: 50%;max-width: 100%;height: auto;">
                            {{ $timechange->user->first_name }} {{ $timechange->user->last_name }}
                        </th>
                    </tr>
                    <tr>
                        <th>Employee Name:</th>
                        <td colspan="2">{{ $timechange->user->first_name }} {{ $timechange->user->last_name }}</td>
                    </tr>

                </thead>
                <tbody>


                    <tr>
                        <th>Date</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>Reason</th>
                    </tr>
                    @foreach($childInfo as $child)

                        <tr>
                            <td>{{$child->date}}</td>
                            <td>{{$child->clock_time}}</td>
                            <td>{{$child->end_time}}</td>
                            <td>{{$child->reason}}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                <tr>

                    <th>Status:</th>
                    <td colspan="3">
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

                    @if($timechange->status == 2 || $timechange->status == 3)

                        <th>Approved By:</th>
                        <td colspan="3"> {{ $timechange->approvedBy->first_name }}   {{ $timechange->approvedBy->last_name }}</td>

                    @else

                        <th>Action:</th>
                        <td colspan="3">

                            {!!  Form::select('status',['2'=>'Accept','3'=>'Reject'],$timechange->status,['class'=>'form-control'] ) !!}
                        </td>
                    @endif
                </tr>

                </tfoot>


            </table>


                   <div class="row">
                    <div class="col-md-12">
                        @if($timechange->is_forwarded == 0 && !\Auth::user()->hasRole(['admins','hr-staff']))

                        <div class="form-group pull-left">
                            <a class="btn btn-success" href="{{route('admin.attendance_request_proxy.forward',$timechange->id) }}">Forward</a>
                        </div>
                        @endif

                        <div class="form-group pull-right">
                            @if($timechange->status == 1)
                            {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                            @endif
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                        </div>
                        </div>
                 </div>

            </form>
        </div>
    </div>
</div>
