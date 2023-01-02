@extends('frontend.layouts.app')
@section('content')
<?php $airline = FlightHelper::getAirlineName($array['Airline'],strlen($array['Airline'])); ?>
<!-- main-cont -->
<div class="main-cont">
    <div class="body-wrapper">
        <div class="wrapper-padding">


            <div class="sp-page">

                <div class="sp-page-a">
                    <div class="sp-page-l">
                        <div class="sp-page-lb">
                            <div class="sp-page-p">


                                <div class="content-tabs">

                                    <div class="content-tabs-body">
                                        <!-- // content-tabs-i // -->
                                        <div class="content-tabs-i">
                                            <h2>{{$array['Departure']}} to {{$array['Arrival']}}, Total Amount- {{$array['Currency']}} {{ \FlightHelper::DomesticTotalAmount($array) }}</h2>
                                            {{-- <div class="flight-d-logo"><img alt="" src="/frontend/img/flight-d-logo.png"></div> --}}
                                            <div class="flight-d-i">
                                                <div class="flight-d-left">
                                                    <div class="flight-da">Flight <b>{{$array['FlightNo']}}</b></div>
                                                    <div class="flight-da">Air Company <b>{{$array['Airline']}}</b></div>
                                                    <div class="flight-da">{{$array['AircraftType']}}</div>
                                                    <div class="flight-da">Class {{$array['FlightClassCode']}}</div>
                                                    <div class="flight-da">Baggage Allowed {{$array['FreeBaggage']}}</div>
                                                </div>
                                                <div class="flight-d-right">
                                                    <div class="flight-d-rightb">
                                                        <div class="flight-d-rightp">
                                                            <div class="flight-d-depart">
                                                                <span>Departure</span> {{$array['DepartureTime']}}<br>
                                                                {{$array['Departure']}}<br>

                                                            </div>
                                                            <div class="flight-d-time">

                                                                <span>In Time</span> {{ FlightHelper::calcTimeFormatted($array['DepartureTime'],$array['ArrivalTime']) }}
                                                                <div class="flight-d-time-icon"><img alt="" src="/frontend/img/flight-d-time.png"></div>
                                                            </div>
                                                            <div class="flight-d-arrival">
                                                                <span>Arrival</span> {{$array['ArrivalTime']}}<br>
                                                                {{$array['Arrival']}}<br>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>

                                            </div>
                                            <div class="clear"></div>
                                            <div class="flight-d-devider"></div>
                                            <div class="flight-d-text">
                                                <?php 
                                                    $airlineInfo = $airline; ?>
                                                <h2>{{ $airlineInfo->name }}</h2>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="clear"></div>
                    </div>
                </div>

                <div class="sp-page-r">
                    <div class="h-detail-r">
                        <div class="h-detail-lbl">
                            <div class="h-detail-lbl-a">{{$array['Departure']}} to {{$array['Arrival']}}</div>
                            <div class="h-detail-lbl-b">ONEWAY FLIGHT</div>
                        </div>
                        <div class="h-detail-stars fligts-s">
                            <div class="flight-line-a">
                                <b>Departure</b>
                                <span>{{$array['DepartureTime']}}</span>
                            </div>

                            <div class="flight-line-d"></div>
                            <div class="flight-line-a">
                                <b>Arrival</b>
                                <span>{{$array['ArrivalTime']}}</span>
                            </div>
                            <div class="flight-line-d"></div>
                            <div class="flight-line-a">
                                <b>Date</b>
                                <span>{{$array['FlightDate']}}</span>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="h-details-logo"><img alt="{{$airline->thumbnail}}" src="/airline/{{$airline->thumbnail}}"></div>
                        <div class="h-details-text">
                            <form method="post" action="/reservation">
                                {{ @csrf_field() }}
                                <input type="hidden" name="FlightId" value="{{$array['FlightId']}}">
                                <input type="hidden" name="uuid" value="{{$uuid}}">
                                @if($value['ReturnFlightId'])
                                <input type="hidden" name="ReturnFlightId" value="{{$array['ReturnFlightId']}}">
                                @endif
                                @if(Auth::check())
                                <button type="submit" class="cat-list-btn" style="background-color:#ed8e3b;color:#ffffff;">
                                    BOOK FLIGHT NOW
                                    <div class="clear"></div>
                                </button>
                                @else
                                <button type="button" class="cat-list-btn guestlogin" style="background-color:#ed8e3b;color:#ffffff;">
                                    BOOK FLIGHT NOW
                                    <div class="clear"></div>
                                </button>
                                @endif


                            </form>
                        </div>

                    </div>

                    <div class="h-help">
                        <div class="h-help-lbl">Need {{ env('SHORT_NAME')}} Help?</div>
                        <div class="h-help-lbl-a">We would be happy to help you!</div>
                        <div class="h-help-phone">{{ env('CONTACT')}}</div>
                        <div class="h-help-email">{{ env('APP_EMAIL')}}</div>
                    </div>



                </div>
                <div class="clear"></div>
            </div>

        </div>
    </div>
</div>
<!-- /main-cont -->

@endsection
