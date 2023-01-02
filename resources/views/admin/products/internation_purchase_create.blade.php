@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>Update International logistics on Purchase 
        <small>{!! $product_name ?? "Product name not available" !!} </small>
    </h1>


{{--       <?php
echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG('4', 'C39+',3,33) . '" alt="barcode"   />';

 ?> --}}


</section>

<div class="nav-tabs-custom" id="tabs">

    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="row">
                <div class="col-md-6">

                   {!! Form::model( $id, ['route' => ['admin.products.int_purch_update', $id], 'method' => 'PUT', 'id' => 'form_edit_course', 'enctype' => 'multipart/form-data'] ) !!}

                       <div class="content">

                            <div class="form-group">
                                {!! Form::label('excise_charge_percentage_per_unit', 'Excise Charge  Per Unit') !!}
                                {!! Form::text('excise_charge_percentage_per_unit', $int_purch->excise_charge_percentage_per_unit ?? "", ['class' => 'form-control']) !!}
                            </div>

                               <div class="form-group">
                                {!! Form::label('agent_commission_per_unit', 'Agent Commission Per Unit') !!}
                                {!! Form::text('agent_commission_per_unit', $int_purch->agent_commission_per_unit ?? "", ['class' => 'form-control']) !!}
                            </div>

                               <div class="form-group">
                                {!! Form::label('insurence_charge_per_unit', 'Insurance Per Unit') !!}
                                {!! Form::text('insurence_charge_per_unit', $int_purch->insurence_charge_per_unit ?? "", ['class' => 'form-control']) !!}
                            </div>

                        </div><!-- /.content -->
                </div>

                <div class="col-md-6">

                    <div class="content">
                        <div class="form-group">
                                    {!! Form::label('bank_commission_percentage_per_unit', 'Bank Commission  Per Unit') !!}
                                    {!! Form::number('bank_commission_percentage_per_unit',  $int_purch->bank_commission_percentage_per_unit ?? "", ['class' => 'form-control']) !!}
                        </div>
                         <div class="form-group">
                                    {!! Form::label('transportation_charge_per_unit', 'Transportation Charge Per Unit') !!}
                                    {!! Form::number('transportation_charge_per_unit',  $int_purch->transportation_charge_per_unit ?? "", ['class' => 'form-control']) !!}
                        </div>
                         <div class="form-group">
                                    {!! Form::label('warehouse_charge_per_unit', 'Ware House Charge Per Unit') !!}
                                    {!! Form::number('warehouse_charge_per_unit',  $int_purch->warehouse_charge_per_unit ?? "", ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                           {!! Form::button( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit','type'=>'Submit'] ) !!}
                           <a href="{!! route('admin.products.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
           </div>

                   {!! Form::close() !!}
            
        </div>

        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<script type="text/javascript">
    $('.searchable').select2();
</script>
@endsection
