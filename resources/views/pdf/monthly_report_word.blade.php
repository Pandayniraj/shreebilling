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
	<?php  
         $i= 0;
	 ?>
	@foreach($data as $dat)
	
  <li><span class="title">{{$dat->first()[0]->category->name}} (Total Work:{{$count_task[$i++]}})</span>

  	    <ol>
  	    	@foreach($dat as $d)
		    
		    	<li><span class="title">{{$d[0]->tasksubcat->name}} ({{count($d)}})</span></li>
		    	<ol>
		    	@foreach($d as $key=>$mr)
		      <li><span class="title">{{$mr->subject}} 
		      	@if(Auth::user()->hasRole('admins'))
		      		(#{{ $mr->id }})  	
		      	@endif
		      	</span>
				<dl>
					@if($mr->description)
						<dd>{!! $mr->description !!}</dd>
					@endif
					@if($mr->attachment)
					<dt>Attachment </dt>
					<dd>
						<a target="_blank" href="#">
							<img src="{{ public_path().'/task_attachments/'.$mr->attachment }}" alt="{{ $mr->subject }}" style="width:150px">
						</a>
					</dd>
					@endif
				</dl>
				@if(count($mr->projectTaskAttachent) > 0 )
					<br><br>
					<ul id="menu">
					@foreach($mr->projectTaskAttachent as $pta)
					<li>
						@if(getimagesize(url('/').'/task_attachments/'.$pta->attachment))
						<a target="_blank" href="#">
							<img src="{{ public_path().'/task_attachments/'.$pta->attachment }}" alt="{{ $mr->subject }}" style="width:150px;max-height: 410px;">
						</a>
						@else
							{{$pta->attachment}} FILE
						@endif
					</li>
					@endforeach

					</ul>
					@endif
		      </li>
		      @endforeach
		    </ol>
		    @endforeach
		 </ol>
    @endforeach
  </li>
</ol>
</section>

</body>
</html>