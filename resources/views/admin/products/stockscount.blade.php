@extends('layouts.master')

@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')
@endsection

@section('content')
{{-- <link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" /> --}}
{{-- <link href="{{ asset("/bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.css") }}" rel="stylesheet" type="text/css" /> --}}
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
		<table class="table table-hover table-striped table-responsive">
			<thead>
				<tr class="bg-purple">
					<th class="text-center">Id</th>
					<th class="text-center">Product</th>
					<th class="text-center">Tran #</th>
					<th class="text-center">Tran Type</th>
					<th class="text-center">Date</th>
					<th class="text-center">Store</th>
					<th class="text-center">In</th>
					<th class="text-center">Out</th>
					<th class="text-center bg-primary"> Closing(bottle-wise)</th>
					<th class="text-center bg-primary"> Closing(case-wise)</th>
					<th class="text-center">cost/unit</th>
					<th class="text-center">Action</th>

				</tr>
			</thead>
			<tbody>
				<?php
				
				
				$count=0;
				$transations=$transations->groupby('stock_id');
				?>
				
				@if(count($transations)>0)
				@foreach($transations as $key=> $transation)
				<?php
					$sum = 0;
				?>
					@foreach($transation as $k=> $result)
					
					<?php
					$StockIn = 0;
						$StockOut = 0;
					?>
				<tr>
					<td align="center">{{$loop->iteration}}</td>
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
						<?php
						$qty=0;
						?>
							@if($result->trans_type == 101 || $result->trans_type == 102)	
							{{$qty+=$result->qty * $result->unit->qty_count}}
							@else
							{{$qty+=$result->qty}}
							@endif
						
						<?php
						$StockIn +=$qty;
						?>
						@else
						-
						@endif
					</td>
					<td align="center">
						@if($result->qty <0 )
						<?php
						$outqty=0;
						?>
							@if($result->trans_type == SALESINVOICE || $result->trans_type == OTHERSALESINVOICE)	
							
							{{ str_ireplace('-','',$outqty+= $result->qty * $result->unit->qty_count)}}
							@else
							
							{{str_ireplace('-','',$outqty+= $result->qty)}}
							
							@endif
						<?php
						$StockOut +=$outqty;
						?>
						@else
						-
						@endif
					</td>

					<td align="center" class="bg-primary">
						
						<?php 
							$sum+=$StockIn+$StockOut;
						?>	 
							{{ $sum }}
					</td>
						<td align="center" class="bg-primary">
							<?php
								$unitid= \App\Models\Product::where('id', $result->stock_id)->first()->product_unit;
								$unitqty= \App\Models\ProductsUnit::where('id', $unitid)->first()->qty_count;
								$casevalue=$sum/$unitqty;
							?>
							{{number_format($casevalue,2)}}
						</td>
					<td>
                        <?php

                        $stock_moves=\App\Models\StockMove::selectRaw('sum(qty) as total_qty,sum(price*qty) as total_price')
                        				->where('trans_type',102)
                        				//->orWhere('trans_type',401)
                            			->where('stock_id',$result->stock_id)
                            			->first();
                        $avg_price = $stock_moves->total_qty!=0?(($stock_moves->total_price)/$stock_moves->total_qty):0;
                        ?>
                        {{ $avg_price }}
                    </td>
                    <td>
                        <a href="" >
                            <a class="btn btn-danger" onclick="return confirm('Are you sure?')" href="{{route('admin.products.stocks_count.delete', $result->id)}}"><i class="fa fa-trash"></i></a>
                        </a>
                    </td>
				</tr>
				{{-- {{$result->links}} --}}
				@endforeach
				@endforeach
				@else
				<tr>
					<td colspan="6" class="text-center text-danger">No Transaction Yet</td>
				</tr>
				@endif

			</tbody>
		</table>
		{{-- $transations->appenda($_GET)->link --}}
		{{-- {!! $transations->links() !!} --}}

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
 <script>
	    $(function() {
	        $('#clients-table').DataTable({
				dom: 'Bfrtip',
				buttons: [
					'csv', 'excel', 'pdf', 'print'
				],
	            'pageLength'  : 65,
	            'lengthChange': true,
	            'searching'   : true,
	            'ordering'    : true,
	            'info'        : true,
	            'autoWidth'   : true,
	            "paging"      : false
	        });
	    });

	</script>
@endsection
