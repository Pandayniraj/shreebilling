@extends('layouts.reportmaster')
@section('content')
<table>
	<tr>
		<td>Account Name</td>
		<td>{{$account->account_name}}</td>
	</tr>
	<tr>
		<td>Account Code</td>
		<td>{{$account->account_code}}</td>
	</tr>
	<tr>
		<td>Account Number</td>
		<td>{{$account->account_number}}</td>
	</tr>
	<tr>
		<td>Bank Name</td>
		<td>{{$account->bank_name}}</td>
	</tr>
	<tr>
		<td>Currency</td>
		<td>{{$account->currency}}</td>
	</tr>
	<tr>
		<td>Routing Number</td>
		<td>{{$account->routing_number}}</td>
	</tr>
</table>

      <table >
        <thead>
          <tr>
          	<th>Id</th>
	        <th>Reference</th>
	        <th>Tags</th>
	        <th>Income Type</th>
	        <th>Amount Deposited</th>
	        <th>Date</th>
	        <th>Fiscal Year</th>
	        <th>Created By</th>
          </tr>
        </thead>
        <tbody>
          @foreach($income as $i)
            <tr>
                <td>{{\FinanceHelper::getAccountingPrefix('INCOME_PRE')}}{{$i->id}}</td>
                <td>{{mb_substr($i->reference_no,0,12)}}</td>
                <td>{{$i->tag->name}}</td>
                <td>
                    
                    {{$types[$i->income_type]}}
                    <small>
                        @if(strlen($i->customers->name) > 25) {{ substr($i->customers->name,0,25).'...' }} @else {{ ucfirst($i->customers->name) }} @endif
                    </small>
                    
                </td>
                <td>{{ env('APP_CURRENCY') }} @money($i->amount)</td>
                <td>{{date('d M y',strtotime($i->date_received))}}</td>
                <td>{{ $i->fiscalyear->fiscal_year }}</td>
                <td>{{$i->user->username}}</td>
            </tr>
            @endforeach
          
        </tbody> 
       
      </table>
@endsection