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


  </head>

<body onload="window.print();" cz-shortcut-listen="true" class="skin-blue sidebar-mini">

  <div class='wrapper'>

      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              <img width="200px" src="{{ env('APP_LOGO_INVOICE') }}">
              <span class="pull-right">
                <span>{{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}}</span>
              </span>
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
              Email: {{ env('APP_EMAIL') }}
            </address>
          </div>
          <!-- /.col -->
          <div class="col-sm-4 invoice-col">
            To
            <address>
              <strong>{{ $saleDataInvoice->lead->name }}</strong><br>
              {{ $saleDataInvoice->lead->mob_phone  }}<br/>
              {!! $saleDataInvoice->lead->email  !!}
            </address>
          </div>
          <!-- /.col -->
          <div class="col-sm-4 invoice-col">
            <b>{{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} #{{ $ord->id }}</b><br>


            <b>Issue Date:</b> {{ date("d/M/Y", strtotime($saleDataInvoice->created_at )) }}<br>
            <?php $timestamp = strtotime($saleDataInvoice->created_at) ?>
            <b>Valid Till:</b> {{ date("d/M/Y", strtotime("+30 days", $timestamp )) }}<br>
            <b>Terms :</b> Nett 15<br>
            <b>Customer Account:</b> #{{ $ord->client_id }}
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        Thank you for choosing {{ env('APP_COMPANY') }}. Your {{ ucwords(str_replace("_", " ", ucfirst($invoiceType)))}} is detailed below. If you find errors or desire certain changes, please contact us.
        <hr/>
        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
              <tr>
                <th>Product ID</th>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Tax(%) *</th>
                <th>Tax({{ env('APP_CURRENCY') }})</th>
                <th>Subtotal</th>
              </tr>
              </thead>
              <tbody>
                  @foreach($salesDetails as $odk => $odv)
                  <tr>
                    <td>#{{ $odv->id }}</td>
                    <td>{{ $odv->description }}</td>
                    <td>{{ $odv->unit_price }}</td>
                    <td>{{ $odv->quantity }}</td>
                    <td>{{ $odv->tax ? $odv->tax : 0 }}</td>
                    <td>{{ number_format($odv->tax_amount,2) }}</td>
                    <td>{{ env('APP_CURRENCY').' '.number_format($odv->unit_price, 2)}}</td>
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
            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
              {{ nl2br($saleDataInvoice->comments) }}
            </p>
            <p class="text-muted well well-sm well-success no-shadow" style="margin-top: 10px;">
              This {{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} includes VAT<br/>
              TPID: {{ \Auth::user()->organization->tpid }}
            </p>
          </div>
          <!-- /.col -->
          <div class="col-xs-6">
            <div class="table-responsive">
              <table class="table">
                <tbody><tr>
                  <th style="width:50%">Total:</th>
                  <td>{{ env('APP_CURRENCY').' '.number_format($saleDataInvoice->total,2) }}</td>
                </tr>
                <!-- <tr>
                  <th>Discount:</th>
                  <td>{{ env('APP_CURRENCY').' '.($ord->discount_amount ? $ord->discount_amount : '0') }}</td>
                </tr>
                <tr>
                  <th>Tax Amount</th>
                  <td>{{ env('APP_CURRENCY').' '.$ord->total_tax_amount }}</td>
                </tr>
                <tr>
                  <th>Total:</th>
                  <td>{{ env('APP_CURRENCY').' '.$ord->total }}</td>
                </tr> -->
              </tbody></table>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

      </section>

  </div><!-- /.col -->

</body>
