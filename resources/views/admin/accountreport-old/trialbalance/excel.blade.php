   <?php

function CategoryTree($parent_id=null,$sub_mark=''){

  $groups= \App\Models\COAgroups::orderBy('code', 'asc')
          ->where('parent_id',$parent_id)->where('org_id',auth()->user()->org_id)->get();

    if(count($groups)>0){
      foreach($groups as $group){ 

        //dd($group->id);

        $cashbygroup = \TaskHelper::getTotalByGroups($group->id);
        
        //dd($cashbygroup);

        if($cashbygroup[0]['dr_amount'] == null && $cashbygroup[0]['dr_amount'] == null){

                echo '<tr>
                   
                    <td class="moto">'.$sub_mark.$group->code.'</td>
                    <td class="moto">'.$group->name.'</td> 
                    <td class="moto">'.'0.00'.'</td>
                    <td class="moto">'.'0.00'.'</td>
                    <td class="moto">'.'0.00'.'</td>
                    <td class="moto">'.'0.00'.'</td>
                   </tr>';

        }else{

            if($cashbygroup[0]['dr_amount']>$cashbygroup[0]['cr_amount']){

                  echo '<tr>
                       
                        <td class="moto ">'.$sub_mark.$group->code.'</td>
                        <td class="moto">'.$group->name.'</td> 
                        <td class="moto">'.number_format($cashbygroup[0]['opening_balance'],2).'</td>
                        <td class="moto">Dr '.number_format($cashbygroup[0]['dr_amount'],2).'</td>
                        <td class="moto">Cr '.number_format($cashbygroup[0]['cr_amount'],2).'</td>
                        <td class="moto">Dr '.number_format(abs($cashbygroup[0]['dr_amount']-$cashbygroup[0]['cr_amount']),2).'</td>
                       </tr>';

               }elseif( $cashbygroup[0]['dr_amount'] < $cashbygroup[0]['cr_amount'] ){

                  echo '<tr>
                   
                    <td class="moto">'.$sub_mark.$group->code.'</td>
                    <td class="moto">'.$group->name.'</td> 
                    <td class="moto">'.number_format($cashbygroup[0]['opening_balance'],2).'</td>
                    <td class="moto">Dr '.number_format($cashbygroup[0]['dr_amount'],2).'</td>
                    <td class="moto">Cr '.number_format($cashbygroup[0]['cr_amount'],2).'</td>
                    <td class="moto">Cr '.number_format(abs($cashbygroup[0]['dr_amount']-$cashbygroup[0]['cr_amount']),2).'</td>
                   </tr>';

               }else{

                 echo '<tr>
                    <td class="moto">'.$sub_mark.$group->code.'</td>
                    <td class="moto">'.$group->name.'</td> 
                    <td class="moto">'.number_format($cashbygroup[0]['opening_balance'],2).'</td>
                    <td class="moto">Dr '.number_format($cashbygroup[0]['dr_amount'],2).'</td>
                    <td class="moto">Cr '.number_format($cashbygroup[0]['cr_amount'],2).'</td>
                    <td class="moto">Dr '.number_format(abs($cashbygroup[0]['dr_amount']-$cashbygroup[0]['cr_amount']),2).'</td>
                   </tr>';


               }

        }

        $ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)->where('group_id',$group->id)->get(); 

        if(count($ledgers)>0){
            $submark= $sub_mark;
            $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 

              foreach($ledgers as $ledger){

                // $closing_balance =TaskHelper::getLedgerDebitCredit($ledger->id);
                $closing_balance =TaskHelper::getLedgerTotal($ledger->id);
                if ($closing_balance['amount'] > 0) {
                  if( $closing_balance['dc'] == 'D'){

                     echo '<tr style="color: #3c8dbc;">
                            
                            <td class="text-success"><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#3c8dbc;">'.$sub_mark.$ledger->code.'</a></td>
                            <td class="text-success"><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#3c8dbc;">'.$ledger->name.'</a></td>
                            <td class="text-success">'.number_format($ledger->op_balance,2).'</td>

                            <td class="text-success">Dr '.number_format($closing_balance['amount'],2).'</td>
                            <td class="text-success">Cr 0.00</td>
                            <td class="text-success">Dr '.number_format($closing_balance['amount'],2).'</td>

                       </tr>';
                  }
                  else{

                    echo '<tr style="color: #3c8dbc;">
                     
                        <td class=""><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#3c8dbc;">'.$sub_mark.$ledger->code.'</a></td>
                        <td class=""><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#3c8dbc;">'.$ledger->name.'</a></td>
                        <td class="">'.number_format($ledger->op_balance,2).'</td>
                        <td class="">Dr 0.00</td>
                        <td class="">Cr '.number_format($closing_balance['amount'],2).'</td>
                        <td class="">Cr '.number_format($closing_balance['amount'],2).'</td>
                       </tr>';
                  }
                  
                }

              
           }
           $sub_mark=$submark;
        }


        CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"); 
      }
    }

  }
?>

   <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped" id="orders-table">
                                <thead>
                                    <tr class="bg-info">
                                        
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th>Opening Balance</th>
                                        <th>Debit Total({{env('APP_CURRENCY')}})</th>
                                        <th>Credit Total({{env('APP_CURRENCY')}})</th>
                                        <th>Closing Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   {{ CategoryTree() }}
                                   <tr style=" font-size: 16.5px; font-weight: bold;">

                                    <?php

                                        $assetstotal = \TaskHelper::getTotalByGroups(1);
                                        $equitytotal = \TaskHelper::getTotalByGroups(2);
                                        $incometotal = \TaskHelper::getTotalByGroups(3);
                                        $expensestotal = \TaskHelper::getTotalByGroups(4);

                                        $total_dr_amount = $assetstotal[0]['dr_amount'] + $equitytotal[0]['dr_amount'] + $incometotal[0]['dr_amount'] + $expensestotal[0]['dr_amount'];

                                        $total_cr_amount =  $assetstotal[0]['cr_amount'] + $equitytotal[0]['cr_amount'] + $incometotal[0]['cr_amount'] + $expensestotal[0]['cr_amount'];

                                     ?>
                                       <td colspan="3"></td>
                                       <td style="font-weight: 25px">Dr {{ number_format($total_dr_amount,2)}}</td>
                                       <td style="font-weight: 25px">Cr {{number_format($total_cr_amount,2)}} </td>
                                       <td style="font-weight: 25px">
                                        @if($total_dr_amount == $total_cr_amount)
                                             <i class="fa fa-check-circle"></i>
                                       @else
                                             <i class="fa fa-close"></i>
                                        @endif
                                       </td>
                                   </tr>
                                </tbody>
                            </table>

                        </div> <!-- table-responsive -->