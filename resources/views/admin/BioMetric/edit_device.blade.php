@extends('layouts.master')
@section('content')
<style>
	select { width:200px !important; }
label {
    font-weight: 600 !important;
}

 .intl-tel-input { width: 100%; }
 .intl-tel-input .iti-flag .arrow {border: none;}



</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Edit Device
                <small>{!! $page_description ?? "Import Data" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
    </section>


 <div class="box box-primary">

<div class="box-body">
 <label for="inputEmail3" class="control-label">
                       Device Basic Info
                        </label>

<form method="post" action="{{ route('editdevice',$device->id) }}">
	{{ csrf_field() }}

	     <div class="col-md-4">
                       <div class="form-group">  
                    <div class="input-group ">
                        <input type="text" name ='device_name' class="form-control" placeholder="Device Name" required="" value="{{ $device->device_name }}">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar-alt"></i></a>
                        </div>
                    </div>
</div>
</div>
 <div class="col-md-4">
                       <div class="form-group">  
                    <div class="input-group ">
                        <input type="text" name ='ip_address' class="form-control" placeholder="Ip address" required="" value="{{ $device->ip_address }}">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar-alt"></i></a>
                        </div>
                    </div>
</div>
</div>
 <div class="col-md-4">
                       <div class="form-group">  
                    <div class="input-group ">
                        <input type="number" name ='serial_number' class="form-control" placeholder="Serial Number" required="" value="{{ $device->serial_number }}">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar-alt"></i></a>
                        </div>
                    </div>
</div>
</div>
   <div class="row">

                     <div class="col-md-12">
                        <label for="inputEmail3" class="control-label">
                        Descriptions and Client Requirements
                        </label>
                          
                          <textarea class="form-control" name="description" id="description" placeholder="Write Description">{!! $device->description  !!}</textarea>
                        </div>
                </div><br>
                <button class="btn btn-primary" id="btn-submit-edit" type="submit">Update Device</button>
                 <a href='/admin/biometricmachine' class="btn btn-default">Cancel</a>
	</form>
</div>
</div>
@endsection