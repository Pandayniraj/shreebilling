@extends('layouts.master')
@section('content')
<style>
[data-letters]:before {
content:attr(data-letters);
display:inline-block;
font-size:1em;
width:2.5em;
height:2.5em;
line-height:2.5em;
text-align:center;
border-radius:50%;
text-transform: capitalize;
background:red;
vertical-align:middle;
margin-right:0.3em;
color:white;
}
</style>
  <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h2>
             <a href="#" data-toggle="modal" data-target="#saveLogo">
              <small data-letters="{{mb_substr($appraisal->username,0,1)}}"></small>
            </a>
           			{{ucfirst(trans($appraisal->username))}}
                </h2>

                   <p> {{$appraisal->general_remarks}}</p>

        </section> 
<div>
	<?php 
	$technical_competency =['None','Beginner','Intermediate','Advanced','Expert leader'];
    $behavioural_competency= ['None','Behavioural','Intermediate', 'Advanced'];
	?><div class="col-md-12">
		<div >
			<p> <i class="fa    fa-calendar-plus-o"></i> Date: 
                    <a style="color: black" >{{date('F Y', strtotime($appraisal->appraisal_month))}}</a> 
                  </p>
		</div>
	</div>
        <div class='col-md-4'>
        	    <p> <i class="fa   fa-user-secret"></i> Customer experiece: 
                    <a style="color: black" >{{ $technical_competency[$appraisal->customer_experiece_management] }}</a> 
                  </p>
                   <p> <i class="fa   fa-shopping-cart"></i>Marketing: 
                    <a style="color: black" >{{$technical_competency[$appraisal->marketing] }}</a> 
                  </p>
                  <p> <i class="fa   fa-eye"></i>Management: 
                    <a style="color: black" >{{$technical_competency[$appraisal->management] }}</a> 
                  </p>
                  <p> <i class="fa   fa-user-plus"></i>Administration: 
                    <a style="color: black" >{{$technical_competency[$appraisal->administration] }}</a> 
                  </p>
                      <p> <i class="fa    fa-group "></i>Presentation skill:
                    <a style="color: black" >{{$technical_competency[$appraisal->presentation_skill] }}</a> 
                  </p>
                  <p> <i class="fa    fa-check-square"></i>Quality of work:
                    <a style="color: black" >{{$technical_competency[$appraisal->quality_of_work] }} </a> 
                  </p>
                    <p> <i class="fa   fa-flash"></i>Efficiency:
                    <a style="color: black" >{{$technical_competency[$appraisal->efficiency] }} </a> 
                  </p>
                 
                   
        </div>
          <div class='col-md-4'>
          	   <p> <i class="fa   fa-object-ungroup"></i>Integrity:
                    <a style="color: black" >{{$behavioural_competency[$appraisal->integrity] }} </a> 
                  </p>
        	    <p> <i class="fa  fa-street-view"></i>Professionalism: 
                    <a style="color: black" >{{ $behavioural_competency[$appraisal->professionalism] }} </a> 
                  </p>
                    <p> <i class="fa    fa-users"></i>Team work: 
                    <a style="color: black" >{{ $behavioural_competency[$appraisal->team_work] }} </a> 
                  </p>
                   <p> <i class="fa   fa-circle-thin"></i>Critical thinking: 
                    <a style="color: black" >{{  $behavioural_competency[$appraisal->critical_thinking] }} </a> 
                  </p>
                   <p> <i class="fa   fa-fire"></i>Conflict management:
                    <a style="color: black" >{{  $behavioural_competency[$appraisal->conflict_management] }}</a> 
                  </p>
                      <p> <i class="fa  fa-pencil-square-o"></i>Attendance:
                    <a style="color: black" >{{  $behavioural_competency[$appraisal->attendance] }}</a> 
                  </p>
                  
                   <p> <i class="fa    fa-hourglass-end"></i>Ability to meet deadline:
                    <a style="color: black" >{{  $behavioural_competency[$appraisal->ability_to_meed_deadline] }}</a> 
                  </p>

        </div>
</div>
  
<div class="col-md-12" style="margin-left: -5px">
   <div class="form-group">
                        	<a href="javascript:history.back()" class='btn btn-danger'>{{ trans('general.button.close') }}</a>
                        	
	                                <a  href="{{route('admin.performance.edit-appeaisal',$appraisal->performance_appraisal_id)}}" class='btn btn-success'>{{ trans('general.button.edit') }}</a>
	                         
                        </div>
</div>
@endsection