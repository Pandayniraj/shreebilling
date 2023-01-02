@extends('frontend.layouts.app')
@section('content')
<?php 
$total_person = $detail['Adult'] + $detail['Child'] + $detail['Infant'];

$airline = \App\Models\Airline::where('digit_code_3',$detail['Airline'])->first();
 ?>



<!-- main-cont -->
<div class="main-cont">
    <div class="body-wrapper">
        <div class="wrapper-padding">
            <div class="page-head">
                <div class="page-title" style="text-indent: 20px;">Air Ticket - <span>booking</span></div>
                <div class="breadcrumbs">
                    <a href="#">Home</a> / <a href="#">Flights</a> / <span>Flight booking</span>
                </div>
                <div class="clear"></div>
            </div>

            <form method="post" id="orderform" action="/international/bookingticket-{{$flightkey}}" enctype="multipart/form-data" onsubmit="return submitForm(this)">

                {{ @csrf_field() }}

                <input type="hidden" name="FlightId" value="{{$reservation['FlightId']}}">

                <input type="hidden" name="searchId" value="{{ $uuid }}">

                <input type="hidden" name="flightkey" value="{{ $flightkey }}">

                <input type="hidden" name="flightNum" value="{{ $detail['FlightNo']}}" >

                @if($reservation['ReturnFlightId'])
                <input type="hidden" name="ReturnFlightId" value="{{$array['ReturnFlightId']}}">
                @endif


                <div class="sp-page">
                    <div class="sp-page-a">
                        <div class="sp-page-l">
                            <div class="sp-page-lb">
                                <div class="sp-page-p">
                                    <div class="booking-left">

                {{-- card forms --}}

                        @include('frontend.partials.bookingform')

                                     

            {{-- card forms --}}
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
                                <a href="#"><img alt="" src="/airline/{{$airlineData->thumbnail}}" width="100%"></a>
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
                                    <span class="chk-r">{{$detail['Currency']}} {{number_format(($detail['AdultFare']))}}</span>
                                    <div class="clear"></div>
                                </div>
                                <div class="chk-line">
                                    <span class="chk-l">Chlid Price</span>
                                    <span class="chk-r">{{$detail['Currency']}} {{number_format( ($detail['ChildFare'] ))}}</span>
                                    <div class="clear"></div>
                                </div>

                                 <div class="chk-line">
                                    <span class="chk-l">Infant Price</span>
                                    <span class="chk-r">{{$detail['Currency']}} {{number_format( ($detail['InfantFare'] ))}}</span>
                                    <div class="clear"></div>
                                </div>

                            </div>
                            <div class="chk-total">
                                <div class="chk-total-l">Total Price</div>
                                <div class="chk-total-r">{{$detail['Currency']}} {{number_format($total_amount)}} <input type="hidden" name="total_amount" value="{{$total_amount}}"></div>
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
                <input type="hidden" name="submit_option" value="">
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
     function submitForm(ev){
        let  option = $(ev).find("*[type=submit]:focus" ).val();
        $('input[name="submit_option"]').val(option)
        let submitbutton = $(ev).find(':submit');
        submitbutton.html(`<i class="fa fa-spinner fa-spin"></i>&nbsp;Booking`);
        submitbutton.prop("disabled",true);

    }



    function manage_toogle_input(parent , class_name, bool){
        
        let el = parent.find(class_name);

        el.prop('required',bool);

        el.val('');

        return;

    }




  $('.toogle-passport-citizen').change(function(){

    let val = $(this).val();
    
    var pos = $(this).attr('data-position');

    var  parent = $(this).parent().parent().parent().parent();

        
    $(`#Passport${pos}`).hide();
    $(`#Citizenship${pos}`).hide();
    $(`#BirthCertificate${pos}`).hide();
    $(`#MinorId${pos}`).hide();
    

    manage_toogle_input(parent,'input.passport',false);
    manage_toogle_input(parent,'input.citizenship',false);
    manage_toogle_input(parent,'input.birth_certificate',false);
    manage_toogle_input(parent,'input.minor_id',false);
    manage_toogle_input(parent,'input.passport-expiry',false);


    if(val == 'citizenship'){

      $(`#Citizenship${pos}`).show();

      manage_toogle_input(parent,'input.citizenship',true);

    }else if(val == 'birth'){

     $(`#BirthCertificate${pos}`).show();

     manage_toogle_input(parent,'input.birth_certificate',true);
 
    }else if(val == 'minorcard'){
      
      $(`#MinorId${pos}`).show();

      manage_toogle_input(parent,'input.minor_id',true);

    }else{
       
       $(`#Passport${pos}`).show();

       manage_toogle_input(parent,'input.passport',true);

       manage_toogle_input(parent,'input.passport-expiry',true);
    }

  });

function validatePassportMonth(dateString){


    let today = new Date("{{date('m-d-Y')}}");
    let dateStringArr= dateString.split("-");
    let birthDate = new Date(`${dateStringArr[1]}/${dateStringArr[0]}/${dateStringArr[2]}`);
    let age = today.getFullYear() - birthDate.getFullYear();
    let m = today.getMonth() - birthDate.getMonth();
    age = age * 12 + m;
    age = - age;
    if(age < 6){

        return false;

    }

    return true;
}



function getAgeYear(dateString) {
    let today = new Date("{{date('m-d-Y')}}");
    let dateStringArr= dateString.split("-");

    let birthDate = new Date(`${dateStringArr[1]}/${dateStringArr[0]}/${dateStringArr[2]}`);
    let age = today.getFullYear() - birthDate.getFullYear();
    let m = today.getMonth() - birthDate.getMonth();
    
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        
        age--;
    
    }
    
    if(age > 12){

        return false;
    }

    return true;
}

function getAge(dateString) {

    let today = new Date("{{date('m-d-Y')}}");
    let dateStringArr= dateString.split("-");
    let birthDate = new Date(`${dateStringArr[1]}/${dateStringArr[0]}/${dateStringArr[2]}`);
    let age = today.getFullYear() - birthDate.getFullYear();
    let m = today.getMonth() - birthDate.getMonth();
    age = age * 12 + m;
    console.log(age);
    if(age > 23){

        return false;
    }


    return true;
}

$(`input[name='INFdob[]'`).change(function(){

    if(!getAge($(this).val())){
        swal({

            title: "Error",

            text: "Age cannot be greater than 23 Months",

            icon: "error",

            button: "OK",

            timer: '10000',

        });

        $(this).val('');
    }

});


$(`input[name='CNNdob[]'`).change(function(){

    if(!getAgeYear($(this).val())){
        swal({

            title: "Error",

            text: "Child Age cannot be greater than 12 Years",

            icon: "error",

            button: "OK",

            timer: '10000',

        });

        $(this).val('');
    }

});



$(`input.passport-expiry`).change(function(){

    if(!validatePassportMonth($(this).val())){
        swal({

            title: "Error",

            text: "Passport Cannot be less than 6 months",

            icon: "error",

            button: "OK",

            timer: '10000',

        });

        $(this).val('');
    }

});







</script>

@endsection
