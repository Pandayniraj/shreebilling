<html><body onload="document.redirectform.submit()">
<form id="HBLPayloadForm" method="POST" action="https://hblpgw.2c2p.com/HBLPGW/Payment/Payment/Payment" name="redirectform">
    <input type="hidden" id="paymentGatewayID" name="paymentGatewayID" value="{{$paymentGatewayID}}">
    <input type="hidden" id="invoiceNo" name="invoiceNo" value="{{$invoiceNo}}">
    <input type="hidden" id="productDesc" name="productDesc" value="{{$productDesc}}">
    <input type="hidden" id="amount" name="amount" value="{{$amount}}">
    <input type="hidden" id="currencyCode" name="currencyCode" value="{{$currencyCode}}">
    <input type="hidden" id="nonSecure" name="nonSecure" value="{{ $nonSecure }}"/>
    <input type="hidden" id="hashValue" name="hashValue" value="{{ $hashValue }}">
    <input type="hidden" id="userDefined1" name="userDefined1" value="{{ $userDefined1 }}"/>
</form>
</body>
</html>



