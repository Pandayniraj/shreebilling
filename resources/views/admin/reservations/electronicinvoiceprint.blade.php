<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ env('APP_COMPANY')}} | INVOICE</title>
    <style type="text/css">

        body { font-size: 13px; }
        @font-face {
            font-family: Arial;
        }

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

        @media print {
body {-webkit-print-color-adjust: exact;}
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
                        <div> PAN: {{ $organization->vat_id }}</div>
                        <div> FAX: {{ $organization->vat_id }}</div>
                    </div>
                </td>
            </tr>

        </table>
    </header>

    <table id="customers">
            <tr>
                <td width="40%" style="float:center">
                     {{env('APP_COMPANY')}}
                </td>
                 <td width="10%" style="float:text-center">
                   <strong> Invoice </strong>
                </td>
                <td width="40%" style="text-align: center">
                        <strong>Ref .No : {{env('SHORT_NAME')}}/{{$passengerdetail->issue_ticket_id}}</strong><br/>
                        <strong>Date : {{$passengerdetail->IssueDate}}</strong><br/>
                         <strong>{{date('l jS \of F Y h:i:s A')}}</strong>
                </td>
            </tr>
    </table>

     <table id="customers">
        <thead>
            <tr>
                <th>
                    Ticket No.
                </th>
                 <th>
                  Pax Name
                </th>
                <th>
                    Pax Type/Class Sector
                </th>
                <th>
                    Charge
                </th>
                <th>
                    Num of Pax (X) 
                </th>
                <th>
                    Per tkt
                </th>
                <th>
                    Amount
                </th>
                <th>
                    Curr
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$passengerdetail->TicketNo}}</td>
                <td>{{$passengerdetail->FirstName}} {{$passengerdetail->LastName}}</td>
                <td>{{$passengerdetail->PaxType}}  &nbsp;{{$passengerdetail->ClassCode}} &nbsp; {{$passengerdetail->Sector}}</td>
                <td>Ticket Fare(C)</td>
                <td>{{$passengerdetail->PaxNo}}</td>
                <td>{{$passengerdetail->Fare + $passengerdetail->Surcharge }}</td>
                <td>{{$passengerdetail->Fare + $passengerdetail->Surcharge }}</td>
                <td>{{$passengerdetail->Currency}}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5"></td>
                <td><strong>Total</strong></td>
                <td><strong>{{$passengerdetail->Fare + $passengerdetail->Surcharge }}</strong></td>
            </tr>
             <tr>
                <td colspan="5"></td>
                <td><strong>Tax</strong></td>
                <td><strong>{{$passengerdetail->Tax }}</strong></td>
            </tr>
             <tr>
                <td colspan="5"></td>
                <td><strong>Grand Total</strong></td>
                <td><strong>{{$passengerdetail->Fare + $passengerdetail->Surcharge + $passengerdetail->Tax }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <?php 

        $f= new NumberFormatter("en", NumberFormatter::SPELLOUT);
     ?>
    <p>In Words: {{ucfirst($f->format($passengerdetail->Fare + $passengerdetail->Surcharge + $passengerdetail->Tax))}} only.</p>
    
        <h4>Note : 22% will be charged if payment is not made within a 7 days</h4>
        <h4>This is a Computer Generated output doesn't require any signature.</h4>
   


        
</body>
</html>
<script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
<script>
    $(document).ready(function() {
        window.print();
    }); 
    
    </script> 