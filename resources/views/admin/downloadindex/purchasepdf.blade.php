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
              <td>{!! ucwords($o->purchase_type) !!}</td>

              <td> 
                  {{ $o->ord_date }}
              </td>

              <td > 
                {{ $o->client->name }} 
                
              </td>
              <td>
                    {{$o->status}}
              </td>
               <?php
                $paid_amount= \TaskHelper::getPurchasePaymentAmount($o->id);
               ?>
              <td>{!! number_format($paid_amount,2) !!}</td>
              <td>{!! number_format($o->total-$paid_amount,2)  !!}</td>

              <td>{{$o->payment_status}}</td>
                
              <td>{!! number_format($o->total,2) !!}</td>
          </tr>
         @endforeach
          
        </tbody> 
       
      </table>
@endsection