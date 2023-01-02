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
				<form action="{!! route('admin.appraisal.objectives.store', [$objectiveType->id]) !!}" method="post">

					{{ csrf_field() }}

					<div class="content col-md-9">

						<div class="row">

							<div class="col-md-12">
								<div class="form-group">  
									<label class="control-label">Objective</label>
									<div class="input-group">
										<input type="text" name="objective" id="" value="" placeholder="Objective Type Name" class="form-control " required="required"> 
										<div class="input-group-addon">
											<a href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">  
									<label class="control-label">Mark</label>
									<div class="input-group">
										<input type="number" name="marks" id="" value="" placeholder="Total Points" class="form-control" max="100" min="5" required="required"> 
										<div class="input-group-addon">
											<a href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
										</div>
									</div>
								</div>
							</div>
						</div>               

					</div><!-- /.content -->

					<div class="col-md-12">
						<div class="form-group">
							<input type="Submit" value="Create Objective Type" class="btn btn-primary">
							<a href="{!! route('admin.appraisal.objectives', [$objectiveType->id]) !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
						</div>
					</div>

				</form>
			</div>
		</div><!-- /.box-body -->
	</div><!-- /.col -->

</div><!-- /.row -->

@endsection
