@extends('sabreXML.layouts.master')
@section('content') <?php $_MODE= Config::get('sabre.env');  ?>
 <PassengerDetailsRQ xmlns="http://services.sabre.com/sp/pd/v3_4" version="3.4.0" ignoreOnError="true" haltOnError="true">
            <PostProcessing ignoreAfter="true">
                <RedisplayReservation waitInterval = "5000"/>
                <EndTransactionRQ>
                    <EndTransaction Ind="true" />
                    <Source ReceivedFrom="SWS Testing" />
                </EndTransactionRQ>
            </PostProcessing>
            <PriceQuoteInfo><?php $nameNumber = 0; ?>
                @foreach($passangerData as $pd => $link)
                    <?php $recordNumber = 0; ?>
                    @foreach($link as $l=>$lk)
                        <Link nameNumber="{{ ++$nameNumber }}.1" record="{{ ++$recordNumber }}" />
                    @endforeach
                @endforeach
            </PriceQuoteInfo>
            <SpecialReqDetails>
                <AddRemarkRQ>
                    <RemarkInfo>
                        <FOP_Remark Type="CASH" />
                    </RemarkInfo>
                </AddRemarkRQ>
                <SpecialServiceRQ>
                    <SpecialServiceInfo>
                        <SecureFlight SegmentNumber="A">
                            @foreach($passangerData as $pd => $link)
                            @foreach($link as $l=>$lk)
                            <PersonName DateOfBirth="{{ $lk['dob'] }}" Gender="{{ $lk['gender'] }}" NameNumber="1.1">
                                <GivenName>{{ $lk['first_name'] }}</GivenName>
                                <Surname>{{ $lk['last_name'] }}</Surname>
                            </PersonName>
                            @endforeach
                            @endforeach
                        </SecureFlight>
                    </SpecialServiceInfo>
                </SpecialServiceRQ>
            </SpecialReqDetails>
            <TravelItineraryAddInfoRQ>
                <AgencyInfo>
                    <Address>
                        <AddressLine>TEST Intl Travel</AddressLine>
                        <CityName>KATHMANDU</CityName>
                        <CountryCode>NP</CountryCode>
                        <PostalCode>00977</PostalCode>
                        <StreetNmbr>12</StreetNmbr>
                        <VendorPrefs>
                            <Airline Hosted="false" />
                        </VendorPrefs>
                    </Address>
                    <Ticketing TicketType="7TAW" />
                </AgencyInfo>
                <CustomerInfo>
                    <ContactNumbers>
                        <ContactNumber NameNumber="1.1" Phone="977-9801234567" PhoneUseType="H" />
                    </ContactNumbers>
                    <Email Address="rajunpl@gmail.com" NameNumber="1.1" ShortText="AirlineTicket" />
                    @foreach($passangerData as $pd => $link)
                    @foreach($link as $l=>$lk)
                    <PersonName Infant="false" NameNumber="1.1" PassengerType="{{ $lk['pax_type_formatted'] }}">
                        <GivenName>{{ $lk['first_name'] }}</GivenName>
                        <Surname>{{ $lk['last_name'] }}</Surname>
                    </PersonName>
                    @endforeach
                   @endforeach
                </CustomerInfo>
            </TravelItineraryAddInfoRQ>
        </PassengerDetailsRQ>
@endsection