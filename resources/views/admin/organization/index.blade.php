@extends('layouts.master')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title }}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->
        {!! Form::open( array('route' => 'admin.organization.enable-selected') ) !!}
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Company list</h3>
                &nbsp;
                <a class="btn btn-default btn-sm" href="{!! route('admin.organization.create') !!}" title="{{ trans('admin/organization/general.button.create') }}">
                    <i class="fa fa-plus-square"></i>
                </a>

                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="organization-table">
                        <thead>
                            <tr>
                                <th style="text-align: center; width:10px">
                                    ID
                                </th>
                                <th>Organization Name</th>
                                <th>VAT ID</th>

                                <th>Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th style="text-align: center">
                                    ID
                                </th>
                                <th>Organization Name</th>
                                <th>VAT ID</th>

                                <th>Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($organizations as $organization)
                            <tr>
                                <td align="center">{!! @$organization->id !!}</td>
                                <td>{!! $organization->organization_name !!}</td>
                                <td>{!! $organization->vat_id !!}</td>
                                <td>{!! $organization->address !!}</td>
                                <td>{!! $organization->phone !!}</td>
                                <td>{!! $organization->email !!}</td>
                                <td>
                                    @if ( $organization->isEditable() || $organization->canChangePermissions() )
                                    <a href="{!! route('admin.organization.edit', $organization->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                    @else
                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/communication/general.error.cant-edit-this-organization') }}"></i>
                                    @endif

                                    @if($organization->enabled )
                                    <i class="fa fa-check-circle enabled"></i>
                                    @else
                                    <i class="fa fa-ban disabled"></i>
                                    @endif

                                    @if ( $organization->isDeletable() )
                                    <a href="{!! route('admin.organization.confirm-delete', $organization->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                    @else
                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/organization/general.error.cant-delete-this-organization') }}"></i>
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
        checkboxes = document.getElementsByName('chkOrganization[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }

</script>

<script>
    $(function() {
        $('#organization-table').DataTable({

        });
    });

</script>

@endsection
