<?php $readonly = ($ProductCategory->isEditable())? '' : 'readonly'; ?>

<div class="content">

    <div class="form-group">
        <label> Category Name</label>
        {!! Form::text('name', null, ['class' => 'form-control', $readonly]) !!}
    </div>
    
    <div class="form-group">
        <div class="checkbox">
            <label>
                {!! '<input type="hidden" name="enabled" value="0">' !!}
                {!! Form::checkbox('enabled', '1', $ProductCategory->enabled) !!} {{ trans('general.status.enabled') }}
            </label>
        </div>
    </div>

</div><!-- /.content -->



