@extends('frontend.layouts.app')
@section('content')

@php 
  
     $adult_no  = \Request::get('adults');
     $child_no = \Request::get('childs');
     $infant_no  = \Request::get('infants');

     $total = $adult_no + $child_no + $infant_no; 
  
 @endphp

<div class="main-cont">
    <div class="body-wrapper">
        <div class="wrapper-padding">
            <div class="page-head">
                <div class="page-title">Tour - <span>Booking</span></div>
                <div class="breadcrumbs">
                    <a href="#">Home</a> / <a href="#">Tours</a> / <span>{{$tour->tour_title}}</span>
                </div>
                <div class="clear"></div>
            </div>

            <form method="post" id="orderform" action="/reservationonly">
                {{ @csrf_field() }}

                <input type="hidden" name="tourId" value="{{$tour->id}}">


                <div class="sp-page">
                    <div class="sp-page-a">
                        <div class="sp-page-l">
                            <div class="sp-page-lb">
                                <div class="sp-page-p">
                                    <div class="booking-left">   
                                        @include('frontend.partials.tourbookingform')
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
                                <a href="#"><img alt="" src="/tours-img/{{$tour->thumbnail_image}}" width="100%" style="object-fit: cover;"></a>
                            </div>
                            <div class="checkout-headr">
                                <div class="checkout-headrb">
                                    <div class="checkout-headrp">
                                        <div class="chk-left">
                                            <div class="chk-lbl"><a href="#">{{$tour->tour_title}}</a></div>
                                            <div class="chk-lbl-a"><i class="fa fa-location-arrow"></i> {{$tour->location->location}}</div>
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
                                    <span>Days</span>
                                    <b>{{$tour->tour_days}}</b>
                                </div>
                               
                                <div class="chk-arrival">
                                    <span>Nights</span>
                                    <b>{{$tour->tour_nights}}</b>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="chk-details">
                            <h2>Details</h2>
                            <div class="chk-detais-row">
                                <div class="chk-line">
                                    <span class="chk-l">Adults ({{$adult_no}}):</span>
                                    <span class="chk-r">{{env('APP_CURRENCY')}} <span id="adult_amount">{{$tour->tour_adult_price * $adult_no}}</span></span>
                                    <div class="clear"></div>
                                </div>
                                <div class="chk-line">
                                    <span class="chk-l">Child ({{$child_no}}):</span>
                                    <span class="chk-r">{{env('APP_CURRENCY')}} <span id="child_amount">{{$tour->tour_child_price * $child_no}}</span></span>
                                    <div class="clear"></div> 
                                </div>
                                <div class="chk-line">
                                    <span class="chk-l">Infant ({{$infant_no}})</span>
                                    <span class="chk-r">{{env('APP_CURRENCY')}} <span id="infant_amount">{{$tour->tour_infant_price * $infant_no}}</span></span>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div id="__extras">
                            </div>


                            <div class="chk-total">
                                <div class="chk-total-l">Total Price</div>
                                <div class="chk-total-r">{{env('APP_CURRENCY')}} <span id="total-amount">{{\FlightHelper::TourTotalAmount($tour->id,$adult_no,$child_no,$infant_no)}}</span>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>

                    </div>

                    <div class="h-help">
                        <div class="h-help-lbl">Need Sparrow Help?</div>
                        <div class="h-help-lbl-a">We would be happy to help you!</div>
                        <div class="h-help-phone">2-800-256-124 23</div>
                        <div class="h-help-email">sparrow@mail.com</div>
                    </div>

                </div>
                <div class="clear"></div>
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
            $('#orderform').attr('action', '/tourreservation/hbl');
            $('button.booking-complete-btn').text("COMPLETE BOOKING WITH HBL");
        } else if(paid_by == 'ipsconnect') {
            $('#orderform').attr('action', '/tourreservation');
            $('button.booking-complete-btn').text("COMPLETE BOOKING WITH IPS");
        }else{
            $('#orderform').attr('action', '/reservationonly');
            $('button.booking-complete-btn').text("COMPLETE Reservation Only");
        }

    });

   $('input:checkbox').change(function() {
    // this will contain a reference to the checkbox   
        div=  $(this).parent().parent();

        if (this.checked) {
          
          let toAppendObj = {
                items: div.find('.items').text(),
                price: div.find('.price').text(),
                id : div.find('.this_id').val(),
          }
          let html = `<div class="chk-total" id='item-${toAppendObj.id}'>
                                <div class="chk-total-l">${toAppendObj.items}</div>
                                <div class="chk-total-r">{{env('APP_CURRENCY')}} <span class="price extra">${toAppendObj.price}</span>
                                </div>
                                <div class="clear"></div>
                            </div>`;
            $('#__extras').after(html);

            calctotal();

        } else {

            let id = div.find('.this_id').val();
            $('#item-'+id).remove();

            calctotal();
        }
  });

   function calctotal(){

    adult_price = Number($('#adult_amount').text());
    child_price  = Number ($('#child_amount').text());
    infant_amount = Number ($('#infant_amount').text());
    var extraPrice = 0;

    $('.price.extra').each(function(){
        extraPrice += Number($(this).text());

    }); 

    total_price = Number(adult_price  + child_price + infant_amount + extraPrice);

    $('#total-amount').text(total_price);


   
   }

</script>

@endsection