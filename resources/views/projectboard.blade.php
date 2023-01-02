@extends('layouts.master')

@section('head_extra')
<!-- jVectorMap 1.2.2 -->
<link href="{{ asset("/bower_components/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css") }}" rel="stylesheet" type="text/css" />
<style>
	.filter_date { font-size:14px; }
</style>
@endsection

@section('content')
    
    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title" }}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
        
    <div class='row'>
        <div class='col-md-12'>

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">@if(\Request::get('start')) Project Tasks (<span class='filter_date'>{!! \Request::get('start') !!} - {!! \Request::get('end') !!}</span> ) @elseif(\Request::get('course_id')) Project Tasks (<span class='filter_date'>{!! $filter_course !!}</span> ) @else Project Tasks @endif</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pad">
                                <!-- get line graph here -->
                                <div id="project_tasks" width="800" height="250"></div>
                            </div>
                        </div><!--   /.col -->
                    </div><!-- /.row -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->

        </div>

        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"> @if(\Request::get('start')) Tasks By Projects (<span class='filter_date'>{!! \Request::get('start') !!} - {!! \Request::get('end') !!}</span> ) @elseif(\Request::get('course_id')) Tasks By Projects (<span class='filter_date'>{!! $filter_course !!}</span> ) @else Tasks By Projects @endif</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pad">
                                <!-- get line graph here -->
                                <div id="taskByProject" width="300" height="250"></div>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div> <!-- col -->

        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"> @if(\Request::get('start')) Tasks By Status (<span class='filter_date'>{!! \Request::get('start') !!} - {!! \Request::get('end') !!}</span> ) @elseif(\Request::get('course_id')) Tasks By Status (<span class='filter_date'>{!! $filter_course !!}</span> ) @else Tasks By Status @endif</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pad">
                                <!-- get line graph here -->
                                <div id="taskByStatus" width="300" height="250"></div>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div> <!-- col -->


        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"> @if(\Request::get('start')) Tasks By User (<span class='filter_date'>{!! \Request::get('start') !!} - {!! \Request::get('end') !!}</span> ) @elseif(\Request::get('course_id')) Tasks By User (<span class='filter_date'>{!! $filter_course !!}</span> ) @else Tasks By User @endif</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pad">
                                <!-- get line graph here -->
                                <div id="taskByUser" width="300" height="250"></div>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div> <!-- col -->

    </div>

		<div class="row">

			<div class="col-md-6">

								<div class="box box-primary">
									<div class="box-header with-border bg-info">
										<h3 class="box-title "><i class="fa fa-history"></i> Latest Project Task Comments</h3>

										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
											</button>
											<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
										</div>
									</div>
									<!-- /.box-header -->
									<div class="box-body">

										<div class="table-responsive">
											<table class="table no-margin">
												<thead>
												<tr class="bg-danger">
													<th>id</th>
													<th>Comment</th>
													
													<th><i class="fa fa-user"></i></th>
												</tr>
												</thead>
												<tbody>
												 @foreach($comments as $ank => $anv)

														<td id="{{$anv->master_id}}" class="openlink"> <a href=""  data-placement="top">OR{{ $anv->id }}</a></td>
														<td class="" style="font-size: 16.5px">

                                                            <a class="openlink" id="{{$anv->master_id}}" href="#" >{{$anv->comment_text}}</a>

                                                            <span class="openlink" id="{{$anv->master_id}}" >{!! mb_substr($anv->comment_text,150) !!}</span>


                                                        </td>
														
														<td class="">{{$anv->user->username}}</td>
													</tr>

												@endforeach
												</tbody>
											</table>
										</div>
										<!-- /.table-responsive -->
									</div>
									<!-- /.box-body -->
									<div class="box-footer text-center">

									</div>
									<!-- /.box-footer -->
								</div>

			</div>
			<div class="col-md-6">

								<div class="box box-primary">
									<div class="box-header with-border bg-danger">
										<h3 class="box-title "><i class="fa fa-tty"></i>Latest Cases<small>Happening Now</small></h3>

										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
											</button>
											<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
										</div>
									</div>
									<!-- /.box-header -->
									<div class="box-body">
										<ul class="products-list product-list-in-box">

											<table class="table no-margin">
												<thead>
												<tr>
													<th>Project</th>
													<th>User</th>
													<th>Subject</th>
													<th>Description</th>
												</tr>
												</thead>
												<tbody>
													@foreach($cases as $ank => $anv)
													<tr>
														<td><a href="#"  data-placement="top">OR{{ $anv->project->name }}</a>
                                                        </td>
														<td><a href="/admin/project_task/{{$anv->id}}" >{{ $anv->user->username }}</a></td>
														<td style="font-size: 16.5px">{{$anv->subject}}</td>
														<td>{{$anv->description}}</td>
													</tr>
												@endforeach
												</tbody>
											</table>

										</ul>
									</div>
									<!-- /.box-body -->
									<div class="box-footer text-center">

									</div>
									<!-- /.box-footer -->
								</div>

			</div>

		</div>
		<div class="row">

			<div class="col-md-6">

								<div class="box box-primary">
									<div class="box-header with-border bg-info">
										<h3 class="box-title "><i class="fa fa-history"></i> Inventory In</h3>

										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
											</button>
											<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
										</div>
									</div>
									<!-- /.box-header -->
									<div class="box-body">

										<div class="table-responsive">
											<table class="table no-margin">
												<thead>
												<tr>
													<th>Stock Id</th>
													<th>Item Name</th>
													<th>Project Name</th>
													<th>Total Stock</th>
												</tr>
												</thead>
												<tbody>
													@foreach($inventory_in as $ank => $anv)
														<td class="bg-danger">OR{{ $anv->stock_id }}</td>
														<td class="bg-danger">{{ $anv->item_name }}</td>
														<td class="bg-danger">{{$anv->project->name}}</td>
														<td class="bg-danger">{{$anv->total_stock}}</td>
													</tr>
												@endforeach
												</tbody>
											</table>
										</div>
										<!-- /.table-responsive -->
									</div>
									<!-- /.box-body -->
									<div class="box-footer text-center">

									</div>
									<!-- /.box-footer -->
								</div>
			</div>
			<div class="col-md-6">

								<div class="box box-primary">
									<div class="box-header with-border bg-danger">
										<h3 class="box-title "><i class="fa fa-tty"></i> Inventory Out</h3>

										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
											</button>
											<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
										</div>
									</div>
									<!-- /.box-header -->
									<div class="box-body">
										<ul class="products-list product-list-in-box">

											<table class="table no-margin">
												<thead>
												<tr>
													<th>Id</th>
													<th>Project</th>
													<th>Item</th>
													<th>Assigned for</th>
												</tr>
												</thead>
												<tbody>
													@foreach($inventory_out as $ank => $anv)

													<tr>
														<td>OR{{ $anv->assign_item_id }}</td>
														<td>{{ $anv->project->name }}</td>
														<td>{{$anv->stock->item_name}}</td>
														<td>{{$anv->user->username}}</td>
													</tr>

												@endforeach
												</tbody>
											</table>

										</ul>
									</div>
									<!-- /.box-body -->
									<div class="box-footer text-center">

									</div>
									<!-- /.box-footer -->
								</div>

			</div>

		</div>

@endsection


@section('body_bottom')
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <!-- ChartJS -->
    <!-- <script src="{{ asset ("/bower_components/admin-lte/plugins/chartjs/Chart.min.js") }}" type="text/javascript"></script> -->
    <script src="{{ asset ("/bower_components/highcharts/highcharts.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/highcharts/funnel.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/highcharts/highcharts-3d.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/highcharts/exporting.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/highcharts/export-data.js") }}" type="text/javascript"></script>

    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

<script>

    $('.openlink').click(function() {
        let id = this.id;
        //window.open('/admin/project_task/'+id);
        window.open('/admin/project_task/' + id, '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=0,left=100,width=999, height=660');
    });

    var barData = <?php echo $line; ?>;
    //var context = document.getElementById('clients').getContext('2d');
    //var clientsChart = new Chart(context).Bar(barData);
    $(function () {
        $('#project_tasks').highcharts({
            chart: {
                type: 'column',
                options3d: {
                    enabled: true,
                    alpha: 10,
                    beta: 25,
                    depth: 70,
                    viewDistance: 25,
                }
            },
            title: {
                text: 'Project Tasks'
            },
            subtitle: {
                //text: 'Notice the difference between a 0 value and a null point'
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    depth: 25,
                    /*dataLabels: {
                        enabled: true,
                        format: 'Lead Title'
                    }*/
                }
            },
            xAxis: {
                //categories: Highcharts.getOptions().lang.shortMonths
                categories: barData.labels
            },
            yAxis: {
                title: {
                    text: null
                }
            },
            series: [{
                name: 'Tasks',
                data: barData.datasets[0].data
            }]
        });
    });

    var taskByProject = {!! $taskByProjectData !!}
    $(function () {
        $('#taskByProject').highcharts({
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'Tasks By Projects'
            },
            tooltip: {
                pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> ({point.y})'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name} <br/> <b>{point.percentage:.1f}%</b> ({point.y})'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'TasksProjects',
                data: taskByProject
            }]
        });
    });

    var taskByStatus = {!! $taskByStatusData !!}
    $(function () {
        $('#taskByStatus').highcharts({
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'Tasks By Status'
            },
            tooltip: {
                pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> ({point.y})'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name} <br/> <b>{point.percentage:.1f}%</b> ({point.y})'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'TasksStatus',
                data: taskByStatus
            }]
        });
    });

    var taskByUser = {!! $taskByUserData !!}
    $(function () {
        $('#taskByUser').highcharts({
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'Tasks By User'
            },
            tooltip: {
                pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> ({point.y})'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name} <br/> <b>{point.percentage:.1f}%</b> ({point.y})'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'TasksUsers',
                data: taskByUser
            }]
        });
    });

</script>


@endsection
