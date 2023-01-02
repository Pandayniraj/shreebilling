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
                                            <b>Thank You. Your Ticket Has Been Booked.</b>
                                            <form method="POST" action="/booking/download/pdf/{{$reservation_id}}">
                                                @csrf
                                            <button  class="btn btn-primary btn-sm" type="submit">Download PDF</a>
                                            </form>

                                        </div>
                                    </div>
                                    @foreach($ticket_booking_data as $key=>$ticket_booking)
                                    <div class="complete-info">
                                        <h2>Your Personal Information</h2>
                                        <div class="complete-info-table">
                                            <div class="complete-info-i">
                                                <div class="complete-info-l">Ticket Number:</div>
                                                <div class="complete-info-r">{{$ticket_booking->ticket_no}}</div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="complete-info-i">
                                                <div class="complete-info-l">PNR No:</div>
                                                <div class="complete-info-r">{{$ticket_booking->pnr}}</div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="complete-info-i">
                                                <div class="complete-info-l">Contact Name:</div>
                                                <div class="complete-info-r">{{$ticket_booking->contact_name}}</div>
                                                <div class="clear"></div>
                                            </div>

                                            <div class="complete-info-i">
                                                <div class="complete-info-l">E-Mail Adress:</div>
                                                <div class="complete-info-r">{{$ticket_booking->email}}</div>
                                                <div class="clear"></div>
                                            </div>

                                            <div class="complete-info-i">
                                                <div class="complete-info-l">Phone Number:</div>
                                                <div class="complete-info-r">{{$ticket_booking->phoneNumber}}</div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="complete-info-i">
                                                <div class="complete-info-l">Paid By:</div>
                                                <div class="complete-info-r">{{ucwords($ticket_booking->reservation->paid_by)}}</div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="complete-info-i">
                                                <div class="complete-info-l">Payment Status:</div>
                                                <div class="complete-info-r">{{ucwords($ticket_booking->reservation->payment)}}</div>
                                                <div class="clear"></div>
                                            </div>
                                        </div>

                                        <div class="complete-devider"></div>

                                       
                                    </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>

                <div class="sp-page-r">

                    <div class="h-help">
                        <div class="h-help-lbl">Need {{ env("APP_COMPANY") }} Help?</div>
                        <div class="h-help-lbl-a">We would be happy to help you!</div>
                        <div class="h-help-phone">{{ env("APP_PHONE") }}</div>
                        <div class="h-help-email">{{ env("APP_EMAIL") }}</div>
                    </div>


                </div>
                <div class="clear"></div>
            </div>

        </div>
    </div>
</div>
<!-- /main-cont -->


@endsection
