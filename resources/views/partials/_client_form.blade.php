<?php $readonly = ($client->isEditable())? '' : 'readonly'; ?>
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />

<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<div class="content" style="padding-left: 0;">
    <div class="col-md-6" style="padding-left: 0;">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label" style="text-transform: capitalize;">
                {{ $_GET['relation_type']}} Name
            </label>
            <div class="col-sm-8">
                {!! Form::text('name', null, ['class' => 'form-control', $readonly]) !!}
            </div>
        </div>


        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                {!! Form::label('phone', trans('admin/clients/general.columns.phone')) !!}
            </label>
            <div class="col-sm-8">
                {!! Form::text('phone', null, ['class' => 'form-control', $readonly]) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                {!! Form::label('email', trans('admin/clients/general.columns.email')) !!}
            </label>
            <div class="col-sm-8">
                {!! Form::text('email', null, ['class' => 'form-control', $readonly]) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                VAT/TAX ID
            </label>
            <div class="col-sm-8">
                {!! Form::text('vat', null, ['class' => 'form-control', $readonly]) !!}
            </div>
        </div>


         {{-- @if(\Request::segment(3) != 'create')
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                Ledger ID
            </label>
            <div class="col-sm-6">
                {!! Form::select('ledger_id',['class'=>'form-control searchable select2',$readonly]) !!}
            </div>
        </div>
        @endif --}}


        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                Bank Name
            </label>
            <div class="col-sm-8">
                {!! Form::text('bank_name', null, ['class' => 'form-control', $readonly]) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                Branch
            </label>
            <div class="col-sm-8">
                {!! Form::text('bank_branch', null, ['class' => 'form-control', $readonly]) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                Account Number
            </label>
            <div class="col-sm-8">
                {!! Form::text('bank_account', null, ['class' => 'form-control', $readonly]) !!}
            </div>
        </div>


        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                Notes
            </label>
            <div class="col-md-8">
                {!! Form::textarea('notes', null, ['class'=>'form-control', 'rows'=>'2']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                Reminder <i style='color: navy' class="fa fa-lightbulb-o"></i>
            </label>
            <div class="col-md-8">
                {!! Form::textarea('reminder', null, ['class'=>'form-control', 'rows'=>'2', 'placeholder'=> 'Will appear each time in thier related actions']) !!}

            </div>
        </div>

    </div>


    <div class="col-md-6">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                {!! Form::label('website', trans('admin/clients/general.columns.website')) !!}
            </label>
            <div class="col-sm-8">
                {!! Form::text('website', null, ['class' => 'form-control', $readonly]) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label" style="text-transform: capitalize;">
                {{ $_GET['relation_type']}}
                {!! Form::label('industry', trans('admin/clients/general.columns.industry')) !!}
            </label>
            <div class="col-sm-8">
                {!! Form::select('industry', ['Hospitality'=>'Hospitality', 'Travel'=>'Travel', 'Education'=>'Education', 'Health'=>'Health', 'IT' =>'IT', 'ISP'=>'ISP', 'Banking'=>'Banking', 'Brokers'=>'Brokers', 'Trading'=>'Trading', 'Construction'=>'Construction', 'Government'=>'Government', 'Engineering'=>'Engineering', 'Media'=>'Media', 'Finance'=>'Finance', 'Retail'=>'Retail'], null, ['class' => 'form-control', $readonly]) !!}
            </div>
        </div>


        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label" style="text-transform: capitalize;">

                {!! Form::label('customer_group', $_GET['relation_type'].' Groups') !!}
            </label>
            <div class="col-sm-8">
                {!! Form::select('customer_group', $groups, null, ['class' => 'form-control searchable', $readonly,'placeholder'=>'Select Groups']) !!}
            </div>
        </div>


        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                Accounting Type
            </label>
            <div class="col-sm-8">
                <!--  {!! Form::select('type', ['Customer'=>'Customer','Supplier'=>'Supplier', 'Agent'=>'Agent'], null, ['class' => 'form-control', $readonly]) !!} -->
                <select class='form-control searchable select2 ' name="types" required>
                    @if($_GET['relation_type']=='supplier')
                    <optgroup label="Supplier">
                        <?php

                    //Sunny_deptors
                    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',\FinanceHelper::get_ledger_id('SUPPLIER_LEDGER_GROUP'))->where('org_id',\Auth::user()->org_id)->get();



                        foreach($groups as $grp)
                        {
                            echo '<option value="'.$grp->id.'"'.
                            (($grp->name==$client->type)?'selected="selected"':"").
                            '>'
                            .$grp->name.'</option>';
                        }
                    ?>
                    </optgroup>
                    @else
                    <optgroup label="Customers">
                        <?php
                     //Sunny_creditors
                    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',\FinanceHelper::get_ledger_id('CUSTOMER_LEDGER_GROUP'))->where('org_id',\Auth::user()->org_id)->get();

                    foreach($groups as $grp)
                    {
                    echo '<option value="'.$grp->id.'"'.
                        (($grp->name==$client->type)?'selected="selected"':"").
                        '>'
                        .$grp->name.'</option>';
                    }
                ?>
                    </optgroup>
                    @endif
                </select>
            </div>
        </div>


        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                {!! Form::label('stock_symbol', trans('admin/clients/general.columns.stock_symbol')) !!}
            </label>
            <div class="col-sm-8">
                {!! Form::text('stock_symbol', null, ['class' => 'form-control', $readonly]) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                Physical Address
            </label>
            <div class="col-md-8">

                {!! Form::textarea('physical_address', null, ['class'=>'form-control', 'rows'=>'2']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for=""  class="col-sm-4 control-label">Image</label>
            <div class="col-md-8">
                <input type="file" name="image" class="form-control"/>
            </div>
            @if($client && $client->image!="")
            <label for=""  class="col-sm-4 control-label"></label>
            <div class="col-md-8">
                <img src="{{asset($client->image)}}" alt="" width="100" height="100">
            </div>

            @endif
        </div>
    </div>


    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">
            {!! Form::checkbox('enabled', '1', $client->enabled) !!} {!! trans('general.status.enabled') !!}
        </label>
    </div>



</div><!-- /.content -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.searchable').select2();
    });
    $("#locations").autocomplete({
        source: "/admin/getCities"
        , minLength: 2
        , select: function(event, ui) {
            $('#locationvalue').val(ui.item.id);
        }
    });

</script>
