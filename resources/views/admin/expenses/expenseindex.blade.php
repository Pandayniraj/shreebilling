
<table class="table table-hover table-bordered" id="clients-table">
<thead>
    <tr class="bg-maroon">
        
        <th>ID</th>
        @if(\Auth::user()->hasRole(['admins']))
        <th>User</th>
        @endif
        <th>Date</th>
         <th>BS Date</th>
        <th>Expenses Account</th>
         <th> Tags</th>
        <th>Paid Through</th>
       
        <th>Amount</th>

        <!--  <th>Actions</th> -->
    </tr>
</thead>
<tbody>
    @foreach($clients as $client)
    <tr>
       
        <td><b>{{\FinanceHelper::getAccountingPrefix('EXPENSE_PRE')}}{{$client->id}}</b></td>
        @if(\Auth::user()->hasRole(['admins']))
        <td>{{ ($client->user->first_name ??'' ) .' ' .($client->user->last_name??'')}}</td>
        @endif
        <td  >{!! date('d M y', strtotime($client->date)) !!} </td>
        <td>
            {{  TaskHelper::getNepaliDate($client->date) }}
        </td>
        <td title="{{$client->ledger->name}}" >
            <a href="/admin/expenses/{{ $client->id }}">
                {{ mb_substr($client->ledger->name,0,16)}}.
            </a>
        </td>
        <td>{{ $client->tag->name ?? '' }}</td>

        <td >{!! mb_substr($client->paidledger->name,0,15) !!}.</td>
      
        <td >{!! $client->amount !!}</td>
    

    </tr>
    @endforeach
</tbody>
</table>
