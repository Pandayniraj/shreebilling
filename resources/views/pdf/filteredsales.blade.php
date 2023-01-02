@extends('layouts.reportmaster')
@section('date') @if($months) Month: {{ (new \App\Helpers\NepaliCalendar())->getnepaliMonth($months - 1) }}
@elseif($startdate && $enddate)
 Date: {{ date('dS M y', strtotime($startdate)) }} - {{ date('dS M y', strtotime($enddate)) }} @endif @endsection
@section('content')
<table>
    <thead>
        <tr>
            <th>Bill Date</th>
            <th>Bill No</th>
            <th>Customer's Name</th>
            <th>Cust. PAN Number</th>
            <th>Total Sales</th>
            <th>Non Tax Sales</th>
            <th>Export Sales</th>
            <th>Discount</th>
            <th>Amount</th>
            <th>Tax(Rs)</th>

        </tr>
    </thead>
    <tbody>
        <?php  
   $total_amount =0 ;
  $taxable_amount = 0;
  $tax_amount = 0;
  ?>
        @foreach($sales_book as $sal_bks)
        <tr>

            <td>{{ date('dS M y', strtotime($sal_bks->bill_date)) }}<br />
                <?php
                    $temp_date = explode(" ",$sal_bks->bill_date );
                    $temp_date1 = explode("-",$temp_date[0]);
                    $cal = new \App\Helpers\NepaliCalendar();
                    //nepali date
                    $a = $temp_date1[0];
                    $b = $temp_date1[1];
                    $c = $temp_date1[2];
                    $d = $cal->eng_to_nep($a,$b,$c);
                
                    $nepali_date = $d['date'].' '.$d['nmonth'] .', '.$d['year'];
                ?>

                <small> {!! $nepali_date !!}</small>

            </td>
            <td>{{env('SALES_BILL_PREFIX')}}{{$sal_bks->bill_no}} </td>
            <td>@if($sal_bks->client){{$sal_bks->client->name}}@else{{$sal_bks->name}}@endif</td>
            <td>@if($sal_bks->client){{$sal_bks->client->vat}}@else {{$sal_bks->customer_pan}} @endif</td>
            <td>{{$sal_bks->total_amount}}</td>
            <td></td>
            <td></td>
            <td>{{$sal_bks->discount_amount}}</td>
            <td>{{$sal_bks->taxable_amount}}</td>
            <td>{{$sal_bks->tax_amount}}</td>
        </tr>
        <?php  
      $taxable_amount = $taxable_amount + $sal_bks->taxable_amount;
      $tax_amount = $tax_amount +  $sal_bks->tax_amount;
       $total_amount = $total_amount +  $sal_bks->total_amount;
    ?>


        @if($sal_bks->invoicemeta->is_bill_active === 0)
        <tr>
            <td>{{ date('dS M y', strtotime($sal_bks->bill_date)) }}/<br />
                <?php
                    $temp_date = explode(" ",$sal_bks->bill_date );
                    $temp_date1 = explode("-",$temp_date[0]);
                    $cal = new \App\Helpers\NepaliCalendar();
                    //nepali date
                    $a = $temp_date1[0];
                    $b = $temp_date1[1];
                    $c = $temp_date1[2];
                    $d = $cal->eng_to_nep($a,$b,$c);

                    $nepali_date = $d['date'].' '.$d['nmonth'] .', '.$d['year'];
                ?>

                <small> {!! $nepali_date !!}</small>

            </td>
            <td>Ref of {{env('SALES_BILL_PREFIX')}}{{$sal_bks->bill_no}} CN {{$sal_bks->invoicemeta->credit_note_no}}</td>
            <td>@if($sal_bks->client){{$sal_bks->client->name}}@else{{$sal_bks->name}}@endif</td>
            <td>@if($sal_bks->client){{$sal_bks->client->vat}}@else {{$sal_bks->customer_pan}} @endif</td>
            <td>-{{$sal_bks->total_amount}}</td>
            <td></td>
            <td></td>
            <td>{{$sal_bks->discount_amount}}</td>
            <td>-{{$sal_bks->taxable_amount}}</td>
            <td>-{{$sal_bks->tax_amount}}</td>
        </tr>

        <?php  
      $taxable_amount = $taxable_amount- $sal_bks->taxable_amount;
      $tax_amount = $tax_amount-  $sal_bks->tax_amount;
       $total_amount = $total_amount -$sal_bks->total_amount;
    ?>

        @endif
        @endforeach
        <tr>
            <th class="foot">Total Amount</th>
            <td class="foot"></td>
            <td class="foot"></td>
            <td class="foot"></td>
            <td class="foot">{{number_format($total_amount,2)}}</td>
            <td class="foot"></td>
            <td class="foot"></td>
            <td class="foot"></td>
            <td class="foot">{{number_format($taxable_amount,2)}}</td>
            <td class="foot">{{number_format($tax_amount,2)}}</td>
        </tr>

    </tbody>
</table>
@endsection

