@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              Product Location
                <small>{{$description}}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<form method="post" action="{{route('admin.product-location.create')}}">
{{ csrf_field() }}
<div class="panel panel-custom">
 <div class="panel-heading">
 	<div class="row">
   <div class="col-sm-4">
<div class="form-group">  
<label class="control-label col-sm-12">Location name</label>
<div class="input-group ">
<input type="text" name="location_name" placeholder="Location name" id="Enter Section name" class="form-control" required>
<div class="input-group-addon">
  <a href="#"><i class="fa  fa-location-arrow"></i></a>
</div>
</div>
</div>
   </div>
      <div class="col-sm-4">
<div class="form-group">  
<label class="control-label col-sm-12">Delivery address</label>
<div class="input-group ">
<input type="text" name="delivery_address" placeholder="Delivery address" id="symbol" class="form-control" required>
<div class="input-group-addon">
  <a href="#"><i class="fa  fa-location-arrow"></i></a>
</div>
</div>
</div>
   </div>
         <div class="col-sm-4">
<div class="form-group">  
<label class="control-label col-sm-12">Location code</label>
<div class="input-group ">
<input type="text" name="loc_code" placeholder="Enter section Name" id="symbol" class="form-control" required>
<div class="input-group-addon">
  <a href="#"><i class="fa  fa-location-arrow"></i></a>
</div>
</div>
</div>
   </div>
 </div>
    <div class="row">
   <div class="col-sm-4">
<div class="form-group">  
<label class="control-label col-sm-12">Phone</label>
<div class="input-group ">
<input type="text" name="phone" placeholder="Phone" id="Enter Section name" class="form-control" required>
<div class="input-group-addon">
  <a href="#"><i class="fa  fa-phone"></i></a>
</div>
</div>
</div>
   </div>
      <div class="col-sm-4">
<div class="form-group">  
<label class="control-label col-sm-12">Contact person</label>
<div class="input-group ">
<input type="text" name="contact_person" placeholder="Contact person" id="symbol" class="form-control" required>
<div class="input-group-addon">
  <a href="#"><i class="fa  fa-user"></i></a>
</div>
</div>
</div>
   </div>
            <div class="col-sm-4">
<div class="form-group">  
<label class="control-label col-sm-12">Email</label>
<div class="input-group ">
<input type="text" name="email" placeholder="Email" id="symbol" class="form-control" required>
<div class="input-group-addon">
  <a href="#"><i class="fa fa-envelope-o"></i></a>
</div>
</div>
</div>
   </div>

 </div>
<input type="checkbox" name="enabled" > <label>Enabled</label><br>
<br>
 <div class="row">
 <div class="col-md-12">
        <div class="form-group">
            <button class="btn btn-primary" id="btn-submit-edit" type="submit" >Add</button>
        </div>
 
    </div>
</div>
</div>
</div>
</form>
@endsection