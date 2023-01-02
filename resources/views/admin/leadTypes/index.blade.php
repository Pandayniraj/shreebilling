@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.leadtypes.enable-selected', 'id' => 'frmLeadTypeList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('admin/leadtypes/general.page.index.table-title') }}</h3>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="{!! route('admin.leadtypes.create') !!}" title="{{ trans('admin/leadtypes/general.button.create') }}">
                            <i class="fa fa-plus-square"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmLeadTypeList'].action = '{!! route('admin.leadtypes.enable-selected') !!}';  document.forms['frmLeadTypeList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmLeadTypeList'].action = '{!! route('admin.leadtypes.disable-selected') !!}';  document.forms['frmLeadTypeList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                            <i class="fa fa-ban"></i>
                        </a>

                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="leadtypes-table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>{{ trans('admin/leadtypes/general.columns.name') }}</th>
                                        <th>{{ trans('admin/leadtypes/general.columns.actions') }}</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>{{ trans('admin/leadtypes/general.columns.name') }}</th>
                                        <th>{{ trans('admin/leadtypes/general.columns.actions') }}</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($LeadTypes as $LeadType)
                                        <tr>
                                            <td align="center">{!! Form::checkbox('chkLeadType[]', $LeadType->id); !!}</td>
                                            <td>{!! link_to_route('admin.leadtypes.show', $LeadType->name, [$LeadType->id], []) !!}</td>
                                            <td>
                                                @if ( $LeadType->isEditable() || $LeadType->canChangePermissions() )
                                                    <a href="{!! route('admin.leadtypes.edit', $LeadType->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/leadtypes/general.error.cant-edit-this-leadtype') }}"></i>
                                                @endif

                                                @if ( $LeadType->enabled )
                                                    <a href="{!! route('admin.leadtypes.disable', $LeadType->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>
                                                @else
                                                    <a href="{!! route('admin.leadtypes.enable', $LeadType->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                                @endif

                                                @if ( $LeadType->isDeletable() )
                                                    <a href="{!! route('admin.leadtypes.confirm-delete', $LeadType->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/leadtypes/general.error.cant-delete-this-leadtype') }}"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $LeadTypes->render() !!}
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
            checkboxes = document.getElementsByName('chkLeadType[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>
    
    <script>
	$(function() {
		$('#leadtypes-table').DataTable({
			
		});
	});
	</script>

@endsection
