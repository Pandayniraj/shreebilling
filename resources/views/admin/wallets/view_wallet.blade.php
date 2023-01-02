@extends('layouts.master')

@section('content')

<div class="panel panel-danger">
  <div class="panel-heading">My Wallet</div>
  <div class="panel-body">
  	
<table class="table">
	<thead>
		<tr>
			<th >WALLET INFO</th>
	</tr>
	<tr class="bg-blue">
		<th>Name</th>
		<th>Current Balance</th>
		<th>Total Transaction</th>
		<th></th>
	</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{$mywallet->user->first_name}} {{$mywallet->user->last_name}}</td>
			<th>{{env('APP_CURRENCY')}}, {{$mywallet->balance}}</th>
			<td style="text-indent: 20px;">{{count($walletTransaction)}}<td>
		</tr>
</tbody>
</table>

  	<table class="table" style="padding: 0;margin: 0;">
  		<thead>
  			
  			<th colspan="5">TRANSACTION SUMMARY</th>
  		</thead>
  		<tr class="bg-olive">
  			<th class="col-md-1">Id</th>
  			<th>Type</th>
  			<th>Amount</th>
  			<th>Date</th>
  			<th>Remark</th>
  		</tr>

  		<tbody>
  			@foreach($walletTransaction as $key=>$trans)
  			<tr>
  				
  				<th class="col-md-1">#{{$trans->transaction_id}}</th>
  				<td>{{ ucfirst($trans->transaction->type) }}</td>
  				<td><b>{{env('APP_CURRENCY')}}</b>,<b>{{abs($trans->amount)}}</b></td>
  				<td>{{date('dS  M Y h:i:s A',strtotime($trans->created_at))}}</td>
  				<td>{{ $trans->transaction->remarks ??'----'}}</td>
  			</tr>
  			@endforeach
  		</tbody>
  	</table>


  </div>
</div>
@endsection