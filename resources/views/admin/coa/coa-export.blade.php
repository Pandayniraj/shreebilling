
<?php

function CategoryTree($parent_id=null,$sub_mark=''){

  $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)->where('org_id',\Auth::user()->org_id)->get();

    if(count($groups)>0){
      foreach($groups as $group){

        if($group->id<=4){
            $ledger_ids=\App\Helpers\TaskHelper::get_ledger_ids($group->id);
            $ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->whereIn('id',$ledger_ids)
                ->where('org_id',\Auth::user()->org_id)->get();
            $closing_blc=0;
            foreach ($ledgers as $ledger){
                $closing_balance =TaskHelper::getLedgerTotal($ledger->id);
                $closing_balance =TaskHelper::calculate_withdc($ledger->op_balance,$ledger->op_balance_dc,
                    $closing_balance['amount'],$closing_balance['dc']);
                $closing_blc+=$closing_balance['amount'];
            }
           $op_balance= $ledgers->sum('op_balance');
           echo '<tr class="bg-purple" style="font-size:16px">

                <td>-</td>
                <td><b>'.$sub_mark.$group->code.'</b></td>
                <td><b>'.$group->name.'</b></td>'.
//                <td><b>'.$group->account_types->name.'</b></td>
                '<td><b>Group</b></td>
                <td>'.$op_balance.'</td>
                <td>'.$closing_blc.'</td>

               </tr>';
        }else{
            $ledger_ids=\App\Helpers\TaskHelper::get_ledger_ids($group->id);
            $ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->whereIn('id',$ledger_ids)
                ->where('org_id',\Auth::user()->org_id)->get();
            $closing_blc='';
            foreach ($ledgers as $ledger){
                $closing_balance =TaskHelper::getLedgerTotal($ledger->id);
                $closing_balance =TaskHelper::calculate_withdc($ledger->op_balance,$ledger->op_balance_dc,
                    $closing_balance['amount'],$closing_balance['dc']);
                $closing_blc+=$closing_balance['amount'];

            }
            $op_balance= $ledgers->sum('op_balance');
           echo '<tr>

                <td><b>'.$group->parent->name.'</b></td>
                <td><b>'.$sub_mark.$group->code.'</b></td>
                <td><b>'.$group->name.'</b></td>'.
//                <td><b>'.$group->account_types->name.'</b></td>
                '<td><b>Group</b></td>
                <td>'.$op_balance.'</td>
                <td>'.$closing_blc.'</td>


               </tr>';
        }

        $ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id',$group->id)->where('org_id',\Auth::user()->org_id)->get();
        if(count($ledgers>0)){
            $submark= $sub_mark;
            $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

              foreach($ledgers as $ledger){

                $closing_balance =TaskHelper::getLedgerTotal($ledger->id);
                $closing_balance =TaskHelper::calculate_withdc($ledger->op_balance,$ledger->op_balance_dc,
                    $closing_balance['amount'],$closing_balance['dc']);
            if ($closing_balance['amount'] > 0) {

              if( $closing_balance['dc'] == 'D'){

             echo '<tr style="color: #009551;">
                <td>-</td>
                <td>'.$sub_mark.$ledger->code.'</td>
                <td>'.$ledger->name.'</td>'.
//                <td><b>'.$ledger->group->account_types->name.'</b></td>
                '<td>Ledgers</td>
                <td>'.$ledger->op_balance.'</td>
                <td> Dr '.$closing_balance['amount'].'</td>


               </tr>';
           }else{
             echo '<tr style="color: #009551;">
                <td>-</td>
                <td>'.$sub_mark.$ledger->code.'</td>
                <td>'.$ledger->name.'</td>'.
//                <td><b>'.$ledger->group->account_types->name.'</b></td>
                '<td><b>Ledgers</b></td>
                <td>'.$ledger->op_balance.'</td>
                <td> Cr '.$closing_balance['amount'].'</td>


               </tr>';
           }
           }else{
             echo '<tr style="color: #009551;">
                <td>-</td>
                <td>'.$sub_mark.$ledger->code.'</td>
                <td>'.$ledger->name.'</td>'.
//                <td><b>'.$ledger->group->account_types->name.'</b></td>
                '<td>Ledgers</td>
                <td>'.$ledger->op_balance.'</td>
                <td>'.$closing_balance['amount'].'</td>
            </tr>';
           }

           }
           $sub_mark=$submark;


        }


        CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
      }
    }


}

 ?>

<table>
    <thead>
    <tr>
        <th colspan="12" align="center">
            <b>{{env('APP_COMPANY')}}</b>
        </th>
    </tr>
    <tr>
        <th  style="text-align: center;" align="center" colspan="12">
            <b>{{\Auth::user()->organization->address}}</b>
        </th>
    </tr>
    <tr>
        <th style="text-align: center;" align="center"  colspan="12">
            <b>Coa Ledgers</b>
        </th>
    </tr>
    <tr></tr>
    <tr>
        <th colspan="12">
            <b>Report Date: {{date('d M Y')}}</b>
        </th>
    </tr>
    </thead>
</table>


<table class="table table-hover table-bordered table-striped" id="orders-table">
    <thead>
    <tr class="bg-danger">

        <th style="background: #d9edf7">Parent Group</th>
        <th style="background: #d9edf7">Account Code</th>
        <th style="background: #d9edf7">Account Name</th>
        <th style="background: #d9edf7">Category</th>
        <th style="background: #d9edf7">O/P Balance({{env('APP_CURRENCY')}})</th>
        <th style="background: #d9edf7">C/L Balance({{env('APP_CURRENCY')}})</th>
    </tr>
    </thead>
    <tbody>
    {{ CategoryTree() }}
    </tbody>
</table>
