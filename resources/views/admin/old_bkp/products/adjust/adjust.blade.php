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
  <p> Whether a supplier gift us some stock or some stocks are just got broken</p>

  <br/>



  {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>



<div class="box box-header">

   <div class="pull-right">
    <a class="btn btn-sm btn-primary" href="/admin/product/stock_adjustment/create"> + Add Adjustment </a>
</div>

<div class="">

    <div style="min-height:200px" class="" id="">
        <table class="table table-bordered table-striped">
            <thead>
                <tr class="bg-purple">
                  <th class="text-center">Adj Id#</th>
                  <th class="text-center">Date</th>
                  <th class="text-center">Warehouse Location</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Reason</th>
                  <th class="text-center"> Total Adj Qty</th>
                  <th class="text-center"> Total Adj Price Value</th>
                  <th class="text-center">Actions</th>
              </tr>
          </thead>
          <tbody> 
            @php
            $total_amount = 0; 
            @endphp 
            @foreach($stockadjustment as $aj)
            @php
            $total_amount += $aj->total_amount; 
            @endphp
            <tr>
               <td>{{$aj->id}}</td>
               <td>{{date('d M Y',strtotime($aj->date))}}</td>
               <td>{{$aj->productlocation->location_name}}</td>
               <td>{{ucwords($aj->status)}}</td>
               <td>{{$aj->adjustmentreason->name}}</td>
               <?php
               $total_qty = \App\Models\StockAdjustmentDetail::where('adjustment_id',$aj->id)->sum('qty');

               ?>
               <td>{{$total_qty}}</td>
               <td>@money($aj->total_amount)</td>
               <td>
                <a href="/admin/product/stock_adjustment/{{$aj->id}}/edit"><i class="fa fa-edit"></i></a>
                
                <a href="{!! route('admin.products.stock_adjustment.confirm-delete', $aj->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="6" class="text-right">
                <h4><b>Total:</b></h4>
            </td>
            <td colspan="2" class="text-left">
                <h4><b>@money($total_amount)</b></h4>
            </td>
        </tr>

        <h4><i class="fa fa-info-circle text-success"></i> The Total Stock Adjustment Price is <b>@money($total_amount)</b></h4>
        <br>
    </tbody>
</table>

</div>

<!-- /.tab-pane -->
</div>
<!-- /.tab-content -->
</div>
@endsection