@extends('layouts.master')

@section('head_extra')
<!-- jVectorMap 1.2.2 -->
<link href="{{ asset("/bower_components/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css") }}" rel="stylesheet" type="text/css" />
<style>
	.filter_date { font-size:14px; }
</style>
@endsection

@section('content')

	Hi There
@endsection


@section('body_bottom')
	<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <!-- ChartJS -->
    <script src="{{ asset ("/bower_components/admin-lte/plugins/chartjs/Chart.min.js") }}" type="text/javascript"></script>    
    <script src="{{ asset ("/bower_components/admin-lte/plugins/highchart/highcharts.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/highchart/highcharts-3d.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/highchart/exporting.js") }}" type="text/javascript"></script>
    
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
	<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

<script>



</script>


@endsection
