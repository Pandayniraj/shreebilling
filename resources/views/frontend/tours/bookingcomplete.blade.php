@extends('frontend.layouts.app')
@section('content')
<!-- main-cont -->
<div class="main-cont">
    <div class="body-wrapper">
        <div class="wrapper-padding">
            <div class="page-head">
                <div class="page-title">Flights - <span>booking complete</span></div>
                <div class="breadcrumbs">
                    <a href="#">Home</a> / <a href="#">Flights</a> / <span>Flights booking</span>
                </div>
                <div class="clear"></div>
            </div>

            <div class="sp-page">
                <div class="sp-page-a">
                    <div class="sp-page-l">
                        <div class="sp-page-lb">
                            <div class="sp-page-p">
                                <div class="booking-left">
                                    <h2>Booking Complete</h2>

                                    <div class="comlete-alert">
                                        <div class="comlete-alert-a">
                                            <b>Thank You. Your Ticket Has Been Booked.</b>@if($booking_id)<a href="/tourbooking/download/pdf/{{$booking_id}}" class="btn btn-primary btn-sm">Download PDF</a>@endif
                                        </div>
                                    </div>

                                    <div class="complete-info">
                                        <h2>Your Personal Information</h2>
                                        <div class="complete-info-table">
                                            <div class="complete-info-i">
                                                <div class="complete-info-l">Reservation Number:</div>
                                                <div class="complete-info-r">{{$tour_booking->id}}</div>
                                                <div class="clear"></div>
                                            </div>
                                           
                                            <div class="complete-info-i">
                                                <div class="complete-info-l">Contact Name:</div>
                                                <div class="complete-info-r">{{$tour_booking->first_name}} {{$tour_booking->last_name}}</div>
                                                <div class="clear"></div>
                                            </div>

                                            <div class="complete-info-i">
                                                <div class="complete-info-l">E-Mail Adress:</div>
                                                <div class="complete-info-r">{{$tour_booking->email}}</div>
                                                <div class="clear"></div>
                                            </div>

                                            <div class="complete-info-i">
                                                <div class="complete-info-l">Phone Number:</div>
                                                <div class="complete-info-r">{{$tour_booking->contact_no}}</div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="complete-info-i">
                                                <div class="complete-info-l">Paid By:</div>
                                                <div class="complete-info-r">{{ucwords($tour_booking->paid_by)}}</div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="complete-info-i">
                                                <div class="complete-info-l">Payment Status:</div>
                                                <div class="complete-info-r">{{ucwords($tour_booking->payment)}}</div>
                                                <div class="clear"></div>
                                            </div>
                                        </div>

                                        <div class="complete-devider"></div>

                                       
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>

                <div class="sp-page-r">

                    <div class="h-help">
                        <div class="h-help-lbl">Need Sparrow Help?</div>
                        <div class="h-help-lbl-a">We would be happy to help you!</div>
                        <div class="h-help-phone">2-800-256-124 23</div>
                        <div class="h-help-email">sparrow@mail.com</div>
                    </div>


                </div>
                <div class="clear"></div>
            </div>

        </div>
    </div>
</div>
<!-- /main-cont -->


@endsection
