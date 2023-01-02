<!-- Custom Tabs -->
@php 
    $readonly = isset($readonly) ? $readonly : false; 
    $time_format = null;
    $time_zones = [];
@endphp
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs bg-info">
        <li class="active"><a href="#tab_profile" data-toggle="tab" aria-expanded="true">{!! trans('general.tabs.profile') !!}</a></li>
        <li class=""><a href="#tab_settings" data-toggle="tab" aria-expanded="false">{!! trans('general.tabs.settings') !!}</a></li>
        <li class=""><a href="#tab_roles" data-toggle="tab" aria-expanded="false">{!! trans('general.tabs.roles') !!}</a></li>
        <li class=""><a href="#tab_perms" data-toggle="tab" aria-expanded="false">{!! trans('general.tabs.perms') !!}</a></li>
        <li class=""><a href="#tab_extra" data-toggle="tab" aria-expanded="false">{!! trans('general.tabs.extra') !!}</a></li>
    </ul>
    <div class="tab-content">

        <div class="tab-pane active" id="tab_profile">
            <div class="form-group">
                {!! Form::label('first_name', trans('admin/users/general.columns.first_name')) !!}
                @if ( $user->isRoot() )
                    {!! Form::text('first_name', null, ['class' => 'form-control', 'readonly']) !!}
                @else
                    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('last_name', trans('admin/users/general.columns.last_name')) !!}
                @if ( $user->isRoot() )
                    {!! Form::text('last_name', null, ['class' => 'form-control', 'readonly']) !!}
                @else
                    {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('username', trans('admin/users/general.columns.username')) !!}
                @if ( $user->isRoot() )
                    {!! Form::text('username', null, ['class' => 'form-control', 'readonly']) !!}
                @else
                    {!! Form::text('username', null, ['class' => 'form-control']) !!}
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('email', trans('admin/users/general.columns.email')) !!}
                {!! Form::text('email', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('phone', trans('admin/users/general.columns.phone')) !!}
                {!! Form::text('phone', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('password', trans('admin/users/general.columns.password')) !!}
                {!! Form::password('password', ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('password_confirmation', trans('admin/users/general.columns.password_confirmation')) !!}
                {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                <label> Organization </label> 
                <select name="org_id" id="org_id" class="form-control select_box" required>
                    <option value="">Select Organization...</option>
                    @foreach($organization as $uk => $uv)
                    <option value="{{ $uv->id }}" @if($user && $user->org_id == $uv->id) selected @endif>{{ $uv->organization_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label> Projects </label>
                <select name="project_id" id="project_id" class="form-control select_box" required>
                    <option value="">Select Projects...</option>
                    @foreach($projects as $uk => $uv)
                    <option value="{{ $uv->id }}" @if($user && $user->project_id == $uv->id) selected @endif>{{ $uv->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label> Department </label>
                <select name="departments_id" id="departments_id" class="form-control select_box" required>
                    <option value="">Select Department...</option>
                    @foreach($departments as $uk => $uv)
                    <option value="{{ $uv->departments_id }}" @if($user && $user->departments_id == $uv->departments_id) selected @endif>{{ $uv->deptname }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label> Designation </label>
                <select name="designations_id" id="designations_id" class="form-control select_box" required>
                    <option value="">Select Designation...</option>
                    @if(isset($designations))
                        @foreach($designations as $uk => $uv)
                        <option value="{{ $uv->designations_id }}" @if($user->designations_id == $uv->designations_id) selected @endif>{{ $uv->designations }}</option>
                        @endforeach
                    @endif
                </select>
            </div>


            <div class="form-group">
                <label>Work Station </label>
                {!! Form::text('work_station', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                <label>Division </label>
                {!! Form::text('division', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                <label>Position </label>
                {!! Form::text('position', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                <label>Internal designation </label>
                {!! Form::text('int_designation', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                <label> First Line Manager </label>
                <select name="first_line_manager" id="first_line_manager" class="form-control select_box" required>
                    <option value="">Select Manager...</option>
                    @foreach($users as $uk => $uv)
                    <option value="{{ $uv->id }}" @if($user && $user->first_line_manager == $uv->id) selected @endif>{{ $uv->first_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label> Second Line Manager </label>
                <select name="second_line_manager" id="second_line_manager" class="form-control select_box" required>
                    <option value="">Select Manager...</option>
                    @foreach($users as $uk => $uv)
                    <option value="{{ $uv->id }}" @if($user && $user->second_line_manager == $uv->id) selected @endif>{{ $uv->first_name }}</option>
                    @endforeach
                </select>
            </div>


            <div class="form-group">
                {!! Form::label('auth_type', trans('admin/users/general.columns.type')) !!}
                {!! Form::text('auth_type', null, ['class' => 'form-control', 'readonly']) !!}
            </div>

            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <!-- Trick to force cleared checkbox to being posted in form! It will be posted as zero unless checked then posted again as 1 and since only last one count! -->
                        {!! '<input type="hidden" name="department_head" value="0">' !!}
                        {!! Form::checkbox('department_head', '1', $user->department_head) !!} Is line Manager
                    </label>
                </div>
            </div> 

            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <!-- Trick to force cleared checkbox to being posted in form! It will be posted as zero unless checked then posted again as 1 and since only last one count! -->
                        {!! '<input type="hidden" name="super_manager" value="0">' !!}
                        {!! Form::checkbox('super_manager', 1, $user->super_manager) !!} Super Manager
                    </label>
                </div>
            </div>   

        </div><!-- /.tab-pane -->

        <div class="tab-pane" id="tab_settings">

            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <!-- Trick to force cleared checkbox to being posted in form! It will be posted as zero unless checked then posted again as 1 and since only last one count! -->
                        {!! '<input type="hidden" name="enabled" value="0">' !!}
                        @if ( $user->isRoot() )
                            {!! Form::checkbox('enabled', '1', $user->enabled, ['disabled']) !!} {!! trans('general.status.enabled') !!}
                        @else
                            {!! Form::checkbox('enabled', '1', $user->enabled) !!} {!! trans('general.status.enabled') !!}
                        @endif
                    </label>
                </div>
            </div>


              <div class="form-group">
                <label>Payroll Method</label>
                {!! Form::select('payroll_method', ['wm'=>'Web & Mobile', 'b'=>'Biometric', 't'=>'Timeshet'], null, ['class' => 'form-control', $readonly]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('theme', trans('admin/users/general.columns.theme')) !!}
                {!! Form::select( 'theme', $themes, $user->settings()->get('theme', null), [ 'class' => 'select-theme', 'placeholder' => trans('admin/users/general.placeholder.select-theme') ] ) !!}</td>
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
                {!! Form::select( 'locale', $locales, $user->settings()->get('locale', null), [ 'class' => 'select-locale', 'placeholder' => trans('admin/users/general.placeholder.select-locale') ] ) !!}</td>
            </div>

        </div><!-- /.tab-pane -->

        <div class="tab-pane" id="tab_roles">
            <div class="form-group">
                {!! Form::hidden('selected_roles', null, [ 'id' => 'selected_roles']) !!}
                <div class="input-group select2-bootstrap-append">
                    @if ( $user->isRoot() )
                        {!! Form::select('role_search', [], null, ['class' => 'form-control', 'id' => 'role_search', 'disabled' => 'disabled',  'style' => "width: 100%"]) !!}
                    @else
                        {!! Form::select('role_search', [], null, ['class' => 'form-control', 'id' => 'role_search',  'style' => "width: 100%"]) !!}
                    @endif
                    <span class="input-group-btn">
                        <button class="btn btn-default"  id="btn-add-role" type="button">
                            <span class="fa fa-plus-square"></span>
                        </button>
                    </span>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover" id="tbl-roles">
                        <tbody>
                        <tr>
                            <th class="hidden" rowname="id">{!! trans('admin/roles/general.columns.id')  !!}</th>
                            <th>{!! trans('admin/roles/general.columns.name')  !!}</th>
                            <th>{!! trans('admin/roles/general.columns.description')  !!}</th>
                            <th>{!! trans('admin/roles/general.columns.enabled')  !!}</th>
                            <th style="text-align: right">{!! trans('admin/roles/general.columns.actions')  !!}</th>
                        </tr>
                        @foreach($user->roles as $role)
                            <tr>
                                <td class="hidden" rowname="id">{!! $role->id !!}</td>
                                <td>{!! link_to_route('admin.roles.show', $role->display_name, [$role->id], []) !!}</td>
                                <td>{!! link_to_route('admin.roles.show', $role->description, [$role->id], []) !!}</td>
                                <td>
                                    @if($role->enabled)
                                        <i class="fa fa-check text-green"></i>
                                    @else
                                        <i class="fa fa-close text-red"></i>
                                    @endif
                                </td>
                                <td style="text-align: right">
                                    @if ( $user->isRoot() )
                                        <i class="fa fa-trash-o text-muted"></i>
                                    @else
                                        <a class="btn-remove-role" href="#" title="{{ trans('general.button.remove-role') }}"><i class="fa fa-trash-o deletable"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div><!-- /.tab-pane -->

        <div class="tab-pane" id="tab_perms">
            <div class="form-group">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>{!! trans('admin/users/general.columns.name')  !!}</th>
                            <th>{!! trans('admin/users/general.columns.assigned')  !!}</th>
                            <th>{!! trans('admin/users/general.columns.effective')  !!}</th>
                        </tr>
                        @foreach($perms as $perm)
                            <tr>
                                <td>{!! link_to_route('admin.permissions.show', $perm->display_name, [$perm->id], []) !!}</td>
                                <td>
                                    @if ( $user->isRoot() )
                                        {!! Form::checkbox('perms[]', $perm->id, $user->hasPermission($perm->name), ['disabled']) !!}
                                    @else
                                        {!! Form::checkbox('perms[]', $perm->id, $user->hasPermission($perm->name)) !!}
                                    @endif

                                </td>
                                <td>
                                    @if($user->can($perm->name))
                                        <i class="fa fa-check text-green"></i>
                                    @else
                                        <i class="fa fa-close text-red"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- /.tab-pane -->

                <div class="tab-pane" id="tab_extra">
            <div class="form-group">
                <div class="box-body table-responsive no-padding">
                    <h3> QR Code </h3>
                   {{-- {!! DNS2D::getBarcodeHTML($user->id, 'QRCODE'); !!} --}}
                </div>
            </div>
        </div><!-- /.tab-pane -->

    </div><!-- /.tab-content -->  
</div>

<script>
    $(function () {
        $('#departments_id').on('change', function() {
            if($(this).val() != '')
            {
                $.ajax({
                    url: "/admin/users/ajax/GetDesignation",
                    data: { departments_id: $(this).val() },
                    dataType: "json",
                    success: function( data ) {
                        var result = data.data;
                        $('#designations_id').html(result);
                    }
                });
            }
        });
    });
</script>
