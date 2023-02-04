<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ env('APP_COMPANY')}} | Purchase Order</title>

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
  padding-top: 2px !important; /*cancels out browser's default cell padding*/
  padding-bottom: 2px !important; /*cancels out browser's default cell padding*/
}

th{
  border: 1px dotted #999 !important;
    padding-top: 2px !important; /*cancels out browser's default cell padding*/
  padding-bottom: 2px !important; /*cancels out browser's default cell padding*/
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
</style>


</head>

<body cz-shortcut-listen="true" class="skin-blue sidebar-mini">

    <div class='wrapper'>

        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <div class="col-xs-3">
                            <img src="/org/{{Auth::user()->organization->logo ??'' }}" style="max-width: 200px;">
                        </div>
                        <div class="col-xs-9">
                            <span class="pull-right">
                                <span>{{ ucwords(str_replace("_", " ", ucfirst($ord->purchase_type)))}}</span>
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
                        <strong>{{ \Auth::user()->organization->organization_name }} </strong><br>
                        {{ \Auth::user()->organization->address }}<br>
                        Phone: {{ \Auth::user()->organization->phone }}<br>
                        Email: {{ \Auth::user()->organization->email }}<br>
                        PAN: {{ \Auth::user()->organization->vat_id }}
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    SUPPLIER
                    <address>
                        <strong>{{ $ord->client->name }}</strong><br>
                        Address: {!! nl2br($ord->client->location ) !!}<br>
                        Phone: {!! $ord->client->phone !!}<br>
                        Email: {!! $ord->client->email !!}<br>
                        PAN NO: {!! $ord->pan_no !!}<br>
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <b>Order No {{\FinanceHelper::getAccountingPrefix('PURCHASE_PRE')}}{{ $ord->reference }}</b><br>
                    <b>Bill Date:</b> {{ date("d/M/Y", strtotime($ord->bill_date )) }}<br>
                    <?php $timestamp = strtotime($ord->created_at) ?>
                    <b>Valid Till:</b> {{ date("d/M/Y", strtotime("+30 days", $timestamp )) }}<br>
                    <b>Vendor Reg No:</b> #{{ $ord->supplier_id }}<br>
                    Generated by: {{ $ord->user->first_name}} {{ $ord->user->last_name}}
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            Your negotiations and agreement to do business are conditioned on your acceptence of and compliance with these terms.
            The {{ ucwords(str_replace("_", " ", ucfirst($ord->purchase_type)))}} is requested by the {{ env('APP_COMPANY') }}.
            <hr />
            <!-- Table row -->
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                        <thead class="vendorListHeading">
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                @if($ord->is_import==1)
                                <th >Landing Price</th>
                                @endif
                                <th>Subtotal</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                  $n= 0;
                  ?>
                            @foreach($orderDetails as $odk => $odv)
                            <tr>
                                <td class="no">{{++$n}}</td>
                                @if($odv->is_inventory == 1)
                                <td>{{ $odv->product->name }}</td>
                                @elseif($odv->is_inventory == 0)
                                <td>{{ $odv->description }}</td>
                                @endif
                                <td>{{$odv->unit_price}}</td>
                                <td>{{ $odv->qty_invoiced }}</td>
                                @if($ord->is_import==1)
                                @php
                                    $count=$orderDetails->count();
                                    $importpurchase=\App\Models\ImportPurchase::where('purchase_order_id',$ord->id)->get();
                                    $total_additional_cost=0;
                                    foreach($importpurchase as $ip){
                                        if($ip->product_id==$odv->product_id){
                                            $total_additional_cost+=$ip->amount;
                                        }elseif($ip->product_id==0){
                                            $total_additional_cost+=$ip->amount/$count;
                                        }
                                    }
                                    $landingcost=($total_additional_cost/$odv->qty_invoiced)+$odv->unit_price;
                                @endphp
                                <td >{{ number_format($landingcost,2) }}</td>
                                @endif
                                <td>{{ $ord->currency.' '.$odv->total }}</td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <?php
                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            ?>

            <div class="row">
                <!-- accepted payments column -->
                <div class="col-xs-6">
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;text-transform: capitalize;">
                        In Words:<?php $f->format($ord->total) ?>
                    </p>
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        {{ nl2br($ord->comments) }}
                    </p>

                </div>
                <!-- /.col -->
                <div class="col-xs-6">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th style="width:50%">Subtotal:</th>
                                    <td>{{ $ord->currency.' '.number_format($ord->subtotal ,2)}}</td>
                                </tr>
                                <tr>
                                    <th>Discount Amount:</th>
                                    <td>{{$ord->currency.' '. number_format($ord->discount_amount,2) }}</td>
                                </tr>
                                <tr>
                                    <th>Taxable Amount:</th>
                                    <td>{{ $ord->currency.' '. number_format($ord->taxable_amount,2) }}</td>
                                </tr>
                                <tr>
                                    <th>Tax Amount</th>
                                    <td>{{ $ord->currency.' '. number_format($ord->tax_amount,2) }}</td>
                                </tr>
                                <tr>
                                    <th>Total:</th>
                                    <td>{{ $ord->currency.' '. number_format($ord->total,2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            @if($ord->is_import==1)
            <p>Additional Cost</p>
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                        <thead class="vendorListHeading">
                            <tr>
                                <th class="no">#</th>
                                <th class="no">Cost Type</th>
                                <th class="no">Product</th>
                                <th class="no">Method</th>
                                <th class="no">Amount</th>
                                <th class="no"> Debit Amount </th>
                                <th class="no">Credit Amount</th>
                                <th class="no">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $importpurchase=\App\Models\ImportPurchase::where('purchase_order_id',$ord->id)->get();
                            @endphp
                            @foreach($importpurchase as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->cost_type}}</td>
                                <td>{{ $item->product_id==0?'All Product':\App\Models\Product::find($item->product_id)->name }}</td>
                                <td>{{ $item->method }}</td>
                                <td>{{ number_format($item->amount,2) }}</td>
                                <td>{{ \App\Models\COALedgers::find($item->debit_account_ledger_id)->name }}</td>
                                <td>{{ \App\Models\COALedgers::find($item->credit_account_ledger_id)->name}}</td>
                                <td>{{ $item->description}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
          @endif
        </section>

    </div><!-- /.col -->

</body>
<script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
<script>
    $(document).ready(function() {
        window.print();
    }); 
    
    </script> 