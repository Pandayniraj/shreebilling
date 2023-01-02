@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

                {!! Form::model( $user, ['route' => ['admin.myprofile.update'],'class' => 'form-horizontal', 'method' => 'POST', 'id' => 'form_edit_user', 'enctype' => 'multipart/form-data'] ) !!}

                @include('partials._profile_form')
                
                <!-- Hidden fields for profile pic -->
                @if($user->image)
                  {!! Form::hidden('real_img', '/images/profiles/'.$user->image, ['id'=>'real_img'])!!}
                 @else
                    {!! Form::hidden('real_img', '', ['id'=>'real_img', 'style'=>'display:none;'])!!}
                 @endif
                {!! Form::hidden('x', '', array('id' => 'x')) !!}
                {!! Form::hidden('y', '', array('id' => 'y')) !!}
                {!! Form::hidden('w', '', array('id' => 'w')) !!}
                {!! Form::hidden('h', '', array('id' => 'h')) !!}
                <!-- /Hidden fields for profile pic -->
				
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                        <a href="{!! route('admin.myprofile.show') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                    </div>
                </div>

                {!! Form::close() !!}

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
	@include('partials._profile_edit_form_js')
@endsection
