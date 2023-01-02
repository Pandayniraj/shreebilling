<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>

<body>

<br>
<div class="footer">
	<h3>Please find the purchase orders an an pdf attachment:</h3>
</div>
<p> If you have any questions or concerns, please contact us.</p> 
<p> Thank You</p>
<p> {{ env('APP_COMPANY') }}</p>
<table>
	<tr>
		<td style="width: 200px;">
			<img src="{{env('APP_URL')}}/org/{{ $organization->logo }}" width="200">
		</td>
	</tr>
	<tr>
		<td rowspan="3" style="width: 200px;padding-right: 90px">
		
			{{ env('APP_ADDRESS2') }}<br>
			{{ env('APP_ADDRESS1') }}<br><br>
			Tax ID: {{ env('TPID')}}
		</td>
	</tr>
</table>
</body>
</html>        