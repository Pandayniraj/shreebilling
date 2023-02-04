<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ \Auth::user()->organization->organization_name }} | INVOICE</title>

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


    <style type="text/css">
        @media print {
         body {
          -webkit-print-color-adjust: exact;
      }
  }

  .vendorListHeading th {
     background-color: #1a4567 !important;
     color: white !important;
 }

 table{
    border: 1px solid dotted !important;
    font-size: 14px !important;
    padding-top: 2px !important; /*cancels out browser's default cell padding*/
    padding-bottom: 2px !important; /*cancels out browser's default cell padding*/
}

td{
  border: 1px dotted #999 !important;
  padding-top: 0px !important; /*cancels out browser's default cell padding*/
  padding-bottom: 0px !important; /*cancels out browser's default cell padding*/
}

th{
  border: 1px dotted #999 !important;
  padding-top: 0px !important; /*cancels out browser's default cell padding*/
  padding-bottom: 0px !important; /*cancels out browser's default cell padding*/
}

.invoice-col{
  /*border: 1px dotted #1a4567 !important;*/
  font-size: 13px !important;
  margin-bottom: -20px !important;
}

@page {
    size: auto;
    margin: 0;
}

body{
    padding-left: 1.3cm;
    padding-right: 1.3cm;
    padding-top: 1.3cm;
}

@media print {
    .pagebreak { page-break-before: always; } /* page-break-after works, as well */
}
img {
    border: 0;
    width: 130px;
    height: 100px;
    display: block;
    text-indent: -9999px;
    object-fit: contain;
    mix-blend-mode: multiply;
    position: relative;
    left: -41px;
    bottom: 23px;
}
.row.invoice-info {
    position: relative;
    bottom: 35px;
}
.page-header {
    border-bottom: none !important;
    margin: 0px !important;
}
.table-responsive {
    margin-top: -32px !important;
}
</style>



</head>


<body cz-shortcut-listen="true" class="skin-blue sidebar-mini">

    <?php

    $loop = $print_no > 0 ? 1: 3;

    ?>

    <div class='wrapper'>


        @foreach(range(1,$loop) as $key)
        @if($key >= 2) <div class="pagebreak" style="padding-top: 45px !important;"> </div>@endif
        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header" >
                      
                      <div class="col-xs-3">
                          <img src="{{ '/org/shree.jpeg' }}">

                      </div>
                    
                      <div class="col-xs-9">
                        <span class="pull-right">
                            <span>@if($print_no == 0 && $key <= 1) TAX Invoice

                              @else Invoice @endif
                          </span>
                      </span>
                  </div>
                  <hr/>
              </h2>
          </div>
          <!-- info row -->
          <div class="row invoice-info">
            <div class="col-xs-12">
                <div class="col-sm-4 invoice-col">

                    <address>
                        <span style="font-size: 15px; font-weight: bold" >{{ \Auth::user()->organization->organization_name }} </span><br>
                        {{ env('APP_ADDRESS1') }}<br>
                        Phone: {{ env('APP_PHONE1') }}<br>
                        Email: {{ env('APP_EMAIL') }}<br>
                        Seller's VAT: {{ \Auth::user()->organization->vat_id }}
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    To:  Customer: {{ $ord->client_id }}
                    <address>

                     <span style="font-size: 15px; font-weight: bold"> {{ $ord->client->name }}</span><br />
                     Address: {!! nl2br($ord->client->physical_address ) !!}<br>
                     Contact: {{ $ord->client->phone }}<br />
                     Cust. PAN: {!! $ord->client->vat !!}<br/>
                     <strong>Contact Person: {{ $ord->name }}</strong><br>

                 </address>
             </div>
             <!-- /.col -->
             <div class="col-sm-4 invoice-col" style="white-space: nowrap;">
                <b>Bill No:</b> 079/80-{{ $ord->bill_no }}
                <br>

                <b>Bill Date:</b> {{ TaskHelper::getNepaliDate($ord->bill_date) }}({{ $ord->bill_date }})<br>
                <?php $timestamp = strtotime($ord->created_at) ?>
                <b>Dispatch Date:</b> {{ TaskHelper::getNepaliDate($ord->due_date) }}({{ $ord->due_date }})<br>
                <b>Sales Order By :</b> {{ $ord->sales_person }} <br>
                <b>Delivery Note/Quotation :</b> <br>
                <b>Mode of Payment :</b>  {{ $ord->terms }} 


                @if($print_no > 0)<br>
                <b> Copy of original {{ $print_no }}</b>
                @endif

            </div>
            <!-- /.col -->
        </div>
    </div>
    <!-- /.row -->
    <br/>
    <!-- Table row -->

    <div class="col-xs-12 table-responsive">
        <table class="table table-striped">
            <thead class="bg-gray">
                <tr class="vendorListHeading">
                    <th>S. No</th>
                    <th>Particulars</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Unit-Price</th>
                    <th>Amount</th>
                    <th>Discount</th>
                    <th>Sub-Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderDetails as $odk => $odv)
                <tr class="tr-sections">
                    <td>{{$odk+1}} </td>
                    @if($odv->is_inventory == 1)
                    <td>{{ $odv->product->name }}</td>
                    @elseif($odv->is_inventory == 0)
                    <td>{{ $odv->description }}</td>
                    @endif
                    <td>{{ number_format($odv->quantity,2) }}</td>
                    <td>{{ $odv->units->symbol }}</td>
                    <td>{{ number_format($odv->price,2) }}</td>
                    <td>{{ number_format($odv->quantity * $odv->price, 2) }}</td>
                    <td>{{ number_format($odv->discount, 2) }}</td>
                    <td>{{ env('APP_CURRENCY').' '.number_format($odv->total,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.col -->

    <!-- /.row -->

    <?php
    if (!function_exists('numberFomatter'))   {
       function getPaisa($number)

       {

        $no = round($number);

        $decimal = round($number - ($no = floor($number)), 2) * 100;

        $words = array(

            0 => '',

            1 => 'One',

            2 => 'Two',

            3 => 'Three',

            4 => 'Four',

            5 => 'Five',

            6 => 'Six',

            7 => 'Seven',

            8 => 'Eight',

            9 => 'Nine',

            10 => 'Ten',

            11 => 'Eleven',

            12 => 'Twelve',

            13 => 'Thirteen',

            14 => 'Fourteen',

            15 => 'Fifteen',

            16 => 'Sixteen',

            17 => 'Seventeen',

            18 => 'Eighteen',

            19 => 'Nineteen',

            20 => 'Twenty',

            30 => 'Thirty',

            40 => 'Forty',

            50 => 'Fifty',

            60 => 'Sixty',

            70 => 'Seventy',

            80 => 'Eighty',

            90 => 'Ninety');

        $paise = ($decimal) ?  ' and ' .($words[$decimal - $decimal%10]) ." " .($words[$decimal%10]) .' Paisa'  : '';
        return $paise;

    }
}

if (!function_exists('numberFomatter'))   {

    function numberFomatter($number)

    {

        $constnum = $number;

        $no = floor($number);

        $point = round($number - $no, 2) * 100;

        $hundred = null;

        $digits_1 = strlen($no);

        $i = 0;

        $str = array();

        $words = array('0' => '', '1' => 'one',

            '2' => 'two',

            '3' => 'three',

            '4' => 'four', '5' => 'five', '6' => 'six',

            '7' => 'seven', '8' => 'eight', '9' => 'nine',

            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',

            '13' => 'thirteen', '14' => 'fourteen',

            '15' => 'fifteen', '16' => 'sixteen',

            '17' => 'seventeen',

            '18' => 'eighteen',

            '19' =>'nineteen',

            '20' => 'twenty',

            '30' => 'thirty',

            '40' => 'forty',

            '50' => 'fifty',

            '60' => 'sixty',

            '70' => 'seventy',

            '80' => 'eighty',

            '90' => 'ninety');

        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');

        while ($i < $digits_1) {

           $divider = ($i == 2) ? 10 : 100;

           $number = floor($no % $divider);

           $no = floor($no / $divider);

           $i += ($divider == 10) ? 1 : 2;

           if ($number) {

            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;

            $hundred = ($counter == 1 && $str[0]) ? '' : null;

            $str [] = ($number < 21) ? $words[$number] .

            " " . $digits[$counter] . $plural . " " . $hundred

            :

            $words[floor($number / 10) * 10]

            . " " . $words[$number % 10] . " "

            . $digits[$counter] . $plural . " " . $hundred;

        } else $str[] = null;

    }

    $str = array_reverse($str);

    $result = implode('', $str);

    $points = getPaisa($constnum);

    return $result . ' Rupees' .$points;
}
}

?>



<!-- accepted payments column -->
<div class="col-xs-4" style="font-size: 11px !important">
    <strong>Total:</strong>
    <p class="text-muted well well-sm no-shadow" style="margin-top: 4px;text-transform: capitalize;">
        In Words: <?php
        echo numberFomatter($ord->total_amount);
        ?>
    </p>

    <br/><br/><br/><br/><br/>
    {{ nl2br($ord->comment) }}<br/>
    Printed by: {{\Auth::user()->username}}<br/>
    Printed Time: {{ date("F j, Y, g:i a") }} <br/>
    E. & O E
    <br/>
</div>
<!-- /.col -->
<div class="col-xs-8" style="margin-top: 20px !important">
    <div class="table-responsive">
        <table class="table">
            <tbody>
                <tr style="padding:0px; margin:0px;">
                    <th style="width:70%">Sub-Total Amount:</th>
                    <td>{{ env('APP_CURRENCY').' '. number_format($ord->non_taxable_amount + $ord->taxable_amount, 2) }}</td>
                </tr>
                <tr style="padding:0px; margin:0px;">
                    <th style="width:50%">Total Discount:</th>
                    <td>{{ env('APP_CURRENCY').' '. number_format($ord->discount_amount,2) }}</td>
                    </tr>

                    <tr style="padding:0px; margin:0px;">
                        <th style="width:50%">Non Taxable Amount:</th>
                        <td>{{ env('APP_CURRENCY').' '. number_format($ord->non_taxable_amount,2) }}</td>
                    </tr>
                    <tr style="padding:0px; margin:0px;">
                        <th style="width:50%">Taxable Amount:</th>
                        <td>{{ env('APP_CURRENCY').' '. number_format($ord->taxable_amount,2) }}</td>
                    </tr>
                    <tr style="padding:0px; margin:0px;">
                        <th style="width:50%">Tax Amount(VAT):</th>
                        <td>{{ env('APP_CURRENCY').' '. number_format($ord->tax_amount,2) }}</td>
                    </tr>
                    <tr style="padding:0px; margin:0px;">
                        <th style="width:50%">Total Amount:</th>
                        <td><b>{{ env('APP_CURRENCY').' '. number_format($ord->total_amount,2) }}</b></td>
                    </tr>
                </tbody>
            </table>
            <table class="table">
                <tbody>
                    <tr style="padding:0px; margin:0px;">


                        <th style="width:55%">
                            Received By:<br/><br/>

                            <br>______________________<br><span style="text-indent: 10px;">
                            &nbsp;&nbsp;Authorised Sign & Stamp</span>

                        </th>
                        <td>
                           <strong>{{env('APP_COMPANY')}}</strong><br/><br/>

                           <br>______________________<br><span style="text-indent: 10px;">
                            &nbsp;&nbsp;<strong>Authorised Signatory</strong></span>
                        </td>

                    </tr>
                </tbody>
            </table>

        </div>
    </div>
    <!-- /.col -->



    <div class="row">
        <div class="col-xs-12">
            <p class="text-muted" style="margin-top: 5px !important; font-size: 11px !important; text-align: center !important">

                <b>Thank you for choosing {{ env('APP_COMPANY')}}. If you have any query about this invoice. Please contact us.</b>
            </p>
        </div>
    </div>
    <!-- /.row -->

</section>

@endforeach

</div><!-- /.col -->

</body>
<script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
<script>
    $(document).ready(function() {
        window.print();
    }); 
    
    </script>    