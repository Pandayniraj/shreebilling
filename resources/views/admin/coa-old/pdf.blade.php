<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<style type="text/css">

table.table.table-condensed {
    border: 1px solid black;
}
.borderpart td,.borderpart th{

  border: 1px solid grey;

}
.titlepart th{
  text-align: center;
}
.headings th,.headings td{


  margin: 0px;
  height:-10px;

}
table td,table th{
   font-size: 12px;
}
</style>
<body>
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />

<div class="container">

  <table style="width: 100%;">



  <tr>
         <th ></th>
         <th colspan="4"><h4>{{ \Auth::user()->organization->organization_name }}</h4></th>
         <th colspan="3"></th>
       </tr>

        <tr>
         <th></th>
         <td colspan="4">Address : {{ \Auth::user()->organization->address }}</td>
         <th colspan="3"></th>
       </tr>
      <tr>
         <th></th>
         <th colspan="4">{{ $ledgers_data->name }}</th>
         <th colspan="3" style="font-size: 10px;"></th>
       </tr>
      <tr>

         <th></th>
         <th colspan="4">Address: @if($ledgers_data->clients) Address : {{ $ledgers_data->clients->location  }} @else  --- @endif</th>
         <th colspan="3" style="font-size: 10px;">


          </th>
       </tr>
        <tr>
         <th></th>
         <td colspan="5" style="font-size: 12px;">Transaction From :   @if($startdate && $enddate)
             {{ date('d-M-Y',strtotime($startdate)) }} [{{TaskHelper::getNepaliDate($startdate)}}] To {{ date('d-M-Y',strtotime($enddate)) }} [{{TaskHelper::getNepaliDate($enddate)}}]
            @endif</td>
         <th colspan="3"></th>
       </tr>
      </table>
  <table class="table-condensed">
      <thead class="headings">

      <tr style="background-color: #337AB7;color: white;" class="titlepart">
        <th style="white-space: nowrap;">Date</th>
        <th style="white-space: nowrap;">Miti</th>
        <th style="white-space: nowrap;">Ref .</th>
        <th style="white-space: nowrap;">Description</th>
        <th style="white-space: nowrap;">Type</th>
        <th style="white-space: nowrap;">Tag</th>
        <th style="white-space: nowrap;">DR Amount</th>
        <th style="white-space: nowrap;">CR Amount</th>
        <th style="white-space: nowrap;">Balance</th>

      </tr>
      </thead>
     <tbody class='borderpart'>
       <?php
            $entry_balance['amount'] = $ledgers_data['op_balance'];
            $entry_balance['dc'] = $ledgers_data['op_balance_dc'];

             ?>
      @foreach($entry_items as $ei)
      <?php

            $entry_balance = TaskHelper::calculate_withdc($entry_balance['amount'], $entry_balance['dc'],
            $ei['amount'], $ei['dc']);

            $getledger= TaskHelper::getLedger($ei->entry_id);
            ?>
      <tr>
        <td style="white-space: nowrap;">{{ $ei->entry->date }}</td>
        <td style="white-space: nowrap;">{{ TaskHelper::getNepaliDate( $ei->entry->date ) }}</td>
        <td style="white-space: nowrap;">{{ $ei->entry->number }}</td>
        @php $getEntryType = $ei->entry->getEntryType();    @endphp
        <td>{{TaskHelper::getLedger($ei->id)}} \ {{  $getEntryType['type']}} No. [{{ $getEntryType['order']->bill_no }}]</td>
          <td>{{$ei->entry->entrytype->name??''}}</td>
          <td>
					    		<span class="tag" style="color:#f51421;">
					    			<span style="color: #f51421;">
					    				{{$ei->entry->tagname->title}}
					    			</span>
					    	    </span>
          </td>
           @if($ei->dc=='D')
                <td style="white-space: nowrap;">Dr {{$ei->amount}}</td>
                <td>-</td>
              @else
                <td>-</td>
                <td style="white-space: nowrap;">Cr {{$ei->amount}}</td>
              @endif
        <td>@if($entry_balance['dc']=='D') Dr @else Cr @endif {{number_format($entry_balance['amount'],2)}}</td>

      </tr>
      @endforeach
     </tbody>
     <tfoot class="borderpart">
       <tr>
         <th colspan="3"></th>
         <th style="white-space: nowrap;">Total Transaction</th>
         <th ></th>
         <th ></th>

         <th style="white-space: nowrap;">Dr{{ number_format($entry_items->where('dc','D')->sum('amount')) }}</th>
         <th style="white-space: nowrap;">Cr {{ number_format($entry_items->where('dc','C')->sum('amount')) }}</th>
         <th style="white-space: nowrap;">@if($entry_balance['dc']=='D') Dr @else Cr @endif {{ number_format($entry_balance['amount'],2)}}</th>
       </tr>
     </tfoot>
  </table>
</div>
</body>
</html>
