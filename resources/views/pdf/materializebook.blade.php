@extends('layouts.reportmaster')

@section('content')

<div >
<table>
    
 <tr>
             <td style="text-align: left;" colspan="4">Sales Materialize Book</td>
            <td  colspan="8" style="text-align: right;">@if($months) Month: {{$months}}
                @else
                Date: {{ date('dS M y', strtotime($startdate)) }} - {{ date('dS M y', strtotime($enddate)) }}
                @endif</td>
        </tr>
</table>

</div>


    


<table style="width:100%; text-align:center; padding: 30px 0; box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); -webkit-box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px;">

    <thead>
        
       

      
        <tr>

            <th>#SN</th>
            <th>Fiscal Year</th>
            <th>Bill No.</th>
            <th>Customer Name</th>
            <th>Customer Pan</th>
            <th>Bill Date</th>
            <th>Bill Amount</th>
            <th>Discount Amount</th>
            <th>Taxable Amount</th>
            <th>Total Amount</th>
            <th>Sync With IRD</th>
            <th>Is Bill Printed</th>
            <th>Is Bill Active</th>
            <th>Print Time</th>
            <th>Entered By</th>
            <th>Printed By</th>
            <th>Is Real Time</th>


        </tr>
    </thead>
    <tbody>
        <?php 
            $n = 0;
                $tbill_amount = 0;
                $tdiscount_amount = 0;
                $ttax_amount = 0;
                $ttotal_amount = 0;
            ?>
        @if(isset($sales_pdf) && !empty($sales_pdf))
        @foreach($sales_pdf as $s)
        <tr>
            <td>#{{++$n}}</td>
            <td>{{$s->fiscal_year}}</td>
            <td style="white-space: nowrap;"><b>{{$s->outlet_code}}</b>{{$s->bill_no}}</td>
            <td>{{$s->customer_name}}</td>
            <td>{{$s->customer_pan}}</td>
            <td>{{$s->bill_date}}</td>
            <td>{{$s->amount}}</td>
            <td>{{$s->discount}}</td>
            <td>{{ $s->taxable_amount }}</td>
            <td>{{$s->total_amount }}</td>
            <td>{{$s->sync_with_ird }}</td>
            <td>{{$s->is_bill_printed}}</td>
            <td>{{$s->is_bill_active}}</td>
            <td>{{$s->printed_time}}</td>
            <td>{{$s->entered_by}}</td>
            <td>{{$s->printed_by}}</td>
            <td>{{$s->is_realtime}}</td>
        </tr>
        <?php 
                        $tbill_amount += $s->amount;
                        $tdiscount_amount += $s->discount;
                        $ttax_amount += $s->taxable_amount;
                        $ttotal_amount += $s->total_amount;
                    ?>
        @if(!$s->is_bill_active)
        <tr class="bg-danger">
            <td>#{{++$n}}</td>
            <td>{{$s->fiscal_year}}</td>
            <td>Ref {{$s->bill_no}}<b>{{$s->outlet_code}}</b></td>
            <td>{{$s->customer_name}}</td>
            <td>{{$s->customer_pan}}</td>
            <td>{{$s->bill_date}}</td>
            <td>-{{$s->amount}}</td>
            <td>-{{$s->discount}}</td>
            <td>-{{ $s->taxable_amount }}</td>
            <td>-{{$s->total_amount }}</td>
            <td>{{$s->sync_with_ird }}</td>
            <td>{{$s->is_bill_printed}}</td>
            <td>{{$s->is_bill_active}}</td>
            <td>{{$s->printed_time}}</td>
            <td>{{$s->entered_by}}</td>
            <td>{{$s->printed_by}}</td>
            <td>{{$s->is_realtime}}</td>
        </tr>
        <?php 
                        $tbill_amount -= $s->amount;
                        $tdiscount_amount -= $s->discount;
                        $ttax_amount -= $s->taxable_amount;
                        $ttotal_amount -= $s->total_amount;
                    ?>
        @endif

        @endforeach
        @endif
    </tbody>


    <tr>
        <th colspan="4"></th>
        <th>Total</th>
        <th>:</th>
        <th>{{$tbill_amount}}</th>
        <th>{{$tdiscount_amount}}</th>
        <th>{{$ttax_amount}}</th>
        <th>{{$ttotal_amount}}</th>
        <th colspan="7"></th>
    </tr>

    </tbody>
</table>

@endsection
