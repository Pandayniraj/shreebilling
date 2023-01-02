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
                    {!! Form::open( ['route' => 'add-event-space', 'class' => 'form-horizontal'] ) !!}
            
                <div class="content col-md-12">

                    <h3> Basic Details</h3>
                  <div class="row">

                      <div class="col-md-4">
                            <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">
                              Event
                              </label>
                              <div class="col-sm-10">
                                <select name="event_id" class="form-control">
                                    @foreach($event as $e)
                                    <option value="{{$e->id}}">{{ucfirst(trans($e->event_name))}}</option>
                                    @endforeach
                                </select>
                              </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">  
                            <label class="control-label col-sm-6">Room Name</label>
                      <div class="input-group ">
                          <input type="text" name="room_name" placeholder="Room Name" id="company_id" class="form-control" required>
                          <div class="input-group-addon">
                              <a href="#"><i class="fa fa-hotel"></i></a>
                          </div>
                      </div>
                  </div>
                  </div>
                     <div class="col-md-4">
                         <div class="form-group">  
                          <label class="control-label col-sm-6">Room capability</label>
                    <div class="input-group ">
                        <input type="text" name="room_capability" placeholder="Room capability" id="company_id" class="form-control" required>
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-home"></i></a>
                        </div>
                    </div>
                </div>
                </div> 

                
                  </div>



                    <div class="row">

                        

                        <div class="col-md-4">
                        <div class="form-group">  
                <label class="control-label col-sm-4">Start date</label>
                    <div class="input-group ">
                      <input required="" type="text" class="form-control occupied_date_from" value="{{ isset($occupied_date_from) ? $event_start_date : '' }}" name="occupied_date_from" id="occupied_date_from">
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
                <input required="" type="text" class="form-control occupied_date_to" value="{{ isset($occupied_date_to) ? $occupied_date_to : '' }}" name="occupied_date_to" id="occupied_date_to">
                <div class="input-group-addon">
                <a href="#"><i class="fa fa-calendar"></i></a>
                </div>
            </div>
            </div>
            </div>     


                    </div>                    
                   
                    


<h3>Others Details</h3>

                <div class="row">

                    
                      <div class="col-md-4">
                            <div class="form-group">
                            <label for="inputEmail3" class="col-sm-6 control-label">
                              Booking Status
                              </label>
                              <div class="col-sm-4">
                                <select name="booking_status" class="form-control">
                                    @foreach($bstatus as $s)
                                    <option value="{{$s}}">{{ucfirst(trans($s))}}</option>
                                    @endforeach
                                </select>
                              </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">  
                <label class="control-label col-sm-8">Daily rate</label>
                    <div class="input-group ">
                        <input type="number" name="daily_rate" placeholder="" id="num_participants"  class="form-control" required="required">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-money"></i></a>
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
                                    <option value="{{$u->id}}">{{ucfirst(trans($u->username))}}</option>
                                    @endforeach
                                </select>
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
                          
                          <textarea class="form-control" name="other_details" id="comments" placeholder="Write Description"></textarea>
                        </div>
                </div>


                </div><!-- /.content -->
                <div class="content col-md-3">

               <!--    //side menu// -->
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn btn-primary" id="btn-submit-edit" type="submit">Add Space</button>
                        <a href="/admin/event-space" class="btn btn-default">Cancel</a>
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
            $('#occupied_date_from').datetimepicker({
                    //inline: true,
                    //format: 'YYYY-MM-DD',
                    format: 'YYYY-MM-DD hh:mm:ss',
                    sideBySide: true
                });
             $('#occupied_date_to').datetimepicker({
                    //inline: true,
                    //format: 'YYYY-MM-DD',
                    format: 'YYYY-MM-DD hh:mm:ss',
                    sideBySide: true
                });
        });

    </script>
   


    <!-- form submit -->
    @include('partials._body_bottom_submit_lead_edit_form_js')
@endsection
