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
                        <label for="date_in" class="col-sm-3 control-label">Month<span class="required"> *</span></label>
                        <div class="col-sm-5">
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
                                            <div class="panel-heading">
                                                <h4 class="panel-title"
                                                    style="margin-left: 8px;">Technical Competency </h4>
                                            </div>
                                            <div style="min-height: 2px;background-color: red;width: 100%;"></div>
                                            <div class="box-body ">
                                                <br/>

                                                <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Customer experiece management</label>
                                                    <div class="col-sm-5">
                                                        <select name="customer_experiece_management"
                                                                class="form-control">
                                                            @foreach($technical_competency as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->customer_experiece_management == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                    
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Marketing</label>
                                                    <div class="col-sm-5">
                                                        <select name="marketing" class="form-control">
                                                                  @foreach($technical_competency as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->marketing == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Management</label>
                                                    <div class="col-sm-5">
                                                        <select name="management" class="form-control">
                                                                @foreach($technical_competency as $key=>$tm)
                                                           <option value="{{$key}}" @if($userappeaisal->management == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Administration</label>
                                                    <div class="col-sm-5">
                                                        <select name="administration" class="form-control">
                                                                 @foreach($technical_competency as $key=>$tm)
                                                            <option value="{{$key}}" @if($userappeaisal->administration == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Presentation skill</label>
                                                    <div class="col-sm-5">
                                                        <select name="presentation_skill" class="form-control">
                                                                 @foreach($technical_competency as $key=>$tm)
                                                           <option value="{{$key}}" @if($userappeaisal->presentation_skill == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                          
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label"> Quality of work</label>
                                                    <div class="col-sm-5">
                                                        <select name="quality_of_work" class="form-control">
                                                               @foreach($technical_competency as $key=>$tm)
                                                         <option value="{{$key}}" @if($userappeaisal->quality_of_work == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Efficiency</label>
                                                    <div class="col-sm-5">
                                                        <select name="efficiency" class="form-control">
                                                            @foreach($technical_competency as $key=>$tm)
                                                      <option value="{{$key}}" @if($userappeaisal->efficiency == $key) selected @endif>{{$tm}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                    </div>

                        <div class="col-sm-6">
                                        <div class="panel panel-custom">
                                            <div class="panel-heading">
                                                <h4 class="panel-title"
                                                    style="margin-left: 8px;">Behavioural Competency</h4>
                                            </div>
                                            <div style="min-height: 2px;background-color: red;width: 100%;"></div>
                                            <div class="box-body ">
                                                <br/>

                                                <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Integrity</label>
                                                    <div class="col-sm-5">
                                                        <select name="integrity"
                                                                class="form-control">
                                             
                                                            @foreach($behavioural_competency as $key=>$bc)
                                                           <option value="{{$key}}" @if($userappeaisal->integrity == $key) selected @endif>{{$bc}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                    
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Professionalism</label>
                                                    <div class="col-sm-5">
                                                        <select name="professionalism" class="form-control">
                                                               @foreach($behavioural_competency as $key=>$bc)
                                                           <option value="{{$key}}" @if($userappeaisal->professionalism == $key) selected @endif>{{$bc}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Team Work</label>
                                                    <div class="col-sm-5">
                                                        <select name="team_work" class="form-control">
                                                              @foreach($behavioural_competency as $key=>$bc)
                                                           <option value="{{$key}}" @if($userappeaisal->team_work == $key) selected @endif>{{$bc}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Critical thinking</label>
                                                    <div class="col-sm-5">
                                                        <select name="critical_thinking" class="form-control">
                                                            @foreach($behavioural_competency as $key=>$bc)
                                                          <option value="{{$key}}" @if($userappeaisal->critical_thinking == $key) selected @endif>{{$bc}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Conflict Management</label>
                                                    <div class="col-sm-5">
                                                        <select name="conflict_management" class="form-control">
                                                           @foreach($behavioural_competency as $key=>$bc)
                                                          <option value="{{$key}}" @if($userappeaisal->conflict_management == $key) selected @endif>{{$bc}}</option>
                                                            @endforeach
                                                          
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Attendance</label>
                                                    <div class="col-sm-5">
                                                        <select name="attendance" class="form-control">
                                                             @foreach($behavioural_competency as $key=>$bc)
                                                        <option value="{{$key}}" @if($userappeaisal->attendance == $key) selected @endif>{{$bc}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Ability To Meet Deadline</label>
                                                    <div class="col-sm-5">
                                                        <select name="ability_to_meed_deadline" class="form-control">
                                                          @foreach($behavioural_competency as $key=>$bc)
                                                          <option value="{{$key}}" @if($userappeaisal->ability_to_meed_deadline == $key) selected @endif>{{$bc}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


</div>


     <div class="col-md-12">
        <label for="inputEmail3" class="control-label">
        General remarks 
        </label>
          
          <textarea class="form-control" name="general_remarks" id="general_remarks" placeholder="Write Description">{!! $userappeaisal->general_remarks !!}</textarea>
        </div>
         

<div class="col-md-12">
    <br>
        <div class="form-group">
            <button class="btn btn-primary" id="btn-submit-edit" type="submit" >Update</button>
            <a class="btn btn-default" href="/admin/performance/appraisal">Cancel</a>
        </div>
  
    </div>
</div>
</form>
</div>
</div>
@endsection


