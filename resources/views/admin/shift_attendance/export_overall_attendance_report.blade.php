
<table>
    <tr>
        <th style="text-align: center;" align="center" colspan="15">
            <b>{{env('APP_COMPANY')}}</b>
        </th>
    </tr>
    <tr>
        <th style="text-align: center;" align="center" colspan="15">
            <b>{{\Auth::user()->organization->address}}</b>
        </th>
    </tr>
    <tr ><td colspan="15" align="center"><b>Overall Attendance Report</b></td></tr>
    @if($division)
    <tr ><td colspan="15" align="center"><b>{{$division->name}}</b></td></tr>
    @endif
    @if($start_date&&$end_date)
        <tr>
            <td colspan="15" align="center"><b>From {{$start_date}} To {{$end_date}}</b></td>
        </tr>
    @endif
    <tr>
        <td colspan="15">Report Date: {{date('Y-m-d')}}</td>
    </tr>
</table>

<?php
$begin = new DateTime($start_date);
$end = new DateTime($end_date);
$end->add(new \DateInterval('P1D'));
$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);
$cal = new \App\Helpers\NepaliCalendar();
?>
<table id="" class="table table-bordered std_table table-striped table-hover">
    <thead>
    <tr class="bg-success">
        <th style="border:1px solid black;min-width: 70px">Emp Id</th>
        <th style="border:1px solid black;min-width: 140px">Emp Name</th>
        @foreach ($period as $dt)
            <?php
            $engdate = $dt->format("Y-m-d");
            $nepdate = $cal->formated_nepali_date($engdate);

            $weekends= Config::get('hrm.weekends');


            ?>
            <td class="std_p" title="{{ date('l',strtotime($engdate)) }}" style="border:1px solid black;
    text-align: center;">{{$dt->format("d M") }}/ {{ $nepdate }}
                <br>
                <div>{{date('D', strtotime($engdate))}}</div>
            </td>
        @endforeach
    </tr>
    </thead>
    <tbody>
    <?php $flag = 0; ?>
    @foreach($attendance as $ak => $av)
        <tr>
            <?php

            $userAtt = \AttendanceHelper::getUserOverallAttendanceHistroy($av->user_id, $start_date,$end_date);
            ?>
            <td style="border:1px solid black;text-align: center;color: blue">#{{ $av->emp_id }}</td>
            <td style="border:1px solid black;">{{ $av->first_name.' '.$av->last_name }}</td>
            @foreach ($period as $dt)


                <?php
                $currentDate =  $dt->format("Y-m-d");
                $checkHoliday = $holidays->where('start_date','<=',$currentDate)
                    ->where('end_date','>=',$currentDate)
                    ->first();
                $checkLeave = \AttendanceHelper::checkUserLeave($av->user_id,$currentDate);

                $checkPresent = count($userAtt->where('date',$currentDate));
                $user_attendance=$userAtt->where('date',$currentDate);

                ?>

                @if(in_array(date('l',strtotime($currentDate)),$weekends ))

                    <td style="border:1px solid black;text-align: center" title="{{date('l',strtotime($currentDate))}}" data-toggle="tooltip" data-placement="top">
                        <span style="padding:2px;" class="label label-primary std_p">W</span>
                    </td>
                @elseif($checkHoliday)
                    <td style="border:1px solid black;text-align: center" title="{{$checkHoliday->event_name}}" data-toggle="tooltip" data-placement="top">
                        <span style="padding:2px;" class="label label-info std_p">H</span>
                    </td>
                @elseif($checkLeave)
                    <td style="border:1px solid black;text-align: center" title="{{ $checkLeave->reason }}" data-toggle="tooltip" data-placement="top">
                        <span style="padding:2px;" class="label label-warning std_p">L</span>
                    </td>
                @elseif($checkPresent > 0)

                    @if($checkPresent>1)
                        <td  style="border:1px solid black;text-align: center" data-toggle="tooltip" data-placement="top">
                            <?php
                            $in_time=$user_attendance->where('attendance_status',1)->first()->time;
                            $out_time=$user_attendance->where('attendance_status',2)->first()->time;
                            ?>
                            <div>
                                {{date('h:i A',strtotime($in_time))}}
                            </div>
                                <br>
                            <div>
                                {{date('h:i A',strtotime($out_time))}}
                            </div>
                                <br>
                            <div>
                                <?php
                                $date_a = new DateTime($in_time);
                                $date_b = new DateTime($out_time);

                                $interval = date_diff($date_a,$date_b);

                                $time_dff= $interval->format('%h hr %i m');
                                ?>
                                {{$time_dff}}
                            </div>
                            {{--                                             <span style="padding:2px;" class="label label-success std_p">P--}}
                            {{--                                            </span>--}}
                        </td>
                    @else
                        <td style="border:1px solid black;text-align: center" data-toggle="tooltip" data-placement="top">
                            <div>
                                {{date('h:i A',strtotime($user_attendance->where('attendance_status',1)->first()->time))}}
                            </div>
                            {{--                                             <span style="padding:2px;" class="label label-warning std_p">P</span>--}}
                        </td>
                    @endif

                @else

                    <td style="border:1px solid black;text-align: center">
                        <span style="padding:2px;" class="label label-danger std_p">A</span>
                    </td>


                @endif


            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>

