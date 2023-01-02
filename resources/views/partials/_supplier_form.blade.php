<?php $readonly = ($client->isEditable())? '' : 'readonly'; ?>

<div class="content" style="padding-left: 0;">
    <div class="col-md-6" style="padding-left: 0;">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
            Company
            </label><div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control', $readonly]) !!}
        </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
            {!! Form::label('location', trans('admin/clients/general.columns.location')) !!}
            </label><div class="col-sm-10">
            {!! Form::text('location', null, ['class' => 'form-control', $readonly]) !!}
        </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
            {!! Form::label('phone', trans('admin/clients/general.columns.phone')) !!}
            </label><div class="col-sm-10">
            {!! Form::text('phone', null, ['class' => 'form-control', $readonly]) !!}
        </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
            {!! Form::label('email', trans('admin/clients/general.columns.email')) !!}
            </label><div class="col-sm-10">
            {!! Form::text('email', null, ['class' => 'form-control', $readonly]) !!}
        </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
            VAT ID
            </label><div class="col-sm-10">
            {!! Form::text('vat', null, ['class' => 'form-control', $readonly]) !!}
        </div>
        </div>


    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
            {!! Form::label('website', trans('admin/clients/general.columns.website')) !!}
            </label><div class="col-sm-10">
            {!! Form::text('website', null, ['class' => 'form-control', $readonly]) !!}
        </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
            {!! Form::label('industry', trans('admin/clients/general.columns.industry')) !!}
             </label><div class="col-sm-10">
            {!! Form::select('industry', ['Hospitality'=>'Hospitality', 'Travel'=>'Travel', 'Education'=>'Education', 'Health'=>'Health', 'IT' =>'IT', 'ISP'=>'ISP', 'Banking'=>'Banking', 'Brokers'=>'Brokers', 'Trading'=>'Trading', 'Construction'=>'Construction', 'Government'=>'Government', 'Engineering'=>'Engineering', 'Media'=>'Media', 'Finance'=>'Finance', 'Retail'=>'Retail'], null, ['class' => 'form-control', $readonly]) !!}
        </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
            {!! Form::label('type', trans('admin/clients/general.columns.type')) !!}
             </label><div class="col-sm-10">
            {!! Form::select('type', ['Customer'=>'Customer', 'Competitor'=>'Competitor', 'Distributor'=>'Distributor', 'Investor'=>'Investor', 'Partner' =>'Partner', 'Supplier'=>'Supplier', 'Vendor'=>'Vendor'], null, ['class' => 'form-control', $readonly]) !!}
        </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
            {!! Form::label('stock_symbol', trans('admin/clients/general.columns.stock_symbol')) !!}
             </label><div class="col-sm-10">
            {!! Form::text('stock_symbol', null, ['class' => 'form-control', $readonly]) !!}
        </div>
        </div>
    </div>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">
            {!! Form::checkbox('enabled', '1', $client->enabled) !!} {!! trans('general.status.enabled') !!}
        </label>
    </div>

</div><!-- /.content -->
