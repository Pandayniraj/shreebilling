@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
               {{$page_title ?? "Page Title"}}
                <small>{{$page_description ?? "Page Description"}}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
        <form method="post" action="{{route('admin.performance.create-appeaisal')}}">
 <div class="panel panel-custom">
      <div class="panel-heading">
<div class="row">
    <div class="col-sm-12">
                <div  class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="user_info" class="col-sm-3 control-label">Designations<span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select required name="user_info" id="user_info" class="form-control select_box">
                              
                                <option value="">Select Employee</option>
                                    @foreach($department as $dep)
                                  <optgroup label ="{{$dep->deptname}}">
                                    <?php $emp =\App\User::select('users.id','users.username','tbl_designations.designations','users.designations_id')->leftjoin('tbl_designations','tbl_designations.designations_id','=','users.designations_id')->where('users.departments_id',$dep->departments_id)->get();
                                    ?>
                                    @foreach($emp as $e)
                                    <option value="<?php echo serialize([$e->id,$e->designations_id])?>" @if($selecteduser == $e->id) selected @endif>
                                   {{ucfirst(trans($e->username))}}({{$e->designations}})</option>
                                    @endforeach
                                </optgroup>
                                    @endforeach
                             <!--    @foreach($designations as $d)
                                 <option value="{{ $d->designations_id }}" >{{$d->designations}}</option>
                                @endforeach -->
                            </select>

                        </div>
                    </div>
                      <div class="form-group">
                        <label for="date_in" class="col-sm-3 control-label">Month<span class="required"> *</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input required="" type="text" class="form-control date_in" value="{{ isset($selecteddate) ? $selecteddate : '' }}" name="appraisal_month" id="appraisal_month">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="form-group">
                        <label for="date_in" class="col-sm-3 control-label"><span class="required"> </span></label>
                        <div class="col-sm-5">
                             <button class="btn btn-primary" id="btn-submit-edit" type="submit" name="showappeaisal" value="show">Go</button>
                        </div>
                    </div>
                </div>
    </div>

</div>
@if($showappeaisal)
@if($userappeaisal)
<div  align="center" style="margin-left: -150px !important"><label style="color: red"> Appraisal Information Already provided to this user once for:</label> <label>{{date('F Y', strtotime($userappeaisal->appraisal_month))}}</label></div>
@endif 
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
    @if($userappeaisal)
        <div class="form-group">
            <button class="btn btn-primary" id="btn-submit-edit" type="submit" name="updateappeasial" value="update">Update</button>
        </div>
        <input type="hidden" name = "aid" value="{{$userappeaisal->performance_appraisal_id}}">
    @else
     <div class="form-group">
            <button class="btn btn-primary" id="btn-submit-edit" type="submit" name="createappeasial" value="create">Create</button>
        </div>
    @endif
    </div>
</div>
@endif
</form>
</div>
</div>
@endsection
@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

<!-- Timepicker -->
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/timepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/timepicker.js") }}" type="text/javascript"></script>

<!-- SELECT2-->
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>

<script type="text/javascript">
    $(function() {
        $('#appraisal_month').datetimepicker({
            format: 'YYYY-MM',
            sideBySide: true
        });

        
    });

    
</script>
@endsection
