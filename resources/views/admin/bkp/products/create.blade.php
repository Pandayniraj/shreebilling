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
<div class="nav-tabs-custom" id="tabs">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">General Product Item Settings</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="row">
                <div class="col-md-6">

                    {!! Form::open( ['route' => 'admin.products.store', 'id' => 'form_edit_course','enctype'=>'multipart/form-data'] ) !!}

                    <div class="content">

                        <div class="form-group">
                            {!! Form::label('name', trans('admin/courses/general.columns.name')) !!}
                            {!! Form::text('name', null, ['class' => 'form-control','required'=>'required', $readonly]) !!}
                        </div>

                        <div class="form-group col-md-6">
                            {!! Form::label('ordernum',  'Sequence No') !!}
                            {!! Form::text('ordernum', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group col-md-6">
                            {!! Form::label('cost', 'Purchase Pricing') !!}
                            {!! Form::text('cost', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group col-md-6">
                            {!! Form::label('price', 'Sales Pricing') !!}
                            {!! Form::text('price', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group col-md-6">
                            {!! Form::label('dollar_price', 'Dollar Pricing') !!}
                            {!! Form::text('dollar_price', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group col-md-6">
                            {!! Form::label('alert_qty', 'Alert Quantity') !!}
                            {!! Form::number('alert_qty', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group col-md-6">
                            {!! Form::label('product_code', 'Produc Code') !!}
                            {!! Form::text('product_code', null, ['class' => 'form-control', $readonly]) !!}
                        </div>
                        <div class="form-group col-md-6">
                            <label>Select Parent Product(If Any)</label>
                            {!! Form::select('parent_product_id',[''=>'Please Select']+ $products, null, ['class' => 'form-control label-success select2']) !!}
                        </div>

                         <div class="form-group col-md-6">
                            <label>Select Outlet</label>
                            {!! Form::select('outlet_id', [''=>'Please Select']+$outlets, null, ['class' => 'form-control label-success','id'=>'outlet_id']) !!}
                        </div>


                        <div class="form-group col-md-6">
                            <label>Select Product Unit <a href="#" data-target="#modal_dialog_unit"  data-toggle="modal" >[+]</a></label>
                            {!! Form::select('product_unit', $product_unit, null, ['class' => 'form-control label-primary','id'=>'product-unit']) !!}
                        </div>





                        <div class="form-group col-md-6">
                            <label>Select Category <a href="#" data-target="#modal_dialog_category"  data-toggle="modal" >[+]</a></label>
                            {!! Form::select('category_id', $categories, null, ['class' => 'form-control label-primary','id'=>'product-category']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('product_image', 'Product Image') !!}
                            <div class="col-sm-6">
                                <input type="file" name="product_image">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="checkbox">
                                <label>
                                    {!! '<input type="hidden" name="enabled" value="0">' !!}
                                    {!! Form::checkbox('enabled', '1','checked') !!} {{ trans('general.status.enabled') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="checkbox">
                                <label>
                                    {!! '<input type="hidden" name="is_raw_material" value="0">' !!}
                                    {!! Form::checkbox('is_raw_material', '1', $course->is_raw_material) !!}
                                    Is Raw Material
                                </label>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="checkbox">
                                <label>
                                    {!! '<input type="hidden" name="is_recipeable" value="0">' !!}
                                    {!! Form::checkbox('is_recipeable', '1', $course->is_recipeable) !!}
                                    Is Recipeable
                                </label>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="checkbox">
                                <label>
                                    {!! '<input type="hidden" name="is_expirable" value="0">' !!}
                                    {!! Form::checkbox('is_expirable', '1', $course->is_expirable) !!}
                                    Is Expirable
                                </label>
                            </div>
                        </div>



                        <div class="form-group col-md-6">
                            <label>Select Product Type Master</label>
                            {!! Form::select('product_type_id', $product_type_masters, null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group col-md-6">
                            <label>Store</label>
                            {!! Form::select('store_id', $stores, null, ['class' => 'form-control']) !!}
                        </div>

                    </div><!-- /.content -->
                    <br>

                    <div class="form-group">

                        {!! Form::button( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit','type'=>'Submit'] ) !!}

                        <a href="{!! route('admin.products.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>

                    </div>

                    {!! Form::close() !!}
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
