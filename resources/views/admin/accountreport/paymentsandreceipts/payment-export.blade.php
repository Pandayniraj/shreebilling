<table>
    <thead>
    <tr>
        <th  align="center" >
            <b>{{env('APP_COMPANY')}}</b>
        </th>
    </tr>
    <tr>
        <th  style="text-align: center;" align="center" >
            <b>{{\Auth::user()->organization->address}}</b>
        </th>
    </tr>
    <tr>
        <th style="text-align: center;" align="center"  >
            <b>{{$excel_name}}</b>
        </th>
    </tr>
    <tr>
        <th style="text-align: center;" align="center"  >
            <b>From {{$start_date}} to {{$end_date}}</b>
        </th>
    </tr>
    <tr></tr>
    <tr>
        <th >
            <b>Date: {{date('d M Y')}}</b>
        </th>
    </tr>

    </thead>
</table>


<table>
    <thead>
    <tr ><th style="font-weight: bold;border: 1px solid black;">Num</th>
        <th style="font-weight: bold;border: 1px solid black;">Date/Miti</th>
        <th style="font-weight: bold;border: 1px solid black;">Bill #</th>
        <th style="font-weight: bold;border: 1px solid black;max-width: 150px">Party</th>

        <th style="font-weight: bold;border: 1px solid black;">Cash/Bank</th>
        <th style="font-weight: bold;border: 1px solid black;">Type</th>
        {{--                        <th>Entry Source</th>--}}
        <th style="font-weight: bold;border: 1px solid black;">Amount</th>


    </tr>
    </thead>
    <tbody>
    <?php
    $total=0
    ?>
    @foreach($entryitems as $ei)
        <tr>
            <td style="border: 1px solid black">{{$ei->dynamicEntry()->number}}</td>
            <td  style="border: 1px solid black">{{$ei->dynamicEntry()->date}}/
                <div style="color: grey">
                    {{ \TaskHelper::getNepaliDate($ei->dynamicEntry()->date) }}
                </div></td>
            <td style="border: 1px solid black">{{ $ei->dynamicEntry()->dynamicSessionBillNum() }}</td>

            <td  style="border: 1px solid black;max-width: 150px">
                <?php
                $vendor=\TaskHelper::getDynamicEntryLedgerName($ei->ledger_id);
                ?>
                {{$vendor->name}}
                    @if($ei->narration)
                <div style="color: gray;font-size: 11px"> &nbsp;({{$ei->narration}})</div>
                        @endif
            </td>
            <td style="border: 1px solid black"><?php
                $corresponding_eis=$ei->dynamicEntry()->dynamicEntryItems()->sortByDesc('id')->where('dc',request('type')=='receipt'?'D':'C');
                $bank_or_cash_ei='';
                foreach ($corresponding_eis as $ei){
                    if (in_array($ei->ledger_id,$bank_cash_ledger_ids)){
                        $bank_or_cash_ei=$ei;
                        break;
                    }
                }

                ?>
                {{$bank_or_cash_ei?$bank_or_cash_ei->dynamicLedgerDetail()->name:'XXX'}}
            </td>

            <td style="border: 1px solid black">
                {{$ei->dynamicEntry()->entrytype->name}}@if($ei->dynamicEntry()->tagname) /
                <div>
                    <span class="label {{$ei->dynamicEntry()->tagname->color}}">{{$ei->dynamicEntry()->tagname->title}}</span>
                </div>
                                                            @endif
            </td>
            <td style="border: 1px solid black">
                {{ $ei->dynamicEntry()->currency}} {{round($ei->amount,2)}}
            </td>


        </tr>
        <?php
        $total+=$ei->amount;
        ?>
    @endforeach
    <tr>
        <th colspan="6" style="text-align: right;font-weight: bold;border: 1px solid black">Total</th>
        <th style="font-weight: bold;border: 1px solid black">{{round($total,2)}}</th>
    </tr>
    </tbody>
</table>
