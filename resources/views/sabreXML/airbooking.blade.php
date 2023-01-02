@extends('sabreXML.layouts.master')
@section('content')
<EnhancedAirBookRQ xmlns="http://services.sabre.com/sp/eab/v3_9" version="3.9.0" HaltOnError="true">
            <OTA_AirBookRQ>
                <HaltOnStatus Code="UC" />
                <HaltOnStatus Code="LL" />
                <HaltOnStatus Code="UL" />
                <HaltOnStatus Code="UN" />
                <HaltOnStatus Code="NO" />
                <HaltOnStatus Code="HL" />
                <OriginDestinationInformation>

                    @foreach($requestData->transationSummary as $key=>$transit)

                    <FlightSegment DepartureDateTime="{{ $transit['DepartureDateTime'] }}" ArrivalDateTime="{{ $transit['ArrivalDateTime'] }}" 
                        FlightNumber="{{ $transit['FlightNumber']['validateFlightNumber'] }}" NumberInParty="{{$totalNumberInParty}}" ResBookDesigCode="{{$transit['ResBookDesigCode']}}" Status="NN">
                        <DestinationLocation LocationCode="{{ $transit['ArrivalAirport']['LocationCode'] }}" />
                        <Equipment AirEquipType="{{ $transit['AirEquipType']}}" />
                        <MarketingAirline Code="{{ $transit['MarketingAirline']['Code'] }}" FlightNumber="{{ $transit['FlightNumber']['validateFlightNumber'] }}" />
                        <OriginLocation LocationCode="{{ $transit['DepartureAirport']['LocationCode'] }}" />
                    </FlightSegment>

                    @endforeach

                    @foreach($requestData->transationMulticity as $key=>$transitMulti)
                        @foreach($transitMulti['transationSummary'] as $key=>$transit)

                        <FlightSegment DepartureDateTime="{{ $transit['DepartureDateTime'] }}" ArrivalDateTime="{{ $transit['ArrivalDateTime'] }}" 
                            FlightNumber="{{ $transit['FlightNumber']['validateFlightNumber'] }}" NumberInParty="1" ResBookDesigCode="{{ $transit['ResBookDesigCode'] }}" Status="NN">
                            <DestinationLocation LocationCode="{{ $transit['ArrivalAirport']['LocationCode'] }}" />
                            <Equipment AirEquipType="{{ $transit['AirEquipType']}}" />
                            <MarketingAirline Code="{{ $transit['MarketingAirline']['Code'] }}" FlightNumber="{{ $transit['FlightNumber']['validateFlightNumber'] }}" />
                            <OriginLocation LocationCode="{{ $transit['DepartureAirport']['LocationCode'] }}" />
                        </FlightSegment>

                        @endforeach
                    @endforeach
                    
                </OriginDestinationInformation>

                <RedisplayReservation NumAttempts="5" WaitInterval="5000" />
            </OTA_AirBookRQ>
            <OTA_AirPriceRQ>
                <PriceRequestInformation Retain="true">
                    <OptionalQualifiers>
                        <FlightQualifiers>
                            <VendorPrefs>
                                <Airline Code="{{ $requestData->ValidatingCarrier }}" />
                            </VendorPrefs>
                        </FlightQualifiers>
                        <PricingQualifiers>
                            <PassengerType Code="ADT"  Quantity="{{ $requestData->formData->adult_no }}" />
                            @if($requestData->formData->clild_no)
                            	<PassengerType Code="CNN"  Quantity="{{ $requestData->formData->clild_no }}" />
                            @endif
                            @if($requestData->formData->infant_no)
                            	<PassengerType Code="INF" Quantity="{{ $requestData->formData->infant_no }}" />
                            @endif
                            <Taxes>
                                <TaxExempt Code="NQ" />
                            </Taxes>
                        </PricingQualifiers>
                    </OptionalQualifiers>
                </PriceRequestInformation>
            </OTA_AirPriceRQ>
            <PostProcessing IgnoreAfter="false">
                <RedisplayReservation />
            </PostProcessing>
            <PreProcessing IgnoreBefore="false" />
        </EnhancedAirBookRQ>
@endsection