@extends('layouts.master')

@section('content')
   <div class='row'>
       <div class='col-md-12'>
          <div class="box">
		     <div class="box-body ">
		     	<form method="post" action="{{route('admin.doc_category.store')}}" enctype="multipart/form-data">  
		     	{{ csrf_field() }}   
		     	<div class="row">
                     <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">Category Name</label>
                        <div class="input-group">
                          <input type="text" name="name" class="form-control" placeholder="Category Name" required="">
                          <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-database"></i></a>
                            </div>
                        </div>
                     </div>   
                 </div>
                 <div class="row">
                    <div class="col-md-4 col-sm-12">
                      <div class="form-group">
                        <label for="foldername">Color</label>
                          {!! Form::select('color', ['bg-primary'=>'Blue','bg-red'=>'Red','bg-yellow'=>'yellow','bg-green'=>'Default'], null, ['class'=>'form-control']) !!}
                    </div>
                 </div>
               </div>
                 <div class="row">
                     <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">Category Descriptions</label>
                        <div class="input-group">
                          <textarea  name="description" class="form-control" placeholder="Category Descriptions"></textarea>
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
	                        <a href="{!! route('admin.doc_category.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
	                    </div>
	                 </div>
	            </div>
		     	</form>
		     </div>
		 </div>
		</div>
@endsection