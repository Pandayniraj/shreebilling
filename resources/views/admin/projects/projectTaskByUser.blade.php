@extends('layouts.master')
@section('content')

<style>
  #tasks-table td:first-child{text-align: center !important;}
  #tasks-table td:last-child a {margin:0 2px;}
  td { text-align:center; }
</style>

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/buttons.dataTables.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        
            <h1>
                Projects
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

 <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.projects.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">

                      <div class="btn-group">

                        
                    <a class="btn btn-default" href="/admin/projects/tasks-status/new">All New</a>
                    <a class="btn btn-default" href="/admin/projects/tasks-status/ongoing">Ongoing</a>
                    <a class="btn btn-default" href="/admin/projects/tasks-status/completed">Overdue</a>
                    <a class="btn btn-default" href="/admin/projects/tasks-status/completed">By Me</a>

                         <a href="/admin/projects" class="btn btn-default" aria-expanded="false"> <i class="fa fa-archive"></i> All Projects </a>

                        <a href="/admin/old_projects" class="btn btn-default" aria-expanded="false"> <i class="fa fa-archive"></i> Old Projects </a>


                   

                      @if(\Auth::user()->hasRole('admins'))
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-users"></i>&nbsp;&nbsp;Other Users Task
                        <span class="caret"></span>
                      </button>


                      &nbsp;
                        <a class="btn btn-default " href="{!! route('admin.projects.create') !!}" title="{{ trans('admin/projects/general.button.create') }}">
                            <i class="fa fa-plus"></i> Create Project
                        </a>

                      @endif

                      <a class="btn btn-default" href="/admin/task/create/global" title="">
                            <i class="fa fa-plus"></i> Create Task
                        </a>

                  <ul class="dropdown-menu ">
                    @foreach($users as $uk => $uv)
                    <li><a href="/admin/projects/tasks/{{$uv->id}}" target="_blank"> {{$uv->name}}</a></li>
                    @endforeach
                  </ul>

                </div>

                    </div>
                    
                </div><!-- /.box -->
            {!! Form::close() !!}
        </div><!-- /.col -->

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
                <div class="box box-primary">
                    <div class="box-body">

                        <div class="">
                            <table class="table table-hover table-bordered table-striped" id="tasks-table">
                                <thead>
                                    <tr>
                                        <th>{{ trans('admin/project-task/general.columns.project') }}</th>
                                        <th>{{ trans('admin/project-task/general.columns.subject') }}</th>
                                        <th>{{ trans('admin/project-task/general.columns.priority') }}</th>
                                        <th>Duration</th>
                                        <th>{{ trans('admin/project-task/general.columns.end_date') }}</th>
                                        <th>{{ trans('admin/project-task/general.columns.status') }}</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(isset($task_assigned) && !empty($task_assigned)){
                                         
                                            foreach($task_assigned as $k => $v) :
                                                echo '<tr>';
                                                    echo '<td>'.$v->project->name.'</td>';
                                                    echo  '<td style="text-align:left">#'. $v->id. ' <a class="lead" href="/admin/project_task/'. $v->id. '"> '. $v->subject. '</a>-'. $v->user->first_name. '</td>';
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
                                </tbody>
                            </table>
                        </div> <!-- table-responsive -->

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/dataTables.buttons.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/buttons.server-side.js") }}"></script>

<script language="JavaScript">
	function toggleCheckbox() {
		checkboxes = document.getElementsByName('chkTask[]');
		for(var i=0, n=checkboxes.length;i<n;i++) {
			checkboxes[i].checked = !checkboxes[i].checked;
		}
	}
</script>

<script>


$(function() {
	$('#tasks-table').DataTable({
		pageLength: 25
	});
});

$("#btn-submit-filter").on("click", function () {
	assign_to = $("#filter-assign_to").val();
	window.location.href = "{!! url('/') !!}/admin/tasks?assign_to="+assign_to;
});
$("#btn-filter-clear").on("click", function () {
	window.location.href = "{!! url('/') !!}/admin/tasks";
});
</script>

@endsection
