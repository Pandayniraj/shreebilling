@extends('layouts.master')
@section('content')

<style>
  #tasks-table td:first-child{text-align: center !important;}
  #tasks-table td:last-child a {margin:0 2px;}
  td { text-align:center; }
</style>

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/buttons.dataTables.css") }}" rel="stylesheet" type="text/css" />

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.tasks.enable-selected', 'id' => 'frmTaskList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        &nbsp;
                        <!-- Single button -->


                <div class="btn-group">
                  <button type="button" class="btn btn-social btn-facebook">
                    <i class="fa fa-edit"></i>&nbsp;&nbsp;Add New Activity </button>
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="/admin/tasks/create?q=meeting"> <i class="fa fa-user"></i> Meeting</a></li>
                   
                    <li role="separator" class="divider"></li>
                    <li><a href="/admin/tasks/create?q=task"> <i class="fa fa-list-alt"></i> Task</a></li>
                     <li role="separator" class="divider"></li>
                    <li><a href="/admin/tasks/create?q=appointment"> <i class="fa fa-calendar-check"></i> Appointment</a></li>
                    <li role="separator" class="divider"></li>
                     <li><a href="/admin/tasks/create?q=outbound"> <i class="fa fa-phone"></i> Outbound Call</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="/admin/tasks/create?q=inbound"><i class="fa fa-phone"></i> Inbound Call</a></li>
                  </ul>
                </div>

               



                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmTaskList'].action = '{!! route('admin.tasks.enable-selected') !!}';  document.forms['frmTaskList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle" style="color:green;"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmTaskList'].action = '{!! route('admin.tasks.disable-selected') !!}';  document.forms['frmTaskList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                            <i class="fa fa-ban" style="color:orange;"></i>
                        </a>
                        &nbsp;
                        <div class="filter form-inline" style="display:inline-block; float:right;">                            
                            {!! Form::label('assign_to', trans('admin/tasks/general.columns.assigned_to')) !!}: &nbsp;
                            {!! Form::select('assign_to', ['' => 'Select user'] + $users, \Request::get('assign_to'), ['id'=>'filter-assign_to', 'class'=>'form-control']) !!}
                            &nbsp;&nbsp;
                            {!! Form::button( 'Filter', ['class' => 'btn btn-primary', 'id' => 'btn-submit-filter'] ) !!}
                            {!! Form::button( 'Clear', ['class' => 'btn btn-danger', 'id' => 'btn-filter-clear'] ) !!}
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="">
                            <table class="table table-hover table-bordered" id="tasks-table">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;width:20px !important">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>{{ trans('admin/tasks/general.columns.task_type') }}</th>
                        
                                        <th>{{ trans('admin/tasks/general.columns.task_subject') }}</th>

                                        <th>{{ trans('admin/tasks/general.columns.task_owner') }}</th>
                                        <th>{{ trans('admin/tasks/general.columns.task_status') }}</th>
                                      
                                        <th>{{ trans('admin/tasks/general.columns.task_due_date') }}</th>
                                        <th>{{ trans('admin/tasks/general.columns.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($tasks) && !empty($tasks))
                                    @foreach($tasks as $tk => $task)
                                    <tr>
                                        <td> {!! \Form::checkbox('chkTask[]', $task->id) !!} </td>
                                        <td> {{ ucfirst($task->task_type) }} </td>


                                        @if( $task->task_status === 'Completed')
                                        <td class="pull-left"><s>{!! \link_to_route('admin.tasks.show', $task->task_subject, [$task->id], []) !!} </s></td>
                                        @else

                                        <td style="text-align: left;" class="lead">{!! \link_to_route('admin.tasks.show', $task->task_subject, [$task->id], []) !!} </td>

                                        @endif


                                        <td> {{ ucfirst($task->task_owner) }}</td>

                                        <td> {{ $task->task_status }} </td>
                                        <td> <?php
                                                    $due = date('d M y',strtotime($task->task_due_date));
                                                    if($due == date('d M y'))
                                                        echo '<span style="color:red; font-weight:bold;">'.$due.'</span>';
                                                    else
                                                        echo $due;
                                              ?>
                                        </td>
                                        <td> <?php 
                                                $datas = '';
                                                if ( $task->isEditable())
                                                    $datas .= '<a href="'.route('admin.tasks.edit', $task->id).'" title="'.trans('general.button.edit').'"> <i class="fa fa-edit"></i> </a>';
                                                else
                                                    $datas .= '<i class="fa fa-edit text-muted" title="'.trans('admin/tasks/general.error.cant-edit-this-lead').'"></i>';

                                                if($task->isEnableDisable())
                                                {
                                                    if ( $task->enabled )
                                                        $datas .= '<a href="'.route('admin.tasks.disable', $task->id).'" title="'.trans('general.button.disable').'"> <i class="fa fa-check-circle enabled"></i> </a>';
                                                    else
                                                        $datas .= '<a href="'.route('admin.tasks.enable', $task->id).'" title="'.trans('general.button.enable').'"> <i class="fa fa-ban disabled"></i> </a>';
                                                }
                                                else
                                                {
                                                    if ( $task->enabled )
                                                        $datas .= '<a title="'.trans('general.button.disable').'"> <i class="fa fa-check-circle enabled"></i> </a>';
                                                    else
                                                        $datas .= '<a title="'.trans('general.button.enable').'"> <i class="fa fa-ban disabled"></i> </a>';
                                                }
                                                if ( $task->isDeletable() )
                                                    $datas .= '<a href="'.route('admin.tasks.confirm-delete', $task->id).'" data-toggle="modal" data-target="#modal_dialog" title="'.trans('general.button.delete').'"><i class="fa fa-trash deletable"></i></a>';
                                                else
                                                    $datas .= '<i class="fa fa-trash text-muted" title="'.trans('admin/tasks/general.error.cant-delete-this-lead').'"></i>';

                                                echo $datas;
                                            ?>
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
