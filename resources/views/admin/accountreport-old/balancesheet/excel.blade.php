<main>

     <?php

    function CategoryTree($parent_id=null,$sub_mark='',$actype){
      $total = 0;
      $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)
              ->where('org_id',auth()->user()->org_id)->get();

        if(count($groups)>0){
          foreach($groups as $group){ 

            $cashbygroup = TaskHelper::getTotalByGroups($group->id);

             if($cashbygroup[0]['dr_amount'] == null && $cashbygroup[0]['dr_amount'] == null){
               echo '<tr>
                        {{-- <td><b>'.$sub_mark.'['.$group->code.']'.$group->name.'</b></td> --}}
                        <td><b><a href="'.route('admin.chartofaccounts.detail.groups', $group->id).'" style="color:black">'.$sub_mark.'['.$group->code.']'.$group->name.'</b></td>
                        <td><b><span>0.00</span></b></td>
                     </tr>';
                }else{
                    if($cashbygroup[0]['dr_amount']>$cashbygroup[0]['cr_amount']){
                     echo '<tr>
                            {{-- <td><b>'.$sub_mark.'['.$group->code.']'.$group->name.'</b></td> --}}
                            <td><b><a href="'.route('admin.chartofaccounts.detail.groups', $group->id).'" style="color:black">'.$sub_mark.'['.$group->code.']'.$group->name.'</b></td>
                            <td><b><span>Dr '.number_format(abs($cashbygroup[0]['dr_amount']-$cashbygroup[0]['cr_amount']),2).'</span></b></td>
                         </tr>';
                       }else
                       {
                       echo '<tr>
                            {{-- <td><b>'.$sub_mark.'['.$group->code.']'.$group->name.'</b></td> --}}
                            <td><b><a href="'.route('admin.chartofaccounts.detail.groups', $group->id).'" style="color:black">'.$sub_mark.'['.$group->code.']'.$group->name.'</b></td>
                            <td><b><span>Cr '.number_format(abs($cashbygroup[0]['dr_amount']-$cashbygroup[0]['cr_amount']),2).'</span></b></td>
                         </tr>';
                       }
                }

            $ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)->where('group_id',$group->id)
                    ->get(); 
            if( count( $ledgers) > 0 ) {

                $submark= $sub_mark;
                $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 

                  foreach($ledgers as $ledger){
                 // $closing_balance =TaskHelper::getLedgerDebitCredit($ledger->id);
                 $closing_balance =TaskHelper::getLedgerTotal($ledger->id);

                 if ($closing_balance['amount'] > 0) {

                    if( $closing_balance['dc'] == 'D'){

                        echo '<tr style="color: #3c8dbc;">
                        
                          <td class="bg-warning"><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#3c8dbc;font-size: 12px;">'.$sub_mark.'['.$ledger->code.']'.$ledger->name.'</a></td>
                           <td class="bg-warning f-16">Dr <span class="dr'.$actype.' dr'.$actype.$index.'">'.
                           $closing_balance['amount'].'</span></td>
                         </tr>';
                         $total += $closing_balance['amount'];
                   }else{

                        echo '<tr style="color: #3c8dbc;">
                        
                            <td class="bg-danger"><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#3c8dbc;font-size: 12px;">'.$sub_mark.'['.$ledger->code.']'.$ledger->name.'</a></td>
                             <td class="bg-danger f-16">Cr <span class="cr'.$actype.'">'.
                            $closing_balance['amount'].'</span></td>
                         </tr>';
                         $total -= $closing_balance['amount'] ;
                  }
                }

               }

               $sub_mark=$submark;
            }
            CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$actype); 
          }
        }

    }

     ?>

      <div id="details" class="clearfix">

        <table>
          <TR>
            <TD>
            <div id="client">
              <h3 style="text-align: center;">Balance Sheet Report</h3>
            </div>
            </TD>
          </TR>
        </table>
      <table border="0" cellspacing="0" cellpadding="0" style="width: 50%; float: left; ">
        <thead>
          <tr>
            <th class="no">Assets</th>
            <th class="no">Amount (Rs)</th>
          </tr>
        </thead>
        <tbody>
           {{ CategoryTree(1,'','assets') }}
        </tbody>
         <tfoot>
          <tr style="font-size: 12.5px;">
           
            <th>Total</th>
       
            <td class="assetsTotal">{{$assetsTotal}}</td>
          </tr>
        </tfoot>
      </table>
      <table border="0" cellspacing="0" cellpadding="0" style="width: 50%; float: right;  ">
        <thead>
          <tr>
            <th class="no">Liabilities and Owners Equity (Cr)</th>
            <th class="no">Amount (Rs)</th>
          </tr>
        </thead>
        <tbody>
          {{ CategoryTree(2,'','libalities') }}
        </tbody>
         <tfoot>
          <tr  style=" font-size: 12.5px;">
          <td>Total Liabilities and Owners Equity</td>
            <td class="assetsTotal">{{$liabilitiesTotal}}</td>
          </tr>
          <tr  style=" font-size: 12.5px;">
            <td>Profit & Loss Account (Net Profit)</td>
            <td id="netProfit">{{$assetsTotal - $liabilitiesTotal}}</td>
            </tr>
           <tr  style="font-size: 12.5px;">
            <th>Total</th>
            <td id="libalitiesTotal">{{$assetsTotal}}</td>
          </tr>
        </tfoot>
      </table>

    </main>