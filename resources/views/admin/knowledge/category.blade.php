@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

    <div class='row'>

<div class='col-md-3'>

    <div class="box-body">
              <table class="table table-bordered">
                <tbody><tr>
                  
                  <th>Category</th>
                  <th style="width: 40px">Count</th>
                </tr>

            @foreach($cat as $k => $v)  
                <tr>
                  <td><a href="/admin/knowledge/cat/{{$v->id}}">{{ $v->name }}</a></td>
                  
                  <td><span class="badge bg-blue">{{ $v->knowl->count() }}</span></td>
                </tr>
            @endforeach    


              </tbody></table>
            </div>

</div>

        <div class='col-md-9'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.knowledge.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="{!! route('admin.knowledge.create') !!}" title="{{ trans('admin/knowledge/general.button.create') }}">
                            <i class="fa fa-plus"></i> Create KB
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.knowledge.enable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.knowledge.disable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                            <i class="fa fa-ban"></i>
                        </a>

                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="knowledge-table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        
                                        <th>{{ trans('admin/knowledge/general.columns.title') }}</th>
                                        <th>{{ trans('admin/knowledge/general.columns.author_id') }}</th>
                                        <th>{{ trans('admin/knowledge/general.columns.created_at') }}</th>
                                        <th>{{ trans('admin/knowledge/general.columns.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(isset($knowledge) && !empty($knowledge)) 
                                    @foreach($knowledge as $k)
                                        <tr>

                                            <td class="" align="center">{!! Form::checkbox('chkClient[]', $k->id); !!}</td>
                                          
                                            <td class="lead"> {!! link_to_route('admin.knowledge.show', $k->title, [$k->id], []) !!}</td>

                                            <td class="">{!! $k->author['first_name'].' '.$k->author['last_name'] !!}</td>
                                            <td class="">{{ date('dS M Y', strtotime($k->created_at)) }}</td> 
                                            
                                            <td class="">
                                                @if ( $k->isEditable() || $k->canChangePermissions() )
                                                    <a href="{!! route('admin.knowledge.edit', $k->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/k/general.error.cant-edit-this-document') }}"></i>
                                                @endif

                                                @if ( $k->enabled )
                                                    <a href="{!! route('admin.knowledge.disable', $k->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>
                                                @else
                                                    <a href="{!! route('admin.knowledge.enable', $k->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                                @endif

                                                @if ( $k->isDeletable() )
                                                    <a href="{!! route('admin.knowledge.confirm-delete', $k->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
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
		$('#Knowledge-table').DataTable({
            pageLength: 25
		});
	});
	</script>

@endsection
