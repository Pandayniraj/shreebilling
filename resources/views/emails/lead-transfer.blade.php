<h3>{{ env('APP_NAME')}} Lead has been transfered to  you</h3>
<p>Following are the details.</p>
<p>
	<strong>Transfer By: </strong>{{ \TaskHelper::getUser(\Auth::user()->id)->first_name }}<br/>
	<strong>Lead ID: </strong>{{ $transfer->lead_id }}<br/>
</p>