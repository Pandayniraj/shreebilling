<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ env('APP_COMPANY')}} | INVOICE</title>
    <style type="text/css">

      .clearfix:after {
  content: "";
  display: table;
  clear: both;
}

a {
  color: #5D6975;
  text-decoration: underline;
}

body {
  position: relative;
  width: 14cm;
  height: 29.7cm;
  margin: 0 auto;
  color: #001028;
  background: #FFFFFF;
  font-family: Arial, sans-serif;
  font-size: 12px;
  font-family: Arial;
}

header {
  padding: 10px 0;
  margin-bottom: 30px;
}

#logo {
  text-align: center;
  margin-bottom: 10px;
}

#logo img {
  max-width: 300px;
}

h1 {
  border-top: 1px solid  #5D6975;
  border-bottom: 1px solid  #5D6975;
  color: #5D6975;
  font-size: 2.4em;
  line-height: 1.4em;
  font-weight: normal;
  text-align: center;
  margin: 0 0 20px 0;
  background: url(dimension.png);
}

#project {
  float: left;
}

#project span {
  color: #5D6975;
  text-align: left;
  width: 52px;
  margin-right: 10px;
  display: inline-block;
  font-size: 0.8em;
}

#company {
  float: left;
  text-align: left;
}

#project div,
#company div {
  white-space: nowrap;
}

table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 5px;
}

table tr:nth-child(2n-1) td {
  background: #F5F5F5;
}

table th,
table td {
  text-align: left;
}

table th {
  padding: 5px 10px;
  color: #5D6975;
  border-bottom: 1px solid #C1CED9;
  white-space: nowrap;
  font-weight: normal;
  background-color: #ccc;
}

table .service,
table .desc {
  text-align: left;
}

table td {
  padding: 10px;
  text-align: left;
}

table td.service,
table td.desc {
  vertical-align: top;
}

table td.unit,
table td.qty,
table td.total {
  font-size: 1.2em;
}

table td.grand {
  border-top: 1px solid #5D6975;;
}

#notices .notice {
  color: #5D6975;
  font-size: 1.2em;
}

footer {
  color: #5D6975;
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #C1CED9;
  padding: 8px 0;
  text-align: center;
}
    </style>
  </head>
  <body>



    <header>
      <div id="logo">
         <img width="" src="https://www.customers.meronetwork.com/images/logo-invoice.png">
      </div>
      <h1>{{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}}</h1>

<table>
        <thead>
          <td>
            <address>
              <strong>{{ env('APP_COMPANY') }} </strong><br>
              {{ env('APP_ADDRESS1') }}<br>
              {{ env('APP_ADDRESS2') }}<br>
              Phone: {{ env('APP_PHONE1') }}<br>
              Email: {{ env('APP_EMAIL') }}<br/>
              Generated by: {{ $ord->user->first_name}} {{ $ord->user->last_name}}
            </address>

    </td>
    <td>

      Bill and Ship To
      <address>
        <strong>{{ $saleDataInvoice->lead->name }}</strong><br>
        {{ $saleDataInvoice->lead->mob_phone  }}<br/>
        {!! $saleDataInvoice->lead->email  !!}
      </address>

    </td>

    <td>
<b>{{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} #{{ $ord->id }}</b><br>
            <b>Issue Date:</b> {{ date("d/M/Y", strtotime($saleDataInvoice->created_at )) }}<br>
            <?php $timestamp = strtotime($saleDataInvoice->created_at) ?>
            <b>Valid Till:</b> {{ date("d/M/Y", strtotime("+30 days", $timestamp )) }}<br>
            <b>Terms :</b> Nett 15<br>
            <b>Customer Account:</b> #00{{ $ord->client_id }}

    </td>


</tr>
        </thead>
      </table>

      Thank you for choosing {{ env('APP_COMPANY') }}. Your Invoice  is detailed below. If you find errors or desire certain changes, please contact us.

    </header>



    <main>
      <div class="row">
          <div class="">
            <table class="">
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
                    <td>#{{ $odv->product_id }}</td>
                    <td>{{ $odv->product->name }}</td>
                    <td>{{ $odv->price }}</td>
                    <td>{{ $odv->quantity }}</td>
                    <td>{{ $odv->tax ? $odv->tax : 0 }}</td>
                    <td>{{ $odv->tax_amount }}</td>
                    <td>{{ env('APP_CURRENCY').' '.$odv->total }}</td>
                  </tr>
                  @endforeach
              </tbody>
            </table>
            <table class="table">
                <tbody>
                  <tr style="text-align: right">
                    <td style="width:60%;"></td>
                  <td>Total:</td>
                  <td>{{ env('APP_CURRENCY').' '.$saleDataInvoice->total }}</td>
                </tr>
                <!-- <tr>
                   <td style="width:60%;"></td>
                  <td>Discount:</td>
                  <td>{{ env('APP_CURRENCY').' '.($ord->discount_amount ? $ord->discount_amount : '0') }}</td>
                </tr>
                <tr>
                  <td style="width:60%;"></td>
                  <td>Tax Amount</td>
                  <td>{{ env('APP_CURRENCY').' '.$ord->total_tax_amount }}</td>
                </tr>
                <tr>
                   <td style="width:60%;"></td>
                  <td><strong>TOTAL:</strong></td>
                  <td><strong>{{ env('APP_CURRENCY').' '.$ord->total }}</strong></td>
                </tr> -->
              </tbody></table>
          </div>
          <!-- /.col -->
        </div>
      <div id="notices">
        <div>NOTICE:</div>
        <div class="notice">

          <h4> Special Notes and Instruction</h4>
          <p> {!! nl2br($saleDataInvoice->comments) !!}</p>

          Above information is only an estimate of services/goods described above.<br/>
          Please confirm your acceptance by signing this document<br/><br/><br/>

          ___________________________________

</div>





        <p> <strong>Thank you for your business</strong></p>
      </div>
    </main>
    <footer>
      This document was created on a computer and is valid without the signature and seal.  This Invoice includes VAT.
              TPID: {{ \Auth::user()->organization->tpid }}
    </footer>
  </body>
</html>
