@extends('layouts.reportmaster')
@section('content')

<h2>Products List</h2>
      <table class="table table-striped">
        <thead>
          <tr>
          <th class="no">id</th>
          <th class="no">Name</th>
          <th class="no">Code</th>
          <th class="no">Purchase</th>
          <th class="no">Agent</th>
          <th class="no">Sell</th>
          <th class="no">Type</th>
          <th class="no">Active</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $n= 0;
          ?>
          @foreach($products as $odk => $o)
          <tr>
              <td>{!! $o->id !!}</td>
              <td>{!! $o->name !!}</td>
              <td>{!! $o->product_code !!}</td>
              <td>{{ env('APP_CURRENCY')}}{!! number_format($o->cost,2) !!}</td>
              <td>{{ env('APP_CURRENCY')}}{!! number_format($o->agent_price,2) !!}</td>
              <td>{{ env('APP_CURRENCY')}}{!! number_format($o->price,2) !!}</td>
              <td>{!! $o->type !!}</td>
              <td>{!! $o->enabled !!}</td>

          </tr>
         @endforeach
          
        </tbody> 
       
      </table>
@endsection