@extends('frontend.layouts.app')
@section('content')

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
                                            <h2> {{ $countryInfo['DepartureCountryFullName'] }} to {{ $countryInfo['ArrivalCountryFullName'] }} ,

                                                Amount {{ $individualFlight['AirItineraryPricingInfo']['CurrencyCode'] }} {{ number_format($individualFlight['AirItineraryPricingInfo']['totalAmount']) }}
                                            </h2>
                                            {{-- <div class="flight-d-logo"><img alt="" src="/frontend/img/flight-d-logo.png"></div> --}}
                                            <div class="flight-d-i">
                                                <div class="flight-d-left">
                                                    <div class="flight-da">Flight <b> 
                                                    {{ $individualFlight['AirItinerary']['totalTime']['departtime']['FlightNumber']['code'] }}-{{ $individualFlight['AirItinerary']['totalTime']['departtime']['FlightNumber']['number'] }} </b></div>
                                                    <div class="flight-da">Air Company <b>{{ $individualFlight['AirItinerary']['totalTime']['departtime']['FlightNumber']['code'] }}</b></div>
                                                    <div class="flight-da">{{$array['AircraftType']}}</div>
                                                    @if( $individualFlight['AirItineraryPricingInfo']['totalBaggage'] )
                                                    <div class="flight-da">Baggage Allowed 
                                                        <span><b>
                                                            {{ $individualFlight['AirItineraryPricingInfo']['totalBaggage'] }}
                                                            {{ $individualFlight['AirItineraryPricingInfo']['BaggageUnit'] }}
                                                        </b>
                                                        </span>


                                                    </div>
                                                    @endif
                                                </div>
                                                <?php 
                                                $nepaliDate = new \App\Helpers\NepaliCalendar();
                                                  $DepartureDateTime = date('Y-m-d',strtotime($individualFlight['AirItinerary']['totalTime']['departtime']['DepartureDateTime']));
                                                  $nepDepartDate = $nepaliDate->formated_nepali_from_eng_date($DepartureDateTime);
                                                   $ArrivalDateTime = date('Y-m-d',strtotime($individualFlight['AirItinerary']['totalTime']['arriveTime']['ArrivalDateTime']));
                                                  $nepArriveDate = $nepaliDate->formated_nepali_from_eng_date($ArrivalDateTime);
                                                ?>

                                                <div class="flight-d-right">
                                                    <div class="flight-d-rightb">
                                                        <div class="flight-d-rightp">
                                                            <div class="flight-d-depart">
                                                                <span>Departure</span>
                                                      
                                                               {{ date('H:i',strtotime($individualFlight['AirItinerary']['totalTime']['departtime']['DepartureDateTime'])) }}
                                                               <br>
                                                                {{$nepDepartDate}}/
                                                               {{ date('d-M-Y',strtotime($DepartureDateTime)) }}<br>

                                                            </div>
                                                            <div class="flight-d-time">
                                                                <span>In Time</span>{{ $individualFlight['AirItinerary']['totalTime']['timeInfo']['hours'] }} Hrs 
                                                                {{ $individualFlight['AirItinerary']['totalTime']['timeInfo']['minutes'] }} Minute 
                                                                <div class="flight-d-time-icon"><img alt="" src="/frontend/img/flight-d-time.png"></div>
                                                            </div>
                                                            <div class="flight-d-arrival">
                                                                <span>Arrival</span> {{ date('H:i',strtotime($individualFlight['AirItinerary']['totalTime']['departtime']['ArrivalDateTime'])) }}<br>{{ $nepArriveDate }}/{{ date('d-M-Y',strtotime($ArrivalDateTime)) }}
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
                                                $airlineInfo = \FlightHelper::getAirlineName($individualFlight['AirItinerary']['totalTime']['departtime']['FlightNumber']['code'],2);
                                            ?>
                                                <h2>About {{ $airlineInfo->name }}</h2>
                                                <p>{!! $airlineInfo->description !!}</p>
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
                            TRANSIT INFO</div>
                            <div class="h-detail-lbl-b">{{ $individualFlight['AirItinerary']['tripType'] }} FLIGHT</div>
                        </div>
                        @foreach($individualFlight['AirItinerary']['transationSummary'] as $k=>$trans)
                        <div class="h-detail-stars fligts-s" >
                            <div class="flight-line-a">
                                <b>Airport</b>
                                <span> {{\FlightHelper::getAirportName($trans['DepartureAirport']['LocationCode'])
                                }}</span><br>
                                <time>{{ date('d-M-Y H:i',strtotime($trans['DepartureDateTime'])) }}</time>
                            </div>
                            

                            <div class="flight-line-d"></div>
                           <div class="flight-line-a">
                                <b>Airport</b>
                                <span>{{\FlightHelper::getAirportName($trans['ArrivalAirport']['LocationCode'])}}</span><br>
                                <time>{{ date('d-M-Y H:i',strtotime($trans['ArrivalDateTime'])) }}</time>
                            </div>
                             
                            <div class="clear"></div>
                        </div>
                        @endforeach 
                        @if($multicity && count($multicity) >0 )
                            <div style="border-bottom: 1px dotted black;"></div>
                        @endif
                        @foreach($multicity as $mc)

                            @foreach($mc['transationSummary'] as $k=>$trans)
                            <div class="h-detail-stars fligts-s">
                            <div class="flight-line-a">
                                <b>Airport</b>
                                <span> {{\FlightHelper::getAirportName($trans['DepartureAirport']['LocationCode'])
                                }}</span><br>
                                <time>{{ date('d-M-Y H:i',strtotime($trans['DepartureDateTime'])) }}</time>
                            </div>
                            

                            <div class="flight-line-d"></div>
                           <div class="flight-line-a">
                                <b>Airport</b>
                                <span>{{\FlightHelper::getAirportName($trans['ArrivalAirport']['LocationCode'])}}</span><br>
                                <time>{{ date('d-M-Y H:i',strtotime($trans['ArrivalDateTime'])) }}</time>
                            </div>
                             
                            <div class="clear"></div>
                            </div>   
                        @endforeach
                        @if(next($multicity))
                            <div style="border-bottom: 1px dotted black;"></div>
                        @endif
                        @endforeach
                        <div class="h-details-logo"><img alt="{{$airlineInfo->thumbnail}}" src="/airline/{{$airlineInfo->thumbnail}}"></div>
                         <form method="post" action="/reservationinternational-{{$segment}}" onsubmit="return bookFlight(this)">
                            {{ csrf_field() }}
                        <input type="hidden" name="searchId" value="{{ $uuid }}">
                        <div class="h-details-text">
                            <p>  @if(Auth::check())
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
                            </p>
                        </div>
                        </form>
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
<script type="text/javascript">
    
var timer2 =  '{{ gmdate('i:s',$remainingtime) }}';

var interval = setInterval(function() {


var timer = timer2.split(':');
//by parsing integer, I avoid all extra string processing
var minutes = parseInt(timer[0], 10);
var seconds = parseInt(timer[1], 10);
--seconds;
minutes = (seconds < 0) ? --minutes : minutes;
if (minutes < 0) {
    
    alert("Session Expired");

    clearInterval(interval);
    location.href = `/flights?{!! Cache::get('Q'.$uuid) !!}`;
    return 0;
}
seconds = (seconds < 0) ? 59 : seconds;
seconds = (seconds < 10) ? '0' + seconds : seconds;
//minutes = (minutes < 10) ?  minutes : minutes;
$('time.countdown').text(minutes + ':' + seconds);

timer2 = minutes + ':' + seconds;

}, 1000);


function bookFlight(ev){

    let submitbutton = $(ev).find(':submit');
    submitbutton.html(`<i class="fa fa-spinner fa-spin"></i>&nbsp;Booking`);
    submitbutton.prop("disabled",true);
    
}
</script>
@endsection
