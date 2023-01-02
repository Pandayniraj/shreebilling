
@if($selectedgroup)
     <select class="form-control searchable select2 " name="expenses_account" required=""  id='expenses_account'>
      <option value="">Select Ledger</option>
        {{ \FinanceHelper::ledgerGroupsOptionshtml($selectedgroup) }}
    </select>

    </select>
@else
<?php 
function CategoryTree($parent_id=null,$sub_mark=''){
    
    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)->get();

    if(count($groups)>0){
        foreach($groups as $group){
            echo '<option value="'.$group->id.'" disabled><strong>'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</strong></option>';

            $ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id',$group->id)->get(); 
            if(count($ledgers>0)){
                $submark= $sub_mark;
                $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 

                 foreach($ledgers as $ledger){
                if($lastcreated->id == $ledger->id)
                 echo '<option value="'.$ledger->id.'" selected><strong>'.$sub_mark.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';
                else 
                    echo '<option value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';

               }
               $sub_mark=$submark;

            }
            CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
        }
    }
 }
?>

<select  class="ledger-dropdown form-control select2" tabindex="-1" aria-hidden="true">
<option value="">Select</option>
    {{ CategoryTree() }}
</select>



@endif