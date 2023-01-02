<?php $readonly = ($lead->isEditable())? '' : 'readonly'; ?>

<div class="content">
    <div class="form-group">
        {!! Form::label('name', trans('admin/leads/general.columns.name')) !!}
        {!! Form::text('name', null, ['class' => 'form-control', $readonly]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('mob_phone', trans('admin/leads/general.columns.mob_phone')) !!}
        {!! Form::text('mob_phone', null, ['class' => 'form-control', $readonly]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('home_phone', trans('admin/leads/general.columns.home_phone')) !!}
        {!! Form::text('home_phone', null, ['class' => 'form-control', $readonly]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('guardian_name', trans('admin/leads/general.columns.guardian_name')) !!}
        {!! Form::text('guardian_name', null, ['class' => 'form-control', $readonly]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('guardian_phone', trans('admin/leads/general.columns.guardian_phone')) !!}
        {!! Form::text('guardian_phone', null, ['class' => 'form-control', $readonly]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('email', trans('admin/leads/general.columns.email')) !!}
        {!! Form::text('email', null, ['class' => 'form-control', $readonly]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('qualification', trans('admin/leads/general.columns.qualification')) !!}
        {!! Form::text('qualification', null, ['class' => 'form-control', $readonly]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('course_id', trans('admin/leads/general.columns.course_name')) !!}
        {!! Form::select('course_id', $courses, null, ['class' => 'form-control', $readonly]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('intake_id', trans('admin/leads/general.columns.intake_session_name')) !!}
        {!! Form::select('intake_id', $intakes, null, ['class' => 'form-control', $readonly]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('communication_id', trans('admin/leads/general.columns.communication_name')) !!}
        {!! Form::select('communication_id', $communications, null, ['class' => 'form-control', $readonly]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('enquiry_mode_id', trans('admin/leads/general.columns.enquiry_mode_name')) !!}
        {!! Form::select('enquiry_mode_id', $enquiry_modes, null, ['class' => 'form-control', $readonly]) !!}
    </div>

</div><!-- /.content -->
