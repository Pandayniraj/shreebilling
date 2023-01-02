<table style="width:100%; text-align:center; padding: 30px 0; box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); -webkit-box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px;">

    <thead>
        <tr>
            <td colspan="8">
                <img src="{{env('APP_URL')}}/{{ '/org/'.$organization->logo }}" alt="" class="img-responsive" style="width:200px;">
                <br/>
                <h1 style="font-size:30px; margin-top:20px;font-weight: bold; color: #00aef0;">Todays Calls</h1>
            </td>
        </tr>
        <tr>
            <th style="text-align: left;">User</th>
            <th style="text-align: left;">Task Type</th>
            <th style="text-align: left;">Task Subject</th>
            <th style="text-align: left;">Task Detail</th>
            <th style="text-align: left;">Task Status</th>
            <th style="text-align: left;">Task Due Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $dk => $dv)
        <tr style="background-color: #f9f9f9; border:1px solid #ccc;">
            <td width="50%" style="text-align:left;">{{ $dv->owner->first_name }}</td>
            <td width="50%" style="text-align:left;">{{ $dv->task_type }}</td>
            <td width="50%" style="text-align:left;">{{ $dv->task_subject }}</td>
            <td width="50%" style="text-align:left;">{{ $dv->task_detail }}</td>
            <td width="50%" style="text-align:left;">{{ $dv->task_status }}</td>
            <td width="50%" style="text-align:left;">{{ date('j M, y', strtotime($dv->task_due_date)) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<hr>
<p style="text-align: center;">Sent from MEROCRM</p>