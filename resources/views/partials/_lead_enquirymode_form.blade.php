<?php $readonly = ($LeadEnquiryMode->isEditable())? '' : 'readonly'; ?>

<div class="content">
    <div class="form-group">
        {!! Form::label('name', trans('admin/lead-enquiry-mode/general.columns.name')) !!}
        {!! Form::text('name', null, ['class' => 'form-control', $readonly]) !!}
    </div>
    
    <div class="form-group">
        <div class="checkbox">
            <label>
                {!! '<input type="hidden" name="enabled" value="0">' !!}
                {!! Form::checkbox('enabled', '1', $LeadEnquiryMode->enabled) !!} {{ trans('general.status.enabled') }}
            </label>
        </div>
    </div>
</div><!-- /.content -->



