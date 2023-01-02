<table style="border: 1px solid; text-align:center">
    <thead>
        <tr>
            <th colspan="16" style="text-align:center; font-size:16px font-weight:bold;">खरिद खाता</th>
        </tr>
        <tr>
            <th colspan="16" style="text-align:center; font-size:16px font-weight:bold;">(नियम २३ को उपनियम (१) को खण्ड  (छ) संग सम्बन्धित )</th>
        </tr>
        <tr>
            <th colspan="16" style="text-align:center; font-size:16px font-weight:bold;"></th>
        </tr>
        <tr>
            <th colspan="16" style="border: 1px solid; text-align:center; font-size:16px font-weight:bold;">करदाता दर्ता नं(PAN):603413805  करदाताको नाम:डि. टेक ट्रेडिंग  साल:  कर अवधि:</th>
        </tr>
        <tr>
            <th colspan="8" style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">बीजक/प्रज्ञापनपत्र नम्बर</th>
            <th rowspan="2" style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">जम्मा खरिद मूल्य (रु)</th>
            <th rowspan="2" style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898; ">कर छुट हुने वस्तु वा सेवाको खरिद / पैठारी मूल्य (रु)</th>
            <th colspan="2" style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">करयोग्य खरिद (पूंजीगत बाहेक)</th>
            <th colspan="2" style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">करयोग्य पैठारी (पूंजीगत बाहेक)</th>
            <th  colspan="2" style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">पूंजीगत करयोग्य खरिद / पैठारी</th>
        </tr>
        <tr>
            <th style="border: 1px solid; text-align:center; font-size:12px; background-color:#999898;">मिति</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">बीजक नं</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">प्रज्ञापनपत्र नं</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">आपूर्तिकर्ताको नाम</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">आपूर्तिकर्ताको स्थायी लेखा नम्बर</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">खरिदरपैठारी गरिएका वस्तु वा सेवाको विवरण</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">खरिदरपैठारी गरिएका वस्तु वा सेवाको परिमाण</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">खरिदरपैठारी गरिएका वस्तु वा सेवा मापन गर्ने इकाइ</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">मूल्य (रु)</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">कर (रु)</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">मूल्य (रु)</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">कर (रु)</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">मूल्य (रु)</th>
            <th style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">कर (रु)</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalpurchaseamount=0;
            $totalnontaxpurchase=0;
            $total_taxable_amount=0;
            $total_non_taxable_amount=0;
        @endphp
        @foreach ($data as $item)
        <tr>
            <td style="border: 1px solid; text-align:center;">{{$item['billdate']}}</td>
            <td style="border: 1px solid; text-align:center;" >{{$item['Bill Num']}}</td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;">{{$item['Supplier’s Name']}}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['Supl. PAN Number']}}</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;"></td>
            <td style="border: 1px solid; text-align:center;">{{$item['Total Purchase']}}</td>
            <td style="border: 1px solid; text-align:center;">{{ $item['Non Tax Purchase'] }}</td>
            <td style="border: 1px solid; text-align:center;">{{$item['Amount']}}</td>
            <td style="border: 1px solid; text-align:center;">{{ $item['Tax(Rs)'] }}</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
            <td style="border: 1px solid; text-align:center;">-</td>
        </tr>
        @php
        $totalpurchaseamount+=$item['Total Purchase'];
        $totalnontaxpurchase+=$item['Non Tax Purchase'];
        $total_taxable_amount+=$item['Amount'];
        $total_non_taxable_amount+=$item['Tax(Rs)'];
        @endphp
        @endforeach
        <tr>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;" colspan="8">कल मूल्य</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{$totalpurchaseamount}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{ $totalnontaxpurchase}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{$total_taxable_amount}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;">{{$total_non_taxable_amount}}</td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;"></td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;"></td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;"></td>
            <td style="border: 1px solid; text-align:center; font-size:12px;  background-color:#999898;"></td>
        </tr>

    </tbody>
</table>
