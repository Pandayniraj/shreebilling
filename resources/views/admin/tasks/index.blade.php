@extends('layouts.master')
@section('content')

<style>
  #tasks-table td:first-child{text-align: center !important;}
  #tasks-table td:nth-child(2){font-weight: bold !important;}
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
                        <h3 class="box-title">{{ trans('admin/tasks/general.page.index.table-title') }}</h3>
                        &nbsp;
                        <a class="btn btn-primary btn-sm" href="{!! route('admin.tasks.create') !!}" title="{{ trans('admin/tasks/general.button.create') }}">
                            <i class="fa fa-plus-square"></i>&nbsp;&nbsp;<strong>Add New Task</strong>
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmTaskList'].action = '{!! route('admin.tasks.enable-selected') !!}';  document.forms['frmTaskList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle-o" style="color:green;"></i>
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
                                        <th>{{ trans('admin/tasks/general.columns.lead') }}</th>
                                        <th>{{ trans('admin/tasks/general.columns.task_subject') }}</th>
                                        <th>{{ trans('admin/tasks/general.columns.task_status') }}</th>
                                        <th>{{ trans('admin/tasks/general.columns.task_owner') }}</th>
                                        <th>{{ trans('admin/tasks/general.columns.assigned_to') }}</th>
                                        <th>{{ trans('admin/tasks/general.columns.task_priority') }}</th>
                                        <th>{{ trans('admin/tasks/general.columns.task_due_date') }}</th>
                                        <th>{{ trans('admin/tasks/general.columns.task_complete_percent') }}</th>
                                        <th>{{ trans('admin/tasks/general.columns.actions') }}</th>
                                    </tr>
                                </thead>
                                 <tbody>
                                @if(isset($tasks) && !empty($tasks)) 
                                    @foreach($tasks as $lk => $task)
                                    <tr>                           
                                        <td  >{!! \Form::checkbox('chkLead[]', $lead->id) !!}</td>

                                        <td class="bg-success">{{ $task->lead->name }}
                                         </td>
                                        <td ><a href="/admin/tasks/{{$task->id}}">{{ mb_substr($task->task_subject,0,25) }}..</a></td>

                                        @if( $task->task_status == 'Started')
                                        <td class="" style="background-color: #4B77BE">{{ $task->task_status }}</td>
                                        @endif
                                        @if( $task->task_status == 'Completed')
                                        <td class="" style="background-color: #26A65B">{{ $task->task_status }}</td>
                                        @endif
                                        @if( $task->task_status == 'Open')
                                        <td class="" style="background-color: #8F1D21">{{ $task->task_status }}</td>
                                        @endif
                                        @if( $task->task_status == 'Processing')
                                        <td class="" style="background-color: pink">{{ $task->task_status }}</td>
                                        @endif

                                         <td class="">
                                             {{$task->owner->full_name}}
                                        </td>

                                        <td><span class="label label-default">{{$task->assigned_to->full_name}}</span></td>
                                    
                                        <td><span class="label label-success">{{ $task->task_priority }}
                                            </span>
                                        </td>
                                        <td>{!! date('dS M y', strtotime($user->task_due_date)) !!}
                                        </td>

                                       <td><div class="progress progress-xs progress-striped active">
                                              <div class="progress-bar progress-bar-success" style="width: {{$task->task_complete_percent}}%"></div>
                                           </div>
                                       </td>

                                        <td>
                                            <?php 
                                                $datas = '';
                                                if ( $task->isEditable())
                                                    $datas .= '<a href="'.route('admin.tasks.edit', $task->id).'" title="{{ trans(\'general.button.edit\') }}"> <i class="fa fa-edit"></i> </a>';
                                                else
                                                    $datas .= '<i class="fa fa-edit text-muted" title="{{ trans(\'admin/leads/general.error.cant-edit-this-lead\') }}"></i>';

                                               
                                                if ( $task->isDeletable() )
                                                    $datas .= '<a href="'.route('admin.tasks.confirm-delete', $task->id).'" data-toggle="modal" data-target="#modal_dialog" title="{{ trans(\'general.button.delete\') }}"><i class="fa fa-trash deletable"></i></a>';
                                                else
                                                    $datas .= '<i class="fa fa-trash text-muted" title="{{ trans(\'admin/leads/general.error.cant-delete-this-lead\') }}"></i>';

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

                <div style="text-align: center;"> {!! $tasks->appends(\Request::except('page'))->render() !!} </div>
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

$("#btn-submit-filter").on("click", function () {
    assign_to = $("#filter-assign_to").val();
    window.location.href = "{!! url('/') !!}/admin/tasks?assign_to="+assign_to;
});
$("#btn-filter-clear").on("click", function () {
    window.location.href = "{!! url('/') !!}/admin/tasks";
});
</script>

@endsection
