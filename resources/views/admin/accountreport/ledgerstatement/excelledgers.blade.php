 <table>
     <thead>
     <tr>
         <th colspan="12" align="center">
             <b>{{env('APP_COMPANY')}}</b>
         </th>
     </tr>
     <tr>
         <th colspan="12" align="center">
             <b>{{\Auth::user()->organization->address}}</b>
         </th>
     </tr>
     <tr>
         <th colspan="12" align="center">
             <b>Ledger Statement</b>
         </th>
     </tr>
     <tr>
         <th colspan="12" align="center">
             <strong>[{{$ledgers_data->code??''}}] {{$ledgers_data->name??''}}</strong>
         </th>
     </tr>
     <tr>
         <th colspan="12" align="center">
             <strong>Transaction Date&nbsp;from&nbsp;{{date('d M Y', strtotime($start_date))}}&nbsp;to&nbsp;{{ date('d M Y', strtotime($start_date))}} </strong>
         </th>
     </tr>
     <tr><td colspan="12"></td></tr>
     <tr>
         <th colspan="12">
             <b>Date: {{date('d M Y')}}</b>
         </th>
     </tr>

     <tr><td colspan="12"></td></tr>

     <tr>
         <td colspan="12">Opening balance as on&nbsp;<strong> {{date('d M Y', strtotime($start_date))}}</strong>:
             &nbsp;&nbsp;&nbsp;<strong>@if($opening_balance['dc']=='D') Dr @else Cr @endif{{is_numeric($opening_balance['amount']) ?  round($opening_balance['amount'],2) : '-'}}</strong></td>

        </tr>
     <tr>
         <?php
         $closing_balance =TaskHelper::getFinalLedgerBalance($ledgers_data->id,$opening_balance,$start_date,$end_date);

         ?>
         <td colspan="12">Closing balance as on <strong> {{date('d M Y', strtotime($end_date))}}</strong>:
             &nbsp;&nbsp;&nbsp;<strong>@if($closing_balance['dc']=='D') Dr @else Cr @endif{{is_numeric($closing_balance['amount']) ?  round($closing_balance['amount'],2) : '-'}}</strong></td>

     </tr>
     <tr><td colspan="12"></td></tr>


        <tr >
            <th style="border:1px solid;font-weight:bold;background-color:#1b95ff;">Date</th>
            <th style="border:1px solid;font-weight:bold;background-color:#1b95ff;">Miti</th>
            <th style="border:1px solid;font-weight:bold;background-color:#1b95ff;">Ref.</th>
            <th style="border:1px solid;font-weight:bold;background-color:#1b95ff;">Bill No.</th>
            <th style="border:1px solid;font-weight:bold;background-color:#1b95ff;width: 20%">Description</th>
            <th style="border:1px solid;font-weight:bold;background-color:#1b95ff;width: 20%">Narration</th>
            <th style="border:1px solid;font-weight:bold;background-color:#1b95ff;">Cheque No.</th>
            <th style="border:1px solid;font-weight:bold;background-color:#1b95ff;">Type</th>
            <th style="border:1px solid;font-weight:bold;background-color:#1b95ff;">Tag</th>
            <th style="border:1px solid;font-weight:bold;background-color:#1b95ff;">Dr Amount</th>
            <th style="border:1px solid;font-weight:bold;background-color:#1b95ff;">Cr Amount</th>
            <th style="border:1px solid;font-weight:bold;background-color:#1b95ff;">Balance</th>
        </tr>
     </thead>
    <tbody>

    <?php
    /* Current opening balance */
    $entry_balance['amount'] = $opening_balance['amount'] ?? '';
    $entry_balance['dc'] = $opening_balance['dc'] ??'';
    $dr_total=0;
    $cr_total=0;
    ?>

    @foreach($entry_items as $ei)
        <?php

        $entry_balance = TaskHelper::calculate_withdc($entry_balance['amount'], $entry_balance['dc'],
            $ei['amount'], $ei['dc']);

        $getledger= \TaskHelper::getLedger($ei->entry_id);
        ?>

        <tr>
            <td >{{$ei->dynamicEntry()->date}}</td>
            <td >{{ \TaskHelper::getNepaliDate($ei->dynamicEntry()->date) }}</td>
            <td>{{   $ei->dynamicEntry()->number}}</td>
            <td>{{ $ei->dynamicEntry() ?  $ei->dynamicEntry()->dynamicBillNum() : '-'  }}</td>
            @php $getEntryType = $ei->dynamicEntry()->getDynamicEntryType();    @endphp
            <td style="width: 20%">{{$getledger}} \ {{  $getEntryType['type']}} No. [{{ $getEntryType['order']->bill_no }}]
            </td>
            <td style="width: 20%">{{$ei->narration}}
            </td>
            <td>{{$ei->cheque_no??'-'}}</td>
            <td>{{$ei->dynamicEntry()->entrytype->name??''}}</td>
            <td>
					    		<span class="tag" style="color:#f51421;">
					    			<span style="color: #f51421;">
					    				{{$ei->dynamicEntry()->tagname->title}}
					    			</span>
					    	    </span>
            </td>
            @if($ei->dc=='D')
                <td>{{round($ei->amount,2)}}</td>
                <td></td>
            @else
                <td></td>
                <td>{{round($ei->amount,2)}}</td>
            @endif

            <td>@if($entry_balance['dc']=='D') Dr @else Cr @endif
                {{
                is_numeric($entry_balance['amount']) ? round($entry_balance['amount'],2) : '-'  }}</td>
        </tr>
        {!! $cr_total+=$ei->dc=='C'?$ei->amount:0 !!}
        {!! $dr_total+=$ei->dc=='D'?$ei->amount:0 !!}
    @endforeach
              <tr>
                <th colspan="9" style="text-align: right;font-weight:bold;background-color:#1b95ff;">Total</th>
                  <th style="font-weight:bold;background-color:#1b95ff;">{{round($dr_total,2)}}</th>
                  <th style="font-weight:bold;background-color:#1b95ff;">{{round($cr_total,2)}}</th>
{{--                  <th style="background-color:#1b95ff;"></th>--}}
                <td style="background-color: #1b95ff;font-weight: bold">@if($entry_balance['dc']=='D') Dr @else Cr @endif {{ round($entry_balance['amount'],2)}}</td>
              </tr>
    </tbody>
  </table>
