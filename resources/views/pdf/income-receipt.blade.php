<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<style type="text/css">
    .footer {
        text-align: left;
        position: relative;
        margin: 8px auto;
        padding: 0;
        width: 100%;
        height: auto;
        border-collapse: collapse;
        text-align: center;
    }

</style>
<body>
    <table>
        <tr>
            <td style="">
            <img style="" src="{{ public_path('org/'.Auth::user()->organization->logo) }}">
            </td>
            <th style="text-align:  left;">
                <h2>Receipt</h2>
            </th>
        </tr>
        <tr>
            <td rowspan="3" style="width: 200px;padding-right: 90px">
            <div style="font-size:16px;font-weight:bold">{{ \Auth::user()->organization->organization_name }} </div>
                        <div>{{ \Auth::user()->organization->address }}</div>
                        <div>Phone: {{ \Auth::user()->organization->phone }}</div>
                        
                Tax ID: {{ \Auth::user()->organization->vat_id }}
                <br><br>
                <strong>{{ $income->customers->name}}</strong><br>

                {{ $income->customers->location}}<br>
                {{ $income->customers->phone}}<br>
                {{ $income->customers->email}}<br>
            </td>
            <td valign="top">
                <table style="padding-right: 50px">
                    <tr>
                        <td style="white-space: nowrap;">Payment Date: </td>
                        <td>{{date('d M Y',strtotime($income->date_received))}}</td>
                    </tr>
                    <tr>
                        <td style="white-space: nowrap;">Reference No: </td>
                        <td>{{ $income->reference_no }}</td>
                    </tr>
                    <tr>
                        <td valign="top" style="white-space: nowrap;">Payment Method: </td>
                        <td>{{ ucfirst($income->received_via) }}</td>
                    </tr>
                    <tr>
                        <td valign="top" style="white-space: nowrap;">Payment #: </td>
                        <td>{{\FinanceHelper::getAccountingPrefix('INCOME_PRE')}}{{ $income->id }}</td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
    <table class="footer" cellspacing="0" style="">
        <tr>
            <th style="background-color: #f5f5f5; padding: 10px; border: 1px dotted black;text-align: left;text-align: left;text-indent: 3px" colspan="5">Description</th>
            <td style="padding: 10px; border: 1px dotted black;text-align: left;" colspan="5">{{ $income->description }}</td>
        </tr>
        <tr>
            <th style="background-color: #f5f5f5; padding: 10px; border: 1px dotted black;text-align: left;text-indent: 3px" colspan="5">Paid Amount</th>
            <td style="padding: 10px; border: 1px dotted black;text-align: left;text-indent: 3px" colspan="5"><strong>{{ env('APP_CURRENCY') }} {{ $income->amount }}</strong></td>
        </tr>
    </table>
    <?php
             $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
          ?>
            In Words: {{ $f->format($income->amount) }}
    <p> If you have any questions or concerns, please contact us. Thank You</p>
    Sincerely
    <p> {{ \Auth::user()->organization->organization_name }}</p>
</body>
</html>
