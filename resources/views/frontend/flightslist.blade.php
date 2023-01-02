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
                <a class="btn btn-danger pull-right" href="javascript:void()" id='next_day'>Next Day <i class="fa fa-arrow-circle-right"></i></a>
                <div class="two-colls-left">
                     <a class="btn btn-primary pull-right" href="javascript:void()" id='prev_day'><i class="fa fa-arrow-circle-left"></i> Previous Day</a>
                      
                    <div class="srch-results-lbl fly-in">
                        <span>{{count($outbound)}} results found.</span>
                    </div>
                    <div class="side-block fly-in">
                        <div class="side-block-search">
                            <div class="page-search-p">
                                <form method="get" action="/flights" id='fight_search_form'>
                                    <input type="hidden" name="strnationality" value="NP">

                                    <!-- // -->
                                    <div class="srch-tab-line">
                                        <div class="srch-tab-left">
                                            <label>From</label>
                                            <div class="input-a"><input type="text" name="sector_form" value="{{Request::get('sector_form')}}" class="sectors" placeholder="From.."> </div>
                                        </div>
                                        <div class="srch-tab-right">
                                            <label>To</label>
                                            <div class="input-a"><input type="text" name="sector_to" value="{{Request::get('sector_to')}}" class="sectors" placeholder="To.."> </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <!-- \\ -->
                                    <!-- // -->
                                    <div class="srch-tab-line">
                                        <div class="srch-tab-left">
                                            <label>Departure</label>
                                            <div class="input-a"><input type="text" name="flight_date" value="{{Request::get('flight_date')}}" class="date-inpt-today" placeholder="mm/dd/yy" autocomplete="off"    id='depart_date'
                                                required> <span class="date-icon"></span></div>
                                        </div>
                                        <div class="srch-tab-right">
                                            <label>Return</label>
                                            <div class="input-a"><input type="text" name="return_date" value="{{Request::get('return_date')}}" class="date-inpt-today" placeholder="mm/dd/yy" autocomplete="off"> <span class="date-icon"></span></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <!-- \\ -->
                                    <div class="srch-tab-line ">
                                        <div class="srch-tab-left ">
                                            <label>Adult</label>
                                            <div class="select-wrapper">
                                                 {!! Form::select('adult_no',['0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4],Request::get('adult_no'),['class'=>'form-control','required'=>true]) !!}
                                            </div>
                                        </div>
                                        <div class="srch-tab-right ">
                                            <label>Child</label>
                                            <div class="select-wrapper">
                                                {!! Form::select('clild_no',['0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4],Request::get('clild_no'),['class'=>'form-control','required'=>true]) !!}
                                            </div>
                                        </div>
                                        <div class="srch-tab-left ">
                                            <label>Travel Class</label>
                                            <div class="select-wrapper">
                                                 <select class="form-control" name="cabin">
                                                    <option value="">All Class</option>
                                                    <option value="Y">Economy class</option>
                                                    <option value="C">Business class</option>
                                                    <option value="F">First class</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="srch-tab-right ">
                                            <label>Nationality</label>
                                            <div class="select-wrapper">
                                                <select class="form-control" name="strnationality">
                                                     <option value="">Select Nation</option>
                                                    @foreach(FlightHelper::getNationality() as $np=>$nation)
                                                    <option value="{{ $nation->iso }}" @if($nation->iso == Request::get('strnationality') ) selected @endif>{{ $nation->nicename }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="srch-tab-line no-margin-bottom">
                                        
                                                <div class="select-wrapper">
                                            
                                                     <input type="radio" name="return" id="gridRadios1" value="O" @if(Request::get('return') != 'R') checked @endif>
                                                    One Way
                                                    <input type="radio" name="return" id="gridRadios1" value="R" @if(Request::get('return') == 'R') checked @endif>
                                                    Two Way
                                                    
                                             
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

<div class="catalog-row">
    <?php 
        $nepaliDate = new \App\Helpers\NepaliCalendar();
    ?>
    @foreach($outbound as $key => $value)
    <?php $airline = \FlightHelper::getAirlineName($value['Airline'],strlen($value['Airline'])); ?>
    <form method="post" action="/reservation" onsubmit="return makeBooking(this)" 
    id='{{$key}}-bookform'>
        {{ @csrf_field() }}

        <input type="hidden" name="uuid" value="{{ $uuid }}">
        <!-- // flight-item // -->
        <div class="flight-item fly-in">
            <div class="flt-i-a">
                <div class="flt-i-b">
                    <div class="flt-i-bb">
                        <div class="flt-l-a">
                            <div class="flt-l-b">
                                <div class="way-lbl">{{$airline->name}}</div>
                                <div class="company"><img alt="{{$airline->thumbnail}}" src="/airline/{{$airline->thumbnail}}" width="50%" style="object-fit: cover;"></div>
                            </div>
                            <div class="flt-l-c">
                                <div class="flt-l-cb">
                                    <div class="flt-l-c-padding">

                                        <div class="flyght-info-head">{{$value['FlightDate']}}/ {{$nepaliDate->formated_nepali_from_eng_date(date('Y-m-d',strtotime($value['FlightDate']))) }} {{$value['Departure']}}- {{$value['Arrival']}}</div>
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
                                            <div class="flight-line-d"></div>
                                            <div class="flight-line-a">
                                                <b>Airline</b>
                                                <span>{{$airline->name}}</span>
                                            </div>

                                            <div class="flight-line-a" style="margin-left: 30px;">
                                            
                                                 
                                            <span>
                                               
                                                
                                                 <div class="flight-d-time-icon">
                                                
                                                    Time
                                                   <img alt="" src="/frontend/img/flight-d-time.png">{{ FlightHelper::calcTimeFormatted($value['date_time_depart'], $value['date_time_arrive'])  }}</div>
                                            </span>
                                            </div><br>

                                            
                                            <div class="flight-radio"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label> </div>
                                           
                                            <div class="flight-line-a">
                                                <b></b>
                                                <span style="color : #F44336!important;"> 
                                                    @if($value['Refundable']=='T') Refundable @else Non Refundable @endif
                                                </span>
                                            </div>
                                            <div class="flight-line-d"></div>
                                            <div class="flight-line-a">
                                                <b></b>
                                                <span><i class="fa fa-briefcase"></i> {{$value['FreeBaggage']}}</span>
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
                                                    {{$airline->name}}-{{$value['Airline']}} {{$value['AircraftType']}} Class-{{$value['FlightClassCode']}}<br>
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
                                                    <div class="flt-i-price">{{ \FlightHelper::DomesticTotalAmount($value) }} </div>
                                                    <div class="flt-i-price-b">{{$value['Currency']}} Total</div> 
                                                </div>
                                                <input type="hidden" name="FlightId" value="{{$value['FlightId']}}">
                                                <input type="hidden" name="nationality" 
                                                value="{{ $requestData['strnationality'] }}">
                                                @if($value['ReturnFlightId'])
                                                <input type="hidden" name="ReturnFlightId" value="{{$value['ReturnFlightId']}}">

                                                @endif
                                                @if(Auth::check())
                                                <button type="submit" class="cat-list-btn submit-button">
                                                    Book now
                                                </button>
                                                @else
                                                <button type="button" 
                                                class="cat-list-btn guestlogin" key='{{$key}}'>
                                                    Book now
                                                </button>
                                                @endif

                                                <a href="/flight/detail?FlightId={{$value['FlightId']}}&uuid={{ $uuid }}" class="cat-list-btn">
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
                            {{-- <div class="pagination">
                                <a class="active" href="#">1</a>
                                <a href="#">2</a>
                                <a href="#">3</a>
                                <div class="clear"></div>
                            </div> --}}
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
<script type="text/javascript">
  function makeBooking(ev){
    let submitbutton = $(ev).find(':submit');
    submitbutton.html(`<i class="fa fa-spinner fa-spin"></i>&nbsp;Booking`);
    submitbutton.prop("disabled",true);

  }


  $("#next_day").click(function(){




    let next_depart_date = `{{ date('d-m-Y', strtotime("+1 day", strtotime(Request::get('flight_date')) ) ) }}`;


    $('#depart_date').val(next_depart_date);



    $('#fight_search_form').submit();


  });

    $("#prev_day").click(function(){




    let next_depart_date = `{{ date('d-m-Y', strtotime("-1 day", strtotime(Request::get('flight_date')) ) ) }}`;


    $('#depart_date').val(next_depart_date);



    $('#fight_search_form').submit();


  });
</script>
@endsection
