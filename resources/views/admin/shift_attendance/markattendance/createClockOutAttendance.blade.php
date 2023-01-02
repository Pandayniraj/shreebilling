<style type="text/css">
    .tooltip {
    z-index: 100000000;
}
</style>
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">


<div class="modal-content">
    <div id="printableArea">
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">Clock Out
                <small><span id='attendanceUserId'
                data-value='{{ $report['user']->id }}'></span>
                Clock out {{ $report ['user']->first_name }} {{ $report ['user']->last_name }}</small>
                <div class="pull-right ">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </h4>
        </div>
        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-body wrap-modal wrap">

                    <form class="form-horizontal" action="{{route('admin.shiftAttendance.make.user.clockout')}}" method="post" autocomplete="off">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{ $userid }}">
                        <input type="hidden" name="shift_id" value="{{$report['shift']->id}}">
                        <input type="hidden" name="date" value="{{$date}}">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Time:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control fixdatetimepicker" placeholder="Enter the time to fix" value="{{$date.' '.date("H:i:s",strtotime($report['shift']['end_time']))}}" name="time">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Reason:</label>
                            <div class="col-sm-10">
                                <textarea type="text" class="form-control" placeholder="Enter the reason" name="reason" required=""></textarea>
                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary submit-button">Apply Change</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>
</div>
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>

<script type="text/javascript">
    // let check = moment(newValue, "YYYY-MM-DD H:m:s", true).isValid();
    // console.log( check)
    // $('.fixdatetimepicker').each(function(){
    //     $(this).editable({

    //         success: function(response, newValue) {
    //             let check = moment(newValue, "YYYY-MM-DD H:m:s", true).isValid()
    //             if(check){
    //                 let action = `/admin/shiftAttendanceFix/${thisid}`;
    //                 $('#attendaceFix form').attr('action',action);
    //                 updatefix(newValue,false);
    //             }
    //         },
    //         validate: function(value) {
    //             let check = moment(value, "YYYY-MM-DD H:m:s", true).isValid()
    //             if (!check) {
    //                 return 'Please Enter date in YYYY-MM-DD H:m:s Format';
    //             }
    //         }
    //     });
    // $('[data-toggle="popover"]').popover();

    // });

</script>

