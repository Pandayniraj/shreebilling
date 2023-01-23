<table>
    <thead>
        <tr>
            <th colspan="15" style="text-align:center; font-size:16px font-weight:bold;">Tax Invoice And Its Detail</th>
        </tr>
        <tr>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Party Name</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Bill Date</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898; ">Invoice Number</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Outlet</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Sellers PAN</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Customers PAN</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Particulars</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Quantity</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Price</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Unit</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Vat</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalsalesquantity=0;
            $totalrate=0;
            $totalamount=0;
            $total_tax=0;
        @endphp
    @foreach($data as $item)
    {{-- @dd($item['client']['name'], $item); --}}
   
     
        <tr>
            <td style="border: 1px solid; text-align:center;">{{$item['client']['name']??'-'}}</td>
            <td style="border: 1px solid; text-align:center;" >{{\App\Helpers\TaskHelper::getNepaliDate($item['bill_date'])}}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['bill_no']}}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['outlet']['name']}}</td>
            <td style="border: 1px solid; text-align:center;">{{ \Auth::user()->organization->vat_id }}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['client']['vat']??'-' }}</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">{{ $item['total_amount'] }}</td>
        </tr>    
            @for($i=0;$i<sizeof($item['invoice_detail']);$i++)
        <tr>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;" ></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>    
            <td style="border: 1px solid; text-align:center;">{{ $item['invoice_detail'][$i]['product']['name'] }}</td>
            <td style="border: 1px solid; text-align:center;">{{ $item['invoice_detail'][$i]['quantity'] }}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['invoice_detail'][$i]['price']}}</td>
            <td style="border: 1px solid; text-align:center;">{{ $item['invoice_detail'][$i]['units']['name'] }}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;">{{ $item['invoice_detail'][$i]['tax_amount'] }}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['invoice_detail'][$i]['total']}}</td>
            @php
            $totalsalesquantity +=$item['invoice_detail'][$i]['quantity'];
            $totalamount+=$item['invoice_detail'][$i]['total'];
            @endphp
            @endfor
        </tr>
         @endforeach
        <tr>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;" colspan="7">Total</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{$totalsalesquantity}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">-</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">-</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">-</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{$totalamount}}</td>

        </tr>

    </tbody>
</table>
