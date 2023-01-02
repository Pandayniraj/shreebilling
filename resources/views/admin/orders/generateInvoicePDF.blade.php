<!DOCTYPE html>
<html lang="en">
<head>
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
            color: #349eeb;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .no {
            color: #FFFFFF;
            font-size: 1em;
            background: #349eeb;
        }

        table .desc {
            text-align: left;
        }

        table .unit {
            background: #DDDDDD;
        }

        table .qty {}

        table .total {
            background: #349eeb;
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
            color: #349eeb;
            font-size: 1em;
            border-top: 1px solid #349eeb;
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
            border-left: 6px solid #349eeb;
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

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

    </style>
</head>
<body>
    <header class="clearfix">
        <table>
            <tr>
                <td width="50%" style="float:left">
                    <div id="logo">
                        <img style="" src="{{ public_path()  }}{{ '/org/'.$organization->logo }}">
                    </div>
                </td>
                <td width="50%" style="text-align: right">
                    <div id="company">
                        <div style="font-size:16px;font-weight:bold">{{ \Auth::user()->organization->organization_name }} </div>
                        <div>{{ \Auth::user()->organization->address }}</div>
                        <div>Phone: {{ \Auth::user()->organization->phone }}</div>
                        <div>Seller's PAN:{{ \Auth::user()->organization->vat_id }}

                        </div>
                        <div><a href="mailto:{{ \Auth::user()->organization->email }}">{{ \Auth::user()->organization->email }}</a></div>
                    </div>
                </td>
            </tr>

        </table>

    </div>
</header>
<main>
    <div id="details" class="clearfix">

        <table>
            <TR>
                <TD width="50%" style="float:left">
                    <div id="client">
                        <div class="to">{{ ucwords($ord->source) }} {{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} To:</div>
                        <div style="font-weight:bold">{{ $ord->name }}</div>
                        <div class="name"> @if($ord->source=='client') {{ $ord->client->name }} @else {{ $ord->lead->name }} @endif</div>
                        <div class='email'>Email: @if($ord->source == 'lead'){{ $ord->lead->email }}@else {{ $ord->client->email }} @endif </div>
                        <div class="address">Position: {!! $ord->position !!}<br /></div>
                        <div class="address">Address: {!! nl2br($ord->address ) !!}<br /></div>
                        <div class=""> @if($ord->source == 'client') Customer's PAN: {!! $ord->client->vat !!} @endif</div>
                    </div>
                </TD>

                <TD width="50%" style="text-align: right">
                    <div id="invoice">
                        <div style="font-weight:bold">{{ ucwords($ord->source) }} {{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} @if($ord->order_type == 'quotation'){{\FinanceHelper::getAccountingPrefix('QUOTATION_PRE')}}@else{{\FinanceHelper::getAccountingPrefix('SALES_PRE')}}@endif{{ $ord->bill_no }}</div>
                        <div class="date">Date of {{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}}: {{ date("d/M/Y", strtotime($ord->bill_date )) }}</div>
                        <div class="date">Due Date: {{ date("d/M/Y", strtotime($ord->due_date )) }}</div>
                        <div>Issued By: {{ $ord->user->first_name}} {{ $ord->user->last_name}} </div>
                        <div>Terms: {{ $ord->terms }} </div>
                        <div>{{ ucwords($ord->source) }} Account:</b> #@if($ord->source == 'lead') {{ $ord->lead->id }} @else {{ $ord->client->id }}@endif</div>
                        <div>Source: {{ ucwords($ord->source) }}</div>
                    </div>
                </div>
            </TD>
        </TR>
    </table>
    <!-- /.row -->
    <p>Thank you for choosing {{ env('APP_COMPANY') }}. Your {{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} is detailed below. If you find errors or desire certain changes, please contact us.</p>
    <table cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th class="no">SN</th>
                <th class="no">PARTICULARS</th>
                <th class="no">QUANTITY</th>
                <th class="no">PRICE</th>

                <th class="no">UNIT</th>
                <th class="no">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $n= 0;
            ?>
            @foreach($orderDetails as $odk => $odv)
            <tr>
                <td>{{\FinanceHelper::getAccountingPrefix('PRODUCT_PRE')}}{{++$n}}</td>
                @if($odv->is_inventory == 1)
                <td>{{ $odv->product->name }}</td>
                @elseif($odv->is_inventory == 0)
                <td>{{ $odv->description }}</td>
                @endif
                <td>{{ $odv->quantity }}</td>
                <td>{{ env('APP_CURRENCY').' '.number_format($odv->price,2) }}</td>
                <td>{{ $odv->units->name }}</td>
                <td>{{ env('APP_CURRENCY').' '.number_format($odv->total,2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"></td>
                <td colspan="2">SubTotal</td>
                <td>{{ env('APP_CURRENCY').' '. number_format($ord->subtotal,2) }}</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td colspan="2">Discount Percentage(%)</td>
                <td>{{$ord->discount_percent }}%</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td colspan="2">Taxable Amount</td>
                <td>{{env('APP_CURRENCY').' '. number_format($ord->taxable_amount,2) }}</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td colspan="2">Tax Amount(13%)</td>
                <td>{{ env('APP_CURRENCY').' '. number_format($ord->tax_amount,2) }}</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td colspan="2">Total</td>
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
    @if($ord->order_type!='quotation')
    <div>
        <h3>EMI Payment Terms</h3>
        <table id="" class="table table-striped">
            <thead class="bg-gray">
                <tr>
                    <th>S.N.</th>
                    <th>Date</th>
                    <th >Condition</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($OrderPaymentTerms as $opk => $opv)

                <tr>
                    <td>{{ $opk+1 }}</td>
                    <td>{{ date('dS M, Y',strtotime($opv->term_date)) }}</td>
                    <td>{{ $opv->term_condition }}</td>
                    <td>{{ number_format($opv->term_amount,2) }}</td>
                    <td>
                        @if($opv->status == 1)
                        Paid
                        @else
                        Due
                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    <div id="notices">
        <div>Note:</div>
        <div class="notice"> {!! nl2br($ord->comment) !!}</div>
    </div>
    <div id=""> ___________________________________</div>
    <div id="">Authorized Signature</div>
    <div>
        <img style="width:250px;" src="{{ public_path()  }}{{ '/org/'.$organization->stamp }}">
    </div>
</main>
<footer>
    {{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} was created on MEROCRM.
</footer>
</body>
</html>
