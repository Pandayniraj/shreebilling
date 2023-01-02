@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<style>
	select { width:200px !important; }
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

<script type="text/javascript">
    $(function() {

        $('#payment_month').datetimepicker({
            format: 'YYYY-MM',
            sideBySide: true
        });
});
</script>

<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body">
                {!! Form::open( ['route' => 'admin.payrollreports.post_monthly_report', 'id' => 'form_lead_report'] ) !!}        
                <div class="content col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('departments_id', 'Department Name') !!}
                                {!! Form::select('departments_id', [''=>'Select']+$departments, null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group" style="margin-bottom:5px;">
                                {!! Form::text('month', null, ['class' => 'form-control payment_month', 'id'=>'payment_month', 'placeholder'=>'Select Month']) !!}
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <br/>
                                {!! Form::submit('Download Report', ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                            </div>
                        </div>
                    </div>                         
                </div>
                {!! Form::close() !!}
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.col -->

</div><!-- /.row -->
@endsection



@section('body_bottom')
 
@endsection
