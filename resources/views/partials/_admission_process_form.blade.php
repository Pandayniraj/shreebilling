<?php $readonly = ($AdmissionProcess->isEditable())? '' : 'readonly'; ?>

<div class="">
    <div class="form-group">
        {!! Form::label('name', trans('admin/admissionprocess/general.columns.name')) !!}
        {!! Form::text('name', null, ['class' => 'form-control', $readonly]) !!}
    </div>
    
    <div class="form-group">
        <div class="checkbox">
            <label>
                {!! '<input type="hidden" name="enabled" value="0">' !!}
                {!! Form::checkbox('enabled', '1', $AdmissionProcess->enabled) !!} {{ trans('general.status.enabled') }}
            </label>
        </div>
    </div>
</div><!-- /.content -->



