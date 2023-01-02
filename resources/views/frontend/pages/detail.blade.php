@extends('frontend.layouts.app')
@section('content')

<!-- main-cont -->
<div class="main-cont">
    <div class="body-wrapper">
        <div class="wrapper-padding">
            <div class="page-head">
                <div class="page-title"> <h1>{{$page->title}}</h1></div>
                <div class="breadcrumbs">
                    <a href="#">Home</a> / <a href="#">Pages</a> / <span>{{$page->title}}</span>
                </div>
                <div class="clear"></div>
            </div>

            <div class="sp-page">


                <div class="sp-page-a">
                    <div class="sp-page-l">
                        <div class="sp-page-lb">
                            <div class="sp-page-p">
                               

                                <div class="mm-tabs-wrapper">
                                    <!-- // tab item // -->
                                    <div class="tab-item">
                                        <div class="tab-gallery-big">
                                            <img alt="" src="/cmspages/{{$page->photo}}" width="100%">
                                        </div>
                                       
                                    </div>
                                    <!-- \\ tab item \\ -->


                                </div>

                                <div class="content-tabs">
                                    
                                    <div class="content-tabs-body">
                                        <!-- // content-tabs-i // -->
                                        <div class="content-tabs-i">
                                          
                                            <p>{{$page->excerpt}}</p>
                                            {!! $page->body !!}
                                        </div>
                                        <!-- \\ content-tabs-i \\ -->
                                        <!-- // content-tabs-i // -->
                                      
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="clear"></div>
                    </div>
                </div>

                <div class="sp-page-r">
                    <div class="h-detail-r">
                        <div class="h-detail-lbl">
                           
                        </div>

                       
                        
                    </div>


                </div>
                <div class="clear"></div>
            </div>

        </div>
    </div>
</div>
<!-- /main-cont -->
<script>
    $(document).ready(function() {
        'use strict';
        $('.review-ranger').each(function() {
            var $this = $(this);
            var $index = $(this).index();
            if ($index == '0') {
                var $val = '3.0'
            } else if ($index == '1') {
                var $val = '3.8'
            } else if ($index == '2') {
                var $val = '2.8'
            } else if ($index == '3') {
                var $val = '4.8'
            } else if ($index == '4') {
                var $val = '4.3'
            } else if ($index == '5') {
                var $val = '5.0'
            }
            $this.find('.slider-range-min').slider({
                range: "min"
                , step: 0.1
                , value: $val
                , min: 0.1
                , max: 5.1
                , create: function(event, ui) {
                    $this.find('.ui-slider-handle').append('<span class="range-holder"><i></i></span>');
                }
                , slide: function(event, ui) {
                    $this.find(".range-holder i").text(ui.value);
                }
            });
            $this.find(".range-holder i").text($val);
        });

        $('#reasons-slider').bxSlider({
            infiniteLoop: true
            , speed: 500
            , mode: 'fade'
            , minSlides: 1
            , maxSlides: 1
            , moveSlides: 1
            , auto: true
            , slideMargin: 0
        });

        $('#gallery').bxSlider({
            infiniteLoop: true
            , speed: 500
            , slideWidth: 108
            , minSlides: 1
            , maxSlides: 6
            , moveSlides: 1
            , auto: false
            , slideMargin: 7
        });
    });

</script>


@endsection
