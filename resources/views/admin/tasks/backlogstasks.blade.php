@extends('layouts.master')
@section('content')

<style>
  #tasks-table td:first-child{text-align: center !important;}
  #tasks-table td:last-child a {margin:0 2px;}
  td { text-align:center; }
</style>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
                <div class="box box-primary">
                    <div class="box-body">
                      <h4> <span class="badge bg-maroon"> {{$tasks->count()}}</span> issues with this project !

                <a class="pull-right btn btn-xs btn-primary" href="/admin/projects/{{ \Request::segment(4) }}"> Back to project</a>

                      </h4>
                            <style>
                                td {padding: 4px !important; margin: 0 !important;}
                            </style>
                        <div>
                            <table class="table table-hover table-bordered table-striped" id="tasks-table">
                                <thead>
                                    <tr class="bg-olive">
                                        <th>User</th>
                                        <th>{{ trans('admin/project-task/general.columns.subject') }}</th>
                                        <th>{{ trans('admin/project-task/general.columns.priority') }}</th>
                                        <th>Estimated</th>
                                        <th>Deadline</th>
                                       
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(isset($tasks) && !empty($tasks)){
                                         
                                            foreach($tasks as $k => $v) :
                                                echo '<tr style="">';
                                                ?>
                                                    <td style="text-align:left">
                                                    <img style="width:25px; height: 25px;" class="direct-chat-img" src="{{ TaskHelper::getProfileImage($v->user_id) }}" alt="Message User Image">
                                                    </td>
                                                    
                                                   

                                            <td style="text-align:left">
                                               <h4 style="margin:0;">{{\FinanceHelper::getAccountingPrefix('PROJECT_TASK_PRE')}}{{$v->id }}<a href="JavaScript::void" onclick="window.open('/admin/project_task/{{$v->id}}', '_blank','toolbar=yes, scrollbars=yes, resizable=yes, top=0,left=100,width=999, height=660');"> {{$v->subject}}</a> <span class="pull-right badge bg-{{$v->category->color}}">{{$v->category->name}}</span> </h4>
                                                
                                            </td>

                                                <?php
                
                                                    echo '<td>'.$v->priority.'</td>';
                                                    ?>
                                                    <td>
                                                        <span class="pull-right badge bg-black">{{$v->duration}}</span>
                                                    </td>

                                                    <?php
                                                    echo '<td>'.date('dS M y', strtotime($v->end_date)).'</td>';

                                                                                   
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
                    {!! $tasks->render() !!}
                </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->

<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/buttons.server-side.js") }}"></script>

<script language="JavaScript">
	function toggleCheckbox() {
		checkboxes = document.getElementsByName('chkTask[]');
		for(var i=0, n=checkboxes.length;i<n;i++) {
			checkboxes[i].checked = !checkboxes[i].checked;
		}
	}
</script>



@endsection
