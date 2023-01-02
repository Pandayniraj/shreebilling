@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<style>
    .dropdown-menu li { background: #ccc !important; border-bottom: 1px solid; }
    .dropdown-menu li a { padding-top: 5px; padding-bottom: 5px; }
</style>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.projects.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="{!! route('admin.projects.create') !!}" title="{{ trans('admin/projects/general.button.create') }}">
                            <i class="fa fa-plus"></i> Create Project
                        </a>
                        &nbsp;
                        <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#taskAssigned" aria-expanded="false"> <i class="fa fa-tasks"></i>&nbsp;&nbsp; Assigned to me </a>

                        <a href="/old_projects/" class="btn btn-sm btn-default" aria-expanded="false"> <i class="fa fa-tasks"></i>&nbsp;&nbsp; Old Projects </a>

                       
                        <div class="btn-group">
                  <button type="button" class="btn btn-dark btn-sm dropdown-toggle label-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-tasks"></i>&nbsp;&nbsp;<strong>Other Users Task</strong> <span class="caret"></span>
                  </button>



                  <ul class="dropdown-menu ">
                    @foreach($users as $uk => $uv)
                    <li><a href="/admin/projects/tasks/{{$uv->id}}" target="_blank"> {{$uv->name}}</a></li>
                    @endforeach
                  </ul>

                </div>
                      


                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.projects.enable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.projects.disable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                            <i class="fa fa-ban"></i>
                        </a>

                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="projects-table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>{{ trans('admin/projects/general.columns.name') }}</th>
                                        <th>{{ trans('admin/projects/general.columns.assign_to') }}</th>
                                        <th>{{ trans('admin/projects/general.columns.start_date') }}</th>
                                        <th>{{ trans('admin/projects/general.columns.end_date') }}</th>
                                        <th>{{ trans('admin/projects/general.columns.status') }}</th>                                        
                                        <th>{{ trans('admin/projects/general.columns.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                        <tr>

                                            <td class="bg-warning" align="center">{!! Form::checkbox('chkClient[]', $project->id); !!}</td>
                                            <td class="bg-warning"><strong> {!! link_to_route('admin.projects.show', $project->name, [$project->id], []) !!} </strong></td>
                                            <td class="bg-warning">{!! $project->assigned->first_name.' '.$project->assigned->last_name !!}</td>

                                            <td class="bg-warning">{!! date('dS M y', strtotime($project->start_date)) !!}</td>
                                            <td class="bg-warning">{!! date('dS M y', strtotime($project->end_date)) !!}</td>
                                            <td class="bg-warning">{!! $project->status !!}</td>
                                            
                                            <td class="bg-warning">
                                                @if ( $project->isEditable() || $project->canChangePermissions() )
                                                    <a href="{!! route('admin.projects.edit', $project->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/projects/general.error.cant-edit-this-document') }}"></i>
                                                @endif

                                                @if ( $project->enabled )
                                                    <a href="{!! route('admin.projects.disable', $project->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>
                                                @else
                                                    <a href="{!! route('admin.projects.enable', $project->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                                @endif

                                                @if ( $project->isDeletable() )
                                                    <a href="{!! route('admin.projects.confirm-delete', $project->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/projects/general.error.cant-delete-this-document') }}"></i>
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



<!-- Due date modal box-->
<div id="taskAssigned" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-green">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="text-align: center; font-size: 20px;">Tasks assigned to me</h4>
      </div>
      <div class="modal-body">
        <table class="table table-hover table-bordered">
            <thead>
                <tr class="bg-success">
                    <th>{{ trans('admin/project-task/general.columns.project') }}</th>
                    <th>{{ trans('admin/project-task/general.columns.subject') }}</th>
                    <th>{{ trans('admin/project-task/general.columns.priority') }}</th>
                    <th>{{ trans('admin/project-task/general.columns.duration') }}</th>
                    <th>{{ trans('admin/project-task/general.columns.end_date') }}</th>
                    <th>{{ trans('admin/project-task/general.columns.status') }}</th>
                    <th>Actions</th>
                </tr>
                <?php
                    if(isset($task_assigned) && !empty($task_assigned)){
                     
                        foreach($task_assigned as $k => $v) :
                            echo '<tr>';
                                echo '<td>'.$v->project->name.'</td>';
                                echo '<td>'.$v->subject.'</td>';
                                echo '<td>'.$v->priority.'</td>';
                                echo '<td>'.$v->duration.'</td>';
                                echo '<td>'.date('dS M y', strtotime($v->end_date)).'</td>';

                                echo '<td>'.$v->status.'</td>';                                
                                echo '<td><a href="'.route('admin.project_task.show', $v->id).'" title="'.trans('general.button.edit').'"> <i class="fa fa-edit"></i> </a></td>';
                                
                            echo '</tr>';
                        endforeach;
                    }
                    else{
                      echo '<tr><td colspan="12" style="text-align:center;"><h3>No any task assigned to you.</h3></td></tr>';
                    }
                ?>
            </thead>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-xs" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
      </div>
    </div>    
  </div>
</div>

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
		$('#projects-table').DataTable({
            pageLength: 25
		});
	});
	</script>

@endsection
