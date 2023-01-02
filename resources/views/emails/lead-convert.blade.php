<h3>{{ env('APP_NAME')}} Lead has been converted to potential</h3>
<p>Following are the details.</p>
<p>
	<strong>Transfer By: </strong>{{ \TaskHelper::getUser(\Auth::user()->id)->first_name }}<br/>
	<strong>Lead ID: </strong>{{ $updateLeadTable->name }}<br/>
	<strong>Amount: </strong>{{ $updateLeadTable->amount }}<br/>
</p>