@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
               Indicator
                <small>Indicator Form</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
        <form method="post" action="/admin/performance/performance-indicator">   
 <div class="panel panel-custom">
      <div class="panel-heading">
<div class="row">
    <div class="col-sm-12"> 
                <div  class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="user_id" class="col-sm-3 control-label">Designations<span
                                class="required">*</span></label>
                        <div class="col-sm-5">
                            <select required name="designations_id" id="user_id" class="form-control select_box">
                                <option value="">Select Designations</option>
                                @foreach($department as $dep)
                                  <optgroup label ="{{$dep->deptname}}">
                                    <?php $deps =\App\Models\Designation::where('departments_id',$dep->departments_id)->get();
                                    ?>
                                    @foreach($deps as $d)
                                    <option value="{{$d->designations_id}}">
                                   {{$d->designations}}</option>
                                    @endforeach
                                </optgroup>
                                    @endforeach
                            </select>
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
                                                            <option value="{{$key}}">{{$tm}}</option>
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
                                                            <option value="{{$key}}">{{$tm}}</option>
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
                                                            <option value="{{$key}}">{{$tm}}</option>
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
                                                            <option value="{{$key}}">{{$tm}}</option>
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
                                                            <option value="{{$key}}">{{$tm}}</option>
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
                                                            <option value="{{$key}}">{{$tm}}</option>
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
                                                            <option value="{{$key}}">{{$tm}}</option>
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
                                                            <option value="{{$key}}">{{$bc}}</option>
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
                                                            <option value="{{$key}}">{{$bc}}</option>
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
                                                            <option value="{{$key}}">{{$bc}}</option>
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
                                                            <option value="{{$key}}">{{$bc}}</option>
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
                                                            <option value="{{$key}}">{{$bc}}</option>
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
                                                            <option value="{{$key}}">{{$bc}}</option>
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
                                                            <option value="{{$key}}">{{$bc}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


</div>
<div class="col-md-12">
        <div class="form-group">
            <button class="btn btn-primary" id="btn-submit-edit" type="submit">Save</button>
            <a class="btn btn-default" href="/admin/performance/indicator">Cancel</a>
        </div>
    </div>
</div>

</form>
</div>
</div>
@endsection