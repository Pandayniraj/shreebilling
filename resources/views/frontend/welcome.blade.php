@extends('frontend.layouts.app')
@section('content')

<style>
    .offer-slider-lbl {
        padding-top: 50px !important;
    }

    #booking {
        font-family: 'PT Sans', sans-serif;
        background-position: center;
    }

    .booking-form {
        background: #203162;   
        padding: 6px;
        border-radius: 6px;
    }

    .booking-form .form-group {
        position: relative;
        margin-bottom: 15px;
    }

    .booking-form .form-control {
        background-color: #fff;
        height: 30px;
        color: #191a1e;
        border: none;
        font-size: 15px;
        font-weight: 350;


        padding: 0px 20px;
    }

    .booking-form .form-control::-webkit-input-placeholder {
        color: rgba(82, 82, 84, 0.4);
    }

    .booking-form .form-control:-ms-input-placeholder {
        color: rgba(82, 82, 84, 0.4);
    }

    .booking-form .form-control::placeholder {
        color: rgba(82, 82, 84, 0.4);
    }

    .booking-form input[type="date"].form-control:invalid {
        color: rgba(82, 82, 84, 0.4);
    }

    .booking-form select.form-control {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .booking-form select.form-control+.select-arrow {
        position: absolute;
        right: 10px;
        bottom: 6px;
        width: 32px;
        line-height: 32px;
        height: 32px;
        text-align: center;
        pointer-events: none;
        color: rgba(0, 0, 0, 0.3);
        font-size: 14px;
    }

    .booking-form select.form-control+.select-arrow:after {
        content: '\279C';
        display: block;
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg);
    }

    .booking-form .form-label {
        display: block;
        margin-left: 20px;
        margin-bottom: 5px;
        font-weight: 400;
        text-transform: uppercase;
        line-height: 24px;
        height: 24px;
        font-size: 12px;
        color: #fff;
    }

    .booking-form .form-checkbox input {
        position: absolute !important;
        margin-left: -9999px !important;
        visibility: hidden !important;
    }

    .booking-form .form-checkbox label {
        position: relative;
        padding-top: 4px;
        padding-left: 30px;
        font-weight: 400;
        color: #fff;
    }

    .booking-form .form-checkbox label+label {
        margin-left: 15px;
    }

    .booking-form .form-checkbox input+span {
        position: absolute;
        left: 2px;
        top: 4px;
        width: 20px;
        height: 20px;
        background: #fff;
        border-radius: 50%;
    }

    .booking-form .form-checkbox input+span:after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0px;
        height: 0px;
        border-radius: 50%;
        background-color: #f4921f;  
        -webkit-transition: 0.2s all;
        transition: 0.2s all;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    .booking-form .form-checkbox input:not(:checked)+span:after {  
        opacity: 0;
    }

    .booking-form .form-checkbox input:checked+span:after {
        opacity: 1;
        width: 10px;
        height: 10px;
    }

    .booking-form .form-btn {
        margin-top: 27px;
    }

    .booking-form .submit-btn {
        color: #fff;
        background-color: #1d1c1c;
        font-weight: 400;
        height: 35px;
        font-size: 12px;
        border: none;
        width: 100%;
        border-radius: 40px;
        text-transform: uppercase;
        -webkit-transition: 0.2s all;
        transition: 0.2s all;
    }

    .booking-form .submit-btn:hover,
    .booking-form .submit-btn:focus {
        opacity: 0.9;
    }
    .container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
    padding-top: 50px !important;
}

.imgLodBg {
    height: 200px;
    -moz-transform-style: preserve-3d;
    -webkit-transform-style: preserve-3d;
    transform-style: preserve-3d;
    -moz-box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
    -webkit-box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
    box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
    position: relative;
    overflow: hidden;
    cursor: pointer;
    min-height: 180px;
    border-radius: 0px;
    background-color: #2a2b2e;
}

 .airplane {
    position: absolute;
    top: -24px;
    display: block;
    background: url(/frontend/img/airplane-city.png) no-repeat center center;
    height: 94px;
    width: 110px;
    left: 65%;
    -webkit-animation: moveairplane 80s cubic-bezier(0.35, 0.35, 1, 1) infinite;
    -moz-animation: moveairplane 80s cubic-bezier(0.35, 0.35, 1, 1) infinite;
    -o-animation: moveairplane 80s cubic-bezier(0.35, 0.35, 1, 1) infinite;
}
.ui-autocomplete-row
          {
            padding:8px;
            background-color: red;
            border-bottom:1px solid #ccc;
            font-weight:bold;
          }
          .ui-autocomplete-row:hover
          {
            background-color: #ddd;
          }
</style>
<!-- main-cont -->
<div class="main-cont">

    <div class="inner-page" >
        <div class="content-wrapper">
            @include('frontend.partials.searchflight')  
        </div>
    </div>
    <span class="airplane"></span>
    <div class="mp-offesr no-margin">
        <div class="wrapper-padding-a">
            <div class="offer-slider duble-margin">
                <header class="fly-in page-lbl">
                    <div class="offer-slider-lbl">{{ date('yy')}} hottest offers at {{env('APP_COMPANY')}}</div>
                    <p>Holidays are here again! Discover inspiring destinations and safe travel tips.</p>
                </header>
                <div class="fly-in offer-slider-c">
                    <div id="offers-a" class="owl-slider">
                        <!-- // -->
                        @foreach($tours as $tour)
                        <div class="offer-slider-i">
                            <a class="offer-slider-img" href="/tours/{{$tour->id}}/{{$tour->tour_slug}}">
                                <img alt="" src="/tours-img/{{$tour->thumbnail_image}}"/>
                                <span class="offer-slider-overlay">
                                    <span class="offer-slider-btn">view details</span>
                                </span>
                            </a>
                            <div class="offer-slider-txt">
                                <div class="offer-slider-link"><a href="/tours/{{$tour->id}}/{{$tour->tour_slug}}">{{$tour->tour_title}}</a></div>
                                <div class="offer-slider-l">
                                    <div class="offer-slider-location">Location: {{$tour->location->location}} </div>
                                    <nav class="stars">
                                        <ul>
                                            <li><a href="#"><img alt="" src="/frontend/img/star-b.png" /></a></li>
                                            <li><a href="#"><img alt="" src="/frontend/img/star-b.png" /></a></li>
                                            <li><a href="#"><img alt="" src="/frontend/img/star-b.png" /></a></li>
                                            <li><a href="#"><img alt="" src="/frontend/img/star-b.png" /></a></li>
                                            <li><a href="#"><img alt="" src="/frontend/img/star-a.png" /></a></li>
                                        </ul>
                                        <div class="clear"></div>
                                    </nav>
                                </div>
                                <div class="offer-slider-r">
                                    <b>{{$tour->tour_basic_price}}$</b>
                                    <span>avg/night</span>
                                </div>

                                <div class="clear"></div>
                            </div>
                        </div>
                        <!-- \\ -->

                        @endforeach



                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
</div>

</div>
<!-- /main-cont -->
<script>
    $(function() {
      

        $(".sectors").autocomplete({
            source: '/getSectors'
            , minLength: 2,
            select: function(ui,item){
                console.log($(this).prev('input.locations'),item)
                $(this).prev('input.locations').val(item.item.value);
                $(this).val(item.item.display);
                ui.preventDefault();
              
            }
        });
    });

  

</script>

@endsection
