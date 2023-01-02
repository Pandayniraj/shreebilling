<?php $readonly = ($producttypemasters->isEditable())? '' : 'readonly'; ?> 
<?php
function CategoryTree($parent_id=null,$sub_mark='',$group_id,$ledgers_data){

    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)->where('id',$group_id)->orwhere('parent_id',$group_id)->get();
    
    if(count($groups)>0){
        foreach($groups as $group){
            
            echo '<option value="'.$group->id.'" disabled><strong>'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</strong></option>';

            $ledgers= \App\Models\COALedgers::with('group:id,name')->orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)->where('group_id',$group->id)
                ->get();
            if(count($ledgers)>0){
                $submark= $sub_mark;
                $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                foreach($ledgers as $ledger){

                    if( ($ledgers_data->id??'') == $ledger->id){
                        echo '<option selected value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';
                        // echo '<option selected value="'.$ledger->id.'"><strong>'.$sub_mark.$ledger->name.'</strong></option>';
                    }else{

                        echo '<option value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';
                    }

                }
                $sub_mark=$submark;

            }
                //            CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$ledgers_data);
        }
    }
}

?>
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<div class="content">

    <div class="form-group">
        <label> Product Type Master Name</label>
          {!! Form::text('name', null, ['class' => 'form-control', $readonly]) !!}
    </div>
    <div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Income/Sales Ledger</label>
            <?php
            $group_id = \FinanceHelper::get_ledger_id('INCOME_TYPE_MASTER_GROUP');
            ?>
            <select class="form-control input-sm ledger_id select2" name="ledger_id" aria-hidden="true">
                <option value="">Select</option>
                {{ CategoryTree($parent_id=null,$sub_mark='',$group_id,$sale_ledger) }}
            </select>

        </div>
    </div>
        <div class="col-md-4">
        <div class="form-group">
            <label>Purchase Ledger</label>
            <?php
            $group_id = \FinanceHelper::get_ledger_id('PURCHASE_TYPE_MASTER_GROUP');
            ?>
            <select class="form-control input-sm ledger_id select2"  name="purchase_ledger_id" aria-hidden="true">
                <option value="">Select</option>
                {{ CategoryTree($parent_id=null,$sub_mark='',$group_id,$purchase_ledger) }}
            </select>

        </div>
    </div>
        <div class="col-md-4">
        <div class="form-group">
            <label>COGS Ledger</label>
            <?php
            $group_id = \FinanceHelper::get_ledger_id('COST_OF_GOODS_GROUP');
            ?>
            <select class="form-control input-sm ledger_id select2" name="cogs_ledger_id" aria-hidden="true">
                <option value="">Select</option>
                {{ CategoryTree($parent_id=null,$sub_mark='',$group_id,$cogs_ledger) }}
            </select>

        </div>
    </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Fixed Asset Ledger</label>
                <?php
                $group_id = \FinanceHelper::get_ledger_id('ASSETS');
                ?>
                <select class="form-control input-sm ledger_id select2" name="assets_ledger_id" aria-hidden="true">
                    <option value="">Select</option>
                    {{ CategoryTree($parent_id=null,$sub_mark='',$group_id, $assets_ledger) }}
                </select>

            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Service Ledger</label>
                <?php
                $group_id = \FinanceHelper::get_ledger_id('SERVICES');
                ?>
                <select class="form-control input-sm ledger_id select2"  name="service_ledger_id" aria-hidden="true">
                    <option value="">Select</option>
                    {{ CategoryTree($parent_id=null,$sub_mark='',$group_id, $service_ledger) }}
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Sales return Ledger</label>
                <?php
                $group_id = \FinanceHelper::get_ledger_id('INCOME_TYPE_MASTER_GROUP');
                ?>
                <select class="form-control input-sm ledger_id select2"  name="sales_return_ledger_id" aria-hidden="true">
                    <option value="">Select</option>
                    {{ CategoryTree($parent_id=null,$sub_mark='',$group_id,$sale_ledger) }}
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Purchase Return Ledger</label>
                <?php
                $group_id = \FinanceHelper::get_ledger_id('PURCHASE_RETURN_TYPE_MASTER_GROUP');
                ?>
                <select class="form-control input-sm ledger_id select2"  name="purchase_return_ledger_id" aria-hidden="true">
                    <option value="">Select</option>
                    {{ CategoryTree($parent_id=null,$sub_mark='',$group_id,$service_ledger)}}
                </select>
            </div>
        </div>
    </div>
{{--    <div class="form-group">--}}
{{--        <label>Ledger ID</label>--}}
{{--          {!! Form::text('ledger_id', null, ['class' => 'form-control', $readonly]) !!}--}}
{{--    </div>--}}
      <div class="form-group">
        <label>Discount Limit</label>
          {!! Form::text('discount_limit', null, ['class' => 'form-control', $readonly]) !!}
    </div>

    <div class="form-group">
        <div class="checkbox">
            <label>
                {!! '<input type="hidden" name="enabled" value="0">' !!}
                {!! Form::checkbox('enabled', '1', $producttypemasters->enabled) !!} {{ trans('general.status.enabled') }}
            </label>
        </div>
    </div>

</div><!-- /.content -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.ledger_id').select2();
    });
</script>


