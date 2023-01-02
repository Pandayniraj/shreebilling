<!DOCTYPE html>
<html>
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
<head>
	<title></title>
</head>
<body>
<style type="text/css">
	.traveler_info {

		background-color: #203162 !important;
		color: white;

	}
body {
  font-family: 'Trebuchet MS', sans-serif;
}
.pdf_table{

  border-collapse: collapse;
  width: 100%;
  padding: 5px;
}
.pdf_table td{
	padding: 5px;
	text-align: left;
}
.nolinebreak{
	white-space: nowrap;
}
    .other-pages{
        page-break-before: always;
      }
</style>
@foreach($ticket_bookings as $tb=>$tb_pax)
<?php 

$allPaxType = ['ADULT'=>'ADT','CHILD'=>'CNN','INFANT'=>'INF'];

$paxType = $allPaxType [$passengerdetail[$tb]->pax_type];



?>

<div class="container @if($tb > 0) other-pages @endif">

<div class="row">
<div class="col-md-12">
<table class="pdf_table">
    <td style="float: left !important;">
          <img src="{{public_path()}}/org/{{$imagepath}}" style="max-width: 150px;float: left;">
      
    </td>
</table><br><br><br>
	<table class="pdf_table">
			<thead>
				<tr style="background:  #EA2742;color:white;">
					<td colspan="2"  style="vertical-align: top;"><h3> e-{{ strtoupper('ticket Itinerary')}}</h3>
			</td>
					
					{{-- <td style="vertical-align: middle;">
						
					</td> --}}
					<td colspan="2" class="nolinebreak">
						Booking ref:&nbsp;&nbsp;&nbsp;{{ $tb_pax->pnr }}<br>
						Issue Date:&nbsp;&nbsp;&nbsp;{{ date('l d F Y' ,strtotime($tb_pax->created_at)) }}
					</td>
					
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="4"></td>
				</tr>
				<tr style="background-color: #203162 !important;color: white;">
					<td style="vertical-align: top;text-transform: uppercase;" class="nolinebreak">Prepared For<br> <br>
						Ticket Number<br> <br>
						Airline Res. Code: <br> <br>
					</td>
					<td style="vertical-align: top;" class="nolinebreak"><b>
						<?php $personname = explode(' ', $tb_pax->contact_name); ?>
						@if(count($personname) > 2)
							{{$personname[2]}}/{{$personname[0]}} {{$personname[1]}}
						@else
							{{$personname[1]}}/{{$personname[0]}}
						@endif
						</b><br> <br>
						<b>{{  $tb_pax->ticket_no  }}</b><br> <br>
						<b>{{ $flightinfo['airlinePnr'] }}</b><br> <br>
					</td>
					<td style="vertical-align: top;">
						Agency:
						<br><br><br>Telephone<br><br><br>Email
					</td>
				<td>Ticket Bhandar.com <br>Naxal–01,Nagpokhari<br>Kathmandu,Nepal<br>+977-1-4434900<br>+977-1-4427587/88<br>+977-1-4428892<br>{{env('APP_EMAIL1')}}</td>
				</tr>
		
			</tbody>

	</table>

<br>
	@if($ads[0])
	<img src="{{public_path()}}/ads/{{$ads[0]->attachment}}" style="height: 100px; width: 100%;">
	@endif
	{{-- <img src="https://scontent.fktm3-1.fna.fbcdn.net/v/t1.0-9/130599964_105552834758466_2669100848613087638_n.jpg?_nc_cat=107&ccb=2&_nc_sid=e3f864&_nc_ohc=LKawnOPJDlcAX9X3yMV&_nc_ht=scontent.fktm3-1.fna&oh=126e6d77902d2f8ac8dd05d4f5aec5fb&oe=5FFDD331" style="width: 100%;height: 100px;">
 --}}



	<table class="pdf_table">

		@foreach($flightinfo['transationSummary'] as $tran=>$transit)

		<?php 
		$airportinfoArrival = FlightHelper::getAirportName($transit['ArrivalAirport']['LocationCode']);
		$airlineInfo = FlightHelper::getAirlineName($transit['FlightNumber']['code'],strlen($transit['FlightNumber']['code']));

		$airportinfoDepart = FlightHelper::getAirportName($transit['DepartureAirport']['LocationCode']);
		?>
				<tr>
					<td colspan="4">{{ date('l d F Y',strtotime($transit['DepartureDateTime'])) }} </td>
				</tr>
				<tr style="background-color: #e5e5e5 !important;color: black; vertical-align: top;">
					<td rowspan="4">
						
						<img src="{{public_path()}}/airline/{{$airlineInfo->thumbnail}}">

					</td>
					<td colspan="4">


						{{$airlineInfo->name}} {{$transit['FlightNumber']['number']}}</td>
				</tr>
				<tr style="background-color: #e5e5e5 !important;color: #2b376e; font-size: 12px;font-weight: 700;vertical-align: top;">
				
					<td>Departure</td>
					<td class="nolinebreak">{{ date('d M H:i',strtotime($transit['DepartureDateTime']))}}</td>
					<td class="nolinebreak">{{ $transit['DepartureAirport']['LocationCode'] }} ({{$airportinfoDepart}})</td>
					<td style="text-align: left;">Terminal: {{$transit['DepartureAirport']['TerminalID']}}</td>
				</tr>
				<tr style="background-color: #e5e5e5 !important;color: #2b376e; font-size: 12px;font-weight: 700;vertical-align: top;">
					
					<td >Arrival</td>
					<td class="nolinebreak">{{ date('d M H:i',strtotime($transit['ArrivalDateTime']))}}</td>
					<td class="nolinebreak">{{ $transit['ArrivalAirport']['LocationCode'] }} ({{$airportinfoArrival}})</td>
					<td></td>
				</tr>

				<tr style="background-color: #e5e5e5 !important;color: black;vertical-align: top;">
					
					<td style="color: #2b376e;font-size: 10.6px;">
						Duration<br>
						Booking Status<br>
						Class<br>
						Baggage allowance<br>
						Equipment<br>
						Flight meal<br>
					</td>
					<td></td>
					<td class="nolinebreak" style="color: #2b376e;font-size: 10.6px;">
						{{FlightHelper::calcTimeFormatted($transit['DepartureDateTime'],$transit['ArrivalDateTime'])}}<br>
						Confirmed<br>
						{{ $flightinfo['travelClass'] }}<br>
						{{$flightinfo['pax_specific_info'][$paxType]['baggage_allowed']}}<br>
						{{ $transit['AirEquipType'] }}<br>
						{{$flightinfo['pax_specific_info'][$paxType]['meal']}}<br>
					</td>
					<td></td>
				</tr>
			
			
			@endforeach

			<tr>
				<td colspan="5"></td>
			</tr>

			<tr style="background-color: #e5e5e5 !important;color: black;">
				<td></td>
				<td colspan="4"><b>Ticket details</b><br>	
					E-ticket <b>{{$tb_pax->ticket_no}}</b> for   {{$tb_pax->contact_name}}
				</td>
			
			</tr>


		
			
	</table>

	@if($ads[1])
	<img src="{{public_path()}}/ads/{{$ads[1]->attachment}}" style="height: 100px; width: 100%;">
	@endif
	</div>
</div>
</div>

@endforeach

</body>
</html>