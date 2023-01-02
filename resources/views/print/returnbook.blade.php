<html>
<head>
    <title>Sales Book</title>
    <!-- block from searh engines -->
    <meta name="robots" content="noindex">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Set a meta reference to the CSRF token for use in AJAX request -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons 4.7.0 -->
    <link href="{{ asset("/bower_components/admin-lte/font-awesome/css/all.css") }}" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.1 -->
    <link href="{{ asset("/bower_components/admin-lte/ionicons/css/ionicons.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css" />

    <!-- Application CSS-->
    <link href="{{ asset(elixir('css/all.css')) }}" rel="stylesheet" type="text/css" />

    <style>
        table {
            width: 100%;
        }

        table,


        th,
        td {
            padding: 5px;
            text-align: left;
        }

        table#t01 tr:nth-child(even) {
            background-color: #eee;
        }

        table#t01 tr:nth-child(odd) {
            background-color: #fff;
        }

        table#t01 th {
            background-color: #696969;
            color: white;
        }


        .table>thead>tr>th {
            border-bottom: 1px solid #696969 !important;
        }

        .table>tbody>tr>th {
            border-top: 1px solid #696969 !important;
        }

        .table>tbody>tr>td {
            border-top: 1px solid #696969 !important;
        }

    </style>
</head>
<body onload="window.print();">
    <table style="width:100%; text-align:center; padding: 30px 0; box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); -webkit-box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px;">

        <thead>
            <tr>
                <td colspan="2" style="text-align: left;">Company:{{\Auth::user()->organization->organization_name}}</td>

            </tr>
            <tr>
                <td style="text-align: left;">Address:{{\Auth::user()->organization->address}}</td>
                <td colspan="13" style="text-align: right;">@if($months) Month: {{$months}}
                    @else
                    Date: {{ date('dS M y', strtotime($startdate)) }} - {{ date('dS M y', strtotime($enddate)) }}
                    @endif</td>
            </tr>
            <tr>
                <td style="text-align: left;">PAN:{{\Auth::user()->organization->tpid}}</td>
            </tr>
            <tr>
                <td style="text-align: left;">Return Book</td>
            </tr>


            <tr>
                <th>
                    SN
                </th>
                <th>Fiscal Year</th>
                <th>Bill Date</th>
                <th>Ref Bill No</th>
                <th>Cancel Date</th>
                <th>Credit Note No</th>
                <th>Cancel Reason</th>
                <th>Guest Name</th>
                <th>Guest PAN</th>
                <th>Total Sales</th>
                <th>Non Tax Sale</th>
                <th>Export Sale</th>

                <th>Taxable Amount</th>
                <th>TAX</th>

            </tr>
        </thead>
        <tbody>
            <?php  
   $n = 0;
                $pos_total_amount = 0;
                $pos_taxable_amount = 0;
                $pos_tax_amount = 0;
  ?>
            @foreach($invoice_print as $o)
            <tr>
                <td>{{++$n}}</td>
                <td>{{$o->fiscal_year}}</td>
                <td>{{$o->bill_date}}
                    <?php
                                $temp_date = explode(" ",$o->bill_date );
                                $temp_date1 = explode("-",$temp_date[0]);
                                $cal = new \App\Helpers\NepaliCalendar();
                                //nepali date
                                $a = $temp_date1[0];
                                $b = $temp_date1[1];
                                $c = $temp_date1[2];
                                $d = $cal->eng_to_nep($a,$b,$c);
                                 $nepali_date = $d['date'].' '.$d['nmonth'] .', '.$d['year'];
                                ?><br>
                    <small> {!! $nepali_date !!}</small></td>

                <td>{{env('SALES_BILL_PREFIX')}}{{$o->bill_no}}</td>
                <td>{{$o->cancel_date}}</td>
                <td>
                    CN {{$o->credit_note_no}}
                </td>
                <td>{{$o->void_reason}}</td>

                <td>@if($o->client_id){{$o->client->name}} @else {{$o->name}} @endif</td>


                <td>@if($o->client_id) {{$o->client->vat}} @else {{$o->customer_pan}} @endif</td>
                <td>{{ number_format($o->total_amount,2)}}</td>
                <td></td>
                <td></td>

                <td>{!! number_format($o->taxable_amount,2) !!}</td>
                <td>{!! number_format($o->tax_amount,2) !!}</td>
                <?php 
                                  $pos_taxable_amount = $pos_taxable_amount+$o->taxable_amount;
                                  $pos_total_amount = $pos_total_amount + $o->total_amount;
                                  $pos_tax_amount = $pos_tax_amount+$o->tax_amount; ?>
            </tr>
            @endforeach
            <tr>
                <td colspan="8">
                </td>
                <td>
                    Total Amount:
                </td>
                <td>{{env(APP_CURRENCY)}} {{ number_format($pos_total_amount,2) }}</td>
                <td colspan="2"></td>
                <td>{{env(APP_CURRENCY)}} {{ number_format($pos_taxable_amount,2) }}</td>
                <td>{{env(APP_CURRENCY)}} {{ number_format($pos_tax_amount,2) }}</td>
            </tr>

        </tbody>
    </table>


</body>
</html>
