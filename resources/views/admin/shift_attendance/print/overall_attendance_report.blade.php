

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ env('APP_COMPANY')}} | Requisition</title>

    <!-- block from searh engines -->
    <meta name="robots" content="noindex">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Set a meta reference to the CSRF token for use in AJAX request -->
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!-- Bootstrap 3.3.4 -->
{{--    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet"--}}
{{--          type="text/css"/>--}}
{{--    <!-- Font Awesome Icons 4.7.0 -->--}}
{{--    <link href="{{ asset("/bower_components/admin-lte/font-awesome/css/all.css") }}" rel="stylesheet" type="text/css"/>--}}
{{--    <!-- Ionicons 2.0.1 -->--}}
{{--    <link href="{{ asset("/bower_components/admin-lte/ionicons/css/ionicons.min.css") }}" rel="stylesheet"--}}
{{--          type="text/css"/>--}}
{{--    <!-- Theme style -->--}}
{{--    <link href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css"/>--}}

    <!-- Application CSS-->


    <style type="text/css">
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            .text-center {
                text-align: center;
            }
            /*.item-detail td,th{*/
            /*    border:1px solid #eee;*/
            /*}*/

            .bg-gray {
                background-color: #d2d6de !important;
            }
        }

        .text-center {
            text-align: center;
        }
        /*.item-detail td,th{*/
        /*    border:1px solid #eee;*/
        /*}*/
        .bg-gray {
            background-color: #d2d6de !important;
        }

        .vendorListHeading th {
            background-color: #1a4567 !important;
            color: white !important;
        }

         .panel-custom .panel-heading {
             margin-bottom: 10px;
         }

        form{
            margin-top: -15px;
        }
        .table th,.table td{
            font-size: 10px;
            /*border: 1px solid grey;*/
        }
        table{
            border-collapse: collapse;
        }


        @page {
            size: auto;
            margin: 0;
        }

        body {
            padding-left: 1.3cm;
            padding-right: 1.3cm;
            padding-top: 1.3cm;
        }

        @media print {
            .pagebreak {
                page-break-before: always;
            }

            /* page-break-after works, as well */
        }
    </style>


</head>

<body onload="window.print();" cz-shortcut-listen="true" class="skin-blue sidebar-mini">

<table style="width: 100%">
    <tr style="text-align: center">
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
<?php $flag = 0; ?>
@foreach($attendance as $ak => $av)
<table id="" class="table table-bordered std_table table-striped table-hover" style="margin-bottom: 15px">
    <thead>
    <tr class="bg-success">
        <th style="border:1px solid black" colspan="2">S.No</th>
        <th style="border:1px solid black" colspan="2">Emp Id</th>
        <th style="border:1px solid black" colspan="4">Emp Name</th>
        <th style="border:1px solid black;min-width: 140px" colspan="4">Designation</th>
        <th style="border:1px solid black" colspan="2">Abs</th>
        <th style="border:1px solid black" colspan="2">Prs</th>
        <th style="border:1px solid black" colspan="2">Holidays</th>
        <th style="border:1px solid black" colspan="2">Leaves</th>
    </tr>
    <tr>
        <?php
        $weekends= Config::get('hrm.weekends');

        $abs_days=0;
        $prs_days=0;
        $ho_days=0;
        $leave_days=0;
        ?>
        @foreach ($period as $dt)
            <?php
            $userAtt = \AttendanceHelper::getUserOverallAttendanceHistroy($av->user_id, $start_date,$end_date);

            $currentDate =  $dt->format("Y-m-d");
            $checkHoliday = $holidays->where('start_date','<=',$currentDate)
                ->where('end_date','>=',$currentDate)
                ->first();
            $checkLeave = \AttendanceHelper::checkUserLeave($av->user_id,$currentDate);
            $checkPresent = count($userAtt->where('date',$currentDate));
            $user_attendance=$userAtt->where('date',$currentDate);
                if(in_array(date('l',strtotime($currentDate)),$weekends )||$checkHoliday){
                    $ho_days++;
                }
                else if($checkPresent > 0){
                    $prs_days++;
                }
                else if($checkLeave){
                    $leave_days++;
                }
                else{
                    $abs_days++;
                }

            ?>
        @endforeach
        <?php

        ?>
        <td style="border:1px solid black;text-align: center;" colspan="2">{{ $ak+1}}.</td>
        <td style="border:1px solid black;text-align: center;color: blue" colspan="2">#{{ $av->emp_id }}</td>
        <td style="border:1px solid black;" colspan="4">{{ $av->first_name.' '.$av->last_name }}</td>
        <td style="border:1px solid black;" colspan="4">{{ $av->designation->designations }}</td>
        <td style="border:1px solid black; text-align: center" colspan="2">{{ $abs_days }}</td>
        <td style="border:1px solid black; text-align: center" colspan="2">{{ $prs_days }}</td>
        <td style="border:1px solid black; text-align: center" colspan="2">{{ $ho_days }}</td>
        <td style="border:1px solid black; text-align: center" colspan="2">{{ $leave_days }}</td>
    </tr>
    </thead>
    <tbody>

    <tr>
        @foreach ($period as $dt)
            <?php
            $engdate = $dt->format("Y-m-d");
            $nepdate = $cal->formated_nepali_date($engdate);

            $weekends= Config::get('hrm.weekends');


            ?>
            <td class="std_p" title="{{ date('l',strtotime($engdate)) }}" style="border:1px solid black;
    text-align: center;">{{$dt->format("d") }}-{{substr($dt->format("M"),0,1) }}
                <br>
                <div>{{substr(date('D', strtotime($engdate)),0,1)}}</div>
            </td>
        @endforeach

    </tr>
        <tr>
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
                                {{date('H:i',strtotime($in_time))}}
                            </div>
                            <br>
                            <div>
                                {{date('H:i',strtotime($out_time))}}
                            </div>
                            <br>
                            <div>
                                <?php
                                $date_a = new DateTime($in_time);
                                $date_b = new DateTime($out_time);

                                $interval = date_diff($date_a,$date_b);

                                $time_dff= $interval->format('%h:%i hr');
                                ?>
                                {{$time_dff}}
                            </div>
                            {{--                                             <span style="padding:2px;" class="label label-success std_p">P--}}
                            {{--                                            </span>--}}
                        </td>
                    @else
                        <td style="border:1px solid black;text-align: center" data-toggle="tooltip" data-placement="top">
                            <div>
                                {{date('H:i',strtotime($user_attendance->where('attendance_status',1)->first()->time))}}
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

    {{--        <tr>--}}
{{--            <?php--}}

{{--            $userAtt = \AttendanceHelper::getUserOverallAttendanceHistroy($av->user_id, $start_date,$end_date);--}}
{{--            ?>--}}
{{--            <td style="border:1px solid black;text-align: center;color: blue">#{{ $av->emp_id }}</td>--}}
{{--            <td style="border:1px solid black;">{{ $av->first_name.' '.$av->last_name }}</td>--}}
{{--            @foreach ($period as $dt)--}}


{{--                <?php--}}
{{--                $currentDate =  $dt->format("Y-m-d");--}}
{{--                $checkHoliday = $holidays->where('start_date','<=',$currentDate)--}}
{{--                    ->where('end_date','>=',$currentDate)--}}
{{--                    ->first();--}}
{{--                $checkLeave = \AttendanceHelper::checkUserLeave($av->user_id,$currentDate);--}}

{{--                $checkPresent = count($userAtt->where('date',$currentDate));--}}
{{--                $user_attendance=$userAtt->where('date',$currentDate);--}}

{{--                ?>--}}

{{--                @if(in_array(date('l',strtotime($currentDate)),$weekends ))--}}

{{--                    <td style="border:1px solid black;text-align: center" title="{{date('l',strtotime($currentDate))}}" data-toggle="tooltip" data-placement="top">--}}
{{--                        <span style="padding:2px;" class="label label-primary std_p">W</span>--}}
{{--                    </td>--}}
{{--                @elseif($checkHoliday)--}}
{{--                    <td style="border:1px solid black;text-align: center" title="{{$checkHoliday->event_name}}" data-toggle="tooltip" data-placement="top">--}}
{{--                        <span style="padding:2px;" class="label label-info std_p">H</span>--}}
{{--                    </td>--}}
{{--                @elseif($checkLeave)--}}
{{--                    <td style="border:1px solid black;text-align: center" title="{{ $checkLeave->reason }}" data-toggle="tooltip" data-placement="top">--}}
{{--                        <span style="padding:2px;" class="label label-warning std_p">L</span>--}}
{{--                    </td>--}}
{{--                @elseif($checkPresent > 0)--}}

{{--                    @if($checkPresent>1)--}}
{{--                        <td  style="border:1px solid black;text-align: center" data-toggle="tooltip" data-placement="top">--}}
{{--                            <?php--}}
{{--                            $in_time=$user_attendance->where('attendance_status',1)->first()->time;--}}
{{--                            $out_time=$user_attendance->where('attendance_status',2)->first()->time;--}}
{{--                            ?>--}}
{{--                            <div>--}}
{{--                                {{date('h:i A',strtotime($in_time))}}--}}
{{--                            </div>--}}
{{--                            <br>--}}
{{--                            <div>--}}
{{--                                {{date('h:i A',strtotime($out_time))}}--}}
{{--                            </div>--}}
{{--                            <br>--}}
{{--                            <div>--}}
{{--                                <?php--}}
{{--                                $date_a = new DateTime($in_time);--}}
{{--                                $date_b = new DateTime($out_time);--}}

{{--                                $interval = date_diff($date_a,$date_b);--}}

{{--                                $time_dff= $interval->format('%h hr %i m');--}}
{{--                                ?>--}}
{{--                                {{$time_dff}}--}}
{{--                            </div>--}}
{{--                            --}}{{--                                             <span style="padding:2px;" class="label label-success std_p">P--}}
{{--                            --}}{{--                                            </span>--}}
{{--                        </td>--}}
{{--                    @else--}}
{{--                        <td style="border:1px solid black;text-align: center" data-toggle="tooltip" data-placement="top">--}}
{{--                            <div>--}}
{{--                                {{date('h:i A',strtotime($user_attendance->where('attendance_status',1)->first()->time))}}--}}
{{--                            </div>--}}
{{--                            --}}{{--                                             <span style="padding:2px;" class="label label-warning std_p">P</span>--}}
{{--                        </td>--}}
{{--                    @endif--}}

{{--                @else--}}

{{--                    <td style="border:1px solid black;text-align: center">--}}
{{--                        <span style="padding:2px;" class="label label-danger std_p">A</span>--}}
{{--                    </td>--}}


{{--                @endif--}}


{{--            @endforeach--}}
{{--        </tr>--}}
    </tbody>
</table>
@endforeach


</body>
</html>
