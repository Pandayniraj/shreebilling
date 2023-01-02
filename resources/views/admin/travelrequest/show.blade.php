@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="/bower_components/tags/js/tag-it.js"></script>
@endsection

@section('content')
        <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {{ $page_title ?? 'Page Title' }}
                <small>{{$page_description ?? 'Page Description'}}</small>
            </h1>
           
        </section>


     <div class='row'>
       <div class='col-md-12'>
          <div class="box">
		     <div class="box-body ">
		     	 
		     	{{ csrf_field() }}                 
		     	<h4>Travel Details</h4>

                     <div class="row">
	                   	<div class="col-md-4">
	                   	    <label class="control-label">Place of visit</label>
                            <input type="text" name="place_of_visit" placeholder="Place of visit" id="place_of_visit" value="{{$travelrequest->place_of_visit}}" class="form-control" readonly="">
	                   	</div>
	                   	<div class="col-md-4">
                            <label class="control-label">Depart date</label>
                            <input type="text" name="depart_date" placeholder="Depart date" id="depart_date" value="{{$travelrequest->depart_date}}" class="form-control datepicker" readonly="">
	                   	</div>
	                   	<div class="col-md-4">
                            <label class="control-label">Arrival date</label>
                            <input type="text" name="arrival_date" placeholder="Arrival date" id="arrival_date" value="{{$travelrequest->arrival_date}}" class="form-control  datepicker" readonly="">
	                   	</div>
                    </div>

                      <div class="row">
	                   	<div class="col-md-4">
	                   	    <label class="control-label">Number of days</label>
                            <input type="text" name="num_days" placeholder="Number of days" id="num_days" value="{{$travelrequest->num_days}}" class="form-control" readonly="">
	                   	</div>
	                   		<div class="col-md-4">
                            <label class="control-label">Travel cost</label>
                            <input type="text" name="travel_cost" placeholder="Travel cost" id="travel_cost" value="{{$travelrequest->travel_cost}}" class="form-control" readonly="">
	                   	</div>
	                   	<div class="col-md-4">
                            <label class="control-label">Can billable to customer</label>
                            <select name="is_billable_to_customer" class="form-control" required readonly="">
                           	   <option value="">Select</option>
                               <option value="1" @if($travelrequest->is_billable_to_customer == '1') selected @endif>Yes</option>
                               <option value="0"  @if($travelrequest->is_billable_to_customer == '0') selected @endif>No</option>
                           </select>
	                   	</div>
                        <div class="col-md-4">
                              <label class="control-label">Status</label>
                                <input type="text" name="status" placeholder="Status" id="status" value="{{$travelrequest->status}}" class="form-control" readonly="">
                          </div>
                            <div class="col-md-4">
                      <label class="control-label">Staff</label>
                      <select name="staff_id" class="form-control" required readonly="">
                       <option value="">Select</option>
                       @foreach($staff as $s)
                       <option value="{{$s->id}}" @if($travelrequest->staff_id) selected @endif>{{ucfirst($s->username)}}</option>
                       @endforeach
                      </select>
                      </div>
                      </div>

                      <h4>User Details</h4>
                      <div class="row">
                      <div class="col-md-4">
                      <label class="control-label">Business account name</label>
                      <select name="business_account" class="form-control" required id="business_account" readonly="">
                            <option value="">Select Business Name</option>
                            @foreach($client as $cl)
                             <option value="{{$cl->id}}" @if($travelrequest->business_account) selected @endif>{{ucfirst($cl->name)}}</option>
                            @endforeach
                      </select>

                      </div>
                           <div class="col-md-4">
                        <label class="control-label">Customer Name</label>
                        <input type="text" name="customer_name" placeholder="Customer Name" class="form-control" value="{{$travelrequest->customer_name}}" readonly="">
                      </div>

                        
                      <div class="col-md-4">
                        <label class="control-label">Phone Number</label>
                        <input type="text" name="phone_num" placeholder="Phone Number" class="form-control" id="phone_num" value="{{$travelrequest->phone_num}}" readonly="">
                      </div>

    	                  
	
                      </div>
                      <h4>More Details</h4>
                      <div class="row">

                        <div class="col-md-4">
                              <label for="inputEmail3" class="control-label">
                        Visit purpose 
                        </label>
                          <textarea class="form-control" name="visit_purpose" id="visit_purpose" placeholder="Visit purpose" readonly="">
                            {!! $travelrequest->visit_purpose !!}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="control-label">
                        Remarks
                        </label>
                          <textarea class="form-control" name="remarks" id="remarks" placeholder="Remarks.." readonly=""> {!! $travelrequest->remarks !!}</textarea>
                        </div>

                      </div>
                  
             
		     </div>
		   </div>

		        <div class="row">
	                <div class="col-md-12">
	                    <div class="form-group">
                        @if($travelrequest->isEditable())
	                      <a  class="btn btn-danger" href="{!! route('admin.tarvelrequest.edit', $travelrequest->id) !!}">Edit</a>
                        @else 
                        <button type="button" class="btn btn-danger" disabled="">Edit</button>
                        @endif
	                        <a href="{!! route('admin.tarvelrequest.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
	                    </div>
	                 </div>
	            </div>

	        </form>
		</div>
	</div>
        
   
@endsection

@section('body_bottom')

<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
 <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>


<script type="text/javascript">
	
     $(function() {
			$('.datepicker').datetimepicker({
					//inline: true,
					//format: 'YYYY-MM-DD',
					format: 'YYYY-MM-DD', 
			        sideBySide: true,
			        allowInputToggle: true
				});
		});
     $('#business_account').change(function(){
        var id = $(this).val();
        $.get('/admin/getClientinfo',{client_id:id},function(data,status){
          let info = data.data;
          $('#phone_num').val(info.phone);
        });
     })

</script>
@endsection
