@extends('layouts.master')

@section('head_extra')


<!-- Select2 css -->
@include('partials._head_extra_select2_css')

<style>
	.panel .mce-panel {
		border-left-color: #fff;
		border-right-color: #fff;
	}

	.panel .mce-toolbar,
	.panel .mce-statusbar {
		padding-left: 20px;
	}

	.panel .mce-edit-area,
	.panel .mce-edit-area iframe,
	.panel .mce-edit-area iframe html {
		padding: 0 10px;
		min-height: 350px;
	}

	.mce-content-body {
		color: #555;
		font-size: 14px;
	}

	.panel.is-fullscreen .mce-statusbar {
		position: absolute;
		bottom: 0;
		width: 100%;
		z-index: 200000;
	}

	.panel.is-fullscreen .mce-tinymce {
		height: 100%;
	}

	.panel.is-fullscreen .mce-edit-area,
	.panel.is-fullscreen .mce-edit-area iframe,
	.panel.is-fullscreen .mce-edit-area iframe html {
		height: 100%;
		position: absolute;
		width: 99%;
		overflow-y: scroll;
		overflow-x: hidden;
		min-height: 100%;
	}
	input.form-control{
		min-width: 55px !important;
	}
	select{
		min-width: 80px !important;

	}
	.p_sn{
		max-width: 3px !important;
	}
    .c_sn{
        max-width: 3px !important;
    }
	@media only screen and (max-width: 770px) {
		input.total{
			width: 140px !important;
		}
	}
    span.select2.select2-container.select2-container--default {
    width: 100% !important;
}
.form-control {
    border-radius: 5px !important;
    box-shadow: none;
    border-color: #3c8dbc7a;
    height: 30px !important;
}
.select2-container .select2-selection--single {
    height: 30px !important;
}
.select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #3c8dbc7a !important;
    border-radius: 5px !important;
}
tr.bg-info.tr-heading th {
    border-left: 1px solid #fff;
    padding: 3px;
}
.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
    border-top: none !important;
}
.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background-color: #fff !important;
    opacity: 1;
}
.panel-footer {
    padding: 10px 15px;
    background-color: #fff !important;
    border-top: none !important;
}
.box {
    border-radius: 12px !important;
    box-shadow: rgb(100 100 111 / 20%) 0px 7px 29px 0px !important;
    border-top: none !important;
}
.form-control {
    display: inline !important;
}
</style>
@endsection

@section('content')


<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
	<h1>
		Purchase {{ucfirst(trans(\Request::get('type')))}}
		<small> Purchase {{ucfirst(trans(\Request::get('type')))}}
		</small>
	</h1>
	{!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
	<div class='col-md-12 '>
		<div class="box">
			<div class="box-body">
				<div id="orderFields" style="display: none;">
					<table class="table">
						<tbody id="more-tr">
							<tr>
								<td class='p_sn'></td>
								<td>
									<select class="form-control input-sm select2 product_id getid" name="product_id[]" required="required" >

										<option value="">Select Product</option>
										@foreach($products as $key => $pk)
										<option value="{{ $pk->id }}" @if(isset($orderDetail->product_id) && $orderDetail->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }} (In Stock {{StockHelper::getRemaingStocks($pk->id)}})</option>
										@endforeach

									</select>
								</td>
								<td class="col-sm-1">
									<input type="number" class="form-control input-sm quantity input-sm" name="quantity[]" placeholder="Quantity" required="required" step=".01" autocomplete="off">
								</td>
								<td>
									<input type="text" class="form-control input-sm price input-sm" name="price[]" placeholder="Rate" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required" autocomplete="off">
								</td>
								<td>
									<input type="number" name="dis_amount[]" class="form-control input-sm discount_amount_line input-sm" placeholder="Discount" step="any">
								</td>
								<td>
									<select name='units[]' class="form-control input-sm input-sm units">
										<option value="">Units</option>
										@foreach($prod_unit as $pu)
										<option value="{{ $pu->id }}">{{ $pu->symbol }}</option>
										@endforeach
									</select>
								</td>
								<td class="col-sm-1">
									<select class="form-control input-sm tax_rate_line input-sm" name="tax_type[]">
										<option value="0">Exempt(0)</option>
										<option value="13">VAT(13)</option>
									</select>
								</td>

								<td style="display:none;">
									<input type="number" class="form-control input-sm tax_amount_line input-sm" name="tax_amount[]" value="0" readonly="readonly" />
								</td>
								<td>
									<input type="number" class="form-control input-sm tds_rate_line input-sm" name="tds_per[]" value="0" step=".01" autocomplete="off"/>
								</td>
								<td style="display: none;">
									<input type="number" class="form-control input-sm tds_rate_amount input-sm" name="tds_amount[]" value="0" readonly="readonly"/>
								</td>

								<td>
									<input type="number" class="form-control input-sm total input-sm" name="total[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" style="float:left; width:70%;" step='any'>
									<a href="javascript::void(1);" style="width: 10%;">
										<i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div id="CustomOrderFields" style="display: none;">
					<table class="table">
						<tbody id="more-custom-tr">
							<tr>
								<td class="p_sn"></td>
								<td>
									<input type="text" class="form-control input-sm product" name="custom_items_name[]" value="" placeholder="Product" autocomplete="off">
								</td>
								<td class="col-sm-1">
									<input type="number" class="form-control input-sm quantity input-sm" name="custom_items_qty[]" placeholder="Quantity" required="required" step=".01" autocomplete="off">
								</td>

								<td>
									<input type="text" class="form-control input-sm price" name="custom_items_price[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required" autocomplete="off">
								</td>
								<td>
									<input type="number" name="custom_dis_amount[]" class="form-control input-sm discount_amount_line" placeholder="Discount" step=".01" >
								</td>
								<td>
									<select name='custome_unit[]' class="form-control input-sm">
										<option value="">Units</option>
										@foreach($prod_unit as $pu)
										<option value="{{ $pu->id }}">{{ $pu->symbol }}</option>
										@endforeach
									</select>
								</td>
								<td class="col-sm-1">
									<select class="form-control input-sm tax_rate_line" name="custom_tax_type[]">
										<option value="0">Exempt(0)</option>
										<option value="13">VAT(13)</option>
									</select>


								</td>

								<td>

									<input type="number" class="form-control input-sm tax_amount_line"
									name="custom_tax_amount[]" value="0" readonly="readonly">
								</td>
								<td>
									<input type="number" class="form-control input-sm total" name="custom_total[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" style="float:left; width:70%;" step='any'>
									<a href="javascript::void(1);" style="width: 10%;">
										<i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
                <div id="orderCostFields" style="display: none;">
					<table class="table">
						<tbody id="more-tr">
							<tr>
								<td class='c_sn'></td>
                                <td>
									<select class="form-control input-sm select2 cost_type" name="cost_type[]" required="required" >
                                        @php
                                            $costterms=\App\Models\CostTerm::pluck('name');
                                        @endphp
										<option value="">Select Cost</option>
                                        @foreach ($costterms as $term)
                                            <option value="{{$term}}">{{$term}}</option>
                                        @endforeach
									</select>
								</td>
								<td>
									<select class="form-control input-sm select2 product_id" name="cost_product_id[]" required="required">

										<option value="">Select Product</option>
										<option value="ALL">ALL Products</option>
										<option value="None">None</option>
										@foreach($products as $key => $pk)
										<option value="{{ $pk->id }}" @if(isset($orderDetail->product_id) && $orderDetail->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }} (In Stock {{StockHelper::getRemaingStocks($pk->id)}})</option>
										@endforeach

									</select>
								</td>
								<td class="col-sm-1">
									<select name='method[]' class="form-control input-sm input-sm method">
										<option value="">--SELECT Method--</option>
										<option value="Quantity">Quantity</option>
										<option value="Value">Value</option>

									</select>
								</td>
								<td>
									<input type="text" class="form-control input-sm amount input-sm" name="amount[]" placeholder="Amount" value="" required="required" autocomplete="off">
								</td>
								<td>
									<select name='debit_account[]' class="form-control input-sm select2 debit_account">
										<option value="">--SELECT--</option>
                                        @foreach ($ledger_all as $ledgerid=>$ledgername)
										<option value="{{$ledgerid}}">{{$ledgername}}</option>
                                        @endforeach
									</select>
								</td>
								<td>
									<select name='credit_account[]' class="form-control input-sm select2 credit_account">
										<option value="">--SELECT--</option>
										@foreach ($ledger_all as $ledgerid=>$ledgername)
										<option value="{{$ledgerid}}">{{$ledgername}}</option>
                                        @endforeach
									</select>
								</td>
								<td class="col-sm-1">
									<input type="text" name="description[]" class="form-control input-sm description input-sm" placeholder="Description" style="width:87%; ">

                                    <a href="javascript::void(1);" style="width: 10%;">
										<i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff; margin-top: 6px;"></i>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-12">
					<div class="">
						<form method="POST" action="/admin/purchase" id="mypurchaseform">

							{{ csrf_field() }}
							<div class="">
                                <input type="hidden" name="supplier_type"
										value='supplier'>
								<div class="col-md-12" id='customers_id' style="text-align: right; margin-top:7px;">


                                        <div class="form-group">
                                            <input type="checkbox" id="is_import" name="is_import" value="1" onclick="ShowImportHideDiv(this)">
                                            <label for="is_import">Is Import</label>
                                        </div>


								</div>

								<div class="clearfix"></div>

								<div class="col-md-12">
									{{-- <div class="col-md-4 form-group">
										<label>Select Product Type:</label>
											<select class="form-control producttype select2" name="product_type">
												<option class="form-control input-sm input" value="" disabled selected>Select Any One</option>
												<option value="bills">Bills</option>
												<option value="assets">Assets</option>
												<option value="services">Services</option>
											</select>
											
									</div>	 --}}
                                    <div class="col-md-4">

                                        <label>Select:<a href="javascript::void()" onClick='openwindow()' id='create_supplier' style="color: black;">[+]*</a></label>
										<select class="customer_id select2" name="customer_id" id="customers" style="width: 400px;">
											<option class="form-control input-sm input input-lg" value="">Select Supplier</option>
											@foreach($clients as $key=>$sup)
											<option value="{{$sup->id}}">{{$sup->name}} - {{$sup->vat}} ({{ $sup->locations->city ?? '' }})</option>
											@endforeach

										</select>

										&nbsp;&nbsp;
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>Order Date:</label>
											<div class="date">
												{{-- <div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div> --}}
												<input type="text" class="form-control input-sm pull-right datepicker  date-toggle-nep-eng" name="ord_date" value="{{date('Y-m-d')}}" id="ord_date">
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>Delivery Date:</label>
											<div class="date">
												{{-- <div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div> --}}
												<input type="text" class="form-control input-sm pull-right datepicker date-toggle-nep-eng" name="delivery_date" value="" id="delivery_date">
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">

											<label>Supplier Bill Date*</label>

											<div class="date">
												{{-- <div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div> --}}
												<div id='dateselectors'>
													<input type="text" class="form-control input-sm pull-right datepicker date-toggle-nep-eng" name="bill_date" value="{{date('Y-m-d')}}" id="bill_date" required="">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>Supplier Payment Date:</label>

											<div class="date">
												{{-- <div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div> --}}
												<div id='dateselectors1'>
													<input type="text" class="form-control input-sm pull-right datepicker date-toggle-nep-eng" name="due_date" value="" id="due_date">
												</div>
											</div>
										</div>
									</div>
								</div>	
						
								<div class="col-md-12">
								
									<div class="col-md-2">
										<div class="form-group">
											<label>Status:</label>
											<select type="text" class="form-control input-sm pull-right " name="status" id="status">
												<option value="">Select</option>
												<option value="Placed">Placed</option>
												<option value="Parked">Parked</option>
												<option value="Recieved">Recieved</option>
											</select>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>PAN NO:</label>
											<div class="">

												<div class="">
													<input type="text" class="form-control input-sm" name="pan_no"
													value="{{ old('pan_no')}}" id="pan_no">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>Bill NO*</label>
											<div class="">

												<div class="">
													<input type="text" class="form-control input-sm " name="bill_no" value="{{ old('bill_no')}}" id="bill_no" required>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>Currency:</label>
											<select class="form-control input-sm select2 currency" name="currency" required="required">
												@foreach($currency as $k => $v)
												<option value="{{ $v->currency_code }}" @if(isset($v->currency_code) && $v->currency_code == $v->currency_code) selected="selected"@endif>
													{{ $v->name }} {{ $v->currency_code }} ({{ $v->currency_symbol }})
												</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>Is renewal:</label>
											<select type="text" class="form-control input-sm pull-right " name="is_renewal" id="is_renewal">
												<option value="0">No</option>
												<option value="1">Yes</option>
											</select>
										</div>
									</div>
									<div class="col-md-2 form-group">
                                        <label >Location*</label>
                                        <select name="into_stock_location" class="form-control input-sm searchable" id="location">

                                            @foreach($productlocation as $key=>$out)
                                                <option value="{{ $out->id }}">
                                                     {{$out->name}}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
								</div>	
							
								<div class="col-md-12" >

									<div class="col-md-2 form-group" style="">
										<label for="user_id">Purchase Owner</label>
										{!! Form::select('user_id', $users, \Auth::user()->id, ['class' => 'form-control input-sm Request-sm', 'id'=>'user_id']) !!}
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="exampleInputEmail1">Reference<span class="text-danger"> *</span></label>
											<div class="input-group">
												{{-- <div class="input-group-addon">PO-</div> --}}
												<input id="reference_no" class="form-control input-sm" value="{{ sprintf("%04d", $order_count+1)}}" type="text">


												<input type="hidden" name="reference" id="reference_no_write" value="">
											</div>
											<span id="errMsg" class="text-danger"></span>
										</div>
									</div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Fiscal Year:</label>
                                            {!! Form::select('fiscal_year',$fiscal_years, \FinanceHelper::cur_fisc_yr()->id , ['class' => 'form-control input-sm','id'=>'fiscal_year']) !!}
                                        </div>
                                    </div>
                                    @if(Auth::user()->hasRole('admins'))

                                    @endif
									<div class="col-md-2 import_show" style="display: none">
                                        <div class="form-group">
                                            <label for="">Import Date</label>
                                            <div id='dateselectors3'>
                                                <input type="text" class="form-control input-sm pull-right datepicker date-toggle-nep-eng" name="import_date" value="{{date('Y-m-d')}}" id="import_date" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 import_show" style="display: none">
                                        <div class="form-group">
                                            <label for="">Document No</label>
                                            <input type="text" name="document_no" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2 import_show" style="display: none">
                                        <div class="form-group">
                                            <label for="">Country</label>
                                            <input type="text" name="country" class="form-control">
                                        </div>
                                    </div>
								</div>
								{{-- <div class="col-md-12">	
                                    
								</div> --}}

								<div class="col-md-12" >

                                </div>
							</div>

							@if(\Request::get('type') && \Request::get('type') == 'purchase_orders')
							<input type="hidden" name="purchase_type" value="purchase_orders">
							@elseif(\Request::get('type') && \Request::get('type') == 'request')
							<input type="hidden" name="purchase_type" value="request">
							@elseif(\Request::get('type') && \Request::get('type') == 'bills')
							<input type="hidden" name="purchase_type" value="bills">
							@elseif(\Request::get('type') == 'assets')
							<input type="hidden" name="purchase_type" value="assets">
							@else
							<input type="hidden" name="purchase_type" value="purchase_orders">
							@endif
							<div class="clearfix"></div>



							<hr />
							<div class="table-responsive">
								<table class="table table-striped">
									<thead>
										<tr class="bg-info tr-heading">
											<th style="width: 4%;">S.N</th>
											<th style="width: 20%;">Item*</th>
											<th style="width: 10%;">Qty*</th>
											<th style="width: 11%;">Rate *</th>
											<th style="width: 11%;" title="Discount">Dis</th>
											<th style="width: 10%;">Unit</th>
											<th style="width: 12%;">Tax Rate</th>
											{{-- <th style="width: 10%;">Tax Amt</th> --}}
											<th style="width:10%;">TDS(%)</th>
											<th style="width: 12%;">Total</th>
										</tr>
									</thead>

									<tbody id='multipleDiv'>
										<tr class="multipleDiv"></tr>
									</tbody>

									<tfoot>
										<tr>
											<td colspan="3">
												<a href="/admin/products/create" data-toggle="modal" data-target="#modal_dialog" class="btn btn-default btn-sm" style="float: left; display: none;" title="Create a new Product" id='addmorProducts'>
													<i class="fa fa-plus"></i> <span>Create New Product</span>
												</a>
											</td>
											<td colspan="6">
												<div class="btn-group pull-right">

													<a href="javascript:void(0);" class="btn btn-primary btn-sm" id="addMore" >
														<i class="fa fa-plus"></i> <span>Add Products Item</span>
													</a> &nbsp;
													<a href="javascript:void(0);" class="btn btn-default btn-sm" id="addCustomMore" style="float:right;"  title='Inventory is not updated with custome product' >
														<i class="fa fa-plus"></i> <span>Customised items</span>
													</a>
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="8" style="text-align: right;">SubTotal</td>
											<td id="sub-total">0.00</td>
											<td>&nbsp; <input type="hidden" name="subtotal" id="subtotal" value="0"></td>
										</tr>
										<tr>
											<td colspan="8" style="text-align: right;">Order Discount</td>
											<td id='discount-amount'>0.00</td>
											<td>
												<input type="hidden" name="discount_amount" value="0"
												id='discount_amount'>
											</td>
										</tr>
										<tr>
											<td colspan="8" style="text-align: right;">Non Taxable Amount</td>
											<td id="non-taxable-amount">0.00</td>
											<td>&nbsp;<input type="hidden" name="non_taxable_amount"
												id="nontaxableamount" value="0">
											</td>
										</tr>
										<tr>
											<td colspan="8" style="text-align: right;">Taxable Amount</td>
											<td id="taxable-amount">0.00</td>
											<td>
												&nbsp; <input type="hidden" name="taxable_amount"
												id="taxableamount" value="0">
											</td>
										</tr>
										<tr>
											<td colspan="8" style="text-align: right;">VAT</td>
											<td id="taxable-tax">0.00</td>
											<td>&nbsp; <input type="hidden" name="taxable_tax" id="taxabletax" value="0"></td>
										</tr>
										<tr>
											<td colspan="8" style="text-align: right; font-weight: bold;">Total</td>
											<td id="total">0.00</td>
											<td>
												<input type="hidden" name="total_tax_amount" id="total_tax_amount" value="0">
												<input type="hidden" name="final_total" id="total_" value="0">
											</td>
										</tr>
										<tr>
											<td colspan="8" style="text-align: right; font-weight: bold;">TDS</td>
											<td id="tdstotal">0.00</td>
											<td>
												<input type="hidden" name="tds_total" id="tds_total" value="0">
											</td>
										</tr>
										<tr>
											<td colspan="8" style="text-align: right; font-weight: bold;">Net Payable</td>
											<td id="netreceivable">0.00</td>
											<td>
												<input type="hidden" name="netpayable" id="net_receivable" value="0">
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<br />
                            {{--anamol --}}
                            <div class="table-responsive import_show" style="display: none">
                                <table class="table table-striped">
									<thead>
                                        <tr class="bg-info tr-heading">
											<th style="width: 4%;">S.N</th>
											<th style="width: 16%;">Cost Term</th>
											<th style="width: 20%;">Product</th>
											<th style="width: 8%;">Methods</th>
											<th style="width: 8%;">Amount</th>
											<th style="width: 12%;">Debit Account</th>
											<th style="width: 12%;">Credit Account</th>
											<th style="width: 20%;">Description</th>

										</tr>
									</thead>

									<tbody id='multipleDivAddtionalCost' class="tr-001">
										<tr class="multipleDivAddtionalCost"></tr>
									</tbody>
                                    <tfoot>
										<tr>
											<td colspan="3">

											</td>
											<td colspan="6">
												<div class="btn-group pull-right">

													<a href="javascript:void(0);" class="btn-sm" id="addMoreCost" style="float:right;" >
														<i class="fa fa-plus"></i> <span>Add Additonal Cost</span>
													</a> &nbsp;

												</div>
											</td>
										</tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div style="margin-top: 3%">
                                <div class="col-md-6 form-group" style="margin-top:-30px;">
                                    <label for="comment">Narration</label>
                                    <textarea class="form-control input-sm TextBox comment" name="comments">@if(isset($order->comment)){{ $order->comments }}@endif</textarea>
                                </div>

                                <div class="col-md-6 form-group" style="margin-top:-30px;">
                                    <label for="address">Address</label>
                                    <textarea id="physical_address" class="form-control input-sm TextBox address" name="address">@if(isset($orderDetail->address)){{ $orderDetail->address }}@endif</textarea>
                                </div>
                            </div>

						</div>
						<div class="panel-footer">
							<button type="button" class="btn btn-social btn-foursquare PurchaseSubmitButton" id='btnSubmit' onclick="checkvalidation();" style="float: right;">
								<i class="fa fa-save"></i>Save Purchase
							</button>
							<a href="/admin/purchase?type={{\Request::get('type')}}" class="btn btn-default" style="float: right; position: relative; left:-9px;">Close</a>
						</div>
					</form>
				</div>
			</div>

		</div>
		<!-- /.box-body -->
	</div>
	<!-- /.col -->

	@endsection
	<div class='supplier_options' style="display: none;">
		<div id='_supplier'>
			<option value="">Select Supplier</option>
			@if(isset($clients))
			@foreach($clients as $key => $uk)
			<option value="{{ $uk->id }}" @if($orderDetail && $uk->id ==
				$orderDetail->customer_id){{ 'selected="selected"' }}@endif>{{ '('.env('APP_CODE'). $uk->id.') '.$uk->name.' -'.$uk->vat }} ({{ $sup->locations->city ?? '-' }}) </option>
				@endforeach
				@endif
			</div>
			<div id='_paid_through'>
				<option value="">Select Supplier</option>
				@if(isset($paid_through))
				@foreach($paid_through as $key => $uk)
				<option value="{{ $uk->id }}" @if($orderDetail && $uk->id ==
					$orderDetail->customer_id){{ 'selected="selected"' }}@endif>{{ '('.env('APP_CODE'). $uk->id.') '.$uk->name.' -'.$uk->organization }}</option>
					@endforeach
					@endif
				</div>

			</div>

			@section('body_bottom')
			<!-- form submit -->
			@include('partials._body_bottom_submit_bug_edit_form_js')
			@include('partials._date-toggle')
            <script type="text/javascript">
                function ShowImportHideDiv(is_import) {
                    var import_show = document.getElementsByClassName("import_show");
                    for (var i=0;i<import_show.length;i+=1){
                        import_show[i].style.display = is_import.checked ? "block" : "none";
                    }
                }
            </script>


			<script>
				$('.date-toggle-nep-eng').nepalidatetoggle();
				const dateRange = {
					<?php $currentFiscalyear = FinanceHelper::cur_fisc_yr();?>
					minDate: `{{ $currentFiscalyear->start_date }}`,
					maxDate: `{{ $currentFiscalyear->end_date }}`
				}
				@if(\Request::get('type') == 'bills' || \Request::get('type') == 'assets')
				$('select[name=customer_id]').prop('required', true);
				@endif

				$('.customer_id').select2({


				});
				function adjustTotalNonTaxable(){


					var  taxableAmount = 0;

					var nontaxableAmount = 0;

					var taxAmount = 0;



					var taxableAmount = 0;

					var nontaxableAmount = 0;
					// let tds_rate = Number(parent.find('.tds_rate_line').val());

					$('.tax_rate_line').each(function(){

						let parent = $(this).parent().parent();

						let tax_rate = Number(parent.find('.tax_amount_line').val());
						var total = Number(parent.find('.total').val());

						if($(this).val() == 0 ){

							nontaxableAmount += total ;

						}else{

							taxableAmount +=  (Number(parent.find('.price').val())* Number(parent.find('.quantity').val()))- Number(parent.find('.discount_amount_line').val());

							taxAmount += tax_rate;
						}

					});
					
					$('#non-taxable-amount').text(nontaxableAmount.toLocaleString());

					$('#nontaxableamount').val(nontaxableAmount);

					$('#taxable-amount').text((taxableAmount ).toFixed(2).toLocaleString());

					$('#taxableamount').val((taxableAmount ).toFixed(2));

					$('#taxabletax').val(taxAmount.toFixed(2));

					$('#taxable-tax').text(taxAmount.toFixed(2).toLocaleString());

					var totalDiscount = 0;
					$('.discount_amount_line').each(function(){

						totalDiscount += Number($(this).val());


					});
					$('#discount-amount').text(totalDiscount);
					$('#discount_amount').val(totalDiscount);

				}

				function adjustTax(ev){

					let parent = ev.parent().parent();
					
					let total = Number(parent.find('.total').val());

					let discount = Number(parent.find('.discount_amount_line').val());
					let total_with_discount = total - discount;

					parent.find('.total').val(total_with_discount);

					let tax_rate = Number(parent.find('.tax_rate_line').val());

					let tax_amount = (tax_rate / 100 * total_with_discount);
					parent.find('.tax_amount_line').val(tax_amount.toFixed(2));
	
					let amount_with_tax = total_with_discount;

					parent.find('.total').val(amount_with_tax);
					let tds_rate = Number(parent.find('.tds_rate_line').val());

					let tds_amount = (tds_rate /100 * total_with_discount);
					parent.find('.tds_rate_amount').val(tds_amount.toFixed(2));
				}


				$(document).on('change','.tax_rate_line',function(){



					let parent = $(this).parent().parent();

					parent.find('.quantity').trigger('change');


				});

				$(document).on('change','.discount_amount_line',function(){

					let parent = $(this).parent().parent();

					parent.find('.quantity').trigger('change');

				})


				function isNumeric(n) {
					return !isNaN(parseFloat(n)) && isFinite(n);
				}


				$(document).on('change', '.product_id', function() {
					var parentDiv = $(this).parent().parent();
					if (this.value != 'NULL') {
						var _token = $('meta[name="csrf-token"]').attr('content');
						$.ajax({
							type: "POST"
							, contentType: "application/json; charset=utf-8"
							, url: "/admin/products/GetProductDetailAjax/" + this.value + '?_token=' + _token
							, success: function(result) {
								var obj = jQuery.parseJSON(result.data);
								parentDiv.find('.price').val(obj.cost);
								parentDiv.find('.units').val("");
								parentDiv.find('.units').val(result.units?.id);
								if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
									var total = parentDiv.find('.quantity').val() * obj.cost;
								} else {
									var total = obj.cost;
								}

								var tax = parentDiv.find('.tax_rate_line').val();
								var discount=parentDiv.find('.discount_amount_line').val();
								if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
									tax_amount = total * Number(tax) / 100;
									parentDiv.find('.tax_amount_line').val(tax_amount);
									total = total;
								} else
								parentDiv.find('.tax_amount_line').val('0');

								parentDiv.find('.total').val(total);
								calcTotal();
							}
						});
					} else {
						parentDiv.find('.price').val('');
						parentDiv.find('.total').val('');
						parentDiv.find('.tax_amount_line').val('');
						calcTotal();
					}
				});

				$(document).on('change', '.customer_id', function() {
					if (this.value != '') {
						$(".quantity").each(function(index) {
							var parentDiv = $(this).parent().parent();
							if (isNumeric($(this).val()) && $(this).val() != '')
								var total = $(this).val() * parentDiv.find('.price').val();
							else
								var total = parentDiv.find('.price').val();

							var tax = parentDiv.find('.tax_rate').val();
							if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
								tax_amount = total * Number(tax) / 100;
								parentDiv.find('.tax_amount').val(tax_amount);
								total = total + tax_amount;
							} else
							parentDiv.find('.tax_amount').val('0');

							if (isNumeric(total) && total != '') {
								parentDiv.find('.total').val(total);
								calcTotal();
							}
						});
					} else {
						$('.total').val('0');
						$('.tax_amount').val('0');
						calcTotal();
					}

					let supp_id = $(this).val();
					$.get('/admin/getpanno/' + supp_id, function(data, status) {
						$('#pan_no').val(data.pan_no);
						$('#physical_address').val(data.physical_address);


					});
				});

				$(document).on('change', '.quantity', function() {
					var parentDiv = $(this).parent().parent();

					if (isNumeric(this.value) && this.value != '') {
						if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
							var total = parentDiv.find('.price').val() * this.value;
						} else
						var total = '';
					} else
					var total = '';

					var tax = parentDiv.find('.tax_rate_line').val();
					console.log(tax);
					if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
						tax_amount = total * Number(tax) / 100;
						parentDiv.find('.tax_amount_line').val(tax_amount);
						total = total + tax_amount;
					} else
					parentDiv.find('.tax_amount_line').val('0');

					parentDiv.find('.total').val(total);

					adjustTax($(this));
					calcTotal();
				});

				$(document).on('change', '.price', function() {

					var parentDiv = $(this).parent().parent();
					if (isNumeric(this.value) && this.value != '') {
						if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
							var total = parentDiv.find('.quantity').val() * this.value;
						} else
						var total = '';
					} else
					var total = '';

					var tax = parentDiv.find('.tax_rate_line').val();
					if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
						tax_amount = total * Number(tax) / 100;
						parentDiv.find('.tax_amount_line').val(tax_amount);
						total = total + tax_amount;
					} else
					parentDiv.find('.tax_amount_line').val('0');

					parentDiv.find('.total').val(total);
					adjustTax($(this));
					calcTotal();
				});

				$(document).on('change', '.total', function() {

					var parentDiv = $(this).parent().parent();
					if (isNumeric(this.value) && this.value != '') {
						var total = this.value;
						if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
							var price = total / parentDiv.find('.quantity').val();
						} else
						var price = '';
					} else
					var price = '';

					var tax = parentDiv.find('.tax_rate_line').val();
					if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
						tax_amount = total * Number(tax) / 100;
						parentDiv.find('.tax_amount_line').val(tax_amount);
						total = total + tax_amount - discount;
					} else
					parentDiv.find('.tax_amount_line').val('0');

					parentDiv.find('.price').val(price);
					adjustTax($(this));
					calcTotal();
				});

				$(document).on('change', '.tax_rate_line', function() {
					var parentDiv = $(this).parent().parent();

					if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
						var total = parentDiv.find('.price').val() * Number(parentDiv.find('.quantity').val());
					} else
					var total = '';

					var tax = $(this).val();
					var discount=parentDiv.find('.discount_amount_line').val();
					if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
						tax_amount = (total-discount) * Number(tax) / 100;
						parentDiv.find('.tax_amount_line').val(tax_amount);
						total = total-discount;
						// + tax_amount
					} else
					parentDiv.find('.tax_amount_line').val('0');

					parentDiv.find('.total').val(total);
					calcTotal();
				});
				$(document).on('change', '.tds_rate_line', function() {
					var parentDiv = $(this).parent().parent();

					if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
						var total = parentDiv.find('.price').val() * Number(parentDiv.find('.quantity').val());
					} else
					var total = '';

					var tds = $(this).val();
					var discount=parentDiv.find('.discount_amount_line').val();
					if (isNumeric(tds) && (total != 0 || total != '')) {
						tds_amount = (total-discount) * Number(tds) / 100;
						parentDiv.find('.tds_rate_amount').val(tds_amount);
						total = total-discount;
					} else
					parentDiv.find('.tds_rate_amount').val('0');

					parentDiv.find('.total').val(total);
					calcTotal();
				});



				function getSn(){

					$('#multipleDiv tr').each(function(index,val){

						if(index > 0){
							$(this).find('.p_sn').html(index);
						}

					});
				}
                function getCsn(){
                    $('#multipleDivAddtionalCost tr').each(function(index,val){

                    if(index > 0){
                        $(this).find('.c_sn').html(index);
                    }

                    });
                }

                $("#addMoreCost").on("click", function() {
					$(".multipleDivAddtionalCost").after($('#orderCostFields #more-tr').html());
					$(".multipleDivAddtionalCost").next('tr').find('.product_id').select2({
						width: '100%'
					});
					let pid =  $(".multipleDivAddtionalCost").next('tr').find('.product_id');
					pid.select2('destroy');
					pid.select2({
						width: '100%',
					});
                    $(".multipleDivAddtionalCost").next('tr').find('.debit_account').select2({
						width: '100%'
					});
					let did =  $(".multipleDivAddtionalCost").next('tr').find('.debit_account');
                    did.select2('destroy');
					did.select2({
						width: '100%',
					});
                    $(".multipleDivAddtionalCost").next('tr').find('.credit_account').select2({
						width: '100%'
					});
					let cid =  $(".multipleDivAddtionalCost").next('tr').find('.credit_account');
                    cid.select2('destroy');
					cid.select2({
						width: '100%',
					});
					$(".multipleDivAddtionalCost").next('tr').find('.quantity').val('1');

					getCsn();
					$('#addmorProducts').show(300);

				});
                $('.orderCostFields').on('click','.remove-this',function(){
                    $(this).parent().parent().parent().remove();
                    getCsn();
                })

				$("#addMore").on("click", function() {
					$(".multipleDiv").after($('#orderFields #more-tr').html());
					$(".multipleDiv").next('tr').find('.product_id').select2({
						width: '100%'
					});
					let pid =  $(".multipleDiv").next('tr').find('.product_id');
					pid.select2('destroy');
					pid.select2({
						width: '100%',
					});
					$(".multipleDiv").next('tr').find('.quantity').val('1');

					getSn();
					$('#addmorProducts').show(300);

				});
				$("#addCustomMore").on("click", function() {
					$(".multipleDiv").after($('#CustomOrderFields #more-custom-tr').html());
					$(".multipleDiv").next('tr').find('.quantity').val('1');
					getSn();
				});

				$(document).on('click', '.remove-this', function() {
					$(this).parent().parent().parent().remove();
					calcTotal();
					$("#multipleDiv .product_id").length > 0 ? $('#addmorProducts').show(300) : $('#addmorProducts').hide(300);
					getSn();
				});

				$(document).on('change', '#vat_type', function() {
					calcTotal();
				});

				function calcTotal() {

					adjustTotalNonTaxable();
					var subTotal = 0;
					var taxableAmount = 0;

					var total = 0;
					var tax_amount = 0;
					var taxableTax = 0;
					var tds= 0;
					var netreceivable=0;
					$(".total").each(function(index) {
						if (isNumeric($(this).val()))
							subTotal = Number(subTotal) + Number($(this).val());
					});
					$(".tax_amount").each(function(index) {
						if (isNumeric($(this).val()))
							tax_amount = Number(tax_amount) + Number($(this).val());
					});

					$('#sub-total').html(subTotal.toLocaleString());
					$('#subtotal').val(subTotal);


					total = Number( $('#nontaxableamount').val() ) + Number( $("#taxableamount").val() ) +
					Number($('#taxabletax').val());

					var discount_amount = $('#discount_amount').val();

					var vat_type = $('#vat_type').val();

					console.log(discount_amount);

					$('#total_tax_amount').val(tax_amount);

					$('#total').html(total.toLocaleString());
					$('#total_').val(total);

					$(".tds_rate_amount").each(function(index) {
						if (isNumeric($(this).val()))
							tds = Number(tds) + Number($(this).val());
					});	
					$('#tdstotal').html(tds.toLocaleString());
					$('#tds_total').val(tds);

					netreceivable= total - Number( $("#tds_total").val()) ;
					$('#netreceivable').html(netreceivable.toLocaleString());
					$('#net_receivable').val(netreceivable);

				}

				$(document).on('keyup', '#discount_amount', function() {
					calcTotal();
				});

			</script>

			<script type="text/javascript">
				$(document).ready(function() {
					$('.project_id').select2();

				});

			</script>

			<script type="text/javascript">
				$(function() {
					$('.datepicker').datetimepicker({
						format: 'YYYY-MM-DD'
						, sideBySide: true
						, allowInputToggle: true,

					});

				});

			</script>

			<script type="text/javascript">
				var refNo = 'PO-' + $("#reference_no").val();

				$("#reference_no_write").val(refNo);

				$(document).on('keyup', '#reference_no', function() {

					var val = $(this).val();

					if (val == null || val == '') {
						$("#errMsg").html("Already Exists");
						$('#btnSubmit').attr('disabled', 'disabled');
						return;
					} else {
						$('#btnSubmit').removeAttr('disabled');
					}

					var ref = 'PO-' + $(this).val();
					$("#reference_no_write").val(ref);
					$.ajax({
						method: "POST"
						, url: "/admin/purchase/reference-validation"
						, data: {
							"ref": ref
							, "_token": token
						}
					})
					.done(function(data) {
						var data = jQuery.parseJSON(data);
						if (data.status_no == 1) {
							$("#errMsg").html('Already Exists!');
						} else if (data.status_no == 0) {
							$("#errMsg").html('Available');
						}
					});
				});

				function openwindow() {
					var win = window.open('/admin/clients/modals?relation_type=supplier', '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=600, height=650');
				}

				function HandlePopupResult(result) {
					if (result) {
						let clients = result.clients;
						let types = $(`input[name=source]:checked`).val();
						if (types == 'lead') {
							lead_clients = clients;
						} else {
							customer_clients = clients;
						}
						var option = '<option value="">Select Supplier</option>';
						for (let c of clients) {
							option = option + `<option value='${c.id}'>${c.name}</option>`;
						}
						$('#customers_id select').html(option);
						$('.supplier_options #_supplier').html(option);
						setTimeout(function() {
							$('.customer_id').select2('destroy');
							$('#customers_id select').val(result.lastcreated);
							$('#pan_no').val(result.pan_no);
							$("#ajax_status").after("<span style='color:green;' id='status_update'>client sucessfully created</span>");
							$('#status_update').delay(3000).fadeOut('slow');
							$('.customer_id').select2();
						}, 500);
					} else {
						$("#ajax_status").after("<span style='color:red;' id='status_update'>failed to create clients</span>");
						$('#status_update').delay(3000).fadeOut('slow');
					}
				}

				$(document).on('hidden.bs.modal', '#modal_dialog', function(e) {
					$('#modal_dialog .modal-content').html('');
				});

				function handleProductModel(result) {
					var lastcreated = result.lastcreated;
					$('#modal_dialog').modal('hide');
					$('select.product_id').each(function() {
						let options = `<option value='${lastcreated.id}'>${lastcreated.name}</option>`
						$(this).append(options);
					});
					setTimeout(function() {
						alert("Product SuccessFully Added");
					}, 500);

				}

				$('#discount_type').change(function() {
					if ($(this).val() == 'a') {
						$('.discount_type').text('Order Discount (Amount)')
					} else {
						$('.discount_type').text('Order Discount (%)')
					}
					$('#discount_amount').val('')
					calcTotal();

				});
				$("input[name=supplier_type]").change(function() {
					if (!$(this).is(":checked")) {
						return;
					}
					$('.customer_id').select2('destroy');
					if ($(this).val() == 'supplier') {
						let option = $('.supplier_options #_supplier').html();
						$('select[name=customer_id]').html(option);
						$('#create_supplier').show();
					} else {
						let option = $('.supplier_options #_paid_through').html();
						$('select[name=customer_id]').html(option);
						$('#create_supplier').hide();
					}
					$('.customer_id').select2();

				});
				$('input[name=supplier_type]').trigger('change');


				$('#selectdatetype').val('nep');


				$('#selectdatetype').trigger('change');

			</script>
			{{-- Bar code scanner --}}
			<script type="text/javascript">
				var keybuffer = '';

				function press(event) {
					if (event.which === 13) {
						bar_val = Number(keybuffer);
						keybuffer = '';


						console.log('Reading from Barcode');
						let prevItem = $(`#prod-${bar_val}`);


						if(prevItem.length > 0){


							let prevQty = prevItem.find('.quantity');
							let newQty = Number(prevQty.val()) + 1;

							prevQty.val(newQty);
							let pid =  prevItem.find('.product_id');
							product_detail(pid);
							return;
						}


						$(".multipleDiv").after($('#orderFields #more-tr').html());
						$(".multipleDiv").next('tr').find('.product_id').select2({
							width: '100%'
						});

						$(".multipleDiv").next('tr').attr('id',`prod-${bar_val}`);

						let pid =  $(".multipleDiv").next('tr').find('.product_id');
						pid.select2('destroy');
						pid.val(bar_val);
						pid.select2({
							width: '100%',
						});
						$(".multipleDiv").next('tr').find('.quantity').val('1');

						getSn();
						product_detail(pid);
					}
					var number = event.which - 48;
					if (number < 0 || number > 9) {
						return;
					}
					keybuffer += number;
				}

				$(document).on("keypress", press);

				function product_detail(pid){
					var parentDiv = $(pid).parent().parent();
					if (pid.val() != 'NULL') {
						var _token = $('meta[name="csrf-token"]').attr('content');
						$.ajax({
							type: "POST"
							, contentType: "application/json; charset=utf-8"
							, url: "/admin/products/GetProductDetailAjax/" + pid.val() + '?_token=' + _token
							, success: function(result) {
								var obj = jQuery.parseJSON(result.data);
								parentDiv.find('.price').val(obj.price);
								parentDiv.find('.units').val("");
								parentDiv.find('.units').val(result.units?.id);
								if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
									var total = parentDiv.find('.quantity').val() * obj.price;
								} else {
									var total = obj.price;
								}

								var tax = parentDiv.find('.tax_rate').val();
								if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
									tax_amount = total * Number(tax) / 100;
									parentDiv.find('.tax_amount').val(tax_amount);
									total = total + tax_amount;
								} else
								parentDiv.find('.tax_amount').val('0');

								parentDiv.find('.total').val(total);
								calcTotal();
							}
						});
					} else {
						parentDiv.find('.price').val('');
						parentDiv.find('.total').val('');
						parentDiv.find('.tax_amount').val('');
						calcTotal();
					}
				}
		async function checkvalidation(){
        const oldHtml = $('.PurchaseSubmitButton').html()
		var checkcustomer=document.getElementById('customers').value;
		var checkdate= document.getElementById('bill_date').value;
		var checkbill=document.getElementById('bill_no').value;
		var checklocation=document.getElementById('location').value;

		if(checkcustomer == null || checkcustomer == ''){
			alert('Please Select Customer Name');
			return false;
		}
		else if(checkdate == null || checkdate == '')
		{
			alert('Please Select Supplier Date');
			return false;
		}
		else if(checkbill == null || checkbill == '')
		{
			alert('Please Enter Bill number');
			return false;
		}
		else if(checklocation == null || checkdate == '')
		{
			alert('Please Select Location');
			return false;
		}
	
        const collection = document.getElementsByClassName("getid");
      

        if(collection.length<=1)
        {
            alert('Select Product Please');
            return false;
        }
        $('.PurchaseSubmitButton').html('<i class="fa fa-spinner fa-spin"></i> Processing')
        for (let i = 1; i < collection.length; i++)
        {
            const response = await $.get(`/admin/purchase/AjaxValidation/${collection[i].value}`)
            if (response.flags == false) {
                alert(`please link product type master and product division for ${response.name}`)
                $('.PurchaseSubmitButton').html(oldHtml)
                return false;
            }
        }
        $('#mypurchaseform').submit()
        return true;
    }

			</script>
			@endsection
