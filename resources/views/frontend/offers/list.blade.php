@extends('frontend.layouts.app')
@section('content')

<!-- main-cont -->
<div class="main-cont">
    <div class="body-wrapper">
        <div class="wrapper-padding">
            
            <div class="two-colls">
               
                <div class="one-colls-right">
                    <div class="one-colls-right-b">
                        <div class="padding">

                          
                            <div class="catalog-row grid">
                                <!-- // -->
                                @foreach($offers as $offer)
                                <div class="offer-slider-i catalog-i tour-grid fly-in">
                                    <a href="/offers/{{$offer->id}}/{{$offer->offer_slug}}" class="offer-slider-img">
                                        <img alt="" src="/offers_images/{{$offer->offer_images}}" width="100%">
                                        <span class="offer-slider-overlay">
                                            <span class="offer-slider-btn">view details</span><span></span>
                                        </span>
                                    </a>
                                    <div class="offer-slider-txt">
                                        <div class="offer-slider-link"><a href="/offers/{{$offer->id}}/{{$offer->offer_slug}}" title="{{$offer->offer_title}}">{{ substr($offer->offer_title,0,25)}}</a></div>
                                        <div class="offer-slider-l">
                                            <div class="offer-slider-location">Duration / @if($offer->available_from && $offer->available_to)
                                                {{$offer->offer_days}} days <br>
                                                <small>{{ date('dS M Y',strtotime($offer->available_from))  }} to {{ date('dS M Y',strtotime($offer->available_to))  }}</small>
                                            @endif</div>
                                            <nav class="stars">
                                                <ul>
                                                    <li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
                                                    <li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
                                                    <li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
                                                    <li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
                                                    <li><a href="#"><img alt="" src="img/star-a.png" /></a></li>
                                                </ul>
                                                <div class="clear"></div>
                                            </nav>
                                        </div>
                                        <div class="offer-slider-r">
                                            <b>{{ env('APP_CURRENCY') }} {{$offer->offer_price}}</b>
                                            <span>Offer price</span>
                                        </div>
                                        <div class="offer-slider-devider"></div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <!-- \\ -->

                                @endforeach
                            </div>

                            <div class="clear"></div>


                            {!! $offers->links() !!}

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

<script type="text/javascript">




</script>


@endsection
