@extends('frontend.layouts.app')
@section('content')
<style>

</style>
<!-- main-cont -->
<div class="main-cont">
    <div class="contacts-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7062.689370507026!2d85.31527627472008!3d27.737512681117277!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb1927068ba057%3A0xea02da97848c5ec5!2sPragatinagar%2C%20Kathmandu%2044600!5e0!3m2!1sen!2snp!4v1599979692298!5m2!1sen!2snp" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>

    <div class="contacts-page-holder">
        <div class="contacts-page">
            <header class="page-lbl">
                <div class="offer-slider-lbl">GET IN TOUCH WITH US</div>
                <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit</p>
            </header>

            <div class="contacts-colls">
                <div class="contacts-colls-l">
                    <div class="contact-colls-lbl">OUR OFFICE</div>  
                    <div class="contacts-colls-txt">
                        <p>Address:  {{$organization->address}}</p>
                        <p>Telephones: {{$organization->phone}}</p>  
                        <p>E-mail: {{$organization->email}}</p>
                     
                        <div class="side-social">
                            <a class="side-social-twitter" href="@if(env('TWITTER')){{env('TWITTER')}}@else#@endif"></a>
                            <a class="side-social-facebook" href="@if(env('FACEBOOK')){{env('FACEBOOK')}}@else#@endif"></a>
                            <a class="side-social-vimeo" href="@if(env('VIMO')){{env('VIMO')}}@else#@endif"></a>
                            <a class="side-social-pinterest" href="@if(env('PINTEREST')){{env('PINTEREST')}}@else#@endif"></a>
                            <a class="side-social-instagram" href="@if(env('INSTAGRAM')){{env('INSTAGRAM')}}@else#@endif"></a>
                        </div>
                    </div>
                </div>
                <div class="contacts-colls-r">
                    <div class="contacts-colls-rb">
                        <div class="contact-colls-lbl">Contact Us</div>
                        <div class="booking-form">
                            <form method="post" action="/contact">
                                <div class="booking-form-i">
                                    <label>Full Name:</label>
                                    <div class="input"><input type="text" name="full_name" value="{{old('full_name')}}" required/></div>
                                </div>
                                <div class="booking-form-i">
                                    <label>Phone:</label>
                                    <div class="input"><input type="text" name="phone" value="{{old('phone')}}" required/></div>
                                </div>
                                <div class="booking-form-i">
                                    <label>Email Adress:</label>
                                    <div class="input"><input type="email" name="email" value="{{old('email')}}" required style="border: 0px;width: 100%;font-size: 14px;font-family: 'Raleway"/></div>
                                </div>
                                <div class="booking-form-i">
                                    <label>Guidence:</label>
                                    <div class="input"><input type="text" name="guidence" value="{{old('guidence')}}" required/></div>
                                </div>
                                <div class="booking-form-i textarea">
                                    <label>Message:</label>
                                    <div class="textarea-wrapper">
                                        <textarea name="message" required>{{old('message')}}</textarea>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                {{@csrf_field()}}
                                <button class="contacts-send" type="submit">Send message</button>
                            </form>

                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>

</div>
<!-- /main-cont -->

@endsection
