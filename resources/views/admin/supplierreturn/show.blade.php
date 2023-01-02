@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')
<style>
    .box-comment { margin-bottom: 5px; padding-bottom: 5px; border-bottom: 1px solid #eee; }
    .box-comment img {float: left; margin-right:10px;}
    .username { font-weight: bold; }
    .comment-text span{display: block;}
</style>

    <div class='row'>
        <div class='col-md-12'>

            <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <img width="30%" height="" src="{{env('APP_URL')}}{{ '/org/'.$organization->logo }}">
            <span class="pull-right">
              <span>Tax Invoice</span>
               <a href="/admin/supplierreturn/print/{{ $ord->id }}" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Print</a>

               <a href="/admin/supplierreturn">
            <button type="button" class="btn btn-success btn-sm pull-right">
              <i class="fa fa-times-circle"></i> Close
            </button>
          </a> &nbsp;
         
          &nbsp;
          <a href="/admin/supplierreturn/pdf/{{ $ord->id }}">
            <button type="button" class="btn btn-primary btn-sm pull-right" style="margin-right: 5px;">
              <i class="fa fa-download"></i> Generate PDF
            </button>
          </a>



            </span>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div align="center" style="background-color: #CCCCCC">
            @if(!$ord->is_bill_active)
            {{$ord->void_reason}}
            @endif
        </div>
        <div class="col-sm-4 invoice-col">
          <address>
            <strong>{{ env('APP_COMPANY') }} </strong><br>
            {{ env('APP_ADDRESS1') }}<br>
            {{ env('APP_ADDRESS2') }}<br>
            Phone: {{ env('APP_PHONE1') }}<br>
            Email: {{ env('APP_EMAIL') }}<br/>
            Sellers PAN:  {{ \Auth::user()->organization->tpid }}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          To
          <address>
            <strong>Name: {{ $ord->name }}</strong><br>
            {{ $ord->supplier->name }}<br/>
           Address: {!! nl2br($ord->address ) !!}<br/>
           PAN: {!! $ord->customer_pan  !!}

          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">

          <b>Return Date:</b> {{ date("d/M/Y", strtotime($ord->return_date )) }}<br>
          
          <b>Purchase Date:</b>  {{ date("d/M/Y", strtotime($ord->purchase_order_date )) }}
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      Thank you for choosing {{ env('APP_COMPANY') }}. Your {{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} is detailed below. If you find errors or desire certain changes, please contact us.
<hr/>
      <!-- Table row -->
      <div class="row col-xs-12 table-responsive">
        <div class="col-xs-12 table-responsive">
          <table id="t01" class="table table-striped">
            <thead class="bg-gray">
            <tr>
              <th>Product</th>
              <th>Units</th>
              <th>Order Price</th>
              <th>Return Price</th>
              <th>Purchase Qty</th>
              <th>Return Qty</th>
              <th>Return Total</th>
              <th>Reason</th>
            </tr>
            </thead>
            <tbody>
                @foreach($orderDetails as $odk => $odv)
                <tr>
                    @if($odv->is_inventory == 1)
                     <td>{{ $odv->product->name }}</td>
                     @elseif($odv->is_inventory == 0)
                    <td>{{ $odv->description }}</td>
                    @endif
                    <td>{{$odv->unitname->name}}</td>
                  <td>{{ env('APP_CURRENCY').' '.number_format($odv->purchase_price,2) }}</td>
                  <td>{{ env('APP_CURRENCY').' '.number_format($odv->return_price,2) }}</td>
                  <td>{{$odv->purchase_quantity}}</td>
                  <td>{{$odv->return_quantity}}</td>
                  <td>{{ env('APP_CURRENCY').' '.number_format($odv->return_total,2) }}</td>
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

          <?php
           $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
           ?>

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;text-transform: capitalize;font-size: 16,5px">
            In Words: {{ $f->format($ord->total_amount)}}
            </p>


          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Above information is only an estimate of services/goods described above.
            </p>

            <h4> Special Notes and Instruction</h4>
            <p class="text-muted well well-sm well-primary no-shadow" style="margin-top: 10px;">
           {!! nl2br($ord->comment) !!}
        </p>

          <p class="text-muted well well-sm well-success no-shadow" style="margin-top: 10px;">
                ___________________________________

                <br>Authorized Signature
          </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">


          <div>
            <table id="" class="table-responsive table table-striped">
              <tbody>
                <tr style="padding:0px; margin:0px;">
                <th style="width:50%" >Sub Total:</th> <td>{{ env('APP_CURRENCY').' '. number_format($ord->subtotal,2) }}</td>
              </tr>
               <tr style="padding:0px; margin:0px;">
                <th style="width:50%">Discount Percentage(%)</th> <td>{{$ord->discount_percent }}%</td>
              </tr>
              <tr>
                <th style="width:50%">Taxable Amount</th> <td>{{env('APP_CURRENCY').' '. number_format($ord->taxable_amount,2) }}</td>
              </tr>
              <tr style="padding:0px; margin:0px;">
                <th style="width:50%">Tax Amount(13%):</th> <td>{{ env('APP_CURRENCY').' '. number_format($ord->tax_amount,2) }}</td>
              </tr>
               <tr style="padding:0px; margin:0px;">
                <th style="width:50%">Total:</th> <td>{{ env('APP_CURRENCY').' '. number_format($ord->total_amount,2) }}</td>
              </tr>
            <!--   <tr>
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

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
         

         
        </div>
      </div>
    </section>



        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection

@section('body_bottom')
    <!-- Select2 js -->
    @include('partials._body_bottom_select2_js_user_search')
@endsection
