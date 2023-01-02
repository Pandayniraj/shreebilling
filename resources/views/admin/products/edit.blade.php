@extends('layouts.master')

@section('head_extra')
<!-- Select2 css -->
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

@include('partials._head_extra_select2_css')
@endsection
@php $readonly = isset($readonly) ? $readonly : false;  @endphp
@section('content')

    @include('admin.products.modals.unit-modal')
    @include('admin.products.modals.category-modal')
<div class="nav-tabs-custom bg-info" id="tabs">
    <ul class="nav nav-tabs bg-danger">

        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">General Settings</a></li>
        <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">Transctions</a></li>
        <li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false">Status</a></li>
          <li class=""><a href="#tab_6" data-toggle="tab" aria-expanded="false">Updates</a></li>
    </ul>
    <div class="tab-content">

        @if(Auth::user()->hasRole('admins') || Auth::user()->id == 1 )
         <div class="tab-pane" id="tab_6">


            <div class="row">
                <div class="col-md-12">
                    @php
                        $lastUpdates = $course->updated_by ? $course->updated_by : '{}';
                        $lastUpdates = json_decode($lastUpdates,true);
                    @endphp

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Date</th>
                                <th>Updated Name</th>
                                <th>Updated Price</th>
                            </tr>
                            @foreach($lastUpdates as $key=>$value)
                            <tr>
                                <td>{{ TaskHelper::getUser($value['user_id'])->username ?? '' }}</td>
                                <td>{{ $value['date'] }}</td>
                                 <td>{{ $value['name'] }}</td>
                                <td>{{ $value['price'] }}</td>

                            </tr>
                            @endforeach
                        </thead>
                    </table>



                </div>
            </div>

         </div>
         @endif
        <div class="tab-pane active" id="tab_1">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="text-info text-center">Item Information</h4>
                    {!! Form::model( $course, ['route' => ['admin.products.update', $course->id], 'method' => 'PATCH', 'id' => 'form_edit_course','enctype'=>'multipart/form-data'] ) !!}
                    <div class="content">
                        <div class="form-group">
                            {!! Form::label('name', trans('admin/courses/general.columns.name')) !!}
                            {!! Form::text('name', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('ordernum', 'Ordernum') !!}
                            {!! Form::text('ordernum', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('cost', 'Purchase Pricing') !!}
                            {!! Form::text('cost', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('price', 'Sales Pricing') !!}
                            {!! Form::text('price', null, ['class' => 'form-control', $readonly]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('dollar_price', 'Dollar Pricing') !!}
                            {!! Form::text('dollar_price', null, ['class' => 'form-control', $readonly]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('alert_qty', 'Alert Quantity') !!}
                            {!! Form::number('alert_qty', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('product_code', 'Produc Code') !!}
                            {!! Form::text('product_code', null, ['class' => 'form-control', $readonly]) !!}
                        </div>
                        <div class="form-group">
                            <label>Select Parent Product(If Any)</label>
                            {!! Form::select('parent_product_id',[''=>'Please Select']+ $products, null, ['class' => 'form-control label-success select2']) !!}
                        </div>
                        <div class="form-group">
                            <label>Select Product Unit <a href="#" data-target="#modal_dialog_unit"  data-toggle="modal" >[+]</a></label>
                            {!! Form::select('product_unit', $product_unit, null, ['class' => 'form-control label-primary','id'=>'product-unit']) !!}
                        </div>
                        <div class="form-group">
                            <label>Select Outlet</label>
                            {!! Form::select('outlet_id', ['Please Select']+$outlets, null, ['class' => 'form-control label-success','id'=>'outlet_id','required'=>'required']) !!}
                        </div>





                        <div class="form-group">
                            <label>Select Category <a href="#" data-target="#modal_dialog_category"  data-toggle="modal" >[+]</a></label>
                            {!! Form::select('category_id', $categories,null, ['class' => 'form-control label-primary','id'=>'product-category']) !!}
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">
                                        {!! Form::label('product_image', 'Product Image') !!}
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="file" name="product_image">
                                        @if($course->product_image != '')
                                        <label>Current Logo: </label><br />
                                        <img style=" width:auto;" src="{{ '/products/'.$course->product_image }}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    {!! '<input type="hidden" name="enabled" value="0">' !!}
                                    {!! Form::checkbox('enabled', '1', $course->enabled) !!} {{ trans('general.status.enabled') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="radio">
                                <label>
                                    {!! '<input type="hidden" name="is_raw_material" value="0">' !!}
                                    {!! Form::radio('purchase', 'raw_material', ($course->product_division== 'raw_material' ? 'checked':'')) !!}
                                    Inventory
                                </label>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="radio">
                                <label>
                                    {!! '<input type="hidden" name="is_recipeable" value="0">' !!}
                                    {!! Form::radio('purchase', 'service', ($course->product_division == 'service' ? 'checked':'')) !!}
                                    Service
                                </label>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="radio">
                                <label>
                                    {!! '<input type="hidden" name="is_expirable" value="0">' !!}
                                    {!! Form::radio('purchase', 'fixed_asset', ($course->product_division == 'fixed_asset' ? ' checked' : '')) !!}
                                    Fixed_asset
                                </label>
                            </div>
                        </div>

                        {{-- <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    {!! '<input type="hidden" name="is_raw_material" value="0">' !!}
                                    {!! Form::checkbox('is_raw_material', '1',  @if($course->product_division == 'fixed_asset')?'checked':''@endif) !!}
                                    Is Raw Material
                                </label>
                            </div>
                        </div>

                     

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    {!! '<input type="hidden" name="is_expirable" value="0">' !!}
                                    {!! Form::checkbox('is_expirable', '1', $course->is_expirable) !!}
                                    Is Expirable
                                </label>
                            </div>
                        </div> --}}

                        <div class="form-group col-md-6">
                            <div class="checkbox">
                                <label>
                                    {!! '<input type="hidden" name="public" value="0">' !!}
                                    {!! Form::checkbox('public', '1', $course->public) !!} Show this product in Public Forms
                                </label>
                            </div>
                        </div>


                        <div class="form-group">
                            <label>Select Product Type Master</label>
                            {!! Form::select('product_type_id', $product_type_masters,null, ['class' => 'form-control label-primary']) !!}
                        </div>

                        <div class="form-group">
                            <label>Store</label>
                            {!! Form::select('store_id', $stores,null, ['class' => 'form-control label-primary']) !!}
                        </div>

                        <?php

                        $code = $course->id;
                        //echo '<img width="150" src="data:image/png;base64,'. \DNS1D::getBarcodePNG($code, "C128") . '" alt="barcode" class="bcimg"/>';

                        ?>
                    </div><!-- /.content -->
                    <div class="form-group">
                        {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                        <a href="{!! route('admin.products.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                    </div>

                    </form>
                </div>
            </div>
        </div>


        <div style="min-height:200px" class="tab-pane" id="tab_4">
            <div class="pull-right">
                <a class="btn btn-xs btn-success" href="/admin/products/{{$course->id}}/trans/excel/csv">Download Excel</a><br>
            </div>

            <br>
            <table class="table table-bordered">
                <thead>
                    <tr class="bg-danger">
                        <th class="text-center">Transaction Type</th>
                        <th class="text-center">Invoice #</th>
                        <th class="text-center">User</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Quantity In</th>
                        <th class="text-center">Quantity Out</th>
                        <th class="text-center">On Hand(closing)</th>
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
                        <td align="center">

                            @if($result->trans_type == PURCHINVOICE)
                            Purchase
                            @elseif($result->trans_type == SALESINVOICE)
                            Sale
                            @elseif($result->trans_type == STOCKMOVEIN)
                            Transfer
                            @elseif($result->trans_type == STOCKMOVEOUT)
                            Transfer
                            @elseif($result->trans_type == 102)
                            Opening Stock
                            @endif

                        </td>
                        <td align="center">
                            <a href="#" target="_blank">  </a></td>
                        <td align="center">{{$result->user->username}}</td>
                        <td align="center" style="white-space: nowrap;">{{$result->tran_date}}<br>
                            {{ TaskHelper::getNepaliDate($result->tran_date)   }}
                        </td>
                        <td align="center">{{$result->location_name}}</td>
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
                            @if($result->qty <0 ) {{ str_ireplace('-','',$result->qty) }} <?php
                          $StockOut +=$result->qty;
                          ?> @else - @endif </td>
                        <td align="center">{{$sum += $result->qty}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" align="right">Total</td>
                        <td align="center">{{$StockIn}}</td>
                        <td align="center">{{str_ireplace('-','',$StockOut)}}</td>
                        <td align="center">{{$StockIn+$StockOut}}</td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="6" class="text-center text-danger">No Transaction Yet</td>
                    </tr>
                    @endif

                </tbody>
            </table>

        </div>

        <!-- /.tab-pane status -->
        <div class="tab-pane " id="tab_5">
            <div class="row">
                <div class="col-md-6">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-gray">
                                    <th>Location</th>
                                    <th>Available(Qty)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($locData))
                                <?php
                                  $sum = 0;
                                ?>
                                @foreach ($locData as $data)
                                
                                <tr>
                                    <td>{{$data->name}}</td>
                                    <td>{{\TaskHelper::getItemQtyByLocationName($data->id,$course->id)}}</td>
                                </tr>
                                <?php
                                  $sum += \TaskHelper::getItemQtyByLocationName($data->id,$course->id);
                                ?>
                                @endforeach
                                <tr>
                                    <td>Other Locations</td>
                                    <td>{{\TaskHelper::getItemQtyByLocationName('',$course->id)}}</td>
                                </tr>
                                <?php
                                  $sum += \TaskHelper::getItemQtyByLocationName('',$course->id);
                                ?>
                                @endif
                                <tr>
                                    <td align="right">Total:</td>
                                    <td>{{$sum}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2()
    })
    $(function() {
        $('#outlet_id').on('change', function() {
            if ($(this).val() != '') {
                $.ajax({
                    url: "/admin/outlet/ajax/getMenu"
                    , data: {
                        outlet_id: $(this).val()
                    }
                    , dataType: "json"
                    , success: function(data) {
                        var result = data.data;
                        $('#menu_id').html(result);
                    }
                });
            }
        });
    });

</script>
@endsection
