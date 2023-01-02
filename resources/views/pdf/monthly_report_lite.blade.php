<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<style type="text/css">
	.title{
		font-weight: 700;
	}
	img {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
  margin-top: 5px;
  width: 150px;
}
#menu li{
	display: inline;
}
li{

	font-size: small;
}
table#ledgend  th, td {
	 border: 1px solid black;
}
</style>
<body>
	<div align="center">
		<h2>{{ $project_name }}
			<p>Periodic Report</p> 
		</h2>
	</div>
	<h3>
		<p>
			Date: {{ date('d M Y', strtotime($start_date)) }} to {{ date('d M Y', strtotime($end_date)) }}  
		</p>
	</h3>

<section>
<ol>
	<?php $i = 0;

	$completed = 0;
         $ongoing = 0;
         $schedule = 0;?>
	@foreach($data as $key => $dat)

		  <li><span class="title">{{$dat->first()[0]->category->name}} (Total Work:{{$count_task[$i]}})</span>
                <ol>
			  	@foreach($dat as $d)
			
			  	<li><span class="title">{{$d[0]->tasksubcat->name}} ({{count($d)}})</span></li>
				    <ol style=" list-style-type: circle;">
				    	@foreach($d as $k=>$mr)

		<?php 
	      	if($mr->schedule == 'on')
	      		$schedule++;
	      	elseif($mr->status == 'completed')
	      		$completed++;
	      	else
	      		$ongoing++;
		   	?> 
				      <li><span @if( $mr->schedule == 'on') style="color: red"  @elseif($mr->status == 'ongoing') style="color: yellow;"  @endif>
				      	{{$mr->subject}}{{$mr->status}}</span>  	
						
				      </li>
				      @endforeach
				    </ol>
		    @endforeach
		   
		</ol>
     <br>
      <?php $i++;?>
    @endforeach
  </li>
</ol>
</section>
<table id='ledgend'>
	<thead>
		<tr>
			<th>Status</th>
			<th>Total</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Completed</td>
			<td>{{$completed}}</td>
		</tr>
		<tr>
			<td>Pending</td>
			<td>{{$ongoing}}</td>
		</tr>
		<tr>
			<td>Schedule</td>
			<td>{{$schedule ?? 0}}</td>
		</tr>
	</tbody>
</table>

</body>
</html>