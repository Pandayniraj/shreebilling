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
<body>
    <table style="width:100%; text-align:center; padding: 30px 0; box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); -webkit-box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px;">

        <thead>
            <tr>
                <td colspan="2" style="text-align: left;">Company:{{\Auth::user()->organization->organization_name}}</td>
                <td colspan="8" style="text-align: right;">Fiscal Year: {{$fiscal_year}}</td>
            </tr>
            <tr>
                <td style="text-align: left;">Address:{{\Auth::user()->organization->address}}</td>
                <td colspan="9" style="text-align: right;">@if($months) Month: {{$months}}
                    @else
                    Date: {{ date('dS M y', strtotime($startdate)) }} - {{ date('dS M y', strtotime($enddate)) }}
                    @endif</td>
            </tr>
            <tr>
                <td style="text-align: left;">PAN:{{\Auth::user()->organization->tpid}}</td>
            </tr>
            <tr>
                <td style="text-align: left;">Sales Book</td>
            </tr>

            <tr>

                <th colspan="3" style="text-align: center; background-color: #eee">Invoice</th>
                <th colspan="5"></th>
                <th colspan="2" style="text-align: center;  background-color: #eee">Taxable Sales</th>

            </tr>
            <tr>
                <th>Bill Date</th>
                <th>Bill No</th>
                <th>Customer's Name</th>
                <th>Cust. PAN Number</th>
                <th>Total Sales</th>
                <th>Non Tax Sales</th>
                <th>Export Sales</th>
                <th>Discount</th>
                <th>Amount</th>
                <th>Tax(Rs)</th>

            </tr>
        </thead>
        <tbody>
            <?php  
               $total_amount =0 ;
              $taxable_amount = 0;
              $tax_amount = 0;
              ?>
            @foreach($sales_book as $sal_bks)
            <tr>

                <td>{{ date('dS M y', strtotime($sal_bks->bill_date)) }}/<br />
                    <?php
                                        $temp_date = explode(" ",$sal_bks->bill_date );
                                        $temp_date1 = explode("-",$temp_date[0]);
                                        $cal = new \App\Helpers\NepaliCalendar();
                                        //nepali date
                                        $a = $temp_date1[0];
                                        $b = $temp_date1[1];
                                        $c = $temp_date1[2];
                                        $d = $cal->eng_to_nep($a,$b,$c);
                                    
                                         $nepali_date = $d['date'].' '.$d['nmonth'] .', '.$d['year'];
                                        ?>

                    <small> {!! $nepali_date !!}</small>

                </td>
                <td>{{env('SALES_BILL_PREFIX')}}{{$sal_bks->bill_no}} </td>
                <td>@if($sal_bks->client){{$sal_bks->client->name}}@else{{$sal_bks->name}}@endif</td>
                <td>@if($sal_bks->client){{$sal_bks->client->vat}}@else {{$sal_bks->customer_pan}} @endif</td>
                <td>{{$sal_bks->total_amount}}</td>
                <td></td>
                <td></td>
                <td>{{$sal_bks->discount_amount}}</td>
                <td>{{$sal_bks->taxable_amount}}</td>
                <td>{{$sal_bks->tax_amount}}</td>
            </tr>
            <?php  
      $taxable_amount = $taxable_amount + $sal_bks->taxable_amount;
      $tax_amount = $tax_amount +  $sal_bks->tax_amount;
       $total_amount = $total_amount +  $sal_bks->total_amount;
    ?>


            @if($sal_bks->invoicemeta->is_bill_active === 0)
            <tr>
                <td>{{ date('dS M y', strtotime($sal_bks->bill_date)) }}/<br />
                    <?php
                                        $temp_date = explode(" ",$sal_bks->bill_date );
                                        $temp_date1 = explode("-",$temp_date[0]);
                                        $cal = new \App\Helpers\NepaliCalendar();
                                        //nepali date
                                        $a = $temp_date1[0];
                                        $b = $temp_date1[1];
                                        $c = $temp_date1[2];
                                        $d = $cal->eng_to_nep($a,$b,$c);
                                    
                                         $nepali_date = $d['date'].' '.$d['nmonth'] .', '.$d['year'];
                                        ?>

                    <small> {!! $nepali_date !!}</small>

                </td>
                <td>Ref of {{env('SALES_BILL_PREFIX')}}{{$sal_bks->bill_no}} CN {{$sal_bks->invoicemeta->credit_note_no}}</td>
                <td>@if($sal_bks->client){{$sal_bks->client->name}}@else{{$sal_bks->name}}@endif</td>
                <td>@if($sal_bks->client){{$sal_bks->client->vat}}@else {{$sal_bks->customer_pan}} @endif</td>
                <td>-{{$sal_bks->total_amount}}</td>
                <td></td>
                <td></td>
                <td>{{$sal_bks->discount_amount}}</td>
                <td>-{{$sal_bks->taxable_amount}}</td>
                <td>-{{$sal_bks->tax_amount}}</td>
            </tr>

            <?php  
      $taxable_amount = $taxable_amount- $sal_bks->taxable_amount;
      $tax_amount = $tax_amount-  $sal_bks->tax_amount;
       $total_amount = $total_amount -$sal_bks->total_amount;
    ?>

            @endif
            @endforeach
            <tr>
                <th>Total Amount</th>
                <td></td>
                <td></td>
                <td></td>
                <td>{{number_format($total_amount,2)}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{number_format($taxable_amount,2)}}</td>
                <td>{{number_format($tax_amount,2)}}</td>
            </tr>

        </tbody>
    </table>
</body>
</html>
<script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
<script>
    $(document).ready(function() {
        window.print();
    }); 
    
    </script> 