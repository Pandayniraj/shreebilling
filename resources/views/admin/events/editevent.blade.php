@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<style>
    select { width:200px !important; }
label {
    font-weight: 600 !important;
}

 .intl-tel-input { width: 100%; }
 .intl-tel-input .iti-flag .arrow {border: none;}



</style>


<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset("/bower_components/intl-tel-input/build/css/intlTelInput.css") }}" rel="stylesheet" type="text/css" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<script src="{{ asset("/bower_components/intl-tel-input/build/js/intlTelInput-jquery.min.js") }}"></script>


<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title" }}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <div class="box">
                <div class="box-body">
                    {!! Form::open( ['route' => ['editevent',$edit->id], 'class' => 'form-horizontal','method'=>'post'] ) !!}
            
                <div class="content col-md-9">

                    <h3> Basic Details</h3>
                  <div class="row">

                    <div class="col-md-4">
                            <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">
                                Type
                              </label>
                              <div class="col-sm-10">
                                <select name="event_type" class="form-control">
                                    @foreach($events_type as $e)
                        <option value="{{$e}}" @if($e == $edit->event_type)selected @endif>{{ucfirst(trans($e))}}</option>
                                    @endforeach
                                </select>
                              </div>
                            </div>
                        </div>
                   
                    

                      <div class="col-md-4">

                         <div class="form-group">  
                
                    <div class="input-group ">
                        <input type="text" name="event_name" placeholder="Event Name" id="company_id" class="form-control" value = "{{$edit->event_name}}"required>
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-building"></i></a>
                        </div>
                    </div>

                </div>
                             
                        </div> 

                <div class="col-md-4">
                            <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">
                              Venue
                              </label>
                              <div class="col-sm-10">
                                <select name="venue_id" class="form-control">
                                    @foreach($venue as $v)
                                    <option value="{{$v->id}}"@if($v->id == $edit->venue_id)selected @endif>{{ucfirst(trans($v->venue_name))}}</option>
                                    @endforeach
                                </select>
                              </div>
                            </div>
                        </div>
                  </div>



                    <div class="row">

                        

                        <div class="col-md-4">
                        <div class="form-group">  
                <label class="control-label col-sm-4">Start date</label>
                    <div class="input-group ">
                      <input required="" type="text" class="form-control event_start_date" value="{{ isset($edit->event_start_date) ? $edit->event_start_date : '' }}" name="event_start_date" id="event_start_date">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
            <div class="form-group">  
                <label class="control-label col-sm-4">End date</label>
                <div class="input-group ">
                <input required="" type="text" class="form-control event_end_date" value="{{ isset($edit->event_end_date) ? $edit->event_end_date : '' }}" name="event_end_date" id="event_end_date">
                <div class="input-group-addon">
                <a href="#"><i class="fa fa-calendar"></i></a>
                </div>
            </div>
            </div>
            </div>     
          </div>                    
                   
                    

<h3>Amount Details</h3>

                    <div class="row">

                         <div class="col-md-4">

                         <div class="form-group">  
                <label class="control-label col-sm-6">Paid</label>
                    <div class="input-group ">
                        <input type="number" name="amount_paid" placeholder="Amount Paid" id="amount_paid"  class="form-control" required="" value="{{$edit->amount_paid}}">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-money"></i></a>
                        </div>
                    </div>
                </div>      
            </div> 

                        <div class="col-md-4">
                          <div class="form-group">  
                             <label class="control-label col-sm-6">Potential cost</label>
                             <div class="input-group ">
                                 <input type="number" name="potential_cost" placeholder="Potential Cost" id="potential_cost" class="form-control" required="required" value="{{$edit->potential_cost}}">
                                 <div class="input-group-addon">
                                  <a href="#"><i class="fa fa-money"></i></a>
                                 </div>
                             </div>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">  
                <label class="control-label col-sm-6">Calculated cost</label>
                    <div class="input-group ">
                        <input type="text" name="calculated_cost" placeholder="Calculated cost" id="calculated_cost" class="form-control" required="required" value="{{$edit->calculated_cost}}" >
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-money"></i></a>
                        </div>
                    </div>
                </div>
                        </div>
                   </div>


                <div class="row">

                    <div class="col-md-4">
                           <div class="form-group"> 
                           <label class="control-label col-sm-6">Edited cost</label> 
                              <div class="input-group">
                              <input type="number" name="edited_cost" id="edited_cost" class="form-control" required="" placeholder="Edited cost" value="{{$edit->edited_cost}}" >
                              <div class="input-group-addon">
                              <a href="#"><i class="fa fa-money"></i></a>
                             </div>
                           </div>
                         </div>
                       </div>


                     
                    <div class="col-md-4">
                           <div class="form-group"> 
                           <label class="control-label col-sm-6">Extra cost</label> 
                              <div class="input-group">
                              <input type="number" name="extra_cost" id="extra_cost" class="form-control" required="" placeholder="Extra cost" value="{{$edit->extra_cost}}">
                              <div class="input-group-addon">
                              <a href="#"><i class="fa fa-money"></i></a>
                             </div>
                           </div>
                         </div>
                       </div>
                </div>

<h3>Others Details</h3>

                <div class="row">

                      <div class="col-md-4">
                            <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">
                             Status
                              </label>
                              <div class="col-sm-10">
                               <select name="event_status" class="form-control">
                                    @foreach($event_status as $s)
                                    <option value="{{$s}}" @if($s == $edit->event_status)selected @endif>{{ucfirst(trans($s))}}</option>
                                    @endforeach
                                </select>
                              </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">  
                <label class="control-label col-sm-4">Participants</label>
                    <div class="input-group ">
                        <input type="number" name="num_participants" placeholder="Participants num" id="num_participants"  class="form-control" required="required" value= "{{$edit->num_participants}}">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-users"></i></a>
                        </div>
                    </div>
                </div>
                        </div>
                       <div class="col-md-4">
                            <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">
                            Owner
                              </label>
                              <div class="col-sm-10">
                             <select name="user_id" class="form-control">
                                    @foreach($users as $u)
                                    <option value="{{$u->id}}"  @if($u->id == $edit->user_id)selected @endif>{{ucfirst(trans($u->username))}}</option>
                                    @endforeach
                                </select>
                              </div>
                            </div>
                        </div>

                        
                    </div> 

                <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">  
                <label class="control-label col-sm-3">Menu</label>
                    <div class="input-group ">
                        <input type="text" name="menu_items" placeholder="Menu items" id="menu_items"  class="form-control" required="required" value="{{$edit->menu_items}}">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-list"></i></a>
                        </div>
                    </div>
                </div>
                        </div>
                         <div class="col-md-4">
                          <div class="form-group">  
                <label class="control-label col-sm-4">Others</label>
                    <div class="input-group ">
                        <input type="text" name="other_details" placeholder="Other Details" id="other_details"  class="form-control" required="required" value="{{$edit->other_details}}">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-hourglass"></i></a>
                        </div>
                    </div>
                </div>
                        </div>
                   
                   
                    </div> 

            

                    

                <div class="row InputsWrapper">


                </div> 
                <div class="row">

                     <div class="col-md-12">
                        <label for="inputEmail3" class="control-label">
                        Descriptions 
                        </label>
                          
                          <textarea class="form-control" name="comments" id="comments" placeholder="Write Description">{{$edit->comments}}</textarea>
                        </div>
                </div>


                </div><!-- /.content -->
                <div class="content col-md-3">

               <!--    //side menu// -->
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn btn-primary" id="btn-submit-edit" type="submit">Update Event</button>
                        <a href="/admin/events" class="btn btn-default">Cancel</a>
                    </div>
                </div>
                    
                    {!! Form::close() !!}
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
    <script type="text/javascript">

        $(function() {
            $('#event_start_date').datetimepicker({
                    //inline: true,
                    //format: 'YYYY-MM-DD',
                    format: 'YYYY-MM-DD HH:mm:ss',
                    sideBySide: true
                });
             $('#event_end_date').datetimepicker({
                    //inline: true,
                    //format: 'YYYY-MM-DD',
                    format: 'YYYY-MM-DD HH:mm:ss',
                    sideBySide: true
                });
        });

    </script>
   


    <!-- form submit -->
    @include('partials._body_bottom_submit_lead_edit_form_js')
@endsection
