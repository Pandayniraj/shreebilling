@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title }}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <p> Categories that applied when you create a new project </p>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.knowledge.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        
                        &nbsp;
                        <a class="btn btn-default" href="{!! route('admin.projectscat.create') !!}" title="{{ trans('admin/knowledge/general.button.create') }}">
                            <i class="fa fa-edit"></i> Create Projects Category
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
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        
                                        <th>Category Name</th>
                                        <th>{{ trans('admin/knowledge/general.columns.created_at') }}</th>
                                        <th>{{ trans('admin/knowledge/general.columns.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(isset($projects) && !empty($projects)) 
                                    @foreach($projects as $k)
                                        <tr>

                                            <td class="" align="center">{!! Form::checkbox('chkClient[]', $k->id); !!}</td>
                                          
                                            <td class="lead"> {!! link_to_route('admin.projectscat.show', $k->name, [$k->id], []) !!}</td>

                                    
                                            <td class="bg-info">{{ date('dS M Y', strtotime($k->created_at)) }}</td> 
                                            
                                            <td class="bg-warning">
                                                @if ( $k->isEditable() || $k->canChangePermissions() )
                                                    <a href="{!! route('admin.projectscat.edit', $k->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/k/general.error.cant-edit-this-document') }}"></i>
                                                @endif

                                              
                                                @if ( $k->isDeletable() )
                                                    <a href="{!! route('admin.projectscat.confirm-delete', $k->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
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
            for(var i=0, n=checkboxes.length;i<n;i++) {
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
