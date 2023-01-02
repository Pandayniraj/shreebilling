@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.leadenquirymode.enable-selected', 'id' => 'frmLeadEnquiryModeList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('admin/lead-enquiry-mode/general.page.index.table-title') }}</h3>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="{!! route('admin.leadenquirymode.create') !!}" title="{{ trans('admin/lead-enquiry-mode/general.button.create') }}">
                            <i class="fa fa-plus-square"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmLeadEnquiryModeList'].action = '{!! route('admin.leadenquirymode.enable-selected') !!}';  document.forms['frmLeadEnquiryModeList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmLeadEnquiryModeList'].action = '{!! route('admin.leadenquirymode.disable-selected') !!}';  document.forms['frmLeadEnquiryModeList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                            <i class="fa fa-ban"></i>
                        </a>

                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="LeadEnquiryMode-table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>{{ trans('admin/lead-enquiry-mode/general.columns.name') }}</th>
                                        <th>{{ trans('admin/lead-enquiry-mode/general.columns.actions') }}</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>{{ trans('admin/lead-enquiry-mode/general.columns.name') }}</th>
                                        <th>{{ trans('admin/lead-enquiry-mode/general.columns.actions') }}</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($allLeadEnquiryMode as $LeadEnquiryMode)
                                        <tr>
                                            <td align="center">{!! Form::checkbox('chkLeadEnquiryMode[]', $LeadEnquiryMode->id); !!}</td>
                                            <td>{!! link_to_route('admin.leadenquirymode.show', $LeadEnquiryMode->name, [$LeadEnquiryMode->id], []) !!}</td>
                                            <td>
                                                @if ( $LeadEnquiryMode->isEditable() || $LeadEnquiryMode->canChangePermissions() )
                                                    <a href="{!! route('admin.leadenquirymode.edit', $LeadEnquiryMode->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/lead-enquiry-mode/general.error.cant-edit-this-leadenquirymode') }}"></i>
                                                @endif

                                                @if ( $LeadEnquiryMode->enabled )
                                                    <a href="{!! route('admin.leadenquirymode.disable', $LeadEnquiryMode->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>
                                                @else
                                                    <a href="{!! route('admin.leadenquirymode.enable', $LeadEnquiryMode->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                                @endif

                                                @if ( $LeadEnquiryMode->isDeletable() )
                                                    <a href="{!! route('admin.leadenquirymode.confirm-delete', $LeadEnquiryMode->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/lead-enquiry-mode/general.error.cant-delete-this-leadenquirymode') }}"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $allLeadEnquiryMode->render() !!}
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
            checkboxes = document.getElementsByName('chkLeadEnquiryMode[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>
    
    <script>
	$(function() {
		$('#LeadEnquiryMode-table').DataTable({
			
		});
	});
	</script>

@endsection
