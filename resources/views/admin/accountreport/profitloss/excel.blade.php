 <main>

      <?php

      	function CategoryTree($parent_id=null,$sub_mark='',$actype){

        $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)->where('parent_id',$parent_id)->get();

          if(count($groups)>0){
            foreach($groups as $group){ 
                $cashbygroup = TaskHelper::getTotalByGroups($group->id);
                   if($cashbygroup[0]['dr_amount'] == null && $cashbygroup[0]['dr_amount'] == null){
                     echo '<tr>
                             <td><b><a href="'.route('admin.chartofaccounts.detail.groups', $group->id).'" style="color:black">'.$sub_mark.'['.$group->code.']'.$group->name.'</b></td>
                              <td><b>0.00</b></td>
                           </tr>';
                         }
                    else{
                      if($cashbygroup[0]['dr_amount']>$cashbygroup[0]['cr_amount']){  
                       echo '<tr>
                             <td><b><a href="'.route('admin.chartofaccounts.detail.groups', $group->id).'" style="color:black">'.$sub_mark.'['.$group->code.']'.$group->name.'</b></td>
                              <td><b><span>Dr '.number_format(abs($cashbygroup[0]['dr_amount']-$cashbygroup[0]['cr_amount']),2).'</span></b></td>
                           </tr>';  
                        }else{
                           echo '<tr>
                             <td><b><a href="'.route('admin.chartofaccounts.detail.groups', $group->id).'" style="color:black">'.$sub_mark.'['.$group->code.']'.$group->name.'</b></td>
                              <td><b><span>Cr '.number_format(abs($cashbygroup[0]['dr_amount']-$cashbygroup[0]['cr_amount']),2).'</span></b></td>
                           </tr>';  
                        } 
                      }    

              $ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)->where('group_id',$group->id)->get(); 
              if(count($ledgers)>0){
                  $submark= $sub_mark;
                  $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 

                    foreach($ledgers as $ledger){
                   $closing_balance =TaskHelper::getLedgerDebitCredit($ledger->id); 
                   if ($closing_balance['amount'] > 0) {
                      if($closing_balance['dc'] == 'D'){

                          echo '<tr style="color: #3c8dbc;">
                           
                            <td class="bg-warning"><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#3c8dbc;">'.$sub_mark.'['.$ledger->code.']'.$ledger->name.'</a></td>
                             <td class="bg-warning"><b>Dr <span class="dr'.$actype.'">'.$closing_balance['amount'].'</span></b></td>
                           </tr>';

                     }else{

                          echo '<tr style="color: #3c8dbc;">
                              
                              <td class="bg-danger"><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#3c8dbc;">'.$sub_mark.'['.$ledger->code.']'.$ledger->name.'</a></td>
                               <td class="bg-danger"><b>Cr <span class="cr'.$actype.'">'.$closing_balance['amount'].'</span></b></td>
                           </tr>';
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
              <h3 style="text-align: center;">Profit Loss Report</h3>
		        </div>
            </TD>
          </TR>
        </table>
      <table border="0" cellspacing="0" cellpadding="0" style="width: 50%; float: left;">
        <thead>
          <tr>
            <th class="no">Gross Expenses (Dr)</th>
            <th class="no">Amount (Rs)</th>
          </tr>
        </thead>
        <tbody>
           {{ CategoryTree(4,'','expenses') }}
        </tbody>
         <tfoot>
           <tr>
              <td>Total Gross Expenses</td>
                <td id="expensesTotal"> {{$expensesTotal}} </td>
            </tr>
            <tr>
                <td>Gross Profit</td>
                <td class="incomesTotal"> {{$incomesTotal-$expensesTotal}}</td>
             </tr>
	        <tr>
	         
	          <th>Total</th>
	     
	          <td class="assetsTotal">{{$incomesTotal}}</td>
	        </tr>
        </tfoot>
      </table>
      <table border="0" cellspacing="0" cellpadding="0" style="width: 50%; float: right;">
        <thead>
          <tr>
            <th class="no">Gross Incomes (Cr)</th>
            <th class="no">Amount (Rs)</th>
          </tr>
        </thead>
        <tbody>
          {{ CategoryTree(3,'','incomes') }}
        </tbody>
         <tfoot>
           <tr>
            <th>Total</th>
            <td id="libalitiesTotal">{{$incomesTotal}}</td>
          </tr>
        </tfoot>
      </table>

    </main>