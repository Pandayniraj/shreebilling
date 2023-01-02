<div class="catalog-row">
  <?php 
    $nepaliDate = new \App\Helpers\NepaliCalendar();
  ?>

@foreach($individualFlight as $key => $value)
<?php $airline = \FlightHelper::getAirlineName($value['AirItinerary']['totalTime']['departtime']['OperatingAirline']['Code'],2); ?>
<form method="post" action="/reservationinternational-{{$value['SequenceNumber']}}" onsubmit="return makeBooking(this)">
   {{ csrf_field() }}
   <?php $multicity = $value['AirItinerary']['multicityInfo']; ?>
   <!-- // flight-item // -->
   <div class="flight-item fly-in">
      <div class="flt-i-a">
         <div class="flt-i-b">
            <?php
               $carrierAirline = \FlightHelper::getAirlineName($value['ValidatingCarrier']['ValidatingCarrier'],2);

              ?> 

            <div class="flt-i-bb">
               <div class="flt-l-a">
                  <div class="flt-l-b">
                 
                     <div class="way-lbl">{{ $carrierAirline->name }}</div>



                     <div class="company"><img alt="" src="/airline/{{$carrierAirline->thumbnail}}"></div>
                  </div>
                  <div class="flt-l-c">
                     <div class="flt-l-cb" style="@if($multicity && count($multicity) >0 )border-bottom: 1px dotted black;@endif">
                        <div class="flt-l-c-padding">
                           <div class="flyght-info-head">
                            <?php 
                              $DepartureDateTime = date('Y-m-d',strtotime($value['AirItinerary']['totalTime']['departtime']['DepartureDateTime']));
                              $nepDepartDate = $nepaliDate->formated_nepali_from_eng_date($DepartureDateTime);

                            ?>

                            {{ date('d-M-Y',strtotime($DepartureDateTime)) }} /{{$nepDepartDate}}

                            {{ $airportInfo['Dept_name'] }}- {{ $airportInfo['Arrive_name'] }}


                           </div>
                           <!-- // -->
                           <div class="flight-line">
                              <div class="flight-radio"><label><input name="radio" type="radio" /></label> </div>
                              <div class="flight-line-a">
                                 <b>Departure</b>
                                 <span>{{ date('H:i',strtotime($value['AirItinerary']['totalTime']['departtime']['DepartureDateTime'])) }}</span>
                              </div>
                              <div class="flight-line-d"></div>
                              <div class="flight-line-a">
                                 <b>Arrival</b>
                                 <span>{{ date('H:i',strtotime($value['AirItinerary']['totalTime']['arriveTime']['ArrivalDateTime'])) }}</span>
                              </div>
                              <div class="flight-line-d"></div>
                              <div class="flight-line-a">
                                 <b>Total Flight Time</b>
                                 <span>
                                  {{$value['AirItinerary']['totalTime']['timeInfo']['hours']}}Hrs {{$value['AirItinerary']['totalTime']['timeInfo']['minutes']}} Min
                                </span>
                              </div>
                              
                              

                              <div class="flight-line-b">
                                 <b>details</b>
                                 <span>View More!
                                  <br> <span style="color: #FF7200;">{{count($value['AirItinerary']['transationSummary'] ) - 1}} STOPS</span>
                                 </span>
                              </div><br>
                              <div class="flight-radio"><label><input name="radio" type="radio" /></label> </div>
                             
                              <div class="flight-line-a">
                                 <b>Flight No</b>
                                   <span>
                                  {{$value['AirItinerary']['totalTime']['departtime']['FlightNumber']['code']}}-
                                    {{$value['AirItinerary']['totalTime']['departtime']['FlightNumber']['validateFlightNumber']}}</span>
                                 
                              </div>
                              <div class="flight-line-d"></div>
                              <div class="flight-line-a">
                                 <b>Baggage</b>
                               <span>
                                  {{$value['AirItineraryPricingInfo']['totalBaggage']}} {{ucwords($value['AirItineraryPricingInfo']['BaggageUnit'])}}</span>
                              </div>
                              <div class="clear"></div>
                              <!-- // details // -->
                              @foreach($value['AirItinerary']['transationSummary'] as $ai=>$trans)
                              
                              <div class="flight-details">
                                 <div class="flight-details-l">
                                    <div class="flight-details-a">
                                      {{ date('d-M-Y',strtotime($trans['DepartureDateTime'])) }}
                                    </div>
                                    <div class="flight-details-b">{{ date('H:i',strtotime($trans['DepartureDateTime'])) }} 
                                      {{
                                        \FlightHelper::getAirportName($trans['DepartureAirport']['LocationCode'])
                                      }}
                                    </div>
                                 </div>
                                 <div class="flight-details-r">
                                    <div class="flight-details-a">{{ date('d-M-Y',strtotime($trans['ArrivalDateTime'])) }}</div>
                                    <div class="flight-details-b">{{ date('H:i',strtotime($trans['ArrivalDateTime'])) }} 
                                    {{
                                        \FlightHelper::getAirportName($trans['ArrivalAirport']['LocationCode'])
                                      }}
                                    </div>
                                 </div>
                                 <div class="clear"></div>
                                 <div class="flight-details-d">
                                   Flight Number  {{$trans['FlightNumber']['code']}}-{{$trans['FlightNumber']['number']}}<br>
                                   Flight  Duration {{ $trans['totalTime']['timeInfo']['hours'] }} Hours {{ $trans['totalTime']['timeInfo']['minutes'] }} Minutes
                                 </div>
                              </div>
                              @if($ai != array_key_last($value['AirItinerary']['transationSummary']))
                              <div class="flight-details">
                                <div class="flight-details-l">
                                 <div class="flight-details-a" style="margin-top: -7px;"><i class="fa fa-clock-o"></i> 
                                  Connecting Time  {{FlightHelper::calcTimeFormatted($trans['ArrivalDateTime'],$value['AirItinerary']['transationSummary'][$ai+1]['DepartureDateTime'])}} </div>

                              </div></div>
                              @endif
                              @endforeach
                              <!-- \\ details \\ -->
                           </div>
                        </div>
                     </div>
                      @if($multicity && count($multicity) >0 )
                        @foreach($multicity as $mc=>$multi)
                          @include('frontend.partials.muliticity_more_search')
                        @endforeach
                      @endif
                     <br class="clear" />
                  </div>
               </div>
               <div class="clear"></div>
            </div>

            <br class="clear" />
         </div>
      </div>
      <div class="flt-i-c">
         <div class="flt-i-padding">
            <div class="flt-i-price-i">
               <div class="flt-i-price">{{$value['AirItineraryPricingInfo']['CurrencyCode']}} 
                {{number_format($value['AirItineraryPricingInfo']['totalAmount'])}}
                </div>
               <div class="flt-i-price-b">avg/person</div>
            </div>
            <input type="hidden" name="FlightId" value="{{$value['FlightId']}}">
            @if($value['ReturnFlightId'])
            <input type="hidden" name="ReturnFlightId" value="{{$value['ReturnFlightId']}}">
            @endif
            <input type="hidden" name="searchId" value="{{$uuid}}">

             @if(Auth::check())
                <button type="submit" class="cat-list-btn submit-button">
                Book now
                </button>
                @else
                <button type="button" class="cat-list-btn guestlogin">
                Book now
                </button>
                @endif
            <a href="/flight-{{$value['SequenceNumber']}}/detail?searchId={{$uuid}}" class="cat-list-btn" target="_blank">
            Details
            </a>
         </div>
      </div>
      <div class="clear"></div>
   </div>
   <!-- \\ flight-item \\ -->
</form><hr>
@endforeach
<script type="text/javascript">
  function makeBooking(ev){
    
    let submitbutton = $(ev).find(':submit');
    
    submitbutton.html(`<i class="fa fa-spinner fa-spin"></i>&nbsp;Booking`);
    
    submitbutton.prop("disabled",true);

  }
</script>
 </div>