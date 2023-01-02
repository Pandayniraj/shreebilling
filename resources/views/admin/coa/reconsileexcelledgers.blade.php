 <table class="table table-bordered">
     <thead>
        <tr><th  style="background-color:#F2BB66;" colspan="9" align="center"><b>Company PAN: {{$pan}}<b></th></tr>
        <tr><th style="background-color: #89C35C;"colspan="9" align="center"><b>Company Address:  {{$address}}</b></th></tr>
        <tr><th style="background-color: #F2BB66;" colspan="9" align="center"><b>Company Name: {{$name}}</b></th></tr>
        <tr><th style="background-color: #89C35C;" colspan="9" align="center"><b>Balance Statement From : {{$start_date}} To {{$end_date}} </b></th></tr>

         <tr>
          <th colspan="8" style="background-color:#1b95ff;">Current opening balance</th>
          <th style="background-color:#1b95ff;">@if($ledgers_data->op_balance_dc == 'D') Dr @else Cr @endif  {{$ledgers_data->op_balance}}</th>
        </tr>

         <tr >
            <th style="background-color:#FF851B;" align="center">Date</th>
             <th style="background-color:#FF851B;" align="center">Reconcile Date</th>
            <th style="background-color:#FF851B;" align="center">Ref.</th>
            <th style="background-color:#FF851B;" align="center">Description</th>
            <th style="background-color:#FF851B;" align="center">Type</th>
            <th style="background-color:#FF851B;" align="center">Tag</th>
            <th style="background-color:#FF851B;" align="center">Dr.({{env(APP_CURRENCY)}})</th>
            <th style="background-color:#FF851B;" align="center">Cr.({{env(APP_CURRENCY)}})</th>
            <th style="background-color:#FF851B;" align="center">Balance({{env(APP_CURRENCY)}})</th>
           
          </tr>
     </thead>
    <tbody>
                <?php
                /* Current opening balance */
                $entry_balance['amount'] = $ledgers_data['op_balance'];
                $entry_balance['dc'] = $ledgers_data['op_balance_dc'];
                
                 ?>
              @foreach($entry_items as $ei)

               <?php 

                $entry_balance = \App\Helpers\TaskHelper::calculate_withdc($entry_balance['amount'], $entry_balance['dc'],
                $ei['amount'], $ei['dc']);

                $getledger= \App\Helpers\TaskHelper::getLedger($ei->entry_id);
                ?>
              <tr>
                  <td style="background-color: #FFFF00;">{{$ei->entry->date}}</td>
                  <td  style="background-color: #FFFF00  ;">{{$ei->reconciliation_date}}</td>
                  <td style="background-color: #FFFF00;">{{$ei->entry->number}}</td>
                  <td style="background-color: #FFFF00;">{{$getledger}}</td>
                  <td style="background-color: #FFFF00;">{{$ei->entry->entrytype->name}}</td>
                  <td style="background-color: #FFFF00;"><span class="tag" style="color:#f51421; background-color:#gba(17;">
                    <span style="color: #f51421;">{{$ei->entry->tagname->title}}</span>
                      </span>
                  </td>
                  @if($ei->dc=='D')
                    <td style="background-color: #FFFF00  ;">Dr {{$ei->amount}}</td>
                    <td style="background-color: #FFFF00  ;">-</td>
                  @else
                    <td style="background-color: #FFFF00  ;">-</td>
                    <td style="background-color: #FFFF00  ;">Cr {{$ei->amount}}</td>
                  @endif

                  <td style="background-color: #FFFF00;">@if($entry_balance['dc']=='D') Dr @else Cr @endif {{number_format($entry_balance['amount'],2)}}</td>
               

                </tr>
              @endforeach
              <tr>
                <td colspan="8" style="background-color: #1b95ff;">Current closing balance</td>
                <td style="background-color: #1b95ff;">@if($entry_balance['dc']=='D') Dr @else Cr @endif {{ number_format($entry_balance['amount'],2)}}</td>
              </tr>
    </tbody>
  </table>  