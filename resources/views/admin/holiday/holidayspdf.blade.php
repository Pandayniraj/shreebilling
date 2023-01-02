<table style="width:100%; text-align:center; padding: 50px 0; box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); -webkit-box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px;">

    <thead>
        <tr>
            <td colspan="3" style="text-align: center;">
                <img src="{{env('APP_URL')}}/{{ '/org/'.$organization->logo }}" alt="" class="img-responsive" style="width:200px;">
                <br/>
                <h1 style="font-size:25px; margin-top:15px;font-weight: bold; color: #00aef0;">Holiday Summary</h1>
                <p style="margin-top: 0; margin-bottom: 15px; padding-top: 0;">January {{$year}} - December {{$year}} </p>
            </td>
        </tr>
    </thead>
    <tbody>
        @foreach($months as $hv)
        <tr>
            @for($i = 0 ;$i
            <3;$i++) <th style="text-align: left;">{{$hv[$i][0]}}</th>
                @endfor
        </tr>
        <tr style="background-color: #f9f9f9; border:1px solid #ccc;">
            @for($i = 0 ;$i
            <3;$i++) @if(count($hv[$i][2])> 0)
                <td style="text-align:left;vertical-align:top;width:100%">
                    <ol>
                        @foreach($hv[$i][2] as $ev) {!! $ev !!} @endforeach
                    </ol>
                </td>
                @else
                <td width="100%" style="text-align:center;color: red">--</td>
                @endif @endfor
        </tr>

        @endforeach
    </tbody>
</table>

<hr>
<p style="text-align: center;">Sent from MEROCRM</p>