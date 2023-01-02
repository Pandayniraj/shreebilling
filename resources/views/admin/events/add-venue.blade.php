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
                    {!! Form::open( ['route' => 'add-venue', 'class' => 'form-horizontal'] ) !!}
            
                <div class="content col-md-9">

                    <h3> Basic Details</h3>
                  <div class="row">

                        <div class="col-md-12">
                        <label for="inputEmail3" class="control-label">
                            Venue Name
                        </label>
                          
                       <div class="input-group ">
                        <input type="text" name="venue_name" placeholder="Enter venue name" id="company_id" class="form-control" required>
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-building"></i></a>
                        </div>
                        </div>
                        </div>

                  </div>
                    <div class="row">
                          <div class="col-md-12">
                        <label for="inputEmail3" class="control-label">
                           Venue facilities
                        </label>
                          
                       <div class="input-group ">
                        <textarea class="form-control" name="venue_facilities" id="comments" placeholder="Write Description" cols="120">{!! \Request::old('description') !!}</textarea>
                        </div>
                        </div>
                        </div>

                          <h3>Other details</h3>

                          <div class="row">
                               <div class="col-md-10">
                                <label for="inputEmail3" class="control-label">
                            Owner
                        </label>
                            <div class="form-group">
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
                          <div class="row" >
                          <div class="col-md-12" style="margin-top: -1.5%">
                        <label for="inputEmail3" class="control-label">
                          Write Description
                        </label>
                          
                       <div class="input-group ">
                        <textarea class="form-control" name="other_details" id="comments" placeholder="Write Description" cols="120">{!! \Request::old('description') !!}</textarea>
                        </div>
                        </div>
                        </div>
                        <div class="col-md-12">
                          <br>
                    <div class="form-group">
                        <button class="btn btn-primary" id="btn-submit-edit" type="submit">Add Venues</button>
                        <a href="/admin/event-venues" class="btn btn-default">Cancel</a>
                    </div>
                </div>

                    {!! Form::close() !!}
                    </div>                    
                   
                    

            

                    

                <div class="row InputsWrapper">
                   <!--  //side menu// -->
                </div> 
                <div class="row">

                     
                </div>


                </div><!-- /.content -->
                <div class="content col-md-3">

               <!--    //side menu// -->
                </div>


                
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
                    format: 'YYYY-MM-DD hh:mm:ss',
                    sideBySide: true
                });
             $('#event_end_date').datetimepicker({
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
