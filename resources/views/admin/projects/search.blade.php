@extends('layouts.master')
@section('content')

<style>
  #tasks-table td:first-child{text-align: center !important;}
  #tasks-table td:last-child a {margin:0 2px;}
  td { text-align:center; }
</style>


<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Task search result
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {{ TaskHelper::topSubMenu('topsubmenu.projects')}}
           <p> Project task search result for the term <strong>"{{ $_GET['table_search']}}"</strong>
            &nbsp; <strong>{{ $tasks->count() }}</strong> results
           </p>

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

                  <div class="row">
                    <div class="col-md-6">
                      <div class="row" style="margin-bottom: 5px" >
                        <div class="col-md-6">
                        <input type="text" name="start_date" class="form-control datepicker" placeholder="Enter Start Date" value="{{ Request::get('start_date')}}">
                      </div>
                      <div class="col-md-6">
                         <input type="text" name="end_date" class="form-control datepicker" placeholder="Enter End Date" value="{{ Request::get('end_date')??date('Y-m-d')  }}">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                 <div class="input-group input-group-sm hidden-xs" style="max-width: 250px; margin-bottom: 5px" >

                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search projects tasks" value="{{ Request::get('table_search')}}">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                     <a href="/admin/projects/search/tasks" class="btn btn-danger"><i class="fa fa-close"></i></a>
                  </div>
                </div>
              </div>
              </div>
                </form>
               </div>
                        <div class="row">
                            <table class="table table-hover table-bordered table table-striped" id="tasks-table">
                                <thead>
                                    <tr class="bg-success">
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
                                <tbody style="text-align:left">
                                    <?php
                                        if(isset($tasks) && !empty($tasks)){
                                         
                                            foreach($tasks as $k => $v) :
                                                echo '<tr style="text-align:left">';
                                                    echo '<td>'. $v->project->name.'</td>';
                                                   
                                                    echo '<td style="font-size:16.5px;text-align:left" class="openlink" id='. $v->id.'>'. mb_substr($v->subject,0,300). '</td>';
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
  <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

   <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>

<script language="JavaScript">




   $('.openlink').click(function() {
        let id = this.id;
        //window.open('/admin/project_task/'+id);
        window.open('/admin/project_task/' + id, '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=0,left=100,width=999, height=660');
    });


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

$('.datepicker').datetimepicker({
    //inline: true,
    format: 'YYYY-MM-DD',
    sideBySide: true
});
</script>

@endsection
