<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>{{ env('APP_COMPANY')}} | {{ ucfirst(Request::segment(4)) }}</title>
    <style type="text/css">
      
@font-face {
  font-family: SourceSansPro;
  src: url(SourceSansPro-Regular.ttf);
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

    </style>
</head><body>
    <header class="clearfix">

      <table>
          <TR><TD width="50%" style="float:left">
      <div id="logo">
        <img src="{{public_path()}}/org/{{$imagepath}}">
      </div>
    </TD><td>

      <div width="50%" style="text-align: right">
         <h3 class="name">{{ \Auth::user()->organization->organization_name }} </h3>
                <div>{{ \Auth::user()->organization->address }}</div>
                  Phone: {{ \Auth::user()->organization->phone }}<br>
                  Email: {{ \Auth::user()->organization->email }}<br/>
                <div>PAN: {{ \Auth::user()->organization->vat_id }}</div>
      </div>
    </td></TR>
  </table>



      </div>
    </header>
    <main>

       <?php
      $mytime = Carbon\Carbon::now();
      $startOfYear = $mytime->copy()->startOfYear();

      $endOfYear   = $mytime->copy()->endOfYear();
  ?>


      <div id="details" class="clearfix">

        <table>
          <TR><TD>
     {{--   <div id="client">
          <div class="address">Bank or cash account:<?php echo ($ledger_data->type == 1) ? 'Yes' : 'No'; ?><br/></div>
          <div class="address">Notes: {{$groups_data->notes}}<br/></div>
          <div class="">Opening balance as on {{date('d-M-Y', strtotime($startOfYear))}}:@if($groups_data->op_balance_dc == 'D') Dr @else Cr @endif{{$groups_data->op_balance}}</div>
            <?php 
                 $closing_balance =TaskHelper::getLedgerBalance($id);
             ?>
          <div class="">Closing balance as on {{date('d-M-Y', strtotime($endOfYear))}}:{{ $closing_balance}}</div>
        </div> --}}
      </TD><TD>
      </div>
    </TD></TR>
  
    </table>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="no">Date</th>
            <th class="no">No.</th>
            <th class="no">Ledger</th>
            <th class="no">Description</th>
            <th class="no">Entry Type</th>
            <th class="no">Tag</th>
            <th class="no">({{env(APP_CURRENCY)}})</th>
            <th class="no">({{env(APP_CURRENCY)}})</th>
            <th class="no">Balance({{env(APP_CURRENCY)}})</th>
          </tr>
        </thead>
        <tbody>

        {{--  <tr>
           <td colspan="7">Current opening balance</td>
           <td>@if($groups_data->op_balance_dc == 'D') Dr @else Cr @endif  {{$groups_data->op_balance}}</td>
          </tr> --}}
          
         <?php
            $entry_balance['amount'] = $groups_data['op_balance'];
            $entry_balance['dc'] = $groups_data['op_balance_dc'];
          
             ?>
          @foreach($entry_items as $ei)

           <?php 

            $entry_balance = TaskHelper::calculate_withdc($entry_balance['amount'], $entry_balance['dc'],
            $ei['amount'], $ei['dc']);

            $getledger= TaskHelper::getLedger($ei->entry_id);
            ?>
           <tr>
              <td>{{$ei->entry->date}}</td>
              <td>{{$ei->entry->number}}</td>
              <td>{{ substr($ei->ledgerdetail->name, 0,  20) }}</td>
              <td>{{$getledger}}</td>
              <td>{{$ei->entry->entrytype->name}}</td>
              <td><span class="tag">
                {{$ei->entry->tagname->title}}
                  </span>
              </td>
              @if($ei->dc=='D')
                <td>Dr {{$ei->amount}}</td>
                <td>-</td>
              @else
                <td>-</td>
                <td>Cr {{$ei->amount}}</td>
              @endif

              <td>@if($entry_balance['dc']=='D') Dr @else Cr @endif {{number_format($entry_balance['amount'],2)}}</td>

            </tr>
          @endforeach
        </tbody>
      
          <tr>
            <td colspan="7" style="text-align: center;"><b>Current closing balance</b></td>
            <td colspan="2"><b>@if($entry_balance['dc']=='D') Dr @else Cr @endif {{ number_format($entry_balance['amount'],2)}}</b></td>
          </tr>
      
      </table>

    </main>
    <footer>
      This Statement was created on MEROCRM.
    </footer>
</body></html>