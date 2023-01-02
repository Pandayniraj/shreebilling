<table style="width:100%; text-align:center; padding: 30px 0; box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); -webkit-box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px;">

    <thead>
        <tr>
            <td colspan="8">
                <img src="{{env('APP_URL')}}/{{ '/org/'.$organization->logo }}" alt="" class="img-responsive" style="width:200px;">
                <br/>
                <h1 style="font-size:30px; margin-top:20px;font-weight: bold; color: #00aef0;">Contacts</h1>
            </td>
        </tr>
        <tr>
            <th style="text-align: left;">ID</th>
            <th style="text-align: left;">Client</th>
            <th style="text-align: left;">Name</th>
            <th style="text-align: left;">Email</th>
            <th style="text-align: left;">Phone</th>
            <th style="text-align: left;">Landline</th>
            <th style="text-align: left;">City</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $dk => $dv)
        <tr style="background-color: #f9f9f9; border:1px solid #ccc;">
            <td width="50%" style="text-align:left;">{{ $dk+1 }}</td>
            <td width="50%" style="text-align:left;">{{ $dv->client->name }}</td>
            <td width="50%" style="text-align:left;">{{ $dv->salutation.' '.$dv->full_name }}</td>
            <td width="50%" style="text-align:left;">{{ $dv->email_1 }}</td>
            <td width="50%" style="text-align:left;">{{ $dv->phone }}</td>
            <td width="50%" style="text-align:left;">{{ $dv->landline }}</td>
            <td width="50%" style="text-align:left;">{{ $dv->city }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<hr>
<p style="text-align: center;">Sent from MEROCRM</p>