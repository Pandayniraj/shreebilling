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
   Select Year: {{-- <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong> --}}
    <select id='years'>
    	@foreach($years as $y)
    	<option value="{{ $y }}" @if($y == $thisyear) selected="" @endif>{{ $y }}</option>
    	@endforeach
    </select>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>



<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">    
		        <div class="box box-primary box-solid">
            		<div class="box-header with-border">
              			<h3 class="box-title">Monthly Installation & Complains</h3>
            		</div>
            <!-- /.box-header -->
            		<div class="box-body">
             			<div id='monthly-install-complain'></div>
            		</div>
            <!-- /.box-body -->
          		</div>
               </div>
		</div>

		<div class="row">
			<div class="col-md-6">    
		        <div class="box box-success box-solid">
            		<div class="box-header with-border">
              			<h3 class="box-title">Installations by Category</h3>
            		</div>
            <!-- /.box-header -->
            		<div class="box-body">
             			<div id='install-product'></div>
            		</div>
            <!-- /.box-body -->
          		</div>
               </div>

               <div class="col-md-6">    
		        <div class="box box-danger box-solid">
            		<div class="box-header with-border">
              			<h3 class="box-title">Complains by Products</h3>
            		</div>
            <!-- /.box-header -->
            		<div class="box-body">
             			<div id='complain-product'></div>
            		</div>
            <!-- /.box-body -->
          		</div>
               </div>
		</div>


		<div class="row">
			<div class="col-md-12">    
		        <div class="box box-primary box-solid">
            		<div class="box-header with-border">
              			<h3 class="box-title">Installation By Status</h3>
            		</div>
            <!-- /.box-header -->
            		<div class="box-body">
             			<div id='install-status'></div>
            		</div>
            <!-- /.box-body -->
          		</div>
               </div>
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
	$('#years').change(function(){
		let year = $(this).val();
		location.href = `{{ url('/') }}/admin/casesdashboard?year=${year}`;
	});

	Highcharts.chart('monthly-install-complain', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Monthly Installation & Complains, {{ $thisyear }}'
    },
    subtitle: false,
    xAxis: {
        categories: [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: false,
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: <?php echo json_encode($m_install_complain); ?>
});



Highcharts.chart('install-product', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Browser market shares in January, 2018'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Installation',
        colorByPoint: true,
        data: <?php echo json_encode($install_products_data) ?>
    }]
});

Highcharts.chart('complain-product', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Browser market shares in January, 2018'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Complains',
        colorByPoint: true,
        data: <?php echo json_encode($complain_products_data) ?>
    }]
});


Highcharts.chart('install-status', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Installation By Status'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
        }
    },
    series: [{
        name: 'Services',
        colorByPoint: true,
        data: <?php echo json_encode($status_installment_data) ?>
    }]
});

</script>

@endsection
