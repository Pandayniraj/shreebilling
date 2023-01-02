@extends('frontend.layouts.app')
@section('content')
<?php 
$total_person = $detail['Adult'] + $detail['Child'] + $detail['Infant'];

$formData->adult_no  = $detail['Adult'];
$formData->clild_no =  $detail['Child'];
$formData->infant_no =  $detail['Infant'];
$passangersArr = [ 'ADT'=>$formData->adult_no,'CNN'=>$formData->clild_no,'INF'=>$formData->infant_no ];
 
$airline = \App\Models\Airline::where('digit_code_3',$detail['Airline'])->first();
 ?>



<!-- main-cont -->
<div class="main-cont">
    <div class="body-wrapper">
        <div class="wrapper-padding">
            <div class="page-head">
                <div class="page-title">Air Ticket - <span>booking</span></div>
                <div class="breadcrumbs">
                    <a href="#">Home</a> / <a href="#">Flights</a> / <span>Flight booking</span>
                </div>
                <div class="clear"></div>
            </div>
        
            @auth

            <form method="post" id="orderform" action="/bookingticket" >

            @else
                <form method="post" id="orderform" action="/guestbookingticket" >


            @endauth

                {{ @csrf_field() }}

                <input type="hidden" name="FlightId" value="{{$reservation['FlightId']}}">

                @if($reservation['ReturnFlightId'])
                <input type="hidden" name="ReturnFlightId" value="{{$array['ReturnFlightId']}}">
                @endif


                <div class="sp-page">
                    <div class="sp-page-a">
                        <div class="sp-page-l">
                            <div class="sp-page-lb">
                                <div class="sp-page-p">
                                    <div class="booking-left">

                                        @include('frontend.partials.bookingform')

                                    </div>

                                    {{-- <div class="booking-devider no-margin"></div>
                                        <h2>Pay With Connect IPS</h2>
                                    <img src="/frontend/img/5.png" width="100px;"> --}}


                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>

                <div class="sp-page-r">

                    <div class="checkout-coll">
                        <div class="checkout-head">
                            <div class="checkout-headl">
                                <a href="#"><img alt="" src="/airline/{{$airline->thumbnail}}" width="100%"></a>
                            </div>
                            <div class="checkout-headr">
                                <div class="checkout-headrb">
                                    <div class="checkout-headrp">
                                        <div class="chk-left">
                                            <div class="chk-lbl"><a href="#">{{$detail['Departure']}} - {{$detail['Arrival']}}</a></div>
                                            <div class="chk-lbl-a">@if($reservation['ReturnFlightId']) TWOWAY FLIGHT @else ONEWAY FLIGHT @endif</div>
                                            <div class="chk-logo">
                                                <img alt="" src="img/lufthansa.png">
                                            </div>

                                        </div>
                                        <div class="chk-right">
                                            <a href="#"><img alt="" src="img/chk-edit.png"></a>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="chk-lines">
                            <div class="chk-line chk-fligth-info">
                                <div class="chk-departure">
                                    <span>Departure</span>
                                    <b>{{$detail['DepartureTime']}}<br>{{$detail['FlightDate']}}</b>
                                </div>
                                <div class="chk-fligth-devider"></div>
                                <div class="chk-fligth-time"><img alt="" src="img/icon-nights.png"></div>
                                <div class="chk-fligth-devider"></div>
                                <div class="chk-arrival">
                                    <span>Arrival</span>
                                    <b>{{$detail['ArrivalTime']}}<br>{{$detail['FlightDate']}}</b>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="chk-details">
                            <h2>Details</h2>
                            <div class="chk-detais-row">
                                <div class="chk-line">
                                    <span class="chk-l">AIRLINE:</span>
                                    <span class="chk-r">{{$airline->name}}-{{$detail['Airline']}}</span>
                                    <div class="clear"></div>
                                </div>
                                <div class="chk-line">
                                    <span class="chk-l">FLIGHT CLASS:</span>
                                    <span class="chk-r">{{$detail['FlightClassCode']}}</span>
                                    <div class="clear"></div>
                                </div>
                                <div class="chk-line">
                                    <span class="chk-l">Adult Price</span>
                                    <span class="chk-r">{{$detail['Currency']}} {{\FlightHelper::TotalAdultAmount($detail)}}</span>
                                    <div class="clear"></div>
                                </div>
                                <div class="chk-line">
                                    <span class="chk-l">Chlid Price</span>
                                    <span class="chk-r">{{$detail['Currency']}} {{\FlightHelper::TotalChildAmount($detail)}}</span>
                                    <div class="clear"></div>
                                </div>

                            </div>
                            <div class="chk-total">
                                <div class="chk-total-l">Total Price</div>
                                <div class="chk-total-r">{{$detail['Currency']}} {{$total_amount}}

                                    <input type="hidden" name="uuid" value="{{ $uuid }}">
                                </div>
                                <div class="clear"></div>
                            </div>
                            <input type="hidden" name="currency" value="{{$detail['Currency']}}">
                        </div>

                    </div>

                    <div class="h-help">
                        <div class="h-help-lbl">Need {{ env('APP_COMPANY') }} Help?</div>
                        <div class="h-help-lbl-a">We would be happy to help you!</div>
                        <div class="h-help-phone">{{ env('APP_COMPANY') }}</div>
                        <div class="h-help-email">{{ env('APP_EMAIL') }}</div>
                    </div>

                </div>
                <div class="clear"></div>
        </div>


        <!-- The Modal -->
        <div class="modal" id="booking_modals">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">

              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">Check Your Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              <!-- Modal body -->
              <div class="modal-body">
               
                

                <div class="card">
                  <div class="card-header bg-primary text-white">Passenger Name</div>
                  <div class="card-body" style="margin: 0; padding: 0;">
                    
                        <table class="table">
                          <thead>
                            @foreach($passangersArr as $kv=>$pas)
                                @for($pos = 0; $pos < $pas ;$pos++ )

                            <tr>
                              <td class="pax_name"></td>
                            </tr>
                            @endfor
                            @endforeach
                          </thead>
                        </table>

                  </div>
                </div>

                <br>
                <div class="card ">
                  <div class="card-header  bg-danger text-white">Flight Info</div>
                  <div class="card-body" style="margin: 0; padding: 0;">
                    
                        <table class="table">
                        
                            <tr>
                              <th>Sector</th>
                              <td >{{ $detail['Departure'] }} to {{ $detail['Arrival'] }}</td>
                            </tr>
                            <tr>
                              <th>Time</th>
                            <td>

                            <div class="row">

                                <div class="col-sm-6">{{$detail['FlightDate']}}<br>
                                    <small>{{ $detail['DepartureTime'] }}</small></div>
                                <div class="col-sm-6">
                                    {{$detail['FlightDate']}} <br>
                                    <small>{{ $detail['ArrivalTime'] }}</small>
                                </div>
                            </div>
                     
                            </td>
                            </tr>
                         
                        </table>
                  </div>
                </div>


                <p>
                  <input type="checkbox" id='agree_checkbox' style="zoom: 1.5;margin-top: 10px;">
                 <span style="font-size: smaller;padding: 10px;"> I Agree With The Rules And Restrictions, Privacy Policy, Visa Rules And Terms And Conditions Of {{ env('APP_COMPANY')}}
                 </span>

                </p>
           
<div id='form_submit_part' style="display: none;"> 
    <hr>
    <button type="submit" class="btn btn-success">Continue Booking <i class="fa  fa-forward"></i> </button>
               {{--  <div class="row">
                    
                    <div class="col-sm-12">
                    @auth
                    <button type="submit" name="submit_option" 
                    class="btn btn-success form-submit-button form-control" value="wallet" style="display: inline-block !important;background-color: #218838;color: white;" >COMPLETE BOOKING WITH WALLET</button>

                    @endauth
                    </div>
                  

                </div>
                @auth
                <div style="text-align: center;font-weight: 800; "> OR </div>@endauth


        <div class="row">
            <div class="col-md-3">
        <div class="card" style="padding: 30px;">
            <a href="javascript::void()"  value="ipsconnect" class="submitButton">
            <img class="card-img-bottom" src="https://login.connectips.com/resources/custom/login/main-logo.png" alt="Card image" style="width:100px;height: 100px;" >
            </a>
          </div>
        </div>

         <div class="col-md-5">
        <div class="card" style="padding: 30px;">
           <a href="javascript::void()" value="hbl" class="submitButton">
            <img class="card-img-bottom" src="/frontend/img/cardpayment.png" alt="Card image" style="width:250px;height: 100px;" >
            </a>
          </div>
        </div>

        </div> --}}
<input type="submit" name="submit_option" id='formSubmit' style="visibility: hidden;">
<input type="hidden" name="paid_by" value="ipsconnect">


        </div>
              </div>

              <!-- Modal footer -->
              <div class="modal-footer" id='form_edit_part'>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Edit</button>
              </div>

            </div>
          </div>
        </div>





        </form>

    </div>
</div>
</div>
<!-- /main-cont -->


<script type="text/javascript">




    $(document).on('change', '#paid_by', function() {

        var paid_by = $(this).val();


        // console.log(paid_by);
        if (paid_by == 'hbl') {
            $('#orderform').attr('action', '/bookingticket/hbl');
            $('button.choice-payment-button').text("COMPLETE BOOKING WITH HBL");
        } else {
            $('#orderform').attr('action', '/bookingticket');
            $('button.choice-payment-button').text("COMPLETE BOOKING WITH IPS");
        }
    });

    $(document).on('click','.submitButton',function(){
        
        let paid_by = $(this).attr('value');

        $('#paid_by').val(paid_by);
        
        if (paid_by == 'hbl') {
            $('#orderform').attr('action', '/bookingticket/hbl');
            $('button.choice-payment-button').text("COMPLETE BOOKING WITH HBL");
        } else {
            $('#orderform').attr('action', '/bookingticket');
            $('button.choice-payment-button').text("COMPLETE BOOKING WITH IPS");
        }


        $('#formSubmit').trigger('click');



    });

    var timer2 = '{{ gmdate('i: s ',$remainingtime) }}';

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

            location.href = `/`;
            return 0;
        }
        seconds = (seconds < 0) ? 59 : seconds;
        seconds = (seconds < 10) ? '0' + seconds : seconds;
        //minutes = (minutes < 10) ?  minutes : minutes;
        $('time.countdown').text(minutes + ':' + seconds);

        timer2 = minutes + ':' + seconds;

    }, 1000);

  $('.toogle-passport-citizen').change(function(){

    let val = $(this).val();
    var pos = $(this).attr('data-position');
    $(`#Passport${pos}`).hide();
    $(`#Citizenship${pos}`).hide();
    $(`#BirthCertificate${pos}`).hide();
    $(`#MinorId${pos}`).hide();
    if(val == 'citizenship'){
      
      $(`#Citizenship${pos}`).show();

    }else if(val == 'birth'){

     $(`#BirthCertificate${pos}`).show();
 
    }else if(val == 'minorcard'){
      $(`#MinorId${pos}`).show();
      
    }else{
       $(`#Passport${pos}`).show();
    }

  });




 
</script>
<script type="text/javascript">
  function open_bookingModal(){

    var first_name = [];
    $('.first_name').each(function(){

        first_name.push($(this).val());


    });
    
    var last_name = [];

    $('.sur_name').each(function(){

        last_name.push($(this).val());


    });

    var index = 0;


    console.log(first_name,last_name);



    $('#booking_modals').modal('show');



    $('.pax_name').each(function(){
        if(first_name[index] && last_name[index] ){

            $(this).text( first_name[index] + ' '+ last_name[index]  );

            index ++;

        }
        
    });

  }

  $(document).on('change','#agree_checkbox',function(){

    let isChecked = $(this).is(':checked');


    if(isChecked){
        
        $('#form_submit_part').show(300);
        $('#form_edit_part').hide();
    }else{

        $('#form_submit_part').hide(300);
        $('#form_edit_part').show();
    }  

    return false; 

  });
</script>


@endsection
