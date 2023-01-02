<table>
	<thead>
        <tr>
            <th colspan="9" align="center">
                <b>{!! config('restro.APP_COMPANY') !!}</b>
            </th>
        </tr>
        <tr>
            <th colspan="9" style="text-align: center;">
                <b>Address: {!! config('restro.APP_ADDRESS1') !!}</b>
            </th>
        </tr>
    
        <tr>
            <th colspan="9" style="text-align: center;">
                <b>Tel:{!! config('restro.APP_PHONE1') !!} | Email: {!! config('restro.APP_ADDRESS2') !!}</b>
            </th>
        </tr>
    
        <tr>
            <th colspan="9" style="text-align: center;">
                <b>Website: {!! config('restro.WEBSITE') !!}</b>
            </th>
        </tr>

        <tr>
            <th colspan="9"></th>
        </tr>

		<tr>
			<th colspan="9" style="text-align: center; font-weight: bold; font-size: 15px; color: black; background-color: #00B050; border: 1px solid black;">
				{{ $title }}
			</th>
		</tr>
		<tr>
			<th colspan="9" style="text-align: center; font-weight: bold; font-size: 10px; color: black; background-color: #C5D9F1; border: 1px solid black;">
				 From Date : {{$start_date}}  &nbsp;&nbsp;&nbsp; To Date : {{$end_date}}
			</th>
		</tr>
		<tr>
        	<th colspan="1" style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black;">
			S.N
            </th>
			<th colspan="1" style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; background-color: #00B0F0;">
				Transcation Date
			</th>
			<th colspan="1" style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; background-color: #FFC000;">
				Trans Type
			</th>
			<th colspan="1" style="text-align: center; font-weight: bold; font-size: 10px; color:black; border: 1px solid black; background-color: #92D050;">
				Transcation No
			</th>
			<th colspan="1" style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; background-color: #4BACC6;">
				Legder
			</th>
            <th colspan="1" style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; background-color: #405cbb;">
                Narration
            </th>
			<th colspan="1" style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; background-color: #F79646;">
				Debit Amount
			</th>
            <th colspan="1" style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; background-color: #F79646;">
				Credit Amount
			</th>
			<th colspan="1" style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; background-color: #F2DCDB;">
				Source
			</th>

		</tr>
	</thead>
    <tbody>
@php
    $total_dr_amount=0;
    $total_cr_amount=0;
@endphp
        @foreach ($entries as $entry)
            <tr>
                <td style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; " >{{$loop->index+1}}</td>
                <td style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; ">{{ $entry->date }}</td>
                <td style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; ">{{ $entry->entrytype->name }}</td>
                <td style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; ">{{ $entry->number }}</td>
                {{-- <td style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; "> {{ TaskHelper::getDynamicEntryLedger($entry->id) }}</td> --}}
                @php
                    $legder_and_lastest_entry=TaskHelper::getDynamicEntryLedger($entry->id);
                @endphp
                <td style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; ">{{$legder_and_lastest_entry['title']}}</td>
                <td style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; ">{{$legder_and_lastest_entry['desc']}}...</td>
                @php
                    $total_dr_amount+=$entry->dr_total;
                    $total_cr_amount+=$entry->cr_total;
                @endphp
                <td style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; ">{{ number_format($entry->dr_total, 2) }}</td>
                <td style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; ">{{ number_format($entry->cr_total, 2) }}</td>
                <td style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; ">{{ $entry->source }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6" style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; background-color: #C5D9F1; border:">Total</td>
            <td style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; background-color: #C5D9F1; border: ">{{$total_dr_amount}}</td>
            <td style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; background-color: #C5D9F1; border: ">{{$total_cr_amount}}</td>
            <td style="text-align: center; font-weight: bold; font-size: 10px; color: black; border: 1px solid black; background-color: #C5D9F1; border: "></td>
        </tr>
    </tbody>
</table>
