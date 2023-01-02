<table id="example-advanced">
    <thead>
    <tr><th  style="background-color:#F2BB66;" colspan="11" align="center"><b>ReceiptType: {{$entry->entrytype->name}}<b></th></tr>
    <tr><th style="background-color: #89C35C;"colspan="11" align="center"><b>ReceiptEntry:  {{$entry->number}}</b></th></tr>
    <tr><th style="background-color: #F2BB66;" colspan="11" align="center"><b>Date: {{$entry->date}}</b></th></tr>
    <tr>
        <th>Dr/Cr</th>
        <th style="width: 40px"><h4>Ledger</h4></th>
        <th style="width: 10px"><h4>Dr Amount</h4></th>
        <th style="width: 10px"><h4>Cr Amount</h4></th>
        <th style="width: 10px"><h4>Cheque No.</h4></th>
        <th style="width: 50px"><h4>Employee</h4></th>
        <th style="width: 50px"><h4>Assign To</h4></th>
        <th style="width: 50px"><h4>Narration</h4></th>
    </tr>
    </thead>
    <tbody>

    @foreach($entryitems as $index=>$item)
        <tr>
            <td>@if($item->dc == 'D') Dr @else  Cr @endif</td>
            <td style="color: #FF0000;">{{$item->ledgerdetail->code}}</td>
            @if($item->dc == "D")
                <td >{{$item->amount}}</td>
                <td>-</td>
                @else
                <td >-</td>
                <td>{{$item->amount}}</td>
            @endif
            <td>{{$item->cheque_no??'-'}}</td>
            <td>{{ ($item->employee_id != 0)? (!empty($item->employee)? $item->employee->first_name.' '. $item->employee->last_name : '-' ) : '-' }}</td>
            <td>{{ ($item->assign_to != 0)? (!empty($item->assignTo)? $item->assignTo->first_name.' '. $item->assignTo->last_name : '-' ) : '-' }}</td>
            <td>{{$item->narration}}</td>
        </tr>

    @endforeach
        <tr>
            <td style colspan="2"><b>Total</b></td>
            <td>{{$entry->dr_total}}</td>
            <td>{{$entry->cr_total}}</td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
