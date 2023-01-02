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

    @if($history)

    <div id="EmpprintReport">
        <div class="row">
            <div class="col-sm-12 std_print">
                <div class="panel panel-custom">
                    <h2 style="text-align: center;">{{ TaskHelper::getUserName($user_id) }} Time Logs :: {{ date('d F-Y', strtotime($date_in[0])) }} to {{ date('d F-Y', strtotime($date_in[1])) }}</h2>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Clock In Time</th>
                                <th>Clock Out Time</th>
                                <th>IP address</th>
                                <th>Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $hk => $hv)
                            <tr>
                                <td colspan="4" style="background: rgba(233, 237, 228, 0.73);font-weight: bold">Date In  : {{ $hv->date_in }}, Date Out : {{ $hv->date_out }}</td>
                            </tr>
                            <tr>
                                <td>{{ date('h:i a', strtotime($hv->clockin_time)) }}</td>
                                <td>{{ date('h:i a', strtotime($hv->clockout_time)) }}</td>
                                <td>{{ $hv->ip_address }}</td>
                                <?php
                                    $startTime = \Carbon\Carbon::parse(date('Y-m-d H:i:s', strtotime($hv->date_in.' '.$hv->clockin_time)));
                                    $finishTime = \Carbon\Carbon::parse(date('Y-m-d H:i:s', strtotime($hv->date_out.' '.$hv->clockout_time)));
                                    //dd($startTime.' '.$finishTime);
                                    $totalDuration = $finishTime->diffInSeconds($startTime);
                                    if(gmdate('d', $totalDuration) != '01')
                                        $hour = ((gmdate('d', $totalDuration) - 1) * 24) + gmdate('H', $totalDuration);
                                    else
                                        $hour = gmdate('H', $totalDuration);

                                    $minute = gmdate('i', $totalDuration);

                                ?>
                                <td>{{ $hour.':'.$minute.' m' }}</td>
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