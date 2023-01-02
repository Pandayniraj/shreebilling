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

.shorttext{
   overflow: hidden;
   text-overflow: ellipsis;
   display: -webkit-box;
   -webkit-line-clamp: 2; /* number of lines to show */
   -webkit-box-orient: vertical;
}


</style>

<!-- main-cont -->
<div class="main-cont">
    <div class="body-wrapper">
        <div class="wrapper-padding">
            <div class="page-head">
               <div class="page-title">Offer - <span>{{$offer->offer_title}}</span></div>
                <div class="breadcrumbs">
                    <a href="#">Home</a> / <a href="#">Offer</a> / <span>{{$offer->offer_title}}</span>
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
                                            <img alt="" src="/offers_images/{{$offer->offer_images}}" width="100%">
                                        </div>
                                      
                                    </div>


                                </div>

                                <div class="content-tabs">
                                   
                                    <div class="content-tabs-body">
                                        <!-- // content-tabs-i // -->
                                        <div class="content-tabs-i">
                                            {!! $offer->description !!}
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
                    <div class="h-detail-lbl-a">{{$offer->offer_title}}</div>
                    <div class="h-detail-lbl-b">{{date('d M Y',strtotime($offer->available_from))}} - {{date('d M Y',strtotime($offer->available_to))}}</div>
                </div>
                <div class="h-tour">
                
                    <div class="h-liked-price" st>
                       {{ env('APP_CURRENCY') }} {{ $offer->offer_price }}
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="h-detail-stars">
                    
                    <div class="h-stars-lbl" style="text-transform: lowercase;">
                        <i class="fa  fa-envelope"></i>&nbsp; 
                        <a href='mailto:{{ $offer->email }}' style="color: #8999B2;">{{$offer->email}}</a>
                    </div>
                    <div class="h-stars-lbl" style="float: right;"><i class="fa fa-phone"></i>&nbsp;<a href='tel:{{ $offer->phone }}' style="color: #8999B2;">{{$offer->phone}}</a></div>
                    <div class="clear"></div>
                </div>



            </div>
            

            <div class="h-liked">
                <div class="h-liked-lbl">You May Also Like</div>
                <div class="h-liked-row">
                @foreach($more_offers as $key=>$mo)
                    <div class="h-liked-item">
                    <div class="h-liked-item-i">
                        <div class="h-liked-item-l">
                            <a  href="/offers/{{$mo->id}}/{{$mo->offer_slug}}"><img alt="{{$mo->offer_title}}" src="/offers_images/{{$mo->offer_images}}" style="height: 120px; width: 100%;"></a>
                        </div>
                    <div class="h-liked-item-c">
                        <div class="h-liked-item-cb">
                            <div class="h-liked-item-p">
                                <div class="h-liked-title shorttext" ><a href="#">{{$mo->offer_title}}</a></div>
                                <div class="h-liked-rating">
                                 <nav class="stars">
                                    <ul>
                                        <li><a href="#"><img alt="" src="img/star-b.png"></a></li>
                                        <li><a href="#"><img alt="" src="img/star-b.png"></a></li>
                                        <li><a href="#"><img alt="" src="img/star-b.png"></a></li>
                                        <li><a href="#"><img alt="" src="img/star-b.png"></a></li>
                                        <li><a href="#"><img alt="" src="img/star-a.png"></a></li>
                                    </ul>
                                    <div class="clear"></div>
                                 </nav>
                                </div>
                                <div class="h-liked-foot">
                                    <span class="h-liked-price">{{env('APP_CURRENCY')}} {{$mo->offer_price}}</span>
                                    <span class="h-liked-comment">{{$mo->offer_days}} Days</span>
                                </div>
                            </div>
                        </div>
                    <div class="clear"></div>
                    </div>
                    </div>
                    <div class="clear"></div>   
                    </div>
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


@endsection
