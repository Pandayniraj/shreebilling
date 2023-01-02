<style type="text/css">
    .tooltip {
    z-index: 100000000; 
}
</style>

<div class="modal-content">
    <div id="printableArea">
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">Attendence Report
                <small><span id='attendanceUserId'
                data-value='{{ $report['user']->id }}'></span>
                Showing attendance report of {{ $report ['user']->first_name }} {{ $report ['user']->last_name }}</small>
                <div class="pull-right ">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </h4>
        </div>
        <div class="modal-body wrap-modal wrap">

            <table class="table table-striped ">
                <thead>
                    <tr>
                        <th class="bg-primary" colspan="2">Basic Info</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Date</th>
                        <td><span id='attendanceDate'
                            data-value='{{$date}}'>{{ date('dS M Y',strtotime($date)) }}</span></td>
                    </tr>
                    <tr>
                        <th>User Name</th>
                        <td>{{ $report['user']->first_name }} {{ $report ['user']->last_name }}</td>
                    </tr>
                     <tr>
                        <th>Degination</th>
                        <td>{{ $report['user']->designation->designations }}</td>
                    </tr>
                    <tr>
                        <th>Shift Name</th>
                        <td>
                        <span id='attendanceShiftId' data-value={{ $report['shift']->id}} >
                            {{ $report['shift']->shift_name }}
                        </span></td>
                    </tr>
                    <tr>
                        <th>Shift Start time</th>
                        <td>{{  $report['shift']->shift_time }}</td>
                    </tr>
                    <tr>
                        <th>Shift End time</th>
                        <td>{{  $report['shift']->end_time }}</td>
                    </tr>
                    <tr>
                        <th>Total Shift Time</th>
                        <td>{{  $report['requiredworkTime'] ? TaskHelper::minutesToHours($report['requiredworkTime']).' Hrs' : '-'  }} /
                        {{  $report['officeTime'] ? TaskHelper::minutesToHours($report['officeTime']).' Hrs' : '-'  }}</td>
                    </tr>
                </tbody>
            </table>


            <table class="table table-striped ">
                <thead>
                   <tr>
                        <th class="bg-yellow" colspan="3">Break Time</th>
                    </tr>
                    <tr>
                        <th>Break Name</th> 
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $breakTime = $report['breakduration']['breakInfo'];
                    ?>
                    @foreach($breakTime as $b=>$break)
                    <tr>
                        <td> {{$break['name']}} <i class="{{$break['icon']}}"></td>
                        <td> {{ $break['start']}}  </td>
                        <td>  {{ $break['end']}} </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <th colspan="2" style="text-align: right;">Total:</th>
                    <td> {{ $report['breakduration']['formatted'] }} </td>
                </tfoot>
            </table>


             <table class="table table-striped ">
                <thead>
                   <tr>
                        <th class="bg-green" colspan="7">Time Report</th>
                    </tr>
                    <tr>
                        <th>Start Time</th> 
                        <th>End Time</th>
                        <th>Duration</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Source</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $timeHistroy = $report['summary']['timeDifference'];
                    ?>
                    @foreach($timeHistroy as $b=>$histroy)
                    <tr @if($histroy['remark']) class="bg-maroon"   title="Adjusted Without Clockin "@endif>
                        <td> <span class="clock_in_time" data-id='{{$histroy['id']}}'>{{ $histroy['start'] }} </span></td>

                        <td @if($histroy['endArr']) 
                        ata-container=".modal-body" data-toggle="popover" data-placement="bottom" data-html="true" data-trigger="hover" data-title='Out Info' data-content="<b>Location:</b> {{ $histroy['endArr']['location']->formatted_address }}  <br><b>Source: {{ $histroy['endArr']['source'] }} </b>" 


                        @endif>
  
                         {{ $histroy['end'] }} </td>

                        <td >  {{ \TaskHelper::minutesToHours($histroy['duration']) }} Hrs </td>
                        <td > {{ ucfirst($histroy['type']) }}  </td>
                       
                            @if($histroy['location'])
                             <td title="{{$histroy['location']->formatted_address }}">
                                 {{ $histroy['location']->street_name }}
                             </td>
                            @else
                             <td>-</td>
                            @endif

                        <td>{{$histroy['source']}}</td>
                        <td>

                           @if($editable) {{-- only hr can do this --}}
                                @if($histroy['remark'])
                                 <a href="javascript::void(0)" onclick="openattendanceFix(this)" data-type='edit' data-id='{{$histroy['id']}}'  
                                 data-value='{{$histroy['start']}}' >
                                    <i class="fa fa-edit" style="color:white;"></i>
                                </a>
                                &nbsp;
                                <a href="javascript::void(0)" onclick="openattendanceFix(this)"
                                data-type='new'>
                                    <i class="fa  fa-clock-o" style="color:white" title="Add Clock Out"></i>
                                </a>
                                @else
                                <a href="javascript::void(0)" onclick="openattendanceFix(this)" data-type='edit' data-id='{{$histroy['id']}}'  
                                 data-value='{{$histroy['start']}}' >
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endif
                            @else
                            <a href="javascript::void()" onclick="openattendanceChange(this)" data-type='edit' data-id='{{$histroy['id']}}' data-value='{{$histroy['start']}}'>
                                <i class="fa fa-edit (alias)"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" style="text-align: right;">Total Break Time:</th>
                        <td> {{ \TaskHelper::minutesToHours($report['summary']['breakTime']) }} Hrs</td>
                    </tr>
                    <tr>
                        <th colspan="4" style="text-align: right;">Total Work Time:</th>
                        <td> {{ \TaskHelper::minutesToHours($report['summary']['workTime']) }} Hrs</td>
                    </tr>
                    <tr>
                        <th colspan="4" style="text-align: right;">Total Overtime Time:</th>
                        <td> {{ $report['overTime'] ?  TaskHelper::minutesToHours($report['overTime']).' Hrs':'-'  }} </td>
                    </tr>
                   
                </tfoot>
            </table>
            
        </div>
    </div>
</div>

<script type="text/javascript">
$('.clock_in_time').each(function(){
  let parent = $(this);
  let thisid = parent.attr('data-id');
  $(this).editable({
   
    success: function(response, newValue) {
        let check = moment(newValue, "YYYY-MM-DD H:m:s", true).isValid()
        if(check){
            let action = `/admin/shiftAttendanceFix/${thisid}`;
            $('#attendaceFix form').attr('action',action);
            updatefix(newValue,false);
        }
  },
  validate: function(value) {
       let check = moment(value, "YYYY-MM-DD H:m:s", true).isValid()
        if (!check) {
            return 'Please Enter date in YYYY-MM-DD H:m:s Format';
        } 
    }
});
});
$('[data-toggle="popover"]').popover();

</script>