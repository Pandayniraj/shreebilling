@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection
@php  
    $readonly = isset($readonly) ? $readonly : null; 
@endphp
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>Create Product or Service

        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    

 
    
</section>

<div class="nav-tabs-custom" id="tabs">
    
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="row">
                <div class="col-md-6">
                    
                   {!! Form::open( ['route' => 'admin.products.store', 'id' => 'form_edit_course', 'enctype' => 'multipart/form-data'] ) !!} 
                   
                       <div class="content">
                            <div class="form-group">
                            <label>Product Type &nbsp;</label><br>
                            <label class="radio-inline">
                                <input type="radio" value="trading" checked="" name="type">Trading
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="service" name="type" >Service
                            </label>
                                               
                            </div>

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
                                

                                  <div class="input-group">
                                    <span class="input-group-addon">{{ env('APP_CURRENCY') }}</span>
                                      {!! Form::text('cost', null, ['class' => 'form-control', $readonly]) !!}
                                   
                                  </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('price', 'Sales Pricing') !!}
                                  <div class="input-group">
                                    <span class="input-group-addon">{{ env('APP_CURRENCY') }}</span>
                                        {!! Form::text('price', null, ['class' => 'form-control', $readonly]) !!}
                                   
                                  </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('agent_price', 'Agent Pricing') !!}
                                  <div class="input-group">
                                    <span class="input-group-addon">{{ env('APP_CURRENCY') }}</span>
                                        {!! Form::number('agent_price', null, ['class' => 'form-control', 'step' => 'any']) !!}

                                  </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('alert_qty', 'Alert Quantity') !!}
                                {!! Form::number('alert_qty', null, ['class' => 'form-control', $readonly]) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('warranty', 'Warranty') !!}
                                <div class="input-group">
                                    {!! Form::number('warranty', null, ['class' => 'form-control','min' => 0 ]) !!}
                                    <div class="input-group-addon">
                                        months
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('product_code', 'Product Code UPC') !!}
                                {!! Form::text('product_code', null, ['class' => 'form-control', $readonly]) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('sku', 'Stock Keeping Unit SKU') !!}
                                {!! Form::text('sku', null, ['class' => 'form-control', $readonly]) !!}
                            </div>

                            <div class="form-group">
                                <label>Select Supplier</label>
                                {!! Form::select('supplier_id', $supplier, null, ['class' => 'form-control label-default searchable','placeholder'=>'Select Supplier']) !!}
                            </div> 

                            <div class="form-group">
                                <label>Select Product Unit</label>
                                {!! Form::select('product_unit', $product_unit, null, ['class' => 'form-control label-primary']) !!}
                            </div> 

                            

                            <div class="form-group">
                                <label>Select Category</label>
                                {!! Form::select('category_id', $categories, null, ['class' => 'form-control label-primary']) !!}
                            </div>

                            <div class="form-group">     
                                 {!! Form::label('product_image', 'Product Image') !!} 
                                <div class="col-sm-6">
                                    <input type="file" name="product_image">
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
                        <div id='trading_product_extra'>
                               <div class="form-group">
                                <div class="checkbox"> 
                                    <label>
                                        {!! '<input type="hidden" name="public" value="0">' !!}
                                        {!! Form::checkbox('public', '1', $course->public) !!} Show this product in Public Forms
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="checkbox"> 
                                    <label>
                                        {!! '<input type="hidden" name="never_deminishing" value="0">' !!}
                                        {!! Form::checkbox('never_deminishing', '1', $course->never_deminishing) !!} Never Diminishing
                                    </label>
                                </div>
                            </div>

                             <div class="form-group">
                                <div class="checkbox"> 
                                    <label>
                                        {!! '<input type="hidden" name="assembled_product" value="0">' !!}
                                        {!! Form::checkbox('assembled_product', '1', $course->assembled_product) !!} Assembled Product
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="checkbox"> 
                                    <label>
                                        {!! '<input type="hidden" name="component_product" value="0">' !!}
                                        {!! Form::checkbox('component_product', '1', $course->component_product) !!} Component Product
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="checkbox"> 
                                    <label>
                                        {!! '<input type="hidden" name="is_fixed_assets" value="0">' !!}
                                        {!! Form::checkbox('is_fixed_assets', '1', $course->is_fixed_assets) !!} Fixed Assets
                                    </label>
                                </div>
                            </div>
                        </div>
                         
                            

                        </div><!-- /.content -->

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
{{--      <?php 
echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG('4', 'C39+',3,33) . '" alt="barcode"   />';

 ?>
     --}}
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<script type="text/javascript">
    $('.searchable').select2();


    $('input[name=type]').change(function(){

        let type = $(this).val();
        if(type == 'service')
            $('#trading_product_extra').hide();
        else
            $('#trading_product_extra').show();





    });
</script>
@endsection