<table style="width:100%; text-align:center; padding: 30px 0; box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); -webkit-box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px;">

    <thead>
        <tr>
            <td colspan="8">
                <img src="{{env('APP_URL')}}/{{ '/org/'.$organization->logo }}" alt="" class="img-responsive" style="width:200px;">
                <br/>
                <h1 style="font-size:30px; margin-top:20px;font-weight: bold; color: #00aef0;">Purchase Payment</h1>
            </td>
        </tr>
        <tr>
            <th style="text-align: left;">S.N</th>
            <th style="text-align: left;">Client</th>
            <th style="text-align: left;">Date</th>
            <th style="text-align: left;">Reference No</th>
            <th style="text-align: left;">Amount</th>
            <th style="text-align: left;">Paid By</th>
        </tr>
    </thead>
    <tbody><?php  $total = 0; ?>
        @foreach($payment_list as $dk => $o)  
        <tr style="background-color: #f9f9f9; border:1px solid #ccc;">
            <td width="50%" style="text-align: left">{{$dk + 1}}</td>
            <td width="50%" style="text-align:left;">{!! $o->purchase->client->name !!}</td>
            <td width="50%" style="text-align:left;">{!! date('dS M y', strtotime($o->date)) !!}</td>
            <td width="50%" style="text-align:left;">{!! $o->reference_no !!}</td>
            <td width="50%" style="text-align:left;">{{env('APP_CURRENCY')}} {{ number_format($o->amount,2) }}</td>
            <td width="50%" style="text-align:left;">{!! $o->paidby->name !!}</td>
        </tr>
        <?php  $total = $total + $o->amount; ?>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th style="text-align:left;" colspan="3"></th>
            <th style="text-align: left">Total</th>
            <th style="text-align: left">{{env('APP_CURRENCY')}} {{ number_format($total,2) }}</th>
        </tr>
    </tfoot>
</table>

<hr>
<p style="text-align: center;">Sent from MEROCRM</p>