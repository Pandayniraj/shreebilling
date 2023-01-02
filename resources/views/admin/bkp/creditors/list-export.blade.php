<table>
    <thead>
    <tr>
        <th  align="center">
            <b>{{env('APP_COMPANY')}}</b>
        </th>
    </tr>
    <tr>
        <th  style="text-align: center;" align="center" >
            <b>{{\Auth::user()->organization->address}}</b>
        </th>
    </tr>
    <tr>
        <th style="text-align: center;" align="center"  >
            <b>{{$excel_name}}</b>
        </th>
    </tr>
    <tr></tr>
    <tr>
        <th >
            <b>Date: {{date('d M Y')}}</b>
        </th>
    </tr>
    <tr>
        <th>
            <b>Selected Filters</b>
        </th>
        @if($selected_customer)
            <th>
                <b>Customer:</b>{{$selected_customer->name}}
            </th>
        @endif
    </tr>
    </thead>
</table>


<table >
    <thead>
    <tr class="bg-primary">
        <th style="border:1px solid;text-align: center;font-weight: bold;">SNo.</th>
        <th  style="border:1px solid;font-weight:bold;width: 12%;text-align: center">Customer Code</th>
        <th  style="border:1px solid;font-weight: bold;">Customer Name</th>
        <th style="width: 10%; text-align: center">Opening B/c(DR)</th>
        <th style="width: 10%; text-align: center">Opening B/c(CR)</th>
        <th  style="border:1px solid;font-weight:bold;width: 12%;text-align: center">Debit Amt</th>
        <th  style="border:1px solid;font-weight:bold;width: 12%;text-align: center">Credit Amt</th>
        <th  style="border:1px solid;font-weight:bold;width: 12%;text-align: center">Closing B/c</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key=>$entries)

        <tr>
            <td style="border:1px solid;text-align: center">{{ $key+1  }}.</td>
            <td style="border:1px solid;text-align: center">{{ $entries['code']  }}</td>
            <td style="border:1px solid;">
               	{{ $entries['name'] }}
            </td>
            <td style="text-align: center;font-size: 16.5px">
                {{$entries['opening_blc_dc']=='D'?round($entries['opening_blc'],2):0 }}</td>
            <td style="text-align: center;font-size: 16.5px">
                {{$entries['opening_blc_dc']=='C'?round($entries['opening_blc'],2):0 }}</td>
            <td style="border:1px solid;text-align: center;" >
                {{ round($entries['dr_amount'],2) }}</td>
            <td style="border:1px solid;text-align: center;" >
                {{ round($entries['cr_amount'],2) }}</td>
            <td style="border:1px solid;text-align: center;" >
                {{ round($entries['amount'],2) }}</td>
        </tr>
        @php $totalSum +=  $entries['amount']; @endphp
    @endforeach
    </tbody>
    <tfoot>
    <tr>
    <tr>
        <td style="text-align: right">Total</td>
        <td colspan="2" style="font-size: 20.5px;">{{ round($totalSum,2) }} </td>
    </tr></tr>
    </tfoot>
</table>
