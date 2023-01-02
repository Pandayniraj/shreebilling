@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')


    <div class='row'>
        <div class='col-md-9'>
        	<div class="box box-primary">
            	<div class="box-header with-border bg-info">
                	<h3>TC Article (#{!! $tasks->id !!}) {!! $tasks->name !!} </h3>
                   
                </div>
                <div class="box-body ">
    
                   
    
                </div><!-- /.box-body -->
            </div>

           

           

        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection

@section('body_bottom')
    <!-- Select2 js -->
    @include('partials._body_bottom_select2_js_user_search')
@endsection
