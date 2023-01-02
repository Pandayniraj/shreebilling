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
    {{-- <link href="{{ asset(elixir('css/all.css')) }}" rel="stylesheet" type="text/css" /> --}}

    <style>
table {
  width:100%;
}
table, th, td {
  border: 1px solid #696969;
  border-collapse: collapse;
}
th, td {
  padding: 15px;
  text-align: left;
}
table#t01 tr:nth-child(even) {
  background-color: #eee;
}
table#t01 tr:nth-child(odd) {
 background-color: #fff;
}
table#t01 th {
  background-color: #696969  ;
  color: white;
}
.table>thead>tr>th {
    border-bottom: 1px solid #696969 !important;
}
.table>tbody>tr>th {
    border-top: 1px solid #696969 !important;
    z-index: 1;
}
.table>tbody>tr>td{
  border-top: 1px solid #696969 !important;
  z-index: 1;
}
@media print {
    .pagebreak { page-break-before: always; } /* page-break-after works, as well */
}



</style>


  </head>
<style type="text/css">
#background{
    position:absolute;
    z-index:0;
    background:white;
    display:block;
    min-height:50%;
    min-width:50%;
    color:yellow;
}
#content{
    position:absolute;
    z-index:1;
}
#bg-text
{
    color:lightgrey;
    position: absolute;
    left:0;
    right:0;
    top:40%;
    text-align:center;
    margin:auto;
    opacity: 0.5;
    z-index: 2;
    font-size:120px;
    transform:rotate(330deg);
    -webkit-transform:rotate(330deg);
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
    bottom: 23px;
    left:-20px;
}
</style>

<body onload="window.print();" cz-shortcut-listen="true" class="skin-blue sidebar-mini">
  <?php

  $loop = $print_no > 0 ? 1: 2;

  ?>
  <div class='wrapper'>

    @foreach(range(1,$loop) as $key)
    @if($key >= 2) <div class="pagebreak" style="padding-top: 45px !important;"> </div>@endif
      <section class="invoice">
        <!-- title row -->
        <div class="row">
            <h2>
                <div class="col-xs-3">
              <img width="" style="max-width: 200px" src="{{ '/org/shree.jpeg' }}" alt="{{ \Auth::user()->organization->organization_name }}">
                </div>
                <div class="col-xs-7" style="text-align: center;font-size:18px">
                   
                        <span style="white-space: nowrap;">JAI SHREEBINAYAK DISTRIBUTOR AND TRADE LINK PVT. LTD.</span>
                        <br>
                        <span>Chamati-15, Kathmandu</span><br>
                        <span>Delivery Note/Quotation</span>
                </div>
          
            </h2>
          <!-- /.col -->
       
         
         
        </div>
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-md-10 invoice-col">
           
            <address>
        
            Delivery Quotation No.:{{$current_year}}- {{$ord->id}}
            <span style="white-space: nowrap;">CustomerName: {{$ord->client->name??''}}</span><br>
            Buyer's VAT No: {{$ord->client->vat??''}}<br>
            Type:  Cash/Credit/Cheque/Draft/Other
          </address>
          </div>
          
          
          <div class="col-md-2 invoice-col" style="float: right;">
           <b>Date:</b> {{ \App\Helpers\TaskHelper::getNepaliDate(\Carbon\Carbon::now()) }}<br>
        <b>Address:</b> {{ $ord->client->location??'' }}<br>
          <b>Sales Order By:</b>  {{$cusdata->sales_person}}<br>
          @if($print_no > 0)
       <b>Copy No: {{$print_no}}</b>
    @endif
          </div>
          <!-- /.col -->-
        </div>
        <!-- /.row -->
        Thank you for choosing {{ \Auth::user()->organization->organization_name }}. Your details are below. If you find errors or desire certain changes, please contact us.
        <hr/>
        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
              <tr>
                  <th>S.N</th>
                  <th>Particulars</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Unit Price</th>
                  <th>Sub Total</th>
                  <th>Remarks</th>
              </tr>
              </thead>
              <tbody>
                 <?php
                  $n= 0;
                  $totalamt=0;
                  $totalqty=0;
                  ?>
                  @foreach($orderDetails as $odk => $odv)
                  {{-- @dd($odv); --}}
                  <tr>
                    <td>{{++$n}}</td>
                     <td>{{ \App\Models\Product::where('id', $odv->product_id)->first()->name }}</td>
                     <td>{{$odv->sales_quantity}}</td>
                  <td>{{ \App\Models\ProductsUnit::where('id', $odv->unit)->first()->name}}</td>
                  <td>{{number_format($odv->return_price,2) }}</td>
                  <td>{{$odv->return_total}}</td>
                  <td>{{$odv->reason}}</td>
                  <?php
                  $totalqty+= (double)$odv->sales_quantity;
                  $totalamt+= (double)$odv->return_total;
                  ?>
                </tr>
                  @endforeach
                  <tr>
                    <td colspan="2">Total</td>
                    <td>{{$totalqty}}</td>
                    <td></td>
                    <td></td>
                    <td>{{$totalamt}}</td>
                    <td></td>
                  </tr>
              </tbody>
            </table>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
         
          <?php
            $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
           ?>
        <div class="row">
          <!-- accepted payments column -->
          <div class="col-xs-4">
             <p style="margin-top: 10px;text-transform: capitalize;">
              Received By
            </p>
            <p>Sign & Stamp</p>
            <p>Name:</p>
            <p>Contact No:</p>
          </div>
          <div class="col-xs-4">
            <p style="margin-top: 10px;text-transform: capitalize;">
              Prepared By: {{\Auth::user()->first_name.' '.\Auth::user()->last_name}}
            </p><br>
          </div>
          <div class="col-xs-4">
            <p style="margin-top: 10px;text-transform: capitalize;">
             Authorized Signature
           </p><br>
         </div>
          <!-- /.col -->
         
          <!-- /.col -->
        </div>
        <!-- /.row -->

      </section>

  </div><!-- /.col -->
  @endforeach
</body>
