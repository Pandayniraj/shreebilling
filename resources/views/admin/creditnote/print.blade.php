<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
  <head>
    <meta charset="UTF-8">
    <title>{{ env('APP_COMPANY')}} | INVOICE</title>

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
</style>

<body cz-shortcut-listen="true" class="skin-blue sidebar-mini">

  <div class='wrapper'>
@if($print_no > 0)
       <div id="bg-text">Copy Of {{$print_no}}</div>
    @endif
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
               <div class="col-xs-3">
              <img src="{{env('APP_URL')}}{{ '/org/'.$organization->logo }}" style="max-width: 200px;">
                </div>
                <div class="col-xs-9">
              <span class="pull-right">
                <span>@if($print_no == 0) TAX Invoice @else Invoice @endif</span>
              </span>
            </div>
             <hr>
            </h2>
           
          </div>
          <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">

            <address>
              <strong>{{ env('APP_COMPANY') }} </strong><br>
              {{ env('APP_ADDRESS1') }}<br>
              {{ env('APP_ADDRESS2') }}<br>
              Phone: {{ env('APP_PHONE1') }}<br>
              Email: {{ env('APP_EMAIL') }}<br>
              Seller's PAN: {{ env('TPID') }}
            </address>
          </div>
          <!-- /.col -->
          <div class="col-sm-4 invoice-col">
            To
            <address>
              <strong>{{ $ord->name }}</strong><br>
              {{ $ord->client->name }}<br/>
              Address: {!! nl2br($ord->address ) !!}<br>
              Purchaser's PAN: {!! $ord->customer_pan  !!}
            </address>
          </div>
          <!-- /.col -->
          <div class="col-sm-4 invoice-col">
            <b>{{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} #{{ $ord->id }}</b><br>

            <b>Bill Date:</b> {{ date("d/M/Y", strtotime($ord->created_at )) }}<br>
            <?php $timestamp = strtotime($ord->created_at) ?>
            <b>Valid Till:</b> {{ date("d/M/Y", strtotime("+30 days", $timestamp )) }}<br>
            <b>Terms :</b> {{ $ord->terms }}<br>
            <b>Customer Account:</b> #{{ $ord->client_id }}
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        Thank you for choosing {{ env('APP_COMPANY') }}. Your {{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} is detailed below. If you find errors or desire certain changes, please contact us.
        <hr/>
        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
              <tr>
                <th>#</th>
                <th>Product</th>
                <th>Invoice Price</th>
                 <th>Invoice Qty</th>
                 <th>Returnable</th>
                 <th>Credit Qty</th>
                 <th>Credit Price</th>
                <th>Credit Total</th>
                <th>Reason</th>
              </tr>
              </thead>
              <tbody>
                 <?php
                  $n= 0;
                  ?>
                  @foreach($orderDetails as $odk => $odv)
                  <tr>
                      <td>{{++$n}}</td>
                       @if($odv->is_inventory == 1)
                       <td>{{ $odv->product->name }}</td>
                       @elseif($odv->is_inventory == 0)
                       <td>{{ $odv->description }}</td>
                       @endif
                      <td>{{ env('APP_CURRENCY').' '.number_format($odv->price,2) }}</td>
                      <td>{{ $odv->quantity }}</td>
                      <td>{{$odv->returnable}}</td>
                      <td>{{$odv->credit_qty}}</td>
                      <td>{{$odv->credit_price}}</td>
                      <td>{{ env('APP_CURRENCY').' '.number_format($odv->credit_total,2) }}</td>
                      <td>{{ $odv->reason }}</td>
                  </tr>
                  @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
         

        <div class="row">
          <!-- accepted payments column -->
          <div class="col-xs-6">
             <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;text-transform: capitalize;">
              In Words:
            </p>
            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
              {{ nl2br($ord->comment) }}
            </p>
            
          </div>
          <!-- /.col -->
          <div class="col-xs-6">
            <div class="table-responsive">
              <table class="table">
                <tbody>
                  <tr>
                   <th style="width:50%">Subtotal:</th>
                   <td>{{ env('APP_CURRENCY').' '.number_format($ord->subtotal ,2)}}</td>
                 </tr>
                <tr>
                  <th>Discount Percent(%):</th>
                  <td>{{ ($ord->discount_percent ? $ord->discount_percent  : '0') }}%</td>
                </tr>
                <tr>
                  <th>Taxable Amount:</th>
                  <td>{{ env('APP_CURRENCY').' '. number_format($ord->taxable_amount,2) }}</td>
                </tr>
                <tr>
                  <th>Tax Amount(13%)</th>
                  <td>{{ env('APP_CURRENCY').' '. number_format($ord->tax_amount,2) }}</td>
                </tr>
                <tr>
                  <th>Total:</th>
                  <td>{{ env('APP_CURRENCY').' '. number_format($ord->total_amount,2) }}</td>
                </tr>
              </tbody></table>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

      </section>

  </div><!-- /.col -->

</body>
<script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
<script>
    $(document).ready(function() {
        window.print();
    }); 
    
    </script> 