@extends('layouts.master')
@section('content')

	<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Biometric Add Emplyee
                <small>{!! $page_description ?? "Add Devices" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Import User</strong>
                </div>
            </div>
            <div class="panel-body">
  <form action="/admin/biometric/adduser" method="POST">
  	@csrf
    <div class="form-group">
      <label for="email">User:</label>

      <select name="user_id" class="form-control select_box">
      	<option value="">Select User</option>
      	@foreach($users as $key=>$user)
      		
      		<option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
      	@endforeach
      </select>

    {{--   {!! Form::select('user_id',$users,null, ['class'=>'form-control'] )    !!} --}}
    </div>

      <div class="form-group">
    <label for="pwd">Password:</label>
    <input type="password" class="form-control" id="pwd" name="password">
  </div>
    <button type="submit" class="btn btn-default">Submit</button>
  </form>
</div>
</div>
</div>
</div>

<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>

<script type="text/javascript">
	 $('.select_box').select2({
            theme: 'bootstrap',
        });
</script>
@endsection