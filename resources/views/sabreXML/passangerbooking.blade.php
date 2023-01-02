@extends('sabreXML.layouts.master')
@section('content') 
 <PassengerDetailsRQ xmlns="http://services.sabre.com/sp/pd/v3_4" version="3.4.0" ignoreOnError="true" haltOnError="true">
            <PostProcessing ignoreAfter="true">
                <RedisplayReservation waitInterval = "5000"/>
                <EndTransactionRQ>
                    <EndTransaction Ind="true" />
                    <Source ReceivedFrom="SEALINKS API" />
                </EndTransactionRQ>
            </PostProcessing>
            <PriceQuoteInfo><?php $nameNumber = 0; $recordNumber = 1; ?>
                @foreach($passangerData as $pd => $link)
                    @foreach($link as $l=>$lk)
                        <Link nameNumber="{{ ++$nameNumber }}.1" record="{{ $recordNumber }}" />
                    @endforeach
                    <?php $recordNumber++; ?>
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
                        <?php 
                            $nameNumberIndex = 1;  
                            $passangerNameNumber = ['ADT'=>0,'CNN'=>0,'INF'=>0];
                            $otherDocNameNumber = [];
                        ?>
                    @foreach($passangerDataAdvanced['adv'] as $l=>$lk)
                        <AdvancePassenger SegmentNumber="A">
                                <Document ExpirationDate="{{$lk['passport_expiry_date']}}" Number="{{$lk['passport_number']}}" Type="P">
                                    <IssueCountry>NP</IssueCountry>
                                    <NationalityCountry>NP</NationalityCountry>
                                </Document>

                                @if($lk['pass_adv'] == 'INF')

                                <PersonName DateOfBirth="{{ $lk['dob'] }}" DocumentHolder="true" Gender="{{ $lk['gender'] }}I" NameNumber="{{ $lk['nameNumber'] }}.1">

                                @else

                                <PersonName DateOfBirth="{{ $lk['dob'] }}" DocumentHolder="true" Gender="{{ $lk['gender'] }}" NameNumber="{{ $lk['nameNumber']}}.1">
                                    
                                @endif
                                    <GivenName>{{ $lk['first_name'] }} {{$lk['middle_name']}}</GivenName>
                                    <Surname>{{ $lk['last_name'] }}</Surname>
                                </PersonName>
                                <VendorPrefs>
                                    <Airline Hosted="false"/>
                                </VendorPrefs>
                        </AdvancePassenger>

                        @endforeach
                         @foreach($passangerDataAdvanced['sec'] as $l=>$lk)
                          <?php 
                            $isInfant = ( $lk['pass_adv'] == 'INF' ) ? true: false;
                            $nameNumber = $lk['nameNumber'];
                            $otherDocNameNumber[] = ['data'=>$lk,'nameNumber'=>$nameNumber];
                        ?>
                        @if(!$isInfant)
                            <SecureFlight SegmentNumber="A">
                                <PersonName DateOfBirth="{{ $lk['dob'] }}" 
                                Gender="{{ $lk['gender'] }}" NameNumber="{{ $nameNumber }}.1">

                                <GivenName>{{ $lk['first_name'] }} {{$lk['middle_name']}}</GivenName>
                                <Surname>{{ $lk['last_name'] }}</Surname>
                                </PersonName>
                            </SecureFlight>
                        @endif
                    @endforeach

                    @foreach($otherDocNameNumber as $key=>$value)
                        <Service SSR_Code="OSI">
                         <PersonName NameNumber="{{$value['nameNumber']}}.1" />
                         <Text>
                            @if($value['data']['citizenship_number'])
                            Citizenship number {{$value['data']['citizenship_number']}}
                            @elseif($value['data']['birth_certificate_number'])
                            BirthCertificate number {{$value['data']['birth_certificate_number']}}
                            @elseif($value['data']['minor_id_number'])
                            MinorId number {{$value['data']['minor_id_number']}}
                            @endif
                         </Text>
                         <VendorPrefs>
                            <Airline   Code="{{ $validateCarrier['ValidatingCarrier'] }}" Hosted="false" />
                         </VendorPrefs>
                        </Service>
                    @endforeach

                    @foreach($passangerData['CNN'] as $key=>$value)
                        <Service SegmentNumber="A" SSR_Code="CHLD">
                            <PersonName NameNumber="{{$nameNumberStart['CNN']}}.1"/>
                            <VendorPrefs>
                                <Airline Hosted="false"/>
                            </VendorPrefs>
                        </Service>
                        <?php $nameNumberStart['CNN']++; ?>
                    @endforeach


                    @foreach($passangerData['INF'] as $key=>$value)
                    <Service SegmentNumber="A" SSR_Code="INFT">
                        <PersonName NameNumber="{{ $key + 1 }}.1"/> 
                        <Text>{{$value['last_name']}} /{{$value['first_name']}} {{$lk['middle_name']}} /{{ date('dMy',strtotime($value['dob']))}}</Text>
                        <VendorPrefs>
                            <Airline Hosted="false"/>
                        </VendorPrefs>
                    </Service>
                    @endforeach

                     <Service SSR_Code="CTCM">
                        <PersonName NameNumber="1.1"/>
                        <Text>{{$requestData->ticket_owner['phone']}}</Text>
                        <VendorPrefs>
                            <Airline Hosted="false"/>
                        </VendorPrefs>
                    </Service>
                    <Service SSR_Code="CTCE">
                        <PersonName NameNumber="1.1"/>
                        <Text>{{ str_replace('@','//', $requestData->ticket_owner['email'])  }}</Text>
                        <VendorPrefs>
                            <Airline Hosted="false"/>
                        </VendorPrefs>
                    </Service>
                    </SpecialServiceInfo>
                </SpecialServiceRQ>
            </SpecialReqDetails>
            <TravelItineraryAddInfoRQ>
                <AgencyInfo>
                    <Address>
                        <AddressLine>Sea Links Travels And Tours pvt Ltd</AddressLine>
                        <CityName>{{env('APP_ADDRESS')}}</CityName>
                        <CountryCode>NP</CountryCode>
                        <PostalCode>00977</PostalCode>
                        <StreetNmbr>00</StreetNmbr>
                        <VendorPrefs>
                            <Airline Hosted="false" />
                        </VendorPrefs>
                    </Address>
                    <Ticketing TicketType="7TAW" />
                </AgencyInfo>
                <CustomerInfo>
                    <ContactNumbers>
                        <ContactNumber NameNumber="1.1" Phone="{{env('APP_PHONE')}}" PhoneUseType="H" />
                    </ContactNumbers>
                    <Email Address="{{env('APP_EMAIL')}}" NameNumber="1.1" ShortText="AirlineTicket" />
                     <?php $nameNumberIndex1 = 0;  ?>
                    @foreach($passangerData as $pd => $link)
                    @foreach($link as $l=>$lk)
                    @if($pd == 'CNN')
                        <PersonName Infant="false"  
                        NameNumber="{{ ++$nameNumberIndex1}}.1" 
                        NameReference="C{{FlightHelper::getchildage($lk['dob'])}}">
                    @elseif($pd == 'INF')
                        <PersonName Infant="true"  
                        NameNumber="{{ ++$nameNumberIndex1}}.1" 
                        NameReference="I{{FlightHelper::getinfantage($lk['dob'])}}">
                    @else
                    <PersonName Infant="false" NameNumber="{{ ++$nameNumberIndex1}}.1" 
                        PassengerType="{{$pd}}">
                    @endif
                        <GivenName>{{ $lk['first_name'] }} {{ $lk['middle_name'] }}</GivenName>
                        <Surname>{{ $lk['last_name'] }}</Surname>
                    </PersonName>
                    @endforeach
                   @endforeach
                </CustomerInfo>
            </TravelItineraryAddInfoRQ>
        </PassengerDetailsRQ>
@endsection

