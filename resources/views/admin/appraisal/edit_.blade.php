@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
               {{$page_title ?? "Page Title"}}
                <small>{{$page_description ?? "Page Description"}}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
        <form method="post" action="{{route('admin.performance.edit-appeaisal',$userappeaisal->performance_appraisal_id)}}">
 <div class="panel panel-custom">
      <div class="panel-heading">
<div class="row">
    <div class="col-sm-12">
                <div  class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="user_info" class="col-sm-3 control-label">Employee<span
                                class="required">*</span></label>

                    <div class="col-sm-5">
                            <div class="input-group">
                                <input required="" type="text" class="form-control date_in" value="{{ucfirst(trans($userappeaisal->username))}}" id="user_id" disabled="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa  fa-user"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="user_info" class="col-sm-3 control-label">Evaluator<span
                                class="required">*</span></label>

                    <div class="col-sm-5">
                            <div class="input-group">
                                <input required="" type="text" class="form-control date_in" value="{{ucfirst(trans($userappeaisal->username))}}" id="user_id" disabled="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa  fa-user"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>


                      <div class="form-group">
                        <label for="date_in" class="col-sm-3 control-label">Month<span class="required"> *</span></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input required="" type="text" class="form-control date_in" value="{{ isset($userappeaisal->appraisal_month) ? $userappeaisal->appraisal_month : '' }}" id="appraisal_month" disabled="">
                                <div class="input-group-addon" >
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>

                      


                    </div>



                </div>
    </div>

</div>
<div class="row">

<div class="col-sm-6">
                                        <div class="panel panel-custom">
                                            <h2 class="panel-title"> Functional Skills </h2><br/>

                                            <div class="bg-info box-header">

                                                <span class="panel-title pull-left">Quality of Work &nbsp; </span>
                                            <span class="panel-title pull-left">
                                                <span class="badge bg-info"> 15</span>
                                             </span>

                                             <span class="panel-title pull-right">
                                                <span class="badge bg-yellow">{{ $a1 = $userappeaisal->accuracy_neatness + $userappeaisal->adherence_to_duties + $userappeaisal->synchronization}}</span>
                                             </span>
                                                
                                            </div>


                                            <div class="box-body">
                                                <br/>

                                                <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Accuracy, neatness and timeliness of work</span>
                                                    <div class="col-sm-4">
                                                        <select name="accuracy_neatness"
                                                                class="form-control">
                                                            @foreach($qw as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->accuracy_neatness == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                    
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Adherence to duties and procedures in Job Description and Work Instructions</span>
                                                    <div class="col-sm-4">
                                                        <select name="adherence_to_duties" class="form-control">
                                                            @foreach($qw as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->adherence_to_duties == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Synchronization with organizations/functional goals</span>
                                                    <div class="col-sm-4">
                                                        <select name="synchronization" class="form-control">
                                                             @foreach($qw as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->synchronization == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            
                                           
                                           
                                        </div>
                                    </div>




                            <div class="panel panel-custom">
                                            <div class="bg-info box-header">
                                                <span class="panel-title pull-left">Work Habits &nbsp; </span>
                                                <span class="panel-title pull-left">
                                                <span class="badge bg-default">20</span>
                                                 </span>

                                                  <span class="panel-title pull-right">
                                                <span class="badge bg-yellow">{{ $a2 = $userappeaisal->punctuality + $userappeaisal->attendance + $userappeaisal->stay_busy + $userappeaisal->submit_report}}</span>
                                             </span>
                                                
                                            </div>


                                            <div class="box-body">
                                                <br/>

                                                <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Punctuality to workplace</span>
                                                    <div class="col-sm-4">
                                                        <select name="punctuality"
                                                                class="form-control">
                                                            @foreach($wh as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->punctuality == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                    
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Attendance</span>
                                                    <div class="col-sm-4">
                                                        <select name="attendance" class="form-control">
                                                             @foreach($wh as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->attendance == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Does the employee stay busy, look for things to do, takes initiatives at workplace</span>
                                                    <div class="col-sm-4">
                                                        <select name="stay_busy" class="form-control">
                                                             @foreach($wh as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->stay_busy == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>

                                             <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Submits reports on time and meets deadlines</span>
                                                    <div class="col-sm-4">
                                                        <select name="submit_report" class="form-control">
                                                             @foreach($wh as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->submit_report == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                        </div>
                                    </div>



                                     <div class="panel panel-custom">
                                            <div class="bg-info box-header">
                                                <span class="panel-title pull-left">Job Knowledge &nbsp; </span>
                                                <span class="panel-title pull-left">
                                                <span class="badge bg-default">15</span>
                                                  </span>

                                                   <span class="panel-title pull-right">
                                                <span class="badge bg-yellow">{{ $a3 = $userappeaisal->skill_and_ability + $userappeaisal->shown_interest + $userappeaisal->problem_solving}}</span>
                                             </span>
                                                
                                            </div>


                                            <div class="box-body">
                                            <div class="row">
                                                    
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Skill and ability to perform job satisfactorily</span>
                                                    <div class="col-sm-4">
                                                        <select name="skill_and_ability" class="form-control">
                                                             @foreach($jk as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->skill_and_ability == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Shown interest in learning and improving</span>
                                                    <div class="col-sm-4">
                                                        <select name="shown_interest" class="form-control">
                                                            @foreach($jk as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->shown_interest == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>

                                             <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Problem solving ability</span>
                                                    <div class="col-sm-4">
                                                        <select name="problem_solving" class="form-control">
                                                           @foreach($jk as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->problem_solving == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                        </div>
                                    </div>



                               </div>






            <div class="col-sm-6">


                    <div class="panel panel-custom">
                         <h2 class="panel-title"> Interpersonal Skills </h2><br/>

                                            <div class="bg-success box-header">
                                                <span class="panel-title pull-left">Interpersonal relations/ behaviour &nbsp; </span>
                                                <span class="panel-title pull-left">
                                                    <span class="badge bg-default">25</span>
                                                  </span>

                                                   <span class="panel-title pull-right">
                                                <span class="badge bg-yellow">{{ $a4 = $userappeaisal->responds + $userappeaisal->positively + $userappeaisal->informed + $userappeaisal->adapts_well + $userappeaisal->seeks_feedback}}</span>
                                             </span>
                                                
                                            </div>


                                            <div class="box-body">
                                            <div class="row">
                                                    
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Responds and contributes to team efforts</span>
                                                    <div class="col-sm-4">
                                                        <select name="responds" class="form-control">
                                                <div class="form-group" id="border-none">
                                                            @foreach($ir as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->responds == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Responds positively to suggestions and instructions and criticism</span>
                                                    <div class="col-sm-4">
                                                        <select name="positively" class="form-control">
                                                <div class="form-group" id="border-none">
                                                             @foreach($ir as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->positively == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>

                                             <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Keeps supervisor informed of all details</span>
                                                    <div class="col-sm-4">
                                                        <select name="informed" class="form-control">
                                                <div class="form-group" id="border-none">
                                                             @foreach($ir as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->informed == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            

                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Adapts well to changing circumstances</span>
                                                    <div class="col-sm-4">
                                                        <select name="adapts_well" class="form-control">
                                                          @foreach($ir as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->adapts_well == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Seeks feedback to improve</span>
                                                    <div class="col-sm-4">
                                                        <select name="seeks_feedback" class="form-control">
                                                            @foreach($ir as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->seeks_feedback == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>


                                        </div>
                                    </div>





                    <div class="panel panel-custom">
                         <h2 class="panel-title"> Leadership Skills </h2><br/>

                                            <div class="bg-warning box-header">
                                                <span class="panel-title pull-left">Interpersonal relations/ behaviour &nbsp; </span>
                                                <span class="panel-title pull-left">
                                                <span class="badge bg-default">25</span>
                                             </span>

                                              <span class="panel-title pull-right">
                                                <span class="badge bg-yellow">{{ $a5= ceil($userappeaisal->aspirant_to + $userappeaisal->innovative + $userappeaisal->motivation) }}</span>
                                             </span>
                                                
                                            </div>


                                            <div class="box-body">
                                            <div class="row">
                                                    
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Aspirant to climb up the ladder, accepts challenges, new responsibilities and roles. (out of 10)</span>
                                                    <div class="col-sm-4">
                                                        <select name="aspirant_to" class="form-control">
                                                            @foreach($ls as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->aspirant_to == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Innovative thinking - contribution to organizations and functions and personal growth. (out of 10)</span>
                                                    <div class="col-sm-4">
                                                        <select name="innovative" class="form-control">
                                                            @foreach($ls as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->innovative == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>

                                             <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <span
                                                        class="col-sm-8">Work motivation. (out of 5)</span>
                                                    <div class="col-sm-4">
                                                        <select name="motivation" class="form-control">
                                                             @foreach($ls as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->motivation == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>


                                        </div>
                                    </div>






</div>


<div class="clearfix"></div>

<p>
    Score : {{ $a1 + $a2 + $a3 + $a4 + $a5}}
</p>
<div class="row">
     <div class="panel panel-custom">
     <div class="col-md-6">

        <label for="inputEmail3" class="control-label">
        Final Comments 
        </label>
          
          <textarea class="form-control" name="general_remarks" id="general_remarks" placeholder="Write Description">{!! $userappeaisal->general_remarks !!}</textarea>
        </div>

        <div class="col-md-6">
        <label for="inputEmail3" class="control-label">
        Recommendations 
        </label>
          
          <textarea class="form-control" name="recommendation" id="recommendation" placeholder="Write Recommendation">{!! $userappeaisal->recommendation !!}</textarea>
        </div>
</div></div>
         

<div class="col-md-12">
    <br>
        <div class="form-group">
            <button class="btn btn-primary" id="btn-submit-edit" type="submit" >Update</button>
            <a class="btn btn-default" href="/admin/performance/appraisal">Close</a>
        </div>
  
    </div>
</div>
</form>
</div>
</div>
@endsection


