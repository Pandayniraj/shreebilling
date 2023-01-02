<h3>{{ env('APP_NAME')}} , {{ $user->first_name }} {{ $user->last_name }} has been disabled</h3>
<p>Following are the details.</p>
<p>
	<strong>Username: </strong> {{ $user->username }} <br/>
	<strong>Full Name</strong>  {{ $user->first_name }} {{ $user->last_name }} <br/>

	<?php 
	$userdetails= $user->userDetail;
	  $service_tenure = strtotime($userdetails->join_date);
	if($service_tenure){

	    $datetime1 = new \DateTime($userdetails->join_date);
	    $datetime2 = new \DateTime(date('Y-m-d'));
	    $interval = $datetime1->diff($datetime2);
	    
	    $requiredData['service_tenure'] = $interval->format('%y years %m months %d days');
	}else{
	    $requiredData['service_tenure'] = '';  
	}



	?>
	<strong>Working Days</strong> {{ $requiredData['service_tenure'] }}
</p>