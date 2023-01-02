
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
                                                      {{$technical_competency[$userappeaisal->customer_experiece_management]}}
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                    
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Marketing</label>
                                                    <div class="col-sm-5">
                                                  
                                                          {{$technical_competency[$userappeaisal->marketing]}}
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Management</label>
                                                    <div class="col-sm-5">
                                                         {{$technical_competency[$userappeaisal->management]}}
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Administration</label>
                                                    <div class="col-sm-5">
                                                {{$technical_competency[$userappeaisal->administration]}}
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Presentation skill</label>
                                                    <div class="col-sm-5">
                                            {{$technical_competency[$userappeaisal->presentation_skill]}}

                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label"> Quality of work</label>
                                                    <div class="col-sm-5">
                                            
                                             {{$technical_competency[$userappeaisal->quality_of_work]}}
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Efficiency</label>
                                                    <div class="col-sm-5">
                                              {{$technical_competency[$userappeaisal->efficiency]}}
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
                                                        

                                                    {{$behavioural_competency[$userappeaisal->integrity]}}
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                    
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Professionalism</label>
                                                    <div class="col-sm-5">
                                    
                                            {{$behavioural_competency[$userappeaisal->professionalism]}}
                                                        
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Team Work</label>
                                                    <div class="col-sm-5">
                                                       
                                                 {{$behavioural_competency[$userappeaisal->team_work]}}
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Critical thinking</label>
                                                    <div class="col-sm-5">
                                                    
                                            {{$behavioural_competency[$userappeaisal->critical_thinking]}}
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Conflict Management</label>
                                                    <div class="col-sm-5">
                                    
                                             {{$behavioural_competency[$userappeaisal->conflict_management]}}
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Attendance</label>
                                                    <div class="col-sm-5">
                                                {{$behavioural_competency[$userappeaisal->attendance]}}
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="form-group" id="border-none">
                                                    <label
                                                        class="col-sm-6  control-label">Ability To Meet Deadline</label>
                                                    <div class="col-sm-5">
                                                    
                                    {{$behavioural_competency[$userappeaisal->ability_to_meed_deadline]}}
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
          <textarea class="form-control" name="general_remarks" id="general_remarks" placeholder="Write Description" disabled="">{!! $userappeaisal->general_remarks !!}</textarea>
        </div>
         

<div class="col-md-12">
    <br>
        <div class="form-group">
          <a href="{{route('admin.performance.edit-appeaisal',$userappeaisal->performance_appraisal_id)}}"><button class="btn btn-primary" id="btn-submit-edit" >Edit</button></a>
        </div>
  
    </div>
</div>
