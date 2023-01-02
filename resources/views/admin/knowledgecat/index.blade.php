@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {!! $page_title ?? "Page Title" !!}

        <small>{!! $page_description ?? "Page Description" !!}</small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->
        {!! Form::open( array('route' => 'admin.knowledge.enable-selected', 'id' => 'frmClientList') ) !!}
        <div class="box box-primary">
            <div class="box-header with-border">

                &nbsp;
                <a class="btn btn-default" href="{!! route('admin.knowledgecat.create') !!}" title="{{ trans('admin/knowledge/general.button.create') }}">
                    <i class="fa fa-edit"></i> Create KB Category
                </a>
                &nbsp;
                <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.knowledgecat.enable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                    <i class="fa fa-check-circle"></i>
                </a>
                &nbsp;
                <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.knowledgecat.disable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                    <i class="fa fa-ban"></i>
                </a>

                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="knowledgecat-table">
                        <thead>
                            <tr>
                                <th style="text-align: center">
                                    Id
                                </th>

                                <th>Category Name</th>
                                <th>{{ trans('admin/knowledge/general.columns.created_at') }}</th>
                                <th>{{ trans('admin/knowledge/general.columns.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($knowledge) && !empty($knowledge))
                            @foreach($knowledge as $k)
                            <tr>

                                <td class="" align="center">{!! $k->id !!}</td>

                                <td class="lead"> {!! link_to_route('admin.knowledgecat.show', $k->name, [$k->id], []) !!}</td>


                                <td class="bg-info">{{ date('dS M Y', strtotime($k->created_at)) }}</td>

                                <td class="bg-warning">
                                    @if ( $k->isEditable() || $k->canChangePermissions() )
                                    <a href="{!! route('admin.knowledgecat.edit', $k->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                    @else <i class="fa fa-edit text-muted" title="{{ trans('admin/k/general.error.cant-edit-this-document') }}"></i>
                                    @endif

                                    @if ( $k->enabled )
                                    <a href="{!! route('admin.knowledgecat.disable', $k->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>

                                    @else
                                    <i class="fa fa-ban text-muted" title="{{ trans('admin/k/general.error.cant-edit-this-document') }}"></i>
                                    @endif


                                    @if ( $k->isDeletable() )
                                    <a href="{!! route('admin.knowledgecat.confirm-delete', $k->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                    @else
                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/knowledge/general.error.cant-delete-this-document') }}"></i>
                                    @endif
                                </td>

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
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }

</script>

<script>
    $(function() {
        $('#knowledgecat-table').DataTable({
            pageLength: 25
        });
    });

</script>

@endsection
