@extends('layouts.master')

@section('head_extra')

@include('partials._head_extra_select2_css')

@endsection
@section('content')

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
	<h1>
		{!! $page_title !!}
		<small>{!! $page_description !!}</small>
	</h1>
	{!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>

<div class='row'>
	<div class='col-md-12'>
		<div class="box">
			<div class="box-body">
				<form action="/admin/appraisal/objective-types/{{ $edit->id }}" method="post">

					{{ csrf_field() }}

					<div class="content col-md-9">

						<div class="row">

							<div class="col-md-12">
								<div class="form-group">  
									<label class="control-label">Objective Type Name</label>
									<div class="input-group">

										<input type="text" name="name" id="" value="{{ $edit->name }}" placeholder="Objective Type Name" class="form-control " required="required"> 
										<div class="input-group-addon">
											<a href="#"><i class="fa fa-info-circle"></i></a>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">  
									<label class="control-label">Total Points</label>
									<div class="input-group">

										<input type="number" name="points" id="" value="{{ $edit->points }}" placeholder="Total Points" class="form-control" max="100" min="5" required="required"> 
										<div class="input-group-addon">
											<a href="#"><i class="fa fa-info-circle"></i></a>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<div class="col-md-2" style="padding-left: 0;"> 
									<div class="form-check">
										<input class="form-check-input" type="checkbox" name="is_master" value="master" id="is_master" {{ $edit->is_master == 'master' ? 'checked' : '' }}>
										<label class="form-check-label" for="is_master">
											Is Master? 
										</label>
									</div>
								</div>

								<div class="col-md-6 appraisal_template" @if($edit->is_master == 'master') style="display:none;" @endif> 
									<label class="control-label">Apprisal Template: <span class="text-danger">*</span></label>
					                  <div class="input-group" style="display:inline-flex;">
						                  <select class="form-control searchable" id="appraisal_template" name="appraisal_template" @if($edit->is_master != 'master') required @endif>
						                      <option value="">Select Template</option>
						                      @foreach($templates as $key => $e)
						                        <option value="{{$e->id}}" @if($edit->template && $edit->template->temp_id == $e->id) selected @endif>{{$e->name}}</option>
						                      @endforeach
						                  </select>
					                  </div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-6" style="padding-left: 0;"> 
									<div class="form-check">
										<input class="form-check-input" type="checkbox" name="status" value="1" id="status" @if($edit->status == '1') checked @endif>
										<label class="form-check-label" for="status">
											Enable
										</label>
									</div>
								</div>
							</div>
						</div>               

					</div><!-- /.content -->

					<div class="col-md-12">
						<div class="form-group">
							<input type="Submit" value="Update Objective Type" class="btn btn-primary">
							<a href="{!! route('admin.appraisal.objective-types') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
						</div>
					</div>

				</form>
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

<script type="text/javascript">
	$(function() {
		$('#is_master').click(function(){
			let isChecked = $(this).is(':checked');
			if(isChecked)
			{
				$('.appraisal_template').css('display', 'none');
				$('#appraisal_template').prop('required',false);
			}
			else
			{
				$('.appraisal_template').css('display', 'block');
				$('#appraisal_template').prop('required',true);
			}
		});

		$('.datepicker').datetimepicker({
			 //inline: true,
			 format: 'YYYY-MM-DD', 
			 sideBySide: true,
			 allowInputToggle: true
			});

	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('.project_id').select2();
	});
</script>

@endsection

