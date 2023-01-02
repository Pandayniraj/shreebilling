<table id="example-advanced">
    <thead>
    <?php

    ?>
    <tr><th  style="background-color:#F2BB66;" colspan="12" align="center"><b>VoucherType: {{$entry->entrytype->name}}<b></th></tr>
    <tr><th style="background-color: #89C35C;"colspan="12" align="center"><b>VoucherNumber:  {{$entry->number}}</b></th></tr>
    <tr><th style="background-color: #F2BB66;" colspan="12" align="center"><b>Date: {{$entry->date}}</b></th></tr>

    <tr>
        <th>Dr/Cr</th>
        <th style="width: 40px"><h4>Ledger</h4></th>
        <th>Class</th>
        <th>Theme</th>
        <th>Category</th>
        <th>Cost Center</th>
        <th>Sof</th>
        <th>PA Activity</th>
        <th>Activity</th>
        <th>Staff</th>
        <th style="width: 10px"><h4>Dr Amount</h4></th>
        <th style="width: 10px"><h4>Cr Amount</h4></th>
        <th style="width: 50px"><h4>Narration</h4></th>
    </tr>
    </thead>
    <tbody>

    @foreach($entryitems as $index=>$item)
        <tr>
            <td>@if($item->dc == 'D') Dr @else  Cr @endif</td>
            <td style="color: #FF0000;">{{$item->ledgerdetail->code}}</td>
            <td>{{$item->expClass->code}}
                <td>{{$item->expTheme->name}}</td>
                <td>{{$item->expCategory->code}}</td>
                <td>{{$item->expCostCenter->name}}</td>
                <td>{{$item->expSof->name}}</td>
                <td>{{$item->expPaActivity->name}}</td>
                <td>{{$item->expActivity->name}}</td>
                <td>{{$item->staffDetail->username}}</td>
            @if($item->dc == "D")
                <td >{{$item->amount}}</td>
                <td>-</td>
                @else
                <td >-</td>
                <td>{{$item->amount}}</td>
            @endif
            <td>{{$item->narration}}</td>
        </tr>

    @endforeach
        <tr>
            <td style colspan="10"><b>Total</b></td>
            <td>{{$entry->dr_total}}</td>
            <td>{{$entry->cr_total}}</td>
            <td></td>
        </tr>
    </tbody>
</table>
