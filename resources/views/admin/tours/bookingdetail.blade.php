@extends('layouts.master')

@section('content')
<link href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />
<script src='{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}'></script>

@include('partials._head_extra_select2_css')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {!! $page_title ?? "Page Title" !!}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {{-- Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong> --}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body">

            	    <div class="row">

            	    	<input type="hidden" id="reservation_id" name="reservation_id" value="{{$bookingdetail->id}}">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Name</label>
                            <div class="input-group">
                                <input type="text" name="tour_title" class="form-control" placeholder="Tour Title" required="" value="{{$bookingdetail->tour->tour_title}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-navicon"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Date</label>
                            <div class="input-group">
                                <input type="text" name="tour_date" class="form-control" placeholder="Tour Date" value="{{$bookingdetail->tour_date}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-star"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Email</label>
                            <div class="input-group">
                                <input type="text" name="tour_date" class="form-control" placeholder="Email" value="{{$bookingdetail->email}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-at"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Full Name</label>
                            <div class="input-group">
                                <input type="text" name="full_name" class="form-control" placeholder="Full Name" required="" value="{{$bookingdetail->first_name}} {{$bookingdetail->last_name}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-navicon"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Contact No</label>
                            <div class="input-group">
                                <input type="text" name="contact_no" class="form-control" placeholder="Contact No" value="{{$bookingdetail->contact_no}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-star"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Address</label>
                            <div class="input-group">
                                <input type="text" name="address" class="form-control" placeholder="Address" value="{{$bookingdetail->address}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-at"></i></a>
                                </div>
                            </div>
                        </div>

                         
                    </div>

                    <div class="row">
                    	<div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Country</label>
                            <div class="input-group">
                                <input type="text" name="country" class="form-control" placeholder="Country" value="{{$bookingdetail->countrydetail->name}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-at"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Paid By</label>
                            <div class="input-group">
                                <input type="text" name="paid_by" class="form-control" placeholder="Paid By" value="{{$bookingdetail->paid_by}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-at"></i></a>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Payment</label>
                            <div class="input-group">
                                <input type="text" name="payment" class="form-control" placeholder="Payment" value="{{$bookingdetail->payment}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-at"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                    	 <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">User</label>
                            <div class="input-group">
                                <input type="text" name="user" class="form-control" placeholder="User" value="{{$bookingdetail->user->first_name}} {{$bookingdetail->user->last_name}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-at"></i></a>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Status</label>
                            <select name="status" class="form-control" id="status">
                            	<option value="New"  @if($bookingdetail->status == 'new') Selected @endif>New</option>
                            	<option value="confirmed" @if($bookingdetail->status == 'confirmed') Selected @endif>Confirmed</option>
                            	<option value="paid" @if($bookingdetail->status == 'paid') Selected @endif>Paid</option>
                            </select>
                            <small id="ajax_status"></small>
                        </div>
                       
                    </div>

                   

                    <h4>Total Billing Format</h1>

                    	 <table class="table table-striped">
				            <thead>
				              <tr>
				               
				                <th scope="col">Item</th>
				                <th scope="col">Price</th>
				              </tr>
				            </thead>
				            <tbody>
				            	<tr>
				            		<td>Adult Total Price ({{$bookingdetail->adult_no}} * {{$bookingdetail->adult_price}}) </td>
				            		<td>{{$bookingdetail->currency}} {{$bookingdetail->adult_no * $bookingdetail->adult_price }}</td>
				            	</tr>
				            	<tr>
				            		<td>Child Total Price ({{$bookingdetail->child_no}} * {{$bookingdetail->child_price}})</td>
				            		<td>{{$bookingdetail->currency}} {{$bookingdetail->child_no * $bookingdetail->child_price }}</td>
				            	</tr>
				            	<tr>
				            		<td>Infant Total Price ({{$bookingdetail->infant_no}} * {{$bookingdetail->infant_price}})</td>
				            		<td>{{$bookingdetail->currency}} {{$bookingdetail->infant_no * $bookingdetail->infant_price }}</td>
				            	</tr>
				             @if($tourbookings_extra)
				              @foreach($tourbookings_extra as $te)
				                <tr>
				                  <td class="items">{{$te->extra->item}}</td>
				                  <td class="price">{{$bookingdetail->currency}} {{$te->extra_price}}</td>  
				                </tr>
				              @endforeach
				              @endif


				            </tbody>
				            <tfoot>
				            	<tr>
				            		<td><strong>Total Amount</strong></td>
				            		<td><strong>{{$bookingdetail->currency}} {{number_format($bookingdetail->total_amount,2)}}</strong></td>
				            	</tr>
				            </tfoot>
				          </table>

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
	
	$(document).on('change', '#status', function() {
        var id = $('#reservation_id').val();
        var status = $(this).val();

        $.post("/admin/ajax_tour_reservation_status", {
                id: id, 
                status: status, 
                _token: $('meta[name="csrf-token"]').attr('content')
            }
            , function(data, status) {
                if (data.status == '1')
                    $("#ajax_status").after("<span style='color:green;' id='status_update'>Successfully updated.</span>");
                else
                    $("#ajax_status").after("<span style='color:red;' id='status_update'>Problem in updating ! Please try again.</span>");

                $('#status_update').delay(3000).fadeOut('slow');
                //alert("Data: " + data + "\nStatus: " + status);
            });
    });

</script>

@endsection