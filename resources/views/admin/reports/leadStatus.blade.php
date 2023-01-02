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
<script>
$(function() {
	$('#start_date').datetimepicker({
			//inline: true,
			format: 'YYYY-MM-DD HH:mm',
			sideBySide: true
		});
	$('#end_date').datetimepicker({
			//inline: true,
			format: 'YYYY-MM-DD HH:mm',
			sideBySide: true
		});
});
</script>
<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body">
                {!! Form::open( ['route' => 'admin.reports.reports_post_leads_by_status', 'id' => 'form_lead_report'] ) !!}        
                <div class="content col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('status_id', trans('admin/leads/general.columns.status_id')) !!}
                                {!! Form::select('status_id', $lead_status, 1, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group" style="margin-bottom:5px;">
                                {!! Form::text('start_date', \Request::get('start'), ['class' => 'form-control', 'id'=>'start_date', 'placeholder'=>"Start Date", 'required' => 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="margin-bottom:5px;">
                                {!! Form::text('end_date', \Request::get('end'), ['class' => 'form-control', 'id'=>'end_date', 'placeholder'=>"End Date", 'required' => 'required']) !!}
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
