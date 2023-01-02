<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>{{ \Auth::user()->organization->organization_name }}</title>
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

table .foot {
  font-size: 1em;
  background: #DCDCDC;
}
table thead th {
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
            <tr>
        <td width="50%" style="float:left">
          <div id="logo">
            <img style="" src="{{ public_path('org/'.\Auth::user()->organization->logo) }}">
          </div>
        </td>
        <td width="50%" style="text-align: right">
        <div  id="company">
        <div style="font-size:16px;font-weight:bold">{{ \Auth::user()->organization->organization_name }} </div>
          <div>{{ \Auth::user()->organization->address }}</div>
          <div>PAN: {{ \Auth::user()->organization->vat_id }}</div>
          @if(\Auth::user()->organization->website)
            <div>Website : {{ \Auth::user()->organization->website }}</div>
          @endif
          <div><a href="mailto:{{ env('APP_EMAIL') }}">{{ \Auth::user()->organization->email }}</a></div>
          @yield('date')
        </div>
        </td></tr>

      </table> 

    </header>
 
      <div id="details" class="clearfix">
      </div>
      <div style="font-size:14px;font-weight:bold">{{$report_title}}</div>
      @yield('content')
      <br>
    
    <footer>
      {{ ucwords(str_replace("_", " ", ucfirst($ord->purchase_type)))}} was created on MEROCRM.<br>
            <span style="text-align: right !important;float: right;">Report Generated On:- {{date('Y/m/d H:i:s')}}</span>
    </footer>

  </body></html>