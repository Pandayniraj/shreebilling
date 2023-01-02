<?php $readonly = ($LeadStatus->isEditable())? '' : 'readonly'; ?>

<div class="content">
    <div class="form-group">
        {!! Form::label('name', trans('admin/leadstatus/general.columns.name')) !!}
        {!! Form::text('name', null, ['class' => 'form-control', $readonly]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('ordernum', 'Ordernum') !!}
         {!! Form::text('ordernum', null, ['class' => 'form-control', $readonly]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Color', 'Color') !!}
         {!! Form::text('color', null, ['class' => 'form-control', $readonly]) !!}
    </div>
    
    <div class="form-group">
        <div class="checkbox">
            <label>
                {!! '<input type="hidden" name="enabled" value="0">' !!}
                {!! Form::checkbox('enabled', '1', $LeadStatus->enabled) !!} {{ trans('general.status.enabled') }}
            </label>
        </div>
    </div>
</div><!-- /.content -->



