@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {!! $page_title ?? "Page Title" !!}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {{-- Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong> --}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>
<div class="row">
<div class="col-md-12">
   <div class="panel panel-primary">
      <div class="panel-heading">Reservation #{{$reservation->id}}
      	<span class="pull-right"><i class="fa   fa-calendar-plus-o"></i>&nbsp;{{$reservation->flight_date}}&nbsp;<i class="fa fa-clock-o"></i> {{$reservation->flight_time}}</span>
      </div>
      <div class="panel-body">
      	<div class="row">

      		<div class="col-md-3">
			  <div class="form-group">
			    <label >Depart Airport:</label>
			    <input type="text" class="form-control" readonly="" value="{{FlightHelper::getAirportName($reservation->from)??$reservation->from}}">
			  </div>
			 </div>

			 <div class="col-md-3">
			  <div class="form-group">
			    <label >Arrival Airport:</label>
			    <input type="text" class="form-control" readonly="" value="{{FlightHelper::getAirportName($reservation->to)??$reservation->to }}">
			  </div>
			 </div>
			  <div class="col-md-3">
			  <div class="form-group">
			    <label >Flight Number:</label>
			    <input type="text" class="form-control" readonly="" value="{{$reservation->flight_number}}">
			  </div>
			 </div>
			 <div class="col-md-3">
			  <div class="form-group">
			    <label >Airline:</label>
			    <input type="text" class="form-control" readonly="" 
			    value="{{FlightHelper::getAirlineName( $reservation->airline_code,strlen($reservation->airline_code))->name}}">
			  </div>
			 </div>


      		<div class="col-md-3">
			  <div class="form-group">
			    <label >FlightId:</label>
			    <input type="text" class="form-control" readonly="" value="{{$reservation->FlightId}}">
			  </div>
			 </div>

			 <div class="col-md-3">
			  <div class="form-group">
			    <label>ReturnFlightId:</label>
			    <input type="text" class="form-control" readonly="" value="{{$reservation->ReturnFlightId}}">
			  </div>
			 </div>

			<div class="col-md-3">
			  <div class="form-group">
			    <label >Contact Num:</label>
			    <input type="text" class="form-control" readonly="" value="{{$reservation->contact_no}}">
			  </div>
			</div>

			<div class="col-md-3">
			  <div class="form-group">
			    <label >Email address:</label>
			    <input type="text" class="form-control" readonly=""  value="{{$reservation->email_address}}">
			  </div>
			</div>

			<div class="col-md-3">
			  <div class="form-group">
			    <label >Contact name:</label>
			    <input type="text" class="form-control" readonly="" value="{{$reservation->contact_name}}">
			  </div>
			</div>

			<div class="col-md-3">
			  <div class="form-group">
			    <label >Referenced:</label>
			    <input type="text" class="form-control" readonly="" value="{{$reservation->referenceId}}">
			  </div>
			</div>

			<div class="col-md-3">
			  <div class="form-group">
			    <label >Due To:</label>
			    <input type="text" class="form-control" readonly="" value="{{$reservation->dueTo}}">
			  </div>
			</div>

			<div class="col-md-3">
			  <div class="form-group">
			    <label >Order Id:</label>
			    <input type="text" class="form-control" readonly="" value="{{$reservation->orderId}}">
			  </div>
			</div>

			<div class="col-md-3">
			  <div class="form-group">
			    <label >Total Amount:</label>
			    <input type="text" class="form-control" readonly="" value="{{$reservation->currency.' '.$reservation->total_amount}}">
			  </div>
			</div>

			<div class="col-md-3">
			  <div class="form-group">
			    <label >Base Fair:</label>
			    <input type="text" class="form-control" readonly="" value="{{$reservation->currency.' '.$reservation->base_fair}}">
			  </div>
			</div>

			<div class="col-md-3">
			  <div class="form-group">
			    <label >Service fee(%):</label>
			    <input type="text" class="form-control" readonly=""  value="{{$reservation->service_fee}}">
			  </div>
			</div>

			<div class="col-md-3">
			  <div class="form-group">
			    <label >Payment:</label>
			    <input type="text" class="form-control" readonly="" value="{{$reservation->payment}}">
			  </div>
			</div>

			<div class="col-md-3">
			  <div class="form-group">
			    <label >Paid By:</label>
			    <input type="text" class="form-control" readonly="" value="{{$reservation->paid_by}}">
			  </div>
			</div>

			<div class="col-md-3">
			  <div class="form-group">
			    <label >Flight Type:</label>
			    <input type="text" class="form-control" readonly="" value="{{ucwords($reservation->flight_type)}}">
			  </div>
			</div>

			<div class="col-md-3">
			  <div class="form-group">
			    <label >PNR:</label>
			    <input type="text" class="form-control" readonly="" value="{{ucwords($reservation->ticket->pnr)}}">
			  </div>
			</div>

      	</div>
      </div>
    </div>

    <div class="row">
    	@foreach($reservationDetails as $key=>$rd)
    	<div class="col-md-6">
	   	<div class="panel panel-danger">
	      <div class="panel-heading">{{ $rd->pax_type }}</div>
	      <div class="panel-body">
	      	
	      	<div class="row">
      		<div class="col-md-3">
			  <div class="form-group">
			    <label >Pax type:</label>
			    <input type="text" class="form-control" readonly="" value="{{$rd->pax_type}}">
			  </div>
			 </div>

			 <div class="col-md-3">
			  <div class="form-group">
			    <label >Name:</label>
			    <input type="text" class="form-control" readonly="" value="{{$rd->first_name}} {{$rd->last_name}}">
			  </div>
			 </div>

			 <div class="col-md-3">
			  <div class="form-group">
			    <label >Gender:</label>
			    <input type="text" class="form-control" readonly="" 
			    value="{{ $rd->gender == M ? Male:Female }}">
			  </div>
			 </div>

			  <div class="col-md-3">
			  <div class="form-group">
			    <label >Nationality:</label>
			    <input type="text" class="form-control" readonly="" value="{{$rd->nationality}}">
			  </div>
			 </div>

			 <div class="col-md-3">
			  <div class="form-group">
			    <label >Dob:</label>
			    <input type="text" class="form-control" readonly="" value="{{$rd->dob}}">
			  </div>
			 </div>

			 <div class="col-md-3">
			  <div class="form-group">
			    <label >Passport No:</label>
			    <input type="text" class="form-control" readonly="" value="{{$rd->passport_number}}">
			  </div>
			 </div>

			 <div class="col-md-3">
			  <div class="form-group">
			    <label title="Passport Expiry Date">Passport exp..</label>
			    <input type="text" class="form-control" readonly="" value="{{$rd->passport_expiry_date == '0000-00-00' ? null: $rd->passport_expiry_date}}">
			  </div>
			 </div>


			 <div class="col-md-3">
			  <div class="form-group">
			    <label title="Citizenship Number">Citizenship:</label>
			    <input type="text" class="form-control" readonly="" value="{{$rd->citizenship_number}}">
			  </div>
			 </div>

			</div>

	      </div>
	    </div>
	    </div>
	    @endforeach


    </div>


    <div class="row">
    	<div class="col-md-12">
	   	<div class="panel panel-primary">
    	<table class="table">
    		<thead>
    			<tr class="bg-primary">
    				<th>Passenger Name</th>
    				<th>Ticket Number</th>
    			</tr>
    		</thead>
    		<tbody>
    			@foreach($ticketInfo as $key=>$value)
    			<tr>
    				<td>{{$value->contact_name}}</td>
    				<td>{{$value->ticket_no}}</td>
    			</tr>
    			@endforeach
    		</tbody>	
    	</table>
</div>


    </div>




    <div class="row">
    	 <div class="col-md-3">
			  <div class="form-group">
			    <a class="btn btn-default" href="/admin/isssued/tickets"><i class="fa fa-backward"></i> &nbsp; Back</a>
			 </div>    
			</div>

</div>
</div>




@endsection