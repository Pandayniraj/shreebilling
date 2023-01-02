@extends('layouts.master')

@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
   <div class='row'>
       <div class='col-md-12'>
          <div class="box">
		     <div class="box-body ">
		     	<form method="post" action="{{route('admin.folders.store')}}" enctype="multipart/form-data">  
		     	{{ csrf_field() }}   
		     	<div class="row">
                     <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">Folder Name</label>
                        <div class="input-group">
                          <input type="text" name="name" class="form-control" placeholder="Folder Name" required="">
                          <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-database"></i></a>
                            </div>
                        </div>
                     </div>   
                 </div>
                 <div class="row">
                     <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">Folder Descriptions</label>
                        <div class="input-group">
                          <textarea  name="description" class="form-control" placeholder="Folder Descriptions"></textarea>
                          <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-edit (alias)"></i></a>
                            </div>
                        </div>
                     </div>   
                 </div>

                 <div class="row">
                     <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">Shared Users</label>
                                                
                          {!!  Form::select('shared_user[]',$users,null,['class'=>'form-control','id'=>'shared_user',"multiple"=>"multiple"])  !!}

                     </div>   
                 </div>






                   <div class="row">
	                <div class="col-md-12">
	                    <div class="form-group">
	                        {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
	                        <a href="{!! route('admin.folders.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
	                    </div>
	                 </div>
	            </div>
		     	</form>
		     </div>
		 </div>
		</div>

    <script type="text/javascript">
       $('#shared_user').select2();
    </script>
@endsection