@extends('layouts.reportmaster')
@section('content')

      <table >
        <thead>
          <tr>
          <th class="no">id</th>
          <th class="no">Date</th>
          <th class="no">Project</th>
          <th class="no">Account</th>
          <th class="no">Amount</th>
          <th class="no">Paid Through</th> 
          <th class="no">Vendor/Supplier</th>
          <th class="no">Type</th>
          <th class="no">Fiscal Year</th>
          <th class="no">Created By</th>
          </tr>
        </thead>
        <tbody>
          @foreach($expenses as $odk => $o)
          <tr>
              <td>{{$o->id}}</td>
              <td style="white-space: nowrap;">{{ date('dS M Y',strtotime($o->date)) }}</td>
              <td>{{$o->project_name}}</td>
              <td>{{$o->expenses_account}}</td>
              <td style="white-space: nowrap;">{{ env('APP_CURRENCY') }}{{$o->amount}}</td>
              <td >{{$o->paid_through}}</td>
              <td>{{$o->vendor}}</td>
              <td>{{$o->expense_type}}</td>
              <td>{{$o->fiscal_year}}</td>
              <td>{{$o->username}}</td>
          </tr>
         @endforeach
          
        </tbody> 
       
      </table>
@endsection