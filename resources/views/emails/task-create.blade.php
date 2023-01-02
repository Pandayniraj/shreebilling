<h3>{{ env('APP_NAME')}} New Task has been created for you</h3>
<p>Following are the details.</p>
<p>
	<strong>BY: </strong>{{ \TaskHelper::getUser(\Auth::user()->id)->first_name }}<br/>
	<strong>Note: </strong>{{ $mailcontent->task_detail }} {{ $mailcontent->task_subject }}<br/>
</p>