@extends('layouts.master')

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
		     	<form method="post" action="{{route('admin.customergroup.store')}}" enctype="multipart/form-data">  
		     	{{ csrf_field() }}   
		     	<div class="row">
                     <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">Group Name</label>
                        <div class="input-group">
                          <input type="text" name="name" class="form-control" placeholder="Group Name" required="">
                          <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-database"></i></a>
                            </div>
                        </div>
                     </div>   
                 </div>
                 <div class="row">
                     <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">Group Name</label>
                        <div class="input-group">
                          {!! Form::select('type',['customer'=>'Customer','supplier'=>'Supplier'],null,['class'=>'form-control'] )!!}
                          <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-user-plus"></i></a>
                            </div>
                        </div>
                     </div>   
                 </div>
                 <div class="row">
                     <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">Group Descriptions</label>
                        <div class="input-group">
                          <textarea  name="description" class="form-control" placeholder="Group Descriptions"></textarea>
                          <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-edit (alias)"></i></a>
                            </div>
                        </div>
                     </div>   
                 </div>

                   <div class="row">
	                <div class="col-md-12">
	                    <div class="form-group">
	                        {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
	                        <a href="{!! route('admin.customergroup.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
	                    </div>
	                 </div>
	            </div>
		     	</form>
		     </div>
		 </div>
		</div>
@endsection