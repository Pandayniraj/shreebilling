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
              <span>BOM</span>
               <a href="/admin/billofmaterials/print/{{ $billofmaterials->id }}" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Print</a>

               <a href="/admin/billofmaterials">
            <button type="button" class="btn btn-success btn-sm pull-right">
              <i class="fa fa-times-circle"></i> Close
            </button>
          </a> &nbsp;
         
          &nbsp;
          <a href="/admin/billofmaterials/pdf/{{ $billofmaterials->id }}">
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
         
          <address>
            <strong>Product: {{ $billofmaterials->productname->name }}</strong><br>
             Can Auto Assemble: {!! $billofmaterials->auto_assemble !!}<br/>
           Can Auto Disassemble: {!! $billofmaterials->auto_disassemble !!}<br/>
           Obsolete: {!! $billofmaterials->obsolete  !!}

          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">

          <b>Bill Date:</b> {{ date("d/M/Y", strtotime($billofmaterials->bill_date )) }}<br>
          <b>Status:</b> {{ ucwords($billofmaterials->status) }}<br>
          

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
              <th>Quantity</th>
              <th>Wastage Quantity</th>
              <th>Cost Price</th>
              <th>Total</th>
            </tr>
            </thead>
            <tbody>
                @foreach($billofmaterials_details as $odk => $odv)
                <tr>
                  <td>{{ $odv->product->name }}</td> 
                  <td>{{$odv->unitname->name}}</td>
                  <td>{{$odv->quantity }}</td>
                  <td>{{$odv->wastage_qty }}</td>
                  <td>{{$odv->cost_price}}</td>
                  
                  <td>{{ env('APP_CURRENCY').' '.number_format($odv->total,2) }}</td>
                 
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
            In Words: {{ $f->format($billofmaterials->total_amount)}}
            </p>


          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Above information is only an estimate of services/goods described above.
            </p>

            <h4> Special Notes and Instruction</h4>
            <p class="text-muted well well-sm well-primary no-shadow" style="margin-top: 10px;">
           {!! nl2br($billofmaterials->comments) !!}
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
                <th style="width:50%">Total:</th> <td>{{ env('APP_CURRENCY').' '. number_format($billofmaterials->total_amount,2) }}</td>
              </tr>
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
