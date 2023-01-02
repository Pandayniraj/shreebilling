<table>
    <thead>
        <tr>
            <th colspan="15" style="text-align:center; font-size:16px font-weight:bold;">बिक्री खाता</th>
        </tr>
        <tr>
            <th colspan="15" style="text-align:center; font-size:16px font-weight:bold;">(नियम २३ को उपनियम (१) को खण्ड  (छ) संग सम्बन्धित )</th>
        </tr>
        <tr>
            <th colspan="15" style="text-align:center; font-size:16px font-weight:bold;"></th>
        </tr>
        <tr>
            <th colspan="15" style="border: 1px solid; text-align:center; font-size:16px font-weight:bold;">करदाता दर्ता नं(PAN):603413805  करदाताको नाम:डि. टेक ट्रेडिंग  साल:  कर अवधि:</th>
        </tr>
        <tr>
            <th colspan="7" style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">बीजक</th>
            <th rowspan="2" style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">जम्मा बिक्री / निकासी (रु)</th>
            <th rowspan="2" style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898; ">स्थानीय कर छुटको बिक्री  मूल्य (रु)</th>
            <th colspan="2" style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">करयोग्य बिक्री </th>
            <th colspan="4" style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">निकासी</th>
        </tr>
        <tr>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">मिति</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">बीजक नं</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">खरिदकर्ताको नाम</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">खरिदकर्ताको स्थायी लेखा नम्ब</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">वस्तु वा सेवाको नाम</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">वस्तु वा सेवाको परिमाण</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">वस्तु वा सेवाको परिमाण मापन गर्ने इकाइ</th>

            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">मूल्य (रु)</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">कर (रु)</th>

            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">निकासी गरेको वस्तु वा सेवाको मूल्य (रु)</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">निकासी गरेको देश</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">निकासी प्रज्ञापनपत्र नम्बर</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">निकासी प्रज्ञापनपत्र मिति</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalsalesamount=0;
            $totalnontaxsales=0;
            $total_taxable_amount=0;
            $total_tax=0;
        @endphp
        @foreach ($data as $item)
        <tr>
            <td style="border: 1px solid; text-align:center;">{{$item['Bill Date']}}</td>
            <td style="border: 1px solid; text-align:center;" >{{$item['Bill No']}}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['Customer Name']}}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['Customer PAN']}}</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;">{{$item['Total Sales']}}</td>
            <td style="border: 1px solid; text-align:center;">{{ $item['Non Tax Sales'] }}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['Taxable Amount']}}</td>
            <td style="border: 1px solid; text-align:center;">{{ $item['Tax'] }}</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
        </tr>
        @php
        $totalsalesamount +=$item['Total Sales'];
        $totalnontaxsales+=$item['Non Tax Sales'];
        $total_taxable_amount+=$item['Taxable Amount'];
        $total_tax+=$item['Tax'];
        @endphp
        @endforeach
        <tr>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;" colspan="7">कल मूल्य</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{$totalsalesamount}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{ $totalnontaxsales}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{$total_taxable_amount}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{$total_tax}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;"></td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;"></td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;"></td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;"></td>
        </tr>

    </tbody>
</table>
