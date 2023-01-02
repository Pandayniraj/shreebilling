<!DOCTYPE html>
<html>
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
<head>
    <title></title>
<style type="text/css">
        .other-pages{
        page-break-before: always;
      }
      table td{
        padding: 2.5px !important;

      }
</style>
</head>

<body>

@foreach($ticket_bookings as $key=>$value)
 
@php 
    $passenger = $passengerdetail[$key]; 

    if($passenger->pax_type == 'ADULT'){

        $adjusted = $flightinfo['other_airline_info']->adjust_adult_amount;

    }elseif($passenger->pax_type == 'CHILD'){

        $adjusted = $flightinfo['other_airline_info']->adjust_child_amount;

    }else{

        $adjusted = $flightinfo['other_airline_info']->adjust_infant_amount;

    }

    $fair = $flightinfo[$passenger->pax_type] + $adjusted;

    $fuel_charge =  $flightinfo['fuel_charge'];

    $tax =  $flightinfo['tax'];

@endphp 

<div class="container @if($key > 0) other-pages @endif  ">

<table class="table">
    <td style="float: left;">
        
        <img src="{{public_path()}}/airline/{{$flightinfo['other_airline_info']->thumbnail}}" style="height: 80px;width: 80px;">

    </td>
    <td style="float: right !important;">
        
        <img src="{{public_path()}}/org/{{$imagepath}}" style="max-width: 150px;float: right;">
    </td>
</table>



<p>
    Important Information for Passengers:<br>
    This e-ticket is not transferable. <b>A valid photo ID is required for verification at check-in counter</b>. Boarding may be denied if satisfactory ID is not produced. Check-in countries close half an hours before depature time.


</p>
<div class="row">
    <div class="col-md-6">
    <table class="table table-sm">
        <tr>
                <td colspan="2" class="bg-primary">PASSENGER INFO</td>
            </tr>
         <tr>
            <td>Passenger Name</td>
            <td>{{$value->contact_name}}</td>
        </tr>
        <tr>
            <td>Booking ID</td>
            <td>{{$value->pnr}}</td>
        </tr>
        <tr>
            <td>Ticket Number</td>
            <td>{{$value->ticket_no}}</td>
        </tr>
     {{--    <tr>
            <td>Flight Date</td>
            <td>{{$flightinfo['flight_date']}}</td>
        </tr> --}}
        <tr>
            <td>Class Code</td>
            <th>{{$flightinfo['classCode']}}</th>
        </tr>
        <tr>
            <td>Issued By</td>
            <td>{{ $value->user->username }}</td>
        </tr>


    </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-sm">
            <thead>
            
            <tr>
                
                <td>
                    <h3>  {{ $flightinfo['from'] }}</h3>
                     <span >
                         Departure {{$flightinfo['flight_date']}} {{ $flightinfo['flight_time'] }}
                    </span>
                </td>
                 <td>
                    <h3>  {{ $flightinfo['to'] }} </h3>
                     <span >
                        Arrival {{$flightinfo['flight_date']}} {{ $flightinfo['arrival_time'] }}
                    </span>
                </td>


            </tr>

             <tr>
                    <th class="bg-primary" colspan="2">FLIGHT DETAILS</th>
            </tr>
        </thead>
        <tbody>
            
                 <tr>
                    <th>Flight Number</th>
                    <td>{{ $flightinfo['flight_no'] }}</td>
                </tr>
                <tr>
                    <th>Free Baggages</th>
                    <td>{{ $flightinfo['baggage'] }}</td>
                </tr>
                <tr>
                    <th  colspan="2" class="bg-info">FARE AND TAX DETAILS</th>
                </tr>
                <tr>
                    <th>Currency</th>
                    <td>{{ $flightinfo['currency'] }}</td>
                </tr>
                 <tr>
                    <th>Base Fare</th>
                    <td>{{ $fair }}</td>
                </tr>

                <tr>
                    <th>Fuel Surcharge</th>
                    <td>{{ $fuel_charge }}</td>
                </tr>

                <tr>
                    <th>Tax</th>
                    <td>{{ $tax }}</td>
                </tr>


                <tr>
                    <th>Total</th>
                    <td>{{  $fair +  ($fuel_charge) + ( $tax )  + $flightinfo['other_airline_info']->adjust_adult_amount }}</td>
                </tr>

        </tbody>

        </table>



    </div>
</div>

<span>
    Generated from <b>{{ env("APP_COMPANY") }} </b>
    <small style="float: right;position: absolute;">Powerd By Meronewtork ERP</small>
</span>
</div>



@endforeach
</body>
</html>