@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.bugs.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="{!! route('admin.bugs.create') !!}" title="{{ trans('admin/bugs/general.button.create') }}">
                            <i class="fa fa-plus"></i> Create Bug
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.bugs.enable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.bugs.disable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                            <i class="fa fa-ban"></i>
                        </a>

                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="bugs-table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>{{ trans('admin/bugs/general.columns.status') }}</th>
                                        <th>{{ trans('admin/bugs/general.columns.subject') }}</th>
                                        <th>{{ trans('admin/bugs/general.columns.type') }}</th>
                                        <th>{{ trans('admin/bugs/general.columns.source') }}</th>
                                       
                                        
                                        <th>{{ trans('admin/bugs/general.columns.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(isset($bugs) && !empty($bugs)) 
                                    @foreach($bugs as $bug)
                                        <tr>

                                            @if($bug->viewed == '0')
                                            <td class="bg-warning" align="center">{!! Form::checkbox('chkClient[]', $bug->id); !!}</td>
                                            <td class="bg-warning">{!! $bug->status !!}</td>
                                            <td class="bg-warning"> {!! link_to_route('admin.bugs.show', $bug->subject, [$bug->id], []) !!}</td>

                                            <td class="bg-warning">{!! $bug->type !!}</td>
                                            <td class="bg-warning">{!! $bug->source !!}</td>

                                          
                                            
                                            <td class="bg-warning">
                                                @if ( $bug->isEditable() || $bug->canChangePermissions() )
                                                    <a href="{!! route('admin.bugs.edit', $bug->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/bugs/general.error.cant-edit-this-document') }}"></i>
                                                @endif

                                                @if ( $bug->enabled )
                                                    <a href="{!! route('admin.bugs.disable', $bug->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>
                                                @else
                                                    <a href="{!! route('admin.bugs.enable', $bug->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                                @endif

                                                @if ( $bug->isDeletable() )
                                                    <a href="{!! route('admin.bugs.confirm-delete', $bug->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/bugs/general.error.cant-delete-this-document') }}"></i>
                                                @endif
                                            </td>



                                               @else

                                               <td align="center">{!! Form::checkbox('chkClient[]', $bug->id); !!}</td>
                                            <td>{!! $bug->status !!}</td>
                                            <td> {!! link_to_route('admin.bugs.show', $bug->subject, [$bug->id], []) !!}</td>

                                            <td>{!! $bug->type !!}</td>
                                            <td>{!! $bug->source !!}</td>
                                            
                                            <td>
                                                @if ( $bug->isEditable() || $bug->canChangePermissions() )
                                                    <a href="{!! route('admin.bugs.edit', $bug->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/bugs/general.error.cant-edit-this-document') }}"></i>
                                                @endif

                                                @if ( $bug->enabled )
                                                    <a href="{!! route('admin.bugs.disable', $bug->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>
                                                @else
                                                    <a href="{!! route('admin.bugs.enable', $bug->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                                @endif

                                                @if ( $bug->isDeletable() )
                                                    <a href="{!! route('admin.bugs.confirm-delete', $bug->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/bugs/general.error.cant-delete-this-document') }}"></i>
                                                @endif
                                            </td>


                                               @endif 

                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </div> <!-- table-responsive -->

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            {!! Form::close() !!}
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

    <script language="JavaScript">
        function toggleCheckbox() {
            checkboxes = document.getElementsByName('chkClient[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>

    <script>
	$(function() {
		$('#bugs-table').DataTable({
            pageLength: 25
		});
	});
	</script>

@endsection
