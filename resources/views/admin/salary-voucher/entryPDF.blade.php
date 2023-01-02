<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>{{ env('APP_COMPANY')}} | INVOICE</title>
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
  color: #000;
  text-decoration: none;
}
body {
  position: relative;
  width: 18cm;
  height: 24.7cm;
  margin: 0 auto;
  color: #000;
  background: #FFFFFF;
  font-family: Arial, sans-serif;
  font-size: 12px;
}
header {
  padding: 5px 0;
  margin-bottom: 10px;
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
  color: #000;
  font-size: 1.2em;
  font-weight: normal;
  margin: 0 0 0.2em 0;
}

table .no {
  color: #FFFFFF;
  font-size: 1em;
  background: #000;
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
  background: #000;
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
  color: #000;
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
  color: #000;
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
          <TR>
            <TD width="50%" style="float:left">
              <div id="logo">
                <img style="max-width: 150px" src="{{env('APP_URL')}}/org/{{$imagepath}}">
              </div>
              <h2 style="font-size: 16.5px;font-weight: bold;">{{\FinanceHelper::get_entry_type_name($entries->entrytype_id)}} Voucher</h2>
            </TD>
            <td>
              <div width="50%" style="text-align: right">
                <h2 class="name">{{ \Auth::user()->organization->organization_name }} </h2>
                <div>{{ \Auth::user()->organization->address }}</div>
                <div>Tax ID: {{ \Auth::user()->organization->vat_id }}</div>

                <div><a href="mailto:{{ env('APP_EMAIL') }}">{{ env('APP_EMAIL') }}</a></div>
              </div>
            </td>
         </TR>
      </table>

      </div>
    </header>
    <main>
      <div id="details" class="">
        <table>
          <TR><TD>
        <div id="client">
          <div class="address">Number:{{$entries->number}}<br/></div>
          <div class="address">Date: {{ $entries->date }} / {{ \TaskHelper::getNepaliDate($entries->date) }}<br/></div>
          <div class="">Tag:{{ $entries->tagname->title}}</div>
        </div>
      </TD><TD>
      </div>
    </TD></TR>

    </table>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>

            <th class="no">Ledger</th>
            <th class="no">Dr Amount (NRs.)</th>
            <th class="no">Cr Amount (NRs.)</th>
            <th class="no">Cheque No.</th>
            <th class="no">Employee</th>
            <th class="no">Assign To</th>
            <th class="no">Narration</th>
          </tr>
        </thead>
        <tbody>
         @foreach($entriesitem as $items)
          <tr>

            <td>[{{$items->ledgerdetail->code}}] {{$items->ledgerdetail->name}}</td>
            @if($items->dc == 'D')
            <td>{{number_format($items->amount,2)}}</td>
            <td>-</td>
            @else
            <td>-</td>
            <td>{{number_format($items->amount,2)}}</td>
            @endif
            <td>{{$items->cheque_no??'-'}}</td>
            <td>{{ ($items->employee_id != 0)? (!empty($items->employee)? $items->employee->first_name.' '. $items->employee->last_name : '-' ) : '-' }}</td>
            <td>{{ ($items->assign_to != 0)? (!empty($items->assignTo)? $items->assignTo->first_name.' '. $items->assignTo->last_name : '-' ) : '-' }}</td>
            <td>{{$items->narration}}</td>
          </tr>
         @endforeach
        </tbody>
        <tfoot>
          <tr>
           <td><strong>Total</strong></td>
           <td><strong>Dr {{number_format($entries->dr_total,2)}}</strong></td>
           <td><strong>Cr {{number_format($entries->cr_total,2)}}</strong></td>
           <td></td>
           <td></td>
          </tr>
        </tfoot>
      </table>

         {{ nl2br($entries->notes) }}<br/>
                    Created at: {{ $entries->created_at }}<br/>
                    Created by: {{ $entries->user->username }}<br/><br/><br/>


    </main>
    <table style="border: none">
        <tr>
            <th style="border: none;padding:0;width: 33%">...................</th>
            <th style="border: none;padding:0;width: 33%">...................</th>
            <th style="border: none;padding:0;width: 33%">...................</th>
        </tr>
        <tr>
            <th style="border: none;padding:0;width: 33%">Prepared By</th>
            <th style="border: none;padding:0;width: 33%">Verified By</th>
            <th style="border: none;padding:0;width: 33%">Approved By</th>
        </tr>
    </table>
{{--    <footer>--}}
{{--      Document was created on Meronetwork ERP.--}}
{{--    </footer>--}}
</body></html>
