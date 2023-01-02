
<div class="modal-header">
    <h4 class="modal-title">
         Adjust Attendance
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<form action="{{route('admin.attendance_request_proxy.create') }}" method="POST">
    <div class="modal-body">
        <p> @if(isset($error) && $error)
            <div>{{{ $error }}}</div>
            @endif</p>
                {{ csrf_field() }}
                <input type="hidden" name="user_id" value="{{\Auth::user()->id}}">
                <input type="hidden" name="shift_id" value="{{$shift->id}}">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th colspan="12">

                             <div class="product-img" style="padding-right: 5px">
                                <img class="direct-chat-img" src="{{ \TaskHelper::getProfileImage(\Auth::user()->firstLineManger->id) }}" alt="Product Image">
                            </div> &nbsp;

                            <span class="text text-success" title="Success"><i class="fa fa-check-circle"></i> &nbsp;</span>{{\Auth::user()->firstLineManger->fullname}}&nbsp; will be notified about this adjustment request. </th>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <th>Clock IN</th>
                        <th>Reason(in)</th>
                        <th>Clock OUT</th>
                        <th>Reason(out)</th>
                        <th>#</th>
                    </tr>
                    </thead>
                    <tbody>
                 
                    @foreach($missing_details_without_request as $clockin)
                    <tr>
                        <input type="hidden" name="attendance_id[]" value="">
                        <input type="hidden" name="attendance_status[]" value="1">
                        <input type="hidden" name="shift_id[]" value="{{$shift->id}}">

                        <td><input type="text" class="form-control clock_date" name="date[]" value="{{date('Y-m-d',strtotime($clockin))}}" readonly></td>
                        <td>
                                <span style="width: 15%; float: left;" class="text text-danger" title="Missing"><i class="fa fa-circle"></i></span>
                            <input type="text" class="form-control clock_in"  name="clock_in[]" value="{{date('H:i:s',strtotime($shift->shift_time))}}" style="width: 80%; float: left;" required>
                        </td>
                         <td><input type="text" class="form-control" name="reason1[]" required=""></td>

                        <td>
                            <span style="width: 15%; float: left;" class="text text-danger" title="Missing"><i class="fa fa-circle"></i></span>
                            <input type="text" class="form-control clock_out" name="clock_out[]" value="{{date('H:i:s',strtotime($shift->end_time))}}" style="width: 80%; float: left;" required>
                        </td>
                        <td><input type="text" class="form-control" name="reason[]" required=""></td>
                        <td><a href="javascript::void(1);" class="text text-danger"> <i class=" remove-this fa fa-trash"></i></a></td>
                    </tr>
                    @endforeach
                    @foreach($incomplete_clockout as $att)
                    <?php 
                     $time_test = \App\Models\ShiftAttendance::where('user_id',\Auth::user()->id)->where('date',$att)->first();
                    $clock_in = date("H:i:s",strtotime($time_test->time));
                    ?>
                    @if($time_test->attendance_status != 2)
                    <tr>
                        <input type="hidden" name="attendance_id[]" value="{{$time_test->id}}">
                        <input type="hidden" name="attendance_status[]" value="2">
                        <input type="hidden" name="shift_id[]" value="{{$shift->id}}">

                        <td><input type="text" class="form-control clock_date"  name="date[]" value="{{date('Y-m-d',strtotime($att))}}" readonly></td>
                        <td>
                            <span style="width: 15%; float: left;" class="text text-success" title="Success"><i class="fa fa-check-circle"></i></span>
                            <input type="text" class="form-control clock_in" readonly name="clock_in[]" value="{{$clock_in}}" style="width: 80%; float: left;" required>
                        </td>
                        <td>--</td>
                        <td>
                            <span style="width: 15%; float: left;" class="text text-danger" title="Missing"><i class="fa fa-circle"></i></span>
                        
                            <input type="text" class="form-control clock_out" name="clock_out[]" value="{{date('H:i:s',strtotime($shift->end_time))}}" style="width: 80%; float: left;" required>
                        </td>
                        <td><input type="text" class="form-control" name="reason[]" required=""></td>
                        <td><a href="javascript::void(1);" class="text text-danger"> <i class=" remove-this fa fa-trash"></i></a></td>

                    </tr>
                    @else
                     <tr>
                        <input type="hidden" name="attendance_id[]" value="{{$time_test->id}}">
                        <input type="hidden" name="attendance_status[]" value="3">
                        <input type="hidden" name="shift_id[]" value="{{$shift->id}}">

                        <td><input type="text" class="form-control clock_date"  name="date[]" value="{{date('Y-m-d',strtotime($att))}}" readonly></td>
                        <td>
                         
                         <span style="width: 15%; float: left;" class="text text-danger" title="Missing"><i class="fa fa-circle"></i></span>
                          
                       <input type="text" class="form-control clock_in"  name="clock_in[]" value="{{date('H:i:s',strtotime($shift->shift_time))}}" style="width: 80%; float: left;" required>
                        </td>
                        <td><input type="text" class="form-control" name="reason1[]" required="">
                        <td>
                           
                            <span style="width: 15%; float: left;" class="text text-success" title="Success"><i class="fa fa-check-circle"></i></span>
                            <input type="text" class="form-control clock_out" name="clock_out[]" value="{{$clock_in}}" style="width: 80%; float: left;" readonly="">
                        </td>
                        <td>-</td>
                        <td><a href="javascript::void(1);" class="text text-danger"> <i class=" remove-this fa fa-trash"></i></a></td>

                    </tr>
                    @endif
                    @endforeach

                    </tbody>

                </table>



    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn  btn-default" data-dismiss="modal">{{ trans('general.button.cancel') }}</button>
        <button type="submit" class="btn  btn-primary">{{ trans('general.button.ok') }}</button>
    </div>
</form>
<script>

    const dateRange = {
        <?php $currentFiscalyear = FinanceHelper::cur_fisc_yr();?>
        min_date: `{{ $currentFiscalyear->start_date }}`,
        max_date: `{{ $currentFiscalyear->end }}`,
    }

    $('.clock_in').datetimepicker({
            format: 'HH:mm:ss',
            sideBySide: true,
            useCurrent: false,
            defaultDate: '{{date('Y-m-d H:i:s',strtotime($shift->shift_time))}}'

        });
    $('.clock_out').click(function() {
        $(this).datetimepicker({
            format: 'HH:mm:ss',
            sideBySide: true,
            useCurrent: false,
            defaultDate: '{{date('Y-m-d H:i:s',strtotime($shift->end_time))}}'

        });

    });



    $(document).on('click', '.remove-this', function () {
        $(this).parent().parent().parent().remove();
    });
</script>

<script type="text/javascript">
   $(document).ready( function() {
         $('.modal-dialog').addClass( 'modal-lg' );
   }); 
</script>