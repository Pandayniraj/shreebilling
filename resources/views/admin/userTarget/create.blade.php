@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<style>
	select { width:200px !important; }
  .col-md-6 { padding-left: 0; }
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <div class='row'>
        <div class='col-md-12'>
            <div class="box">
				      <div class="box-body">
                	{!! Form::open( ['route' => 'admin.userTarget.store', 'id' => 'form_edit_lead'] ) !!}
			
                  <div class="form-group">
                    {!! Form::label('user_id', 'Select User') !!}
                    {!! Form::select('user_id', $users, null, ['class' => 'form-control']) !!}
                  </div>
                  <div class="form-group">
                    <div class="col-md-6" style="margin-bottom: 10px; background: #ccc;">
                      <h3 style="margin:10px;">Course</h3>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 10px; background: #ccc;">
                      <h3 style="margin:10px;">Target</h3>
                    </div>
                    @foreach($products as $ck => $cv)
                    <div class="col-md-6" style="margin-bottom: 10px;">
                      <label>{{ $cv->name }}</label>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 10px;">
                      {!! Form::number('target[]', 0, ['class' => 'form-control', 'style' => 'width:20%;']) !!}
                      {!! Form::hidden('course[]', $cv->id, ['class' => 'form-control']) !!}
                    </div>
                    <div class="clearfix"></div>
                    @endforeach
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::button( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                            <a href="{!! route('admin.userTarget.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
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
	<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
	<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
    <!-- form submit -->
    @include('partials._body_bottom_submit_lead_edit_form_js')
@endsection
