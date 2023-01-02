@extends('layouts.master')
@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection
@section('content')


	<div class="box">
		<div class="box-header with-border">Filter Expense</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>Classification</label>
						<select class="form-control">
							<option value="">Select Classification</option>
						</select>
					</div>

				</div>
			</div>
		</div>
	</div>




@endsection