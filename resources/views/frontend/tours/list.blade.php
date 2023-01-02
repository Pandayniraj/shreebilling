@extends('frontend.layouts.app')
@section('content')

<!-- main-cont -->
<div class="main-cont">
    <div class="body-wrapper">
        <div class="wrapper-padding">
            
            <div class="two-colls">
                <div class="two-colls-left">

                    <div class="srch-results-lbl fly-in">
                        <span>{{count($tours)}} results found.</span>
                    </div>

                    <div class="side-block fly-in">
                        <div class="side-block-search">
                            <div class="page-search-p">
                                <!-- // -->
                                <div class="srch-tab-line">
                                    <div class="srch-tab-left">
                                        <label>country</label>
                                        <div class="input-a"><input type="text" value="" placeholder="example: france"></div>
                                    </div>
                                    <div class="srch-tab-right">
                                        <label>city</label>
                                        <div class="input-a"><input type="text" value="" placeholder="Vienna"></div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <!-- \\ -->

                                <!-- // -->
                                <div class="srch-tab-line">
                                    <div class="srch-tab-left">
                                        <label>Departure</label>
                                        <div class="input-a"><input type="text" value="" class="date-inpt" placeholder="mm/dd/yy"> <span class="date-icon"></span></div>
                                    </div>
                                    <div class="srch-tab-right">
                                        <label>Return</label>
                                        <div class="input-a"><input type="text" value="" class="date-inpt" placeholder="mm/dd/yy"> <span class="date-icon"></span></div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <!-- \\ -->
                                <div class="srch-tab-line no-margin-bottom">
                                    <div class="srch-tab-left transformed">
                                        <label>Hotel stars</label>
                                        <div class="select-wrapper">
                                            <select class="custom-select">
                                                <option>--</option>
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="srch-tab-right transformed">
                                        <label>peoples</label>
                                        <div class="select-wrapper">
                                            <select class="custom-select">
                                                <option>--</option>
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <!-- \\ -->

                                <button class="srch-btn">Search</button>
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
                                    <select name="tour_type" id="tour_type">
                                        <option value="">Select Type</option>
                                        @foreach($tourtypes as $tt)
                                           <option value="{{$tt->id}}" @if(\Request::get("tour_type") == $tt->id) selected @endif>{{$tt->sett_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="search-select">
                                    <select name="price_type" id="price_type">
                                        <option value="">Select Price</option>
                                        <option value="asc" @if(\Request::get("price_type") == 'asc') selected @endif>Low To high</option>
                                        <option value="desc" @if(\Request::get("price_type") == 'desc') selected @endif>Hight To low</option>
                                    </select>
                                </div>
                                <div><button class="btn btn-danger btn-sm" id="clear">Clear</button></div>
                               
                                <a href="#" class="show-list"></a>
                                <a class="show-thumbs chosen" href="#"></a>
                                <div class="clear"></div>
                            </div>

                            <div class="catalog-row grid">
                                <!-- // -->
                                @foreach($tours as $tour)
                                <div class="offer-slider-i catalog-i tour-grid fly-in">
                                    <a href="/tours/{{$tour->id}}/{{$tour->tour_slug}}" class="offer-slider-img">
                                        <img alt="" src="/tours-img/{{$tour->thumbnail_image}}" width="100%">
                                        <span class="offer-slider-overlay">
                                            <span class="offer-slider-btn">view details</span><span></span>
                                        </span>
                                    </a>
                                    <div class="offer-slider-txt">
                                        <div class="offer-slider-link"><a href="/tours/{{$tour->id}}/{{$tour->tour_slug}}" title="{{$tour->tour_title}}">{{ substr($tour->tour_title,0,25)}}</a></div>
                                        <div class="offer-slider-l">
                                            <div class="offer-slider-location">Duration / @if($tour->tour_days){{$tour->tour_days}} days @endif</div>
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
                                            <b>{{ env('APP_CURRENCY') }} {{$tour->tour_basic_price}}</b>
                                            <span>tour price</span>
                                        </div>
                                        <div class="offer-slider-devider"></div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <!-- \\ -->

                                @endforeach
                            </div>

                            <div class="clear"></div>


                            {!! $tours->links() !!}

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

    $('#price_type,#tour_type').change(function(){
           var price_type = $('#price_type').val();
           var tour_type = $('#tour_type').val();
           window.location.href = "{!! url('/') !!}/tours?price_type=" + price_type + "&tour_type=" + tour_type; 
    });

    $('#clear').click(function(){
       window.location.href = "{!! url('/') !!}/tours"; 

    });



</script>


@endsection
