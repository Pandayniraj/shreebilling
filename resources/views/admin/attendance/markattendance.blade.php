@extends('layouts.master')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title"}} 
                <small> {{ $page_description ?? "Page Description" }}</small>
            </h1>
            {{ TaskHelper::topSubMenu('topsubmenu.hr')}}
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>


<div class="box box-primary">
    <div class="box-header with-border">  
       
       <table class="table table-hover table-no-border" id="leads-table">

		<thead>
		    <tr class="bg-success">
		        <th>EMP UserName</th>   
		        <th>Name</th>
		        <th>Clocking Hours</th>
		    </tr>
		</thead>
		<tbody>
		    @foreach($users as $key=>$att)

		    <tr>
		        <td>{{ $att->username }}</td>
		        <td>{{ $att->full_name }} </td>
		        <td>
		        	<?php 
                        $mark_attendence_log =  \App\Models\Attendance::where('user_id', $att->id)->where('clocking_status', '1')->first();
		        	 ?>
		            @if(!$mark_attendence_log)    
		            <a class="btn btn-success btn-xs" href="#" onclick="clockin('{{ $att->id }}')";> 
		              <i class="fa fa-clock-o"></i> Clock In
		            </a>
		            @else
		            <a class="btn btn-success btn-xs" href="#" onclick="clockout('{{ $att->id }}')">
		              <i class="fa fa-clock-o"></i> Clock Out 
		            </a>
		            <small>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($mark_attendence_log->created_at))->diffForHumans() }}...</small>
		            @endif
		        </td> 
		    </tr>
		    @endforeach

      </tbody>
   </table>
 </div>
</div>


 <script type="text/javascript">
 	const options = {
  enableHighAccuracy: true,
  timeout: 5000,
  maximumAge: 0
};

var user_id = null;


function getLocation() {

    return new Promise((resolve,reject) => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                return resolve(position);
            }, function(err) {
                return reject(err);
            });

        } else {
            return reject(false);
        }
    })

}

function clockin(user_id){
	getLocation().then(response=>{
		  let  crd = response.coords;
         let location = JSON.stringify({lat: crd.latitude,long: crd.latitude});
		console.log(user_id,location);
		 window.location = `/markattendence/${user_id}/clockin?location=${location}`;
	}).catch(err=>{
              window.location = `/markattendence/${user_id}/clockin`;
	});
}

function clockout(user_id){
	getLocation().then(response=>{
		  let  crd = response.coords;
         let location = JSON.stringify({lat: crd.latitude,long: crd.latitude});
		console.log(user_id,location);
		 window.location = `/markattendence/${user_id}/clockout?location=${location}`;
	}).catch(err=>{
     window.location = `/markattendence/${user_id}/clockout`;
	});
}




  
 </script>


@endsection