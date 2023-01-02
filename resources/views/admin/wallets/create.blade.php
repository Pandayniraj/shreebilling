@extends('layouts.master')

@section('content')
@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection



<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Users Wallets
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
          

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
        	<div class="box box-danger">
            	<div class="box-header with-border">
                    <form class="form-horizontal" action="{{ route('admin.users.wallet') }}">

                        @csrf
                        
                      <div class="form-group">
                        <label class="control-label col-sm-2" for="email">User:</label>
                        <div class="col-sm-5">
                          <select class="form-control select_box" name="user_id">
                              @foreach($users as $key=>$user)
                                <option value="{{$user->id}}">{{ $user->username }}
                                    [{{$user->id}}]
                                </option>
                              @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Amount:</label>

                        <div class="col-sm-5">
                          <input type="number" class="form-control" placeholder="Enter Amount" step="any" name="amount">

                        </div>
                      </div>
                      
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-primary">Submit</button>
                          <button type="submit" class="btn btn-default">Cancel</button>
                        </div>

                      </div>
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