@extends('layouts.master')

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

                <form method="post" action="/admin/tasksubcat/{{$task_sub_cat->id}}">

                    {{ csrf_field() }}

                 <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">Task Category</label>
                        {!! Form::select('task_cat_id', $task_cat,$task_sub_cat->task_cat_id , ['class' => 'form-control']) !!}
                        
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Name" value="{{$task_sub_cat->name}}">
                        
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">

                        <label for="inputEmail3" class=" control-label">
                            </label>

                            {!! Form::checkbox('enabled', '1', $task_sub_cat->enabled) !!} {!! trans('general.status.enabled') !!}

                    </div>
                    
                </div>
                <br/>
                
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                        <a href="{!! route('admin.tasksubcat.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                    </div>
                </div>
                
                </form>

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <!-- form submit -->
    @include('partials._body_bottom_submit_knowledge_edit_form_js')
@endsection
