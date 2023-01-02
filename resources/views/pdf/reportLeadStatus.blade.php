<table style="width:100%; text-align:center; padding: 30px 0; box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); -webkit-box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px;">

    <thead>
        <tr>
            <td colspan="8">
                <img src="{{env('APP_URL')}}/{{ '/org/'.$organization->logo }}" alt="" class="img-responsive" style="width:200px;">
                <br/>
                <h1 style="font-size:30px; margin-top:20px; margin-bottom:0px; font-weight: bold; color: #00aef0;">Leads By Status</h1>
                <p style="margin-top: 0; margin-bottom: 15px; padding-top: 0;">Between: {{ $start_date ." - ".$end_date}} </p>
            </td>
        </tr>
        <tr>
            <th style="text-align: left;">User</th>
            <th style="text-align: left;">Status</th>
            <th style="text-align: left;">Name</th>
            <th style="text-align: left;">Contacts</th>
            <th style="text-align: left;">Address</th>
            <th style="text-align: left;">Description</th>
            <th style="text-align: left;">Price Value</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $dk => $dv)
        <tr style="background-color: #f9f9f9; border:1px solid #ccc;">
            <td width="50%" style="text-align:left;">{{ TaskHelper::getUserName($dv->user_id) }}</td>
            <td width="50%" style="text-align:left;">{{ $dv->status->name }}</td>
            <td width="50%" style="text-align:left;">{{ $dv->title.' '.$dv->name }}</td>
            <td width="50%" style="text-align:left;">{{ $dv->mob_phone }} <br/> {{ $dv->home_phone }}</td>

            <td width="50%" style="text-align:left;">{{ $dv->address_line_1 }}</td>
            <td width="50%" style="text-align:left;">{{ $dv->description }} - {{ $dv->email}}</td>
            <td width="50%" style="text-align:left;">{{ $dv->price_value }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<hr>
<p style="text-align: center;">Sent from MEROCRM</p>