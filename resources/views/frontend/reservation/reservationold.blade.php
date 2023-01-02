@extends('frontend.layouts.app')
@section('content')
<?php 
$total_person = $detail['Adult'] + $detail['Child'] + $detail['Infant'];
dd($detail['Adult']);
$formData->Adult  = $detail['Adult'];
$formData->Child =  $detail['Child'];
$formData->Infant =  $detail['Infant'];

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

            <form method="post" id="orderform" action="/bookingticket">
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
                                        <h2>Passenger Information</h2>
                                        @for($i=0;$i < $total_person; ++$i) <div class="booking-form ">
                                            <div class="booking-form-i">
                                                <div class="card-expiration">
                                                    <label>Pax Type</label>
                                                    <select class="custom-select" name="pax_type[]">
                                                        <option value="">Select</option>
                                                        <option value="ADULT">Adult</option>
                                                        <option value="CHILD">Child</option>
                                                    </select>
                                                </div>
                                                <div class="card-expiration">
                                                    <label>Male/Female</label>
                                                    <select class="custom-select" name="gender[]">
                                                        <option value="">Select</option>
                                                        <option value="M">Male</option>
                                                        <option value="F">Female</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="booking-form-i">
                                                <div class="card-expiration">
                                                    <label>Country:</label>
                                                    <select type="text" class="custom-select" value="" name="nationality[]">
                                                        <option value="">Select</option>
                                                        <option value="NP">Nepal</option>
                                                    </select>
                                                </div>
                                                <div class="card-expiration">
                                                    <label>Title</label>
                                                    <select class="custom-select" name="title[]">
                                                        <option value="">Select</option>
                                                        <option value="MR">Mr</option>
                                                        <option value="MRS">Mrs</option>
                                                    </select>
                                                </div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="booking-form-i">
                                                <label>First Name:</label>
                                                <div class="input"><input type="text" value="" name="first_name[]" placeholder="First Name" /></div>
                                            </div>
                                            <div class="booking-form-i">
                                                <label>Last Name:</label>
                                                <div class="input"><input type="text" value="" name="last_name[]" placeholder="Last Name" /></div>
                                            </div>

                                            <div class="bookin-three-coll">
                                                <div class="booking-form-i">
                                                    <label>Remarks:</label>
                                                    <div class="input"><input type="text" name="remarks[]" value="" placeholder="Remarks" /></div>
                                                </div>

                                            </div>
                                            <div class="clear"></div>
                                            <div class="booking-devider"></div>
                                    </div>
                                    @endfor


                                    <h2>Customer Information</h2>

                                    <div class="booking-form">
                                        <div class="booking-form-i">
                                            <label>Conatct Name:</label>
                                            <div class="input"><input type="text" name="contact_name" value="" required></div>
                                        </div>

                                        <div class="booking-form-i">
                                            <label>Email Adress:</label>
                                            <div class="input"><input type="text" name="email_address" value="" required></div>
                                        </div>
                                        <div class="booking-form-i">
                                            <label>Contact No:</label>
                                            <div class="input"><input type="text" name="contact_no" value="" required></div>
                                        </div>
                                        <div class="booking-form-i">
                                            <div class="card-expiration">
                                                <label>Paid By</label>
                                                <select class="custom-select" id="paid_by" name="paid_by" required>
                                                    <option value="ipsconnect">IPSConnet</option>
                                                    <option value="hbl">HBL</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>

                                    <div class="booking-devider no-margin"></div>
                                    <h2>Pay With Connect IPS</h2>
                                    <img src="/frontend/img/5.jpg" width="100px;">

                                  
                                    <div class="booking-complete">
                                        <h2>Review and book your trip</h2>
                                        <p>Voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui voluptatem sequi nesciunt. </p>
                                        <button class="booking-complete-btn">COMPLETE BOOKING</button>
                                    </div>

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
                                    <span class="chk-r">{{$detail['Currency']}} {{number_format($detail['Adult'] *($detail['AdultFare'] + $detail['Tax']+ $detail['FuelSurcharge']),2)}}</span>
                                    <div class="clear"></div>
                                </div>
                                <div class="chk-line">
                                    <span class="chk-l">Chlid Price</span>
                                    <span class="chk-r">{{$detail['Currency']}} {{number_format($detail['Child'] * ($detail['ChildFare'] + $detail['FuelSurcharge'] + $detail['Tax'] + $detail['ChildTaxAdjustment']),2)}}</span>
                                    <div class="clear"></div>
                                </div>

                            </div>
                            <div class="chk-total">
                                <div class="chk-total-l">Total Price</div>
                                <div class="chk-total-r">{{$detail['Currency']}} {{number_format($total_amount,2)}} <input type="hidden" name="total_amount" value="{{$total_amount}}"></div>
                                <div class="clear"></div>
                            </div>
                            <input type="hidden" name="currency" value="{{$detail['Currency']}}">
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
            $('#orderform').attr('action', '/bookingticket/hbl');
        } else {
            $('#orderform').attr('action', '/bookingticket');
        }
    });

</script>

@endsection
