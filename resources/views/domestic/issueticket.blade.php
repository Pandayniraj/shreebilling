<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:book="http://booking.us.org/">
   <soapenv:Header/>
   <soapenv:Body>
      <book:IssueTicket>
         <strFlightId>{{$params->strFlightId}}</strFlightId>
         <strReturnFlightId>{{$params->strReturnFlightId}}</strReturnFlightId>
         <strContactName>{{$params->strContactName}}</strContactName>
         <strContactEmail>{{$params->strContactEmail}}</strContactEmail>
         <strContactMobile>{{$params->strContactMobile}}</strContactMobile>
         <strPassengerDetail>
         {!! $params->strPassengerDetail !!}
         </strPassengerDetail>
      </book:IssueTicket>
   </soapenv:Body>
</soapenv:Envelope>