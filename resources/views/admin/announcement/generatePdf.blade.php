<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>All Announcements</title>
    <style>
      table td {
        padding: 5px;
        text-align: left;
        border: 1px solid #ccc;
      }
    </style>
  </head>
  <body>

    @if($announcements)

    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                <h2 style="text-align: center;">All Announcements</h2>
                <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Created By</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($announcements as $ak => $av)
                        <tr>
                            <td>{{ $av->title }}</td>
                            <td>{{ TaskHelper::getUserName($av->user_id) }}</td>
                            <td>{{ $av->start_date }}</td>
                            <td>{{ $av->end_date }}</td>
                            <td>{{ ucfirst($av->status) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @endif

  </body>
</html>