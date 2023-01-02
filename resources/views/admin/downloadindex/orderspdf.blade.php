@extends('layouts.reportmaster')
@section('content')
<table >
  <thead>
    <tr>
    <th class="no">id</th>
    <th class="no">Type</th>
    <th class="no">Issue Date</th>
    <th class="no">Client</th>
    <th class="no">Order Status</th>
    <th class="no">Source</th>
    <th class="no">Paid Amount</th> 
    <th class="no">Balance {{ env('APP_CURRENCY')}}</th>
    <th class="no">Pay Status</th>
    <th class="no">Total</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $n= 0;
    ?>
    @foreach($orders as $odk => $o)
    <tr>
        <td>{!! $o->id !!}</td>
        <td>{!! ucwords($o->order_type) !!}</td>

        <td> 
            {{ $o->bill_date }}
        </td>

        <td > 
          @if($o->source=='client') {{ $o->client->name }} @else {{ $o->lead->name }} @endif
          <small>{{ $o->name }}</small></a>
        </td>
        <td>
              {{$o->status}}
        </td>
          <td>{{ ucfirst($o->source) }}</td>

         <?php
          $paid_amount= \TaskHelper::getSalesPaymentAmount($o->id);
         ?>
        <td>{!! number_format($paid_amount,2) !!}</td>
        <td>{!! number_format($o->total_amount-$paid_amount,2)  !!}</td>

        <td>{{$o->payment_status}}</td>
          
        <td>{!! number_format($o->total_amount,2) !!}</td>
    </tr>
   @endforeach
    
  </tbody> 
 
</table>
@endsection