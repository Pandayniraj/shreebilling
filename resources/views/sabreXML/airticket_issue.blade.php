@extends('sabreXML.layouts.master')
@section('content')

<AirTicketRQ xmlns="http://services.sabre.com/sp/air/ticket/v1" version="1.2.0" >
            <DesignatePrinter>
                <Printers>
                    <Hardcopy LNIATA="E5EA93"/> 
                     <InvoiceItinerary LNIATA="E5EA93"/> 
                    <Ticket CountryCode="IN"/>
                </Printers>
            </DesignatePrinter>
            <Itinerary ID="{{ $requestData->pnrCode }}"/>  
            <Ticketing>
                <FOP_Qualifiers>
                    <BasicFOP Type="CASH"/>
                </FOP_Qualifiers>
                <MiscQualifiers>
                    <Commission Percent="{{ $airlineInfo->airline_commission ?? 0 }}" /> 
                    <Ticket Type="ETR"/>
                </MiscQualifiers>
                <PricingQualifiers>
                    <PriceQuote>
                        @for($i = 1; $i <= $totalPax; $i++)
                            <Record Number="{{ $i }}"/>
                        @endfor
                    </PriceQuote>
                </PricingQualifiers>
            </Ticketing>
            <PostProcessing acceptNegotiatedFare="true" acceptPriceChanges="true" actionOnPQExpired="Q">
                <EndTransaction>
                    <Source ReceivedFrom="SWS"/> 
                </EndTransaction>
            </PostProcessing>
</AirTicketRQ>

@endsection