@extends('layouts.master')
@section('content')

<style>
  #tasks-table td:first-child{text-align: center !important;}
  #tasks-table td:last-child a {margin:0 2px;}
  td { text-align:center; }
</style>


        <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        
            <h1>
                Latest Tasks
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <p> Latest tasks posted</p>
             {{ TaskHelper::topSubMenu('topsubmenu.projects')}}
         <!--    <a href="" class="btn btn-default btn-xs">Back</a> -->
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

        <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
        
        
        </div><!-- /.col -->

    </div><!-- /.row -->

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
                <div class="box box-primary">
                    <div class="box-body">
                      <div class="pull-left" style="margin-top: -10px">
                            <h4>
                              <small>Total {{count($tasks)}} results</small>
                          </h4>
                      </div>
                       <div class="pull-right" >
                <form method="GET" action="/admin/projects/search/tasks/">                          
                 <div class="input-group input-group-sm hidden-xs" style="max-width: 250px; margin-bottom: 5px" >
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search projects tasks" >

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    <a href="/admin/projects/" class="btn btn-primary" title="Projects dashboard"><i class="fa fa-backward"></i></a>
                  </div>
                </div>
                </form>
               </div>
                        <div class="row">
                            <table class="table table-hover table-bordered table table-striped" id="tasks-table">
                                <thead>
                                    <tr class="bg-blue">
                                        <th>{{ trans('admin/project-task/general.columns.project') }}</th>
                                        <th>{{ trans('admin/project-task/general.columns.subject') }}</th>
                                        <th width="50px"> User</th>
                                        <th width="50px"> Progress</th>
                                        <th>{{ trans('admin/project-task/general.columns.priority') }}</th>
                                        <th>Created at</th>

                                        <th>{{ trans('admin/project-task/general.columns.end_date') }}</th>
                                       
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(isset($tasks) && !empty($tasks)){
                                         
                                            foreach($tasks as $k => $v) :
                                                echo '<tr>';
                                                    echo '<td style="text-align:left">'.$v->project->name.'</td>';
                                                   

echo  '<td style="text-align:left">'.\FinanceHelper::getAccountingPrefix('PROJECT_TASK_PRE') .$v->id. ' <a style="font-size:16.5px" href="/admin/project_task/'. $v->id. '"> '. mb_substr($v->subject,0,55). '</td>';
                                                    echo '<td><a href="/admin/profile/show/"'. $v->user_id .'">'. $v->user->first_name.'</a> </td>';

                                                    echo '<td><div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width:'. $v->percent_complete .'%"></div>
                    </div></td>';
                
                                                    echo '<td>'.$v->priority.'</td>';
                                                    
                                                    echo '<td>'.date('dS M y', strtotime($v->created_at)).'</td>';
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
                </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->


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
