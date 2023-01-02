@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection


@section('content')
        <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {{ $page_title ?? 'Page Title' }}
                <small>{{$page_description ?? 'Page Description'}}</small>
            </h1>
           
        </section>


<div class='row'>
  <div class='col-md-12'>
    <div class="box">
      <div class="box-body ">
          <form class="form-horizontal" 
            action="{{ route('admin.appraisalTemplate.store') }}"
            method="POST"
            enctype='multipart/form-data' 
            >
            {{ csrf_field() }}

              <div class="form-group">
                <label class="control-label col-sm-2">Name: <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                  <div class="input-group">
                  <input type="text" class="form-control"  placeholder="Enter Template Name" 
                  name="name" required="">
                  <div class="input-group-addon">
                    <a href="#"><i class="fa fa-edit"></i></a>
                    </div>
                  </div>
                </div>
              </div>
             
              <div class="form-group">        
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="/admin/appraisal-template" class="btn btn-default">Cancel</a>
                </div>

              </div>

            </form>
      </div>
    </div>
  </div>
</div>
@endsection
