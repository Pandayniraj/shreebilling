<?php $_MODE= Config::get('sabre.env');  ?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsi="http://www.w3.org/2020/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2020/XMLSchema">
	<SOAP-ENV:Header>
		<m:MessageHeader xmlns:m="http://www.ebxml.org/namespaces/messageHeader">
			<m:From>
				<m:PartyId type="urn:x12.org:IO5:01">99999</m:PartyId>
			</m:From>
			<m:To>
				<m:PartyId type="urn:x12.org:IO5:01">123123</m:PartyId>
			</m:To>
			<m:CPAId>{{Config::get("sabre.{$_MODE}.group") }}</m:CPAId>
			<m:ConversationId>abc123</m:ConversationId>
			<m:Service m:type="OTA">Air Shopping Service</m:Service>
			<m:Action>{{ $requestData->Action }}</m:Action>
			<m:MessageData>
				<m:MessageId>mid:20001209-133003-2333@clientofsabre.com</m:MessageId>
				<m:Timestamp>2020-02-15T11:15:12Z</m:Timestamp>
				<m:TimeToLive>2020-02-15T11:15:12Z</m:TimeToLive>
			</m:MessageData>
			<m:DuplicateElimination/>
			<m:Description>Bargain Finder Max Service</m:Description>
		</m:MessageHeader>
		<wsse:Security xmlns:wsse="http://schemas.xmlsoap.org/ws/2002/12/secext">
			<wsse:BinarySecurityToken valueType="String" EncodingType="wsse:Base64Binary">{{$token}}</wsse:BinarySecurityToken>
		</wsse:Security>
	</SOAP-ENV:Header>
<SOAP-ENV:Body>

	@yield('content')

</SOAP-ENV:Body>
</SOAP-ENV:Envelope>