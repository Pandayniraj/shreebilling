@extends('layouts.master')

@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
	<h1>
		{!! $page_title ?? "Page title" !!}

		<small>{!! $page_description ?? "Page description" !!}</small>
	</h1>


	<br/>

	{{ TaskHelper::topSubMenu('topsubmenu.purchase')}}

	{!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>

<div class="box box-header">

	<div class="">
		<div class='row'>
			<form method="get" action="/admin/product/stocks_count">
				<div class="col-md-3">
					<div class="form-group">
						<label>Start Date</label>
						<div class="input-group">
							<input id="ReportStartdate" type="text" name="startdate" class="form-control input-sm datepicker" value="{{$startdate}}">
							<div class="input-group-addon">
								<i>
									<div class="fa fa-calendar" data-toggle="tooltip" title="Note : Leave start date as empty if you want statement from the start of the financial year.">
									</div>
								</i>
							</div>
						</div>
						<!-- /.input group -->
					</div>
					<!-- /.form group -->
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>End Date</label>

						<div class="input-group">
							<input id="ReportEnddate" type="text" name="enddate" class="form-control input-sm datepicker" value="{{$enddate}}">
							<div class="input-group-addon">
								<i>
									<div class="fa fa-calendar" data-toggle="tooltip" title="Note : Leave end date as empty if you want statement till the end of the financial year.">
									</div>
								</i>
							</div>
						</div>
						<!-- /.input group -->
					</div>
					<!-- /.form group -->
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label>Transaction Type</label>

						<div class="input-group">
							<select class="form-control" name="trans_type">
								<option value="">--SELECT--</option>
								<option value="101" {{ $trans_type == 101 ? 'selected' : '' }} >PURCHORDER</option>
								<option value="102" {{ $trans_type == 102 ? 'selected' : '' }} >PURCHINVOICE</option>
								<option value="103" {{ $trans_type == 103 ? 'selected' : '' }} >GRN</option>
								<option value="201" {{ $trans_type == 201 ? 'selected' : '' }} >SALESORDER</option>
								<option value="202" {{ $trans_type == 202 ? 'selected' : '' }} >SALESINVOICE</option>
								<option value="203" {{ $trans_type == 203 ? 'selected' : '' }} >OTHERSALESINVOICE</option>
								<option value="301" {{ $trans_type == 301 ? 'selected' : '' }} >DELIVERYORDER</option>
								<option value="401" {{ $trans_type == 401 ? 'selected' : '' }} >STOCKMOVEIN</option>
								<option value="402" {{ $trans_type == 402 ? 'selected' : '' }} >STOCKMOVEOUT</option>
								<option value="403" {{ $trans_type == 403 ? 'selected' : '' }} >OPENINGSTOCK</option>
							</select>
							<div class="input-group-addon">
								<i>
									<div class="fa fa-info-circle" data-toggle="tooltip" title="Transaction Type">
									</div>
								</i>
							</div>
						</div>
						<!-- /.input group -->
					</div>
					<!-- /.form group -->
				</div>
				<div class="col-md-3">
					<div class="form-group" style="margin-top: 25px; "> 
						<button class="btn btn-primary btn-sm" id="btn-submit-filter" type="submit"> <i
							class="fa fa-list"></i> Filter 
						</button>
						<a href="/admin/debtors_lists" class="btn btn-danger btn-sm" id="btn-filter-clear">
							<i class="fa fa-close"></i> Clear 
						</a> 
						<button class="btn btn-success btn-sm" id="btn-submit-export" type="submit"
						name="export" value="true"> <i class="fa fa-file-excel-o"></i> Export To Excel
					</button> 
				</div> 
			</div> 
		</form>
	</div>



	<div style="min-height:200px" class="" id="">
		<table class="table table-bordered table-striped">
			<thead>
				<tr class="bg-purple">
					<th class="text-center">Id</th>
					<th class="text-center">Product</th>
					<th class="text-center">Tran No #</th>
					<th class="text-center">Tran Type</th>
					<th class="text-center">Date</th>
					<th class="text-center">Store</th>
					<th class="text-center">Quantity In</th>
					<th class="text-center">Quantity Out</th>
					<th class="text-center"> <i class="fa fa- fa-hand-paper-o"></i> On Hand</th>
					<th class="text-center">Avg Price</th>
				</tr>
			</thead>
			<tbody>  
				<?php
				$sum = 0;
				$StockIn = 0;
				$StockOut = 0;
				?>
				@if(count($transations)>0)
				@foreach($transations as $result)
				<tr>
					<td align="center">{{$result->id}}</td>
					<td style="font-size: 16.5px" align="left"><a href="/admin/products/{{$result->pid}}/edit?op=trans" target="_blank"> {{$result->name}}</a></td>
					<td align="center">{{$result->order_no}}</td>
					<td align="center">
						@if($result->trans_type == PURCHORDER)
						PURCHORDER
						@elseif($result->trans_type == PURCHINVOICE)
						PURCHINVOICE
						@elseif($result->trans_type == GRN)
						GRN
						@elseif($result->trans_type == SALESORDER)
						SALESORDER
						@elseif($result->trans_type == SALESINVOICE)
						SALESINVOICE
						@elseif($result->trans_type == OTHERSALESINVOICE)
						OTHERSALESINVOICE
						@elseif($result->trans_type == DELIVERYORDER)
						DELIVERYORDER
						@elseif($result->trans_type == STOCKMOVEIN)
						STOCKMOVEIN
						@elseif($result->trans_type == STOCKMOVEOUT)
						STOCKMOVEOUT
						@elseif($result->trans_type == OPENINGSTOCK)
						OPENINGSTOCK
						@endif

					</td>
					<td align="center">{{$result->tran_date}}</td>
					<td align="center">{{$result->storename}}</td>
					<td align="center">
						@if($result->qty >0 )
						{{$result->qty}}
						<?php
						$StockIn +=$result->qty;
						?>
						@else
						-
						@endif
					</td>
					<td align="center">
						@if($result->qty <0 )
						{{str_ireplace('-','',$result->qty)}}
						<?php
						$StockOut +=$result->qty;
						?>
						@else
						-
						@endif
					</td>
					<td align="center">{{$sum += $result->qty}}</td>
					<td>0.00</td>
				</tr>
				@endforeach
				<tr>
					<td colspan="7" align="right">Total</td>
					<td align="center">{{$StockIn}}</td><td align="center">{{str_ireplace('-','',$StockOut)}}</td>
					<td align="center">{{$StockIn+$StockOut}}</td>
				</tr>
				@else
				<tr>
					<td colspan="6" class="text-center text-danger">No Transaction Yet</td>
				</tr>
				@endif

			</tbody>
		</table>

		{!! $transations->render() !!}

	</div>



	<!-- /.tab-pane -->
</div>
<!-- /.tab-content -->
</div>

<script>
	$('.datepicker').datetimepicker({
		format: 'YYYY-MM-DD',
		sideBySide: true
	});

</script>
@endsection