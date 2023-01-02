<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ env('APP_COMPANY')}} | Attendance Report</title>
    <style>
      table td {
        padding: 5px;
        text-align: left;
        border: 1px solid #ccc;
      }
    </style>
  </head>
  <body>

    @if($attendance)
    <div id="EmpprintReport">
     <?php
            $datetype = \Request::get('type');
            $begin = new DateTime($start_date);
            $end = new DateTime($end_date);
            $end->add(new \DateInterval('P1D'));
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            $cal = new \App\Helpers\NepaliCalendar();
            $date_in = implode('.', $date_in);
        ?>
        <div class="row">
            <div class="col-sm-12 std_print">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <address>
                        Company Name: {{ \Auth::user()->organization->organization_name }} <br>
                        Pan Number:   {{ \Auth::user()->organization->tpid }}<br>
                        Address: {{ \Auth::user()->organization->address }} <br>
                        </address>
                        <h3 class="panel-title">
                            Attendance List of 
                            @if($datetype == 'nepali')
                            {{ ucfirst($department->deptname).':-'.$cal->full_nepali_from_eng_date($start_date).' to '.$cal->full_nepali_from_eng_date($start_date)  }}
                            &nbsp;in B.S 
                            @else
                             {{ ucfirst($department->deptname).':-'.($start_date).' to '.($start_date)  }}
                            &nbsp;in A.D 
                            @endif
                        </h3>
                    </div>

                    

                    <table id="" class="table table-bordered std_table" width=100%>
                        <caption>
                            Days
                        </caption>
                        <thead>

                        <tr>
                            <th >Name</th>
                            @foreach ($period as $dt) 
                                <?php
                                    $engdate = $dt->format("Y-m-d");
                                    if($datetype == 'nepali'){
                                        $nepdate = $cal->formated_nepali_from_eng_date($engdate);
                                        $d = explode('-', $nepdate);
                                        echo "<th class='std_p'>{$d[0]}</th>";
                                    }else{
                                        echo "<th class='std_p'>{$dt->format('d')}</th>";
                                    }
                                    
                                ?>
                                
                            @endforeach
                        </tr>
                        </thead>
                            <tbody>
                                <?php $flag = 0; ?>
                                @foreach($attendance as $ak => $av)
                                
                                <tr>
                                    <?php $userAtt = \TaskHelper::getUserAttendanceHistroy($av->user_id, $date_in); ?>
                                
                                    <td >{{ $av->user_name }}</td>
                                @foreach ($period as $dt) 
                                    <?php $data = '<td></td>'; ?>
                                    @if(date('l', strtotime($dt->format("Y-m-d"))) == 'Saturday')
                                        <?php $data = '<th data-toggle="tooltip" data-placement="top" title="Saturday"><span style="padding:2px; 4px"class="col-sm-1">H</span></th>'; ?>
                                    @else
                                        <?php
                                            $holidayFlag = 0;
                                        ?>
                                        @foreach($holidays as $hk => $hv)
                                            @if(strtotime($date_in.'-'.$i) >= strtotime($hv->start_date) && strtotime($dt->format("Y-m-d")) <= strtotime($hv->end_date))
                                                <?php
                                                    $data = '<th data-toggle="tooltip" data-placement="top" title="'.$hv->event_name.'"><span style="padding:2px; 4px" class="label label-info std_p">H</span></th>';
                                                    $holidayFlag++;
                                                    break;
                                                ?>
                                            @endif
                                        @endforeach
                                        @if(!$holidayFlag)
                                            @foreach($userAtt as $uk => $uv)
                                                @if(strtotime($uv->date_in) == strtotime( $dt->format("Y-m-d")))
                                                    <?php 
                                                    if($uv->date_out)
                                                    $data = '<th><span style="padding:2px; 4px" std_p">P</span></th>'; 
                                                    else
                                                        $data = '<th><span style="padding:2px; 4px" std_p">P</span></th>'; 

                                                    ?>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                    <?php echo $data; ?>
                                @endforeach
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

  </body>
</html>