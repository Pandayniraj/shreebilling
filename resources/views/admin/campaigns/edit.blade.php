@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              Campaings
                <small>Edit Campaings #{{$edit->id}}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

 <form method="post" action="{{route('admin.campaigns.store',$edit->id)}}">
  {{ csrf_field() }}
<div class="panel panel-custom">
 <div class="panel-heading">
  <h3>Basic Info</h3>
  <div class="row">
    <div class="col-md-4">
              <div class="form-group">  
                <label class="control-label col-sm-6">Name</label>
                    <div class="input-group ">
                        <input type="text" name="name" placeholder="Campaings name" id="calculated_cost" value="{{$edit->name}}" class="form-control" required="required">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa  fa-bullhorn"></i></a>
                        </div>
                    </div>
                </div>

  </div>
    <div class="col-md-4">
              <div class="form-group">  
                <label class="control-label col-sm-6">Start Date</label>
                    <div class="input-group ">
                        <input type="text" name="start_date" placeholder="Start Date" id="start_date" value="{{$edit->start_date}}" class="form-control">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa  fa-calendar"></i></a>
                        </div>
                    </div>
                </div>

  </div>

  
      <div class="col-md-4">
              <div class="form-group">  
                <label class="control-label col-sm-6">End Date</label>
                    <div class="input-group ">
                        <input type="text" name="end_date" placeholder="End Date" id="end_date" value="{{$edit->end_date}}" class="form-control">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa  fa-calendar"></i></a>
                        </div>
                    </div>
                </div>

  </div>

      </div>
 <h3>Cost</h3>
 <div class="row">
     <div class="col-md-4">
              <div class="form-group">  
                <label class="control-label col-sm-6">Currency</label>
                    <div class="input-group ">
                        <input type="text" name="currency" placeholder="Currency...." id="" value="{{$edit->currency}}" class="form-control">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa  fa-usd"></i></a>
                        </div>
            </div>
            </div>
  </div>
     <div class="col-md-4">
              <div class="form-group">  
                <label class="control-label col-sm-6">Budget</label>
                    <div class="input-group ">
                        <input type="text" name="budget" placeholder="Budget...." id="dob" value="{{$edit->budget}}" class="form-control">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa   fa-btc"></i></a>
                        </div>
            </div>
            </div>
  </div>
      <div class="col-md-4">
              <div class="form-group">  
                <label class="control-label col-sm-6">Expected cost</label>
                    <div class="input-group ">
                        <input type="text" name="expected_cost" placeholder="Expected cost...." id="dob" value="{{$edit->expected_cost}}" class="form-control">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa   fa-money"></i></a>
                        </div>
            </div>
            </div>
  </div>
   <div class="col-md-4">
              <div class="form-group">  
                <label class="control-label col-sm-6">Actual cost</label>
                    <div class="input-group ">
                        <input type="text" name="actual_cost" placeholder="Actual cost...." id="dob" value="{{$edit->actual_cost}}" class="form-control">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa   fa-money"></i></a>
                        </div>
            </div>
            </div>
  </div>

     
 </div>

<h3>Other Info</h3>
<div class="row">
  <div class="col-md-4">
           <div class="form-group">  
                <label class="control-label col-sm-6">Expected revenue</label>
                    <div class="input-group ">
                        <input type="text" name="expected_revenue" placeholder="Expected_revenue..." id="expected_revenue" value="{{$edit->expected_revenue}}" class="form-control" >
                        <div class="input-group-addon">
                            <a href="#"><i class="fa   fa-location-arrow"></i></a>
                        </div>
                    </div>
    </div>
  </div>
   <div class="col-md-4">
           <div class="form-group">  
                <label class="control-label col-sm-6">Campaign type</label>
                    <div class="input-group ">
                        <input type="text" name="campaign_type" placeholder="Campaign type ....." id="campaign_type" value="{{$edit->campaign_type}}" class="form-control" >
                        <div class="input-group-addon">
                            <a href="#"><i class="fa  fa-bullseye"></i></a>
                        </div>
                    </div>
    </div>
  </div>
   <div class="col-md-4">
           <div class="form-group">  
                <label class="control-label col-sm-6">Camp status</label>
                    <div class="input-group ">
                        <select name="camp_status" placeholder="Camp status....." id="landline" value="" class="form-control">
                            <option value="starred">Starred</option>
                             <option value="ongoing" @if($edit->camp_status == 'ongoing') selected @endif >Ongoing</option>
                              <option value="finished" @if($edit->camp_status == 'finished') selected @endif >Finished</option>
                        </select>
                        <div class="input-group-addon">
                            <a href="#"><i class="fa    fa-hourglass-start"></i></a>
                        </div>
                    </div>
    </div>
  </div>

  </div>
  
 
<div class="row">
      <div class="col-md-7">
                        <label for="inputEmail3" class="control-label">
                        Objectives 
                        </label>
                          
                          <textarea class="form-control" name="objective" id="objective" placeholder="Write objective">{{$edit->objective}}</textarea>
                        </div>
                    </div><br>
<div class="row">
      <div class="col-md-7">
                        <label for="inputEmail3" class="control-label">
                        Content 
                        </label>
                          
                          <textarea class="form-control" name="content" id="content" placeholder="Write content">{{$edit->content}}</textarea>
                        </div>
                    </div><br>
   <div class="row">
 <div class="col-md-12">
        <div class="form-group">
            <button class="btn btn-primary" id="btn-submit-edit" type="submit" >Update</button>
            <a class="btn btn-default" href="/admin/campaigns/index">Cancel</a>
        </div>
    </div>
</div>
</div>
</div>
  @endsection
    @section('body_bottom')
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
    <script type="text/javascript">

        $(function() {
            $('#start_date').datepicker({
                     //format: 'YYYY-MM-DD',
                    dateFormat: 'yy-m-d',
                    sideBySide: true,
                   
                });
            $('#end_date').datepicker({
                     //format: 'YYYY-MM-DD',
                    dateFormat: 'yy-m-d',
                    sideBySide: true,
                   
                });
            });    
    </script>
   


    <!-- form submit -->
    @include('partials._body_bottom_submit_lead_edit_form_js')
@endsection