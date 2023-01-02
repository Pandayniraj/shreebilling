
<style>
    .panel-custom .panel-heading {
        margin-bottom: 10px;
    }

    form{
        margin-top: -15px;
    }
    th,td{
        font-size: 11px;
        border: 1px solid grey;
    }
    table{
        border-collapse: collapse;
    }
</style>


@if($attendance)
<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Attendance List of {{ date('d F-Y', strtotime($start_date)) }} to
                        {{ date('d F-Y', strtotime($end_date)) }}
                        <div class="pull-right hidden-print">
                        </div>
                    </h3>
                </div>
                <?php
                    $begin = new DateTime($start_date);
                    $end = new DateTime($end_date);
                    $end->add(new \DateInterval('P1D'));
                    $interval = DateInterval::createFromDateString('1 day');
                    $period = new DatePeriod($begin, $interval, $end);
                    $cal = new \App\Helpers\NepaliCalendar();
                ?>
                <div class="table-responsive">
                <table id="" class="table table-bordered std_table table-striped table-hover">
                    <thead>
                    <tr class="bg-success">
                        <th style="min-width: 70px">Emp Id</th>
                        <th style="min-width: 140px">Emp Name</th>
                        @foreach ($period as $dt)
                        <?php
                            $engdate = $dt->format("Y-m-d");
                            $nepdate = $cal->formated_nepali_from_eng_date($engdate);

                            $weekends= Config::get('hrm.weekends');


                        ?>
                        <td class="std_p" title="{{ date('l',strtotime($engdate)) }}" style="min-width: 70px;
    text-align: center;">{{$dt->format("d, M") }}
                            <div>{{date('D', strtotime($engdate))}}</div>
{{--                            /{{ $nepdate }}--}}
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
                                <td style="text-align: center;color: blue">#{{ $av->emp_id }}</td>
                                <td >{{ $av->first_name.' '.$av->last_name }}</td>
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

                                    <td style="text-align: center" title="{{date('l',strtotime($currentDate))}}" data-toggle="tooltip" data-placement="top">
                                        <span style="padding:2px;" class="label label-primary std_p">W</span>
                                    </td>
                                    @elseif($checkHoliday)
                                    <td style="text-align: center" title="{{$checkHoliday->event_name}}" data-toggle="tooltip" data-placement="top">
                                        <span style="padding:2px;" class="label label-info std_p">H</span>
                                    </td>
                                    @elseif($checkLeave)
                                    <td style="text-align: center" title="{{ $checkLeave->reason }}" data-toggle="tooltip" data-placement="top">
                                         <span style="padding:2px;" class="label label-warning std_p">L</span>
                                    </td>
                                    @elseif($checkPresent > 0)

                                        @if($checkPresent>1)
                                        <td  style="text-align: center" data-toggle="tooltip" data-placement="top">
                                            <?php
                                            $in_time=$user_attendance->where('attendance_status',1)->first()->time;
                                            $out_time=$user_attendance->where('attendance_status',2)->first()->time;
                                                    ?>
                                            <div>
                                                {{date('h:i A',strtotime($in_time))}}
                                            </div>
                                            <div>
                                                {{date('h:i A',strtotime($out_time))}}
                                            </div>
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
                                        <td style="text-align: center" data-toggle="tooltip" data-placement="top" title="Present But No clockout">
                                            <div>
                                                {{date('h:i A',strtotime($user_attendance->where('attendance_status',1)->first()->time))}}
                                            </div>
{{--                                             <span style="padding:2px;" class="label label-warning std_p">P</span>--}}
                                        </td>
                                        @endif

                                    @else

                                    <td style="text-align: center">
                                        <span style="padding:2px;" class="label label-danger std_p">A</span>
                                    </td>


                                    @endif


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

{{--<div class="row">--}}
{{--    <div class="col-md-4">--}}
{{--        <table class="table table-striped">--}}
{{--            <tr style="background-color: #dff0d8">--}}
{{--                <th>Symbol</th>--}}
{{--                <th>Meaning</th>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <td><button class="btn btn-primary btn-block  btn-xs">W</button></td>--}}
{{--                <th>Weekend</th>--}}
{{--            </tr>--}}
{{--             <tr>--}}
{{--                <td><button class="btn btn-info btn-block  btn-xs">H</button></td>--}}
{{--                <th>Holiday</th>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <td><button class="btn btn-block btn-warning  btn-xs">P</button></td>--}}
{{--                <th>Present without clockout</th>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <td><button class="btn btn-success btn-block  btn-xs">P</button></td>--}}
{{--                <th>Present</th>--}}
{{--            </tr>--}}
{{--              <tr>--}}
{{--                <td><button class="btn btn-warning btn-block  btn-xs">L</button></td>--}}
{{--                <th>On Leave</th>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <td><button class="btn btn-danger btn-block  btn-xs">L</button></td>--}}
{{--                <th>Absent</th>--}}
{{--            </tr>--}}
{{--        </table>--}}
{{--    </div>--}}
{{--</div>--}}
@endif
