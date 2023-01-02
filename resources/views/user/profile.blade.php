@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
            Profile function               
            </h1>
            <p> Browse tabs for various functions. To change your password goto profile tab and enter your password and confirm.</p>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
    <div class='row'>
        <div class='col-md-6'>
            <div class="box-body">

                {!! Form::model( $user, ['route' => ['user.profile.patch', $user->id], 'method' => 'PATCH', 'id' => 'form_edit_user', 'enctype' => 'multipart/form-data'] ) !!}

                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bg-danger">
                            <li class="active"><a href="#tab_profile" data-toggle="tab" aria-expanded="true">{!! trans('general.tabs.profile') !!}</a></li>
                            <li class=""><a href="#tab_settings" data-toggle="tab" aria-expanded="false">{!! trans('general.tabs.settings') !!}</a></li>
                           <!--  <li class=""><a href="#tab_roles" data-toggle="tab" aria-expanded="false">{!! trans('general.tabs.roles') !!}</a></li>
                            <li class=""><a href="#tab_perms" data-toggle="tab" aria-expanded="false">{!! trans('general.tabs.perms') !!}</a></li> -->
                            <li class=""><a href="#tab_others" data-toggle="tab" aria-expanded="false">Other Functions</a></li>
                        </ul>

                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_profile">

                                <div class="form-group">
                                    {!! Form::label('first_name', trans('admin/users/general.columns.first_name')) !!}
                                    {!! Form::text('first_name', null, ['class' => 'form-control', $readOnlyIfLDAP]) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('last_name', trans('admin/users/general.columns.last_name')) !!}
                                    {!! Form::text('last_name', null, ['class' => 'form-control', $readOnlyIfLDAP]) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('username', trans('admin/users/general.columns.username')) !!}
                                    {!! Form::text('username', null, ['class' => 'form-control', $readOnlyIfLDAP]) !!}
                                </div>

                                <div class="form-group">
                                    <label>Mobile Phone <small>e.g 977XXX</small></label>
                                    {!! Form::text('phone', null, ['class' => 'form-control', $readOnlyIfLDAP]) !!}
                                </div>

                                <div class="form-group">
                                    <label> DOB <small>e.g Year-month-day</small></label>
                                    {!! Form::text('dob', null, ['class' => 'form-control datepicker', $readOnlyIfLDAP]) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('email', trans('admin/users/general.columns.email')) !!}
                                    {!! Form::text('email', null, ['class' => 'form-control', $readOnlyIfLDAP]) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('password', trans('admin/users/general.columns.password')) !!}
                                    {!! Form::password('password', ['class' => 'form-control', $readOnlyIfLDAP]) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('password_confirmation', trans('admin/users/general.columns.password_confirmation')) !!}
                                    {!! Form::password('password_confirmation', ['class' => 'form-control', $readOnlyIfLDAP]) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('auth_type', trans('admin/users/general.columns.type')) !!}
                                    {!! Form::text('auth_type', null, ['class' => 'form-control', 'readonly']) !!}
                                </div>

                                <div class="form-group">
                                    <label >Image</label>
                                     <input type="file" name="image"> 
                                        @if($user->image != '')
                                        <label>Current Logo: </label><br/>
                                        <img width="150px" height="auto" src="{{ '/images/profiles/'.$user->image }}">
                                        @endif
                                </div>

                            </div><!-- /.tab-pane -->

                            <div class="tab-pane" id="tab_settings">

                                <div class="form-group">
                                    {!! Form::label('theme', trans('admin/users/general.columns.theme')) !!}
                                    {!! Form::select( 'theme', $themes, $theme, [ 'class' => 'select-theme', 'placeholder' => trans('admin/users/general.placeholder.select-theme') ] ) !!}</td>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('time_zone', trans('admin/users/general.columns.time_zone')) !!}
                                    {!! Form::select( 'time_zone', $time_zones, $tzKey, [ 'class' => 'select-time_zone', 'placeholder' => trans('admin/users/general.placeholder.select-time_zone') ] ) !!}</td>
                                </div>

                                <div class="form-group">
                                    {!! '<input type="hidden" name="time_format" value="">' !!}
                                    {!! Form::label('time_format', trans('admin/users/general.columns.time_format')) !!}&nbsp;
                                    <label class="radio-inline"><input type="radio" name="time_format" value="12" {{("12"==$time_format)?'checked="checked"':''}}>{{trans('admin/users/general.options.12_hours')}}</label>
                                    <label class="radio-inline"><input type="radio" name="time_format" value="24" {{("24"==$time_format)?'checked="checked"':''}}>{{trans('admin/users/general.options.24_hours')}}</label>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('locale', trans('admin/users/general.columns.locale')) !!}
                                    {!! Form::select( 'locale', $locales, $locale, [ 'class' => 'select-locale', 'placeholder' => trans('admin/users/general.placeholder.select-locale') ] ) !!}</td>
                                </div>

                            </div><!-- /.tab-pane -->

                            <div class="tab-pane" id="tab_roles">
                                <div class="form-group">
                                    <div class="box-body table-responsive no-padding">
                                        <table class="table table-hover">
                                            <tbody>
                                            <tr>
                                                <th>{!! trans('admin/roles/general.columns.name')  !!}</th>
                                                <th>{!! trans('admin/roles/general.columns.description')  !!}</th>
                                                <th>{!! trans('admin/roles/general.columns.enabled')  !!}</th>
                                            </tr>
                                            @foreach($user->roles as $role)
                                                <tr>
                                                    <td>{!! $role->display_name !!}</td>
                                                    <td>{!! $role->description !!}</td>
                                                    <td>
                                                        @if($role->enabled)
                                                            <i class="fa fa-check text-green"></i>
                                                        @else
                                                            <i class="fa fa-close text-red"></i>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div><!-- /.table-responsive -->

                                </div><!-- /.form-group -->
                            </div><!-- /.tab-pane -->

                            <div class="tab-pane" id="tab_perms">
                                <div class="form-group">
                                    <div class="box-body table-responsive no-padding">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <th>{!! trans('admin/users/general.columns.name')  !!}</th>
                                                    <th>{!! trans('admin/roles/general.columns.description')  !!}</th>
                                                    <th>{!! trans('admin/roles/general.columns.enabled')  !!}</th>
                                                </tr>
                                                @foreach($perms as $perm)
                                                    @if($user->can($perm->name))
                                                        <tr>
                                                            <td>{!! $perm->display_name !!}</td>
                                                            <td>{!! $perm->description !!}</td>
                                                            <td>
                                                                @if($perm->enabled)
                                                                    <i class="fa fa-check text-green"></i>
                                                                @else
                                                                    <i class="fa fa-close text-red"></i>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- /.tab-pane -->

                            <div class="tab-pane" id="tab_others">

                                <h3> Functions</h3> 
                                <ul style="font-size: 15px">
                                    <li><a href="/admin/profile/show/{{ \Auth::user()->id}}">View my profile</a> </li> 

                                 <?php 
                                    $user_detail = \App\Models\UserDetail::where('user_id',$user->id)->first();
                                 ?>
                                @if( is_array($user_detail) &&  count($user_detail)>0)   
                                <li><a href="/admin/users/{{\Auth::user()->id}}/detail/{{$user_detail->id}}/edit" title="Details">Update My PIS</a> </li>
                                @else
                                 <li><a href="/admin/users/{{ \Auth::user()->id }}/detail" title="Details">Update My PIS</a> </li>
                                @endif



                                 <li><a href="/admin/mytimehistory">My Time change Requests</a> </li>  
                                 <li><a href="/admin/leave_management">My Leaves</a> </li>
                                 <li><a href="/admin/tarvelrequest/create">My Travel Requests</a> </li>
                            </ul>



                            </div>

                        </div><!-- /.tab-content -->

                    </div>

                <div class="form-group">
                    {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                </div>

                {!! Form::close() !!}

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection

@section('body_bottom')

<script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
        });

      });
</script>
    <!-- Select2 4.0.0 -->
    <script src="{{ asset ("/bower_components/admin-lte/select2/js/select2.min.js") }}" type="text/javascript"></script>

    <!-- Select2 js -->
    @include('partials._body_bottom_select2_js_user_settings')
@endsection
