<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>All Trainings</title>
    <style>
      table td {
        padding: 5px;
        text-align: left;
        border: 1px solid #ccc;
      }
    </style>
  </head>
  <body>


    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                <h2 style="text-align: center;">All Trainings</h2>
                <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Course/Training</th>
                            <th>Vendor</th>
                            <th>Start Date</th>
                            <th>Finish Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trainings as $ak => $av)
                        <tr>
                            <td>{{ TaskHelper::getUserName($av->user_id) }}</td>
                            <td>{{ $av->training_name }}</td>
                            <td>{{ $av->vendor_name }}</td>
                            <td>{{ $av->start_date }}</td>
                            <td>{{ $av->finish_date }}</td>
                            <td>@if($av->status == 0) Pending @elseif($av->status == 1) Started @elseif($av->status == 2) Completed @else Terminated @endif</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

  </body>
</html>