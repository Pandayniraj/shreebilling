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
                <td colspan="16" style="text-align: right;">@if($months) Month: {{$months}}
                    @else
                    Date: {{ date('dS M y', strtotime($startdate)) }} - {{ date('dS M y', strtotime($enddate)) }}
                    @endif</td>
            </tr>
            <tr>
                <td style="text-align: left;">PAN:{{\Auth::user()->organization->tpid}}</td>
            </tr>
            <tr>
                <td style="text-align: left;">Materalized View</td>
            </tr>

            <tr>

                <th colspan="3" style="text-align: center; background-color: #eee">Invoice</th>
                <th colspan="12"></th>
                <th colspan="2" style="text-align: center;  background-color: #eee">Taxable Purchase</th>

            </tr>
            <tr>
                <th>#SN</th>
                <th>Fiscal Year</th>
                <th>Bill No.</th>
                <th>Customer Name</th>
                <th>Customer Pan</th>
                <th>Bill Date</th>
                <th>Bill Amount</th>
                <th>Discount Amount</th>
                <th>Taxable Amount</th>
                <th>Total Amount</th>
                <th>Sync With IRD</th>
                <th>Is Bill Printed</th>
                <th>Is Bill Active</th>
                <th>Print Time</th>
                <th>Entered By</th>
                <th>Printed By</th>
                <th>Is Real Time</th>

            </tr>
        </thead>
        <tbody>
            <?php  
    $n = 0;
                $tbill_amount = 0;
                $tdiscount_amount = 0;
                $ttax_amount = 0;
                $ttotal_amount = 0;
  ?>
            @if(isset($sales_print) && !empty($sales_print))
            @foreach($sales_print as $s)
            <tr>
                <td>#{{++$n}}</td>
                <td>{{$s->fiscal_year}}</td>
                <td style="white-space: nowrap;"><b>{{$s->outlet_code}}</b>{{$s->bill_no}}</td>
                <td>{{$s->customer_name}}</td>
                <td>{{$s->customer_pan}}</td>
                <td>{{$s->bill_date}}</td>
                <td>{{$s->amount}}</td>
                <td>{{$s->discount}}</td>
                <td>{{ $s->taxable_amount }}</td>
                <td>{{$s->total_amount }}</td>
                <td>{{$s->sync_with_ird }}</td>
                <td>{{$s->is_bill_printed}}</td>
                <td>{{$s->is_bill_active}}</td>
                <td>{{$s->printed_time}}</td>
                <td>{{$s->entered_by}}</td>
                <td>{{$s->printed_by}}</td>
                <td>{{$s->is_realtime}}</td>
            </tr>
            <?php 
                        $tbill_amount += $s->amount;
                        $tdiscount_amount += $s->discount;
                        $ttax_amount += $s->taxable_amount;
                        $ttotal_amount += $s->total_amount;
                    ?>
            @if(!$s->is_bill_active)
            <tr class="bg-danger">
                <td>#{{++$n}}</td>
                <td>{{$s->fiscal_year}}</td>
                <td>Ref {{$s->bill_no}}<b>{{$s->outlet_code}}</b></td>
                <td>{{$s->customer_name}}</td>
                <td>{{$s->customer_pan}}</td>
                <td>{{$s->bill_date}}</td>
                <td>-{{$s->amount}}</td>
                <td>-{{$s->discount}}</td>
                <td>-{{ $s->taxable_amount }}</td>
                <td>-{{$s->total_amount }}</td>
                <td>{{$s->sync_with_ird }}</td>
                <td>{{$s->is_bill_printed}}</td>
                <td>{{$s->is_bill_active}}</td>
                <td>{{$s->printed_time}}</td>
                <td>{{$s->entered_by}}</td>
                <td>{{$s->printed_by}}</td>
                <td>{{$s->is_realtime}}</td>
            </tr>
            <?php 
                        $tbill_amount -= $s->amount;
                        $tdiscount_amount -= $s->discount;
                        $ttax_amount -= $s->taxable_amount;
                        $ttotal_amount -= $s->total_amount;
                    ?>
            @endif

            @endforeach
            @endif
            <tr>
                <th colspan="4"></th>
                <th>Total</th>
                <th>:</th>
                <th>{{$tbill_amount}}</th>
                <th>{{$tdiscount_amount}}</th>
                <th>{{$ttax_amount}}</th>
                <th>{{$ttotal_amount}}</th>
                <th colspan="7"></th>
            </tr>

        </tbody>
    </table>


</body>
</html>
