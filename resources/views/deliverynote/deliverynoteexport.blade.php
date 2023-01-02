<table style="border: 1px solid; text-align:center">
    <tr>
            <th colspan="16" style="text-align:center; font-size:16px font-weight:bold;">Statement of Calculation</th>
        </tr>
</table>

@foreach ($data as $index => $invoice)

<table style="border: 1px solid; text-align:center">
    <thead>
        <?php
        $totalunitcost=0;
        $totalqty=0;
        $subtotal=0;
        $totalvat=0;
        $totalamount=0;
        $totaldeliveryrate=0;
        $totaldeliveryamt=0;
        $totaldifferenceamt=0;
        ?>
    
        <tr>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;"> S.N</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Particulars</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Product-id</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Unit Purchase Price</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Total Purchase Price</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Billing Unit Cost</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Qty</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Unit</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Total</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">VAT13%</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Total Amount</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Delivery Quotation Amount</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Total</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Difference Amount</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">VAT Bill No.</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">VAT No.</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Bill Date</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">Claim Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border: 1px solid; text-align:center;">{{ $loop->iteration }}</td>
            <td style="border: 1px solid; text-align:center;">{{ \App\Models\Client::where('id', $invoice['client_id'])->first()->name }}</td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;">{{ $invoice['bill_no'] }}</td>
            <td style="border: 1px solid; text-align:center;">{{ \App\Models\Client::where('id', $invoice['client_id'])->first()->vat}}</td>
            <td style="border: 1px solid; text-align:center;">{{ $invoice['bill_date']}}</td>
           <?php
           $invoiceamount=0;
           $delieveryamount=0;
           foreach($invoice['invoice_detail'] as $invoicedetail){
           $invoiceamount+=((float)$invoicedetail['total'] + (float)$invoicedetail['tax_amount']);
           }
           foreach($invoice['deliverynote']['notedetails'] as $deliverydetail){
           $delieveryamount+=(float)$deliverydetail['return_total'];
           }
           ?>
            <td style="border: 1px solid; text-align:center;">{{ $invoiceamount- $delieveryamount}}
            </td>
        </tr>
        <tr>
            @for($i=0;$i<count($invoice['invoice_detail']);$i++)
                <tr>
                    <td style="border: 1px solid; text-align:center;"></td>
                    <td style="border: 1px solid; text-align:center;">{{ \App\Models\Product::where('id', $invoice['invoice_detail'][$i]['product_id'])->first()->name }}</td>
                    <td style="border: 1px solid; text-align:center;">{{$invoice['invoice_detail'][$i]['product_id']}}</td>
                    <td style="border: 1px solid; text-align:center;">{{\App\Models\Product::where('id',$invoice['invoice_detail'][$i]['product_id'])->first()->cost}}</td>
                    <td style="border: 1px solid; text-align:center;">{{(float)\App\Models\Product::where('id',$invoice['invoice_detail'][$i]['product_id'])->first()->cost * (float)$invoice['invoice_detail'][$i]['quantity']}}</td>

                    <td style="border: 1px solid; text-align:center;">{{ $invoice['invoice_detail'][$i]['price'] }}</td>
                    <td style="border: 1px solid; text-align:center;">{{ $invoice['invoice_detail'][$i]['quantity'] }}</td>
                    <td style="border: 1px solid; text-align:center;">{{ \App\Models\ProductsUnit::where('id', $invoice['invoice_detail'][$i]['unit'])->first()->name }}</td>
                    <td style="border: 1px solid; text-align:center;">{{ $invoice['invoice_detail'][$i]['total'] }}</td>
                    <td style="border: 1px solid; text-align:center;">{{ number_format( $invoice['invoice_detail'][$i]['tax_amount'],2) }}
                        @php
                            $bill_total = (float) $invoice['invoice_detail'][$i]['total'] + (float) $invoice['invoice_detail'][$i]['tax_amount'];
                        @endphp
                    <td style="border: 1px solid; text-align:center;">{{ number_format($bill_total,2) }}</td>
            
                    <td style="border: 1px solid; text-align:center;">{{ $invoice['deliverynote']['notedetails'][$i]['return_price'] }}</td>
                    @php
                        $del_total_row =  $invoice['deliverynote']['notedetails'][$i]['return_total'];
                    @endphp
                    <td style="border: 1px solid; text-align:center;">{{ number_format($del_total_row,2) }}</td>
                    <td style="border: 1px solid; text-align:center;">{{  $bill_total-$del_total_row }}</td>
                    <td style="border: 1px solid; text-align:center;"></td>
                    <td style="border: 1px solid; text-align:center;"></td>
                    <td style="border: 1px solid; text-align:center;"></td>
                    <td style="border: 1px solid; text-align:center;"></td>
                    <?php 
                    $totalunitcost+=$invoice['invoice_detail'][$i]['price'];
                    $totalqty+=$invoice['invoice_detail'][$i]['quantity'];
                 $subtotal+=$invoice['invoice_detail'][$i]['total'] ;
                   $totalvat+=$invoice['invoice_detail'][$i]['tax_amount '] ;
                  $totalamount+=$bill_total;
                    $totaldeliveryrate+=$invoice['deliverynote']['notedetails'][$i]['return_price'];
                    $totaldeliveryamt+=$del_total_row;
                    $totaldifferenceamt+=$bill_total-$del_total_row;
                    ?>
                </tr>
                
            @endfor
            <tr>
                <td style="border: 1px solid; text-align:center;"></td>
                <td style="border: 1px solid; text-align:center;">Total</td>
                <td style="border: 1px solid; text-align:center;"></td>
                <td style="border: 1px solid; text-align:center;"></td>
                <td style="border: 1px solid; text-align:center;"></td>  
                <td style="border: 1px solid; text-align:center;">{{number_format($totalunitcost,2)}}</td>
                <td style="border: 1px solid; text-align:center;">{{number_format($totalqty,2)}}</td>
                <td style="border: 1px solid; text-align:center;"></td>
                <td style="border: 1px solid; text-align:center;">{{number_format($subtotal,2)}}</td>
                <td style="border: 1px solid; text-align:center;">{{number_format($totalvat,2)}}</td>  
                <td style="border: 1px solid; text-align:center;">{{number_format($totalamount,2)}}</td> 
                <td style="border: 1px solid; text-align:center;">{{number_format($totaldeliveryrate,2)}}</td> 
                <td style="border: 1px solid; text-align:center;">{{number_format($totaldeliveryamt,2)}}</td> 
                <td style="border: 1px solid; text-align:center;">{{number_format($totaldifferenceamt,2)}}</td> 
                <td style="border: 1px solid; text-align:center;"></td>
                <td style="border: 1px solid; text-align:center;"></td>
                <td style="border: 1px solid; text-align:center;"></td>
                <td style="border: 1px solid; text-align:center;"></td>
            </tr>
            
        {{-- @php
            $totalpurchaseamount=0;
            $totalnontaxpurchase=0;
            $total_taxable_amount=0;
            $total_non_taxable_amount=0;
        @endphp
        @foreach ($data as $item)
        <tr>
            <td >{{$item['billdate']}}</td>
            <td style="border: 1px solid; text-align:center;" >{{$item['Bill Num']}}</td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;">{{$item['Supplier’s Name']}}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['Supl. PAN Number']}}</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;">{{$item['Total Purchase']}}</td>
            <td style="border: 1px solid; text-align:center;">{{ $item['Non Tax Purchase'] }}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['Amount']}}</td>
            <td style="border: 1px solid; text-align:center;">{{ $item['Tax(Rs)'] }}</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
        </tr>
        @php
        $totalpurchaseamount+=$item['Total Purchase'];
        $totalnontaxpurchase+=$item['Non Tax Purchase'];
        $total_taxable_amount+=$item['Amount'];
        $total_non_taxable_amount+=$item['Tax(Rs)'];
        @endphp
        @endforeach
        <tr>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;" colspan="8">कल मूल्य</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{$totalpurchaseamount}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{ $totalnontaxpurchase}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{$total_taxable_amount}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{$total_non_taxable_amount}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;"></td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;"></td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;"></td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;"></td>
        </tr> --}}

    </tbody>
</table>

@endforeach