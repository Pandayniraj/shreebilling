<table>
    <thead>
        <tr>
            <th style="text-align: center;" align="center" colspan="13">
                <b>{{env('APP_COMPANY')}}</b>
            </th>
        </tr>
        <tr>
            <th style="text-align: center;" align="center" colspan="13">
                <b>{{\Auth::user()->organization->address}}</b>
            </th>
        </tr>
        <tr>
            <th style="text-align: center;" align="center" colspan="13">
                <b>{{$excel_name}}</b>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr></tr>
        <tr>
            <th colspan="12">
                <b>From Date: {{$startdate}}</b> - 
                <b>To Date: {{$enddate}}</b>
            </th>
        </tr>
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <th class="text-center">Id</th>
            <th class="text-center">Product</th>
            <th class="text-center">Tran No #</th>
            <th class="text-center">Tran Type</th>
            <th class="text-center">Date</th>
            <th class="text-center">Store</th>
            <th class="text-center">Quantity In</th>
            <th class="text-center">Quantity Out</th>
            <th class="text-center"> <i class="fa fa- fa-hand-paper-o"></i> On Hand</th>
            <th class="text-center">Avg Price</th>
        </tr>
    </thead>
    <tbody>  
        <?php
        $sum = 0;
        $StockIn = 0;
        $StockOut = 0;
        ?>
        @if(count($transations)>0)
        @foreach($transations as $result)
        <tr>
            <td align="center">{{$result->id}}</td>
            <td align="left"> {{$result->name}}</a></td>
            <td align="center">{{$result->order_no}}</td>
            <td align="center">
                @if($result->trans_type == PURCHORDER)
                PURCHORDER
                @elseif($result->trans_type == PURCHINVOICE)
                PURCHINVOICE
                @elseif($result->trans_type == GRN)
                GRN
                @elseif($result->trans_type == SALESORDER)
                SALESORDER
                @elseif($result->trans_type == SALESINVOICE)
                SALESINVOICE
                @elseif($result->trans_type == OTHERSALESINVOICE)
                OTHERSALESINVOICE
                @elseif($result->trans_type == DELIVERYORDER)
                DELIVERYORDER
                @elseif($result->trans_type == STOCKMOVEIN)
                STOCKMOVEIN
                @elseif($result->trans_type == STOCKMOVEOUT)
                STOCKMOVEOUT
                @elseif($result->trans_type == OPENINGSTOCK)
                OPENINGSTOCK
                @endif

            </td>
            <td align="center">{{$result->tran_date}}</td>
            <td align="center">{{$result->storename}}</td>
            <td align="center">
                @if($result->qty >0 )
                {{$result->qty}}
                <?php
                $StockIn +=$result->qty;
                ?>
                @else
                -
                @endif
            </td>
            <td align="center">
                @if($result->qty <0 )
                {{str_ireplace('-','',$result->qty)}}
                <?php
                $StockOut +=$result->qty;
                ?>
                @else
                -
                @endif
            </td>
            <td align="center">{{$sum += $result->qty}}</td>
            <td>0.00</td>
        </tr>
        @endforeach
        <tr><td colspan="7" align="right">Total</td><td align="center">{{$StockIn}}</td><td align="center">{{str_ireplace('-','',$StockOut)}}</td><td align="center">{{$StockIn+$StockOut}}</td></tr>
        @else
        <tr>
            <td colspan="6" class="text-center text-danger">No Transaction Yet</td>
        </tr>
        @endif

    </tbody>

</table>
