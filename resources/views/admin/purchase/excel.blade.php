<table>
    <thead>
        <tr>
            <th colspan="15" style="text-align:center; font-size:16px font-weight:bold;">Purchase Bill And Its Detail</th>
        </tr>
        <tr>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Party Name</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Bill Date</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Bill Number</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">PAN</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Particulars</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Quantity</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Price</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Unit</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Vat</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Amount</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Additional Amount</th>

        </tr>
    </thead>
    <tbody>
        @php
            $totalpurchasequantity=0;
            $totaltax=0;
            $totalrate=0;
            $totalamount=0;
            $totaladditionalamount=0;
        @endphp
    @foreach($data as $item)     
        <tr>
            <td style="border: 1px solid; text-align:center;">{{$item['client']['name']??'-'}}</td>
            <td style="border: 1px solid; text-align:center;" >{{\App\Helpers\TaskHelper::getNepaliDate($item['bill_date'])}}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['bill_no']}}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['client']['vat']??'-' }}</td>
        </tr>    
            @for($i=0;$i<sizeof($item['product_details']);$i++)
            <tr>
                <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;" ></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;">{{ $item['product_details'][$i]['product']['name'] }}</td>
            <td style="border: 1px solid; text-align:center;">{{ $item['product_details'][$i]['qty_invoiced'] }}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['product_details'][$i]['unit_price']}}</td>
            <td style="border: 1px solid; text-align:center;">{{ $item['product_details'][$i]['units']['name'] }}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;">{{ $item['product_details'][$i]['tax_amount'] }}
            </td>
            <td style="border: 1px solid; text-align:center;">{{$item['product_details'][$i]['total'] }}</td>

            <td style="border: 1px solid; text-align:center;">{{$item['product_details'][$i]['unitpricewithimport']-$item['product_details'][$i]['total'] }}</td>
            @php
            $totaltax+=$item['product_details'][$i]['tax_amount'];
            $totalpurchasequantity +=$item['product_details'][$i]['qty_invoiced'];
            $totalamount+=$item['product_details'][$i]['total'];
            $totaladditionalamount+=$item['product_details'][$i]['unitpricewithimport']-$item['product_details'][$i]['total'];
            @endphp
            @endfor
        </tr>
         @endforeach
        <tr>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;" colspan="5">Total</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{$totalpurchasequantity}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">-</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">-</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{ $totaltax }}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{$totalamount}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{$totaladditionalamount}}</td>
        </tr>

    </tbody>
</table>
