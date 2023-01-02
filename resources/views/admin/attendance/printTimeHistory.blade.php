<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
  <head>
    <meta charset="UTF-8">
    <title>{{ env('APP_COMPANY')}} | Time History Log</title>

    <!-- block from searh engines -->
    <meta name="robots" content="noindex">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Set a meta reference to the CSRF token for use in AJAX request -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons 4.7.0 -->
    <link href="{{ asset("/bower_components/admin-lte/font-awesome/css/all.css") }}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css" />

    <!-- Application CSS-->
    <link href="{{ asset(elixir('css/all.css')) }}" rel="stylesheet" type="text/css" />


  </head>

<body onload="window.print();" cz-shortcut-listen="true" class="skin-blue sidebar-mini">

  <div class='wrapper'>

    @if($history)

    <div id="EmpprintReport">
        <div class="row">
            <div class="col-sm-12 std_print">
                <div class="panel panel-custom">
                    <h2 style="text-align: center;">{{ TaskHelper::getUserName($user_id) }} Time Logs :: {{ date('d F-Y', strtotime($date_in[0])) }} to {{ date('d F-Y', strtotime($date_in[1])) }} </h2>
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

  </div><!-- /.col -->

</body>
