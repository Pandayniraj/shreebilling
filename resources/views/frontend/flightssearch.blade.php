@extends('frontend.layouts.app')
@section('content')
<style>
    .ui-state-active h4,
    .ui-state-active h4:visited {
        color: #26004d;
    }

    .ui-menu-item {
        width: 100%;
        border: 1px solid #ececf9;
    }

    .ui-widget-content .ui-state-active {
        background-color: green !important;
        border: none !important;
    }

    .list_item_container {
        width: 740px;
        height: 80px;
        float: left;
        margin-left: 20px;
    }

    .ui-widget-content .ui-state-active .list_item_container {
        background-color: #f5f5f5;
    }

    .label {
        width: 85%;
        float: right;
        white-space: nowrap;
        overflow: hidden;
        color: rgb(124, 77, 255);
        text-align: left;
    }

    input:focus {
        background-color: #f5f5f5;
    }

</style>
<!-- main-cont -->
<div class="main-cont">
   <div class="body-wrapper">
      <div class="wrapper-padding">
         <div class="two-colls">
            <div class="two-colls-left">
               <div class="srch-results-lbl fly-in">
                  <span>{{count($outbound)}} results found.</span>
               </div>
               <div class="side-block fly-in">
                  <div class="side-block-search">
                     <div class="page-search-p">
                        <form method="get" action="/flights">
                           <input type="hidden" name="strnationality" value="NP">
                           <input type="hidden" name="strclientip" value="49.25.14.12">
                           <!-- // -->
                           <div class="srch-tab-line">
                              <div class="srch-tab-left">
                                 <label>From</label>
                                 <div class="input-a"><input type="text" name="sector_form" value="{{old('sector_form')}}" class="sectors" placeholder="From.."> </div>
                              </div>
                              <div class="srch-tab-right">
                                 <label>To</label>
                                 <div class="input-a"><input type="text" name="sector_to" value="{{old('sector_to')}}" class="sectors" placeholder="To.."> </div>
                              </div>
                              <div class="clear"></div>
                           </div>
                           <!-- \\ -->
                           <!-- // -->
                           <div class="srch-tab-line">
                              <div class="srch-tab-left">
                                 <label>Departure</label>
                                 <div class="input-a"><input type="text" name="flight_date" value="{{old('flight_date')}}" class="date-inpt" placeholder="mm/dd/yy" autocomplete="off" required> <span class="date-icon"></span></div>
                              </div>
                              <div class="srch-tab-right">
                                 <label>Return</label>
                                 <div class="input-a"><input type="text" name="return_date" value="{{old('return_date')}}" class="date-inpt" placeholder="mm/dd/yy" autocomplete="off"> <span class="date-icon"></span></div>
                              </div>
                              <div class="clear"></div>
                           </div>
                           <!-- \\ -->
                           <div class="srch-tab-line ">
                              <div class="srch-tab-left ">
                                 <label>Adult</label>
                                 <div class="select-wrapper">
                                    <select class="custom-select" name="adult_no" required>
                                       <option value="">0</option>
                                       <option value="1">1</option>
                                       <option vlaue="2">2</option>
                                       <option value="3">3</option>
                                       <option value="4">4</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="srch-tab-right ">
                                 <label>Child</label>
                                 <div class="select-wrapper">
                                    <select class="custom-select" name="clild_no">
                                       <option value="0">0</option>
                                       <option value="1">1</option>
                                       <option value="2">2</option>
                                       <option value="3">3</option>
                                       <option value="4">4</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="clear"></div>
                           </div>
                           <div class="srch-tab-line no-margin-bottom">
                              <div class="srch-tab-left transformed">
                                 <div class="select-wrapper">
                                    <div class="select-wrapper">
                                       One Way
                                       <input type="radio" name="return" id="gridRadios1" value="O" checked><br>
                                       Two Way
                                       <input type="radio" name="return" id="gridRadios1" value="R">
                                    </div>
                                 </div>
                              </div>
                              <div class="clear"></div>
                           </div>
                           <!-- \\ -->
                           <button class="srch-btn">Search</button>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <div class="two-colls-right">
               <div class="two-colls-right-b">
                  <div class="padding">
                     <div class="catalog-head fly-in">
                        <label>Sort results by:</label>
                        <div class="search-select">
                           <select>
                              <option>Name</option>
                              <option>Name</option>
                              <option>Name</option>
                              <option>Name</option>
                              <option>Name</option>
                           </select>
                        </div>
                        <div class="search-select">
                           <select>
                              <option>Price</option>
                              <option>Price</option>
                              <option>Price</option>
                              <option>Price</option>
                              <option>Price</option>
                           </select>
                        </div>
                        <div class="search-select">
                           <select>
                              <option>Duration</option>
                              <option>Duration</option>
                              <option>Duration</option>
                              <option>Duration</option>
                              <option>Duration</option>
                           </select>
                        </div>
                        <a href="#" class="show-list"></a>
                        <div class="clear"></div>
                     </div>
                     <div class="catalog-row">
                        @foreach($outbound as $key => $value)
                        <form method="post" action="/reservation">
                           {{ @csrf_field() }}
                           <!-- // flight-item // -->
                           <div class="flight-item fly-in">
                              <div class="flt-i-a">
                                 <div class="flt-i-b">
                                    <div class="flt-i-bb">
                                       <div class="flt-l-a">
                                          <div class="flt-l-b">
                                             <div class="way-lbl">One Way</div>
                                             <div class="company"><img alt="" src="{{$value['AirlineLogo']}}"></div>
                                          </div>
                                          <div class="flt-l-c">
                                             <div class="flt-l-cb">
                                                <div class="flt-l-c-padding">
                                                   <div class="flyght-info-head">{{$value['FlightDate']}} {{$value['Departure']}}- {{$value['Arrival']}}</div>
                                                   <!-- // -->
                                                   <div class="flight-line">
                                                      <div class="flight-radio"><label><input name="radio" type="radio" /></label> </div>
                                                      <div class="flight-line-a">
                                                         <b>Departure</b>
                                                         <span>{{$value['DepartureTime']}}</span>
                                                      </div>
                                                      <div class="flight-line-d"></div>
                                                      <div class="flight-line-a">
                                                         <b>Arrival</b>
                                                         <span>{{$value['ArrivalTime']}}</span>
                                                      </div>
                                                      <div class="flight-line-d"></div>
                                                      <div class="flight-line-a">
                                                         <b>Flight No</b>
                                                         <span>{{$value['FlightNo']}}</span>
                                                      </div>
                                                      <div class="flight-line-b">
                                                         <b>details</b>
                                                         <span>View More!</span>
                                                      </div>
                                                      <div class="clear"></div>
                                                      <!-- // details // -->
                                                      <div class="flight-details">
                                                         <div class="flight-details-l">
                                                            <div class="flight-details-a">{{$value['FlightDate']}}</div>
                                                            <div class="flight-details-b">{{$value['DepartureTime']}} {{$value['Departure']}}</div>
                                                         </div>
                                                         <div class="flight-details-r">
                                                            <div class="flight-details-a">{{$value['FlightDate']}}</div>
                                                            <div class="flight-details-b">{{$value['ArrivalTime']}} {{$value['Arrival']}}</div>
                                                         </div>
                                                         <div class="clear"></div>
                                                         <div class="flight-details-d">
                                                            {{$value['Airline']}} {{$value['AircraftType']}} Class-{{$value['FlightClassCode']}}<br>
                                                            @if($value['Refundable']=='T') Refundable @else Non Refundable @endif, Free Baggage {{$value['FreeBaggage']}}
                                                         </div>
                                                      </div>
                                                      <!-- \\ details \\ -->
                                                   </div>
                                                </div>
                                             </div>
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
                                       <div class="flt-i-price">{{$value['Currency']}} {{ number_format($value['AdultFare']+$value['FuelSurcharge']+$value['Tax'],2) }}</div>
                                       <div class="flt-i-price-b">avg/person</div>
                                    </div>
                                    <input type="hidden" name="FlightId" value="{{$value['FlightId']}}">
                                    @if($value['ReturnFlightId'])
                                    <input type="hidden" name="ReturnFlightId" value="{{$value['ReturnFlightId']}}">
                                    @endif
                                    <button type="submit" class="cat-list-btn">
                                    Book now
                                    </button>
                                    <a href="/flight/detail?FlightId={{$value['FlightId']}}" class="cat-list-btn">
                                    Details
                                    </a>
                                 </div>
                              </div>
                              <div class="clear"></div>
                           </div>
                           <!-- \\ flight-item \\ -->
                        </form>
                        @endforeach
                     </div>
                     <div class="clear"></div>
                     <div class="pagination">
                        <a class="active" href="#">1</a>
                        <a href="#">2</a>
                        <a href="#">3</a>
                        <div class="clear"></div>
                     </div>
                  </div>
               </div>
               <br class="clear" />
            </div>
         </div>
         <div class="clear"></div>
      </div>
   </div>
</div>
<!-- /main-cont -->
<script>
    $(function() {
        $(".sectors").autocomplete({
            source: '/getSectors'
            , minLength: 1
        });
    });

</script>

@endsection
