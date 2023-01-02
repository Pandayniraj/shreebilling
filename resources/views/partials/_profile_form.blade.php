<div class="tab-pane active col-md-6" id="tab_details">
    <div class="form-group">
         <label for="inputEmail3" class="col-sm-2 control-label">
        {!! Form::label('first_name', trans('admin/users/general.columns.first_name')) !!}
        </label><div class="col-sm-10">
        {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
    </div>
    </div>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">
        {!! Form::label('last_name', trans('admin/users/general.columns.last_name')) !!}
        </label> <div class="col-sm-10">
        {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
    </div>
    </div>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">
        {!! Form::label('username', trans('admin/users/general.columns.username')) !!}
        </label> <div class="col-sm-10">
        {!! Form::text('username', null, ['class' => 'form-control']) !!}
    </div>
    </div>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">
        {!! Form::label('email', trans('admin/users/general.columns.email')) !!}
        </label> <div class="col-sm-10">
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
    </div>
    </div>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">
        {!! Form::label('password', trans('admin/users/general.columns.password')) !!}
        </label> <div class="col-sm-10">
        {!! Form::password('password', ['class' => 'form-control']) !!}
    </div>
    </div>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">
        {!! Form::label('password_confirmation', trans('admin/users/general.columns.password_confirmation')) !!}
        </label> <div class="col-sm-10">
        {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
    </div>
    </div>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">
        Phone
        </label> <div class="col-sm-10">
        {!! Form::text('phone', null, ['class' => 'form-control']) !!}
    </div>
    </div>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">
        Parent Phone
        </label> <div class="col-sm-10">
        {!! Form::text('parent_phone', null, ['class' => 'form-control']) !!}
    </div>
    </div>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">
        Date of birth
        </label> <div class="col-sm-10">
        {!! Form::text('dob', null, ['class' => 'form-control']) !!}
    </div>
    </div>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">
        Fathers Name
        </label> <div class="col-sm-10">
        {!! Form::text('father_name', null, ['class' => 'form-control']) !!}
    </div>
    </div>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">
        {!! Form::label('auth_type', trans('admin/users/general.columns.type')) !!}
        </label> <div class="col-sm-10">
        {!! Form::text('auth_type', null, ['class' => 'form-control', 'readonly']) !!}
    </div>
    </div>
</div>

<div class="col-md-6">
	<h2>Update Profile Photo</h2>                        
    <div class="form-group">
        <div style="float:left;">
            <label for="imageFileInput"><i class="fa fa-photo"></i> Profile Photo</label>
            <input type="file" id="imageFileInput" name="profilephoto">
            <span class="help-block">Choose a file jpg, png or gif only</span>
        </div>
   		@if($user->image)
        <div style="float:left">
            <label>Current Image:</label><br/>
            <div style="width:80px;">
                <img src="/images/profiles/{!! $user->image !!}" alt="" class="img-responsive" />
            </div>
        </div>
        @endif
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
    	<div class="crop-section">
            <label>Crop Image: </label>
            <div id="targetdiv">
                @if($user->image)
                    <img src="/images/profiles/{!! $user->image !!}" id="target" alt="" />
                @else
                    <img src="/images/profiles/default.png" id="target" alt="" />
                @endif
            </div>

            <div id="preview-pane">
                <div class="preview-container">
                  @if($user->image)
                    <img src="/images/profiles/{!! $user->image !!}" class="jcrop-preview" alt="Preview" />
                  @else
                    <img src="" class="jcrop-preview" alt="Preview" style="display:none;" />
                  @endif
                </div>
            </div>
        </div>
   </div>
</div>
<div class="clearfix"></div>
