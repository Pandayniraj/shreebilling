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
             <b>Ledger Bulk Group Statement</b>
         </th>
     </tr>
     <tr>
         <th colspan="12" align="center">
             <strong>[{{$groups_data->code??''}}] {{$groups_data->name??''}}</strong>
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

     <tr><td colspan="12"></td></tr>

         <tr>
             <th style="border:1px solid;font-weight:bold;background-color:#d9edf7;">SNo.</th>
             <th style="border:1px solid;font-weight:bold;background-color:#d9edf7;">Ledger Code</th>
             <th style="border:1px solid;font-weight:bold;background-color:#d9edf7;">Ledger</th>
             <th style="border:1px solid;font-weight:bold;background-color:#d9edf7;">Opening Balance</th>
             <th style="border:1px solid;font-weight:bold;background-color:#d9edf7;">Dr Amount</th>
             <th style="border:1px solid;font-weight:bold;background-color:#d9edf7;">Cr Amount</th>
             <th style="border:1px solid;font-weight:bold;background-color:#d9edf7;">Closing Balance</th>
         </tr>
         @if($ledgers_data)
             <?php
             $dr_total=0;
             $cr_total=0;
             ?>
             @foreach($ledgers_data as $key=>$ledger)

                 <tr>
                     <td style="width: 4%;text-align: center">{{$key+1}}.</td>
                     <td>{{$ledger['code']}}</td>
                     <td>{{$ledger['name']}}</td>
                     <th>{{$ledger['op_dc']=='D'?'DR':'CR'}} {{round($ledger['op_balance'],2)}}</th>
                     <th>{{round($ledger['dr_amount'],2)}}</th>
                     <th>{{round($ledger['cr_amount'],2)}}</th>
                     <th>{{$ledger['closing_dc']=='D'?'DR':'CR'}} {{round($ledger['closing_balance'],2)}}</th>
                 </tr>
                 <?php
                 $dr_total+=$ledger['dr_amount'];
                 $cr_total+=$ledger['cr_amount'];
                 ?>


             @endforeach

             <tr >
                 <td colspan="3" style="text-align: right;font-weight:bold;background-color:#d9edf7;"><b>Total</b></td>
                 <td style="font-weight:bold;background-color:#d9edf7;"></td>

                 <td style="font-weight:bold;background-color:#d9edf7;">
                     <b>DR {{ round($dr_total,2) }}</b>
                 </td>
                 <td style="font-weight:bold;background-color:#d9edf7;">
                     <b>CR {{ round($cr_total,2) }}</b>
                 </td>
                 <td style="font-weight:bold;background-color:#d9edf7;"></td>
             </tr>
         @else
             <tr>
                 <td colspan="9" style="font-weight: bold;color:grey;text-align: center">No Records Found..</td>
             </tr>
         @endif

    </tbody>
  </table>
