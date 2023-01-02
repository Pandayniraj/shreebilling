@extends('frontend.layouts.app')
@section('content')

<style>
.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border: solid 1px #e7e7e7;
    width: 5100px;
}

.selectx {
    font-weight: bold;
    width: 100%;
 
    padding-top: 0em;
    padding-left: 10px !important;
    padding-bottom: .4em;
}


</style>

<!-- main-cont -->
<div class="main-cont">
    <div class="body-wrapper">
        <div class="wrapper-padding">
            <div class="page-head">
                <div class="page-title">Tours - <span>{{$tour->tour_title}}</span></div>
                <div class="breadcrumbs">
                    <a href="#">Home</a> / <a href="#">Tours</a> / <span>{{$tour->tour_title}}</span>
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
                                            <img alt="" src="/tours-img/{{$tour->thumbnail_image}}" width="100%">
                                        </div>
                                        <div class="tab-gallery-preview">
                                            <div id="gallery">
                                                @foreach($tour_images as $ti)
                                                <!-- // -->
                                                <div class="gallery-i">
                                                    <a href="/tours-img/{{$ti->timg_image}}"><img alt="/tours-img/{{$ti->timg_image}}" src="/tours-img/{{$ti->timg_image}}" width="100%" height="100px;" style="object-fit: cover;"><span></span></a>
                                                </div>
                                                <!-- \\ -->
                                                @endforeach                                                
                                            </div>
                                        </div>
                                    </div>
                                    <!-- \\ tab item \\ -->
                                    <!-- // tab item // -->
                                    <div class="tab-item">
                                        <div class="calendar-tab">
                                            <div class="calendar-tab-select">
                                                <label>Select month</label>
                                                <select class="custom-select">
                                                    <option>january 2015</option>
                                                    <option>january 2015</option>
                                                    <option>january 2015</option>
                                                </select>
                                            </div>


                                            
                                            <div class="tab-calendar-collsr">
                                                <div class="tab-calendar-s">

                                                    <div class="map-symbol passed">
                                                        <div class="map-symbol-l"></div>
                                                        <div class="map-symbol-r">Date past</div>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="map-symbol available">
                                                        <div class="map-symbol-l"></div>
                                                        <div class="map-symbol-r">available</div>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="map-symbol unavailable">
                                                        <div class="map-symbol-l"></div>
                                                        <div class="map-symbol-r">unavailable</div>
                                                        <div class="clear"></div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="clear"></div>

                                        </div>
                                    </div>
                                    <!-- \\ tab item \\ -->

                                </div>

                                <div class="content-tabs">
                                   
                                    <div class="content-tabs-body">
                                        <!-- // content-tabs-i // -->
                                        <div class="content-tabs-i">
                                            {!! $tour->tour_desc !!}
                                        </div>
                                           
                                    </div>
                                      
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
                            <div class="h-detail-lbl-a">{{$tour->tour_title}}</div>
                            <div class="h-detail-lbl-b">{{date('d M Y', strtotime($tour->created_at))}}</div>
                        </div>
                        <div class="h-tour">
                            <div class="tour-item-icons">
                                <img alt="" src="img/tour-icon-01.png">
                                <span class="tour-item-plus"><img alt="" src="img/tour-icon.png"></span>
                                <img alt="" src="img/tour-icon-02.png">
                            </div>
                          
                            <div class="tour-icon-person">{{$tour->tour_max_adults}} max-persons</div>
                            <div class="clear"></div>
                        </div>


                       
                        <form method="get" action="/admin/tours/book/{{$tour->tour_slug}}">

                           
                           <div class="search-tab-content hotels-tab">
                                <div class="page-search-p">
                                    <!-- // -->
                                 
                                    <div class="srch-tab-line">
                                        <div class="srch-tab">
                                            <label>Select Tour Date</label>
                                            <div class="input-a"><input name="tour_date" type="text" value="" class="date-inpt " placeholder="dd-mm-yy" required> <span class="date-icon"></span></div> 
                                        </div>
                                       
                                        <div class="clear"></div>
                                    </div>
                                   
                                </div>
                              <table class="table">
                                  <thead>
                                    <tr>
                                      <th scope="col" style="line-height: 1.428571;">Who</th>
                                      <th scope="col" style="line-height: 1.428571;">No</th>
                                      <th scope="col" style="line-height: 1.428571;">Price</th>
                                      
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <th scope="row" style="border: 1px solid #ddd;width: 30px;">Adults ({{env('APP_CURRENCY')}} {{$tour->tour_adult_price}})<div  id="adults_price" style="display: none;" >{{$tour->tour_adult_price}}</div></th>
                                      <td>
                                        <select style="min-width:50px" name="adults" class="selectx changeInfo input-sm" id="adults">
                                            <option value="1" selected="">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </td>
                                      <td class="text-center" id="adults_total">{{env('APP_CURRENCY')}} {{$tour->tour_adult_price}}</td>
                                    </tr>
                                    <tr>
                                      <th scope="row" style="border: 1px solid #ddd;">Childs ({{env('APP_CURRENCY')}} {{$tour->tour_child_price}})<div id="childs_price" style="display: none;">{{$tour->tour_child_price}}</div></th>
                                      <td>
                                        <select style="min-width:50px" name="childs" class="selectx changeInfo input-sm" id="childs">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                      </td>
                                      <td class="text-center" id="childs_total">{{env('APP_CURRENCY')}} 0</td>
                                    </tr>
                                    <tr>
                                      <th scope="row" style="border: 1px solid #ddd;">Infant ({{env('APP_CURRENCY')}} {{$tour->tour_infant_price}})<div id="infants_price" style="display: none;">{{$tour->tour_infant_price}}</div></th>
                                      <td>
                                        <select style="min-width:50px" name="infants" class="selectx changeInfo input-sm" id="infants">
                                             <option value="0" selected="">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </td>

                                      <td class="text-center" id="infants_total">{{env('APP_CURRENCY')}} 0</td>
                                     
                                    </tr>
                                  </tbody>
                                </table>
                             </div>
                        <br>
                        
                               
                              @if(Auth::check())
                                <button type="submit" class="cat-list-btn" style="background-color:#ed8e3b;color:#ffffff;">
                                    BOOK TOUR NOW
                                    <div class="clear"></div>
                                </button>
                                @else
                                <button type="button" class="cat-list-btn guestlogin" style="background-color:#ed8e3b;color:#ffffff;">
                                    BOOK TOUR NOW
                                    <div class="clear"></div>
                                </button>
                                @endif
                        </form>
                    </div>

                    <div class="h-liked">
                        <div class="h-liked-lbl">You May Also Like</div>
                        <div class="h-liked-row">

                            @foreach($tour_may_like as $tml)
                            <!-- // -->
                            <div class="h-liked-item">
                                <div class="h-liked-item-i">
                                    <div class="h-liked-item-l">
                                        <a href="/tours/{{$tml->id}}/{{$tml->tour_slug}}"><img alt="" src="/tours-img/{{$tml->thumbnail_image}}" width="100%"></a>
                                    </div>
                                    <div class="h-liked-item-c">
                                        <div class="h-liked-item-cb">
                                            <div class="h-liked-item-p">
                                                <div class="h-liked-title"><a href="/tours/{{$tml->id}}/{{$tml->tour_slug}}">{{$tml->tour_title}}</a></div>
                                                <div class="h-liked-rating">
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
                                                <div class="h-liked-foot">
                                                    <span class="h-liked-price">{{ env('APP_CURRENCY') }} {{$tml->tour_basic_price}}</span>
                                                    <span class="h-liked-comment">avg/night</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <!-- \\ -->
                            @endforeach


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


    $(function(){

         const currency = `{{env('APP_CURRENCY')}}`;

        $("#adults").on('change',function(){

           adults_no =  $(this).val();
           adults_price = $('#adults_price').html();

           if(adults_no){
            adults_total = currency +' '+ adults_no * adults_price;

            $('#adults_total').html(adults_total);

           }
        });
         $("#childs").on('change',function(){

           childs_no =  $(this).val();
           childs_price = $('#childs_price').html();

           if(childs_no){
              childs_total = currency +' '+ childs_no * childs_price;

              $('#childs_total').html(childs_total);
           }
        });

          $("#infants").on('change',function(){

           infants_no =  $(this).val();
           infants_price = $('#infants_price').html();

           if(infants_no){
            infants_total = currency +' '+ infants_no * infants_price;
            $('#infants_total').html(infants_total);

           }
        });
    });

    


</script>


@endsection
