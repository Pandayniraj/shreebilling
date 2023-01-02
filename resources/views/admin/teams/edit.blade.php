@extends('layouts.master')

@section('content')
   <div class='row'>
       <div class='col-md-12'>
          <div class="box">
		     <div class="box-body ">
		     	<form method="post" action="{{route('admin.teams.update',$teams->id)}}" enctype="multipart/form-data">  
		     	{{ csrf_field() }}   
		     	<div class="row">
                     <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">Team Name</label>
                        <div class="input-group">
                          <input type="text" name="name" class="form-control" placeholder="Team Name" required="" value="{{ $teams->name }}">
                          <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-database"></i></a>
                            </div>
                        </div>
                     </div>   
                 </div>
                 <div class="row">
                     <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">Team Descriptions</label>
                        <div class="input-group">
                          <textarea  name="description" class="form-control" placeholder="Team Descriptions">
                            {{ $teams->description }}
                          </textarea>
                          <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-edit (alias)"></i></a>
                            </div>
                        </div>
                     </div>   
                 </div>
                   <div class="row">
	                <div class="col-md-12">
	                    <div class="form-group">
	                        {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
	                        <a href="{!! route('admin.teams.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
	                    </div>
	                 </div>
	            </div>
		     	</form>
		     </div>
		 </div>
		</div>
    <script type="text/javascript">
       $('textarea').each(function(){
            $(this).val($(this).val().trim());
        }
    );
    </script>
@endsection