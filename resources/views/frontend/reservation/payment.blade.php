@extends('frontend.layouts.app')
@section('content')

<!-- main-cont -->
<div class="main-cont" style="padding:20px;text-align:center">
    <div class="">
        <div class="mp-slider search-only">

        </div>

        <h4 class="text-center">Redirecting to Paymet page please wait....</h4>

        <form id="HBLPayloadForm" method="POST" action="https://uat.connectips.com:7443/connectipswebgw/loginpage">
            <input type="hidden" id="MERCHANTID" name="MERCHANTID" value="{{$data['MERCHANTID']}}">
            <input type="hidden" id="APPID" name="APPID" value={{$data['APPID']}}>
            <input type="hidden" id="APPNAME" name="APPNAME" value="{{$data['APPNAME']}}">
            <input type="hidden" id="TXNID" name="TXNID" value="{{$data['TXNID']}}">
            <input type="hidden" id="TXNDATE" name="TXNDATE" value="{{$data['TXNDATE']}}">
            <input type="hidden" id="TXNCRNCY" name="TXNCRNCY" value="{{$data['TXNCRNCY']}}">
            <input type="hidden" id="TXNAMT" name="TXNAMT" value="{{$data['TXNAMT']}}">
            <input type="hidden" id="REFERENCEID" name="REFERENCEID" value="{{$data['REFERENCEID']}}">
            <input type="hidden" id="REMARKS" name="REMARKS" value="{{$data['REMARKS']}}">
            <input type="hidden" id="PARTICULARS" name="PARTICULARS" value="{{$data['PARTICULARS']}}">
            <input type="hidden" id="TOKEN" name="TOKEN" value="{{$data['TOKEN']}}">
            <div class="text-center">
                <small>If you aren't redirected automatically. Please click the button</small><br><br>
                <input class="btn btn-primary " type="submit" id="process-pay" value="Go to Payment Page">
            </div>
        </form>
    </div>
</div>
@endsection
