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
    Current Fiscal Year: <strong>
     {!! Form::select('fiscal_year', $all_fiscal_year,
     \Request::get('fiscal_year') ?? FinanceHelper::cur_fisc_yr()->id, ['id'=>'active_fiscal_year']) !!}</strong>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>

  <?php
      $startOfYear = FinanceHelper::cur_fisc_yr()->start_date;
      $endOfYear   = FinanceHelper::cur_fisc_yr()->end_date;
  ?>


 <div class='row'>
        <div class='col-md-12'>
           
            <div class="row">
            	<div class="col-md-6">
		            
		            <div class="box box-default box-solid">
		                <div class="box-header">
		                    <h1 class="box-title" align="center">Bank &amp; Cash Accounts</h1>
		                </div>
		                <div class="box-body no-padding">
		                    <div id="bank_table_wrapper" class="dataTables_wrapper form-inline" role="grid">
		                    	<div class="row">
		                    		<div class="col-md-6 text-left"></div>
		                    		<div class="col-md-6 text-right"></div>
		                    	</div>
		                    	<div class="dataTables_scroll">
		                    		<div class="dataTables_scrollHead" style="overflow: hidden; position: relative; border: 0px; width: 100%;">
		                    			<div class="dataTables_scrollHeadInner" style="width: 418px; padding-right: 17px;">
		                    				<table class="table table-bordered table-condensed dataTable" style="margin-left: 0px; width: 418px;">
		                    					<thead>
		                                            <tr role="row">
		                                            	<th style="display: none; width: 0px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1"></th>
		                                            	<th style="display: none; width: 0px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1"></th>
		                                            </tr>
		                                        </thead>
		                                    </table>
		                                </div>
		                            </div>
		                            <div class="dataTables_scrollBody" style="overflow: auto; height: 193px; width: 100%;">
		                            	<table class="table table-bordered table-condensed dataTable" id="bank_table" style="margin-left: 0px; width: 100%;">
		                            		<thead>
		                                         <tr role="row" style="height: 0px;">
		                                         	<th style="display: none; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px; width: 0px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1"></th>
		                                         	<th style="display: none; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px; width: 0px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1">	
		                                         	</th>
		                                         </tr>
		                                    </thead>  
		                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
		                                    	@foreach($bankandcash as $k => $v)
		                                    	<tr id="{{$lcb['code'] ?? ''}}" class="odd">
		                                    		<td style="font-size: 16.5px" class=" "> <i class="fa fa-building"></i> {{ $v->name }}</td>
		                                    		<td style="font-size: 16.5px"class=" "><strong>{{env(APP_CURRENCY)}} {{ number_format($v->amount,2) }}</strong></td>
		                                    	</tr>
		                                    	@endforeach
		                                    </tbody>
		                                </table>
		                            </div>
		                        </div>

		                        <div class="row">
		                        	<div class="col-md-6 text-left"></div>
		                        	<div class="col-md-6 text-right"></div>
		                        </div>
		                    </div>
		                </div>
		            </div>

		             <div class="box box-solid box-danger">
		                <div class="box-header">
		                    <h1 class="box-title" align="center">Total Expenses and Income</h1>
		                </div>
		                <div class="box-body no-padding">
		                    <div id="bank_table_wrapper" class="dataTables_wrapper form-inline" role="grid">
		                    	<div class="row">
		                    		<div class="col-md-6 text-left"></div>
		                    		<div class="col-md-6 text-right"></div>
		                    	</div>
		                    	<div class="dataTables_scroll">
		                    		<div class="dataTables_scrollHead" style="overflow: hidden; position: relative; border: 0px; width: 100%;">
		                    			<div class="dataTables_scrollHeadInner" style="width: 418px; padding-right: 17px;">
		                    				<table class="table table-bordered table-condensed dataTable" style="margin-left: 0px; width: 418px;">
		                    					<thead>
		                                            <tr role="row">
		                                            	<th style="display: none; width: 0px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1"></th>
		                                            	<th style="display: none; width: 0px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1"></th>
		                                            </tr>
		                                        </thead>
		                                    </table>
		                                </div>
		                            </div>
		                            <div class="dataTables_scrollBody" style="overflow: auto; height: 193px; width: 100%;">
		                            	<table class="table table-bordered table-condensed dataTable" id="bank_table" style="margin-left: 0px; width: 100%;">
		                            		<thead>
		                                         <tr role="row" style="height: 0px;">
		                                         	<th style="display: none; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px; width: 0px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1"></th>
		                                         	<th style="display: none; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px; width: 0px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1">	
		                                         	</th>
		                                         </tr>
		                                    </thead>  

             <tbody role="alert" aria-live="polite" aria-relevant="all">
            	
            	<tr id="{{$lcb['code']?? ''}}" class="odd">
            		<td style="font-size: 16.5px; vertical-align: middle;" class=" "> <i class="fa fa-building"></i>Total Income Of This Year</td>
            		<td style="font-size: 16.5px" class=" "><strong>
                  
                        @foreach($TotalYearlyIncome as $curr=>$value)
                          {{$curr}}  {{ $value }} <br> 
                        @endforeach
                          @if(empty($TotalYearlyIncome)) 0 @endif 
                    </strong></td>
            	</tr>
            	<tr id="{{$lcb['code']??''}}" class="odd">
            		<td style="font-size: 16.5px;vertical-align: middle;" class=" "> <i class="fa fa-building"></i>Total Expense Of This Year</td>
            		<td style="font-size: 16.5px" class=" "><strong>
                        @foreach($TotalYearlyExpense as $curr=>$value)
                          {{$curr}}  {{ $value }} <br> 
                        @endforeach 
                         @if(empty($TotalYearlyExpense)) 0 @endif     
                    </strong></td>
            	</tr>
            	<tr id="{{$lcb['code']??''}}" class="odd">
            		<td style="font-size: 16.5px;vertical-align: middle;" class=" "> <i class="fa fa-building"></i>Total Income Of This Month</td>
            		<td style="font-size: 16.5px" class=" "><strong>
                  
                        @foreach($TotalMonthlyIncome as $curr=>$value)
                          {{$curr}}  {{ $value }} <br> 
                        @endforeach

                        @if(empty($TotalMonthlyIncome)) 0 @endif
                    </strong></td>
            	</tr>
            	<tr id="{{$lcb['code']??''}}" class="odd">
            		<td style="font-size: 16.5px;vertical-align: middle;" class=" "> <i class="fa fa-building"></i>Total Expense Of This Month</td>
            		<td style="font-size: 16.5px" class=" "><strong>
                  
                        @foreach($TotalMonthlyExpense as $curr=>$value)
                          {{$curr}}  {{ $value }} <br> 
                        @endforeach

                        @if(empty($TotalMonthlyExpense)) 0 @endif

                    </strong></td>
            	</tr>

            </tbody>
		                                </table>
		                            </div>
		                        </div>

		                        <div class="row">
		                        	<div class="col-md-6 text-left"></div>
		                        	<div class="col-md-6 text-right"></div>
		                        </div>
		                    </div>
		                </div>
		            </div>

               </div>
                <div class="col-md-6">
                    <div class="box box-info box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Balance Summary By Groups</h3>
                           
                        </div><!-- /.box-header -->
                        <div class="box-body no-padding">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pad">
                                        <!-- get line graph here -->
                                        <div id="balancegroups" width="300" height="250"></div>
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
                            <h3 class="box-title">Balance Summary By Ledgers</h3>
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
                                        <div id="balanceledgers" width="300" height="250"></div>
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div> <!-- col -->

                 <div class="col-md-6">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cash Flow</h3>
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
                                        <div id="cashflow" width="300" height="250"></div>
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div> <!-- col -->
               
            </div><!-- row -->

              <div class="row">
            	
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Income And Expenses</h3>
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
                                        <div id="incomeexpenses" width="300" height="250"></div>
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div> <!-- col -->
               
            </div><!-- row -->


            <div class="row">
                
                <div class="col-md-6">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Sundry Debtors (AR)</h3>
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
                                        <div id="sundry_debtors_chart" width="300" height="250"></div>
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div> <!-- col -->

                 <div class="col-md-6">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Sundry Creditors (AP)</h3>
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
                                        <div id="sundry_creditors_chart" width="300" height="250"></div>
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div> <!-- col -->
               
            </div><!-- row -->


        </div>
</div>

<?php 
           $startDay = \Carbon\Carbon::now();

          $firstDay = $startDay->firstOfMonth();

          $monthname=date('F', strtotime($firstDay));

          $startOfYear = $startDay->copy()->startOfYear();

          $yearname=  date('Y', strtotime($startOfYear))

?>




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

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

<script>

   var parent_groupsData1 = {!! $parent_groupsData !!}
     $(function () {
		$('#balancegroups').highcharts({
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45,
					beta: 0
				}
			},
			title: {
				text: 'Balance Summary By Groups'
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
				name: 'Communication',
				data: parent_groupsData1,
			}]
		});
	});


    var parent_ledgersData = {!! $parent_ledgersData !!}
     $(function () {
		$('#balanceledgers').highcharts({
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45,
					beta: 0
				}
			},
			title: {
				text: 'Balance Summary By Ledgers'
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
				name: 'Communication',
				data: parent_ledgersData,
			}]
		});
	});

   var incomeCashFlowMonth = {!! $incomeCashFlowMonth !!}
    var expenseCashFlowMonth = {!! $expenseCashFlowMonth !!}

                Highcharts.chart('cashflow', {
					    chart: {
					        type: 'line'
					    },
					    title: {
					        text: 'Cash Flow '
					    },
					    subtitle: {
					        text: 'Cash Flow of {{$yearname}}'
					    },
					    xAxis: {
					        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
					    },
					    yAxis: {
					        title: {
					            text: 'Amount ({{env("APP_CURRENCY")}})'
					        }
					    },
					    plotOptions: {
					        line: {
					            dataLabels: {
					                enabled: true
					            },
					            enableMouseTracking: false
					        }
					    },
					    series: [{
					        name: 'Income',
					        data: incomeCashFlowMonth
					    }, {
					        name: 'Expense',
					        data: expenseCashFlowMonth
					    }]
		         });


                var incomeCashFlowdays = {!! $incomeCashFlowMonthlyData !!}
                var expenseCashFlowdays = {!! $expenseCashFlowMonthlyData !!}


            Highcharts.chart('incomeexpenses', {
					    chart: {
					        type: 'line'
					    },
					    title: {
					        text: 'Income And Expense'
					    },
					    subtitle: {
					        text: '{{$monthname}}'
					    },
					    xAxis: {
					        categories: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12','13','14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25','26','27','28','29','30','31','32']
					    },
					    yAxis: {
					        title: {
					            text: 'Amount ({{env("APP_CURRENCY")}})'
					        }
					    },
					    plotOptions: {
					        line: {
					            dataLabels: {
					                enabled: true
					            },
					            enableMouseTracking: false
					        }
					    },
					    series: [{
					        name: 'Income',
					        data: incomeCashFlowdays
					    }, {
					        name: 'Expense',
					        data: expenseCashFlowdays
					    }]
		         });
	
        
$('#active_fiscal_year').change(function(){
  let val = $(this).val();
  location.href = `/admin/finance/dashboard/?fiscal_year=${val}`;
});

$(function(){

    $('#sundry_creditors_chart').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'Sundry Creditors (AP)'
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
            name: 'sundry_creditor',
            data: <?php echo $sundry_creditor; ?>,
        }]
    });


     $('#sundry_debtors_chart').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'Sundry Debtors (AR)'
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
            name: 'Sundry_Debtors',
            data: <?php echo $sundry_debitors; ?>,
        }]
    });

});


</script>


@endsection