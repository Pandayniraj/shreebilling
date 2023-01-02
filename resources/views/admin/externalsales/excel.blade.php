<table style="border: 1px solid; text-align:center">
    <tr>
        <th colspan="16" style="text-align:center; font-size:16px font-weight:bold;">Jai Shreebinayak Distributor and Trade Link Pvt.Ltd</th>
    </tr>
    <tr>    
        <th colspan="16" style="text-align:center; font-size:14px font-weight:800;">Chamati-15, Kathmandu, Nepal</th>
    </tr>
    <tr>
        <th colspan="16" style="text-align:center; font-size:14px font-weight:800;">Quotation Report</th>
    </tr>    
   
</table>


@foreach ($data as $index => $invoice)

<table style="border: 1px solid; text-align:center">

        <?php
        $totalcost=0;
        $totalqty=0;
        ?>
        <tr>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;"> S.N</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Date</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Quotation No</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Particular</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Qty</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Unit</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Unit Price</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">SubTotal</th>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">Location</th>
        </tr>

        <tr>
            <td style="border: 1px solid; text-align:center;">{{ $loop->iteration }}</td>
            <td style="border: 1px solid; text-align:center;">{{$invoice->bill_date}}</td>
            <td style="border: 1px solid; text-align:center;">{{$invoice->bill_no}}</td>
            <td style="border: 1px solid; text-align:center;">{{ $invoice->client_name }}</td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;">{{ $invoice->from_stock_location }}</td>

        </tr>
        @for($i=0;$i<count($invoice->externalsalesdetail);$i++)
            <tr>
            
                    <td style="border: 1px solid; text-align:center;"></td>
                    <td style="border: 1px solid; text-align:center;"></td>
                    <td style="border: 1px solid; text-align:center;"></td>
                    <td style="border: 1px solid; text-align:center;">{{ \App\Models\Product::where('id', $invoice->externalsalesdetail[$i]->product_id)->first()->name }}</td>
                    <td style="border: 1px solid; text-align:center;">{{$invoice->externalsalesdetail[$i]->quantity}}</td>
                    <td style="border: 1px solid; text-align:center;">{{ \App\Models\ProductsUnit::where('id', $invoice->externalsalesdetail[$i]->unit)->first()->name }}</td>
                    <td style="border: 1px solid; text-align:center;">{{ $invoice->externalsalesdetail[$i]->price }}</td>
                    <td style="border: 1px solid; text-align:center;">{{ (float)$invoice->externalsalesdetail[$i]->quantity *(float)$invoice->externalsalesdetail[$i]->price}}</td>
                    <td style="border: 1px solid; text-align:center;"></td>
                    <?php
                        $totalcost+=(float)$invoice->externalsalesdetail[$i]->quantity *(float)$invoice->externalsalesdetail[$i]->price;
                        $totalqty+= (float)$invoice->externalsalesdetail[$i]->quantity;
                    ?>
            </tr>
        @endfor
            <tr>
                <td style="border: 1px solid; text-align:center;"></td>
                <td style="border: 1px solid; text-align:center;">Total</td>
                <td style="border: 1px solid; text-align:center;"></td>
                <td style="border: 1px solid; text-align:center;"></td>
                <td style="border: 1px solid; text-align:center;">{{number_format($totalqty,2)}}</td>  
                <td style="border: 1px solid; text-align:center;"></td>  
                <td style="border: 1px solid; text-align:center;"></td>
                <td style="border: 1px solid; text-align:center;">{{number_format($totalcost,2)}}</td>
                <td style="border: 1px solid; text-align:center;"></td> 
            </tr>
    </tbody>
</table>
@endforeach
