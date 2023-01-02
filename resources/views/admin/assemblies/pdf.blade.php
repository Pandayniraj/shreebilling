<!DOCTYPE html><html lang="en"><head>
    <meta charset="utf-8">
    <title>{{ env('APP_COMPANY')}} | INVOICE</title>
    <style type="text/css"> 
@font-face {
  font-family: Arial;
}

.clearfix:after {
  content: "";
  display: table;
  clear: both;
}
a {
  color: #0087C3;
  text-decoration: none;
}
body {
  position: relative;
  width: 18cm;  
  height: 24.7cm; 
  margin: 0 auto; 
  color: #555555;
  background: #FFFFFF; 
  font-family: Arial, sans-serif; 
  font-size: 12px; 
}
header {
  padding: 10px 0;
  margin-bottom: 20px;
  border-bottom: 1px solid #AAAAAA;
}

table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 5px;
}

table th,
table td {
  padding: 3px;
  background:;
  text-align: left;
  border-bottom: 1px solid #FFFFFF;
}

table th {
  white-space: nowrap;        
  font-weight: normal;
}

table td {
  text-align: left;
}

table td h3{
  color: #57B223;
  font-size: 1.2em;
  font-weight: normal;
  margin: 0 0 0.2em 0;
}

table .no {
  color: #FFFFFF;
  font-size: 1em;
  background: #57B223;
}

table .desc {
  text-align: left;
}

table .unit {
  background: #DDDDDD;
}

table .qty {
}

table .total {
  background: #57B223;
  color: #FFFFFF;
}

table td.unit,
table td.qty,
table td.total {
  font-size: 1.2em;
}

table tbody tr:last-child td {
  border: none;
}

table tfoot td {
  padding: 5px 10px;
  background: #FFFFFF;
  border-bottom: none;
  font-size: 1em;
  white-space: nowrap; 
  border-top: 1px solid #AAAAAA; 
}

table tfoot tr:first-child td {
  border-top: none; 
}

table tfoot tr:last-child td {
  color: #57B223;
  font-size: 1em;
  border-top: 1px solid #57B223; 
  font-weight: bold;

}

table tfoot tr td:first-child {
  border: none;
}

#thanks{
  font-size: 2em;
  margin-bottom: 50px;
}

#notices{
  padding-left: 6px;
  border-left: 6px solid #0087C3;  
}

#notices .notice {
  font-size: 1.2em;
}

footer {
  color: #777777;
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #AAAAAA;
  padding: 8px 0;
  text-align: center;
}
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
  </head><body>
 
       <div id="bg-text">PDF COPY</div>
 
    <header class="clearfix">
        <table>
            <tr>
        <td width="50%" style="float:left">
          <div id="logo">
            <img style="max-width: 150px" src="{{env('APP_URL')}}{{ '/org/'.$organization->logo }}">
          </div>
        </td>
        <td width="50%" style="text-align: right">
        <div  id="company">
          <h4 class="name">{{ env('APP_COMPANY') }} </h4>
          <div>{{ env('APP_ADDRESS1') }}</div>
          <div>{{ env('APP_ADDRESS2') }}</div>
          <div>Seller's PAN: {{ env('TPID') }}</div>
          <div><a href="mailto:{{ env('APP_EMAIL') }}">{{ env('APP_EMAIL') }}</a></div>
        </div>
        </td></tr>

      </table> 

      </div>
    </header>
      <div id="details" class="clearfix">

        <table>
        <TR><TD width="50%" style="float:left">
        <div id="client">
          <div class=""><strong>Product: {{ $ord->productname->name }}</strong></div>
          <div class=""> Source: {!! $ord->sourcename->location_name !!}</div>
          <div class="">Destination: {!! $ord->destinationname->location_name !!}</div>
          <div class="">Assembled Quantity: {!! $ord->assembled_quantity  !!}</div>
         
        </div>
      </TD>

      <TD width="50%" style="text-align: right">
        <div id="invoice">
          <div class="date">Assemble By: {{ date("d/M/Y", strtotime($ord->assemble_by )) }}</div>
          <div class="date">Status: {{ $ord->status }}</div>
          <div class="date">Total Cost: {{ $ord->total_cost }}</div>
          
        </div>
      </div>
    </TD></TR>
    </table>
    <!-- /.row -->
      <p>Thank you for choosing {{ env('APP_COMPANY') }}. Your {{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} is detailed below. If you find errors or desire certain changes, please contact us.</p>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="no">#</th>
            <th class="no">PRODUCT</th>
            <th class="no">Units</th>
            <th class="no">Qantity</th>
            <th class="no">Wastage Qantity</th>
            <th class="no">Cost Price</th>
            <th class="no">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $n= 0;
          ?>
          @foreach($orderDetails as $odk => $odv)
          <tr>
          <td>{{++$n}}</td>
          <td>{{ $odv->product->name }}</td> 
          <td>{{$odv->unitname->name}}</td>
          <td>{{$odv->quantity }}</td>
          <td>{{$odv->wastage_qty }}</td>
          <td>{{$odv->cost_price}}</td>
          
          <td>{{ env('APP_CURRENCY').' '.number_format($odv->total,2) }}</td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
          
          <tr>
            <td colspan="5"></td>
            <td colspan="1">Total</td>
            <td>{{ env('APP_CURRENCY').' '. number_format($ord->total_amount,2) }}</td>
          </tr>
        </tfoot>
      </table>
       <p id="" style="text-transform: capitalize;">
          <?php
             $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
          ?>
          In Words:{{ $f->format($ord->total_amount) }} 
       </p>
      <br>
      <div id="notices">
        <div>Note:</div>
        <div class="notice"> {!! nl2br($ord->comments) !!}</div>
      </div>
       <div id=""> ___________________________________</div>
        <div id="">Authorized Signature</div>
    <footer>
      {{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} was created on MEROCRM.
    </footer></body></html>