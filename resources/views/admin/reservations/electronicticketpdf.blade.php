<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ env('APP_COMPANY')}} | INVOICE</title>
    <style type="text/css">
        @font-face {
            font-family: Arial;
        }
        body { font-size: 14px; }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        #customers {
          border-collapse: collapse;
          width: 100%;
          margin-bottom: 20px;
        }

        #customers td, #customers th {
          padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
          padding-top: 12px;
          padding-bottom: 12px;
          text-align: left;
          background-color: #7a7878;
          color: white;
        }

        footer {
            color: #777777;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #AAAAAA;
            padding: 8px 0;
            text-align: center;
        }

      

    </style>
</head>
<body>
    <header class="clearfix">
        <table id="customers">
            <tr>
                <td width="50%" style="float:left">
                    <div id="logo">
                        <img style="max-width: 150px" src="{{env('APP_URL')}}{{ '/org/'.$organization->logo }}">
                    </div>
                </td>
                <td width="50%" style="text-align: right">
                    <div id="company">
                        <h4 class="name">{{ $organization->organization_name }} </h4>
                        <div>{{ $organization->address }}</div>
                        <div>Tel: {{ $organization->phone }}</div>
                        <div>E-mail: <a href="mailto:{{$organization->email }}">{{$organization->email}}</a></div>
                        <div>Seller's PAN: {{ $organization->vat_id }}</div>
                    </div>
                </td>
            </tr>

        </table>
    </header>
        <h3>Electronic Ticket</h3>
        <h3>Passenger Information</h3>
        <table cellspacing="0" cellpadding="0" id="customers">
            <thead>
                <tr>
                    <th class="no">Passenger Information</th>
                    <th class="no">Passport Number</th>
                    <th class="no">Frequent Flyer Number</th>
                    <th class="no">Ticket</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$passengerdetail->LastName}}/{{$passengerdetail->FirstName}} {{$passengerdetail->Title}}</td>
                    <td>06831412</td>
                    <td></td>
                    <td>{{$passengerdetail->TicketNo}}</td>
                </tr>
            </tbody>
        </table>

        <table cellspacing="0" cellpadding="0" id="customers">
            <thead>
                <tr>
                    <th class="no">Airline PNR</th>
                   
                    <th class="no">Date Of Issue</th>
                </tr>
            </thead>
            <tbody>
                @php 

                        $airline = \FlightHelper::getAirlineName($passengerdetail->Airline,strlen($passengerdetail->Airline));

                 @endphp
                <tr>
                    <td>{{$passengerdetail->PnrNo}} ({{$passengerdetail->Airline}}-{{$airline->name}})</td>
                    <td>{{$passengerdetail->IssueDate}}</td>
                </tr>
            </tbody>
        </table>



        <h3>Itinerary Information</h3>

        <table cellspacing="0" cellpadding="0" id="customers">
            <thead>
                <tr>
                    <th class="no">Flight #</th>
                    <th class="no">From</th>
                    <th class="no">To</th>
                    <th class="no">Depart</th>
                    <th class="no">Arrive</th>
                    <th class="no">Seat</th>
                    <th class="no">Info</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$airline->name}} ({{$passengerdetail->Airline}} {{$passengerdetail->FlightNo}})</td>

                    <td>{{$passengerdetail->Departure}}-{{\FlightHelper::getAirportName($passengerdetail->Departure)}}</td>
                    <td>{{$passengerdetail->Arrival}}-{{\FlightHelper::getAirportName($passengerdetail->Arrival)}}</td>
                    <td>
                        {{$passengerdetail->FlightDate}} {{$passengerdetail->FlightTime}} 
                    </td>
                     <td>
                         {{$passengerdetail->FlightDate}} {{$passengerdetail->ArrivalTime}} 
                    </td>
                    <td></td>

                    <?php 
                        $t1 = strtotime($passengerdetail->FlightTime);

                       $t2 = strtotime($passengerdetail->ArrivalTime);

                       $diff = gmdate('H:i', $t2 - $t1);
                     ?>
                    <td>
                        Baggage : {{$passengerdetail->FreeBaggage}}<br/>
                        Class : {{$passengerdetail->ClassCode}}<br/>
                        Duration : {{$diff}}<br/>
                        Status : Confirmed<br/>
                        Aircraft : Boeing 787-8 Dreamkiner<br/>
                        Special SVC:<br/>
                        Fare Basis: NKR4R1RIO
                    </td>
                </tr>
               
            </tbody>
        </table>


        
</body>
</html>
