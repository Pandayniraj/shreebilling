@extends('layouts.master')
@php
$readonly = isset($readonly) ? $readonly: null
@endphp
@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection
<?php
function CategoryTree($parent_id=null,$sub_mark='',$group_id,$ledger_id){

    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)->where('id',$group_id)->get();

    if(count($groups)>0){
        foreach($groups as $group){
            echo '<option value="'.$group->id.'" disabled><strong>'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</strong></option>';

            $ledgers= \App\Models\COALedgers::with('group:id,name')->orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)->where('group_id',$group->id)
                ->get();
            if(count($ledgers)>0){
                $submark= $sub_mark;
                $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                foreach($ledgers as $ledger){

                    if( $ledger_id == $ledger->id){
                        echo '<option selected value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';
//                            echo '<option selected value="'.$ledger->id.'"><strong>'.$sub_mark.$ledger->name.'</strong></option>';
                    }else{

                        echo '<option value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';
                    }

                }
                $sub_mark=$submark;

            }
//            CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$ledger_id);
        }
    }
}

?>
@section('content')
    <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<div class="nav-tabs-custom" id="tabs">
    <ul class="nav nav-tabs">

        <li class="@if(!(session('panel_tab')) && \Request::get('op') !='trans') active @endif">
          <a href="#tab_1" data-toggle="tab" aria-expanded="true">General Setting</a>
        </li>
        <li class="@if(session('panel_tab')=='2') active @endif"><a href="#tab_2" data-toggle="tab" aria-expanded="true">Models</a></li>
        <li class="@if(session('panel_tab')=='3') active @endif"><a href="#tab_3" data-toggle="tab" aria-expanded="true">Serial Numbers</a></li>
        <li class="@if( \Request::get('op') =='trans') active @endif"><a href="#tab_4" data-toggle="tab" aria-expanded="false">Transctions</a></li>
        <li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false">Inventory</a></li>

    </ul>
    <div class="tab-content">
        <div class="tab-pane @if(!(session('panel_tab')) && \Request::get('op') !='trans') active @endif" id="tab_1">
            <div class="row">
                <div class="col-md-6">

                       {!! Form::model( $course, ['route' => ['admin.products.update', $course->id], 'method' => 'PATCH', 'id' => 'form_edit_course', 'enctype' => 'multipart/form-data'] ) !!}
                       <div class="content">
                         <div class="form-group">
                            <label>Product Type &nbsp;</label><br>
                            <label class="radio-inline">
                                <input type="radio" value="trading" checked="" name="type">Trading
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="service" name="type" @if($course->type == 'service') checked="" @endif >Service
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
                            </div></div>

                            <div class="form-group">
                                {!! Form::label('price', 'Sales Pricing') !!}
                                  <div class="input-group">
                                    <span class="input-group-addon">{{ env('APP_CURRENCY') }}</span>
                                 {!! Form::text('price', null, ['class' => 'form-control', $readonly]) !!}
                            </div></div>
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
                                {!! Form::label('product_code', 'Product Code') !!}
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
                                {!! Form::select('product_unit', $product_unit, $course->product_unit, ['class' => 'form-control label-primary']) !!}
                            </div>

                            <div class="form-group">
                                <label>Select Category</label>
                                {!! Form::select('category_id', $categories, $course->category_id, ['class' => 'form-control label-primary']) !!}
                            </div>
{{--                           <div class="form-group">--}}
{{--                               <label>Purchase Ledger</label>--}}
{{--                               <?php--}}
{{--                               $group_id = \FinanceHelper::get_ledger_id('PURCHASE_GROUP_ID');--}}
{{--                               ?>--}}
{{--                               <select class="form-control input-sm ledger_id select2"  name="purchase_ledger_id" aria-hidden="true" required>--}}
{{--                                   <option value="">Select</option>--}}
{{--                                   {{ CategoryTree($parent_id=null,$sub_mark='',$group_id,$course->purchase_ledger_id) }}--}}
{{--                               </select>--}}

{{--                           </div>--}}
{{--                           <div class="form-group">--}}
{{--                               <label>Income/Sales Ledger</label>--}}
{{--                               <?php--}}
{{--                               $group_id = \FinanceHelper::get_ledger_id('DIRECT_INCOME_LEDGER_GROUP');--}}
{{--                               ?>--}}
{{--                               <select class="form-control input-sm ledger_id select2" name="ledger_id" aria-hidden="true" required>--}}
{{--                                   <option value="">Select</option>--}}
{{--                                   {{ CategoryTree($parent_id=null,$sub_mark='',$group_id,$course->ledger_id) }}--}}
{{--                               </select>--}}

{{--                           </div>--}}



{{--                           <div class="form-group">--}}
{{--                               <label>COGS Ledger</label>--}}
{{--                               <?php--}}
{{--                               $group_id = \FinanceHelper::get_ledger_id('COST_OF_GOODS_GROUP');--}}
{{--                               ?>--}}
{{--                               <select class="form-control input-sm ledger_id select2" required name="cogs_ledger_id" aria-hidden="true">--}}
{{--                                   <option value="">Select</option>--}}
{{--                                   {{ CategoryTree($parent_id=null,$sub_mark='',$group_id,$course->cogs_ledger_id) }}--}}
{{--                               </select>--}}

{{--                           </div>--}}




                           <div class="row">
                              <div class="col-md-6">
                               <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">
                                      {!! Form::label('product_image', 'Product Image') !!}
                                    </label>
                                    <div class="col-sm-10">
                                       <input type="file" name="product_image">
                                        @if($course->product_image != '')
                                        <label>Current Logo: </label><br/>
                                        <a href="{{ '/products/'.$course->product_image }}" target="_blank">
                                        <img style=" width:auto;height: 20vh;" src="{{ '/products/'.$course->product_image }}">
                                      </a>
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
                             <div id='trading_product_extra'  @if($course->type == 'service') style="display: none" @endif>
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


        <?php

          $code = $course->id;
          // echo '<img width="150" src="data:image/png;base64,'. \DNS1D::getBarcodePNG($code, "C128") . '" alt="barcode" class="bcimg"/>';

          echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($code, 'C39+',3,33) . '" alt="barcode"   />'
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


      <div style="min-height:200px"  class="tab-pane @if(session('panel_tab')=='2') active @endif" id="tab_2">
        <table class="table table-bordered">
          <thead>
            <tr class="bg-success">
              <th>ID</th>
              <th class="text-left">Model Name</th>
              <th class="text-left">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($product_model as $pm)
            <tr>
             <td>{{$pm->id}}</td>
             <td>{{ucfirst($pm->model_name)}}</td>
             <td><a href="{{route('admin.confirm-delete-prod-model',$pm->id)}}" data-toggle="modal" data-target="#modal_dialog" title="Delete"><i class="fa fa-trash btn-xs btn-danger"></i></a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <button class="btn btn-default btn-xs addmore" style="margin-left: 6px"><i class="fa fa-plus"></i> Add more</button>
        <form method="post" action="{{route('admin.product.model',$course->id)}}">
          {{csrf_field()}}
        <div class="row more-model">
          <div class="col-md-4">
      <table class="table" style="float: left;">
                    <tbody class="moretr1">
                      <tr>
                         <td ><input type="text" name="model[]" class="form-control input-sm" style="width: 80%;float: left" placeholder="Model Name" required=""></td>
                      </tr>
                    <tr class="multipleDiv">
                     </tr>

                    </tbody>
                </table>
                <div class="row">
          <div class="col-md-2" style="margin-top: -10px">
          <button class="btn btn-primary">Update</button>
        </div>
          </div>
        </div>
      </form>
      </div>
    </div>
    <div style="min-height:200px"  class="tab-pane @if(session('panel_tab')=='3') active @endif" id="tab_3">
      <table class="table table-bordered">
          <thead>
            <tr  class="bg-warning">
              <th>ID</th>
              <th>Model</th>
              <th class="text-left">Serial number</th>
              <th class="text-left">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($product_serial_num as $pm)
            <tr>
             <td>{{$pm->id}}</td>
             <td>{{ucfirst($pm->product_model->model_name)}}</td>
             <td>{{ucfirst($pm->serial_num)}}</td>
             <td><a href="{{route('admin.confirm-delete-prod-serial_num',$pm->id)}}" data-toggle="modal" data-target="#modal_dialog" title="Delete"><i class="fa fa-trash btn-xs btn-danger"></i></a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <button class="btn btn-default btn-xs addmore1" style="margin-left: 6px"><i class="fa fa-plus"></i> Add more</button>
        <form method="post" action="{{route('admin.product.serial_num',$course->id)}}">
          {{csrf_field()}}
        <div class="row more-model">
          <div class="col-md-8">
      <table class="table" style="float: left;">
                    <tbody class="moretr1">
                      <tr>
                        <td > <select id="model_no" class="form-control lead_id select2 searchable" name="model_id[]" required="">
                        <option class="input input-lg" value="">Select Model</option>

                        @if(isset($product_model))
                            @foreach($product_model as $key => $v)
                                <option value="{{ $v->id }}">{{ ucfirst($v->model_name) }}</option>
                            @endforeach
                        @endif
                        </select></td>
                         <td ><input type="text" name="serial_num[]" class="form-control input-sm" style="width: 80%;float: left" placeholder="Serial number" required=""></td>
                      </tr>
                    <tr class="multipleDiv1">
                     </tr>

                    </tbody>
                </table>
                <div class="row">
          <div class="col-md-2" style="margin-top: -10px">
          <button class="btn btn-primary">Update</button>
        </div>
          </div>
        </div>
      </form>
      </div>
     </div>


        <div style="min-height:200px" class="tab-pane @if( \Request::get('op') =='trans') active @endif" id="tab_4">


          @foreach($transations as $key=>$transType)

            @if($key == PURCHINVOICE || $key == PURCHORDER)
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr><th colspan="5">
                  @if($key == PURCHINVOICE) Purchase Invoice @elseif($key == PURCHORDER) Purchase Order @endif
                </th>  </tr>
                <tr class="bg-danger">
                    <th class="text-center">Date</th>
                    <th class="text-center">Supplier</th>
                    <th class="text-center">Location</th>
                    <th class="text-center">Quantity In</th>
                    <th class="text-center">Quantity On Hand</th>
                    <th class="text-center">Rate</th>
                    <th class="text-center">Value</th>

                </tr>
              </thead>
              <tbody>
                @php $total = 0; $totalRate = 0;$sum=0;  @endphp
                @foreach($transType as $result)
                @php
                  $getPurch = $result->get_purchase;
                  if($getPurch){

                  $proDetails = $getPurch->product_details->where('product_id',$course->id)->first();
                  }
                @endphp
                <tr>
                    <td align="center">{{$result->tran_date}}</td>
                    <td align="center" style="font-size: 16.5px;">{{ $getPurch->client->name }}</td>
                    <td align="center">{{$result->location_name}}</td>
                    <td align="center">{{$result->qty}}</td>
                    <td align="center">{{ $sum += $result->qty }}</td>
                    <td align="center">{{ $proDetails->unit_price }}</td>
                    <td align="center">{{ $proDetails->unit_price * abs($result->qty) }}</td>
                </tr>
                @php $total +=  ( $proDetails->unit_price * abs($result->qty) );
                    $totalRate += $proDetails->unit_price;
                @endphp
                @endforeach
              </tbody>
              <tfoot>
                <th colspan="5" class="text-right">Total</th>

                <th class="text-center"> {{ $totalRate }} </th>
                <th class="text-center" > {{ $total }} </th>
              </tfoot>
            </table>
            @elseif($key == SALESINVOICE || $key ==  SALESORDER)
             <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr><th colspan="5">@if($key == SALESINVOICE) Sales Invoice @elseif($key == SALESORDER) Sales Order @endif </th>  </tr>
                <tr class="bg-primary">
                    <th class="text-center">Date</th>
                    <th colspan="text-center">Customer</th>
                    <th class="text-center">Location</th>
                    <th class="text-center">Quantity out</th>
                    <th class="text-center">Quantity On Hand</th>
                    <th  class="text-center">Rate</th>
                    <th class="text-center">Value</th>
                </tr>
              </thead>
              <tbody>
                @php $total = 0; $totalRate = 0;$sum = 0;  @endphp
                @foreach($transType as $result)
                @php
                  $getSales = $result->get_sales;
                  if($getSales){
                     $proDetails = $getSales->product_details->where('product_id',$course->id)->first();
                  }

                @endphp
                <tr>
                   <td align="center">{{$result->tran_date}}</td>
                    <td align="center" style="font-size: 16.5px;"> {{ $getSales->client->name }} </td>
                      <td align="center">{{$result->location_name}}</td>
                      <td align="center">{{$result->qty}}</td>
                      <td align="center">{{$sum += $result->qty}}</td>
                      <td align="center">{{ $proDetails->unit_price }}</td>
                      <td align="center">{{$proDetails->unit_price * abs($result->qty) }}</td>
                </tr>
                @php $total +=  ( $proDetails->unit_price * abs($result->qty) );
                    $totalRate += $proDetails->unit_price;
                @endphp
                @endforeach
              </tbody>
               <tfoot>
                <th colspan="5" class="text-right">Total</th>

                <th class="text-center"> {{ $totalRate }} </th>
                <th class="text-center" > {{ $total }} </th>
              </tfoot>
            </table>





            @endif

          @endforeach

{{--
            <table class="table table-bordered">
                <thead>
                    <tr class="bg-danger">
                        <th class="text-center">Transaction Type</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Quantity In</th>
                        <th class="text-center">Quantity Out</th>
                        <th class="text-center">Quantity On Hand</th>
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
                        @endif

                      </td>
                      <td align="center">{{$result->tran_date}}</td>
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
                    </tr>
                    @endforeach
                     <tr><td colspan="3" align="right">Total</td><td align="center">{{$StockIn}}</td><td align="center">{{str_ireplace('-','',$StockOut)}}</td><td align="center">{{$StockIn+$StockOut}}</td></tr>
                    @else
                    <tr>
                        <td colspan="6" class="text-center text-danger">No Transaction Yet</td>
                    </tr>
                   @endif

                </tbody>
            </table> --}}

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
                                  <td>{{$data->location_name}}</td>
                                  <td>{{\TaskHelper::getItemQtyByLocationName($data->id,$course->id)}}</td>
                                </tr>
                                <?php
                                  $sum += \TaskHelper::getItemQtyByLocationName($data->id,$course->id);
                                ?>
                               @endforeach

                               @endif
                                  <tr><td align="right">Total:</td><td>{{$sum}}</td></tr>
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
<div class="more-tr" style="display: none;">
              <table class="table">
<tbody id="more-custom-tr">
        <tr>
        <td ><input type="text" name="model[]" class="form-control input-sm" style="width: 80%;float: left" placeholder="Model Name"  required="">
        <a href="javascript::void(1);" style="width: 10%; float: right">
        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
        </a></td>
        </tr>
    </tbody>

    <tbody id="more-custom-tr1">
        <tr>
              <td > <select id="model_no" class="form-control lead_id select2 searchable" name="model_id[]" required="">
                        <option class="input input-lg" value="">Select Model</option>

                        @if(isset($product_model))
                            @foreach($product_model as $key => $v)
                                <option value="{{ $v->id }}">{{ ucfirst($v->model_name) }}</option>
                            @endforeach
                        @endif
                        </select></td>
        <td ><input type="text" name="serial_num[]" class="form-control input-sm" style="width: 80%;float: left" placeholder="Serial number" required="">
        <a href="javascript::void(1);" style="width: 10%; float: right">
        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
        </a></td>
        </tr>
    </tbody>
</table>
  </div>
  <script type="text/javascript">
          $('.addmore').click(function(){
            $(".multipleDiv").after($('.more-tr #more-custom-tr').html());
        });
          $('.addmore1').click(function(){
            $(".multipleDiv1").after($('.more-tr #more-custom-tr1').html());
        });
    $(document).on('click', '.remove-this', function () {
        $(this).parent().parent().parent().remove();
        caltotal();
    });
  </script>
  <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<script type="text/javascript">

    $(document).ready(function () {
        $('.select2').select2({

        });
    })
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
