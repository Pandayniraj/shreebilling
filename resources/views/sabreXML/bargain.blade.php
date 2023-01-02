@extends('sabreXML.layouts.master')
@section('content') <?php $_MODE= Config::get('sabre.env');  ?>
<OTA_AirLowFareSearchRQ xmlns:xs="http://www.w3.org/2020/XMLSchema" xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2020/XMLSchema-instance" Target="Production" Version="6.1.0" ResponseType="OTA" ResponseVersion="6.1.0">
	<POS>
		<Source PseudoCityCode="{{Config::get("sabre.{$_MODE}.group") }}">
			<RequestorID ID="1" Type="1">
				<CompanyName Code="TN"/>
			</RequestorID>
		</Source>
	</POS>

	<OriginDestinationInformation RPH="1">
		<DepartureDateTime>{{$requestData->strFlightDate}}</DepartureDateTime>
		<OriginLocation LocationCode="{{$requestData->strSectorFrom}}"/>
		<DestinationLocation LocationCode="{{$requestData->strSectorTo}}"/>
		<TPA_Extensions>
			<SegmentType Code="O"/>
		</TPA_Extensions>
	</OriginDestinationInformation>
	@if($requestData->multicity)
		@foreach($requestData->multicitydata as $mk=>$md)
			<OriginDestinationInformation RPH="{{$mk+2}}">
				<DepartureDateTime>{{$md->departdate}}</DepartureDateTime>
				<OriginLocation LocationCode="{{$md->from}}"/>
				<DestinationLocation LocationCode="{{$md->to}}"/>
				<TPA_Extensions>
					<SegmentType Code="O"/>
				</TPA_Extensions>
			</OriginDestinationInformation>
		@endforeach
	@elseif($requestData->strReturnDate)
	<OriginDestinationInformation RPH="2">
		<DepartureDateTime>{{$requestData->strReturnDate}}</DepartureDateTime>
		<OriginLocation LocationCode="{{$requestData->strSectorTo}}"/>
		<DestinationLocation LocationCode="{{$requestData->strSectorFrom}}"/>
		<TPA_Extensions>
			<SegmentType Code="O"/>
		</TPA_Extensions>
	</OriginDestinationInformation>
	@endif

	<TravelPreferences ValidInterlineTicket="true">
		@if($requestData->cabin)
		<CabinPref PreferLevel="Preferred" Cabin="{{ $requestData->cabin }}"/>
		@endif
		<TPA_Extensions>
			<TripType Value="{{ $requestData->tripType }}"/>
			<LongConnectTime Min="90" Max="1200" Enable="true"/>
			<ExcludeCallDirectCarriers Enabled="true"/>
		</TPA_Extensions>
		<Baggage RequestType='A' Description='true' FreePieceRequired='true' ></Baggage>
	</TravelPreferences>
	<TravelerInfoSummary>
		<SeatsRequested>{{$totalNumberSeat}}</SeatsRequested>
		<AirTravelerAvail>
			@if($requestData->adult_no > 0)
			<PassengerTypeQuantity Code="ADT" Quantity="{{ $requestData->adult_no }}"/>
			@endif
			@if($requestData->clild_no > 0)
			<PassengerTypeQuantity Code="CNN" Quantity="{{ $requestData->clild_no }}"/>
			@endif
			@if($requestData->infant_no > 0)
			<PassengerTypeQuantity Code="INF" Quantity="{{ $requestData->infant_no }}"/>
			@endif
		</AirTravelerAvail>
		<PriceRequestInformation>
                <TPA_Extensions>
                    <Indicators>
                    	@if($requestData->is_refundable == 'on')
                        	<RefundPenalty Ind="false"/>
                        @else
                        	<RefundPenalty Ind="true"/>
                        @endif
                    </Indicators>
                </TPA_Extensions>
        </PriceRequestInformation>
	</TravelerInfoSummary>
	<TPA_Extensions>
		<IntelliSellTransaction>
			<RequestType Name="50ITINS"/>
		</IntelliSellTransaction>
	</TPA_Extensions>
</OTA_AirLowFareSearchRQ>
@endsection