<div class="table-responsive">
<table class="table">
	<thead>
		<tr>
			<th colspan="22" style="text-align: center; font-weight: bold; color: black; background-color: #00B050; border: 1px solid black;">
				{{ $title }}
			</th>
		</tr>
		<tr>
			<th colspan="22" style="text-align: center; font-weight: bold;  color: black; background-color: #C5D9F1; border: 1px solid black;">
				Fiscal Year : {{$fiscal_year}} From Date : {{$startdate}} To Date : {{$enddate}}, Outlet:{{$outlet_name}}, Category:{{$filter_category_name != null && $filter_category_name ? $filter_category_name : 'ALL' }}
			</th>
		</tr>
		<tr>
			<th colspan="4" style="text-align: center; font-weight: bold;  color: black; border: 1px solid black;">
			</th>
			<th colspan="3" style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; background-color: #00B0F0;">
				OPENING
			</th>
			<th colspan="3" style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; background-color: #FFC000;">
				PURCHASE
			</th>
			<th colspan="2" style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; background-color: #92D050;">
				PURCHASE RETURN
			</th>
			<th colspan="3" style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; background-color: #4BACC6;">
				SALES
			</th>
			<th colspan="2" style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; background-color: #F79646;">
				SALES RETURN
			</th>
			<th colspan="2" style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; background-color: #F2DCDB;">
				ADJUSTMENT
			</th>
			<th colspan="3" style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; background-color: #FFFF00;">
				CLOSING
			</th>
		</tr>

        <tr>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                FISCAL YEAR</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                 SN.</td>
            <td style="text-align: center; border: 1px solid black; " height="25"
            colspan="2">ITEM NAME</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                QTY</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                RATE(NRS)</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                AMOUNT(NRS)</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                QTY</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                RATE(NRS)</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                AMOUNT(NRS)</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                QTY</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                AMOUNT(NRS)</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                QTY</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                RATE(NRS)</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                AMOUNT(NRS)</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                QTY</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                AMOUNT(NRS)</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                QTY</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                AMOUNT(NRS)</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                QTY</td>
            <td style="text-align: center; border: 1px solid black; " height="25" >
                RATE(NRS)</td>
            <td style="text-align: center;border: 1px solid black; " height="25">
                AMOUNT(NRS)</td>

        </tr>

	</thead>
    <tbody>
        @php
            $total_opening_qty=0;
            $total_opening_rate=0;
            $total_opening_total=0;

            $total_receipt_qty=0;
            $total_receipt_rate=0;
            $total_receipt_total=0;

            $total_receipt_return_qty=0;
            $total_receipt_return_rate=0;
            $total_receipt_return_total=0;

            $total_issue_qty=0;
            $total_issue_rate=0;
            $total_issue_total=0;

            $total_issue_return_qty=0;
            $total_issue_return_rate=0;
            $total_issue_return_total=0;

            $total_adjustment_qty=0;
            $total_adjustment_rate=0;
            $total_adjustment_total=0;

            $total_closing_qty=0;
            $total_closing_rate=0;
            $total_closing_total=0;
        @endphp
        @foreach ($records as $category=>$product)
        
            <tr>
                <td colspan="22" style="text-align: left; font-weight: bold;  color: black; border: 1px solid black; background-color: #FFFF00;">
                {{$category}}
                </td>
            </tr>
            @php
                $subtotal_opening_qty=0;
                $subtotal_opening_rate=0;
                $subtotal_opening_total=0;

                $subtotal_receipt_qty=0;
                $subtotal_receipt_rate=0;
                $subtotal_receipt_total=0;

                $subtotal_receipt_return_qty=0;
                $subtotal_receipt_return_rate=0;
                $subtotal_receipt_return_total=0;

                $subtotal_issue_qty=0;
                $subtotal_issue_rate=0;
                $subtotal_issue_total=0;

                $subtotal_issue_return_qty=0;
                $subtotal_issue_return_rate=0;
                $subtotal_issue_return_total=0;

                $subtotal_adjustment_qty=0;
                $subtotal_adjustment_rate=0;
                $subtotal_adjustment_total=0;

                $subtotal_closing_qty=0;
                $subtotal_closing_rate=0;
                $subtotal_closing_total=0;
            @endphp
            @foreach ($product as $name=>$title)
                @php
                    $closing_qty=0;
                    $closing_rate=0;
                    $closing_amount=0;
                @endphp
                <tr>
                    <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                        {{$fiscal_year}}</td>
                    <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                        {{$loop->index+1}}</td>
                    <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; "
                    colspan="2">{{$name}}</td>
                    @foreach ($title as $key=>$value)
                        <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                                {{ $value->quantity }}
                        </td>
                        @if($key!='issue_return' && $key!='adjustment' && $key!='receipt_return')
                            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                                {{$value->rate}}</td>
                        @endif
                        <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                            {{$value->total}}</td>
                            @php
                                if($key=='opening'){
                                    $subtotal_opening_qty+=$value->quantity;
                                    $subtotal_opening_rate+=$value->rate;
                                    $subtotal_opening_total+=$value->total;
                                    $opening_rate=$value->rate;
                                    $opening_quantity=$value->quantity;
                                }elseif($key=='receipt'){
                                    $subtotal_receipt_qty+=$value->quantity;
                                    $subtotal_receipt_rate+=$value->rate;
                                    $subtotal_receipt_total+=$value->total;
                                    $receipt_rate=$value->rate;
                                    $receipt_quantity=$value->quantity;

                                }elseif($key=='receipt_return'){
                                    $subtotal_receipt_return_qty+=$value->quantity;
                                    $subtotal_receipt_return_rate+=$value->rate;
                                    $subtotal_receipt_return_total+=$value->total;
                                }elseif($key=='issue'){
                                    $subtotal_issue_qty+=$value->quantity;
                                    $subtotal_issue_rate+=$value->rate;
                                    $subtotal_issue_total+=$value->total;
                                }elseif($key=='issue_return'){
                                    $subtotal_issue_return_qty+=$value->quantity;
                                    $subtotal_issue_return_rate+=$value->rate;
                                    $subtotal_issue_return_total+=$value->total;
                                }elseif($key=='adjustment'){
                                    $subtotal_adjustment_qty+=$value->quantity;
                                    $subtotal_adjustment_rate+=$value->rate;
                                    $subtotal_adjustment_total+=$value->total;
                                }

                                if($key=='opening' || $key=='receipt' || $key== 'issue_return'){
                                    $closing_qty+=$value->quantity;
                                }else{
                                    $closing_qty-=$value->quantity;
                                }

                            @endphp

                    @endforeach

                    <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                        {{$closing_qty}}</td>
                        @php
                        if($closing_qty>0){
                            if($receipt_quantity>0 && $opening_quantity >0){
                                $count=2;
                            }
                            else{
                                $count=1;
                            }
                            $closing_rate=round(($opening_rate+$receipt_rate)/$count,2);
                            $closing_amount=round($closing_rate*$closing_qty,2);
                        }
                        @endphp
                    <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                        {{$closing_rate}}</td>
                    <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                        {{$closing_amount}}</td>
                        @php
                            $subtotal_closing_qty+=$closing_qty;
                            $subtotal_closing_rate+=$closing_rate;
                            $subtotal_closing_total+=$closing_amount;
                        @endphp
                </tr>


            @endforeach

            <tr>
                <td colspan="4" style="text-align: left; font-weight: bold;  color: black; border: 1px solid black; background-color: #FFFF00;">
                    SUB TOTAL
                </td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                    {{ $subtotal_opening_qty}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_opening_rate}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_opening_total}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_receipt_qty}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_receipt_rate}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_receipt_total}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_receipt_return_qty}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_receipt_return_total}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_issue_qty}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_issue_rate}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_issue_total}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_issue_return_qty}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_issue_return_qty}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_adjustment_qty}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_adjustment_qty}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_closing_qty}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_closing_rate}}</td>
                <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{$subtotal_closing_total}}</td>
            </tr>
            @php
                    $total_opening_qty+=$subtotal_opening_qty;
                    $total_opening_rate+=$subtotal_opening_rate;
                    $total_opening_total+=$subtotal_opening_total;

                    $total_receipt_qty+=$subtotal_receipt_qty;
                    $total_receipt_rate+=$subtotal_receipt_rate;
                    $total_receipt_total+=$subtotal_receipt_total;

                    $total_receipt_return_qty+=$subtotal_receipt_return_qty;
                    $total_receipt_return_rate+=$subtotal_receipt_return_rate;
                    $total_receipt_return_total+=$subtotal_receipt_return_total;

                    $total_issue_qty+=$subtotal_issue_qty;
                    $total_issue_rate+=$subtotal_issue_rate;
                    $total_issue_total+=$subtotal_issue_total;

                    $total_issue_return_qty+=$subtotal_issue_return_qty;
                    $total_issue_return_rate+=$subtotal_issue_return_rate;
                    $total_issue_return_total+=$subtotal_issue_return_total;

                    $total_adjustment_qty+=$subtotal_adjustment_qty;
                    $total_adjustment_rate+=$subtotal_adjustment_rate;
                    $total_adjustment_total+=$subtotal_adjustment_total;

                    $total_closing_qty+=$subtotal_closing_qty;
                    $total_closing_rate+=$subtotal_closing_rate;
                    $total_closing_total+=$subtotal_closing_total;
                @endphp

        @endforeach
        <tr>
            <td colspan="4" style="text-align: left; font-weight: bold;  color: black; border: 1px solid black; background-color: #FFFF00;">
                GRAND TOTAL
            </td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
                {{ $total_opening_qty}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_opening_rate}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_opening_total}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_receipt_qty}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_receipt_rate}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_receipt_total}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_receipt_return_qty}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_receipt_return_total}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_issue_qty}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_issue_rate}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_issue_total}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_issue_return_qty}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_issue_return_qty}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_adjustment_qty}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_adjustment_qty}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_closing_qty}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_closing_rate}}</td>
            <td style="text-align: center; font-weight: bold;  color: black; border: 1px solid black; " >
            {{$total_closing_total}}</td>
        </tr>
    </tbody>
</table>
</div>
