<table>
    <thead>
    <tr>
        <th colspan="12" align="center">
            <b>{{env('APP_COMPANY')}}</b>
        </th>
    </tr>
    <tr>
        <th  style="text-align: center;" align="center" colspan="12">
            <b>{{\Auth::user()->organization->address}}</b>
        </th>
    </tr>
    <tr>
        <th style="text-align: center;" align="center"  colspan="12">
            <b>Expenses List</b>
        </th>
    </tr>
    <tr></tr>
    <tr>
        <th colspan="12">
            <b>Report Date: {{date('d M Y')}}</b>
        </th>
    </tr>
    </thead>
</table>

<table class="table table-hover table-bordered" id="clients-table">
<thead>
    <tr class="bg-maroon">

        <th style="font-weight: bold;border:1px solid black">ID</th>
        @if(\Auth::user()->hasRole(['admins']))
        <th style="font-weight: bold;border:1px solid black">User</th>
        @endif
        <th style="font-weight: bold;border:1px solid black">Date</th>
         <th style="font-weight: bold;border:1px solid black">BS Date</th>
        <th style="font-weight: bold;border:1px solid black">Voucher No.</th>
        <th style="font-weight: bold;border:1px solid black">Bill No.</th>
        <th style="font-weight: bold;border:1px solid black">Expenses Account</th>
         <th style="font-weight: bold;border:1px solid black"> Tags</th>
        <th style="font-weight: bold;border:1px solid black">Paid Through</th>

        <th style="font-weight: bold;border:1px solid black">Taxable/Non Taxable Amt</th>
        <th style="font-weight: bold;border:1px solid black">Tax Amt</th>
        <th style="font-weight: bold;border:1px solid black">Amount</th>

        <!--  <th>Actions</th> -->
    </tr>
</thead>
<tbody>
<?php
    $total_taxable=0;
    $total_tax=0;
    $total=0;
    ?>
    @foreach($clients as $client)
    <tr>

        <td style="border:1px solid black"><b>{{\FinanceHelper::getAccountingPrefix('EXPENSE_PRE')}}{{$client->id}}</b></td>
        @if(\Auth::user()->hasRole(['admins']))
        <td style="border:1px solid black">{{ ($client->user->first_name ??'' ) .' ' .($client->user->last_name??'')}}</td>
        @endif
        <td  style="border:1px solid black" >{!! date('d M y', strtotime($client->date)) !!} </td>
        <td style="border:1px solid black">
            {{  TaskHelper::getNepaliDate($client->date) }}
        </td>
        <td style="border:1px solid black">
            {{$client->entry->number}}
        </td>
        <td style="border:1px solid black">
            {{$client->bill_no}}
        </td>
        <td style="border:1px solid black">
           {{$client->ledger->name}}
        </td>
        <td style="border:1px solid black">{{ $client->tag->name ?? '' }}</td>

        <td style="border:1px solid black" >{!! $client->paidledger?mb_substr($client->paidledger->name,0,15).'..':'' !!}</td>
        <td style="border:1px solid black">{!! round($client->amount,2) !!}</td>
        <td style="border:1px solid black">{!! round($client->tax_amount,2) !!}</td>
        <td style="border:1px solid black">{!! round($client->amount+$client->tax_amount,2) !!}</td>
        <?php
        $total_taxable+=$client->amount;
        $total_tax+=$client->tax_amount;
        $total+=$client->amount+$client->tax_amount;
        ?>

    </tr>
    @endforeach
<tr>
    <td style="border:1px solid black;font-weight: bold;text-align: right" colspan="9" >Total</td>
    <td style="border:1px solid black;font-weight: bold">{{round($total_taxable,2)}}</td>
    <td style="border:1px solid black;font-weight: bold">{{round($total_tax,2)}}</td>
    <td style="border:1px solid black;font-weight: bold">{{round($total,2)}}</td>
</tr>
</tbody>
</table>
