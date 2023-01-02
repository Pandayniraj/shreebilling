@extends('layouts.master')

@section('content')

<div class="panel box box-success">
  <div class="box-header with-border">
    <h4 class="box-title">
      <a class="btn btn-danger btn-social" href="/admin/orders/create?type=invoice" target="_blank">
      	<i class="fa fa-plus"></i> Add Sales
      </a>
      <a class="btn btn-primary btn-social" href="/admin/purchase/create?type=bills" target="_blank">
      	<i class="fa fa-plus"></i> Add Purchase
      </a>

    </h4>
     <div class="pull-right">
    	<form action="/admin/trading-boards" >
      <input type="text" required="" name="start_date" class="btn btn-default datepicker" style="cursor: auto;text-align: left;" placeholder="Start Date" value="{{ $dates['start']  }}">
       <input type="text" required="" name="end_date" class="btn btn-default datepicker" style="cursor: auto;text-align: left;" placeholder="End Date" value="{{ $dates['end']  }}">
       <button class="btn btn-default" type="submit">Filter</button>
   		</form>
  	</div>
  </div>
</div>


<div class="row">

<div class="col-md-8">
<div class="row">
   <div class="col-md-7">
      <div class="box">
         <div class="box-header with-border">
            <h3 class="box-title ">Sales</h3>
            <span class="pull-right">
            	{{ date('d M',strtotime($dates['start'])) }} to  {{ date('d M',strtotime($dates['end'])) }}
        	</span>
         </div>
         <div class="box-body">
            <div class="row">
               
               <div class="col-sm-12" id='salesCharts' style="height: 260px">
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-5">
      <div class="box">
         <div class="box-header with-border">
            <h3 class="box-title ">Expenses</h3>
             <span class="pull-right">
            	{{ date('d M',strtotime($dates['start'])) }} to  {{ date('d M',strtotime($dates['end'])) }}
        	</span>
         </div>
         <div class="box-body">
            <div class="row">
               
               <div class="col-sm-12" id='expenseCharts' style="height: 260px">
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">You'll Receive</h3><br>
				<span style="font-size: 20px">{{ env("APP_CURRENCY") }} {{ number_format($salesData['receiveAmount']) }}</span>
			</div>
			<div class="box-body">
				<ul class="nav nav-pills nav-stacked" >
           
            @foreach(array_slice($salesData['dataWithCustomer'],0,5) as $key=>$value)
             <li>
            <a href="#" >
            <span >{{ $value['customer']->name  }}</span>
              <span class="pull-right text-green"><i class="fa fa-angle-up"></i> {{$value['amount']}}</span>
          	</a>
          	</li>

          	@endforeach
            
          </ul>
			</div>
			<div class="box-footer text-center">
              <a href="/admin/orders?type=invoice&start_date={{$dates['start']}}&end_date={{$dates['end']}}&payment=pending_partial" target="_blank"  class="uppercase text-muted" >
              	@if(count($salesData['dataWithCustomer']) > 5)
              	+ {{ count($salesData['dataWithCustomer']) - 5 }} 
              	@endif More
              </a>
        	</div>
		</div>
	</div>
		<div class="col-md-4">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">You'll Pay</h3><br>
				<span style="font-size: 20px">
					
					{{ env("APP_CURRENCY") }} {{ number_format($purhaseData['payAmount']) }}
				</span>
			</div>
			<div class="box-body">
				<ul class="nav nav-pills nav-stacked" >
			@foreach(array_slice($purhaseData['dataWithCustomer'],0,5) as $key=>$value)
            <li style="padding: 0px;margin: 0px;">
            <a href="#" >
            <span >{{ $value['customer']->name  }}</span>
              <span class="pull-right text-red"><i class="fa fa-angle-down"></i> {{$value['amount']}}</span>
          	</a>
          	</li>
            @endforeach
          </ul>
			</div>
			<div class="box-footer text-center">
              <a href="/admin/purchase?type=bills&start_date={{$dates['start']}}&end_date={{$dates['end']}}&payment=pending_partial" target="_blank" class="uppercase text-muted" >
              	@if(count($purhaseData['dataWithCustomer']) > 5)
              	+ {{ count($purhaseData['dataWithCustomer']) - 5 }} 
              	@endif More
              </a>
        	</div>
		</div>
	</div>
		<div class="col-md-4">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Purchase</h3><br>
				<span style="font-size: 20px">{{ env("APP_CURRENCY") }} {{ number_format($purhaseData['totalSales']) }}</span>
			</div>
			<div class="box-body">
				<ul class="nav nav-pills nav-stacked" >
            @if(isset($purhaseData['allDataWithCustomer']))
			     @foreach(  array_slice($purhaseData['allDataWithCustomer'],0,5) as $key=>$value)
            <li style="padding: 0px;margin: 0px;">
            <a href="#" >
            <span >{{ $value['customer']->name  }}</span>
              <span class="pull-right text-red"><i class="fa fa-angle-down"></i> {{$value['amount']}}</span>
          	</a>
          	</li>
            @endforeach
            @endif
          </ul>
			</div>
			<div class="box-footer text-center">
              <a href="/admin/purchase?type=bills&start_date={{$dates['start']}}&end_date={{$dates['end']}}" target="_blank" 
              class="uppercase text-muted" >
              	@if(  count($purhaseData['allDataWithCustomer'] ?? []) > 5)
              	+ {{ count($purhaseData['allDataWithCustomer']) - 5 }} 
              	@endif More
              </a>
        	</div>
		</div>
	</div>
</div>



</div>

   <div class="col-md-4">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title text-muted">Stock Value</h3>
		</div>
		<div class="box-body">
			<span style="font-size: 30px;">{{env("APP_CURRENCY")}}	{{ number_format($stockInventoryData['stocksValue']) }} </span>
		</div>
	</div>

		<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title text-muted">Low Value</h3>
		</div>
		<div class="box-body">
			
           <ul class="nav nav-pills nav-stacked">
           	@foreach($stockInventoryData['lowStocks']->take(5) as $key=>$value)

            <li><a href="#">
            	{{$value->name}}
              <span class="pull-right text-red"><i class="fa fa-angle-down"></i> 
              	{{$value->qty ?? '0'}}</span></a></li>
            @endforeach
            
          </ul>
              
		</div>
		<div class="box-footer text-center">
              <a href="/admin/products?alert_qty=neg" target="_blank" class="uppercase text-muted" >
              	+ {{ $stockInventoryData['lowStocks']->count() - 5 }} More
              </a>
        </div>
	
	</div>
	</div>
</div>
@section('body_bottom')
<script src="{{ asset ("/bower_components/highcharts/highcharts.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/highcharts/funnel.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/highcharts/highcharts-3d.js") }}" type="text/javascript"></script>

    
<script type="text/javascript">
	Highcharts.chart('salesCharts', {

    title: {
        text: `{{env("APP_CURRENCY")}} {{number_format($salesData['totalSales'])}}<br>
        	<span class='text-muted' >Total Sales</span>`
    },

    xAxis: {
        tickInterval: 1,
        type: 'logarithmic',
        accessibility: {
            rangeDescription: 'Range: 1 to 10'
        }
    },

    yAxis: {
        type: 'logarithmic',

        minorTickInterval: 0.1,
        accessibility: {
            rangeDescription: 'Range: 0.1 to 1000'
        }
    },

    tooltip: {
        headerFormat: '<b>{series.name}</b><br />',
        pointFormat: 'x = {point.x}, y = {point.y}'
    },

    series: [{
        data: @php echo json_encode($salesData['graphData']);  @endphp,
        color: 'green',
        pointStart: 1,
        name:'Sales',
    }]
});


		Highcharts.chart('expenseCharts', {

    title: {
        text: '{{env("APP_CURRENCY")}} {{  number_format($expenseData['totalSales']) }}'
    },

    xAxis: {
        tickInterval: 1,
        type: 'logarithmic',
        accessibility: {
            rangeDescription: 'Range: 1 to 10'
        }
    },

    yAxis: {
        type: 'logarithmic',
        minorTickInterval: 0.1,
        accessibility: {
            rangeDescription: 'Range: 0.1 to 1000'
        }
    },

    tooltip: {
        headerFormat: '<b>{series.name}</b><br />',
        pointFormat: 'x = {point.x}, y = {point.y}'
    },

    series: [{
        data: @php echo json_encode($expenseData['graphData']);  @endphp,
        color: 'green',
        pointStart: 1,
        name:'Expenses',
    }]
});
$('.datepicker').datetimepicker({
	//inline: true,
	format: 'YYYY-MM-DD',
	sideBySide: true
});
</script>
@endsection
@endsection