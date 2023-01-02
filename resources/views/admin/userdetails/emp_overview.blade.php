<style type="text/css">
	.circle_icon{
		border-radius: 500px;height: 40px;width: 40px;
	}
	.circle_icon .fa{
		font-size: 1.33333333em;    
		line-height: 0.75em;
		vertical-align: -15%;
		margin: auto;
		color: white;						
		position: absolute; 
		top: 15px;
		left: 25px;
	}
	.content_status{

		position: absolute;margin-top: -75px;margin-left: 40px;
	}
	.content_status h3{
		font-size: 16px;font-weight: 300;
	}
	.content_status small{
		position: absolute;margin-top: -8px;font-size: 13px;font-weight: 300;
	}
	.attendace{

		margin-bottom: 20px;




	}
</style>

<div class="row">
	

	<div class="col-md-4">

		<div class="panel">
		   <div class="panel-body" style="margin: 0;padding: 0;">
		      <ul class="list-group list-group-unbordered"style="margin: 0;padding: 0;">
		         <li class="list-group-item">
		            <strong style="font-size: 16.5px;">Key Information</strong>
		         </li>
		         <li class="list-group-item">
		            <i class="fa  fa-envelope"></i>&nbsp;&nbsp;{{ $user->email }}
		         </li>
		         <li class="list-group-item">
		            <i class="fa  fa-phone"></i>&nbsp;&nbsp;{{ $user->phone }}
		         </li>
		         <li class="list-group-item">
		            <strong>{{ ucfirst($user_detail->employemnt_type) }}</strong>
		         </li>
		         <li class="list-group-item">
		            Join Date: {{ $user_detail->join_date  }} A.D
		         </li>
		         @if(isset($current_employement))
		         <li class="list-group-item">
		         	{{ ucfirst($current_employement->employment_type) }} Since, {{ $current_employement->getActiveTime() }}
		         </li>

		         @endif



		      </ul>
		   </div>
		</div>
		@php $lineManager = $user->firstLineManger; 

			$secondManager = $user->secondLineManger;
		 @endphp 
		@if($lineManager)
		<div class="panel">
		   <div class="panel-body" style="margin: 0;padding: 0;">
		      <ul class="list-group list-group-unbordered"style="margin: 0;padding: 0;">
		         <li class="list-group-item">
		            <strong style="font-size: 16.5px;">Supervisor</strong>
		         </li>
		         <li class="list-group-item">
		            <h5 style="white-space: nowrap;">
                    <img src="{{ $lineManager->image?'/images/profiles/'.$lineManager->image:$lineManager->avatar }}" alt="User Image"

                    style="height: 30px;width: 30px;">
             
                  <span style="margin-top: 5px;font-size: 16.5px;">&nbsp;{{ $lineManager->first_name }} {{ $lineManager->last_name }}</span>
                  <br>
                    <small style="margin-left: 35px;"> {{ $lineManager->designation->designations}}</small>
                </h5>
		         </li>
		         @if($secondManager)
		         <li class="list-group-item">
		            <h5 style="white-space: nowrap;">
                    <img src="{{ $secondManager->image?'/images/profiles/'.$secondManager->image:$secondManager->avatar }}" alt="User Image"

                    style="height: 30px;width: 30px;">
             
                  <span style="margin-top: 5px;font-size: 16.5px;">&nbsp;{{ $secondManager->first_name }} {{ $secondManager->last_name }}</span>
                  <br>
                    <small style="margin-left: 35px;"> {{ $secondManager->designation->designations}}</small>
                </h5>
		         </li>
		         @endif

		      </ul>
		   </div>
		</div>
		@endif

		<div class="panel">
		   <div class="panel-body" style="margin: 0;padding: 0;">
		      <ul class="list-group list-group-unbordered"style="margin: 0;padding: 0;">
		         <li class="list-group-item">
		            <strong style="font-size: 16.5px;">Notice Board Activities</strong>
		         </li>
		         @if(isset($news_feed_info))
		         <li class="list-group-item">
		          	<i class="fa fa-comment"></i> Has {{ $news_feed_info['posted'] }} posts with 
		          	{{$news_feed_info['posted_likes']}} likes & {{$news_feed_info['posted_comment']}} comments total.
		         </li>
		        <li class="list-group-item">
		          	<i class="fa fa-thumbs-o-up"></i> Has liked {{$news_feed_info['liked']}} items & posted {{$news_feed_info['comment']}} comments..
		         </li>
		         @endif
		      </ul>
		   </div>
		</div>

	</div>

	<div class="col-md-8">
		<div class="box">
			<div class="box-header bg-olive">
				<div class="row">
				<div class="col-sm-6">
					<strong style="font-size: 16.5px;">Attendance</strong>
				</div>
				<div class="col-sm-6">
					<select class="form-control input-sm" 
					style="color: black;padding: 2px;float: right;width: 100px;"
					>
						<option value="">This Year</option>
					</select>
				</div>
			</div>
			</div>
			<div class="box-body">
				<div class="row" >
					
					<div class="col-md-4 attendace">
						
						<div class="circle_icon" style="background: #4CAF50;">
							<i class="fa fa-fast-forward"></i>
						</div>
						@if(isset($attendace_summary))
						<div class="content content_status">
							<h3 >Avg. In Time</h3>
							<small>{{ $attendace_summary['avgInTime'] ? $attendace_summary['avgInTime']: 'N/A'    }}</small>
						</div>
						@endif
					</div>

					<div class="col-md-4 attendace">
						
						<div class="circle_icon" style="background: #2196F3;">
							<i class="fa  fa-clock-o " style="margin-left: 2px;margin-top: -1px;"></i>
						</div>
						@if(isset($attendace_summary))
						<div class="content content_status">
							<h3 >Avg. Stay</h3>
							<small>{{ $attendace_summary['averageStay'] ?? 'N/A '  }} hours</small>
						</div>
						@endif
					</div>

					<div class="col-md-4 attendace">
						
						<div class="circle_icon" style="background: red;">
							<i class="fa  fa-paper-plane"></i>
						</div>
						@if(isset($attendace_summary))
						<div class="content content_status">
							<h3 >Avg. Leaves</h3>
							<small>{{ $attendace_summary['leaveAvg'] ?? 'N/A '  }} days</small>
						</div>
						@endif
					</div>

					<div class="col-md-4 attendace">
						
						<div class="circle_icon" style="background: orange;">
							<i class="fa fa-fast-backward"></i>
						</div>
						@if(isset($attendace_summary))
						<div class="content content_status">
							<h3 >Avg. Out Time</h3>
							<small>{{ $attendace_summary['avgOutTime'] ? $attendace_summary['avgOutTime']: 'N/A' }}</small>
						</div>
						@endif

					</div>

					<div class="col-md-4 attendace">
						
						<div class="circle_icon" style="background: #2196F3;">
							<i class="fa fa-clock-o" style="margin-left: 2px;margin-top: -1px;" ></i>
						</div>
						@if(isset($attendace_summary))
						<div class="content content_status">
							<h3 >Total Stay</h3>
							<small> 
								{{ $attendace_summary['totalStay'] ?? 'N/A ' }} hours
							</small>
						</div>
						@endif
					</div>


					<div class="col-md-4 attendace">
						
						<div class="circle_icon" style="background: red;">
							<i class="fa fa-paper-plane fa-flip-horizontal"></i>
						</div>
						@if(isset($attendace_summary))
						<div class="content content_status">
							<h3 >Total Leaves</h3>
							<small>{{ $attendace_summary['leaveTotal']  }} days</small>
						</div>
						@endif
					</div>




				</div>
			</div>
		</div>
	</div>
	




</div>