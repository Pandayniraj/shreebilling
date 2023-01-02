<table id="" class="table table-bordered std_table table-striped" style="width:100%;">
    <thead>
    <tr style="background: #3c8dbc;color: #FFFFFF;">
{{--        <th class="text-center" rowspan="2">Ord Num.</th>--}}
        <th class="text-center" style="width: 10%;" rowspan="2">Date</th>

        <th class="text-center" style="width: 20%;" rowspan="2">Customer</th>
        <th class="text-center" style="width: 10%;" rowspan="2">Bill No.</th>

{{--        <th class="text-center" rowspan="2">Ref No.#</th>--}}
        <th class="text-center" style="width: 15%;" rowspan="2">Tran Type</th>
{{--        <th class="text-center" rowspan="2">Date</th>--}}
{{--        <th class="text-center" rowspan="2">Location</th>--}}
        <th class="text-center" style="width: 15%;" colspan="3">Inwards</th>
        <th class="text-center" style="width: 15%;" colspan="3">Outwards</th>
        <th class="text-center" style="width: 15%;" colspan="3"><i class="fa fa- fa-hand-paper-o"></i> Closing</th>
    </tr>
    <tr style="background: #3c8dbc;color: #FFFFFF;">
        <td>Qty</td>
        <td>Rate</td>
        <td>Value</td>
        <td>Qty</td>
        <td>Rate</td>
        <td>Value</td>
        <td>Qty</td>
        <td>Rate</td>
        <td>Value</td>
    </tr>
    </thead>
    <tbody>
    <?php
    $sum = 0;
    $StockIn = 0;
    $StockOut = 0;
    $totalAmount = 0;
    $StockInAmount = 0;
    $StockOutAmount = 0;
    ?>
    @if(count($transations)>0)
        @foreach($transations as $result)

            <?php
            $reasons = \App\Models\AdjustmentReason::all();
            if ($result->trans_type == PURCHINVOICE) {

                $order = $result->get_purchase;
                $type = 'Purchase';
                $href = $order->id ? "/admin/purchase/{$order->id}?type={$order->purchase_type}" : null;

            } elseif ($result->trans_type == SALESINVOICE) {
                $order = $result->get_sales;
                $type = 'Sales';
                $href = $order->id ? "/admin/orders/{$order->id}" : null;
            } elseif ($result->trans_type == OTHERSALESINVOICE) {
                $order = $result->get_invoice;
                $type = 'Invoice';
                $href = $order->id ? "/admin/invoice1/{$order->id}" : null;
            }elseif ($result->trans_type == PURCHASEADDITIONALCOST) {
                $order = $result->get_entries;
                $type = 'Voucher Entry';
                $href = $order->id ? ("/admin/entries/show/".$order->entrytype->label.'/'.$order->id): null;
            } elseif ($result->trans_type == STOCKMOVEIN) {

                $type = 'Transfer';
                $href = null;
            } elseif ($result->trans_type == STOCKMOVEOUT) {

                $type = 'Transfer';
                $href = null;
            }
            ?>
            <tr>
                <td align="center">
                    @if($href)
                                            <a href="{{ $href }}" target="_blank">
                                                {{date('d M Y',strtotime($result->tran_date))}}
                                            </a>
                                        @else
                                            {{date('d M Y',strtotime($result->tran_date))}}
                                        @endif
                    </td>

{{--                <td align="center">--}}
{{--                    @if($href)--}}
{{--                        <a href="{{ $href }}" target="_blank">--}}
{{--                            #{{$result->id}}--}}
{{--                        </a>--}}
{{--                    @else--}}
{{--                        #{{$result->id}}--}}
{{--                    @endif--}}
{{--                </td>--}}
                <td style="font-size: 16.5px" align="left">
                    {{$result->trans_type == PURCHASEADDITIONALCOST?$result->reference:($order->client->name ?? '')}}
                </td>
                <td align="center">
                    @if($href)
                        <a href="{{ $href }}" target="_blank">
                            {{$result->trans_type == PURCHASEADDITIONALCOST?$order->number:$order->bill_no }}
                        </a>
                    @else
                        {{$result->trans_type == PURCHASEADDITIONALCOST?$order->number:$order->bill_no }}
                    @endif
                     </td>
{{--                <td align="center">{{$order->reference}}</td>--}}
                <td align="center">

                    {{ $type  }}

                    @foreach($reasons as $reason)

                        @if($reason->trans_type == $result->trans_type)

                            {{ucwords($reason->name)}}

                        @endif

                    @endforeach

                </td>
{{--                <td align="center">{{$result->location_name}}</td>--}}
                <td align="center">
                    @if($result->qty >0)
                        {{number_format($result->qty,2)}}
                        <?php
                        $StockIn += $result->qty;
                        ?>
                    @else
                        -
                    @endif
                </td>
                <td align="center">
                    @if($result->qty >0)
                        {{number_format($result->price,2)}}
                    @else
                        -
                    @endif
                </td>
                <td align="center">
                    @if($result->qty >0)
                        {{number_format($result->qty*$result->price,2)}}
                        <?php
                        $StockInAmount += $result->qty*$result->price;
                        ?>
                    @elseif($result->qty ==0)
                        {{number_format($result->price,2)}}
                        <?php
                        $StockInAmount +=$result->price;
                        ?>
                        @else
                        -
                    @endif
                </td>
                <td align="center">
                    @if($result->qty <0 )
                        {{abs($result->qty)}}
                        <?php
                        $StockOut += $result->qty;
                        ?>
                    @else
                        -
                    @endif
                </td>
                <td align="center">
                    @if($result->qty <0 )
                        {{number_format($result->price,2)}}
                    @else
                        -
                    @endif
                </td>
                <td align="center">
                    @if($result->qty <0 )
                        {{number_format(abs($result->qty)*$result->price,2)}}
                        <?php
                        $StockOutAmount += $result->qty*$result->price;
                        ?>
                    @else
                        -
                    @endif
                </td>
                <td align="center">{{$StockIn+$StockOut}}</td>
                <td align="center">{{number_format(($StockInAmount+$StockOutAmount)/($StockIn+$StockOut),2)}}</td>
                <td align="center">{{number_format($StockInAmount+$StockOutAmount,2)}}</td>
            </tr>
        @endforeach
        <tr class="bg-gray" style="font-weight: bold;">
            <td colspan="4" align="right">Total</td>
            <td align="center">{{number_format($StockIn,2)}}</td>
            <td align="center">{{number_format($StockInAmount/$StockIn,2)}}</td>
            <td align="center">{{number_format($StockInAmount,2)}}</td>
            <td align="center">{{number_format(abs($StockOut),2)}}</td>
            <td align="center">{{number_format($StockOutAmount/$StockOut,2)}}</td>
            <td align="center">{{number_format($StockOutAmount,2)}}</td>
            <td align="center">{{number_format($StockIn+$StockOut,2)}}</td>
            <td align="center">{{number_format(($StockInAmount+$StockOutAmount)/($StockIn+$StockOut),2)}}</td>
            <td align="center">{{number_format($StockInAmount+$StockOutAmount,2)}}</td>
        </tr>
    @else
        <tr>
            <td colspan="9" class="text-center text-danger">No Transaction Yet</td>
        </tr>
    @endif

    </tbody>
</table>