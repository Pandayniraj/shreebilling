@extends('frontend.layouts.app')
@section('content')

<!-- main-cont -->
<div class="main-cont">
    <div class="body-wrapper">
        <div class="wrapper-padding">
            <div class="page-head">
                <div class="page-title">Tours
                <div class="breadcrumbs">
                    <a href="#">Home</a> / <a href="#">Tours</a> 
                </div>
                <div class="clear"></div>
            </div>
            <div class="two-colls">
                <div class="two-colls-left">

                    <div class="srch-results-lbl fly-in">
                        <span>2,435 results found.</span>
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

                            

                            <div class="catalog-row grid">
                                <!-- // -->
                                @foreach($pages as $page)
                                <div class="offer-slider-i catalog-i tour-grid fly-in">
                                    <a href="/pages/{{$page->slug}}" class="offer-slider-img">
                                        <img alt="" src="/cmspages/{{$page->photo}}" width="100%">  
                                        <span class="offer-slider-overlay">
                                            <span class="offer-slider-btn">view details</span><span></span>
                                        </span>
                                    </a>
                                    <div class="offer-slider-txt">
                                        <div class="offer-slider-link"><a href="/pages/{{$page->slug}}" title="{{$page->title}}">{{ substr($page->title,0,25)}}</a></div>
                                        <div class="offer-slider-l">
                                            <div class="offer-slider-location">Author: {{$page->user->first_name}} {{$page->user->last_name}} </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="clear"></div>


                            {!! $pages->links() !!}

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


@endsection
