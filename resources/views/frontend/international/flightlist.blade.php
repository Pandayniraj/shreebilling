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
                  <span id='flightCounts'>{{count($individualFlight)}} results found.</span>
               </div>
               <div class="side-block fly-in">
                  <div class="side-block-search">
                     <div class="page-search-p">
                        <form method="get" action="/flights" onsubmit="return searchFlight(this)">
                           <input type="hidden" name="strnationality" value="NP">
                           <input type="hidden" name="strclientip" value="49.25.14.12">
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
                                 <div class="input-a"><input type="text" name="flight_date" value="{{Request::get('flight_date')}}" class="date-inpt-today" placeholder="mm/dd/yy" autocomplete="off" required> <span class="date-icon"></span></div>
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
                                                    <option value="{{ $nation->iso }}" @if($nation->iso== Request::get('strnationality') ) selected @endif>{{ $nation->nicename }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                           <div class="srch-tab-line no-margin-bottom">
                               <div class="select-wrapper">
                                            
                                 <input type="radio" name="return" id="gridRadios1" value="O" checked>
                                One Way
                                <input type="radio" name="return" id="gridRadios1" value="R">
                                Two Way
                                                  
                                </div>
                              <div class="clear"></div>
                           </div>
                           <div class="srch-tab-line no-margin-bottom">
                               <div class="select-wrapper">
                                <label>Refundable
                                            
                                 <input type="checkbox" name="is_refundable" @if(Request::get('is_refundable') == 'on') checked @endif>
                                </label>
                              <div class="clear"></div>
                           </div>
                         </div>
                           <!-- \\ -->
                           <button class="srch-btn" type="submit">Search</button>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <div class="two-colls-right">
               <div class="two-colls-right-b">
                  <div class="padding">
                    <form action="#">
                     <div class="catalog-head fly-in">
                        <label>Sort results by:</label>
                        
                        <div style="width:100px !important" class="search-select">
                           <select id='airlineName'>
                            <option value="">All Airlines</option>
                            @foreach($sortedFlight as $sf=>$fli)
                              <option value="{{ $sf }}">{{ FlightHelper::getAirlineName($sf,2)->name }}</option>
                            @endforeach
                           </select>
                        </div>
                       
                        
                        <div class="search-select">
                          <button type="button" name="Search" style="width: 100%;" id='filter'>Filter</button>
                        </div>
                        <div class="search-select">
                          <input type="button" name="Search" value="Reset" style="width: 100%;" id='resetfilter'>
                        </div>
                        <a href="#" class="show-list"></a>
                        <div class="clear"></div>
                     </div>
                   </form>
                     <div id='flightsearchlist'>
                      @include('frontend.partials.flight_searchlist_international')
                     </div>


                     <div class="clear"></div>
                    
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

var timer2 =  '{{ gmdate('i:s',$remainingtime) }}';

var interval = setInterval(function() {


var timer = timer2.split(':');
//by parsing integer, I avoid all extra string processing
var minutes = parseInt(timer[0], 10);
var seconds = parseInt(timer[1], 10);
--seconds;
minutes = (seconds < 0) ? --minutes : minutes;
if (minutes < 0) {
    
    swal({

            title: "Session Out",

            text: "",

            icon: "warning",

            button: "OK",

            timer: '10000',

        });

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

$('button#filter').click(function(){
  let airlineName = $('#airlineName').val();
  var parent = $(this);
  parent.html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');
  let uuid = `{{$uuid}}`;
  let data = {
    searchId: uuid,
    airline: airlineName
  }
  $.get('ajax/filterflight',data,function(response){
      
    $('#flightsearchlist').html(response.html);
    $('span#flightCounts').html(`${response.totalFlight} results found.`);
    parent.html("Filter");
  }).fail(function(){
     swal({

            title: "Failed To Filter",

            text: "Error",

            icon: "error",

            button: "OK",

            timer: '10000',

        });
    parent.html("Filter");
  });
  return false;
});
$('input#resetfilter').click(function(){
  $('#airlineName').val('');

});
function searchFlight(ev){

    let submitbutton = $(ev).find(':submit');
    submitbutton.html(`<i class="fa fa-spinner fa-spin"></i>&nbsp;Searching`);
    submitbutton.prop("disabled",true);
    
}
</script>

@endsection
