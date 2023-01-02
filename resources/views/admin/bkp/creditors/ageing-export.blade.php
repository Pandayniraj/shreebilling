<table>
    <thead>
    <tr>
        <th style="text-align: center;" align="center" colspan="12">
            <b>{{env('APP_COMPANY')}}</b>
        </th>
    </tr>
    <tr>
        <th  style="text-align: center;" align="center" colspan="15">
            <b>{{\Auth::user()->organization->address}}</b>
        </th>
    </tr>
    <tr>
        <th style="text-align: center;" align="center"  colspan="15">
            <b>{{$excel_name}}</b>
        </th>
    </tr>
    </thead>
    <tbody>
    <tr></tr>
    <tr>
        <th colspan="12">
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
        <th>
            <b>Standing Date:</b>{{$standing_date?$standing_date:date('Y-m-d')}}
        </th>
    </tr>
    </tbody>
</table>


<table >
    <thead>
    <tr class="bg-primary">
        <th style="font-weight: bold;border: 1px solid ;text-align: center">SNo.</th>
        <th style="font-weight: bold;border: 1px solid;text-align: center">Customer Code</th>
        <th colspan="5"  style="font-weight: bold;border: 1px solid">Customer Name</th>
        <th colspan="1" style="font-weight: bold;border: 1px solid;text-align: center">Total (Rs.)</th>
        <th colspan="1" style="white-space: normal;font-weight: bold;border: 1px solid;text-align: center">Past 0-30 days (Rs.)</th>
        <th colspan="1" style="font-weight: bold;border: 1px solid;text-align: center">Past 31-60 days (Rs.)</th>
        <th colspan="1" style="font-weight: bold;border: 1px solid;text-align: center">Past 61-90 days (Rs.)</th>
        <th colspan="1" style="font-weight: bold;border: 1px solid;text-align: center">Past 91-180<br>days (Rs.)</th>
        <th colspan="1" style="font-weight: bold;border: 1px solid;text-align: center">Past 181-365 days(Rs.)</th>
        <th colspan="1" style="font-weight: bold;border: 1px solid;text-align: center">Past 1 year plus (Rs.)</th>
    </tr>
    </thead>
    <tbody>

    @foreach($data as $key=>$entries)
        <tr>
            <td style="border: 1px solid;text-align: center">{{ $key+1  }}</td>
            <td style="border: 1px solid;text-align: center">{{ $entries['code']}}</td>
            <td colspan="5" style="border: 1px solid;">{{ $entries['name']  }}</td>
            <td colspan="1" style="border: 1px solid;text-align: center">{{ round($entries['total'],2)  }}</td>
            <td colspan="1" style="border: 1px solid;text-align: center">{{ round($entries['1-30'],2)  }}</td>
            <td colspan="1" style="border: 1px solid;text-align: center">{{ round($entries['31-60'],2)  }}</td>
            <td colspan="1" style="border: 1px solid;text-align: center">{{ round($entries['61-90'],2)  }}</td>
            <td colspan="1" style="border: 1px solid;text-align: center">{{ round($entries['91-180'],2)  }}</td>
            <td colspan="1" style="border: 1px solid;text-align: center">{{ round($entries['181-365'],2)  }}</td>
            <td colspan="1" style="border: 1px solid;text-align: center">{{ round($entries['1 year +'],2)  }}</td>
        {{--                                        @php $totalSum +=  $entries['amount']; @endphp--}}
    @endforeach
    </tbody>
    {{--                                    <tfoot>--}}
    {{--                                    <tr>--}}
    {{--                                        <td class="text-right" style="font-size: 20.5px">Total</td>--}}
    {{--                                        <td style="font-size: 20.5px;">{{ env('APP_CURRENCY') }} {{ round($totalSum,2) }} </td>--}}
    {{--                                    </tr>--}}
    {{--                                    </tfoot>--}}
</table>
