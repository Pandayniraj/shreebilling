@php 

    $readonly = isset($readonly) ? $readonly : false;
@endphp



<div class="content">
    <div class="form-group">
        {!! Form::label('leave_year', 'Leave Year') !!}
        {!! Form::text('leave_year', null, ['class' => 'form-control', $readonly]) !!}
    </div>


    <div class="form-group">
        {!! Form::label('start_date', 'Start Date') !!}
         {!! Form::text('start_date', null, ['class' => 'form-control datepicker', 'id'=>'datepicker',$readonly]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('end_date', 'End Date') !!}
         {!! Form::text('end_date', null, ['class' => 'form-control datepicker', 'id'=>'datepicker2', $readonly]) !!}
    </div>
    
   
</div><!-- /.content --> 







