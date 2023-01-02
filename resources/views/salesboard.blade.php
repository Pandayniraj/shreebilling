@extends('layouts.master')

@section('head_extra')
<!-- jVectorMap 1.2.2 -->
<link href="{{ asset("/bower_components/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css") }}" rel="stylesheet" type="text/css" />
<style>
	.filter_date { font-size:14px; }
</style>
@endsection



@section('content')
	<?php 
		$filter_course = '';
		if(\Request::get('course_id'))
			$filter_course = TaskHelper::getCourseName(\Request::get('course_id'));
	?>


<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title" }}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
        

	<div class="row">
    	<div class="col-md-12">
        	<div class="wrap">
        	{!! Form::open( ['url' => 'dashboard', 'class' => 'form-inline', 'id' => 'form_filter', 'method'=>'get'] ) !!}
                <div class="form-group" style="margin-bottom:5px;">
                    {!! Form::label('course_id', trans('admin/leads/general.columns.course_name'), ['style'=>'']) !!}
                    {!! Form::select('course_id', ['0'=>'Select Product']+$courses, \Request::get('course_id'), ['class' => 'form-control', 'id' => 'course_id']) !!}
                    <select name="years" class="form-control">
                    	<option value="0">Select Year</option>
                        @foreach($years as $yk => $yv)
                        	<option value="{{$yv->date}}" @if(\Request::get('years') && \Request::get('years') == $yv->date)selected @endif>{{$yv->date}}</option>
                        @endforeach
                    </select>
                    
                    <input type="text" name="start_date" class="form-control" id="start_date" @if(\Request::get('start_date'))  value="{{\Request::get('start_date')}}" @endif placeholder="Start Date">
                    <input type="text" name="end_date" class="form-control" id="end_date" @if(\Request::get('end_date'))  value="{{\Request::get('end_date')}}" @endif placeholder="End Date">

                    {!! Form::select('status_id', ['0'=>'Select Status']+$lead_status, \Request::get('status_id'), ['class' => 'form-control', 'id' => 'status_id']) !!}
                    
                    {{-- Form::button('Filter', ['style' => 'margin-left:5px;', 'class' => 'btn btn-primary', 'id' => 'btn-filter-course'] ) --}}
                    {!! Form::submit('Filter', ['style' => 'margin-left:5px;', 'class' => 'btn btn-primary'] ) !!}
                </div>
            {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-12'>
            <!-- SERVER HEALTH REPORT -->
            <!-- MAP & BOX PANE -->
            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">@if(\Request::get('start')) Lead Report (<span class='filter_date'>{!! \Request::get('start') !!} - {!! \Request::get('end') !!}</span> ) @elseif(\Request::get('course_id')) Lead Report (<span class='filter_date'>{!! $filter_course !!}</span> ) @else Lead Report @endif</h3>
                    
                </div><!-- /.box-header -->
                <div class="box-body no-padding"> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pad">
                                <!-- get line graph here -->
                                <div id="clients" width="800" height="250"></div>
                            </div>
                        </div><!--   /.col -->
                    </div><!-- /.row -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->

            <div class="row">
                <div class="col-md-6">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"> @if(\Request::get('start')) Leads By Source (<span class='filter_date'>{!! \Request::get('start') !!} - {!! \Request::get('end') !!}</span> ) @elseif(\Request::get('course_id')) Lead Report (<span class='filter_date'>{!! $filter_course !!}</span> ) @else Leads By Source @endif</h3>
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
                                        <div id="communication" width="300" height="250"></div>
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div> <!-- col -->
                <div class="col-md-6">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"> @if(\Request::get('start')) Leads By Product (<span class='filter_date'>{!! \Request::get('start') !!} - {!! \Request::get('end') !!}</span> ) @elseif(\Request::get('course_id')) Lead Report (<span class='filter_date'>{!! $filter_course !!}</span> ) @else Leads By Product @endif</h3>
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
                                        <div id="leadcourse" width="300" height="250"></div>
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div> <!-- col -->
            </div><!-- row -->

            <div class="row">
                <div class="col-md-6">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"> @if(\Request::get('start')) Marketing Funel (<span class='filter_date'>{!! \Request::get('start') !!} - {!! \Request::get('end') !!}</span> ) @elseif(\Request::get('course_id')) Funnel (<span class='filter_date'>{!! $filter_course !!}</span> ) @else Marketing Funel @endif</h3>
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
                                        <div id="leadstatus" width="300" height="250"></div>
                                    </div>
                                </div><!-- /.col --> 
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div> <!-- col -->
                <div class="col-md-6">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"> @if(\Request::get('start')) Leads By Rating (<span class='filter_date'>{!! \Request::get('start') !!} - {!! \Request::get('end') !!}</span> ) @elseif(\Request::get('course_id')) Lead Rating (<span class='filter_date'>{!! $filter_course !!}</span> ) @else Leads By Rating @endif</h3>
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
                                        <div id="leadrating" width="300" height="250"></div>
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div> <!-- col -->
            </div><!-- row -->

            <div class="row">
                <div class="col-md-6">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"> @if(\Request::get('start')) Leads By City (<span class='filter_date'>{!! \Request::get('start') !!} - {!! \Request::get('end') !!}</span> ) @elseif(\Request::get('course_id')) Lead City (<span class='filter_date'>{!! $filter_course !!}</span> ) @else Leads By City @endif</h3>
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
                                        <div id="leadcity" width="300" height="250"></div>
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div> <!-- col -->
            </div><!-- row -->


            <!-- LEADS STATUS -->
     <div class="row">
            <div class="col-md-6">
                <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">@if(\Request::get('start')) Leads by Users (<span class='filter_date'>{!! \Request::get('start') !!} - {!! \Request::get('end') !!}</span> ) @elseif(\Request::get('course_id')) Lead Report (<span class='filter_date'>{!! $filter_course !!}</span> ) @else Leads by User @endif</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body col-md-6">
                    @foreach($top_poster as $key => $value)
                        <h5>
                            {!! $value->name !!}
                            <small class="label label-success pull-right">{!! $value->total !!}</small>
                        </h5>
                        <div class="progress progress-xxs">
                            <div class="progress-bar progress-bar-success" style="width: {!! $value->total !!}%"></div>
                        </div>
                    @endforeach

                </div><!-- /.box-body -->
            </div>

            </div><!-- /.box -->

        </div>

        </div><!-- /.col -->


    </div><!-- /.row -->
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

$(function() {
	$('#start_date').datetimepicker({
			//inline: true,
			format: 'YYYY-MM-DD HH:mm',
			sideBySide: true
		});
	$('#end_date').datetimepicker({
			//inline: true,
			format: 'YYYY-MM-DD HH:mm',
			sideBySide: true
		});
	
	// Filter by date range submit
	$("#btn-submit-filter").on("click", function () {
		if($('#start_date').val() != '' && $('#end_date').val() != '')
		{
			var start = $('#start_date').val();
			var end = $('#end_date').val();
			var course_id1 = $('#course_id1').val();
			window.location.href = '{!!  Request::url() !!}?start='+start+'&end='+end+'&course_id1='+course_id1;
		}
	});
	
	// Filter by course
	$("#btn-filter-course").on("click", function () {
		if($('#course_id').val() != '')
		{
			var course_id = $('#course_id').val();
			window.location.href = '{!!  Request::url() !!}?course_id='+course_id;
		}
	});		
});
    //line bar

//   var barData = {
//     "labels": ["27 Feb"],
//     "datasets": [
//         {
//             "fillColor": "red",
//             "data": ["200"]
//         }
//     ]
// };

	var barData = <?php echo $line; ?>;
	//var context = document.getElementById('clients').getContext('2d');
	//var clientsChart = new Chart(context).Bar(barData);
	$(function () {
		$('#clients').highcharts({
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
				text: 'Lead Report'
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
				name: 'Leads',
				data: barData.datasets[0].data
			}]
		});
	});


	// Piechart for Communication
	var communicationsData = {!! $communicationsData !!}
	$(function () {
		$('#communication').highcharts({
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45,
					beta: 0
				}
			},
			title: {
				text: 'Leads By Source'
			},
			tooltip: {
				pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> ({point.y})'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
                    point: {
                        events: {
                           click: function() {
                              location.href = location.href = this.options.url;

                              }
                           }
                      },
					depth: 35,
					dataLabels: {
						enabled: true,
						format: '{point.name} <br/> <b>{point.percentage:.1f}%</b> ({point.y})'
					}
				}
			},
			series: [{
				type: 'pie',
				name: 'Communication',
				data: communicationsData
			}]
		});
	});
	
	
	var leadcourseData = {!! $leadcourseData !!}
	$(function () {
		$('#leadcourse').highcharts({
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45,
					beta: 0
				}
			},
			title: {
				text: 'Leads By Product'
			},
			tooltip: {
				pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> ({point.y})'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
                     point: {
                        events: {
                           click: function() {
                              location.href = location.href = this.options.url;

                              }
                           }
                      },

					depth: 35,
					dataLabels: {
						enabled: true,
						format: '{point.name} <br/> <b>{point.percentage:.1f}%</b> ({point.y})'
					}
				}
			},
			series: [{
				type: 'pie',
				name: 'Course',
				data: leadcourseData,


			}]
		});
	});

	var byStatusData = {!! $byStatusData !!}
	$(function () {

        $('#leadstatus').highcharts({
            chart: {
                type: 'funnel'
            },
            title: {
                text: 'Leads Funnel'
            },
            tooltip: {
                pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> ({point.y})'

            },
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b> ({point.y:,.0f})',
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                        softConnector: true
                    },
                    center: ['40%', '50%'],
                    neckWidth: '30%',
                    neckHeight: '25%',
                    width: '80%'
                }
            },
            legend: {
                enabled: false
            },
            series: [{
                name: 'Course',
                data: byStatusData,
                point: {
                        events: {
                           click: function() {
                              location.href = location.href = this.options.url;

                              }
                           }
                      },
            }]
        });
	});

	var byRatingData = {!! $byRatingData !!}
	$(function () {
		$('#leadrating').highcharts({
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45,
					beta: 0
				}
			},
			title: {
				text: 'Leads By Rating'
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
				name: 'Course',
				data: byRatingData,
                 point: {
                        events: {
                           click: function() {
                              location.href = location.href = this.options.url;

                              }
                           }
                      },
			}]
		});
	});

	var byCityData = {!! $byCityData !!}
	$(function () {
		$('#leadcity').highcharts({
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45,
					beta: 0
				}
			},
			title: {
				text: 'Leads By City'
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
				name: 'Lead City',
				data: byCityData
			}]
		});
	});

    

</script>


@endsection
