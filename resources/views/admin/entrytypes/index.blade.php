
@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Voucher Entry Types
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {{ TaskHelper::topSubMenu('topsubmenu.accounts')}}
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.productcats.enable-selected', 'id' => 'frmLeadStatusList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Entry Types List</h3>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="{!! route('admin.entrytype.create') !!}" title="">
                            <i class="fa fa-plus-square"></i>
                        </a>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="productcats-table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>Label</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
          
                                <tbody>
                                    @foreach($entrytypes as $p)
                                        <tr>
                                            <td align="center">{!! Form::checkbox('chkProductCat[]', $p->id); !!}</td>
                                            <td>{{ $p->label }}</td>
                                            <td>{{ $p->name }}</td>
                                            <td>
                                                @if ( $p->isEditable() || $p->canChangePermissions() )
                                                    <a href="{!! route('admin.entrytype.edit', $p->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/leadstatus/general.error.cant-edit-this-LeadStatus') }}"></i>
                                                @endif

                                                @if ($p->isDeletable())
                                                    <a href="{!! route('admin.entrytype.confirm-delete', $p->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/leadstatus/general.error.cant-delete-this-LeadStatus') }}"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
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
            checkboxes = document.getElementsByName('chkLeadStatus[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>

    <script>
	$(function() {
		$('#LeadStatus-table').DataTable({
			
		});
	});
	</script>

@endsection
