 <div class="flt-l-cb" style="@if(next($multicity))border-bottom: 1px dotted black;@endif">
  <div class="flt-l-c-padding">
   <div class="flyght-info-head">
    <?php 
      $DepartureDateTime = date('Y-m-d',strtotime($multi['totalTime']['departtime']['DepartureDateTime']));
      $nepDepartDate = $nepaliDate->formated_nepali_from_eng_date($DepartureDateTime);

    ?>

    {{ date('d-M-Y',strtotime($DepartureDateTime)) }} /{{$nepDepartDate}}

    {{ $multicityAirportinfo[$mc]['Dept_name'] }}- {{ $multicityAirportinfo[$mc]['Arrive_name'] }}


   </div>
   <!-- // -->
   <div class="flight-line">
      <div class="flight-radio"><label><input name="radio" type="radio" /></label> </div>
      <div class="flight-line-a">
         <b>Departure</b>
         <span>{{ date('H:i',strtotime($multi['totalTime']['departtime']['DepartureDateTime'])) }}</span>
      </div>
      <div class="flight-line-d"></div>
      <div class="flight-line-a">
         <b>Arrival</b>
         <span>{{ date('H:i',strtotime($multi['totalTime']['arriveTime']['ArrivalDateTime'])) }}</span>
      </div>
      <div class="flight-line-d"></div>
      <div class="flight-line-a">
         <b>Total Flight Time</b>
         <span>
          {{$multi['totalTime']['timeInfo']['hours']}}Hrs {{$multi['totalTime']['timeInfo']['minutes']}} Min
        </span>
      </div>
      
      

      <div class="flight-line-b">
         <b>details</b>
         <span>View More!
          <br> <span style="color: #FF7200;">{{count($multi['transationSummary'])}} STOPS</span>
         </span>
      </div><br>
      <div class="flight-radio"><label><input name="radio" type="radio" /></label> </div>
     
      <div class="flight-line-a">
         <b>Flight No</b>
           <span>
          {{$multi['totalTime']['departtime']['FlightNumber']['code']}}-
            {{$multi['totalTime']['departtime']['FlightNumber']['number']}}</span>
         
      </div>
      {{-- <div class="flight-line-d"></div>
      <div class="flight-line-a">
         <b>Baggage</b>
       <span>
          {{$value['AirItineraryPricingInfo']['totalBaggage']}} {{ucwords($value['AirItineraryPricingInfo']['BaggageUnit'])}}</span>
      </div> --}}
      <div class="clear"></div>
      <!-- // details // -->
      @foreach($multi['transationSummary'] as $ai=>$trans)
      
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
           Required Time {{ $trans['totalTime']['timeInfo']['hours'] }} Hours {{ $trans['totalTime']['timeInfo']['minutes'] }} Minutes
         </div>
      </div>
      @if($ai != array_key_last($multi['transationSummary']))
      <div class="flight-details">
        <div class="flight-details-l">
         <div class="flight-details-a" style="margin-top: -7px;"><i class="fa fa-clock-o"></i> 
          Connecting Time  {{FlightHelper::calcTimeFormatted($trans['ArrivalDateTime'],$multi['transationSummary'][$ai+1]['DepartureDateTime'])}} </div>

      </div></div>
      @endif
      @endforeach
      <!-- \\ details \\ -->
   </div>
  </div>
</div>