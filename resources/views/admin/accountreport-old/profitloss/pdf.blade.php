<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>{{ env('APP_COMPANY')}} | {{ ucfirst(Request::segment(4)) }}</title>

 <style type="text/css">
      
@font-face {
  font-family: SourceSansPro;
  src: url(SourceSansPro-Regular.ttf);
}

.clearfix:after {
  content: "";
  display: table;
  clear: both;
}
a {
  color: #0087C3;
  text-decoration: none;
}
body {
  position: relative;
  width: 18cm;  
  height: 24.7cm; 
  margin: 0 auto; 
  color: #555555;
  background: #FFFFFF; 
  font-family: Arial, sans-serif; 
  font-size: 12px; 
}
header {
  padding: 10px 0;
  margin-bottom: 20px;
  border-bottom: 1px solid #AAAAAA;
}



table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 5px;
}

table th,
table td {
  padding: 3px;
  background:;
  text-align: left;
  /*border-bottom: 1px solid #FFFFFF;*/
}

table th {
  white-space: nowrap;        
  font-weight: normal;
}

table td {
  text-align: left;
}

table td h3{
  color: #57B223;
  font-size: 1.2em;
  font-weight: normal;
  margin: 0 0 0.2em 0;
}

table .no {
  color: #FFFFFF;
  font-size: 1em;
  background: #57B223;
}

table .desc {
  text-align: left;
}

table .unit {
  background: #DDDDDD;
}

table .qty {
}

/*table .total {
  background: #57B223;
  color: #FFFFFF;
}

table td.unit,
table td.qty,
table td.total {
  font-size: 1.2em;
}*/
/*
table tbody tr:last-child td {
  border: none;
}*/

table tfoot td {
  padding: 5px 10px;
  /*background: #FFFFFF;*/
  border-bottom: none;
  /*font-size: 1em;*/
  /*white-space: nowrap; */
  border-top: 1px solid #AAAAAA; 
}

/*table tfoot tr:first-child td {
  border-top: none; 
}*/

/*table tfoot tr:last-child td {
  color: #57B223;
  font-size: 1em;
  border-top: 1px solid #57B223; 
  font-weight: bold;

}*/

table tfoot tr td {
  border: none;
}

#thanks{
  font-size: 2em;
  margin-bottom: 50px;
}

#notices{
  padding-left: 6px;
  border-left: 6px solid #0087C3;  
}

#notices .notice {
  font-size: 1.2em;
}

footer {
  /*color: #777777;*/
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #AAAAAA;
  padding: 8px 0;
  text-align: center;
}

    </style>
    
</head><body>
    <header class="clearfix">

	 <table>
	          <TR><TD width="50%" style="float:left">
	      <div id="logo">
	        <img src="{{public_path()}}/org/{{$imagepath}}">
	      </div>
	    </TD><td>

	      <div width="50%" style="text-align: right">
	         <h3 class="name">{{ \Auth::user()->organization->organization_name }} </h3>
	                <div>{{ \Auth::user()->organization->address }}</div>
	                  Phone: {{ \Auth::user()->organization->phone }}<br>
	                  Email: {{ \Auth::user()->organization->email }}<br/>
	                <div>PAN: {{ \Auth::user()->organization->vat_id }}</div>
	      </div>
	    </td></TR>
	  </table>
    </header>
    <main>

      <?php

      	function CategoryTree($parent_id=null,$affects_gross,$sub_mark='',$actype){

        $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('affects_gross',$affects_gross)->where('org_id',auth()->user()->org_id)->where('parent_id',$parent_id)->get();

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

              CategoryTree($group->id,$affects_gross,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$actype); 
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
             {{ CategoryTree(4,1,'','expenses') }}
          </tbody>
           <tfoot>
             <tr>
                <td>Total Gross Expenses</td>
                  <td id="expensesTotal">@if($expenses1Total >=  0) Dr {{$expenses1Total}} @else Cr {{-$expenses1Total}} @endif </td>
              </tr>
              <tr>
                  <td>Gross Profit</td>
                  <td class="incomesTotal"> {{$incomes1Total-$expenses1Total}}</td>
               </tr>
  	        <tr>
  	         
  	          <th>Total</th>
  	     
  	          <td class="assetsTotal">@if($incomes1Total >=  0) Cr {{$incomes1Total}} @else Dr {{-$incomes1Total}} @endif</td>
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
            {{ CategoryTree(3,1,'','incomes') }}
          </tbody>
           <tfoot>
             <tr>
              <th>Total</th>
              <td id="libalitiesTotal">@if($incomes1Total >=  0) Cr {{$incomes1Total}} @else Dr {{-$incomes1Total}} @endif</td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="clearfix">
         <table border="0" cellspacing="0" cellpadding="0" style="width: 50%; float: left;">
            <thead>
              <tr>
                <th class="no">NET Expenses (Dr)</th>
                <th class="no">Amount (Rs)</th>
              </tr>
            </thead>
            <tbody>
               {{ CategoryTree(4,0,'','expenses') }}
            </tbody>
             <tfoot>
               <tr>
                  <td>Total NET Expenses</td>
                    <td id="expensesTotal">@if($expenses0Total >= 0)Dr {{$expenses0Total}} @else Cr {{ -$expenses0Total}} @endif </td>
                </tr>
                <tr>
                    <td>NET Profit</td>
                    <td class="incomesTotal"> {{($incomes1Total-$expenses1Total + $incomes0Total)-($expenses0Total)}}</td>
                 </tr>
              <tr>
               
                <th>Total</th>
           
                <td class="assetsTotal">@if((($incomes1Total-$expenses1Total) + $incomes0Total) >= 0) Cr {{$incomes1Total-$expenses1Total + $incomes0Total}} @else Dr {{ -(($incomes1Total-$expenses1Total) + $incomes0Total)}} @endif </td>
              </tr>
            </tfoot>
         </table>
         <table border="0" cellspacing="0" ce$incomes1Total-$expenses1Total + $incomes0Totalllpadding="0" style="width: 50%; float: right;">
          <thead>
            <tr>
              <th class="no">NET Incomes (Cr)</th>
              <th class="no">Amount (Rs)</th>
            </tr>
          </thead>
          <tbody>
            {{ CategoryTree(3,0,'','incomes') }}
          </tbody>
           <tfoot>
            <tr>
             <td>Total NET Income</td>
                <td>@if($incomes0Total >= 0) Cr {{$incomes0Total}} @else Dr {{(-$incomes0Total)}} @endif</td>
             </tr>
              <tr>
                <td>Gross Profit</td>
                <td>{{$incomes1Total-$expenses1Total}}</td>
              </tr>
             <tr>
              <th>Total</th>
              <td>@if((($incomes1Total-$expenses1Total) + $incomes0Total)>= 0) Cr {{($incomes1Total-$expenses1Total) + $incomes0Total}} @else Dr {{-(($incomes1Total-$expenses1Total) + $incomes0Total)}}@endif</td>
            </tr>
          </tfoot>
         </table>
      </div>  
    </main>
    <footer>
      This Statement was created on MEROCRM.
    </footer>
</body></html>