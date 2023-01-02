<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ env('APP_COMPANY')}} | INVOICE</title>
    <style type="text/css">
        @font-face {
            font-family: Arial;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            position: relative;
            width: 18cm;
            height: 24.7cm;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
        }

        table th,
        table td {
            padding: 3px;
            background: ;
            text-align: left;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: left;
        }

        table td h3 {
            color: #349eeb;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .no {
            color: #FFFFFF;
            font-size: 1em;
            background: #349eeb;
        }

        table .desc {
            text-align: left;
        }

        table .unit {
            background: #DDDDDD;
        }

        table .qty {}

        table .total {
            background: #349eeb;
            color: #FFFFFF;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table tbody tr:last-child td {
            border: none;
        }

        table tfoot td {
            padding: 5px 10px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1em;
            white-space: nowrap;
            border-top: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr:last-child td {
            color: #349eeb;
            font-size: 1em;
            border-top: 1px solid #349eeb;
            font-weight: bold;

        }

        table tfoot tr td:first-child {
            border: none;
        }

        #thanks {
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices {
            padding-left: 6px;
            border-left: 6px solid #349eeb;
        }

        #notices .notice {
            font-size: 1.2em;
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

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

    </style>
</head>
<body>
    <header class="clearfix">
        <table>
            <tr>
                <td width="50%" style="float:left">
                    <div id="logo">
                        <img style="max-width: 150px" src="{{env('APP_URL')}}{{ '/org/'.$organization->logo }}">
                    </div>
                </td>
                <td width="50%" style="text-align: right">
                    <div id="company">
                        <h4 class="name">{{ env('APP_COMPANY') }} </h4>
                        <div>{{ env('APP_ADDRESS1') }}</div>
                        <div>{{ env('APP_ADDRESS2') }}</div>
                        <div>Seller's PAN: {{ \Auth::user()->organization->tpid }}</div>
                        <div><a href="mailto:{{ env('APP_EMAIL') }}">{{ env('APP_EMAIL') }}</a></div>
                    </div>
                </td>
            </tr>

        </table>

        </div>
    </header>
    <main>
        </TD>
        </TR>
        </table>
        <!-- /.row -->
        <p>Thank you for choosing {{ env('APP_COMPANY') }}</p>
        <h3>Booking Details</h3>
        <table cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td>Reservation No:</td>
                    <td>{{$tour_booking->id}}</td>
                </tr>
               
                <tr>
                    <td>Contact Name:</td>
                    <td>{{$tour_booking->first_name}} {{$tour_booking->last_name}}</td>
                </tr>
                <tr>
                    <td>E-Mail Adress:</td>
                    <td>{{$tour_booking->email}}</td>
                </tr>
                <tr>
                    <td>Phone Number:</td>
                    <td>{{ucwords($tour_booking->contact_no)}}</td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td>{{$tour_booking->address}}</td>
                </tr>
                <tr>
                    <td>Payment Status:</td>
                    <td>{{ucwords($tour_booking->payment)}}</td>
                </tr>
                <tr>
                    <td>Paid By:</td>
                    <td>{{ucwords($tour_booking->paid_by)}}</td>
                </tr>

            </tbody>
        </table>
        <p>Contact {{ env('APP_COMPANY') }} For further Information.</p>

    </main>
    <footer>
        {{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} was created on MEROCRM.
    </footer>
</body>
</html>
