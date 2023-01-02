<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php $employee_name = \TaskHelper::getUserName($training->user_id); ?>
    <title>Training Detail - {{ $employee_name }}</title>
    <style>
      table td {
        padding: 5px;
        text-align: left;
        border: 1px solid #ccc;
      }
      table {border: none;}
    </style>
  </head>
  <body>


    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                <h2 style="text-align: center;">Training Detail - {{ $employee_name }}</h2>
                <table class="table" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td>Course/Training</td>
                            <td>{{ $training->training_name }}</td>
                        </tr>
                        <tr>
                            <td>Vendor</td>
                            <td>{{ $training->vendor_name }}</td>
                        </tr>
                        <tr>
                            <td>Start Date<</td>
                            <td>{{ $training->start_date }}</td>
                        </tr>
                        <tr>
                            <td>Finish Date</td>
                            <td>{{ $training->finish_date }}</td>
                        </tr>
                        <tr>
                            <td>Training Cost</td>
                            <td>{{ $training->training_cost }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>@if($training->status == 0) Pending @elseif($training->status == 1) Started @elseif($training->status == 2) Completed @else Terminated @endif</td>
                        </tr>
                        <tr>
                            <td>Performance</td>
                            <td>@if($training->performance == 0) Not Concluded @elseif($training->performance == 1) Satisfactory @elseif($training->performance == 2) Average @elseif($training->performance == 3) Poor @else Excellent @endif</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

  </body>
</html>