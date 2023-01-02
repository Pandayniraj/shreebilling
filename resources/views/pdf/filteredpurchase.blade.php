<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ env('APP_COMPANY')}} | Purchase Order</title>
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
            background: ;
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

        table td h3 {
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

        table .qty {}

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

        #thanks {
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices {
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

</head>
<body>
    <header class="clearfix">
       <table>
            <tr>
                <td width="50%" style="float:left">
                    <div id="logo">
                        <img style="max-width: 150px" src="{{env('APP_URL')}}/{{ '/org/'.$organization->logo }}">
                    </div>
                </td>
                <td width="50%" style="text-align: right">
                    <div id="company">
                        <h4 class="name">{{ \Auth::user()->organization->organization_name }} </h4>
                        <div>{{ \Auth::user()->organization->address }}</div>
                        <div>PAN: {{ \Auth::user()->organization->vat_id }}</div>
                        <div><a href="mailto:{{ \Auth::user()->organization->email }}">{{ \Auth::user()->organization->email }}</a></div>
                    </div>
                </td>
            </tr>

        </table>

        </div>
    </header>

    <main>

      <table style="width:100%; text-align:center; padding: 30px 0; box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); -webkit-box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px;">

          <thead>
              <tr>
                  <td colspan="9" style="text-align: right;">Fiscal Year: {{$fiscal_year}}</td>
              </tr>
              <tr>
                  <td colspan="9" style="text-align: right;">
                    @if($months) Month: {{$months}} 
                    @else
                    Date: {{ date('dS M y', strtotime($startdate)) }} - {{ date('dS M y', strtotime($enddate)) }}
                    @endif</td> 
              </tr>

              <tr>

              <th colspan="3" style="text-align: center; background-color: #eee" >Invoice</th>
              <th colspan="5"></th>
              <th colspan="2" style="text-align: center;  background-color: #eee">Taxable Purchase</th>
          
          </tr>
          <tr>
                  <th>Date</th>
              <th>Bill No</th>
              <th>Supplier’s Name</th>
              <th>Supl. PAN Number</th>
              <th>Total Purchase</th>
              <th>Non Tax Purchase</th>
              <th>Exp. Purchase</th>
              <th>Discount</th> 
              <th>Amount</th>
              <th>Tax(Rs)</th>

          </tr>
      </thead>
      <tbody>
        <?php  
        $taxable_amount = 0;
        $tax_amount = 0;
        ?>
          @foreach($purchase_book as $pur_bks)
      <tr>
            <td>{{ date('dS M y', strtotime($pur_bks->ord_date)) }}<br/>
                                    <?php
                                              $temp_date = explode(" ",$pur_bks->ord_date );
                                              $temp_date1 = explode("-",$temp_date[0]);
                                              $cal = new \App\Helpers\NepaliCalendar();
                                              //nepali date
                                              $a = $temp_date1[0];
                                              $b = $temp_date1[1];
                                              $c = $temp_date1[2];
                                              $d = $cal->eng_to_nep($a,$b,$c);
                                          
                                               $nepali_date = $d['date'].' '.$d['nmonth'] .', '.$d['year'];
                                              ?>

                               <small> {!! $nepali_date !!}</small>

                              </td>
          <td>#{{$pur_bks->bill_no}} </td>
          <td>{{$pur_bks->client->name}}</td>
              <td>{{$pur_bks->client->vat}}</td>
          <td>{{$pur_bks->taxable_amount + $pur_bks->tax_amount}}</td>
          <td></td>
          <td></td>
          <td></td>
          <td>{{$pur_bks->taxable_amount}}</td>
          <?php  
            $taxable_amount = $taxable_amount + $pur_bks->taxable_amount;
            $tax_amount = $tax_amount +  $pur_bks->tax_amount;
          ?>
       <td>{{$pur_bks->tax_amount}}</td>
      </tr>
      @endforeach
      <tr>
          <th>Total Amount</th>
          <td></td>
           <td></td>
            <td></td>
             <td></td>
              <td></td>
               <td></td>
                <td></td>
          <td>{{$taxable_amount}}</td>
          <td>{{$tax_amount}}</td>
          </tr>



          </tbody>
      </table>

      <hr>
      <p style="text-align: center;">Sent from MEROCRM</p>
  </main>
</body>
</html>